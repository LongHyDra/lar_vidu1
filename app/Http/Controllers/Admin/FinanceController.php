<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Order;
use App\Models\PaymentTransaction;
use App\Support\PaymentStatus;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Validation\Rule;
use Illuminate\Validation\ValidationException;

class FinanceController extends Controller
{
    private function ordersQuery()
    {
        $orders = DB::table('orders')
            ->leftJoin('payment_transactions as payment', function ($join) {
                $join->on('payment.order_id', '=', 'orders.id')
                    ->where('payment.id', '=', function ($sub) {
                        $sub->select('id')
                            ->from('payment_transactions')
                            ->whereColumn('order_id', 'orders.id')
                            ->orderByRaw(PaymentStatus::SELECTION_PRIORITY)
                            ->orderByDesc('id')
                            ->limit(1);
                    });
            })
            ->select('orders.*', 'payment.id as payment_id', 'payment.paid_at')
            ->selectRaw("COALESCE(payment.gateway, CASE WHEN orders.status IN ('cod_ordered', 'cod_paid') THEN 'cod' WHEN orders.status IN ('paid', 'paid_momo') THEN 'momo' ELSE 'unknown' END) as gateway")
            ->selectRaw("COALESCE(payment.status, CASE WHEN orders.status = 'cod_ordered' THEN 'pending' WHEN orders.status IN ('cod_paid', 'paid_momo') THEN 'paid' ELSE orders.status END) as payment_status");

        return DB::query()->fromSub($orders, 'finance_orders');
    }

    private function filteredOrders(Request $request): array
    {
        $filters = $request->validate([
            'search'         => ['nullable', 'string', 'max:100'],
            'date_from'      => ['nullable', 'date_format:Y-m-d'],
            'date_to'        => ['nullable', 'date_format:Y-m-d', ...($request->filled('date_from') ? ['after_or_equal:date_from'] : [])],
            'min_amount'     => ['nullable', 'numeric', 'min:0', 'max:9999999999999.99'],
            'max_amount'     => ['nullable', 'numeric', 'min:0', 'max:9999999999999.99', ...($request->filled('min_amount') ? ['gte:min_amount'] : [])],
            'gateway'        => ['nullable', Rule::in(['cod', 'momo', 'unknown'])],
            'payment_status' => ['nullable', Rule::in(PaymentStatus::all())],
            'sort'           => ['nullable', Rule::in(['newest', 'oldest', 'amount_asc', 'amount_desc'])],
            'page'           => ['nullable', 'integer', 'min:1'],
        ], [
            'date_to.after_or_equal' => 'Ngày kết thúc phải sau hoặc bằng ngày bắt đầu.',
            'max_amount.gte'         => 'Số tiền tối đa phải lớn hơn hoặc bằng số tiền tối thiểu.',
        ]);

        $query = $this->ordersQuery()->where('created_at', '<=', now());

        if ($request->filled('search')) {
            $search = trim($filters['search']);
            $query->where(function ($query) use ($search) {
                $query->where('name', 'like', '%' . $search . '%')
                    ->orWhere('phone', 'like', '%' . $search . '%');
                if (ctype_digit(ltrim($search, '#'))) {
                    $query->orWhere('id', ltrim($search, '#'));
                }
            });
        }

        foreach (['gateway', 'payment_status'] as $field) {
            if ($request->filled($field)) {
                $query->where($field, $filters[$field]);
            }
        }

        if ($request->filled('date_from')) {
            $query->where('created_at', '>=', Carbon::parse($filters['date_from'])->startOfDay());
        }

        if ($request->filled('date_to')) {
            $query->where('created_at', '<', Carbon::parse($filters['date_to'])->addDay()->startOfDay());
        }

        foreach (['min_amount' => '>=', 'max_amount' => '<='] as $field => $operator) {
            if ($request->filled($field)) {
                $query->where('total_price', $operator, $filters[$field]);
            }
        }

        return [$query, $filters];
    }

    public function index(Request $request)
    {
        return view('admin.finance.index', $this->summaryData($request));
    }

    public function transactions(Request $request)
    {
        return view('admin.finance.transactions', $this->transactionsData($request));
    }

    public function summaryData(Request $request): array
    {
        [$query, $filters] = $this->filteredOrders($request);

        $summary = (clone $query)->selectRaw('COUNT(*) as order_count, COALESCE(SUM(total_price), 0) as total_amount')->first();
        $statusTotals = (clone $query)->select('payment_status')->selectRaw('COUNT(*) as order_count, COALESCE(SUM(total_price), 0) as total_amount')->groupBy('payment_status')->get()->keyBy('payment_status');
        $methodTotals = (clone $query)->select('gateway')->selectRaw('COUNT(*) as order_count, COALESCE(SUM(total_price), 0) as total_amount')->selectRaw("SUM(CASE WHEN payment_status = 'paid' THEN total_price ELSE 0 END) as paid_amount")->groupBy('gateway')->get()->keyBy('gateway');

        return compact('filters', 'summary', 'statusTotals', 'methodTotals') + [
            'statuses' => PaymentStatus::LABELS,
            'methods'  => $this->methods(),
        ];
    }

