<?php

namespace App\Http\Controllers\Administrator\Catalog\Product;

use App\Http\Controllers\Controller;
use App\Http\Requests\CatalogProductCategoriesRequest;
use App\Http\Requests\CatalogProductFeaturesRequest;
use App\Http\Requests\CatalogProductImagesRequest;
use App\Http\Requests\CatalogProductRequest;
use App\Http\Requests\MetaRequest;
use App\Http\Requests\ObjectLocaleRequest;
use App\Http\Requests\VideoRequest;
use App\Repositories\ProductRepository;
use App\Rules\ValidationFloat;
use Exception;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;

class Store extends Controller
{
    /**
     * @param ObjectLocaleRequest $objectLocaleRequest
     * @param CatalogProductRequest $productRequest
     * @param CatalogProductFeaturesRequest $featuresRequest
     * @param CatalogProductCategoriesRequest $categoriesRequest
     * @param CatalogProductImagesRequest $imagesRequest
     * @param MetaRequest $metaRequest
     * @param VideoRequest $videoRequest
     * @return RedirectResponse
     */
    public function index(
        ObjectLocaleRequest $objectLocaleRequest,
        CatalogProductRequest $productRequest,
        CatalogProductFeaturesRequest $featuresRequest,
        CatalogProductCategoriesRequest $categoriesRequest,
        CatalogProductImagesRequest $imagesRequest,
        MetaRequest $metaRequest,
        VideoRequest $videoRequest
    ): RedirectResponse
    {
        $object = $objectLocaleRequest->validated();
        $product = $productRequest->validated();
        $features = $featuresRequest->validated();
        $categories = $categoriesRequest->validated();
        $images = $imagesRequest->validated();
        $videos = $videoRequest->validated();
        $meta = $metaRequest->validated();

        $product = (new ProductRepository())->store(
            $object['uuid'],
            $object['locale'],
            $product,
            $categories['category'],
            $features['features'] ?? [],
            $images['images'] ?? [],
            $videos['video'] ?? [],
            $meta['meta']
        );

        flash(__('messages.success.save', ['name' => $product->name]))->success();

        return redirect()->route('admin.catalog.product.edit', $object);
    }
}
