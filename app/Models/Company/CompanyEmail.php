<?php

namespace App\Models\Company;

use Anita\Traits\TranslateField;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Astrotomic\Translatable\Contracts\Translatable as TranslatableContract;
use Astrotomic\Translatable\Translatable;
use Kalnoy\Nestedset\NodeTrait;

class CompanyEmail extends Model implements TranslatableContract
{
    use HasFactory, Translatable, TranslateField, NodeTrait;

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
    protected $fillable = ['value'];

    /**
     * @var array
     */
    public $translatedAttributes = ['description'];

    /**
     * @var string[]
     */
//    protected $with = ['translations'];

    /**
     * @var string
     */
    protected $keyType = 'string';
}
