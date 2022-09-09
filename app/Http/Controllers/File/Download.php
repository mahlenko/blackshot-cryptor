<?php

namespace App\Http\Controllers\File;

use App\Http\Controllers\Controller;
use App\Models\Finder\File;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class Download extends Controller
{
    /**
     * Скачивание файла с подсчетом скачиваний
     * @param string $uuid
     * @return \Symfony\Component\HttpFoundation\BinaryFileResponse
     */
    public function index(string $uuid)
    {
        $file = File::findOrFail($uuid);
        $file->downloads = $file->downloads + 1;
        $file->save();

        return response()->download(Storage::path($file->fullName()), $file->name);
    }
}
