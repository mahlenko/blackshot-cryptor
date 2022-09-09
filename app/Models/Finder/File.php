<?php

namespace App\Models\Finder;

use Anita\Traits\TranslateField;
use App\Repositories\WebpRepository;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;
use Intervention\Image\Facades\Image;
use Kalnoy\Nestedset\NodeTrait;
use Ramsey\Uuid\Uuid;
use Astrotomic\Translatable\Contracts\Translatable as TranslatableContract;
use Astrotomic\Translatable\Translatable;

/**
 * @param Folder $folder
 * @package App\Models\Finder
 */
class File extends Model implements TranslatableContract
{
    use HasFactory, NodeTrait, Translatable, TranslateField;

    /**
     * @var string
     */
    protected $primaryKey = 'uuid';

    /**
     * @var bool
     */
    public $incrementing = false;

    /**
     * @var string[]
     */
    public $translatedAttributes = ['alt', 'title', 'description'];

    /**
     * @var array
     */
    protected $with = ['translations'];

    /**
     * @var string
     */
    protected $keyType = 'string';

    /**
     * @return bool
     */
    public function isImage(): bool
    {
        return Str::startsWith($this->mimeType, 'image');
    }

    /**
     * @return string
     */
    public function onlyName(): string
    {
        return str_replace('.' . $this->extension(), '', $this->name);
    }

    /**
     * @return string
     */
    public function extension(): string
    {
        return substr($this->filename, strrpos($this->filename, '.') + 1);
    }

    /**
     * @return BelongsTo
     */
    public function folder(): BelongsTo
    {
        return $this->belongsTo(Folder::class, 'folder_uuid', 'uuid');
    }

    /**
     * @param string $value
     */
    public function setFolderUuidAttribute(string $value)
    {
        $this->attributes['folder_uuid'] = $value;

        if (!empty($value)) {
            $folder = Folder::where(['uuid' => $value])->first();
            $this->attributes['folder_path'] = $folder->path();
        }
    }

    /**
     * Получит путь к каталогу для сохранения в объекте,
     * чтобы не дергать N раз бд
     */
    public function generatePath()
    {
        $this->folder_path = $this->folder->path();
    }

    /**
     * @return string
     */
    public function thumbnail(): ?string
    {
        if (! $this->isImage()) return null;
        return $this->folder_path . 'thumbnails' . DIRECTORY_SEPARATOR . $this->filename;
    }

    /**
     * @return string
     */
    public function fullName(): string
    {
        return $this->folder_path . $this->filename;
    }

    /**
     * @param bool $fullname
     * @return string
     */
    public function webp(bool $fullname = true): string
    {
        $file = $fullname ? $this->fullName() : $this->thumbnail();
        $webp = Str::replaceLast($this->extension(), 'webp', $file);

        if (Storage::exists($webp)) {
            return $webp;
        }

        return '';
    }

    /**
     * Размеры изображения
     * @return array
     */
    public function getImageSize(): array
    {
        if (Storage::exists(Storage::path($this->fullName()))) {
            list($width, $height) = getimagesize(Storage::path($this->fullName()));
            return [$width, $height];
        }

        return [0, 0];
    }

    /**
     * @return int
     */
    public function kilobytes(): int
    {
        return intval($this->size / 1024);
    }

    /**
     * @return string
     */
    public function sizeText(): string
    {
        if ($this->size < 1024) {
            return $this->size .' '. trans_choice('file.size_choice.bytes', $this->size);
        }

        return $this->kilobytes() > 1000
            ? intval($this->kilobytes() / 1024) .' '. __('file.size_choice.megabytes')
            : $this->kilobytes() .' '. __('file.size_choice.kilobytes');
    }

    /**
     * @param int $start
     * @param int $end
     * @return string
     */
    public function shortName(int $start = 8, int $end = 3): string
    {
        $postfix = $this->extension();
        $max_len = $start + $end + mb_strlen($this->extension()) + 2;

        $name = str_replace('.'.$this->extension(), '', $this->name);
        $result_name = $name .' ';

        if (mb_strlen($name) > $max_len) {
            $result_name = substr($name, 0, $start). '…';
            $result_name .= substr($name, $end * -1);
        }

        return $result_name .'.'. $postfix;
    }

    /**
     * @param UploadedFile $uploadedFile
     * @param Folder $folder
     * @param string|null $parent_uuid
     * @return File
     */
    public static function upload(UploadedFile $uploadedFile, Folder $folder, string $parent_uuid = null): File
    {
        $file = new self();
        $file->uuid = Uuid::uuid4()->toString();
        $file->parent_uuid = $parent_uuid;
        $file->folder_uuid = $folder->uuid;
        $file->name = $uploadedFile->getClientOriginalName();
        $file->filename = Str::lower(Str::random(8)) .'.'. $uploadedFile->extension();
        $file->mimeType = $uploadedFile->getMimeType();
        $file->size = $uploadedFile->getSize();

        $uploadedFile->storePubliclyAs($folder->path(), $file->filename);

        if ($file->isImage()) {
            $imageSize = $file->getImageSize();
            $file->image_x = $imageSize[0];
            $file->image_y = $imageSize[1];
            self::createThumbnail($file);
        }

        $file->save();

        /* webp convert */
        if ($file->isImage() && $uploadedFile->getMimeType() !== 'image/svg+xml') {
            WebpRepository::convert($file);
        }

        return $file;
    }

    /**
     * Создаст превью изображения в тот же каталог + thumbnails
     * @param File $file
     */
    public static function createThumbnail(File $file)
    {
        if ($file->isImage() && $file->extension() !== 'svg')
        {
            $thumbnail_folder = str_replace($file->filename, '', $file->thumbnail());
            Storage::makeDirectory(rtrim($thumbnail_folder, DIRECTORY_SEPARATOR));

            $img = Image::make(Storage::path($file->fullName()));
            $img->resize(
                config('image.preview.width'),
                config('image.preview.height'),
                function ($constraint) {
                    $constraint->aspectRatio();
                    $constraint->upsize();
                })->save(Storage::path($file->thumbnail()));
        }
    }

    /**
     * @return string[]
     */
    protected function getScopeAttributes()
    {
        return ['folder_uuid', 'parent_uuid'];
    }

    /**
     *
     */
    protected static function booting()
    {
        parent::deleting(function($file)
        {
            /* @var File $file */

            /* удалим превью */
            $thumbnail = $file->thumbnail();
            if ($file->isImage() && $thumbnail && Storage::exists($thumbnail)) {
                Storage::delete($thumbnail);

                // webp
                if (Storage::exists($file->webp(false))) {
                    Storage::delete($file->webp(false));
                }
            }

            /* удалим оригинал */
            if (Storage::exists($file->fullName())) {
                Storage::delete($file->fullName());

                // webp
                if ($file->isImage() && Storage::exists($file->webp())) {
                    Storage::delete($file->webp());
                }
            }

            /* если каталог пустой, удалить за собой каталог */
//            $file->folder->delete();
        });
    }

}
