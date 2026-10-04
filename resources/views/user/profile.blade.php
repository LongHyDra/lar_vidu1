@extends('layouts.storefront')

@section('title', 'Hồ sơ người dùng')

@section('account_content')
<div class="account-page">
    <div class="card">
        <div class="header">
            <h2>Hồ sơ cá nhân</h2>
            <a href="{{ route('welcome') }}" class="btn btn-link text-decoration-none">← Về trang chủ</a>
            <a href="{{ route('user.addresses') }}" class="btn btn-outline-primary btn-sm ms-2">Địa chỉ giao hàng</a>
            <a href="{{ route('user.notifications') }}" class="btn btn-outline-secondary btn-sm ms-2">Thông báo</a>
            <a href="{{ route('user.loyalty') }}" class="btn btn-outline-warning btn-sm ms-2">Điểm thưởng</a>
        </div>

        @if (session('success'))
            <div class="alert alert-success">{{ session('success') }}</div>
        @endif

        <form action="{{ route('user.profile.update') }}" method="POST">
            @csrf
            @method('PUT')

            <div class="mb-3">
                <label for="name" class="form-label">Họ và tên</label>
                <input type="text" class="form-control" id="name" name="name" value="{{ old('name', $user->name) }}" required>
            </div>

            <div class="mb-3">
                <label for="email" class="form-label">Email</label>
                <input type="email" class="form-control" id="email" name="email" value="{{ old('email', $user->email) }}" required>
            </div>

            <div class="mb-3">
                <label for="password" class="form-label">Mật khẩu mới</label>
                <input type="password" class="form-control" id="password" name="password" placeholder="Để trống nếu không đổi mật khẩu">
            </div>

            <div class="mb-3">
                <label for="password_confirmation" class="form-label">Xác nhận mật khẩu mới</label>
                <input type="password" class="form-control" id="password_confirmation" name="password_confirmation">
            </div>

            <button type="submit" class="btn btn-primary">Cập nhật hồ sơ</button>
        </form>
    </div>
</div>
@endsection
