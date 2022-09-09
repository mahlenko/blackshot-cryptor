<?php

namespace App\Models\Navigation;

use Anita\Traits\TranslateField;
use App\Models\Finder\File;
use App\Models\Finder\Folder;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasOne;
use Illuminate\Http\UploadedFile;
use Astrotomic\Translatable\Contracts\Translatable as TranslatableContract;
use Astrotomic\Translatable\Translatable;

class NavigationItemParam extends Model implements TranslatableContract
{
    use HasFactory, Translatable, TranslateField;

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
    protected $fillable = ['style', 'css', 'icon', 'iconCss', 'target'];

    /**
     * @var string[]
     */
    public $translatedAttributes = ['title', 'iconAlt'];

    /**
     * @var string[]
     */
    protected $with = ['icon'];

    /**
     * @var string
     */
    protected $keyType = 'string';

    /**
     * @return HasOne
     */
    public function icon(): HasOne
    {
        return $this->hasOne(File::class, 'parent_uuid', 'uuid');
    }

    /**
     * Загрузим фотографию для ссылки
     * @param $value
     */
    public function setIconAttribute($value)
    {
        if (!$value) return;

        /* Удалим предыдущее превью, если было */
        $files = File::where('parent_uuid', $this->uuid)
            ->get();

        if ($files->count()) {
            foreach ($files as $file) {
                $file->delete();
            }
        }

        /* Создание каталогов */
        $module_folder = Folder::createFolder(class_basename(self::class));
        $item_folder = Folder::createFolder($this->uuid, $module_folder->uuid);

        /* Загружаем файл в каталог товара */
        File::upload($value, $item_folder, $this->uuid);

        unset($this->attributes['icon']);
    }

    /**
     *
     */
    protected static function booted()
    {
        static::deleting(function($item) {
            if ($item->icon) $item->delete();
        });
    }
}
