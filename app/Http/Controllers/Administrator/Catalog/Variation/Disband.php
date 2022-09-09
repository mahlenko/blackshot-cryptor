<?php

namespace App\Http\Controllers\Administrator\Catalog\Variation;

use App\Http\Controllers\Controller;
use App\Models\Catalog\ProductVariationGroup;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;

/**
 * Распустить группу товаров
 * @package App\Http\Controllers\Administrator\Catalog\Variation
 */
class Disband extends Controller
{
    /**
     * @param Request $request
     * @return RedirectResponse
     */
    public function index(Request $request): RedirectResponse
    {
        $data = $request->validate([
            'uuid' => 'required|uuid|exists:App\Models\Catalog\ProductVariationGroup,uuid'
        ]);

        $group = ProductVariationGroup::find($data['uuid']);
        $code = $group->code;

        if ($group->delete()) {
            flash(__('messages.success.delete', ['name' => $code]))->success();
        } else {
            flash(__('messages.fail.delete'))->error();
        }

        return back();
    }
}
