<!DOCTYPE html>
<html lang="vi">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>@yield('title', 'Quản Trị Hệ Thống') | PHỤ KIỆN XE MÁY 247</title>

    <!-- Google Font Inter -->
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700;800&display=swap" rel="stylesheet">
    <!-- Bootstrap 5 CSS -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
    <!-- FontAwesome 6 -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.0/css/all.min.css">

    <style>
        :root {
            --sidebar-width: 255px;
            --primary: #2563eb;
            --primary-hover: #1d4ed8;
            --bg-canvas: #f8fafc;
            --card-bg: #ffffff;
            --text-main: #0f172a;
            --text-sub: #64748b;
            --border-color: #e2e8f0;
        }

        * {
            box-sizing: border-box;
        }

        body {
            font-family: 'Inter', sans-serif;
            background-color: var(--bg-canvas) !important;
            color: var(--text-main) !important;
            min-height: 100vh;
            margin: 0;
        }

        /* SIDEBAR STYLING */
        .admin-sidebar {
            width: var(--sidebar-width);
            height: 100vh;
            position: fixed;
            top: 0;
            left: 0;
            background: #0f172a;
            /* Slate 900 */
            color: #f8fafc;
            display: flex;
            flex-direction: column;
            z-index: 1050;
            box-shadow: 4px 0 20px rgba(0, 0, 0, 0.1);
        }

        .sidebar-brand {
            padding: 20px 18px;
            border-bottom: 1px solid rgba(255, 255, 255, 0.08);
            display: flex;
            align-items: center;
            gap: 12px;
        }

        .brand-icon {
            width: 38px;
            height: 38px;
            border-radius: 10px;
            background: linear-gradient(135deg, #3b82f6, #1d4ed8);
            display: flex;
            align-items: center;
            justify-content: center;
            color: #ffffff;
            font-size: 1.1rem;
            box-shadow: 0 4px 10px rgba(37, 99, 235, 0.35);
        }

        .brand-title {
            font-size: 0.92rem;
            font-weight: 800;
            letter-spacing: -0.2px;
            color: #ffffff;
            line-height: 1.2;
        }

        .brand-badge {
            background: #f59e0b;
            color: #000;
            font-size: 0.62rem;
            font-weight: 900;
            padding: 1px 5px;
            border-radius: 4px;
        }

        .sidebar-scroll {
            flex-grow: 1;
            overflow-y: auto;
            padding: 14px 10px;
        }

        .nav-category {
            font-size: 0.65rem;
            font-weight: 700;
            text-transform: uppercase;
            letter-spacing: 1px;
            color: #64748b;
            padding: 12px 10px 6px;
        }

        .nav-link-custom {
            display: flex;
            align-items: center;
            gap: 10px;
            padding: 9px 12px;
            border-radius: 8px;
            color: #94a3b8;
            text-decoration: none;
            font-weight: 500;
            font-size: 0.86rem;
            margin-bottom: 3px;
            transition: all 0.2s ease;
        }

        .nav-link-custom:hover {
            background: rgba(255, 255, 255, 0.06);
            color: #ffffff;
        }

        .nav-link-custom.active {
            background: #2563eb;
            color: #ffffff;
            font-weight: 600;
        }

        .nav-link-custom .nav-icon {
            width: 24px;
            display: flex;
            justify-content: center;
            font-size: 0.95rem;
        }

        /* SIDEBAR USER FOOTER */
        .sidebar-user-footer {
            padding: 12px 16px;
            border-top: 1px solid rgba(255, 255, 255, 0.08);
            background: rgba(0, 0, 0, 0.2);
            display: flex;
            align-items: center;
            justify-content: space-between;
        }

        .user-avatar {
            width: 32px;
            height: 32px;
            border-radius: 8px;
            background: #3b82f6;
            color: #fff;
            display: flex;
            align-items: center;
            justify-content: center;
            font-weight: 700;
            font-size: 0.8rem;
        }

        /* MAIN CONTENT LAYOUT */
        .admin-main {
            margin-left: var(--sidebar-width);
            min-height: 100vh;
            display: flex;
            flex-direction: column;
        }

        /* TOPBAR */
        .admin-topbar {
            height: 68px;
            background: #ffffff !important;
            border-bottom: 1px solid var(--border-color);
            display: flex;
            align-items: center;
            justify-content: space-between;
            padding: 0 32px;
            position: sticky;
            top: 0;
            z-index: 1000;
        }

        .page-header-title {
            font-size: 1.15rem;
            font-weight: 700;
            color: var(--text-main);
            margin: 0;
        }

        .page-header-breadcrumb {
            font-size: 0.76rem;
            color: var(--text-sub);
            margin-top: 2px;
        }

        .content-body {
            padding: 28px 32px;
            flex-grow: 1;
        }

        /* STANDARDIZED FORM CONTROLS (Chống Dark Mode của browser) */
        .card-custom {
            background-color: #ffffff !important;
            border: 1px solid var(--border-color) !important;
            border-radius: 12px;
            box-shadow: 0 1px 3px rgba(0, 0, 0, 0.03);
            color: var(--text-main) !important;
        }

        .form-control,
        .form-select {
            background-color: #ffffff !important;
            color: #0f172a !important;
            border: 1px solid #cbd5e1 !important;
            border-radius: 8px;
            padding: 9px 13px;
            font-size: 0.88rem;
        }

        .form-control:focus,
        .form-select:focus {
            border-color: #3b82f6 !important;
            box-shadow: 0 0 0 3px rgba(59, 130, 246, 0.15) !important;
        }
    </style>

    @stack('styles')
</head>

<body>

    <!-- 1. SIDEBAR CỐ ĐỊNH -->
    <aside class="admin-sidebar">
        <div class="sidebar-brand">
            <div class="brand-icon"><i class="fa-solid fa-motorcycle"></i></div>
            <div>
                <div class="brand-title">PHỤ KIỆN XE MÁY <span class="brand-badge">247</span></div>
                <div style="font-size: 0.68rem; color: #94a3b8;">Hệ thống quản trị</div>
            </div>
        </div>

        <div class="sidebar-scroll">
            <div class="nav-category">Bán Hàng & Kho</div>
            <a href="{{ url('/admin/dashboard?section=overview') }}" class="nav-link-custom {{ request('section', 'overview') === 'overview' && request()->is('admin/dashboard') ? 'active' : '' }}">
                <span class="nav-icon"><i class="fa-solid fa-chart-pie"></i></span> Tổng quan
            </a>
            <a href="{{ url('/admin/dashboard?section=orders') }}" class="nav-link-custom {{ request('section') === 'orders' ? 'active' : '' }}">
                <span class="nav-icon"><i class="fa-solid fa-receipt"></i></span> Đơn hàng
            </a>
            <a href="{{ url('/admin/dashboard?section=financial') }}" class="nav-link-custom {{ request('section') === 'financial' ? 'active' : '' }}">
                <span class="nav-icon"><i class="fa-solid fa-chart-line"></i></span> Thống kê tài chính
            </a>
            <a href="{{ url('/admin/dashboard?section=inventory') }}" class="nav-link-custom {{ request('section') === 'inventory' ? 'active' : '' }}">
                <span class="nav-icon"><i class="fa-solid fa-warehouse"></i></span> Nhật ký tồn kho
            </a>

            <div class="nav-category">Danh Mục & Hàng Hóa</div>
            <a href="{{ route('categories.index') }}" class="nav-link-custom {{ request()->is('categories*') ? 'active' : '' }}">
                <span class="nav-icon"><i class="fa-solid fa-tags"></i></span> Danh mục phụ kiện
            </a>
            <a href="{{ route('products.index') }}" class="nav-link-custom {{ request()->is('products*') ? 'active' : '' }}">
                <span class="nav-icon"><i class="fa-solid fa-box-open"></i></span> Sản phẩm phụ kiện
            </a>

            <div class="nav-category">Hệ Thống</div>
            <a href="{{ url('/admin/dashboard?section=users') }}" class="nav-link-custom {{ request('section') === 'users' ? 'active' : '' }}">
                <span class="nav-icon"><i class="fa-solid fa-users"></i></span> Người dùng
            </a>
            <a href="{{ url('/admin/dashboard?section=payments') }}" class="nav-link-custom {{ request('section') === 'payments' ? 'active' : '' }}">
                <span class="nav-icon"><i class="fa-solid fa-money-check-dollar"></i></span> Giao dịch cổng MoMo
            </a>
            <a href="{{ route('welcome') }}" class="nav-link-custom" target="_blank">
                <span class="nav-icon"><i class="fa-solid fa-arrow-up-right-from-square"></i></span> Xem cửa hàng
            </a>
        </div>

        @auth
        <div class="sidebar-user-footer">
            <div class="d-flex align-items-center gap-2">
                <div class="user-avatar">{{ strtoupper(substr(Auth::user()->name, 0, 1)) }}</div>
                <div style="line-height: 1.2;">
                    <div style="font-size: 0.8rem; font-weight: 700; color: #fff;">{{ Auth::user()->name }}</div>
                    <small style="font-size: 0.68rem; color: #94a3b8;">Quản trị viên</small>
                </div>
            </div>
            <form action="{{ route('logout') }}" method="POST" class="m-0">
                @csrf
                <button type="submit" class="btn btn-sm btn-outline-danger border-0 p-1" title="Đăng xuất">
                    <i class="fa-solid fa-arrow-right-from-bracket"></i>
                </button>
            </form>
        </div>
        @endauth
    </aside>

    <!-- 2. NỘI DUNG CHÍNH -->
    <div class="admin-main">
        <!-- TOPBAR -->
        <header class="admin-topbar">
            <div>
                <h1 class="page-header-title">@yield('page_title', 'Hệ Thống Quản Lý')</h1>
                <div class="page-header-breadcrumb">@yield('page_breadcrumb', 'Bảng điều khiển quản trị')</div>
            </div>
            <div>
                @yield('topbar_actions')
            </div>
        </header>

        <!-- CONTENT BODY -->
        <main class="content-body">
            <!-- Thông báo Toast / Flash Message -->
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
        </main>
    </div>

    <!-- Bootstrap 5 JS -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js"></script>
    @stack('scripts')

</body>

</html>