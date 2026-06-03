<?php

namespace App\Http\Requests\Frontend\Checkout;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Support\Facades\Auth;

class CheckoutPlaceOrderRequest extends FormRequest
{
    public function authorize(): bool
    {
        return Auth::guard('customer')->check();
    }

    public function rules(): array
    {
        return [

            /*
            |--------------------------------------------------------------------------
            | SHIPPING
            |--------------------------------------------------------------------------
            */

            'shipping_name' => [
                'required',
                'string',
                'max:100'
            ],

            'shipping_phone' => [
                'required',
                'string',
                'max:20'
            ],

            'shipping_email' => [
                'nullable',
                'email',
                'max:100'
            ],

            'shipping_province' => [
                'required',
                'string',
                'max:100'
            ],

            'shipping_district' => [
                'required',
                'string',
                'max:100'
            ],

            'shipping_ward' => [
                'required',
                'string',
                'max:100'
            ],

            'shipping_address' => [
                'required',
                'string',
                'max:255'
            ],

            /*
            |--------------------------------------------------------------------------
            | PAYMENT
            |--------------------------------------------------------------------------
            */

            'payment_method' => [
                'required',
                'in:cod,vnpay,momo,zalopay'
            ],

            /*
            |--------------------------------------------------------------------------
            | NOTE
            |--------------------------------------------------------------------------
            */

            'note' => [
                'nullable',
                'string',
                'max:1000'
            ],

            /*
            |--------------------------------------------------------------------------
            | ADDRESS BOOK
            |--------------------------------------------------------------------------
            */

            'customer_address_id' => [
                'nullable',
                'integer',
                'exists:customer_addresses,id'
            ],
        ];
    }

    public function messages(): array
    {
        return [

            'shipping_name.required' =>
            'Vui lòng nhập họ tên',

            'shipping_phone.required' =>
            'Vui lòng nhập số điện thoại',

            'shipping_province.required' =>
            'Vui lòng chọn tỉnh/thành phố',

            'shipping_district.required' =>
            'Vui lòng chọn quận/huyện',

            'shipping_ward.required' =>
            'Vui lòng chọn phường/xã',

            'shipping_address.required' =>
            'Vui lòng nhập địa chỉ',

            'payment_method.required' =>
            'Vui lòng chọn phương thức thanh toán',
        ];
    }
}
