<?php

namespace App\Services;

use App\Models\Order;

class GHNOrderService
{
    public function __construct(private GHNService $ghn)
    {
    }

    public function create(Order $order, bool $isPaid = false): array
    {
        $items = [];
        $weight = 0;
        foreach ($order->items as $item) {
            $itemWeight = (int) ($item->product->weight ?? 200);
            $weight += $itemWeight * (int) $item->quantity;
            $items[] = [
                'name' => $item->product->name ?? 'Sản phẩm',
                'quantity' => (int) $item->quantity,
                'price' => (int) $item->price,
                'weight' => $itemWeight,
            ];
        }

        return $this->ghn->createOrder([
            'payment_type_id' => 2,
            'note' => 'Đơn hàng #' . $order->id,
            'required_note' => 'KHONGCHOXEMHANG',
            'to_name' => $order->name,
            'to_phone' => $order->phone,
            'to_address' => $order->address,
            'to_ward_code' => (string) $order->to_ward_code,
            'to_district_id' => (int) $order->to_district_id,
            'cod_amount' => $isPaid ? 0 : (int) $order->total_price,
            'weight' => $weight > 0 ? $weight : 300,
            'length' => 15,
            'width' => 15,
            'height' => 10,
            'service_type_id' => 2,
            'items' => $items,
        ]);
    }
}
