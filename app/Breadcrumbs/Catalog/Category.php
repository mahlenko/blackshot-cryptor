<?php

declare(strict_types=1);

use Diglactic\Breadcrumbs\Breadcrumbs;

/**
 * -----------------------------------------------------------------------------
 * Модуль каталога
 * -----------------------------------------------------------------------------
 */
Breadcrumbs::for('admin.catalog.home', function($trail)
{
    /* Главная страница панели администратора */
    $trail->parent('admin');

    /* модуль "Каталог" */
    $trail->push(__('catalog.title'), route('admin.catalog.home'));
});

/**
 * -----------------------------------------------------------------------------
 *  Главная страница: Просмотр списка категорий
 * -----------------------------------------------------------------------------
 */
Breadcrumbs::for('admin.catalog.category.home', function($trail, $data = [])
{
    $trail->parent('admin.catalog.home');

    /* модуль "Категории" */
    $trail->push(__('catalog.category.title'), route('admin.catalog.category.home'));

    /* Просмотр категории */
    if ($data['category']) {
        /* родительские категории выбранной */
        if ($data['category']->ancestors->count())
        {
            foreach ($data['category']->ancestors->sortBy('_lft') as $ancestor) {
                if ($ancestor->parent_id) {
                    $trail->push(
                        $ancestor->name,
                        route('admin.catalog.category.home', [
                            'uuid' => $ancestor->uuid
                        ])
                    );
                }
            }
        }

        /* выбранная категория */
        if ($data['category']->parent_id) {
            $trail->push(
                $data['category']->name,
                route('admin.catalog.category.home', [
                    'uuid' => $data['category']->uuid
                ])
            );
        }
    }
});

/**
 * -----------------------------------------------------------------------------
 *  Редактирование или создание категории
 * -----------------------------------------------------------------------------
 */
Breadcrumbs::for('admin.catalog.category.edit', function($trail, $data = [])
{
    $trail->parent('admin.catalog.category.home', [
        'category' => $data['category'] ? $data['category']->parent : $data['parent_category']
    ]);

    if ($data['category'])
    {
        /* Редактируемая катагория */
        $trail->push(
            $data['category']->name,
            route('admin.catalog.category.edit', [
                'locale' => app()->getLocale(),
                'uuid' => $data['category']->uuid
            ])
        );

        /* Язык категории */
        if ($data['locale'] !== app()->getLocale()) {
            $trail->push($data['locale']);
        }
    } else {
        /* Создание новой категории */
        $trail->push(__('catalog.category.create'));
    }
});

