@extends('layouts.admin')

@section('title', 'Chỉnh Sửa Danh Mục | PHỤ KIỆN XE MÁY')
@section('page_title', 'Chỉnh Sửa Danh Mục')
@section('page_breadcrumb', 'Trang chủ > Danh mục > ' . $category->name)

@section('topbar_actions')
<a href="{{ route('categories.index') }}" class="btn btn-sm btn-outline-secondary fw-semibold">
    <i class="fa-solid fa-arrow-left me-1"></i> Quay lại danh sách
</a>
@endsection

@section('content')
<div class="row justify-content-center">
    <div class="col-lg-8">
        <div class="card card-custom shadow-sm">
            <div class="card-header bg-white py-3 px-4 border-bottom d-flex align-items-center justify-content-between">
                <span class="fw-bold fs-6 text-dark">
                    <i class="fa-solid fa-pen-to-square text-primary me-2"></i>Chỉnh sửa danh mục phụ kiện
                </span>
                <a href="{{ route('categories.index') }}" class="btn btn-sm btn-light border text-muted">
                    <i class="fa-solid fa-arrow-left me-1"></i> Quay lại
                </a>
            </div>

            <div class="card-body p-4 bg-white">
                <!-- Thông tin danh mục hiện tại -->
                <div class="d-flex align-items-center gap-3 p-3 bg-light rounded border mb-4">
                    <div class="bg-primary text-white rounded p-2 d-flex align-items-center justify-content-center" style="width: 38px; height: 38px;">
                        <i class="fa-solid fa-tag"></i>
                    </div>
                    <div>
                        <div class="text-uppercase text-muted" style="font-size: 0.7rem; font-weight: 700;">Đang chỉnh sửa</div>
                        <div class="fw-bold text-dark fs-6">
                            {{ $category->name }} <span class="text-muted fw-normal small">(ID #{{ $category->id }})</span>
                        </div>
                    </div>
                </div>

                @if($errors->any())
                <div class="alert alert-danger mb-4">
                    <strong><i class="fa-solid fa-circle-exclamation me-1"></i> Vui lòng kiểm tra lại:</strong>
                    <ul class="mb-0 ps-3 mt-1 small">
                        @foreach($errors->all() as $e)
                        <li>{{ $e }}</li>
                        @endforeach
                    </ul>
                </div>
                @endif

                <form action="{{ route('categories.update', $category->id) }}" method="POST">
                    @csrf
                    @method('PUT')

                    <div class="mb-3">
                        <label class="form-label fw-bold small text-dark">
                            Tên danh mục <span class="text-danger">*</span>
                        </label>
                        <input type="text"
                            name="name"
                            class="form-control @error('name') is-invalid @enderror"
                            value="{{ old('name', $category->name) }}"
                            required>
                        @error('name')
                        <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>

                    <div class="mb-4">
                        <label class="form-label fw-bold small text-dark">Mô tả chi tiết</label>
                        <textarea name="description"
                            rows="4"
                            class="form-control @error('description') is-invalid @enderror">{{ old('description', $category->description) }}</textarea>
                        @error('description')
                        <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>

                    <div class="d-flex gap-2 justify-content-end border-top pt-3">
                        <a href="{{ route('categories.index') }}" class="btn btn-light border px-4 fw-semibold">
                            <i class="fa-solid fa-xmark me-1"></i> Hủy
                        </a>
                        <button type="submit" class="btn btn-primary px-4 fw-bold shadow-sm">
                            <i class="fa-solid fa-floppy-disk me-1"></i> Cập nhật danh mục
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>
@endsection