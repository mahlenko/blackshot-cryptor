<?php


namespace App\Http\Controllers\Administrator\Widget;

use Illuminate\Support\Collection;
use Illuminate\Support\Str;

/**
 * Class ServiceAbstract
 * @package App\Http\Controllers\Administrator\Widget
 */
abstract class ServiceAbstract
{
    /**
     * Название сервиса
     * @return string
     */
    public static abstract function name(): string;

    /**
     * Каталог с шаблонами
     * @return string
     */
    public static abstract function templateFolder(): string;

    /**
     * Опции виджета
     * @return array
     */
    public abstract function options(): array;

    /**
     * Результат с данными
     * @param array $parameters
     * @param string|null $widget_uuid
     */
    public abstract function result(array $parameters, string $widget_uuid = null);

    /**
     * @param string $object_uuid
     * @return mixed
     */
    public abstract static function clearCacheByObjectUuid(string $object_uuid);

    /**
     * @param string $class_name
     * @return array
     */
    protected function getTranslateTable(string $class_name): array
    {
        $basename = Str::lower(class_basename($class_name));

        return [
            'table' => $basename .'_translations',
            'key' => $basename.'.uuid'
        ];
    }

    /**
     * @param Collection $collection
     * @return Collection
     */
    protected static function buildCollection(Collection $collection)
    {
        $collect = collect();
        $collection->each(function($item) use ($collect) {
            $item_collect = [
                'value' => $item->uuid,
                'label' => $item->name,
                'description' => $item->ancestors->sortBy('_lft')->pluck('name')->join(' > ')
            ];

            $collect->add($item_collect);
        });

        return $collect;
    }
}
