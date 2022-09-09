<?php

declare(strict_types=1);

/* Главная страница */

use Diglactic\Breadcrumbs\Breadcrumbs;

/**
 * -----------------------------------------------------------------------------
 *  Главная страница каталога
 * -----------------------------------------------------------------------------
 */
Breadcrumbs::for('admin.catalog.feature.group.home', function($trail) {
    $trail->parent('admin.catalog.feature.home');
    $trail->push(__('catalog.feature.group.title_breadcrumbs'), route('admin.catalog.feature.group.home'));
});

/**
 * -----------------------------------------------------------------------------
 *  Создать или редактировать группу характеристик
 * -----------------------------------------------------------------------------
 */
Breadcrumbs::for('admin.catalog.feature.group.edit', function($trail, $data = []) {
    $trail->parent('admin.catalog.feature.group.home');

    if ($data['group']) {
        $trail->push(
            $data['group']->name,
            route(
                'admin.catalog.feature.group.edit', [
                    'locale' => app()->getLocale(),
                    'uuid' => $data['group']->uuid
                ])
        );

        if (app()->getLocale() !== $data['locale']) {
            $trail->push($data['locale']);
        }

    } else {
        $trail->push(__('catalog.feature.group.create'), route('admin.catalog.feature.group.edit', ['locale' => $data['locale']]));
    }
});
