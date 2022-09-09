<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class CatalogFeatureVariantRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     *
     * @return bool
     */
    public function authorize()
    {
        return true;
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules()
    {
        return [
            'name' => 'nullable',
            'color' => 'nullable|starts_with:#',
            'feature_uuid' => 'required|uuid',
            'description' => 'nullable',
            'url' => 'nullable|url',
            'body' => 'nullable',
            'icon' => 'nullable|image'
        ];
    }
}
