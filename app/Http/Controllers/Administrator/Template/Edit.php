<?php

namespace App\Http\Controllers\Administrator\Template;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

class Edit extends Controller
{
    public function index(Request $request)
    {
        if (!$request->has('file')) {
            return [
                'ok' => false,
                'description' => ''
            ];
        }

        $file = $request->input('file');

        return [
            'ok' => true,
            'content' => file_get_contents(resource_path('views/web' . $file))
        ];
    }
}
