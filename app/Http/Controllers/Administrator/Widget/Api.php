<?php

namespace App\Http\Controllers\Administrator\Widget;

use App\Http\Controllers\Controller;
use App\Models\Video;
use App\Models\Widget\Widget;

class Api extends Controller
{
    /**
     * @return array
     */
    public function index(): array
    {
        $result = [];
        foreach (Widget::all()->groupBy('type') as $class => $group) {
            $items = [];
            foreach ($group as $widget) {
                $widget->tag = 'x-widget';
                $items[] = $widget;
            }

            $result[] = [
                'name' => $class::name(),
                'items' => $items
            ];
        }

        /* Добавим видео */
        $videos = Video::all();
        if ($videos->count()) {
            $video_items = [];
            foreach ($videos as $video) {
                $video->tag = 'x-video';
                $video_items[] = $video;
            }

            $result[] = [
                'name' => strip_tags(__('video.title')),
                'items' => $video_items
            ];
        }

        return [
            'ok' => count($result) > 0,
            'data' => $result
        ];
    }
}
