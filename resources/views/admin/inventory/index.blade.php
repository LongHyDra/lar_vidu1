<!DOCTYPE html>
<html lang="vi">
<head>
    <meta charset="UTF-8"><meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Nhật ký tồn kho</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body class="bg-light">
<div class="container-fluid py-4">
    <div class="d-flex justify-content-between align-items-center mb-3"><div><h1 class="h3 mb-1">Quản lý tồn kho</h1><p class="text-muted mb-0">Theo dõi cảnh báo và lịch sử nhập/xuất.</p></div><a href="{{ route('admin.dashboard') }}" class="btn btn-outline-primary">Dashboard</a></div>
    <div class="row g-3 mb-4"><div class="col-12"><div class="card border-0 shadow-sm"><div class="card-body"><h2 class="h5">Cảnh báo sắp hết hàng</h2><div class="row g-2">@forelse($lowStockProducts as $product)<div class="col-md-3"><div class="border rounded p-3"><strong>{{ $product->name }}</strong><div class="text-danger mt-1">Còn {{ $product->stock }} sản phẩm</div></div></div>@empty<p class="text-success mb-0">Tất cả sản phẩm đang đủ tồn kho.</p>@endforelse</div></div></div></div></div>
    <div class="card border-0 shadow-sm"><div class="card-body"><h2 class="h5 mb-3">Lịch sử nhập/xuất kho</h2><div class="table-responsive"><table class="table align-middle"><thead><tr><th>Thời gian</th><th>Sản phẩm</th><th>Loại</th><th>Số lượng</th><th>Tồn sau giao dịch</th><th>Người thực hiện</th><th>Ghi chú</th></tr></thead><tbody>@forelse($movements as $movement)<tr><td>{{ $movement->created_at->format('d/m/Y H:i') }}</td><td>{{ $movement->product->name ?? 'Đã xóa' }}</td><td><span class="badge text-bg-{{ $movement->type === 'in' ? 'success' : 'primary' }}">{{ $movement->type === 'in' ? 'Nhập' : 'Xuất' }}</span></td><td>{{ $movement->quantity > 0 ? '+' : '' }}{{ $movement->quantity }}</td><td>{{ $movement->stock_after }}</td><td>{{ $movement->user->name ?? 'Hệ thống' }}</td><td>{{ $movement->note }}</td></tr>@empty<tr><td colspan="7" class="text-center text-muted py-5">Chưa có lịch sử tồn kho.</td></tr>@endforelse</tbody></table></div>{{ $movements->links() }}</div></div>
</div>
</body>
</html>
