<?php

namespace App\Http\Controllers\Administrator\Catalog\Feature;

use App\Http\Controllers\Controller;
use App\Http\Requests\CatalogFeatureVariantRequest;
use App\Http\Requests\CatalogFeatureViriantsRequest;
use App\Http\Requests\MetaRequest;
use App\Http\Requests\ObjectLocaleRequest;
use App\Models\Catalog\FeatureVariant;
use App\Repositories\FeatureVariantRepository;
use App\Stores\FeatureVariantPageStore;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;

class PageStore extends Controller
{
    /**
     * Сохранение страницы значения характеристики
     * @param ObjectLocaleRequest $objectLocaleRequest
     * @param CatalogFeatureVariantRequest $featureVariantRequest
     * @param MetaRequest $metaRequest
     * @return array|RedirectResponse
     */
    public function index(
        ObjectLocaleRequest $objectLocaleRequest,
        CatalogFeatureVariantRequest $featureVariantRequest,
        MetaRequest $metaRequest
    )
    {
        $object = $objectLocaleRequest->validated();
        $data = $featureVariantRequest->validated();
        $meta = $metaRequest->validated();

        /* сохраняем страницу значения */
        $store = (new FeatureVariantRepository())->store(
            $object['uuid'],
            $object['locale'],
            $data,
            $meta['meta']
        );

        if (\Illuminate\Support\Facades\Request::ajax()) {
            return [
                'ok' => $store ? true : false,
                'message' => $store
                    ? __('messages.success.save', ['name' => $store->name])
                    : __('messages.fail.save')
            ];
        }

        if ($store) {
            flash(__('messages.success.save', ['name' => $store->name]))->success();
            return redirect()->route('admin.catalog.feature.edit', $object);
        }

        flash(__('messages.fail.save'))->error();
        return back()->withInput(array_merge($data, $meta));
    }
}
