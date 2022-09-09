<?php

namespace App\Http\Controllers\Administrator\Catalog\Feature;

use App\Http\Controllers\Controller;
use App\Http\Requests\CatalogFeatureCategoryRequest;
use App\Http\Requests\CatalogFeatureRequest;
use App\Http\Requests\CatalogFeatureVariantRequest;
use App\Http\Requests\MetaRequest;
use App\Http\Requests\ObjectLocaleRequest;
use App\Repositories\FeatureRepository;
use Illuminate\Http\Request;

class Store extends Controller
{
    /**
     * @param Request $request
     * @param ObjectLocaleRequest $objectLocaleRequest
     * @param CatalogFeatureRequest $featureRequest
     * @param CatalogFeatureCategoryRequest $categoryRequest
     * @param MetaRequest $metaRequest
     * @return \Illuminate\Http\RedirectResponse
     */
    public function index(
        Request $request,
        ObjectLocaleRequest $objectLocaleRequest,
        CatalogFeatureRequest $featureRequest,
        CatalogFeatureCategoryRequest $categoryRequest,
        MetaRequest $metaRequest
    )
    {
        $object = $objectLocaleRequest->validated();
        $data = $featureRequest->validated();
        $variants = $request->validate([
            'feature_variants' => 'nullable|array'
        ]);
        $category = $categoryRequest->validated();
        $meta = $metaRequest->validated();
        $nested = $request->validate([
            'nested.position' => 'nullable',
            'nested.parent_id' => 'nullable|uuid'
        ]);

        $feature = (new FeatureRepository())->store(
            $object['uuid'],
            $object['locale'],
            $data,
            $variants['feature_variants'] ?? [],
            $category['category'] ?? [],
            $nested['nested'],
            $meta['meta']
        );

        flash(__('messages.success.save', ['name' => $feature->name]))->success();
        return redirect()->route('admin.catalog.feature.edit', $object);
    }
}
