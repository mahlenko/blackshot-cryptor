<?php


namespace App\Repositories;


use App\Models\Catalog\Product;
use App\Models\Catalog\ProductCategory;
use App\Models\Catalog\ProductFeature;
use App\Models\Catalog\ProductVariationGroup;
use App\Models\Finder\File;
use App\Models\Finder\Folder;
use DateTimeImmutable;
use Exception;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Str;
use Ramsey\Uuid\Uuid;

class ProductRepository
{
    /**
     * Создание и обновление товара
     * @param string $uuid
     * @param string $locale
     * @param array $data
     * @param array $categories
     * @param array $features
     * @param array $images
     * @param array $videos
     * @param array $meta
     * @return Product
     */
    public function store(
        string $uuid,
        string $locale,
        array $data,
        array $categories,
        array $features,
        array $images,
        array $videos,
        array $meta
    ): Product {
        $product = Product::find($uuid);
        if (!$product) {
            $product = new Product();
            $product->uuid = $uuid;
        }

        /* сохраним товар */
        $product->setDefaultLocale($locale)->fill($data)->push();

        $meta['title'] = $meta['title'] ?? $data['name'];
        $product->meta->setDefaultLocale($locale)->fill($meta)->save();

//        $product->push();


        foreach ($features as $feature_uuid => $feature_values) {

            $values = collect($feature_values);

            $product_features = $product->features->where('feature_uuid', $feature_uuid);
            $delete_features = $product_features->pluck('feature_variant_uuid')->diff($values);
            if ($delete_features->count()) {
                foreach ($delete_features as $feature_variant_uuid) {
                    ProductFeature::where([
                        'product_uuid' => $product->uuid,
                        'feature_uuid' => $feature_uuid,
                        'feature_variant_uuid' => $feature_variant_uuid
                    ])->delete();
                }
            }

            foreach ($feature_values as $value) {
                if (empty(trim($value))) {
                    $this->deleteFeature($product, $feature_uuid);
                } else {
                    /* сохраним характиристику */
                    $product_feature = $this->findOrCreateFeature($locale, $product, $feature_uuid, $value);

                    /* если товар находится в группе, нужно проверить есть ли эта характеристика в этой группе */
                    if (in_array($product_feature->feature->purpose, ['group_variants', 'group_products']) && $product->group)
                    {
                        $find_feature_in_group = $product->group->features->where(['feature_uuid' => $feature_uuid]);

                        /*  */
                        if (!$find_feature_in_group->count()) {
                            /* если характиристики нет в группе, ее нужно добавить */
                            $product->group->addFeature($product_feature->feature);
                        }
                    }
                }
            }
        }

        if ($categories && count($categories)) {
            foreach ($categories as $category_uuid => $value_uuid) {
                if (empty(trim($value_uuid))) {
                    try {
                        $this->deleteCategory($product, $category_uuid);
                    } catch (Exception $e) {}
                } else {
                    $this->findOrCreateCategory($product, $category_uuid);
                }
            }
        }

        if ($images) {
            foreach ($images as $image) {
                $this->uploadImages($product, $image);
            }
        }

        if ($videos) {
            foreach ($videos as $video_uuid => $data) {
                $video_item = (new VideoRepository())
                    ->store($video_uuid, $locale, $data, $product->uuid);
            }
        }

        return $product;
    }

    /**
     * Сдлает полную копию товара
     * @param Product $original
     * @param string $postfix Поствикс добавиться в название товара и код
     * @return Product
     */
    public function copy(Product $original, string $postfix): Product
    {
        $uuid = Uuid::uuid4()->toString();

        /*  */
        $data = $original->getAttributes();
        $data['product_code'] .= '-'. Str::upper($postfix);
        foreach ($original->translatedAttributes as $key) {
            $data[$key] = $original->translate()->getAttribute($key);
        }
        $data['name'] = $data['name'] .' - '. $postfix;
        $data['publish_at'] = (new DateTimeImmutable())->format('Y-m-d H:i:s');
        unset($data['_lft'], $data['_rgt'], $data['parent_id'], $data['created_at'], $data['updated_at']);

        /*  */
        $meta = $original->meta->getAttributes();
        $meta['uuid'] = Uuid::uuid4()->toString();
        $meta['slug'] = Str::slug($data['name']);
        $meta['url'] = null;
        $meta['views'] = 0;
        $meta['object_id'] = $uuid;
        $meta['publish_at'] = (new DateTimeImmutable())->format('Y-m-d H:i:s');
        foreach ($original->meta->translatedAttributes as $key) {
            $meta[$key] = $original->meta->translate()->getAttribute($key);
        }
        unset($meta['created_at'], $meta['updated_at']);

        /*  */
        $images = [];
        if ($original->images) {
            foreach ($original->images as $image) {
                $images[] = new UploadedFile(
                    Storage::path($image->fullName()),
                    $image->name,
                    $image->mimeType
                );
            }
        }

        /*  */
        $videos = [];
        if ($original->videos) {
            foreach ($original->videos as $video) {
                $videos[Uuid::uuid4()->toString()]['url'] = $video->url;
            }
        }

//        dd($data, $meta, $images);

        return $this->store(
            $uuid,
            app()->getLocale(),
            $data,
            $original->categories->pluck('pivot.category_uuid', 'pivot.category_uuid')->toArray(),
            $original->features->pluck('feature_variant_uuid', 'feature_uuid')->toArray(),
            $images,
            $videos,
            $meta
        );
    }

