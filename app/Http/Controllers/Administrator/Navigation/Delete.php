<?php

namespace App\Http\Controllers\Administrator\Navigation;

use App\Http\Controllers\Controller;
use App\Models\Navigation\Navigation;
use Illuminate\Http\Request;

class Delete extends Controller
{
    public function index(Request $request)
    {
        $data = $request->validate([
            'uuid' => 'required|uuid|exists:App\Models\Navigation\Navigation,uuid'
        ]);

        $navigation = Navigation::findOrFail($data['uuid']);
        $navigation_array = $navigation->toArray();

        if ($navigation->delete()) {
            flash(__('messages.success.delete', ['name' => $navigation_array['name']]))->success();
        } else {
            flash(__('messages.fail.delete'))->error();
        }

        return redirect()->route('admin.navigation.home');
    }
}
