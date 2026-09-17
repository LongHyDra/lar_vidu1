<?php

namespace App\Http\Controllers\User;

use App\Http\Controllers\Controller;
use App\Models\InventoryMovement;
use App\Models\Order;
use App\Models\OrderItem;
use App\Models\OrderStatusHistory;
use App\Models\PaymentTransaction;
use App\Models\Product;
use App\Models\User;
use App\Services\GHNOrderService;
use App\Services\GHNService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Illuminate\Validation\ValidationException;

class OrderController extends Controller
{
    public function index()
    {
        return view('checkout.index');
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
        /** @var User|null $user */
        $user = Auth::user();
        $isOwner = $user && $order->user_id === $user->id;
        $isAdmin = $user && $user->isAdmin();

        if (!$isOwner && !$isAdmin) {
            abort(403);
        }

        $order->load('items.product', 'statusHistories.user', 'paymentTransaction');
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

        if (!$toDistrictId) {
            return response()->json(['code' => 400, 'message' => 'Mã quận/huyện không hợp lệ.']);
        }

        $res = $ghn->calculateFee(array_merge([
            'service_type_id' => 2,
            'from_district_id' => (int) config('services.ghn.from_district_id', 1450),
            'to_district_id' => $toDistrictId,
            'to_ward_code' => $toWardCode,
        ], $ghn->packageParameters($totalWeight > 0 ? $totalWeight : 300)));

        return response()->json($res);
    }

    public function processPayment(Request $request, GHNService $ghn, GHNOrderService $ghnOrders)
    {
        if (!Auth::check()) {
            return response()->json(['message' => 'Vui lòng đăng nhập để tiếp tục.'], 401);
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
            throw ValidationException::withMessages(['cart_items' => 'Giỏ hàng đang trống.']);
        }

        $order = DB::transaction(function () use ($validated, $cart, $ghn) {
            $productIds = collect($cart)->pluck('id')->filter()->all();
            $products = Product::whereIn('id', $productIds)->lockForUpdate()->get()->keyBy('id');

            $subtotal = 0;
            $totalWeight = 0;
            $orderItemsData = [];

            foreach ($cart as $item) {
                $productId = (int) ($item['id'] ?? 0);
                $quantity = (int) ($item['quantity'] ?? 0);
                $product = $products->get($productId);

                if (!$product) {
                    throw ValidationException::withMessages(['cart_items' => "Sản phẩm ID #{$productId} không tồn tại."]);
                }

                if ($quantity <= 0 || $product->stock < $quantity) {
                    throw ValidationException::withMessages([
                        'cart_items' => "Sản phẩm \"{$product->name}\" không đủ hàng trong kho (Còn: {$product->stock})."
                    ]);
                }

                $dbPrice = (float) $product->price;
                $subtotal += $dbPrice * $quantity;
                $itemWeight = (int) ($product->weight ?? 200);
                $totalWeight += $itemWeight * $quantity;

                $orderItemsData[] = [
                    'product' => $product,
                    'quantity' => $quantity,
                    'price' => $dbPrice,
                ];
            }

            $feeResponse = $ghn->calculateFee(array_merge([
                'service_type_id' => 2,
                'from_district_id' => (int) config('services.ghn.from_district_id', 1450),
                'to_district_id' => (int) $validated['to_district_id'],
                'to_ward_code' => (string) $validated['to_ward_code'],
            ], $ghn->packageParameters($totalWeight > 0 ? $totalWeight : 300)));

            $shippingFee = ($feeResponse['code'] ?? 0) === 200 && isset($feeResponse['data']['total'])
                ? (int) $feeResponse['data']['total']
                : 0;

            $finalTotal = $subtotal + $shippingFee;

            $newOrder = Order::create([
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
                'order_id' => $newOrder->id,
                'status' => 'pending',
                'note' => 'Khởi tạo đơn hàng',
                'changed_by' => Auth::id(),
            ]);

            foreach ($orderItemsData as $itemData) {
                $product = $itemData['product'];
                $quantity = $itemData['quantity'];

                $product->decrement('stock', $quantity);

                InventoryMovement::create([
                    'product_id' => $product->id,
                    'user_id' => Auth::id(),
                    'type' => 'out',
                    'quantity' => -$quantity,
                    'stock_after' => $product->fresh()->stock,
                    'note' => "Trừ kho đơn hàng #{$newOrder->id}",
                ]);

                OrderItem::create([
                    'order_id' => $newOrder->id,
                    'product_id' => $product->id,
                    'quantity' => $quantity,
                    'price' => $itemData['price'],
                ]);
            }

            return $newOrder;
        });

        if ($validated['payment_method'] === 'momo') {
            $redirectUrl = route('user.orders.momo.start', $order);
            return response()->json([
                'status' => 'success',
                'message' => 'Đang chuyển hướng sang MoMo...',
                'redirect_url' => $redirectUrl,
            ]);
        }

        PaymentTransaction::create([
            'order_id' => $order->id,
            'gateway' => 'cod',
            'amount' => $order->total_price,
            'status' => 'pending',
            'message' => 'Thanh toán tiền mặt khi nhận hàng (COD)',
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
                'note' => 'Tạo vận đơn GHN tự động thành công',
                'changed_by' => Auth::id(),
            ]);

            return response()->json([
                'status' => 'success',
                'message' => 'Đặt hàng thành công! Mã vận đơn GHN: ' . $ghnOrderResponse['data']['order_code'],
                'redirect_url' => route('user.orders.index'),
            ]);
        }

        Log::error('GHN COD Order Failed: ', $ghnOrderResponse ?? []);
        $order->update(['status' => 'cod_ordered']);

        return response()->json([
            'status' => 'warning',
            'message' => 'Đặt hàng thành công! (Vận đơn GHN sẽ được admin xử lý sau).',
            'redirect_url' => route('user.orders.index'),
        ]);
    }

    public function cancel(Order $order, GHNService $ghn)
    {
        /** @var User|null $user */
        $user = Auth::user();

        if (!$user || ($order->user_id !== $user->id && !$user->isAdmin())) {
            abort(403);
        }

        if (in_array($order->status, ['shipping', 'delivered', 'cancelled'], true)) {
            return back()->with('error', 'Đơn hàng không thể hủy ở trạng thái hiện tại.');
        }

        DB::transaction(function () use ($order, $user, $ghn) {
            if (!empty($order->ghn_order_code)) {
                try {
                    $ghn->cancelOrder([$order->ghn_order_code]);
                } catch (\Exception $e) {
                    Log::warning("Không thể hủy vận đơn GHN #{$order->ghn_order_code}: " . $e->getMessage());
                }
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
                        'note' => "Hoàn kho khi hủy đơn #{$order->id}",
                    ]);
                }
            }

            $order->update([
                'status' => 'cancelled',
                'shipping_status' => 'cancelled',
            ]);

            OrderStatusHistory::create([
                'order_id' => $order->id,
                'status' => 'cancelled',
                'note' => 'Hủy đơn hàng và hoàn trả tồn kho',
                'changed_by' => $user->id,
            ]);
        });

        return back()->with('success', 'Đơn hàng đã được hủy và tồn kho đã được hoàn lại.');
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
