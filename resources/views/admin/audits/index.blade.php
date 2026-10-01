@extends('layouts.admin')
@section('title', 'Nhật ký quản trị')
@section('content')
<div class="card-panel"><div class="card-panel-title"><i class="fa-solid fa-shield-halved text-primary"></i> Nhật ký thao tác quản trị</div><div class="table-responsive"><table class="table"><thead><tr><th>Thời gian</th><th>Người thực hiện</th><th>Thao tác</th><th>Đối tượng</th><th>IP</th></tr></thead><tbody>@forelse($audits as $audit)<tr><td>{{ $audit->created_at->format('d/m/Y H:i') }}</td><td>{{ $audit->user->name ?? 'Hệ thống' }}</td><td>{{ $audit->action }}</td><td>{{ class_basename($audit->auditable_type ?? '') }} #{{ $audit->auditable_id }}</td><td>{{ $audit->ip_address }}</td></tr>@empty<tr><td colspan="5" class="text-muted">Chưa có nhật ký.</td></tr>@endforelse</tbody></table></div>{{ $audits->links() }}</div>
@endsection
