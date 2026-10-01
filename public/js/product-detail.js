(() => {
    const button = document.getElementById('detailAddCart');
    const message = document.getElementById('detailCartMessage');
    const counter = document.getElementById('detailCartCount');
    const variantSelect = document.getElementById('variantSelect');
    const priceDisplay = document.querySelector('.detail-price');
    const syncVariantUi = () => {
        const option = variantSelect?.selectedOptions?.[0];
        if (!option) return;
        if (priceDisplay) priceDisplay.textContent = new Intl.NumberFormat('vi-VN').format(Number(option.dataset.price || 0)) + '₫';
        if (button) button.disabled = Number(option.dataset.stock || 0) <= 0;
    };
    const key = 'lar_accessories_cart';
    function readCart() {
        const cart = JSON.parse(localStorage.getItem(key) || '[]');
        return Array.isArray(cart) ? cart : [];
    }
    function updateCount() {
        try { counter.textContent = readCart().reduce((total, item) => total + (Number(item.quantity) || 0), 0); }
        catch { counter.textContent = '0'; }
    }
    button?.addEventListener('click', () => {
        const id = Number(button.dataset.id);
        const variant = variantSelect?.selectedOptions?.[0];
        const variantId = variant ? Number(variant.value) : null;
        const variantName = variant?.dataset.name || '';
        const price = variant ? Number(variant.dataset.price) : Number(button.dataset.price);
        const stock = variant ? Number(variant.dataset.stock) : Number(button.dataset.stock);
        if (stock <= 0) return;
        try {
            const cart = readCart();
            const existing = cart.find(item => Number(item.id) === id && Number(item.variant_id || 0) === Number(variantId || 0));
            if (existing && Number(existing.quantity) >= stock) {
                message.textContent = 'Bạn đã chọn tối đa số lượng hiện có trong kho.';
                return;
            }
            if (existing) {
                existing.quantity = Number(existing.quantity) + 1;
                existing.stock = stock;
                existing.checked = true;
            } else {
                cart.push({ id, variant_id: variantId, variant_name: variantName, name: button.dataset.name,
                    price, category: button.dataset.category, stock, quantity: 1, checked: true });
            }
            localStorage.setItem(key, JSON.stringify(cart));
            updateCount();
            message.textContent = 'Đã thêm vào giỏ hàng. Bạn có thể tiếp tục lựa chọn hoặc mở giỏ để thanh toán.';
        } catch {
            message.textContent = 'Không thể lưu giỏ hàng. Vui lòng kiểm tra quyền lưu dữ liệu của trình duyệt.';
        }
    });
    variantSelect?.addEventListener('change', syncVariantUi);
    window.addEventListener('storage', updateCount);
    updateCount();
    syncVariantUi();
})();
