<?php

declare(strict_types=1);

use App\Models\Navigation\NavigationItem;
use Diglactic\Breadcrumbs\Breadcrumbs;

/* Список меню */
Breadcrumbs::for('admin.navigation.home', function($trail, $data = []) {
    $trail->parent('admin');
    $trail->push(__('navigation.title'), route('admin.navigation.home'));

    if (key_exists('navigation', $data) && $data['navigation']) {
        $trail->push(
            __('navigation.items.title') .': '. $data['navigation']->name,
            route('admin.navigation.items.home', ['uuid' => $data['navigation']->uuid])
        );
    }
});

/* Редактировать меню */
Breadcrumbs::for('admin.navigation.edit', function($trail, $data = []) {
    $trail->parent('admin.navigation.home', $data);

    if (key_exists('navigation', $data) && $data['navigation']) {
        $trail->push(__('navigation.edit'));
    } else {
        $trail->push(__('navigation.create'));
    }
});

/* -------------------------------------------------------------------------- */

/* Список ссылок */
Breadcrumbs::for('admin.navigation.items.home', function($trail, $data = []) {
    $trail->parent('admin.navigation.home', $data);

    if (key_exists('parent_id', $data)) {
        $ancestors = \App\Models\Navigation\NavigationItem::withTranslation()
            ->where('navigation_uuid', $data['navigation']->uuid)
            ->defaultOrder()
            ->ancestorsAndSelf($data['parent_id']);

        foreach ($ancestors as $ancestor) {
            $trail->push($ancestor->name, route('admin.navigation.items.home', [
                'uuid' => $data['navigation']->uuid,
                'parent_id' => $ancestor->uuid
            ]));
        }
    }
});

/* Редактировать ссылку меню */
Breadcrumbs::for('admin.navigation.items.edit', function($trail, $data = []) {
    $trail->parent('admin.navigation.items.home', $data);

    if (key_exists('parent', $data) && $data['parent']) {
        $ancestors = \App\Models\Navigation\NavigationItem::withTranslation()
            ->where('navigation_uuid', $data['parent']->navigation_uuid)
            ->defaultOrder()
            ->ancestorsAndSelf($data['parent']->uuid);

        foreach ($ancestors as $ancestor) {
            $trail->push($ancestor->name, route('admin.navigation.items.home', [
                'uuid' => $data['navigation']->uuid,
                'parent_id' => $ancestor->uuid
            ]));
        }
    }

    if (key_exists('item', $data) && $data['item']) {
        /* категория ссылок */
        $trail->push($data['item']->name, route('admin.navigation.items.edit', [
            'uuid' => $data['navigation']->uuid,
            'navigation_item' => $data['item']->uuid,
            'locale' => app()->getLocale(),
        ]));

        if ($data['locale'] !== app()->getLocale()) {
            $trail->push($data['locale']);
        }

    } else {
        $trail->push(__('navigation.items.add'));
    }
});
