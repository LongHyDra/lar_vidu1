<?php

namespace App\Http\Controllers;

use App\Models\Category;
use App\Models\Product;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class CategoryController extends Controller
{
    // 1. Hiển thị danh sách danh mục + thống kê dashboard
    public function index()
    {
        $categories = Category::withCount('products')->get();
        $totalProducts = Product::count();
        $lowStockProducts = Product::where('stock', '<=', 5)->count();
        $totalValue = Product::sum(DB::raw('price * stock'));

        return view('categories.index', compact('categories', 'totalProducts', 'lowStockProducts', 'totalValue'));
    }

    // 2. Hiển thị form thêm
    public function create()
    {
        return view('categories.create');
    }

    // 3. Lưu danh mục vào Database
    public function store(Request $request)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'description' => 'nullable|string',
        ]);

        Category::create($request->only(['name', 'description']));

        return redirect()->route('categories.index')->with('success', 'Thêm danh mục thành công!');
    }

    // 4. Hiển thị form chỉnh sửa
    public function edit(Category $category)
    {
        return view('categories.edit', compact('category'));
    }

    // 5. Cập nhật thông tin danh mục
    public function update(Request $request, Category $category)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'description' => 'nullable|string',
        ]);

        $category->update($request->only(['name', 'description']));

        return redirect()->route('categories.index')->with('success', 'Cập nhật danh mục thành công!');
    }

    // 6. Xóa danh mục
    public function destroy(Category $category)
    {
        $category->delete();

        return redirect()->route('categories.index')->with('success', 'Xóa danh mục thành công!');
    }
}