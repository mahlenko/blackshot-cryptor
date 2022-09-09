<?php


namespace Anita\Entities;

use Anita\Entities\Navigation\Icon;
use Anita\Entities\Navigation\Item;
use App\Models\Catalog\Category;
use App\Models\Catalog\Product;
use App\Models\Catalog\ProductCategory;
use App\Models\Catalog\ProductTranslation;
use App\Models\Finder\File;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;
use stdClass;

/**
 * Генерирует все ссылки в меню
 * @package Anita\Entities\Navigation
 */
class Navigation
{
    /**
     * @var string
     */
    public string $uuid;

    /**
     * @var string
     */
    public string $name;

    /**
     * @var string|null
     */
    public ?string $description;

    /**
     * @var string
     */
    public string $locale;

    /**
     * @var Collection|null
     */
    public ?Collection $items;

    /**
     * @var string
     */
    public string $template = 'default';

    /**
     * Navigation constructor.
     */
    public function __construct(string $key, string $locale = null)
    {
        if (is_null($locale)) $locale = app()->getLocale();
        $this->locale = (string) $locale;

        $this->getNavigation($key);
    }

    /**
     * @param string $key
     * @param string|null $locale
     * @return Navigation
     */
    public static function generate(string $key, string $locale = null): Navigation
    {
        return new self($key, $locale);
    }

    /**
     * @param string $key
     * @return void
     */
    private function getNavigation(string $key): void
    {
        $navigation = DB::table('navigations')
            ->select(['uuid', 'name', 'description', 'template'])
            ->where('key', '=', $key)
            ->first();

        if (!$navigation) {
            return;
        }

        $this->uuid = $navigation->uuid;
        $this->name = $navigation->name;
        $this->description = $navigation->description;
        $this->template = $navigation->template;
        $this->items = $this->getItems();
    }

    /**
     * @return Collection|null
     */
    private function getItems(): ?Collection
    {
        $items = DB::table('navigation_items', 'item')
            ->select([
                'item.*',
                'translate.name',

                'metas.object_type',
                'metas.object_id',
                'metas.url as object_url',

                'files.uuid as icon_uuid',
                'files.filename as icon_filename',
                'files.folder_path as icon_folder',
                'files.image_x as icon_x',
                'files.image_y as icon_y',
                'files.mimeType as icon_mimetype',
                'files.size as icon_size',
                'file_translations.alt as icon_alt',
                'file_translations.title as icon_title',
                'file_translations.description as icon_description',

                'params.style',
                'params.css',
                'params.target',
                'params_trans.title as title'
                ]
            )
            ->join('navigation_item_translations as translate', 'item.uuid', '=', 'navigation_item_uuid')
            ->join('navigation_item_params as params', 'item.uuid', '=', 'params.uuid')
            ->join('navigation_item_param_translations as params_trans', 'params.uuid', '=', 'params_trans.navigation_item_param_uuid')
            ->join('metas', 'metas.uuid', '=', 'item.meta_uuid', 'left')
            ->join('files', 'item.uuid', '=', 'files.parent_uuid', 'left')
            ->join('file_translations', 'files.uuid', '=', 'file_translations.file_uuid', 'left')
            ->where([
                'item.navigation_uuid' => $this->uuid,
                'item.is_active' => 1,
                'translate.locale' => $this->locale,
                'params_trans.locale' => $this->locale
            ])
            ->orderBy('_lft')
            ->get();

        if (!$items || !$items->count()) return $items;

        /* генерация дополнительных пунктов */
        $items = $this->generationItems($items);

        return $this->toTree($items);
    }

