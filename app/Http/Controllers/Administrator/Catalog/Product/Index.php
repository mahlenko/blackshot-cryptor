<?php

namespace App\Http\Controllers\Administrator\Catalog\Product;

use App\Http\Controllers\Controller;
use App\Models\Catalog\Product;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Http\Request;

class Index extends Controller
{
    public function index(Request $request)
    {
        $products = Product::select('products.*')
            ->orderBy('products._lft', 'asc')
            ->with(['categories', 'categories.translation', 'images', 'translations']);

        $products_filter = $this->filter($products, $request);

        return view('administrator.catalog.product.index', [
            'products' => $products_filter
                ->paginate()
                ->appends($request->all())
        ]);
    }

    /**
     * @param Builder $builder
     * @param Request $request
     * @return Builder
     */
    private function filter(Builder $builder, Request $request): Builder
    {
        if ($request->has('category_uuid')) {
            $builder->join('product_categories', 'product_categories.product_uuid', '=', 'products.uuid');
            $builder->where('product_categories.category_uuid', $request->input('category_uuid'));
        }

        if ($request->has('name')) {
            $builder->join('product_translations', 'product_translations.product_uuid', '=', 'products.uuid');
            $builder->where('product_translations.name', 'like', '%'. $request->input('name') .'%');
        }

        return $builder;
    }
}
