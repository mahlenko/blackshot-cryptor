<?php

namespace App\Models\Catalog;

use Anita\Traits\TranslateField;
use App\Models\Finder\File;
use App\Models\Meta;
use App\Models\Navigation\Navigation;
use App\Models\Video;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Astrotomic\Translatable\Contracts\Translatable as TranslatableContract;
use Astrotomic\Translatable\Translatable;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\HasManyThrough;
use Illuminate\Database\Eloquent\Relations\HasOne;
use Illuminate\Database\Eloquent\Relations\HasOneThrough;
use Illuminate\Support\Str;
use Kalnoy\Nestedset\Collection;
use Kalnoy\Nestedset\NodeTrait;

/**
 * Товар
 * @package App\Models\Catalog
 */
class Product extends Model implements TranslatableContract
{
    use HasFactory, Translatable, TranslateField, NodeTrait, \Anita\Traits\Meta;

    /**
     * Префикс ссылок
     */
    public const PREFIX = 'product';

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
    public $fillable = [
        'price', 'product_code', 'quantity', 'weight', 'length', 'width', 'height',
        'min_qty', 'max_qty', 'step_qty', 'age_limit', 'age_verification', 'popular',
        'name', 'body', 'url'];

    /**
     * @var array
     */
    public $translatedAttributes = ['name', 'description', 'body'];

    /**
     * @var string[]
     */
    public $with = ['images', 'meta', 'translation'];

    /**
     * @var string
     */
    protected $keyType = 'string';

    /**
     * Категрии товара
     * @return BelongsToMany
     */
    public function categories(): BelongsToMany
    {
        return $this->belongsToMany(Category::class, ProductCategory::class)
            ->orderByPivot('_lft');
    }

    /**
     * Характеристики
     * @return HasMany
     */
    public function features(): HasMany
    {
        return $this->hasMany(ProductFeature::class, 'product_uuid', 'uuid')
            ->with(['feature', 'feature.translation', 'feature.group', 'variant', 'variant.translation']);
    }

    /**
     * Характеристики товара, типа Бренд или Автор
     */
    public function organizeFeatures()
    {
        return $this->features->filter(function($item) {
            return $item->feature->purpose === 'organize_catalog';
        });
    }

    /**
     * @return HasMany
     */
    public function videos(): HasMany
    {
        return $this->hasMany(Video::class, 'parent_uuid', 'uuid')->defaultOrder();
    }

    /**
     * Изображения
     * @return HasMany
     */
    public function images(): HasMany
    {
        return $this->hasMany(File::class, 'parent_uuid', 'uuid')
            ->where('mimeType', 'like', 'image/%')
            ->defaultOrder();
    }

    /**
     * @return null
     */
    public function preview()
    {
        return $this->images->count() ? $this->images->first() : null;
    }

    /**
     * Группа в которой находится товар
     * @return HasOneThrough
     */
    public function group(): HasOneThrough
    {
        return $this->hasOneThrough(
            ProductVariationGroup::class,
            ProductVariationGroupProduct::class,
            'product_uuid',
            'uuid',
            'uuid',
            'group_uuid'
        );
    }

    /**
     * Характеристики которые доступны для вариантов
     * @return HasManyThrough
     */
    public function variation_features(): HasManyThrough
    {
        return $this->hasManyThrough(
            Feature::class,
            ProductFeature::class,
            'product_uuid',
            'uuid',
            'uuid',
            'feature_uuid'
        )
            ->whereIn('purpose', ['group_products', 'group_variants'])
            ->orderBy('purpose')->defaultOrder();
    }

    /**
     * Похожие товары по характеристикам для вариантов
     * @return Product[]|null
     */
    public function similar(): ?Collection
    {
        /**
         * Проверим что данный товар имеет заполненные характеристики
         * у которых цель указана как варинт товара
         */
        if (!$this->variation_features) return null;

        /**
         * Получими характеристики по которым будем искать товары
         */
        $features = $this->features->whereIn('feature_uuid', $this->variation_features->pluck('uuid'));

        /**
         * Выбор категорий товара
         */
        $product_categories = $this->categories;
        if (!$product_categories) return null;

        /**
         * Поиск товаров с такими же категориями
         */
        $product_by_categories = ProductCategory::whereIn('category_uuid', $product_categories->pluck('uuid'))
            ->where('product_uuid', '<>', $this->uuid)
            ->groupBy('product_uuid')
            ->havingRaw('COUNT(*) = ' . $product_categories->count())
            ->pluck('product_uuid');

        if (!$product_by_categories) return null;

        /**
         * Отфильтруем товары, по количеству доступных характеристик для вариантов
         * Может получится что у текущего товара заполнена 1 характеристика для вариантов,
         * столько же должно быть и у предлагаемых вариантов, для полного соответствия.
         */
        $variants_products = Product::whereIn('uuid', $product_by_categories)->get()->filter(function($product) {
            return $product->variation_features->count() === $this->variation_features->count();
        });

        /**
         * Сравниваем характеристики. Товары должны отличатся значениями
         * характеристик от текущего.
         */
        $products = ProductFeature::select('product_uuid')->whereIn('product_uuid', $variants_products->pluck('uuid'))
            ->where(function ($query) use ($features) {
                foreach ($features as $feature) {
                    $query->orWhere(function($q) use ($feature) {
                        $q->where('feature_uuid', $feature->feature_uuid);
                        $q->where('feature_variant_uuid', '<>', $feature->feature_variant_uuid);
                    });
                }
            })
            ->groupBy('product_uuid')
            ->havingRaw('COUNT(*) > 0');

        return $products->count()
            ? Product::whereIn('uuid', $products->pluck('product_uuid'))->get()
            : null;
    }

