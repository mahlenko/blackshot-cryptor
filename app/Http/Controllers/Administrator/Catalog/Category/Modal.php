<?php

namespace App\Http\Controllers\Administrator\Catalog\Category;

use App\Helpers\Nested;
use App\Http\Controllers\Controller;
use App\Models\Catalog\Category;
use Illuminate\Http\Request;

class Modal extends Controller
{
    public function index()
    {
        return view('administrator.catalog.category.popup', [
            'title' => __('catalog.category.popup.title'),
            'description' => __('catalog.category.popup.description'),
            'action_text' => __('catalog.category.popup.action_text'),
            'object_list' => (new Nested(Category::class))->optGroup(app()->getLocale())
        ]);
    }
}
