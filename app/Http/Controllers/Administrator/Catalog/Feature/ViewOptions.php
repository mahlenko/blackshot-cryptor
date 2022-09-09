<?php

namespace App\Http\Controllers\Administrator\Catalog\Feature;

use App\Http\Controllers\Controller;
use App\Models\Catalog\Feature;
use App\Repositories\FeatureRepository;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;

class ViewOptions extends Controller
{

    /**
     * Варианты отображения в товаре
     * @param Request $request
     * @return array
     */
    public function product(Request $request): array
    {
        $data = $request->validate([
            'value' => ['required', Rule::in(array_keys(FeatureRepository::OPTIONS_PRODUCT))]
        ]);

        $options = FeatureRepository::OPTIONS_PRODUCT[$data['value']];
        foreach ($options as $index => $value) {
            $options[$index]['name'] = __($value['name']);
        }

        return $options;
    }

    /**
     * Варианты отображения в фильтре
     * @param Request $request
     * @return array
     */
    public function filter(Request $request): array
    {
        $data = $request->validate([
            'value' => ['required', Rule::in(array_keys(FeatureRepository::OPTIONS_FILTER))],
            'purpose' => 'required'
        ]);

        $options = FeatureRepository::OPTIONS_FILTER[$data['value']];
        foreach ($options as $index => $value) {
            $options[$index]['name'] = __($value['name']);
        }

        return $data['purpose'] === 'describe' ? [] : $options;
    }
}
