@extends('layouts.admin')

@section('title', 'Thống Kê Tài Chính | PHỤ KIỆN XE MÁY 247')

@section('content')
<!-- TABS CHUYỂN ĐỔI CHUẨN NÚT BO TRÒN -->
<div class="d-flex gap-2 mb-4">
    <a href="{{ route('admin.finance.index') }}" class="btn btn-sm btn-primary fw-bold px-3 py-2 rounded-pill shadow-sm">
        <i class="fa-solid fa-chart-pie me-1"></i> Thống kê chỉ số
    </a>
    <a href="{{ route('admin.finance.transactions') }}" class="btn btn-sm btn-outline-secondary bg-white fw-bold px-3 py-2 rounded-pill shadow-sm">
        <i class="fa-solid fa-money-bill-transfer me-1"></i> Giao dịch thanh toán
    </a>
</div>

<!-- BỘ LỌC TÀI CHÍNH -->
<div class="card-panel">
    <div class="card-panel-title">
        <i class="fa-solid fa-filter text-primary"></i> Bộ Lọc Tìm Kiếm Đơn Hàng
    </div>
    <form action="{{ route('admin.finance.index') }}" method="GET">
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
                    <option value="">Tất cả phương thức</option>
                    @foreach($methods as $key => $label)
                    <option value="{{ $key }}" @selected(($filters['gateway'] ?? '') === $key)>{{ $label }}</option>
                    @endforeach
                </select>
            </div>
            <div class="col-md-3">
                <label class="form-label small fw-bold text-muted">Trạng thái thanh toán</label>
                <select name="payment_status" class="form-select">
                    <option value="">Tất cả trạng thái</option>
                    @foreach($statuses as $key => $label)
                    <option value="{{ $key }}" @selected(($filters['payment_status'] ?? '') === $key)>{{ $label }}</option>
                    @endforeach
                </select>
            </div>
            <div class="col-12 d-flex gap-2 pt-2">
                <button type="submit" class="btn btn-primary btn-sm fw-bold px-3">
                    <i class="fa-solid fa-magnifying-glass me-1"></i> Áp dụng bộ lọc
                </button>
                <a href="{{ route('admin.finance.index') }}" class="btn btn-outline-secondary btn-sm fw-semibold bg-white px-3">Xóa bộ lọc</a>
            </div>
        </div>
    </form>
</div>

<p class="text-muted small mb-3">
    <i class="fa-solid fa-circle-info me-1 text-primary"></i> Có <strong>{{ number_format($summary->order_count) }}</strong> đơn phù hợp. Số tiền bao gồm phí vận chuyển, thống kê theo ngày tạo đơn.
</p>

