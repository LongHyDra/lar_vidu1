<?php

namespace App\Http\Controllers\Webhook;

use App\Http\Controllers\Controller;
use App\Models\Order;
use App\Models\OrderStatusHistory;
use App\Models\SiteNotification;
use App\Support\PaymentStatus;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use App\Services\LoyaltyService;

class GhnWebhookController extends Controller
{
    public function handle(Request $request, string $token, LoyaltyService $loyalty): JsonResponse
    {
        $expectedToken = (string) config('services.ghn.webhook_token');

        if ($expectedToken === '' || ! hash_equals($expectedToken, $token)) {
            abort(403);
        }

        $payload = $request->json()->all() ?: $request->all();
        $orderCode = $payload['OrderCode'] ?? $payload['order_code'] ?? null;
        $providerStatus = strtolower((string) ($payload['Status'] ?? $payload['status'] ?? ''));

        if (! is_string($orderCode) || $orderCode === '' || $providerStatus === '') {
            return response()->json(['message' => 'Payload không hợp lệ.'], 422);
        }

        $shippingStatus = $this->shippingStatus($providerStatus);

        DB::transaction(function () use ($orderCode, $providerStatus, $shippingStatus, $loyalty) {
            $order = Order::where('ghn_order_code', $orderCode)->lockForUpdate()->first();

            if (! $order) {
                return;
            }

            $payment = $order->paymentTransactions()
                ->orderByRaw(PaymentStatus::SELECTION_PRIORITY)
                ->orderByDesc('id')
                ->lockForUpdate()
                ->first();

            if ($providerStatus === 'delivered' && $order->status === 'cancelled') {
                return;
            }

            $attributes = ['shipping_status' => $shippingStatus];
            $note = 'GHN cập nhật: ' . $providerStatus;

            if ($providerStatus === 'delivered' && $order->status !== 'cancelled') {
                $attributes['status'] = 'delivered';
                if ($payment?->gateway === 'cod' && $payment->status === PaymentStatus::PENDING) {
                    $payment->update([
                        'status' => PaymentStatus::PAID,
                        'paid_at' => now(),
                        'message' => 'GHN xác nhận giao hàng thành công (đã thu COD).',
                    ]);
                }
            }

            if ($providerStatus === 'cancel' && $order->status === 'cancelled') {
                $attributes['shipping_status'] = 'cancelled';
            }

            if ($order->shipping_status === $attributes['shipping_status']
                && (! isset($attributes['status']) || $order->status === $attributes['status'])) {
                return;
            }

            $order->update($attributes);
            if ($providerStatus === 'delivered') {
                $loyalty->awardForOrder($order->fresh());
            }
            OrderStatusHistory::create([
                'order_id' => $order->id,
                'status' => $order->status,
                'note' => $note,
            ]);
            SiteNotification::create([
                'user_id' => $order->user_id,
                'title' => 'Đơn hàng cập nhật',
                'body' => 'Đơn #'.$order->id.' vừa chuyển sang trạng thái: '.$shippingStatus.'.',
                'url' => route('user.orders.show', $order),
            ]);
        });

        return response()->json(['message' => 'ok']);
    }

    private function shippingStatus(string $providerStatus): string
    {
        return match ($providerStatus) {
            'ready_to_pick', 'picking', 'money_collect_picking', 'picked', 'storing', 'transporting', 'sorting', 'delivering', 'money_collect_delivering' => $providerStatus,
            'delivered' => 'delivered',
            'delivery_fail', 'return_fail', 'exception', 'damage', 'lost' => 'delivery_failed',
            'waiting_to_return', 'return', 'return_transporting', 'return_sorting', 'returning', 'returned' => 'returned',
            'cancel' => 'cancelled',
            default => 'shipping',
        };
    }
}
