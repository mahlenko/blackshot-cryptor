<?php

namespace App\Http\Requests;

use App\Repositories\FeatureRepository;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class CatalogFeatureRequest extends FormRequest
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
            'is_active' => 'nullable',
            'name' => 'required',
            'feature_group_uuid' => 'nullable|uuid',
            'purpose' => ['required', Rule::in(array_keys((new FeatureRepository())->getPurposes()))],
            'view_product' => 'required',
            'view_filter' => 'nullable',
            'is_show_feature' => 'nullable',
            'is_show_description' => 'nullable',
            'description' => 'nullable',
            'prefix' => 'nullable',
            'postfix' => 'nullable',
        ];
    }
}
