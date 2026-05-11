<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\App;
use Illuminate\Support\Facades\Session;

class SetLocale
{
    public function handle(Request $request, Closure $next)
    {
        if (auth()->check() && auth()->user()->preferred_locale) {
            $locale = auth()->user()->preferred_locale;
        } elseif (Session::has('locale')) {
            $locale = Session::get('locale');
        } else {
            $locale = config('app.locale', 'en');
        }

        if (!in_array($locale, config('app.available_locales', ['en', 'ar']))) {
            $locale = 'en';
        }

        App::setLocale($locale);

        return $next($request);
    }
}
