<?php

namespace App\Models\Company;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class CompanyPhoneTranslation extends Model
{
    use HasFactory;

    protected $fillable = ['description'];

    /**
     * @var bool
     */
    public $timestamps = false;
}
