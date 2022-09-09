<?php

namespace App\Models\Catalog;

use Anita\Traits\TranslateField;
use App\Helpers\Nested;
use App\Models\Finder\File;
use App\Models\Finder\Folder;
use App\Models\Meta;
use App\Models\Navigation\NavigationItem;
use App\Repositories\FeatureRepository;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Astrotomic\Translatable\Contracts\Translatable as TranslatableContract;
use Astrotomic\Translatable\Translatable;
use Illuminate\Database\Eloquent\Relations\HasManyThrough;
use Illuminate\Database\Eloquent\Relations\HasOne;
use Illuminate\Database\Eloquent\Relations\HasOneThrough;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Str;
use Kalnoy\Nestedset\NodeTrait;

/**
 * Категории каталога
 * @package App\Models\Catalog
 */
class Category extends Model implements TranslatableContract
{
    use HasFactory, Translatable, NodeTrait, \Anita\Traits\Meta, TranslateField;

    /**
     * Префикс ссылок
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
    public $fillable = ['name', 'description', 'body', 'template'];

    /**
     * @var string[]
     */
    public $translatedAttributes = ['name', 'description', 'body'];

    /**
     * @var string
     */
    protected $keyType = 'string';

    /**
     * Изображение
     */
    public function icon(): HasOne
    {
        return $this->hasOne(File::class, 'parent_uuid', 'uuid');
    }

    public function preview()
    {
        return $this->icon;
    }

    /**
     * Характеристики
     * @return hasManyThrough
     */
    public function features(): hasManyThrough
    {
        return $this->hasManyThrough(
            Feature::class,
            FeatureCategories::class,
            'category_uuid',
            'uuid',
            'uuid',
            'feature_uuid'
        )->orderBy('features._lft');
    }

    /**
     * Все характеристики категории.
     * Те что назначены прямо и те которые общие
     * @return Collection
     */
    public function allFeatures(): Collection
    {
        return Feature::whereNotIn(
            'uuid',
            FeatureCategories::all()
                ->pluck('feature_uuid')
                ->groupBy('feature_uuid')
        )
            ->with(['group', 'values', 'translation'])
            ->defaultOrder()
            ->get()
            ->merge($this->features);
    }

    /**
     * Товары
     * @return HasManyThrough
     */
    public function products(): HasManyThrough
    {
        return $this->hasManyThrough(
            Product::class,
            ProductCategory::class,
            'category_uuid',
            'uuid',
            'uuid',
            'product_uuid'
        )->orderBy('product_categories._lft');
    }

    /**
     * @return string
     */
    public function getUrl(): string
    {
        $short_uuid = substr($this->uuid, 0, 8);

        return route('catalog.category', [
            'slug' => $this->slug .'-'. $short_uuid . '.html'
        ]);
    }

    /**
     * @return string
     */
    protected static function moduleName(): string
    {
        return __('catalog.category.navigation_title');
    }
}
