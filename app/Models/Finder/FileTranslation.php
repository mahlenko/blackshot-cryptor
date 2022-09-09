<?php

namespace App\Models\Finder;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class FileTranslation extends Model
{
    use HasFactory;

    /**
     * @var string[]
     */
    protected $fillable = ['alt', 'title', 'description'];

    /**
     * @var bool
     */
    public $timestamps = false;
}
