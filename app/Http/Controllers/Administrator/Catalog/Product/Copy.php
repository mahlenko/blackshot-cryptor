<?php

namespace App\Http\Controllers\Administrator\Catalog\Product;

use App\Http\Controllers\Controller;
use App\Models\Catalog\Product;
use App\Repositories\ProductRepository;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Str;

class Copy extends Controller
{
    /**
     * @param Request $request
     * @return RedirectResponse
     */
    public function index(Request $request): RedirectResponse
    {
        $data = $request->validate([
            'uuid' => 'required|exists:\App\Models\Catalog\Product,uuid',
            'postfix' => 'nullable'
        ]);

        /* @var Product $product */
        $product = Product::where(['uuid' => $data['uuid']])->first();
        $copy = (new ProductRepository)->copy($product, $data['postfix'] ?? Str::random(4));

        if ($copy) {
            flash(__('messages.success.copy', ['name' => $product->name]))->success();
        } else {
            flash(__('messages.fail.copy', ['name' => $product->name]))->error();
        }

        return back();
    }
}
