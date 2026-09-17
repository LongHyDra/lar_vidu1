<!DOCTYPE html>
<html lang="vi">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Lịch sử đơn hàng</title>
    <style>
        body { font-family: Arial, sans-serif; margin: 32px; background: #f8fafc; color: #111827; }
        .container { max-width: 1100px; margin: 0 auto; }
        .card { background: #fff; border-radius: 12px; padding: 24px; box-shadow: 0 8px 24px rgba(15, 23, 42, 0.08); }
        h1 { margin-top: 0; }
        .alert { padding: 12px 16px; border-radius: 8px; margin-bottom: 20px; }
        .alert-success { background: #ecfdf5; color: #065f46; }
        .alert-error { background: #fef2f2; color: #991b1b; }
        table { width: 100%; border-collapse: collapse; margin-top: 20px; }
        th, td { text-align: left; padding: 12px 10px; border-bottom: 1px solid #e5e7eb; }
        th { background: #f3f4f6; }
        .badge { display: inline-block; padding: 4px 10px; border-radius: 999px; font-size: 12px; font-weight: 600; }
        .badge-pending { background: #fef3c7; color: #92400e; }
        .badge-paid { background: #dcfce7; color: #166534; }
        .badge-cod_ordered { background: #dbeafe; color: #1d4ed8; }
        .link { color: #2563eb; text-decoration: none; }
        .empty { padding: 20px; text-align: center; color: #6b7280; }
        .actions { margin-top: 20px; }
        .btn { display: inline-block; padding: 10px 16px; border-radius: 8px; background: #111827; color: white; text-decoration: none; }
    </style>
</head>
<body>
<div class="container">
    <div class="card">
        <h1>Đơn hàng của tôi</h1>

        @if (session('success'))
            <div class="alert alert-success">{{ session('success') }}</div>
        @endif

        @if (session('error'))
            <div class="alert alert-error">{{ session('error') }}</div>
        @endif

        @if ($orders->isEmpty())
            <div class="empty">Bạn chưa có đơn hàng nào.</div>
            <div class="actions">
                <a href="{{ route('welcome') }}" class="btn">Tiếp tục mua sắm</a>
            </div>
        @else
            <table>
                <thead>
                <tr>
                    <th>Mã đơn</th>
                    <th>Ngày đặt</th>
                    <th>Tổng tiền</th>
                    <th>Trạng thái</th>
                    <th>GHN</th>
                    <th>Chi tiết</th>
                </tr>
                </thead>
                <tbody>
                @foreach ($orders as $order)
                    <tr>
                        <td>#{{ $order->id }}</td>
                        <td>{{ $order->created_at->format('d/m/Y H:i') }}</td>
                        <td>{{ number_format($order->total_price, 0, ',', '.') }}đ</td>
                        <td>
                            @php $status = $order->status ?? 'pending'; @endphp
                            <span class="badge badge-{{ $status }}">{{ $status }}</span>
                        </td>
                        <td>{{ $order->ghn_order_code ?: '—' }}</td>
                        <td>
                            <a href="{{ route('user.orders.show', $order) }}" class="link">Xem</a>
                        </td>
                    </tr>
                @endforeach
                </tbody>
            </table>

            <div class="actions">
                {{ $orders->links() }}
            </div>
        @endif
    </div>
</div>
</body>
</html>
