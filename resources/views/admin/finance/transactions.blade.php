@extends('layouts.admin')

@section('title', 'Giao Dịch Thanh Toán | PHỤ KIỆN XE MÁY 247')

@section('content')
<!-- TABS CHUYỂN ĐỔI CHUẨN NÚT BO TRÒN -->
<div class="d-flex gap-2 mb-4">
    <a href="{{ route('admin.finance.index') }}" class="btn btn-sm btn-outline-secondary bg-white fw-bold px-3 py-2 rounded-pill shadow-sm">
        <i class="fa-solid fa-chart-pie me-1"></i> Thống kê chỉ số
    </a>
    <a href="{{ route('admin.finance.transactions') }}" class="btn btn-sm btn-primary fw-bold px-3 py-2 rounded-pill shadow-sm">
        <i class="fa-solid fa-money-bill-transfer me-1"></i> Giao dịch thanh toán
    </a>
</div>

<!-- BỘ LỌC TÌM KIẾM GIAO DỊCH -->
<div class="card-panel">
    <div class="card-panel-title">
        <i class="fa-solid fa-filter text-primary"></i> Tra Cứu Giao Dịch & Quản Lý Đơn COD
    </div>
    <form action="{{ route('admin.finance.transactions') }}" method="GET">
        <div class="row g-3">
            <div class="col-md-4">
                <label class="form-label small fw-bold text-muted">Tìm đơn hàng</label>
                <input type="text" name="search" value="{{ $filters['search'] ?? '' }}" class="form-control" placeholder="Mã đơn, tên hoặc số điện thoại">
            </div>
            <div class="col-md-4">
                <label class="form-label small fw-bold text-muted">Từ ngày tạo đơn</label>
                <input type="date" name="date_from" value="{{ $filters['date_from'] ?? '' }}" class="form-control">
            </div>
            <div class="col-md-4">
                <label class="form-label small fw-bold text-muted">Đến ngày tạo đơn</label>
                <input type="date" name="date_to" value="{{ $filters['date_to'] ?? '' }}" class="form-control">
            </div>
            <div class="col-md-3">
                <label class="form-label small fw-bold text-muted">Số tiền từ (₫)</label>
                <input type="number" name="min_amount" min="0" value="{{ $filters['min_amount'] ?? '' }}" class="form-control" placeholder="Không giới hạn">
            </div>
            <div class="col-md-3">
                <label class="form-label small fw-bold text-muted">Số tiền đến (₫)</label>
                <input type="number" name="max_amount" min="0" value="{{ $filters['max_amount'] ?? '' }}" class="form-control" placeholder="Không giới hạn">
            </div>
            <div class="col-md-3">
                <label class="form-label small fw-bold text-muted">Phương thức</label>
                <select name="gateway" class="form-select">
                    <option value="">Tất cả</option>
                    @foreach($methods as $key => $label)
                    <option value="{{ $key }}" @selected(($filters['gateway'] ?? '') === $key)>{{ $label }}</option>
                    @endforeach
                </select>
            </div>
            <div class="col-md-3">
                <label class="form-label small fw-bold text-muted">Trạng thái thanh toán</label>
                <select name="payment_status" class="form-select">
                    <option value="">Tất cả</option>
                    @foreach($statuses as $key => $label)
                    <option value="{{ $key }}" @selected(($filters['payment_status'] ?? '') === $key)>{{ $label }}</option>
                    @endforeach
                </select>
            </div>
            <div class="col-md-3">
                <label class="form-label small fw-bold text-muted">Sắp xếp theo</label>
                <select name="sort" class="form-select">
                    <option value="newest" @selected(($filters['sort'] ?? 'newest') === 'newest')>Mới nhất</option>
                    <option value="oldest" @selected(($filters['sort'] ?? '') === 'oldest')>Cũ nhất</option>
                    <option value="amount_desc" @selected(($filters['sort'] ?? '') === 'amount_desc')>Số tiền giảm dần</option>
                    <option value="amount_asc" @selected(($filters['sort'] ?? '') === 'amount_asc')>Số tiền tăng dần</option>
                </select>
            </div>
            <div class="col-12 d-flex gap-2 pt-2">
                <button type="submit" class="btn btn-primary btn-sm fw-bold px-3">
                    <i class="fa-solid fa-magnifying-glass me-1"></i> Áp dụng bộ lọc
                </button>
                <a href="{{ route('admin.finance.transactions') }}" class="btn btn-outline-secondary btn-sm fw-semibold bg-white px-3">Xóa bộ lọc</a>
                <a href="{{ route('admin.finance.export', request()->except('page')) }}" class="btn btn-success btn-sm fw-bold ms-auto px-3">
                    <i class="fa-solid fa-file-csv me-1"></i> Xuất file CSV
                </a>
            </div>
        </div>
    </form>
