<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

/**
 * Sets the application locale from (in order of priority):
 *   1. ?lang= query parameter
 *   2. session('locale')
 *   3. config('app.locale')
 *
 * The /admin/locale POST endpoint stores the chosen locale in the session
 * so that subsequent requests honour it without a full page reload.
 */
class SetLocale
{
    public function handle(Request $request, Closure $next): Response
    {
        $supported = config('pos.locales', ['en', 'km']);

        $locale = $request->query('lang')
            ?: $request->session()->get('locale')
            ?: config('app.locale');

        if (!in_array($locale, $supported, true)) {
            $locale = config('app.locale');
        }

        app()->setLocale($locale);
        $request->session()->put('locale', $locale);

        return $next($request);
    }
}
