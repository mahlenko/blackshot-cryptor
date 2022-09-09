<?php

use Diglactic\Breadcrumbs\Breadcrumbs;

Breadcrumbs::for('admin.references.country.home', function ($trail) {
    $trail->parent('admin.home');
    $trail->push(__('references.references'), route('admin.references.country.home'));
    $trail->push(__('countries.title'), route('admin.references.country.home'));
});

Breadcrumbs::for('admin.references.country.edit', function ($trail, $data = []) {
    $trail->parent('admin.references.country.home');

    if (!key_exists('country', $data) || !$data['country']) {
        $trail->push(__('countries.create'), route('admin.references.country.edit', ['locale' => $data['locale']]));
    } else {
        $trail->push(__('countries.edit'), route('admin.references.country.edit', ['locale' => $data['locale']]));
    }
});
