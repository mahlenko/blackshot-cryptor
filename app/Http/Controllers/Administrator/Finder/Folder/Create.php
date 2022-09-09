<?php

namespace App\Http\Controllers\Administrator\Finder\Folder;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

class Create extends Controller
{
    public function index(string $uuid)
    {
        return view('administrator.finder.folder.create', [
            'parent_uuid' => $uuid
        ]);
    }
}
