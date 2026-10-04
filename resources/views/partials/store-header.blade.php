<header class="store-header">
    <div class="container store-header-inner">
        <a class="store-brand" href="{{ route('welcome') }}"><i class="fa-solid fa-motorcycle" aria-hidden="true"></i><span>PHỤ KIỆN XE MÁY <b>247</b></span></a>
        <nav class="store-header-links" aria-label="Điều hướng cửa hàng">
            <a href="{{ route('welcome') }}#catalog">Cửa hàng</a>
            <a href="{{ route('faq') }}" @if(request()->routeIs('faq')) aria-current="page" @endif>Hướng dẫn</a>
            <a href="{{ route('cart.index') }}" @if(request()->routeIs('cart.index')) aria-current="page" @endif><i class="fa-solid fa-cart-shopping" aria-hidden="true"></i> Giỏ hàng @isset($cartCountId)<span class="cart-count-badge" id="{{ $cartCountId }}">0</span>@endisset</a>
            @auth
                <a class="store-account-link" href="{{ route('user.profile') }}">Tài khoản</a>
                @if(auth()->user()->isAdmin())<a href="{{ route('admin.dashboard') }}">Quản trị</a>@endif
            @else
                <a class="store-account-link" href="{{ route('login') }}">Đăng nhập</a>
            @endauth
        </nav>
    </div>
</header>
