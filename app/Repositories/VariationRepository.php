<?php


namespace App\Repositories;


use App\Models\Catalog\Product;
use App\Models\Catalog\ProductVariationGroup;
use Illuminate\Support\Str;

class VariationRepository
{
    /**
     * Создать группу для вариантов
     * @param string $group_uuid
     * @param Product $product
     * @return ProductVariationGroup
     */
    public function findOrCreate(string $group_uuid, Product $product): ProductVariationGroup
    {
        $group = ProductVariationGroup::find($group_uuid);

        if (!$group) {
            $group = new ProductVariationGroup();
            $group->uuid = $group_uuid;
            $group->code = Str::upper(Str::random(2). '-' . Str::random(6));
            $group->save();

            foreach ($product->variation_features as $feature) {
                $group->addFeature($feature);
            }

            $group->addProduct($product);
            $group->refresh();
        }

        return $group;
    }
}
