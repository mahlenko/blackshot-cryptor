<?php

namespace App\Http\Controllers\Administrator\Video;

use App\Http\Controllers\Controller;
use App\Http\Requests\ObjectLocaleRequest;
use App\Http\Requests\VideoRequest;
use App\Models\Video;
use App\Repositories\VideoRepository;
use Illuminate\Http\Request;

class Store extends Controller
{
    /**
     * @param ObjectLocaleRequest $localeRequest
     * @param VideoRequest $videoRequest
     * @return array
     */
    public function index(ObjectLocaleRequest $localeRequest, VideoRequest $videoRequest): array
    {
        $object = $localeRequest->validated();
        $data = $videoRequest->validated();

        $video = (new VideoRepository())->store(
            $object['uuid'],
            $object['locale'],
            $data['video'][$object['uuid']]
        );

        if ($video) {
            return [
                'ok' => true,
                'message' => __('messages.success.save', [
                    'name' => $video->name
                ])
            ];
        } else {
            return [
                'ok' => false,
                'message' => __('messages.fail.save')
            ];
        }
    }
}