<!-- CÁC THẺ STAT-CARD ĐỒNG BỘ 100% VỚI DASHBOARD -->
<div class="row g-3 mb-4">
    <div class="col-md-3">
        <div class="stat-card">
            <div class="stat-icon blue"><i class="fa-solid fa-sack-dollar"></i></div>
            <div>
                <div class="stat-value text-primary">{{ number_format($summary->total_amount, 0, ',', '.') }}₫</div>
                <div class="stat-label">Tổng giá trị đơn</div>
                <small class="text-muted" style="font-size: 0.7rem;">{{ number_format($summary->order_count) }} đơn (gồm cả đơn hủy)</small>
            </div>
        </div>
    </div>
    <div class="col-md-3">
        <div class="stat-card">
            <div class="stat-icon orange"><i class="fa-solid fa-hourglass-half"></i></div>
            <div>
                <div class="stat-value text-warning">{{ number_format($statusTotals['pending']->total_amount ?? 0, 0, ',', '.') }}₫</div>
                <div class="stat-label">Chờ thanh toán</div>
                <small class="text-muted" style="font-size: 0.7rem;">{{ number_format($statusTotals['pending']->order_count ?? 0) }} đơn</small>
            </div>
        </div>
    </div>
    <div class="col-md-3">
        <div class="stat-card">
            <div class="stat-icon purple"><i class="fa-solid fa-wallet"></i></div>
            <div>
                <div class="stat-value" style="color: #9333ea;">{{ number_format($statusTotals['initiated']->total_amount ?? 0, 0, ',', '.') }}₫</div>
                <div class="stat-label">Đang chờ MoMo</div>
                <small class="text-muted" style="font-size: 0.7rem;">{{ number_format($statusTotals['initiated']->order_count ?? 0) }} đơn</small>
            </div>
        </div>
    </div>
    <div class="col-md-3">
        <div class="stat-card">
            <div class="stat-icon green"><i class="fa-solid fa-circle-check"></i></div>
            <div>
                <div class="stat-value text-success">{{ number_format($statusTotals['paid']->total_amount ?? 0, 0, ',', '.') }}₫</div>
                <div class="stat-label">Đã thanh toán</div>
                <small class="text-muted" style="font-size: 0.7rem;">{{ number_format($statusTotals['paid']->order_count ?? 0) }} đơn</small>
            </div>
        </div>
    </div>
    <div class="col-md-3">
        <div class="stat-card">
            <div class="stat-icon red"><i class="fa-solid fa-circle-xmark"></i></div>
            <div>
                <div class="stat-value text-danger">{{ number_format($statusTotals['failed']->total_amount ?? 0, 0, ',', '.') }}₫</div>
                <div class="stat-label">Thanh toán thất bại</div>
                <small class="text-muted" style="font-size: 0.7rem;">{{ number_format($statusTotals['failed']->order_count ?? 0) }} đơn</small>
            </div>
        </div>
    </div>
    <div class="col-md-3">
        <div class="stat-card">
            <div class="stat-icon gray"><i class="fa-solid fa-ban"></i></div>
            <div>
                <div class="stat-value text-secondary">{{ number_format($statusTotals['cancelled']->total_amount ?? 0, 0, ',', '.') }}₫</div>
                <div class="stat-label">Đã hủy</div>
                <small class="text-muted" style="font-size: 0.7rem;">{{ number_format($statusTotals['cancelled']->order_count ?? 0) }} đơn</small>
            </div>
        </div>
    </div>
    <div class="col-md-3">
        <div class="stat-card">
            <div class="stat-icon blue"><i class="fa-solid fa-rotate-left"></i></div>
            <div>
                <div class="stat-value text-info">{{ number_format($statusTotals['refund_pending']->total_amount ?? 0, 0, ',', '.') }}₫</div>
                <div class="stat-label">Chờ hoàn tiền</div>
                <small class="text-muted" style="font-size: 0.7rem;">{{ number_format($statusTotals['refund_pending']->order_count ?? 0) }} đơn</small>
            </div>
        </div>
    </div>
    <div class="col-md-3">
        <div class="stat-card">
            <div class="stat-icon green"><i class="fa-solid fa-hand-holding-dollar"></i></div>
            <div>
                <div class="stat-value text-success">{{ number_format($statusTotals['refunded']->total_amount ?? 0, 0, ',', '.') }}₫</div>
                <div class="stat-label">Đã hoàn tiền</div>
                <small class="text-muted" style="font-size: 0.7rem;">{{ number_format($statusTotals['refunded']->order_count ?? 0) }} đơn</small>
            </div>
        </div>
    </div>
</div>

<!-- BẢNG THỐNG KÊ PHƯƠNG THỨC -->
<div class="card-panel">
    <div class="card-panel-title">
        <i class="fa-solid fa-layer-group text-primary"></i> Thống Kê Theo Phương Thức Thanh Toán
    </div>
    <div class="table-responsive">
        <table class="table table-hover align-middle mb-0">
            <thead class="table-light">
                <tr>
                    <th>Phương thức</th>
                    <th class="text-end">Số đơn</th>
                    <th class="text-end">Tổng giá trị đơn</th>
                    <th class="text-end">Thực thu thành công</th>
                </tr>
            </thead>
            <tbody>
                @foreach($methods as $key => $label)
                @php($total = $methodTotals[$key] ?? null)
                <tr>
                    <td class="fw-bold">{{ $label }}</td>
                    <td class="text-end">{{ number_format($total->order_count ?? 0) }} đơn</td>
                    <td class="text-end fw-semibold">{{ number_format($total->total_amount ?? 0, 0, ',', '.') }}₫</td>
                    <td class="text-end text-success fw-bold">{{ number_format($total->paid_amount ?? 0, 0, ',', '.') }}₫</td>
                </tr>
                @endforeach
            </tbody>
        </table>
    </div>
</div>
@endsection