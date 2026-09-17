<?php

namespace App\Http\Controllers\User;

use App\Http\Controllers\Controller;
use App\Models\Product;
use App\Models\Wishlist;
use Illuminate\Http\RedirectResponse;
use Illuminate\Support\Facades\Auth;
use Illuminate\View\View;

class WishlistController extends Controller
{
    public function index(): View
    {
        $wishlists = Wishlist::with('product.category')
            ->where('user_id', Auth::id())
            ->latest()
            ->get();

        return view('user.wishlist', compact('wishlists'));
    }

    public function store(Product $product): RedirectResponse
    {
        Wishlist::firstOrCreate([
            'user_id' => Auth::id(),
            'product_id' => $product->id,
        ]);

        return back()->with('success', 'Đã thêm sản phẩm vào danh sách yêu thích.');
    }

    public function destroy(Product $product): RedirectResponse
    {
        Wishlist::where('user_id', Auth::id())
            ->where('product_id', $product->id)
            ->delete();

        return back()->with('success', 'Đã xóa sản phẩm khỏi danh sách yêu thích.');
    }
}
