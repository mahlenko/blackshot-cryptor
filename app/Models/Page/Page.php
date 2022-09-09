<?php

namespace App\Models\Page;

use Anita\Traits\TranslateField;
use App\Models\Finder\File;
use App\Models\Finder\Folder;
use App\Models\Meta;
use App\Models\Navigation\NavigationItem;
use App\Models\Video;
use App\Repositories\SettingRepository;
use Astrotomic\Translatable\Contracts\Translatable as TranslatableContract;
use Astrotomic\Translatable\Translatable;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\HasOne;
use Illuminate\Support\Facades\Storage;
use Illuminate\Testing\Fluent\Concerns\Has;
use Kalnoy\Nestedset\NodeTrait;

/**
 * @property string $uuid
 * @property string $lang
 * @property string $name
 * @property string $slug
 * @property bool $is_homepage
 * @property string $description
 * @property string $body
 * @property string $publish_at
 * @property string $views
 * @property string $template
 * @property File $preview
 *
 * @package App\Models
 */
class Page extends Model implements TranslatableContract
{
    use HasFactory, NodeTrait, Translatable, \Anita\Traits\Meta, TranslateField;

    /**
     * Префикс ссылок и шаблонов
     */
    public const PREFIX = null;

    /**
     * @var string
     */
    protected $primaryKey = 'uuid';

    /**
     * @var bool
     */
    public $incrementing = false;

    /**
     * @var array
     */
    protected $fillable = ['images', 'preview', 'template'];

    /**
     * @var string[]
     */
    public $translatedAttributes = ['name', 'description', 'body'];

    /**
     * @var string
     */
    protected $keyType = 'string';

    /**
     * Превью страницы
     * @return File|null
     */
    public function preview(): ?File
    {
        return $this->images->first();
    }

    /**
     * @return HasMany
     */
    public function images(): HasMany
    {
        return $this->hasMany(File::class, 'parent_uuid', 'uuid')
            ->with(['folder', 'translations'])
            ->defaultOrder();
    }

    /**
     * @return string
     */
    public function lightGalleryJson(): string
    {
        $images = collect();
        foreach ($this->images as $image) {
            $images->add([
                'src' => Storage::url($image->fullName()),
                'thumb' => Storage::url($image->thumbnail()),
                'subHtml' => $image->description,
                'sources' => [
                    [
                        'type' => 'image/webp',
                        'srcset' => Storage::url($image->webp(false)),
                        'media' => '(max-width: 400px)'
                    ],
                    [
                        'type' => 'image/webp',
                        'srcset' => Storage::url($image->webp())
                    ]
                ],
                'title' => $image->title,
                'alt' => $image->alt
            ]);
        }

        return $images->toJson();
    }

    /**
     * @return HasMany
     */
    public function videos(): HasMany
    {
        return $this->hasMany(Video::class, 'parent_uuid', 'uuid')
            ->defaultOrder();
    }

    /**
     * Загрузка файлов
     * @param $value
     */
    public function setPreviewAttribute($value)
    {
        if (!$value) {
            unset($this->attributes['preview']);
            return;
        };

        $folder = Folder::createFolder(Page::class);

        foreach ($value as $file) {
            File::upload($file, $folder, $this->uuid);
        }

        unset($this->attributes['preview']);
    }

    /**
     * @return string
     */
    protected static function moduleName(): string
    {
        return __('page.title');
    }

    /**
     * Удалит зависимости
     */
    protected static function booted()
    {
        static::deleting(function ($page) {
            /* удалить ссылку из меню, если страница использовалась меню */
            foreach (NavigationItem::where(['meta_uuid' => $page->meta->uuid])->get() as $item) {
                $item->delete();
            }

            /* удалим страницу */
            if ($page->meta) $page->meta->delete();
            if ($page->images) {
                foreach($page->images as $image) {
                    $image->delete();
                }
            }

            foreach ($page->descendants as $child) {
                $child->delete();
            }
        });
    }
}
