<?php

namespace Database\Seeders;

use App\Models\Country;
use Illuminate\Database\Seeder;
use Illuminate\Support\Collection;
use PHPUnit\Framework\Constraint\Count;
use Ramsey\Uuid\Uuid;

class CountrySeeder extends Seeder
{
    const REPOSITORIES = [
        'en' => [
            'url' => 'https://raw.githubusercontent.com/lukes/ISO-3166-Countries-with-Regional-Codes/master/all/all.json',
            'keys_code' => ['name' => 'name', 'alpha2' => 'alpha-2', 'alpha3' => 'alpha-3']
            ],
        'ru' => [
            'url' => 'https://gist.githubusercontent.com/sanchezzzhak/8606e9607396fb5f8216/raw/8a7209a4c1f4728314ef4208abc78be6e9fd5a2f/ISO3166_RU.json',
            'keys_code' => ['name' => 'name_ru', 'alpha2' => 'iso_code2', 'alpha3' => 'iso_code3']
            ]
    ];

    const DEFAULT_REPOSITORIES = 'en'; // config('translatable.locale')

    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $repositories = $this->getRepositories()->sortBy(function($items, $key) {
            return $key !== self::DEFAULT_REPOSITORIES;
        });

        foreach ($repositories as $locale => $items) {
            $keys_code = self::REPOSITORIES[$locale]['keys_code'];

            foreach ($items as $item) {
                $country_data = [
                    'alpha2' => $item[$keys_code['alpha2']],
                    'alpha3' => $item[$keys_code['alpha3']]
                ];

                $country = Country::where($country_data)->first();
                if (!$country) {
                    $country = new Country();
                    $country->uuid = Uuid::uuid4();
                    $country->fill($country_data);
                }

                $country->setDefaultLocale($locale);
                $country->name = $item[$keys_code['name']];

                $country->save();
            }
        }
    }

    /**
     * @return Collection
     */
    public function getRepositories(): Collection
    {
        $repositories = collect();
        foreach (self::REPOSITORIES as $locale => $repository) {
            $content = file_get_contents($repository['url']);
            if (empty($content)) continue;

            $collection = collect();
            foreach (json_decode($content) as $item) {
                $collection->push(collect($item));
            }

            $repositories->put($locale, $collection);
        }

        return $repositories;
    }
}
