@extends('layouts.storefront')

@section('title', 'Thông báo')

@section('account_content')
<div class="account-page"><h1 class="h3 fw-bold mb-4">Thông báo</h1>
    @forelse($notifications as $notification)
        <a href="{{ route('user.notifications.read', $notification) }}" class="d-block text-decoration-none text-dark card border-0 shadow-sm p-3 mb-2 {{ $notification->read_at ? '' : 'border-start border-primary border-3' }}"><strong>{{ $notification->title }}</strong><span>{{ $notification->body }}</span><small class="text-muted">{{ $notification->created_at->diffForHumans() }}</small></a>
    @empty <div class="text-muted">Chưa có thông báo.</div> @endforelse
    {{ $notifications->links() }}
</div>
@endsection
