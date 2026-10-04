@extends('layouts.storefront')

@section('title', $product->name)

@section('content')
    <div class="detail-page">
        <nav class="detail-breadcrumb" aria-label="Đường dẫn">
            <a href="{{ route('welcome') }}">Cửa hàng</a>
            <span>/</span>
            <a href="{{ route('welcome', ['category' => $product->category_id]) }}">{{ $product->category->name ?? 'Phụ kiện' }}</a>
            <span>/</span>
            <span aria-current="page">{{ $product->name }}</span>
        </nav>

        <div class="detail-panel">
            <div class="detail-image">
                @if($product->image)
                    <img src="{{ asset($product->image) }}" alt="{{ $product->name }}">
                @else
                    <i class="fa-solid fa-motorcycle" aria-hidden="true"></i>
                @endif
            </div>

            <div class="detail-info">
                <span class="eyebrow">{{ $product->brand ?: ($product->category->name ?? 'Phụ kiện xe máy') }}</span>
                <h1>{{ $product->name }}</h1>
                <span class="detail-stock">{{ $product->stock > 0 ? 'Còn ' . $product->stock . ' sản phẩm' : 'Tạm hết hàng' }}</span>
                <div class="detail-price">{{ number_format($product->price, 0, ',', '.') }}₫</div>

                @if($product->variants->isNotEmpty())
                    <div class="mb-3">
                        <label for="variantSelect" class="form-label fw-bold">Phiên bản</label>
                        <select id="variantSelect" class="form-select" aria-label="Chọn phiên bản sản phẩm">
                            @foreach($product->variants as $variant)
                                <option value="{{ $variant->id }}"
                                    data-name="{{ $variant->variant_name }}"
                                    data-price="{{ $variant->price }}"
                                    data-stock="{{ $variant->stock }}"
                                    data-image="{{ $variant->image ? asset($variant->image) : asset($product->image) }}"
                                    @disabled($variant->stock <= 0)>
                                    {{ $variant->variant_name }} — {{ number_format($variant->price, 0, ',', '.') }}₫ (còn {{ $variant->stock }})
                                </option>
                            @endforeach
                        </select>
                    </div>
                @endif

                <p class="detail-description">{{ $product->description ?: 'Khám phá chi tiết sản phẩm và liên hệ cửa hàng để được tư vấn lựa chọn phù hợp.' }}</p>

                @if($product->attributes)
                    <div class="detail-specs">
                        <strong>Thông số kỹ thuật</strong>
                        <ul>
                            @foreach($product->attributes as $key => $value)
                                <li>{{ $key }}: {{ is_array($value) ? implode(', ', $value) : $value }}</li>
                            @endforeach
                        </ul>
                    </div>
                @endif

                <div class="detail-actions">
                    <button type="button" id="detailAddCart" class="btn-add-cart-prominent"
                        data-id="{{ $product->id }}"
                        data-name="{{ $product->name }}"
                        data-price="{{ $product->price }}"
                        data-category="{{ $product->category->name ?? 'Phụ kiện' }}"
                        data-stock="{{ $product->stock }}"
                        @disabled($product->variants->isEmpty() && $product->stock <= 0)>
                        <i class="fa-solid fa-cart-plus" aria-hidden="true"></i>
                        {{ $product->stock > 0 ? 'Thêm vào giỏ hàng' : 'Tạm hết hàng' }}
                    </button>
                    <a href="{{ route('user.tickets.create') }}" class="btn btn-outline-dark">Cần tư vấn?</a>
                </div>

                <p id="detailCartMessage" class="detail-message" role="status" aria-live="polite"></p>
                <div class="small text-muted">
                    <i class="fa-solid fa-truck-fast me-2" aria-hidden="true"></i>
                    Phí vận chuyển được tính theo địa chỉ khi thanh toán.
                </div>
            </div>
        </div>

        <section class="detail-related">
            <span class="eyebrow">Thêm lựa chọn cho bạn</span>
            <h2>Sản phẩm liên quan</h2>
            <div class="row g-3">
                @forelse($relatedProducts as $related)
                    <div class="col-6 col-md-3">
                        <div class="mini-product">
                            <small class="text-muted">{{ $related->category->name ?? 'Phụ kiện' }}</small>
                            <a class="d-block mt-2" href="{{ route('products.show', $related) }}">{{ $related->name }}</a>
                            <div class="text-primary fw-bold mt-3">{{ number_format($related->price, 0, ',', '.') }}₫</div>
                        </div>
                    </div>
                @empty
                    <p class="text-muted">Chưa có sản phẩm liên quan.</p>
                @endforelse
            </div>
        </section>

        <section class="detail-related">
            <h2>Sản phẩm được quan tâm</h2>
            <div class="row g-3">
                @forelse($popularProducts as $popular)
                    <div class="col-6 col-md-3">
                        <div class="mini-product">
                            <a href="{{ route('products.show', $popular) }}">{{ $popular->name }}</a>
                            <div class="text-primary fw-bold mt-3">{{ number_format($popular->price, 0, ',', '.') }}₫</div>
                        </div>
                    </div>
                @empty
                    <p class="text-muted">Chưa có dữ liệu gợi ý.</p>
                @endforelse
            </div>
        </section>

        <section class="detail-related" aria-labelledby="reviews-heading">
            <span class="eyebrow">Khách hàng chia sẻ</span>
            <h2 id="reviews-heading">Đánh giá sản phẩm</h2>
            @forelse($product->reviews as $review)
                <div class="border-bottom py-3">
                    <strong>{{ $review->user->name }}</strong>
                    <span class="text-warning">{{ str_repeat('★', $review->rating) }}</span>
                    <p class="mb-0 text-muted">{{ $review->body }}</p>
                </div>
            @empty
                <p class="text-muted">Chưa có đánh giá được duyệt.</p>
            @endforelse
        </section>
    </div>

    <script type="application/ld+json">{!! json_encode(['@context' => 'https://schema.org', '@type' => 'Product', 'name' => $product->name, 'description' => strip_tags((string) $product->description), 'image' => $product->image ? asset($product->image) : null, 'offers' => ['@type' => 'Offer', 'priceCurrency' => 'VND', 'price' => (float) $product->price, 'availability' => $product->stock > 0 ? 'https://schema.org/InStock' : 'https://schema.org/OutOfStock', 'url' => route('products.show', $product)]], JSON_UNESCAPED_UNICODE|JSON_UNESCAPED_SLASHES) !!}</script>
@endsection

@push('scripts')
    <script src="{{ asset('js/product-detail.js') }}" defer></script>
    @auth
        @include('partials.cart-sync')
    @endauth
@endpush
