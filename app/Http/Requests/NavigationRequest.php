<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class NavigationRequest extends FormRequest
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
            'uuid' => 'nullable|uuid',
            'name' => 'required|string',
            'key' => 'nullable|string',
            'cached' => 'nullable|boolean',
            'description' => 'nullable|string',
            'template' => 'required|string',
        ];
    }
}
