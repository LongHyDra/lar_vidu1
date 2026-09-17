<!DOCTYPE html>
<html lang="vi">
<head>
    <meta charset="UTF-8"><title>Báo cáo doanh thu</title>
    <style>body{font-family:Arial,sans-serif;margin:32px;color:#111827}h1{margin-bottom:4px}p{color:#64748b}table{width:100%;border-collapse:collapse;margin-top:24px}th,td{border:1px solid #d1d5db;padding:10px;text-align:left}th{background:#f3f4f6}@media print{.print-button{display:none}}</style>
</head>
<body>
    <button class="print-button" onclick="window.print()">In hoặc lưu thành PDF</button>
    <h1>Báo cáo doanh thu</h1>
    <p>Khoảng thời gian: {{ $from ?: 'Tất cả' }} đến {{ $to ?: 'hiện tại' }}</p>
    <table><thead><tr><th>Ngày</th><th>Trạng thái</th><th>Doanh thu</th></tr></thead><tbody>
        @forelse($orders as $order)<tr><td>{{ $order->created_at->format('d/m/Y H:i') }}</td><td>{{ $order->status }}</td><td>{{ number_format($order->total_price, 0, ',', '.') }}đ</td></tr>@empty<tr><td colspan="3">Không có dữ liệu.</td></tr>@endforelse
    </tbody></table>
</body>
</html>
