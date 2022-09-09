<?php


namespace App\Repositories;


use App\Helpers\Nested;
use App\Models\Catalog\Category;
use App\Models\Meta;
use App\Models\Navigation\NavigationItem;
use Illuminate\Support\Str;

class NavigationItemRepository
{

    /**
     * @param string $uuid
     * @param string $locale
     * @param array $item
     * @param array $params
     * @param array $nested
     * @return NavigationItem
     */
    public function store(
        string $uuid,
        string $locale,
        array $item,
        array $params,
        array $nested)
    : NavigationItem {

        $item['is_active'] = !empty($item['is_active']);
        $item['generate_catalog'] = !empty($item['generate_catalog']);
        $item['generate_products'] = !empty($item['generate_products']);

        if ($item['generate_products']) {
            $meta = Meta::find($item['meta_uuid']);
            if ($meta->object_type !== Category::class) {
                $item['generate_products'] = false;
            }
        }

        if (!Str::startsWith('//', $item['url'])) {
            $item['url'] = trim($item['url'], '/');
        }

        $object = NavigationItem::find($uuid);
        if (!$object) {
            $object = new NavigationItem();
            $object->uuid = $uuid;
        }

        $object->setDefaultLocale($locale)->fill($item);
        $object->params->setDefaultLocale($locale)->fill($params);

        /* Сортировка в дереве */
        Nested::model(NavigationItem::class)
            ->tree($object, $nested['position'], $nested['parent_id']);

        $object->push();

        return $object;
    }

}
