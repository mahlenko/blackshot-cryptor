<?php

namespace App\Models;

use Anita\Traits\TranslateField;
use App\Models\Catalog\Category;
use App\Models\Catalog\Product;
use App\Models\Navigation\Navigation;
use App\Models\Navigation\NavigationItem;
use App\Repositories\SettingRepository;
use App\Repositories\WidgetRepository;
use Astrotomic\Translatable\Contracts\Translatable as TranslatableContract;
use Astrotomic\Translatable\Translatable;
use DateTimeImmutable;
use Exception;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\URL;
use Illuminate\Support\Str;
use Ramsey\Uuid\Uuid;

class Meta extends Model implements TranslatableContract
{
    use HasFactory, Translatable, TranslateField;

    /**
     * @var string
     */
    protected $primaryKey = 'uuid';

    /**
     * @var bool
     */
    public $incrementing = false;

    /**
     * @var array
     */
    public $fillable = ['slug', 'url', 'redirect', 'object_type', 'robots', 'views', 'is_active', 'template', 'publish_at', 'show_nested'];

    /**
     * @var string[]
     */
    public $translatedAttributes = ['title', 'description', 'keywords', 'heading_h1'];

    /**
     * @var string
     */
    protected $keyType = 'string';

    /**
     * @var array
     */
//    protected $with = ['translations'];

    /**
     * Разделит ссылку на сегменты, чтобы достать Slug страницы
     */
    public function urlSegments(): Collection
    {
        return collect(explode('/', $this->url))->filter();
    }

    /**
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function object()
    {
        return $this->belongsTo($this->object_type, 'object_id', (new $this->object_type)->getKey())
            ->withDepth();
    }

    /**
     * Форматирование даты
     */
    public function setPublishAtAttribute($value)
    {
        $this->attributes['publish_at'] = new DateTimeImmutable($value ?? 'now');
    }

    /**
     * Сгенерирует html теги
     * @return string
     * @throws Exception
     */
    public function generate():string
    {
        $data = [
            '<title>'. ($this->translateOrDefault(app()->getLocale())->title ?? '') .'</title>',
            '<meta name="robots" content="'. $this->robots .'"/>',
            '<meta name="description" content="'. ($this->translateOrDefault(app()->getLocale())->description ?? '') .'"/>',
            '<meta name="keywords" content="'. ($this->translateOrDefault(app()->getLocale())->keywords ?? '') .'"/>',
            '<meta name="generator" content="'. env('APP_NAME') .'"/>',
        ];

        /* settings */
        $setting = (new SettingRepository())->all('website');

        /* canonical */
        $data[] = '<link rel="canonical" href="'. $this->canonical() .'">';

        $hreflangs = $this->hreflangs();
        if ($hreflangs->count()) {
            foreach ($hreflangs as $key => $hreflang) {
                $data[] = '<link rel="alternate" hreflang="'. $key .'" href="'. $hreflang .'">';
            }
        }

        /* favicon */
        if ($setting && key_exists('favicon', $setting) && $setting['favicon']) {
            $favicon_file = Storage::url($setting['favicon']->thumbnail());

            if (Storage::path($favicon_file)) {
                $size = getimagesize(Storage::path($setting['favicon']->thumbnail()));
                $data[] = '<link rel="icon" type="' . $size['mime'] . '" href="' . $favicon_file . '"/>';
                $data[] = '<link rel="apple-touch-icon" sizes="' . $size[0] . 'x' . $size[1] . '" href="' . $favicon_file . '"/>';
            }
        }

        return implode("\n\t", $data);
    }

    /**
     * @return string
     */
    public function canonical(): string
    {
        if (empty($this->url)) return '';

        switch($this->object_type) {
            case Category::class:
                return route('catalog', ['catalog' => $this->url]);

            case Product::class:
                return route('product', ['product' => $this->url]);

            default:
                return route('view', ['slug' => $this->url]);
        }
    }

    /**
     * @return Collection
     */
    public function hreflangs(): Collection
    {
        $hreflangs = collect();
        foreach (config('translatable.locales') as $locale) {
            if ($locale == 'ua') $locale = 'uk'; // @todo для greensystem

            $locale_key = $locale;
            if ($locale == config('translatable.locale')) $locale = '';

            $segments = array_values(array_filter(explode('/', $this->url)));
            array_unshift($segments, $locale);

            $hreflangs->put($locale_key, URL::to(implode('/', $segments)));
        }

        return $hreflangs;
    }

    /**
     * Сгенерирует ссылку на страницу
     */
    public function generateUrl()
    {
        $url_segments = $this->object_type::PREFIX
            ? collect(explode('/', $this->object_type::PREFIX))
            : collect();

        $ancestors = $this->object_type::with('meta')
            ->ancestorsOf($this->object_id)
            ->sortBy('_lft');

        if ($ancestors->count()) {
            foreach ($ancestors as $ancestor) {
                $url_segments->push($ancestor->meta->slug);
            }
        }

        $url_segments->push($this->slug)->filter(function($segment) {
            return $segment !== '/';
        })->unique();

        $this->attributes['url'] = trim($url_segments->join('/'), '/') ?: '/';

        $is_duplicate = Meta::where('url', $this->attributes['url'])
            ->where('object_id', '<>', $this->object_id)
            ->count() > 0;

        if ($is_duplicate) {
            $this->attributes['slug'] .= '-'. Str::slug(Str::random(4));
            $this->generateUrl();
        }
    }