    /**
     * Загрузить изображение в товар
     * @param Product $product
     * @param UploadedFile $file
     */
    public function uploadImages(Product $product, UploadedFile $file)
    {
        if (!$file) return;

        /* Создание каталогов */
        $module_folder = Folder::createFolder(class_basename($product));
        $item_folder = Folder::createFolder($product->name, $module_folder->uuid);

        /* Загружаем файл в каталог товара */
        File::upload($file, $item_folder, $product->uuid);
    }

    /**
     * Вернет категорию и добавит её если ещё нет
     * @param Product $product
     * @param $uuid
     * @return ProductCategory
     */
    public function findOrCreateCategory(Product $product, $uuid): ProductCategory
    {
        $category = ProductCategory::where([
            'product_uuid' => $product->uuid,
            'category_uuid' => $uuid
        ])->first();

        if (!$category) {
            $category = new ProductCategory();
            $category->uuid = Uuid::uuid4()->toString();
            $category->product_uuid = $product->uuid;
            $category->category_uuid = $uuid;
            $category->save();
        }

        return $category;
    }

    /**
     * Удалит категорию
     * @param Product $product
     * @param $uuid
     * @return bool|null
     */
    public function deleteCategory(Product $product, $uuid): ?bool
    {
        $category = ProductCategory::where([
            'product_uuid' => $product->uuid,
            'category_uuid' => $uuid
        ])->first();

        return $category
            ? $category->delete()
            : null;
    }

    /**
     * @param string $locale
     * @param Product $product
     * @param string $uuid
     * @param string $value
     * @return ProductFeature
     */
    public function findOrCreateFeature(string $locale, Product $product, string $uuid, string $value): ProductFeature
    {
        $feature = ProductFeature::where([
            'product_uuid' => $product->uuid,
            'feature_uuid' => $uuid,
            'feature_variant_uuid' => $value
        ])->first();

        if (!$feature) {
            $feature = new ProductFeature();
            $feature->uuid = Uuid::uuid4()->toString();
            $feature->product_uuid = $product->uuid;
            $feature->feature_uuid = $uuid;
        }

        $feature->setDefaultLocale($locale);

        $validator = Validator::make(['value' => $value], [
            'value' => 'uuid'
        ]);

        if ($validator->fails()) {
            $feature->value = $value;
        } else {
            $feature->feature_variant_uuid = $value;
        }

        $feature->save();

        return $feature;
    }

    /**
     * Удалит характеристику из товара
     * @param Product $product
     * @param string $uuid
     * @return null
     */
    public function deleteFeature(Product $product, string $uuid)
    {
        $feature = ProductFeature::where([
            'product_uuid' => $product->uuid,
            'feature_uuid' => $uuid
        ])->first();

        return $feature
            ? $feature->delete()
            : null;
    }

    /**
     * Вернет группу и предварительно создаст её, если её ещё нет
     * @param string $uuid
     * @param string $product_uuid
     * @return ProductVariationGroup
     */
//    public function getOrCreateVariationGroup(string $uuid, string $product_uuid): ProductVariationGroup
//    {
//        /* @var ProductVariationGroup $group */
//        $group = ProductVariationGroup::where(['uuid' => $uuid])->first();
//
//        /* @var Product $product */
//        $product = Product::where(['uuid' => $product_uuid])->first();
//
//        if (!$group) {
//            /* создаем группу */
//            $group = new ProductVariationGroup();
//            $group->uuid = $uuid;
//            $group->code = Str::upper(Str::random(2) .'-'. Str::random(8));
//            $group->save();
//
//            /* добавляем характеристики группы */
//            $group->setFeatures($product->features_variants);
//        }
//
//        return $group;
//    }

//    /**
//     * @param Product $product
//     * @param ProductVariationGroup $group
//     * @param array $combinations
//     * @return bool
//     */
//    public function addVariation(Product $product, ProductVariationGroup $group, array $combinations): bool
//    {
//        /* Добавим основной товар */
//        $main_product = ProductVariationGroupProduct::where(['group_uuid' => $group->uuid, 'product_uuid' => $product->uuid])->first();
//        if (!$main_product) {
//            $variant_group_product = new ProductVariationGroupProduct();
//            $variant_group_product->uuid = Uuid::uuid4()->toString();
//            $variant_group_product->group_uuid = $group->uuid;
//            $variant_group_product->product_uuid = $product->uuid;
//            $variant_group_product->parent_product_uuid = null;
//
//            $variant_group_product->save();
//        }
//
//        /* Добавляем варианты товара */
//        foreach ($combinations as $uuid) {
//            $combination = ProductVariationGroupProduct::where([
//                'product_uuid' => $uuid,
//                'group_uuid' => $group->uuid
//            ])->count();
//
//            if ($combination) continue;
//
//            $combination = new ProductVariationGroupProduct();
//            $combination->uuid = Uuid::uuid4()->toString();
//            $combination->group_uuid = $group->uuid;
//            $combination->product_uuid = $uuid;
//            $combination->parent_product_uuid = $variant_group_product->product_uuid;
//            $combination->save();
//        }
//
//        return true;
//    }
//
//    /**
//     * @param Product $product
//     * @param ProductVariationGroup $group
//     * @param array $combinations
//     * @return bool
//     */
//    public function createVariation(Product $product, ProductVariationGroup $group, array $combinations)
//    {
//        foreach ($combinations as $combination) {
//            /* @var Product $clone */
//            $clone = $this->copy($product, Str::random(4));
//
//            /* Заменим характеристики на выбранные из варианта товара */
//            foreach ($combination as $feature_uuid => $value) {
//                $feature = $clone->features->where('feature_uuid', $feature_uuid)->first();
//                $feature->feature_variant_uuid = $value;
//                $feature->save();
//            }
//
//            /* Добавим клон в группу */
//            $group->addProduct($clone);
//        }
//
//        return true;
//    }
}
