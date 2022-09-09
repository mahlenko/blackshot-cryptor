<?php

namespace App\Models\Navigation;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class NavigationItemParamTranslation extends Model
{
    use HasFactory;

    protected $fillable = ['title', 'iconAlt'];
}
