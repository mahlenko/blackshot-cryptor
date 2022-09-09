<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class VideoRequest extends FormRequest
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
            'video' => 'nullable|array',
            'video.*' => 'array',
            'video.*.type' => 'nullable',
            'video.*.url' => 'nullable',
            'video.*.name' => 'nullable',
            'video.*.description' => 'nullable',
            'video.*.thumbnail_url' => 'nullable',
            'video.*.width' => 'nullable|integer',
            'video.*.width_unit' => ['nullable', Rule::in(['px', 'rem', 'em', '%'])],
            'video.*.height' => 'nullable|integer',
            'video.*.height_unit' => ['nullable', Rule::in(['px', 'rem', 'em', '%'])],
            'video.*.duration' => 'nullable|integer',
        ];
    }
}
