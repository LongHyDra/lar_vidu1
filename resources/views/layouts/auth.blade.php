<!DOCTYPE html>
<html lang="vi">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>@yield('title') | Phụ Kiện Xe Máy 247</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.0/css/all.min.css" rel="stylesheet">
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700;800&display=swap" rel="stylesheet">
    <link href="{{ asset('css/storefront.css') }}" rel="stylesheet">
    <link href="{{ asset('css/account-admin.css') }}" rel="stylesheet">
    <script src="{{ asset('js/account-admin.js') }}" defer></script>
</head>
<body class="auth-theme">
    <header class="auth-topbar">
        <a class="account-brand" href="{{ route('welcome') }}"><i class="fa-solid fa-motorcycle" aria-hidden="true"></i> PHỤ KIỆN XE MÁY <span>247</span></a>
        <a class="auth-back" href="{{ route('welcome') }}">Về cửa hàng <span aria-hidden="true">↗</span></a>
    </header>
    <main class="auth-shell">
        <section class="auth-story" aria-labelledby="auth-story-title">
            <span class="eyebrow">Đồng hành cùng mọi hành trình</span>
            <h2 id="auth-story-title">Chất riêng.<br>Trên từng<br><em>chặng đường.</em></h2>
            <p>Một tài khoản, mọi trải nghiệm. Lưu lựa chọn yêu thích, theo dõi đơn hàng và kết nối với cửa hàng.</p>
            <div class="auth-art" aria-hidden="true"><i class="fa-solid fa-motorcycle"></i><span>247 / RIDE YOUR WAY</span></div>
            <div class="auth-story-bottom"><span>PHỤ KIỆN &amp; PHỤ TÙNG</span><span>YOUR RIDE. YOUR STYLE.</span></div>
        </section>
        <section class="auth-form-panel" aria-label="@yield('title')">
            @yield('content')
        </section>
    </main>
    <footer class="auth-bottom">&copy; {{ date('Y') }} Phụ Kiện Xe Máy 247 <span>Chi tiết nhỏ. Trải nghiệm khác biệt.</span></footer>
</body>
</html>
