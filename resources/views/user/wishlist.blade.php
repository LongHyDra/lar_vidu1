@extends('layouts.storefront')

@section('title', 'Sản phẩm yêu thích')

@section('account_content')
<div class="account-page">
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
@endsection
