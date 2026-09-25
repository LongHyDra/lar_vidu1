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
            $itemWeight = $itemWeight > 0 ? $itemWeight : 200;
            $weight += $itemWeight * (int) $item->quantity;

            $items[] = [
                'name' => (string) ($item->product->name ?? 'Phụ kiện xe máy'),
                'quantity' => (int) $item->quantity,
                'price' => (int) $item->price,
                'weight' => $itemWeight,
            ];
        }

        // Hạn mức COD GHN Sandbox tối đa 5.000.000đ (nếu đơn lớn hơn thì giới hạn 5tr để không bị GHN từ chối)
        $codAmount = 0;
        if (!$isPaid) {
            $codAmount = min((int) $order->total_price, 5000000);
        }

        $fromDistrictId = (int) config('services.ghn.from_district_id', env('GHN_FROM_DISTRICT_ID', 1450));

        $payload = [
            'payment_type_id' => 2, // Người nhận trả cước
            'note' => 'Đơn hàng phụ kiện #' . $order->id,
            'required_note' => 'KHONGCHOXEMHANG',
            
            // 1. THÔNG TIN NGƯỜI GỬI / KHO XUẤT HÀNG (BẮT BUỘC ĐỂ KHÔNG BỊ LỖI 400)
            'from_name' => 'PHỤ KIỆN XE MÁY 247',
            'from_phone' => '0901234567',
            'from_address' => '123 Đường Cầu Giấy, Phường Dịch Vọng, Quận Cầu Giấy, Hà Nội',
            'from_district_id' => $fromDistrictId,
            'from_ward_code' => '1A0107',
            
            // 2. THÔNG TIN HOÀN HÀNG
            'return_phone' => '0901234567',
            'return_address' => '123 Đường Cầu Giấy, Phường Dịch Vọng, Quận Cầu Giấy, Hà Nội',
            'return_district_id' => $fromDistrictId,
            
            // 3. THÔNG TIN NGƯỜI NHẬN
            'to_name' => $order->name,
            'to_phone' => $order->phone,
            'to_address' => $order->address,
            'to_ward_code' => (string) $order->to_ward_code,
            'to_district_id' => (int) $order->to_district_id,
            
            // 4. TIỀN COD & TRỌNG LƯỢNG
            'cod_amount' => $codAmount,
            'weight' => $weight > 0 ? $weight : 300,
            'length' => 15,
            'width' => 15,
            'height' => 10,
            'service_type_id' => 2, // Giao hàng chuẩn E-commerce
            'items' => $items,
        ];

        return $this->ghn->createOrder($payload);
    }
}