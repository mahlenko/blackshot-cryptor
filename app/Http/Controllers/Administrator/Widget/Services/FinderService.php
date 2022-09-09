<?php


namespace App\Http\Controllers\Administrator\Widget\Services;


use App\Helpers\Nested;
use App\Http\Controllers\Administrator\Widget\ServiceAbstract;
use App\Models\Finder\File;
use App\Models\Finder\Folder;
use App\Models\Page\Page;
use App\Models\Widget\Widget;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\Cache;

/**
 * Class PageService
 * @package App\Http\Controllers\Administrator\Widget\Services
 */
class FinderService extends ServiceAbstract
{
    const TYPE_ALL = 'all';
    const TYPE_ONLY_IMAGES = 'images';
    const TYPE_ONLY_FILES = 'files';

    /**
     * @return string
     */
    public static function name(): string
    {
        return __('finder.widget.name');
    }

    /**
     * @return string
     */
    public static function templateFolder(): string
    {
        return 'finder';
    }

    /**
     * @return array
     */
    public function options(): array
    {
        $folders = Folder::where(['name' => 'Editor'])
            ->first()->descendants;

        return [
            [
                'type' => 'text',
                'name' => 'title',
                'label' => __('finder.widget.title'),
            ],
            [
                'type' => 'textarea',
                'name' => 'description',
                'description' => __('finder.widget.descriptions.description'),
                'label' => __('finder.widget.description'),
                'args' => [
                    'rows' => 2
                ]
            ],
            [
                'type' => 'dropdown',
                'name' => 'item',
                'options' => self::buildCollection($folders),
                'label' => __('finder.widget.item'),
                'args' => [
                    'required' => true
                ],
            ],
            [
                'type' => 'dropdown',
                'name' => 'types',
                'options' => [
                    [
                        'value' => self::TYPE_ALL,
                        'label' => __('finder.widget.types.options.all'),
                    ],
                    [
                        'value' => self::TYPE_ONLY_IMAGES,
                        'label' => __('finder.widget.types.options.images')
                    ],
                    [
                        'value' => self::TYPE_ONLY_FILES,
                        'label' => __('finder.widget.types.options.files')
                    ]
                ],
                'label' => __('finder.widget.types.name'),
                'args' => [
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
        if (!key_exists('item', $parameters)) {
            return null;
        }

        return Cache::remember(
            'widget.finder.'. $widget_uuid,
            3600,
            function() use ($parameters) {
                $files = File::where(['folder_uuid' => $parameters['item']])
                    ->defaultOrder();

                //
                if ($parameters['types'] !== self::TYPE_ALL) {
                    switch ($parameters['types']) {
                        case self::TYPE_ONLY_IMAGES:
                            $files->where('mimeType', 'like', 'image/%');
                            break;
                        case self::TYPE_ONLY_FILES:
                            $files->whereNot('mimeType', 'like', 'image/%');
                            break;
                    }
                }

                return $files->get();
            });
    }

    /**
     * Сброс кеша виджета по элементу внутри
     * @param string $object_uuid
     */
    public static function clearCacheByObjectUuid(string $object_uuid)
    {
//        $widgets = Widget::where(['type' => '\\'.self::class]);
//        if (!$widgets->count()) {
//            return;
//        }
//
//        foreach ($widgets->get() as $widget) {
//            if (in_array($object_uuid, $widget->parameters->items)) {
//                /* пройдем по всем языковым версиям виджета */
//                foreach (config('translatable.locales') as $locale) {
//                    $cache_key = 'widget.' . $widget->uuid. '.' . $locale;
//                    if (Cache::has($cache_key)) {
//                        /* сброс кеша */
//                        Cache::forget($cache_key);
//                    }
//
//                    /* сброс кеша view */
//                    Cache::forget($cache_key . '_view');
//                }
//            }
//        }
    }
}
