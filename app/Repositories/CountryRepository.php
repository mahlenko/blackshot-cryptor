<?php

namespace App\Repositories;

use App\Models\Country;
use Illuminate\Support\Str;
use InvalidArgumentException;

class CountryRepository
{

    /**
     * @param string $locale
     * @param string $uuid
     * @param string $name
     * @param string|null $alpha2
     * @param string|null $alpha3
     * @return Country
     */
    public function store(
        string $locale,
        string $uuid,
        string $name,
        string $alpha2 = null,
        string $alpha3 = null
    ): Country {

        $country = Country::find($uuid);

        if (!$country) {
            $country = new Country();
            $country->uuid = $uuid;
        }

        $alpha2 = Str::upper($alpha2);
        $alpha3 = Str::upper($alpha3);

        if (!empty($alpha2)) {
            $unique = Country::where('alpha2', $alpha2)->where('uuid', '!=', $uuid)->count();
            if ($unique) {
                throw new InvalidArgumentException('Страна с двухзначным кодом '. $alpha2 .' уже есть в справочнике.');
            }
        }

        if (!empty($alpha3)) {
            $unique = Country::where('alpha3', $alpha3)->where('uuid', '!=', $uuid)->count();
            if ($unique) {
                throw new InvalidArgumentException('Страна с трехзначным кодом '. $alpha2 .' уже есть в справочнике.');
            }
        }

        $country->setDefaultLocale($locale)
            ->fill([
                'name' => $name,
                'alpha2' => $alpha2,
                'alpha3' => $alpha3,
            ]);

        $country->save();
        return $country;
    }

}
