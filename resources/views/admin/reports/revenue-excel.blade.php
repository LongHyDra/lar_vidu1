<html xmlns:x="urn:schemas-microsoft-com:office:excel">

<head>
    <meta http-equiv="Content-Type" content="text/html; charset=utf-8">
    <style>
        body { font-family: 'Times New Roman', Arial, sans-serif; }
        table { border-collapse: collapse; width: 100%; }
        th, td { border: 1px solid #000000; padding: 6px 10px; font-size: 13px; }
        th { background-color: #dbeafe; font-weight: bold; text-align: center; }
        .title { font-size: 18px; font-weight: bold; text-align: center; margin-bottom: 5px; }
        .subtitle { font-size: 13px; font-style: italic; text-align: center; margin-bottom: 15px; }
        .text-center { text-align: center; }
        .text-end { text-align: right; }
        .total-row { background-color: #fef08a; font-weight: bold; }
    </style>
</head>

<body>
    <div class="title">BÁO CÁO DOANH THU & ĐỐI SOÁT BÁN HÀNG</div>
    <div class="subtitle">
        Cửa hàng Phụ Kiện Xe Máy 247 - Thời gian: {{ $from ?: 'Tất cả' }} đến {{ $to ?: date('d/m/Y') }}
    </div>

    @php
    $totalGrossRevenue = $orders->sum('total_price');
    $totalGhnShipping = $orders->sum('ghn_total_fee');
    $totalNetProducts = $totalGrossRevenue - $totalGhnShipping;
    
    $collectedOrders = $orders->filter(function($o) {
        $payStatus = $o->paymentTransaction->status ?? ($o->status === 'paid' ? 'paid' : '');
        return in_array($o->status, ['delivered', 'paid', 'cod_paid'], true) || $payStatus === 'paid';
    });
    $totalCollected = $collectedOrders->sum('total_price');
    @endphp

    <table>
        <thead>
            <tr>
                <th style="width: 50px;">STT</th>
                <th style="width: 80px;">Mã đơn</th>
                <th style="width: 140px;">Thời gian đặt</th>
                <th style="width: 180px;">Khách hàng</th>
                <th style="width: 110px;">Số điện thoại</th>
                <th style="width: 90px;">Cổng TT</th>
                <th style="width: 120px;">Tiền hàng (₫)</th>
                <th style="width: 100px;">Cước GHN (₫)</th>
                <th style="width: 130px;">Tổng tiền đơn (₫)</th>
                <th style="width: 120px;">Thanh toán</th>
                <th style="width: 130px;">Tiến độ giao</th>
            </tr>
        </thead>
        <tbody>
            @forelse($orders as $index => $order)
            @php
                $gateway = $order->paymentTransaction->gateway ?? (str_starts_with($order->status, 'cod') ? 'cod' : 'momo');
                $payStatus = $order->paymentTransaction->status ?? ($order->status === 'paid' ? 'paid' : 'pending');
                $isPaid = in_array($order->status, ['delivered', 'paid', 'cod_paid'], true) || $payStatus === 'paid';
                $productSubtotal = max(0, $order->total_price - ($order->ghn_total_fee ?? 0));
            @endphp
            <tr>
                <td class="text-center">{{ $index + 1 }}</td>
                <td class="text-center">#{{ $order->id }}</td>
                <td class="text-center">{{ $order->created_at->format('d/m/Y H:i') }}</td>
                <td>{{ $order->user->name ?? $order->name }}</td>
                <td class="text-center">{{ $order->phone }}</td>
                <td class="text-center">{{ strtoupper($gateway) }}</td>
                <td class="text-end">{{ number_format($productSubtotal, 0, ',', '.') }}₫</td>
                <td class="text-end">{{ number_format($order->ghn_total_fee ?? 0, 0, ',', '.') }}₫</td>
                <td class="text-end" style="font-weight: bold;">{{ number_format($order->total_price, 0, ',', '.') }}₫</td>
                <td class="text-center">{{ $isPaid ? 'Đã thu tiền' : 'Chờ thu tiền' }}</td>
                <td class="text-center">{{ $order->status }}</td>
            </tr>
            @empty
            <tr>
                <td colspan="11" class="text-center">Không có dữ liệu đơn hàng trong kỳ này.</td>
            </tr>
            @endforelse
        </tbody>
        <tfoot>
            <tr class="total-row">
                <td colspan="6" class="text-end">TỔNG CỘNG PHÁT SINH:</td>
                <td class="text-end">{{ number_format($totalNetProducts, 0, ',', '.') }}₫</td>
                <td class="text-end">{{ number_format($totalGhnShipping, 0, ',', '.') }}₫</td>
                <td class="text-end" style="color: #1d4ed8;">{{ number_format($totalGrossRevenue, 0, ',', '.') }}₫</td>
                <td colspan="2" class="text-center" style="color: #15803d;">Thực thu: {{ number_format($totalCollected, 0, ',', '.') }}₫</td>
            </tr>
        </tfoot>
    </table>

    <table style="border: none; margin-top: 30px;">
        <tr style="border: none;">
            <td colspan="7" style="border: none;"></td>
            <td colspan="4" style="border: none; text-align: center; font-style: italic;">
                Ngày lập báo cáo: {{ date('d/m/Y') }}<br>
                <strong>Người lập biểu</strong><br><br><br>
                (Ký và ghi rõ họ tên)
            </td>
        </tr>
    </table>
</body>

</html>