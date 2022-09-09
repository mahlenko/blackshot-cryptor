<?php

namespace App\Http\Controllers\Administrator\Catalog\Product;

use App\Helpers\Nested;
use App\Http\Controllers\Controller;
use App\Models\Catalog\ProductCategory;
use Illuminate\Http\Request;

class Sortable extends Controller
{
    public function category(Request $request, string $uuid)
    {
        $data = $request->validate([
            'uuid' => 'required|uuid',
            'amount' => 'required|numeric'
        ]);

        $object = ProductCategory::where(['product_uuid' => $uuid, 'category_uuid' => $data['uuid']])->first();

        $nested = new Nested(ProductCategory::class);
        $result_ok = $nested->sortable($object->uuid, $data['amount']);

        return [
            'ok' => $result_ok,
            'message' => $result_ok
                ? __('sortable.message.success', ['name' => $object->category->name])
                : __('sortable.message.fail', ['name' => $object->category->name])
        ];
    }
}
