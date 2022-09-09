<?php

use Diglactic\Breadcrumbs\Breadcrumbs;

Breadcrumbs::for('admin.setting.home', function ($trail) {
    $trail->parent('admin');
    $trail->push(__('settings.title'), route('admin.setting.website.home', ['locale' => app()->getLocale()]));
});


Breadcrumbs::for('admin.setting.website.home', function ($trail, array $data = null) {
    $trail->parent('admin.setting.home');
    $trail->push(__('settings/website.title'), route('admin.setting.website.home', ['locale' => app()->getLocale()]));

    if (key_exists('locale', $data) && $data['locale'] !== app()->getLocale()) {
        $trail->push($data['locale']);
    }
});
