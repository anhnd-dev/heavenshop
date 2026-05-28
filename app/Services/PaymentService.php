<?php

namespace App\Services;

class PaymentService
{
    /*
    |--------------------------------------------------------------------------
    | CREATE VNPAY URL
    |--------------------------------------------------------------------------
    */

    public function createVnpayUrl(
        array $data
    ): string {

        $vnp_TmnCode =
            config('services.vnpay.tmn_code');

        $vnp_HashSecret =
            config('services.vnpay.hash_secret');

        $vnp_Url =
            config('services.vnpay.url');

        $vnp_Returnurl =
            route('vnpay.return');

        $inputData = [

            "vnp_Version" => "2.1.0",

            "vnp_TmnCode" => $vnp_TmnCode,

            "vnp_Amount" =>
            (int)$data['amount'] * 100,

            "vnp_Command" => "pay",

            "vnp_CreateDate" =>
            now()->format('YmdHis'),

            "vnp_ExpireDate" =>
            now()
                ->addMinutes(15)
                ->format('YmdHis'),

            "vnp_CurrCode" => "VND",

            "vnp_IpAddr" =>
            request()->ip(),

            "vnp_Locale" => "vn",

            "vnp_OrderInfo" =>
            'Thanh toán đơn hàng '
                . $data['order_code'],

            "vnp_OrderType" =>
            "billpayment",

            "vnp_ReturnUrl" =>
            $vnp_Returnurl,

            "vnp_TxnRef" =>
            $data['order_code'],
        ];

        ksort($inputData);

        $query = '';

        $hashData = '';

        $i = 0;

        foreach ($inputData as $key => $value) {

            if ($i == 1) {

                $hashData .= '&';
            }

            $hashData .=
                urlencode($key)
                . "=" .
                urlencode($value);

            $query .=
                urlencode($key)
                . "=" .
                urlencode($value)
                . '&';

            $i = 1;
        }

        $vnpSecureHash = hash_hmac(

            'sha512',

            $hashData,

            $vnp_HashSecret
        );

        return $vnp_Url
            . "?"
            . $query
            . 'vnp_SecureHash='
            . $vnpSecureHash;
    }

    /*
    |--------------------------------------------------------------------------
    | VERIFY VNPAY
    |--------------------------------------------------------------------------
    */

    public function verify(
        array $input
    ): bool {

        $secureHash =
            $input['vnp_SecureHash']
            ?? '';

        unset($input['vnp_SecureHash']);
        unset($input['vnp_SecureHashType']);

        ksort($input);

        $hashData = '';

        $i = 0;

        foreach ($input as $key => $value) {

            if ($i == 1) {

                $hashData .= '&';
            }

            $hashData .=
                urlencode($key)
                . "=" .
                urlencode($value);

            $i = 1;
        }

        $secureHashCheck = hash_hmac(

            'sha512',

            $hashData,

            config('services.vnpay.hash_secret')
        );

        return $secureHash
            === $secureHashCheck;
    }
}
