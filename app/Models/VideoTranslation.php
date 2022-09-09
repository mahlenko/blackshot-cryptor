<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class VideoTranslation extends Model
{
    use HasFactory;

    /**
     * @var array
     */
    protected $fillable = ['name', 'description'];

    /**
     * @var bool
     */
    public $timestamps = false;
}
