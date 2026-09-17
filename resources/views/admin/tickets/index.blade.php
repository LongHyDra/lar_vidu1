<!DOCTYPE html>
<html lang="vi">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Quản lý hỗ trợ</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body class="bg-light">
<div class="container-fluid py-4">
    <div class="d-flex justify-content-between align-items-center mb-3"><h1 class="h3">Ticket hỗ trợ</h1><a href="{{ route('admin.dashboard') }}" class="btn btn-outline-primary">Dashboard</a></div>
    @if(session('success'))<div class="alert alert-success">{{ session('success') }}</div>@endif
    <div class="table-responsive bg-white rounded-3 shadow-sm"><table class="table align-middle mb-0">
        <thead><tr><th>#</th><th>Khách hàng</th><th>Tiêu đề</th><th>Nội dung</th><th>Cập nhật</th></tr></thead>
        <tbody>
        @forelse($tickets as $ticket)
            <tr><td>{{ $ticket->id }}</td><td>{{ $ticket->user->name ?? 'N/A' }}</td><td>{{ $ticket->subject }}</td><td style="max-width:260px">{{ $ticket->message }}</td><td>
                <form action="{{ route('admin.tickets.update', $ticket) }}" method="POST" style="min-width:280px">@csrf @method('PUT')
                    <select name="status" class="form-select form-select-sm mb-2"><option value="open" @selected($ticket->status === 'open')>Mở</option><option value="in_progress" @selected($ticket->status === 'in_progress')>Đang xử lý</option><option value="resolved" @selected($ticket->status === 'resolved')>Đã xử lý</option><option value="closed" @selected($ticket->status === 'closed')>Đã đóng</option></select>
                    <textarea name="admin_reply" class="form-control form-control-sm mb-2" rows="2" placeholder="Phản hồi khách hàng">{{ $ticket->admin_reply }}</textarea>
                    <button class="btn btn-sm btn-primary">Lưu</button>
                </form>
            </td></tr>
        @empty <tr><td colspan="5" class="text-center text-muted py-5">Chưa có ticket nào.</td></tr> @endforelse
        </tbody>
    </table></div>
    <div class="mt-3">{{ $tickets->links() }}</div>
</div>
</body>
</html>
