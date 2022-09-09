<?php

namespace App\Models\Catalog;

use Anita\Traits\TranslateField;
use App\Models\Finder\File;
use App\Models\Finder\Folder;
use App\Models\Meta;
use Exception;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Astrotomic\Translatable\Contracts\Translatable as TranslatableContract;
use Astrotomic\Translatable\Translatable;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasOne;
use Illuminate\Http\UploadedFile;
use Kalnoy\Nestedset\NodeTrait;

/**
 * @param string $uuid
 * @param string $feature_uuid
 * @param string $slug
 * @param string $color
 * @package App\Models\Catalog
 */
class FeatureVariant extends Model implements TranslatableContract
{
    use HasFactory, Translatable, TranslateField, NodeTrait, \Anita\Traits\Meta;

    /**
     * Префикс ссылок
     */
    public const PREFIX = 'catalog/feature';

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
    public $fillable = ['color', 'url'];

    /**
     * @var array
     */
    public $translatedAttributes = ['name', 'description', 'body'];

    /**
     * @var string
     */
    protected $keyType = 'string';

    /**
     * @return BelongsTo
     */
    public function feature(): BelongsTo
    {
        return $this->belongsTo(Feature::class, 'feature_uuid', 'uuid');
    }

    /**
     * @return BelongsTo
     */
    public function products(): BelongsTo
    {
        return $this->belongsTo(ProductFeature::class, 'feature_variant_uuid', 'uuid');
    }

    /**
     * @return HasOne
     */
    public function icon(): HasOne
    {
        return $this->hasOne(File::class, 'parent_uuid', 'uuid');
    }

    /**
     * Установит и обновит иконку значения
     * @param UploadedFile|null $uploadedFile
     * @throws Exception
     */
    public function setIcon(UploadedFile $uploadedFile = null): void
    {
        if (!$uploadedFile) return;

        /* Удалим предыдущее превью, если было */
        if ($this->icon) $this->icon->delete();

        /* Создание каталогов */
        $module_folder = Folder::createFolder(class_basename(self::class));
        $item_folder = Folder::createFolder($this->name ?? $this->uuid, $module_folder->uuid);

        /* Загружаем файл в каталог товара */
        File::upload($uploadedFile, $item_folder, $this->uuid);
    }

    /**
     * @return string
     */
    protected static function moduleName(): string
    {
        return __('catalog.feature.variant.navigation_title');
    }

    /**
     *
     */
    protected static function booted()
    {
        self::deleting(function($item) {
            if ($item->meta) $item->meta->delete();
            if ($item->icon) $item->icon->delete();
            if ($item->products) $item->products()->delete();
        });
    }
}
