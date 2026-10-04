<?php

namespace App\Http\Controllers\User;

use App\Http\Controllers\Controller;
use App\Models\InventoryMovement;
use App\Models\Order;
use App\Models\OrderStatusHistory;
use App\Models\PaymentTransaction;
use App\Models\Coupon;
use App\Models\CouponRedemption;
use App\Models\Product;
use App\Models\ProductVariant;
use App\Services\GHNOrderService;
use App\Services\MomoService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;

class MomoController extends Controller
{
    public function start(Order $order, MomoService $momo)
    {
        if ($order->user_id !== Auth::id()) {
            abort(403);
        }

        return DB::transaction(function () use ($order, $momo) {
            $order = Order::lockForUpdate()->findOrFail($order->id);
            if ($order->status !== 'pending' || $order->ghn_order_code
                || $order->paymentTransactions()->where('status', 'paid')->exists()
                || $order->paymentTransactions()->where('gateway', 'cod')->exists()) {
                return redirect()->route('user.orders.index')->with('error', 'Đơn không thể thanh toán MoMo ở trạng thái hiện tại.');
            }

            return $this->redirectToMomo($order, $this->newTransaction($order), $momo);
        });
    }

    public function payAgain(Order $order, MomoService $momo)
    {
        return $this->start($order, $momo);
    }

    public function callback(Request $request, GHNOrderService $ghnOrders, MomoService $momo)
    {
        Log::info('MoMo callback received', [
            'payload' => $request->except('signature'),
            'has_signature' => $request->has('signature'),
        ]);

        if (! $momo->isValidSuccessfulResponse($request->all())) {
            if ($momo->isValidResponse($request->all())) {
                $this->markFailed($request->all(), $momo);
            }

            return redirect()->route('user.orders.index')->with('error', 'Giao dịch MoMo không thành công hoặc bị hủy.');
        }

        $result = $this->completePayment($request->all(), $ghnOrders, $momo);
        if ($result === 'invalid') {
            return redirect()->route('user.orders.index')->with('error', 'Thông tin giao dịch không khớp đơn hàng.');
        }
        if ($result === 'review') {
            return redirect()->route('user.orders.index')->with('warning', 'Đã nhận tiền nhưng đơn cần kiểm tra hoặc hoàn tiền. Vui lòng liên hệ cửa hàng.');
        }
        $message = in_array($result, ['created', 'already_created'], true)
            ? 'Thanh toán MoMo thành công! Vận đơn GHN đã được khởi tạo.'
            : 'Thanh toán thành công! Đơn hàng đang chờ điều phối giao vận.';

        return redirect()->route('user.orders.index')->with('success', $message);
    }

    public function ipn(Request $request, GHNOrderService $ghnOrders, MomoService $momo)
    {
        Log::info('MoMo IPN received', [
            'payload' => $request->except('signature'),
            'has_signature' => $request->has('signature'),
        ]);

        if ($momo->isValidSuccessfulResponse($request->all())) {
            $this->completePayment($request->all(), $ghnOrders, $momo);
        } elseif ($momo->isValidResponse($request->all())) {
            $this->markFailed($request->all(), $momo);
        }

        return response()->json(['message' => 'Received']);
    }

    private function newTransaction(Order $order): PaymentTransaction
    {
        return PaymentTransaction::create([
            'order_id' => $order->id,
            'gateway' => 'momo',
            'amount' => $order->total_price,
            'status' => 'pending',
        ]);
    }

    private function redirectToMomo(Order $order, PaymentTransaction $transaction, MomoService $momo)
    {
        $result = $momo->createPayment($order, $transaction);

        if (isset($result['payUrl'])) {
            return redirect($result['payUrl']);
        }

        $errorMsg = $result['message'] ?? 'Không thể tạo phiên thanh toán MoMo.';

        return redirect()->route('user.orders.index')->with('error', 'Lỗi MoMo: '.$errorMsg);
    }

