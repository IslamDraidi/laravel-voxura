<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Session;

class LanguageController extends Controller
{
    public function switch(Request $request)
    {
        $locale = $request->input('locale');

        if (!in_array($locale, config('app.available_locales', ['en', 'ar']))) {
            return back();
        }

        Session::put('locale', $locale);

        if (auth()->check()) {
            auth()->user()->update(['preferred_locale' => $locale]);
        }

        return back()->with('locale_changed', true);
    }
}
