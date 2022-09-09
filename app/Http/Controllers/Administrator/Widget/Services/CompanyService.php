<?php


namespace App\Http\Controllers\Administrator\Widget\Services;


use App\Helpers\Nested;
use App\Http\Controllers\Administrator\Widget\ServiceAbstract;
use App\Models\Company\Company;
use App\Models\Page\Page;
use App\Models\Widget\Widget;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\Cache;

/**
 * Class PageService
 * @package App\Http\Controllers\Administrator\Widget\Services
 */
class CompanyService extends ServiceAbstract
{
    /**
     * @return string
     */
    public static function name(): string
    {
        return __('company.title');
    }

    /**
     * @return string
     */
    public static function templateFolder(): string
    {
        return 'companies';
    }

    /**
     * @return array
     */
    public function options(): array
    {
        return [
            [
                'type' => 'dropdown',
                'name' => 'item',
                'options' => self::buildCollection(Company::all()),
                'label' => __('company.widget.columns.company'),
                'args' => [
                    'required' => true
                ],
            ]
        ];
    }

    /**
     * @param array $parameters
     * @param string|null $widget_uuid
     * @return null
     */
    public function result(array $parameters, string $widget_uuid = null)
    {
        if (!key_exists('item', $parameters) || !$parameters['item']) {
            return null;
        }

        /* кеш для виджета */
        $widget = Cache::rememberForever(
            'company.'. $parameters['item'] .'.'. app()->getLocale(),
            function () use ($parameters) {
                return Company::find($parameters['item'])->translateOrDefault(app()->getLocale())->widget_data ?? null;
            }
        );

        return $widget
            ? json_decode($widget)
            : null;
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
            if ($object_uuid == $widget->parameters->item) {
                /* пройдем по всем языковым версиям виджета */
                foreach (config('translatable.locales') as $locale) {
                    $cache_key = 'company.' . $object_uuid. '.' . $locale;
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
