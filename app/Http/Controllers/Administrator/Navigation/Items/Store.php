<?php

namespace App\Http\Controllers\Administrator\Navigation\Items;

use App\Http\Controllers\Controller;
use App\Http\Requests\NavigationItemParamsRequest;
use App\Http\Requests\NavigationItemRequest;
use App\Http\Requests\NestedRequest;
use App\Http\Requests\ObjectLocaleRequest;
use App\Models\Navigation\Navigation;
use App\Models\Navigation\NavigationItem;
use App\Repositories\NavigationItemRepository;
use App\Stores\NavigationItemStore;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;

class Store extends Controller
{
    public function index(
        ObjectLocaleRequest $objectLocaleRequest,
        NavigationItemRequest $itemRequest,
        NavigationItemParamsRequest $itemParamsRequest,
        NestedRequest $nestedRequest
    ): \Illuminate\Http\RedirectResponse
    {
        $object = $objectLocaleRequest->validated();
        $item = $itemRequest->validated();
        $itemParams = $itemParamsRequest->validated();
        $nested = $nestedRequest->validated();

        $item_store = (new NavigationItemRepository())->store(
            $object['uuid'],
            $object['locale'],
            $item,
            $itemParams['params'],
            $nested['nested']
        );

        flash(__('messages.success.save', ['name' => $item_store->name]))->success();

        return redirect()->route('admin.navigation.items.edit', [
            'locale' => $object['locale'],
            'uuid' => $item['navigation_uuid'],
            'navigation_item' => $object['uuid']
        ]);
    }
}
