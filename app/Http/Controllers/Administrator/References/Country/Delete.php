<?php

namespace App\Http\Controllers\Administrator\References\Country;

use App\Http\Controllers\Controller;
use App\Models\Country;
use Illuminate\Http\Request;

class Delete extends Controller
{
    public function index(Request $request)
    {
        $data = $request->validate([
            'uuid' => 'required|uuid'
        ]);

        $country = Country::findOrFail($data['uuid']);
        $country_arr = $country->toArray();

        if ($country->delete()) {
            flash(__('messages.success.delete', ['name' => $country_arr['name']]))->success();
        } else {
            flash(__('messages.fail.delete'))->error();
        }

        return back();
    }
}
