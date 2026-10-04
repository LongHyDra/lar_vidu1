<?php

namespace App\Http\Controllers;

use App\Models\Category;
use App\Models\InventoryMovement;
use App\Models\Product;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Cache;

class ProductController extends Controller
{
    public function index()
    {
        $products = Product::with('category')->latest()->get();
        return view('products.index', compact('products'));
    }

    public function show(Product $product)
    {
        $viewed = session('recently_viewed', []);
        $viewed = array_values(array_unique(array_merge([$product->id], $viewed)));
        session(['recently_viewed' => array_slice($viewed, 0, 12)]);

        $product->load(['category', 'variants', 'reviews.user']);

        $relatedProducts = Cache::remember('product.related.'.$product->id, now()->addMinutes(10), fn () => Product::with('category')
            ->where('category_id', $product->category_id)->where('id', '!=', $product->id)->latest()->limit(4)->get());

        $popularProducts = Cache::remember('product.popular.'.$product->id, now()->addMinutes(10), fn () => Product::with('category')
            ->leftJoin('order_items', 'products.id', '=', 'order_items.product_id')
            ->leftJoin('orders', function ($join) {
                $join->on('order_items.order_id', '=', 'orders.id')
                    ->where('orders.status', '!=', 'cancelled');
            })
            ->where('products.id', '!=', $product->id)
            ->select('products.*', DB::raw('COALESCE(SUM(order_items.quantity), 0) as sold_quantity'))
            ->groupBy('products.id')
            ->orderByDesc('sold_quantity')
            ->limit(4)
            ->get());

        return view('products.show', compact('product', 'relatedProducts', 'popularProducts'));
    }

    public function create()
    {
        $categories = Category::all();
        return view('products.create', compact('categories'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'category_id' => 'required|exists:categories,id',
            'name'        => 'required|string|max:255',
            'brand'       => 'nullable|string|max:100',
            'attributes'  => 'nullable|json',
            'price'       => 'required|numeric|min:0',
            'stock'       => 'required|integer|min:0',
            'description' => 'nullable|string',
            'image'       => 'nullable|url|max:2048',
        ]);

        $data = $request->only(['category_id', 'name', 'brand', 'attributes', 'price', 'stock', 'description']);

        // Lưu trực tiếp URL ảnh do quản trị viên cung cấp.
        if ($request->filled('image')) {
            $data['image'] = trim($request->input('image'));
        }

        $product = Product::create($data);

        InventoryMovement::create([
            'product_id'  => $product->id,
            'user_id'     => Auth::id(),
            'type'        => 'in',
            'quantity'    => $product->stock,
            'stock_after' => $product->stock,
            'note'        => 'Tồn kho ban đầu khi tạo sản phẩm',
        ]);

        return redirect()->route('products.index')->with('success', 'Thêm sản phẩm phụ kiện mới thành công!');
    }

    public function edit(Product $product)
    {
        $categories = Category::all();
        return view('products.edit', compact('product', 'categories'));
    }

    public function update(Request $request, Product $product)
    {
        $request->validate([
            'category_id' => 'required|exists:categories,id',
            'name'        => 'required|string|max:255',
            'brand'       => 'nullable|string|max:100',
            'attributes'  => 'nullable|json',
            'price'       => 'required|numeric|min:0',
            'stock'       => 'required|integer|min:0',
            'description' => 'nullable|string',
            'image'       => 'nullable|url|max:2048',
        ]);

        $data = $request->only(['category_id', 'name', 'brand', 'attributes', 'price', 'stock', 'description']);

        // Cập nhật URL ảnh; dữ liệu ảnh cũ vẫn được giữ nguyên nếu URL hợp lệ.
        if ($request->filled('image')) {
            $data['image'] = trim($request->input('image'));
        }

        $oldStock = (int) $product->stock;
        $product->update($data);

        // Theo dõi biến động kho hàng nếu số lượng bị thay đổi
        if ((int) $product->stock !== $oldStock) {
            $difference = (int) $product->stock - $oldStock;
            InventoryMovement::create([
                'product_id'  => $product->id,
                'user_id'     => Auth::id(),
                'type'        => $difference > 0 ? 'in' : 'out',
                'quantity'    => $difference,
                'stock_after' => $product->stock,
                'note'        => 'Điều chỉnh tồn kho từ quản lý sản phẩm',
            ]);
        }

        return redirect()->route('products.index')->with('success', 'Cập nhật sản phẩm thành công!');
    }

    public function destroy(Product $product)
    {
        $product->delete();
        return redirect()->route('products.index')->with('success', 'Xóa sản phẩm thành công!');
    }
}
