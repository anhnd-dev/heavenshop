<?php

namespace App\Services\Frontend;

use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;

class MomoService
{
    /**
     * =========================
     * CREATE MOMO PAYMENT
     * =========================
     */
    public function createPayment(array $data): ?string
    {
        $endpoint = config('services.momo.endpoint');

        $partnerCode = config('services.momo.partner_code');

        $accessKey = config('services.momo.access_key');

        $secretKey = config('services.momo.secret_key');

        $orderCode = $data['order_code'];

        $amount = (string) ((int) $data['amount']);

        $orderInfo =
            'Thanh toán đơn hàng ' .
            $orderCode;

        $requestId =
            now()->timestamp . '';

        $redirectUrl =
            route('momo.return');

        $ipnUrl =
            route('momo.ipn');

        $extraData = '';

        /**
         * captureWallet
         * payWithATM
         * payWithCC
         */
        $requestType = 'captureWallet';

        /**
         * =========================
         * RAW SIGNATURE
         * =========================
         */
        $rawHash =
            "accessKey={$accessKey}" .
            "&amount={$amount}" .
            "&extraData={$extraData}" .
            "&ipnUrl={$ipnUrl}" .
            "&orderId={$orderCode}" .
            "&orderInfo={$orderInfo}" .
            "&partnerCode={$partnerCode}" .
            "&redirectUrl={$redirectUrl}" .
            "&requestId={$requestId}" .
            "&requestType={$requestType}";

        $signature = hash_hmac(
            'sha256',
            $rawHash,
            $secretKey
        );

        /**
         * =========================
         * PAYLOAD
         * =========================
         */
        $payload = [

            'partnerCode' => $partnerCode,

            'partnerName' => config(
                'app.name',
                'Laravel Shop'
            ),

            'storeId' => config(
                'app.name',
                'Laravel Shop'
            ),

            'requestId' => $requestId,

            'amount' => $amount,

            'orderId' => $orderCode,

            'orderInfo' => $orderInfo,

            'redirectUrl' => $redirectUrl,

            'ipnUrl' => $ipnUrl,

            'lang' => 'vi',

            'extraData' => $extraData,

            'requestType' => $requestType,

            'signature' => $signature,
        ];

        /**
         * =========================
         * REQUEST
         * =========================
         */
        $response = Http::timeout(20)
            ->post($endpoint, $payload);

        /**
         * =========================
         * REQUEST FAIL
         * =========================
         */
        if (!$response->successful()) {

            Log::error(
                'Momo request failed',
                [
                    'payload' => $payload,
                    'response' => $response->body(),
                ]
            );

            return null;
        }

        $result = $response->json();

        /**
         * =========================
         * MOMO ERROR
         * =========================
         */
        if (
            !isset($result['resultCode']) ||
            $result['resultCode'] != 0
        ) {

            Log::error(
                'Momo payment error',
                [
                    'response' => $result
                ]
            );

            return null;
        }

        return $result['payUrl'] ?? null;
    }

    /**
     * =========================
     * VERIFY SIGNATURE
     * =========================
     */
    public function verify(array $data): bool
    {
        try {

            $secretKey =
                config('services.momo.secret_key');

            $accessKey =
                config('services.momo.access_key');

            $rawHash =
                "accessKey={$accessKey}" .
                "&amount={$data['amount']}" .
                "&extraData={$data['extraData']}" .
                "&message={$data['message']}" .
                "&orderId={$data['orderId']}" .
                "&orderInfo={$data['orderInfo']}" .
                "&orderType={$data['orderType']}" .
                "&partnerCode={$data['partnerCode']}" .
                "&payType={$data['payType']}" .
                "&requestId={$data['requestId']}" .
                "&responseTime={$data['responseTime']}" .
                "&resultCode={$data['resultCode']}" .
                "&transId={$data['transId']}";

            $signature = hash_hmac(
                'sha256',
                $rawHash,
                $secretKey
            );

            return hash_equals(
                $signature,
                $data['signature']
            );
        } catch (\Throwable $e) {

            Log::error(
                'Momo verify error',
                [
                    'message' => $e->getMessage(),
                    'data' => $data,
                ]
            );

            return false;
        }
    }
}
