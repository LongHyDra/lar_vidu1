<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\CategoryController;
use App\Http\Controllers\ProductController;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\AdminController;
use App\Http\Controllers\Admin\ChatController as AdminChatController;
use App\Http\Controllers\User\ChatController as UserChatController;
use App\Http\Controllers\User\MomoController;
use App\Http\Controllers\User\OrderController;
use App\Http\Controllers\User\ProfileController;

// Third-party callbacks
Route::post('/payment/momo/ipn', [MomoController::class, 'ipn'])->name('payment.momo.ipn');
Route::get('/payment/momo/callback', [MomoController::class, 'callback'])->name('user.payment.momo.callback');

// Location API routes for GHN
Route::prefix('locations')->name('locations.')->group(function () {
    Route::get('/provinces', [OrderController::class, 'getProvinces'])->name('provinces');
    Route::get('/districts/{provinceId}', [OrderController::class, 'getDistricts'])->name('districts');
    Route::get('/wards/{districtId}', [OrderController::class, 'getWards'])->name('wards');
    Route::post('/calculate-fee', [OrderController::class, 'getShippingFee'])->name('fee');
});


// Trang chủ - Hiển thị cửa hàng Phụ Kiện Xe Máy
Route::get('/', function () {
    try {
        $query = \App\Models\Product::with('category');
        $search = request('q');

        if ($search) {
            $query->where(function ($productQuery) use ($search) {
                $productQuery->where('name', 'like', '%' . $search . '%')
                    ->orWhere('brand', 'like', '%' . $search . '%')
                    ->orWhere('attributes', 'like', '%' . $search . '%')
                    ->orWhere('description', 'like', '%' . $search . '%')
                    ->orWhereHas('category', function ($categoryQuery) use ($search) {
                        $categoryQuery->where('name', 'like', '%' . $search . '%');
                    });
            });
        }

        if (request()->filled('category')) {
            $query->where('category_id', request('category'));
        }

        if (request()->filled('min_price')) {
            $query->where('price', '>=', (float) request('min_price'));
        }

        if (request()->filled('max_price')) {
            $query->where('price', '<=', (float) request('max_price'));
        }

        if (request('availability') === 'in_stock') {
            $query->where('stock', '>', 0);
        }

        $products = $query->get();
        $categories = \App\Models\Category::all();
    } catch (\Exception $e) {
        $products = collect([
            (object)[
                'id' => 1,
                'name' => 'Heo Dầu Brembo 4 Piston Monoblock',
                'image' => 'https://images.unsplash.com/photo-1568772585407-9361f9bf3a87?w=600&auto=format&fit=crop',
                'price' => 3500000,
                'stock' => 15,
                'category_id' => 1,
                'category' => (object)['name' => 'Hệ Thống Phanh'],
                'description' => 'Chính hãng Italy, lực phanh êm ái, độ bền cao. Phù hợp PKL từ 250cc trở lên.'
            ],
            (object)[
                'id' => 2,
                'name' => 'Phuộc Ohlins HO831 cho SH350i',
                'image' => 'https://images.unsplash.com/photo-1558981403-c5f9899a28bc?w=600&auto=format&fit=crop',
                'price' => 14500000,
                'stock' => 5,
                'category_id' => 2,
                'category' => (object)['name' => 'Phuộc & Giảm Xóc'],
                'description' => 'Hàng xịn Thụy Điển, bình dầu dưới, êm ái, tăng cứng vô cực.'
            ],
            (object)[
                'id' => 3,
                'name' => 'Bộ Nhông Sên Dĩa DID Vàng 428HD Exciter',
                'image' => 'https://images.unsplash.com/photo-1619642751034-765dfdf7c58e?w=600&auto=format&fit=crop',
                'price' => 850000,
                'stock' => 50,
                'category_id' => 6,
                'category' => (object)['name' => 'Nhông Sên Dĩa'],
                'description' => 'Sên phốt cao su êm ái, chịu tải nặng tốt, tuổi thọ dài.'
            ],
            (object)[
                'id' => 4,
                'name' => 'Đèn Trợ Sáng Bi Cầu CX60W',
                'image' => 'https://images.unsplash.com/photo-1558981806-ec527fa84c39?w=600&auto=format&fit=crop',
                'price' => 1250000,
                'stock' => 30,
                'category_id' => 3,
                'category' => (object)['name' => 'Đèn Chiếu Sáng'],
                'description' => 'Công suất 60W, 2 chế độ cos vàng và pha trắng siêu sáng.'
            ]
        ]);

        $categories = collect([
            (object)['id' => 1, 'name' => 'Hệ Thống Phanh'],
            (object)['id' => 2, 'name' => 'Phuộc & Giảm Xóc'],
            (object)['id' => 3, 'name' => 'Đèn Chiếu Sáng'],
            (object)['id' => 6, 'name' => 'Nhông Sên Dĩa']
        ]);
    }

    $wishlistItems = auth()->check()
        ? \App\Models\Wishlist::with('product.category')
            ->where('user_id', auth()->id())
            ->latest()
            ->get()
        : collect();
    $recentlyViewed = \Illuminate\Support\Facades\Schema::hasTable('products')
        ? \App\Models\Product::with('category')
            ->whereIn('id', session('recently_viewed', []))
            ->get()
            ->sortBy(function ($product) {
                return array_search($product->id, session('recently_viewed', []), true);
            })
        : collect();

    return view('welcome', compact('products', 'categories', 'wishlistItems', 'recentlyViewed'));
})->name('welcome');

