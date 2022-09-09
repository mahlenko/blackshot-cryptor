<?php

use Diglactic\Breadcrumbs\Breadcrumbs;

Breadcrumbs::for('admin.company.home', function ($trail) {
    $trail->parent('admin');
    $trail->push(__('company.title'), route('admin.company.home'));
});

Breadcrumbs::for('admin.company.edit', function ($trail, array $data = []) {
    $trail->parent('admin.company.home');

    if (key_exists('company', $data) && $data['company']) {
        $trail->push($data['company']->name, route('admin.company.edit', [
            'locale' => app()->getLocale(),
            'uuid' => $data['company']->uuid
        ]));

        if ($data['locale'] !== app()->getLocale()) {
            $trail->push($data['locale']);
        }

    } else {
        $trail->push(__('company.create'));
    }
});
