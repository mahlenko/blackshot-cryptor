<?php

namespace App\Providers;

use Illuminate\Cache\RateLimiting\Limit;
use Illuminate\Foundation\Support\Providers\RouteServiceProvider as ServiceProvider;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\RateLimiter;
use Illuminate\Support\Facades\Route;

class RouteServiceProvider extends ServiceProvider
{
    /**
     * The path to the "home" route for your application.
     *
     * This is used by Laravel authentication to redirect users after login.
     *
     * @var string
     */
    public const HOME = '/dashboard';

    /**
     * The controller namespace for the application.
     *
     * When present, controller route declarations will automatically be prefixed with this namespace.
     *
     * @var string|null
     */
    // protected $namespace = 'App\\Http\\Controllers';

    /**
     * Define your route model bindings, pattern filters, etc.
     *
     * @return void
     */
    public function boot()
    {
        $this->configureRateLimiting();

        $this->routes(function () {
            Route::prefix('api')
                ->middleware('api')
                ->namespace($this->namespace)
                ->group(base_path('routes/api.php'));

            Route::middleware('web')
                ->namespace($this->namespace)
                ->group(base_path('routes/web.php'));
        });

        /*  */
        $locales = collect();
        if (config('translatable.locales')) {
            foreach (config('translatable.locales') as $key => $locale) {
                if (is_array($locale)) {
                    foreach($locale as $chunk) {
                        $locales->add($key.'_'.$chunk);
                    }
                } else {
                    $locales->add($locale);
                }
            }

        } else {
            $locales->add('ru');
        }

        Route::pattern('locale', '('.  implode('|', $locales->toArray()).')');
        Route::pattern('uuid', '[0-9a-f]{8}-[0-9a-f]{4}-[0-9a-f]{4}-[0-9a-f]{4}-[0-9a-f]{12}');
    }

    /**
     * Переписать роутеры для для сайта с использованием локализации
     */
    protected function loadRoutes()
    {
        $locale = app()->getLocale();

        Route::group([
            'middleware' => 'web',
            'namespace' => $this->namespace,
            'prefix' => $locale == config('translatable.locale') ? null : $locale
        ], function ($router) {
            require base_path('routes/web.php');
        });
    }

    /**
     * Configure the rate limiters for the application.
     *
     * @return void
     */
    protected function configureRateLimiting()
    {
        RateLimiter::for('api', function (Request $request) {
            return Limit::perMinute(60)->by(optional($request->user())->id ?: $request->ip());
        });
    }
}
