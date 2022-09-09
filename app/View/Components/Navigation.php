<?php

namespace App\View\Components;

use App\Models\Catalog\Category;
use App\Models\Catalog\Product;
use App\Models\Catalog\ProductCategory;
use App\Models\Finder\File;
use App\Models\Navigation\NavigationItem;
use Closure;
use Illuminate\Contracts\Foundation\Application;
use Illuminate\Contracts\View\Factory;
use Illuminate\Contracts\View\View;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\URL;
use Illuminate\View\Component;

class Navigation extends Component
{
    /**
     * @var string
     */
    private string $key;

    /**
     * Show title is view
     * @var bool
     */
    public bool $title = false;

    /**
     * Create a new component instance.
     *
     * @param $key
     * @param bool $title
     */
    public function __construct($key, bool $title = false)
    {
        $this->key = $key;
        $this->title = $title;
    }

    /**
     * Get the view / contents that represent the component.
     *
     * @return Application|Factory|View
     */
    public function render()
    {
        $navigation = \App\Models\Navigation\Navigation::where('key', $this->key)
            ->first();

        if (!$navigation) {
            return null;
        }

        if (isset($navigation->cached) && $navigation->cached) {
            $navigation_generation = Cache::rememberForever('navigation.'.$this->key.'.'. app()->getLocale(), function() {
                return \Anita\Entities\Navigation::generate($this->key);
            });
        } else {
            $navigation_generation = \Anita\Entities\Navigation::generate($this->key);
        }

        return view('web.components.navigation.' . $navigation->template, [
            'navigation' => $navigation_generation ?? []
        ]);
    }
}
