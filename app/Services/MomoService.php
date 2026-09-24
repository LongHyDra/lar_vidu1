<?php

namespace App\Services;

use App\Models\Order;
use App\Models\PaymentTransaction;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;

class MomoService
{
    /**
     * Khởi tạo giao dịch MoMo với $requestType linh hoạt (payWithATM hoặc payWithCC)
     */
    public function createPayment(Order $order, PaymentTransaction $transaction, string $requestType = 'payWithATM'): array
    {
        $endpoint = config('services.momo.endpoint', env('MOMO_ENDPOINT', 'https://test-payment.momo.vn/v2/gateway/api/create'));
        
        $partnerCode = config('services.momo.partner_code', env('MOMO_PARTNER_CODE', 'MOMO'));
        $accessKey = config('services.momo.access_key', env('MOMO_ACCESS_KEY', 'F8BBA842ECF85'));
        $secretKey = config('services.momo.secret_key', env('MOMO_SECRET_KEY', 'K951B6PE1wa8ngf4S01072xExx'));

        $orderInfo = 'Thanh toan don hang #' . $order->id;
        $amount = (string) ((int) $order->total_price);
        $orderId = $order->id . '_' . $transaction->id . '_' . time();
        $redirectUrl = config('services.momo.redirect_url') ?: route('user.payment.momo.callback');
        $ipnUrl = config('services.momo.ipn_url') ?: route('payment.momo.ipn');
        $extraData = (string) $order->id;
        $requestId = (string) time();

        $rawHash = 'accessKey=' . $accessKey .
            '&amount=' . $amount .
            '&extraData=' . $extraData .
            '&ipnUrl=' . $ipnUrl .
            '&orderId=' . $orderId .
            '&orderInfo=' . $orderInfo .
            '&partnerCode=' . $partnerCode .
            '&redirectUrl=' . $redirectUrl .
            '&requestId=' . $requestId .
            '&requestType=' . $requestType;

        $signature = hash_hmac('sha256', $rawHash, $secretKey);

        $data = [
            'partnerCode' => $partnerCode,
            'partnerName' => 'PHỤ KIỆN XE MÁY 247',
            'storeId' => 'PKXM247',
            'requestId' => $requestId,
            'amount' => $amount,
            'orderId' => $orderId,
            'orderInfo' => $orderInfo,
            'redirectUrl' => $redirectUrl,
            'ipnUrl' => $ipnUrl,
            'lang' => 'vi',
            'extraData' => $extraData,
            'requestType' => $requestType,
            'signature' => $signature,
        ];

        $transaction->update([
            'gateway_order_id' => $orderId,
            'request_payload' => $data,
        ]);

        try {
            $response = Http::withOptions([
                'verify' => false,
            ])->acceptJson()->timeout(15)->post($endpoint, $data);

            $result = $response->json() ?? [];
        } catch (\Exception $e) {
            Log::error('MoMo connection error: ' . $e->getMessage());
            $result = [
                'resultCode' => -1,
                'message' => 'Lỗi kết nối cổng MoMo: ' . $e->getMessage()
            ];
        }

        $transaction->update([
            'response_payload' => $result,
            'result_code' => isset($result['resultCode']) ? (int) $result['resultCode'] : null,
            'message' => $result['message'] ?? null,
            'status' => isset($result['payUrl']) ? 'initiated' : 'failed',
        ]);

        return $result;
    }

    public function isSuccessful(array $payload): bool
    {
        return (string) ($payload['resultCode'] ?? '') === '0';
    }

    public function markPaid(PaymentTransaction $transaction, array $payload): void
    {
        $transaction->update([
            'transaction_id' => $payload['transId'] ?? null,
            'result_code' => (int) ($payload['resultCode'] ?? 0),
            'message' => $payload['message'] ?? null,
            'response_payload' => $payload,
            'status' => 'paid',
            'paid_at' => Carbon::now(),
        ]);
    }

    public function markFailed(PaymentTransaction $transaction, array $payload): void
    {
        $transaction->update([
            'transaction_id' => $payload['transId'] ?? null,
            'result_code' => isset($payload['resultCode']) ? (int) $payload['resultCode'] : null,
            'message' => $payload['message'] ?? null,
            'response_payload' => $payload,
            'status' => 'failed',
        ]);
    }

    public function isValidSuccessfulResponse(array $payload): bool
    {
        return $this->isValidResponse($payload) && $this->isSuccessful($payload);
    }

    public function isValidResponse(array $payload): bool
    {
        if (!isset($payload['signature'])) {
            return false;
        }

        $accessKey = config('services.momo.access_key', env('MOMO_ACCESS_KEY', 'F8BBA842ECF85'));
        $secretKey = config('services.momo.secret_key', env('MOMO_SECRET_KEY', 'K951B6PE1wa8ngf4S01072xExx'));

        $rawHash = 'accessKey=' . $accessKey .
            '&amount=' . ($payload['amount'] ?? '') .
            '&extraData=' . ($payload['extraData'] ?? '') .
            '&message=' . ($payload['message'] ?? '') .
            '&orderId=' . ($payload['orderId'] ?? '') .
            '&orderInfo=' . ($payload['orderInfo'] ?? '') .
            '&orderType=' . ($payload['orderType'] ?? '') .
            '&partnerCode=' . ($payload['partnerCode'] ?? '') .
            '&payType=' . ($payload['payType'] ?? '') .
            '&requestId=' . ($payload['requestId'] ?? '') .
            '&responseTime=' . ($payload['responseTime'] ?? '') .
            '&resultCode=' . ($payload['resultCode'] ?? '') .
            '&transId=' . ($payload['transId'] ?? '');

        return hash_equals(
            hash_hmac('sha256', $rawHash, $secretKey),
            (string) $payload['signature']
        );
    }

    public function orderId(array $payload): ?int
    {
        $orderId = $payload['extraData'] ?? null;
        return is_numeric($orderId) ? (int) $orderId : null;
    }
}