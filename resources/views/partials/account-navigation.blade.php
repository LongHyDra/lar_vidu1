<aside class="customer-sidebar">
    <div class="customer-identity"><span class="eyebrow">Không gian của bạn</span><strong>{{ auth()->user()->name }}</strong><small>Đồng hành cùng mọi hành trình.</small></div>
    <nav aria-label="Điều hướng tài khoản">
        @foreach ([['user.profile', 'user.profile', 'user', 'Hồ sơ cá nhân'], ['user.orders.index', 'user.orders.*', 'box', 'Đơn hàng của tôi'], ['user.addresses', 'user.addresses*', 'location-dot', 'Địa chỉ giao hàng'], ['user.wishlist.index', 'user.wishlist.*', 'heart', 'Sản phẩm yêu thích'], ['user.loyalty', 'user.loyalty*', 'gift', 'Điểm thưởng'], ['user.notifications', 'user.notifications*', 'bell', 'Thông báo'], ['user.chat.index', 'user.chat.*', 'comments', 'Chat trực tuyến']] as [$link, $pattern, $icon, $label])
            <a href="{{ route($link) }}" @if(request()->routeIs($pattern)) aria-current="page" @endif><i class="fa-solid fa-{{ $icon }}" aria-hidden="true"></i>{{ $label }}</a>
        @endforeach
    </nav>
    <form method="POST" action="{{ route('logout') }}">@csrf<button class="btn btn-outline-secondary w-100" type="submit">Đăng xuất</button></form>
</aside>
