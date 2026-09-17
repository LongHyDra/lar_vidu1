<html xmlns:x="urn:schemas-microsoft-com:office:excel">

<head>
    <meta http-equiv="Content-Type" content="text/html; charset=utf-8">
    <style>
        body {
            font-family: 'Times New Roman', Arial, sans-serif;
        }

        table {
            border-collapse: collapse;
            width: 100%;
        }

        th,
        td {
            border: 1px solid #000000;
            padding: 6px 10px;
            font-size: 13px;
        }

        th {
            background-color: #dbeafe;
            font-weight: bold;
            text-align: center;
        }

        .title {
            font-size: 18px;
            font-weight: bold;
            text-align: center;
            margin-bottom: 5px;
        }

        .subtitle {
            font-size: 13px;
            font-style: italic;
            text-align: center;
            margin-bottom: 15px;
        }

        .text-center {
            text-align: center;
        }

        .text-end {
            text-align: right;
        }

        .total-row {
            background-color: #fef08a;
            font-weight: bold;
        }
    </style>
</head>

<body>
    <div class="title">BÁO CÁO THỐNG KÊ DOANH THU BÁN HÀNG</div>
    <div class="subtitle">
        Cửa hàng Phụ Kiện Xe Máy 247 - Thời gian: {{ $from ?: 'Tất cả' }} đến {{ $to ?: date('d/m/Y') }}
    </div>

    <table>
        <thead>
            <tr>
                <th style="width: 60px;">STT</th>
                <th style="width: 90px;">Mã đơn</th>
                <th style="width: 180px;">Khách hàng</th>
                <th style="width: 120px;">Số điện thoại</th>
                <th style="width: 140px;">Ngày đặt</th>
                <th style="width: 130px;">Trạng thái</th>
                <th style="width: 150px;">Doanh thu (VNĐ)</th>
            </tr>
        </thead>
        <tbody>
            @forelse($orders as $index => $order)
            <tr>
                <td class="text-center">{{ $index + 1 }}</td>
                <td class="text-center">#{{ $order->id }}</td>
                <td>{{ $order->user->name ?? $order->name }}</td>
                <td class="text-center">{{ $order->phone }}</td>
                <td class="text-center">{{ $order->created_at->format('d/m/Y H:i') }}</td>
                <td class="text-center">{{ $order->status }}</td>
                <td class="text-end">{{ number_format($order->total_price, 0, ',', '.') }}₫</td>
            </tr>
            @empty
            <tr>
                <td colspan="7" class="text-center">Không có dữ liệu đơn hàng trong kỳ này.</td>
            </tr>
            @endforelse
        </tbody>
        <tfoot>
            <tr class="total-row">
                <td colspan="6" class="text-end" style="text-align: right; font-weight: bold;">TỔNG CỘNG DOANH THU:</td>
                <td class="text-end" style="font-weight: bold; color: #1d4ed8;">{{ number_format($totalRevenue, 0, ',', '.') }}₫</td>
            </tr>
        </tfoot>
    </table>

    <table style="border: none; margin-top: 30px;">
        <tr style="border: none;">
            <td colspan="4" style="border: none;"></td>
            <td colspan="3" style="border: none; text-align: center; font-style: italic;">
                Ngày lập báo cáo: {{ date('d/m/Y') }}<br>
                <strong>Người lập biểu</strong><br><br><br>
                (Ký và ghi rõ họ tên)
            </td>
        </tr>
    </table>
</body>

</html>