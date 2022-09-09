<?php

namespace App\Http\Controllers;

use App\Models\Meta;

class Sitemap extends Controller
{
    /**
     * Генерирует карту сайта из всех страниц на всех языках
     */
    public function index(): \Illuminate\Http\Response
    {
        return \Anita\Sitemap::generate(
            Meta::groupBy('object_type')
                ->pluck('object_type')
                ->toArray()
        );
    }
}
