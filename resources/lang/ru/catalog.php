<?php

declare(strict_types=1);

return [
    'title' => 'Каталог',
    'settings' => 'Настройки',
    'forward_back' => 'Вернуться назад',

    /* категории */
    'category' => [
        'all' => 'Все категории',
        'title' => 'Категории',
        'navigation_title' => 'Каталог: категории',
        'create' => 'Добавить категорию',
        'product_add' => 'Добавить товар в категорию',
        'products_choice' => 'В категории :count товар|В категории :count товара|В категории :count товаров',
        'features_choice' => 'В категории :count характеристика + общие|В категории :count характеристики + общие|В категории :count характеристик + общие',
        'no_products' => 'В категории ещё нет товаров',
        'no_features' => 'В категории только общие характеристики',
        'columns' => [
            'name' => 'Название',
            'template' => 'Шаблон',
            'preview' => 'Промо изображение',
            'description' => 'Промо текст',
        ],
        'column_descriptions' => [
            'description' => 'Изображение и текст промо будут показаны при просмотре категории.'
        ],
        'popup' => [
            'title' => 'Категории каталога',
            'description' => 'Выберите категории к которым нужно привязать объект',
            'action_text' => 'Выбрать'
        ],
        'widget' => [
            'title' => 'Категории каталога',
            'columns' => [
                'items' => 'Выберите категории'
            ]
        ],
    ],

    /* Характеристики */
    'feature' => [
        'title' => 'Характеристики',
        'navigation_title' => 'Каталог: характеристики (Бренд, Автор)',
        'create' => 'Добавить характеристику',
        'append_category' => 'Выбрать категории',
        'purposes' => [
            'find_product' => 'Поиск товаров через фильтры',
            'group_products' => 'Варианты как отдельные товары',
            'group_variants' => 'Варианты как один товар',
            'organize_catalog' => 'Бренд, автор, и т.п.',
            'describe' => 'Дополнительная информация',
        ],
        'purposes_description' => [
            'find_product' => 'Для характеристики, которая просто позволяет указать какое-нибудь дополнительное свойство товара. Например, у футболок это может быть "Ткань". Если вы создадите фильтр по этой характеристике, покупатели увидят, что она есть, и смогут легко найти по ней нужный товар.',
            'group_products' => 'Для случаев, когда похожие товары явно отличаются одной характеристикой и называются по-разному (например, несколько вариантов характеристики "Цвет" у футболки). Эти товары появятся как отдельные позиции в каталоге. Покупатели также смогут выбрать нужное значение характеристики на странице товара. Для этого настройте вариации для товаров с этой характеристикой.',
            'group_variants' => 'Для случаев, когда несколько похожих товаров отличаются характеристикой, но выглядят похоже и называются одинаково (например, одна футболка с разными значениями характеристики "Размер"). Эти товары появятся как одна позиция в каталоге. Покупатели смогут выбрать нужное значение характеристики на странице товара. Для этого настройте вариации для товаров с этой характеристикой.',
            'organize_catalog' => 'Для характеристик наподобие "Бренд" или "Производитель" (у большинства товаров), "Исполнитель" или "Лейбл" (у музыки), "Автор" или "Издательство" (у книг), и так далее. Каждый вариант такой характеристики получает свою страницу с изображением, описанием и списком товаров.',
            'describe' => 'Для случаев, когда просто нужна какая-то информация на странице товара. Фильтр для поиска товаров по такой характеристике создать не получится.',
        ],
        'columns' => [
            'name' => 'Название',
            'is_active' => 'Характеристика активна',
            'group' => 'Группа характеристик',
            'icon' => 'Изображение',
            'is_show_feature' => 'Показывать: в характеристиках',
            'is_show_description' => 'Показывать: вместе с описанием товара в списке товаров',
            'purpose' => 'Цель',
            'purpose_description' => 'Определяет как ваши покупатели будут использовать характеристику',
            'view_filter' => 'Вид в фильтре',
            'view_product' => [
                'title' => 'Вид в товаре',
                'dropdown' => 'Выпадающий список',
                'image' => 'Изображение',
                'text_label' => 'Текстовая метка'
            ],
            'prefix' => 'Префикс',
            'prefix_description' => 'Текст перед названием характеристики',
            'postfix' => 'Постфикс',
            'postfix_description' => 'Текст после названия характеристики',
        ],
        'fail_data_options' => 'Нет параметров для выбранной цели',
        'options' => [
            'checkbox' => 'Флажок',
            'checkbox_group' => 'Группа флажков',
            'slider' => 'Слайдер с числами',
            'color' => 'Цвет',
            'text' => 'Текст или число',
            'dropdown' => 'Выпадающий список',
            'image' => 'Изображения',
            'label' => 'Текстовые метки'
        ],
        'tabs' => [
            'general' => 'Общие',
            'variants' => 'Варианты',
            'categories' => 'Категории'
        ],
        'without_group' => '-- без группы --',
        'variant' => [
            'title' => 'Значения',
            'navigation_title' => 'Каталог: значения характеристик',
            'create' => 'Добавить значение',
            'value' => 'Новое значение',
            'info' => 'Оставьте значение пустым, чтобы удалить его из характеристики.',
            'columns' => [
                'name' => 'Название',
                'url' => 'Сайт',
                'url_description' => 'Ссылка на официальный сайт бренда, автора, издательства и т.д.',
                'preview' => 'Логотип или фотография',
                'description' => 'Краткое описание',
                'description_description' => 'Описание будет показано на странице характеристики со списком значений.'
            ]
        ],

        'all_allow_category' => 'Характеристика доступна для всех категорий каталога.',
        'allow_category' => 'Характеристика доступна для: ',
        'categories' => 'Категории',
        'and_more_categories' => 'и ещё :count',
        'choice_category' => 'категория|категории|категорий',
        'choice_and_more' => 'и ещё :count характеристика|и ещё :count характеристики|и ещё :count характеристик',
        'choice_used_product' => 'Характеристику использует :count товар|Характеристику используют :count товара|Характеристику используют :count товаров',
        'unused_product' => 'Характеристика не используется',
        'hidden' => 'Не показывается',
        'use_template' => 'Как настроено в шаблоне',
        'all_categories' => 'Все категории',

        /* Группы характеристик */
        'group' => [
            'title' => 'Группы характеристик',
            'title_breadcrumbs' => 'Группы',
            'create' => 'Добавить группу',
            'edit' => 'Редактировать группу',
            'save' => 'Сохранить',
            'tabs' => [],
            'forward_back' => 'Вернуться назад',
            'columns' => [
                'is_active' => 'Группа активна',
                'name' => 'Название'
            ],
            'no_group' => 'Без группы',
            'features_choice' => 'В группе :count характеристика|В группе :count характеристики|В группе :count характеристик',
            'no_features' => 'В группе ещё нет характеристик',
        ]
    ],

    /* Товары */
    'product' => [
        'title' => 'Товары',
        'navigation_title' => 'Каталог: товары',
        'create' => 'Добавить товар',
        'video' => 'Видео',
        'files' => 'Файлы',
        'combine' => 'Комбинации',
        'columns' => [
            'is_active' => 'Показывать товар',
            'status' => 'Статус',
            'name' => 'Название',
            'categories' => 'Категории',
            'price' => 'Цена ($)',
            'images' => 'Изображения',
            'product_code' => 'Код (артикул)',
            'url' => 'Ссылка на сайт или документ',
            'quantity' => 'Количество',
            'out_of_stock_action' => 'При отсутствии товара в наличии',
            'weight' => 'Вес (кг)',
            'length' => 'Длина (см)',
            'width' => 'Ширина (см)',
            'height' => 'Высота (см)',
            'min_qty' => 'Минимальный заказ',
            'max_qty' => 'Максимальный заказ',
            'step_qty' => 'Шаг выбора количества',
            'age_limit' => 'Возрастные ограничения',
            'age_verification' => 'Требовать подтверждение возраста',
            'views' => 'Просмотров',
            'popular' => 'Популярность',
        ],
        'variants' => 'Варианты',
        'separators' => [
            'package' => 'Упаковка',
            'order' => 'Параметры заказа',
            'age_limit' => 'Возрастные ограничения',
            'extends' => 'Дополнительно',
        ],
        'descriptions' => [
            'categories' => 'Выберите категории в которых будет отображаться товар. <code>Первая категория в списке</code>, будет основной для товара.',
            'product_code' => 'Если не указан, будет сгенерирован автоматически случайной строкой.',
            'url' => 'Например, ссылка на товар на официальном сайте. <strong>Обязательно:</strong> указать http:// или https://',
            'quantity' => 'Товаров в наличии.',
            'weight' => 'Для расчета стоимости доставки товару с нулевым весом (не цифровому) автоматически назначается минимальный ненулевой вес.',
            'nullable' => 'Укажите <code>ноль</code> для отключения ограничения.',
            'age_limit' => 'Некоторые товары могут иметь ограничения по возрасту.',
            'views' => 'Количество просмотров в магазине',
            'popular' => 'Рейтинг популярности товара, рассчитываемый на основе количества просмотров в магазине, добавлений в корзину и покупок.'
        ],
        'update_ajax_message' => [
            'ok' => [
                'feature' => 'Характеристика успешно обновлена.',
            ],
            'fail' => [
                'feature' => 'Вариант с такими значениями уже есть в группе.'
            ]
        ]
    ],

    'variation' => [
        'add' => 'Добавить варианты',
        'create' => 'Создать варианты',
        'use_existing_products' => 'Использовать существующие товары',
        'description' => 'Выберите варианты которые нужно добавить или создать',
        'no_features_for_variants' => '<p>У этого товара нет характеристик, по которым можно группировать товары. Проверьте, что:</p><ol>
            <li class="list-item">В каталоге создана хотя бы одна характеристика, у которой Цель: Вариации как отдельные товары или Вариации как один товар.</li>
            <li class="list-item">Вы задали товару какое-то значение этой характеристики.</li>
        </ol>',
        'no_similar_products' => 'В каталоге нет подходящих товаров, создайте новые.',
        'added_choice_combination' => 'Добавлен :count вариант товара|Добавлено :count варианта товара|Добавлено :count вариантов товара',
        'this_is_product' => 'Вы тут',
        'primary' => 'Основной',
        'disband' => 'Распустить группу',
        'columns' => [
            'code' => 'Код группы'
        ],
        'action' => [
            'remove_by_group' => 'Убрать из группы',
            'delete' => 'Удалить товар',
        ]
    ]
];
