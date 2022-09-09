<?php

namespace App\Http\Controllers\Administrator\Catalog\Product;

use App\Http\Controllers\Controller;
use App\Models\Catalog\Product;
use App\Models\Catalog\ProductFeature;
use App\Repositories\ProductRepository;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class AjaxUpdate extends Controller
{
    /**
     * Обновление характиристик для вариантов товара
     * @param Request $request
     * @return JsonResponse
     */
    public function variantFeatures(Request $request): JsonResponse
    {
        $data = $request->validate([
            'uuid' => 'required|uuid|exists:App\Models\Catalog\Product,uuid',
            'feature' => 'required|uuid',
            'value' => 'required'
        ]);

        /* @var Product $product */
        $product = Product::find($data['uuid']);

        /* доступные характеристики для смены через эту функцию */
        $variation_features_group = $product->group->features;

        /* характеристики проекта который редактируем */
        $variation_features = ProductFeature::where('product_uuid', $product->uuid)
            ->whereIn('feature_uuid', $variation_features_group->pluck('feature_uuid'))
            ->pluck('feature_variant_uuid', 'feature_uuid')->sort()->toArray();

        /* обновим характеристику на новое значение */
        if (!key_exists($data['feature'], $variation_features)) {
            return $this->responseFail(__('messages.fail.general'));
        }

        $variation_features[$data['feature']] = $data['value'];

        /* смотрим чтобы не было совпадений с другими товарами */
        foreach ($product->group->products as $product_group) {
            if ($product_group['product_uuid'] == $product->uuid) continue;

            $product_group_features = ProductFeature::where('product_uuid', $product_group['product_uuid'])
                ->whereIn('feature_uuid', $variation_features_group->pluck('feature_uuid'))
                ->pluck('feature_variant_uuid', 'feature_uuid')->sort()->toArray();

            if ($variation_features == $product_group_features) {
                return $this->responseFail(__('catalog.product.update_ajax_message.fail.feature'));
            }
        }

        (new ProductRepository())->findOrCreateFeature($product, $data['feature'], $data['value']);

        return $this->responseOk(__('catalog.product.update_ajax_message.ok.feature'));
    }

    /**
     * Обновление параметров товара
     * @param Request $request
     * @return JsonResponse
     */
    public function productParams(Request $request): JsonResponse
    {
        $data = $request->validate([
            'uuid' => 'required|uuid|exists:App\Models\Catalog\Product,uuid',
            'key' => 'required',
            'value' => 'required'
        ]);

        $product = Product::find($data['uuid']);
        $product->{$data['key']} = $data['value'];
        $product->save();

        return $this->responseOk(__('messages.success.save', ['name' => __('catalog.product.columns.' . $data['key'])]));
    }
}
