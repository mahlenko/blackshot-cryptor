<?php


namespace App\Repositories;


use App\Helpers\Data;
use App\Helpers\Nested;
use App\Models\Catalog\Category;
use App\Models\Catalog\FeatureVariant;
use App\Models\Catalog\Product;
use App\Models\Catalog\ProductCategory;
use App\Models\Finder\File;
use App\Models\Finder\Folder;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Session;
use Illuminate\Support\Str;
use Kalnoy\Nestedset\Collection;
use Ramsey\Uuid\Uuid;

class CategoryRepository
{
    /**
     * @param string $uuid
     * @param string $locale
     * @param array $data
     * @param array $nested
     * @param array $meta
     * @return Category
     */
    public function save(
        string $uuid,
        string $locale,
        array $data,
        array $nested,
        array $meta
    ): Category {
        /* Создаем или обновляем категорию */
        $category = Category::find($uuid);
        if (!$category) {
            $category = new Category();
            $category->uuid = $uuid;
        }

        /* Заполним данными категорию */
        $category->setDefaultLocale($locale)->fill($data);

        /* Nested sets */
        if (!empty($nested['parent_id'])) {
            /* поиск корневой категории */
            $root = Category::select('uuid')
                ->where(['_lft' => 1])
                ->first();

            /* запретим добавлять категорию рядом с корневой, только внутрь ее */
            if ($nested['parent_id'] == $root->uuid) {
                $nested['position'] = 'append';
            }

            Nested::model(Category::class)
                ->tree($category, $nested['position'], $nested['parent_id']);
        }

        /* Meta теги */
        $meta['object_type'] = Category::class;
        $meta['is_active'] = !empty($meta['is_active']);
        $meta['show_nested'] = !empty($meta['show_nested']);
        $meta['title'] = $meta['title'] ?? $data['name'];
        $category->meta->setDefaultLocale($locale)->fill($meta);

        $category->push();

        /* загрузить иконку категории */
        if (key_exists('icon', $data)) {
            $this->updateIcon($data['icon'], $category);
        }

        return $category;
    }

    /**
     * @param UploadedFile $file
     * @param Category $category
     */
    public function updateIcon(UploadedFile $file, Category $category)
    {
        if (!$file) return;

        /* Удалим предыдущее превью, если было */
        if ($category->icon) $category->icon->delete();

        /* Создание каталогов */
        $module_folder = Folder::createFolder(class_basename($category));
        $item_folder = Folder::createFolder($category->name, $module_folder->uuid);

        /* Загружаем файл в каталог товара */
        File::upload($file, $item_folder, $category->uuid);
    }

