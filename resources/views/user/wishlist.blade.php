<!DOCTYPE html>
<html lang="vi">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Sản phẩm yêu thích | Phụ Kiện Xe Máy 247</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.0/css/all.min.css">
    <style>
        body { background: #f8fafc; color: #0f172a; }
        .page { max-width: 1120px; margin: 40px auto; padding: 0 18px; }
        .panel { background: #fff; border: 1px solid #e2e8f0; border-radius: 16px; padding: 24px; }
        .product { height: 100%; border: 1px solid #e2e8f0; border-radius: 12px; padding: 16px; display: flex; flex-direction: column; }
        .product-icon { height: 130px; display: grid; place-items: center; background: #eff6ff; border-radius: 10px; color: #2563eb; font-size: 3rem; margin-bottom: 14px; }
        .product h3 { font-size: 1rem; min-height: 48px; }
        .empty { color: #64748b; padding: 48px 0; text-align: center; }
    </style>
</head>
<body>
<div class="page">
    <div class="panel">
        <div class="d-flex flex-wrap justify-content-between align-items-center gap-2 mb-4">
            <div>
                <h1 class="h3 mb-1">Sản phẩm yêu thích</h1>
                <p class="text-muted mb-0">Lưu lại những món đồ bạn đang quan tâm.</p>
            </div>
            <a href="{{ route('welcome') }}" class="btn btn-outline-primary"><i class="fa-solid fa-arrow-left me-1"></i>Tiếp tục mua sắm</a>
        </div>

        @if (session('success'))
            <div class="alert alert-success">{{ session('success') }}</div>
        @endif

        @if ($wishlists->isEmpty())
            <div class="empty">
                <i class="fa-regular fa-heart fs-1 mb-3"></i>
                <p class="mb-0">Bạn chưa lưu sản phẩm nào.</p>
            </div>
        @else
            <div class="row g-3">
                @foreach ($wishlists as $wishlist)
                    @if ($wishlist->product)
                        <div class="col-12 col-sm-6 col-lg-3">
                            <article class="product">
                                <div class="product-icon"><i class="fa-solid fa-motorcycle"></i></div>
                                <div class="small text-primary fw-bold mb-1">{{ $wishlist->product->category->name ?? 'Phụ kiện' }}</div>
                                <h3>{{ $wishlist->product->name }}</h3>
                                <div class="fw-bold text-primary mb-3">{{ number_format($wishlist->product->price, 0, ',', '.') }}đ</div>
                                <div class="mt-auto d-flex gap-2">
                                    <a href="{{ route('welcome', ['q' => $wishlist->product->name]) }}" class="btn btn-sm btn-primary flex-grow-1">Xem sản phẩm</a>
                                    <form action="{{ route('user.wishlist.destroy', $wishlist->product) }}" method="POST">
                                        @csrf
                                        @method('DELETE')
                                        <button class="btn btn-sm btn-outline-danger" title="Xóa khỏi yêu thích" aria-label="Xóa khỏi yêu thích"><i class="fa-solid fa-trash"></i></button>
                                    </form>
                                </div>
                            </article>
                        </div>
                    @endif
                @endforeach
            </div>
        @endif
    </div>
</div>
</body>
</html>
