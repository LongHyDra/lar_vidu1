@extends('layouts.admin')
@section('title', 'Biến thể sản phẩm')
@section('content')
<div class="card-panel">
    <div class="d-flex flex-wrap justify-content-between align-items-center gap-2 mb-3">
        <div class="card-panel-title mb-0">Biến thể: {{ $product->name }}</div>
        <a href="{{ route('products.edit', $product) }}" class="btn btn-outline-secondary btn-sm">
            <i class="fa-solid fa-arrow-left me-1"></i>Quay lại sản phẩm
        </a>
    </div>
    <form method="POST" action="{{ route('admin.products.variants.store', $product) }}" class="row g-2">
        @csrf
        <input name="sku" class="form-control col" placeholder="SKU" required>
        <input name="variant_name" class="form-control col" placeholder="Tên biến thể" required>
        <input name="price" type="number" step="0.01" class="form-control col" placeholder="Giá" required>
        <input name="stock" type="number" class="form-control col" placeholder="Tồn kho" required>
        <input name="weight" type="number" class="form-control col" placeholder="Gram">
        <input name="image" type="url" class="form-control col-12" placeholder="Link ảnh biến thể (https://...)" maxlength="2048">
        <button class="btn btn-primary col-auto">Thêm biến thể</button>
    </form>
</div>

<div class="card-panel">
    <div class="table-responsive">
        <table class="table align-middle">
            <thead><tr><th>SKU</th><th>Biến thể</th><th>Giá</th><th>Tồn kho</th><th>Link ảnh URL</th><th>Cập nhật</th></tr></thead>
            <tbody>
            @forelse($variants as $variant)
                <tr>
                    <td>{{ $variant->sku }}</td>
                    <td colspan="5">
                        <form method="POST" action="{{ route('admin.products.variants.update', $variant) }}" class="row g-2 align-items-center">
                            @csrf @method('PATCH')
                            <div class="col-md-3"><input name="variant_name" value="{{ $variant->variant_name }}" class="form-control" required></div>
                            <div class="col-md-2"><input name="price" type="number" step="0.01" value="{{ $variant->price }}" class="form-control" required></div>
                            <div class="col-md-2"><input name="stock" type="number" min="0" value="{{ $variant->stock }}" class="form-control" required></div>
                            <div class="col-md-3"><input name="image" type="url" value="{{ $variant->image }}" placeholder="https://..." class="form-control" maxlength="2048"></div>
                            <div class="col-md-2 d-flex gap-2"><input name="weight" type="number" min="1" value="{{ $variant->weight }}" class="form-control" placeholder="Gram"><button class="btn btn-sm btn-primary">Lưu</button></div>
                        </form>
                    </td>
                </tr>
            @empty
                <tr><td colspan="6" class="text-center text-muted py-4">Chưa có biến thể.</td></tr>
            @endforelse
            </tbody>
        </table>
    </div>
</div>
@endsection
