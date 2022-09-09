<?php

namespace App\Http\Controllers\Administrator\Catalog\Product;

use App\Http\Controllers\Controller;
use App\Models\Catalog\Category;
use App\Models\Catalog\Product;
use App\Repositories\FeatureRepository;
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
        $product = $uuid
            ? Product::findOrFail($uuid)
            : null;

        $set_category_list = null;
        if (\Illuminate\Support\Facades\Request::has('category_uuid')) {
            $set_category_list = Category::where([
                'uuid' => \Illuminate\Support\Facades\Request::input('category_uuid')
            ])->get();
        }

        if (old('category')) {
            $set_category_list = Category::where(['uuid' => old('category')])->get();
        }

        $category_uuid = null;
        if ($product && $product->categories) {
            $category_uuid = $product->categories->first()->uuid;
        }

        $withoutCategories = (new FeatureRepository())->findWithoutCategories($category_uuid);

        if ($withoutCategories) {
            $withoutCategories = $withoutCategories->groupBy('feature_group_uuid');
        }

        return view('administrator.catalog.product.edit', [
            'uuid' => $uuid,
            'locale' => $locale,
            'product' => $product,
            'group' => $product ? $product->group : null,
            'old_category' => $set_category_list,
            'withoutCategories' => $withoutCategories,
            'tabs' => [
                [
                    'key' => 'general',
                    'name' => __('global.tabs.general'),
                    'template' => 'administrator.catalog.product.tabs.general'
                ],
                [
                    'key' => 'meta',
                    'name' => __('meta.tab_name'),
                    'template' => 'administrator.meta.edit',
                    'data' => [
                        'object' => $product ?? null,
                        'object_type' => Product::class,
                        'default_template' => 'web.catalog.product'
                    ]
                ],
                [
                    'key' => 'variants',
                    'name' => '<i class="fas fa-layer-group me-1"></i>' . __('catalog.product.variants'),
                    'template' => 'administrator.catalog.product.tabs.variation',
                ],
                [
                    'key' => 'features',
                    'name' => '<i class="fas fa-list-ol me-1"></i>' . __('catalog.feature.title'),
                    'template' => 'administrator.catalog.product.tabs.feature'
                ],
                [
                    'key' => 'videos',
                    'name' => __('video.title'),
                    'template' => 'administrator.video.items',
                    'data' => [
                        'videos' => $product->videos ?? null,
                        'object' => $product
                    ]
                ],
            ]
        ]);
    }
}
