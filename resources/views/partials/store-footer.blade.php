<footer class="site-footer">
    <div class="container">
        <div class="footer-grid">
            <div><div class="footer-brand">PHỤ KIỆN XE MÁY <span>247</span></div><p>Chi tiết nhỏ. Trải nghiệm khác biệt.</p></div>
            <nav class="footer-links" aria-label="Liên kết cuối trang">
                <a href="{{ route('welcome') }}">Cửa hàng</a>
                <a href="{{ route('faq') }}">Hướng dẫn mua hàng</a>
                <a href="{{ route('user.tickets.index') }}">Hỗ trợ</a>
            </nav>
        </div>
        <div class="footer-bottom">&copy; {{ date('Y') }} Phụ Kiện Xe Máy 247 · Đồng hành cùng mọi hành trình.</div>
    </div>
</footer>
@auth
<script>
(() => {
    const key = 'lar_accessories_cart';
    const token = '{{ csrf_token() }}';
    const read = () => { try { const value = JSON.parse(localStorage.getItem(key) || '[]'); return Array.isArray(value) ? value : []; } catch { return []; } };
    const sync = async (method, items) => fetch('{{ route('user.cart.sync') }}', {method, headers: {'Content-Type': 'application/json', 'Accept': 'application/json', 'X-CSRF-TOKEN': token}, body: method === 'PUT' ? JSON.stringify({items}) : undefined}).then(r => r.ok ? r.json() : null);
    document.addEventListener('DOMContentLoaded', async () => {
        const local = read();
        const remote = await sync('GET');
        const merged = [...(remote?.items || []), ...local];
        const map = new Map();
        merged.forEach(item => { const id = `${item.id}:${item.variant_id || 0}`; const current = map.get(id); map.set(id, current ? {...current, quantity: Math.min(100000, Number(current.quantity || 0) + Number(item.quantity || 0))} : item); });
        const items = [...map.values()];
        localStorage.setItem(key, JSON.stringify(items));
        await sync('PUT', items);
    });
    window.addEventListener('beforeunload', () => { sync('PUT', read()); });
})();
</script>
@endauth
