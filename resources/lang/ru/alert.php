<?php

declare(strict_types=1);

$success = ['Браво!', 'Супер!', 'Ты молодец!', 'Вау!', 'Круто!'];
$success_emoji = ['🎉', '👏', '👍', '💪', '🔥', '😎', '🥳'];

$fail = ['Упс!', 'Ой! Что-то не так!'];
$fail_emoji = ['🌪', '😔', '👻', '⚠️'];

return [
    'title' => [
        'success' => $success_emoji[array_rand($success_emoji)] . ' ' . $success[array_rand($success)],
        'fail' => $fail_emoji[array_rand($fail_emoji)] . ' ' . $fail[array_rand($fail)],
        'validation' => '⚠️ ' . $fail[array_rand($fail)]
    ],

    'description' => [
        'success' => '',
        'fail' => '',
        'validation' => 'Проверьте форму, одно или несколько полей заполнены некорректно.'
    ]
];
