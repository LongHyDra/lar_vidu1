<?php

namespace App\Http\Controllers;

use App\Models\Category;
use App\Models\InventoryMovement;
use App\Models\Order;
use App\Models\OrderItem;
use App\Models\OrderStatusHistory;
use App\Models\PaymentTransaction;
use App\Models\Product;
use App\Models\User;
use App\Services\GHNOrderService;
use App\Services\GHNService;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;

class AdminController extends Controller
{
    /**
     * Bảng điều khiển trung tâm & phân vùng quản lý
     */
    public function dashboard(Request $request)
    {
        $section = $request->input('section', 'overview');
        if (!in_array($section, ['overview', 'financial', 'users', 'payments', 'orders', 'inventory'], true)) {
            $section = 'overview';
        }

        $orderStatus = $request->input('order_status');
        if ($orderStatus && !in_array($orderStatus, ['pending', 'confirmed', 'packaging', 'shipping', 'delivered', 'cancelled', 'cod_ordered'], true)) {
            $orderStatus = null;
        }

        $period = (int) $request->input('period', 30);
        if (!in_array($period, [7, 30, 90, 365], true)) {
            $period = 30;
        }

        $fromDate = $request->input('from');
        $toDate = $request->input('to');

        if ($fromDate && !preg_match('/^\d{4}-\d{2}-\d{2}$/', $fromDate)) {
            $fromDate = null;
        }

        if ($toDate && !preg_match('/^\d{4}-\d{2}-\d{2}$/', $toDate)) {
            $toDate = null;
        }

        // 1. Thống kê sản phẩm & kho hàng
        $totalProducts = Product::count();
        $totalCategories = Category::count();
        $lowStockProducts = Product::where('stock', '<=', 5)->count();
        $totalValue = Product::sum(DB::raw('price * stock'));

        // 2. Thống kê đơn hàng & doanh thu (loại bỏ đơn hủy)
        $activeOrders = Order::whereNotIn('status', ['cancelled']);
        $totalOrdersQuery = Order::query();
        $cancelledOrdersQuery = Order::where('status', 'cancelled');

        if ($fromDate) {
            $activeOrders->where('created_at', '>=', Carbon::parse($fromDate)->startOfDay());
            $totalOrdersQuery->where('created_at', '>=', Carbon::parse($fromDate)->startOfDay());
            $cancelledOrdersQuery->where('created_at', '>=', Carbon::parse($fromDate)->startOfDay());
        }

        if ($toDate) {
            $activeOrders->where('created_at', '<=', Carbon::parse($toDate)->endOfDay());
            $totalOrdersQuery->where('created_at', '<=', Carbon::parse($toDate)->endOfDay());
            $cancelledOrdersQuery->where('created_at', '<=', Carbon::parse($toDate)->endOfDay());
        }

        $totalOrders = $totalOrdersQuery->count();
        $cancelledOrders = $cancelledOrdersQuery->count();
        $totalRevenue = (clone $activeOrders)->sum('total_price');

        // 3. Số lượng bán & doanh thu thời điểm
        $soldQtyQuery = OrderItem::join('orders', 'order_items.order_id', '=', 'orders.id')
            ->where('orders.status', '!=', 'cancelled');

        if ($fromDate) {
            $soldQtyQuery->where('orders.created_at', '>=', Carbon::parse($fromDate)->startOfDay());
        }

        if ($toDate) {
            $soldQtyQuery->where('orders.created_at', '<=', Carbon::parse($toDate)->endOfDay());
        }

        $totalSoldQty = $soldQtyQuery->sum('order_items.quantity');
        $todayRevenue = (clone $activeOrders)->whereDate('created_at', today())->sum('total_price');
        $monthRevenue = (clone $activeOrders)
            ->whereYear('created_at', now()->year)
            ->whereMonth('created_at', now()->month)
            ->sum('total_price');

        // 4. Top 5 sản phẩm bán chạy nhất
        $topProductsQuery = OrderItem::join('products', 'order_items.product_id', '=', 'products.id')
            ->join('orders', 'order_items.order_id', '=', 'orders.id')
            ->select('products.name', DB::raw('SUM(order_items.quantity) as total_qty'))
            ->where('orders.status', '!=', 'cancelled');

        if ($fromDate) {
            $topProductsQuery->where('orders.created_at', '>=', Carbon::parse($fromDate)->startOfDay());
        }

        if ($toDate) {
            $topProductsQuery->where('orders.created_at', '<=', Carbon::parse($toDate)->endOfDay());
        }

        $topProducts = $topProductsQuery->groupBy('products.id', 'products.name')
            ->orderByDesc('total_qty')
            ->limit(5)
            ->get();

        // 5. Biểu đồ doanh thu theo ngày
        $dailyRevenueQuery = Order::select(
            DB::raw('DATE(created_at) as date'),
            DB::raw('SUM(total_price) as total_revenue')
        )->whereNotIn('status', ['cancelled']);

        if ($fromDate) {
            $dailyRevenueQuery->where('created_at', '>=', Carbon::parse($fromDate)->startOfDay());
        } else {
            $dailyRevenueQuery->where('created_at', '>=', now()->subDays($period - 1)->startOfDay());
        }

        if ($toDate) {
            $dailyRevenueQuery->where('created_at', '<=', Carbon::parse($toDate)->endOfDay());
        }

        $dailyRevenue = $dailyRevenueQuery->groupBy(DB::raw('DATE(created_at)'))
            ->orderBy('date')
            ->get();

        // 6. Biểu đồ doanh thu 12 tháng (tự tương thích SQLite & MySQL)
        $monthFormat = DB::getDriverName() === 'sqlite'
            ? "strftime('%Y-%m', created_at)"
            : "DATE_FORMAT(created_at, '%Y-%m')";

        $monthlyRevenue = Order::select(
            DB::raw("{$monthFormat} as month"),
            DB::raw('SUM(total_price) as total_revenue')
        )
            ->whereNotIn('status', ['cancelled'])
            ->where('created_at', '>=', now()->subMonths(11)->startOfMonth())
            ->groupBy(DB::raw($monthFormat))
            ->orderBy('month')
            ->get();

        // 7. Nạp danh sách đơn hàng chi tiết
        $recentOrders = Order::with(['user', 'items.product', 'paymentTransaction', 'statusHistories.user'])
            ->when($orderStatus, fn($query) => $query->where('status', $orderStatus))
            ->orderByDesc('created_at')
            ->get();

        $orderStatusCounts = Order::select('status', DB::raw('COUNT(*) as total'))
            ->groupBy('status')
            ->pluck('total', 'status');

        $users = User::withCount('orders')->latest()->get();
        $transactions = PaymentTransaction::with('order.user')->latest()->get();
        $movements = InventoryMovement::with('product', 'user')->latest()->limit(50)->get();

        return view('admin.dashboard', [
            'totalProducts'      => $totalProducts,
            'totalCategories'    => $totalCategories,
            'lowStockProducts'   => $lowStockProducts,
            'totalValue'         => $totalValue,
            'totalOrders'        => $totalOrders,
            'cancelledOrders'    => $cancelledOrders,
            'totalRevenue'       => $totalRevenue,
            'totalSoldQty'       => $totalSoldQty,
            'todayRevenue'       => $todayRevenue,
            'monthRevenue'       => $monthRevenue,
            'period'             => $period,
            'fromDate'           => $fromDate,
            'toDate'             => $toDate,
            'topProducts'        => $topProducts,
            'dailyRevenue'       => $dailyRevenue,
            'monthlyRevenue'     => $monthlyRevenue,
            'recentOrders'       => $recentOrders,
            'section'            => $section,
            'orderStatus'        => $orderStatus,
            'orderStatusCounts'  => $orderStatusCounts,
            'users'              => $users,
            'transactions'       => $transactions,
            'movements'          => $movements,
        ]);
    }

