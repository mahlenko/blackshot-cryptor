<?php

namespace App\Http\Controllers\Administrator\Catalog\Variation\Store;

use App\Http\Controllers\Controller;
use App\Models\Catalog\Product;
use App\Models\Catalog\ProductFeature;
use App\Repositories\ProductRepository;
use App\Repositories\VariationRepository;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Str;

/**
 * Создать новый вариант товара
 * @package App\Http\Controllers\Administrator\Catalog\Variation\Store
 */
class CreateProduct extends Controller
{
    /**
     * @param Request $request
     * @return JsonResponse
     */
    public function index(Request $request)
    {
        $data = $request->validate([
            'product_clone_uuid' => 'required|uuid|exists:App\Models\Catalog\Product,uuid',
            'group_uuid' => 'nullable|uuid',
            'combinations' => 'required|array',
            'combinations.*' => 'required|array',
            'combinations.*.*' => 'required|uuid',
            'selected' => 'required|array',
        ]);


        /* Характеристики выбранных вариантов */
        $combinations = [];
        foreach ($data['selected'] as $index) {
            $combinations[] = $data['combinations'][$index];
        }

        /* Товар которые будем клонировать */
        $product = Product::where(['uuid' => $data['product_clone_uuid']])->first();

        $group = (new VariationRepository())->findOrCreate($data['group_uuid'], $product);

        /* Создание комбинаций */
        foreach ($combinations as $combination) {
            /* Создаем копию товара */
            $clone = (new ProductRepository())->copy($product, Str::random(4));

            /* Заменим характеристики на выбранные из варианта товара */
            foreach ($combination as $feature_uuid => $value) {
                $feature = ProductFeature::where(['feature_uuid' => $feature_uuid, 'product_uuid' => $clone->uuid])->first();
                $feature->feature_variant_uuid = $value;
                $feature->save();
            }

            /* Добавим товар в группу */
            $group->addProduct($clone);
        }

        $message = __('catalog.variation.added_choice_combination', ['count' => count($combinations)]);
        return $this->responseOk( trans_choice($message, count($combinations)), ['reload' => true] );
    }
}