</div>

<p class="text-muted small mb-3">
    <i class="fa-solid fa-circle-info me-1 text-primary"></i> Có <strong>{{ number_format($orders->total()) }}</strong> đơn phù hợp. 
    <em>(COD: xác nhận thu tiền hoặc thất bại; đơn đã thu tiền có thể chuyển sang chờ hoàn tiền rồi xác nhận đã hoàn tiền).</em>
</p>

<!-- BẢNG DANH SÁCH GIAO DỊCH -->
<div class="card-panel">
    <div class="card-panel-title">
        <i class="fa-solid fa-receipt text-primary"></i> Danh Sách Giao Dịch Đơn Hàng ({{ number_format($orders->total()) }} đơn)
    </div>
    <div class="table-responsive">
        <table class="table table-hover align-middle mb-0">
            <thead class="table-light">
                <tr>
                    <th>Mã đơn</th>
                    <th>Khách hàng</th>
                    <th>Phương thức</th>
                    <th class="text-end">Số tiền</th>
                    <th>Thanh toán</th>
                    <th class="text-end" style="min-width: 220px;">Cập nhật COD</th>
                </tr>
            </thead>
            <tbody>
                @forelse($orders as $order)
                @php($canUpdate = $order->gateway === 'cod' && isset($codTransitions[$order->payment_status]))
                <tr>
                    <td>
                        <strong class="text-primary">#{{ $order->id }}</strong><br>
                        <small class="text-muted">{{ \Carbon\Carbon::parse($order->created_at)->format('d/m/Y H:i') }}</small>
                    </td>
                    <td>
                        <div class="fw-bold">{{ $order->name }}</div>
                        <small class="text-muted">{{ $order->phone }}</small>
                    </td>
                    <td>
                        <span class="badge text-bg-{{ $order->gateway === 'cod' ? 'primary' : 'danger' }} px-2 py-1">
                            {{ $methods[$order->gateway] ?? $order->gateway }}
                        </span>
                    </td>
                    <td class="text-end fw-bold text-dark fs-6">
                        {{ number_format($order->total_price, 0, ',', '.') }}₫
                    </td>
                    <td>
                        <span class="badge {{ \App\Support\PaymentStatus::badgeClass($order->payment_status ?? '') }} px-2 py-1">
                            {{ $statuses[$order->payment_status] ?? $order->payment_status }}
                        </span>
                    </td>
                    <td class="text-end">
                        @if($canUpdate)
                        <form method="POST" action="{{ route('admin.finance.update-status', $order->id) }}" class="d-inline-flex align-items-center justify-content-end gap-1">
                            @csrf
                            @method('PATCH')
                            <select name="payment_status" class="form-select form-select-sm" style="width: auto; min-width: 140px;">
                                @foreach($codTransitions[$order->payment_status] as $status)
                                <option value="{{ $status }}" @selected($status === $order->payment_status)>
                                    {{ $statuses[$status] }}
                                </option>
                                @endforeach
                            </select>
                            <input type="hidden" name="current_payment_status" value="{{ $order->payment_status }}">
                            <input type="hidden" name="current_order_status" value="{{ $order->status }}">
                            <input type="hidden" name="current_payment_id" value="{{ $order->payment_id ?? 0 }}">
                            <button type="submit" class="btn btn-primary btn-sm px-2 fw-bold" title="Lưu trạng thái">
                                <i class="fa-solid fa-floppy-disk"></i>
                            </button>
                        </form>
                        @else
                        <span class="text-muted small">—</span>
                        @endif
                    </td>
                </tr>
                @empty
                <tr>
                    <td colspan="6" class="text-center text-muted py-4">Không tìm thấy giao dịch nào.</td>
                </tr>
                @endforelse
            </tbody>
        </table>
    </div>

    @if($orders->hasPages())
    <div class="p-3 border-top mt-2">
        {{ $orders->links() }}
    </div>
    @endif
</div>
@endsection