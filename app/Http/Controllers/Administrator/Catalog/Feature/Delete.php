<?php

namespace App\Http\Controllers\Administrator\Catalog\Feature;

use App\Http\Controllers\Controller;
use App\Models\Catalog\Feature;
use Illuminate\Http\Request;

class Delete extends Controller
{
    /**
     * @param Request $request
     * @return \Illuminate\Http\RedirectResponse
     */
    public function index(Request $request)
    {
        $data = $request->validate([
            'uuid' => 'required|uuid|exists:App\Models\Catalog\Feature,uuid'
        ]);

        $item = Feature::where(['uuid' => $data['uuid']])->first();
        $item_arr = $item->toArray();

        if ($item->delete()) {
            flash(__('messages.success.delete', ['name' => $item_arr['name']]))->success();
        } else {
            flash(__('messages.fail.delete'))->danger();
        }

        return redirect()->route('admin.catalog.feature.home');
    }
}
