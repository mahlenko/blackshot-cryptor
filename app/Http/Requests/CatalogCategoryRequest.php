<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class CatalogCategoryRequest extends FormRequest
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
            'preview' => 'nullable|image',
            'description' => 'nullable|max:255',
            'slug' => 'nullable',
            'icon' => 'nullable|image',
            'name' => 'required',
            'body' => 'nullable',
        ];
    }
}
