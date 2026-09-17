<!DOCTYPE html>
<html lang="vi">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>@yield('title', 'Quản Lý Phụ Kiện Xe Máy')</title>
    <!-- Bootstrap 5 CSS -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
    <!-- FontAwesome Icons -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.2/css/all.min.css">
    <!-- Google Fonts (Inter) -->
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700&display=swap" rel="stylesheet">
    
    <style>
        body { font-family: 'Inter', sans-serif; background-color: #f4f6f9; color: #333; }
        .sidebar { width: 260px; height: 100vh; position: fixed; top: 0; left: 0; background: #1e293b; color: #fff; padding-top: 20px; z-index: 100; }
        .sidebar .brand { font-size: 1.25rem; font-weight: 700; padding: 0 20px 20px 20px; border-bottom: 1px solid #334155; color: #38bdf8; }
        .sidebar a { display: flex; align-items: center; padding: 12px 20px; color: #94a3b8; text-decoration: none; font-weight: 500; transition: all 0.2s; }
        .sidebar a:hover, .sidebar a.active { background: #0f172a; color: #38bdf8; border-left: 4px solid #38bdf8; }
        .sidebar a i { width: 25px; font-size: 1.1rem; }
        .main-content { margin-left: 260px; padding: 30px; }
        .top-navbar { background: #fff; padding: 15px 30px; border-radius: 10px; box-shadow: 0 2px 10px rgba(0,0,0,0.03); margin-bottom: 25px; display: flex; justify-content: space-between; align-items: center; }
        .card-custom { border: none; border-radius: 12px; box-shadow: 0 4px 15px rgba(0,0,0,0.03); background: #fff; }
        .table-custom thead { background-color: #f8fafc; color: #64748b; font-weight: 600; text-transform: uppercase; font-size: 0.75rem; letter-spacing: 0.5px; }
        .table-custom td { vertical-align: middle; padding: 14px 16px; }
        .badge-subtle { padding: 6px 12px; font-weight: 600; border-radius: 20px; }
        .badge-subtle-success { background-color: #dcfce7; color: #15803d; }
        .btn-action { width: 34px; height: 34px; border-radius: 8px; display: inline-flex; align-items: center; justify-content: center; font-size: 0.875rem; border: none; }
    </style>
</head>
<body>

    <!-- Sidebar Điều Hướng -->
    <div class="sidebar">
        <div class="brand">
            <i class="fa-solid fa-motorcycle me-2"></i> PHỤ KIỆN XE MÁY
        </div>
        <div class="mt-3">
            <a href="{{ route('categories.index') }}" class="active"><i class="fa-solid fa-list me-2"></i> Danh Mục Phụ Kiện</a>
            <a href="{{ route('products.index') }}"><i class="fa-solid fa-box me-2"></i> Sản Phẩm Phụ Kiện</a>
            <a href="{{ route('admin.dashboard') }}"><i class="fa-solid fa-chart-pie me-2"></i> Bảng Điều Khiển</a>
        </div>
    </div>

    <!-- Nội dung chính -->
    <div class="main-content">
        <!-- Top Navbar -->
        <div class="top-navbar">
            <h5 class="mb-0 fw-bold text-slate-800">Hệ Thống Quản Lý Phụ Kiện Xe Máy</h5>
            <div class="d-flex align-items-center gap-3">
                <span class="badge bg-primary-subtle text-primary fw-medium px-3 py-2">Phiên bản 1.0</span>
                <img src="https://ui-avatars.com/api/?name=Admin+Phu+Kien&background=0D8ABC&color=fff" class="rounded-circle" width="38" alt="Avatar">
            </div>
        </div>

        @yield('content')
    </div>

    @auth
        <div id="admin-chat-box" style="position:fixed; right:24px; bottom:24px; z-index:1050;">
            <button id="chat-toggle" class="btn btn-dark shadow" type="button" style="border-radius:999px; padding:12px 18px; font-weight:700;">
                <i class="fa-solid fa-comments me-2"></i> Chat Khách hàng
            </button>
            <div id="chat-popup" class="card shadow-lg" style="display:none; width:360px; position:absolute; right:0; bottom:66px; border-radius:14px; overflow:hidden;">
                <div class="card-header bg-dark text-white d-flex justify-content-between align-items-center">
                    <strong>Hỗ trợ trực tuyến</strong>
                    <button id="chat-close" type="button" class="btn btn-sm btn-light">X</button>
                </div>
                <div id="user-list" class="border-bottom" style="background:#f8fafc; max-height:180px; overflow-y:auto;">
                    <div class="p-2 text-center text-muted"><small>Đang tải danh sách...</small></div>
                </div>
                <div id="chat-messages" style="height:260px; overflow-y:auto; padding:14px; background:#fff;">
                    <div class="text-center mt-5 text-muted">Chọn một khách hàng để xem tin nhắn</div>
                </div>
                <div class="card-footer bg-white">
                    <div class="input-group">
                        <input type="text" id="chat-input" class="form-control form-control-sm" placeholder="Nhập câu trả lời..." autocomplete="off">
                        <button id="send-btn" class="btn btn-success btn-sm" type="button">Gửi</button>
                    </div>
                </div>
            </div>
        </div>
    @endauth

    <!-- SweetAlert2 cho thông báo & xác nhận xóa chuyên nghiệp -->
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
    @yield('scripts')
    <script>
        document.addEventListener('DOMContentLoaded', function () {
            const toggleBtn = document.getElementById('chat-toggle');
            const chatPopup = document.getElementById('chat-popup');
            const closeBtn = document.getElementById('chat-close');
            const chatMessages = document.getElementById('chat-messages');
            const chatInput = document.getElementById('chat-input');
            const sendBtn = document.getElementById('send-btn');
            const userList = document.getElementById('user-list');

            if (!toggleBtn || !chatPopup) return;

            let currentUserId = null;

            const loadUsers = () => {
                fetch('{{ route("admin.chat.users") }}')
                    .then(res => res.json())
                    .then(users => {
                        if (!userList) return;

                        if (!users.length) {
                            userList.innerHTML = '<div class="p-2 text-muted text-center"><small>Chưa có hội thoại</small></div>';
                            return;
                        }

                        let html = '';
                        users.forEach(user => {
                            const active = Number(currentUserId) === Number(user.id) ? 'active' : '';
                            html += `
                                <div class="user-item p-2 border-bottom ${active}" data-user-id="${user.id}" style="cursor:pointer; background:${active ? '#e0f2fe' : '#fff'};">
                                    <strong>${user.name}</strong>
                                </div>
                            `;
                        });
                        userList.innerHTML = html;

                        userList.querySelectorAll('.user-item').forEach(item => {
                            item.addEventListener('click', function () {
                                currentUserId = Number(this.dataset.userId);
                                userList.querySelectorAll('.user-item').forEach(el => el.style.background = '#fff');
                                this.style.background = '#e0f2fe';
                                loadMessages();
                            });
                        });
                    })
                    .catch(() => {
                        userList.innerHTML = '<div class="p-2 text-muted text-center"><small>Không thể tải danh sách</small></div>';
                    });
            };

            const loadMessages = () => {
                if (!currentUserId) {
                    chatMessages.innerHTML = '<div class="text-center mt-5 text-muted">Chọn một khách hàng để xem tin nhắn</div>';
                    return;
                }

                fetch(`/admin/chat/messages/${currentUserId}`)
                    .then(res => res.json())
                    .then(messages => {
                        let html = '';
                        messages.forEach(msg => {
                            const isMine = Number(msg.sender_id) === Number('{{ Auth::id() }}');
                            const senderName = isMine ? 'Bạn' : (msg.sender?.name || 'Khách hàng');
                            const color = isMine ? 'rgb(37 99 235)' : '#111827';
                            html += `<div class="mb-2" style="color:${color};"><strong>${senderName}:</strong> ${msg.content}</div>`;
                        });
                        chatMessages.innerHTML = html || '<div class="text-center mt-5 text-muted">Chưa có tin nhắn nào</div>';
                        chatMessages.scrollTop = chatMessages.scrollHeight;
                    })
                    .catch(() => {
                        chatMessages.innerHTML = '<div class="text-center mt-5 text-danger">Không thể tải tin nhắn</div>';
                    });
            };

            const sendMessage = () => {
                const message = chatInput.value.trim();
                if (!message || !currentUserId) return;

                fetch('{{ route("admin.chat.send") }}', {
                    method: 'POST',
                    headers: {
                        'Content-Type': 'application/json',
                        'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]')?.getAttribute('content') || '',
                        'Accept': 'application/json'
                    },
                    body: JSON.stringify({ user_id: currentUserId, message })
                })
                    .then(res => res.json())
                    .then(() => {
                        chatInput.value = '';
                        loadMessages();
                    })
                    .catch(() => {
                        chatInput.value = '';
                    });
            };

            toggleBtn.addEventListener('click', () => {
                chatPopup.style.display = chatPopup.style.display === 'none' ? 'block' : 'none';
                if (chatPopup.style.display === 'block') {
                    loadUsers();
                }
            });

            closeBtn.addEventListener('click', () => {
                chatPopup.style.display = 'none';
            });

            sendBtn.addEventListener('click', sendMessage);
            chatInput.addEventListener('keydown', function (event) {
                if (event.key === 'Enter') {
                    sendMessage();
                }
            });

            setInterval(() => {
                if (chatPopup.style.display === 'block') {
                    loadUsers();
                    loadMessages();
                }
            }, 3000);
        });
    </script>
</body>
</html>