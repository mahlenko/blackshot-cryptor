<?php

namespace App\Http\Controllers\Administrator\Catalog\Variation;

use App\Http\Controllers\Controller;
use App\Models\Catalog\Product;
use Illuminate\Contracts\Foundation\Application;
use Illuminate\Contracts\View\Factory;
use Illuminate\Contracts\View\View;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use Illuminate\Validation\ValidationException;

/**
 * Создать или добавить варианты товара в группу
 * @package App\Http\Controllers\Administrator\Catalog\Variation
 */
class Index extends Controller
{
    /**
     * @param string $uuid
     * @return Application|Factory|View
     * @throws ValidationException
     */
    public function index(string $uuid)
    {
        $validator = Validator::make(['uuid' => $uuid], ['uuid' => 'required|uuid']);
        if ($validator->fails()) {
            abort(404);
        }

        $data = $validator->validate();

        /* @var Product $product */
        $product = Product::where(['uuid' => $data['uuid']])->first();

        return view('administrator.catalog.variation.index', [
            'uuid' => $data['uuid'],
            'locale' => app()->getLocale(),
            'product' => $product,
            'title' => __('catalog.variation.add'),
            'description' => __('catalog.variation.description'),
            'max_width' => 900,
            'chunks_template' => [
                [
                    'key' => 'add_product',
                    'name' => '<i class="fas fa-shopping-cart me-1"></i>' . __('catalog.variation.use_existing_products'),
                    'template' => 'administrator.catalog.variation.tabs.similar',
                    'data' => [
                        'similar' => $product->similarWithoutGroup(), // похожие товары
                    ]
                ],
                [
                    'key' => 'create_product',
                    'name' => '<i class="fas fa-cart-plus me-1"></i>' . __('catalog.variation.create'),
                    'template' => 'administrator.catalog.variation.tabs.variation',
                    'data' => [
                        'combinations' => $product->variants(), // варианты для создания новых товаров
                    ]
                ]
            ],
        ]);
    }
}
