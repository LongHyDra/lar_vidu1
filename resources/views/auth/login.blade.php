<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Đăng Nhập | PHỤ KIỆN XE MÁY</title>
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
            width: 50px; height: 50px;
            background: linear-gradient(135deg, #3b82f6, #2563eb);
            border-radius: 12px;
            display: inline-flex; align-items: center; justify-content: center;
            color: #fff; font-size: 1.4rem;
            margin-bottom: 12px;
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
            margin-bottom: 6px;
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
        .error-list {
            margin-top: 5px;
        }
        .error-list li {
            font-size: 0.85rem;
            color: #dc3545;
        }
    </style>
</head>
<body>
    <div class="login-container">
        <div class="brand-header">
            <div class="brand-icon"><i class="fa-solid fa-motorcycle"></i></div>
            <div class="brand-title">PHỤ KIỆN XE MÁY</div>
            <div class="brand-sub">Đăng nhập tài khoản hệ thống</div>
        </div>

        @if (session('error'))
            <div class="alert alert-danger">
                {{ session('error') }}
            </div>
        @endif

        @if (session('success'))
            <div class="alert alert-success">
                {{ session('success') }}
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

        <form action="{{ url('login') }}" method="POST">
            @csrf
            
            <div class="form-group">
                <label for="email">Email</label>
                <input type="email" name="email" id="email" class="form-control @error('email') is-invalid @enderror" value="{{ old('email') }}" required>
                @error('email')
                    <div class="invalid-feedback" style="display: block;">{{ $message }}</div>
                @enderror
            </div>

            <div class="form-group">
                <label for="password">Mật khẩu</label>
                <input type="password" name="password" id="password" class="form-control @error('password') is-invalid @enderror" required>
                @error('password')
                    <div class="invalid-feedback" style="display: block;">{{ $message }}</div>
                @enderror
            </div>

            <button type="submit" class="btn btn-primary btn-login">Đăng Nhập</button>
        </form>

        <div class="register-link">
            Chưa có tài khoản? <a href="{{ route('register') }}">Đăng ký</a>
        </div>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
