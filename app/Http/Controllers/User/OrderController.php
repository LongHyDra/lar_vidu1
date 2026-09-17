<?php

namespace App\Http\Controllers\User;

use App\Http\Controllers\Controller;
use App\Models\Order;
use App\Models\OrderItem;
use App\Models\PaymentTransaction;
use App\Models\OrderStatusHistory;
use App\Models\InventoryMovement;
use App\Services\GHNService;
use App\Services\GHNOrderService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Illuminate\Validation\ValidationException;

class OrderController extends Controller
{
    // ==========================================
    // 1. CÁC VIEW HIỂN THỊ ĐƠN HÀNG & THANH TOÁN
    // ==========================================
    public function index()
    {
        $cart = session('cart', []);
        if (empty($cart)) {
            return redirect()->route('cart.index')->with('error', 'Giỏ hàng đang trống.');
        }
        $totalPrice = collect($cart)->sum(fn($item) => $item['price'] * $item['quantity']);
        return view('checkout.index', compact('cart', 'totalPrice'));
    }

    public function orderHistory()
    {
        $orders = Order::where('user_id', Auth::id())
            ->with(['items.product'])
            ->orderByDesc('created_at')
            ->paginate(10);
        return view('user.payment.order', compact('orders'));
    }

    public function show(Order $order)
    {
        $user = Auth::user();
        $isOwner = $user && $order->user_id === $user->id;
        $isAdmin = $user && method_exists($user, 'isAdmin') && $user->isAdmin();

        if (!$isOwner && !$isAdmin) {
            abort(403);
        }

        $order->load('items.product', 'statusHistories.user', 'paymentTransaction');
        return view('user.payment.show', compact('order'));
    }

    // ==========================================
    // 2. AJAX LOCATION & TÍNH PHÍ GHN
    // ==========================================
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
        $totalWeight = collect($cart)->sum(function (array $item) {
            return ((int) ($item['weight'] ?? 200)) * (int) ($item['quantity'] ?? 0);
        });

        $toDistrictId = (int) $request->input('to_district_id');
        $toWardCode = (string) $request->input('to_ward_code');

        if (!$toDistrictId) {
            return response()->json(['code' => 400, 'message' => 'Mã quận/huyện không hợp lệ.']);
        }

        $res = $ghn->calculateFee([
            'service_type_id' => 2,
            'from_district_id' => (int) config('services.ghn.from_district_id', 1450),
            'to_district_id' => $toDistrictId,
            'to_ward_code' => $toWardCode,
            'weight' => $totalWeight > 0 ? $totalWeight : 300,
            'length' => 15,
            'width' => 15,
            'height' => 10,
        ]);

