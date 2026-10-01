<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\AdminAudit;
use App\Models\Product;
use App\Models\ProductVariant;
use Illuminate\Http\Request;

class ProductVariantController extends Controller
{
    public function index(Product $product)
    {
        return view('admin.products.variants', ['product' => $product, 'variants' => $product->variants()->latest()->get()]);
    }

    public function store(Request $request, Product $product)
    {
        $data = $request->validate(['sku' => ['required', 'string', 'max:80', 'unique:product_variants,sku'], 'variant_name' => ['required', 'string', 'max:120'], 'price' => ['required', 'numeric', 'min:0'], 'stock' => ['required', 'integer', 'min:0'], 'weight' => ['nullable', 'integer', 'min:1']]);
        $variant = $product->variants()->create($data);
        AdminAudit::record('variant.created', $variant, $data);
        return back()->with('success', 'Đã thêm biến thể.');
    }

    public function update(Request $request, ProductVariant $variant)
    {
        $data = $request->validate(['variant_name' => ['required', 'string', 'max:120'], 'price' => ['required', 'numeric', 'min:0'], 'stock' => ['required', 'integer', 'min:0'], 'weight' => ['nullable', 'integer', 'min:1']]);
        $before = $variant->only(array_keys($data)); $variant->update($data);
        AdminAudit::record('variant.updated', $variant, ['before' => $before, 'after' => $data]);
        return back()->with('success', 'Đã cập nhật biến thể.');
    }
}
