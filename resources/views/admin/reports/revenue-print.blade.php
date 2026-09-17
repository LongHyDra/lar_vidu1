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
        * {
            box-sizing: border-box;
        }

        body {
            background-color: #525659;
            /* Màu nền xám chuẩn của trình đọc PDF */
            font-family: 'Roboto', 'Times New Roman', serif;
            color: #111827;
            margin: 0;
            padding: 0;
            -webkit-print-color-adjust: exact !important;
            print-color-adjust: exact !important;
        }

        /* Thanh điều hướng cố định phía trên */
        .preview-toolbar {
            position: sticky;
            top: 0;
            z-index: 1000;
            background: #1e293b;
            color: #fff;
            padding: 12px 24px;
            display: flex;
            align-items: center;
            justify-content: space-between;
            box-shadow: 0 4px 12px rgba(0, 0, 0, 0.25);
        }

        /* Giả lập trang giấy A4 */
        .a4-page {
            width: 210mm;
            min-height: 297mm;
            padding: 20mm 15mm 20mm 15mm;
            margin: 25px auto;
            background: #ffffff !important;
            color: #111827 !important;
            box-shadow: 0 8px 30px rgba(0, 0, 0, 0.35);
            border-radius: 4px;
            position: relative;
        }

        /* Header công ty */
        .company-header {
            border-bottom: 2px solid #1e293b;
            padding-bottom: 14px;
            margin-bottom: 22px;
        }

        .company-title {
            font-size: 1.15rem;
            font-weight: 800;
            text-transform: uppercase;
            color: #0f172a;
            letter-spacing: 0.5px;
        }

        .meta-text {
            font-size: 0.82rem;
            color: #475569;
            line-height: 1.45;
        }

        /* Tiêu đề báo cáo */
        .report-title-box {
            text-align: center;
            margin-bottom: 24px;
        }

        .report-main-title {
            font-size: 1.45rem;
            font-weight: 800;
            text-transform: uppercase;
            color: #0f172a;
            margin-bottom: 4px;
        }

        .report-subtitle {
            font-size: 0.88rem;
            color: #64748b;
            font-style: italic;
        }

        /* Thẻ tóm tắt KPIs */
        .kpi-wrapper {
            display: grid;
            grid-template-columns: repeat(3, 1fr);
            gap: 12px;
            margin-bottom: 22px;
        }

        .kpi-card {
            border: 1px solid #e2e8f0;
            border-radius: 8px;
            padding: 10px 14px;
            background: #f8fafc !important;
        }

        .kpi-label {
            font-size: 0.72rem;
            text-transform: uppercase;
            font-weight: 700;
            color: #64748b;
            margin-bottom: 2px;
        }

        .kpi-val {
            font-size: 1.1rem;
            font-weight: 800;
            color: #0f172a;
        }

        /* Bảng dữ liệu */
        .report-table {
            width: 100%;
            border-collapse: collapse;
            font-size: 0.82rem;
            margin-bottom: 24px;
        }

        .report-table th {
            background-color: #0f172a !important;
            color: #ffffff !important;
            font-weight: 700;
            text-transform: uppercase;
            font-size: 0.75rem;
            border: 1px solid #0f172a;
            padding: 8px 10px;
            text-align: center;
        }

        .report-table td {
            border: 1px solid #cbd5e1;
            padding: 8px 10px;
            color: #1e293b;
        }

        .report-table tbody tr:nth-child(even) {
            background-color: #f8fafc !important;
        }

        .report-table tfoot td {
            background-color: #e2e8f0 !important;
            font-weight: 800;
            border: 1px solid #94a3b8;
        }

        /* Huy hiệu trạng thái tiếng Việt */
        .badge-status {
            display: inline-block;
            padding: 3px 8px;
            font-size: 0.72rem;
            font-weight: 600;
            border-radius: 4px;
            border: 1px solid transparent;
        }

        .st-packaging {
            background: #fef3c7;
            color: #b45309;
            border-color: #fde68a;
        }

        .st-delivered {
            background: #dcfce7;
            color: #15803d;
            border-color: #bbf7d0;
        }

        .st-shipping {
            background: #e0f2fe;
            color: #0369a1;
            border-color: #bae6fd;
        }

        .st-confirmed {
            background: #ede9fe;
            color: #6d28d9;
            border-color: #ddd6fe;
        }

        .st-pending {
            background: #f1f5f9;
            color: #475569;
            border-color: #e2e8f0;
        }

        /* Khối chữ ký */
        .signature-section {
            margin-top: 36px;
            display: grid;
            grid-template-columns: repeat(3, 1fr);
            text-align: center;
            page-break-inside: avoid;
        }

        .sig-title {
            font-size: 0.82rem;
            font-weight: 700;
            text-transform: uppercase;
            color: #0f172a;
            margin-bottom: 2px;
        }

        .sig-sub {
            font-size: 0.72rem;
            color: #64748b;
            font-style: italic;
            margin-bottom: 60px;
        }

        /* Tối ưu riêng cho máy in và xuất PDF */
        @media print {
            body {
                background: #fff !important;
            }

            .preview-toolbar {
                display: none !important;
            }

            .a4-page {
                box-shadow: none !important;
                margin: 0 !important;
                width: 100% !important;
                min-height: auto !important;
                padding: 0 !important;
                border-radius: 0 !important;
            }

            @page {
                size: A4 portrait;
                margin: 15mm 12mm;
            }
        }
    </style>
