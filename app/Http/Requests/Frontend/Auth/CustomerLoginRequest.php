<?php

namespace App\Http\Requests\Frontend\Auth;

use Illuminate\Foundation\Http\FormRequest;

class CustomerLoginRequest extends FormRequest
{
    /**
     * Authorize
     */
    public function authorize(): bool
    {
        return true;
    }

    /**
     * Rules
     */
    public function rules(): array
    {
        return [

            'login' => [
                'required'
            ],

            'password' => [
                'required',
                'min:6'
            ]
        ];
    }

    /**
     * Messages
     */
    public function messages(): array
    {
        return [

            'login.required' =>
            'Vui lòng nhập email hoặc số điện thoại',

            'password.required' =>
            'Vui lòng nhập mật khẩu',

            'password.min' =>
            'Mật khẩu tối thiểu 6 ký tự'
        ];
    }
}
