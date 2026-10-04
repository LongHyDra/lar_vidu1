@extends('layouts.storefront')

@section('title', 'Đơn Hàng Của Tôi')

@section('account_content')
<!-- 1. THANH ĐIỀU HƯỚNG HEADER -->
    

    <!-- 2. NỘI DUNG DANH SÁCH ĐƠN HÀNG -->
    <div class="account-page">
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
@endsection
