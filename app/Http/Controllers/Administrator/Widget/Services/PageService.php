<?php


namespace App\Http\Controllers\Administrator\Widget\Services;


use App\Helpers\Nested;
use App\Http\Controllers\Administrator\Widget\ServiceAbstract;
use App\Models\Page\Page;
use App\Models\Widget\Widget;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\Cache;

/**
 * Class PageService
 * @package App\Http\Controllers\Administrator\Widget\Services
 */
class PageService extends ServiceAbstract
{
    /**
     * @return string
     */
    public static function name(): string
    {
        return __('page.widget.title.page');
    }

    /**
     * @return string
     */
    public static function templateFolder(): string
    {
        return 'pages';
    }

    /**
     * @return array
     */
    public function options(): array
    {
        $categories = Page::defaultOrder()->withTranslation()->get();

        return [
            [
                'type' => 'dropdown',
                'name' => 'items[]',
                'options' => self::buildCollection($categories),
                'label' => __('page.widget.columns.pages'),
                'args' => [
                    'multiple' => 'multiple',
                    'required' => true
                ],
            ]
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

        return Cache::rememberForever(
            'widget.' . $widget_uuid. '.' . app()->getLocale(),
            function () use ($parameters) {
                return Page::whereIn('uuid', $parameters['items'])
                    ->with(['meta', 'images'])
                    ->get()->sortBy('_lft');
            }
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
                    $cache_key = 'widget.' . $widget->uuid. '.' . $locale;
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
