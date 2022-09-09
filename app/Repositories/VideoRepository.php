<?php

namespace App\Repositories;

use App\Models\Video;

class VideoRepository
{
    /**
     * @param string $uuid
     * @param string $locale
     * @param array $data
     * @param string|null $parent_uuid
     * @return Video
     */
    public function store(string $uuid, string $locale, array $data, string $parent_uuid = null): Video
    {
        $video = Video::find($uuid);

        if (!$video) {
            $video = new Video();
            $video->uuid = $uuid;
            $video->parent_uuid = $parent_uuid;
        }

        if (empty($data['width'])) $data['width'] = 0;
        if (empty($data['width_unit'])) $data['width_unit'] = 'px';
        if (empty($data['height'])) $data['height'] = 0;
        if (empty($data['height_unit'])) $data['height_unit'] = 'px';
        if (empty($data['duration'])) $data['duration'] = 0;

        if (empty(trim($data['url']))) {
            $video->delete();
        } else {
            $video->setDefaultLocale($locale)->fill($data);
            $video->save();
        }

        return $video;
    }
}
