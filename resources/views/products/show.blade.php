<!DOCTYPE html>
<html lang="vi">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>{{ $product->name }} | Phụ Kiện Xe Máy 247</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.0/css/all.min.css">
    <style>
        body { background:#f8fafc; color:#0f172a; }
        .page { max-width:1100px; margin:40px auto; padding:0 18px; }
        .panel { background:#fff; border:1px solid #e2e8f0; border-radius:16px; padding:24px; }
        .product-image { min-height:320px; border-radius:12px; background:#eff6ff; display:grid; place-items:center; color:#2563eb; font-size:7rem; }
        .product-image img { width:100%; height:320px; object-fit:cover; border-radius:12px; }
        .price { color:#1d4ed8; font-size:1.7rem; font-weight:800; }
        .mini-product { border:1px solid #e2e8f0; border-radius:10px; padding:14px; height:100%; }
        .mini-product a { color:#0f172a; text-decoration:none; font-weight:700; }
        .timeline { border-left:2px solid #bfdbfe; padding-left:20px; }
        .timeline-item { position:relative; margin-bottom:18px; }
        .timeline-item::before { content:''; position:absolute; width:10px; height:10px; border-radius:50%; background:#2563eb; left:-26px; top:5px; }
    </style>
</head>
<body>
<div class="page">
    <div class="mb-3"><a href="{{ route('welcome') }}" class="text-decoration-none"><i class="fa-solid fa-arrow-left me-1"></i>Về cửa hàng</a></div>
    <div class="panel">
        <div class="row g-4 align-items-center">
            <div class="col-md-5">
                <div class="product-image">
                    @if($product->image)<img src="{{ asset($product->image) }}" alt="{{ $product->name }}">@else<i class="fa-solid fa-motorcycle"></i>@endif
                </div>
            </div>
            <div class="col-md-7">
                <span class="badge text-bg-primary mb-2">{{ $product->category->name ?? 'Phụ kiện' }}</span>
                <h1 class="h2">{{ $product->name }}</h1>
                @if($product->brand)<div class="text-muted mb-2">Thương hiệu: <strong>{{ $product->brand }}</strong></div>@endif
                <div class="price mb-3">{{ number_format($product->price, 0, ',', '.') }}đ</div>
                <p class="text-muted">{{ $product->description ?: 'Sản phẩm phụ kiện xe máy chính hãng chất lượng cao.' }}</p>
                @if($product->attributes)
                    <div class="border rounded p-3 mb-3"><strong>Thông số kỹ thuật</strong><ul class="mb-0 mt-2">@foreach($product->attributes as $key => $value)<li>{{ $key }}: {{ $value }}</li>@endforeach</ul></div>
                @endif
                <div class="mb-3">Tồn kho: <strong class="text-success">{{ $product->stock }} sản phẩm</strong></div>
                <a href="{{ route('cart.index') }}" class="btn btn-primary"><i class="fa-solid fa-cart-shopping me-1"></i>Thêm vào giỏ từ trang cửa hàng</a>
            </div>
        </div>
    </div>

    <div class="row g-3 mt-4">
        <div class="col-lg-6"><div class="panel h-100"><h2 class="h5 mb-3">Sản phẩm liên quan</h2><div class="row g-3">@forelse($relatedProducts as $related)<div class="col-6"><div class="mini-product"><small class="text-muted">{{ $related->category->name ?? 'Phụ kiện' }}</small><a class="d-block mt-1" href="{{ route('products.show', $related) }}">{{ $related->name }}</a><div class="text-primary fw-bold mt-2">{{ number_format($related->price, 0, ',', '.') }}đ</div></div></div>@empty<p class="text-muted">Chưa có sản phẩm liên quan.</p>@endforelse</div></div></div>
        <div class="col-lg-6"><div class="panel h-100"><h2 class="h5 mb-3">Sản phẩm được quan tâm</h2><div class="row g-3">@forelse($popularProducts as $popular)<div class="col-6"><div class="mini-product"><a class="d-block" href="{{ route('products.show', $popular) }}">{{ $popular->name }}</a><div class="text-primary fw-bold mt-2">{{ number_format($popular->price, 0, ',', '.') }}đ</div></div></div>@empty<p class="text-muted">Chưa có dữ liệu gợi ý.</p>@endforelse</div></div></div>
    </div>
</div>
</body>
</html>
