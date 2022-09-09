<?php

namespace App\Http\Controllers\Administrator\Finder;

use App\Http\Controllers\Controller;
use App\Models\Finder\File;
use Illuminate\Http\Request;

class FileEdit extends Controller
{
    public function index(string $locale, string $uuid)
    {
        return view('administrator.finder.file.edit', [
            'uuid' => $uuid,
            'locale' => $locale,
            'object' => $object = File::where(['uuid' => $uuid])->first(),
            'back' => [
                'route' => back()
            ],
            'routes' => [
                'store' => 'admin.finder.file.store',
                'edit' => 'admin.finder.file.edit'
            ],
            'title' => $object->name,
            'description' => null,
            'switcher_data' => ['uuid' => $uuid],
            'max_width' => 800,
            'chunks_template' => [
                [
                    'icon' => '<i class="fas fa-cog me-1"></i>',
                    'key' => 'general_file_object',
                    'name' => __('global.tabs.general'),
                    'template' => 'administrator.finder.file.general',
                    'data' => []
                ],
            ]
        ]);
    }
}
