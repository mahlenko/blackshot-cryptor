<?php

namespace App\Http\Controllers\Administrator\Navigation\Items;

use App\Http\Controllers\Controller;
use App\Models\Catalog\Category;
use App\Models\Catalog\Feature;
use App\Models\Meta;
use App\Models\Navigation\NavigationItem;
use App\Models\Page\Page;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;

class ObjectsType extends Controller
{
    /**
     * @param Request $request
     * @return array
     */
    public function index(Request $request): array
    {
        $data = $request->validate([
            'type' => 'required'
        ]);

        $show_generation_children = in_array($data['type'], NavigationItem::SHOW_GENERATE_CHILDREN);
        $show_generation_products = false;

        $meta_table = (new Meta)->getTable();
        $translations_class = $data['type'].'Translation';
        $translations_table = (new $translations_class)->getTable();
        $method_name = class_basename($data['type']);
        $key = Str::of($method_name)->snake() . '_uuid';

        if (method_exists($this, $method_name)) {
            $metas = $this->$method_name($meta_table, $data['type'], $key);
        } else {
            $metas = DB::table($meta_table)
                ->select($meta_table . '.uuid', $meta_table . '.url', $translations_table . '.name')
                ->join($translations_table, $meta_table . '.object_id', '=', $translations_table . '.' . $key)
                ->where($translations_table . '.locale', '=', app()->getLocale())
                ->get();
        }

        if ($metas) {
            $metas = collect($metas);
            $root = $metas->where('url', '/')->first();

            if ($root) {
                $root_meta = Meta::select('object_type')->find($root->uuid);
                $object_prefix = $root_meta->object_type::PREFIX;
                $root->url = $object_prefix;
            }

            $show_generation_products = $data['type'] == Category::class;
        }

        return [
            'ok' => $metas->count() ? true : false,
            'data' => $metas->toArray(),
            'show_generate' => [
                'children' => $show_generation_children,
                'products' => $show_generation_products
            ]
        ];
    }

    /**
     * @param string $meta_table
     * @param string $data_class
     * @param string $key
     * @return \Illuminate\Support\Collection
     */
    private function FeatureVariant(string $meta_table, string $data_class, string $key)
    {
        $features = Feature::where(['purpose' => 'organize_catalog'])
            ->get()->pluck('uuid');

        $data_table = (new $data_class)->getTable();
        $translation = $data_class .'Translation';
        $translations_table = (new $translation)->getTable();

        return DB::table($meta_table)
            ->select($meta_table . '.uuid', $meta_table . '.url', $translations_table . '.name')
            ->join($data_table, $meta_table . '.object_id', '=', $data_table.'.uuid')
            ->join($translations_table, $meta_table . '.object_id', '=', $translations_table . '.' . $key)
            ->where($translations_table . '.locale', '=', app()->getLocale())
            ->whereIn($data_table.'.feature_uuid', $features)
            ->get();
    }

    /**
     * @param string $meta_table
     * @param string $data_class
     * @param string $key
     * @return \Illuminate\Support\Collection
     */
    private function Feature(string $meta_table, string $data_class, string $key)
    {
        $data_table = (new $data_class)->getTable();
        $translation = $data_class .'Translation';
        $translations_table = (new $translation)->getTable();

        return DB::table($meta_table)
            ->select($meta_table . '.uuid', $meta_table . '.url', $translations_table . '.name')
            ->join($data_table, $meta_table . '.object_id', '=', $data_table.'.uuid')
            ->join($translations_table, $meta_table . '.object_id', '=', $translations_table . '.' . $key)
            ->where($translations_table . '.locale', '=', app()->getLocale())
            ->where([$data_table.'.purpose' => 'organize_catalog'])
            ->get();
    }
}
