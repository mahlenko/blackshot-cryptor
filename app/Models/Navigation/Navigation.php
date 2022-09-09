<?php

namespace App\Models\Navigation;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Support\Facades\Cache;

class Navigation extends Model
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
     * @return HasMany
     */
    public function items(): HasMany
    {
        return $this->hasMany(NavigationItem::class, 'navigation_uuid', 'uuid')
            ->defaultOrder();
    }

    /**
     * Удалит свой кеш
     */
    public function clearCache()
    {
        self::forgetCache($this->key);
    }

    /**
     * Очистит кеш меню
     * @param string $key
     */
    public static function forgetCache(string $key)
    {
        foreach (config('translatable.locales') as $locale) {
            Cache::forget('navigation.'. $key .'.'. $locale);

            /* удалит кеш view */
            Cache::forget('navigation_view_' . $key.'.'.$locale);
        }

    }

    /**
     *
     */
    protected static function booted()
    {
        static::deleting(function ($navigation) {
            foreach ($navigation->items as $item) {
                $item->delete();
            }

            $navigation->clearCache();
        });
    }

}