    /**
     * @param Collection $items
     * @return Collection
     */
    private function generationItems(Collection $items): Collection
    {
        /* генерация внутренних страниц с товарами */
        $generate_catalog_and_products = $items
            ->where('generate_catalog', '=', 1)
            ->where('generate_products', '=', 1);

        $generate_catalog = $items
            ->where('generate_catalog', '=', 1)
            ->where('generate_products', '=', 0);

        /* только товары */
        $generate_only_products = $items
            ->where('generate_catalog', '=', 0)
            ->where('generate_products', '=', 1);

        if ($generate_catalog_and_products->count()) {
            $items = $this->generateItemsChildrenAndProducts($items, $generate_catalog_and_products);
        }

        if ($generate_catalog->count()) {
            $items = $this->generateItemsChildren($items, $generate_catalog);
        }

//        if ($generate_only_products->count()) {
//            $items = $this->generateItemsProducts($items, $generate_only_products->pluck('object_id'));
//        }

        return $items;
    }

    /**
     * Сгенерировать внутренние страницы
     * @param Collection $collection
     * @return Collection
     */
    private function getChildren(Collection $collection): ?Collection
    {
        if (!$collection->count()) return collect();

        $collectionChildren = collect();

        foreach ($collection->groupBy('object_type') as $type => $items)
        {
            $model = new $type();

            $objects = $this->getObjects(new $type(), $items);

            $table = $model->getTable();
            $trans = $this->getTranslationTable($model);

            /*  */
            $fields = [
                $table.'.uuid',
                $table.'.parent_id',
                $table.'._lft',
                $table.'._rgt',
                $trans ? $trans->table.'.name' : $table .'.name',
                'metas.object_type',
                'metas.object_id',
                'metas.url as object_url',
            ];

            /*  */
            $children = DB::table($table)
                ->select($fields)
                ->join('metas', 'metas.object_id', '=', $table.'.uuid')
                ->orderBy($table .'._lft');

            if ($trans) {
                $children->join($trans->table, $trans->table .'.'. $trans->key, '=', $table .'.uuid');
            }

            foreach ($objects as $object) {
                $children->orWhere(function($query) use ($object, $table, $trans) {
                    $query->where('metas.is_active', 1);
                    $query->where($table.'._lft', '>', $object->_lft);
                    $query->where($table.'._rgt', '<', $object->_rgt);
                    if ($trans) {
                        $query->where($trans->table.'.locale', '=', $this->locale);
                    }
                });
            }

            $collectionChildren->push($children->get());
        }

        return $collectionChildren->flatten();
    }

    /**
     * Сгенерировать товары
     * @param Collection $collection
     * @return Collection
     */
    private function getProducts(Collection $collection): Collection
    {
        $products_table = (new Product())->getTable();
        $products_trans = (new ProductTranslation())->getTable();
        $categories = (new ProductCategory())->getTable();

        $products = DB::table($categories)
            ->select([$categories.'.product_uuid as uuid', $categories.'.category_uuid as parent_id', $products_trans.'.name', 'metas.object_type', 'metas.object_id', 'metas.url as object_url'])
            ->join($products_table, $categories.'.product_uuid', '=', $products_table.'.uuid')
            ->join($products_trans, $categories.'.product_uuid', '=', $products_trans.'.product_uuid')
            ->join('metas', 'metas.object_id', '=', $categories.'.product_uuid')
            ->where($products_trans.'.locale', '=', $this->locale)
            ->whereIn('category_uuid', $collection)
            ->orderByDesc($products_table.'._lft');

        return $products->get();
    }

    /**
     * @param Collection $items
     * @param Collection $collection
     * @return Collection
     */
    private function generateItemsChildrenAndProducts(Collection $items, Collection $collection): Collection
    {
        $children = $this->getChildren($collection);

        /* добавить связи между пунктами меню */
        $items = $this->setRelations($items, $children);

        /*  */
        $categories_uuids = $collection->where('generate_products', 1)->pluck('object_id');
        foreach ($children->pluck('uuid') as $uuid) $categories_uuids->add($uuid);

        $products = $this->getProducts($categories_uuids);

        $products_navs = $items->where('generate_products', 1);
        foreach ($children as $item) {
            $products_navs->add($item);
        }

        /* добавить связи между пунктами меню */
        foreach ($products_navs as $item) {
            $children_item = $products->where('parent_id', $item->object_id);

            if ($children_item->count()) {
                foreach ($children_item as $child) {
                    $child_clone = clone $child;
                    $child_clone->parent_id = $item->uuid;
                    $items->add($child_clone);
                }
            }
        }

        return $items;//->unique();
    }

