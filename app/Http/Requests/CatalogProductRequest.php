<?php

namespace App\Http\Requests;

use App\Rules\ValidationFloat;
use Illuminate\Foundation\Http\FormRequest;

class CatalogProductRequest extends FormRequest
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
            'name' => 'required|max:255',
            'price' => ['min:0', new ValidationFloat],
            'description' => 'nullable',
            'body' => 'nullable',
            'product_code' => 'nullable:max:64',
            'url' => 'nullable|url',
            'quantity' => 'integer',
            'weight' => ['nullable', 'min:0', new ValidationFloat],
            'length' => 'nullable|min:0,integer',
            'width' => 'nullable|min:0,integer',
            'height' => 'nullable|min:0,integer',
            'min_qty' => 'nullable|min:0,integer',
            'max_qty' => 'nullable|integer',
            'step_qty' => 'nullable|integer',
            'age_limit' => 'integer|min:0',
            'age_verification' => 'nullable',
            'views' => 'nullable|integer',
            'popular' => 'nullable|integer',
        ];
    }
}
