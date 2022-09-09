<?php

// Home
use Diglactic\Breadcrumbs\Breadcrumbs;
use Illuminate\Support\Facades\Request;
use Illuminate\Support\Facades\URL;

Breadcrumbs::for('view', function($trail, array $data = [])
{
    if (isset($data['page'])) {
        $segments = Request::segments();
        if (!$data['page'] instanceof \App\Models\Page\Page) {
            $meta = \App\Models\Meta::where(['url' => '/', 'object_type' => \App\Models\Page\Page::class])->first();

            if ($meta->hasTranslation(app()->getLocale())) {
                $meta->setDefaultLocale(app()->getLocale());
            } else {
                $meta->setDefaultLocale(config('translatable.locale'));
            }

            if ($meta) {
                $trail->push($meta->object->name, URL::route('home'));
            }
        }

        /* определим открытый товар */
        if (key_exists(1, $segments) && $segments[1] == \App\Models\Catalog\Product::PREFIX) {
            $object_breadcrumbs = $data['page']->categories->first();
        } else {
            $object_breadcrumbs = $data['page'];
        }


        if ($object_breadcrumbs->hasTranslation(app()->getLocale())) {
            $object_breadcrumbs->setDefaultLocale(app()->getLocale());
        } else {
            $object_breadcrumbs->setDefaultLocale(config('translatable.locale'));
        }

        /* категория страницы */
        $cache_key = 'breadcrumbs.' . $object_breadcrumbs->uuid .'.'. app()->getLocale();
        $ancestors = \Illuminate\Support\Facades\Cache::rememberForever($cache_key, function() use ($object_breadcrumbs) {
            return $object_breadcrumbs->ancestors->load(['meta', 'translations'])->sortBy('_lft');
        });

        if ($ancestors->count()) {
            foreach ($ancestors as $ancestor) {
                if ($ancestor->meta->url == '/') {
                    $trail->push($ancestor->name, URL::route('home'));
                } else {
                    if ($ancestor->hasTranslation(app()->getLocale())) {
                        $ancestor->setDefaultLocale(app()->getLocale());
                    } else {
                        $ancestor->setDefaultLocale(config('translatable.locale'));
                    }

                    $trail->push($ancestor->name, URL::route('view', ['slug' => $ancestor->meta->url]));
                }
            }
        }

        /* открытая страница */
        $trail->push($data['page']->translateOrDefault(app()->getLocale())->name, URL::route('view', ['slug' => $data['meta']->url]));
    }
});

Breadcrumbs::for('search', function ($trail, array $data = []) {
    $trail->parent('view');
});

Breadcrumbs::for('catalog', function ($trail, array $data = []) {
    $trail->parent('view', $data);
});

Breadcrumbs::for('product', function ($trail, array $data = []) {
    $trail->parent('view', $data);
});