    /**
     * Xuất file CSV doanh thu chuẩn UTF-8
     */
    public function exportRevenueCsv(Request $request)
    {
        $from = $request->input('from');
        $to = $request->input('to');

        $orders = Order::with(['user', 'paymentTransaction'])
            ->whereNotIn('status', ['cancelled'])
            ->when($from, fn($q) => $q->whereDate('created_at', '>=', $from))
            ->when($to, fn($q) => $q->whereDate('created_at', '<=', $to))
            ->orderByDesc('created_at')
            ->get();

        return response()->streamDownload(function () use ($orders) {
            $handle = fopen('php://output', 'w');
            // Chèn BOM UTF-8 để Microsoft Excel mở tiếng Việt không bị lỗi font
            fwrite($handle, "\xEF\xBB\xBF");
            fputcsv($handle, ['Mã đơn', 'Ngày đặt', 'Khách hàng', 'Số điện thoại', 'Cổng thanh toán', 'Tiền hàng', 'Phí GHN', 'Tổng tiền', 'Trạng thái đơn', 'Tiến độ giao']);

            foreach ($orders as $order) {
                $gateway = $order->paymentTransaction->gateway ?? (str_starts_with($order->status, 'cod') ? 'cod' : 'momo');
                $productSubtotal = max(0, $order->total_price - ($order->ghn_total_fee ?? 0));

                fputcsv($handle, [
                    '#' . $order->id,
                    $order->created_at->format('d/m/Y H:i'),
                    $order->user->name ?? $order->name,
                    $order->phone,
                    strtoupper($gateway),
                    $productSubtotal,
                    $order->ghn_total_fee ?? 0,
                    $order->total_price,
                    $order->status,
                    $order->shipping_status ?? 'pending',
                ]);
            }
            fclose($handle);
        }, 'bao-cao-doanh-thu-' . now()->format('Ymd-His') . '.csv', [
            'Content-Type' => 'text/csv; charset=UTF-8',
        ]);
    }

