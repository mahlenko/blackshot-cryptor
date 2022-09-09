<?php

namespace App\Http\Controllers\Administrator\Catalog\FeatureGroup;

use App\Helpers\Nested;
use App\Http\Controllers\Controller;
use App\Models\Catalog\FeatureGroup;
use Illuminate\Http\Request;

class Sortable extends Controller
{
    /**
     * @param Request $request
     * @return array
     */
    public function index(Request $request): array
    {
        $data = $request->validate([
            'uuid' => 'required|uuid',
            'amount' => 'required|numeric'
        ]);

        $object = FeatureGroup::find($data['uuid']);

        $nested = new Nested(FeatureGroup::class);
        $result_ok = $nested->sortable($data['uuid'], $data['amount']);

        return [
            'ok' => $result_ok,
            'message' => $result_ok
                ? __('sortable.message.success', ['name' => $object->name])
                : __('sortable.message.fail', ['name' => $object->name])
        ];
    }
}
