<?php

use Diglactic\Breadcrumbs\Breadcrumbs;

Breadcrumbs::for('admin.user.home', function ($trail) {
    $trail->parent('admin');
    $trail->push(__('user.title'), route('admin.user.home'));
});


Breadcrumbs::for('admin.user.edit', function ($trail, array $data = []) {
    $trail->parent('admin.user.home');
    $trail->push($data['object']->name, route('admin.user.edit', ['id' => $data['object']->id]));
});
