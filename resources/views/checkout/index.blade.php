<!DOCTYPE html>
<html lang="vi">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Thanh Toán Đơn Hàng | Phụ Kiện Xe Máy 247</title>
    <!-- Bootstrap 5 CSS -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
    <!-- FontAwesome 6 Icons -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.0/css/all.min.css">
    <!-- Google Font (Inter) -->
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700;800&display=swap" rel="stylesheet">
    <!-- SweetAlert2 -->
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>

    <style>
        :root {
            --primary: #1d4ed8;
            --primary-hover: #1e40af;
            --dark: #0f172a;
            --slate: #334155;
            --muted: #64748b;
            --light-bg: #f8fafc;
            --border-color: #cbd5e1;
        }

        body {
            font-family: 'Inter', sans-serif;
            background-color: var(--light-bg);
            color: var(--dark);
            min-height: 100vh;
            display: flex;
            flex-direction: column;
        }

        /* HEADER */
        .site-header {
            background: #ffffff;
            border-bottom: 2px solid #e2e8f0;
            padding: 16px 0;
            margin-bottom: 30px;
            box-shadow: 0 2px 10px rgba(0,0,0,0.03);
        }
        /* LOGO STYLES & ANIMATIONS */
        @keyframes badgeGlowPulse {
            0%, 100% { opacity: 0.5; filter: blur(5px); transform: scale(1); }
            50% { opacity: 0.9; filter: blur(8px); transform: scale(1.04); }
        }
        @keyframes revEngine {
            0% { transform: scale(1) rotate(0deg); }
            20% { transform: scale(1.22) rotate(-12deg); }
            40% { transform: scale(1.18) rotate(6deg); }
            60% { transform: scale(1.22) rotate(-8deg); }
            80% { transform: scale(1.18) rotate(3deg); }
            100% { transform: scale(1.15) rotate(-5deg); }
        }
        @keyframes sparkTwinkle {
            0%, 100% { opacity: 0.3; transform: scale(0.8); }
            50% { opacity: 1; transform: scale(1.3); filter: drop-shadow(0 0 6px #f59e0b); }
        }
        @keyframes shimmerSweep {
            0% { transform: translateX(-150%) rotate(25deg); }
            25%, 100% { transform: translateX(180%) rotate(25deg); }
        }
        @keyframes badgeFloat {
            0%, 100% { transform: translateY(0); }
            50% { transform: translateY(-2px); box-shadow: 0 4px 10px rgba(217, 119, 6, 0.45); }
        }

        .brand-logo {
            display: flex;
            align-items: center;
            gap: 14px;
            text-decoration: none;
            transition: transform 0.25s cubic-bezier(0.34, 1.56, 0.64, 1);
        }
        .brand-logo:hover {
            transform: translateY(-2px);
        }
        .brand-badge-wrap {
            position: relative;
            width: 44px;
            height: 44px;
            flex-shrink: 0;
        }
        .brand-badge-glow {
            position: absolute;
            inset: -3px;
            background: linear-gradient(135deg, #2563eb, #f59e0b, #3b82f6);
            border-radius: 13px;
            animation: badgeGlowPulse 3s ease-in-out infinite;
        }
        .brand-badge-inner {
            position: relative;
            width: 100%;
            height: 100%;
            background: linear-gradient(135deg, #0f172a, #1e293b);
            border: 1.5px solid rgba(255, 255, 255, 0.25);
            border-radius: 12px;
            display: flex;
            align-items: center;
            justify-content: center;
            color: #ffffff;
            box-shadow: inset 0 1px 1px rgba(255,255,255,0.4), 0 4px 10px rgba(15, 23, 42, 0.4);
            overflow: hidden;
        }
        .brand-badge-inner::after {
            content: '';
            position: absolute;
            top: -50%;
            left: -50%;
            width: 200%;
            height: 200%;
            background: linear-gradient(60deg, transparent 30%, rgba(255, 255, 255, 0.25) 50%, transparent 70%);
            animation: shimmerSweep 4.5s ease-in-out infinite;
            pointer-events: none;
        }
        .brand-badge-inner .icon-main {
            font-size: 1.35rem;
            background: linear-gradient(135deg, #60a5fa, #3b82f6);
            -webkit-background-clip: text;
            -webkit-text-fill-color: transparent;
            filter: drop-shadow(0 2px 4px rgba(37,99,235,0.4));
            transition: transform 0.3s cubic-bezier(0.34, 1.56, 0.64, 1);
        }
        .brand-logo:hover .brand-badge-inner .icon-main {
            animation: revEngine 0.6s ease-in-out forwards;
        }
        .brand-badge-inner .icon-spark {
            position: absolute;
            top: 3px;
            right: 4px;
            font-size: 0.6rem;
            color: #f59e0b;
            animation: sparkTwinkle 2s ease-in-out infinite;
        }
        .brand-title {
            display: flex;
            align-items: center;
            gap: 7px;
            font-size: 1.2rem;
            font-weight: 900;
            line-height: 1.1;
            letter-spacing: -0.5px;
        }
        .text-gradient {
            background: linear-gradient(135deg, #0f172a 20%, #1e40af 100%);
            -webkit-background-clip: text;
            -webkit-text-fill-color: transparent;
        }
        .badge-247 {
            background: linear-gradient(135deg, #f59e0b, #d97706);
            color: #ffffff;
            font-size: 0.7rem;
            font-weight: 900;
            padding: 2px 6px;
            border-radius: 6px;
            letter-spacing: 0.5px;
            box-shadow: 0 2px 6px rgba(217, 119, 6, 0.35);
            animation: badgeFloat 2.5s ease-in-out infinite;
        }



        /* STEP PROCESS BAR */
        .step-bar {
            display: flex;
            align-items: center;
            justify-content: center;
            gap: 20px;
            margin-bottom: 30px;
        }
        .step-item {
            display: flex;
            align-items: center;
            gap: 10px;
            font-size: 0.95rem;
            font-weight: 700;
            color: var(--muted);
        }
        .step-item.active {
            color: var(--primary);
        }
        .step-num {
            width: 32px;
            height: 32px;
            border-radius: 50%;
            background: #cbd5e1;
            color: var(--slate);
            display: flex;
            align-items: center;
            justify-content: center;
            font-size: 0.9rem;
            font-weight: 800;
        }
        .step-item.active .step-num {
            background: var(--primary);
            color: #ffffff;
            box-shadow: 0 4px 10px rgba(29, 78, 216, 0.3);
        }

        /* CHECKOUT CARDS */
        .checkout-box {
            background: #ffffff;
            border-radius: 14px;
            border: 1.5px solid #e2e8f0;
            box-shadow: 0 4px 16px rgba(0,0,0,0.04);
            padding: 26px;
            margin-bottom: 24px;
        }
        .checkout-title {
            font-size: 1.1rem;
            font-weight: 800;
            color: var(--dark);
            margin-bottom: 20px;
            padding-bottom: 12px;
            border-bottom: 1.5px solid #e2e8f0;
        }

        /* FORM LABELS & CONTROL CUSTOM */
        .form-label-custom {
            font-size: 0.88rem;
            font-weight: 800;
            color: var(--dark);
            margin-bottom: 6px;
            display: flex;
            align-items: center;
            gap: 6px;
        }
        .form-control-custom, .form-select-custom {
            padding: 11px 16px;
            border-radius: 10px;
            border: 2px solid #cbd5e1;
            font-size: 0.92rem;
            font-weight: 600;
            color: #0f172a;
            background-color: #ffffff;
        }
        .form-control-custom:focus, .form-select-custom:focus {
            border-color: var(--primary);
            box-shadow: 0 0 0 4px rgba(37, 99, 235, 0.15);
            background-color: #ffffff;
        }

        /* PAYMENT RADIO BOXES */
        .payment-radio-box {
            border: 2px solid #e2e8f0;
            border-radius: 12px;
            padding: 16px 20px;
            margin-bottom: 14px;
            cursor: pointer;
            display: flex;
            align-items: center;
            gap: 14px;
            background: #ffffff;
            transition: all 0.2s ease;
        }
        .payment-radio-box:hover {
            border-color: #93c5fd;
            background: #f8fafc;
        }
        .payment-radio-box.active {
            border-color: var(--primary);
            background: #eff6ff;
            box-shadow: 0 4px 12px rgba(37, 99, 235, 0.12);
        }

        .radio-custom {
            width: 22px;
            height: 22px;
            cursor: pointer;
        }

        /* NÚT XÁC NHẬN ĐẶT HÀNG */
        .btn-order-prominent {
            background: linear-gradient(135deg, #2563eb, #1d4ed8);
            color: #ffffff;
            border: none;
            width: 100%;
            padding: 16px 20px;
            border-radius: 12px;
            font-size: 1.1rem;
            font-weight: 800;
            letter-spacing: 0.4px;
            display: flex;
            align-items: center;
            justify-content: center;
            gap: 10px;
            cursor: pointer;
            transition: all 0.2s ease;
            box-shadow: 0 4px 16px rgba(37, 99, 235, 0.35);
        }
        .btn-order-prominent:hover {
            background: linear-gradient(135deg, #1d4ed8, #1e40af);
            transform: translateY(-2px);
            box-shadow: 0 6px 20px rgba(37, 99, 235, 0.45);
            color: #ffffff;
        }
    </style>
</head>
<body>

    <!-- HEADER -->
    <header class="site-header">
        <div class="container">
            <div class="d-flex align-items-center justify-content-between">
                <a href="{{ route('welcome') }}" class="brand-logo">
                    <div class="brand-badge-wrap">
                        <div class="brand-badge-glow"></div>
                        <div class="brand-badge-inner">
                            <i class="fa-solid fa-motorcycle icon-main"></i>
                            <i class="fa-solid fa-bolt icon-spark"></i>
                        </div>
                    </div>
                    <div>
                        <div class="brand-title">
                            <span class="text-gradient">PHỤ KIỆN XE MÁY</span>
                            <span class="badge-247">247</span>
                        </div>
                    </div>
                </a>

                <a href="{{ route('cart.index') }}" class="btn btn-md btn-outline-dark fw-bold px-3">
                    <i class="fa-solid fa-arrow-left me-1"></i> Quay lại giỏ hàng
                </a>
            </div>
        </div>
    </header>

    <main class="container flex-grow-1">

        <!-- STEP PROCESS -->
        <div class="step-bar">
            <div class="step-item">
                <div class="step-num">1</div>
                <span>Giỏ hàng</span>
            </div>
            <i class="fa-solid fa-chevron-right text-muted small"></i>
            <div class="step-item active">
                <div class="step-num">2</div>
                <span>Thanh toán</span>
            </div>
            <i class="fa-solid fa-chevron-right text-muted small"></i>
            <div class="step-item">
                <div class="step-num">3</div>
                <span>Hoàn tất</span>
            </div>
        </div>

        <div class="row g-4">
            <!-- LEFT COLUMN: CUSTOMER INFO & PAYMENT METHOD -->
            <div class="col-lg-7">

                <!-- 1. THÔNG TIN GIAO HÀNG (CÓ HỆ THỐNG COMBOBOX API GHN TÍNH PHÍ VẬN CHUYỂN) -->
                <div class="checkout-box">
                    <div class="d-flex align-items-center justify-content-between mb-3 border-bottom pb-2">
                        <h5 class="checkout-title border-0 m-0"><i class="fa-solid fa-user text-primary me-2"></i> 1. Thông Tin Nhận Hàng</h5>
                        <span class="badge bg-primary-subtle text-primary border border-primary-subtle fw-bold px-2 py-1">
                            <i class="fa-solid fa-truck-fast me-1"></i> Tự động tính phí GHN
                        </span>
                    </div>

                    <form id="checkoutForm">
                        <input type="hidden" id="total_price_input" value="4350000">

                        <!-- HỌ VÀ TÊN -->
                        <div class="mb-3">
                            <label for="fullname" class="form-label-custom">
                                <i class="fa-solid fa-id-card text-primary me-1"></i> Họ và tên người nhận <span class="text-danger">*</span>
                            </label>
                            <input type="text" class="form-control form-control-custom" id="fullname" name="fullname"
                                value="{{ Auth::check() ? Auth::user()->name : 'Nguyễn Văn A' }}" placeholder="Nhập họ và tên người nhận" required>
                        </div>

                        <div class="row">
                            <!-- SỐ ĐIỆN THOẠI -->
                            <div class="col-md-6 mb-3">
                                <label for="phone" class="form-label-custom">
                                    <i class="fa-solid fa-phone text-primary me-1"></i> Số điện thoại giao hàng <span class="text-danger">*</span>
                                </label>
                                <input type="tel" class="form-control form-control-custom" id="phone" name="phone"
                                    value="0901234567" placeholder="Nhập số điện thoại" required>
                            </div>

                            <div class="col-md-6 mb-3">
                                <label for="email" class="form-label-custom">
                                    <i class="fa-solid fa-envelope text-primary me-1"></i> Email nhận thông báo
                                </label>
                                <input type="email" class="form-control form-control-custom" id="email" name="email"
                                    value="{{ Auth::check() ? Auth::user()->email : 'khachhang@gmail.com' }}">
                            </div>
                        </div>

                        <!-- COMBOBOX CHỌN TỈNH / QUẬN / PHƯỜNG GHN -->
                        <div class="row">
                            <div class="col-md-4 mb-3">
                                <label for="province_select" class="form-label-custom">
                                    <i class="fa-solid fa-city text-primary me-1"></i> Tỉnh / Thành phố <span class="text-danger">*</span>
                                </label>
                                <select class="form-select form-select-custom" id="province_select" name="province_id" required>
                                    <option value="">-- Đang tải Tỉnh/Thành... --</option>
                                </select>
                            </div>

                            <!-- COMBOBOX CHỌN QUẬN / HUYỆN -->
                            <div class="col-md-4 mb-3">
                                <label for="district_select" class="form-label-custom">
                                    <i class="fa-solid fa-building text-primary me-1"></i> Quận / Huyện <span class="text-danger">*</span>
                                </label>
                                <select class="form-select form-select-custom" id="district_select" name="to_district_id" disabled required>
                                    <option value="">-- Chọn Quận/Huyện --</option>
                                </select>
                            </div>

                            <!-- COMBOBOX CHỌN PHƯỜNG / XÃ -->
                            <div class="col-md-4 mb-3">
                                <label for="ward_select" class="form-label-custom">
                                    <i class="fa-solid fa-map-pin text-primary me-1"></i> Phường / Xã <span class="text-danger">*</span>
                                </label>
                                <select class="form-select form-select-custom" id="ward_select" name="to_ward_code" disabled required>
                                    <option value="">-- Chọn Phường/Xã --</option>
                                </select>
                            </div>
                        </div>

                        <!-- SỐ NHÀ, TÊN ĐƯỜNG -->
                        <div class="mb-3">
                            <label for="streetInput" class="form-label-custom">
                                <i class="fa-solid fa-house text-primary me-1"></i> Số nhà, tên đường chi tiết <span class="text-danger">*</span>
                            </label>
                            <input type="text" class="form-control form-control-custom" id="streetInput"
                                value="123 Đường Trần Hưng Đạo" placeholder="Ví dụ: 123 Đường Trần Hưng Đạo" required>
                        </div>

                        <div class="mb-0">
                            <label for="note" class="form-label-custom">
                                <i class="fa-solid fa-note-sticky text-secondary me-1"></i> Ghi chú đơn hàng (Tùy chọn)
                            </label>
                            <textarea class="form-control form-control-custom" id="note" name="note" rows="2" placeholder="Ví dụ: Giao giờ hành chính, gọi trước 15 phút"></textarea>
                        </div>
                    </form>
                </div>

                <!-- 2. HÌNH THỨC THANH TOÁN (2 RADIO TRỰC TIẾP VÀ MOMO) -->
                <div class="checkout-box">
                    <h5 class="checkout-title"><i class="fa-solid fa-wallet me-2 text-primary"></i> 2. Hình Thức Thanh Toán</h5>

                    <!-- RADIO 1: THANH TOÁN TRỰC TIẾP (COD) -->
                    <div class="payment-radio-box active" id="radioBoxCod" onclick="setPayment('cod')">
                        <input type="radio" class="form-check-input radio-custom" name="paymentMethod" id="payCod" value="cod" checked>
                        <div>
                            <label for="payCod" class="fw-extrabold text-dark mb-0 d-block fs-6" style="cursor:pointer;">
                                <i class="fa-solid fa-hand-holding-dollar text-success me-2"></i> Thanh toán Trực tiếp (COD)
                            </label>
                            <small class="text-secondary fw-medium">Thanh toán bằng tiền mặt trực tiếp cho shipper khi nhận hàng tận nơi.</small>
                        </div>
                    </div>

                    <!-- RADIO 2: THANH TOÁN QUA VÍ MOMO -->
                    <div class="payment-radio-box" id="radioBoxMomo" onclick="setPayment('momo')">
                        <input type="radio" class="form-check-input radio-custom" name="paymentMethod" id="payMomo" value="momo">
                        <div>
                            <label for="payMomo" class="fw-extrabold text-dark mb-0 d-block fs-6" style="cursor:pointer;">
                                <i class="fa-solid fa-qrcode text-danger me-2"></i> Thanh toán Ví Điện Tử MoMo
                            </label>
                            <small class="text-secondary fw-medium">Quét mã QR Code hoặc chuyển khoản qua ứng dụng MoMo 24/7.</small>
                        </div>
                    </div>

                    <!-- MOMO DETAILS -->
                    <div class="p-3 bg-light border border-danger-subtle rounded-3 mt-2 d-none" id="momoInfoBox">
                        <div class="d-flex align-items-center gap-3">
                            <i class="fa-solid fa-qrcode text-danger" style="font-size: 3.5rem;"></i>
                            <div class="small">
                                <div class="fw-bold text-danger fs-6 mb-1">Thanh toán qua MoMo</div>
                                <div class="text-muted">Sau khi xác nhận, hệ thống sẽ chuyển bạn đến cổng thanh toán MoMo sandbox để tiếp tục thanh toán.</div>
                            </div>
                        </div>
                    </div>
                </div>

            </div>

            <!-- RIGHT COLUMN: ORDER SUMMARY -->
            <div class="col-lg-5">
                <div class="checkout-box position-sticky" style="top: 90px;">
                    <h5 class="checkout-title"><i class="fa-solid fa-receipt me-2 text-primary"></i> Chi Tiết Đơn Hàng</h5>

                    <div id="checkoutItemsList" class="mb-3" style="max-height: 260px; overflow-y: auto;">
                        <!-- Items rendered by JS -->
                    </div>

                    <div class="border-top pt-3">
                        <div class="d-flex justify-content-between mb-2 text-secondary">
                            <span>Tạm tính:</span>
                            <strong class="text-dark" id="checkoutSubtotal">0₫</strong>
                        </div>
                        <div class="d-flex justify-content-between mb-2 text-secondary">
                            <span>Phí vận chuyển (GHN):</span>
                            <strong class="text-primary" id="shipping_fee_text">0 VNĐ</strong>
                        </div>
                        <hr class="my-2">
                        <div class="d-flex justify-content-between align-items-center mb-4">
                            <span class="fw-bold fs-6">Tổng cộng:</span>
                            <span class="fw-extrabold text-primary fs-4" id="final_total_text">0 VNĐ</span>
                        </div>

                        <!-- NÚT XÁC NHẬN ĐẶT HÀNG -->
                        <button type="button" class="btn-order-prominent" onclick="submitOrder()">
                            <i class="fa-solid fa-circle-check fs-5"></i>
                            XÁC NHẬN ĐẶT HÀNG
                        </button>
                    </div>
                </div>
            </div>
        </div>

    </main>

    <footer class="mt-auto py-3 bg-white border-top text-center text-muted small">
        <div class="container">&copy; {{ date('Y') }} Phụ Kiện Xe Máy 247. All rights reserved.</div>
    </footer>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js"></script>

    <script>
        const CHECKOUT_KEY = 'lar_accessories_checkout';

        // CƠ SỞ DỮ LIỆU CÁC COMBOBOX ĐỊA CHỈ DỰ PHÒNG (NINH BÌNH, HÀ NỘI, HCM)
        const FALLBACK_LOCATION_DATA = [
            {
                ProvinceID: 1000,
                ProvinceName: 'Tỉnh Ninh Bình',
                districts: [
                    { DistrictID: 10001, DistrictName: 'TP. Ninh Bình', wards: [{ WardCode: '1000101', WardName: 'Phường Đông Thành' }, { WardCode: '1000102', WardName: 'Phường Bích Đào' }] },
                    { DistrictID: 10002, DistrictName: 'TP. Tam Điệp', wards: [{ WardCode: '1000201', WardName: 'Phường Bắc Sơn' }] },
                    { DistrictID: 10003, DistrictName: 'Huyện Hoa Lư', wards: [{ WardCode: '1000301', WardName: 'Xã Trường Yên' }] }
                ]
            },
            {
                ProvinceID: 201,
                ProvinceName: 'Hà Nội',
                districts: [
                    { DistrictID: 1482, DistrictName: 'Quận Cầu Giấy', wards: [{ WardCode: '1A0101', WardName: 'Phường Dịch Vọng' }] },
                    { DistrictID: 1485, DistrictName: 'Quận Hoàn Kiếm', wards: [{ WardCode: '1A0201', WardName: 'Phường Hàng Bạc' }] }
                ]
            },
            {
                ProvinceID: 202,
                ProvinceName: 'TP. Hồ Chí Minh',
                districts: [
                    { DistrictID: 1442, DistrictName: 'Quận 1', wards: [{ WardCode: '20101', WardName: 'Phường Bến Nghé' }] },
                    { DistrictID: 1450, DistrictName: 'Quận 5', wards: [{ WardCode: '20501', WardName: 'Phường 2' }] }
                ]
            }
        ];

        document.addEventListener("DOMContentLoaded", function () {
            const provinceSelect = document.getElementById('province_select');
            const districtSelect = document.getElementById('district_select');
            const wardSelect = document.getElementById('ward_select');
            const shippingFeeText = document.getElementById('shipping_fee_text');
            const finalTotalText = document.getElementById('final_total_text');
            const totalPriceInput = document.getElementById('total_price_input');

            const districtsUrl = "{{ route('locations.districts', ['provinceId' => '__PROVINCE__']) }}";
            const wardsUrl = "{{ route('locations.wards', ['districtId' => '__DISTRICT__']) }}";

            loadItems();

            // 1. Tải danh sách Tỉnh/Thành phố từ GHN API
            fetch("{{ route('locations.provinces') }}")
                .then(res => res.json())
                .then(res => {
                    if (res && res.data && res.data.length > 0) {
                        let options = '<option value="">-- Chọn Tỉnh/Thành --</option>';
                        res.data.forEach(p => {
                            options += `<option value="${p.ProvinceID}">${p.ProvinceName}</option>`;
                        });
                        provinceSelect.innerHTML = options;
                    } else {
                        populateFallbackProvinces();
                    }
                })
                .catch(err => {
                    console.warn("Dùng dữ liệu địa chỉ dự phòng:", err);
                    populateFallbackProvinces();
                });

            function populateFallbackProvinces() {
                let options = '<option value="">-- Chọn Tỉnh/Thành --</option>';
                FALLBACK_LOCATION_DATA.forEach(p => {
                    const isSelected = p.ProvinceID === 1000 ? 'selected' : '';
                    options += `<option value="${p.ProvinceID}" ${isSelected}>${p.ProvinceName}</option>`;
                });
                provinceSelect.innerHTML = options;
                if (provinceSelect.value) {
                    provinceSelect.dispatchEvent(new Event('change'));
                }
            }

            // 2. Khi chọn Tỉnh -> Tải Quận/Huyện
            provinceSelect.addEventListener('change', function () {
                districtSelect.innerHTML = '<option value="">-- Đang tải... --</option>';
                districtSelect.disabled = true;
                wardSelect.innerHTML = '<option value="">-- Chọn Phường/Xã --</option>';
                wardSelect.disabled = true;
                updateTotals(0);

                if (!this.value) return;

                fetch(districtsUrl.replace('__PROVINCE__', this.value))
                    .then(res => res.json())
                    .then(res => {
                        if (res && res.data && res.data.length > 0) {
                            let options = '<option value="">-- Chọn Quận/Huyện --</option>';
                            res.data.forEach(d => {
                                options += `<option value="${d.DistrictID}">${d.DistrictName}</option>`;
                            });
                            districtSelect.innerHTML = options;
                            districtSelect.disabled = false;
                        } else {
                            loadFallbackDistricts(this.value);
                        }
                    })
                    .catch(() => loadFallbackDistricts(this.value));
            });

            function loadFallbackDistricts(provId) {
                const p = FALLBACK_LOCATION_DATA.find(x => x.ProvinceID == provId);
                if (p && p.districts) {
                    let options = '<option value="">-- Chọn Quận/Huyện --</option>';
                    p.districts.forEach(d => {
                        options += `<option value="${d.DistrictID}">${d.DistrictName}</option>`;
                    });
                    districtSelect.innerHTML = options;
                    districtSelect.disabled = false;
                } else {
                    districtSelect.innerHTML = '<option value="">-- Không tải được quận/huyện --</option>';
                }
            }

            // 3. Khi chọn Quận/Huyện -> Tải Phường/Xã
            districtSelect.addEventListener('change', function () {
                wardSelect.innerHTML = '<option value="">-- Đang tải... --</option>';
                wardSelect.disabled = true;
                updateTotals(0);

                if (!this.value) return;

                fetch(wardsUrl.replace('__DISTRICT__', this.value))
                    .then(res => res.json())
                    .then(res => {
                        if (res && res.data && res.data.length > 0) {
                            let options = '<option value="">-- Chọn Phường/Xã --</option>';
                            res.data.forEach(w => {
                                options += `<option value="${w.WardCode}">${w.WardName}</option>`;
                            });
                            wardSelect.innerHTML = options;
                            wardSelect.disabled = false;
                        } else {
                            loadFallbackWards(this.value);
                        }
                    })
                    .catch(() => loadFallbackWards(this.value));
            });

            function loadFallbackWards(distId) {
                let foundWards = null;
                FALLBACK_LOCATION_DATA.forEach(p => {
                    const d = p.districts.find(x => x.DistrictID == distId);
                    if (d) foundWards = d.wards;
                });

                if (foundWards) {
                    let options = '<option value="">-- Chọn Phường/Xã --</option>';
                    foundWards.forEach(w => {
                        options += `<option value="${w.WardCode}">${w.WardName}</option>`;
                    });
                    wardSelect.innerHTML = options;
                    wardSelect.disabled = false;
                } else {
                    wardSelect.innerHTML = '<option value="">-- Không tải được phường/xã --</option>';
                }
            }

            // 4. Khi chọn Phường/Xã -> Tính cước vận chuyển GHN
            wardSelect.addEventListener('change', function () {
                if (!this.value || !districtSelect.value) return;
                shippingFeeText.innerText = 'Đang tính cước...';

                fetch("{{ route('locations.fee') }}", {
                    method: 'POST',
                    headers: {
                        'Content-Type': 'application/json',
                        'X-CSRF-TOKEN': '{{ csrf_token() }}'
                    },
                    body: JSON.stringify({
                        to_district_id: districtSelect.value,
                        to_ward_code: this.value
                    })
                })
                .then(res => res.json())
                .then(res => {
                    if (res && res.code === 200 && res.data) {
                        const fee = parseInt(res.data.total) || 28000;
                        updateTotals(fee);
                    } else {
                        updateTotals(28000); // Phí tiêu chuẩn mặc định
                    }
                })
                .catch(() => updateTotals(28000));
            });

            function updateTotals(fee) {
                const subtotal = getSubtotal();
                shippingFeeText.innerText = new Intl.NumberFormat('vi-VN').format(fee) + ' VNĐ';
                const finalAmount = subtotal + fee;
                finalTotalText.innerText = new Intl.NumberFormat('vi-VN').format(finalAmount) + ' VNĐ';
                if (totalPriceInput) {
                    totalPriceInput.value = finalAmount;
                }
            }
        });

        function getSubtotal() {
            let items = [];
            try {
                const stored = localStorage.getItem(CHECKOUT_KEY);
                if (stored) items = JSON.parse(stored);
            } catch(e) {}
            if (!items || items.length === 0) {
                return 4350000;
            }
            return items.reduce((sum, item) => sum + (item.price * item.quantity), 0);
        }

        function loadItems() {
            let items = [];
            try {
                const stored = localStorage.getItem(CHECKOUT_KEY);
                if (stored) items = JSON.parse(stored);
            } catch(e) {}

            if (!items || items.length === 0) {
                items = [
                    { id: 1, name: 'Heo Dầu Brembo 4 Piston Monoblock', price: 3500000, quantity: 1 },
                    { id: 2, name: 'Bộ Nhông Sên Dĩa DID Vàng 428HD', price: 850000, quantity: 1 }
                ];
            }

            const container = document.getElementById('checkoutItemsList');
            let html = '';
            let total = 0;

            items.forEach(item => {
                const sub = item.price * item.quantity;
                total += sub;
                html += `
                    <div class="d-flex justify-content-between align-items-center py-2 border-bottom">
                        <div>
                            <div class="fw-bold text-dark" style="font-size:0.9rem;">${escapeHtml(item.name)}</div>
                            <div class="text-muted" style="font-size:0.78rem;">SL: ${item.quantity} x ${formatMoney(item.price)}₫</div>
                        </div>
                        <div class="fw-bold text-primary" style="font-size:0.92rem;">${formatMoney(sub)}₫</div>
                    </div>
                `;
            });

            container.innerHTML = html;
            document.getElementById('checkoutSubtotal').textContent = formatMoney(total) + '₫';
            document.getElementById('final_total_text').textContent = formatMoney(total) + ' VNĐ';
            document.getElementById('total_price_input').value = total;
        }

        function setPayment(method) {
            document.getElementById('payCod').checked = (method === 'cod');
            document.getElementById('payMomo').checked = (method === 'momo');

            document.getElementById('radioBoxCod').classList.toggle('active', method === 'cod');
            document.getElementById('radioBoxMomo').classList.toggle('active', method === 'momo');
            document.getElementById('momoInfoBox').classList.toggle('d-none', method !== 'momo');
        }

        function submitOrder() {
            const fullname = document.getElementById('fullname').value.trim();
            const phone = document.getElementById('phone').value.trim();
            const street = document.getElementById('streetInput').value.trim();
            const provSelect = document.getElementById('province_select');
            const distSelect = document.getElementById('district_select');
            const wardSelect = document.getElementById('ward_select');
            const paymentMethod = document.querySelector('input[name="paymentMethod"]:checked')?.value || 'cod';

            const provName = provSelect.options[provSelect.selectedIndex] ? provSelect.options[provSelect.selectedIndex].text : '';
            const distName = distSelect.options[distSelect.selectedIndex] ? distSelect.options[distSelect.selectedIndex].text : '';
            const wardName = wardSelect.options[wardSelect.selectedIndex] ? wardSelect.options[wardSelect.selectedIndex].text : '';

            if (!fullname || !phone || !street || !provSelect.value || !distSelect.value || !wardSelect.value) {
                Swal.fire({ icon: 'warning', title: 'Thông báo', text: 'Vui lòng chọn đầy đủ Tỉnh/Thành, Quận/Huyện, Phường/Xã và nhập Số nhà tên đường.' });
                return;
            }

            const cartItems = JSON.parse(localStorage.getItem(CHECKOUT_KEY) || '[]');
            if (!cartItems.length) {
                Swal.fire({ icon: 'warning', title: 'Thông báo', text: 'Giỏ hàng đang trống, vui lòng quay lại giỏ hàng.' });
                return;
            }

            const payload = {
                name: fullname,
                phone: phone,
                address: `${street}, ${wardName}, ${distName}, ${provName}`,
                to_district_id: Number(distSelect.value),
                to_ward_code: wardSelect.value,
                payment_method: paymentMethod,
                cart_items: JSON.stringify(cartItems)
            };

            Swal.fire({
                title: 'Đang xử lý đơn hàng...',
                text: 'Vui lòng chờ trong giây lát.',
                allowOutsideClick: false,
                didOpen: () => Swal.showLoading()
            });

            fetch("{{ route('user.payment.process') }}", {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json',
                    'Accept': 'application/json',
                    'X-CSRF-TOKEN': '{{ csrf_token() }}'
                },
                body: JSON.stringify(payload)
            })
            .then(async response => {
                const data = await response.json().catch(() => ({}));
                if (!response.ok) {
                    throw new Error(data.message || 'Không thể đặt hàng.');
                }
                return data;
            })
            .then(data => {
                Swal.close();
                Swal.fire({
                    title: 'Thành công!',
                    text: data.message || 'Đặt hàng thành công.',
                    icon: 'success',
                    confirmButtonText: 'Tiếp tục',
                    confirmButtonColor: '#1d4ed8'
                }).then(() => {
                    localStorage.removeItem('lar_accessories_cart');
                    localStorage.removeItem(CHECKOUT_KEY);
                    window.location.href = data.redirect_url || "{{ route('welcome') }}";
                });
            })
            .catch(error => {
                Swal.close();
                Swal.fire({
                    icon: 'error',
                    title: 'Không thể đặt hàng',
                    text: error.message || 'Vui lòng thử lại.'
                });
            });
        }

        function formatMoney(n) { return new Intl.NumberFormat('vi-VN').format(n); }
        function escapeHtml(s) { return s ? s.replace(/&/g, "&amp;").replace(/</g, "&lt;").replace(/>/g, "&gt;") : ''; }
    </script>
</body>
</html>
