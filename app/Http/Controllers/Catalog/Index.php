<?php

namespace App\Http\Controllers\Catalog;

use App\Http\Controllers\Controller;
use App\Models\Catalog\Category;
use Illuminate\Http\Request;

class Index extends Controller
{
    /**
     * Главная страница каталога
     */
    public function index()
    {
        $categories = Category::where(['parent_id' => null])->defaultOrder()->with(['icon'])->withTranslation()->get();

        return view('web.catalog.home', [
            'categories' => $categories
        ]);
    }
}
