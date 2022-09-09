<?php

namespace App\Http\Controllers\Administrator\Catalog\Category;

use App\Http\Controllers\Controller;
use App\Http\Requests\CatalogCategoryRequest;
use App\Http\Requests\MetaRequest;
use App\Http\Requests\NestedRequest;
use App\Http\Requests\ObjectLocaleRequest;
use App\Models\Catalog\Category;
use App\Repositories\CategoryRepository;
use App\Stores\CatalogCategoryStore;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;

/**
 * Class Store
 * @package App\Http\Controllers\Administrator\Catalog\Category
 */
class Store extends Controller
{
    /**
     * @param ObjectLocaleRequest $objectLocaleRequest
     * @param CatalogCategoryRequest $categoryRequest
     * @param NestedRequest $nestedRequest
     * @param MetaRequest $metaRequest
     * @return RedirectResponse
     */
    public function index(
        ObjectLocaleRequest $objectLocaleRequest,
        CatalogCategoryRequest $categoryRequest,
        NestedRequest $nestedRequest,
        MetaRequest $metaRequest
    ): RedirectResponse
    {
        $object = $objectLocaleRequest->validated();
        $category_data = $categoryRequest->validated();
        $nested = $nestedRequest->validated();
        $meta = $metaRequest->validated();

        $category = (new CategoryRepository())->save(
            $object['uuid'],
            $object['locale'],
            $category_data,
            $nested['nested'],
            $meta['meta']
        );

        flash(__('messages.success.save', ['name' => $category->name]))->success();
        return redirect()->route('admin.catalog.category.edit', $object);
    }
}
