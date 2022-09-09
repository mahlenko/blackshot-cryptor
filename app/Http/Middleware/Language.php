<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Arr;
use Illuminate\Support\Facades\Route;

class Language
{
    /**
     * @param Request $request
     * @param Closure $next
     * @return RedirectResponse|mixed
     */
    public function handle(Request $request, Closure $next)
    {
        $segment_locale = $request->segment(1);

        if ($segment_locale == config('translatable.locale')) {
            $segments = $request->segments();
            Arr::forget($segments, [0]);

            return redirect()->to(implode('/', $segments), 301);
        }

        /* Dashboard только на русской версии */
        if (   $request->segment(1) != config('translatable.locale')
            && $request->segment(2) == config('anita.dashboard.prefix')
        ) {
            $segments = $request->segments();
            $segments[0] = config('translatable.locale');
            return redirect()->to(implode('/', $segments));
        }

        return $next($request);
    }
}
