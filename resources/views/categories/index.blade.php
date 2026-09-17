<!DOCTYPE html>
<html lang="vi">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Quản Lý Danh Mục | PHỤ KIỆN XE MÁY</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.0/css/all.min.css">
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700;800&display=swap" rel="stylesheet">
    <style>
        * { box-sizing: border-box; }
        body {
            background: #f0f4f8;
            font-family: 'Inter', sans-serif;
            min-height: 100vh;
        }

        /* ===== SIDEBAR ===== */
        .sidebar {
            width: 260px;
            height: 100vh;
            position: fixed;
            top: 0; left: 0;
            background: #ffffff;
            border-right: 1px solid #e2e8f0;
            display: flex;
            flex-direction: column;
            z-index: 100;
            box-shadow: 2px 0 12px rgba(0,0,0,0.06);
        }
        .sidebar-brand {
            padding: 18px 18px 16px; border-bottom: 1px solid #f1f5f9;
            display: flex; align-items: center; gap: 11px;
            background: linear-gradient(180deg, #f8fafc 0%, #ffffff 100%);
        }
        .brand-icon-wrap { position: relative; width: 42px; height: 42px; flex-shrink: 0; }
        .brand-icon-glow { position: absolute; inset: -2px; background: linear-gradient(135deg, #2563eb, #f59e0b); border-radius: 12px; filter: blur(4px); opacity: 0.7; }
        .brand-icon-inner { position: relative; width: 100%; height: 100%; background: linear-gradient(135deg, #0f172a, #1e293b); border: 1px solid rgba(255,255,255,0.2); border-radius: 11px; display: flex; align-items: center; justify-content: center; color: #60a5fa; font-size: 1.15rem; box-shadow: 0 4px 10px rgba(15,23,42,0.3); }
        .sidebar-brand .brand-text { font-size: 0.95rem; font-weight: 900; color: #0f172a; line-height: 1.1; display: flex; align-items: center; gap: 5px; }
        .sidebar-brand .brand-sub  { font-size: 0.7rem; color: #64748b; font-weight: 600; margin-top: 2px; }
        .badge-247-sm { background: linear-gradient(135deg, #f59e0b, #d97706); color: #fff; font-size: 0.65rem; font-weight: 900; padding: 1px 5px; border-radius: 4px; }

        .sidebar-nav { padding: 12px 10px; flex: 1; }
        .nav-label {
            font-size: 0.65rem;
            font-weight: 700;
            text-transform: uppercase;
            letter-spacing: 1px;
            color: #94a3b8;
            padding: 10px 10px 6px;
        }
        .nav-item-link {
            display: flex;
            align-items: center;
            gap: 10px;
            padding: 10px 14px;
            border-radius: 9px;
            color: #64748b;
            text-decoration: none;
            font-weight: 500;
            font-size: 0.875rem;
            transition: all 0.18s;
            margin-bottom: 2px;
        }
        .nav-item-link:hover { background: #f1f5f9; color: #1e293b; }
        .nav-item-link.active {
            background: #eff6ff;
            color: #2563eb;
            font-weight: 600;
        }
        .nav-item-link .nav-icon {
            width: 32px; height: 32px;
            border-radius: 8px;
            display: flex; align-items: center; justify-content: center;
            font-size: 0.8rem;
            background: #f1f5f9;
            transition: all 0.18s;
        }
        .nav-item-link.active .nav-icon { background: #dbeafe; color: #2563eb; }
        .nav-item-link:hover .nav-icon { background: #e2e8f0; color: #1e293b; }

        /* ===== MAIN CONTENT ===== */
        .main-content {
            margin-left: 260px;
            padding: 28px 32px;
            min-height: 100vh;
        }

        /* ===== TOP BAR ===== */
        .topbar {
            display: flex;
            justify-content: space-between;
            align-items: center;
            background: #ffffff;
            padding: 14px 22px;
            border-radius: 14px;
            box-shadow: 0 2px 8px rgba(0,0,0,0.04);
            margin-bottom: 24px;
            border: 1px solid #f1f5f9;
        }
        .topbar-title {
            font-size: 1rem;
            font-weight: 700;
            color: #1e293b;
        }
        .topbar-breadcrumb {
            font-size: 0.8rem;
            color: #94a3b8;
            margin-top: 1px;
        }

        /* ===== PAGE HEADER ===== */
        .page-header {
            display: flex;
            justify-content: space-between;
            align-items: flex-start;
            margin-bottom: 22px;
        }
        .page-header-left h1 {
            font-size: 1.5rem;
            font-weight: 800;
            color: #0f172a;
            margin-bottom: 4px;
        }
        .page-header-left p {
            font-size: 0.85rem;
            color: #64748b;
            margin: 0;
        }
        .btn-add-main {
            display: inline-flex;
            align-items: center;
            gap: 8px;
            background: linear-gradient(135deg, #3b82f6, #2563eb);
            color: #fff;
            padding: 10px 20px;
            border-radius: 10px;
            font-weight: 600;
            font-size: 0.875rem;
            text-decoration: none;
            border: none;
            cursor: pointer;
            transition: all 0.2s;
            box-shadow: 0 4px 12px rgba(37,99,235,0.25);
        }
        .btn-add-main:hover {
            background: linear-gradient(135deg, #2563eb, #1d4ed8);
            color: #fff;
            transform: translateY(-1px);
            box-shadow: 0 6px 18px rgba(37,99,235,0.35);
        }

        /* ===== STATS CARDS ===== */
        .stats-row { margin-bottom: 22px; }
        .stat-card {
            background: #fff;
            border-radius: 14px;
            padding: 18px 20px;
            border: 1px solid #f1f5f9;
            box-shadow: 0 2px 8px rgba(0,0,0,0.04);
            display: flex;
            align-items: center;
            gap: 14px;
            transition: box-shadow 0.2s;
        }
        .stat-card:hover { box-shadow: 0 4px 16px rgba(0,0,0,0.08); }
        .stat-icon {
            width: 48px; height: 48px;
            border-radius: 12px;
            display: flex; align-items: center; justify-content: center;
            font-size: 1.1rem;
            flex-shrink: 0;
        }
        .stat-icon.blue { background: #eff6ff; color: #2563eb; }
        .stat-icon.green { background: #f0fdf4; color: #16a34a; }
        .stat-icon.orange { background: #fff7ed; color: #ea580c; }
        .stat-icon.purple { background: #faf5ff; color: #9333ea; }
        .stat-value { font-size: 1.4rem; font-weight: 800; color: #0f172a; line-height: 1; }
        .stat-label { font-size: 0.78rem; color: #64748b; margin-top: 4px; }

        /* Quick Actions Card */
        .quick-actions-card {
            background: #ffffff;
            border-radius: 14px;
            padding: 16px 20px;
            border: 1px solid #f1f5f9;
            box-shadow: 0 2px 8px rgba(0,0,0,0.04);
            margin-bottom: 22px;
        }
        .quick-actions-title {
            font-size: 0.88rem;
            font-weight: 700;
            color: #1e293b;
            margin-bottom: 12px;
            display: flex;
            align-items: center;
            gap: 8px;
        }
        .quick-btn {
            display: inline-flex;
            align-items: center;
            gap: 7px;
            padding: 8px 16px;
            border-radius: 8px;
            font-size: 0.82rem;
            font-weight: 600;
            text-decoration: none;
            transition: all 0.18s;
            border: 1px solid transparent;
        }
        .quick-btn-primary { background: #eff6ff; color: #2563eb; border-color: #bfdbfe; }
        .quick-btn-primary:hover { background: #dbeafe; color: #1d4ed8; }
        .quick-btn-success { background: #f0fdf4; color: #16a34a; border-color: #bbf7d0; }
        .quick-btn-success:hover { background: #dcfce7; color: #15803d; }
        .quick-btn-secondary { background: #f8fafc; color: #475569; border-color: #e2e8f0; }
        .quick-btn-secondary:hover { background: #f1f5f9; color: #1e293b; }

        /* ===== TABLE CARD ===== */
        .table-card {
            background: #ffffff;
            border-radius: 16px;
            box-shadow: 0 2px 12px rgba(0,0,0,0.05);
            border: 1px solid #f1f5f9;
            overflow: hidden;
        }
        .table-card-header {
            padding: 18px 24px;
            border-bottom: 1px solid #f1f5f9;
            display: flex;
            align-items: center;
            justify-content: space-between;
        }
        .table-card-title {
            font-size: 0.95rem;
            font-weight: 700;
            color: #1e293b;
            display: flex;
            align-items: center;
            gap: 8px;
        }
        .table-search {
            display: flex;
            align-items: center;
            gap: 8px;
            background: #f8fafc;
            border: 1px solid #e2e8f0;
            border-radius: 8px;
            padding: 7px 14px;
            font-size: 0.85rem;
            color: #64748b;
            width: 220px;
        }
        .table-search input {
            border: none;
            background: transparent;
            outline: none;
            font-size: 0.85rem;
            color: #334155;
            width: 100%;
            font-family: 'Inter', sans-serif;
        }
        table.cat-table { width: 100%; border-collapse: collapse; }
        table.cat-table thead tr {
            background: #f8fafc;
            border-bottom: 1px solid #e9eef5;
        }
        table.cat-table thead th {
            padding: 12px 20px;
            font-size: 0.72rem;
            font-weight: 700;
            color: #64748b;
            text-transform: uppercase;
            letter-spacing: 0.6px;
        }
        table.cat-table tbody tr {
            border-bottom: 1px solid #f8fafc;
            transition: background 0.15s;
        }
        table.cat-table tbody tr:last-child { border-bottom: none; }
        table.cat-table tbody tr:hover { background: #f8fafc; }
        table.cat-table td { padding: 15px 20px; vertical-align: middle; }

        .id-badge {
            display: inline-flex;
            align-items: center;
            justify-content: center;
            width: 36px; height: 36px;
            border-radius: 8px;
            background: #f1f5f9;
            color: #475569;
            font-size: 0.78rem;
            font-weight: 700;
        }
        .cat-name {
            font-weight: 600;
            color: #1e293b;
            font-size: 0.92rem;
        }
        .cat-desc {
            color: #64748b;
            font-size: 0.82rem;
            line-height: 1.5;
        }
        .status-badge {
            display: inline-flex;
            align-items: center;
            gap: 5px;
            padding: 4px 11px;
            border-radius: 20px;
            font-size: 0.75rem;
            font-weight: 600;
            background: #dcfce7;
            color: #15803d;
        }
        .status-dot {
            width: 6px; height: 6px;
            border-radius: 50%;
            background: #22c55e;
        }

        .btn-edit-tbl {
            display: inline-flex; align-items: center; gap: 5px;
            background: #fef9c3; color: #a16207;
            border: 1px solid #fef08a;
            border-radius: 7px;
            font-size: 0.8rem; font-weight: 600;
            padding: 6px 12px;
            text-decoration: none;
            transition: all 0.15s;
        }
        .btn-edit-tbl:hover { background: #fef08a; color: #854d0e; }
        .btn-del-tbl {
            display: inline-flex; align-items: center; gap: 5px;
            background: #fff1f2; color: #e11d48;
            border: 1px solid #fecdd3;
            border-radius: 7px;
            font-size: 0.8rem; font-weight: 600;
            padding: 6px 12px;
            cursor: pointer;
            transition: all 0.15s;
        }
        .btn-del-tbl:hover { background: #fecdd3; color: #be123c; }

        /* Empty state */
        .empty-state {
            text-align: center;
            padding: 60px 20px;
        }
        .empty-state-icon {
            width: 70px; height: 70px;
            background: #f1f5f9;
            border-radius: 50%;
            display: inline-flex; align-items: center; justify-content: center;
            font-size: 1.8rem; color: #94a3b8;
            margin-bottom: 16px;
        }
        .empty-state h6 { font-weight: 700; color: #334155; margin-bottom: 6px; }
        .empty-state p { font-size: 0.85rem; color: #94a3b8; margin: 0; }

        /* Alert */
        .alert-success-custom {
            background: #f0fdf4;
            border: 1px solid #bbf7d0;
            color: #15803d;
            border-radius: 10px;
            padding: 12px 18px;
            display: flex;
            align-items: center;
            gap: 10px;
            font-size: 0.875rem;
            font-weight: 500;
            margin: 16px 24px 0;
        }

        /* Pagination area */
        .table-footer {
            padding: 14px 24px;
            border-top: 1px solid #f1f5f9;
            display: flex;
            justify-content: space-between;
            align-items: center;
        }
        .table-info-text {
            font-size: 0.8rem;
            color: #94a3b8;
        }
    </style>
</head>
<body>

<!-- ===== SIDEBAR ===== -->
<div class="sidebar">
    <div class="sidebar-brand">
        <div class="brand-icon-wrap">
            <div class="brand-icon-glow"></div>
            <div class="brand-icon-inner"><i class="fa-solid fa-motorcycle"></i></div>
        </div>
        <div>
            <div class="brand-text">PHỤ KIỆN XE MÁY <span class="badge-247-sm">247</span></div>
            <div class="brand-sub">Hệ thống đồ chơi chính hãng</div>
        </div>
    </div>

    <div class="sidebar-nav">
        <div class="nav-label">Cửa Hàng</div>
        <a href="{{ route('welcome') }}" class="nav-item-link">
            <div class="nav-icon"><i class="fa-solid fa-store"></i></div>
            Trang Chủ Cửa Hàng
        </a>
        <a href="{{ route('cart.index') }}" class="nav-item-link">
            <div class="nav-icon"><i class="fa-solid fa-cart-shopping"></i></div>
            Giỏ Hàng
        </a>

        <div class="nav-label" style="margin-top: 15px;">Quản lý</div>
        <a href="{{ route('categories.index') }}" class="nav-item-link active">
            <div class="nav-icon"><i class="fa-solid fa-tags"></i></div>
            Danh Mục Phụ Kiện
        </a>
        <a href="{{ route('products.index') }}" class="nav-item-link">
            <div class="nav-icon"><i class="fa-solid fa-box"></i></div>
            Sản Phẩm Phụ Kiện
        </a>
        
        @auth
            @if(Auth::user()->isAdmin())
                <div class="nav-label" style="margin-top: 20px;">Admin</div>
                <a href="{{ route('admin.dashboard') }}" class="nav-item-link">
                    <div class="nav-icon"><i class="fa-solid fa-chart-line"></i></div>
                    Bảng Điều Khiển
                </a>
            @endif
        @endauth
    </div>
</div>

<!-- ===== MAIN CONTENT ===== -->
<div class="main-content">

    <!-- Top Bar -->
    <div class="topbar">
        <div>
            <div class="topbar-title">Hệ Thống Quản Lý Phụ Kiện Xe Máy</div>
            <div class="topbar-breadcrumb">Trang chủ &rsaquo; Danh mục phụ kiện</div>
        </div>
        <div class="d-flex align-items-center gap-3">
            <div style="font-size:0.8rem;color:#64748b;"><i class="fa-regular fa-clock me-1"></i>{{ now()->format('d/m/Y') }}</div>
            
            @auth
                <div style="border-left: 1px solid #e2e8f0; padding-left: 15px; display: flex; align-items: center; gap: 12px;">
                    <div style="text-align: right;">
                        <div style="font-size: 0.85rem; font-weight: 600; color: #1e293b;">{{ Auth::user()->name }}</div>
                        <div style="font-size: 0.7rem; color: #94a3b8;">
                            @if(Auth::user()->isAdmin())
                                Quản trị viên
                            @elseif(Auth::user()->isEditor())
                                Biên tập viên
                            @elseif(Auth::user()->isManager())
                                Quản lý
                            @else
                                Khách hàng
                            @endif
                        </div>
                    </div>
                    <form action="{{ route('logout') }}" method="POST" style="margin: 0;">
                        @csrf
                        <button type="submit" style="background: #dc3545; color: white; border: none; padding: 6px 12px; border-radius: 6px; font-size: 0.8rem; font-weight: 600; cursor: pointer; transition: all 0.2s;">
                            <i class="fa-solid fa-sign-out me-1"></i>Đăng Xuất
                        </button>
                    </form>
                </div>
            @else
                <div style="border-left: 1px solid #e2e8f0; padding-left: 15px; display: flex; align-items: center; gap: 8px;">
                    <a href="{{ route('login') }}" style="background: #0d6efd; color: white; border: none; padding: 6px 12px; border-radius: 6px; font-size: 0.8rem; font-weight: 600; text-decoration: none; transition: all 0.2s;">
                        Đăng Nhập
                    </a>
                    <a href="{{ route('register') }}" style="background: #e2e8f0; color: #334155; border: none; padding: 6px 12px; border-radius: 6px; font-size: 0.8rem; font-weight: 600; text-decoration: none; transition: all 0.2s;">
                        Đăng Ký
                    </a>
                </div>
            @endauth
        </div>
    </div>

    <!-- Page Header -->
    <div class="page-header">
        <div class="page-header-left">
            <h1>Danh Mục Phụ Kiện Xe Máy</h1>
            <p>Quản lý phân loại các nhóm linh kiện &amp; phụ kiện xe máy</p>
        </div>
        @auth
            @if(Auth::user()->isAdmin() || Auth::user()->isEditor() || Auth::user()->isManager())
                <a href="{{ route('categories.create') }}" class="btn-add-main">
                    <i class="fa-solid fa-plus"></i> Thêm danh mục mới
                </a>
            @endif
        @endauth
    </div>

    <!-- Stats Row (Đồng bộ từ Dashboard) -->
    <div class="row stats-row g-3">
        <div class="col-md-3">
            <div class="stat-card">
                <div class="stat-icon blue"><i class="fa-solid fa-tags"></i></div>
                <div>
                    <div class="stat-value">{{ $categories->count() }}</div>
                    <div class="stat-label">Tổng danh mục</div>
                </div>
            </div>
        </div>
        <div class="col-md-3">
            <div class="stat-card">
                <div class="stat-icon green"><i class="fa-solid fa-box-open"></i></div>
                <div>
                    <div class="stat-value">{{ $totalProducts ?? $categories->sum(fn($c) => $c->products_count ?? 0) }}</div>
                    <div class="stat-label">Tổng sản phẩm phụ kiện</div>
                </div>
            </div>
        </div>
        <div class="col-md-3">
            <div class="stat-card">
                <div class="stat-icon orange"><i class="fa-solid fa-triangle-exclamation"></i></div>
                <div>
                    <div class="stat-value">{{ $lowStockProducts ?? 0 }}</div>
                    <div class="stat-label">Sắp hết hàng (&le;5)</div>
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

    <!-- Quick Actions Panel (Thao Tác Nhanh Dashboard) -->
    <div class="quick-actions-card">
        <div class="quick-actions-title">
            <i class="fa-solid fa-bolt" style="color: #f59e0b;"></i>
            Thao Tác Nhanh Quản Lý Phụ Kiện Xe Máy
        </div>
        <div class="d-flex flex-wrap gap-2">
            @auth
                @if(Auth::user()->isAdmin() || Auth::user()->isEditor() || Auth::user()->isManager())
                    <a href="{{ route('categories.create') }}" class="quick-btn quick-btn-primary">
                        <i class="fa-solid fa-plus"></i> Thêm danh mục mới
                    </a>
                    <a href="{{ route('products.create') }}" class="quick-btn quick-btn-success">
                        <i class="fa-solid fa-plus-circle"></i> Thêm sản phẩm phụ kiện
                    </a>
                @endif
            @endauth
            <a href="{{ route('products.index') }}" class="quick-btn quick-btn-secondary">
                <i class="fa-solid fa-boxes-stacked"></i> Xem danh sách sản phẩm
            </a>
            @auth
                @if(Auth::user()->isAdmin())
                    <a href="{{ route('admin.dashboard') }}" class="quick-btn quick-btn-secondary">
                        <i class="fa-solid fa-chart-pie"></i> Bảng điều khiển Admin
                    </a>
                @endif
            @endauth
        </div>
    </div>

    <!-- Table Card -->
    <div class="table-card">
        <div class="table-card-header">
            <div class="table-card-title">
                <i class="fa-solid fa-list-ul" style="color:#2563eb;"></i>
                Danh sách danh mục
                <span style="background:#eff6ff;color:#2563eb;border-radius:20px;padding:2px 10px;font-size:0.72rem;font-weight:700;">{{ $categories->count() }}</span>
            </div>
            <div class="table-search">
                <i class="fa-solid fa-magnifying-glass" style="color:#94a3b8;font-size:0.8rem;"></i>
                <input type="text" id="searchInput" placeholder="Tìm kiếm danh mục...">
            </div>
        </div>

        @if(session('success'))
            <div class="alert-success-custom">
                <i class="fa-solid fa-circle-check"></i>
                {{ session('success') }}
            </div>
        @endif

        <div class="table-responsive">
            <table class="cat-table" id="categoryTable">
                <thead>
                    <tr>
                        <th style="width:60px;">ID</th>
                        <th style="width:220px;">Tên danh mục</th>
                        <th>Mô tả chi tiết</th>
                        <th style="width:110px;">Trạng thái</th>
                        <th style="width:150px;" class="text-center">Thao tác</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($categories as $cat)
                    <tr>
                        <td>
                            <div class="id-badge">#{{ sprintf('%02d', $cat->id) }}</div>
                        </td>
                        <td>
                            <div class="cat-name">{{ $cat->name }}</div>
                        </td>
                        <td>
                            <div class="cat-desc">{{ Str::limit($cat->description, 100, '...') ?: '—' }}</div>
                        </td>
                        <td>
                            <span class="status-badge">
                                <span class="status-dot"></span> Hoạt động
                            </span>
                        </td>
                        <td class="text-center">
                            @auth
                                @if(Auth::user()->isAdmin() || Auth::user()->isEditor() || Auth::user()->isManager())
                                    <a href="{{ route('categories.edit', $cat->id) }}" class="btn-edit-tbl me-1">
                                        <i class="fa-solid fa-pen-to-square"></i> Sửa
                                    </a>
                                @endif
                                @if(Auth::user()->isAdmin())
                                    <form action="{{ route('categories.destroy', $cat->id) }}" method="POST" class="d-inline" onsubmit="return confirmDelete(event, this)">
                                        @csrf
                                        @method('DELETE')
                                        <button type="submit" class="btn-del-tbl border-0">
                                            <i class="fa-solid fa-trash"></i> Xóa
                                        </button>
                                    </form>
                                @endif
                                @if(Auth::user()->isCustomer())
                                    <span class="badge bg-light text-secondary fw-normal px-2 py-1"><i class="fa-solid fa-eye me-1"></i>Chỉ xem</span>
                                @endif
                            @else
                                <span class="badge bg-light text-secondary fw-normal px-2 py-1"><i class="fa-solid fa-eye me-1"></i>Chỉ xem</span>
                            @endauth
                        </td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="5">
                            <div class="empty-state">
                                <div class="empty-state-icon"><i class="fa-solid fa-folder-open"></i></div>
                                <h6>Chưa có danh mục nào</h6>
                                <p>Bắt đầu bằng cách thêm danh mục phụ kiện đầu tiên.</p>
                            </div>
                        </td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        <div class="table-footer">
            <div class="table-info-text">Hiển thị {{ $categories->count() }} danh mục</div>
            <div class="table-info-text">Cập nhật lúc {{ now()->format('H:i, d/m/Y') }}</div>
        </div>
    </div>

</div>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
<script>
    // Search filter
    document.getElementById('searchInput').addEventListener('input', function() {
        const query = this.value.toLowerCase();
        document.querySelectorAll('#categoryTable tbody tr').forEach(row => {
            const text = row.innerText.toLowerCase();
            row.style.display = text.includes(query) ? '' : 'none';
        });
    });

    // SweetAlert2 confirm delete
    function confirmDelete(e, form) {
        e.preventDefault();
        Swal.fire({
            title: 'Xác nhận xóa?',
            text: 'Danh mục này sẽ bị xóa vĩnh viễn. Bạn không thể hoàn tác!',
            icon: 'warning',
            showCancelButton: true,
            confirmButtonColor: '#e11d48',
            cancelButtonColor: '#64748b',
            confirmButtonText: '<i class="fa-solid fa-trash me-1"></i> Xóa ngay',
            cancelButtonText: 'Hủy bỏ',
            borderRadius: '12px',
            customClass: { popup: 'rounded-3' }
        }).then((result) => {
            if (result.isConfirmed) form.submit();
        });
    }
</script>
</body>
</html>