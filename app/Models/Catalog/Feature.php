<?php

namespace App\Models\Catalog;

use Anita\Traits\Meta;
use Anita\Traits\TranslateField;
use Astrotomic\Translatable\Contracts\Translatable as TranslatableContract;
use Astrotomic\Translatable\Translatable;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\HasManyThrough;
use Kalnoy\Nestedset\NodeTrait;

/**
 * @param $uuid
 * @param $feature_group_uuid
 * @param $slug
 * @param $locale
 * @param $name
 * @param $description
 * @param $purpose
 * @param $view_product
 * @param $view_filter
 * @param $is_active
 * @param $is_show_feature
 * @param $is_show_description
 * @param $prefix
 * @param $postfix
 * @package App\Models\Catalog
 */
class Feature extends Model implements TranslatableContract
{
    use HasFactory, Translatable, TranslateField, NodeTrait, Meta;

    /**
     * Префикс ссылок
     */
    public const PREFIX = 'catalog/features';

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
    public $fillable = ['feature_group_uuid', 'purpose', 'view_product', 'view_filter', 'is_active', 'is_show_feature', 'is_show_description'];

    /**
     * @var string[]
     */
    public $translatedAttributes = ['name', 'description', 'prefix', 'postfix'];

    /**
     * @var string
     */
    protected $keyType = 'string';

    /**
     * Значения характеристики
     * @return HasMany
     */
    public function values(): HasMany
    {
        return $this->hasMany(FeatureVariant::class, 'feature_uuid', 'uuid')
            ->withTranslation()
            ->defaultOrder();
    }

    /**
     * Группа характеристик
     * @return BelongsTo
     */
    public function group(): BelongsTo
    {
        return $this->belongsTo(FeatureGroup::class, 'feature_group_uuid', 'uuid');
    }

    /**
     * Категории в которых находится характеристика
     * @return HasManyThrough
     */
    public function categories(): HasManyThrough
    {
        return $this->hasManyThrough(
            Category::class,
            FeatureCategories::class,
            'feature_uuid',
            'uuid',
            'uuid',
            'category_uuid'
        );
    }

    /**
     * Товары которые используют характеристику
     * @return HasManyThrough
     */
    public function products(): HasManyThrough
    {
        return $this->hasManyThrough(
            Product::class,
            ProductFeature::class,
            'feature_uuid',
            'uuid',
            'uuid',
            'product_uuid'
        );
    }

    /**
     * @return string
     */
    protected static function moduleName(): string
    {
        return __('catalog.feature.navigation_title');
    }

    /**
     *
     */
    protected static function booted()
    {
        self::deleting(function($item) {
            /* @var self $item */
            if ($item->meta) $item->meta->delete();

            if ($item->values && $item->values->count()) {
                foreach ($item->values as $value) $value->delete();
            }

        });
    }
}
