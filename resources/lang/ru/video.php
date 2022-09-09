<?php

return [
    'title' => '<i class="fas fa-video me-1"></i> Видео',
    'create' => 'Добавить видео',
    'columns' => [
        'name' => 'Название',
        'description' => 'Описание видео',
        'url' => 'Ссылка на видео <i class="fab fa-vimeo-v text-info"></i> Vimeo или <i class="fab fa-youtube text-danger"></i> Youtube.',
        'thumbnail_url' => 'Ссылка на изображение превью',
        'size' => 'Размеры, ШxВ',
        'duration' => 'Длительность видео, секунды',
    ],
    'description' => [
        'reset_data' => 'Вся информация будет изменена для основного языка сайта на полученную с :name. Предварительно сохраните данные, если делали изменения. Страница будет перезагружена.'
    ],
    'success' => [
        'reset_data' => 'Информация о видео успешно обновлены из источника.',
    ],
    'reset_data' => 'Сбросить информацию',
];
