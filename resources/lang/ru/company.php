<?php

return [
    'title' => 'Моя компания',
    'description' => 'Настройте головной офис, добавьте офисы продаж, магазины, точки самовывоза',
    'create' => 'Добавить офис',
    'create_element' => [
        'phone' => 'Добавить телефон',
        'email' => 'Добавить email',
        'address' => 'Добавить адрес',
        'website' => 'Добавить ссылку',
    ],
    'columns' => [
        'name' => 'Название офиса',
        'type' => 'Тип',
        'image' => 'Промо фотография',
        'description' => 'Промо текст',
        'headers' => [
            'addresses' => 'Адрес',
            'phones' => 'Номера телефонов',
            'emails' => 'Email адреса',
            'websites' => 'Сайты',
            'timework' => 'Рабочее время',
        ],
        'phone' => [
            'country_code' => 'Код страны',
            'number' => 'Номер телефона',
            'description' => 'Описание',
        ],
        'address' => 'Адрес',
        'email' => [
            'value' => 'Email',
            'description' => 'Описание'
        ],
        'website' => 'Сайт или соц. сеть',
    ],
    'column_descriptions' => [
        'name' => 'Укажите название офиса, магазина или склада',
        'description' => 'Дополнительное краткое описание'
    ],
    'tabs' => [
        'contacts' => 'Контакты'
    ],
    'info' => 'Для удаления записи, оставьте поле пустым.',
    'widget' => [
        'columns' => [
            'company' => 'Выберите компанию'
        ]
    ]
];
