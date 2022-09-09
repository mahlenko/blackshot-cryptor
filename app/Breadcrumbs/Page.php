<?php

declare(strict_types=1);

use Diglactic\Breadcrumbs\Breadcrumbs;
use Illuminate\Support\Facades\Request;

/* Список страниц */
Breadcrumbs::for('admin.page.home', function($trail, $data) {
    $trail->parent('admin');
    $trail->push(__('page.title'), route('admin.page.home'));

    if (key_exists('object', $data) && $data['object']) {
        foreach ($data['object']->ancestors()->defaultOrder()->withTranslation()->get() as $page) {
            if ($page->parent_id) {
                $trail->push($page->name, route('admin.page.home', ['uuid' => $page->uuid]));
            }
        }

        if ($data['object']->parent_id) {
            $trail->push($data['object']->name, route('admin.page.home', ['uuid' => $data['object']->uuid]));
        }
    }

});

/* Редактирование страницы */
Breadcrumbs::for('admin.page.edit', function($trail, $data)
{
    $trail->parent('admin.page.home', $data);

    if (key_exists('item', $data) && $data['item']) {
        $trail->push($data['item']->name, route('admin.page.edit', ['uuid' => $data['item']->uuid, 'locale' => app()->getLocale()]));
        if (key_exists('locale', $data) && $data['locale'] && $data['locale'] !== app()->getLocale()) {
            $trail->push($data['locale']);
        }
    } else {
        $trail->push(__('page.create'));
    }
});
