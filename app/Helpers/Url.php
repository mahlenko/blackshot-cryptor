<?php
namespace App\Helpers;

class Url
{
    /**
     * @param string $name
     * @param array|null $parameters
     * @param string|null $locale
     * @return string
     */
    public static function route_locale(string $name, array $parameters = null, string $locale = null): string
    {
        if (!in_array($locale, config('translatable.locales'))) $locale = config('translatable.locale');
        if ($locale == config('translatable.locale')) $locale = '';

        $url = route($name, $parameters, false);
        $segments = array_values(array_filter(explode('/', $url)));

        if ($segments && in_array($segments[0], config('translatable.locales'))) {
            array_shift($segments);
            $url = implode('/', $segments);
        }

        return \Illuminate\Support\Facades\URL::to($locale .'/'. trim($url, '/'));
    }
}
