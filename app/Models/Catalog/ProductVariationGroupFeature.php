<?php

namespace App\Models\Catalog;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class ProductVariationGroupFeature extends Model
{
    use HasFactory;

    /**
     * @var string
     */
    protected $primaryKey = 'uuid';

    /**
     * @var bool
     */
    public $incrementing = false;

    /**
     * @var string
     */
    protected $keyType = 'string';

    /**
     * Группа характеристики
     * @return BelongsTo
     */
    public function group(): BelongsTo
    {
        return $this->belongsTo(ProductVariationGroup::class, 'uuid', 'group_uuid');
    }

    /**
     * Объект характеристики
     * @return BelongsTo
     */
    public function object(): BelongsTo
    {
        return $this->belongsTo(Feature::class, 'feature_uuid', 'uuid');
    }
}
