<!DOCTYPE html>
<html lang="vi">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>Thanh Toán Đơn Hàng | PHỤ KIỆN XE MÁY 247</title>

    <!-- Google Font Inter -->
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700;800&display=swap" rel="stylesheet">
    <!-- Bootstrap 5 CSS -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
    <!-- FontAwesome 6 Icons -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.0/css/all.min.css">
    <!-- SweetAlert2 -->
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>

    <style>
        * { box-sizing: border-box; }
        body { background: #f8fafc; font-family: 'Inter', sans-serif; color: #1e293b; min-height: 100vh; }
        .checkout-header { background: #ffffff; border-bottom: 1px solid #e2e8f0; padding: 18px 0; margin-bottom: 30px; }
        .checkout-brand { font-size: 1.15rem; font-weight: 800; color: #0f172a; text-decoration: none; display: flex; align-items: center; gap: 8px; }
        .checkout-badge { background: #f59e0b; color: #000; font-size: 0.7rem; font-weight: 900; padding: 2px 6px; border-radius: 4px; }
        .checkout-card { background: #ffffff; border: 1px solid #e2e8f0; border-radius: 14px; box-shadow: 0 2px 8px rgba(0, 0, 0, 0.03); padding: 24px; margin-bottom: 24px; }
        .checkout-card-title { font-size: 1.05rem; font-weight: 700; color: #0f172a; margin-bottom: 20px; display: flex; align-items: center; gap: 10px; border-bottom: 1px solid #f1f5f9; padding-bottom: 12px; }
        .form-label { font-size: 0.82rem; font-weight: 600; color: #475569; margin-bottom: 6px; }
        .form-control, .form-select { border: 1px solid #cbd5e1; border-radius: 8px; font-size: 0.88rem; padding: 8px 12px; }
        .form-control:focus, .form-select:focus { border-color: #2563eb; box-shadow: 0 0 0 3px rgba(37, 99, 235, 0.12); }
        .payment-radio-box { border: 1px solid #cbd5e1; border-radius: 10px; padding: 14px 16px; cursor: pointer; transition: all 0.2s; margin-bottom: 12px; }
        .payment-radio-box.active { border-color: #2563eb; background: #eff6ff; }
        .order-summary-box { background: #f8fafc; border-radius: 12px; padding: 18px; border: 1px solid #e2e8f0; }
        .btn-order-submit { background: #2563eb; border: none; border-radius: 10px; padding: 14px; font-size: 1rem; font-weight: 700; color: #fff; width: 100%; transition: background 0.2s; }
        .btn-order-submit:hover { background: #1d4ed8; }
    </style>
</head>

<body>
    <!-- HEADER -->
    <header class="checkout-header">
        <div class="container d-flex justify-content-between align-items-center">
            <a href="{{ route('welcome') }}" class="checkout-brand">
                <i class="fa-solid fa-motorcycle text-primary"></i>
                PHỤ KIỆN XE MÁY <span class="checkout-badge">247</span>
            </a>
            <div class="d-flex align-items-center gap-3">
                <a href="{{ route('welcome') }}" class="text-decoration-none text-muted small fw-semibold">
                    <i class="fa-solid fa-house me-1"></i> Trang chủ
                </a>
                <a href="{{ route('cart.index') }}" class="text-decoration-none text-muted small fw-semibold">
                    <i class="fa-solid fa-cart-shopping me-1"></i> Giỏ hàng
                </a>
            </div>
        </div>
    </header>

    <!-- CHECKOUT CONTENT -->
    <div class="container pb-5">
        <div class="row g-4">
            <!-- CỘT TRÁI: THÔNG TIN GIAO HÀNG & PHƯƠNG THỨC -->
            <div class="col-lg-7">
                <div class="checkout-card">
                    <div class="checkout-card-title">
                        <i class="fa-solid fa-location-dot text-primary"></i> Thông tin giao nhận hàng
                    </div>
                    <div class="row g-3">
                        <div class="col-md-6">
                            <label class="form-label">Họ và tên người nhận <span class="text-danger">*</span></label>
                            <input type="text" id="fullname" class="form-control" placeholder="Nguyễn Văn A" value="{{ Auth::check() ? Auth::user()->name : '' }}" required>
                        </div>
                        <div class="col-md-6">
                            <label class="form-label">Số điện thoại <span class="text-danger">*</span></label>
                            <input type="tel" id="phone" class="form-control" placeholder="0912345678" value="{{ Auth::check() ? (Auth::user()->phone ?? '') : '' }}" required>
                        </div>
                        <div class="col-md-4">
                            <label class="form-label">Tỉnh / Thành phố <span class="text-danger">*</span></label>
                            <select id="province_select" class="form-select">
                                <option value="">-- Đang tải... --</option>
                            </select>
                        </div>
                        <div class="col-md-4">
                            <label class="form-label">Quận / Huyện <span class="text-danger">*</span></label>
                            <select id="district_select" class="form-select" disabled>
                                <option value="">-- Chọn Quận/Huyện --</option>
                            </select>
                        </div>
                        <div class="col-md-4">
                            <label class="form-label">Phường / Xã <span class="text-danger">*</span></label>
                            <select id="ward_select" class="form-select" disabled>
                                <option value="">-- Chọn Phường/Xã --</option>
                            </select>
                        </div>
                        <div class="col-12">
                            <label class="form-label">Địa chỉ chi tiết (Số nhà, tên đường) <span class="text-danger">*</span></label>
                            <input type="text" id="streetInput" class="form-control" placeholder="Ví dụ: Số 25 ngõ 123 đường Cầu Giấy" required>
                        </div>
                    </div>
                </div>

                <div class="checkout-card">
                    <div class="checkout-card-title">
                        <i class="fa-solid fa-credit-card text-primary"></i> Phương thức thanh toán
                    </div>
                    
                    <!-- LỰA CHỌN 1: COD -->
                    <div class="payment-radio-box active" id="box_cod" onclick="setPayment('cod')">
                        <div class="d-flex align-items-center">
                            <input type="radio" name="paymentMethod" id="pay_cod" value="cod" class="form-check-input me-3" checked>
                            <div>
                                <div class="fw-bold">Thanh toán khi nhận hàng (COD)</div>
                                <div class="text-muted small">Thanh toán tiền mặt cho shipper khi nhận kiện hàng</div>
                            </div>
                        </div>
                    </div>

                    <!-- LỰA CHỌN 2: THẺ ATM NỘI ĐỊA (NAPAS) -->
                    <div class="payment-radio-box" id="box_momo_atm" onclick="setPayment('momo_atm')">
                        <div class="d-flex align-items-center">
                            <input type="radio" name="paymentMethod" id="pay_momo_atm" value="momo_atm" class="form-check-input me-3">
                            <div>
                                <div class="fw-bold text-primary">
                                    <i class="fa-solid fa-building-columns me-1"></i> Thẻ ATM Nội Địa / Internet Banking
                                </div>
                                <div class="text-muted small">Hỗ trợ các ngân hàng: Vietcombank, Techcombank, MB, ACB, Sacombank, NCB...</div>
                            </div>
                        </div>
                    </div>

                    <!-- LỰA CHỌN 3: THẺ QUỐC TẾ (VISA / MASTER / JCB) -->
                    <div class="payment-radio-box" id="box_momo_cc" onclick="setPayment('momo_cc')">
                        <div class="d-flex align-items-center">
                            <input type="radio" name="paymentMethod" id="pay_momo_cc" value="momo_cc" class="form-check-input me-3">
                            <div>
                                <div class="fw-bold text-success">
                                    <i class="fa-brands fa-cc-visa me-1"></i> Thẻ Quốc Tế (Visa / Mastercard / JCB)
                                </div>
                                <div class="text-muted small">Thanh toán bảo mật trực tiếp bằng thẻ tín dụng hoặc ghi nợ quốc tế</div>
                            </div>
                        </div>
                    </div>

                    <div id="cardNoticeBox" class="alert alert-light border d-none mt-2">
                        <small class="text-muted"><i class="fa-solid fa-shield-halved me-1 text-primary"></i> Sau khi nhấn Xác nhận, hệ thống sẽ mở cổng thanh toán bảo mật để bạn nhập thông tin thẻ.</small>
                    </div>
                </div>
            </div>

            <!-- CỘT PHẢI: TỔNG KẾT ĐƠN HÀNG -->
            <div class="col-lg-5">
                <div class="checkout-card sticky-top" style="top: 20px;">
                    <div class="checkout-card-title">
                        <i class="fa-solid fa-receipt text-primary"></i> Đơn hàng của bạn
                    </div>

                    <div id="checkoutItemsList" class="mb-3" style="max-height: 280px; overflow-y: auto;">
                        <div class="text-center py-3 text-muted small">Đang tải sản phẩm...</div>
                    </div>

                    <div class="order-summary-box mb-4">
                        <div class="d-flex justify-content-between mb-2">
                            <span class="text-muted small">Tạm tính tiền hàng:</span>
                            <span id="checkoutSubtotal" class="fw-semibold">0₫</span>
                        </div>
                        <div class="d-flex justify-content-between mb-2">
                            <span class="text-muted small">Phí vận chuyển:</span>
                            <span id="shipping_fee_text" class="fw-semibold text-primary">Chọn địa chỉ để tính</span>
                        </div>
                        <hr class="my-2">
                        <div class="d-flex justify-content-between align-items-center pt-1">
                            <span class="fw-bold fs-6">Tổng cộng:</span>
                            <span id="final_total_text" class="fw-bold fs-5 text-danger">0 VNĐ</span>
                        </div>
                        <input type="hidden" id="total_price_input" value="0">
                    </div>

                    <button type="button" class="btn-order-submit shadow-sm" onclick="submitOrder()">
                        <i class="fa-solid fa-lock me-2"></i> Xác nhận & Đi đến thanh toán
                    </button>
                </div>
            </div>
        </div>
    </div>

    <!-- JAVASCRIPT XỬ LÝ -->
    <script>
        const CHECKOUT_KEY = 'lar_accessories_checkout';

        function getCheckoutItems() {
            try {
                const stored = localStorage.getItem(CHECKOUT_KEY);
                return stored ? JSON.parse(stored) : [];
            } catch (e) {
                return [];
            }
        }

        document.addEventListener("DOMContentLoaded", function() {
            const provinceSelect = document.getElementById('province_select');
            const districtSelect = document.getElementById('district_select');
            const wardSelect = document.getElementById('ward_select');
            const shippingFeeText = document.getElementById('shipping_fee_text');
            const finalTotalText = document.getElementById('final_total_text');
            const totalPriceInput = document.getElementById('total_price_input');

            const items = getCheckoutItems();
            if (items.length === 0) {
                Swal.fire({
                    icon: 'warning',
                    title: 'Giỏ hàng trống',
                    text: 'Vui lòng chọn sản phẩm trước khi thanh toán.'
                }).then(() => {
                    window.location.href = "{{ route('cart.index') }}";
                });
                return;
            }

            renderItems(items);

            fetch("{{ route('locations.provinces') }}")
                .then(res => res.json())
                .then(res => {
                    if (res && res.data && res.data.length > 0 && provinceSelect) {
                        let options = '<option value="">-- Chọn Tỉnh/Thành --</option>';
                        res.data.forEach(p => {
                            options += `<option value="${p.ProvinceID}">${p.ProvinceName}</option>`;
                        });
                        provinceSelect.innerHTML = options;
                    }
                })
                .catch(console.error);

            if (provinceSelect) {
                provinceSelect.addEventListener('change', function() {
                    if (districtSelect) {
                        districtSelect.innerHTML = '<option value="">-- Đang tải... --</option>';
                        districtSelect.disabled = true;
                    }
                    if (wardSelect) {
                        wardSelect.innerHTML = '<option value="">-- Chọn Phường/Xã --</option>';
                        wardSelect.disabled = true;
                    }
                    updateTotals(0);

                    if (!this.value) return;

                    fetch("{{ url('/locations/districts') }}/" + this.value)
                        .then(res => res.json())
                        .then(res => {
                            if (res && res.data && res.data.length > 0 && districtSelect) {
                                let options = '<option value="">-- Chọn Quận/Huyện --</option>';
                                res.data.forEach(d => {
                                    options += `<option value="${d.DistrictID}">${d.DistrictName}</option>`;
                                });
                                districtSelect.innerHTML = options;
                                districtSelect.disabled = false;
                            }
                        });
                });
            }

            if (districtSelect) {
                districtSelect.addEventListener('change', function() {
                    if (wardSelect) {
                        wardSelect.innerHTML = '<option value="">-- Đang tải... --</option>';
                        wardSelect.disabled = true;
                    }
                    updateTotals(0);

                    if (!this.value) return;

                    fetch("{{ url('/locations/wards') }}/" + this.value)
                        .then(res => res.json())
                        .then(res => {
                            if (res && res.data && res.data.length > 0 && wardSelect) {
                                let options = '<option value="">-- Chọn Phường/Xã --</option>';
                                res.data.forEach(w => {
                                    options += `<option value="${w.WardCode}">${w.WardName}</option>`;
                                });
                                wardSelect.innerHTML = options;
                                wardSelect.disabled = false;
                            }
                        });
                });
            }

            if (wardSelect) {
                wardSelect.addEventListener('change', function() {
                    if (!this.value || !districtSelect || !districtSelect.value) return;
                    if (shippingFeeText) shippingFeeText.innerText = 'Đang tính cước...';

                    fetch("{{ route('locations.fee') }}", {
                            method: 'POST',
                            headers: {
                                'Content-Type': 'application/json',
                                'X-CSRF-TOKEN': '{{ csrf_token() }}'
                            },
                            body: JSON.stringify({
                                to_district_id: districtSelect.value,
                                to_ward_code: this.value,
                                cart_items: JSON.stringify(getCheckoutItems())
                            })
                        })
                        .then(res => res.json())
                        .then(res => {
                            const fee = (res && res.code === 200 && res.data) ? (parseInt(res.data.total) || 28000) : 28000;
                            updateTotals(fee);
                        })
                        .catch(() => updateTotals(28000));
                });
            }

            function updateTotals(fee) {
                const subtotal = getSubtotal();
                if (shippingFeeText) shippingFeeText.innerText = new Intl.NumberFormat('vi-VN').format(fee) + ' VNĐ';
                const finalAmount = subtotal + fee;
                if (finalTotalText) finalTotalText.innerText = new Intl.NumberFormat('vi-VN').format(finalAmount) + ' VNĐ';
                if (totalPriceInput) totalPriceInput.value = finalAmount;
            }
        });

        function getSubtotal() {
            const items = getCheckoutItems();
            return items.reduce((sum, item) => sum + (item.price * item.quantity), 0);
        }

        function renderItems(items) {
            const container = document.getElementById('checkoutItemsList');
            if (!container) return;

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
            const subtotalEl = document.getElementById('checkoutSubtotal');
            const finalTotalEl = document.getElementById('final_total_text');
            if (subtotalEl) subtotalEl.textContent = formatMoney(total) + '₫';
            if (finalTotalEl) finalTotalEl.textContent = formatMoney(total) + ' VNĐ';
        }

        function setPayment(method) {
            const methods = ['cod', 'momo_atm', 'momo_cc'];
            methods.forEach(m => {
                const radio = document.getElementById('pay_' + m);
                const box = document.getElementById('box_' + m);
                if (radio) radio.checked = (m === method);
                if (box) box.classList.toggle('active', m === method);
            });

            const noticeBox = document.getElementById('cardNoticeBox');
            if (noticeBox) {
                noticeBox.classList.toggle('d-none', method === 'cod');
            }
        }

        function submitOrder() {
            const fullname = document.getElementById('fullname')?.value.trim();
            const phone = document.getElementById('phone')?.value.trim();
            const street = document.getElementById('streetInput')?.value.trim();
            const provSelect = document.getElementById('province_select');
            const distSelect = document.getElementById('district_select');
            const wardSelect = document.getElementById('ward_select');
            const paymentMethod = document.querySelector('input[name="paymentMethod"]:checked')?.value || 'cod';

            if (!fullname || !phone || !street || !provSelect?.value || !distSelect?.value || !wardSelect?.value) {
                Swal.fire({
                    icon: 'warning',
                    title: 'Thông báo',
                    text: 'Vui lòng điền đầy đủ thông tin giao nhận.'
                });
                return;
            }

            const cartItems = getCheckoutItems();
            if (!cartItems.length) {
                Swal.fire({
                    icon: 'warning',
                    title: 'Thông báo',
                    text: 'Giỏ hàng đang trống.'
                });
                return;
            }

            const provName = provSelect.options[provSelect.selectedIndex].text;
            const distName = distSelect.options[distSelect.selectedIndex].text;
            const wardName = wardSelect.options[wardSelect.selectedIndex].text;

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
                .then(async res => {
                    const data = await res.json().catch(() => ({}));
                    if (!res.ok) throw new Error(data.message || 'Lỗi đặt hàng.');
                    return data;
                })
                .then(data => {
                    Swal.close();
                    localStorage.removeItem('lar_accessories_cart');
                    localStorage.removeItem(CHECKOUT_KEY);

                    // NẾU LÀ THANH TOÁN THẺ (ATM NỘI ĐỊA HOẶC QUỐC TẾ): CHUYỂN HƯỚNG CỔNG THANH TOÁN
                    if (paymentMethod === 'momo_atm' || paymentMethod === 'momo_cc') {
                        if (data.redirect_url) {
                            Swal.fire({
                                title: 'Đang kết nối cổng thanh toán...',
                                text: 'Đang chuyển bạn đến cổng nhập thông tin thẻ ngân hàng.',
                                icon: 'info',
                                showConfirmButton: false,
                                allowOutsideClick: false,
                                didOpen: () => Swal.showLoading()
                            });
                            window.location.href = data.redirect_url;
                        } else {
                            Swal.fire({
                                icon: 'error',
                                title: 'Lỗi cổng thanh toán',
                                text: data.message || 'Không tìm thấy đường dẫn thanh toán.'
                            });
                        }
                        return;
                    }

                    // NẾU LÀ COD: HIỂN THỊ POPUP THÀNH CÔNG
                    Swal.fire({
                        icon: 'success',
                        title: 'Đặt hàng thành công!',
                        html: 'Đơn hàng COD của bạn đã được ghi nhận.<br>Bạn muốn về trang chủ tiếp tục mua sắm hay xem đơn hàng?',
                        showCancelButton: true,
                        confirmButtonColor: '#2563eb',
                        cancelButtonColor: '#64748b',
                        confirmButtonText: '<i class="fa-solid fa-house me-1"></i> Về trang chủ',
                        cancelButtonText: '<i class="fa-solid fa-receipt me-1"></i> Xem đơn hàng'
                    }).then((result) => {
                        if (result.isConfirmed) {
                            window.location.href = "{{ route('welcome') }}";
                        } else {
                            window.location.href = data.redirect_url || "{{ route('user.orders.index') }}";
                        }
                    });
                })
                .catch(err => {
                    Swal.close();
                    Swal.fire({
                        icon: 'error',
                        title: 'Không thể đặt hàng',
                        text: err.message
                    });
                });
        }

        function formatMoney(n) {
            return new Intl.NumberFormat('vi-VN').format(n);
        }

        function escapeHtml(s) {
            return s ? s.replace(/&/g, "&amp;").replace(/</g, "&lt;").replace(/>/g, "&gt;") : '';
        }
    </script>
</body>

</html>