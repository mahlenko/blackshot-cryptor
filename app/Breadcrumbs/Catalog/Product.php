<?php

/* Список характеристик */

use Diglactic\Breadcrumbs\Breadcrumbs;

/**
 * -----------------------------------------------------------------------------
 *  Главная страница товаров
 * -----------------------------------------------------------------------------
 */
Breadcrumbs::for('admin.catalog.product.home', function($trail)
{
    $trail->parent('admin.catalog.home');
    $trail->push(__('catalog.product.title'), route('admin.catalog.product.home'));
});

/**
 * -----------------------------------------------------------------------------
 *  Создание и редактирование товара
 * -----------------------------------------------------------------------------
 */
Breadcrumbs::for('admin.catalog.product.edit', function($trail, $data = [])
{
    $trail->parent('admin.catalog.product.home');

    if (key_exists('product', $data) && $data['product']) {
        $trail->push($data['product']->name ?? '', route('admin.catalog.product.edit', [
            'locale' => app()->getLocale(),
            'uuid' => $data['product']->uuid
        ]));

        if ($data['locale'] !== app()->getLocale()) {
            $trail->push($data['locale']);
        }

    } else {
        $trail->push(__('catalog.product.create'));
    }
});

