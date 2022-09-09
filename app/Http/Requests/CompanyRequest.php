<?php

namespace App\Http\Requests;

use App\Repositories\CompanyRepository;
use App\Rules\LocaleRule;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class CompanyRequest extends FormRequest
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
            'name' => 'required',
            'type' => ['required', Rule::in(array_keys(CompanyRepository::types()))],
            'image' => 'nullable|image',
            'description' => 'nullable',
            'body' => 'nullable',
            'phone' => 'nullable|array',
            'email' => 'nullable|array',
            'website' => 'nullable|array',
            'address' => 'nullable|max:255',
            'timework' => 'nullable|max:255'
        ];
    }
}