    public function transactionsData(Request $request): array
    {
        [$query, $filters] = $this->filteredOrders($request);

        [$column, $direction] = match ($filters['sort'] ?? 'newest') {
            'oldest'      => ['created_at', 'asc'],
            'amount_asc'  => ['total_price', 'asc'],
            'amount_desc' => ['total_price', 'desc'],
            default       => ['created_at', 'desc'],
        };

        $orders = $query->orderBy($column, $direction)->orderBy('id', $direction)
            ->paginate(15)->withQueryString();

        return [
            'orders'         => $orders,
            'filters'        => $filters,
            'statuses'       => PaymentStatus::LABELS,
            'codTransitions' => PaymentStatus::COD_TRANSITIONS,
            'methods'        => $this->methods(),
        ];
    }

    public function export(Request $request)
    {
        [$query] = $this->filteredOrders($request);
        $orders = $query->orderByDesc('created_at')->orderByDesc('id')->get();

        return response()->streamDownload(function () use ($orders) {
            $handle = fopen('php://output', 'w');
            fwrite($handle, "\xEF\xBB\xBF");
            fputcsv($handle, ['Mã đơn', 'Người nhận', 'Số điện thoại', 'Phương thức', 'Trạng thái thanh toán', 'Tổng tiền', 'Thời gian tạo', 'Thời gian đã thu']);

            foreach ($orders as $order) {
                fputcsv($handle, [
                    '#' . $order->id,
                    $order->name,
                    $order->phone,
                    $this->methods()[$order->gateway] ?? $order->gateway,
                    PaymentStatus::label($order->payment_status),
                    $order->total_price,
                    Carbon::parse($order->created_at)->format('d/m/Y H:i'),
                    $order->paid_at ? Carbon::parse($order->paid_at)->format('d/m/Y H:i') : '',
                ]);
            }

            fclose($handle);
        }, 'bao-cao-giao-dich-' . now()->format('Ymd-His') . '.csv', [
            'Content-Type' => 'text/csv; charset=UTF-8',
        ]);
    }

    public function updateStatus(Request $request, Order $order)
    {
        $data = $request->validate([
            'payment_status'         => ['required', Rule::in(array_keys(PaymentStatus::COD_TRANSITIONS))],
            'current_payment_status' => ['required', 'string'],
            'current_order_status'   => ['required', 'string'],
            'current_payment_id'     => ['required', 'integer', 'min:0'],
        ]);

        DB::transaction(function () use ($order, $data, $request) {
            /** @var Order $lockedOrder */
            $lockedOrder = Order::whereKey($order->id)->lockForUpdate()->firstOrFail();

            /** @var PaymentTransaction|null $payment */
            $payment = $lockedOrder->paymentTransactions()
                ->orderByRaw(PaymentStatus::SELECTION_PRIORITY)
                ->orderByDesc('id')
                ->lockForUpdate()
                ->first();

            $isCod = $payment ? $payment->gateway === 'cod' : in_array($lockedOrder->status, ['cod_ordered', 'cod_paid'], true);

            if (!$isCod) {
                throw ValidationException::withMessages(['payment_status' => 'Chỉ cho phép cập nhật thủ công đơn COD.']);
            }

            $currentStatus = $payment?->status ?? ($lockedOrder->status === 'cod_paid' ? 'paid' : 'pending');

            if ($currentStatus !== $data['current_payment_status']
                || $lockedOrder->status !== $data['current_order_status']
                || (int) ($payment?->id ?? 0) !== (int) $data['current_payment_id']) {
                throw ValidationException::withMessages(['payment_status' => 'Dữ liệu đơn hàng vừa thay đổi. Vui lòng tải lại trang.']);
            }

            $newStatus = $data['payment_status'];

            if (!PaymentStatus::canTransitionTo($currentStatus, $newStatus)) {
                throw ValidationException::withMessages(['payment_status' => 'Không thể chuyển sang trạng thái thanh toán này.']);
            }

            if ($newStatus === $currentStatus) {
                return;
            }

            if (in_array($newStatus, ['pending', 'paid'], true)
                && ($lockedOrder->status === 'cancelled' || in_array($lockedOrder->shipping_status, ['cancelled', 'return', 'returned'], true))) {
                throw ValidationException::withMessages(['payment_status' => 'Không thể xác nhận thu tiền cho đơn đã hủy hoặc hoàn hàng.']);
            }

            $paidAt = match ($newStatus) {
                'paid'              => ($payment?->paid_at ?? now()),
                'pending', 'failed' => null,
                default             => $payment?->paid_at,
            };

            $attributes = [
                'status'  => $newStatus,
                'message' => 'Quản trị viên #' . $request->user()->id . ' cập nhật: ' . PaymentStatus::label($newStatus),
                'paid_at' => $paidAt,
            ];

            if ($payment instanceof PaymentTransaction) {
                $payment->update($attributes);
            } else {
                $lockedOrder->paymentTransactions()->create($attributes + [
                    'gateway' => 'cod',
                    'amount'  => $lockedOrder->total_price,
                ]);
            }

            if ($newStatus === 'paid') {
                $lockedOrder->update(['status' => 'cod_paid']);
            } elseif (in_array($newStatus, ['pending', 'failed'], true)) {
                $lockedOrder->update(['status' => 'cod_ordered']);
            }
        });

        return back()->with('success', 'Đã lưu trạng thái thanh toán COD #' . $order->id . '.');
    }

    private function methods(): array
    {
        return ['cod' => 'COD', 'momo' => 'MoMo', 'unknown' => 'Chưa xác định'];
    }
}