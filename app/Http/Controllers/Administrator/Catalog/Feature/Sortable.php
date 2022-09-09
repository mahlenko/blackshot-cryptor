<?php

namespace App\Http\Controllers\Administrator\Catalog\Feature;

use App\Helpers\Nested;
use App\Http\Controllers\Controller;
use App\Models\Catalog\Feature;
use App\Models\Catalog\FeatureVariant;
use Illuminate\Http\Request;

/**
 * Сортировка хврактеристик в списке
 * @package App\Http\Controllers\Administrator\Catalog\Feature
 */
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

        $object = Feature::find($data['uuid']);

        $nested = new Nested(Feature::class);
        $result_ok = $nested->sortable($data['uuid'], $data['amount']);

        return [
            'ok' => $result_ok,
            'message' => $result_ok
                ? __('sortable.message.success', ['name' => $object->name])
                : __('sortable.message.fail', ['name' => $object->name])
        ];
    }

    /**
     * @param Request $request
     * @return array
     */
    public function variants(Request $request): array
    {
        $data = $request->validate([
            'uuid' => 'required|uuid',
            'amount' => 'required|numeric'
        ]);

        $object = FeatureVariant::find($data['uuid']);

        $nested = new Nested(FeatureVariant::class);
        $result_ok = $nested->sortable($data['uuid'], $data['amount']);

        return [
            'ok' => $result_ok,
            'message' => $result_ok
                ? __('sortable.message.success', ['name' => $object->name])
                : __('sortable.message.fail', ['name' => $object->name])
        ];
    }
}
