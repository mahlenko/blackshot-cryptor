<?php

namespace App\Models\Catalog;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\HasManyThrough;
use Illuminate\Database\Eloquent\Relations\HasOne;
use Ramsey\Uuid\Uuid;

/**
 * Группы с вариантами товаров
 * @package App\Models\Catalog
 */
class ProductVariationGroup extends Model
{
    use HasFactory;

    /**
     * @var string
     */
    protected $primaryKey = 'uuid';

    /**
     * @var bool
     */
    public $incrementing = false;

    /**
     * @var string
     */
    protected $keyType = 'string';

    /**
     * Добавит товар в группу
     * @param Product $product
     * @return ProductVariationGroupProduct
     */
    public function addProduct(Product $product): ProductVariationGroupProduct
    {
        $product_variant = ProductVariationGroupProduct::where([
            'group_uuid' => $this->uuid,
            'product_uuid' => $product->uuid
        ])->first();

        if (!$product_variant) {
            $product_variant = new ProductVariationGroupProduct();
            $product_variant->uuid = Uuid::uuid4()->toString();
            $product_variant->group_uuid = $this->uuid;
            $product_variant->product_uuid = $product->uuid;
            $product_variant->parent_product_uuid = $this->productParent->product_uuid ?? null;

            $product_variant->save();
        }

        return $product_variant;
    }

    /**
     * Объекты товаров из группы
     * @return HasManyThrough
     */
    public function productObjects(): HasManyThrough
    {
        return $this->hasManyThrough(
            Product::class,
            ProductVariationGroupProduct::class,
            'group_uuid',
            'uuid',
            'uuid',
            'product_uuid',
        );
    }

    /**
     * Добавит характеристику в группу
     * @param Feature $feature
     * @return ProductVariationGroupFeature
     */
    public function addFeature(Feature $feature): ProductVariationGroupFeature
    {
        $feature_group = ProductVariationGroupFeature::where([
            'group_uuid' => $this->uuid,
            'feature_uuid' => $feature->uuid
        ])->first();

        if (!$feature_group) {
            $feature_group = new ProductVariationGroupFeature();
            $feature_group->uuid = Uuid::uuid4()->toString();
            $feature_group->group_uuid = $this->uuid;
            $feature_group->feature_uuid = $feature->uuid;
            $feature_group->purpose = $feature->purpose;

            $feature_group->save();
        }

        return $feature_group;
    }

    /**
     * Объекты характеристик из группы
     * @return HasManyThrough
     */
    public function featureObjects(): HasManyThrough
    {
        return $this->hasManyThrough(
            Feature::class,
            ProductVariationGroupFeature::class,
            'group_uuid',
            'uuid',
            'uuid',
            'feature_uuid',
        );
    }

    /**
     * Товар родитель группы
     * @return HasOne
     */
    public function productParent(): HasOne
    {
        return $this->hasOne(ProductVariationGroupProduct::class, 'group_uuid', 'uuid')
            ->where(['parent_product_uuid' => null]);
    }

    /**
     * Товары из группы
     * @return HasMany
     */
    public function products(): HasMany
    {
        return $this->hasMany(ProductVariationGroupProduct::class, 'group_uuid', 'uuid')
            ->with('object.features');
    }

    /**
     * Характеристики из группы
     * @return HasMany
     */
    public function features(): HasMany
    {
        return $this->hasMany(ProductVariationGroupFeature::class, 'group_uuid', 'uuid')
            ->with(['object', 'object.translation']);
    }

    /**
     * Характеристики из группы
     */
    public function featuresActive()
    {
        return $this->features->filter(function($item) {
            return $item->object->is_active;
        });
    }

}
