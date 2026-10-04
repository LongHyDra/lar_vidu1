@extends('layouts.storefront')

@section('title', 'Yêu cầu hỗ trợ')

@section('account_content')
<div class="account-page">
    <div class="bg-white rounded-4 shadow-sm p-4">
        <div class="d-flex justify-content-between align-items-center mb-4">
            <div><h1 class="h3 mb-1">Yêu cầu hỗ trợ</h1><p class="text-muted mb-0">Theo dõi các câu hỏi đã gửi cho cửa hàng.</p></div>
            <a href="{{ route('user.tickets.create') }}" class="btn btn-primary">Tạo yêu cầu</a>
        </div>
        @if(session('success'))<div class="alert alert-success">{{ session('success') }}</div>@endif
        @forelse($tickets as $ticket)
            <div class="border rounded-3 p-3 mb-3">
                <div class="d-flex justify-content-between gap-3"><strong>{{ $ticket->subject }}</strong><span class="badge text-bg-secondary">{{ $ticket->status }}</span></div>
                <p class="text-muted mt-2 mb-2">{{ $ticket->message }}</p>
                @if($ticket->admin_reply)<div class="bg-light rounded p-2"><strong>Phản hồi từ cửa hàng:</strong> {{ $ticket->admin_reply }}</div>@endif
                <small class="text-muted">{{ $ticket->created_at->format('d/m/Y H:i') }}</small>
            </div>
        @empty
            <p class="text-center text-muted py-5">Bạn chưa gửi yêu cầu hỗ trợ nào.</p>
        @endforelse
        <a href="{{ route('welcome') }}" class="btn btn-link px-0">Về trang chủ</a>
    </div>
</div>
@endsection
