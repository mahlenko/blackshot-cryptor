<?php

namespace App\Providers;

use App\Models\Catalog\Category;
use App\Models\Catalog\CategoryTranslation;
use App\Models\Company\Company;
use App\Observers\CategoryObserver;
use App\Observers\CategoryTranslateObserver;
use App\Repositories\SettingRepository;
use Illuminate\Pagination\Paginator;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\URL;
use Illuminate\Support\Facades\View;
use Illuminate\Support\ServiceProvider;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     *
     * @return void
     */
    public function register()
    {
        $this->app->bind('path.public', function() {
            return realpath(base_path() . '/' . env('APP_PUBLIC_FOLDER', 'public'));
        });
    }

    /**
     * Bootstrap any application services.
     *
     * @return void
     */
    public function boot(Request $request)
    {
        // Set default locale
        $locale = in_array($request->segment(1), config('translatable.locales'))
            ? $request->segment(1)
            : config('translatable.locale');

        app()->setLocale($locale);

        try {
            /* проверяем что есть соединение с БД - при чистой установке будут ошибки если не делать проверку */
            DB::connection()->getPdo();
            View::share('settings', (new SettingRepository())->all('website', $locale));

            // добавить информацию о компаниях в шаблоны
            View::share('companies', Company::all()->load('image', 'phones', 'emails', 'address', 'websites', 'timework'));
        } catch (\PDOException $exception) {}

        /* использовать bootstrap для пагинации */
        Paginator::useBootstrap();

        /* принудительно использовать схему https или http */
        URL::forceScheme(env('APP_FORCE_SCHEME', $request->getScheme()));

        /* Registration Observers */
        Category::observe([CategoryObserver::class, CategoryTranslateObserver::class]);
        CategoryTranslation::observe(CategoryTranslateObserver::class);
    }
}
