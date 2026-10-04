<!DOCTYPE html>
<html lang="vi">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <meta name="description" content="Phụ kiện và đồ chơi xe máy chính hãng, giao hàng toàn quốc tại Phụ Kiện Xe Máy 247.">
    <link rel="canonical" href="{{ url('/') }}">
    <title>Phụ Kiện Xe Máy 247 | Cửa Hàng Đồ Chơi & Phụ Tùng Xe Máy Chính Hãng</title>
    <!-- Bootstrap 5 CSS -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
    <!-- FontAwesome 6 Icons -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.0/css/all.min.css">
    <!-- Google Font (Inter) -->
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700;800&display=swap" rel="stylesheet">
    <!-- SweetAlert2 -->
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>

    <style>
        :root {
            --primary: #1d4ed8;
            --primary-hover: #1e40af;
            --dark: #0f172a;
            --slate: #334155;
            --muted: #64748b;
            --light-bg: #f8fafc;
            --border-color: #cbd5e1;
        }

        body {
            font-family: 'Inter', sans-serif;
            background-color: var(--light-bg);
            color: var(--dark);
            margin: 0;
            padding: 0;
        }

        /* TOP BAR */
        .top-bar {
            background-color: #0f172a;
            color: #94a3b8;
            font-size: 0.82rem;
            padding: 7px 0;
        }

        /* HEADER */
        .site-header {
            background: #ffffff;
            border-bottom: 2px solid #e2e8f0;
            position: sticky;
            top: 0;
            z-index: 1030;
            box-shadow: 0 4px 12px rgba(0,0,0,0.05);
        }

        /* LOGO STYLES & ANIMATIONS */
        @keyframes badgeGlowPulse {
            0%, 100% { opacity: 0.5; filter: blur(6px); transform: scale(1); }
            50% { opacity: 0.9; filter: blur(9px); transform: scale(1.04); }
        }
        @keyframes revEngine {
            0% { transform: scale(1) rotate(0deg); }
            20% { transform: scale(1.22) rotate(-12deg); }
            40% { transform: scale(1.18) rotate(6deg); }
            60% { transform: scale(1.22) rotate(-8deg); }
            80% { transform: scale(1.18) rotate(3deg); }
            100% { transform: scale(1.15) rotate(-5deg); }
        }
        @keyframes sparkTwinkle {
            0%, 100% { opacity: 0.3; transform: scale(0.8); }
            50% { opacity: 1; transform: scale(1.3); filter: drop-shadow(0 0 6px #f59e0b); }
        }
        @keyframes shimmerSweep {
            0% { transform: translateX(-150%) rotate(25deg); }
            25%, 100% { transform: translateX(180%) rotate(25deg); }
        }
        @keyframes badgeFloat {
            0%, 100% { transform: translateY(0); }
            50% { transform: translateY(-2px); box-shadow: 0 4px 10px rgba(217, 119, 6, 0.45); }
        }

        .brand-logo {
            display: flex;
            align-items: center;
            gap: 14px;
            text-decoration: none;
            transition: transform 0.25s cubic-bezier(0.34, 1.56, 0.64, 1);
        }
        .brand-logo:hover {
            transform: translateY(-2px);
        }
        .brand-badge-wrap {
            position: relative;
            width: 48px;
            height: 48px;
            flex-shrink: 0;
        }
        .brand-badge-glow {
            position: absolute;
            inset: -3px;
            background: linear-gradient(135deg, #2563eb, #f59e0b, #3b82f6);
            border-radius: 14px;
            animation: badgeGlowPulse 3s ease-in-out infinite;
        }
        .brand-badge-inner {
            position: relative;
            width: 100%;
            height: 100%;
            background: linear-gradient(135deg, #0f172a, #1e293b);
            border: 1.5px solid rgba(255, 255, 255, 0.25);
            border-radius: 13px;
            display: flex;
            align-items: center;
            justify-content: center;
            color: #ffffff;
            box-shadow: inset 0 1px 1px rgba(255,255,255,0.4), 0 4px 12px rgba(15, 23, 42, 0.4);
            overflow: hidden;
        }
        .brand-badge-inner::after {
            content: '';
            position: absolute;
            top: -50%;
            left: -50%;
            width: 200%;
            height: 200%;
            background: linear-gradient(60deg, transparent 30%, rgba(255, 255, 255, 0.25) 50%, transparent 70%);
            animation: shimmerSweep 4.5s ease-in-out infinite;
            pointer-events: none;
        }
        .brand-badge-inner .icon-main {
            font-size: 1.45rem;
            background: linear-gradient(135deg, #60a5fa, #3b82f6);
            -webkit-background-clip: text;
            background-clip: text;
            -webkit-text-fill-color: transparent;
            filter: drop-shadow(0 2px 4px rgba(37,99,235,0.4));
            transition: transform 0.3s cubic-bezier(0.34, 1.56, 0.64, 1);
        }
        .brand-logo:hover .brand-badge-inner .icon-main {
            animation: revEngine 0.6s ease-in-out forwards;
        }
        .brand-badge-inner .icon-spark {
            position: absolute;
            top: 4px;
            right: 5px;
            font-size: 0.65rem;
            color: #f59e0b;
            animation: sparkTwinkle 2s ease-in-out infinite;
        }
        .brand-title {
            display: flex;
            align-items: center;
            gap: 7px;
            font-size: 1.25rem;
            font-weight: 900;
            line-height: 1.1;
            letter-spacing: -0.5px;
        }
        .text-gradient {
            background: linear-gradient(135deg, #0f172a 20%, #1e40af 100%);
            -webkit-background-clip: text;
            background-clip: text;
            -webkit-text-fill-color: transparent;
        }
        .badge-247 {
            background: linear-gradient(135deg, #f59e0b, #d97706);
            color: #ffffff;
            font-size: 0.72rem;
            font-weight: 900;
            padding: 3px 7px;
            border-radius: 6px;
            letter-spacing: 0.5px;
            box-shadow: 0 2px 6px rgba(217, 119, 6, 0.35);
            text-shadow: 0 1px 2px rgba(0,0,0,0.2);
            animation: badgeFloat 2.5s ease-in-out infinite;
        }
        .brand-subtitle {
            font-size: 0.72rem;
            color: #64748b;
            font-weight: 700;
            margin-top: 2px;
            letter-spacing: 0.2px;
        }

        /* SEARCH BAR */
        .header-search {
            position: relative;
            flex: 1 1 420px;
            max-width: 520px;
            min-width: 220px;
            width: 100%;
        }
        .header-search input {
            width: 100%;
            height: 50px;
            padding: 12px 58px 12px 18px;
            border-radius: 999px;
            border: 2px solid #dfe7f1;
            background: linear-gradient(180deg, #f8fbff 0%, #f1f5f9 100%);
            color: #0f172a;
            font-size: 0.9rem;
            font-weight: 500;
            transition: all 0.2s ease;
            box-shadow: inset 0 1px 2px rgba(15,23,42,0.04);
        }
        .header-search input::placeholder {
            color: #64748b;
        }
        .header-search input:focus {
            background-color: #ffffff;
            border-color: var(--primary);
            box-shadow: 0 0 0 4px rgba(37, 99, 235, 0.14);
            outline: none;
        }
        .header-search button {
            position: absolute;
            right: 8px;
            top: 8px;
            width: 34px;
            height: 34px;
            border: none;
            background: linear-gradient(135deg, #2563eb, #1d4ed8);
            color: #ffffff;
            border-radius: 50%;
            display: flex;
            align-items: center;
            justify-content: center;
            font-size: 0.9rem;
            cursor: pointer;
            box-shadow: 0 6px 14px rgba(37,99,235,0.24);
        }
        .header-search button:hover {
            background: linear-gradient(135deg, #1d4ed8, #1e40af);
        }

        @media (max-width: 991px) {
            .header-search {
                max-width: 360px;
            }
        }

        @media (max-width: 767px) {
            .header-search {
                max-width: none;
                width: 100%;
                flex: 1 1 100%;
            }
        }

        /* NÚT GIỎ HÀNG */
        .btn-cart-header {
            background: #eff6ff;
            color: #1d4ed8;
            border: 2px solid #93c5fd;
            border-radius: 25px;
            padding: 8px 20px;
            font-weight: 800;
            font-size: 0.95rem;
            text-decoration: none;
            display: inline-flex;
            align-items: center;
            gap: 10px;
            transition: all 0.2s ease;
            box-shadow: 0 2px 8px rgba(37,99,235,0.12);
        }
        .btn-cart-header:hover {
            background: #1d4ed8;
            color: #ffffff;
            border-color: #1d4ed8;
            transform: translateY(-1px);
            box-shadow: 0 4px 14px rgba(37,99,235,0.3);
        }
        .cart-count-badge {
            background: #ef4444;
            color: #ffffff;
            font-size: 0.8rem;
            font-weight: 800;
            padding: 3px 8px;
            border-radius: 12px;
        }

        /* NÚT DANH MỤC */
        .cat-chip {
            background: #ffffff;
            border: 2px solid #cbd5e1;
            color: #334155;
            padding: 9px 20px;
            border-radius: 25px;
            font-size: 0.88rem;
            font-weight: 700;
            cursor: pointer;
            transition: all 0.2s;
            display: inline-flex;
            align-items: center;
            gap: 6px;
            margin-right: 8px;
            margin-bottom: 10px;
            text-decoration: none;
        }
        .cat-chip:hover {
            background: #f1f5f9;
            border-color: #94a3b8;
            color: #0f172a;
        }
        .cat-chip.active {
            background: #1d4ed8;
            color: #ffffff;
            border-color: #1d4ed8;
            box-shadow: 0 4px 12px rgba(29, 78, 216, 0.3);
        }

        /* CARD SẢN PHẨM */
        .product-card {
            background: #ffffff;
            border-radius: 14px;
            border: 1.5px solid #e2e8f0;
            transition: all 0.2s ease;
            height: 100%;
            display: flex;
            flex-direction: column;
            overflow: hidden;
        }
        .product-card:hover {
            box-shadow: 0 8px 24px rgba(0,0,0,0.08);
            transform: translateY(-3px);
            border-color: #94a3b8;
        }
        .product-thumb {
            height: 195px;
            background: #ffffff;
            display: flex;
            align-items: center;
            justify-content: center;
            position: relative;
            border-bottom: 1.5px solid #f1f5f9;
            overflow: hidden;
        }
        .product-thumb img {
            width: 100%;
            height: 100%;
            object-fit: cover;
            transition: transform 0.35s ease;
        }
        .product-card:hover .product-thumb img {
            transform: scale(1.08);
        }
        .product-thumb > i {
            font-size: 3.8rem;
            color: #cbd5e1;
            transition: transform 0.2s;
        }
        .product-card:hover .product-thumb > i {
            transform: scale(1.1);
            color: #1d4ed8;
        }
        .cat-tag {
            position: absolute;
            top: 10px;
            left: 10px;
            background: #ffffff;
            color: #1d4ed8;
            font-size: 0.72rem;
            font-weight: 800;
            padding: 4px 10px;
            border-radius: 6px;
            border: 1px solid #bfdbfe;
        }

        .product-details {
            padding: 18px;
            flex: 1;
            display: flex;
            flex-direction: column;
        }
        .product-name {
            font-size: 0.98rem;
            font-weight: 800;
            color: var(--dark);
            margin-bottom: 6px;
            line-height: 1.35;
        }
        .product-desc {
            font-size: 0.8rem;
            color: var(--muted);
            margin-bottom: 14px;
            display: -webkit-box;
            -webkit-line-clamp: 2;
            line-clamp: 2;
            -webkit-box-orient: vertical;
            overflow: hidden;
            flex: 1;
        }
        .product-price {
            font-size: 1.25rem;
            font-weight: 800;
            color: #1d4ed8;
            margin-bottom: 14px;
        }

        /* NÚT THÊM VÀO GIỎ */
        .btn-add-cart-prominent {
            background: linear-gradient(135deg, #2563eb, #1d4ed8);
            color: #ffffff;
            border: none;
            width: 100%;
            padding: 11px 16px;
            border-radius: 10px;
            font-weight: 800;
            font-size: 0.9rem;
            letter-spacing: 0.3px;
            display: flex;
            align-items: center;
            justify-content: center;
            gap: 8px;
            cursor: pointer;
            transition: all 0.2s ease;
            box-shadow: 0 4px 12px rgba(37,99,235,0.25);
        }
        .btn-add-cart-prominent:hover {
            background: linear-gradient(135deg, #1d4ed8, #1e40af);
            transform: translateY(-1px);
            box-shadow: 0 6px 16px rgba(37,99,235,0.35);
            color: #ffffff;
        }
        .btn-add-cart-prominent:active {
            transform: translateY(0);
        }

        /* FOOTER */
        .site-footer {
            background: #ffffff;
            border-top: 2px solid #e2e8f0;
            padding: 40px 0 20px;
            margin-top: 60px;
            font-size: 0.88rem;
            color: var(--muted);
        }

        .benefit-section {
            background: linear-gradient(180deg, #ffffff 0%, #f8fafc 100%);
            border-radius: 26px;
            padding: 28px 22px;
            margin-top: 24px;
            border: 1px solid #e2e8f0;
        }
        .benefit-grid {
            display: grid;
            grid-template-columns: repeat(4, minmax(0, 1fr));
            gap: 18px;
        }
        .benefit-card {
            background: #fff;
            border: 1px solid #e2e8f0;
            border-radius: 18px;
            padding: 18px 16px;
            box-shadow: 0 8px 18px rgba(15,23,42,0.04);
        }
        .benefit-card i {
            display: inline-flex;
            width: 42px;
            height: 42px;
            border-radius: 12px;
            align-items: center;
            justify-content: center;
            background: #eff6ff;
            color: #2563eb;
            margin-bottom: 12px;
            font-size: 1.1rem;
        }
        .benefit-card strong {
            display: block;
            margin-bottom: 6px;
            font-size: 0.9rem;
            color: #0f172a;
        }
        .benefit-card span {
            color: #64748b;
            font-size: 0.76rem;
            line-height: 1.6;
        }

        @media (max-width: 991px) {
            .benefit-grid {
                grid-template-columns: repeat(2, minmax(0, 1fr));
            }
        }
        @media (max-width: 575px) {
            .benefit-grid {
                grid-template-columns: 1fr;
            }
        }
    </style>
    <link rel="stylesheet" href="{{ asset('css/storefront.css') }}?v={{ filemtime(public_path('css/storefront.css')) }}">
</head>
<body><a class="skip-link" href="#catalog">Đến danh sách sản phẩm</a>

    <!-- TOP BAR -->
    <div class="top-bar">
        <div class="container d-flex justify-content-between align-items-center">
            <div>
                <i class="fa-solid fa-phone me-1 text-info"></i> Hotline: <strong class="text-white">0901.234.567</strong>
                <span class="mx-2 text-secondary">|</span>
                <i class="fa-solid fa-location-dot me-1 text-info"></i> 123 Nguyễn Trãi, Q.5, TP.HCM
            </div>
            <div>
                <i class="fa-solid fa-clock me-1 text-warning"></i> Giờ mở cửa: 08:00 - 21:00 (T2 - CN)
            </div>
        </div>
    </div>

    <!-- MAIN HEADER -->
    <header class="site-header">
        <div class="container py-3">
            <div class="d-flex align-items-center justify-content-between gap-3">
                <a href="{{ route('welcome') }}" class="brand-logo">
                    <div class="brand-badge-wrap">
                        <div class="brand-badge-glow"></div>
                        <div class="brand-badge-inner">
                            <i class="fa-solid fa-motorcycle icon-main"></i>
                            <i class="fa-solid fa-bolt icon-spark"></i>
                        </div>
                    </div>
                    <div>
                        <div class="brand-title">
                            <span class="text-gradient">PHỤ KIỆN XE MÁY</span>
                            <span class="badge-247">247</span>
                        </div>
                        <div class="brand-subtitle">
                            <i class="fa-solid fa-shield-halved text-primary me-1"></i>ĐỒ CHƠI &amp; PHỤ TÙNG CHÍNH HÃNG
                        </div>
                    </div>
                </a>

                <!-- SEARCH BAR -->
                <form action="{{ route('welcome') }}" method="GET" class="header-search d-none d-md-flex">
                    <input type="text" id="searchInput" name="q" value="{{ request('q') }}" placeholder="Tìm kiếm phụ kiện (Brembo, Ohlins, Akrapovic...)...">
                    <button type="submit" title="Tìm kiếm"><i class="fa-solid fa-magnifying-glass"></i></button>
                </form>

                <!-- ACTIONS & CART BUTTON -->
                <div class="d-flex align-items-center gap-3">
                    @auth
                        @if(Auth::user()->isAdmin() || Auth::user()->isEditor() || Auth::user()->isManager())
                            <a href="{{ route('categories.index') }}" class="btn btn-md btn-light fw-bold text-dark border me-1 d-none d-lg-inline-block">
                                <i class="fa-solid fa-tags text-primary me-1"></i> Danh mục
                            </a>
                            <a href="{{ route('products.index') }}" class="btn btn-md btn-light fw-bold text-dark border me-1 d-none d-lg-inline-block">
                                <i class="fa-solid fa-box text-primary me-1"></i> Sản phẩm
                            </a>
                        @endif

                        @if(Auth::user()->isAdmin())
                            <a href="{{ route('admin.dashboard') }}" class="btn btn-md btn-dark fw-bold me-1 d-none d-lg-inline-block">
                                <i class="fa-solid fa-chart-pie me-1"></i> Dashboard
                            </a>
                        @endif
                    @endauth

                    <!-- NÚT GIỎ HÀNG -->
                    <a href="{{ route('cart.index') }}" class="btn-cart-header">
                        <i class="fa-solid fa-cart-shopping fs-5"></i>
                        <span>Giỏ hàng</span>
                        <span class="cart-count-badge" id="headerCartCount">0</span>
                    </a>

                    <!-- AUTH MENU -->
                    @auth
                        <div class="dropdown">
                            <button class="btn btn-md btn-outline-dark dropdown-toggle fw-bold" type="button" data-bs-toggle="dropdown">
                                <i class="fa-solid fa-user me-1 text-primary"></i> {{ Auth::user()->name }}
                            </button>
                            <ul class="dropdown-menu dropdown-menu-end shadow">
                                <li><a class="dropdown-item fw-semibold" href="{{ route('user.profile') }}"><i class="fa-solid fa-user-pen me-2 text-primary"></i>Hồ sơ cá nhân</a></li>
                                <li><button type="button" class="dropdown-item fw-semibold" data-bs-toggle="modal" data-bs-target="#wishlistModal"><i class="fa-solid fa-heart me-2 text-danger"></i>Sản phẩm yêu thích</button></li>
                                <li><a class="dropdown-item fw-semibold" href="{{ route('user.orders.index') }}"><i class="fa-solid fa-receipt me-2 text-primary"></i>Lịch sử đơn hàng</a></li>
                                <li><a class="dropdown-item fw-semibold" href="#chat-box"><i class="fa-solid fa-comments me-2 text-primary"></i>Chat trực tuyến</a></li>
                                <li><a class="dropdown-item fw-semibold" href="{{ route('faq') }}"><i class="fa-solid fa-circle-question me-2 text-primary"></i>Câu hỏi thường gặp</a></li>
                                <li><hr class="dropdown-divider"></li>
                                @if(Auth::user()->isAdmin() || Auth::user()->isEditor() || Auth::user()->isManager())
                                    <li><a class="dropdown-item fw-semibold" href="{{ route('categories.index') }}">Quản lý danh mục</a></li>
                                    <li><a class="dropdown-item fw-semibold" href="{{ route('products.index') }}">Quản lý sản phẩm</a></li>
                                    <li><hr class="dropdown-divider"></li>
                                @endif
                                <li>
                                    <form action="{{ route('logout') }}" method="POST" class="px-2">
                                        @csrf
                                        <button type="submit" class="btn btn-danger btn-sm w-100 fw-bold"><i class="fa-solid fa-sign-out me-1"></i>Đăng xuất</button>
                                    </form>
                                </li>
                            </ul>
                        </div>
                    @else
                        <a href="{{ route('login') }}" class="btn btn-md btn-primary fw-extrabold px-3 py-2">Đăng nhập</a>
                    @endauth
                </div>
            </div>
        </div>
        <div class="container">
            <nav class="store-nav" aria-label="Điều hướng cửa hàng">
                <span class="nav-label"><i class="fa-solid fa-bars me-2" aria-hidden="true"></i> KHÁM PHÁ 247</span>
                <a href="#catalog">Tất cả sản phẩm</a>
                <a href="{{ route('user.orders.index') }}">Đơn hàng của tôi</a>
                <a href="{{ route('faq') }}">Hướng dẫn mua hàng</a>
                <a href="#chat-box">Chat trực tuyến</a>
            </nav>
        </div>
    </header>

    <section class="container ride-hero" aria-labelledby="hero-title">
        <div class="ride-hero-inner">
            <div class="ride-copy">
                <span class="eyebrow">Phụ kiện xe máy / Phong cách của bạn</span>
                <h1 id="hero-title">Nâng chất xe.<br><em>Đậm chất bạn.</em></h1>
                <p>Từ những chi tiết nhỏ đến trải nghiệm khác biệt. Khám phá phụ tùng và phụ kiện cho hành trình mang dấu ấn riêng.</p>
                <div class="ride-actions">
                    <a href="#catalog" class="btn">Khám phá sản phẩm <i class="fa-solid fa-arrow-right ms-2" aria-hidden="true"></i></a>
                    <a href="{{ route('faq') }}">Tư vấn mua hàng</a>
                </div>
            </div>
            <div class="ride-visual">
                @php
                    $featuredProduct = $products->first();
                @endphp
                @if($featuredProduct)
                    <a class="hero-feature" href="{{ route('products.show', $featuredProduct) }}">
                        <small>Khám phá tại 247</small>
                        @if($featuredProduct->image)
                            <img src="{{ asset($featuredProduct->image) }}" alt="{{ $featuredProduct->name }}">
                        @else
                            <div class="hero-placeholder"><i class="fa-solid fa-motorcycle" aria-hidden="true"></i></div>
                        @endif
                        <strong>{{ $featuredProduct->name }}</strong>
                        <div class="feature-bottom"><span>{{ number_format($featuredProduct->price, 0, ',', '.') }}₫</span><i class="fa-solid fa-arrow-up-right-from-square" aria-hidden="true"></i></div>
                    </a>
                @else
                    <div class="hero-feature"><small>Phụ kiện xe máy 247</small><div class="hero-placeholder"><i class="fa-solid fa-motorcycle" aria-hidden="true"></i></div><strong>Sẵn sàng cho hành trình mới.</strong></div>
                @endif
                <span class="hero-caption">YOUR RIDE. YOUR STYLE.</span>
            </div>
        </div>
    </section>

    <section class="container">
        <div class="benefit-section">
            <div class="mb-4">
                <div class="text-uppercase text-primary fw-bold small mb-1">Vì sao chọn chúng tôi</div>
                <h3 class="fw-extrabold text-dark mb-0">Lý do khách hàng tin tưởng 247</h3>
            </div>
            <div class="benefit-grid">
                <div class="benefit-card">
                    <i class="fa-solid fa-shield-halved"></i>
                    <strong>Thông tin rõ ràng</strong>
                    <span>Xem thông số, giá và tình trạng hàng trước khi lựa chọn.</span>
                </div>
                <div class="benefit-card">
                    <i class="fa-solid fa-truck-fast"></i>
                    <strong>Giao hàng nhanh</strong>
                    <span>Giao hàng qua GHN, phí vận chuyển hiển thị khi đặt hàng.</span>
                </div>
                <div class="benefit-card">
                    <i class="fa-solid fa-headset"></i>
                    <strong>Tư vấn lựa chọn</strong>
                    <span>Đội ngũ tư vấn chuyên nghiệp, phản hồi nhanh qua chat và hotline.</span>
                </div>
                <div class="benefit-card">
                    <i class="fa-solid fa-wallet"></i>
                    <strong>Thanh toán dễ</strong>
                    <span>Tiện ích thanh toán online, đặt hàng nhanh, theo dõi đơn hàng rõ ràng.</span>
                </div>
            </div>
        </div>
    </section>

    <!-- STOREFRONT MAIN CONTENT -->
    <main class="container" id="catalog">
        <div class="catalog-heading">
            <div><span class="eyebrow">Trang bị cho hành trình</span><h2>Chọn chất riêng cho xe.</h2></div>
            <p>Tìm phụ kiện phù hợp, theo cách của bạn.</p>
        </div>
        <!-- CATEGORIES FILTER CHIPS -->
        <div class="mb-4">
            <div class="d-flex align-items-center justify-content-between mb-3">
                <h4 class="fw-bold m-0 text-dark"><i class="fa-solid fa-tags text-primary me-2"></i> Danh Mục Phụ Tùng</h4>
                <a href="{{ route('cart.index') }}" class="btn btn-sm btn-outline-primary fw-bold rounded-pill px-3">
                    Xem giỏ hàng &rsaquo;
                </a>
            </div>

            <form action="{{ route('welcome') }}" method="GET" class="row g-2 align-items-end mb-3 catalog-filter">
                <div class="col-12 col-md-4">
                    <label class="form-label small fw-bold mb-1" for="filter-q">Từ khóa</label>
                    <input id="filter-q" name="q" value="{{ request('q') }}" class="form-control" placeholder="Tên sản phẩm hoặc danh mục">
                </div>
                <div class="col-6 col-md-2">
                    <label class="form-label small fw-bold mb-1" for="min-price">Giá từ</label>
                    <input id="min-price" name="min_price" value="{{ request('min_price') }}" type="number" min="0" class="form-control" placeholder="0">
                </div>
                <div class="col-6 col-md-2">
                    <label class="form-label small fw-bold mb-1" for="max-price">Giá đến</label>
                    <input id="max-price" name="max_price" value="{{ request('max_price') }}" type="number" min="0" class="form-control" placeholder="Không giới hạn">
                </div>
                <div class="col-8 col-md-2">
                    <label class="form-label small fw-bold mb-1" for="filter-category">Danh mục</label>
                    <select id="filter-category" name="category" class="form-select">
                        <option value="">Tất cả</option>
                        @foreach($categories as $cat)
                            <option value="{{ $cat->id }}" {{ (string) request('category') === (string) $cat->id ? 'selected' : '' }}>{{ $cat->name }}</option>
                        @endforeach
                    </select>
                </div>
                <div class="col-4 col-md-2">
                    <button type="submit" class="btn btn-primary w-100"><i class="fa-solid fa-filter me-1"></i>Lọc</button>
                </div>
                <div class="col-12">
                    <label class="form-check-label small text-muted">
                        <input class="form-check-input me-1" type="checkbox" name="availability" value="in_stock" {{ request('availability') === 'in_stock' ? 'checked' : '' }}>
                        Chỉ hiển thị sản phẩm còn hàng
                    </label>
                </div>
            </form>
            <div class="d-flex flex-wrap">
                <button class="cat-chip active" onclick="filterCat('all', this)">
                    <i class="fa-solid fa-border-all me-1"></i> Tất cả sản phẩm
                </button>
                @foreach($categories as $cat)
                    <button class="cat-chip" onclick="filterCat('cat-{{ $cat->id }}', this)">
                        <i class="fa-solid fa-tag me-1"></i> {{ $cat->name }}
                    </button>
                @endforeach
            </div>
        </div>

        <!-- PRODUCT GRID -->
        <div class="row g-4" id="productContainer">
            @forelse($products as $product)
                <div class="col-6 col-md-4 col-lg-3 prod-col cat-{{ $product->category_id }}" data-name="{{ strtolower($product->name) }}">
                    <div class="product-card">
                        <div class="product-thumb">
                            <span class="cat-tag">{{ $product->category->name ?? 'Phụ kiện' }}</span>
                            @auth
                                <form action="{{ route('user.wishlist.store', $product) }}" method="POST" style="position:absolute; right:10px; top:10px; z-index:2;">
                                    @csrf
                                    <button type="submit" class="btn btn-light rounded-circle shadow-sm p-0" style="width:34px;height:34px;font-size:.85rem;" title="Thêm vào yêu thích" aria-label="Thêm vào yêu thích"><i class="fa-regular fa-heart text-danger"></i></button>
                                </form>
                            @endauth
                            @if(!empty($product->image))
                                <img src="{{ asset($product->image) }}" alt="{{ $product->name }}" loading="lazy">
                            @else
                                @php
                                    $iconClass = match($product->category_id ?? 0) {
                                        1 => 'fa-compact-disc',
                                        2 => 'fa-sliders',
                                        3 => 'fa-lightbulb',
                                        4 => 'fa-volume-high',
                                        5 => 'fa-circle-notch',
                                        6 => 'fa-gear',
                                        default => 'fa-motorcycle'
                                    };
                                @endphp
                                <i class="fa-solid {{ $iconClass }}"></i>
                            @endif
                        </div>

                        <div class="product-details">
                            <h5 class="product-name" title="{{ $product->name }}">
                                @if($product instanceof \App\Models\Product)
                                    <a href="{{ route('products.show', $product) }}" class="text-decoration-none text-dark">{{ $product->name }}</a>
                                @else
                                    {{ $product->name }}
                                @endif
                            </h5>
                            <p class="product-desc">{{ $product->description ?: 'Sản phẩm phụ kiện xe máy chính hãng chất lượng cao.' }}</p>
                            
                            <div class="d-flex align-items-center justify-content-between mt-auto mb-2">
                                <div class="product-price">{{ number_format($product->price, 0, ',', '.') }}₫</div>
                                <span class="badge bg-success-subtle text-success border border-success-subtle fw-bold px-2 py-1">Còn {{ $product->stock }} SP</span>
                            </div>

                            <!-- NÚT THÊM VÀO GIỎ DÙNG DATA-* ĐỂ SẠCH LỖI JAVASCRIPT VS CODE -->
                            <button type="button" class="btn-add-cart-prominent btn-add-cart-action"
                                data-id="{{ $product->id }}"
                                data-name="{{ $product->name }}"
                                data-price="{{ $product->price }}"
                                data-category="{{ $product->category->name ?? 'Phụ kiện' }}"
                                data-stock="{{ $product->stock }}" @disabled($product->stock <= 0)>
                                <i class="fa-solid fa-cart-plus fs-6"></i>
                                {{ $product->stock > 0 ? 'Thêm vào giỏ' : 'Tạm hết hàng' }}
                            </button>
                        </div>
                    </div>
                </div>
            @empty
                <div class="col-12 text-center py-5">
                    <p class="text-muted mb-0 fs-5">Chưa có sản phẩm nào trong hệ thống.</p>
                </div>
            @endforelse
        </div>

        @if($products instanceof \Illuminate\Contracts\Pagination\Paginator)
            <div class="d-flex justify-content-center mt-4">{{ $products->links('pagination::bootstrap-5') }}</div>
        @endif
        @auth
            @if($recentlyViewed->isNotEmpty())
                <section class="mt-5 mb-4">
                    <div class="d-flex justify-content-between align-items-center mb-3">
                        <h4 class="fw-bold m-0"><i class="fa-solid fa-clock-rotate-left text-primary me-2"></i>Sản phẩm bạn đã xem</h4>
                    </div>
                    <div class="row g-3">
                        @foreach($recentlyViewed->take(4) as $viewedProduct)
                            <div class="col-6 col-md-3">
                                <a href="{{ route('products.show', $viewedProduct) }}" class="text-decoration-none">
                                    <div class="bg-white border rounded-3 p-3 h-100">
                                        <div class="fw-bold text-dark">{{ $viewedProduct->name }}</div>
                                        <div class="text-primary fw-bold mt-2">{{ number_format($viewedProduct->price, 0, ',', '.') }}đ</div>
                                    </div>
                                </a>
                            </div>
                        @endforeach
                    </div>
                </section>
            @endif
        @endauth
    </main>

    @auth
        <div class="modal fade" id="wishlistModal" tabindex="-1" aria-labelledby="wishlistModalLabel" aria-hidden="true">
            <div class="modal-dialog modal-dialog-scrollable modal-lg">
                <div class="modal-content border-0 shadow-lg">
                    <div class="modal-header bg-danger text-white">
                        <div>
                            <h5 class="modal-title" id="wishlistModalLabel"><i class="fa-solid fa-heart me-2"></i>Sản phẩm yêu thích</h5>
                            <small class="text-white-50">Xem và xóa sản phẩm ngay trên trang chủ</small>
                        </div>
                        <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal" aria-label="Đóng"></button>
                    </div>
                    <div class="modal-body">
                        @forelse($wishlistItems as $wishlist)
                            @if($wishlist->product)
                                <div class="d-flex align-items-center gap-3 border-bottom py-3">
                                    <div class="rounded-3 bg-light d-flex align-items-center justify-content-center flex-shrink-0" style="width:72px;height:72px;">
                                        @if(!empty($wishlist->product->image))
                                            <img src="{{ asset($wishlist->product->image) }}" alt="{{ $wishlist->product->name }}" style="width:100%;height:100%;object-fit:cover;border-radius:10px;">
                                        @else
                                            <i class="fa-solid fa-motorcycle text-primary fs-3"></i>
                                        @endif
                                    </div>
                                    <div class="flex-grow-1">
                                        <div class="fw-bold">{{ $wishlist->product->name }}</div>
                                        <small class="text-muted">{{ $wishlist->product->category->name ?? 'Phụ kiện' }}</small>
                                        <div class="text-primary fw-bold">{{ number_format($wishlist->product->price, 0, ',', '.') }}₫</div>
                                    </div>
                                    <form action="{{ route('user.wishlist.destroy', $wishlist->product) }}" method="POST">
                                        @csrf
                                        @method('DELETE')
                                        <button type="submit" class="btn btn-outline-danger btn-sm" title="Xóa khỏi yêu thích" aria-label="Xóa khỏi yêu thích"><i class="fa-solid fa-trash"></i></button>
                                    </form>
                                </div>
                            @endif
                        @empty
                            <div class="text-center text-muted py-5">
                                <i class="fa-regular fa-heart fs-1 mb-3"></i>
                                <p class="mb-0">Bạn chưa lưu sản phẩm nào.</p>
                            </div>
                        @endforelse
                    </div>
                    <div class="modal-footer"><button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Đóng</button></div>
                </div>
            </div>
        </div>
    @endauth

    <!-- FOOTER -->
    @include('partials.store-footer')


    <!-- Bootstrap 5 JS -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js"></script>

    <script>
        const CART_KEY = 'lar_accessories_cart';

        function getCart() {
            try {
                const stored = localStorage.getItem(CART_KEY);
                return stored ? JSON.parse(stored) : [];
            } catch (e) {
                return [];
            }
        }

        function saveCart(cart) {
            localStorage.setItem(CART_KEY, JSON.stringify(cart));
            window.dispatchEvent(new CustomEvent('cart:changed', { detail: { items: cart } }));
            updateHeaderCart();
        }

        function addToCart(id, name, price, category, stock) {
            if (stock <= 0) return;
            let cart = getCart();
            let item = cart.find(i => i.id === id);

            if (item) {
                if (item.quantity >= stock) {
                    Swal.fire({ icon: 'warning', title: 'Thông báo Kho', text: `Số lượng trong kho chỉ còn ${stock} sản phẩm.` });
                    return;
                }
                item.quantity += 1;
            } else {
                cart.push({ id, name, price, category, stock, quantity: 1, checked: true });
            }

            saveCart(cart);

            Swal.fire({
                toast: true,
                position: 'top-end',
                icon: 'success',
                title: `Đã thêm "${name}" vào giỏ hàng!`,
                showConfirmButton: false,
                timer: 2000,
                timerProgressBar: true
            });
        }

        function updateHeaderCart() {
            const cart = getCart();
            const count = cart.reduce((sum, item) => sum + item.quantity, 0);
            const el = document.getElementById('headerCartCount');
            if (el) el.textContent = count;
        }

        function filterCat(catClass, btn) {
            document.querySelectorAll('.cat-chip').forEach(b => b.classList.remove('active'));
            btn.classList.add('active');
            document.querySelectorAll('.prod-col').forEach(col => {
                if (catClass === 'all' || col.classList.contains(catClass)) {
                    col.style.display = '';
                } else {
                    col.style.display = 'none';
                }
            });
        }

        document.getElementById('searchInput')?.addEventListener('input', function() {
            const q = this.value.toLowerCase().trim();
            document.querySelectorAll('.prod-col').forEach(col => {
                const name = col.getAttribute('data-name') || '';
                col.style.display = name.includes(q) ? '' : 'none';
            });
        });

        // LẮNG NGHE SỰ KIỆN THÊM VÀO GIỎ HÀNG QUA DATA-*
        document.addEventListener('click', function(e) {
            const btn = e.target.closest('.btn-add-cart-action');
            if (btn) {
                const id = Number(btn.dataset.id);
                const name = btn.dataset.name;
                const price = Number(btn.dataset.price);
                const category = btn.dataset.category;
                const stock = Number(btn.dataset.stock);
                addToCart(id, name, price, category, stock);
            }
        });

        document.addEventListener('DOMContentLoaded', function () {
            updateHeaderCart();
        });
    </script>
</body>
</html>
