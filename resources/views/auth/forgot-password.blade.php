@extends('layouts.auth')

@section('title', 'Quên mật khẩu')

@section('content')
<div class="card-auth">
        <div class="text-center mb-4">
            <div class="d-inline-flex p-3 rounded-circle bg-primary bg-opacity-10 text-primary fs-3 mb-2">
                <i class="fa-solid fa-key"></i>
            </div>
            <h1 class="fw-bold text-dark">Quên mật khẩu?</h1>
            <p class="text-muted small">Nhập địa chỉ email đăng ký để nhận liên kết đặt lại mật khẩu.</p>
        </div>

        @if (session('success'))
            <div class="alert alert-success border-0 small mb-3">{{ session('success') }}</div>
        @endif

        @if ($errors->any())
            <div class="alert alert-danger border-0 small mb-3">{{ $errors->first() }}</div>
        @endif

        <form action="{{ route('password.email') }}" method="POST">
            @csrf
            <div class="mb-3">
                <label for="email" class="form-label small fw-bold text-secondary">Email tài khoản</label>
                <input autocomplete="email" type="email" id="email" name="email" value="{{ old('email') }}" class="form-control" placeholder="user@gmail.com" required autofocus>
            </div>
            <button type="submit" class="btn btn-primary w-100 fw-bold py-2 rounded-3 mb-3">
                <i class="fa-solid fa-paper-plane me-1"></i> Gửi liên kết xác nhận
            </button>
            <div class="text-center">
                <a href="{{ route('login') }}" class="text-decoration-none small text-muted"><i class="fa-solid fa-arrow-left me-1"></i> Quay lại đăng nhập</a>
            </div>
        </form>
    </div>
@endsection
