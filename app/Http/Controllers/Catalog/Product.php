<?php

namespace App\Http\Controllers\Catalog;

use App\Http\Controllers\Controller;
use App\Http\Controllers\Page;
use Illuminate\Http\Request;

class Product extends Controller
{
    /**
     *
     */
    public function index(string $slug)
    {
        $page = Page::getPageData($slug);
        if (!$page) abort(404);

        $product = $page['data'];

        if ($product->group) {
            $group_products = $product->group->products->pluck('object');
            $features_variants = collect([
                'features' => $product->group->features,
                'products' => collect()
            ]);

            foreach ($group_products as $group_product) {
                $product_items = collect([
                    'url' => $group_product->meta->url,
                    'label' => null,
                    'preview' => $group_product->preview(),
                    'current' => $group_product->uuid == $product->uuid,
                    'features' => collect()
                ]);

                $group_product_features = $group_product->features->sortBy('feature._lft');

                foreach($group_product_features as $product_feature) {
                    $product_items['features']->push([
                        'feature_uuid' => $product_feature->feature->uuid,
                        'feature_name' => $product_feature->feature->name,
                        'view' => $product_feature->feature->view_product,
                        'label' => implode(', ', array_filter([
                            $product_feature->feature->prefix,
                            $product_feature->variant->name,
                            $product_feature->feature->postfix,
                        ]))
                    ]);
                }

                if ($product->group->features->count() > 1) {
                    $collect = collect();
                    foreach ($product_items['features'] as $feature_item) {
                        $collect->push($feature_item['feature_name'] .': '. $feature_item['label']);
                    }
                    $product_items['label'] = $collect->join('; ');
                } else {
                    $product_items['label'] = $product_items['features']->join('; ');
                }

                $features_variants['products']->push($product_items);
            }
        }

        return view($page['meta']->template, [
            'meta' => $page['meta'],
            'page' => $page['data'],
            'page_breadcrumbs' => $page['data'],
            'features' => $product->features->groupBy('feature.group') ?? collect(),
            'features_group_active' => $features_active ?? [],
            'features_variants' => $features_variants ?? []
        ]);
    }
}
