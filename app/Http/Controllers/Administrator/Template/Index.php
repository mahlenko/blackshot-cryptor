<?php

namespace App\Http\Controllers\Administrator\Template;

use App\Http\Controllers\Controller;
use Illuminate\Contracts\Foundation\Application;
use Illuminate\Contracts\View\Factory;
use Illuminate\Contracts\View\View;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;

class Index extends Controller
{
    /**
     * Каталог шаблона
     */
    private const RESOURCE_PATH = 'views/web';

    /**
     * @return Application|Factory|View
     */
    public function index()
    {
        $template_path = resource_path(self::RESOURCE_PATH);

        return view('administrator.template.index', [
            'files_list' => $this->glob($template_path, '.blade.php')
        ]);
    }

    /**
     * @param string $path
     * @param string $pattern
     * @return array
     */
    private function glob(string $path, string $pattern): array
    {
        $path = rtrim($path, DIRECTORY_SEPARATOR) . DIRECTORY_SEPARATOR;
        $list = glob($path . '*', GLOB_MARK);

        $files = [];
        foreach ($list as $index => $item) {

            $path = str_replace(resource_path(self::RESOURCE_PATH), '', $item);

            if (is_dir($item)) {

                $children = $this->glob($item, $pattern);
                if (count($children) == 0) continue;

                $files[$index] = [
                    'type' => 'folder',
                    'name' => basename($item),
                    'path' => $path,
                    'children' => $children
                ];
            } elseif (Str::endsWith($item, $pattern)) {
                $files[$index] = [
                    'type' => 'file',
                    'name' => basename($item),
                    'path' => $path,
                    'children' => null
                ];
            }
        }

        return array_values(array_filter($files));
    }
}
