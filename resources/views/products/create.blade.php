<!DOCTYPE html>
<html lang="vi">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Thêm Sản Phẩm | PHỤ KIỆN XE MÁY</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.0/css/all.min.css">
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700;800&display=swap" rel="stylesheet">
    <style>
        * { box-sizing: border-box; }
        body { background: #f0f4f8; font-family: 'Inter', sans-serif; min-height: 100vh; }
        .sidebar { width: 260px; height: 100vh; position: fixed; top: 0; left: 0; background: #fff; border-right: 1px solid #e2e8f0; display: flex; flex-direction: column; z-index: 100; box-shadow: 2px 0 12px rgba(0,0,0,0.06); }
        .sidebar-brand { padding: 18px 18px 16px; border-bottom: 1px solid #f1f5f9; display: flex; align-items: center; gap: 11px; background: linear-gradient(180deg, #f8fafc 0%, #ffffff 100%); }
        .brand-icon-wrap { position: relative; width: 42px; height: 42px; flex-shrink: 0; }
        .brand-icon-inner { width: 100%; height: 100%; background: linear-gradient(135deg, #0f172a, #1e293b); border: 1px solid rgba(255,255,255,0.2); border-radius: 11px; display: flex; align-items: center; justify-content: center; color: #60a5fa; font-size: 1.15rem; }
        .sidebar-nav { padding: 12px 10px; }
        .nav-label { font-size: 0.65rem; font-weight: 700; text-transform: uppercase; letter-spacing: 1px; color: #94a3b8; padding: 10px 10px 6px; }
        .nav-link-item { display: flex; align-items: center; gap: 10px; padding: 10px 14px; border-radius: 9px; color: #64748b; text-decoration: none; font-weight: 500; font-size: 0.875rem; margin-bottom: 2px; }
        .nav-link-item.active { background: #eff6ff; color: #2563eb; font-weight: 600; }
        .main-content { margin-left: 260px; padding: 28px 32px; }
        .form-card { background: #fff; border-radius: 16px; box-shadow: 0 2px 12px rgba(0,0,0,0.05); border: 1px solid #f1f5f9; overflow: hidden; margin-bottom: 24px; }
        .fc-header { padding: 18px 24px; border-bottom: 1px solid #f1f5f9; display: flex; justify-content: space-between; align-items: center; background: linear-gradient(135deg, #f8fafc, #f1f5f9); }
        .fc-body { padding: 26px 28px; }
        .field-label { font-size: 0.82rem; font-weight: 600; color: #374151; margin-bottom: 6px; display: flex; align-items: center; gap: 5px; }
        .req { color: #e11d48; }
        .btn-save { background: linear-gradient(135deg, #3b82f6, #2563eb); color: #fff; padding: 10px 24px; border-radius: 10px; font-weight: 700; border: none; cursor: pointer; }
    </style>
</head>
<body>

<div class="sidebar">
    <div class="sidebar-brand">
        <div class="brand-icon-wrap"><div class="brand-icon-inner"><i class="fa-solid fa-motorcycle"></i></div></div>
        <div class="fw-bold fs-6 ms-2">PHỤ KIỆN XE MÁY 247</div>
    </div>
    <div class="sidebar-nav">
        <div class="nav-label">Quản lý</div>
        <a href="{{ route('categories.index') }}" class="nav-link-item"><i class="fa-solid fa-tags me-2"></i> Danh Mục Phụ Kiện</a>
        <a href="{{ route('products.index') }}" class="nav-link-item active"><i class="fa-solid fa-box me-2"></i> Sản Phẩm Phụ Kiện</a>
        <a href="{{ route('admin.dashboard') }}" class="nav-link-item"><i class="fa-solid fa-chart-line me-2"></i> Bảng Điều Khiển</a>
    </div>
</div>

<div class="main-content">
    <div class="form-card">
        <div class="fc-header">
            <div class="fw-bold fs-5 text-dark"><i class="fa-solid fa-plus text-primary me-2"></i>Thêm sản phẩm mới</div>
            <a href="{{ route('products.index') }}" class="btn btn-sm btn-outline-secondary"><i class="fa-solid fa-arrow-left me-1"></i> Quay lại</a>
        </div>
        <div class="fc-body">
            @if($errors->any())
                <div class="alert alert-danger">
                    <ul class="mb-0">@foreach($errors->all() as $e)<li>{{ $e }}</li>@endforeach</ul>
                </div>
            @endif

            <form action="{{ route('products.store') }}" method="POST" enctype="multipart/form-data">
                @csrf
                <div class="row g-4">
                    <div class="col-md-6">
                        <div class="mb-3">
                            <label class="field-label">Danh mục phân loại <span class="req">*</span></label>
                            <select name="category_id" class="form-select" required>
                                <option value="">-- Chọn nhóm danh mục --</option>
                                @foreach($categories as $cat)
                                    <option value="{{ $cat->id }}" @selected(old('category_id') == $cat->id)>{{ $cat->name }}</option>
                                @endforeach
                            </select>
                        </div>
                        <div class="mb-3">
                            <label class="field-label">Thương hiệu</label>
                            <input type="text" name="brand" class="form-control" value="{{ old('brand') }}" placeholder="Brembo, Ohlins, Akrapovic...">
                        </div>
                        <div class="mb-3">
                            <label class="field-label">Tên sản phẩm <span class="req">*</span></label>
                            <input type="text" name="name" class="form-control" value="{{ old('name') }}" placeholder="Ví dụ: Đĩa phanh Galfer 245mm" required>
                        </div>
                    </div>

                    <div class="col-md-6">
                        <div class="mb-3">
                            <label class="field-label">Giá bán niêm yết (VNĐ) <span class="req">*</span></label>
                            <input type="number" name="price" class="form-control" value="{{ old('price') }}" required min="0">
                        </div>
                        <div class="mb-3">
                            <label class="field-label">Số lượng tồn kho <span class="req">*</span></label>
                            <input type="number" name="stock" class="form-control" value="{{ old('stock', 0) }}" required min="0">
                        </div>
                        <div class="mb-3">
                            <label class="field-label"><i class="fa-solid fa-upload text-primary me-1"></i> Tải ảnh từ máy tính (Khuyên dùng)</label>
                            <input type="file" name="image_file" class="form-control" accept="image/*" onchange="previewUpload(event)">
                            <div class="mt-2 d-none" id="previewBox">
                                <img id="imgDisplay" src="" alt="Preview" style="max-height: 100px; border-radius: 8px; border: 1px solid #cbd5e1;">
                            </div>
                        </div>
                    </div>

                    <div class="col-12">
                        <label class="field-label">Thuộc tính kỹ thuật (JSON tùy chọn)</label>
                        <textarea name="attributes" rows="2" class="form-control" placeholder='{"Mau":"Do","XuatXu":"Italy"}'>{{ old('attributes') }}</textarea>
                    </div>
                    <div class="col-12">
                        <label class="field-label">Mô tả sản phẩm</label>
                        <textarea name="description" rows="3" class="form-control">{{ old('description') }}</textarea>
                    </div>
                </div>

                <div class="d-flex justify-content-end gap-2 border-top pt-3 mt-4">
                    <a href="{{ route('products.index') }}" class="btn btn-light border">Hủy bỏ</a>
                    <button type="submit" class="btn-save"><i class="fa-solid fa-floppy-disk me-1"></i> Lưu sản phẩm</button>
                </div>
            </form>
        </div>
    </div>
</div>

<script>
    function previewUpload(e) {
        const file = e.target.files[0];
        if (file) {
            document.getElementById('imgDisplay').src = URL.createObjectURL(file);
            document.getElementById('previewBox').classList.remove('d-none');
        }
    }
</script>
</body>
</html>