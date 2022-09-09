<?php

namespace App\Http\Controllers\Administrator\Catalog\Feature;

use App\Helpers\Nested;
use App\Http\Controllers\Controller;
use App\Models\Catalog\Category;
use App\Models\Catalog\Feature;
use App\Models\Catalog\FeatureGroup;
use App\Repositories\FeatureRepository;
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
     * @param string $locale
     * @param string|null $uuid
     * @return Application|Factory|View
     */
    public function index(string $locale, string $uuid = null)
    {
        $feature = $uuid
            ? Feature::with(['values', 'translations'])->findOrFail($uuid)
            : null;

        $validator = Validator::make(
            ['locale' => $locale],
            ['locale' => ['required', Rule::in(config('translatable.locales'))]]
        );

        if ($validator->fails()) {
            abort(404);
        }

        return view('administrator.catalog.feature.edit', [
            'locale' => $locale,
            'uuid' => $uuid,
            'feature' => $feature,
            'feature_groups' => FeatureGroup::all(),
            'purpose_list' => (new FeatureRepository())->getPurposes(),
            'nested_nodes' => (new Nested(Feature::class))->optGroup($locale, $feature->uuid ?? null),
            'tabs' => [
                [
                    'key' => 'general',
                    'name' => __('global.tabs.general'),
                    'template' => 'administrator.catalog.feature.tabs.general'
                ],
                [
                    'key' => 'variants',
                    'name' => '<i class="fas fa-list-ol me-1"></i>' . __('catalog.feature.tabs.variants'),
                    'template' => 'administrator.catalog.feature.tabs.variants'
                ],
                [
                    'key' => 'categories',
                    'name' => '<i class="far fa-folder me-1"></i>' . __('catalog.feature.tabs.categories'),
                    'template' => 'administrator.catalog.feature.tabs.categories'
                ],
                [
                    'key' => 'meta',
                    'name' => __('meta.tab_name'),
                    'template' => 'administrator.meta.edit',
                    'data' => [
                        'object' => $feature ?? null,
                        'object_type' => Feature::class,
                    ]
                ]
            ]
        ]);
    }

    /**
     * @param Feature|null $object
     * @return array
     */
//    private function chunks_template(Feature $object = null): array
//    {
//        return [
//            [
//                'icon' => '<i class="fas fa-cog me-1"></i>',
//                'key'  => 'general',
//                'name' => __('catalog.feature.tabs.general'),
//                'data' => [
//                    'purposes' => (new Feature())->getPurposes(),
//                    'groups' => (new Nested(FeatureGroup::class))->optGroup(app()->getLocale()),
//                    'parent_slug_text' => '/catalog/features' /* @todo настроить под роутинг */
//                ],
//                'template' => 'administrator.catalog.feature.general',
//            ],
//            [
//                'icon' => '<i class="fas fa-list-ol me-1"></i>',
//                'key'  => 'variants',
//                'name' => __('catalog.feature.tabs.variants'),
//                'template' => 'administrator.catalog.feature.variants',
//                'data' => ['parent' => $object],
//            ],
//            [
//                'icon' => '<i class="far fa-folder me-1"></i>',
//                'key'  => 'categories',
//                'name' => __('catalog.feature.tabs.categories'),
//                'template' => 'administrator.catalog.feature.categories',
//                'data' => ['parent' => $object],
//            ],
//            [
//                'icon' => '<i class="fas fa-chart-line me-1"></i>',
//                'key'  => __('meta.name'),
//                'name' => 'SEO',
//                'template' => 'administrator.meta.edit',
//                'data' => ['parent' => $object],
//            ],
//        ];
//    }
}
