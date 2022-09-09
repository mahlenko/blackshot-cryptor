<?php

namespace App\Http\Controllers\Administrator\Catalog\Category;

use App\Helpers\Nested;
use App\Http\Controllers\Controller;
use App\Models\Catalog\Category;
use Illuminate\Http\Request;

class Sortable extends Controller
{
    /**
     * @param Request $request
     * @return array
     */
    public function index(Request $request)
    {
        $data = $request->validate([
            'uuid' => 'required|uuid',
            'amount' => 'required|numeric'
        ]);

        $object = Category::find($data['uuid']);

        $nested = new Nested(Category::class);
        $result_ok = $nested->sortable($data['uuid'], $data['amount']);

        return [
            'ok' => $result_ok,
            'message' => $result_ok
                ? __('sortable.message.success', ['name' => $object->name])
                : __('sortable.message.fail', ['name' => $object->name])
        ];
    }
}
