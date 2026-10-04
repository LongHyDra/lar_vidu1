@if ($errors->any())
    <div class="alert alert-danger" role="alert">
        <strong>Vui lòng kiểm tra lại thông tin:</strong>
        <ul class="mb-0 mt-2">@foreach ($errors->all() as $error)<li>{{ $error }}</li>@endforeach</ul>
    </div>
@endif
