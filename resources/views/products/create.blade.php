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
        /* SIDEBAR */
        .sidebar { width: 260px; height: 100vh; position: fixed; top: 0; left: 0; background: #fff; border-right: 1px solid #e2e8f0; display: flex; flex-direction: column; z-index: 100; box-shadow: 2px 0 12px rgba(0,0,0,0.06); }
        .sidebar-brand { padding: 18px 18px 16px; border-bottom: 1px solid #f1f5f9; display: flex; align-items: center; gap: 11px; background: linear-gradient(180deg, #f8fafc 0%, #ffffff 100%); }
        .brand-icon-wrap { position: relative; width: 42px; height: 42px; flex-shrink: 0; }
        .brand-icon-glow { position: absolute; inset: -2px; background: linear-gradient(135deg, #2563eb, #f59e0b); border-radius: 12px; filter: blur(4px); opacity: 0.7; }
        .brand-icon-inner { position: relative; width: 100%; height: 100%; background: linear-gradient(135deg, #0f172a, #1e293b); border: 1px solid rgba(255,255,255,0.2); border-radius: 11px; display: flex; align-items: center; justify-content: center; color: #60a5fa; font-size: 1.15rem; box-shadow: 0 4px 10px rgba(15,23,42,0.3); }
        .brand-text { font-size: 0.95rem; font-weight: 900; color: #0f172a; line-height: 1.1; display: flex; align-items: center; gap: 5px; }
        .brand-sub { font-size: 0.7rem; color: #64748b; font-weight: 600; margin-top: 2px; }
        .badge-247-sm { background: linear-gradient(135deg, #f59e0b, #d97706); color: #fff; font-size: 0.65rem; font-weight: 900; padding: 1px 5px; border-radius: 4px; }

        .sidebar-nav { padding: 12px 10px; }
        .nav-label { font-size: 0.65rem; font-weight: 700; text-transform: uppercase; letter-spacing: 1px; color: #94a3b8; padding: 10px 10px 6px; }
        .nav-link-item { display: flex; align-items: center; gap: 10px; padding: 10px 14px; border-radius: 9px; color: #64748b; text-decoration: none; font-weight: 500; font-size: 0.875rem; margin-bottom: 2px; transition: all 0.18s; }
        .nav-link-item:hover { background: #f1f5f9; color: #1e293b; }
        .nav-link-item.active { background: #eff6ff; color: #2563eb; font-weight: 600; }
        .nav-icon { width: 32px; height: 32px; border-radius: 8px; display: flex; align-items: center; justify-content: center; font-size: 0.8rem; background: #f1f5f9; }
        .nav-link-item.active .nav-icon { background: #dbeafe; color: #2563eb; }
        /* MAIN */
        .main-content { margin-left: 260px; padding: 28px 32px; }
        /* TOPBAR */
        .topbar { display: flex; justify-content: space-between; align-items: center; background: #fff; padding: 14px 22px; border-radius: 14px; box-shadow: 0 2px 8px rgba(0,0,0,0.04); margin-bottom: 24px; border: 1px solid #f1f5f9; }
        .topbar-title { font-size: 1rem; font-weight: 700; color: #1e293b; }
        .topbar-bc { font-size: 0.8rem; color: #94a3b8; }
        /* FORM CARD */
        .form-card { background: #fff; border-radius: 16px; box-shadow: 0 2px 12px rgba(0,0,0,0.05); border: 1px solid #f1f5f9; overflow: hidden; margin-bottom: 24px; }
        .fc-header { padding: 18px 24px; border-bottom: 1px solid #f1f5f9; display: flex; justify-content: space-between; align-items: center; background: linear-gradient(135deg, #f8fafc, #f1f5f9); }
        .fc-title { font-size: 1rem; font-weight: 700; color: #1e293b; display: flex; align-items: center; gap: 9px; }
        .ic-wrap { width: 34px; height: 34px; border-radius: 9px; background: #dbeafe; color: #2563eb; display: flex; align-items: center; justify-content: center; }
        .btn-back { display: inline-flex; align-items: center; gap: 6px; background: #f1f5f9; color: #475569; border-radius: 8px; padding: 7px 15px; font-size: 0.82rem; font-weight: 600; text-decoration: none; border: 1px solid #e2e8f0; transition: all 0.15s; }
        .btn-back:hover { background: #e2e8f0; color: #1e293b; }
        .fc-body { padding: 26px 28px; }
        /* FIELDS */
        .field-label { font-size: 0.82rem; font-weight: 600; color: #374151; margin-bottom: 6px; display: flex; align-items: center; gap: 5px; }
        .req { color: #e11d48; }
        .form-control, .form-select { border: 1.5px solid #e2e8f0; border-radius: 9px; font-size: 0.88rem; font-family: 'Inter', sans-serif; color: #1e293b; padding: 10px 14px; background: #fafbfc; transition: all 0.18s; }
        .form-control:focus, .form-select:focus { border-color: #3b82f6; box-shadow: 0 0 0 3px rgba(59,130,246,0.12); background: #fff; outline: none; }
        .form-control.is-invalid, .form-select.is-invalid { border-color: #e11d48; }
        /* Preview danh mục */
        .cat-preview { margin-top: 10px; padding: 10px 14px; background: #eff6ff; border: 1px solid #bfdbfe; border-radius: 9px; font-size: 0.82rem; color: #1d4ed8; font-weight: 500; display: none; align-items: center; gap: 7px; }
        .cat-preview.show { display: flex; }
        /* Buttons */
        .btn-save { display: inline-flex; align-items: center; gap: 8px; background: linear-gradient(135deg, #3b82f6, #2563eb); color: #fff; padding: 10px 24px; border-radius: 10px; font-weight: 700; border: none; cursor: pointer; box-shadow: 0 4px 12px rgba(37,99,235,0.25); transition: all 0.2s; }
        .btn-save:hover { background: linear-gradient(135deg, #2563eb, #1d4ed8); transform: translateY(-1px); }
        .btn-cancel { display: inline-flex; align-items: center; gap: 6px; background: #f1f5f9; color: #475569; border: 1px solid #e2e8f0; border-radius: 10px; padding: 10px 20px; font-size: 0.88rem; font-weight: 600; text-decoration: none; }
        .btn-cancel:hover { background: #e2e8f0; color: #1e293b; }
        /* Alert */
        .alert-err { background: #fff1f2; border: 1px solid #fecdd3; color: #be123c; border-radius: 10px; padding: 12px 18px; margin-bottom: 18px; font-size: 0.85rem; }
        /* CATEGORY TABLE */
        .cat-table-card { background: #fff; border-radius: 16px; box-shadow: 0 2px 12px rgba(0,0,0,0.05); border: 1px solid #f1f5f9; overflow: hidden; }
        .ct-header { padding: 16px 24px; border-bottom: 1px solid #f1f5f9; display: flex; align-items: center; justify-content: space-between; }
        .ct-title { font-size: 0.92rem; font-weight: 700; color: #1e293b; display: flex; align-items: center; gap: 8px; }
        .count-pill { background: #eff6ff; color: #2563eb; border-radius: 20px; padding: 2px 10px; font-size: 0.72rem; font-weight: 700; }
        .tip-box { font-size: 0.78rem; color: #64748b; background: #f8fafc; border: 1px solid #e2e8f0; border-radius: 7px; padding: 5px 12px; display: flex; align-items: center; gap: 5px; }
        table.cat-tbl { width: 100%; border-collapse: collapse; }
        table.cat-tbl thead tr { background: #f8fafc; border-bottom: 1px solid #e9eef5; }
        table.cat-tbl thead th { padding: 11px 18px; font-size: 0.71rem; font-weight: 700; color: #64748b; text-transform: uppercase; letter-spacing: 0.6px; }
        table.cat-tbl tbody tr { border-bottom: 1px solid #f8fafc; transition: background 0.15s; cursor: pointer; }
        table.cat-tbl tbody tr:last-child { border-bottom: none; }
        table.cat-tbl tbody tr:hover { background: #eff6ff; }
        table.cat-tbl tbody tr.selected-row { background: #dbeafe; }
        table.cat-tbl td { padding: 13px 18px; vertical-align: middle; }
        .id-pill { width: 34px; height: 34px; border-radius: 8px; background: #f1f5f9; color: #475569; font-size: 0.75rem; font-weight: 700; display: inline-flex; align-items: center; justify-content: center; }
        .cat-name-tbl { font-weight: 600; color: #1e293b; font-size: 0.88rem; }
        .cat-desc-tbl { font-size: 0.78rem; color: #64748b; margin-top: 2px; }
        .btn-pick { display: inline-flex; align-items: center; gap: 5px; background: #eff6ff; color: #2563eb; border: 1px solid #bfdbfe; border-radius: 7px; font-size: 0.78rem; font-weight: 600; padding: 5px 12px; cursor: pointer; transition: all 0.15s; border: none; }
        .btn-pick:hover { background: #dbeafe; color: #1d4ed8; }
        .ct-footer { padding: 12px 24px; border-top: 1px solid #f1f5f9; font-size: 0.78rem; color: #94a3b8; }
    </style>
</head>
<body>

<!-- SIDEBAR -->
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
        <a href="{{ route('categories.index') }}" class="nav-link-item">
            <div class="nav-icon"><i class="fa-solid fa-tags"></i></div> Danh Mục Phụ Kiện
        </a>
        <a href="{{ route('products.index') }}" class="nav-link-item active">
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

<!-- MAIN -->
<div class="main-content">

    <!-- Topbar -->
    <div class="topbar">
        <div>
            <div class="topbar-title">Thêm Sản Phẩm Mới</div>
            <div class="topbar-bc">Trang chủ &rsaquo; Sản phẩm &rsaquo; Thêm mới</div>
        </div>
        <div class="d-flex align-items-center gap-3">
            <div style="font-size:0.8rem;color:#64748b;"><i class="fa-regular fa-clock me-1"></i>{{ now()->format('d/m/Y') }}</div>
        </div>
    </div>

    <!-- FORM CARD -->
    <div class="form-card">
        <div class="fc-header">
            <div class="fc-title">
                <div class="ic-wrap"><i class="fa-solid fa-plus"></i></div>
                Thêm sản phẩm phụ kiện mới
            </div>
            <a href="{{ route('products.index') }}" class="btn-back">
                <i class="fa-solid fa-arrow-left"></i> Quay lại
            </a>
        </div>
        <div class="fc-body">

            @if($errors->any())
            <div class="alert-err">
                <strong><i class="fa-solid fa-circle-exclamation me-1"></i> Vui lòng kiểm tra lại:</strong>
                <ul class="mb-0 ps-3 mt-1">@foreach($errors->all() as $e)<li>{{ $e }}</li>@endforeach</ul>
            </div>
            @endif

            <form action="{{ route('products.store') }}" method="POST" id="createForm">
                @csrf
                <div class="row g-4">
                    <!-- Cột trái -->
                    <div class="col-md-6">
                        <div class="mb-3">
                            <label class="field-label">
                                <i class="fa-solid fa-tags" style="color:#2563eb;font-size:0.75rem;"></i>
                                Danh mục phân loại <span class="req">*</span>
                            </label>
                            <select name="category_id" id="categorySelect"
                                class="form-select @error('category_id') is-invalid @enderror" required>
                                <option value="">-- Chọn nhóm danh mục --</option>
                                @foreach($categories as $cat)
                                <option value="{{ $cat->id }}"
                                    {{ old('category_id') == $cat->id ? 'selected' : '' }}
                                    data-name="{{ $cat->name }}">
                                    {{ $cat->name }}
                                </option>
                                @endforeach
                            </select>
                            @error('category_id')<div class="invalid-feedback">{{ $message }}</div>@enderror
                            <!-- Preview -->
                            <div class="cat-preview" id="catPreview">
                                <i class="fa-solid fa-circle-check"></i>
                                <span>Nhóm: <strong id="catPreviewName"></strong></span>
                            </div>
                        </div>

                        <div class="mb-3">
                            <label class="field-label"><i class="fa-solid fa-copyright" style="color:#2563eb;font-size:0.75rem;"></i> Thương hiệu</label>
                            <input type="text" name="brand" class="form-control @error('brand') is-invalid @enderror" value="{{ old('brand') }}" placeholder="Ví dụ: Brembo, Ohlins">
                            @error('brand')<div class="invalid-feedback">{{ $message }}</div>@enderror
                        </div>

                        <div class="mb-3">
                            <label class="field-label">
                                <i class="fa-solid fa-box" style="color:#7c3aed;font-size:0.75rem;"></i>
                                Tên sản phẩm <span class="req">*</span>
                            </label>
                            <input type="text" name="name"
                                class="form-control @error('name') is-invalid @enderror"
                                placeholder="Ví dụ: Phuộc Ohlins HO831"
                                value="{{ old('name') }}" required>
                            @error('name')<div class="invalid-feedback">{{ $message }}</div>@enderror
                        </div>
                    </div>

                    <!-- Cột phải -->
                    <div class="col-md-6">
                        <div class="mb-3">
                            <label class="field-label">
                                <i class="fa-solid fa-dong-sign" style="color:#16a34a;font-size:0.75rem;"></i>
                                Giá bán (VNĐ) <span class="req">*</span>
                            </label>
                            <input type="number" name="price"
                                class="form-control @error('price') is-invalid @enderror"
                                placeholder="1500000" value="{{ old('price') }}" required min="0">
                            @error('price')<div class="invalid-feedback">{{ $message }}</div>@enderror
                        </div>

                        <div class="mb-3">
                            <label class="field-label">
                                <i class="fa-solid fa-cubes" style="color:#ea580c;font-size:0.75rem;"></i>
                                Số lượng tồn kho <span class="req">*</span>
                            </label>
                            <input type="number" name="stock"
                                class="form-control @error('stock') is-invalid @enderror"
                                placeholder="10" value="{{ old('stock', 0) }}" required min="0">
                            @error('stock')<div class="invalid-feedback">{{ $message }}</div>@enderror
                        </div>

                        <div class="mb-3">
                            <label class="field-label">
                                <i class="fa-solid fa-image" style="color:#2563eb;font-size:0.75rem;"></i>
                                Đường dẫn hình ảnh sản phẩm (URL)
                            </label>
                            <input type="text" name="image"
                                class="form-control @error('image') is-invalid @enderror"
                                placeholder="Ví dụ: https://images.unsplash.com/... hoặc /images/products/sp1.jpg"
                                value="{{ old('image') }}">
                            @error('image')<div class="invalid-feedback">{{ $message }}</div>@enderror
                        </div>

                    </div>

                    <!-- Mô tả -->
                    <div class="col-12">
                        <label class="field-label"><i class="fa-solid fa-list-check" style="color:#7c3aed;font-size:0.75rem;"></i> Thuộc tính kỹ thuật (JSON tùy chọn)</label>
                        <textarea name="attributes" rows="2" class="form-control @error('attributes') is-invalid @enderror" placeholder='{"Màu":"Đen","Dung tích":"500ml"}'>{{ old('attributes') }}</textarea>
                        @error('attributes')<div class="invalid-feedback">{{ $message }}</div>@enderror
                    </div>
                    <div class="col-12">
                        <label class="field-label">
                            <i class="fa-solid fa-align-left" style="color:#64748b;font-size:0.75rem;"></i>
                            Mô tả sản phẩm
                        </label>
                        <textarea name="description" rows="3"
                            class="form-control @error('description') is-invalid @enderror"
                            placeholder="Nhập thông số kỹ thuật, xuất xứ...">{{ old('description') }}</textarea>
                        @error('description')<div class="invalid-feedback">{{ $message }}</div>@enderror
                    </div>
                </div>

                <div class="d-flex gap-2 justify-content-end border-top pt-4 mt-3">
                    <a href="{{ route('products.index') }}" class="btn-cancel"><i class="fa-solid fa-xmark"></i> Hủy bỏ</a>
                    <button type="submit" class="btn-save"><i class="fa-solid fa-floppy-disk"></i> Lưu sản phẩm</button>
                </div>
            </form>
        </div>
    </div>

    <!-- BẢNG PHÂN LOẠI DANH MỤC -->
    <div class="cat-table-card">
        <div class="ct-header">
            <div class="ct-title">
                <i class="fa-solid fa-table-list" style="color:#2563eb;"></i>
                Bảng phân loại danh mục
                <span class="count-pill">{{ $categories->count() }}</span>
            </div>
            <div class="tip-box">
                <i class="fa-solid fa-lightbulb" style="color:#f59e0b;"></i>
                Nhấn <strong>"Chọn"</strong> để điền nhanh danh mục vào form
            </div>
        </div>

        <div class="table-responsive">
            <table class="cat-tbl" id="catTable">
                <thead>
                    <tr>
                        <th style="width:55px;">ID</th>
                        <th style="width:210px;">Tên nhóm danh mục</th>
                        <th>Mô tả</th>
                        <th style="width:110px;" class="text-center">Áp dụng</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($categories as $cat)
                    <tr id="row-{{ $cat->id }}">
                        <td><div class="id-pill">#{{ sprintf('%02d', $cat->id) }}</div></td>
                        <td>
                            <div class="cat-name-tbl">{{ $cat->name }}</div>
                        </td>
                        <td>
                            <div class="cat-desc-tbl">{{ Str::limit($cat->description, 90, '...') ?: '—' }}</div>
                        </td>
                        <td class="text-center">
                            <button type="button" class="btn-pick"
                                onclick="pickCategory({{ $cat->id }}, '{{ addslashes($cat->name) }}')">
                                <i class="fa-solid fa-hand-pointer"></i> Chọn
                            </button>
                        </td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="4" class="text-center py-4" style="color:#94a3b8;font-size:0.85rem;">
                            Chưa có danh mục nào. <a href="{{ route('categories.create') }}">Thêm danh mục ngay</a>
                        </td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        <div class="ct-footer">
            Tổng {{ $categories->count() }} nhóm danh mục — Nhấn "Chọn" để tự động điền vào ô phân loại bên trên.
        </div>
    </div>

</div>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js"></script>
<script>
    // Khi load trang, kiểm tra nếu old() đã có giá trị
    window.addEventListener('DOMContentLoaded', function () {
        const sel = document.getElementById('categorySelect');
        const opt = sel.options[sel.selectedIndex];
        if (sel.value && opt) {
            showPreview(sel.value, opt.dataset.name);
            highlightRow(sel.value);
        }
        sel.addEventListener('change', function () {
            const o = this.options[this.selectedIndex];
            showPreview(this.value, o?.dataset?.name);
            highlightRow(this.value);
        });
    });

    function showPreview(val, name) {
        const p = document.getElementById('catPreview');
        const n = document.getElementById('catPreviewName');
        if (val && name) { n.textContent = name; p.classList.add('show'); }
        else { p.classList.remove('show'); }
    }

    function highlightRow(catId) {
        document.querySelectorAll('#catTable tbody tr').forEach(r => r.classList.remove('selected-row'));
        document.querySelectorAll('.btn-pick').forEach(b => {
            b.innerHTML = '<i class="fa-solid fa-hand-pointer"></i> Chọn';
        });
        if (catId) {
            const row = document.getElementById('row-' + catId);
            if (row) {
                row.classList.add('selected-row');
                row.querySelector('.btn-pick').innerHTML = '<i class="fa-solid fa-circle-check"></i> Đã chọn';
            }
        }
    }

    function pickCategory(id, name) {
        const sel = document.getElementById('categorySelect');
        sel.value = id;
        showPreview(id, name);
        highlightRow(id);
        // Cuộn lên form
        document.getElementById('categorySelect').scrollIntoView({ behavior: 'smooth', block: 'center' });
    }
</script>
</body>
</html>