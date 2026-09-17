<!DOCTYPE html>
<html lang="vi">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Tạo yêu cầu hỗ trợ</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body class="bg-light">
<div class="container py-5" style="max-width:760px">
    <div class="bg-white rounded-4 shadow-sm p-4">
        <h1 class="h3 mb-1">Tạo yêu cầu hỗ trợ</h1>
        <p class="text-muted mb-4">Mô tả vấn đề để cửa hàng xử lý nhanh hơn.</p>
        <form action="{{ route('user.tickets.store') }}" method="POST">
            @csrf
            <div class="mb-3"><label class="form-label fw-bold">Tiêu đề</label><input name="subject" value="{{ old('subject') }}" class="form-control" required maxlength="150"></div>
            <div class="mb-3"><label class="form-label fw-bold">Nội dung</label><textarea name="message" rows="7" class="form-control" required maxlength="5000">{{ old('message') }}</textarea></div>
            <button class="btn btn-primary">Gửi yêu cầu</button>
            <a href="{{ route('user.tickets.index') }}" class="btn btn-link">Hủy</a>
        </form>
    </div>
</div>
</body>
</html>
