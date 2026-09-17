<!DOCTYPE html>
<html lang="vi">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Hồ sơ người dùng</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
    <style>
        body { background: #f8fafc; font-family: Arial, sans-serif; }
        .container { max-width: 900px; margin: 40px auto; }
        .card { background: #fff; border-radius: 16px; padding: 24px; box-shadow: 0 8px 24px rgba(15, 23, 42, 0.06); }
        .header { margin-bottom: 24px; }
        .alert { margin-bottom: 20px; }
        .form-label { font-weight: 600; }
        .btn-primary { background: #2563eb; border-color: #2563eb; }
        .btn-link { text-decoration: none; }
    </style>
</head>
<body>
<div class="container">
    <div class="card">
        <div class="header">
            <h2>Hồ sơ cá nhân</h2>
            <a href="{{ route('welcome') }}" class="btn btn-link text-decoration-none">← Về trang chủ</a>
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
</body>
</html>
