<?php

return [

    /*
    |--------------------------------------------------------------------------
    | Image Driver
    |--------------------------------------------------------------------------
    |
    | Intervention Image supports "GD Library" and "Imagick" to process images
    | internally. You may choose one of them according to your PHP
    | configuration. By default PHP's "GD Library" implementation is used.
    |
    | Supported: "gd", "imagick"
    |
    */

    'driver' => 'gd',

    /* конвертировать изображения в webp */
    'convert_webp' => env('IMAGE_CONVERT_WEBP', false),

    'preview' => [
        'width' => 400,
        'height' => 400,
    ],

];
