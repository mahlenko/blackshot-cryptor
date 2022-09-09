<?php


namespace App\Repositories;


use App\Helpers\Nested;
use App\Models\Catalog\Feature;
use App\Models\Catalog\FeatureCategories;
use App\Models\Catalog\FeatureVariant;
use App\Models\Meta;
use Exception;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;
use Ramsey\Uuid\Uuid;

class FeatureRepository
{
    const CHECKBOX = [
        'value' => 'checkbox',
        'name' => 'catalog.feature.options.checkbox'
    ];

    const CHECKBOX_GROUP = [
        'value' => 'checkbox_group',
        'name' => 'catalog.feature.options.checkbox_group'
    ];

    const SLIDER = [
        'value' => 'slider',
        'name' => 'catalog.feature.options.slider'
    ];

    const COLOR = [
        'value' => 'color',
        'name' => 'catalog.feature.options.color'
    ];

    const TEXT = [
        'value' => 'text',
        'name' => 'catalog.feature.options.text'
    ];

    const DROPDOWN = [
        'value' => 'dropdown', 'name' => 'catalog.feature.options.dropdown'
    ];

    const IMAGE = [
        'value' => 'image',
        'name' => 'catalog.feature.options.image'
    ];

    const LABEL = [
        'value' => 'label',
        'name' => 'catalog.feature.options.label'
    ];

    const DEFAULT = [
        'value' => 'default',
        'name' => 'Default'
    ];

    /* Вид характеристики в товаре */
    const OPTIONS_PRODUCT = [
        'find_product' => [
            self::TEXT,
            self::CHECKBOX,
            self::CHECKBOX_GROUP
        ],
        'group_products' => [
            self::DROPDOWN,
            self::IMAGE,
            self::LABEL
        ],
        'group_variants' => [
            self::DROPDOWN,
            self::IMAGE,
            self::LABEL
        ],
        'organize_catalog' => [
            self::DEFAULT
        ],
        'describe' => [
            self::TEXT
        ],
    ];

    /* Вид характеристики в фильтре */
    const OPTIONS_FILTER = [
        'text' => [
            self::CHECKBOX,
            self::SLIDER,
            self::COLOR
        ],
        'checkbox' => [
            self::CHECKBOX
        ],
        'checkbox_group' => [
            self::CHECKBOX
        ],
        'dropdown' => [
            self::CHECKBOX,
            self::SLIDER,
            self::COLOR
        ],
        'image' => [
            self::CHECKBOX,
            self::COLOR
        ],
        'label' => [
            self::CHECKBOX,
            self::SLIDER,
            self::COLOR
        ],
        'default' => []
    ];

    /**
     * @param string $uuid
     * @param string $locale
     * @param array $data
     * @param array $variants
     * @param array $categories
     * @param array $nested
     * @param array $meta
     * @return Feature
     */
    public function store(
        string $uuid,
        string $locale,
        array $data,
        array $variants,
        array $categories,
        array $nested,
        array $meta
    ): Feature
    {
        $feature = Feature::find($uuid);

        if (!$feature) {
            $feature = new Feature();
            $feature->uuid = $uuid;
        }

        /* Feature data */
        $data['is_active'] = !empty($data['is_active']);
        $data['is_show_feature'] = !empty($data['is_show_feature']);
        $data['is_show_description'] = !empty($data['is_show_description']);
        $feature->setDefaultLocale($locale)->fill($data);

        /* Позиция в каталоге */
        if (!empty($nested['position']) && !empty($nested['parent_id'])) {
            (new Nested(Feature::class))
                ->tree($feature, $nested['position'], $nested['parent_id']);
        }

        $feature->save();

        /* Meta */
        $meta['object_type'] = Feature::class;
        $meta['show_nested'] = false;
        $meta['is_active'] = !empty($meta['is_active']);
        if ($feature->purpose !== 'organize_catalog') {
            $meta['is_active'] = false;
        }
        $feature->meta->setDefaultLocale($locale)->fill($meta)->save();

        /* Добавим варианты к характеристике */
        if ($variants && count($variants)) {
            foreach ($variants as $variant_uuid => $variant_data) {
                $variant = FeatureVariant::find($variant_uuid);

                $variant_data['feature_uuid'] = $feature->uuid;
                (new FeatureVariantRepository())->store(
                    $variant_uuid,
                    $locale,
                    $variant_data,
                    $variant ? $variant->meta->toArray() : null);
            }
        }

        foreach ($categories as $category_uuid => $value) {
            $category = FeatureCategories::where([
                'feature_uuid' => $feature->uuid,
                'category_uuid' => $category_uuid
            ])->first();

            if (!$category) {
                $category = new FeatureCategories();
                $category->feature_uuid = $feature->uuid;
                $category->category_uuid = $category_uuid;
                if (!empty($value)) {
                    $category->save();
                }
            }

            if (empty($value)) {
                $category->delete();
            }
        }

        return $feature;
    }

    /**
     * Вернет характеристики, которые используются для всех категорий.
     * @param string|null $category_uuid
     * @return mixed
     */
    public function findWithoutCategories(string $category_uuid = null)
    {
        $result = Feature::with(['categories'])
            ->leftJoin($category_table = (new FeatureCategories)->getTable(), $category_table.'.feature_uuid', 'uuid')
            ->where(function($builder) use ($category_uuid, $category_table) {
                $builder->where($category_table.'.category_uuid', $category_uuid);
                $builder->orWhere($category_table.'.category_uuid', null);
            });

        $features_uuid = $result->pluck('uuid')
            ->toArray();

        return count($features_uuid)
            ? Feature::whereIn('uuid', $features_uuid)
                ->with(['group', 'values'])
                ->defaultOrder()
                ->get()
            : null;
    }

    /**
     * Цели характеристик
     * @return array
     */
    public function getPurposes(): array
    {
        return [
            'find_product' => [
                'title' => __('catalog.feature.purposes.find_product'),
                'description' => __('catalog.feature.purposes_description.find_product'),
            ],
            'group_products' => [
                'title' => __('catalog.feature.purposes.group_products'),
                'description' => __('catalog.feature.purposes_description.group_products'),
            ],
//            'group_variants' => [
//                'title' => __('catalog.feature.purposes.group_variants'),
//                'description' => __('catalog.feature.purposes_description.group_variants'),
//            ],
            'organize_catalog' => [
                'title' => __('catalog.feature.purposes.organize_catalog'),
                'description' => __('catalog.feature.purposes_description.organize_catalog'),
            ],
            'describe' => [
                'title' => __('catalog.feature.purposes.describe'),
                'description' => __('catalog.feature.purposes_description.describe'),
            ],
        ];
    }

}
