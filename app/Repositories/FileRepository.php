<?php


namespace App\Repositories;


use App\Models\Finder\File;

class FileRepository
{
    /**
     * Сохранит параметры файла
     * @param string $locale
     * @param array $data
     * @return mixed
     */
    public function store(string $locale, array $data)
    {
        $default_locale = app()->getLocale();
        app()->setLocale($locale);

        $file = File::where(['uuid' => $data['uuid']])->firstOrFail();

        $file->name = $data['name'] .'.'. $file->extension();
        $file->alt = $data['alt'];
        $file->title = $data['title'];
        $file->description = $data['description'];

        $file->save();

        app()->setLocale($default_locale);

        return $file;
    }
}
