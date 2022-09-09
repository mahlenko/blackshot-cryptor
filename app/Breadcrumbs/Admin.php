<?php

declare(strict_types=1);

use Diglactic\Breadcrumbs\Breadcrumbs;

Breadcrumbs::for('admin', function ($trail) {
    $trail->push(__('dashboard.title'), route('admin.home'));
});

Breadcrumbs::for('admin.home', function ($trail) {
    $trail->parent('admin');
});
