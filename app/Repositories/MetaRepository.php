<?php


namespace App\Repositories;


use Illuminate\Support\Str;

class MetaRepository
{
    /**
     * Доступные шаблоны
     * @param string $model
     * @return \Illuminate\Support\Collection
     */
    public static function templates(string $model)
    {
        $template_name = 'web';

        $default_folder = resource_path('views/' . $template_name);

        $ignore_folder = ['auth', 'layouts', 'partials'];

        $items = collect();
        foreach (scandir($default_folder) as $folder) {
            if (Str::startsWith($folder, '.') || in_array($folder, $ignore_folder)) continue;

            $pattern = rtrim($default_folder .'/'. $folder, '/') . '/*.blade.php';

            $folder_collect = collect();

            foreach (glob($pattern) as $file) {
                $file = Str::replaceFirst($default_folder .'/', '', $file);
                $file_name = basename($file);

                $file = $template_name .'/'. $file;
                $key = Str::replaceLast('.blade.php', '', $file);
                $key = str_replace('/', '.', $key);

                $folder_collect->push([
                    'file' => $file_name,
                    'value' => $key
                ]);
            }

            if ($folder_collect->count()) {
                $items->push(['name' => Str::ucfirst($folder), 'items' => $folder_collect]);
            }
        }

        return $items;
    }

}
