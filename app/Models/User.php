<?php

namespace App\Models;

use App\Models\Finder\File;
use App\Models\Finder\Folder;
use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Relations\HasOne;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Http\UploadedFile;
use Illuminate\Notifications\Notifiable;
use Illuminate\Support\Facades\Storage;

class User extends Authenticatable
{
    use HasFactory, Notifiable;

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'name',
        'email',
        'password',
    ];

    /**
     * The attributes that should be hidden for arrays.
     *
     * @var array
     */
    protected $hidden = [
        'password',
        'remember_token',
    ];

    /**
     * The attributes that should be cast to native types.
     *
     * @var array
     */
    protected $casts = [
        'email_verified_at' => 'datetime',
    ];

    /**
     * Administrator role
     */
    public const ROLE_ADMIN = 'admin';

    /**
     * Default user role
     */
    public const ROLE_USER = 'user';

    /**
     * @var string
     */
    protected $keyType = 'string';

    /**
     * @return bool
     */
    public function isAdmin(): bool
    {
        return $this->role === self::ROLE_ADMIN;
    }

    /**
     * Аватара пользователя
     * @return HasOne
     */
    public function avatar(): HasOne
    {
        return $this->hasOne(File::class, 'parent_uuid', 'id');
    }

    /**
     * @param string $default 404 | mp | identicon | monsterid | wavatar
     * @param int $size
     * @return string
     */
    public function getAvatarUrl(string $default = 'monsterid', int $size = 100): string
    {
        if ($this->avatar) {
            return Storage::url($this->avatar->thumbnail());
        }

        /* Gravatar */
        $url = 'https://www.gravatar.com/avatar/' . md5(strtolower($this->email));
        return $url . '?' . http_build_query([
            's' => $size,
            'd' => $default,
        ]);

    }

    /**
     * Аватарка пользователя
     * @param UploadedFile|null $uploadedFile
     */
    public function setAvatar(UploadedFile $uploadedFile = null): void
    {
        if (!$uploadedFile) return;

        /* Удалим предыдущее превью, если было */
        if ($this->avatar) $this->avatar->delete();

        /* Создание каталогов */
        $module_folder = Folder::createFolder(class_basename(self::class));

        /* Загружаем файл в каталог товара */
        File::upload($uploadedFile, $module_folder, $this->id);
    }
}
