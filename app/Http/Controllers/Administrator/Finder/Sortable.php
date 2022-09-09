<?php

namespace App\Http\Controllers\Administrator\Finder;

use App\Helpers\Nested;
use App\Http\Controllers\Controller;
use App\Models\Finder\File;
use App\Models\Finder\Folder;
use Illuminate\Http\Request;

class Sortable extends Controller
{
    public function index(Request $request, string $uuid)
    {
        $data = $request->validate([
            'uuid' => 'required|uuid',
            'amount' => 'required|numeric'
        ]);

        $object = File::where(['parent_uuid' => $uuid ?? null, 'uuid' => $data['uuid']])->first();

        $nested = new Nested(File::class);
        $result_ok = $nested->sortable(
            $data['uuid'],
            $data['amount'],
            !empty($uuid) ? ['parent_uuid' => $uuid] : null
        );

        return [
            'ok' => $result_ok,
            'message' => $result_ok
                ? __('sortable.message.success', ['name' => $object->name])
                : __('sortable.message.fail', ['name' => $object->name])
        ];
    }

    /**
     * @param Request $request
     * @param string $folder_uuid
     */
    public function folder(Request $request, string $folder_uuid)
    {
        $data = $request->validate([
            'uuid' => 'required|uuid',
            'amount' => 'required|numeric'
        ]);

        $folder = Folder::find($folder_uuid);
        $file = File::find($data['uuid']);

        $nested = new Nested(File::class);

        $result_ok = $nested->sortable(
            $data['uuid'],
            $data['amount']
        );

        return [
            'ok' => $result_ok,
            'message' => $result_ok
                ? __('sortable.message.success', ['name' => $file->name])
                : __('sortable.message.fail', ['name' => $file->name])
        ];
    }
}
