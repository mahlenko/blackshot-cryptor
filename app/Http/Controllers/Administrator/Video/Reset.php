<?php

namespace App\Http\Controllers\Administrator\Video;

use App\Http\Controllers\Controller;
use App\Models\Video;
use Illuminate\Http\Request;

class Reset extends Controller
{
    public function index(string $uuid)
    {
        /* @var Video $video */
        $video = Video::find($uuid);

        if (!$video) {
            flash(__('messages.fail.object_find'))->error();
            return back();
        }

        if ($video->type == 'vimeo') {
            $video->updateVimeoData();
        } elseif ($video->type == 'youtube') {
            $video->updateYoutubeData();
        }

        $video->save();

        flash(__('video.success.reset_data'))->success();
        return back();
    }
}
