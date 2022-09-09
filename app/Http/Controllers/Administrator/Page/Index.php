<?php

namespace App\Http\Controllers\Administrator\Page;

use App\Http\Controllers\Controller;
use App\Models\Page\Page;
use Illuminate\Contracts\Foundation\Application;
use Illuminate\Contracts\View\Factory;
use Illuminate\Contracts\View\View;

class Index extends Controller
{
    /**
     * @param string|null $uuid
     * @return Application|Factory|View
     */
    public function index(string $uuid = null)
    {
        if (!$uuid) {
//            $root = Page::where(['parent_id' => null])->first();
            $root = Page::where(['_lft' => 1])->first();
            $uuid = $root->uuid;
        }

        $object_list = Page::where(['parent_id' => $uuid])
            ->with(['meta', 'meta.translations', 'children', 'translations'])
            ->defaultOrder()
            ->paginate();

        return view('administrator.content.index', [
            'object' => $object = Page::where(['uuid' => $uuid])->with(['meta'])->first(),
            'object_list' => $object_list,
            'pagination' => $object_list->links(),
            'breadcrumbs_data' => [
                'object' => $object
            ],
            'routes' => [
                'home' => 'admin.page.home',
                'edit' => 'admin.page.edit',
                'delete' => 'admin.page.delete',
                'sortable' => route('admin.page.sortable'),
                'create' => route('admin.page.edit', [
                    'locale' => config('app.locale'),
                    'parent_id' => $uuid]
                )
            ],
            'header' => [
                'icon' => null,
                'title' => $object ? $object->name : __('page.title'),
                'create' => __('page.create')
            ],
        ]);
    }
}
