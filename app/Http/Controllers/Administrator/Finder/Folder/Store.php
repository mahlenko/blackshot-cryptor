<?php

namespace App\Http\Controllers\Administrator\Finder\Folder;

use App\Http\Controllers\Controller;
use App\Models\Finder\Folder;
use Illuminate\Http\Request;

class Store extends Controller
{
    public function index(Request $request)
    {
        $data = $request->validate([
            'parent_uuid' => ['required', 'uuid'],
            'name' => ['required']
        ]);

        $folder = Folder::createFolder(trim($data['name']), $data['parent_uuid']);

        return [
            'ok' => true,
            'data' => $folder,
            'reload' => true
        ];
    }
}
