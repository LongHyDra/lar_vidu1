<!DOCTYPE html>
<html lang="vi">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>@yield('title', 'Quản Trị Hệ Thống') | PHỤ KIỆN XE MÁY 247</title>
    <!-- Bootstrap 5 CSS -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
    <!-- FontAwesome 6 Icons -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.0/css/all.min.css">
    <!-- Google Font Inter -->
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700;800&display=swap" rel="stylesheet">
    <style>
        * { box-sizing: border-box; }
        body { background: #f0f4f8; font-family: 'Inter', sans-serif; min-height: 100vh; margin: 0; }

        /* SIDEBAR MÀU TRẮNG ĐỒNG BỘ */
        .sidebar {
            width: 248px; height: 100vh; position: fixed; top: 0; left: 0;
            background: #ffffff; border-right: 1px solid #e2e8f0; display: flex; flex-direction: column;
            z-index: 100; box-shadow: 6px 0 24px rgba(15, 23, 42, 0.06); overflow-y: auto;
        }
        .sidebar-brand {
            padding: 18px 16px 16px; border-bottom: 1px solid #f1f5f9; display: flex; align-items: center;
            gap: 11px; background: linear-gradient(180deg, #f8fafc 0%, #ffffff 100%);
        }
        .brand-icon-wrap { position: relative; width: 42px; height: 42px; flex-shrink: 0; }
        .brand-icon-glow {
            position: absolute; inset: -2px; background: linear-gradient(135deg, #2563eb, #f59e0b);
            border-radius: 12px; filter: blur(4px); opacity: 0.7;
        }
        .brand-icon-inner {
            position: relative; width: 100%; height: 100%; background: linear-gradient(135deg, #0f172a, #1e293b);
            border: 1px solid rgba(255, 255, 255, 0.2); border-radius: 11px; display: flex; align-items: center;
            justify-content: center; color: #60a5fa; font-size: 1.15rem; box-shadow: 0 4px 10px rgba(15, 23, 42, 0.3);
        }
        .sidebar-brand .brand-text {
            font-size: 0.95rem; font-weight: 900; color: #0f172a; line-height: 1.1; display: flex; align-items: center; gap: 5px;
        }
        .badge-247-sm {
            background: linear-gradient(135deg, #f59e0b, #d97706); color: #fff; font-size: 0.65rem; font-weight: 900; padding: 1px 5px; border-radius: 4px;
        }
        .sidebar-nav { padding: 14px 10px 10px; }
        .nav-label {
            font-size: 0.65rem; font-weight: 700; text-transform: uppercase; letter-spacing: 1px; color: #94a3b8; padding: 14px 10px 7px;
        }
        .nav-item-link {
            display: flex; align-items: center; gap: 10px; min-height: 44px; padding: 7px 10px; border-radius: 9px;
            color: #64748b; text-decoration: none; font-weight: 500; font-size: 0.875rem; transition: all 0.18s; margin-bottom: 3px;
        }
        .nav-item-link:hover { background: #f1f5f9; color: #1e293b; }
        .nav-item-link.active {
            background: #eff6ff; color: #2563eb; font-weight: 600; box-shadow: inset 3px 0 #2563eb;
        }
        .nav-item-link .nav-icon {
            width: 32px; height: 32px; border-radius: 8px; display: flex; align-items: center; justify-content: center;
            font-size: 0.8rem; background: #f1f5f9; transition: all 0.18s;
        }
        .nav-item-link.active .nav-icon { background: #dbeafe; color: #2563eb; }
        .sidebar-footer {
            margin: 8px 10px 14px; padding: 11px; border: 1px solid #e2e8f0; border-radius: 12px; background: #f8fafc;
        }
        .sidebar-user { display: flex; align-items: center; gap: 9px; min-width: 0; }
        .sidebar-avatar {
            width: 32px; height: 32px; border-radius: 9px; display: grid; place-items: center; flex-shrink: 0;
            background: #dbeafe; color: #2563eb; font-weight: 800; font-size: .8rem;
        }
        .sidebar-user-name {
            overflow: hidden; text-overflow: ellipsis; white-space: nowrap; font-size: .76rem; font-weight: 700; color: #1e293b;
        }
        .sidebar-user-role { color: #94a3b8; font-size: .67rem; }
        .sidebar-logout {
            display: inline-flex; align-items: center; justify-content: center; width: 30px; height: 30px;
            border: 0; border-radius: 8px; color: #ef4444; background: #fee2e2;
        }

        /* MAIN CONTENT & PANELS */
        .main-content { margin-left: 248px; padding: 28px 32px; min-height: 100vh; }
        .topbar {
            display: flex; justify-content: space-between; align-items: center; background: #ffffff;
            padding: 14px 22px; border-radius: 14px; box-shadow: 0 2px 8px rgba(0, 0, 0, 0.04); margin-bottom: 24px; border: 1px solid #f1f5f9;
        }
        .card-panel {
            background: #ffffff; border-radius: 16px; border: 1px solid #f1f5f9; box-shadow: 0 2px 12px rgba(0, 0, 0, 0.04);
            padding: 24px; margin-bottom: 24px;
        }
        .card-panel-title {
            font-size: 1rem; font-weight: 700; color: #1e293b; margin-bottom: 16px; display: flex; align-items: center; gap: 8px;
        }

        /* KPI STAT CARDS */
        .stat-card {
            background: #fff; border-radius: 14px; padding: 18px 20px; border: 1px solid #f1f5f9;
            box-shadow: 0 2px 8px rgba(0, 0, 0, 0.04); display: flex; align-items: center; gap: 14px;
        }
        .stat-icon {
            width: 48px; height: 48px; border-radius: 12px; display: flex; align-items: center; justify-content: center;
            font-size: 1.2rem; flex-shrink: 0;
        }
        .stat-icon.blue { background: #eff6ff; color: #2563eb; }
        .stat-icon.green { background: #f0fdf4; color: #16a34a; }
        .stat-icon.orange { background: #fff7ed; color: #ea580c; }
        .stat-icon.purple { background: #faf5ff; color: #9333ea; }
        .stat-icon.red { background: #fef2f2; color: #dc2626; }
        .stat-icon.gray { background: #f1f5f9; color: #64748b; }
        .stat-value { font-size: 1.25rem; font-weight: 800; color: #0f172a; line-height: 1.1; }
        .stat-label { font-size: 0.78rem; color: #64748b; margin-top: 3px; font-weight: 500; }

        /* FORM CONTROLS */
        .form-control, .form-select {
            border: 1px solid #cbd5e1; border-radius: 8px; font-size: 0.85rem; padding: 7px 12px;
        }
        .form-control:focus, .form-select:focus {
            border-color: #2563eb; box-shadow: 0 0 0 3px rgba(37, 99, 235, 0.12);
        }
    </style>
</head>

<body>
    <!-- SIDEBAR MÀU TRẮNG -->
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
            <a href="{{ route('admin.dashboard', ['section' => 'overview']) }}" class="nav-item-link {{ request()->routeIs('admin.dashboard') && request('section', 'overview') === 'overview' ? 'active' : '' }}">
                <div class="nav-icon"><i class="fa-solid fa-chart-line"></i></div> Bảng Điều Khiển
            </a>
            <a href="{{ route('admin.dashboard', ['section' => 'orders']) }}" class="nav-item-link {{ request('section') === 'orders' ? 'active' : '' }}">
                <div class="nav-icon"><i class="fa-solid fa-receipt"></i></div> Đơn hàng
            </a>
            <a href="{{ route('admin.finance.index') }}" class="nav-link-custom nav-item-link {{ request()->routeIs('admin.finance.index') ? 'active' : '' }}">
                <div class="nav-icon"><i class="fa-solid fa-chart-pie"></i></div> Thống kê tài chính
            </a>
            <a href="{{ route('admin.finance.transactions') }}" class="nav-link-custom nav-item-link {{ request()->routeIs('admin.finance.transactions') ? 'active' : '' }}">
                <div class="nav-icon"><i class="fa-solid fa-money-check-dollar"></i></div> Giao dịch thanh toán
            </a>
            <a href="{{ route('admin.dashboard', ['section' => 'inventory']) }}" class="nav-item-link {{ request('section') === 'inventory' ? 'active' : '' }}">
                <div class="nav-icon"><i class="fa-solid fa-warehouse"></i></div> Nhật ký tồn kho
            </a>
            <a href="{{ route('admin.dashboard', ['section' => 'users']) }}" class="nav-item-link {{ request('section') === 'users' ? 'active' : '' }}">
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
        <!-- TOPBAR ĐỒNG BỘ -->
        <div class="topbar">
            <div>
                <div class="fw-bold fs-6">Hệ Thống Quản Lý Phụ Kiện Xe Máy</div>
                <div class="text-muted small"><i class="fa-regular fa-clock me-1"></i>{{ now()->format('d/m/Y') }}</div>
            </div>
            <div class="d-flex align-items-center gap-2">
                <a class="btn btn-sm btn-success fw-bold shadow-sm" href="{{ route('admin.reports.revenue.excel') }}" title="Tải file Excel doanh thu">
                    <i class="fa-solid fa-file-excel me-1"></i> Xuất Excel
                </a>
                <a class="btn btn-sm btn-danger fw-bold shadow-sm" target="_blank" href="{{ route('admin.reports.revenue.print') }}" title="Mở bản in hoặc xuất PDF">
                    <i class="fa-solid fa-file-pdf me-1"></i> Báo cáo PDF
                </a>
            </div>
        </div>

        <!-- FLASH NOTIFICATION -->
        @if(session('success'))
        <div class="alert alert-success alert-dismissible fade show border-0 shadow-sm" role="alert">
            <i class="fa-solid fa-circle-check me-2"></i>{{ session('success') }}
            <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
        </div>
        @endif
        @if(session('error'))
        <div class="alert alert-danger alert-dismissible fade show border-0 shadow-sm" role="alert">
            <i class="fa-solid fa-circle-exclamation me-2"></i>{{ session('error') }}
            <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
        </div>
        @endif

        @yield('content')
    </div>

    <!-- Bootstrap 5 Bundle JS -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js"></script>
    @stack('scripts')
</body>

</html>