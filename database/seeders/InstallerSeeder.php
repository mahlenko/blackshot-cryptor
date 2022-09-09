<?php

namespace Database\Seeders;

use App\Repositories\CategoryRepository;
use App\Repositories\PageRepository;
use Illuminate\Database\Seeder;
use Ramsey\Uuid\Uuid;

class InstallerSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        /* home page */
        (new PageRepository())->store(
            Uuid::uuid4()->toString(),
            config('translatable.locale'),
            ['name' => __('page.columns.is_homepage')],
            ['position' => 'root', 'parent_id' => null],
            ['slug' => '/', 'is_active' => true],
            []
        );

        /* catalog homepage */
        $catalog = (new CategoryRepository())->save(
            Uuid::uuid4()->toString(),
            config('translatable.locale'),
            ['name' => 'catalog'],
            ['position' => 'root', 'parent_id' => null],
            ['is_active' => true]
        );

        $catalog->name = __('catalog.title');
        $catalog->save();

        $this->call(CountrySeeder::class);
    }
}
