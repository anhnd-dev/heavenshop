<?php

namespace App\Http\Requests\Frontend\Auth;

use Illuminate\Foundation\Http\FormRequest;

class CustomerRegisterRequest extends FormRequest
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

            'name' => [
                'required',
                'max:100'
            ],

            'phone' => [
                'required',
                'max:20',
                'unique:customers,phone'
            ],

            'email' => [
                'nullable',
                'email',
                'max:100',
                'unique:customers,email'
            ],

            'password' => [
                'required',
                'min:6',
                'confirmed'
            ]
        ];
    }

    /**
     * Messages
     */
    public function messages(): array
    {
        return [

            'name.required' =>
            'Vui lòng nhập họ tên',

            'phone.required' =>
            'Vui lòng nhập số điện thoại',

            'phone.unique' =>
            'Số điện thoại đã tồn tại',

            'email.email' =>
            'Email không hợp lệ',

            'email.unique' =>
            'Email đã tồn tại',

            'password.required' =>
            'Vui lòng nhập mật khẩu',

            'password.min' =>
            'Mật khẩu tối thiểu 6 ký tự',

            'password.confirmed' =>
            'Mật khẩu xác nhận không khớp'
        ];
    }
}
