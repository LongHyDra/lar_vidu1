(() => {
    const menu = document.getElementById('adminMenuToggle');
    function closeMenu() {
        document.body.classList.remove('admin-nav-open');
        menu?.setAttribute('aria-expanded', 'false');
    }
    menu?.addEventListener('click', () => {
        const open = document.body.classList.toggle('admin-nav-open');
        menu.setAttribute('aria-expanded', String(open));
    });
    document.addEventListener('keydown', event => {
        if (event.key === 'Escape' && document.body.classList.contains('admin-nav-open')) {
            closeMenu();
            menu?.focus();
        }
    });
    document.querySelectorAll('.admin-sidebar a').forEach(link => {
        if (link.classList.contains('active')) link.setAttribute('aria-current', 'page');
    });
    document.querySelectorAll('.auth-theme input[type="password"]').forEach((input, index) => {
        if (!input.id) input.id = 'account-password-' + index;
        const wrapper = document.createElement('div');
        wrapper.className = 'password-field';
        input.before(wrapper);
        wrapper.append(input);
        const toggle = document.createElement('button');
        toggle.type = 'button';
        toggle.className = 'password-toggle';
        toggle.textContent = 'Hiện';
        toggle.setAttribute('aria-label', 'Hiện mật khẩu');
        toggle.setAttribute('aria-controls', input.id);
        toggle.setAttribute('aria-pressed', 'false');
        toggle.addEventListener('click', () => {
            const show = input.type === 'password';
            input.type = show ? 'text' : 'password';
            toggle.textContent = show ? 'Ẩn' : 'Hiện';
            toggle.setAttribute('aria-label', show ? 'Ẩn mật khẩu' : 'Hiện mật khẩu');
            toggle.setAttribute('aria-pressed', String(show));
        });
        wrapper.append(toggle);
    });
})();
