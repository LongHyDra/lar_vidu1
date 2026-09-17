<!DOCTYPE html>
<html lang="vi">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Câu hỏi thường gặp | Phụ Kiện Xe Máy 247</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
    <style>
        body { background: #f8fafc; color: #0f172a; font-family: Arial, sans-serif; }
        .page { max-width: 900px; margin: 48px auto; padding: 0 18px; }
        .hero { background: #0f172a; color: #fff; border-radius: 16px; padding: 32px; margin-bottom: 24px; }
        .faq-item { background: #fff; border: 1px solid #e2e8f0; border-radius: 10px; margin-bottom: 12px; overflow: hidden; }
        .faq-item summary { cursor: pointer; padding: 18px 20px; font-weight: 700; list-style: none; }
        .faq-item summary::-webkit-details-marker { display: none; }
        .faq-item p { padding: 0 20px 18px; margin: 0; color: #475569; line-height: 1.6; }
    </style>
</head>
<body>
<div class="page">
    <section class="hero">
        <h1 class="h3 mb-2">Câu hỏi thường gặp</h1>
        <p class="mb-0 text-white-50">Thông tin nhanh về đặt hàng, thanh toán, giao nhận và bảo hành.</p>
    </section>

    <details class="faq-item" open>
        <summary>Làm thế nào để đặt hàng?</summary>
        <p>Chọn sản phẩm, thêm vào giỏ hàng, nhập thông tin giao nhận và chọn phương thức thanh toán trước khi xác nhận đơn.</p>
    </details>
    <details class="faq-item">
        <summary>Tôi có thể theo dõi đơn hàng ở đâu?</summary>
        <p>Sau khi đăng nhập, mở mục lịch sử đơn hàng để xem sản phẩm, tổng tiền và trạng thái mới nhất.</p>
    </details>
    <details class="faq-item">
        <summary>Có thể hủy đơn hàng không?</summary>
        <p>Bạn có thể hủy đơn trước khi đơn được giao. Tồn kho sẽ được hoàn lại sau khi hủy thành công.</p>
    </details>
    <details class="faq-item">
        <summary>Cửa hàng có hỗ trợ tư vấn không?</summary>
        <p>Có. Bạn có thể nhắn tin trực tiếp với admin bằng khung chat trên trang chủ.</p>
    </details>
    <details class="faq-item">
        <summary>Chính sách bảo hành như thế nào?</summary>
        <p>Sản phẩm chính hãng được hỗ trợ theo chính sách bảo hành của từng thương hiệu. Vui lòng giữ hóa đơn để được hỗ trợ.</p>
    </details>

    <a href="{{ route('welcome') }}" class="btn btn-primary mt-3">Về trang chủ</a>
</div>
</body>
</html>
