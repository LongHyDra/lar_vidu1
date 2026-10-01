<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\InventoryMovement;
use App\Models\Product;
use App\Models\ProductVariant;
use Illuminate\View\View;

class InventoryController extends Controller
{
    public function index(): View
    {
        $lowStockProducts = Product::with('category')
            ->where('stock', '<=', 5)
            ->orderBy('stock')
            ->get();
        $lowStockVariants = ProductVariant::with('product')->where('stock', '<=', 5)->orderBy('stock')->get();
        $movements = InventoryMovement::with('product', 'variant', 'user')
            ->latest()
            ->paginate(30);

        return view('admin.inventory.index', compact('lowStockProducts', 'lowStockVariants', 'movements'));
    }
}
