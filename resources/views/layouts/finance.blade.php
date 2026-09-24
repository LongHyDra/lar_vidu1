<!DOCTYPE html>
<html lang="vi">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>@yield('title', 'Tài chính') | Shop Admin</title>

    <!-- Google Font Inter -->
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700&display=swap" rel="stylesheet">
    <!-- FontAwesome 6 Icons -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.0/css/all.min.css">

    <style>
        * { box-sizing: border-box; margin: 0; padding: 0; }
        body { font-family: 'Inter', sans-serif; background-color: #f8fafc; color: #1e293b; min-height: 100vh; display: flex; }
        a { text-decoration: none; color: inherit; }

        /* SIDEBAR (THEO ĐÚNG ẢNH MẪU) */
        .finance-sidebar {
            width: 230px; background-color: #1e293b; color: #f8fafc; display: flex; flex-direction: column;
            position: fixed; top: 0; left: 0; height: 100vh; z-index: 100;
        }
        .sidebar-header {
            padding: 18px 16px; border-bottom: 1px solid rgba(255, 255, 255, 0.08);
            display: flex; align-items: center; justify-content: space-between; font-weight: 700; font-size: 0.95rem; letter-spacing: 0.5px;
        }
        .sidebar-profile {
            padding: 24px 16px; display: flex; flex-direction: column; align-items: center; text-align: center;
            border-bottom: 1px solid rgba(255, 255, 255, 0.08);
        }
        .profile-avatar {
            width: 54px; height: 54px; border-radius: 50%; background: #475569; color: #fff;
            display: flex; align-items: center; justify-content: center; font-size: 1.3rem; font-weight: 700; margin-bottom: 10px;
        }
        .profile-name { font-size: 0.9rem; font-weight: 600; color: #fff; }
        .profile-role { font-size: 0.72rem; color: #94a3b8; text-transform: uppercase; margin-top: 2px; }

        .sidebar-menu { flex: 1; padding: 12px 0; overflow-y: auto; }
        .menu-item {
            display: flex; align-items: center; gap: 12px; padding: 10px 18px; color: #94a3b8;
            font-size: 0.85rem; font-weight: 500; transition: all 0.15s;
        }
        .menu-item:hover { background: rgba(255, 255, 255, 0.05); color: #fff; }
        .menu-item.active { background: #0f172a; color: #fff; font-weight: 600; border-left: 3px solid #3b82f6; }
        .menu-item i { width: 18px; text-align: center; font-size: 0.95rem; }

        .sidebar-footer { padding: 14px 16px; border-top: 1px solid rgba(255, 255, 255, 0.08); }
        .btn-logout {
            width: 100%; padding: 8px; background: rgba(255, 255, 255, 0.05); border: 1px solid rgba(255, 255, 255, 0.1);
            border-radius: 6px; color: #cbd5e1; font-size: 0.8rem; font-weight: 500; cursor: pointer; transition: background 0.15s;
        }
        .btn-logout:hover { background: #ef4444; border-color: #ef4444; color: #fff; }

        /* MAIN CONTENT */
        .finance-main { margin-left: 230px; flex: 1; padding: 20px 32px 60px; min-width: 0; }

        /* TOPBAR & BREADCRUMBS */
        .finance-topbar {
            display: flex; justify-content: space-between; align-items: center; margin-bottom: 20px; font-size: 0.82rem; color: #64748b;
        }
        .user-badge {
            background: #e2e8f0; color: #334155; padding: 3px 8px; border-radius: 4px; font-size: 0.75rem; font-weight: 600; margin-left: 6px;
        }

        .page-header { margin-bottom: 18px; }
        .page-header h1 { font-size: 1.45rem; font-weight: 700; color: #0f172a; margin-bottom: 4px; }
        .page-header p { font-size: 0.85rem; color: #64748b; }

        /* TABS CHUYỂN ĐỔI ("Thống kê chỉ số" / "Giao dịch thanh toán") */
        .finance-tabs-card {
            background: #fff; border: 1px solid #e2e8f0; border-radius: 8px; padding: 10px 14px;
            margin-bottom: 18px; display: flex; gap: 8px; box-shadow: 0 1px 2px rgba(0,0,0,0.02);
        }
        .finance-tab-btn {
            padding: 6px 14px; border-radius: 5px; font-size: 0.82rem; font-weight: 600;
            color: #2563eb; background: transparent; transition: all 0.15s;
        }
        .finance-tab-btn.active { background: #334155; color: #ffffff; }

        /* STYLES CHO BỘ LỌC VÀ NỘI DUNG */
        .finance-card {
            background: #ffffff; border: 1px solid #e2e8f0; border-radius: 8px; margin-bottom: 20px;
            box-shadow: 0 1px 3px rgba(0,0,0,0.02); overflow: hidden;
        }
        .finance-card-head {
            padding: 13px 18px; font-size: 0.92rem; font-weight: 700; color: #1e293b; border-bottom: 1px solid #f1f5f9;
        }
        .finance-filter { padding: 16px 18px; }
        .finance-filter-grid { display: grid; grid-template-columns: repeat(3, 1fr); gap: 12px; margin-bottom: 12px; }
        .finance-filter-grid.second { grid-template-columns: repeat(4, 1fr); }
        .finance-field { display: flex; flex-direction: column; gap: 5px; }
        .finance-field label { font-size: 0.78rem; font-weight: 600; color: #475569; }
        .finance-field input, .finance-field select {
            height: 35px; padding: 6px 10px; border: 1px solid #cbd5e1; border-radius: 6px;
            font-size: 0.82rem; outline: none; background: #fff; color: #1e293b; width: 100%;
        }
        .finance-field input:focus, .finance-field select:focus {
            border-color: #2563eb; box-shadow: 0 0 0 2px rgba(37,99,235,0.1);
        }
        .finance-actions { display: flex; gap: 8px; margin-top: 14px; align-items: center; }
        .finance-button {
            display: inline-flex; align-items: center; justify-content: center; padding: 7px 15px; border-radius: 6px;
            font-size: 0.82rem; font-weight: 600; border: none; cursor: pointer; text-decoration: none;
            background: #2563eb; color: #fff; transition: background 0.15s;
        }
        .finance-button:hover { background: #1d4ed8; }
        .finance-button.secondary { background: #fff; border: 1px solid #cbd5e1; color: #334155; }
        .finance-button.secondary:hover { background: #f8fafc; border-color: #94a3b8; }

        .finance-note { font-size: 0.8rem; color: #64748b; margin: 12px 0 16px; line-height: 1.5; }

        /* KPI STATS GRID */
        .finance-kpis { display: grid; grid-template-columns: repeat(4, 1fr); gap: 14px; margin-bottom: 22px; }
        .finance-kpi {
            background: #fff; border: 1px solid #e2e8f0; border-radius: 8px; padding: 16px 18px;
            display: flex; flex-direction: column; gap: 4px; box-shadow: 0 1px 2px rgba(0,0,0,0.02);
        }
        .finance-kpi label { font-size: 0.78rem; color: #64748b; font-weight: 500; }
        .finance-kpi strong { font-size: 1.15rem; font-weight: 700; color: #0f172a; margin: 3px 0; }
        .finance-kpi small { font-size: 0.72rem; color: #94a3b8; }
        .finance-kpi.pending strong { color: #d97706; }
        .finance-kpi.paid strong { color: #16a34a; }
        .finance-kpi.failed strong { color: #dc2626; }
        .finance-kpi.refund strong { color: #2563eb; }

        /* TABLE */
        .finance-table { width: 100%; border-collapse: collapse; font-size: 0.83rem; }
        .finance-table th {
            background: #f8fafc; padding: 10px 16px; text-align: left; font-weight: 600; color: #475569;
            border-bottom: 1px solid #e2e8f0; font-size: 0.8rem;
        }
        .finance-table td { padding: 11px 16px; border-bottom: 1px solid #f1f5f9; color: #334155; vertical-align: middle; }
        .finance-table tr:last-child td { border-bottom: none; }
        .finance-table .right { text-align: right; }
        .finance-status { display: inline-block; padding: 3px 9px; border-radius: 999px; font-size: 0.74rem; font-weight: 600; }
        .finance-status.pending { background: #fef3c7; color: #b45309; }
        .finance-status.paid { background: #dcfce7; color: #15803d; }
        .finance-status.failed { background: #fee2e2; color: #b91c1c; }
        .finance-pagination { padding: 12px 18px; border-top: 1px solid #f1f5f9; }
        .finance-empty { padding: 30px; text-align: center; color: #94a3b8; font-size: 0.85rem; }
        .d-none { display: none !important; }

        /* NÚT CHAT KHÁCH HÀNG FLOATING GÓC DƯỚI */
        .chat-floating-btn {
            position: fixed; right: 24px; bottom: 20px; z-index: 1000;
            background: #0f172a; color: #fff; border: none; border-radius: 999px;
            padding: 9px 16px; font-size: 0.8rem; font-weight: 600; box-shadow: 0 4px 14px rgba(0,0,0,0.25);
            display: inline-flex; align-items: center; gap: 7px; cursor: pointer;
        }
        .chat-floating-btn span { width: 7px; height: 7px; background: #22c55e; border-radius: 50%; display: inline-block; }
    </style>
</head>

<body>
    <!-- 1. SIDEBAR -->
    <aside class="finance-sidebar">
        <div class="sidebar-header">
            <span><i class="fa-solid fa-shapes me-2"></i>SHOP ADMIN</span>
            <i class="fa-solid fa-bars text-secondary" style="font-size:0.85rem;"></i>
        </div>

        <div class="sidebar-profile">
            <div class="profile-avatar">S</div>
            <div class="profile-name">Shop Admin</div>
            <div class="profile-role">Quản lý bán hàng</div>
        </div>

        <nav class="sidebar-menu">
            <a href="{{ route('admin.dashboard', ['section' => 'overview']) }}" class="menu-item {{ request()->routeIs('admin.dashboard') && request('section') === 'overview' ? 'active' : '' }}">
                <i class="fa-solid fa-chart-pie"></i> Dashboard
            </a>
            <a href="{{ route('products.index') }}" class="menu-item {{ request()->is('products*') ? 'active' : '' }}">
                <i class="fa-solid fa-box"></i> Sản phẩm
            </a>
            <a href="{{ route('categories.index') }}" class="menu-item {{ request()->is('categories*') ? 'active' : '' }}">
                <i class="fa-solid fa-tags"></i> Danh mục
            </a>
            <a href="{{ route('admin.users.index') }}" class="menu-item {{ request()->routeIs('admin.users.*') ? 'active' : '' }}">
                <i class="fa-solid fa-users"></i> Người dùng
            </a>
            <a href="{{ route('admin.dashboard', ['section' => 'orders']) }}" class="menu-item {{ request()->routeIs('admin.dashboard') && request('section') === 'orders' ? 'active' : '' }}">
                <i class="fa-solid fa-receipt"></i> Đơn hàng
            </a>
            <a href="{{ route('admin.finance.index') }}" class="menu-item {{ request()->routeIs('admin.finance.index') ? 'active' : '' }}">
                <i class="fa-solid fa-chart-line"></i> Thống kê tài chính
            </a>
            <a href="{{ route('admin.finance.transactions') }}" class="menu-item {{ request()->routeIs('admin.finance.transactions') ? 'active' : '' }}">
                <i class="fa-solid fa-money-check-dollar"></i> Giao dịch thanh toán
            </a>
            <a href="{{ route('admin.reports.revenue.print') }}" target="_blank" class="menu-item">
                <i class="fa-solid fa-file-invoice-dollar"></i> Báo cáo
            </a>
        </nav>

        <div class="sidebar-footer">
            <form action="{{ route('logout') }}" method="POST">
                @csrf
                <button type="submit" class="btn-logout"><i class="fa-solid fa-sign-out me-1"></i> Đăng xuất</button>
            </form>
        </div>
    </aside>

    <!-- 2. MAIN CONTENT -->
    <main class="finance-main">
        <!-- TOPBAR -->
        <div class="finance-topbar">
            <div>Quản trị / <strong>@yield('crumb')</strong></div>
            <div>Shop Admin <span class="user-badge">Quản trị viên</span></div>
        </div>

        <!-- PAGE TITLE -->
        <div class="page-header">
            <h1>@yield('page_title')</h1>
            <p>@yield('page_subtitle')</p>
        </div>

        <!-- 2 TAB CHUYỂN ĐỔI GIỮA THỐNG KÊ & GIAO DỊCH -->
        <div class="finance-tabs-card">
            <a href="{{ route('admin.finance.index') }}" class="finance-tab-btn {{ request()->routeIs('admin.finance.index') ? 'active' : '' }}">
                Thống kê chỉ số
            </a>
            <a href="{{ route('admin.finance.transactions') }}" class="finance-tab-btn {{ request()->routeIs('admin.finance.transactions') ? 'active' : '' }}">
                Giao dịch thanh toán
            </a>
        </div>

        <!-- NỘI DUNG TỪNG TRANG -->
        @yield('content')
    </main>

    <!-- FLOATING CHAT BUTTON -->
    <a href="{{ route('admin.dashboard') }}" class="chat-floating-btn">
        <span></span> Chat Khách hàng
    </a>
</body>

</html>