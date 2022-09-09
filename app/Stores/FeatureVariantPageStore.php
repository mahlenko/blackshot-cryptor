<?php


namespace App\Stores;


use App\Models\Catalog\FeatureVariant;
use App\Models\Meta;
use Illuminate\Support\Str;
use Ramsey\Uuid\Uuid;

class FeatureVariantPageStore
{
    /**
     * @param array $data
     * @return false
     */
    public function handle(array $data)
    {
        $variant = FeatureVariant::where(['uuid' => $data['uuid']])->first();
        if (!$variant) return false;

        $variant->name = $data['name'];
        $variant->slug = Str::slug($data['slug'] ?? $data['name']);
        $variant->description = $data['description'];
        $variant->body = $data['body'];
        $variant->url = $data['url'];
        if (key_exists('icon', $data)) {
            $variant->setIcon($data['icon']);
        }

        /* meta params */
        if (!$variant->meta->uuid) {
            $variant->meta->uuid = Uuid::uuid4()->toString();
        }

        $variant->meta->title = $data['meta']['title'];
        $variant->meta->description = $data['meta']['description'];
        $variant->meta->keywords = $data['meta']['keywords'];
        $variant->meta->robots = $data['meta']['robots'];
        $variant->meta->save();

        return $variant->save();
    }
}
