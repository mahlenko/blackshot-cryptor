<?php

namespace App\Http\Controllers\Administrator\Video;

use App\Http\Controllers\Controller;
use App\Models\Video;
use Illuminate\Http\Request;

class Edit extends Controller
{
    public function index(string $locale, string $uuid)
    {
        /* @var Video $video */
        $video = Video::findOrFail($uuid);
        $video->setDefaultLocale($locale);

        return view('administrator.video.edit', [
            'uuid' => $uuid,
            'locale' => $locale,
            'object' => $video,
            'routes' => [
                'store' => 'admin.video.store',
                'edit' => 'admin.video.edit'
            ],
            'title' => $video->name,
            'description' => null,
            'max_width' => 700,
            'switcher_data' => ['uuid' => $uuid],
            'chunks_template' => [
                [
                    'icon' => '<i class="fas fa-cog me-1"></i>',
                    'key' => 'general_video',
                    'name' => __('global.tabs.general'),
                    'template' => 'administrator.video.tabs.general',
                    'data' => []
                ],
            ]
        ]);
    }
}
