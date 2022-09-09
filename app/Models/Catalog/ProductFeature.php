<?php

namespace App\Models\Catalog;

use Anita\Traits\TranslateField;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Astrotomic\Translatable\Contracts\Translatable as TranslatableContract;
use Astrotomic\Translatable\Translatable;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class ProductFeature extends Model implements TranslatableContract
{
    use HasFactory, Translatable, TranslateField;

    /**
     * @var string
     */
    public $primaryKey = 'uuid';

    /**
     * @var bool
     */
    public $incrementing = false;

    /**
     * @var array
     */
    protected $fillable = ['product_uuid', 'feature_uuid', 'feature_variant_uuid'];

    /**
     * @var bool
     */
    public $timestamps = false;

    /**
     * @var string[]
     */
    public $translatedAttributes = ['value'];

    /**
     * @var array
     */
    protected $with = ['feature', 'variant'];

    /**
     * @var string
     */
    protected $keyType = 'string';

    /**
     * Характеристика
     * @return BelongsTo
     */
    public function feature(): BelongsTo
    {
        return $this->belongsTo(Feature::class, 'feature_uuid', 'uuid');
    }

    /**
     * Значение характеристики
     * @return BelongsTo
     */
    public function variant(): BelongsTo
    {
        return $this->belongsTo(FeatureVariant::class, 'feature_variant_uuid', 'uuid');
    }

}
