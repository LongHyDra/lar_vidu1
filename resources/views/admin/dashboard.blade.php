<!DOCTYPE html>
<html lang="vi">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>Bảng Điều Khiển | PHỤ KIỆN XE MÁY</title>
    <!-- Bootstrap 5 CSS -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
    <!-- FontAwesome 6 Icons -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.0/css/all.min.css">
    <!-- Google Font Inter -->
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700;800&display=swap" rel="stylesheet">
    <style>
        * {
            box-sizing: border-box;
        }

        body {
            background: #f0f4f8;
            font-family: 'Inter', sans-serif;
            min-height: 100vh;
        }

        /* SIDEBAR */
        .sidebar {
            width: 248px;
            height: 100vh;
            position: fixed;
            top: 0;
            left: 0;
            background: #ffffff;
            border-right: 1px solid #e2e8f0;
            display: flex;
            flex-direction: column;
            z-index: 100;
            box-shadow: 6px 0 24px rgba(15, 23, 42, 0.06);
            overflow-y: auto;
        }

        .sidebar-brand {
            padding: 18px 16px 16px;
            border-bottom: 1px solid #f1f5f9;
            display: flex;
            align-items: center;
            gap: 11px;
            background: linear-gradient(180deg, #f8fafc 0%, #ffffff 100%);
        }

        .brand-icon-wrap {
            position: relative;
            width: 42px;
            height: 42px;
            flex-shrink: 0;
        }

        .brand-icon-glow {
            position: absolute;
            inset: -2px;
            background: linear-gradient(135deg, #2563eb, #f59e0b);
            border-radius: 12px;
            filter: blur(4px);
            opacity: 0.7;
        }

        .brand-icon-inner {
            position: relative;
            width: 100%;
            height: 100%;
            background: linear-gradient(135deg, #0f172a, #1e293b);
            border: 1px solid rgba(255, 255, 255, 0.2);
            border-radius: 11px;
            display: flex;
            align-items: center;
            justify-content: center;
            color: #60a5fa;
            font-size: 1.15rem;
            box-shadow: 0 4px 10px rgba(15, 23, 42, 0.3);
        }

        .sidebar-brand .brand-text {
            font-size: 0.95rem;
            font-weight: 900;
            color: #0f172a;
            line-height: 1.1;
            display: flex;
            align-items: center;
            gap: 5px;
        }

        .badge-247-sm {
            background: linear-gradient(135deg, #f59e0b, #d97706);
            color: #fff;
            font-size: 0.65rem;
            font-weight: 900;
            padding: 1px 5px;
            border-radius: 4px;
        }

        .sidebar-nav {
            padding: 14px 10px 10px;
        }

        .nav-label {
            font-size: 0.65rem;
            font-weight: 700;
            text-transform: uppercase;
            letter-spacing: 1px;
            color: #94a3b8;
            padding: 14px 10px 7px;
        }

        .nav-item-link {
            display: flex;
            align-items: center;
            gap: 10px;
            min-height: 44px;
            padding: 7px 10px;
            border-radius: 9px;
            color: #64748b;
            text-decoration: none;
            font-weight: 500;
            font-size: 0.875rem;
            transition: all 0.18s;
            margin-bottom: 3px;
        }

        .nav-item-link:hover {
            background: #f1f5f9;
            color: #1e293b;
        }

        .nav-item-link.active {
            background: #eff6ff;
            color: #2563eb;
            font-weight: 600;
            box-shadow: inset 3px 0 #2563eb;
        }

        .nav-item-link .nav-icon {
            width: 32px;
            height: 32px;
            border-radius: 8px;
            display: flex;
            align-items: center;
            justify-content: center;
            font-size: 0.8rem;
            background: #f1f5f9;
            transition: all 0.18s;
        }

        .nav-item-link.active .nav-icon {
            background: #dbeafe;
            color: #2563eb;
        }

        .sidebar-footer {
            margin: 8px 10px 14px;
            padding: 11px;
            border: 1px solid #e2e8f0;
            border-radius: 12px;
            background: #f8fafc;
        }

        .sidebar-user {
            display: flex;
            align-items: center;
            gap: 9px;
            min-width: 0;
        }

        .sidebar-avatar {
            width: 32px;
            height: 32px;
            border-radius: 9px;
            display: grid;
            place-items: center;
            flex-shrink: 0;
            background: #dbeafe;
            color: #2563eb;
            font-weight: 800;
            font-size: .8rem;
        }

        .sidebar-user-name {
            overflow: hidden;
            text-overflow: ellipsis;
            white-space: nowrap;
            font-size: .76rem;
            font-weight: 700;
            color: #1e293b;
        }

        .sidebar-user-role {
            color: #94a3b8;
            font-size: .67rem;
        }

        .sidebar-logout {
            display: inline-flex;
            align-items: center;
            justify-content: center;
            width: 30px;
            height: 30px;
            border: 0;
            border-radius: 8px;
            color: #ef4444;
            background: #fee2e2;
        }

        /* MAIN CONTENT */
        .main-content {
            margin-left: 248px;
            padding: 28px 32px;
            min-height: 100vh;
        }

        .topbar {
            display: flex;
            justify-content: space-between;
            align-items: center;
            background: #ffffff;
            padding: 14px 22px;
            border-radius: 14px;
            box-shadow: 0 2px 8px rgba(0, 0, 0, 0.04);
            margin-bottom: 24px;
            border: 1px solid #f1f5f9;
        }

        .welcome-card {
            background: linear-gradient(135deg, #1e293b, #0f172a);
            border-radius: 16px;
            padding: 28px;
            color: #fff;
            margin-bottom: 24px;
            position: relative;
            overflow: hidden;
        }

        .welcome-card h2 {
            font-size: 1.4rem;
            font-weight: 800;
            margin-bottom: 8px;
        }

        .welcome-card p {
            font-size: 0.88rem;
            color: #94a3b8;
            margin: 0;
            max-width: 600px;
        }

        .welcome-card .bg-icon {
            position: absolute;
            right: 20px;
            bottom: -15px;
            font-size: 7rem;
            color: rgba(255, 255, 255, 0.05);
        }

        .stats-row {
            margin-bottom: 24px;
        }

        .stat-card {
            background: #fff;
            border-radius: 14px;
            padding: 20px;
            border: 1px solid #f1f5f9;
            box-shadow: 0 2px 8px rgba(0, 0, 0, 0.04);
            display: flex;
            align-items: center;
            gap: 14px;
        }

        .stat-icon {
            width: 52px;
            height: 52px;
            border-radius: 12px;
            display: flex;
            align-items: center;
            justify-content: center;
            font-size: 1.25rem;
            flex-shrink: 0;
        }

        .stat-icon.blue {
            background: #eff6ff;
            color: #2563eb;
        }

        .stat-icon.green {
            background: #f0fdf4;
            color: #16a34a;
        }

        .stat-icon.orange {
            background: #fff7ed;
            color: #ea580c;
        }

        .stat-icon.purple {
            background: #faf5ff;
            color: #9333ea;
        }

        .stat-value {
            font-size: 1.5rem;
            font-weight: 800;
            color: #0f172a;
            line-height: 1;
        }

        .stat-label {
            font-size: 0.8rem;
            color: #64748b;
            margin-top: 4px;
            font-weight: 500;
        }

        .report-panel {
            background: #fff;
            border-radius: 16px;
            border: 1px solid #f1f5f9;
            box-shadow: 0 2px 12px rgba(0, 0, 0, 0.04);
            padding: 24px;
            height: 100%;
        }

        .report-heading {
            display: flex;
            align-items: center;
            justify-content: space-between;
            gap: 12px;
            margin-bottom: 18px;
        }

        .report-heading h3 {
            font-size: 1rem;
            font-weight: 700;
            color: #1e293b;
            margin: 0;
        }

        .revenue-bars {
            display: flex;
            align-items: end;
            gap: 6px;
            min-height: 170px;
            border-bottom: 1px solid #e2e8f0;
            padding: 12px 4px 0;
        }

        .revenue-bar-wrap {
            flex: 1;
            min-width: 10px;
            height: 150px;
            display: flex;
            align-items: end;
            justify-content: center;
            position: relative;
        }

        .revenue-bar {
            width: 100%;
            max-width: 24px;
            min-height: 3px;
            background: linear-gradient(180deg, #2563eb, #60a5fa);
            border-radius: 5px 5px 0 0;
        }

        .revenue-bar-label {
            position: absolute;
            bottom: -23px;
            font-size: 0.62rem;
            color: #64748b;
            white-space: nowrap;
        }

        .card-panel {
            background: #ffffff;
            border-radius: 16px;
            border: 1px solid #f1f5f9;
            box-shadow: 0 2px 12px rgba(0, 0, 0, 0.04);
            padding: 24px;
            margin-bottom: 24px;
        }

        .card-panel-title {
            font-size: 1rem;
            font-weight: 700;
            color: #1e293b;
            margin-bottom: 16px;
            display: flex;
            align-items: center;
            gap: 8px;
        }

        .action-grid {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(220px, 1fr));
            gap: 14px;
        }

        .action-card-btn {
            display: flex;
            align-items: center;
            gap: 12px;
            padding: 14px 18px;
            border-radius: 12px;
            text-decoration: none;
            font-weight: 600;
            font-size: 0.88rem;
            border: 1px solid #e2e8f0;
            background: #f8fafc;
            color: #334155;
        }

        .action-card-btn:hover {
            background: #fff;
            color: #2563eb;
            border-color: #bfdbfe;
        }

        .action-card-btn .icon-box {
            width: 38px;
            height: 38px;
            border-radius: 10px;
            display: flex;
            align-items: center;
            justify-content: center;
            font-size: 1rem;
        }

        .icon-blue {
            background: #eff6ff;
            color: #2563eb;
        }

        .icon-green {
            background: #f0fdf4;
            color: #16a34a;
        }

        .icon-orange {
            background: #fff7ed;
            color: #ea580c;
        }

        .icon-purple {
            background: #faf5ff;
            color: #9333ea;
        }

        .order-status-chip {
            display: inline-flex;
            align-items: center;
            gap: 7px;
            padding: 7px 11px;
            border: 1px solid #cbd5e1;
            border-radius: 999px;
            background: #fff;
            color: #475569;
            text-decoration: none;
            font-size: 0.78rem;
            font-weight: 700;
        }

        .order-status-chip.active {
            background: #2563eb;
            border-color: #2563eb;
            color: #fff;
        }
    </style>
</head>

<body>
    @php
    $orderStatusLabels = [
    'pending' => 'Chờ xác nhận',
    'confirmed' => 'Đã xác nhận',
    'packaging' => 'Đang đóng gói',
    'shipping' => 'Đang vận chuyển',
    'delivered' => 'Đã giao',
    'cancelled' => 'Đã hủy',
    'cod_ordered' => 'Đã tạo vận đơn',
    ];
    @endphp

    <!-- SIDEBAR -->
    <div class="sidebar">
        <div class="sidebar-brand">
            <div class="brand-icon-wrap">
                <div class="brand-icon-glow"></div>
                <div class="brand-icon-inner"><i class="fa-solid fa-motorcycle"></i></div>
            </div>
            <div>
                <div class="brand-text">PHỤ KIỆN XE MÁY <span class="badge-247-sm">247</span></div>
            </div>
        </div>

        <div class="sidebar-nav">
            <div class="nav-label"><i class="fa-solid fa-store me-1"></i> Cửa hàng</div>
            <a href="{{ route('welcome') }}" class="nav-item-link">
                <div class="nav-icon"><i class="fa-solid fa-house"></i></div> Trang chủ
            </a>
            <a href="{{ route('cart.index') }}" class="nav-item-link">
                <div class="nav-icon"><i class="fa-solid fa-cart-shopping"></i></div> Giỏ hàng
            </a>
            <a href="{{ route('categories.index') }}" class="nav-item-link">
                <div class="nav-icon"><i class="fa-solid fa-tags"></i></div> Danh Mục Phụ Kiện
            </a>
            <a href="{{ route('products.index') }}" class="nav-item-link">
                <div class="nav-icon"><i class="fa-solid fa-box"></i></div> Sản Phẩm Phụ Kiện
            </a>

            @auth
            @if(Auth::user()->isAdmin())
            <div class="nav-label" style="margin-top: 16px;"><i class="fa-solid fa-shield-halved me-1"></i> Quản trị</div>
            <a href="{{ route('admin.dashboard', ['section' => 'overview']) }}" class="nav-item-link {{ $section === 'overview' ? 'active' : '' }}">
                <div class="nav-icon"><i class="fa-solid fa-chart-line"></i></div> Bảng Điều Khiển
            </a>
            <a href="{{ route('admin.dashboard', ['section' => 'orders']) }}" class="nav-item-link {{ $section === 'orders' ? 'active' : '' }}">
                <div class="nav-icon"><i class="fa-solid fa-receipt"></i></div> Đơn hàng
            </a>
            <a href="{{ route('admin.dashboard', ['section' => 'financial']) }}" class="nav-item-link {{ $section === 'financial' ? 'active' : '' }}">
                <div class="nav-icon"><i class="fa-solid fa-chart-pie"></i></div> Thống kê tài chính
            </a>
            <a href="{{ route('admin.dashboard', ['section' => 'inventory']) }}" class="nav-item-link {{ $section === 'inventory' ? 'active' : '' }}">
                <div class="nav-icon"><i class="fa-solid fa-warehouse"></i></div> Nhật ký tồn kho
            </a>
            <a href="{{ route('admin.dashboard', ['section' => 'payments']) }}" class="nav-item-link {{ $section === 'payments' ? 'active' : '' }}">
                <div class="nav-icon"><i class="fa-solid fa-money-check-dollar"></i></div> Giao dịch thanh toán
            </a>
            <a href="{{ route('admin.dashboard', ['section' => 'users']) }}" class="nav-item-link {{ $section === 'users' ? 'active' : '' }}">
                <div class="nav-icon"><i class="fa-solid fa-users"></i></div> Người dùng
            </a>
            @endif
            @endauth
        </div>

        @auth
        <div class="sidebar-footer">
            <div class="sidebar-user">
                <div class="sidebar-avatar">{{ strtoupper(substr(Auth::user()->name, 0, 1)) }}</div>
                <div class="flex-grow-1 min-w-0">
                    <div class="sidebar-user-name">{{ Auth::user()->name }}</div>
                    <div class="sidebar-user-role">Quản trị viên</div>
                </div>
                <form action="{{ route('logout') }}" method="POST">
                    @csrf
                    <button class="sidebar-logout" type="submit" title="Đăng xuất"><i class="fa-solid fa-arrow-right-from-bracket"></i></button>
                </form>
            </div>
        </div>
        @endauth
    </div>

    <!-- MAIN CONTENT -->
    <div class="main-content">
        <!-- TOPBAR -->
        <div class="topbar">
            <div>
                <div class="topbar-title">Hệ Thống Quản Lý Phụ Kiện Xe Máy</div>
                <div class="text-muted small"><i class="fa-regular fa-clock me-1"></i>{{ now()->format('d/m/Y') }}</div>
            </div>
            <!-- CỤM NÚT XUẤT FILE LUÔN HIỂN THỊ Ở MỌI TAB -->
            <div class="d-flex align-items-center gap-2">
                <a class="btn btn-sm btn-success fw-bold shadow-sm" href="{{ url('/admin/reports/revenue/excel') }}?from={{ $fromDate }}&to={{ $toDate }}" title="Tải file Excel doanh thu">
                    <i class="fa-solid fa-file-excel me-1"></i> Xuất Excel
                </a>
                <a class="btn btn-sm btn-danger fw-bold shadow-sm" target="_blank" href="{{ url('/admin/reports/revenue/print') }}?from={{ $fromDate }}&to={{ $toDate }}" title="Mở bản in hoặc xuất PDF">
                    <i class="fa-solid fa-file-pdf me-1"></i> Báo cáo PDF
                </a>
            </div>
        </div>

        @if(session('success'))
        <div class="alert alert-success alert-dismissible fade show" role="alert">
            <i class="fa-solid fa-circle-check me-2"></i>{{ session('success') }}
            <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
        </div>
        @endif
        @if(session('error'))
        <div class="alert alert-danger alert-dismissible fade show" role="alert">
            <i class="fa-solid fa-circle-exclamation me-2"></i>{{ session('error') }}
            <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
        </div>
        @endif

        <!-- OVERVIEW SECTION -->
        <div class="welcome-card" data-dashboard-section="overview">
            <i class="fa-solid fa-motorcycle bg-icon"></i>
            <h2>Xin chào Quản Trị Viên! 👋</h2>
            <p>Chào mừng bạn đến với Hệ thống Quản lý Phụ Kiện Xe Máy. Theo dõi nhanh các chỉ số kho hàng và thực hiện các thao tác quản trị bên dưới.</p>
        </div>

        <div class="row stats-row g-3" data-dashboard-section="overview">
            <div class="col-md-3">
                <div class="stat-card">
                    <div class="stat-icon blue"><i class="fa-solid fa-box"></i></div>
                    <div>
                        <div class="stat-value">{{ $totalProducts }}</div>
                        <div class="stat-label">Tổng sản phẩm</div>
                    </div>
                </div>
            </div>
            <div class="col-md-3">
                <div class="stat-card">
                    <div class="stat-icon green"><i class="fa-solid fa-tags"></i></div>
                    <div>
                        <div class="stat-value">{{ $totalCategories }}</div>
                        <div class="stat-label">Tổng danh mục</div>
                    </div>
                </div>
            </div>
            <div class="col-md-3">
                <div class="stat-card">
                    <div class="stat-icon orange"><i class="fa-solid fa-triangle-exclamation"></i></div>
                    <div>
                        <div class="stat-value">{{ $lowStockProducts }}</div>
                        <div class="stat-label">Sắp hết hàng (≤5)</div>
                    </div>
                </div>
            </div>
            <div class="col-md-3">
                <div class="stat-card">
                    <div class="stat-icon purple"><i class="fa-solid fa-wallet"></i></div>
                    <div>
                        <div class="stat-value" style="font-size: 1.15rem;">{{ number_format($totalValue ?? 0, 0, ',', '.') }}₫</div>
                        <div class="stat-label">Tổng giá trị kho</div>
                    </div>
                </div>
            </div>
        </div>

        <div class="card-panel" data-dashboard-section="overview">
            <div class="card-panel-title"><i class="fa-solid fa-bolt" style="color:#f59e0b;"></i> Thao Tác Nhanh Hệ Thống</div>
            <div class="action-grid">
                <a href="{{ route('categories.create') }}" class="action-card-btn">
                    <div class="icon-box icon-blue"><i class="fa-solid fa-folder-plus"></i></div> Thêm danh mục mới
                </a>
                <a href="{{ route('products.create') }}" class="action-card-btn">
                    <div class="icon-box icon-green"><i class="fa-solid fa-cart-plus"></i></div> Thêm sản phẩm mới
                </a>
                <a href="{{ route('admin.dashboard', ['section' => 'orders']) }}" class="action-card-btn">
                    <div class="icon-box icon-blue"><i class="fa-solid fa-receipt"></i></div> Quản lý đơn hàng
                </a>
                <a href="{{ route('admin.dashboard', ['section' => 'inventory']) }}" class="action-card-btn">
                    <div class="icon-box icon-orange"><i class="fa-solid fa-warehouse"></i></div> Xem nhật ký tồn kho
                </a>
            </div>
        </div>

        <!-- FINANCIAL SECTION -->
        <div data-dashboard-section="financial">
            <div class="row g-3 mb-4">
                <div class="col-md-3">
                    <div class="stat-card h-100">
                        <div class="stat-icon green"><i class="fa-solid fa-sack-dollar"></i></div>
                        <div>
                            <div class="stat-value" style="font-size:1.15rem;">{{ number_format($totalRevenue, 0, ',', '.') }}₫</div>
                            <div class="stat-label">Tổng doanh thu</div>
                        </div>
                    </div>
                </div>
                <div class="col-md-3">
                    <div class="stat-card h-100">
                        <div class="stat-icon blue"><i class="fa-solid fa-calendar-day"></i></div>
                        <div>
                            <div class="stat-value" style="font-size:1.15rem;">{{ number_format($todayRevenue, 0, ',', '.') }}₫</div>
                            <div class="stat-label">Doanh thu hôm nay</div>
                        </div>
                    </div>
                </div>
                <div class="col-md-3">
                    <div class="stat-card h-100">
                        <div class="stat-icon purple"><i class="fa-solid fa-calendar-days"></i></div>
                        <div>
                            <div class="stat-value" style="font-size:1.15rem;">{{ number_format($monthRevenue, 0, ',', '.') }}₫</div>
                            <div class="stat-label">Doanh thu tháng này</div>
                        </div>
                    </div>
                </div>
                <div class="col-md-3">
                    <div class="stat-card h-100">
                        <div class="stat-icon orange"><i class="fa-solid fa-chart-simple"></i></div>
                        <div>
                            <div class="stat-value">{{ number_format($totalSoldQty) }}</div>
                            <div class="stat-label">Số lượng đã bán</div>
                        </div>
                    </div>
                </div>
            </div>

            <div class="row g-3 mb-4">
                <div class="col-lg-8">
                    <div class="report-panel">
                        <div class="report-heading">
                            <div>
                                <h3>Doanh thu theo ngày</h3>
                                <p class="text-muted small mb-0">Biểu đồ trong {{ $period }} ngày gần nhất (không tính đơn hủy)</p>
                            </div>
                            <form method="GET" action="{{ route('admin.dashboard') }}" class="d-flex flex-wrap gap-2 align-items-center">
                                <input type="hidden" name="section" value="financial">
                                <select name="period" class="form-select form-select-sm" style="width: auto;" onchange="this.form.submit()">
                                    @foreach([7 => '7 ngày', 30 => '30 ngày', 90 => '90 ngày', 365 => '1 năm'] as $val => $lbl)
                                    <option value="{{ $val }}" {{ $period === $val ? 'selected' : '' }}>{{ $lbl }}</option>
                                    @endforeach
                                </select>
                                <input type="date" name="from" value="{{ $fromDate }}" class="form-control form-control-sm" style="width: auto;">
                                <input type="date" name="to" value="{{ $toDate }}" class="form-control form-control-sm" style="width: auto;">
                                <button class="btn btn-sm btn-primary" type="submit"><i class="fa-solid fa-filter me-1"></i>Lọc</button>
                                <a class="btn btn-sm btn-success fw-semibold" href="{{ url('/admin/reports/revenue/excel') }}?from={{ $fromDate }}&to={{ $toDate }}" title="Xuất file Excel">
                                    <i class="fa-solid fa-file-excel me-1"></i> Xuất Excel
                                </a>
                                <a class="btn btn-sm btn-danger fw-semibold" target="_blank" href="{{ url('/admin/reports/revenue/print') }}?from={{ $fromDate }}&to={{ $toDate }}" title="Bản in PDF">
                                    <i class="fa-solid fa-file-pdf me-1"></i> Báo cáo PDF
                                </a>
                            </form>
                        </div>
                        @php $maxDaily = max((float) ($dailyRevenue->max('total_revenue') ?? 0), 1); @endphp
                        <div class="revenue-bars">
                            @forelse($dailyRevenue as $rev)
                            <div class="revenue-bar-wrap" title="{{ $rev->date }}: {{ number_format($rev->total_revenue, 0, ',', '.') }}₫">
                                <div class="revenue-bar" data-height="{{ max(3, ($rev->total_revenue / $maxDaily) * 145) }}"></div>
                                <span class="revenue-bar-label">{{ date('d/m', strtotime($rev->date)) }}</span>
                            </div>
                            @empty
                            <div class="w-100 text-center text-muted small py-4">Chưa có dữ liệu doanh thu trong kỳ này.</div>
                            @endforelse
                        </div>
                    </div>
                </div>
                <div class="col-lg-4">
                    <div class="report-panel">
                        <div class="report-heading">
                            <h3>Tổng quan đơn hàng</h3>
                        </div>
                        <div class="d-flex justify-content-between border-bottom py-2"><span class="text-muted">Tổng đơn</span><strong>{{ number_format($totalOrders) }}</strong></div>
                        <div class="d-flex justify-content-between border-bottom py-2"><span class="text-muted">Đơn đã hủy</span><strong class="text-danger">{{ number_format($cancelledOrders) }}</strong></div>
                        <div class="d-flex justify-content-between py-2"><span class="text-muted">Đơn thành công</span><strong class="text-success">{{ number_format($totalOrders - $cancelledOrders) }}</strong></div>
                    </div>
                </div>
            </div>

            <div class="row g-3 mb-4">
                <div class="col-lg-7">
                    <div class="report-panel">
                        <div class="report-heading">
                            <div>
                                <h3>Top 5 sản phẩm bán chạy</h3>
                                <p class="text-muted small mb-0">Theo số lượng đã xuất kho</p>
                            </div><i class="fa-solid fa-fire text-danger"></i>
                        </div>
                        <div class="table-responsive">
                            <table class="table align-middle mb-0">
                                <thead>
                                    <tr>
                                        <th>#</th>
                                        <th>Tên sản phẩm</th>
                                        <th class="text-end">Số lượng đã bán</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @forelse($topProducts as $index => $prod)
                                    <tr>
                                        <td>{{ $index + 1 }}</td>
                                        <td class="fw-bold">{{ $prod->name }}</td>
                                        <td class="text-end text-primary fw-bold">{{ number_format($prod->total_qty) }}</td>
                                    </tr>
                                    @empty
                                    <tr>
                                        <td colspan="3" class="text-center text-muted py-3">Chưa có dữ liệu bán hàng.</td>
                                    </tr>
                                    @endforelse
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>
                <div class="col-lg-5">
                    <div class="report-panel">
                        <div class="report-heading">
                            <div>
                                <h3>Doanh thu theo tháng</h3>
                                <p class="text-muted small mb-0">12 tháng gần nhất</p>
                            </div><i class="fa-solid fa-calendar-check text-primary"></i>
                        </div>
                        <div class="table-responsive">
                            <table class="table align-middle mb-0">
                                <thead>
                                    <tr>
                                        <th>Tháng</th>
                                        <th class="text-end">Doanh thu</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @forelse($monthlyRevenue as $mRev)
                                    <tr>
                                        <td>{{ date('m/Y', strtotime($mRev->month . '-01')) }}</td>
                                        <td class="text-end fw-bold text-primary">{{ number_format($mRev->total_revenue, 0, ',', '.') }}₫</td>
                                    </tr>
                                    @empty
                                    <tr>
                                        <td colspan="2" class="text-center text-muted py-3">Chưa có số liệu.</td>
                                    </tr>
                                    @endforelse
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- ORDERS SECTION -->
        <div class="card-panel" data-dashboard-section="orders">
            <div class="d-flex flex-wrap align-items-center gap-2 mb-3">
                <div class="card-panel-title mb-0 me-2"><i class="fa-solid fa-receipt text-primary"></i> Quản lý đơn hàng</div>
                <div class="d-flex flex-wrap gap-2">
                    <a href="{{ route('admin.dashboard', ['section' => 'orders']) }}" class="order-status-chip {{ !$orderStatus ? 'active' : '' }}">
                        Tất cả <span>{{ $orderStatusCounts->sum() }}</span>
                    </a>
                    @foreach($orderStatusLabels as $st => $lbl)
                    @if($st !== 'cod_ordered')
                    <a href="{{ route('admin.dashboard', ['section' => 'orders', 'order_status' => $st]) }}" class="order-status-chip {{ $orderStatus === $st ? 'active' : '' }}">
                        {{ $lbl }} <span>{{ $orderStatusCounts[$st] ?? 0 }}</span>
                    </a>
                    @endif
                    @endforeach
                </div>
            </div>
            <div class="table-responsive">
                <table class="table table-hover align-middle">
                    <thead>
                        <tr>
                            <th>Mã đơn</th>
                            <th>Khách hàng</th>
                            <th>Ngày đặt</th>
                            <th>Tổng tiền</th>
                            <th>Trạng thái</th>
                            <th>Thao tác</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($recentOrders as $order)
                        <tr>
                            <td class="fw-bold">#{{ $order->id }}</td>
                            <td>{{ $order->user->name ?? $order->name }}</td>
                            <td>{{ $order->created_at->format('d/m/Y H:i') }}</td>
                            <td class="fw-bold text-primary">{{ number_format($order->total_price, 0, ',', '.') }}₫</td>
                            <td>
                                <span class="badge text-bg-{{ $order->status === 'cancelled' ? 'danger' : ($order->status === 'delivered' ? 'success' : 'warning') }}">
                                    {{ $orderStatusLabels[$order->status] ?? $order->status }}
                                </span>
                            </td>
                            <td>
                                @if($order->status === 'cancelled')
                                <span class="text-muted small"><i class="fa-solid fa-lock me-1"></i>Đã hủy</span>
                                @else
                                <form action="{{ route('admin.orders.updateStatus', $order) }}" method="POST" class="d-flex gap-2">
                                    @csrf
                                    <select name="status" class="form-select form-select-sm">
                                        @foreach($orderStatusLabels as $st => $lbl)
                                        @if($st !== 'cod_ordered')
                                        <option value="{{ $st }}" {{ $order->status === $st ? 'selected' : '' }}>{{ $lbl }}</option>
                                        @endif
                                        @endforeach
                                    </select>
                                    <button class="btn btn-sm btn-primary">Lưu</button>
                                </form>
                                @endif
                            </td>
                        </tr>
                        @empty
                        <tr>
                            <td colspan="6" class="text-center text-muted py-4">Không có đơn hàng nào.</td>
                        </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>

        <!-- INVENTORY MOVEMENTS SECTION -->
        <div class="card-panel" data-dashboard-section="inventory">
            <div class="card-panel-title"><i class="fa-solid fa-warehouse text-warning"></i> Nhật ký biến động tồn kho</div>
            <div class="table-responsive">
                <table class="table align-middle table-hover">
                    <thead>
                        <tr>
                            <th>Thời gian</th>
                            <th>Sản phẩm</th>
                            <th>Loại</th>
                            <th>Biến động</th>
                            <th>Tồn sau GD</th>
                            <th>Người thực hiện</th>
                            <th>Ghi chú</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($movements as $m)
                        <tr>
                            <td>{{ $m->created_at->format('d/m/Y H:i') }}</td>
                            <td class="fw-bold">{{ $m->product->name ?? 'Sản phẩm đã xóa' }}</td>
                            <td>
                                <span class="badge text-bg-{{ $m->type === 'in' ? 'success' : 'primary' }}">
                                    {{ $m->type === 'in' ? 'Nhập kho' : 'Xuất kho' }}
                                </span>
                            </td>
                            <td class="fw-bold {{ $m->quantity > 0 ? 'text-success' : 'text-danger' }}">
                                {{ $m->quantity > 0 ? '+' : '' }}{{ $m->quantity }}
                            </td>
                            <td><strong>{{ $m->stock_after }}</strong></td>
                            <td>{{ $m->user->name ?? 'Hệ thống' }}</td>
                            <td class="text-muted small">{{ $m->note }}</td>
                        </tr>
                        @empty
                        <tr>
                            <td colspan="7" class="text-center text-muted py-5">Chưa có lịch sử tồn kho.</td>
                        </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>

        <!-- USERS SECTION -->
        <div class="card-panel" data-dashboard-section="users">
            <div class="d-flex justify-content-between align-items-center mb-3">
                <div class="card-panel-title mb-0"><i class="fa-solid fa-users text-primary"></i> Quản lý người dùng</div>
                <button type="button" class="btn btn-primary btn-sm fw-semibold" data-bs-toggle="modal" data-bs-target="#createUserModal">
                    <i class="fa-solid fa-user-plus me-1"></i> Thêm tài khoản mới
                </button>
            </div>

            <div class="table-responsive">
                <table class="table align-middle table-hover">
                    <thead class="table-light">
                        <tr>
                            <th style="width: 60px;">ID</th>
                            <th>Họ tên</th>
                            <th>Email</th>
                            <th>Vai trò</th>
                            <th>Số đơn</th>
                            <th>Ngày tạo</th>
                            <th class="text-center" style="width: 140px;">Thao tác</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($users as $user)
                        <tr>
                            <td>#{{ $user->id }}</td>
                            <td class="fw-bold">{{ $user->name }}</td>
                            <td>{{ $user->email }}</td>
                            <td>
                                @php
                                $roleColors = [
                                'admin' => 'bg-dark',
                                'manager' => 'bg-warning text-dark',
                                'editor' => 'bg-info text-dark',
                                'customer' => 'bg-primary',
                                ];
                                @endphp
                                <span class="badge {{ $roleColors[$user->role] ?? 'bg-secondary' }}">
                                    {{ ucfirst($user->role) }}
                                </span>
                            </td>
                            <td><span class="badge bg-light text-dark border">{{ $user->orders_count ?? 0 }} đơn</span></td>
                            <td class="text-muted small">{{ $user->created_at ? $user->created_at->format('d/m/Y') : '—' }}</td>
                            <td class="text-center">
                                <button type="button" class="btn btn-sm btn-outline-primary me-1" data-bs-toggle="modal" data-bs-target="#editUserModal{{ $user->id }}" title="Chỉnh sửa">
                                    <i class="fa-solid fa-pen-to-square"></i>
                                </button>
                                @if(Auth::id() !== $user->id)
                                <form action="{{ route('admin.users.destroy', $user) }}" method="POST" class="d-inline" onsubmit="return confirm('Bạn có chắc muốn xóa tài khoản {{ $user->name }}?');">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit" class="btn btn-sm btn-outline-danger" title="Xóa">
                                        <i class="fa-solid fa-trash"></i>
                                    </button>
                                </form>
                                @else
                                <span class="badge bg-light text-muted small border">Đang dùng</span>
                                @endif
                            </td>
                        </tr>

                        <!-- MODAL EDIT USER -->
                        <div class="modal fade" id="editUserModal{{ $user->id }}" tabindex="-1" aria-hidden="true">
                            <div class="modal-dialog">
                                <div class="modal-content border-0 shadow">
                                    <div class="modal-header bg-light">
                                        <h5 class="modal-title fs-6 fw-bold">
                                            <i class="fa-solid fa-user-pen text-primary me-1"></i> Sửa tài khoản: {{ $user->name }}
                                        </h5>
                                        <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                                    </div>
                                    <form action="{{ route('admin.users.update', $user) }}" method="POST">
                                        @csrf
                                        @method('PUT')
                                        <div class="modal-body text-start">
                                            <div class="mb-3">
                                                <label class="form-label fw-semibold small">Họ và tên <span class="text-danger">*</span></label>
                                                <input type="text" name="name" class="form-control form-control-sm" value="{{ old('name', $user->name) }}" required>
                                            </div>
                                            <div class="mb-3">
                                                <label class="form-label fw-semibold small">Email <span class="text-danger">*</span></label>
                                                <input type="email" name="email" class="form-control form-control-sm" value="{{ old('email', $user->email) }}" required>
                                            </div>
                                            <div class="mb-3">
                                                <label class="form-label fw-semibold small">Vai trò <span class="text-danger">*</span></label>
                                                <select name="role" class="form-select form-select-sm" required>
                                                    <option value="customer" @selected($user->role === 'customer')>Khách hàng (customer)</option>
                                                    <option value="editor" @selected($user->role === 'editor')>Biên tập viên (editor)</option>
                                                    <option value="manager" @selected($user->role === 'manager')>Quản lý kho (manager)</option>
                                                    <option value="admin" @selected($user->role === 'admin')>Quản trị viên (admin)</option>
                                                </select>
                                            </div>
                                            <hr class="my-3">
                                            <div class="mb-2">
                                                <label class="form-label fw-semibold small">Đổi mật khẩu mới <span class="text-muted fw-normal">(để trống nếu không đổi)</span></label>
                                                <input type="password" name="password" class="form-control form-control-sm" placeholder="Tối thiểu 8 ký tự">
                                            </div>
                                            <div class="mb-2">
                                                <label class="form-label fw-semibold small">Nhập lại mật khẩu mới</label>
                                                <input type="password" name="password_confirmation" class="form-control form-control-sm" placeholder="Xác nhận mật khẩu">
                                            </div>
                                        </div>
                                        <div class="modal-footer bg-light">
                                            <button type="button" class="btn btn-secondary btn-sm" data-bs-dismiss="modal">Hủy</button>
                                            <button type="submit" class="btn btn-primary btn-sm"><i class="fa-solid fa-floppy-disk me-1"></i>Lưu thay đổi</button>
                                        </div>
                                    </form>
                                </div>
                            </div>
                        </div>
                        @empty
                        <tr>
                            <td colspan="7" class="text-center text-muted py-4">Chưa có người dùng nào.</td>
                        </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>

        <!-- MODAL CREATE USER -->
        <div class="modal fade" id="createUserModal" tabindex="-1" aria-hidden="true">
            <div class="modal-dialog">
                <div class="modal-content border-0 shadow">
                    <div class="modal-header bg-primary text-white">
                        <h5 class="modal-title fs-6 fw-bold">
                            <i class="fa-solid fa-user-plus me-1"></i> Thêm tài khoản người dùng mới
                        </h5>
                        <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal"></button>
                    </div>
                    <form action="{{ route('admin.users.store') }}" method="POST">
                        @csrf
                        <div class="modal-body text-start">
                            <div class="mb-3">
                                <label class="form-label fw-semibold small">Họ và tên <span class="text-danger">*</span></label>
                                <input type="text" name="name" class="form-control form-control-sm" placeholder="Ví dụ: Nguyễn Văn A" required>
                            </div>
                            <div class="mb-3">
                                <label class="form-label fw-semibold small">Email đăng nhập <span class="text-danger">*</span></label>
                                <input type="email" name="email" class="form-control form-control-sm" placeholder="user@gmail.com" required>
                            </div>
                            <div class="mb-3">
                                <label class="form-label fw-semibold small">Vai trò <span class="text-danger">*</span></label>
                                <select name="role" class="form-select form-select-sm" required>
                                    <option value="customer" selected>Khách hàng (customer)</option>
                                    <option value="editor">Biên tập viên (editor)</option>
                                    <option value="manager">Quản lý kho (manager)</option>
                                    <option value="admin">Quản trị viên (admin)</option>
                                </select>
                            </div>
                            <div class="mb-3">
                                <label class="form-label fw-semibold small">Mật khẩu <span class="text-danger">*</span></label>
                                <input type="password" name="password" class="form-control form-control-sm" placeholder="Tối thiểu 8 ký tự" required>
                            </div>
                            <div class="mb-3">
                                <label class="form-label fw-semibold small">Xác nhận mật khẩu <span class="text-danger">*</span></label>
                                <input type="password" name="password_confirmation" class="form-control form-control-sm" placeholder="Nhập lại mật khẩu trên" required>
                            </div>
                        </div>
                        <div class="modal-footer bg-light">
                            <button type="button" class="btn btn-secondary btn-sm" data-bs-dismiss="modal">Hủy</button>
                            <button type="submit" class="btn btn-primary btn-sm"><i class="fa-solid fa-check me-1"></i>Tạo tài khoản</button>
                        </div>
                    </form>
                </div>
            </div>
        </div>

        <!-- PAYMENTS SECTION -->
        <div class="card-panel" data-dashboard-section="payments">
            <div class="card-panel-title"><i class="fa-solid fa-money-check-dollar text-success"></i> Giao dịch thanh toán</div>
            <div class="table-responsive">
                <table class="table align-middle">
                    <thead>
                        <tr>
                            <th>Mã GD</th>
                            <th>Đơn hàng</th>
                            <th>Cổng</th>
                            <th>Số tiền</th>
                            <th>Trạng thái</th>
                            <th>Thời gian</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($transactions as $trans)
                        <tr>
                            <td>#{{ $trans->id }}</td>
                            <td>#{{ $trans->order_id }}</td>
                            <td class="text-uppercase">{{ $trans->gateway }}</td>
                            <td>{{ number_format($trans->amount, 0, ',', '.') }}₫</td>
                            <td><span class="badge text-bg-{{ in_array($trans->status, ['paid', 'success'], true) ? 'success' : 'warning' }}">{{ $trans->status }}</span></td>
                            <td>{{ $trans->created_at->format('d/m/Y H:i') }}</td>
                        </tr>
                        @empty
                        <tr>
                            <td colspan="6" class="text-center text-muted">Chưa có giao dịch.</td>
                        </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>
    </div>

    <!-- CHAT POPUP -->
    @auth
    <div id="admin-chat-box" style="position:fixed; right:24px; bottom:24px; z-index:1050;">
        <button id="chat-toggle" class="btn btn-dark shadow" type="button" style="border-radius:999px; padding:12px 18px; font-weight:700;">
            <i class="fa-solid fa-comments me-2"></i> Chat Khách hàng
        </button>
        <div id="chat-popup" class="card shadow-lg" style="display:none; width:360px; position:absolute; right:0; bottom:66px; border-radius:14px; overflow:hidden;">
            <div class="card-header bg-dark text-white d-flex justify-content-between align-items-center">
                <strong>Hỗ trợ trực tuyến</strong>
                <button id="chat-close" type="button" class="btn btn-sm btn-light">X</button>
            </div>
            <div id="user-list" class="border-bottom" style="background:#f8fafc; max-height:180px; overflow-y:auto;">
                <div class="p-2 text-center text-muted"><small>Đang tải danh sách...</small></div>
            </div>
            <div id="chat-messages" style="height:260px; overflow-y:auto; padding:14px; background:#fff;">
                <div class="text-center mt-5 text-muted">Chọn một khách hàng để xem tin nhắn</div>
            </div>
            <div class="card-footer bg-white">
                <div class="input-group">
                    <input type="text" id="chat-input" class="form-control form-control-sm" placeholder="Nhập câu trả lời..." autocomplete="off">
                    <button id="send-btn" class="btn btn-success btn-sm" type="button">Gửi</button>
                </div>
            </div>
        </div>
    </div>
    @endauth

    <!-- BOOTSTRAP 5 BUNDLE JS -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js"></script>
    <script>
        document.addEventListener('DOMContentLoaded', function() {
            // Gán chiều cao cột biểu đồ doanh thu
            document.querySelectorAll('.revenue-bar[data-height]').forEach(function(bar) {
                bar.style.height = bar.getAttribute('data-height') + 'px';
            });

            // Ẩn/hiện tab dashboard đang chọn
            const activeSection = "{{ $section }}";
            document.querySelectorAll('[data-dashboard-section]').forEach(function(element) {
                element.style.display = element.dataset.dashboardSection === activeSection ? '' : 'none';
            });

            // Xử lý khung Chat Admin
            const toggleBtn = document.getElementById('chat-toggle');
            const chatPopup = document.getElementById('chat-popup');
            const closeBtn = document.getElementById('chat-close');
            const chatMessages = document.getElementById('chat-messages');
            const chatInput = document.getElementById('chat-input');
            const sendBtn = document.getElementById('send-btn');
            const userList = document.getElementById('user-list');

            if (!toggleBtn || !chatPopup) return;

            let currentUserId = null;

            const loadUsers = () => {
                fetch('{{ route("admin.chat.users") }}')
                    .then(res => res.json())
                    .then(users => {
                        if (!userList) return;
                        if (!users.length) {
                            userList.innerHTML = '<div class="p-2 text-muted text-center"><small>Chưa có hội thoại</small></div>';
                            return;
                        }

                        let html = '';
                        users.forEach(user => {
                            const active = Number(currentUserId) === Number(user.id) ? 'active' : '';
                            html += `
                            <div class="user-item p-2 border-bottom ${active}" data-user-id="${user.id}" style="cursor:pointer; background:${active ? '#e0f2fe' : '#fff'};">
                                <strong>${user.name}</strong>${user.unread_count ? `<span class="badge bg-danger ms-1">${user.unread_count}</span>` : ''}
                            </div>
                        `;
                        });
                        userList.innerHTML = html;

                        userList.querySelectorAll('.user-item').forEach(item => {
                            item.addEventListener('click', function() {
                                currentUserId = Number(this.dataset.userId);
                                userList.querySelectorAll('.user-item').forEach(el => el.style.background = '#fff');
                                this.style.background = '#e0f2fe';
                                loadMessages();
                            });
                        });
                    })
                    .catch(() => {
                        if (userList) userList.innerHTML = '<div class="p-2 text-muted text-center"><small>Không thể tải danh sách</small></div>';
                    });
            };

            const loadMessages = () => {
                if (!currentUserId) {
                    chatMessages.innerHTML = '<div class="text-center mt-5 text-muted">Chọn một khách hàng để xem tin nhắn</div>';
                    return;
                }

                fetch(`/admin/chat/messages/${currentUserId}`)
                    .then(res => res.json())
                    .then(messages => {
                        let html = '';
                        messages.forEach(msg => {
                            const isMine = Number(msg.sender_id) === Number('{{ Auth::id() }}');
                            const senderName = isMine ? 'Bạn' : (msg.sender?.name || 'Khách hàng');
                            const color = isMine ? '#2563eb' : '#111827';
                            html += `<div class="mb-2" style="color:${color};"><strong>${senderName}:</strong> ${msg.content}</div>`;
                        });
                        chatMessages.innerHTML = html || '<div class="text-center mt-5 text-muted">Chưa có tin nhắn nào</div>';
                        chatMessages.scrollTop = chatMessages.scrollHeight;
                    })
                    .catch(() => {
                        chatMessages.innerHTML = '<div class="text-center mt-5 text-danger">Không thể tải tin nhắn</div>';
                    });
            };

            const sendMessage = () => {
                const message = chatInput.value.trim();
                if (!message || !currentUserId) return;

                fetch('{{ route("admin.chat.send") }}', {
                        method: 'POST',
                        headers: {
                            'Content-Type': 'application/json',
                            'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]')?.getAttribute('content') || '',
                            'Accept': 'application/json'
                        },
                        body: JSON.stringify({
                            user_id: currentUserId,
                            message
                        })
                    })
                    .then(res => res.json())
                    .then(() => {
                        chatInput.value = '';
                        loadMessages();
                    });
            };

            toggleBtn.addEventListener('click', () => {
                chatPopup.style.display = chatPopup.style.display === 'none' ? 'block' : 'none';
                if (chatPopup.style.display === 'block') loadUsers();
            });

            closeBtn.addEventListener('click', () => {
                chatPopup.style.display = 'none';
            });

            sendBtn.addEventListener('click', sendMessage);
            chatInput.addEventListener('keydown', (e) => {
                if (e.key === 'Enter') sendMessage();
            });
        });
    </script>
</body>

</html>