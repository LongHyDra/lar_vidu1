@extends('layouts.auth')

@section('title', 'Đăng nhập')

@section('content')
<div class="login-container">
    <div class="brand-header">
        <span class="eyebrow">Tài khoản của bạn</span>
        <h1>Chào mừng trở lại.</h1>
        <p class="brand-sub">Đăng nhập để tiếp tục hành trình cùng 247.</p>
    </div>

    @if (session('error'))
        <div class="alert alert-danger border-0 small mb-3">
            <i class="fa-solid fa-circle-exclamation me-1"></i> {{ session('error') }}
        </div>
    @endif

    @if (session('success'))
        <div class="alert alert-success border-0 small mb-3">
            <i class="fa-solid fa-circle-check me-1"></i> {{ session('success') }}
        </div>
    @endif

    @if (session('info'))
        <div class="alert alert-info border-0 small mb-3">
            <i class="fa-solid fa-circle-info me-1"></i> {{ session('info') }}
        </div>
    @endif

    @if (session('warning'))
        <div class="alert alert-warning border-0 small mb-3">
            <i class="fa-solid fa-triangle-exclamation me-1"></i> {{ session('warning') }}
        </div>
    @endif

    @if ($errors->any())
        <div class="alert alert-danger border-0 small mb-3">
            <ul class="mb-0 ps-3">
                @foreach ($errors->all() as $error)
                    <li>{{ $error }}</li>
                @endforeach
            </ul>
        </div>
    @endif

    <form action="{{ url('login') }}" method="POST">
        @csrf

        <div class="form-group">
            <label for="email" class="mb-2">Email</label>
            <input autocomplete="email" type="email" name="email" id="email" class="form-control @error('email') is-invalid @enderror" value="{{ old('email') }}" placeholder="name@example.com" required autofocus>
            @error('email')
                <div class="invalid-feedback d-block">{{ $message }}</div>
            @enderror
        </div>

        <div class="form-group">
            <div class="d-flex justify-content-between align-items-center mb-2">
                <label for="password" class="mb-0">Mật khẩu</label>
                <a href="{{ route('password.request') }}" class="forgot-link">Quên mật khẩu?</a>
            </div>
            <input autocomplete="current-password" type="password" name="password" id="password" class="form-control @error('password') is-invalid @enderror" placeholder="••••••••" required>
            @error('password')
                <div class="invalid-feedback d-block">{{ $message }}</div>
            @enderror
        </div>

        <button type="submit" class="btn btn-login">
            <i class="fa-solid fa-right-to-bracket me-1"></i> Đăng Nhập
        </button>
    </form>

    <div class="register-link">
        Chưa có tài khoản? <a href="{{ route('register') }}">Đăng ký ngay</a>
    </div>
</div>
@endsection
