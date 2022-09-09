<?php

namespace App\Http\Controllers\Administrator\Finder;

use App\Http\Controllers\Controller;
use App\Models\Finder\File;
use App\Models\Finder\Folder;
use Exception;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class EditorUpload extends Controller
{
    /**
     * Загрузка изображений в каталог
     * @param Request $request
     * @return array
     */
    public function index(Request $request)
    {
        if ($request->hasFile('file')) {
            try {
                $file = File::upload(
                    $request->file('file'),
                    $model_folder = Folder::createFolder('editor')
                );

                return [
                    'location' => Storage::url($file->fullName())
                ];
            } catch (Exception $e) {
                return [ /* @todo не обрабатывается в JS */
                    'ok' => false,
                    'error' => $e->getMessage()
                ];
            }
        }
    }
}
