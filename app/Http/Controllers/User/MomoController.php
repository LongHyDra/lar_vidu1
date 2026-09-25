<?php

namespace App\Http\Controllers\User;

use App\Http\Controllers\Controller;
use App\Models\InventoryMovement;
use App\Models\Order;
use App\Models\PaymentTransaction;
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
        if ($order->status === 'paid' || $order->ghn_order_code) {
            return redirect()->route('user.orders.index')->with('info', 'Đơn hàng đã được thanh toán.');
        }

        return $this->redirectToMomo($order, $this->newTransaction($order), $momo);
    }

    public function payAgain(Order $order, MomoService $momo)
    {
        if ($order->user_id !== Auth::id()) {
            abort(403);
        }
        if ($order->status === 'paid' || $order->ghn_order_code) {
            return redirect()->route('user.orders.index')->with('info', 'Đơn hàng đã được thanh toán.');
        }

        return $this->redirectToMomo($order, $this->newTransaction($order), $momo);
    }

    public function callback(Request $request, GHNOrderService $ghnOrders, MomoService $momo)
    {
        Log::info('MoMo callback received', [
            'payload'       => $request->except('signature'),
            'has_signature' => $request->has('signature'),
        ]);

        if (!$momo->isValidSuccessfulResponse($request->all())) {
            if ($momo->isValidResponse($request->all())) {
                $this->markFailed($request->all(), $momo);
            }
            return redirect()->route('user.orders.index')->with('error', 'Giao dịch MoMo không thành công hoặc bị hủy.');
        }

        $result = $this->completePayment($request->all(), $ghnOrders, $momo);
        $message = in_array($result, ['created', 'already_created'], true)
            ? 'Thanh toán MoMo thành công! Vận đơn GHN đã được khởi tạo.'
            : 'Thanh toán thành công! Đơn hàng đang chờ điều phối giao vận.';

        return redirect()->route('user.orders.index')->with('success', $message);
    }

    public function ipn(Request $request, GHNOrderService $ghnOrders, MomoService $momo)
    {
        Log::info('MoMo IPN received', [
            'payload'       => $request->except('signature'),
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
            'gateway'  => 'momo',
            'amount'   => $order->total_price,
            'status'   => 'pending',
        ]);
    }

    private function redirectToMomo(Order $order, PaymentTransaction $transaction, MomoService $momo)
    {
        $result = $momo->createPayment($order, $transaction);

        if (isset($result['payUrl'])) {
            return redirect($result['payUrl']);
        }

        $errorMsg = $result['message'] ?? 'Không thể tạo phiên thanh toán MoMo.';
        return redirect()->route('user.orders.index')->with('error', 'Lỗi MoMo: ' . $errorMsg);
    }

    private function completePayment(array $payload, GHNOrderService $ghnOrders, MomoService $momo): string
    {
        $result = DB::transaction(function () use ($payload, $momo) {
            /** @var PaymentTransaction|null $transaction */
            $transaction = PaymentTransaction::where('gateway', 'momo')
                ->where('gateway_order_id', $payload['orderId'] ?? '')
                ->lockForUpdate()
                ->first();

            if (!$transaction) {
                return 'invalid';
            }

            /** @var Order|null $order */
            $order = Order::with('items.product')->lockForUpdate()->find($transaction->order_id);
            if (!$order) {
                return 'invalid';
            }

            if ($order->ghn_order_code) {
                return 'already_created';
            }

            if ((int) $transaction->amount !== (int) ($payload['amount'] ?? 0)) {
                $momo->markFailed($transaction, $payload);
                return 'invalid';
            }

            // Trừ tồn kho thực tế khi thanh toán hoàn tất
            foreach ($order->items as $item) {
                if ($item->product) {
                    $item->product->decrement('stock', $item->quantity);
                    InventoryMovement::create([
                        'product_id'  => $item->product->id,
                        'user_id'     => $order->user_id,
                        'type'        => 'out',
                        'quantity'    => -$item->quantity,
                        'stock_after' => $item->product->fresh()->stock,
                        'note'        => "Trừ kho sau khi thanh toán MoMo thành công đơn #{$order->id}",
                    ]);
                }
            }

            $order->update(['status' => 'paid', 'shipping_status' => 'processing']);
            $momo->markPaid($transaction, $payload);

            return ['create', $order->id];
        });

        if (!is_array($result)) {
            return (string) $result;
        }

        /** @var Order|null $order */
        $order = Order::with('items.product')->find($result[1]);
        if (!$order) {
            return 'invalid';
        }

        $response = $ghnOrders->create($order, true);
        if (isset($response['code']) && (int) $response['code'] === 200) {
            $order->update([
                'ghn_order_code'  => $response['data']['order_code'],
                'shipping_status' => 'ready_to_pick',
            ]);
            return 'created';
        }

        Log::error('GHN order failed after MoMo payment', [
            'order_id' => $order->id,
            'response' => $response,
        ]);

        $order->update(['shipping_status' => 'pending']);
        return 'failed';
    }

    private function markFailed(array $payload, MomoService $momo): void
    {
        /** @var PaymentTransaction|null $transaction */
        $transaction = PaymentTransaction::where('gateway', 'momo')
            ->where('gateway_order_id', $payload['orderId'] ?? '')
            ->first();

        if ($transaction && $transaction->status !== 'paid') {
            $momo->markFailed($transaction, $payload);
        }
    }
}