    /**
     * Xuất báo cáo Excel đối soát
     */
    public function exportRevenueExcel(Request $request)
    {
        $from = $request->input('from');
        $to = $request->input('to');

        $orders = Order::with(['user', 'paymentTransaction'])
            ->whereNotIn('status', ['cancelled'])
            ->when($from, fn($q) => $q->whereDate('created_at', '>=', $from))
            ->when($to, fn($q) => $q->whereDate('created_at', '<=', $to))
            ->orderByDesc('created_at')
            ->get();

        $filename = 'bao-cao-doanh-thu-' . date('d-m-Y') . '.xls';

        return response()->view('admin.reports.revenue-excel', compact('orders', 'from', 'to'))
            ->header('Content-Type', 'application/vnd.ms-excel; charset=UTF-8')
            ->header('Content-Disposition', "attachment; filename=\"{$filename}\"")
            ->header('Pragma', 'no-cache')
            ->header('Expires', '0');
    }

    /**
     * Mở giao diện bản in A4 / Xuất PDF kế toán
     */
    public function printRevenueReport(Request $request)
    {
        $from = $request->input('from');
        $to = $request->input('to');

        $orders = Order::with(['user', 'paymentTransaction'])
            ->whereNotIn('status', ['cancelled'])
            ->when($from, fn($q) => $q->whereDate('created_at', '>=', $from))
            ->when($to, fn($q) => $q->whereDate('created_at', '<=', $to))
            ->orderByDesc('created_at')
            ->get();

        return view('admin.reports.revenue-print', [
            'orders' => $orders,
            'from'   => $from,
            'to'     => $to,
        ]);
    }

    /**
     * Danh sách phân trang đơn hàng độc lập
     */
    public function orders()
    {
        $orders = Order::with(['user', 'items.product', 'paymentTransaction'])
            ->orderByDesc('created_at')
            ->paginate(20);

        return view('admin.orders', compact('orders'));
    }

