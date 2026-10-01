@extends('layouts.auth')

@section('title', 'Đăng ký')

@section('content')
<div class="register-container">
        <div class="brand-header"><span class="eyebrow">Tài khoản của bạn</span><h1>Bắt đầu hành trình.</h1><p class="brand-sub">Tạo tài khoản để lưu lựa chọn và theo dõi đơn hàng.</p></div>

        @if (session('error'))
            <div class="alert alert-danger">
                {{ session('error') }}
            </div>
        @endif

        @if ($errors->any())
            <div class="alert alert-danger">
                <ul class="error-list mb-0">
                    @foreach ($errors->all() as $error)
                        <li>{{ $error }}</li>
                    @endforeach
                </ul>
            </div>
        @endif

        <form action="{{ url('register') }}" method="POST">
            @csrf
            
            <div class="form-group">
                <label for="name">Họ tên</label>
                <input autocomplete="name" type="text" name="name" id="name" class="form-control @error('name') is-invalid @enderror" value="{{ old('name') }}" required>
                @error('name')
                    <div class="invalid-feedback" style="display: block;">{{ $message }}</div>
                @enderror
            </div>

            <div class="form-group">
                <label for="email">Email</label>
                <input autocomplete="email" type="email" name="email" id="email" class="form-control @error('email') is-invalid @enderror" value="{{ old('email') }}" required>
                @error('email')
                    <div class="invalid-feedback" style="display: block;">{{ $message }}</div>
                @enderror
            </div>

            <div class="form-group">
                <label for="password">Mật khẩu</label>
                <input autocomplete="new-password" type="password" name="password" id="password" class="form-control @error('password') is-invalid @enderror" required>
                @error('password')
                    <div class="invalid-feedback" style="display: block;">{{ $message }}</div>
                @enderror
            </div>

            <div class="form-group">
                <label for="password_confirmation">Xác nhận mật khẩu</label>
                <input autocomplete="new-password" type="password" name="password_confirmation" id="password_confirmation" class="form-control @error('password_confirmation') is-invalid @enderror" required>
                @error('password_confirmation')
                    <div class="invalid-feedback" style="display: block;">{{ $message }}</div>
                @enderror
            </div>

            <button type="submit" class="btn btn-primary btn-register">Đăng Ký</button>
        </form>

        <div class="login-link">
            Đã có tài khoản? <a href="{{ route('login') }}">Đăng nhập</a>
        </div>
    </div>
@endsection
