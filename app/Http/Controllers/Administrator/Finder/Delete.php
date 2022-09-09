<?php

namespace App\Http\Controllers\Administrator\Finder;

use App\Http\Controllers\Controller;
use App\Models\Finder\File;
use Illuminate\Http\Request;

class Delete extends Controller
{
    public function index(Request $request)
    {
        $data = $request->validate([
            'uuid' => 'required|uuid|exists:App\Models\Finder\File,uuid'
        ]);

        $item = File::where(['uuid' => $data['uuid']])->first();
        return [
            'ok' => $item->delete()
        ];
    }
}
