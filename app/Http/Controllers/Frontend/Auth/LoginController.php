<?php

namespace App\Http\Controllers\Frontend\Auth;

use App\Http\Controllers\Controller;

use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;

use App\Models\Customer;

use App\Http\Requests\Frontend\Auth\CustomerLoginRequest;
use App\Http\Requests\Frontend\Auth\CustomerRegisterRequest;

class LoginController extends Controller
{
    // =========================
    // AJAX LOGIN
    // =========================
    public function ajaxLogin(CustomerLoginRequest $request)
    {
        // =========================
        // LOGIN FIELD
        // =========================
        $field = filter_var(
            $request->login,
            FILTER_VALIDATE_EMAIL
        )
            ? 'email'
            : 'phone';

        // =========================
        // CREDENTIALS
        // =========================
        $credentials = [

            $field => $request->login,

            'password' => $request->password
        ];

        // =========================
        // LOGIN
        // =========================
        if (!Auth::guard('customer')->attempt($credentials)) {

            return response()->json([

                'success' => false,

                'message' =>
                'Thông tin đăng nhập không chính xác'

            ], 422);
        }

        return response()->json([

            'success' => true,

            'message' =>
            'Đăng nhập thành công'
        ]);
    }

    // =========================
    // AJAX REGISTER
    // =========================
    public function ajaxRegister(CustomerRegisterRequest $request)
    {
        // =========================
        // CREATE CUSTOMER
        // =========================
        $customer = Customer::create([

            'name' => $request->name,

            'phone' => $request->phone,

            'email' => $request->email,

            'password' => Hash::make(
                $request->password
            )
        ]);

        // =========================
        // AUTO LOGIN
        // =========================
        Auth::guard('customer')
            ->login($customer);

        return response()->json([

            'success' => true,

            'message' =>
            'Đăng ký thành công'
        ]);
    }
}
