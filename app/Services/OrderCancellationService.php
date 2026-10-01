<?php

namespace App\Services;

use App\Models\InventoryMovement;
use App\Models\Order;
use App\Models\OrderStatusHistory;
use App\Models\Product;
use App\Models\ProductVariant;
use App\Models\User;
use Illuminate\Support\Facades\DB;
use Illuminate\Validation\ValidationException;

class OrderCancellationService
{
    public function cancel(Order $order, User $user, GHNService $ghn): void
    {
        DB::transaction(function () use ($order, $user, $ghn) {
            $order = Order::lockForUpdate()->findOrFail($order->id);
            if ($order->status === 'cancelled') {
                return;
            }
            if (in_array($order->status, ['shipping', 'delivered'], true)) {
                throw ValidationException::withMessages(['status' => 'Đơn đang giao hoặc đã giao không thể hủy.']);
            }
            if (! $user->isAdmin() && ($order->paymentTransactions()->where('status', 'paid')->exists()
                || in_array($order->status, ['paid', 'cod_paid'], true))) {
                throw ValidationException::withMessages(['status' => 'Đơn đã thanh toán cần liên hệ hỗ trợ hoàn tiền.']);
            }
            if ($order->ghn_order_code) {
                $result = $ghn->cancelOrder([$order->ghn_order_code]);
                $cancelled = collect($result['data'] ?? [])->contains(fn ($item) => ($item['order_code'] ?? null) === $order->ghn_order_code && ($item['result'] ?? false) === true);
                if ((int) ($result['code'] ?? 0) !== 200 || ! $cancelled) {
                    throw ValidationException::withMessages(['status' => 'GHN chưa xác nhận hủy vận đơn. Vui lòng thử lại.']);
                }
            }
            if ($order->hasDeductedStock()) {
                $items = $order->items()->with('variant')->orderBy('product_id')->get();
                $products = Product::whereIn('id', $items->pluck('product_id'))->orderBy('id')->lockForUpdate()->get()->keyBy('id');
                $variants = ProductVariant::whereIn('id', $items->pluck('variant_id')->filter())->orderBy('id')->lockForUpdate()->get()->keyBy('id');
                foreach ($items as $item) {
                    if ($product = $products->get($item->product_id)) {
                        $stockItem = $item->variant_id ? $variants->get($item->variant_id) : $product;
                        if (! $stockItem) continue;
                        $stockItem->increment('stock', $item->quantity);
                        InventoryMovement::create([
                            'product_id' => $product->id, 'user_id' => $user->id,
                            'variant_id' => $item->variant_id,
                            'type' => 'in', 'quantity' => $item->quantity,
                            'stock_after' => $stockItem->stock,
                            'note' => "Hoàn kho khi hủy đơn hàng #{$order->id}",
                        ]);
                    }
                }
            }
            $order->update(['status' => 'cancelled', 'shipping_status' => 'cancelled', 'stock_deducted' => false]);
            OrderStatusHistory::create([
                'order_id' => $order->id, 'status' => 'cancelled',
                'note' => 'Hủy đơn hàng thành công', 'changed_by' => $user->id,
            ]);
        });
    }
}
