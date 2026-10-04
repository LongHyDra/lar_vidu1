@extends('layouts.storefront')

@section('title', 'Chi tiết đơn hàng')

@section('account_content')
<div class="account-page">
    <div class="card">
        <div class="header">
            <h1>Đơn hàng #{{ $order->id }}</h1>
            @php($statusLabels = ['pending' => 'Chờ xác nhận', 'confirmed' => 'Đã xác nhận', 'packaging' => 'Đang đóng gói', 'shipping' => 'Đang vận chuyển', 'delivered' => 'Đã giao', 'cancelled' => 'Đã hủy', 'cod_ordered' => 'Đã tạo vận đơn'])
            <span class="badge badge-{{ $order->status ?? 'pending' }}">{{ $statusLabels[$order->status] ?? $order->status }}</span>
        </div>

        <div class="info">
            <div><strong>Khách hàng:</strong> {{ $order->name }}</div>
            <div><strong>Số điện thoại:</strong> {{ $order->phone }}</div>
            <div><strong>Địa chỉ:</strong> {{ $order->address }}</div>
            <div><strong>Ngày đặt:</strong> {{ $order->created_at->format('d/m/Y H:i') }}</div>
            <div><strong>GHN code:</strong> {{ $order->ghn_order_code ?: 'Chưa có' }}</div>
            <div><strong>Phương thức thanh toán:</strong> {{ strtoupper($order->paymentTransaction->gateway ?? 'COD') }}</div>
            <div><strong>Thanh toán:</strong> {{ $order->paymentTransaction->status ?? 'Chưa cập nhật' }}</div>
        </div>

        <h2 style="font-size: 18px; margin-top: 26px;">Lịch sử trạng thái</h2>
        <div class="timeline">
            @forelse($order->statusHistories as $history)
                <div class="timeline-item">
                    <strong>{{ $statusLabels[$history->status] ?? $history->status }}</strong>
                    <div class="muted">{{ $history->created_at->format('d/m/Y H:i') }} · {{ $history->note ?: 'Cập nhật trạng thái' }}</div>
                </div>
            @empty
                <div class="muted">Chưa có lịch sử trạng thái.</div>
            @endforelse
        </div>

        <div class="table-responsive"><table class="table align-middle">
            <thead>
            <tr>
                <th>Sản phẩm</th>
                <th>Số lượng</th>
                <th>Đơn giá</th>
                <th>Thành tiền</th>
            </tr>
            </thead>
            <tbody>
            @foreach ($order->items as $item)
                <tr>
                    <td>{{ $item->product->name ?? 'Sản phẩm' }} @if($item->variant)<small class="muted">({{ $item->variant->variant_name }})</small>@endif</td>
                    <td>{{ $item->quantity }}</td>
                    <td>{{ number_format($item->price, 0, ',', '.') }}đ</td>
                    <td>{{ number_format($item->price * $item->quantity, 0, ',', '.') }}đ</td>
                </tr>
            @endforeach
            </tbody>
        </table></div>

        <div class="info" style="margin-top: 20px;">
            <div><strong>Phí vận chuyển:</strong> {{ number_format($order->ghn_total_fee ?? 0, 0, ',', '.') }}đ</div>
            <div><strong>Tổng tiền:</strong> {{ number_format($order->total_price, 0, ',', '.') }}đ</div>
        </div>

        @if($order->status === 'delivered' && auth()->id() === $order->user_id)
        <h2 style="font-size:18px;margin-top:26px">Đánh giá sản phẩm</h2>
        @foreach($order->items as $item)
        <form method="POST" action="{{ route('user.products.reviews.store', $item->product) }}" style="border-top:1px solid #e5e7eb;padding:14px 0">
            @csrf <input type="hidden" name="order_id" value="{{ $order->id }}"><strong>{{ $item->product->name }}</strong>
            <div><select name="rating" class="form-select" aria-label="Số sao đánh giá" required><option value="5">5 sao</option><option value="4">4 sao</option><option value="3">3 sao</option><option value="2">2 sao</option><option value="1">1 sao</option></select></div>
            <textarea class="form-control" aria-label="Nội dung đánh giá" name="body" maxlength="2000" placeholder="Chia sẻ trải nghiệm" style="width:100%;margin-top:8px"></textarea>
            <button class="btn btn-primary mt-2">Gửi đánh giá</button>
        </form>
        @endforeach
        @endif

        <a href="{{ route('user.orders.index') }}" class="link">← Quay lại lịch sử đơn hàng</a>
    </div>
</div>
@endsection
