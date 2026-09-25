<?php

namespace App\Services;

use Illuminate\Http\Client\ConnectionException;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;

class GHNService
{
    protected string $baseUrl;
    protected string $token;
    protected int $shopId;

    public function __construct()
    {
        $this->baseUrl = config('services.ghn.base_url', env('GHN_BASE_URL', 'https://dev-online-gateway.ghn.vn/shiip/public-api'));
        $this->token = (string) config('services.ghn.token', env('GHN_TOKEN', ''));
        $this->shopId = (int) config('services.ghn.shop_id', env('GHN_SHOP_ID', 0));
    }

    protected function client()
    {
        return Http::baseUrl($this->baseUrl)
            ->withOptions([
                'verify' => false, // Tắt verify SSL trên local Windows
            ])
            ->acceptJson()
            ->timeout(15)
            ->withHeaders([
                'Token' => $this->token,
                'ShopId' => $this->shopId,
                'Content-Type' => 'application/json',
            ]);
    }

    public function getProvinces(): array
    {
        return $this->get('/master-data/province');
    }

    public function getDistricts(int $provinceId): array
    {
        return $this->get('/master-data/district', [
            'province_id' => $provinceId,
        ]);
    }

    public function getWards(int $districtId): array
    {
        return $this->get('/master-data/ward', [
            'district_id' => $districtId,
        ]);
    }

    public function calculateFee(array $params): array
    {
        return $this->post('/v2/shipping-order/fee', array_merge([
            'shop_id' => $this->shopId,
        ], $params));
    }

    public function createOrder(array $orderData): array
    {
        return $this->post('/v2/shipping-order/create', array_merge([
            'shop_id' => $this->shopId,
        ], $orderData));
    }

    public function packageParameters(int $weight): array
    {
        return [
            'weight' => $weight > 0 ? $weight : 300,
            'length' => 15,
            'width' => 15,
            'height' => 10,
            'service_type_id' => 2,
        ];
    }

    public function cancelOrder(array $orderCodes): array
    {
        return $this->post('/v2/switch-status/cancel', [
            'order_codes' => $orderCodes,
            'shop_id' => $this->shopId,
        ]);
    }

    protected function get(string $uri, array $query = []): array
    {
        try {
            $response = $this->client()->get($uri, $query);
            return $response->json() ?? ['code' => $response->status(), 'message' => 'Lỗi kết nối GHN.'];
        } catch (ConnectionException $exception) {
            Log::error('GHN connection error', ['uri' => $uri, 'error' => $exception->getMessage()]);
            return ['code' => -1, 'message' => 'Không thể kết nối đến máy chủ GHN.'];
        }
    }

    protected function post(string $uri, array $payload): array
    {
        try {
            $response = $this->client()->post($uri, $payload);
            $result = $response->json() ?? [];

            // Ghi log chi tiết nếu GHN báo lỗi
            if (!$response->successful() || (isset($result['code']) && $result['code'] != 200)) {
                Log::warning('GHN API returned error', [
                    'uri' => $uri,
                    'status' => $response->status(),
                    'payload' => $payload,
                    'response' => $result,
                ]);
            }

            return $result;
        } catch (ConnectionException $exception) {
            Log::error('GHN connection error', ['uri' => $uri, 'error' => $exception->getMessage()]);
            return ['code' => -1, 'message' => 'Không thể kết nối đến máy chủ GHN.'];
        }
    }
}