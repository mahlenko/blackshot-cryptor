<?php

namespace App\Models\Catalog;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasOne;

class FeatureCategories extends Model
{
    use HasFactory;

    /**
     * @var bool
     */
    public $timestamps = false;

    /**
     * @var string[]
     */
    public $with = ['object'];

    /**
     * @return HasOne
     */
    public function object(): HasOne
    {
        return $this->hasOne(Category::class, 'uuid', 'category_uuid');
    }
}
