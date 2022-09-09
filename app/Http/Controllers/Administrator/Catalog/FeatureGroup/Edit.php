<?php

namespace App\Http\Controllers\Administrator\Catalog\FeatureGroup;

use App\Helpers\Nested;
use App\Http\Controllers\Controller;
use App\Models\Catalog\Feature;
use App\Models\Catalog\FeatureGroup;
use Illuminate\Contracts\Foundation\Application;
use Illuminate\Contracts\View\Factory;
use Illuminate\Contracts\View\View;
use Illuminate\Http\Request;
use Ramsey\Uuid\Uuid;

class Edit extends Controller
{
    /**
     * @param string $locale
     * @param string|null $uuid
     * @return Application|Factory|View
     */
    public function index(string $locale, string $uuid = null)
    {
        return view('administrator.catalog.feature-group.edit', [
            'locale' => $locale,
            'group' => $uuid ? FeatureGroup::findOrFail($uuid) : null,
            'nested_nodes' => (new Nested(FeatureGroup::class))->optGroup(app()->getLocale(), $uuid),
            'tabs' => [
                [
                    'key' => 'general',
                    'name' => __('global.tabs.general'),
                    'template' => 'administrator.catalog.feature-group.tabs.general'
                ],
            ],
        ]);
    }
}
