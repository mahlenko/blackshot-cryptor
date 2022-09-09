<?php

namespace App\Http\Controllers\Administrator\Navigation\Items;

use Anita\Model;
use App\Http\Controllers\Controller;
use App\Models\Navigation\Navigation;
use App\Models\Navigation\NavigationItem;
use Illuminate\Contracts\Foundation\Application;
use Illuminate\Contracts\View\Factory;
use Illuminate\Contracts\View\View;
use Illuminate\Http\Request;
use Kalnoy\Nestedset\Collection;

/**
 * Просмотр списка ссылок навигационного меню
 * @package App\Http\Controllers\Administrator\Navigation\Items
 */
class Index extends Controller
{
    /**
     * @param string $uuid
     * @param string|null $parent_id
     * @return Application|Factory|View
     */
    public function index(string $uuid, string $parent_id = null)
    {
//        if ($parent_id) {
//            $navigation = NavigationItem::where(['uuid' => $parent_id])
//                ->with(['translations', 'meta'])
//                ->defaultOrder()
//                ->first();
//
//            $object_list = $navigation->children->sortBy('_lft');
////            dd($object_list);
//
//        } else {
//            $navigation = Navigation::with(['items', 'items.navigation', 'items.meta', 'items.translations'])
//                ->find($uuid);
//
//            $object_list = $navigation->items->toTree();
//        }
//
//        return view('administrator.navigation.items.index', [
//            'parent_id' => $parent_id,
//            'navigation' => $navigation,
//            'object_list' => $object_list,
//            'nested_view_name' => 'navigation-item',
//            'breadcrumbs_data' => [
//                'navigation' => $parent_id ? $navigation->navigation : $navigation,
//                'parent' => $parent_id ? $navigation : null,
//            ],
//            'routes' => [
//                'home' => '',
//                'create' => route('admin.navigation.items.edit', [
//                    'uuid' => $parent_id ? $navigation->navigation->uuid : $navigation->uuid,
//                    'locale' => app()->getLocale(),
//                    'parent_id' => $parent_id
//                ]),
//                'edit' => $parent_id ? 'admin.navigation.items.edit' : 'admin.navigation.edit',
//                'sortable' => '',
//                'delete' => 'admin.navigation.delete',
//            ],
//            'header' => [
//                'icon' => null,
//                'text' => $navigation->name,
//                'create' => __('navigation.items.add')
//            ]
//        ]);

        $parent_id = \Illuminate\Support\Facades\Request::input('parent_id');

        $navigation = Navigation::find($uuid);
        $items = NavigationItem::where([
            'parent_id' => $parent_id,
            'navigation_uuid' => $uuid
        ])
            ->with(['translations', 'meta'])
            ->defaultOrder()
            ->get();

//        dd($items);

        //        $items = NavigationItem::where([
//            'parent_id' => $parent_id,
//            'navigation_uuid' => $navigation->uuid
//          ])->withDepth()
//            ->having()
//            ->defaultOrder()
//            ->get();

//        $navigation = Navigation::with([
//            'items' => function($query) use ($parent_id) {
//                if ($parent_id) {
//                    $query->where(['parent_id' => $parent_id]);
//                }
//            }, 'items.navigation', 'items.meta', 'items.translations'])
//            ->find($uuid);

//        $object_list = $navigation->items->toTree();

        $object_list = $items->toTree();

        return view('administrator.navigation.items.index', [
            'navigation' => $navigation,
            'object_list' => $object_list,
            'meta_objects' => $this->getMetaObjects($object_list),
            'breadcrumbs_data' => [
                'navigation' => $navigation,
                'parent_id'  => $parent_id
            ],
        ]);
    }

    /**
     * @param Collection|null $collection
     * @return \Illuminate\Support\Collection|null
     */
    private function getMetaObjects(Collection $collection = null): ?\Illuminate\Support\Collection
    {
        if (!$collection) return null;

        $objects_types = [];
        foreach ($collection->pluck('meta') as $meta) {
            if (isset($meta->object_type)) {
                $objects_types[$meta->object_type][] = $meta->object_id;
            }
        }

        $objects = collect();
        /* @var Model $type */
        foreach($objects_types as $type => $ids) {
            $items = $type::withTranslation()->find($ids);
            if ($items->count()) {
                $objects->put($type, $items);
            }
        }

        return $objects;
    }
}
