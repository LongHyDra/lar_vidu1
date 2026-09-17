<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\PaymentTransaction;
use Illuminate\View\View;

class PaymentTransactionController extends Controller
{
    public function index(): View
    {
        $transactions = PaymentTransaction::with('order.user')->latest()->paginate(25);

        return view('admin.payment-transactions.index', compact('transactions'));
    }
}
