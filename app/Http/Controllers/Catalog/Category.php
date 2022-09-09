<?php

namespace App\Http\Controllers\Catalog;

use App\Http\Controllers\Controller;
use App\Http\Controllers\Page;
use App\Models\Catalog\Feature;
use App\Repositories\CategoryRepository;
use Exception;
use Illuminate\Contracts\Foundation\Application;
use Illuminate\Contracts\View\Factory;
use Illuminate\Contracts\View\View;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;

class Category extends Controller
{
    /**
     * @param Request $request
     * @param string $slug
     * @return Application|Factory|View
     * @throws Exception
     */
    public function index(Request $request, string $slug)
    {
        $page = Page::getPageData($slug);
        if (!$page) abort(404);

        $products = (new CategoryRepository())->viewFilterProducts($page['data'], $request->filter, $request->products);

        $category_root = $page['data']->parent ?? $page['data'];

        $categories = \App\Models\Catalog\Category::with(['meta', 'translations'])
            ->descendantsOf($category_root->uuid)
            ->filter(function($category) {
                return $category->hasTranslation(app()->getLocale());
            });

//        dd($categories->toTree());

        return view($page['meta']->template, [
            'meta' => $page['meta'],
            'page' => $page['data'],
            'page_breadcrumbs' => $page['data'],
            'category_root' => $category_root,
            'categories' => $categories ? $categories->toTree() : collect(),
            'filter' => [
                'features' => $request->filter,
                'products' => $request->products
            ],
            'products' => $products['items'] ?? collect(),
            'pagination_links' => $products['links'],
            'price' => [
                0,
                intval(\App\Models\Catalog\Product::max('price'))
            ]
        ]);
    }

    /**
     * @param Request $request
     * @return RedirectResponse
     */
    public function sortable(Request $request): RedirectResponse
    {
        $request->validate([
            'sortable' => 'required'
        ]);

        (new CategoryRepository())->setUserSortable($request->sortable);

        return back();
    }

    /**
     * Количество товаров с выбранным фильтром
     * @param Request $request
     * @return array
     * @throws Exception
     */
    public function countProducts(Request $request): array
    {
        $request->validate([
            'slug' => 'required',
            'products' => 'required|array',
            'filter' => 'nullable|array',
        ]);

        $page = Page::getPageData($request->slug);
        if (!$page) abort(404);

        /* */
        $products = (new CategoryRepository())->viewFilterProducts($page['data'], $request->filter, $request->products);

        return [
            'ok' => true,
            'label' => !$products->count()
                ? __('website.filter_no_products')
                : trans_choice(__('website.product_choice', ['count' => $products->count()]), $products->count()),
            'count' => $products->count()
        ];
    }

}
