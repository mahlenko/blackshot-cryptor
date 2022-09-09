<?php

declare(strict_types=1);

namespace App\Stores;

use App\Helpers\Nested;
use App\Models\Navigation\NavigationItem;
use App\Models\Navigation\NavigationItemParam;

class NavigationItemStore
{
    /**
     * @param array $data
     * @return bool
     */
    public function handle(array $data): bool
    {
        $item = NavigationItem::where('uuid', $data['uuid'])->first();

        /* create new object */
        if (!$item) {
            $item = new NavigationItem();
            $item->uuid = $data['uuid'];
            $item->navigation_uuid = $data['navigation_uuid'];
        }

        $item->name = $data['name'];
        $item->class_name = $data['class_name'];
        $item->object_uuid = $data['object_uuid'];
        $item->is_active = !empty($data['is_active']);

        /* Сортировка в дереве */
        Nested::model(NavigationItem::class)
            ->tree($item, $data['nested']['position'], $data['nested']['parent_id']);

        $item_save_result = $item->save();

        /* Добавляем параметры */
        $params = $this->setParams($item->params, $data['params']);
        $params_save_result = $params->save();

        return $item_save_result && $params_save_result;
    }

    /**
     * @param string $uuid
     * @param array $params
     * @return NavigationItemParam
     */
    private function setParams(NavigationItemParam $param, array $data): NavigationItemParam
    {
        $param->title = $data['title'];
        $param->style = $data['style'];
        $param->css = $data['css'];
        $param->iconCss = $data['iconCss'];
        $param->iconAlt = $data['iconAlt'];
        $param->target = $data['target'];
        $param->setIcon($data['icon'] ?? null);

        return $param;
    }
}