        return response()->json($res);
    }

    public function processPayment(Request $request, GHNService $ghn, GHNOrderService $ghnOrders)
    {
        if (!Auth::check()) {
            return response()->json([
                'message' => 'Bạn cần đăng nhập để đặt hàng.',
            ], 401);
        }

        $validated = $request->validate([
            'name' => ['required', 'string', 'max:100'],
            'phone' => ['required', 'regex:/^0\d{9}$/'],
            'address' => ['required', 'string', 'max:255'],
            'to_district_id' => ['required', 'integer'],
            'to_ward_code' => ['required', 'string'],
            'payment_method' => ['required', 'in:cod,momo'],
            'cart_items' => ['required', 'string'],
        ]);

        $cart = json_decode($validated['cart_items'], true);
        if (!is_array($cart) || empty($cart)) {
            throw ValidationException::withMessages([
                'cart_items' => 'Giỏ hàng đang trống.',
            ]);
        }

        $productMap = \App\Models\Product::whereIn('id', collect($cart)->pluck('id')->filter()->all())->get()->keyBy('id');
        foreach ($cart as $item) {
            $productId = (int) ($item['id'] ?? 0);
            $quantity = (int) ($item['quantity'] ?? 0);
            $product = $productMap->get($productId);

            if (!$product) {
                throw ValidationException::withMessages([
                    'cart_items' => 'Một sản phẩm trong giỏ hàng không còn tồn tại.',
                ]);
            }

            if ($quantity <= 0 || $product->stock < $quantity) {
                throw ValidationException::withMessages([
                    'cart_items' => 'Sản phẩm "' . $product->name . '" không đủ tồn kho để đặt hàng.',
                ]);
            }
        }

        $subtotal = collect($cart)->sum(function (array $item) {
            return (float) ($item['price'] ?? 0) * (int) ($item['quantity'] ?? 0);
        });

        $totalWeight = collect($cart)->sum(function (array $item) use ($ghn) {
            return ((int) ($item['weight'] ?? $ghn->productWeight())) * (int) ($item['quantity'] ?? 0);
        });

        $feeResponse = $ghn->calculateFee(array_merge([
            'service_type_id' => 2,
            'from_district_id' => (int) config('services.ghn.from_district_id', 0),
            'to_district_id' => (int) $validated['to_district_id'],
            'to_ward_code' => (string) $validated['to_ward_code'],
        ], $ghn->packageParameters($totalWeight > 0 ? $totalWeight : 300)));

        $shippingFee = ((isset($feeResponse['code']) && (int) $feeResponse['code'] === 200) && isset($feeResponse['data']['total']))
            ? (int) $feeResponse['data']['total']
            : 0;

        $finalTotal = $subtotal + $shippingFee;

        $order = DB::transaction(function () use ($validated, $cart, $shippingFee, $finalTotal) {
            $order = Order::create([
                'user_id' => Auth::id(),
                'name' => $validated['name'],
                'address' => $validated['address'],
                'phone' => $validated['phone'],
                'total_price' => $finalTotal,
                'status' => 'pending',
                'to_district_id' => (int) $validated['to_district_id'],
                'to_ward_code' => (string) $validated['to_ward_code'],
                'ghn_total_fee' => $shippingFee,
                'shipping_status' => 'pending',
            ]);
            OrderStatusHistory::create([
                'order_id' => $order->id,
                'status' => 'pending',
                'note' => 'Đơn hàng được tạo',
                'changed_by' => Auth::id(),
            ]);

            foreach ($cart as $item) {
                $product = \App\Models\Product::find((int) ($item['id'] ?? 0));
                if ($product) {
                    $quantity = (int) ($item['quantity'] ?? 1);
                    $product->decrement('stock', $quantity);
                    InventoryMovement::create([
                        'product_id' => $product->id,
                        'user_id' => Auth::id(),
                        'type' => 'out',
                        'quantity' => -$quantity,
                        'stock_after' => $product->fresh()->stock,
                        'note' => 'Trừ tồn kho khi đặt đơn #' . $order->id,
                    ]);
                }

                OrderItem::create([
                    'order_id' => $order->id,
                    'product_id' => (int) ($item['id'] ?? 0),
                    'quantity' => (int) ($item['quantity'] ?? 1),
                    'price' => (float) ($item['price'] ?? 0),
                ]);
            }

            return $order;
        });

        if ($validated['payment_method'] === 'momo') {
            PaymentTransaction::create([
                'order_id' => $order->id,
                'gateway' => 'momo',
                'amount' => $order->total_price,
                'status' => 'pending',
            ]);

            $redirectUrl = route('user.orders.momo.start', $order);

            if ($request->expectsJson()) {
                return response()->json([
                    'message' => 'Đơn hàng đã được tạo. Đang chuyển đến MoMo.',
                    'redirect_url' => $redirectUrl,
                ]);
            }

            return redirect()->to($redirectUrl);
        }

        PaymentTransaction::create([
            'order_id' => $order->id,
            'gateway' => 'cod',
            'amount' => $order->total_price,
            'status' => 'pending',
            'message' => 'Thanh toán khi nhận hàng',
        ]);

        $order->load('items.product');
        $ghnOrderResponse = $ghnOrders->create($order);

        if (($ghnOrderResponse['code'] ?? null) == 200 && !empty($ghnOrderResponse['data']['order_code'])) {
            $order->update([
                'status' => 'cod_ordered',
                'ghn_order_code' => $ghnOrderResponse['data']['order_code'],
                'shipping_status' => 'ready_to_pick',
            ]);
            OrderStatusHistory::create([
                'order_id' => $order->id,
                'status' => 'cod_ordered',
                'note' => 'Đã tạo vận đơn GHN',
                'changed_by' => Auth::id(),
            ]);

            if ($request->expectsJson()) {
                return response()->json([
                    'message' => 'Đặt hàng thành công! Mã vận đơn GHN: ' . $ghnOrderResponse['data']['order_code'],
                    'redirect_url' => route('user.orders.index'),
                    'status' => 'success',
                ]);
            }

            return redirect()->route('user.orders.index')
                ->with('success', 'Đặt hàng thành công! Mã vận đơn GHN: ' . $ghnOrderResponse['data']['order_code']);
        }

        Log::error('GHN COD Order Failed: ', $ghnOrderResponse ?? []);
        $order->update(['status' => 'cod_ordered']);

        if ($request->expectsJson()) {
            return response()->json([
                'message' => 'Đặt hàng thành công nhưng chưa thể tạo vận đơn GHN tự động.',
                'redirect_url' => route('user.orders.index'),
                'status' => 'warning',
            ]);
        }

        return redirect()->route('user.orders.index')
            ->with('warning', 'Đặt hàng thành công nhưng chưa thể tạo vận đơn GHN tự động.');
    }

    public function cancel(Order $order)
    {
        $user = Auth::user();
        if (!$user || ($order->user_id !== $user->id && !$user->isAdmin())) {
            abort(403);
        }

        if ($order->status === 'cancelled') {
            return back()->with('info', 'Đơn hàng này đã bị hủy trước đó.');
        }

        foreach ($order->items as $item) {
            if ($item->product) {
                $item->product->increment('stock', $item->quantity);
                InventoryMovement::create([
                    'product_id' => $item->product->id,
                    'user_id' => $user->id,
                    'type' => 'in',
                    'quantity' => $item->quantity,
                    'stock_after' => $item->product->fresh()->stock,
                    'note' => 'Hoàn tồn kho khi hủy đơn #' . $order->id,
                ]);
            }
        }

        $order->status = 'cancelled';
        $order->shipping_status = 'cancelled';
        $order->save();
        OrderStatusHistory::create([
            'order_id' => $order->id,
            'status' => 'cancelled',
            'note' => 'Đơn hàng được hủy',
            'changed_by' => $user->id,
        ]);

        return back()->with('success', 'Đơn hàng đã được hủy và tồn kho đã được hoàn trả.');
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