    /**
     * @param Collection $items
     * @param Collection $collection
     * @return Collection
     */
    public function generateItemsChildren(Collection $items, Collection $collection): Collection
    {
        $children = $this->getChildren($collection);

        /* добавить связи между пунктами меню */
        return $this->setRelations($items, $children);
    }

    /**
     * Добавит сгенерированные страницы к родительским пунктам меню
     * @param Collection $navigations
     * @param Collection $relations
     * @return Collection
     */
    private function setRelations(Collection $navigations, Collection $relations): Collection
    {
        foreach ($navigations->unique() as $navigation) {
            $relation_items = $relations
                ->where('object_type', $navigation->object_type)
                ->where('parent_id', $navigation->object_id);

            if ($relation_items->count()) {
                foreach ($relation_items as $child) {
                    if (!$navigations->where('uuid', $child->uuid)->where('parent_id', $navigation->uuid)->count()) {
                        $child->parent_id = $navigation->uuid;
                        $navigations->add($child);
                    }
                }
            }
        }

        return $navigations;
    }

    /**
     * Получит объекты выбранного типа
     * @param Model $model
     * @param Collection $collection
     * @return Collection|null
     */
    private function getObjects(Model $model, Collection $collection): ?Collection
    {
        $table = $model->getTable();

        $objects = DB::table($table)
            ->join('metas', 'metas.object_id', '=', $table .'.uuid')
            ->select([$table .'.uuid', $table .'._lft', $table .'._rgt'])
            ->where('metas.is_active', 1)
            ->whereIn($table.'.uuid', $collection->pluck('object_id'));

        if (!$objects->count()) return null;

        return $objects->get();
    }

    /**
     * @param Model $model
     * @return stdClass|null
     */
    private function getTranslationTable(Model $model): ?stdClass
    {
        $trans = new stdClass();

        $trans->table = method_exists($model, 'hasTranslation')
            ? Str::singular($model->getTable()) . '_translations'
            : null;

        $trans->key = $trans->table
            ? Str::singular($model->getTable()) . '_' . $model->getKeyName()
            : null;

        return $trans->table ? $trans : null;
    }

    /**
     * @param Collection $items
     * @param string|null $parent
     * @param int $depth
     * @return Collection
     */
    public function toTree(Collection $items, string $parent = null, int $depth = 0): Collection
    {
        /* модели иконок */
        $_file_filter_uuid = $items->pluck('icon_uuid')->filter();
        $icons = $_file_filter_uuid
            ? File::find($_file_filter_uuid)
            : collect();

        $tree = collect();
        foreach ($items as $item) {
            if ($parent == $item->parent_id)
            {
                $children = $this->toTree($items, $item->uuid, $depth + 1);

                if ($children) {
                    $item->children = $children;
                }

                $icon = !empty($item->icon_filename)
                    ? new Icon(
                            (string) $item->icon_filename,
                            (string) $item->icon_folder,
                            intval($item->icon_x),
                            intval($item->icon_y),
                            intval($item->icon_size),
                            (string) $item->icon_mimetype,
                            (string) $item->icon_alt,
                            (string) $item->icon_title,
                            (string) $item->icon_description,
                            $icons->where('uuid', $item->icon_uuid)->first()
                        )
                    : new Icon();

                $tree->add(new Item(
                    $item->uuid,
                    $item->name,
                    $item->object_url ?? $item->url,
                    $item->title ?? '',
                    $item->target ?? '_self',
                    (string) class_basename($item->object_type),
                    $item->style ?? '',
                    $item->css ?? '',
                    $item->template ?? '',
                    $depth,
                    $icon,
                    $item->children
                ));
            }
        }

        return $tree;
    }
}
