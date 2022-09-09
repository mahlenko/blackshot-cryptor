<?php

namespace App\Http\Controllers\Administrator\Catalog\Feature;

use App\Http\Controllers\Controller;
use App\Models\Catalog\FeatureVariant;
use Illuminate\Contracts\Foundation\Application;
use Illuminate\Contracts\View\Factory;
use Illuminate\Contracts\View\View;
use Illuminate\Http\Request;

class Page extends Controller
{
    /**
     * @param Request $request
     * @param string $locale
     * @param string $uuid
     * @return Application|Factory|View
     */
    public function index(Request $request, string $locale, string $uuid)
    {
        $object = FeatureVariant::where(['uuid' => $uuid])->first();

        if (!$object || $object->feature->purpose !== 'organize_catalog') {
            abort(404);
        }

        return view($request->ajax() ? 'administrator.catalog.feature.page-edit-popup' : 'administrator.content.edit', [
            /* popup */
            'is_ajax' => $request->ajax(),
            'title' => $object->name,
            'description' => 'Настройте персональную страницу значения характеристики',
            'max_width' => 1000,

            /* general */
            'uuid' => $uuid,
            'locale' => $locale,
            'object' => $object,
            'back' => [
                'route' => route('admin.catalog.feature.edit', ['locale' => $locale, 'uuid' => $object->feature->uuid])
            ],
            'chunks_template' => $this->chunks_template($object),
            'routes' => [
                'store' => 'admin.catalog.feature.page.store',
                'edit' => 'admin.catalog.feature.page.edit'
            ],
            'breadcrumbs_data' => [
                'object' => $object,
                'locale' => $locale
            ],
            'switcher_data' => ['uuid' => $uuid]
        ]);
    }

    /**
     * @return array[]
     */
    private function chunks_template($object)
    {
        return [
            [
                'icon' => '<i class="fas fa-cog me-1"></i>',
                'key' => 'general_object',
                'name' => __('page.data.general'),
                'template' => 'administrator.catalog.feature.variant.general',
                'data' => []
            ],
            [
                'icon' => '<i class="fas fa-chart-line me-1"></i>',
                'key'  => 'seo_variant',
                'name' => __('meta.name'),
                'template' => 'administrator.meta.edit',
                'data' => [
                    'parent' => $object,
                    'class_type' => FeatureVariant::class,
                ],
            ],
        ];
    }
}
