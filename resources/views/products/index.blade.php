<!DOCTYPE html>
<html lang="vi">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Quản Lý Sản Phẩm | PHỤ KIỆN XE MÁY</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.0/css/all.min.css">
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700;800&display=swap" rel="stylesheet">
    <style>
        * { box-sizing: border-box; }
        body { background: #f0f4f8; font-family: 'Inter', sans-serif; min-height: 100vh; }

        /* SIDEBAR */
        .sidebar {
            width: 260px; height: 100vh; position: fixed; top: 0; left: 0;
            background: #fff; border-right: 1px solid #e2e8f0;
            display: flex; flex-direction: column; z-index: 100;
            box-shadow: 2px 0 12px rgba(0,0,0,0.06);
        }
        @keyframes badgeGlowPulse {
            0%, 100% { opacity: 0.5; filter: blur(4px); transform: scale(1); }
            50% { opacity: 0.9; filter: blur(7px); transform: scale(1.05); }
        }
        @keyframes revEngine {
            0% { transform: scale(1) rotate(0deg); }
            20% { transform: scale(1.2) rotate(-12deg); }
            40% { transform: scale(1.15) rotate(6deg); }
            60% { transform: scale(1.2) rotate(-8deg); }
            80% { transform: scale(1.15) rotate(3deg); }
            100% { transform: scale(1.12) rotate(-4deg); }
        }

        .sidebar-brand {
            padding: 18px 18px 16px; border-bottom: 1px solid #f1f5f9;
            display: flex; align-items: center; gap: 11px;
            background: linear-gradient(180deg, #f8fafc 0%, #ffffff 100%);
            cursor: pointer;
        }
        .brand-icon-wrap {
            position: relative; width: 42px; height: 42px; flex-shrink: 0;
        }
        .brand-icon-glow {
            position: absolute; inset: -2px;
            background: linear-gradient(135deg, #2563eb, #f59e0b);
            border-radius: 12px;
            animation: badgeGlowPulse 3s ease-in-out infinite;
        }
        .brand-icon-inner {
            position: relative; width: 100%; height: 100%;
            background: linear-gradient(135deg, #0f172a, #1e293b);
            border: 1px solid rgba(255,255,255,0.2);
            border-radius: 11px; display: flex; align-items: center; justify-content: center;
            color: #60a5fa; font-size: 1.15rem;
            box-shadow: 0 4px 10px rgba(15,23,42,0.3);
            transition: transform 0.3s ease;
        }
        .sidebar-brand:hover .brand-icon-inner i {
            animation: revEngine 0.6s ease-in-out forwards;
        }
        .brand-text { font-size: 0.95rem; font-weight: 900; color: #0f172a; line-height: 1.1; display: flex; align-items: center; gap: 5px; }
        .brand-sub  { font-size: 0.7rem; color: #64748b; font-weight: 600; margin-top: 2px; }
        .badge-247-sm { background: linear-gradient(135deg, #f59e0b, #d97706); color: #fff; font-size: 0.65rem; font-weight: 900; padding: 1px 5px; border-radius: 4px; }


        .sidebar-nav { padding: 12px 10px; flex: 1; }
        .nav-label {
            font-size: 0.65rem; font-weight: 700; text-transform: uppercase;
            letter-spacing: 1px; color: #94a3b8; padding: 10px 10px 6px;
        }
        .nav-link-item {
            display: flex; align-items: center; gap: 10px;
            padding: 10px 14px; border-radius: 9px;
            color: #64748b; text-decoration: none;
            font-weight: 500; font-size: 0.875rem;
            transition: all 0.18s; margin-bottom: 2px;
        }
        .nav-link-item:hover { background: #f1f5f9; color: #1e293b; }
        .nav-link-item.active { background: #eff6ff; color: #2563eb; font-weight: 600; }
        .nav-icon {
            width: 32px; height: 32px; border-radius: 8px;
            display: flex; align-items: center; justify-content: center;
            font-size: 0.8rem; background: #f1f5f9; transition: all 0.18s;
        }
        .nav-link-item.active .nav-icon { background: #dbeafe; color: #2563eb; }
        .nav-link-item:hover .nav-icon { background: #e2e8f0; color: #1e293b; }

        /* MAIN */
        .main-content { margin-left: 260px; padding: 28px 32px; min-height: 100vh; }

        /* TOPBAR */
        .topbar {
            display: flex; justify-content: space-between; align-items: center;
            background: #fff; padding: 14px 22px; border-radius: 14px;
            box-shadow: 0 2px 8px rgba(0,0,0,0.04);
            margin-bottom: 24px; border: 1px solid #f1f5f9;
        }
        .topbar-title { font-size: 1rem; font-weight: 700; color: #1e293b; }
        .topbar-breadcrumb { font-size: 0.8rem; color: #94a3b8; margin-top: 1px; }

        /* PAGE HEADER */
        .page-header { display: flex; justify-content: space-between; align-items: flex-start; margin-bottom: 22px; }
        .page-header h1 { font-size: 1.5rem; font-weight: 800; color: #0f172a; margin-bottom: 4px; }
        .page-header p  { font-size: 0.85rem; color: #64748b; margin: 0; }
        .btn-add-main {
            display: inline-flex; align-items: center; gap: 8px;
            background: linear-gradient(135deg, #3b82f6, #2563eb);
            color: #fff; padding: 10px 20px; border-radius: 10px;
            font-weight: 600; font-size: 0.875rem; text-decoration: none;
            box-shadow: 0 4px 12px rgba(37,99,235,0.25); transition: all 0.2s;
        }
        .btn-add-main:hover {
            background: linear-gradient(135deg, #2563eb, #1d4ed8);
            color: #fff; transform: translateY(-1px);
            box-shadow: 0 6px 18px rgba(37,99,235,0.35);
        }

        /* STATS */
        .stats-row { margin-bottom: 22px; }
        .stat-card {
            background: #fff; border-radius: 14px; padding: 18px 20px;
            border: 1px solid #f1f5f9;
            box-shadow: 0 2px 8px rgba(0,0,0,0.04);
            display: flex; align-items: center; gap: 14px;
            transition: box-shadow 0.2s;
        }
        .stat-card:hover { box-shadow: 0 4px 16px rgba(0,0,0,0.08); }
        .stat-icon {
            width: 48px; height: 48px; border-radius: 12px;
            display: flex; align-items: center; justify-content: center;
            font-size: 1.1rem; flex-shrink: 0;
        }
        .si-blue   { background: #eff6ff; color: #2563eb; }
        .si-green  { background: #f0fdf4; color: #16a34a; }
        .si-orange { background: #fff7ed; color: #ea580c; }
        .si-purple { background: #faf5ff; color: #7c3aed; }
        .stat-value { font-size: 1.5rem; font-weight: 800; color: #0f172a; line-height: 1; }
        .stat-label { font-size: 0.78rem; color: #64748b; margin-top: 3px; }

        /* TABLE CARD */
        .table-card {
            background: #fff; border-radius: 16px;
            box-shadow: 0 2px 12px rgba(0,0,0,0.05);
            border: 1px solid #f1f5f9; overflow: hidden;
        }
        .table-card-header {
            padding: 18px 24px; border-bottom: 1px solid #f1f5f9;
            display: flex; align-items: center; justify-content: space-between;
        }
        .tc-title {
            font-size: 0.95rem; font-weight: 700; color: #1e293b;
            display: flex; align-items: center; gap: 8px;
        }
        .count-pill {
            background: #eff6ff; color: #2563eb;
            border-radius: 20px; padding: 2px 10px;
            font-size: 0.72rem; font-weight: 700;
        }
        .search-box {
            display: flex; align-items: center; gap: 8px;
            background: #f8fafc; border: 1px solid #e2e8f0;
            border-radius: 8px; padding: 7px 14px; width: 230px;
        }
        .search-box input {
            border: none; background: transparent; outline: none;
            font-size: 0.85rem; color: #334155; width: 100%;
            font-family: 'Inter', sans-serif;
        }

        /* TABLE */
        table.prod-table { width: 100%; border-collapse: collapse; }
        table.prod-table thead tr { background: #f8fafc; border-bottom: 1px solid #e9eef5; }
        table.prod-table thead th {
            padding: 12px 16px; font-size: 0.72rem;
            font-weight: 700; color: #64748b;
            text-transform: uppercase; letter-spacing: 0.6px;
        }
        table.prod-table tbody tr { border-bottom: 1px solid #f8fafc; transition: background 0.15s; }
        table.prod-table tbody tr:last-child { border-bottom: none; }
        table.prod-table tbody tr:hover { background: #f8fafc; }
        table.prod-table td { padding: 14px 16px; vertical-align: middle; }

        .id-badge {
            width: 36px; height: 36px; border-radius: 8px;
            background: #f1f5f9; color: #475569;
            font-size: 0.78rem; font-weight: 700;
            display: inline-flex; align-items: center; justify-content: center;
        }
        .prod-name  { font-weight: 600; color: #1e293b; font-size: 0.9rem; }
        .prod-desc  { font-size: 0.78rem; color: #94a3b8; margin-top: 2px; }
        .cat-tag {
            display: inline-flex; align-items: center; gap: 4px;
            background: #f0fdf4; color: #15803d;
            border-radius: 6px; padding: 3px 10px;
            font-size: 0.75rem; font-weight: 600;
        }

        /* GIÁ TIỀN – nổi bật */
        .price-cell {
            font-size: 0.95rem; font-weight: 700;
            color: #0f172a; white-space: nowrap;
        }
        .price-cell .price-vnđ {
            display: block;
            font-size: 0.95rem;
            font-weight: 800;
            color: #2563eb;
        }
        .price-cell .price-label {
            font-size: 0.7rem; font-weight: 500; color: #94a3b8;
        }

        /* Tồn kho */
        .stock-good { color: #15803d; font-weight: 700; font-size: 0.88rem; }
        .stock-low  { color: #ea580c; font-weight: 700; font-size: 0.88rem; }
        .stock-zero { color: #e11d48; font-weight: 700; font-size: 0.88rem; }

        /* Buttons */
        .btn-edit-r {
            display: inline-flex; align-items: center; gap: 5px;
            background: #fef9c3; color: #a16207;
            border: 1px solid #fef08a; border-radius: 7px;
            font-size: 0.78rem; font-weight: 600;
            padding: 5px 11px; text-decoration: none; transition: all 0.15s;
        }
        .btn-edit-r:hover { background: #fef08a; color: #854d0e; }
        .btn-del-r {
            display: inline-flex; align-items: center; gap: 5px;
            background: #fff1f2; color: #e11d48;
            border: 1px solid #fecdd3; border-radius: 7px;
            font-size: 0.78rem; font-weight: 600;
            padding: 5px 11px; cursor: pointer; transition: all 0.15s;
        }
        .btn-del-r:hover { background: #fecdd3; color: #be123c; }

        /* Empty */
        .empty-state { text-align: center; padding: 60px 20px; }
        .empty-icon {
            width: 70px; height: 70px; background: #f1f5f9; border-radius: 50%;
            display: inline-flex; align-items: center; justify-content: center;
            font-size: 1.8rem; color: #94a3b8; margin-bottom: 16px;
        }
        .empty-state h6 { font-weight: 700; color: #334155; margin-bottom: 6px; }
        .empty-state p  { font-size: 0.85rem; color: #94a3b8; margin: 0; }

        /* Alert */
        .alert-ok {
            background: #f0fdf4; border: 1px solid #bbf7d0; color: #15803d;
            border-radius: 10px; padding: 12px 18px;
            display: flex; align-items: center; gap: 10px;
            font-size: 0.875rem; font-weight: 500; margin: 16px 24px 0;
        }

        /* Footer */
        .tbl-footer {
            padding: 14px 24px; border-top: 1px solid #f1f5f9;
            display: flex; justify-content: space-between; align-items: center;
        }
        .tbl-info { font-size: 0.8rem; color: #94a3b8; }
    </style>
</head>
<body>

<!-- SIDEBAR -->
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
        <a href="{{ route('welcome') }}" class="nav-link-item">
            <div class="nav-icon"><i class="fa-solid fa-store"></i></div> Trang Chủ Cửa Hàng
        </a>
        <a href="{{ route('cart.index') }}" class="nav-link-item">
            <div class="nav-icon"><i class="fa-solid fa-cart-shopping"></i></div> Giỏ Hàng
        </a>

        <div class="nav-label" style="margin-top: 15px;">Quản lý</div>
        <a href="{{ route('categories.index') }}" class="nav-link-item">
            <div class="nav-icon"><i class="fa-solid fa-tags"></i></div> Danh Mục Phụ Kiện
        </a>
        <a href="{{ route('products.index') }}" class="nav-link-item active">
            <div class="nav-icon"><i class="fa-solid fa-box"></i></div> Sản Phẩm Phụ Kiện
        </a>
        @auth
            @if(Auth::user()->isAdmin())
                <div class="nav-label" style="margin-top: 20px;">Admin</div>
                <a href="{{ route('admin.dashboard') }}" class="nav-link-item">
                    <div class="nav-icon"><i class="fa-solid fa-chart-line"></i></div> Bảng Điều Khiển
                </a>
            @endif
        @endauth
    </div>
</div>

<!-- MAIN -->
<div class="main-content">

    <!-- Topbar -->
    <div class="topbar">
        <div>
            <div class="topbar-title">Hệ Thống Quản Lý Phụ Kiện Xe Máy</div>
            <div class="topbar-breadcrumb">Trang chủ &rsaquo; Sản phẩm phụ kiện</div>
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
                        <button type="submit" style="background: #dc3545; color: white; border: none; padding: 6px 12px; border-radius: 6px; font-size: 0.8rem; font-weight: 600; cursor: pointer;">
                            <i class="fa-solid fa-sign-out me-1"></i>Đăng Xuất
                        </button>
                    </form>
                </div>
            @else
                <div style="border-left: 1px solid #e2e8f0; padding-left: 15px; display: flex; align-items: center; gap: 8px;">
                    <a href="{{ route('login') }}" style="background: #0d6efd; color: white; border: none; padding: 6px 12px; border-radius: 6px; font-size: 0.8rem; font-weight: 600; text-decoration: none;">Đăng Nhập</a>
                    <a href="{{ route('register') }}" style="background: #e2e8f0; color: #334155; border: none; padding: 6px 12px; border-radius: 6px; font-size: 0.8rem; font-weight: 600; text-decoration: none;">Đăng Ký</a>
                </div>
            @endauth
        </div>
    </div>

    <!-- Page Header -->
    <div class="page-header">
        <div>
            <h1>Danh Sách Sản Phẩm</h1>
            <p>Quản lý toàn bộ sản phẩm phụ kiện xe máy &amp; giá bán</p>
        </div>
        @auth
            @if(Auth::user()->isAdmin() || Auth::user()->isEditor() || Auth::user()->isManager())
                <a href="{{ route('products.create') }}" class="btn-add-main">
                    <i class="fa-solid fa-plus"></i> Thêm sản phẩm mới
                </a>
            @endif
        @endauth
    </div>

    <!-- Stats -->
    <div class="row stats-row g-3">
        <div class="col-md-3">
            <div class="stat-card">
                <div class="stat-icon si-blue"><i class="fa-solid fa-box"></i></div>
                <div>
                    <div class="stat-value">{{ $products->count() }}</div>
                    <div class="stat-label">Tổng sản phẩm</div>
                </div>
            </div>
        </div>
        <div class="col-md-3">
            <div class="stat-card">
                <div class="stat-icon si-purple"><i class="fa-solid fa-tag"></i></div>
                <div>
                    <div class="stat-value">{{ number_format($products->avg('price'), 0, ',', '.') }}₫</div>
                    <div class="stat-label">Giá trung bình</div>
                </div>
            </div>
        </div>
        <div class="col-md-3">
            <div class="stat-card">
                <div class="stat-icon si-green"><i class="fa-solid fa-cubes"></i></div>
                <div>
                    <div class="stat-value">{{ number_format($products->sum('stock')) }}</div>
                    <div class="stat-label">Tổng tồn kho</div>
                </div>
            </div>
        </div>
        <div class="col-md-3">
            <div class="stat-card">
                <div class="stat-icon si-orange"><i class="fa-solid fa-triangle-exclamation"></i></div>
                <div>
                    <div class="stat-value">{{ $products->where('stock', '<=', 5)->count() }}</div>
                    <div class="stat-label">Sắp hết hàng</div>
                </div>
            </div>
        </div>
    </div>

    <!-- Table -->
    <div class="table-card">
        <div class="table-card-header">
            <div class="tc-title">
                <i class="fa-solid fa-box-open" style="color:#2563eb;"></i>
                Bảng sản phẩm
                <span class="count-pill">{{ $products->count() }}</span>
            </div>
            <div class="search-box">
                <i class="fa-solid fa-magnifying-glass" style="color:#94a3b8;font-size:0.8rem;"></i>
                <input type="text" id="searchInput" placeholder="Tìm kiếm sản phẩm...">
            </div>
        </div>

        @if(session('success'))
        <div class="alert-ok">
            <i class="fa-solid fa-circle-check"></i> {{ session('success') }}
        </div>
        @endif

        <div class="table-responsive">
            <table class="prod-table" id="prodTable">
                <thead>
                    <tr>
                        <th style="width:55px;">ID</th>
                        <th style="width:70px;">Hình ảnh</th>
                        <th style="width:200px;">Tên sản phẩm</th>
                        <th style="width:140px;">Danh mục</th>
                        <th style="width:140px;" class="text-end pe-4">Giá bán</th>
                        <th style="width:100px;" class="text-center">Tồn kho</th>
                        <th>Mô tả</th>
                        <th style="width:150px;" class="text-center">Thao tác</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($products as $product)
                    <tr>
                        <td><div class="id-badge">#{{ sprintf('%02d', $product->id) }}</div></td>
                        <td>
                            @if(!empty($product->image))
                                <img src="{{ asset($product->image) }}" alt="{{ $product->name }}" style="width:48px;height:48px;object-fit:cover;border-radius:8px;border:1px solid #e2e8f0;">
                            @else
                                <div style="width:48px;height:48px;border-radius:8px;background:#f1f5f9;display:flex;align-items:center;justify-content:center;color:#94a3b8;"><i class="fa-solid fa-image"></i></div>
                            @endif
                        </td>
                        <td>
                            <div class="prod-name">{{ $product->name }}</div>
                        </td>

                        <td>
                            @if($product->category)
                                <span class="cat-tag">
                                    <i class="fa-solid fa-tag" style="font-size:0.65rem;"></i>
                                    {{ $product->category->name }}
                                </span>
                            @else
                                <span style="color:#94a3b8;font-size:0.8rem;">—</span>
                            @endif
                        </td>
                        <td class="text-end pe-4">
                            <div class="price-cell">
                                <span class="price-vnđ">{{ number_format($product->price, 0, ',', '.') }}₫</span>
                                <span class="price-label">VNĐ</span>
                            </div>
                        </td>
                        <td class="text-center">
                            @if($product->stock == 0)
                                <span class="stock-zero"><i class="fa-solid fa-circle-xmark me-1"></i>Hết hàng</span>
                            @elseif($product->stock <= 5)
                                <span class="stock-low"><i class="fa-solid fa-triangle-exclamation me-1"></i>{{ $product->stock }}</span>
                            @else
                                <span class="stock-good"><i class="fa-solid fa-circle-check me-1"></i>{{ $product->stock }}</span>
                            @endif
                        </td>
                        <td>
                            <span style="font-size:0.8rem;color:#64748b;">{{ Str::limit($product->description, 60, '...') ?: '—' }}</span>
                        </td>
                        <td class="text-center">
                            @auth
                                @if(Auth::user()->isAdmin() || Auth::user()->isEditor() || Auth::user()->isManager())
                                    <a href="{{ route('products.edit', $product->id) }}" class="btn-edit-r me-1">
                                        <i class="fa-solid fa-pen-to-square"></i> Sửa
                                    </a>
                                @endif
                                @if(Auth::user()->isAdmin())
                                    <form action="{{ route('products.destroy', $product->id) }}" method="POST" class="d-inline" onsubmit="return confirmDel(event, this)">
                                        @csrf
                                        @method('DELETE')
                                        <button type="submit" class="btn-del-r border-0">
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
                        <td colspan="7">
                            <div class="empty-state">
                                <div class="empty-icon"><i class="fa-solid fa-box-open"></i></div>
                                <h6>Chưa có sản phẩm nào</h6>
                                <p>Thêm sản phẩm đầu tiên để bắt đầu quản lý kho hàng.</p>
                            </div>
                        </td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        <div class="tbl-footer">
            <div class="tbl-info">Hiển thị {{ $products->count() }} sản phẩm</div>
            <div class="tbl-info">Tổng giá trị: <strong style="color:#2563eb;">{{ number_format($products->sum(fn($p)=>$p->price * $p->stock), 0, ',', '.') }}₫</strong></div>
        </div>
    </div>
</div>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
<script>
    document.getElementById('searchInput').addEventListener('input', function () {
        const q = this.value.toLowerCase();
        document.querySelectorAll('#prodTable tbody tr').forEach(row => {
            row.style.display = row.innerText.toLowerCase().includes(q) ? '' : 'none';
        });
    });

    function confirmDel(e, form) {
        e.preventDefault();
        Swal.fire({
            title: 'Xác nhận xóa sản phẩm?',
            text: 'Sản phẩm này sẽ bị xóa vĩnh viễn khỏi hệ thống!',
            icon: 'warning',
            showCancelButton: true,
            confirmButtonColor: '#e11d48',
            cancelButtonColor: '#64748b',
            confirmButtonText: '<i class="fa-solid fa-trash me-1"></i> Xóa ngay',
            cancelButtonText: 'Hủy bỏ',
            customClass: { popup: 'rounded-3' }
        }).then(r => { if (r.isConfirmed) form.submit(); });
    }
</script>
</body>
</html>
