<!DOCTYPE html>
<html lang="vi">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Xác Thực Email | PHỤ KIỆN XE MÁY 247</title>
    <!-- Bootstrap 5 CSS -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
    <!-- FontAwesome 6 -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.0/css/all.min.css">
    <!-- Google Font Inter -->
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700;800&display=swap" rel="stylesheet">

    <style>
        body {
            background-color: #f8fafc;
            font-family: 'Inter', sans-serif;
            display: flex;
            align-items: center;
            justify-content: center;
            min-height: 100vh;
            margin: 0;
            padding: 16px;
        }
        .verify-card {
            background: #ffffff;
            border: 1px solid #e2e8f0;
            border-radius: 16px;
            padding: 40px 32px;
            max-width: 520px;
            width: 100%;
            box-shadow: 0 10px 25px rgba(0, 0, 0, 0.05);
            text-align: center;
        }
        .icon-circle {
            width: 76px;
            height: 76px;
            border-radius: 50%;
            background: #eff6ff;
            color: #2563eb;
            display: flex;
            align-items: center;
            justify-content: center;
            font-size: 2.2rem;
            margin: 0 auto 24px;
        }
    </style>
</head>
<body>
    <div class="verify-card">
        <div class="icon-circle">
            <i class="fa-regular fa-envelope-open"></i>
        </div>
        <h4 class="fw-bold text-dark mb-2">Xác thực tài khoản của bạn</h4>
        <p class="text-muted small mb-4">
            Cảm ơn bạn đã đăng ký tại <strong>PHỤ KIỆN XE MÁY 247</strong>. Trước khi bắt đầu mua hàng và thanh toán, vui lòng kiểm tra email <strong>{{ Auth::user()->email }}</strong> và nhấn vào liên kết xác thực chúng tôi vừa gửi.
        </p>

        @if (session('success'))
            <div class="alert alert-success border-0 small text-start mb-4" role="alert">
                <i class="fa-solid fa-circle-check me-2"></i>{{ session('success') }}
            </div>
        @endif

        <div class="d-flex flex-column gap-2">
            <form method="POST" action="{{ route('verification.send') }}">
                @csrf
                <button type="submit" class="btn btn-primary w-100 fw-bold py-2 rounded-3">
                    <i class="fa-solid fa-paper-plane me-2"></i>Gửi lại link xác thực
                </button>
            </form>

            <form method="POST" action="{{ route('logout') }}">
                @csrf
                <button type="submit" class="btn btn-link text-muted text-decoration-none small">
                    <i class="fa-solid fa-right-from-bracket me-1"></i>Đăng xuất tài khoản
                </button>
            </form>
        </div>
    </div>
</body>
</html>