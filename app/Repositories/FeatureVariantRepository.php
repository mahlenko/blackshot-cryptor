<?php


namespace App\Repositories;


use App\Models\Catalog\FeatureVariant;
use App\Models\Finder\File;
use App\Models\Finder\Folder;
use Illuminate\Http\UploadedFile;

class FeatureVariantRepository
{
    /**
     * @param string $uuid
     * @param string $locale
     * @param array $data
     * @param array|null $meta
     * @return FeatureVariant
     */
    public function store(string $uuid, string $locale, array $data, array $meta = null): FeatureVariant
    {
        $variant = FeatureVariant::find($uuid);

        if (!$variant) {
            $variant = new FeatureVariant();
            $variant->uuid = $uuid;
            $variant->feature_uuid = $data['feature_uuid'];
        }

        if (empty(trim($data['name']))) {
            $variant->delete();
        } else {
            $variant->setDefaultLocale($locale)->fill($data)->save();

            $meta['object_type'] = FeatureVariant::class;
            $meta['is_active'] = !empty($meta['is_active']);
            $meta['show_nested'] = false;

            $variant->meta->setDefaultLocale($locale)->fill($meta)->save();
        }

        /* обновим изображение */
        if (key_exists('icon', $data) && $data['icon'] instanceof UploadedFile) {
            if ($variant->icon) {
                $variant->icon->delete();
            }

            /* upload file */
            $folder = Folder::createFolder(FeatureVariant::class);
            File::upload($data['icon'], $folder, $variant->uuid);
        }

        return $variant;
    }
}