    private function completePayment(array $payload, GHNOrderService $ghnOrders, MomoService $momo): string
    {
        $result = DB::transaction(function () use ($payload, $momo) {
            $transaction = PaymentTransaction::where('gateway', 'momo')
                ->where('gateway_order_id', $payload['orderId'] ?? '')->first();
            if (! $transaction) {
                return 'invalid';
            }
            // Lock order before payment, consistently with cancellation and finance.
            $order = Order::lockForUpdate()->find($transaction->order_id);
            if (! $order) {
                return 'invalid';
            }
            $transaction = PaymentTransaction::lockForUpdate()->findOrFail($transaction->id);
            if ((int) $transaction->amount !== (int) ($payload['amount'] ?? 0)) {
                return 'invalid';
            }
            if ($transaction->status === 'paid') {
                return $order->status === 'cancelled' || $order->shipping_status === 'payment_review'
                    ? 'review' : ($order->ghn_order_code ? 'already_created' : 'processed');
            }
            $alreadyPaid = $order->paymentTransactions()->where('status', 'paid')->exists();
            $momo->markPaid($transaction, $payload);
            if ($order->status === 'cancelled' || $alreadyPaid) {
                $transaction->update(['message' => 'Đã nhận tiền; cần kiểm tra hoàn tiền cho đơn hủy hoặc thanh toán trùng.']);

                return 'review';
            }
            $items = $order->items()->get();
            $quantities = $items->groupBy(fn ($item) => $item->product_id.':'.($item->variant_id ?: 0))->map(fn ($rows) => $rows->sum('quantity'));
            $productIds = $items->pluck('product_id')->unique()->values();
            $variantIds = $items->pluck('variant_id')->filter()->unique()->values();
            $products = Product::whereIn('id', $productIds)->orderBy('id')->lockForUpdate()->get()->keyBy('id');
            $variants = ProductVariant::whereIn('id', $variantIds)->orderBy('id')->lockForUpdate()->get()->keyBy('id');
            if (! $order->hasDeductedStock()) {
                foreach ($quantities as $key => $quantity) {
                    [$id, $variantId] = array_pad(explode(':', (string) $key), 2, 0);
                    $stockItem = $variantId ? $variants->get((int) $variantId) : $products->get((int) $id);
                    if (! $products->has((int) $id) || ! $stockItem || $stockItem->stock < $quantity || $quantity <= 0) {
                        $order->update(['shipping_status' => 'payment_review']);
                        $transaction->update(['message' => 'Đã nhận tiền nhưng không đủ tồn kho; cần hỗ trợ hoặc hoàn tiền.']);

                        return 'review';
                    }
                }
                if ($items->isEmpty()) {
                    $order->update(['shipping_status' => 'payment_review']);

                    return 'review';
                }
                foreach ($quantities as $key => $quantity) {
                    [$id, $variantId] = array_pad(explode(':', (string) $key), 2, 0);
                    $stockItem = $variantId ? $variants->get((int) $variantId) : $products->get((int) $id);
                    $stockItem->decrement('stock', $quantity);
                    InventoryMovement::create([
                        'product_id' => (int) $id, 'variant_id' => $variantId ? (int) $variantId : null, 'user_id' => $order->user_id,
                        'type' => 'out', 'quantity' => -$quantity, 'stock_after' => $stockItem->fresh()->stock,
                        'note' => "Trừ kho sau khi thanh toán MoMo thành công đơn #{$order->id}",
                    ]);
                }
            }
            $order->update(['status' => 'paid', 'shipping_status' => 'processing', 'stock_deducted' => true]);
            OrderStatusHistory::create([
                'order_id' => $order->id, 'status' => 'paid', 'note' => 'Đã nhận thanh toán MoMo',
                'changed_by' => $order->user_id,
            ]);

            return ['create', $order->id];
        });
        if (! is_array($result)) {
            return $result;
        }

        // Do not race shipment creation against cancellation.
        return DB::transaction(function () use ($result, $ghnOrders) {
            $order = Order::with('items.product')->lockForUpdate()->findOrFail($result[1]);
            if ($order->status === 'cancelled') {
                return 'review';
            }
            if ($order->ghn_order_code) {
                return 'already_created';
            }
            $response = $ghnOrders->create($order, true);
            if ((int) ($response['code'] ?? 0) === 200 && ! empty($response['data']['order_code'])) {
                $order->update(['ghn_order_code' => $response['data']['order_code'], 'shipping_status' => 'ready_to_pick']);

                return 'created';
            }
            Log::error('GHN order failed after MoMo payment', ['order_id' => $order->id, 'response' => $response]);
            $order->update(['shipping_status' => 'pending']);

            return 'failed';
        });
    }

    private function markFailed(array $payload, MomoService $momo): void
    {
        DB::transaction(function () use ($payload, $momo) {
            $transaction = PaymentTransaction::where('gateway', 'momo')
                ->where('gateway_order_id', $payload['orderId'] ?? '')->first();
            if (! $transaction) {
                return;
            }
            Order::lockForUpdate()->findOrFail($transaction->order_id);
            $transaction = PaymentTransaction::lockForUpdate()->findOrFail($transaction->id);
            $order = Order::lockForUpdate()->findOrFail($transaction->order_id);
            if ($transaction->status !== 'paid') {
                $momo->markFailed($transaction, $payload);
                $this->releaseCouponReservation($order);
            }
        });
    }

    private function releaseCouponReservation(Order $order): void
    {
        $redemption = CouponRedemption::where('order_id', $order->id)->lockForUpdate()->first();
        if (! $redemption) {
            return;
        }

        $coupon = Coupon::lockForUpdate()->find($redemption->coupon_id);
        $redemption->delete();
        if ($coupon && $coupon->used_count > 0) {
            $coupon->decrement('used_count');
        }
    }
}
