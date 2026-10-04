<!DOCTYPE html>
<html lang="vi">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>@yield('title', 'Cửa hàng') | Phụ Kiện Xe Máy 247</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.0/css/all.min.css" rel="stylesheet">
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700;800&display=swap" rel="stylesheet">
    @stack('styles')
    <link href="{{ asset('css/storefront.css') }}" rel="stylesheet">
    <link href="{{ asset('css/account-admin.css') }}" rel="stylesheet">
</head>
<body class="customer-theme">
    <a href="#page-content" class="skip-link">Đến nội dung chính</a>
    @include('partials.store-header')
    <main id="page-content" class="container customer-main">
        @hasSection('account_content')
            <div class="customer-shell">
                @include('partials.account-navigation')
                <div class="customer-content">
                    @include('partials.form-errors')
                    @yield('account_content')
                </div>
            </div>
        @else
            @include('partials.form-errors')
            @yield('content')
        @endif
    </main>
    @include('partials.store-footer-content')
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js"></script>
    <script src="{{ asset('js/account-admin.js') }}" defer></script>
    @stack('scripts')
</body>
</html>
