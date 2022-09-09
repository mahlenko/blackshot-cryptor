<?php

namespace App\Http\Controllers\Administrator\Catalog\Category;

use App\Http\Controllers\Controller;
use App\Models\Catalog\Category;
use Illuminate\Http\Request;

class Delete extends Controller
{
    /**
     * @param Request $request
     * @return \Illuminate\Http\RedirectResponse
     */
    public function index(Request $request)
    {
        $data = $request->validate([
            'uuid' => 'required|uuid|exists:App\Models\Catalog\Category,uuid'
        ]);

        $category = Category::find($data['uuid']);

        /* запретим удалять корневую страницу */
        if (!$category->parent_id) {
            flash(__('messages.fail.delete_homepage'))->warning();
            return back();
        }

        $category_name = $category->name;

        if ($category->delete()) {
            flash(__('messages.success.delete', ['name' => $category_name]))->success();
        } else {
            flash(__('messages.fail.delete'))->danger();
        }

        return back();
    }
}
