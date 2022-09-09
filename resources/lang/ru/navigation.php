<?php

declare(strict_types=1);

return [
    'title' => 'Менеджер меню',
    'create' => 'Создать меню',
    'edit' => 'Редактировать меню',
    'is_double' => 'Меню навигации с таким ключом уже есть на сайте.',
    'delete' => 'Удалить меню',
    'delete_confirm' => 'Удалить меню ":name" со всеми ссылками?\n\nХотите продолжить?',
    'back' => 'Вернуться назад',
    'tabs' => [
        'general' => 'Общие',
    ],
    'columns' => [
        'lang' => 'Язык сайта',
        'name' => 'Название',
        'key' => 'Ключ',
        'description' => 'Описание',
        'cached' => 'Включить кэширование меню',
        'template' => 'Шаблон',
        'updated_at' => 'Дата обновления',
        'created_at' => 'Дата создания',
    ],
    'descriptions' => [
        'name' => 'Будет отображаться в списке навигаций в панели управления',
        'key' => 'По данному ключу меню будет доступно в шаблоне. Кирилица будет переведена в транслит.'
    ],
    'items' => [
        'title' => 'Ссылки',
        'save' => 'Сохранить пункт меню',
        'add' => 'Добавить пункт меню',
        'edit' => 'Редактировать пункт меню',
        'external' => 'Внешняя или внутренняя ссылка',
        'tabs' => [
            'general' => 'Общие',
            'params' => 'Параметры',
        ],
        'columns' => [
            'is_active' => 'Показывать в меню',
            'text' => 'Название',
            'class_name' => 'Тип',
            'meta_uuid' => 'Страница',
            'url' => 'Ссылка',
            'generate_catalog' => 'Автоматически генерировать дочерние ссылки этой страницы',
            'generate_products' => 'Сформировать ссылки на товары',
            'template' => 'Шаблон'
        ],
        'descriptions' => [
            'url' => 'Для внешней ссылки укажите http:// или https://',
            'generate_catalog' => 'Дочерние пункты если они есть, будут отсортированы исходя из сортировки их в панели администратора',
            'generate_products' => 'Сформирует пункты в меню на товары автоматически сгенерированных пунктов категорий',
        ],
        'is_active' => ['выключена', 'включена'],
        'no_select' => 'Сделайте выбор',
        'delete' => 'Удалить пункт',
        'delete_confirm' => 'Вы действительно хотите удалить ссылку ":name" из меню?\n\nХотите продолжить?',
    ],
    'params' => [
        'columns' => [
            'title' => 'Подсказка при наведении',
            'style' => 'CSS стили ссылки',
            'css' => 'CSS класс ссылки',
            'icon' => 'Иконка',
            'iconCss' => 'CSS класс иконки',
            'iconAlt' => 'Атрибут ALT для иконки',
            'target' => 'Открывать',
        ],
        'target' => [
            'self' => 'В текущем окне',
            'blank' => 'В новом окне'
        ],
    ],
    'choice' => [
        'pages' => 'пункт меню|пункта меню|пунктов меню'
    ],
    'generate' => [
        'catalog' => 'Страницы категории ":name" будут добавлены пунктами этой ссылки.',
        'products' => ' Товары категории ":name" будут добавлены пунктами этой ссылки.'
    ]
];
