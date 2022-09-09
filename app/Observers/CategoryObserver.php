<?php

namespace App\Observers;

use App\Models\Catalog\Category;
use App\Models\Navigation\Navigation;
use App\Models\Navigation\NavigationItem;
use Illuminate\Support\Facades\Cache;

class CategoryObserver
{
    /**
     * @param Category $category
     */
    public function deleting(Category $category)
    {
        if ($category->icon) $category->icon->delete();
        if ($category->meta) $category->meta->delete();

        /* удалить ссылку из меню, если страница использовалась меню */
        foreach (NavigationItem::where(['meta_uuid' => $category->meta->uuid])->get() as $item) {
            $item->delete();
        }

        foreach ($category->descendants as $child) {
            $child->delete();
        }

        /* @todo сбрасывать кеш только у тех меню в которых есть товар */
        foreach (Navigation::all() as $navigation) {
            $navigation->clearCache();
        }
    }

    public function created()
    {
        /* @todo сбрасывать кеш только у тех меню в которых есть товар */
        foreach (Navigation::all() as $navigation) {
            $navigation->clearCache();
        }
    }

    public function updated()
    {
        /* @todo сбрасывать кеш только у тех меню в которых есть товар */
        foreach (Navigation::all() as $navigation) {
            $navigation->clearCache();
        }
    }
}
