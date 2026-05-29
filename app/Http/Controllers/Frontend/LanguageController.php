<?php

namespace App\Http\Controllers\Frontend;

use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Session;

class LanguageController extends Controller
{
    public function switchLang($lang) // :GET
    {
        if (array_key_exists($lang, config('app.languages'))) {
            Session::put('user_locale', $lang);
        }

        return redirect()->back();
    }
}
