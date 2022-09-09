<?php

namespace App\Models\Company;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class CompanyEmailTranslation extends Model
{
    use HasFactory;

    /**
     * @var array
     */
    protected $fillable = ['description'];

    /**
     * @var bool
     */
    public $timestamps = false;
}
