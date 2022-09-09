<?php

declare(strict_types=1);

return [
    'dashboard' => [
        'prefix' => 'dashboard',
        'home' => [
            'class' => \App\Http\Controllers\Administrator\Dashboard::class,
            'method' => 'index'
        ]
    ],
];
