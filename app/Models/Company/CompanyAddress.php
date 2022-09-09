<?php

namespace App\Models\Company;

use Anita\Traits\TranslateField;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Astrotomic\Translatable\Contracts\Translatable as TranslatableContract;
use Astrotomic\Translatable\Translatable;

class CompanyAddress extends Model implements TranslatableContract
{
    use HasFactory, Translatable, TranslateField;

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
}
