<?php

namespace App\Http\Controllers\User;

use App\Http\Controllers\Controller;
use App\Models\InventoryMovement;
use App\Models\Order;
use App\Models\OrderItem;
use App\Models\OrderStatusHistory;
use App\Models\PaymentTransaction;
use App\Models\Product;
use App\Models\ProductVariant;
use App\Models\Coupon;
use App\Models\CouponRedemption;
use App\Models\UserAddress;
use App\Models\User;
use App\Services\GHNOrderService;
use App\Services\GHNService;
use App\Services\MomoService;
use App\Services\OrderCancellationService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Validator;
use Illuminate\Validation\ValidationException;

class OrderController extends Controller
{
    public function index()
    {
        $addresses = Auth::user()->addresses()->orderByDesc('is_default')->latest()->get();
        return view('checkout.index', compact('addresses'));
    }

    public function orderHistory()
    {
        $orders = Order::where('user_id', Auth::id())
            ->with(['items.product', 'paymentTransaction'])
            ->orderByDesc('created_at')
            ->paginate(10);

        return view('user.payment.order', compact('orders'));
    }

    public function show(Order $order)
    {
        /** @var User|null $user */
        $user = Auth::user();
        $isOwner = $user && $order->user_id === $user->id;
        $isAdmin = $user && $user->isAdmin();

        if (! $isOwner && ! $isAdmin) {
            abort(403);
        }

        $order->load('items.product', 'items.variant', 'statusHistories.user', 'paymentTransaction');

        return view('user.payment.show', compact('order'));
    }

    public function getProvinces(GHNService $ghn)
    {
        return response()->json($ghn->getProvinces());
    }

    public function getDistricts(int $provinceId, GHNService $ghn)
    {
        return response()->json($ghn->getDistricts($provinceId));
    }

    public function getWards(int $districtId, GHNService $ghn)
    {
        return response()->json($ghn->getWards($districtId));
    }

    public function getShippingFee(Request $request, GHNService $ghn)
    {
        $cart = $this->cartItemsFromRequest($request);
        $productIds = collect($cart)->pluck('id')->filter()->all();
        $products = Product::whereIn('id', $productIds)->get()->keyBy('id');

        $totalWeight = collect($cart)->sum(function (array $item) use ($products) {
            $product = $products->get((int) ($item['id'] ?? 0));
            $weight = (int) ($product->weight ?? 200);

            return $weight * (int) ($item['quantity'] ?? 1);
        });

        $toDistrictId = (int) $request->input('to_district_id');
        $toWardCode = (string) $request->input('to_ward_code');

        if (! $toDistrictId) {
            return response()->json(['code' => 400, 'message' => 'Mã quận/huyện không hợp lệ.']);
        }

        $fromDistrictId = (int) config('services.ghn.from_district_id', env('GHN_FROM_DISTRICT_ID', 1450));

        $res = $ghn->calculateFee(array_merge([
            'service_type_id' => 2,
            'from_district_id' => $fromDistrictId,
            'to_district_id' => $toDistrictId,
            'to_ward_code' => $toWardCode,
        ], $ghn->packageParameters($totalWeight > 0 ? $totalWeight : 300)));

        return response()->json($res);
    }

