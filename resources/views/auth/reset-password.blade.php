@extends('layouts.auth')

@section('title', 'Đặt lại mật khẩu')

@section('content')
<div class="card-auth">
        <h1 class="fw-bold text-dark text-center mb-1">Tạo mật khẩu mới</h1>
        <p class="text-muted small text-center mb-4">Vui lòng thiết lập mật khẩu an toàn tối thiểu 8 ký tự.</p>

        @if ($errors->any())
            <div class="alert alert-danger border-0 small mb-3">{{ $errors->first() }}</div>
        @endif

        <form action="{{ route('password.update') }}" method="POST">
            @csrf
            <input type="hidden" name="token" value="{{ $token }}">

            <div class="mb-3">
                <label for="email" class="form-label small fw-bold">Email</label>
                <input autocomplete="email" type="email" id="email" name="email" value="{{ old('email', $email) }}" class="form-control" required readonly>
            </div>
            <div class="mb-3">
                <label for="password" class="form-label small fw-bold">Mật khẩu mới</label>
                <input autocomplete="new-password" type="password" id="password" name="password" class="form-control" placeholder="Tối thiểu 8 ký tự" required>
            </div>
            <div class="mb-3">
                <label for="password_confirmation" class="form-label small fw-bold">Xác nhận mật khẩu</label>
                <input autocomplete="new-password" type="password" id="password_confirmation" name="password_confirmation" class="form-control" placeholder="Nhập lại mật khẩu mới" required>
            </div>
            <button type="submit" class="btn btn-primary w-100 fw-bold py-2 rounded-3">Cập nhật mật khẩu</button>
        </form>
    </div>
@endsection
