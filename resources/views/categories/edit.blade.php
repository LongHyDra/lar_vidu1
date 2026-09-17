<!DOCTYPE html>
<html lang="vi">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Chỉnh Sửa Danh Mục | PHỤ KIỆN XE MÁY</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.0/css/all.min.css">
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700;800&display=swap" rel="stylesheet">
    <style>
        * { box-sizing: border-box; }
        body { background: #f0f4f8; font-family: 'Inter', sans-serif; }
        .sidebar { width: 260px; height: 100vh; position: fixed; top: 0; left: 0; background: #fff; border-right: 1px solid #e2e8f0; display: flex; flex-direction: column; z-index: 100; box-shadow: 2px 0 12px rgba(0,0,0,0.06); }
        .sidebar-brand { padding: 18px 18px 16px; border-bottom: 1px solid #f1f5f9; display: flex; align-items: center; gap: 11px; background: linear-gradient(180deg, #f8fafc 0%, #ffffff 100%); }
        .brand-icon-wrap { position: relative; width: 42px; height: 42px; flex-shrink: 0; }
        .brand-icon-glow { position: absolute; inset: -2px; background: linear-gradient(135deg, #2563eb, #f59e0b); border-radius: 12px; filter: blur(4px); opacity: 0.7; }
        .brand-icon-inner { position: relative; width: 100%; height: 100%; background: linear-gradient(135deg, #0f172a, #1e293b); border: 1px solid rgba(255,255,255,0.2); border-radius: 11px; display: flex; align-items: center; justify-content: center; color: #60a5fa; font-size: 1.15rem; box-shadow: 0 4px 10px rgba(15,23,42,0.3); }
        .brand-text { font-size: 0.95rem; font-weight: 900; color: #0f172a; line-height: 1.1; display: flex; align-items: center; gap: 5px; }
        .brand-sub  { font-size: 0.7rem; color: #64748b; font-weight: 600; margin-top: 2px; }
        .badge-247-sm { background: linear-gradient(135deg, #f59e0b, #d97706); color: #fff; font-size: 0.65rem; font-weight: 900; padding: 1px 5px; border-radius: 4px; }
    </style>
</head>
<body>
<div class="sidebar">
    <div class="sidebar-brand">
        <div class="brand-icon-wrap">
            <div class="brand-icon-glow"></div>
            <div class="brand-icon-inner"><i class="fa-solid fa-motorcycle"></i></div>
        </div>
        <div>
            <div class="brand-text">PHỤ KIỆN XE MÁY <span class="badge-247-sm">247</span></div>
            <div class="brand-sub">Hệ thống đồ chơi chính hãng</div>
        </div>
    </div>

    <div class="sidebar-nav">
        <div class="nav-label">Quản lý</div>
        <a href="{{ route('categories.index') }}" class="nav-link-item active">
            <div class="nav-icon"><i class="fa-solid fa-tags"></i></div> Danh Mục Phụ Kiện
        </a>
        <a href="{{ route('products.index') }}" class="nav-link-item">
            <div class="nav-icon"><i class="fa-solid fa-box"></i></div> Sản Phẩm Phụ Kiện
        </a>
        @auth
            @if(Auth::user()->isAdmin())
                <div class="nav-label" style="margin-top: 20px;">Admin</div>
                <a href="{{ route('admin.dashboard') }}" class="nav-link-item">
                    <div class="nav-icon"><i class="fa-solid fa-chart-line"></i></div> Bảng Điều Khiển
                </a>
            @endif
        @endauth
    </div>
</div>

<div class="main-content">
    <div class="topbar">
        <div>
            <div class="topbar-title">Chỉnh Sửa Danh Mục</div>
            <div class="topbar-bc">Trang chủ &rsaquo; Danh mục &rsaquo; {{ $category->name }}</div>
        </div>

    </div>

    <div class="form-card">
        <div class="fc-header">
            <div class="fc-title">
                <div class="ic-wrap"><i class="fa-solid fa-pen-to-square"></i></div>
                Chỉnh sửa danh mục phụ kiện
            </div>
            <a href="{{ route('categories.index') }}" class="btn-back">
                <i class="fa-solid fa-arrow-left"></i> Quay lại
            </a>
        </div>
        <div class="fc-body">
            <div class="cur-info">
                <div class="cur-icon"><i class="fa-solid fa-tag"></i></div>
                <div>
                    <div style="font-size:0.72rem;color:#94a3b8;font-weight:600;text-transform:uppercase;">Đang chỉnh sửa</div>
                    <div style="font-size:0.92rem;font-weight:700;color:#1e293b;">
                        {{ $category->name }}
                        <span style="color:#94a3b8;font-size:0.8rem;font-weight:400;">(ID #{{ $category->id }})</span>
                    </div>
                </div>
            </div>

            @if($errors->any())
            <div class="alert-err">
                <strong><i class="fa-solid fa-circle-exclamation me-1"></i> Vui lòng kiểm tra lại:</strong>
                <ul class="mb-0 ps-3 mt-1">@foreach($errors->all() as $e)<li>{{ $e }}</li>@endforeach</ul>
            </div>
            @endif

            <form action="{{ route('categories.update', $category->id) }}" method="POST">
                @csrf
                @method('PUT')
                <div class="mb-3">
                    <label class="field-label">Tên danh mục <span class="req">*</span></label>
                    <input type="text" name="name"
                        class="form-control @error('name') is-invalid @enderror"
                        value="{{ old('name', $category->name) }}" required>
                    @error('name')<div class="invalid-feedback">{{ $message }}</div>@enderror
                </div>
                <div class="mb-4">
                    <label class="field-label">Mô tả chi tiết</label>
                    <textarea name="description" rows="4"
                        class="form-control @error('description') is-invalid @enderror">{{ old('description', $category->description) }}</textarea>
                    @error('description')<div class="invalid-feedback">{{ $message }}</div>@enderror
                </div>
                <div class="d-flex gap-2 justify-content-end border-top pt-3">
                    <a href="{{ route('categories.index') }}" class="btn-cancel"><i class="fa-solid fa-xmark"></i> Hủy</a>
                    <button type="submit" class="btn-save"><i class="fa-solid fa-floppy-disk"></i> Cập nhật danh mục</button>
                </div>
            </form>
        </div>
    </div>
</div>
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>