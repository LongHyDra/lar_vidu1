<!DOCTYPE html>
<html lang="vi">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Quản lý đơn hàng</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
    <style>
        body { background: #f8fafc; font-family: Arial, sans-serif; }
        .container { max-width: 1200px; margin: 40px auto; }
        .card { background: #fff; border-radius: 16px; padding: 20px; box-shadow: 0 8px 24px rgba(15, 23, 42, 0.06); }
        .status { display: inline-block; padding: 6px 12px; border-radius: 999px; font-size: 12px; font-weight: 700; }
        .status-pending { background: #fef3c7; color: #92400e; }
        .status-confirmed { background: #dbeafe; color: #1d4ed8; }
        .status-packaging { background: #ede9fe; color: #6d28d9; }
        .status-shipping { background: #cffafe; color: #0f766e; }
        .status-delivered { background: #dcfce7; color: #166534; }
        .status-cancelled { background: #fee2e2; color: #991b1b; }
        .table th, .table td { vertical-align: middle; }
        select { min-width: 180px; }
        .alert { margin-bottom: 16px; }
    </style>
</head>
<body>
<div class="container">
    <div class="card">
        <div class="d-flex justify-content-between align-items-center mb-3">
            <h2 class="mb-0">Quản lý đơn hàng</h2>
            <a href="{{ route('admin.dashboard') }}" class="btn btn-outline-primary">← Dashboard</a>
        </div>

        @if (session('success'))
            <div class="alert alert-success">{{ session('success') }}</div>
        @endif

        <div class="table-responsive">
            <table class="table table-hover align-middle">
                <thead>
                <tr>
                    <th>#</th>
                    <th>Khách hàng</th>
                    <th>Ngày đặt</th>
                    <th>Tổng tiền</th>
                    <th>Phương thức</th>
                    <th>Trạng thái</th>
                    <th>Thay đổi</th>
                </tr>
                </thead>
                <tbody>
                @foreach ($orders as $order)
                    <tr>
                        <td>#{{ $order->id }}</td>
                        <td>{{ $order->user->name ?? 'Khách hàng' }}</td>
                        <td>{{ $order->created_at->format('d/m/Y H:i') }}</td>
                        <td>{{ number_format($order->total_price, 0, ',', '.') }}đ</td>
                        <td>{{ optional($order->paymentTransaction)->gateway ?? 'COD' }}</td>
                        <td>
                            <span class="status status-{{ $order->status ?? 'pending' }}">{{ $order->status ?? 'pending' }}</span>
                        </td>
                        <td>
                            <form action="{{ route('admin.orders.updateStatus', $order) }}" method="POST" class="d-flex gap-2 align-items-center">
                                @csrf
                                <select name="status" class="form-select form-select-sm">
                                    <option value="pending" {{ $order->status === 'pending' ? 'selected' : '' }}>Chờ xác nhận</option>
                                    <option value="confirmed" {{ $order->status === 'confirmed' ? 'selected' : '' }}>Đã xác nhận</option>
                                    <option value="packaging" {{ $order->status === 'packaging' ? 'selected' : '' }}>Đang đóng gói</option>
                                    <option value="shipping" {{ $order->status === 'shipping' ? 'selected' : '' }}>Đang vận chuyển</option>
                                    <option value="delivered" {{ $order->status === 'delivered' ? 'selected' : '' }}>Đã giao</option>
                                    <option value="cancelled" {{ $order->status === 'cancelled' ? 'selected' : '' }}>Đã hủy</option>
                                </select>
                                <button type="submit" class="btn btn-sm btn-primary">Lưu</button>
                            </form>
                        </td>
                    </tr>
                @endforeach
                </tbody>
            </table>
        </div>

        <div class="mt-3">
            {{ $orders->links() }}
        </div>
    </div>
</div>
</body>
</html>
