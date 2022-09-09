<?php

namespace App\Models\Company;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Kalnoy\Nestedset\NodeTrait;

class CompanyWebsite extends Model
{
    use HasFactory, NodeTrait;

    /**
     * @var string
     */
    protected $primaryKey = 'uuid';

    /**
     * @var array
     */
    protected $fillable = ['value'];

    /**
     * @var bool
     */
    public $incrementing = false;

    /**
     * @var string
     */
    protected $keyType = 'string';
}