    /**
     * @return \Illuminate\Support\Collection
     */
    public function similarWithoutGroup(): ?\Illuminate\Support\Collection
    {
        $exclude_product_uuid = [];
        if ($this->group) {
            $exclude_product_uuid = $this->group->products->pluck('product_uuid')->toArray();
        }

        return $this->similar()
            ? $this->similar()->whereNotIn('uuid', $exclude_product_uuid)
            : null;
    }

    /**
     * Сгенерированные варианты для товара
     * @return \Illuminate\Support\Collection
     */
    public function variants(): \Illuminate\Support\Collection
    {
        /* Товары для отметки комбинаций которые уже в группе */
        $checked_combination = collect([$this]);
        if ($this->group) {
            foreach ($this->group->products as $product) {
                if ($product->product_uuid === $this->uuid) continue;
                $checked_combination->add($product->object);
            }
        }

        /* Варианты характеристик */
        $features_variants = collect();
        foreach ($this->variation_features as $feature) {
            $features_variants->add($feature->values);
        }

        /* Комбинации вариантов */
        $combinations_temp = $this
            ->generation($features_variants, $checked_combination)
            ->groupBy('group_by')->values();

        /* Добавим к группе название */
        $combinations = collect();
        foreach ($combinations_temp as $combination) {
            $name = implode(': ', [
                $combination->first()['features']->first()->feature->name,
                $combination->first()['features']->first()->name
            ]);

            $combinations->add([
                'name' => $name,
                'key_group_uuid' => $combination->first()['features']->first()->uuid,
                'collection' => collect($combination)
            ]);
        }

        return $combinations;
    }

    /**
     * @param null $value
     */
    public function setWeightAttribute($value = null)
    {
        $this->attributes['weight'] = intval($value);
    }

    /**
     * @param null $value
     */
    public function setProductCodeAttribute($value = null)
    {
        $this->attributes['product_code'] = Str::upper($value ?? Str::random(8));
    }

    /**
     * Сгенерирует варианты товаров из характеристик
     * @param $options
     * @param \Illuminate\Support\Collection $has_combinations
     * @return \Illuminate\Support\Collection
     */
    private function generation($options, \Illuminate\Support\Collection $has_combinations): \Illuminate\Support\Collection
    {
        $combinations = [[]];

        for ($count = 0; $count < $options->count(); $count++) {
            $tmp = [];
            foreach ($combinations as $v1) {
                foreach ($options[$count] as $v2) {
                    $tmp[] = array_merge($v1, [$v2]);
                }
            }
            $combinations = $tmp;
        }

        $combinations = array_filter($combinations);

        foreach ($combinations as $index => $combination) {
            $combinations[$index] = collect($combination);
        }

        return $this->createVariantNameAndKeyGroup(collect($combinations), $has_combinations);
    }

    /**
     * Создаст названия для вариантов.
     *
     * @param \Illuminate\Support\Collection $combinations
     * @param \Illuminate\Support\Collection $has_combinations
     * @return \Illuminate\Support\Collection
     */
    private function createVariantNameAndKeyGroup(
        \Illuminate\Support\Collection $combinations,
        \Illuminate\Support\Collection $has_combinations
    ): \Illuminate\Support\Collection
    {
        /* характеристики товаров которые уже есть */
        $variation_features_value = [];
        foreach ($has_combinations as $product) {
            $variation_features_value[] = $product->features
                ->whereIn('feature_uuid', $product->variation_features->pluck('uuid'))
                ->pluck('feature_variant_uuid', 'feature_uuid')->sortKeys()->toArray();
        }

        /* */
        $variants = collect();
        foreach ($combinations as $combination) {
            /* Создаем название комбинации */
            $name = [];
            foreach ($combination as $item) {
                $name[] = $item->feature->name .': '. $item->name;
            }

            /* собираем характеристики комбинации */
            $combination_values = $combination->pluck('uuid', 'feature_uuid')->sortKeys()->toArray();

            /* записываем обновленную комбинацию */
            $variants->add([
                'name' => implode(', ', $name),
                'features' => $combination,
                'checked' => array_search($combination_values, $variation_features_value) !== false,
                'group_by' => $combination->first()->uuid
            ]);
        }

        return $variants;
    }

    public function getUrl(): string
    {
        $chunk_uuid = substr($this->uuid, 0, 8);
        return route('product', ['slug' => $this->slug .'-'. $chunk_uuid .'.html']);
    }

    /**
     * @return string
     */
    protected static function moduleName(): string
    {
        return __('catalog.product.navigation_title');
    }

    /**
     * Удалить зависимости
     */
    protected static function booted()
    {
        parent::deleting(function($product) {
            /* изображения */
            if ($product->images && $product->images->count()) {
                foreach ($product->images as $image) {
                    $image->delete();
                }
            }

            /* мета теги */
            if ($product->meta) {
                $product->meta->delete();
            }
        });

        parent::created(function ($product) {
            /* @todo сбрасывать кеш только у тех меню в которых есть товар */
            foreach (Navigation::all() as $navigation) {
                $navigation->clearCache();
            }
        });

        parent::updated(function ($product) {
            /* @todo сбрасывать кеш только у тех меню в которых есть товар */
            foreach (Navigation::all() as $navigation) {
                $navigation->clearCache();
            }
        });
    }
}
