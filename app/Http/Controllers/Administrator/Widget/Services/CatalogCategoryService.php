<?php


namespace App\Http\Controllers\Administrator\Widget\Services;


use App\Http\Controllers\Administrator\Widget\ServiceAbstract;
use App\Models\Catalog\Category;
use App\Models\Catalog\Product;
use App\Models\Widget\Widget;
use DateInterval;
use Illuminate\Support\Facades\Cache;
use Kalnoy\Nestedset\Collection;

class CatalogCategoryService extends ServiceAbstract
{
    /**
     * @return string
     */
    public static function name(): string
    {
        return __('catalog.category.widget.title');
    }

    /**
     * @return string
     */
    public static function templateFolder(): string
    {
        return 'catalog/categories';
    }

    /**
     * @return array[]
     */
    public function options(): array
    {
        $categories = Category::defaultOrder()
            ->withTranslation()
            ->get();

        return [
            [
                'type' => 'dropdown',
                'name' => 'items[]',
                'options' => self::buildCollection($categories),
                'label' => __('catalog.category.widget.columns.items'),
                'args' => [
                    'multiple' => 'multiple',
                    'required' => true
                ],
            ],
            [
                'type' => 'number',
                'name' => 'limit',
                'default' => 0,
                'label' => __('page.widget.columns.limit'),
                'args' => [
                    'required' => true
                ]
            ],
        ];
    }

    /**
     * @param array $parameters
     * @param string|null $widget_uuid
     * @return Collection|null
     */
    public function result(array $parameters, string $widget_uuid = null): ?Collection
    {
        if (!key_exists('items', $parameters) || !$parameters['items']) {
            return null;
        }

        return Cache::remember(
            'widget.catalog.'. $widget_uuid .'.'. app()->getLocale(),
            new DateInterval('PT10M'),
            function() use ($parameters) {
                return Product::select('products.*')
                    ->where('publish_at', '<=', date('Y-m-d H:i:s'))
                    ->with(['images', 'meta'])
                    ->withTranslation()
                    ->join('product_categories', 'products.uuid', '=', 'product_categories.product_uuid')
                    ->whereIn('product_categories.category_uuid', collect($parameters['items']))
                    ->limit($parameters['limit'] ?? 5)
                    ->get();
            },
        );
    }

    /**
     * Сброс кеша виджета по элементу внутри
     * @param string $object_uuid
     */
    public static function clearCacheByObjectUuid(string $object_uuid)
    {
        $widgets = Widget::where(['type' => '\\'.self::class]);
        if (!$widgets->count()) {
            return;
        }

        foreach ($widgets->get() as $widget) {
            if (in_array($object_uuid, $widget->parameters->items)) {
                /* пройдем по всем языковым версиям виджета */
                foreach (config('translatable.locales') as $locale) {
                    $cache_key = 'widget.catalog.' . $widget->uuid. '.' . $locale;
                    if (Cache::has($cache_key)) {
                        /* сброс кеша */
                        Cache::forget($cache_key);
                    }

                    /* сброс кеша view */
                    Cache::forget($cache_key . '_view');
                }
            }
        }
    }
}