    /**
     * Отображение товаров на странице сайта
     * @param Category $category
     * @param array|null $filter
     * @param array|null $filter_product_data
     * @return array
     */
    public function viewFilterProducts(Category $category, array $filter = null, array $filter_product_data = null)
    {
        /* Все дочерние категории и себя */
        $children_uuid = $category->descendants()->pluck('uuid');
        $children_and_self_uuid = $children_uuid->add($category->uuid);

        /* Варианты характеристик товаров которые подходят под поиск */
        $feature_variants = $this->featureVariants($filter ?? []);

        /* ------------------------------------------------------------------ */
        /* поиск товаров категории */
        $query = DB::table('products')->select('products.uuid')
            /* выбор товаров только из категорий где мы сейчас находимся */
            ->join('product_categories', 'products.uuid', '=', 'product_categories.product_uuid')
            ->join('product_translations', 'products.uuid', '=', 'product_translations.product_uuid')
            ->where('product_translations.locale', app()->getLocale())
            ->whereIn('product_categories.category_uuid', $children_and_self_uuid);

        /* ------------------------------------------------------------------ */
        /* Фильтр по параметрам товара (цена и прочее) */
        if ($filter_product_data) {
            foreach ($filter_product_data as $key => $values) {
                if (key_exists('between', $values)) {
                    /* если массив значений, скорее всего это диапазон чего либо */
                    $query->whereBetween('products.' . $key, $values);
                } else {
                    $query->where('products.' . $key, $values);
                }
            }
        }

        /* ------------------------------------------------------------------ */
        /* фильтруем по характеристикам */
        if ($feature_variants) {
            $query->join('product_features', 'products.uuid', '=', 'sproduct_features.product_uuid');
            $query->where(function($feature_query) use ($feature_variants) {
                foreach ($feature_variants as $filter_group) {
                    $keys = array_keys($filter_group->first());
                    $values = [];
                    foreach ($filter_group as $filter) {
                        $values[] = '(\'' . implode('\', \'', $filter) . '\')';
                    }

                    $feature_query->orWhereRaw('(' . implode(', ', $keys) . ') IN (' . implode(', ', $values) . ')');
                }
            });

            $query->groupBy('product_features.product_uuid');
            $query->havingRaw('COUNT(product_features.feature_uuid) = ' . $feature_variants->first()->count());
        }

        /* ------------------------------------------------------------------ */
        /* сортирвка результата */
        $sortable_segments = explode('.', $this->getUserSortable());
        $sortable_column = implode('.', array_slice($sortable_segments, 0, -1));
        $sortable_direction = $sortable_segments[count($sortable_segments) - 1];

        if ($sortable_direction == 'asc') {
            $query->orderBy($sortable_column);
        } else {
            $query->orderByDesc($sortable_column);
        }

        /* ------------------------------------------------------------------ */

        $sortableQuery = $sortable_segments[count($sortable_segments) - 2];

        $pagination_items = $sortable_direction == 'asc'
            ? $query->orderBy($sortableQuery)->paginate()
            : $query->orderByDesc($sortableQuery)->paginate();

        $products = Product::find($pagination_items->pluck('uuid'));

        return [
            'items' => $sortable_direction == 'asc'
                ? $products->sortBy($sortableQuery)
                : $products->sortByDesc($sortableQuery),
            'links' => $pagination_items->links()
        ];
    }

    /**
     * @return string
     */
    public function getUserSortable(): string
    {
        if ($sortable = Session::get('sortable')) {
            return $sortable;
        }

        $default_sortable = 'products.created_at.desc';
        $this->setUserSortable($default_sortable);
        return $default_sortable;
    }

    /**
     * @param string $sortable
     */
    public function setUserSortable(string $sortable)
    {
        Session::put('sortable', $sortable);
        Session::save();
    }

    /**
     *
     */
    public function resetUserSortable()
    {
        Session::remove('sortable');
    }

    /**
     * Сгенерирует варианты товаров по их характеристикам
     * @param array $filter
     * @return \Illuminate\Support\Collection|null
     */
    private function featureVariants(array $filter = []): ?\Illuminate\Support\Collection
    {
        $filter_variants = null;

        /* добавим uuid характеристики в варианты из фильтра */
        if ($filter && count(array_filter($filter))) {
            $filter_variants = [];
            foreach ($filter as $feature_uuid => $feature_values) {
                $values = [];

                /* поиск диапазона значений */
                if (key_exists('between', $feature_values)) {
                    $variants = FeatureVariant::where(['feature_uuid' => $feature_uuid])->with(['translation']);
                    $feature_values = $variants->get()->filter(function($item) use ($feature_values) {
                        return intval($item->name) >= $feature_values['between'][0]
                            && intval($item->name) <= $feature_values['between'][1];
                    })->pluck('uuid');
                }

                /*  */
                foreach ($feature_values as $variant_uuid) {
                    $values[] = ['product_features.feature_uuid' => $feature_uuid, 'product_features.feature_variant_uuid' => $variant_uuid];
                }

                $filter_variants[] = $values;
            }
        }

        /* сгенерируем или вернем пустой результат вариантов */
        return $filter_variants
            ? Data::generationVariants(collect($filter_variants))
            : null;
    }

}
