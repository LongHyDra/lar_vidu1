@extends('layouts.admin')

@section('title', 'Quản lý người dùng')
@section('page_title', 'Quản lý người dùng')

@section('content')
<div class="container-fluid py-4">
    <div class="d-flex flex-wrap justify-content-between align-items-center gap-2 mb-3">
        <div><h1 class="h3 mb-1">Quản lý người dùng</h1><p class="text-muted mb-0">Chọn một hoặc nhiều tài khoản để xóa.</p></div>
        <a href="{{ route('admin.dashboard') }}" class="btn btn-outline-primary">Dashboard</a>
    </div>
    @if(session('success'))<div class="alert alert-success">{{ session('success') }}</div>@endif
    @if(session('error'))<div class="alert alert-danger">{{ session('error') }}</div>@endif
    <form id="bulk-delete-form" action="{{ route('admin.users.bulk-destroy') }}" method="POST">
        @csrf @method('DELETE')
        <div class="card border-0 shadow-sm">
            <div class="d-flex flex-wrap justify-content-between align-items-center gap-2 p-3 border-bottom">
                <label class="d-flex align-items-center gap-2 mb-0"><input id="select-all-users" type="checkbox" class="form-check-input mt-0"><span>Chọn tất cả trang này</span></label>
                <div class="d-flex align-items-center gap-2"><span id="selected-user-count" class="text-muted small">Chưa chọn tài khoản nào</span><button id="bulk-delete-button" type="submit" class="btn btn-danger btn-sm" disabled><i class="fa-solid fa-trash me-1"></i>Xóa đã chọn</button></div>
            </div>
            <div class="table-responsive">
                <table class="table align-middle mb-0">
                    <thead><tr><th class="text-center" style="width:48px">Chọn</th><th>#</th><th>Họ tên</th><th>Email</th><th>Vai trò</th><th>Số đơn</th><th>Ngày tham gia</th></tr></thead>
                    <tbody>
                    @forelse($users as $user)
                        <tr><td class="text-center"><input class="form-check-input user-checkbox" type="checkbox" name="user_ids[]" value="{{ $user->id }}" aria-label="Chọn {{ $user->name }}" @disabled($user->id === auth()->id())></td><td>{{ $user->id }}</td><td class="fw-semibold">{{ $user->name }} @if($user->id === auth()->id())<small class="text-muted">(Bạn)</small>@endif</td><td>{{ $user->email }}</td><td><span class="badge text-bg-{{ $user->role === 'admin' ? 'dark' : 'primary' }}">{{ $user->role }}</span></td><td>{{ $user->orders_count }}</td><td>{{ $user->created_at->format('d/m/Y') }}</td></tr>
                    @empty
                        <tr><td colspan="7" class="text-center py-5 text-muted">Chưa có người dùng.</td></tr>
                    @endforelse
                    </tbody>
                </table>
            </div>
            <div class="p-3">{{ $users->links() }}</div>
        </div>
    </form>
</div>
@endsection

@push('scripts')
<script>
(() => {
    const form=document.getElementById('bulk-delete-form'),selectAll=document.getElementById('select-all-users'),checkboxes=[...document.querySelectorAll('.user-checkbox')],button=document.getElementById('bulk-delete-button'),count=document.getElementById('selected-user-count');
    const selectable=()=>checkboxes.filter(checkbox=>!checkbox.disabled),selectedItems=()=>selectable().filter(checkbox=>checkbox.checked);
    const update=()=>{const selected=selectedItems().length,total=selectable().length;button.disabled=selected===0;count.textContent=selected?selected+' tài khoản đã chọn':'Chưa chọn tài khoản nào';selectAll.checked=total>0&&selected===total;selectAll.indeterminate=selected>0&&selected<total;};
    selectAll.addEventListener('change',()=>{selectable().forEach(checkbox=>{checkbox.checked=selectAll.checked;});update();});checkboxes.forEach(checkbox=>checkbox.addEventListener('change',update));
    form.addEventListener('submit',event=>{const selected=selectedItems().length;if(!selected||!window.confirm('Bạn có chắc muốn xóa '+selected+' tài khoản đã chọn? Dữ liệu liên quan có thể bị xóa theo database.'))event.preventDefault();});
})();
</script>
@endpush
