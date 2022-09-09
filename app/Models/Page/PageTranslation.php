<?php

namespace App\Models\Page;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class PageTranslation extends Model
{
    use HasFactory;

    /**
     * @var array
     */
    protected $fillable = ['name', 'description', 'body'];

    /**
     * @var bool
     */
    public $timestamps = false;
}
