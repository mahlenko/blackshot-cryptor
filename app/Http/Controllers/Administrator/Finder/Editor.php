<?php

namespace App\Http\Controllers\Administrator\Finder;

use App\Http\Controllers\Controller;
use App\Models\Finder\File;
use App\Models\Finder\Folder;
use Illuminate\Contracts\Foundation\Application;
use Illuminate\Contracts\View\Factory;
use Illuminate\Contracts\View\View;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class Editor extends Controller
{
    /**
     * @param Request $request
     * @param string|null $uuid
     * @return Application|Factory|View
     */
    public function index(Request $request, string $uuid = null)
    {
        if ($uuid) {
            $folder = Folder::find($uuid);
        } else {
            $folder = Folder::createFolder('Editor');
        }

        return view(
            $request->has('iframe')
                ? 'administrator.finder.iframe'
                : 'administrator.finder.home',
            array_merge([
                'current' => $folder,
                'is_iframe' => $request->has('iframe'),
                'breadcrumbs_data' => [
                    'folder' => $folder,
                    'items' => $breadcrumbs = $folder->ancestors->sortBy('_lft')
                ],
                'breadcrumbs' => $breadcrumbs,
            ], $this->readPath($folder))
        );
    }

    /**
     * Вернет каталоги и файлы в выбранном каталоге
     * @param Folder|null $folder
     * @return array
     */
    public function readPath(Folder $folder = null): array
    {
        if ($folder === null) {
            $folder = Folder::createFolder('Editor');
        }

        return [
            'uuid' => $folder->uuid,
            'files' => File::where(['folder_uuid' => $folder->uuid ?? null])->defaultOrder()->get(),
            'folders' => Folder::where(['parent_id' => $folder->uuid ?? null])->get()
        ];
    }

}
