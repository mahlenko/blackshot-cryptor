<?php

use Diglactic\Breadcrumbs\Breadcrumbs;

/* Список характеристик */
Breadcrumbs::for('admin.catalog.feature.home', function($trail, $data = []) {
    $trail->parent('admin');
    $trail->push(__('catalog.title'), route('admin.catalog.home'));
    $trail->push(__('catalog.feature.title'), route('admin.catalog.feature.home'));
});

/* Редактирование характеристики */
Breadcrumbs::for('admin.catalog.feature.edit', function($trail, $data = []) {
    $trail->parent('admin.catalog.feature.home');

    /* Редактирование */
    if (key_exists('object', $data) && $data['object']) {
        /* Название характеристики */
        $trail->push($data['object']->name, route('admin.catalog.feature.edit', [
            'locale' => app()->getLocale(),
            'uuid' => $data['object']->uuid
        ]));

        /* Редактируем языковую версию */
        if ($data['locale'] !== app()->getLocale()) {
            $trail->push($data['locale']);
        }

    } else {
        /* Создание новой характеристики */
        $trail->push(__('catalog.feature.create'));
    }
});

/* Редактирование варианта */
Breadcrumbs::for('admin.catalog.feature.page.edit', function($trail, $data = []) {
    $trail->parent('admin.catalog.feature.home');

    /* Название характеристики */
    $trail->push($data['object']->feature->name, route('admin.catalog.feature.edit', [
        'locale' => app()->getLocale(),
        'uuid' => $data['object']->feature->uuid
    ]));

    /* Значение характеристики */
    $trail->push($data['object']->name, route('admin.catalog.feature.page.edit', [
        'locale' => app()->getLocale(),
        'uuid' => $data['object']->uuid
    ]));

    /* Редактируем языковую версию */
    if ($data['locale'] !== app()->getLocale()) {
        $trail->push($data['locale']);
    }
});
