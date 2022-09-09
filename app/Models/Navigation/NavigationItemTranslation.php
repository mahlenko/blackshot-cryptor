<?php

namespace App\Models\Navigation;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class NavigationItemTranslation extends Model
{
    use HasFactory;
    protected $fillable = ['name'];
}
