<?php

namespace App\Models\Navigation;

use Anita\Traits\TranslateField;
use App\Models\Catalog\Category;
use App\Models\Meta;
use App\Models\Page\Page;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasOne;
use Illuminate\Support\Facades\Cache;
use Kalnoy\Nestedset\NodeTrait;
use Astrotomic\Translatable\Contracts\Translatable as TranslatableContract;
use Astrotomic\Translatable\Translatable;

class NavigationItem extends Model implements TranslatableContract
{
    use HasFactory, NodeTrait, Translatable, TranslateField;

    /**
     *
     */
    const SHOW_GENERATE_CHILDREN = [
        Category::class,
        Page::class
    ];

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
    protected $fillable = ['uuid', 'url', 'meta_uuid', 'navigation_uuid', 'is_active', 'template', 'generate_catalog', 'generate_products'];

    /**
     * @var string[]
     */
    public $translatedAttributes = ['name'];

    /**
     * @var string
     */
    protected $keyType = 'string';

    /**
     * Параметры ссылки
     * @return HasOne
     */
    public function params(): HasOne
    {
        return $this
            ->hasOne(NavigationItemParam::class, 'uuid', 'uuid')
            ->withDefault();
    }

    /**
     * @return BelongsTo
     */
    public function meta(): BelongsTo
    {
        return $this->belongsTo(Meta::class, 'meta_uuid', 'uuid');
    }

    /**
     * @return BelongsTo
     */
    public function navigation(): BelongsTo
    {
        return $this->belongsTo(Navigation::class, 'navigation_uuid', 'uuid');
    }

    /**
     * @return string[]
     */
    protected function getScopeAttributes()
    {
        return ['navigation_uuid'];
    }

    /**
     *
     */
    protected static function booted()
    {
        static::created(function($item) {
            Navigation::forgetCache($item->navigation->key);
        });

        static::updated(function($item) {
            Navigation::forgetCache($item->navigation->key);
        });

        static::deleting(function ($item) {
            if ($item->params->icon) {
                $item->params->icon->delete();
            }

            foreach ($item->descendants as $child) {
                if ($child->params->icon) $child->params->icon->delete();
            }

            Navigation::forgetCache($item->navigation->key);
        });
    }

}
