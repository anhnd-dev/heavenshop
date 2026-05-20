<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
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
