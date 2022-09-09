<?php


namespace Anita\Interfaces;


use Anita\Entities\SitemapAlternativeLocale;
use Anita\Entities\SitemapItem;
use App\Models\Catalog\Product;
use DateTimeImmutable;
use Exception;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;

class SitemapAbstract
{
    const CHANGE_FREQ = 'monthly';

    const PRIORITY = 0.6;

    /**
     * @var Model
     */
    protected Model $model;

    /**
     * @var string
     */
    protected string $table;

    /**
     * @var string
     */
    protected string $primary_key;

    /**
     * @var string|null
     */
    protected ?string $table_translations;

    /**
     * @var string|null
     */
    protected ?string $translation_primary_key;

    /**
     * @var Collection
     */
    public Collection $items;

    /**
     * SitemapAbstract constructor.
     * @param Model $model
     */
    public function __construct(Model $model)
    {
        $this->model = $model;
        $this->table = $model->getTable();
        $this->primary_key = $model->getKeyName();

        $this->table_translations = method_exists($model, 'hasTranslation')
            ? Str::singular($this->table) .'_translations'
            : null;

        $this->translation_primary_key = $this->table_translations
            ? Str::singular($this->table) .'_'. $this->primary_key
            : null;

        $this->getItems();
    }

    /**
     * @param Model $model
     * @return SitemapAbstract
     */
    public static function build(Model $model): SitemapAbstract
    {
        return new self($model);
    }

    /**
     *
     */
    protected function getItems()
    {
        $items = DB::table('metas')
            ->select([$this->table.'.parent_id', 'metas.object_id as uuid', 'metas.url', 'meta_translations.locale', 'metas.updated_at', 'metas.created_at'])
            ->join('meta_translations', 'metas.uuid', '=', 'meta_translations.meta_uuid')
            ->join($this->table, $this->table .'.'. $this->primary_key, '=', 'metas.object_id')
            ->where('robots', 'like', 'index%')
            ->where('metas.publish_at', '<=', new DateTimeImmutable('now'))
            ->where(['is_active' => 1, 'object_type' => get_class($this->model) ])
            ->orderBy($this->table .'._lft');

        $this->items = $items->get()->sortBy(function($item) {
            return $item->locale != config('translatable.locale');
        })->groupBy('uuid');
    }

    /**
     * @param string $url
     * @param string $lastmod
     * @param float $priority
     * @param string $changefreq
     * @param Collection|null $alternative
     * @return SitemapItem
     * @throws Exception
     */
    public static function item(
        string $url,
        string $lastmod,
        float  $priority = self::PRIORITY,
        string $changefreq = self::CHANGE_FREQ,
        Collection $alternative = null
    ): SitemapItem {
        /*  */
        $navigation_cache_name = 'navigation.general.'. app()->getLocale();
        $navigation_cache = Cache::get($navigation_cache_name);
        $navigation = collect();
        if ($navigation_cache) {
            $navigation = self::navigationFlatten($navigation, $navigation_cache->items);
            if ($navigation->search($url) && !Str::startsWith($url, Product::PREFIX)) {
                $priority = 0.9;
            }
        }

        $alternative_list = collect();
        if ($alternative &&  $alternative->count()) {
            foreach ($alternative as $item) {
                $alternative_list->add(new SitemapAlternativeLocale($item->locale, $item->locale .'/'. $item->url));
            }
        }

        return new SitemapItem($url, $lastmod, $changefreq, $priority, $alternative_list);
    }

    /**
     * @param Collection $result
     * @param Collection $items
     * @return Collection
     */
    private static function navigationFlatten(Collection $result, Collection $items): Collection
    {
        foreach ($items as $item) {
            $result->add(trim($item->url, '/'));
            if ($item->children) {
                self::navigationFlatten($result, $item->children);
            }
        }

        return $result;
    }
}
