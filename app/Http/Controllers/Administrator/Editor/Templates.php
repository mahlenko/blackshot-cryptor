<?php

namespace App\Http\Controllers\Administrator\Editor;

use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;

class Templates extends \App\Http\Controllers\Controller
{
    public function index()
    {
        $template_path = public_path(trim(env('TEMPLATE_EDITOR_FOLDER'), '/'));

        $templates = [];
        if (file_exists($template_path)) {
            foreach (glob($template_path . '/*.html') as $filename) {
                $templates[] = [
                    'title' => basename($filename),
                    'description' => '',
                    'url' => Str::replace(public_path(), '', $filename)
                ];
            }
        }

        return [
            'ok' => true,
            'data' => $templates
        ];
    }
}
