<!DOCTYPE html>
<html lang="vi">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Đăng Nhập | PHỤ KIỆN XE MÁY 247</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.0/css/all.min.css">
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700;800&display=swap" rel="stylesheet">
    <style>
        body {
            background-color: #f0f4f8;
            font-family: 'Inter', sans-serif;
            min-height: 100vh;
            display: flex;
            align-items: center;
            justify-content: center;
            padding: 20px;
            margin: 0;
        }
        .login-container {
            background: white;
            border-radius: 16px;
            box-shadow: 0 4px 20px rgba(0, 0, 0, 0.06);
            border: 1px solid #f1f5f9;
            max-width: 420px;
            width: 100%;
            padding: 36px;
        }
        .brand-header {
            text-align: center;
            margin-bottom: 24px;
        }
        .brand-icon {
            width: 52px; height: 52px;
            background: linear-gradient(135deg, #3b82f6, #2563eb);
            border-radius: 14px;
            display: inline-flex; align-items: center; justify-content: center;
            color: #fff; font-size: 1.4rem;
            margin-bottom: 12px;
            box-shadow: 0 4px 12px rgba(37,99,235,0.25);
        }
        .brand-title {
            font-size: 1.2rem;
            font-weight: 800;
            color: #0f172a;
            letter-spacing: 0.5px;
        }
        .brand-sub {
            font-size: 0.8rem;
            color: #64748b;
        }
        .form-group {
            margin-bottom: 18px;
        }
        label {
            font-size: 0.85rem;
            font-weight: 600;
            color: #334155;
            display: block;
        }
        .form-control {
            border: 1.5px solid #e2e8f0;
            border-radius: 10px;
            padding: 10px 14px;
            font-size: 0.9rem;
            background-color: #fafbfc;
        }
        .form-control:focus {
            border-color: #3b82f6;
            box-shadow: 0 0 0 3px rgba(59, 130, 246, 0.12);
            background-color: #fff;
        }
        .forgot-link {
            font-size: 0.8rem;
            color: #2563eb;
            text-decoration: none;
            font-weight: 600;
            transition: color 0.15s;
        }
        .forgot-link:hover {
            color: #1d4ed8;
            text-decoration: underline;
        }
        .btn-login {
            width: 100%;
            padding: 11px;
            background: linear-gradient(135deg, #3b82f6, #2563eb);
            color: white;
            border: none;
            border-radius: 10px;
            font-weight: 700;
            font-size: 0.92rem;
            cursor: pointer;
            margin-top: 10px;
            transition: all 0.2s;
            box-shadow: 0 4px 12px rgba(37,99,235,0.25);
        }
        .btn-login:hover {
            background: linear-gradient(135deg, #2563eb, #1d4ed8);
            transform: translateY(-1px);
        }
        .register-link {
            text-align: center;
            margin-top: 20px;
            font-size: 0.88rem;
            color: #64748b;
        }
        .register-link a {
            color: #2563eb;
            text-decoration: none;
            font-weight: 600;
        }
        .register-link a:hover {
            text-decoration: underline;
        }
    </style>
</head>
<body>
    <div class="login-container">
        <div class="brand-header">
            <div class="brand-icon"><i class="fa-solid fa-motorcycle"></i></div>
            <div class="brand-title">PHỤ KIỆN XE MÁY 247</div>
            <div class="brand-sub">Đăng nhập tài khoản hệ thống</div>
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
                <input type="email" name="email" id="email" class="form-control @error('email') is-invalid @enderror" value="{{ old('email') }}" placeholder="name@example.com" required autofocus>
                @error('email')
                    <div class="invalid-feedback d-block">{{ $message }}</div>
                @enderror
            </div>

            <div class="form-group">
                <!-- ĐOẠN HIỂN THỊ NÚT QUÊN MẬT KHẨU -->
                <div class="d-flex justify-content-between align-items-center mb-2">
                    <label for="password" class="mb-0">Mật khẩu</label>
                    <a href="{{ route('password.request') }}" class="forgot-link">Quên mật khẩu?</a>
                </div>
                <input type="password" name="password" id="password" class="form-control @error('password') is-invalid @enderror" placeholder="••••••••" required>
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

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>