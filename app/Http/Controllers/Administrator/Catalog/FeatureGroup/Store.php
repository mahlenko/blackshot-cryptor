<?php

namespace App\Http\Controllers\Administrator\Catalog\FeatureGroup;

use App\Http\Controllers\Controller;
use App\Http\Requests\CatalogFeatureGroupRequest;
use App\Http\Requests\NestedRequest;
use App\Http\Requests\ObjectLocaleRequest;
use App\Repositories\FeatureGroupRepository;
use App\Stores\FeatureGroupStore;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;

class Store extends Controller
{
    /**
     * @param Request $request
     * @param ObjectLocaleRequest $objectLocaleRequest
     * @param CatalogFeatureGroupRequest $featureGroupRequest
     * @return RedirectResponse
     */
    public function index(
        Request $request,
        ObjectLocaleRequest $objectLocaleRequest,
        CatalogFeatureGroupRequest $featureGroupRequest
    ): RedirectResponse
    {
        $object = $objectLocaleRequest->validated();
        $data = $featureGroupRequest->validated();

        $nested = $request->validate([
            'nested.position' => 'nullable',
            'nested.parent_id' => 'nullable|uuid'
        ]);

        $group = (new FeatureGroupRepository())->store(
            $object['uuid'],
            $object['locale'],
            $data,
            $nested['nested']
        );

        flash(__('messages.success.save', ['name' => $data['name']]))->success();
        return redirect()->route('admin.catalog.feature.group.edit', $object);
    }
}
