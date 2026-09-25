<!DOCTYPE html>
<html lang="vi">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Chỉnh Sửa Sản Phẩm | PHỤ KIỆN XE MÁY</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.0/css/all.min.css">
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700;800&display=swap" rel="stylesheet">
    <style>
        * { box-sizing: border-box; }
        body { background: #f0f4f8; font-family: 'Inter', sans-serif; min-height: 100vh; }
        .sidebar { width: 260px; height: 100vh; position: fixed; top: 0; left: 0; background: #fff; border-right: 1px solid #e2e8f0; display: flex; flex-direction: column; z-index: 100; box-shadow: 2px 0 12px rgba(0,0,0,0.06); }
        .sidebar-brand { padding: 18px 18px 16px; border-bottom: 1px solid #f1f5f9; display: flex; align-items: center; gap: 11px; }
        .sidebar-nav { padding: 12px 10px; }
        .nav-link-item { display: flex; align-items: center; gap: 10px; padding: 10px 14px; border-radius: 9px; color: #64748b; text-decoration: none; font-weight: 500; font-size: 0.875rem; margin-bottom: 2px; }
        .nav-link-item.active { background: #eff6ff; color: #2563eb; font-weight: 600; }
        .main-content { margin-left: 260px; padding: 28px 32px; }
        .form-card { background: #fff; border-radius: 16px; box-shadow: 0 2px 12px rgba(0,0,0,0.05); border: 1px solid #f1f5f9; overflow: hidden; margin-bottom: 24px; }
        .fc-header { padding: 18px 24px; border-bottom: 1px solid #f1f5f9; display: flex; justify-content: space-between; align-items: center; background: linear-gradient(135deg, #f8fafc, #f1f5f9); }
        .fc-body { padding: 26px 28px; }
        .field-label { font-size: 0.82rem; font-weight: 600; color: #374151; margin-bottom: 6px; display: flex; align-items: center; gap: 5px; }
        .req { color: #e11d48; }
        .btn-save { background: linear-gradient(135deg, #f59e0b, #d97706); color: #fff; padding: 10px 24px; border-radius: 10px; font-weight: 700; border: none; cursor: pointer; }
    </style>
</head>
<body>

<div class="sidebar">
    <div class="sidebar-brand">
        <div class="fw-bold fs-6"><i class="fa-solid fa-motorcycle text-primary me-2"></i>PHỤ KIỆN XE MÁY 247</div>
    </div>
    <div class="sidebar-nav">
        <a href="{{ route('categories.index') }}" class="nav-link-item"><i class="fa-solid fa-tags me-2"></i> Danh Mục Phụ Kiện</a>
        <a href="{{ route('products.index') }}" class="nav-link-item active"><i class="fa-solid fa-box me-2"></i> Sản Phẩm Phụ Kiện</a>
        <a href="{{ route('admin.dashboard') }}" class="nav-link-item"><i class="fa-solid fa-chart-line me-2"></i> Bảng Điều Khiển</a>
    </div>
</div>

<div class="main-content">
    <div class="form-card">
        <div class="fc-header">
            <div class="fw-bold fs-5 text-dark"><i class="fa-solid fa-pen-to-square text-warning me-2"></i>Sửa sản phẩm: {{ $product->name }}</div>
            <a href="{{ route('products.index') }}" class="btn btn-sm btn-outline-secondary"><i class="fa-solid fa-arrow-left me-1"></i> Quay lại</a>
        </div>
        <div class="fc-body">
            @if($errors->any())
                <div class="alert alert-danger">
                    <ul class="mb-0">@foreach($errors->all() as $e)<li>{{ $e }}</li>@endforeach</ul>
                </div>
            @endif

            <form action="{{ route('products.update', $product) }}" method="POST" enctype="multipart/form-data">
                @csrf
                @method('PUT')
                <div class="row g-4">
                    <div class="col-md-6">
                        <div class="mb-3">
                            <label class="field-label">Danh mục phân loại <span class="req">*</span></label>
                            <select name="category_id" class="form-select" required>
                                @foreach($categories as $cat)
                                    <option value="{{ $cat->id }}" @selected(old('category_id', $product->category_id) == $cat->id)>{{ $cat->name }}</option>
                                @endforeach
                            </select>
                        </div>
                        <div class="mb-3">
                            <label class="field-label">Thương hiệu</label>
                            <input type="text" name="brand" class="form-control" value="{{ old('brand', $product->brand) }}">
                        </div>
                        <div class="mb-3">
                            <label class="field-label">Tên sản phẩm <span class="req">*</span></label>
                            <input type="text" name="name" class="form-control" value="{{ old('name', $product->name) }}" required>
                        </div>
                    </div>

                    <div class="col-md-6">
                        <div class="mb-3">
                            <label class="field-label">Giá bán niêm yết (VNĐ) <span class="req">*</span></label>
                            <input type="number" name="price" class="form-control" value="{{ old('price', $product->price) }}" required min="0">
                        </div>
                        <div class="mb-3">
                            <label class="field-label">Số lượng tồn kho <span class="req">*</span></label>
                            <input type="number" name="stock" class="form-control" value="{{ old('stock', $product->stock) }}" required min="0">
                        </div>
                        <div class="mb-3">
                            <label class="field-label"><i class="fa-solid fa-upload text-primary me-1"></i> Tải ảnh thay thế</label>
                            <input type="file" name="image_file" class="form-control" accept="image/*" onchange="previewUpload(event)">
                            <div class="mt-2" id="previewBox">
                                @if($product->image)
                                    <img id="imgDisplay" src="{{ asset($product->image) }}" alt="{{ $product->name }}" style="max-height: 100px; border-radius: 8px; border: 1px solid #cbd5e1;">
                                @else
                                    <img id="imgDisplay" src="" alt="Preview" class="d-none" style="max-height: 100px; border-radius: 8px; border: 1px solid #cbd5e1;">
                                @endif
                            </div>
                        </div>
                    </div>

                    <div class="col-12">
                        <label class="field-label">Thuộc tính kỹ thuật (JSON tùy chọn)</label>
                        <textarea name="attributes" rows="2" class="form-control">{{ old('attributes', $product->attributes ? json_encode($product->attributes, JSON_UNESCAPED_UNICODE) : '') }}</textarea>
                    </div>
                    <div class="col-12">
                        <label class="field-label">Mô tả sản phẩm</label>
                        <textarea name="description" rows="3" class="form-control">{{ old('description', $product->description) }}</textarea>
                    </div>
                </div>

                <div class="d-flex justify-content-end gap-2 border-top pt-3 mt-4">
                    <a href="{{ route('products.index') }}" class="btn btn-light border">Hủy bỏ</a>
                    <button type="submit" class="btn-save"><i class="fa-solid fa-floppy-disk me-1"></i> Cập nhật sản phẩm</button>
                </div>
            </form>
        </div>
    </div>
</div>

<script>
    function previewUpload(e) {
        const file = e.target.files[0];
        if (file) {
            const img = document.getElementById('imgDisplay');
            img.src = URL.createObjectURL(file);
            img.classList.remove('d-none');
        }
    }
</script>
</body>
</html>