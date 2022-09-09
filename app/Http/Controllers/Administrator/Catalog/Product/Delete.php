<?php

namespace App\Http\Controllers\Administrator\Catalog\Product;

use App\Http\Controllers\Controller;
use App\Models\Catalog\Product;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;

class Delete extends Controller
{
    /**
     * @param Request $request
     * @return RedirectResponse
     */
    public function index(Request $request): RedirectResponse
    {
        $data = $request->validate([
            'uuid' => 'required|uuid|exists:App\Models\Catalog\Product,uuid'
        ]);

        $item = Product::where(['uuid' => $data['uuid']])->first();
        $item_arr = $item->toArray();

        if ($item->delete()) {
            flash(__('messages.success.delete', ['name' => $item_arr['name']]))->success();
        } else {
            flash(__('messages.fail.delete'))->error();
        }

        return redirect()->route('admin.catalog.product.home');
    }
}
