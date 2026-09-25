<!DOCTYPE html>
<html lang="vi">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Quên Mật Khẩu | PHỤ KIỆN XE MÁY 247</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.0/css/all.min.css">
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700;800&display=swap" rel="stylesheet">
    <style>
        body { background: #f0f4f8; font-family: 'Inter', sans-serif; display: flex; align-items: center; justify-content: center; min-height: 100vh; padding: 20px; margin: 0; }
        .card-auth { background: #fff; border-radius: 16px; border: 1px solid #f1f5f9; padding: 36px; max-width: 440px; width: 100%; box-shadow: 0 4px 20px rgba(0,0,0,0.06); }
    </style>
</head>
<body>
    <div class="card-auth">
        <div class="text-center mb-4">
            <div class="d-inline-flex p-3 rounded-circle bg-primary bg-opacity-10 text-primary fs-3 mb-2">
                <i class="fa-solid fa-key"></i>
            </div>
            <h4 class="fw-bold text-dark">Quên mật khẩu?</h4>
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
                <label class="form-label small fw-bold text-secondary">Email tài khoản</label>
                <input type="email" name="email" value="{{ old('email') }}" class="form-control" placeholder="user@gmail.com" required autofocus>
            </div>
            <button type="submit" class="btn btn-primary w-100 fw-bold py-2 rounded-3 mb-3">
                <i class="fa-solid fa-paper-plane me-1"></i> Gửi liên kết xác nhận
            </button>
            <div class="text-center">
                <a href="{{ route('login') }}" class="text-decoration-none small text-muted"><i class="fa-solid fa-arrow-left me-1"></i> Quay lại đăng nhập</a>
            </div>
        </form>
    </div>
</body>
</html>