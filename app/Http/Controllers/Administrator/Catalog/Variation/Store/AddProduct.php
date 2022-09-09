<?php

namespace App\Http\Controllers\Administrator\Catalog\Variation\Store;

use App\Http\Controllers\Controller;
use App\Models\Catalog\Product;
use App\Repositories\ProductRepository;
use App\Repositories\VariationRepository;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

/**
 * Добавить товар в группу из существующих
 * @package App\Http\Controllers\Administrator\Catalog\Variation\Store
 */
class AddProduct extends Controller
{
    /**
     * Добавить в группу из существующих
     * @param Request $request
     * @return JsonResponse
     */
    public function index(Request $request): JsonResponse
    {
        $data = $request->validate([
            'product_uuid' => 'required|uuid|exists:App\Models\Catalog\Product,uuid',
            'group_uuid' => 'required|uuid',
            'combination' => 'required|array',
            'combination.*' => 'required|uuid'
        ]);

        /* @var Product $product */
        $product = Product::findOrFail($data['product_uuid']);

        /* Создадим группу с характеристиками если еще нет её */
        $variations_group = (new VariationRepository())->findOrCreate($data['group_uuid'], $product);

        foreach ($data['combination'] as $product_uuid) {
            $variations_group->addProduct(Product::findOrFail($product_uuid));
        }

        $message = __('catalog.variation.added_choice_combination', [ 'count' => count($data['combination']) ]);
        return $this->responseOk( trans_choice($message, count($data['combination'])), ['reload' => true] );
    }
}
