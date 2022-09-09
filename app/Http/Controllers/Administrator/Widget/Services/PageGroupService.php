<?php


namespace App\Http\Controllers\Administrator\Widget\Services;


use App\Helpers\Nested;
use App\Http\Controllers\Administrator\Widget\ServiceAbstract;
use App\Models\Page\Page;
use App\Models\Widget\Widget;
use DateTimeImmutable;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Cache;

class PageGroupService extends ServiceAbstract
{
    /**
     * @inheritDoc
     */
    public static function name(): string
    {
        return __('page.widget.title.group');
    }

    /**
     * @return string
     */
    public static function templateFolder(): string
    {
        return 'pages';
    }

    /**
     * @inheritDoc
     */
    public function options(): array
    {
        $ignore_uuid = Page::whereIsLeaf()->select('uuid')->get()->pluck('uuid');
        $categories = Page::defaultOrder()->whereNotIn('uuid', $ignore_uuid)->withTranslation()->get();

        return [
            [
                'type' => 'dropdown',
                'name' => 'item',
                'options' => self::buildCollection($categories),
                'label' => __('page.widget.columns.category'),
                'args' => [
                    'required' => true
                ],
            ],
            [
                'type' => 'number',
                'name' => 'limit',
                'default' => 5,
                'label' => __('page.widget.columns.limit'),
                'args' => [
                    'required' => true
                ]
            ],
            [
                'type' => 'number',
                'name' => 'children_limit',
                'default' => 0,
                'label' => __('page.widget.columns.children_limit'),
                'description' => __('page.widget.columns.descriptions.children_limit'),
                'args' => [
                    'required' => true
                ]
            ],
            [
                'type' => 'dropdown',
                'name' => 'sortable',
                'options' => [
                    ['value' => '_lft.asc', 'label' => __('page.widget.sortable._lft.asc')],
                    ['value' => '_lft.desc', 'label' => __('page.widget.sortable._lft.desc')],
                    ['value' => 'metas.created_at.asc', 'label' => __('page.widget.sortable.created_at.asc')],
                    ['value' => 'metas.created_at.desc', 'label' => __('page.widget.sortable.created_at.desc')],
                ],
                'label' => __('page.widget.columns.sortable'),
                'args' => [
                    'required' => true
                ]
            ]
        ];
    }

    /**
     * @inheritDoc
     */
    public function result(array $parameters, string $widget_uuid = null)
    {
        if (!key_exists('item', $parameters) || !$parameters['item']) {
            return null;
        }

        if (key_exists('sortable', $parameters) && !empty($parameters['sortable'])) {
            $sortable_segments = explode('.', $parameters['sortable']);
            $column = implode('.', array_slice($sortable_segments, 0, -1));
            $direction = $sortable_segments[count($sortable_segments) - 1];
        } else {
            $column = '_lft';
            $direction = 'asc';
        }

        /* Кешированный результат */
        return Cache::remember(
            'widget.group.'. $widget_uuid .'.'. app()->getLocale(),
            3600,
            function () use ($column, $direction, $parameters) {
                /* @var Page $item */
                $item = Page::where('uuid', $parameters['item'])
                    ->withDepth()
                    ->first();

                $pages_uuids = $item
                    ->descendants()
                    ->select('pages.uuid')
                    ->join('metas', 'metas.object_id', $item->getTable() .'.'. $item->getKeyName())
                    ->join('page_translations', 'page_translations.page_uuid', '=', $item->getTable() .'.'. $item->getKeyName())
                    ->where('page_translations.locale', app()->getLocale())
                    ->where('metas.publish_at', '<=', (new DateTimeImmutable('now'))
                        ->format('Y-m-d H:i:s'))
                    ->withDepth()
                    ->orderBy($column, $direction)
                    ->having('depth', $item->depth + 1)
                    ->limit($parameters['limit'])
                    ->pluck('uuid');

                if (!$pages_uuids->count()) return collect();

                $pages = Page::find($pages_uuids)
                    ->load(['translations', 'descendants', 'meta', 'images']);

                if ($direction == 'asc') $pages = $pages->sortBy($column);
                else $pages = $pages->sortByDesc($column);

                return $pages->each(function($page) use ($column, $direction) {
                    if ($direction == 'asc') $page->descendants->sortBy($column);
                    else $page->descendants->sortByDesc($column);
                });
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
            if (!$widget->parameters) continue;

            if ($object_uuid == $widget->parameters->item) {
                /* пройдем по всем языковым версиям виджета */
                foreach (config('translatable.locales') as $locale) {
                    $cache_key = 'widget.group.' . $widget->uuid. '.' . $locale;
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
