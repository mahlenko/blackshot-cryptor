<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class MetaRequest extends FormRequest
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
            'meta.is_active' => 'nullable|boolean',
            'meta.show_nested' => 'nullable|boolean',
            'meta.publish_at' => 'nullable|date|max:255',
            'meta.slug' => 'nullable|max:255',
            'meta.redirect' => 'nullable|url|max:255',
            'meta.views' => 'nullable|integer',
            'meta.title' => 'nullable',
            'meta.description' => 'nullable',
            'meta.keywords' => 'nullable',
            'meta.heading_h1' => 'nullable',
            'meta.robots' => 'nullable|max:255',
            'meta.template' => 'required|max:255',
        ];
    }
}
