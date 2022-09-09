<?php

declare(strict_types=1);

use Diglactic\Breadcrumbs\Breadcrumbs;

/* Главная страница */
Breadcrumbs::for('admin.finder.home', function($trail, $data = []) {
    $trail->parent('admin');
    $trail->push(__('finder.title'), route('admin.finder.home'));

    if (key_exists('items', $data) && $data['items']) {
        foreach ($data['items']->slice(1) as $folder) {
            $trail->push($folder->name, route('admin.finder.home', $folder->uuid));
        }
    }

    if (key_exists('folder', $data) && $data['folder'] && $data['folder']->ancestors->count()) {
        $trail->push($data['folder']->name, route('admin.finder.home'));
    }

});
