<?php

namespace App\Http\Controllers\Administrator\References\Country;

use App\Http\Controllers\Controller;
use App\Models\Country;
use Illuminate\Http\Request;
use Ramsey\Uuid\Uuid;

class Edit extends Controller
{
    public function index(string $locale, string $uuid = null)
    {
        return view('administrator.references.country.edit', [
            'locale' => $locale,
            'uuid' => $uuid ?? Uuid::uuid4(),
            'country' => Country::find($uuid),
            'tabs' => [
                [
                    'key' => 'general',
                    'name' => __('global.tabs.general'),
                    'template' => 'administrator.references.country.tabs.general'
                ],
            ]
        ]);
    }
}
