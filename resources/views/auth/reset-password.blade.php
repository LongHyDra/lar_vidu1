<!DOCTYPE html>
<html lang="vi">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Đặt Lại Mật Khẩu | PHỤ KIỆN XE MÁY 247</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700;800&display=swap" rel="stylesheet">
    <style>
        body { background: #f0f4f8; font-family: 'Inter', sans-serif; display: flex; align-items: center; justify-content: center; min-height: 100vh; padding: 20px; margin: 0; }
        .card-auth { background: #fff; border-radius: 16px; border: 1px solid #e2e8f0; padding: 36px; max-width: 440px; width: 100%; box-shadow: 0 4px 20px rgba(0,0,0,0.06); }
    </style>
</head>
<body>
    <div class="card-auth">
        <h4 class="fw-bold text-dark text-center mb-1">Tạo mật khẩu mới</h4>
        <p class="text-muted small text-center mb-4">Vui lòng thiết lập mật khẩu an toàn tối thiểu 8 ký tự.</p>

        @if ($errors->any())
            <div class="alert alert-danger border-0 small mb-3">{{ $errors->first() }}</div>
        @endif

        <form action="{{ route('password.update') }}" method="POST">
            @csrf
            <input type="hidden" name="token" value="{{ $token }}">

            <div class="mb-3">
                <label class="form-label small fw-bold">Email</label>
                <input type="email" name="email" value="{{ old('email', $email) }}" class="form-control" required readonly>
            </div>
            <div class="mb-3">
                <label class="form-label small fw-bold">Mật khẩu mới</label>
                <input type="password" name="password" class="form-control" placeholder="Tối thiểu 8 ký tự" required>
            </div>
            <div class="mb-3">
                <label class="form-label small fw-bold">Xác nhận mật khẩu</label>
                <input type="password" name="password_confirmation" class="form-control" placeholder="Nhập lại mật khẩu mới" required>
            </div>
            <button type="submit" class="btn btn-primary w-100 fw-bold py-2 rounded-3">Cập nhật mật khẩu</button>
        </form>
    </div>
</body>
</html>