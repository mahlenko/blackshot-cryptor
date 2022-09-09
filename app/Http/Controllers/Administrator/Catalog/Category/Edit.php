<?php

namespace App\Http\Controllers\Administrator\Catalog\Category;

use App\Helpers\Nested;
use App\Http\Controllers\Controller;
use App\Models\Catalog\Category;
use Illuminate\Contracts\Foundation\Application;
use Illuminate\Contracts\View\Factory;
use Illuminate\Contracts\View\View;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use Illuminate\Validation\Rule;
use Ramsey\Uuid\Uuid;

class Edit extends Controller
{
    /**
     * @param Request $request
     * @param string $locale
     * @param string|null $uuid
     * @return Application|Factory|View
     */
    public function index(Request $request, string $locale, string $uuid = null)
    {
        $category = $uuid
            ? Category::findOrFail($uuid)
            : null;

        $parent_category = $request->has('parent_uuid')
            ? Category::findOrFail($request->input('parent_uuid'))
            : null;

        $validator = Validator::make(
            ['locale' => $locale],
            ['locale' => ['required', Rule::in(config('translatable.locales'))]]
        );

        if ($validator->fails()) {
            abort(404);
        }

        return view('administrator.catalog.category.edit', [
            'locale' => $locale,
            'category' => $category,
            'parent_category' => $parent_category,
            'nested_nodes' => (new Nested(Category::class))->optGroup(app()->getLocale(), $category->uuid ?? null),
            'tabs' => [
                [
                    'key' => 'general',
                    'name' => __('global.tabs.general'),
                    'template' => 'administrator.catalog.category.tabs.general'
                ],
                [
                    'key' => 'meta',
                    'name' => __('meta.tab_name'),
                    'template' => 'administrator.meta.edit',
                    'data' => [
                        'object' => $category ?? null,
                        'object_type' => Category::class,
                        'default_template' => 'web.catalog.category'
                    ]
                ]
            ]
        ]);
    }
}
