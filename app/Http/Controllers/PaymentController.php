<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class PaymentController extends Controller
{
    public function index() // :GET
    {
        return view('pages.payment.index');
    }
}
