<!DOCTYPE html>
<html lang="vi">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Chi tiết đơn hàng</title>
    <style>
        body { font-family: Arial, sans-serif; margin: 32px; background: #f8fafc; color: #111827; }
        .container { max-width: 980px; margin: 0 auto; }
        .card { background: #fff; border-radius: 12px; padding: 24px; box-shadow: 0 8px 24px rgba(15, 23, 42, 0.08); }
        .header { display: flex; justify-content: space-between; align-items: center; margin-bottom: 20px; }
        .badge { display: inline-block; padding: 6px 12px; border-radius: 999px; font-size: 12px; font-weight: 700; }
        .badge-pending { background: #fef3c7; color: #92400e; }
        .badge-paid { background: #dcfce7; color: #166534; }
        .badge-cod_ordered { background: #dbeafe; color: #1d4ed8; }
        table { width: 100%; border-collapse: collapse; margin-top: 20px; }
        th, td { text-align: left; padding: 12px 10px; border-bottom: 1px solid #e5e7eb; }
        th { background: #f3f4f6; }
        .info { margin-bottom: 18px; line-height: 1.8; }
        .link { color: #2563eb; text-decoration: none; }
        .btn { display: inline-block; padding: 10px 16px; border-radius: 8px; background: #111827; color: white; text-decoration: none; }
        .timeline { border-left: 3px solid #bfdbfe; padding-left: 20px; margin: 20px 0; }
        .timeline-item { position: relative; margin-bottom: 16px; }
        .timeline-item::before { content: ''; position: absolute; left: -27px; top: 5px; width: 11px; height: 11px; border-radius: 50%; background: #2563eb; }
        .muted { color: #64748b; font-size: 13px; }
    </style>
</head>
<body>
<div class="container">
    <div class="card">
        <div class="header">
            <h1>Đơn hàng #{{ $order->id }}</h1>
            @php($statusLabels = ['pending' => 'Chờ xác nhận', 'confirmed' => 'Đã xác nhận', 'packaging' => 'Đang đóng gói', 'shipping' => 'Đang vận chuyển', 'delivered' => 'Đã giao', 'cancelled' => 'Đã hủy', 'cod_ordered' => 'Đã tạo vận đơn'])
            <span class="badge badge-{{ $order->status ?? 'pending' }}">{{ $statusLabels[$order->status] ?? $order->status }}</span>
        </div>

        <div class="info">
            <div><strong>Khách hàng:</strong> {{ $order->name }}</div>
            <div><strong>Số điện thoại:</strong> {{ $order->phone }}</div>
            <div><strong>Địa chỉ:</strong> {{ $order->address }}</div>
            <div><strong>Ngày đặt:</strong> {{ $order->created_at->format('d/m/Y H:i') }}</div>
            <div><strong>GHN code:</strong> {{ $order->ghn_order_code ?: 'Chưa có' }}</div>
            <div><strong>Phương thức thanh toán:</strong> {{ strtoupper($order->paymentTransaction->gateway ?? 'COD') }}</div>
            <div><strong>Thanh toán:</strong> {{ $order->paymentTransaction->status ?? 'Chưa cập nhật' }}</div>
        </div>

        <h2 style="font-size: 18px; margin-top: 26px;">Lịch sử trạng thái</h2>
        <div class="timeline">
            @forelse($order->statusHistories as $history)
                <div class="timeline-item">
                    <strong>{{ $statusLabels[$history->status] ?? $history->status }}</strong>
                    <div class="muted">{{ $history->created_at->format('d/m/Y H:i') }} · {{ $history->note ?: 'Cập nhật trạng thái' }}</div>
                </div>
            @empty
                <div class="muted">Chưa có lịch sử trạng thái.</div>
            @endforelse
        </div>

        <table>
            <thead>
            <tr>
                <th>Sản phẩm</th>
                <th>Số lượng</th>
                <th>Đơn giá</th>
                <th>Thành tiền</th>
            </tr>
            </thead>
            <tbody>
            @foreach ($order->items as $item)
                <tr>
                    <td>{{ $item->product->name ?? 'Sản phẩm' }}</td>
                    <td>{{ $item->quantity }}</td>
                    <td>{{ number_format($item->price, 0, ',', '.') }}đ</td>
                    <td>{{ number_format($item->price * $item->quantity, 0, ',', '.') }}đ</td>
                </tr>
            @endforeach
            </tbody>
        </table>

        <div class="info" style="margin-top: 20px;">
            <div><strong>Phí vận chuyển:</strong> {{ number_format($order->ghn_total_fee ?? 0, 0, ',', '.') }}đ</div>
            <div><strong>Tổng tiền:</strong> {{ number_format($order->total_price, 0, ',', '.') }}đ</div>
        </div>

        <a href="{{ route('user.orders.index') }}" class="link">← Quay lại lịch sử đơn hàng</a>
    </div>
</div>
</body>
</html>
