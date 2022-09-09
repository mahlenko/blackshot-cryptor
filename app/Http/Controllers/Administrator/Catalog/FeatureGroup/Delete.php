<?php

namespace App\Http\Controllers\Administrator\Catalog\FeatureGroup;

use App\Http\Controllers\Controller;
use App\Models\Catalog\FeatureGroup;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;

class Delete extends Controller
{
    /**
     * @param Request $request
     * @return RedirectResponse
     */
    public function index(Request $request): RedirectResponse
    {
        $data = $request->validate([
            'uuid' => 'required|uuid|exists:App\Models\Catalog\FeatureGroup,uuid'
        ]);

        $category = FeatureGroup::find($data['uuid']);
        $category_name = $category->name;

        if ($category->delete()) {
            flash(__('messages.success.delete', ['name' => $category_name]))->success();
        } else {
            flash(__('messages.fail.delete'))->danger();
        }

        return back();
    }
}
