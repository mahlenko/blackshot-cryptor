<?php

namespace App\Http\Controllers\Administrator\Page;

use App\Helpers\Nested;
use App\Http\Controllers\Administrator\Widget\Services\PageGroupService;
use App\Http\Controllers\Controller;
use App\Models\Page\Page;
use Illuminate\Http\Request;

class Sortable extends Controller
{
    /**
     * @param Request $request
     * @return array
     */
    public function index(Request $request): array
    {
        $data = $request->validate([
            'uuid' => 'required|uuid',
            'amount' => 'required|numeric'
        ]);

        $object = Page::find($data['uuid']);

        $nested = new Nested(Page::class);
        $result_ok = $nested->sortable($data['uuid'], $data['amount']);

        if ($result_ok) {
            PageGroupService::clearCacheByObjectUuid($object->parent_id);
        }

        return [
            'ok' => $result_ok,
            'message' => $result_ok
                ? __('sortable.message.success', ['name' => $object->name])
                : __('sortable.message.fail', ['name' => $object->name])
        ];
    }
}
