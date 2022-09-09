<?php

namespace App\Observers;

use App\Models\Catalog\CategoryTranslation;
use Illuminate\Support\Facades\Cache;

class CategoryTranslateObserver
{
    /**
     * @param $category
     */
    public function created($category)
    {
        $this->deleteCache();
    }

    /**
     * @param $category
     */
    public function updated($category)
    {
        $this->deleteCache();
    }

    /**
     * @param $category
     */
    public function deleted($category)
    {
        $this->deleteCache();
    }

    /**
     * Удалит закешированное меню
     */
    private function deleteCache()
    {
        $cache_key = 'navigation.catalog.category';
        if (Cache::has($cache_key)) {
            Cache::forget($cache_key);
        }
    }
}