    /**
     * Обновит дочерние ссылки
     */
    public function descendantsUpdateUrl()
    {
        /* Не обновляем если ссылка не изменилась */
        if ($this->getOriginal('url') == $this->url) {
            return;
        }

        $descendants = $this->object_type::with('meta')
            ->withDepth()
            ->having('depth', '=', $this->object->depth + 1)
            ->descendantsOf($this->object_id);

        if (!$descendants->count()) {
            return;
        }

        foreach ($descendants->pluck('meta') as $meta) {
            $meta->generateUrl();
            $meta->save();
        }
    }

    /**
     * Обновит ссылку в меню
     */
    public function updateNavigationUrl()
    {
        /* Не обновляем если ссылка не изменилась */
        if ($this->getOriginal('url') == $this->url) {
            return;
        }

        $navigation_items = NavigationItem::with('navigation')
            ->where(['meta_uuid' => $this->uuid]);

        if (!$navigation_items->count()) {
            return;
        }

        foreach ($navigation_items->get() as $item) {
            $item->url = $this->url;
            $item->save();
        }

        /* сбросить кеширование меню */
        $this->clearCacheNavigation($navigation_items->get()->pluck('navigation'));
    }

    /**
     * Сбросить кеширование если изменен внутренний элемент
     * ссылки с автогенерацией
     */
    public function updateNavigationAutoItems()
    {
        // родители
        $ancestors = $this->object_type::with('meta')->ancestorsOf($this->object_id);
        if ($ancestors) {
            $items = NavigationItem::whereIn('meta_uuid', $ancestors->pluck('meta.uuid'))
                ->where('generate_catalog', 1)
                ->with('navigation')
                ->get();

            $this->clearCacheNavigation($items->pluck('navigation'));
        }
    }

    /**
     * Сбросит закешированное меню в котором находится ссылка
     */
    public function clearCacheNavigation(Collection $navigations)
    {
        $navigation_uniques = $navigations->unique('key');
        foreach ($navigation_uniques as $navigation) {
            Navigation::forgetCache($navigation->key);
        }
    }

    /**
     * Сбросит закешированный виджет в котором находится ссылка
     */
    public function clearCacheWidget()
    {
        $types = (new WidgetRepository())->types();
        if (!$types) {
            return;
        }

        $descendants = $this->object_type::withDepth()
            ->having('depth', '=', $this->object->depth + 1)
            ->descendantsOf($this->object_id);

        $types_class = array_column($types, 'value');
        foreach ($types_class as $class) {
            /* текущая страница */
            $class::clearCacheByObjectUuid($this->object_id);

            /* дочерние страницы */
            if ($descendants->count()) {
                foreach ($descendants as $descendant) {
                    $class::clearCacheByObjectUuid($descendant->uuid);
                }
            }
        }
    }

    /**
     * Сбросит кеши хлебных крошек
     */
    public function clearCacheBreadcrumbs()
    {
        $descendants = $this->object_type::withDepth()
            ->having('depth', '>=', $this->object->depth + 1)
            ->descendantsOf($this->object_id);

        foreach (config('translatable.locales') as $locale) {
            $cache_key = 'breadcrumbs.' . $this->object_id .'.'. $locale;
            if (Cache::has($cache_key)) {
                Cache::forget($cache_key);
            }
        }

        foreach ($descendants as $descendant) {
            foreach (config('translatable.locales') as $locale) {
                $cache_key = 'breadcrumbs.' . $descendant->uuid .'.'. $locale;
                if (Cache::has($cache_key)) {
                    Cache::forget($cache_key);
                }
            }
        }
    }

    /**
     *
     */
    private function createSlug()
    {
        /* удалит слеши по бокам, если заполнили поле слешем, тогда оставим его */
        if (!empty($this->slug)) {
            $this->attributes['slug'] = trim(Str::slug($this->slug), '/') ?: '/';
        } else {
            /* пустое поле, формируем ссылку из названия объекта */
            $this->attributes['slug'] = Str::slug(Str::words($this->object->name, 5, ''));
        }
    }

    /**
     *
     */
    protected static function booted()
    {
        self::creating(function($meta) {
            /* @var Meta $meta */
            $meta->uuid = Uuid::uuid4()->toString();
            $meta->createSlug();
            $meta->generateUrl();
        });

        self::updating(function($meta) {
            /* @var Meta $meta */
            $meta->createSlug();
            $meta->generateUrl();
        });

        self::updated(function($meta) {
            /* @var Meta $meta */
            $meta->descendantsUpdateUrl();
            $meta->updateNavigationUrl();
            $meta->updateNavigationAutoItems();

            $meta->clearCacheWidget();
            $meta->clearCacheBreadcrumbs();
        });
    }

}