    /**
     * Cập nhật tiến độ giao hàng & Tự động đồng bộ sang GHN và bảng Thanh toán
     */
    public function updateOrderStatus(Request $request, Order $order, GHNService $ghn)
    {
        $request->validate([
            'status' => ['required', 'string', 'in:pending,confirmed,packaging,shipping,delivered,cancelled'],
        ]);

        $newStatus = $request->input('status');

        if ($order->status === 'cancelled' && $newStatus !== 'cancelled') {
            return redirect()->route('admin.dashboard', ['section' => 'orders'])
                ->with('error', 'Đơn hàng đã hủy không thể thay đổi trạng thái.');
        }

        // 1. Nếu hủy đơn: Hủy vận đơn GHN và hoàn lại tồn kho sản phẩm
        if ($newStatus === 'cancelled' && $order->status !== 'cancelled') {
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
                        'product_id'  => $item->product->id,
                        'user_id'     => Auth::id(),
                        'type'        => 'in',
                        'quantity'    => $item->quantity,
                        'stock_after' => $item->product->fresh()->stock,
                        'note'        => "Hoàn kho khi admin hủy đơn #{$order->id}",
                    ]);
                }
            }
        }

        // 2. Tự động đồng bộ: Khi giao hàng thành công -> chuyển giao dịch sang Đã thu tiền (paid)
        if ($newStatus === 'delivered') {
            $payment = $order->paymentTransaction;
            if ($payment && $payment->status !== 'paid') {
                $payment->update([
                    'status'  => 'paid',
                    'paid_at' => now(),
                    'message' => 'Admin xác nhận giao hàng thành công (Đã thu tiền)',
                ]);
            }

            // Đồng bộ sang GHN Sandbox nếu có mã vận đơn
            if (!empty($order->ghn_order_code)) {
                try {
                    if (method_exists($ghn, 'switchStatus')) {
                        $ghn->switchStatus([$order->ghn_order_code], 'delivered');
                    }
                } catch (\Exception $e) {
                    Log::warning("Không thể đổi trạng thái GHN #{$order->ghn_order_code}: " . $e->getMessage());
                }
            }
        }

        $order->status = $newStatus;
        if ($newStatus === 'cancelled') {
            $order->shipping_status = 'cancelled';
        }

        $order->save();

        OrderStatusHistory::create([
            'order_id'   => $order->id,
            'status'     => $newStatus,
            'note'       => 'Admin cập nhật trạng thái đơn: ' . $newStatus,
            'changed_by' => Auth::id(),
        ]);

        return redirect()->route('admin.dashboard', ['section' => 'orders'])
            ->with('success', 'Cập nhật trạng thái đơn hàng thành công.');
    }

    /**
     * Nút bấm tạo vận đơn GHN thủ công cho Admin
     */
    public function createGhnOrder(Order $order, GHNOrderService $ghnOrders)
    {
        $order->load('items.product', 'paymentTransaction');
        
        $payStatus = $order->paymentTransaction->status ?? ($order->status === 'paid' ? 'paid' : '');
        $isPaid = in_array($order->status, ['delivered', 'paid', 'cod_paid'], true) || $payStatus === 'paid';

        $res = $ghnOrders->create($order, $isPaid);

        if (($res['code'] ?? null) == 200 && !empty($res['data']['order_code'])) {
            $order->update([
                'ghn_order_code'  => $res['data']['order_code'],
                'ghn_total_fee'   => (int) ($res['data']['total_fee'] ?? $order->ghn_total_fee),
                'shipping_status' => 'ready_to_pick',
            ]);

            OrderStatusHistory::create([
                'order_id'   => $order->id,
                'status'     => $order->status,
                'note'       => 'Admin tạo vận đơn GHN thành công: ' . $res['data']['order_code'],
                'changed_by' => Auth::id(),
            ]);

            return back()->with('success', 'Tạo vận đơn GHN thành công! Mã: ' . $res['data']['order_code']);
        }

        $errorMsg = $res['message'] ?? ($res['code_message_value'] ?? 'Lỗi không xác định từ GHN');
        return back()->with('error', 'Lỗi GHN: ' . $errorMsg);
    }
}