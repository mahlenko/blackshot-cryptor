<?php

namespace App\Models\Finder;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;
use Kalnoy\Nestedset\NodeTrait;
use Ramsey\Uuid\Uuid;

class Folder extends Model
{
    use HasFactory, NodeTrait;

    public const PUBLIC_FOLDER = 'public' . DIRECTORY_SEPARATOR;

    /**
     * @var string
     */
    protected $primaryKey = 'uuid';

    /**
     * @var bool
     */
    public $incrementing = false;

    /**
     * @var string
     */
    protected $keyType = 'string';

    /**
     * Создание каталога
     * @param string $name
     * @param string|null $parent_uuid
     * @return Folder
     */
    public static function createFolder(string $name, string $parent_uuid = null): Folder
    {
        /* смотрим что такого каталога ещё нет, если есть возвращаем его */
        $folder = Folder::where(['name' => $name, 'parent_id' => $parent_uuid])->first();
        if ($folder) return $folder;

        /* каталог приложения в котором храним все файлы */
        $app_folder = self::PUBLIC_FOLDER;

        /* поиск родительского каталога */
        if (!empty($parent_uuid)) {
            $parent = Folder::where(['uuid' => $parent_uuid])->first();
            $app_folder = $parent->path();
        }

        $folder = new self();
        $folder->uuid = Uuid::uuid4()->toString();
        $folder->name = $name;
        $folder->parent_id = $parent_uuid;
        $folder->path = Str::random(6);

        Storage::makeDirectory($app_folder . $folder->path);
        $folder->save();

        return $folder;
    }

    /**
     * Путь на диске к каталогу
     * @return string
     */
    public function path(): string
    {
        $folder_paths = $this->ancestors->sortBy('_lft')->pluck('path');
        $folder_paths->add($this->path);
        return self::PUBLIC_FOLDER . $folder_paths->join(DIRECTORY_SEPARATOR) . DIRECTORY_SEPARATOR;
    }

    /**
     *
     */
    protected static function booting()
    {
        parent::deleting(function($folder)
        {
            /* удалить пустые каталоги с сервера */
            $files = Storage::files($folder->path(), true);
            if (count($files) !== 0) {
                return false;
            }

            /* удаляем каталог с диска */
            Storage::deleteDirectory($folder->path());
        });
    }
}
