<?php


namespace App\Repositories;


use App\Models\Meta;

class NavigationRepository
{

    public static function itemTypes()
    {
        $meta_classes = Meta::selectRaw('COUNT(*) as count, object_type')
            ->groupBy('object_type')
            ->get()->pluck('object_type');

        $types = collect(['external' => __('navigation.items.external')]);

        foreach ($meta_classes as $class) {
            $types[$class] = $class::moduleName();
        }

        return $types->sort();
    }
}
