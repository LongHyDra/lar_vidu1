<!DOCTYPE html>
<html lang="vi">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Báo Cáo Doanh Thu Bán Hàng - PKXM 247</title>
    <!-- Bootstrap 5 CSS -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
    <!-- FontAwesome 6 -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.0/css/all.min.css">
    <!-- Google Fonts -->
    <link href="https://fonts.googleapis.com/css2?family=Roboto:ital,wght@0,300;0,400;0,500;0,700;1,400&display=swap" rel="stylesheet">

    <style>
        * { box-sizing: border-box; }
        body {
            background-color: #525659;
            font-family: 'Roboto', 'Times New Roman', serif;
            color: #111827;
            margin: 0; padding: 0;
            -webkit-print-color-adjust: exact !important;
            print-color-adjust: exact !important;
        }

        .preview-toolbar {
            position: sticky; top: 0; z-index: 1000;
            background: #1e293b; color: #fff; padding: 12px 24px;
            display: flex; align-items: center; justify-content: space-between;
            box-shadow: 0 4px 12px rgba(0, 0, 0, 0.25);
        }

        .a4-page {
            width: 210mm; min-height: 297mm;
            padding: 18mm 14mm 20mm 14mm;
            margin: 25px auto;
            background: #ffffff !important;
            color: #111827 !important;
            box-shadow: 0 8px 30px rgba(0, 0, 0, 0.35);
            border-radius: 4px;
        }

        .company-header {
            border-bottom: 2px solid #0f172a;
            padding-bottom: 12px; margin-bottom: 20px;
        }
        .company-title {
            font-size: 1.15rem; font-weight: 800; text-transform: uppercase; color: #0f172a;
        }
        .meta-text { font-size: 0.8rem; color: #475569; line-height: 1.4; }

        .report-title-box { text-align: center; margin-bottom: 20px; }
        .report-main-title {
            font-size: 1.35rem; font-weight: 800; text-transform: uppercase; color: #0f172a; margin-bottom: 4px;
        }
        .report-subtitle { font-size: 0.85rem; color: #64748b; font-style: italic; }

        /* TÁCH BẠCH 4 CHỈ SỐ KẾ TOÁN */
        .kpi-wrapper {
            display: grid; grid-template-columns: repeat(4, 1fr); gap: 10px; margin-bottom: 20px;
        }
        .kpi-card {
            border: 1px solid #cbd5e1; border-radius: 8px; padding: 10px 12px; background: #f8fafc !important;
        }
        .kpi-label { font-size: 0.7rem; text-transform: uppercase; font-weight: 700; color: #64748b; margin-bottom: 2px; }
        .kpi-val { font-size: 1.05rem; font-weight: 800; color: #0f172a; }

        .report-table {
            width: 100%; border-collapse: collapse; font-size: 0.78rem; margin-bottom: 20px;
        }
        .report-table th {
            background-color: #0f172a !important; color: #ffffff !important;
            font-weight: 700; text-transform: uppercase; font-size: 0.72rem;
            border: 1px solid #0f172a; padding: 7px 6px; text-align: center;
        }
        .report-table td {
            border: 1px solid #cbd5e1; padding: 7px 8px; color: #1e293b;
        }
        .report-table tbody tr:nth-child(even) { background-color: #f8fafc !important; }
        .report-table tfoot td {
            background-color: #f1f5f9 !important; font-weight: 800; border: 1px solid #94a3b8;
        }

        .badge-status {
            display: inline-block; padding: 2px 6px; font-size: 0.68rem; font-weight: 700; border-radius: 4px;
        }
        .st-success { background: #dcfce7; color: #15803d; }
        .st-warning { background: #fef3c7; color: #b45309; }
        .st-info    { background: #e0f2fe; color: #0369a1; }

        .signature-section {
            margin-top: 32px; display: grid; grid-template-columns: repeat(3, 1fr);
            text-align: center; page-break-inside: avoid;
        }
        .sig-title { font-size: 0.8rem; font-weight: 700; text-transform: uppercase; color: #0f172a; margin-bottom: 2px; }
        .sig-sub { font-size: 0.7rem; color: #64748b; font-style: italic; margin-bottom: 55px; }

        @media print {
            body { background: #fff !important; }
            .preview-toolbar { display: none !important; }
            .a4-page {
                box-shadow: none !important; margin: 0 !important; width: 100% !important;
                min-height: auto !important; padding: 0 !important;
            }
            @page { size: A4 portrait; margin: 12mm 10mm; }
        }
    </style>
</head>

<body>
    <!-- TOOLBAR -->
    <div class="preview-toolbar no-print">
        <div class="d-flex align-items-center gap-3">
            <span class="badge bg-primary px-3 py-2 fs-6"><i class="fa-solid fa-file-pdf me-2"></i>Bản In Kế Toán & Quản Trị</span>
            <span class="small text-white-50">Dữ liệu đã chuẩn hóa: Phân tách tiền hàng, cước ship GHN và doanh thu thực thu.</span>
        </div>
        <div class="d-flex align-items-center gap-2">
            <button onclick="window.print()" class="btn btn-success btn-sm fw-bold px-3">
                <i class="fa-solid fa-print me-1"></i> In / Lưu PDF
            </button>
            <button onclick="window.close()" class="btn btn-outline-light btn-sm px-3">
                <i class="fa-solid fa-xmark me-1"></i> Đóng
            </button>
        </div>
    </div>

    <!-- NỘI DUNG A4 -->
    <div class="a4-page">
        @php
        $totalOrdersCount = $orders->count();
        $totalGrossRevenue = $orders->sum('total_price'); // Tổng giá trị đơn hàng phát sinh
        $totalGhnShipping = $orders->sum('ghn_total_fee'); // Tổng cước vận chuyển GHN
        $totalNetProducts = $totalGrossRevenue - $totalGhnShipping; // Tổng tiền hàng thuần

        // Đơn đã thu tiền thực tế (đã giao thành công hoặc thanh toán online trước)
        $collectedOrders = $orders->filter(function($o) {
            $payStatus = $o->paymentTransaction->status ?? ($o->status === 'paid' ? 'paid' : '');
            return in_array($o->status, ['delivered', 'paid', 'cod_paid'], true) || $payStatus === 'paid';
        });

        $totalCollected = $collectedOrders->sum('total_price'); // Tiền thực thu về quỹ
        $totalPending = $totalGrossRevenue - $totalCollected;   // Tiền đang chờ thu (shipper giữ hoặc chưa giao)
        @endphp

        <!-- HEADER SHOP -->
        <div class="company-header d-flex justify-content-between align-items-start">
            <div>
                <div class="company-title"><i class="fa-solid fa-motorcycle text-primary me-2"></i>CỬA HÀNG PHỤ KIỆN XE MÁY 247</div>
                <div class="meta-text">Hệ thống phân phối đồ chơi & phụ tùng nâng cấp xe máy chính hãng</div>
                <div class="meta-text">Địa chỉ: 123 Đường Cầu Giấy, P. Dịch Vọng Hậu, Q. Cầu Giấy, Hà Nội</div>
                <div class="meta-text">Hotline CSKH: 0901.234.567 | Website: https://phukienxemay247.vn</div>
            </div>
            <div class="text-end">
                <div class="fw-bold small">MẪU SỐ: 02-BCTK/BH</div>
                <div class="meta-text">Số: <strong>BC-{{ now()->format('Ymd') }}-{{ rand(100, 999) }}</strong></div>
                <div class="meta-text">Ngày xuất: {{ now()->format('d/m/Y H:i') }}</div>
                <div class="meta-text">Người tạo: <strong>{{ Auth::user()->name ?? 'Quản trị viên' }}</strong></div>
            </div>
        </div>

        <!-- TIÊU ĐỀ BÁO CÁO -->
        <div class="report-title-box">
            <h1 class="report-main-title">BÁO CÁO DOANH THU & ĐỐI SOÁT BÁN HÀNG</h1>
            <div class="report-subtitle">
                @if($from && $to)
                    Kỳ báo cáo: Từ ngày <strong>{{ date('d/m/Y', strtotime($from)) }}</strong> đến ngày <strong>{{ date('d/m/Y', strtotime($to)) }}</strong>
                @elseif($from)
                    Kỳ báo cáo: Từ ngày <strong>{{ date('d/m/Y', strtotime($from)) }}</strong> đến nay
                @elseif($to)
                    Kỳ báo cáo: Đến ngày <strong>{{ date('d/m/Y', strtotime($to)) }}</strong>
                @else
                    Kỳ báo cáo: <strong>Tháng {{ now()->format('m/Y') }} (Tất cả đơn hàng phát sinh)</strong>
                @endif
            </div>
        </div>

        <!-- BẢNG TỔNG HỢP CHỈ SỐ TÀI CHÍNH CHUẨN XÁC -->
        <div class="kpi-wrapper">
            <div class="kpi-card">
                <div class="kpi-label">Đơn hàng phát sinh</div>
                <div class="kpi-val text-primary">{{ number_format($totalOrdersCount) }} <span class="fs-6 fw-normal text-muted">đơn</span></div>
                <small class="text-muted" style="font-size:0.68rem;">Tổng GT: {{ number_format($totalGrossRevenue, 0, ',', '.') }}₫</small>
            </div>
            <div class="kpi-card">
                <div class="kpi-label">Thực thu vào quỹ</div>
                <div class="kpi-val text-success">{{ number_format($totalCollected, 0, ',', '.') }}₫</div>
                <small class="text-success" style="font-size:0.68rem;">{{ $collectedOrders->count() }} đơn đã chốt tiền</small>
            </div>
            <div class="kpi-card">
                <div class="kpi-label">Chờ thu (COD / Đang giao)</div>
                <div class="kpi-val text-warning">{{ number_format($totalPending, 0, ',', '.') }}₫</div>
                <small class="text-muted" style="font-size:0.68rem;">{{ $totalOrdersCount - $collectedOrders->count() }} đơn chưa quyết toán</small>
            </div>
            <div class="kpi-card">
                <div class="kpi-label">Cước trả GHN (Thu hộ)</div>
                <div class="kpi-val text-secondary">{{ number_format($totalGhnShipping, 0, ',', '.') }}₫</div>
                <small class="text-muted" style="font-size:0.68rem;">Doanh thu thuần: {{ number_format($totalNetProducts, 0, ',', '.') }}₫</small>
            </div>
        </div>

        <!-- BẢNG CHI TIẾT TỪNG ĐƠN HÀNG -->
        <table class="report-table">
            <thead>
                <tr>
                    <th style="width: 32px;">STT</th>
                    <th style="width: 58px;">Mã đơn</th>
                    <th style="width: 105px;">Ngày đặt</th>
                    <th>Khách hàng</th>
                    <th style="width: 90px;">Điện thoại</th>
                    <th style="width: 80px;">Cổng TT</th>
                    <th style="width: 85px;" class="text-end">Tiền hàng</th>
                    <th style="width: 70px;" class="text-end">Cước ship</th>
                    <th style="width: 90px;" class="text-end">Tổng đơn</th>
                    <th style="width: 90px;" class="text-center">Thanh toán</th>
                    <th style="width: 95px;" class="text-center">Tiến độ giao</th>
                </tr>
            </thead>
            <tbody>
                @forelse($orders as $idx => $order)
                @php
                    $gateway = $order->paymentTransaction->gateway ?? (str_starts_with($order->status, 'cod') ? 'cod' : 'momo');
                    $payStatus = $order->paymentTransaction->status ?? ($order->status === 'paid' ? 'paid' : 'pending');
                    $isPaid = in_array($order->status, ['delivered', 'paid', 'cod_paid'], true) || $payStatus === 'paid';
                    $productSubtotal = max(0, $order->total_price - ($order->ghn_total_fee ?? 0));
                @endphp
                <tr>
                    <td class="text-center text-muted">{{ $idx + 1 }}</td>
                    <td class="text-center fw-bold">#{{ $order->id }}</td>
                    <td class="text-center">{{ $order->created_at ? $order->created_at->format('d/m/Y H:i') : '—' }}</td>
                    <td class="fw-semibold">{{ $order->user->name ?? $order->name ?? 'Khách vãng lai' }}</td>
                    <td class="text-center">{{ $order->phone ?? '—' }}</td>
                    <td class="text-center font-monospace">
                        {{ strtoupper($gateway) }}
                    </td>
                    <td class="text-end">{{ number_format($productSubtotal, 0, ',', '.') }}₫</td>
                    <td class="text-end text-muted">{{ number_format($order->ghn_total_fee ?? 0, 0, ',', '.') }}₫</td>
                    <td class="text-end fw-bold text-dark">{{ number_format($order->total_price, 0, ',', '.') }}₫</td>
                    
                    <!-- CỘT THANH TOÁN (TÁCH BIỆT RÕ RÀNG VỚI GIAO HÀNG) -->
                    <td class="text-center">
                        @if($isPaid)
                            <span class="badge-status st-success">Đã thu tiền</span>
                        @else
                            <span class="badge-status st-warning">Chờ thu tiền</span>
                        @endif
                    </td>

                    <!-- TIẾN ĐỘ VẬN CHUYỂN -->
                    <td class="text-center">
                        @php
                        $shipLabel = match($order->status) {
                            'delivered' => 'Giao thành công',
                            'shipping' => 'Đang giao hàng',
                            'packaging' => 'Đang đóng gói',
                            'confirmed' => 'Đã xác nhận',
                            default => 'Chờ xác nhận'
                        };
                        $shipClass = match($order->status) {
                            'delivered' => 'st-success',
                            'shipping' => 'st-info',
                            default => 'st-warning'
                        };
                        @endphp
                        <span class="badge-status {{ $shipClass }}">{{ $shipLabel }}</span>
                    </td>
                </tr>
                @empty
                <tr>
                    <td colspan="11" class="text-center py-4 text-muted">Không có dữ liệu đơn hàng trong kỳ báo cáo này.</td>
                </tr>
                @endforelse
            </tbody>
            <tfoot>
                <tr>
                    <td colspan="6" class="text-end text-uppercase pe-2">Tổng cộng toàn kỳ:</td>
                    <td class="text-end">{{ number_format($totalNetProducts, 0, ',', '.') }}₫</td>
                    <td class="text-end text-muted">{{ number_format($totalGhnShipping, 0, ',', '.') }}₫</td>
                    <td class="text-end text-primary fs-6">{{ number_format($totalGrossRevenue, 0, ',', '.') }}₫</td>
                    <td colspan="2" class="text-center text-success fw-bold">
                        Đã thu: {{ number_format($totalCollected, 0, ',', '.') }}₫
                    </td>
                </tr>
            </tfoot>
        </table>

        <!-- CHÚ THÍCH KẾ TOÁN MINH BẠCH -->
        <div class="small text-muted mb-4 fst-italic" style="font-size: 0.72rem; line-height: 1.5;">
            * Ghi chú đối soát: Cửa hàng ghi nhận thực thu <strong>{{ number_format($totalCollected, 0, ',', '.') }} VNĐ</strong> vào quỹ. Số tiền <strong>{{ number_format($totalPending, 0, ',', '.') }} VNĐ</strong> còn lại thuộc các đơn COD đang trong tiến trình giao nhận, chưa hoàn tất thu tiền mặt. Cước vận chuyển GHN là khoản thu chi hộ cho đối tác bưu chính.
        </div>

        <!-- KHỐI CHỮ KÝ -->
        <div class="signature-section">
            <div>
                <div class="sig-title">Người lập biểu</div>
                <div class="sig-sub">(Ký và ghi rõ họ tên)</div>
                <div class="fw-bold mt-5">{{ Auth::user()->name ?? 'Người lập' }}</div>
            </div>
            <div>
                <div class="sig-title">Kế toán trưởng</div>
                <div class="sig-sub">(Ký và ghi rõ họ tên)</div>
                <div class="text-muted small mt-5">........................................</div>
            </div>
            <div>
                <div class="sig-title">Giám đốc duyệt</div>
                <div class="sig-sub">(Ký, đóng dấu nếu có)</div>
                <div class="text-muted small mt-5">........................................</div>
            </div>
        </div>
    </div>
</body>

</html>