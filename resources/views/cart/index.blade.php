<!DOCTYPE html>
<html lang="vi">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Giỏ Hàng | Phụ Kiện Xe Máy 247</title>
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
        .brand-logo {
            display: flex;
            align-items: center;
            gap: 12px;
            text-decoration: none;
        }
        .brand-icon-box {
            width: 42px;
            height: 42px;
            background: linear-gradient(135deg, #2563eb, #1d4ed8);
            color: #ffffff;
            border-radius: 10px;
            display: flex;
            align-items: center;
            justify-content: center;
            font-size: 1.2rem;
        }
        .brand-name {
            font-size: 1.25rem;
            font-weight: 800;
            color: var(--dark);
            line-height: 1;
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

        /* CART CONTAINER */
        .cart-box {
            background: #ffffff;
            border-radius: 14px;
            border: 1.5px solid #e2e8f0;
            box-shadow: 0 4px 16px rgba(0,0,0,0.04);
            overflow: hidden;
        }
        .cart-header {
            padding: 18px 24px;
            background: #ffffff;
            border-bottom: 1.5px solid #e2e8f0;
            display: flex;
            align-items: center;
            justify-content: space-between;
        }

        table.cart-table {
            width: 100%;
            border-collapse: collapse;
        }
        table.cart-table th {
            background: #f8fafc;
            padding: 14px 18px;
            font-size: 0.82rem;
            font-weight: 800;
            color: var(--muted);
            text-transform: uppercase;
            letter-spacing: 0.5px;
            border-bottom: 1.5px solid #e2e8f0;
        }
        table.cart-table td {
            padding: 18px;
            border-bottom: 1px solid #f1f5f9;
            vertical-align: middle;
        }

        /* CHECKBOX CỰC KỲ DỄ NHÌN */
        .checkbox-custom {
            width: 22px;
            height: 22px;
            cursor: pointer;
            border: 2px solid #94a3b8;
        }
        .checkbox-custom:checked {
            background-color: var(--primary);
            border-color: var(--primary);
        }

        .item-title {
            font-size: 0.95rem;
            font-weight: 800;
            color: var(--dark);
            margin-bottom: 4px;
        }
        .item-cat {
            font-size: 0.75rem;
            color: var(--primary);
            background: #eff6ff;
            padding: 3px 8px;
            border-radius: 6px;
            font-weight: 700;
            border: 1px solid #bfdbfe;
        }

        /* NÚT TĂNG GIẢM SỐ LƯỢNG DỄ NHÌN */
        .qty-control {
            display: inline-flex;
            align-items: center;
            border: 2px solid #cbd5e1;
            border-radius: 8px;
            overflow: hidden;
            background: #fff;
        }
        .qty-control button {
            background: #f1f5f9;
            border: none;
            width: 32px;
            height: 32px;
            font-weight: 800;
            font-size: 1rem;
            color: var(--dark);
            cursor: pointer;
            transition: background 0.15s;
        }
        .qty-control button:hover {
            background: #e2e8f0;
        }
        .qty-control input {
            width: 40px;
            height: 32px;
            border: none;
            text-align: center;
            font-weight: 800;
            font-size: 0.9rem;
            outline: none;
        }

        /* NÚT XÓA SẢN PHẨM DỄ NHÌN */
        .btn-delete-item {
            background: #fff1f2;
            color: #dc2626;
            border: 1.5px solid #fecdd3;
            width: 36px;
            height: 36px;
            border-radius: 8px;
            display: inline-flex;
            align-items: center;
            justify-content: center;
            cursor: pointer;
            transition: all 0.15s;
        }
        .btn-delete-item:hover {
            background: #dc2626;
            color: #ffffff;
            border-color: #dc2626;
        }

        /* SIDEBAR SUMMARY & NÚT THANH TOÁN NỔI BẬT */
        .summary-box {
            background: #ffffff;
            border-radius: 14px;
            border: 1.5px solid #e2e8f0;
            box-shadow: 0 4px 16px rgba(0,0,0,0.04);
            padding: 24px;
            position: sticky;
            top: 90px;
        }

        .btn-checkout-prominent {
            background: linear-gradient(135deg, #16a34a, #15803d);
            color: #ffffff;
            border: none;
            width: 100%;
            padding: 15px 20px;
            border-radius: 12px;
            font-size: 1.05rem;
            font-weight: 800;
            letter-spacing: 0.3px;
            display: flex;
            align-items: center;
            justify-content: center;
            gap: 10px;
            cursor: pointer;
            transition: all 0.2s ease;
            box-shadow: 0 4px 14px rgba(22, 163, 74, 0.3);
            text-decoration: none;
        }
        .btn-checkout-prominent:hover {
            background: linear-gradient(135deg, #15803d, #166534);
            transform: translateY(-2px);
            box-shadow: 0 6px 20px rgba(22, 163, 74, 0.4);
            color: #ffffff;
        }

        .empty-cart-box {
            background: #ffffff;
            border-radius: 14px;
            border: 1.5px solid #e2e8f0;
            padding: 60px 20px;
            text-align: center;
        }
    </style>
</head>
<body>

    <!-- HEADER -->
    <header class="site-header">
        <div class="container">
            <div class="d-flex align-items-center justify-content-between">
                <a href="{{ route('welcome') }}" class="brand-logo">
                    <div class="brand-icon-box"><i class="fa-solid fa-motorcycle"></i></div>
                    <div class="brand-name">PHỤ KIỆN XE MÁY 247</div>
                </a>
                <a href="{{ route('welcome') }}" class="btn btn-md btn-outline-dark fw-bold px-3">
                    <i class="fa-solid fa-arrow-left me-1"></i> Tiếp tục mua sắm
                </a>
            </div>
        </div>
    </header>

    <!-- MAIN CONTAINER -->
    <main class="container flex-grow-1">

        <!-- STEP PROCESS -->
        <div class="step-bar">
            <div class="step-item active">
                <div class="step-num">1</div>
                <span>Giỏ hàng</span>
            </div>
            <i class="fa-solid fa-chevron-right text-muted small"></i>
            <div class="step-item">
                <div class="step-num">2</div>
                <span>Thanh toán</span>
            </div>
            <i class="fa-solid fa-chevron-right text-muted small"></i>
            <div class="step-item">
                <div class="step-num">3</div>
                <span>Hoàn tất</span>
            </div>
        </div>

        <div class="row g-4" id="cartContentRow">
            <div class="col-lg-8">
                <div class="cart-box">
                    <div class="cart-header">
                        <!-- SELECT ALL CHECKBOX DỄ NHÌN -->
                        <div class="form-check d-flex align-items-center gap-2 m-0">
                            <input class="form-check-input checkbox-custom" type="checkbox" id="selectAll" onchange="toggleSelectAll(this.checked)" checked>
                            <label class="form-check-label fw-bold text-dark fs-6 ms-1" for="selectAll" style="cursor: pointer;">
                                Chọn tất cả sản phẩm (<span id="totalItemsCount">0</span>)
                            </label>
                        </div>
                        <button type="button" class="btn btn-sm btn-outline-danger fw-bold px-3" onclick="clearCart()">
                            <i class="fa-solid fa-trash-can me-1"></i> Xóa tất cả
                        </button>
                    </div>

                    <div class="table-responsive">
                        <table class="cart-table">
                            <thead>
                                <tr>
                                    <th class="text-center" style="width:55px;">Chọn</th>
                                    <th>Sản Phẩm</th>
                                    <th class="text-end" style="width:130px;">Đơn Giá</th>
                                    <th class="text-center" style="width:130px;">Số Lượng</th>
                                    <th class="text-end" style="width:140px;">Thành Tiền</th>
                                    <th class="text-center" style="width:60px;">Xóa</th>
                                </tr>
                            </thead>
                            <tbody id="cartTableBody">
                                <!-- Cart items loaded by JS -->
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>

            <div class="col-lg-4">
                <div class="summary-box">
                    <h5 class="fw-extrabold mb-3 text-dark"><i class="fa-solid fa-receipt text-primary me-2"></i>Tóm Tắt Đơn Hàng</h5>
                    
                    <div class="d-flex justify-content-between mb-2 text-secondary">
                        <span>Đã chọn:</span>
                        <strong class="text-dark" id="summaryCount">0 sản phẩm</strong>
                    </div>
                    <div class="d-flex justify-content-between mb-2 text-secondary">
                        <span>Tạm tính:</span>
                        <strong class="text-dark" id="summarySubtotal">0₫</strong>
                    </div>
                    <div class="d-flex justify-content-between mb-3 text-secondary">
                        <span>Phí vận chuyển:</span>
                        <span class="text-success fw-bold"><i class="fa-solid fa-truck-fast me-1"></i> Miễn phí</span>
                    </div>

                    <hr class="my-3">

                    <div class="d-flex justify-content-between align-items-center mb-4">
                        <span class="fw-bold fs-6">Tổng thanh toán:</span>
                        <span class="fw-extrabold text-primary fs-4" id="summaryTotal">0₫</span>
                    </div>

                    <!-- NÚT TIẾN HÀNH THANH TOÁN CỰC KỲ DỄ NHÌN -->
                    <button type="button" class="btn-checkout-prominent" onclick="proceedToCheckout()">
                        <i class="fa-solid fa-credit-card fs-5"></i>
                        TIẾN HÀNH THANH TOÁN
                    </button>
                </div>
            </div>
        </div>

        <div id="emptyCartView" class="empty-cart-box d-none">
            <i class="fa-solid fa-cart-flatbed-suitcases fs-1 text-muted mb-3 d-block"></i>
            <h4 class="fw-bold">Giỏ hàng của bạn đang trống</h4>
            <p class="text-muted mb-4">Chưa có sản phẩm phụ kiện nào trong giỏ hàng.</p>
            <a href="{{ route('welcome') }}" class="btn btn-md btn-primary fw-bold px-4 py-2">
                <i class="fa-solid fa-store me-1"></i> Quay lại mua sắm ngay
            </a>
        </div>

    </main>

    <footer class="mt-auto py-3 bg-white border-top text-center text-muted small">
        <div class="container">&copy; {{ date('Y') }} Phụ Kiện Xe Máy 247. All rights reserved.</div>
    </footer>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js"></script>

    <script>
        const CART_KEY = 'lar_accessories_cart';
        const CHECKOUT_KEY = 'lar_accessories_checkout';

        function getCart() {
            try {
                const stored = localStorage.getItem(CART_KEY);
                return stored ? JSON.parse(stored) : [];
            } catch(e) { return []; }
        }

        function saveCart(cart) {
            localStorage.setItem(CART_KEY, JSON.stringify(cart));
            renderCart();
        }

        function renderCart() {
            const cart = getCart();
            const tbody = document.getElementById('cartTableBody');
            const row = document.getElementById('cartContentRow');
            const emptyView = document.getElementById('emptyCartView');

            if (cart.length === 0) {
                row.classList.add('d-none');
                emptyView.classList.remove('d-none');
                return;
            } else {
                row.classList.remove('d-none');
                emptyView.classList.add('d-none');
            }

            let html = '';
            let selectedCount = 0;
            let totalMoney = 0;
            let allChecked = true;

            cart.forEach(item => {
                const isChecked = item.checked !== false;
                if (!isChecked) allChecked = false;
                const subtotal = item.price * item.quantity;

                if (isChecked) {
                    selectedCount += item.quantity;
                    totalMoney += subtotal;
                }

                html += `
                    <tr>
                        <td class="text-center">
                            <input type="checkbox" class="form-check-input checkbox-custom" ${isChecked ? 'checked' : ''} onchange="toggleItem(${item.id}, this.checked)">
                        </td>
                        <td>
                            <div class="item-title">${escapeHtml(item.name)}</div>
                            <span class="item-cat">${escapeHtml(item.category || 'Phụ kiện')}</span>
                        </td>
                        <td class="text-end fw-bold">${formatMoney(item.price)}₫</td>
                        <td class="text-center">
                            <div class="qty-control">
                                <button type="button" onclick="updateQty(${item.id}, -1)">-</button>
                                <input type="text" value="${item.quantity}" readonly>
                                <button type="button" onclick="updateQty(${item.id}, 1)">+</button>
                            </div>
                        </td>
                        <td class="text-end fw-extrabold text-primary">${formatMoney(subtotal)}₫</td>
                        <td class="text-center">
                            <button type="button" class="btn-delete-item" onclick="removeItem(${item.id})" title="Xóa món này"><i class="fa-solid fa-trash"></i></button>
                        </td>
                    </tr>
                `;
            });

            tbody.innerHTML = html;
            document.getElementById('selectAll').checked = allChecked && cart.length > 0;
            document.getElementById('totalItemsCount').textContent = cart.length;
            document.getElementById('summaryCount').textContent = `${selectedCount} sản phẩm`;
            document.getElementById('summarySubtotal').textContent = formatMoney(totalMoney) + '₫';
            document.getElementById('summaryTotal').textContent = formatMoney(totalMoney) + '₫';
        }

        function toggleItem(id, isChecked) {
            let cart = getCart();
            let item = cart.find(i => i.id === id);
            if (item) {
                item.checked = isChecked;
                saveCart(cart);
            }
        }

        function toggleSelectAll(isChecked) {
            let cart = getCart();
            cart.forEach(item => item.checked = isChecked);
            saveCart(cart);
        }

        function updateQty(id, delta) {
            let cart = getCart();
            let item = cart.find(i => i.id === id);
            if (item) {
                let n = item.quantity + delta;
                if (n <= 0) return removeItem(id);
                if (item.stock && n > item.stock) {
                    Swal.fire('Thông báo', `Kho hàng chỉ còn ${item.stock} sản phẩm.`, 'info');
                    return;
                }
                item.quantity = n;
                saveCart(cart);
            }
        }

        function removeItem(id) {
            Swal.fire({
                title: 'Xóa sản phẩm?',
                text: 'Bạn có chắc chắn muốn bỏ sản phẩm này ra khỏi giỏ hàng?',
                icon: 'question',
                showCancelButton: true,
                confirmButtonColor: '#ef4444',
                cancelButtonColor: '#64748b',
                confirmButtonText: 'Xóa',
                cancelButtonText: 'Hủy'
            }).then((res) => {
                if (res.isConfirmed) {
                    let cart = getCart().filter(i => i.id !== id);
                    saveCart(cart);
                }
            });
        }

        function clearCart() {
            Swal.fire({
                title: 'Xóa toàn bộ giỏ hàng?',
                icon: 'warning',
                showCancelButton: true,
                confirmButtonColor: '#ef4444',
                confirmButtonText: 'Xóa tất cả',
                cancelButtonText: 'Hủy'
            }).then((res) => {
                if (res.isConfirmed) {
                    localStorage.removeItem(CART_KEY);
                    renderCart();
                }
            });
        }

        function proceedToCheckout() {
            const cart = getCart();
            const selected = cart.filter(i => i.checked !== false);

            if (selected.length === 0) {
                Swal.fire({ icon: 'warning', title: 'Thông báo', text: 'Vui lòng tích chọn ít nhất 1 sản phẩm để tiến hành thanh toán.' });
                return;
            }

            localStorage.setItem(CHECKOUT_KEY, JSON.stringify(selected));
            window.location.href = "{{ route('checkout.index') }}";
        }

        function formatMoney(n) { return new Intl.NumberFormat('vi-VN').format(n); }
        function escapeHtml(s) { return s ? s.replace(/&/g, "&amp;").replace(/</g, "&lt;").replace(/>/g, "&gt;") : ''; }

        document.addEventListener('DOMContentLoaded', renderCart);
    </script>
</body>
</html>
