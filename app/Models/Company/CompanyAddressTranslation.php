<?php

namespace App\Models\Company;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class CompanyAddressTranslation extends Model
{
    use HasFactory;

    /**
     * @var array
     */
    protected $fillable = ['value'];

    /**
     * @var bool
     */
    public $timestamps = false;
}
