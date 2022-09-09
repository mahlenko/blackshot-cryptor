<?php

namespace App\Models\Catalog;

use Anita\Traits\TranslateField;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Astrotomic\Translatable\Contracts\Translatable as TranslatableContract;
use Astrotomic\Translatable\Translatable;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Kalnoy\Nestedset\NodeTrait;

class FeatureGroup extends Model implements TranslatableContract
{
    use HasFactory, Translatable, TranslateField, NodeTrait;

    /**
     * @var string
     */
    protected $primaryKey = 'uuid';

    /**
     * @var bool
     */
    public $incrementing = false;

    /**
     * @var array
     */
    public $fillable = ['is_active'];

    /**
     * @var string[]
     */
    public $translatedAttributes = ['name', 'body'];

    /**
     * @var string
     */
    protected $keyType = 'string';

    /**
     * Характеристики в группе
     * @return HasMany
     */
    public function features(): HasMany
    {
        return $this->hasMany(Feature::class, 'feature_group_uuid', 'uuid');
    }
}
