<div class="admin-mobile-bar">
    <a class="account-brand" href="{{ route('welcome') }}">PHỤ KIỆN <span>247</span></a>
    <button type="button" class="btn btn-outline-dark" id="adminMenuToggle" aria-controls="adminNavigation" aria-expanded="false"><i class="fa-solid fa-bars me-2" aria-hidden="true"></i>Menu quản trị</button>
</div>
<aside class="sidebar admin-sidebar" id="adminNavigation" aria-label="Điều hướng quản lý">
    <a href="{{ route('welcome') }}" class="sidebar-brand">
        <div class="brand-icon-inner"><i class="fa-solid fa-motorcycle" aria-hidden="true"></i></div>
        <div><div class="brand-text">PHỤ KIỆN XE MÁY <span class="badge-247-sm">247</span></div><small>KHÔNG GIAN QUẢN LÝ</small></div>
    </a>
    <nav class="sidebar-nav">
        @if(auth()->user()?->isAdmin())
            <div class="nav-label">Tổng quan</div>
            <a class="nav-item-link {{ request()->routeIs('admin.dashboard') && request('section', 'overview') === 'overview' ? 'active' : '' }}" href="{{ route('admin.dashboard') }}"><span class="nav-icon"><i class="fa-solid fa-chart-line" aria-hidden="true"></i></span>Bảng điều khiển</a>
        @endif
        <div class="nav-label">Cửa hàng của bạn</div>
        <a class="nav-item-link {{ request()->routeIs('products.*') ? 'active' : '' }}" href="{{ route('products.index') }}"><span class="nav-icon"><i class="fa-solid fa-box" aria-hidden="true"></i></span>Sản phẩm</a>
        <a class="nav-item-link {{ request()->routeIs('categories.*') ? 'active' : '' }}" href="{{ route('categories.index') }}"><span class="nav-icon"><i class="fa-solid fa-tags" aria-hidden="true"></i></span>Danh mục</a>
        @if(auth()->user()?->isAdmin())
            <a class="nav-item-link {{ request()->routeIs('admin.dashboard') && request('section') === 'orders' ? 'active' : '' }}" href="{{ route('admin.dashboard', ['section' => 'orders']) }}"><span class="nav-icon"><i class="fa-solid fa-receipt" aria-hidden="true"></i></span>Đơn hàng</a>
            <a class="nav-item-link {{ request()->routeIs('admin.inventory.*') || (request()->routeIs('admin.dashboard') && request('section') === 'inventory') ? 'active' : '' }}" href="{{ route('admin.dashboard', ['section' => 'inventory']) }}"><span class="nav-icon"><i class="fa-solid fa-warehouse" aria-hidden="true"></i></span>Tồn kho</a>
            <div class="nav-label">Tài chính &amp; khách hàng</div>
            <a class="nav-item-link {{ request()->routeIs('admin.finance.index') ? 'active' : '' }}" href="{{ route('admin.finance.index') }}"><span class="nav-icon"><i class="fa-solid fa-chart-pie" aria-hidden="true"></i></span>Thống kê tài chính</a>
            <a class="nav-item-link {{ request()->routeIs('admin.finance.transactions', 'admin.payment-transactions.*') ? 'active' : '' }}" href="{{ route('admin.finance.transactions') }}"><span class="nav-icon"><i class="fa-solid fa-money-check-dollar" aria-hidden="true"></i></span>Giao dịch thanh toán</a>
            <a class="nav-item-link {{ request()->routeIs('admin.users.*') || (request()->routeIs('admin.dashboard') && request('section') === 'users') ? 'active' : '' }}" href="{{ route('admin.dashboard', ['section' => 'users']) }}"><span class="nav-icon"><i class="fa-solid fa-users" aria-hidden="true"></i></span>Người dùng</a>
            <a class="nav-item-link {{ request()->routeIs('admin.tickets.*') ? 'active' : '' }}" href="{{ route('admin.tickets.index') }}"><span class="nav-icon"><i class="fa-solid fa-headset" aria-hidden="true"></i></span>Yêu cầu hỗ trợ</a>
            <a class="nav-item-link {{ request()->routeIs('admin.coupons.*') ? 'active' : '' }}" href="{{ route('admin.coupons.index') }}"><span class="nav-icon"><i class="fa-solid fa-ticket" aria-hidden="true"></i></span>Mã giảm giá</a>
            <a class="nav-item-link {{ request()->routeIs('admin.reviews.*') ? 'active' : '' }}" href="{{ route('admin.reviews.index') }}"><span class="nav-icon"><i class="fa-solid fa-star" aria-hidden="true"></i></span>Đánh giá</a>
            <a class="nav-item-link {{ request()->routeIs('admin.audits.*') ? 'active' : '' }}" href="{{ route('admin.audits.index') }}"><span class="nav-icon"><i class="fa-solid fa-shield-halved" aria-hidden="true"></i></span>Nhật ký</a>
        @endif
        <div class="nav-label">Liên kết nhanh</div>
        <a class="nav-item-link" href="{{ route('welcome') }}"><span class="nav-icon"><i class="fa-solid fa-arrow-up-right-from-square" aria-hidden="true"></i></span>Xem cửa hàng</a>
    </nav>
    @auth
        <div class="sidebar-footer">
            <div class="sidebar-avatar">{{ mb_strtoupper(mb_substr(auth()->user()->name, 0, 1)) }}</div>
            <div class="sidebar-user-info"><div class="sidebar-user-name">{{ auth()->user()->name }}</div><div class="sidebar-user-role">{{ auth()->user()->isAdmin() ? 'Quản trị viên' : 'Nhân viên cửa hàng' }}</div></div>
            <form method="POST" action="{{ route('logout') }}">@csrf<button class="sidebar-logout" type="submit" aria-label="Đăng xuất" title="Đăng xuất"><i class="fa-solid fa-arrow-right-from-bracket" aria-hidden="true"></i></button></form>
        </div>
    @endauth
</aside>
