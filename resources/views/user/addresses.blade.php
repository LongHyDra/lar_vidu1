@extends('layouts.auth')

@section('title', 'Địa chỉ giao hàng')

@section('content')
<div class="container py-5" style="max-width: 980px">
    <div class="d-flex justify-content-between align-items-center mb-4">
        <div><span class="eyebrow">Tài khoản</span><h1 class="h3 fw-bold mb-0">Địa chỉ giao hàng</h1></div>
        <a href="{{ route('user.profile') }}" class="btn btn-outline-secondary">Hồ sơ</a>
    </div>
    @if(session('success'))<div class="alert alert-success">{{ session('success') }}</div>@endif
    <div class="row g-4">
        <div class="col-lg-5">
            <div class="card border-0 shadow-sm p-4">
                <h2 class="h5 fw-bold mb-3">Thêm địa chỉ</h2>
                <form method="POST" action="{{ route('user.addresses.store') }}">
                    @csrf
                    <input name="label" class="form-control mb-2" placeholder="Nhãn: Nhà riêng / Công ty">
                    <input name="recipient_name" class="form-control mb-2" required placeholder="Tên người nhận">
                    <input name="phone" class="form-control mb-2" required placeholder="Số điện thoại">
                    <textarea name="address" class="form-control mb-2" required placeholder="Địa chỉ chi tiết"></textarea>
                    <input name="district_id" type="number" class="form-control mb-2" placeholder="Mã quận/huyện GHN (không bắt buộc)">
                    <input name="ward_code" class="form-control mb-3" placeholder="Mã phường GHN (không bắt buộc)">
                    <label class="form-check mb-3"><input type="checkbox" name="is_default" value="1" class="form-check-input"> Đặt làm mặc định</label>
                    <button class="btn btn-primary w-100">Lưu địa chỉ</button>
                </form>
            </div>
        </div>
        <div class="col-lg-7">
            @forelse($addresses as $address)
            <div class="card border-0 shadow-sm p-4 mb-3 {{ $address->is_default ? 'border border-primary' : '' }}">
                <div class="d-flex justify-content-between gap-3">
                    <div><span class="fw-bold">{{ $address->label }}</span> @if($address->is_default)<span class="badge text-bg-primary ms-2">Mặc định</span>@endif
                        <div class="mt-2">{{ $address->recipient_name }} · {{ $address->phone }}</div><div class="text-muted">{{ $address->address }}</div></div>
                    <div class="d-flex gap-2 align-items-start">
                        @unless($address->is_default)<form method="POST" action="{{ route('user.addresses.default', $address) }}">@csrf @method('PATCH')<button class="btn btn-sm btn-outline-primary">Chọn mặc định</button></form>@endunless
                        <form method="POST" action="{{ route('user.addresses.destroy', $address) }}">@csrf @method('DELETE')<button class="btn btn-sm btn-outline-danger">Xóa</button></form>
                    </div>
                </div>
            </div>
            @empty <div class="card border-0 shadow-sm p-4 text-muted">Chưa có địa chỉ lưu.</div> @endforelse
        </div>
    </div>
</div>
@endsection
