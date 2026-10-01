@extends('layouts.admin')
@section('title', 'Quản lý người dùng')
@section('page_title', 'Quản lý người dùng')

@section('content')
<div class="container-fluid py-4"><div class="d-flex justify-content-between align-items-center mb-3"><div><h1 class="h3 mb-1">Quản lý người dùng</h1><p class="text-muted mb-0">Danh sách tài khoản và số đơn đã đặt.</p></div><a href="{{ route('admin.dashboard') }}" class="btn btn-outline-primary">Dashboard</a></div><div class="card border-0 shadow-sm"><div class="table-responsive"><table class="table align-middle mb-0"><thead><tr><th>#</th><th>Họ tên</th><th>Email</th><th>Vai trò</th><th>Số đơn</th><th>Ngày tham gia</th></tr></thead><tbody>@forelse($users as $user)<tr><td>{{ $user->id }}</td><td>{{ $user->name }}</td><td>{{ $user->email }}</td><td><span class="badge text-bg-{{ $user->role === 'admin' ? 'dark' : 'primary' }}">{{ $user->role }}</span></td><td>{{ $user->orders_count }}</td><td>{{ $user->created_at->format('d/m/Y') }}</td></tr>@empty<tr><td colspan="6" class="text-center py-5 text-muted">Chưa có người dùng.</td></tr>@endforelse</tbody></table></div><div class="p-3">{{ $users->links() }}</div></div></div>
@endsection
