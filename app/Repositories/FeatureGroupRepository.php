<?php


namespace App\Repositories;


use App\Helpers\Nested;
use App\Models\Catalog\FeatureGroup;

class FeatureGroupRepository
{
    /**
     * @param string $uuid
     * @param string $locale
     * @param array $data
     * @param array $nested
     * @return FeatureGroup
     */
    public function store(string $uuid, string $locale, array $data, array $nested): FeatureGroup
    {
        $group = FeatureGroup::find($uuid);
        if (!$group) {
            $group = new FeatureGroup();
            $group->uuid = $uuid;
        }

        $data['is_active'] = !empty($data['is_active']);

        if (!empty($nested['position']) && !empty($nested['parent_id'])) {
            Nested::model(FeatureGroup::class)
                ->tree($group, $nested['position'], $nested['parent_id']);
        }

        $group->setDefaultLocale($locale)->fill($data);
        $group->push();

        return $group;
    }
}
