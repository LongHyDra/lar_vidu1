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

        // 1. Tải Tỉnh/Thành
        fetch("{{ route('locations.provinces') }}")
            .then(res => res.json())
            .then(res => {
                if (res && res.data && res.data.length > 0) {
                    let options = '<option value="">-- Chọn Tỉnh/Thành --</option>';
                    res.data.forEach(p => {
                        options += `<option value="${p.ProvinceID}">${p.ProvinceName}</option>`;
                    });
                    provinceSelect.innerHTML = options;
                }
            })
            .catch(console.error);

        // 2. Chọn Tỉnh -> Tải Quận/Huyện
        provinceSelect.addEventListener('change', function() {
            districtSelect.innerHTML = '<option value="">-- Đang tải... --</option>';
            districtSelect.disabled = true;
            wardSelect.innerHTML = '<option value="">-- Chọn Phường/Xã --</option>';
            wardSelect.disabled = true;
            updateTotals(0);

            if (!this.value) return;

            fetch("{{ url('/locations/districts') }}/" + this.value)
                .then(res => res.json())
                .then(res => {
                    if (res && res.data && res.data.length > 0) {
                        let options = '<option value="">-- Chọn Quận/Huyện --</option>';
                        res.data.forEach(d => {
                            options += `<option value="${d.DistrictID}">${d.DistrictName}</option>`;
                        });
                        districtSelect.innerHTML = options;
                        districtSelect.disabled = false;
                    }
                });
        });

        // 3. Chọn Quận/Huyện -> Tải Phường/Xã
        districtSelect.addEventListener('change', function() {
            wardSelect.innerHTML = '<option value="">-- Đang tải... --</option>';
            wardSelect.disabled = true;
            updateTotals(0);

            if (!this.value) return;

            fetch("{{ url('/locations/wards') }}/" + this.value)
                .then(res => res.json())
                .then(res => {
                    if (res && res.data && res.data.length > 0) {
                        let options = '<option value="">-- Chọn Phường/Xã --</option>';
                        res.data.forEach(w => {
                            options += `<option value="${w.WardCode}">${w.WardName}</option>`;
                        });
                        wardSelect.innerHTML = options;
                        wardSelect.disabled = false;
                    }
                });
        });

        // 4. Chọn Phường/Xã -> Tính cước vận chuyển chuẩn theo giỏ hàng
        wardSelect.addEventListener('change', function() {
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

        function updateTotals(fee) {
            const subtotal = getSubtotal();
            shippingFeeText.innerText = new Intl.NumberFormat('vi-VN').format(fee) + ' VNĐ';
            const finalAmount = subtotal + fee;
            finalTotalText.innerText = new Intl.NumberFormat('vi-VN').format(finalAmount) + ' VNĐ';
            if (totalPriceInput) totalPriceInput.value = finalAmount;
        }
    });

    function getSubtotal() {
        const items = getCheckoutItems();
        return items.reduce((sum, item) => sum + (item.price * item.quantity), 0);
    }

    function renderItems(items) {
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

        if (!fullname || !phone || !street || !provSelect.value || !distSelect.value || !wardSelect.value) {
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
                window.location.href = data.redirect_url || "{{ route('user.orders.index') }}";
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