<?php

namespace App\Http\Controllers;

use App\Models\Category;
use App\Models\Order;
use App\Models\OrderItem;
use App\Models\OrderStatusHistory;
use App\Models\InventoryMovement;
use App\Models\Product;
use App\Models\User;
use App\Models\PaymentTransaction;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Carbon\Carbon;

class AdminController extends Controller
{
    public function dashboard(Request $request)
    {
        $section = $request->input('section', 'overview');
        if (!in_array($section, ['overview', 'financial', 'users', 'payments', 'orders'], true)) {
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

        $activeOrders = Order::whereNotIn('status', ['cancelled']);
        $fromDate = $request->input('from');
        $toDate = $request->input('to');
        if ($fromDate && preg_match('/^\d{4}-\d{2}-\d{2}$/', $fromDate)) {
            $activeOrders->where('created_at', '>=', Carbon::parse($fromDate)->startOfDay());
        } else {
            $fromDate = null;
        }
        if ($toDate && preg_match('/^\d{4}-\d{2}-\d{2}$/', $toDate)) {
            $activeOrders->where('created_at', '<=', Carbon::parse($toDate)->endOfDay());
        } else {
            $toDate = null;
        }
        $totalProducts = Product::count();
        $totalCategories = Category::count();
        $lowStockProducts = Product::where('stock', '<=', 5)->count();
        $totalValue = Product::sum(DB::raw('price * stock'));
        $totalOrdersQuery = Order::query();
        $cancelledOrdersQuery = Order::where('status', 'cancelled');
        if ($fromDate) {
            $totalOrdersQuery->where('created_at', '>=', Carbon::parse($fromDate)->startOfDay());
            $cancelledOrdersQuery->where('created_at', '>=', Carbon::parse($fromDate)->startOfDay());
        }
        if ($toDate) {
            $totalOrdersQuery->where('created_at', '<=', Carbon::parse($toDate)->endOfDay());
            $cancelledOrdersQuery->where('created_at', '<=', Carbon::parse($toDate)->endOfDay());
        }
        $totalOrders = $totalOrdersQuery->count();
        $cancelledOrders = $cancelledOrdersQuery->count();
        $totalRevenue = (clone $activeOrders)->sum('total_price');
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
        $topProducts = $topProductsQuery->groupBy('products.id', 'products.name')->orderByDesc('total_qty')->limit(5)->get();

        $dailyRevenueQuery = Order::select(
                DB::raw('DATE(created_at) as date'),
                DB::raw('SUM(total_price) as total_revenue')
            )
            ->whereNotIn('status', ['cancelled']);
        if ($fromDate) {
            $dailyRevenueQuery->where('created_at', '>=', Carbon::parse($fromDate)->startOfDay());
        } else {
            $dailyRevenueQuery->where('created_at', '>=', now()->subDays($period - 1)->startOfDay());
        }
        if ($toDate) {
            $dailyRevenueQuery->where('created_at', '<=', Carbon::parse($toDate)->endOfDay());
        }
        $dailyRevenue = $dailyRevenueQuery->groupBy(DB::raw('DATE(created_at)'))->orderBy('date')->get();

        $monthlyRevenue = Order::select(
                DB::raw("strftime('%Y-%m', created_at) as month"),
                DB::raw('SUM(total_price) as total_revenue')
            )
            ->whereNotIn('status', ['cancelled'])
            ->where('created_at', '>=', now()->subMonths(11)->startOfMonth())
            ->groupBy(DB::raw("strftime('%Y-%m', created_at)"))
            ->orderBy('month')
            ->get();

        $recentOrders = Order::with('user', 'items.product')
            ->when($orderStatus, fn ($query) => $query->where('status', $orderStatus))
            ->orderByDesc('created_at')
            ->get();
        $orderStatusCounts = Order::select('status', DB::raw('COUNT(*) as total'))
            ->groupBy('status')
            ->pluck('total', 'status');
        $users = User::withCount('orders')->latest()->get();
        $transactions = PaymentTransaction::with('order.user')->latest()->get();

        return view('admin.dashboard', [
            'totalProducts' => $totalProducts,
            'totalCategories' => $totalCategories,
            'lowStockProducts' => $lowStockProducts,
            'totalValue' => $totalValue,
            'totalOrders' => $totalOrders,
            'cancelledOrders' => $cancelledOrders,
            'totalRevenue' => $totalRevenue,
            'totalSoldQty' => $totalSoldQty,
            'todayRevenue' => $todayRevenue,
            'monthRevenue' => $monthRevenue,
            'period' => $period,
            'fromDate' => $fromDate,
            'toDate' => $toDate,
            'topProducts' => $topProducts,
            'dailyRevenue' => $dailyRevenue,
            'monthlyRevenue' => $monthlyRevenue,
            'recentOrders' => $recentOrders,
            'section' => $section,
            'orderStatus' => $orderStatus,
            'orderStatusCounts' => $orderStatusCounts,
            'users' => $users,
            'transactions' => $transactions,
        ]);
    }

    public function exportRevenueCsv(Request $request)
    {
        $from = $request->input('from');
        $to = $request->input('to');
        $orders = Order::whereNotIn('status', ['cancelled'])
            ->when($from, fn ($query) => $query->whereDate('created_at', '>=', $from))
            ->when($to, fn ($query) => $query->whereDate('created_at', '<=', $to))
            ->orderBy('created_at')
            ->get(['created_at', 'status', 'total_price']);

        return response()->streamDownload(function () use ($orders) {
            $handle = fopen('php://output', 'w');
            fputcsv($handle, ['Ngày', 'Trạng thái', 'Doanh thu']);
            foreach ($orders as $order) {
                fputcsv($handle, [$order->created_at->format('d/m/Y H:i'), $order->status, $order->total_price]);
            }
            fclose($handle);
        }, 'bao-cao-doanh-thu.csv', ['Content-Type' => 'text/csv; charset=UTF-8']);
    }

    public function printRevenueReport(Request $request)
    {
        $orders = Order::whereNotIn('status', ['cancelled'])
            ->when($request->input('from'), fn ($query, $from) => $query->whereDate('created_at', '>=', $from))
            ->when($request->input('to'), fn ($query, $to) => $query->whereDate('created_at', '<=', $to))
            ->orderBy('created_at')
            ->get();

        return view('admin.reports.revenue-print', [
            'orders' => $orders,
            'from' => $request->input('from'),
            'to' => $request->input('to'),
        ]);
    }

    public function orders()
    {
        $orders = Order::with('user', 'items.product')
            ->orderByDesc('created_at')
            ->paginate(20);

        return view('admin.orders', compact('orders'));
    }

    public function updateOrderStatus(Request $request, Order $order)
    {
        $request->validate([
            'status' => ['required', 'string', 'in:pending,confirmed,packaging,shipping,delivered,cancelled'],
        ]);

        $newStatus = $request->input('status');

        if ($order->status === 'cancelled' && $newStatus !== 'cancelled') {
            return redirect()->route('admin.dashboard', ['section' => 'orders'])
                ->with('error', 'Đơn hàng đã hủy không thể thay đổi trạng thái.');
        }

        if ($newStatus === 'cancelled' && $order->status !== 'cancelled') {
            foreach ($order->items as $item) {
                if ($item->product) {
                    $item->product->increment('stock', $item->quantity);
                    InventoryMovement::create([
                        'product_id' => $item->product->id,
                        'user_id' => auth()->id(),
                        'type' => 'in',
                        'quantity' => $item->quantity,
                        'stock_after' => $item->product->fresh()->stock,
                        'note' => 'Hoàn tồn kho khi admin hủy đơn #' . $order->id,
                    ]);
                }
            }
        }

        $order->status = $newStatus;
        $order->save();
        OrderStatusHistory::create([
            'order_id' => $order->id,
            'status' => $newStatus,
            'note' => 'Admin cập nhật trạng thái',
            'changed_by' => auth()->id(),
        ]);

        return redirect()->route('admin.dashboard', ['section' => 'orders'])
            ->with('success', 'Cập nhật trạng thái đơn hàng thành công.');
    }
}
