<?php

namespace App\Models;

use App\Models\Finder\File;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasOne;

class SettingTranslation extends Model
{
    use HasFactory;

    /**
     * @var string[]
     */
    protected $fillable = ['value'];

    /**
     * @var bool
     */
    public $timestamps = false;

    /**
     * @var string[]
     */
    protected $with = ['file'];

    /**
     * @return HasOne
     */
    public function file(): HasOne
    {
        return $this->hasOne(File::class, 'uuid', 'value')->with('translations');
    }
}
