<?php

return [
    'title' => 'Пользователи',
    'columns' => [
        'created_at' => 'Дата регистрации',
        'name' => 'Имя пользователя',
        'email' => 'Email',
        'role' => 'Роль',
        'new_password' => 'Новый пароль',
        'new_password_confirmation' => 'Подтвердите новый пароль',
        'avatar' => 'Фотография пользователя'
    ],
    'fail' => [
        'access_role' => 'Недостаточно прав для смены роли пользователю.',
        'double_email' => 'Пользователь с таким email уже зарегистрирован',
    ],
    'descriptions' => [
        'email' => 'Адрес является логином пользователя',
        'role' => 'Обратите внимание! Администраторы имеют доступ к панели администрирования. Не назначайте роль администратора, незнакомому аккаунту. Вы можете снять права администратора только у пользователей зарегистрированных после вас.',
    ],
    'roles' => [
        \App\Models\User::ROLE_ADMIN => 'Администратор',
        \App\Models\User::ROLE_USER => 'Пользователь'
    ]
];