@auth
    <div id="chat-box" class="store-chat-widget">
        <button id="chat-toggle" type="button" class="store-chat-toggle" aria-label="Mở chat hỗ trợ">
            <i class="fa-solid fa-comment-dots" aria-hidden="true"></i>
            <span>Chat</span>
        </button>

        <section id="chat-popup" class="store-chat-popup" aria-label="Chat hỗ trợ khách hàng" hidden>
            <div class="store-chat-header">
                <div>
                    <strong>Hỗ trợ trực tuyến</strong>
                    <small><span class="store-chat-status"></span> Đang hoạt động</small>
                </div>
                <button id="chat-close" type="button" class="store-chat-close" aria-label="Đóng chat">&times;</button>
            </div>
            <div id="chat-messages" class="store-chat-messages" aria-live="polite">
                <small class="text-muted">Đang tải lịch sử trò chuyện...</small>
            </div>
            <form id="chat-form" class="store-chat-form">
                <input type="text" id="chat-input" class="form-control" placeholder="Nhập tin nhắn..." autocomplete="off" maxlength="2000">
                <button id="send-btn" type="submit" class="store-chat-send" aria-label="Gửi tin nhắn">
                    <i class="fa-solid fa-paper-plane" aria-hidden="true"></i>
                </button>
            </form>
        </section>
    </div>

    <script>
        (() => {
            if (window.__storeChatInitialized) return;
            window.__storeChatInitialized = true;

            const toggle = document.getElementById('chat-toggle');
            const close = document.getElementById('chat-close');
            const popup = document.getElementById('chat-popup');
            const messages = document.getElementById('chat-messages');
            const form = document.getElementById('chat-form');
            const input = document.getElementById('chat-input');
            const csrf = document.querySelector('meta[name="csrf-token"]')?.content || '';
            const currentUserId = Number(@json(Auth::id()));

            if (!toggle || !popup || !messages || !form || !input) return;

            const escapeHtml = value => String(value ?? '').replace(/[&<>'"]/g, character => ({
                '&': '&amp;', '<': '&lt;', '>': '&gt;', "'": '&#039;', '"': '&quot;'
            }[character]));

            const renderMessages = list => {
                if (!Array.isArray(list) || list.length === 0) {
                    messages.innerHTML = '<div class="store-chat-empty">Bắt đầu cuộc trò chuyện với cửa hàng.</div>';
                    return;
                }

                messages.innerHTML = list.map(message => {
                    const mine = Number(message.sender_id) === currentUserId;
                    return `<div class="store-chat-message ${mine ? 'is-mine' : 'is-admin'}">
                        <span>${escapeHtml(message.content)}</span>
                        <small>${mine ? 'Bạn' : 'Cửa hàng'}</small>
                    </div>`;
                }).join('');
                messages.scrollTop = messages.scrollHeight;
            };

            const loadMessages = () => fetch('{{ route('user.chat.messages') }}', {
                headers: { Accept: 'application/json' }
            })
                .then(response => response.ok ? response.json() : Promise.reject())
                .then(renderMessages)
                .catch(() => {
                    messages.innerHTML = '<div class="store-chat-empty text-danger">Không thể tải tin nhắn.</div>';
                });

            form.addEventListener('submit', event => {
                event.preventDefault();
                const message = input.value.trim();
                if (!message) return;

                const button = form.querySelector('button');
                button.disabled = true;
                fetch('{{ route('user.chat.send') }}', {
                    method: 'POST',
                    headers: {
                        'Content-Type': 'application/json',
                        'X-CSRF-TOKEN': csrf,
                        Accept: 'application/json'
                    },
                    body: JSON.stringify({ message })
                })
                    .then(response => response.ok ? response.json() : Promise.reject())
                    .then(() => {
                        input.value = '';
                        return loadMessages();
                    })
                    .catch(() => {
                        messages.insertAdjacentHTML('beforeend', '<div class="store-chat-empty text-danger">Gửi tin nhắn thất bại, vui lòng thử lại.</div>');
                    })
                    .finally(() => {
                        button.disabled = false;
                        input.focus();
                    });
            });

            toggle.addEventListener('click', () => {
                popup.hidden = !popup.hidden;
                if (!popup.hidden) {
                    loadMessages();
                    input.focus();
                }
            });
            close.addEventListener('click', () => { popup.hidden = true; });
            setInterval(() => { if (!popup.hidden) loadMessages(); }, 3000);
        })();
    </script>
@endauth
