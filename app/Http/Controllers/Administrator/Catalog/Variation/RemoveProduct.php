<?php

namespace App\Http\Controllers\Administrator\Catalog\Variation;

use App\Http\Controllers\Controller;
use App\Models\Catalog\ProductVariationGroupProduct;
use Illuminate\Http\Request;

class RemoveProduct extends Controller
{
    /**
     * @param Request $request
     * @return \Illuminate\Http\RedirectResponse
     */
    public function index(Request $request): \Illuminate\Http\RedirectResponse
    {
        $data = $request->validate([
            'uuid' => 'required|uuid',
            'group_uuid' => 'required|uuid'
        ]);

        $product = ProductVariationGroupProduct::where([
            'product_uuid' => $data['uuid'],
            'group_uuid' => $data['group_uuid'],
        ])->first();

        $name = $product->object->name;

        /* если после удаления останется 1 товар, то распускаем группу */
        if ($product->group->products->count() == 2) {
            $name = $product->group->code;
            $product->group->delete();
        } else {
            /* убираем товар из группы */
            $product->delete();
        }

        flash(__('messages.success.delete', ['name' => $name]))->success();
        return back();
    }
}
