<?php

namespace App\Models;

use App\Models\Finder\File;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Astrotomic\Translatable\Contracts\Translatable as TranslatableContract;
use Astrotomic\Translatable\Translatable;
use Illuminate\Database\Eloquent\Relations\HasOne;

class Setting extends Model implements TranslatableContract
{
    use HasFactory, Translatable;

    /**
     * @var string
     */
    protected $primaryKey = 'uuid';

    /**
     * @var bool
     */
    public $incrementing = false;

    /**
     * @var array
     */
    public $translatedAttributes = ['value'];

    /**
     * @var string
     */
    protected $keyType = 'string';

    /**
     * @return HasOne
     */
    public function file()
    {
        return $this->hasOne(File::class, 'parent_uuid', 'uuid');
    }
}
