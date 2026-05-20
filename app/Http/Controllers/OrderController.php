<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class OrderController extends Controller
{
    public function complete() // :GET
    {
        return view('pages.complete.index');
    }
}