    public function processPayment(Request $request, GHNService $ghn, GHNOrderService $ghnOrders, MomoService $momoService)
    {
        if (! Auth::check()) {
            return response()->json(['message' => 'Vui lòng đăng nhập để thanh toán.'], 401);
        }

        $validated = $request->validate([
            'name' => ['required', 'string', 'max:100'],
            'phone' => ['required', 'regex:/^0\d{9}$/'],
            'address' => ['required', 'string', 'max:255'],
            'to_district_id' => ['required', 'integer'],
            'to_ward_code' => ['required', 'string'],
            'payment_method' => ['required', 'in:cod,momo,momo_atm,momo_cc'],
            'cart_items' => ['required', 'string'],
            'address_id' => ['nullable', 'integer'],
            'coupon_code' => ['nullable', 'string', 'max:50'],
        ]);

        if (! empty($validated['address_id'])) {
            $savedAddress = UserAddress::where('user_id', Auth::id())->findOrFail($validated['address_id']);
            $validated['name'] = $savedAddress->recipient_name;
            $validated['phone'] = $savedAddress->phone;
            $validated['address'] = $savedAddress->address;
            $validated['to_district_id'] = $savedAddress->district_id ?: $validated['to_district_id'];
            $validated['to_ward_code'] = $savedAddress->ward_code ?: $validated['to_ward_code'];
        }

        $cart = json_decode($validated['cart_items'], true);
        if (! is_array($cart) || empty($cart)) {
            throw ValidationException::withMessages(['cart_items' => 'Giỏ hàng đang trống.']);
        }

        Validator::make(['cart_items' => $cart], [
            'cart_items' => ['required', 'array', 'min:1', 'max:100'],
            'cart_items.*' => ['required', 'array'],
            'cart_items.*.id' => ['required', 'integer', 'min:1'],
            'cart_items.*.variant_id' => ['nullable', 'integer', 'min:1'],
            'cart_items.*.quantity' => ['required', 'integer', 'min:1', 'max:100000'],
        ])->validate();
        $cart = collect($cart)->groupBy(fn ($item) => ((int) ($item['id'] ?? 0)).':'.((int) ($item['variant_id'] ?? 0)))->map(function ($items) {
            $first = $items->first();
            return [
                'id' => (int) $first['id'],
                'variant_id' => ! empty($first['variant_id']) ? (int) $first['variant_id'] : null,
                'quantity' => $items->sum('quantity'),
            ];
        })->values()->all();

        // Tính cước ngoài transaction
        $productIds = collect($cart)->pluck('id')->filter()->all();
        $productsForWeight = Product::whereIn('id', $productIds)->get()->keyBy('id');
        $totalWeight = 0;

        foreach ($cart as $item) {
            $prod = $productsForWeight->get((int) ($item['id'] ?? 0));
            $variant = ! empty($item['variant_id']) ? ProductVariant::find((int) $item['variant_id']) : null;
            $w = (int) ($variant->weight ?? $prod->weight ?? 200);
            $totalWeight += $w * (int) ($item['quantity'] ?? 1);
        }

        $fromDistrictId = (int) config('services.ghn.from_district_id', env('GHN_FROM_DISTRICT_ID', 1450));
        $feeResponse = $ghn->calculateFee(array_merge([
            'service_type_id' => 2,
            'from_district_id' => $fromDistrictId,
            'to_district_id' => (int) $validated['to_district_id'],
            'to_ward_code' => (string) $validated['to_ward_code'],
        ], $ghn->packageParameters($totalWeight > 0 ? $totalWeight : 300)));

        $shippingFee = ($feeResponse['code'] ?? 0) === 200 && isset($feeResponse['data']['total'])
            ? (int) $feeResponse['data']['total']
            : 28000;

        $paymentMethod = $validated['payment_method'];
        $coupon = ! empty($validated['coupon_code'])
            ? Coupon::where('code', strtoupper(trim($validated['coupon_code'])))->first()
            : null;
        if (! empty($validated['coupon_code']) && ! $coupon) {
            throw ValidationException::withMessages(['coupon_code' => 'Mã giảm giá không tồn tại.']);
        }
        $couponId = $coupon?->id;
        $discountAmount = 0;

        // Mở transaction kiểm tra kho và tạo đơn
        $order = DB::transaction(function () use ($validated, $cart, $shippingFee, $paymentMethod, $couponId, &$discountAmount) {
            $productIds = collect($cart)->pluck('id')->filter()->all();
            $products = Product::whereIn('id', $productIds)->orderBy('id')->lockForUpdate()->get()->keyBy('id');
            $variantIds = collect($cart)->pluck('variant_id')->filter()->all();
            $variants = ProductVariant::whereIn('id', $variantIds)->orderBy('id')->lockForUpdate()->get()->keyBy('id');
            $coupon = $couponId ? Coupon::lockForUpdate()->find($couponId) : null;

            $subtotal = 0;
            $orderItemsData = [];

            foreach ($cart as $item) {
                $productId = (int) ($item['id'] ?? 0);
                $quantity = (int) ($item['quantity'] ?? 0);
                $product = $products->get($productId);
                $variant = ! empty($item['variant_id']) ? $variants->get((int) $item['variant_id']) : null;

                if (! $product) {
                    throw ValidationException::withMessages(['cart_items' => "Sản phẩm ID #{$productId} không tồn tại."]);
                }

                if ($variant && $variant->product_id !== $product->id) {
                    throw ValidationException::withMessages(['cart_items' => 'Phiên bản sản phẩm không hợp lệ.']);
                }
                $availableStock = $variant ? $variant->stock : $product->stock;
                if ($quantity <= 0 || $availableStock < $quantity) {
                    throw ValidationException::withMessages([
                        'cart_items' => "Sản phẩm \"{$product->name}\" không đủ hàng trong kho (Còn: {$product->stock}).",
                    ]);
                }

                $dbPrice = (float) ($variant?->price ?? $product->price);
                $subtotal += $dbPrice * $quantity;

                $orderItemsData[] = [
                    'product' => $product,
                    'variant' => $variant,
                    'quantity' => $quantity,
                    'price' => $dbPrice,
                ];
            }

            $discountAmount = $coupon?->discountFor($subtotal) ?? 0;
            if ($couponId && (! $coupon || ! $coupon->isUsableFor($subtotal) || CouponRedemption::where('coupon_id', $coupon->id)->where('user_id', Auth::id())->exists())) {
                throw ValidationException::withMessages(['coupon_code' => 'Mã giảm giá không hợp lệ hoặc đã được sử dụng.']);
            }
            $finalTotal = max(0, $subtotal - $discountAmount) + $shippingFee;

            $newOrder = Order::create([
                'user_id' => Auth::id(),
                'name' => $validated['name'],
                'address' => $validated['address'],
                'phone' => $validated['phone'],
                'total_price' => $finalTotal,
                'discount_amount' => $discountAmount,
                'coupon_code' => $coupon?->code,
                'status' => $paymentMethod === 'cod' ? 'cod_ordered' : 'pending',
                'stock_deducted' => $paymentMethod === 'cod',
                'to_district_id' => (int) $validated['to_district_id'],
                'to_ward_code' => (string) $validated['to_ward_code'],
                'ghn_total_fee' => $shippingFee,
                'shipping_status' => 'pending',
            ]);

            OrderStatusHistory::create([
                'order_id' => $newOrder->id,
                'status' => 'pending',
                'note' => 'Khách hàng khởi tạo đơn hàng ('.strtoupper($paymentMethod).')',
                'changed_by' => Auth::id(),
            ]);

            foreach ($orderItemsData as $itemData) {
                OrderItem::create([
                    'order_id' => $newOrder->id,
                    'product_id' => $itemData['product']->id,
                    'variant_id' => $itemData['variant']?->id,
                    'quantity' => $itemData['quantity'],
                    'price' => $itemData['price'],
                ]);

                // CHỈ TRỪ TỒN KHO NGAY NẾU LÀ ĐƠN COD
                if ($paymentMethod === 'cod') {
                    $stockItem = $itemData['variant'] ?: $itemData['product'];
                    $stockItem->decrement('stock', $itemData['quantity']);
                    InventoryMovement::create([
                        'product_id' => $itemData['product']->id,
                        'variant_id' => $itemData['variant']?->id,
                        'user_id' => Auth::id(),
                        'type' => 'out',
                        'quantity' => -$itemData['quantity'],
                        'stock_after' => $stockItem->fresh()->stock,
                        'note' => "Trừ kho cho đơn COD #{$newOrder->id}",
                    ]);
                }
            }

            PaymentTransaction::create([
                'order_id' => $newOrder->id,
                'gateway' => $paymentMethod === 'cod' ? 'cod' : 'momo',
                'amount' => $newOrder->total_price,
                'status' => 'pending',
            ]);

            if ($coupon && $discountAmount > 0) {
                $coupon->increment('used_count');
                CouponRedemption::create([
                    'coupon_id' => $coupon->id,
                    'user_id' => Auth::id(),
                    'order_id' => $newOrder->id,
                    'discount_amount' => $discountAmount,
                ]);
            }

            return $newOrder;
        });

        // Xử lý cổng MoMo
        if (in_array($paymentMethod, ['momo', 'momo_atm', 'momo_cc'], true)) {
            $isCC = ($paymentMethod === 'momo_cc');
            $requestType = $isCC ? 'payWithCC' : 'payWithATM';
            $methodTitle = $isCC ? 'Thanh toán Thẻ quốc tế Visa/Master' : 'Thanh toán Thẻ ATM nội địa (Napas)';

            $transaction = $order->paymentTransaction;
            $transaction->update(['message' => $methodTitle]);

            $momoResult = $momoService->createPayment($order, $transaction, $requestType);

            if (! empty($momoResult['payUrl'])) {
                return response()->json([
                    'status' => 'success',
                    'payment_method' => $paymentMethod,
                    'redirect_url' => $momoResult['payUrl'],
                ]);
            }

            return response()->json([
                'status' => 'error',
                'message' => 'Lỗi kết nối cổng thanh toán thẻ: '.($momoResult['message'] ?? 'Không lấy được đường dẫn thanh toán.'),
            ], 422);
        }

        // Xử lý COD
        return DB::transaction(function () use ($order, $ghnOrders) {
            $order = Order::lockForUpdate()->findOrFail($order->id);
            if ($order->status === 'cancelled') {
                return response()->json(['status' => 'error', 'message' => 'Đơn đã hủy.'], 409);
            }
            $order->load('items.product');
            $ghnOrderResponse = $ghnOrders->create($order);

            if (($ghnOrderResponse['code'] ?? null) == 200 && ! empty($ghnOrderResponse['data']['order_code'])) {
                $order->update([
                    'status' => 'cod_ordered',
                    'ghn_order_code' => $ghnOrderResponse['data']['order_code'],
                    'shipping_status' => 'ready_to_pick',
                ]);

                OrderStatusHistory::create([
                    'order_id' => $order->id,
                    'status' => 'cod_ordered',
                    'note' => 'Tạo vận đơn GHN tự động thành công: '.$ghnOrderResponse['data']['order_code'],
                    'changed_by' => Auth::id(),
                ]);

                return response()->json([
                    'status' => 'success',
                    'payment_method' => 'cod',
                    'message' => 'Đặt hàng thành công! Mã vận đơn GHN: '.$ghnOrderResponse['data']['order_code'],
                    'redirect_url' => route('user.orders.index'),
                ]);
            }

            Log::error('GHN COD Order Failed: ', $ghnOrderResponse ?? []);
            $order->update(['status' => 'cod_ordered']);

            return response()->json([
                'status' => 'warning',
                'payment_method' => 'cod',
                'message' => 'Đặt hàng thành công! (Vận đơn GHN sẽ được quản trị viên xử lý sau).',
                'redirect_url' => route('user.orders.index'),
            ]);
        });
    }

    public function cancel(Order $order, GHNService $ghn)
    {
        /** @var User|null $user */
        $user = Auth::user();
        if (! $user || ($order->user_id !== $user->id && ! $user->isAdmin())) {
            abort(403);
        }

        app(OrderCancellationService::class)->cancel($order, $user, $ghn);

        return back()->with('success', 'Đơn hàng đã được hủy thành công.');
    }

    private function cartItemsFromRequest(Request $request): array
    {
        $rawItems = $request->input('cart_items', '[]');
        if (is_array($rawItems)) {
            return $rawItems;
        }

        $decoded = json_decode((string) $rawItems, true);

        return is_array($decoded) ? $decoded : [];
    }
}
