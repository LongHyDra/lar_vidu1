@extends('layouts.storefront')

@section('title', 'Tạo yêu cầu hỗ trợ')

@section('account_content')
<div class="account-page">
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
@endsection
