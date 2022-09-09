<?php

use Diglactic\Breadcrumbs\Breadcrumbs;

Breadcrumbs::for('admin.widget.home', function ($trail) {
    $trail->parent('admin');
    $trail->push(__('widget.title'), route('admin.widget.home'));
});

Breadcrumbs::for('admin.widget.edit', function ($trail, array $data = []) {
    $trail->parent('admin.widget.home');

    if (key_exists('widget', $data) && $data['widget']) {
        $trail->push($data['widget']->name, route('admin.widget.edit', [
            'uuid' => $data['widget']->uuid
        ]));
    } else {
        $trail->push(__('widget.create'));
    }
});
