<?php

namespace App\Models\Company;

use Anita\Traits\TranslateField;
use App\Http\Controllers\Administrator\Widget\Services\CompanyService;
use App\Models\Finder\File;
use App\Models\Meta;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Astrotomic\Translatable\Contracts\Translatable as TranslatableContract;
use Astrotomic\Translatable\Translatable;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\HasOne;
use Kalnoy\Nestedset\NodeTrait;
use Ramsey\Uuid\Uuid;

class Company extends Model implements TranslatableContract
{
    use HasFactory, Translatable, NodeTrait, \Anita\Traits\Meta, TranslateField;

    /**
     * Префикс ссылок
     */
    public const PREFIX = 'company';

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
    protected $fillable = ['uuid', 'type'];

    /**
     * @var array
     */
    public $translatedAttributes = ['name', 'description', 'body', 'widget_data'];

    /**
     * @var string
     */
    protected $keyType = 'string';


    /**
     * Изображение офиса
     * @return HasOne
     */
    public function image(): HasOne
    {
        return $this->hasOne(File::class, 'parent_uuid', 'uuid');
    }

    /**
     * @return HasMany
     */
    public function phones(): HasMany
    {
        return $this->hasMany(CompanyPhone::class, 'company_uuid', 'uuid')
            ->defaultOrder();
    }

    /**
     * @return HasMany
     */
    public function emails(): HasMany
    {
        return $this->hasMany(CompanyEmail::class, 'company_uuid', 'uuid')
            ->defaultOrder();
    }

    /**
     * @return HasMany
     */
    public function websites(): HasMany
    {
        return $this->hasMany(CompanyWebsite::class, 'company_uuid', 'uuid')
            ->defaultOrder();
    }

    /**
     * @return HasOne
     */
    public function address(): HasOne
    {
        return $this->hasOne(CompanyAddress::class, 'company_uuid', 'uuid');
    }

    /**
     * @return HasOne
     */
    public function timework(): HasOne
    {
        return $this->hasOne(CompanyTimework::class, 'company_uuid', 'uuid');
    }

    /**
     *
     */
    public function updateWidget()
    {
        $this->load(['image', 'phones', 'emails', 'websites', 'address']);
        $data = $this->toArray();

        unset(
            $data['uuid'],
            $data['widget_data'],
            $data['translations'],
            $data['meta'],
            $data['_lft'],
            $data['_rgt'],
            $data['parent_id'],
            $data['created_at'],
            $data['updated_at'],
        );

        foreach ($data as $index => $values) {
            if (is_array($values) && key_exists('translations', $values)) {
                unset($data[$index]['translations']);
            }
        }

        $this->widget_data = json_encode($data);
        $this->save();
    }

    /**
     * @return string
     */
    protected static function moduleName(): string
    {
        return __('company.title');
    }

    /**
     *
     */
    protected static function booted()
    {
        self::deleting(function ($company) {
            /* @var Company $company */
            if ($company->image) {
                $company->image->delete();
            }
        });

        self::updated(function(Company $company) {
            CompanyService::clearCacheByObjectUuid($company->uuid);
        });
    }
}
