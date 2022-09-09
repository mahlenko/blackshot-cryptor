<?php

namespace App\Http\Controllers\Administrator\Page;

use App\Http\Controllers\Controller;
use App\Http\Requests\MetaRequest;
use App\Http\Requests\NestedRequest;
use App\Http\Requests\ObjectLocaleRequest;
use App\Http\Requests\PageRequest;
use App\Http\Requests\VideoRequest;
use App\Repositories\PageRepository;
use Exception;
use Illuminate\Http\RedirectResponse;

/**
 * Сохраняет страницу сайта
 * @package App\Http\Controllers\Administrator\Page
 */
class Store extends Controller
{
    /**
     * @param PageRequest $pageRequest
     * @param NestedRequest $nestedRequest
     * @param ObjectLocaleRequest $objectLocaleRequest
     * @param MetaRequest $metaRequest
     * @param VideoRequest $videoRequest
     * @return RedirectResponse
     */
    public function index(
        PageRequest $pageRequest,
        NestedRequest $nestedRequest,
        ObjectLocaleRequest $objectLocaleRequest,
        MetaRequest $metaRequest,
        VideoRequest $videoRequest
    ): RedirectResponse {

        /* Validated data */
        $object = $objectLocaleRequest->validated();
        $data = $pageRequest->validated();
        $nested = $nestedRequest->validated();
        $meta = $metaRequest->validated();
        $videos = $videoRequest->validated();

        $page = (new PageRepository())->store(
            $object['uuid'],
            $object['locale'],
            $data,
            $nested['nested'],
            $meta['meta'],
            $videos['video'] ?? []
        );

        flash(__('messages.success.save', ['name' => $page->name]))->success();
        return redirect()->route('admin.page.edit', ['uuid' => $page->uuid, 'locale' => $object['locale']]);
    }
}
