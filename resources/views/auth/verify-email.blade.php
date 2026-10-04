@extends('layouts.auth')

@section('title', 'Xác thực email')

@section('content')
<div class="verify-card">
        <div class="icon-circle">
            <i class="fa-regular fa-envelope-open"></i>
        </div>
        <h1 class="fw-bold text-dark mb-2">Xác thực tài khoản của bạn</h1>
        <p class="text-muted small mb-4">
            Cảm ơn bạn đã đăng ký tại <strong>PHỤ KIỆN XE MÁY 247</strong>. Trước khi bắt đầu mua hàng và thanh toán, vui lòng kiểm tra email <strong>{{ Auth::user()->email }}</strong> và nhấn vào liên kết xác thực chúng tôi vừa gửi.
        </p>

        @if (session('success'))
            <div class="alert alert-success border-0 small text-start mb-4" role="alert">
                <i class="fa-solid fa-circle-check me-2"></i>{{ session('success') }}
            </div>
        @endif

        @if (session('warning'))
            <div class="alert alert-warning border-0 small text-start mb-4" role="alert">
                <i class="fa-solid fa-triangle-exclamation me-2"></i>{{ session('warning') }}
            </div>
        @endif

        <div class="d-flex flex-column gap-2">
            <form method="POST" action="{{ route('verification.send') }}">
                @csrf
                <button type="submit" class="btn btn-primary w-100 fw-bold py-2 rounded-3">
                    <i class="fa-solid fa-paper-plane me-2"></i>Gửi lại link xác thực
                </button>
            </form>

            <form method="POST" action="{{ route('logout') }}">
                @csrf
                <button type="submit" class="btn btn-link text-muted text-decoration-none small">
                    <i class="fa-solid fa-right-from-bracket me-1"></i>Đăng xuất tài khoản
                </button>
            </form>
        </div>
    </div>
@endsection
