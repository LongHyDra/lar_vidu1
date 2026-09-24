<!DOCTYPE html>
<html lang="vi">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Đơn Hàng Của Tôi | PHỤ KIỆN XE MÁY 247</title>

    <!-- Google Font Inter -->
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700;800&display=swap" rel="stylesheet">
    <!-- Bootstrap 5 CSS -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
    <!-- FontAwesome 6 Icons -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.0/css/all.min.css">

    <style>
        * { box-sizing: border-box; }
        body { background: #f8fafc; font-family: 'Inter', sans-serif; color: #1e293b; min-height: 100vh; margin: 0; }
        
        /* HEADER ĐỒNG BỘ */
        .site-header {
            background: #ffffff;
            border-bottom: 1px solid #e2e8f0;
            padding: 16px 0;
            margin-bottom: 32px;
            box-shadow: 0 1px 3px rgba(0, 0, 0, 0.03);
        }
        .brand-link {
            font-size: 1.15rem;
            font-weight: 800;
            color: #0f172a;
            text-decoration: none;
            display: flex;
            align-items: center;
            gap: 9px;
        }
        .brand-badge {
            background: #f59e0b;
            color: #000;
            font-size: 0.68rem;
            font-weight: 900;
            padding: 2px 6px;
            border-radius: 4px;
        }

        /* CARD CONTAINER */
        .order-card {
            background: #ffffff;
            border: 1px solid #e2e8f0;
            border-radius: 16px;
            box-shadow: 0 4px 16px rgba(15, 23, 42, 0.04);
            padding: 28px;
        }
        .table > :not(caption) > * > * {
            padding: 14px 16px;
        }
        .badge-status {
            font-size: 0.76rem;
            font-weight: 600;
            padding: 5px 10px;
            border-radius: 999px;
        }
    </style>
</head>

<body>
    <!-- 1. THANH ĐIỀU HƯỚNG HEADER -->
    <header class="site-header">
        <div class="container d-flex justify-content-between align-items-center">
            <a href="{{ route('welcome') }}" class="brand-link">
                <i class="fa-solid fa-motorcycle text-primary fs-5"></i>
                <span>PHỤ KIỆN XE MÁY <span class="brand-badge">247</span></span>
            </a>
            <div class="d-flex align-items-center gap-2">
                <a href="{{ route('welcome') }}" class="btn btn-primary btn-sm fw-bold px-3 py-2 rounded-3 shadow-sm">
                    <i class="fa-solid fa-house me-1"></i> Trang chủ
                </a>
                <a href="{{ route('cart.index') }}" class="btn btn-outline-secondary btn-sm fw-semibold px-3 py-2 rounded-3 bg-white">
                    <i class="fa-solid fa-cart-shopping me-1"></i> Giỏ hàng
                </a>
            </div>
        </div>
    </header>

    <!-- 2. NỘI DUNG DANH SÁCH ĐƠN HÀNG -->
    <div class="container pb-5">
        <div class="order-card">
            <div class="d-flex flex-wrap justify-content-between align-items-center pb-3 mb-4 border-bottom gap-3">
                <div>
                    <h2 class="fw-bold fs-4 text-dark mb-1">Đơn hàng của tôi</h2>
                    <p class="text-muted small mb-0">Theo dõi thông tin và tiến độ giao nhận các đơn hàng bạn đã mua</p>
                </div>
                <a href="{{ route('welcome') }}" class="btn btn-outline-primary btn-sm fw-bold px-3 py-2 rounded-3">
                    <i class="fa-solid fa-bag-shopping me-1"></i> Tiếp tục mua sắm
                </a>
            </div>

            <!-- THÔNG BÁO FLASH MESSAGE -->
            @if (session('success'))
            <div class="alert alert-success alert-dismissible fade show border-0 shadow-sm mb-4" role="alert">
                <i class="fa-solid fa-circle-check me-2"></i>{{ session('success') }}
                <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
            </div>
            @endif

            @if (session('error'))
            <div class="alert alert-danger alert-dismissible fade show border-0 shadow-sm mb-4" role="alert">
                <i class="fa-solid fa-circle-exclamation me-2"></i>{{ session('error') }}
                <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
            </div>
            @endif

            @if ($orders->isEmpty())
            <div class="text-center py-5">
                <div class="text-muted mb-3">
                    <i class="fa-solid fa-box-open" style="font-size: 3.5rem; opacity: 0.35;"></i>
                </div>
                <h5 class="fw-semibold text-secondary">Bạn chưa có đơn hàng nào</h5>
                <p class="text-muted small mb-4">Hãy khám phá các phụ kiện xe máy chất lượng cao ngay hôm nay.</p>
                <a href="{{ route('welcome') }}" class="btn btn-primary px-4 py-2 fw-bold rounded-3">
                    <i class="fa-solid fa-arrow-left me-1"></i> Khám phá sản phẩm
                </a>
            </div>
            @else
            <div class="table-responsive">
                <table class="table table-hover align-middle mb-0">
                    <thead class="table-light text-muted small">
                        <tr>
                            <th style="width: 110px;">Mã đơn</th>
                            <th>Ngày đặt</th>
                            <th class="text-end">Tổng tiền</th>
                            <th class="text-center">Trạng thái</th>
                            <th class="text-center">Mã vận đơn GHN</th>
                            <th class="text-end" style="width: 100px;">Thao tác</th>
                        </tr>
                    </thead>
                    <tbody>
                        @php
                        $statusLabels = [
                            'pending' => 'Chờ xác nhận',
                            'confirmed' => 'Đã xác nhận',
                            'packaging' => 'Đang đóng gói',
                            'shipping' => 'Đang vận chuyển',
                            'delivered' => 'Đã giao hàng',
                            'cancelled' => 'Đã hủy',
                            'cod_ordered' => 'Đã tạo vận đơn COD',
                            'cod_paid' => 'Đã thu tiền COD',
                            'paid' => 'Đã thanh toán',
                            'failed' => 'Thất bại',
                            'refund_pending' => 'Chờ hoàn tiền',
                            'refunded' => 'Đã hoàn tiền',
                        ];
                        @endphp

                        @foreach ($orders as $order)
                        @php
                            $status = $order->status ?? 'pending';
                            $badgeClass = match($status) {
                                'paid', 'cod_paid', 'delivered', 'refunded' => 'text-bg-success',
                                'cancelled', 'failed' => 'text-bg-danger',
                                'shipping' => 'text-bg-info text-white',
                                'confirmed', 'packaging', 'refund_pending' => 'text-bg-primary',
                                default => 'text-bg-warning text-dark'
                            };
                        @endphp
                        <tr>
                            <td class="fw-bold text-primary">#{{ $order->id }}</td>
                            <td>{{ $order->created_at->format('d/m/Y H:i') }}</td>
                            <td class="text-end fw-bold text-dark fs-6">
                                {{ number_format($order->total_price, 0, ',', '.') }}₫
                            </td>
                            <td class="text-center">
                                <span class="badge badge-status {{ $badgeClass }}">
                                    {{ $statusLabels[$status] ?? $status }}
                                </span>
                            </td>
                            <td class="text-center text-muted">
                                @if($order->ghn_order_code)
                                    <span class="badge bg-light text-dark border font-monospace">{{ $order->ghn_order_code }}</span>
                                @else
                                    <span class="text-muted small">—</span>
                                @endif
                            </td>
                            <td class="text-end">
                                <a href="{{ route('user.orders.show', $order) }}" class="btn btn-sm btn-outline-primary fw-semibold px-2 py-1">
                                    Xem <i class="fa-solid fa-angle-right ms-1"></i>
                                </a>
                            </td>
                        </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>

            <!-- PHÂN TRANG -->
            @if (method_exists($orders, 'hasPages') && $orders->hasPages())
            <div class="pt-4 border-top mt-3">
                {{ $orders->links() }}
            </div>
            @endif
            @endif
        </div>
    </div>

    <!-- Bootstrap 5 Bundle JS -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js"></script>
</body>

</html>