<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class NavigationItemParamsRequest extends FormRequest
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
            'params.title'   => 'nullable',
            'params.style'   => 'nullable',
            'params.css'     => 'nullable',
            'params.icon'    => 'nullable|image',
            'params.iconCss' => 'nullable',
            'params.iconAlt' => 'nullable',
            'params.target'  => 'nullable|in:_self,_blank',
        ];
    }
}