</head>

<body>

    <!-- THANH ĐIỀU KHIỂN CÔNG CỤ -->
    <div class="preview-toolbar no-print">
        <div class="d-flex align-items-center gap-3">
            <span class="badge bg-primary px-3 py-2 fs-6"><i class="fa-solid fa-file-pdf me-2"></i>Xem trước bản in A4</span>
            <span class="small text-slate-300">Nhấn nút bên phải để in trực tiếp hoặc lưu dưới dạng tệp PDF.</span>
        </div>
        <div class="d-flex align-items-center gap-2">
            <button onclick="window.print()" class="btn btn-success btn-sm fw-bold px-3">
                <i class="fa-solid fa-print me-1"></i> In / Lưu thành PDF
            </button>
            <button onclick="window.close()" class="btn btn-outline-light btn-sm px-3">
                <i class="fa-solid fa-xmark me-1"></i> Đóng
            </button>
        </div>
    </div>

    <!-- NỘI DUNG TRANG GIẤY A4 -->
    <div class="a4-page">
        @php
        $statusMap = [
        'pending' => ['label' => 'Chờ xác nhận', 'class' => 'st-pending'],
        'confirmed' => ['label' => 'Đã xác nhận', 'class' => 'st-confirmed'],
        'packaging' => ['label' => 'Đang đóng gói', 'class' => 'st-packaging'],
        'shipping' => ['label' => 'Đang giao hàng', 'class' => 'st-shipping'],
        'delivered' => ['label' => 'Giao thành công', 'class' => 'st-delivered'],
        'cancelled' => ['label' => 'Đã hủy đơn', 'class' => 'st-pending'],
        ];

        $totalCount = $orders->count();
        $totalSum = $orders->sum('total_price');
        $avgOrder = $totalCount > 0 ? $totalSum / $totalCount : 0;
        @endphp

        <!-- 1. THÔNG TIN DOANH NGHIỆP / CỬA HÀNG -->
        <div class="company-header d-flex justify-content-between align-items-start">
            <div>
                <div class="company-title"><i class="fa-solid fa-motorcycle text-primary me-2"></i>CỬA HÀNG PHỤ KIỆN XE MÁY 247</div>
                <div class="meta-text">Hệ thống phân phối linh kiện & phụ tùng nâng cấp xe máy chính hãng</div>
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

        <!-- 2. TIÊU ĐỀ BÁO CÁO -->
        <div class="report-title-box">
            <h1 class="report-main-title">BÁO CÁO THỐNG KÊ DOANH THU BÁN HÀNG</h1>
            <div class="report-subtitle">
                @if($from && $to)
                Thời gian thống kê: Từ ngày <strong>{{ date('d/m/Y', strtotime($from)) }}</strong> đến ngày <strong>{{ date('d/m/Y', strtotime($to)) }}</strong>
                @elseif($from)
                Thời gian thống kê: Từ ngày <strong>{{ date('d/m/Y', strtotime($from)) }}</strong> đến nay
                @elseif($to)
                Thời gian thống kê: Toàn bộ dữ liệu đến ngày <strong>{{ date('d/m/Y', strtotime($to)) }}</strong>
                @else
                Thời gian thống kê: <strong>Toàn bộ thời gian hoạt động</strong>
                @endif
            </div>
        </div>

        <!-- 3. TỔNG QUAN CHỈ SỐ (KPIS) -->
        <div class="kpi-wrapper">
            <div class="kpi-card">
                <div class="kpi-label">Tổng số đơn hàng</div>
                <div class="kpi-val text-primary">{{ number_format($totalCount) }} <span class="fs-6 fw-normal text-muted">đơn</span></div>
            </div>
            <div class="kpi-card">
                <div class="kpi-label">Tổng doanh thu ghi nhận</div>
                <div class="kpi-val text-success">{{ number_format($totalSum, 0, ',', '.') }}₫</div>
            </div>
            <div class="kpi-card">
                <div class="kpi-label">Giá trị trung bình / đơn (AOV)</div>
                <div class="kpi-val text-dark">{{ number_format($avgOrder, 0, ',', '.') }}₫</div>
            </div>
        </div>

        <!-- 4. BẢNG DỮ LIỆU ĐƠN HÀNG CHI TIẾT -->
        <table class="report-table">
            <thead>
                <tr>
                    <th style="width: 42px;">STT</th>
                    <th style="width: 80px;">Mã đơn</th>
                    <th style="width: 125px;">Thời gian</th>
                    <th>Khách hàng</th>
                    <th style="width: 110px;">Điện thoại</th>
                    <th style="width: 120px;">Trạng thái</th>
                    <th style="width: 135px;" class="text-end">Doanh số (VNĐ)</th>
                </tr>
            </thead>
            <tbody>
                @forelse($orders as $idx => $order)
                @php
                $stInfo = $statusMap[$order->status] ?? ['label' => ucfirst($order->status), 'class' => 'st-pending'];
                @endphp
                <tr>
                    <td class="text-center text-muted">{{ $idx + 1 }}</td>
                    <td class="text-center fw-bold">#{{ $order->id }}</td>
                    <td class="text-center">{{ $order->created_at ? $order->created_at->format('d/m/Y H:i') : '—' }}</td>
                    <td class="fw-semibold">{{ $order->user->name ?? $order->name ?? 'Khách vãng lai' }}</td>
                    <td class="text-center">{{ $order->phone ?? '—' }}</td>
                    <td class="text-center">
                        <span class="badge-status {{ $stInfo['class'] }}">
                            {{ $stInfo['label'] }}
                        </span>
                    </td>
                    <td class="text-end fw-bold">{{ number_format($order->total_price, 0, ',', '.') }}₫</td>
                </tr>
                @empty
                <tr>
                    <td colspan="7" class="text-center py-4 text-muted">Không tìm thấy đơn hàng nào trong khoảng thời gian đã chọn.</td>
                </tr>
                @endforelse
            </tbody>
            <tfoot>
                <tr>
                    <td colspan="6" class="text-end text-uppercase pe-3">Tổng cộng doanh thu thực nhận:</td>
                    <td class="text-end text-danger fs-6">{{ number_format($totalSum, 0, ',', '.') }}₫</td>
                </tr>
            </tfoot>
        </table>

        <div class="small text-muted mb-4 fst-italic">
            * Báo cáo chỉ ghi nhận các đơn hàng hợp lệ đã phát sinh doanh thu (đã loại trừ toàn bộ đơn hủy khỏi hệ thống).
        </div>

        <!-- 5. CHỮ KÝ XÁC NHẬN -->
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