<?php

namespace App\Http\Controllers\Administrator\Finder;

use App\Http\Controllers\Controller;
use App\Models\Finder\File;
use App\Models\Finder\Folder;
use Exception;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class Upload extends Controller
{
    /**
     * Загрузка изображений в каталог
     * @param Request $request
     * @return \Illuminate\Http\RedirectResponse
     */
    public function index(Request $request): \Illuminate\Http\RedirectResponse
    {
        if ($request->hasFile('files')) {
            try {
                /* создадим каталог если еще нет */
                $model_folder = Folder::find($request->input('folder_uuid'));

                /* загрузим выбранные файлы */
                foreach ($request->file('files') as $file) {
                    File::upload($file, $model_folder);
                }
            } catch (Exception $e) {
                flash($e->getMessage())->error();
            }
        }

        return back();
    }
}