// ===== Cart & Checkout Routes =====
Route::get('/cart', function () {
    return view('cart.index');
})->name('cart.index');

Route::get('/cart/index', function () {
    return view('cart.index');
});

Route::get('/checkout', function () {
    return view('checkout.index');
})->name('checkout.index');

Route::view('/faq', 'faq')->name('faq');

// ===== Authentication Routes (Dành cho khách chưa đăng nhập) =====
Route::middleware('guest')->group(function () {
    Route::get('register', [AuthController::class, 'showRegistrationForm'])->name('register');
    Route::post('register', [AuthController::class, 'register']);
    Route::get('login', [AuthController::class, 'showLoginForm'])->name('login');
    Route::post('login', [AuthController::class, 'login']);
});

// ===== Staff Management Routes (Chỉ cho phép admin, editor, manager) =====
Route::middleware('auth')->group(function () {
    Route::middleware('roles:admin,editor,manager')->group(function () {
        Route::get('/categories', [CategoryController::class, 'index'])->name('categories.index');
        Route::get('/products', [ProductController::class, 'index'])->name('products.index');
    });
});

// ===== Authenticated Routes (Yêu cầu đã đăng nhập) =====
Route::middleware('auth')->group(function () {
    Route::post('logout', [AuthController::class, 'logout'])->name('logout');

    // User order flows
    Route::prefix('user')->name('user.')->group(function () {
        Route::get('/profile', [ProfileController::class, 'show'])->name('profile');
        Route::put('/profile', [ProfileController::class, 'update'])->name('profile.update');
        Route::get('/wishlist', [\App\Http\Controllers\User\WishlistController::class, 'index'])->name('wishlist.index');
        Route::post('/wishlist/{product}', [\App\Http\Controllers\User\WishlistController::class, 'store'])->name('wishlist.store');
        Route::delete('/wishlist/{product}', [\App\Http\Controllers\User\WishlistController::class, 'destroy'])->name('wishlist.destroy');
        Route::get('/tickets', [\App\Http\Controllers\User\TicketController::class, 'index'])->name('tickets.index');
        Route::get('/tickets/create', [\App\Http\Controllers\User\TicketController::class, 'create'])->name('tickets.create');
        Route::post('/tickets', [\App\Http\Controllers\User\TicketController::class, 'store'])->name('tickets.store');
        Route::get('/payment', [OrderController::class, 'index'])->name('payment.index');
        Route::post('/payment/process', [OrderController::class, 'processPayment'])->name('payment.process');
        Route::get('/orders', [OrderController::class, 'orderHistory'])->name('orders.index');
        Route::get('/orders/{order}', [OrderController::class, 'show'])->name('orders.show');
        Route::post('/orders/{order}/cancel', [OrderController::class, 'cancel'])->name('orders.cancel');
        Route::get('/orders/{order}/start-momo', [MomoController::class, 'start'])->name('orders.momo.start');
        Route::get('/orders/{order}/pay/momo', [MomoController::class, 'payAgain'])->name('orders.momo.pay');
        Route::post('/chat/send', [UserChatController::class, 'send'])->name('chat.send');
        Route::get('/chat/messages', [UserChatController::class, 'getMessages'])->name('chat.messages');
    });

    // Quản lý Thêm & Chỉnh sửa (Cho phép: Admin, Editor, Manager)
    Route::middleware('roles:admin,editor,manager')->group(function () {
        Route::get('/categories/create', [CategoryController::class, 'create'])->name('categories.create');
        Route::post('/categories', [CategoryController::class, 'store'])->name('categories.store');
        Route::get('/categories/{category}/edit', [CategoryController::class, 'edit'])->name('categories.edit');
        Route::put('/categories/{category}', [CategoryController::class, 'update'])->name('categories.update');

        Route::get('/products/create', [ProductController::class, 'create'])->name('products.create');
        Route::post('/products', [ProductController::class, 'store'])->name('products.store');
        Route::get('/products/{product}/edit', [ProductController::class, 'edit'])->name('products.edit');
        Route::put('/products/{product}', [ProductController::class, 'update'])->name('products.update');
    });

    // Quản lý Xóa (Chỉ cho phép: Admin)
    Route::middleware('admin')->group(function () {
        Route::delete('/categories/{category}', [CategoryController::class, 'destroy'])->name('categories.destroy');
        Route::delete('/products/{product}', [CategoryController::class, 'destroy'])->name('products.destroy');
    });

    // Bảng Điều Khiển Admin (Chỉ cho phép: Admin)
    Route::middleware('admin')->prefix('admin')->name('admin.')->group(function () {
        Route::get('/dashboard', [AdminController::class, 'dashboard'])->name('dashboard');
        Route::get('/reports/revenue.csv', [AdminController::class, 'exportRevenueCsv'])->name('reports.revenue.csv');
        Route::get('/reports/revenue/print', [AdminController::class, 'printRevenueReport'])->name('reports.revenue.print');
        Route::post('/orders/{order}/status', [AdminController::class, 'updateOrderStatus'])->name('orders.updateStatus');
        Route::get('/tickets', [\App\Http\Controllers\Admin\TicketController::class, 'index'])->name('tickets.index');
        Route::put('/tickets/{ticket}', [\App\Http\Controllers\Admin\TicketController::class, 'update'])->name('tickets.update');
        Route::get('/inventory', [\App\Http\Controllers\Admin\InventoryController::class, 'index'])->name('inventory.index');
        Route::get('/users', [\App\Http\Controllers\Admin\UserController::class, 'index'])->name('users.index');
        Route::post('/users', [\App\Http\Controllers\Admin\UserController::class, 'store'])->name('users.store');
        Route::put('/users/{user}', [\App\Http\Controllers\Admin\UserController::class, 'update'])->name('users.update');
        Route::delete('/users/{user}', [\App\Http\Controllers\Admin\UserController::class, 'destroy'])->name('users.destroy');
        Route::get('/payment-transactions', [\App\Http\Controllers\Admin\PaymentTransactionController::class, 'index'])->name('payment-transactions.index');
        Route::get('/chat/users', [AdminChatController::class, 'getUsers'])->name('chat.users');
        Route::get('/chat/messages/{userId}', [AdminChatController::class, 'getMessages'])->name('chat.messages');
        Route::post('/chat/send', [AdminChatController::class, 'send'])->name('chat.send');
    });

});

Route::get('/products/{product}', [ProductController::class, 'show'])->name('products.show');