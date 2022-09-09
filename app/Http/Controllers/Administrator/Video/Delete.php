<?php

namespace App\Http\Controllers\Administrator\Video;

use App\Http\Controllers\Controller;
use App\Models\Video;
use Illuminate\Http\Request;

class Delete extends Controller
{
    /**
     * @param Request $request
     * @return array
     */
    public function index(Request $request)
    {
        $data = $request->validate([
            'uuid' => 'required|uuid|exists:App\Models\Video,uuid'
        ]);

        $item = Video::where(['uuid' => $data['uuid']])->first();
        return [
            'ok' => $item->delete()
        ];
    }
}
