<?php

namespace App\Http\Controllers\Administrator\Catalog\Category;

use App\Http\Controllers\Controller;
use App\Models\Catalog\Category;
use Astrotomic\Translatable\Locales;
use Illuminate\Contracts\Foundation\Application;
use Illuminate\Contracts\View\Factory;
use Illuminate\Contracts\View\View;
use Illuminate\Http\Request;

class Index extends Controller
{
    /**
     * @param string|null $uuid
     * @return Application|Factory|View
     */
    public function index(string $uuid = null)
    {
        if (!$uuid) {
            $root = Category::where(['_lft' => 1])->first();
            $uuid = $root->uuid;
        }

        return view('administrator.catalog.category.index', [
            'category' => Category::find($uuid),
            'category_list' => Category::where('parent_id', $uuid)
                ->with(['products', 'features', 'children', 'children.products', 'translations'])
                ->defaultOrder()
                ->paginate()
        ]);
    }
}
