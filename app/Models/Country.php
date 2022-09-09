<?php

namespace App\Models;

use Anita\Traits\TranslateField;
use Astrotomic\Translatable\Contracts\Translatable as TranslatableContract;
use Astrotomic\Translatable\Translatable;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Kalnoy\Nestedset\NodeTrait;

class Country extends Model implements TranslatableContract
{
    use HasFactory, NodeTrait, Translatable, TranslateField;

    /**
     * @var string
     */
    protected $primaryKey = 'uuid';

    /**
     * @var string
     */
    protected $keyType = 'string';

    /**
     * @var bool
     */
    public $incrementing = false;

    /**
     * @var string[]
     */
    protected $fillable = ['alpha2', 'alpha3'];

    /**
     * @var string[]
     */
    public $translatedAttributes = ['name'];
}
