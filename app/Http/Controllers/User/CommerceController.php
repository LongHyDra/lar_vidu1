<?php

namespace App\Http\Controllers\User;

use App\Http\Controllers\Controller;
use App\Models\Cart;
use App\Models\Coupon;
use App\Models\CouponRedemption;
use App\Models\Order;
use App\Models\Product;
use App\Models\ProductReview;
use App\Models\SiteNotification;
use App\Models\UserAddress;
use App\Services\LoyaltyService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Validation\Rule;

class CommerceController extends Controller
{
    public function cart(Request $request)
    {
        $cart = Cart::firstOrCreate(['user_id' => Auth::id()], ['items' => []]);

        if ($request->isMethod('put') || $request->isMethod('patch')) {
            $items = $request->input('items', []);
            if (! is_array($items) || count($items) > 100) {
                return response()->json(['message' => 'Giỏ hàng không hợp lệ.'], 422);
            }
            $normalized = collect($items)->map(function ($item) {
                return [
                    'id' => (int) ($item['id'] ?? 0),
                    'variant_id' => ! empty($item['variant_id']) ? (int) $item['variant_id'] : null,
                    'name' => strip_tags((string) ($item['name'] ?? '')),
                    'variant_name' => strip_tags((string) ($item['variant_name'] ?? '')),
                    'price' => max(0, (float) ($item['price'] ?? 0)),
                    'category' => strip_tags((string) ($item['category'] ?? '')),
                    'stock' => max(0, (int) ($item['stock'] ?? 0)),
                    'quantity' => min(100000, max(1, (int) ($item['quantity'] ?? 1))),
                    'checked' => ($item['checked'] ?? true) !== false,
                ];
            })->filter(fn ($item) => $item['id'] > 0)
                ->groupBy(fn ($item) => $item['id'].':'.($item['variant_id'] ?? 0))
                ->map(function ($group) {
                    $item = $group->first();
                    // PUT is a replacement snapshot. Duplicate lines from a repeated
                    // frontend request must not turn into an implicit quantity increment.
                    $item['quantity'] = min(100000, $group->max('quantity'));

                    return $item;
                })
                ->values()
                ->all();
            $cart->update(['items' => $normalized]);
        }

        return response()->json(['items' => $cart->items ?: []]);
    }

    public function addresses()
    {
        $addresses = Auth::user()->addresses()->orderByDesc('is_default')->latest()->get();
        return view('user.addresses', compact('addresses'));
    }

    public function storeAddress(Request $request)
    {
        $data = $request->validate([
            'label' => ['nullable', 'string', 'max:50'],
            'recipient_name' => ['required', 'string', 'max:100'],
            'phone' => ['required', 'regex:/^0\d{9}$/'],
            'address' => ['required', 'string', 'max:255'],
            'district_id' => ['nullable', 'integer'],
            'ward_code' => ['nullable', 'string', 'max:30'],
            'is_default' => ['nullable', 'boolean'],
        ]);
        $data['user_id'] = Auth::id();
        $address = UserAddress::create($data);
        if ($request->boolean('is_default') || Auth::user()->addresses()->count() === 1) {
            $this->makeDefault($address);
        }
        return back()->with('success', 'Đã lưu địa chỉ giao hàng.');
    }

    public function destroyAddress(UserAddress $address)
    {
        $this->ensureOwner($address);
        $wasDefault = $address->is_default;
        $address->delete();
        if ($wasDefault && ($replacement = Auth::user()->addresses()->latest()->first())) $this->makeDefault($replacement);
        return back()->with('success', 'Đã xóa địa chỉ.');
    }

    public function defaultAddress(UserAddress $address)
    {
        $this->ensureOwner($address);
        $this->makeDefault($address);
        return back()->with('success', 'Đã chọn địa chỉ mặc định.');
    }

    public function validateCoupon(Request $request)
    {
        $data = $request->validate(['code' => ['required', 'string', 'max:50'], 'subtotal' => ['required', 'numeric', 'min:0']]);
        $coupon = Coupon::where('code', strtoupper(trim($data['code'])))->first();
        if (! $coupon || ! $coupon->isUsableFor((float) $data['subtotal']) || CouponRedemption::where('coupon_id', $coupon->id)->where('user_id', Auth::id())->exists()) {
            return response()->json(['message' => 'Mã giảm giá không hợp lệ hoặc đã được sử dụng.'], 422);
        }
        return response()->json(['code' => $coupon->code, 'discount' => $coupon->discountFor((float) $data['subtotal'])]);
    }

    public function storeReview(Request $request, Product $product)
    {
        $data = $request->validate(['rating' => ['required', 'integer', 'between:1,5'], 'body' => ['nullable', 'string', 'max:2000'], 'order_id' => ['required', 'integer']]);
        $order = Order::whereKey($data['order_id'])->where('user_id', Auth::id())->where('status', 'delivered')->whereHas('items', fn ($query) => $query->where('product_id', $product->id))->firstOrFail();
        ProductReview::updateOrCreate(['product_id' => $product->id, 'user_id' => Auth::id(), 'order_id' => $order->id], $data + ['status' => 'pending']);
        return back()->with('success', 'Đánh giá đã gửi và đang chờ duyệt.');
    }

    public function notifications()
    {
        $notifications = Auth::user()->notifications()->latest()->paginate(20);
        return view('user.notifications', compact('notifications'));
    }

    public function loyalty()
    {
        $transactions = Auth::user()->loyaltyTransactions()->latest()->paginate(15);
        return view('user.loyalty', compact('transactions'));
    }

    public function redeemPoints(Request $request, LoyaltyService $loyalty)
    {
        $data = $request->validate(['points' => ['required', 'integer', 'min:100', 'max:1000000']]);
        $code = $loyalty->redeem(Auth::user(), (int) $data['points']);
        return back()->with('success', 'Đã đổi điểm thành công. Mã giảm giá của bạn: '.$code);
    }

    public function readNotification(SiteNotification $notification)
    {
        if ($notification->user_id !== Auth::id()) abort(403);
        $notification->update(['read_at' => now()]);
        return redirect($notification->url ?: route('user.notifications'));
    }

    private function makeDefault(UserAddress $address): void
    {
        Auth::user()->addresses()->where('id', '!=', $address->id)->update(['is_default' => false]);
        $address->update(['is_default' => true]);
    }

    private function ensureOwner(UserAddress $address): void
    {
        if ($address->user_id !== Auth::id()) abort(403);
    }
}
