<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\CategoryController;
use App\Http\Controllers\ProductController;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\AdminController;
use App\Http\Controllers\Admin\ChatController as AdminChatController;
use App\Http\Controllers\Admin\FinanceController;
use App\Http\Controllers\Admin\InventoryController;
use App\Http\Controllers\Admin\PaymentTransactionController;
use App\Http\Controllers\Admin\TicketController as AdminTicketController;
use App\Http\Controllers\Admin\UserController;
use App\Http\Controllers\User\ChatController as UserChatController;
use App\Http\Controllers\User\MomoController;
use App\Http\Controllers\User\OrderController;
use App\Http\Controllers\User\ProfileController;
use App\Http\Controllers\User\TicketController as UserTicketController;
use App\Http\Controllers\User\WishlistController;
use App\Models\Category;
use App\Models\Product;
use App\Models\Wishlist;
use Illuminate\Foundation\Auth\EmailVerificationRequest;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\Schema;

/*
|--------------------------------------------------------------------------
| 1. PUBLIC ROUTES
|--------------------------------------------------------------------------
*/
Route::post('/payment/momo/ipn', [MomoController::class, 'ipn'])->name('payment.momo.ipn');
Route::get('/payment/momo/callback', [MomoController::class, 'callback'])->name('user.payment.momo.callback');

Route::prefix('locations')->name('locations.')->group(function () {
    Route::get('/provinces', [OrderController::class, 'getProvinces'])->name('provinces');
    Route::get('/districts/{provinceId}', [OrderController::class, 'getDistricts'])->name('districts');
    Route::get('/wards/{districtId}', [OrderController::class, 'getWards'])->name('wards');
    Route::post('/calculate-fee', [OrderController::class, 'getShippingFee'])->name('fee');
});

Route::get('/cart', fn() => view('cart.index'))->name('cart.index');
Route::get('/cart/index', fn() => view('cart.index'));
Route::view('/faq', 'faq')->name('faq');

Route::get('/products/{product}', [ProductController::class, 'show'])
    ->name('products.show')
    ->whereNumber('product');

Route::get('/test-mail', function () {
    try {
        Mail::raw('Xin chào, đây là thư kiểm tra kết nối SMTP từ Phụ Kiện Xe Máy 247!', function ($message) {
            $message->to(config('mail.from.address'))
                    ->subject('Kiểm tra gửi mail Laravel 12');
        });
        return '<h3 style="color:green;">Gửi email thành công! Hãy kiểm tra hòm thư của bạn.</h3>';
    } catch (\Exception $e) {
        return '<h3 style="color:red;">Lỗi gửi email:</h3> ' . $e->getMessage();
    }
});

Route::get('/', function () {
    try {
        $query = Product::with('category');
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

        // PHÂN TRANG 12 SẢN PHẨM TRÊN TRANG CHỦ
        $products = $query->latest()->paginate(12)->withQueryString();
        $categories = Category::all();
    } catch (\Exception $e) {
        $products = collect();
        $categories = collect();
    }

    $wishlistItems = Auth::check()
        ? Wishlist::with('product.category')->where('user_id', Auth::id())->latest()->get()
        : collect();

    $recentlyViewed = Schema::hasTable('products')
        ? Product::with('category')
        ->whereIn('id', session('recently_viewed', []))
        ->get()
        ->sortBy(fn($product) => array_search($product->id, session('recently_viewed', []), true))
        : collect();

    return view('welcome', compact('products', 'categories', 'wishlistItems', 'recentlyViewed'));
})->name('welcome');

/*
|--------------------------------------------------------------------------
| 2. GUEST AUTH ROUTES
|--------------------------------------------------------------------------
*/
Route::middleware('guest')->group(function () {
    Route::get('register', [AuthController::class, 'showRegistrationForm'])->name('register');
    Route::post('register', [AuthController::class, 'register']);
    Route::get('login', [AuthController::class, 'showLoginForm'])->name('login');
    Route::post('login', [AuthController::class, 'login']);

    // QUÊN VÀ ĐẶT LẠI MẬT KHẨU
    Route::get('forgot-password', [AuthController::class, 'showForgotPasswordForm'])->name('password.request');
    Route::post('forgot-password', [AuthController::class, 'sendResetLinkEmail'])->name('password.email');
    Route::get('reset-password/{token}', [AuthController::class, 'showResetPasswordForm'])->name('password.reset');
    Route::post('reset-password', [AuthController::class, 'resetPassword'])->name('password.update');
});

/*
|--------------------------------------------------------------------------
| 3. AUTHENTICATED ROUTES
|--------------------------------------------------------------------------
*/
Route::middleware('auth')->group(function () {
    Route::match(['get', 'post'], '/logout', [AuthController::class, 'logout'])->name('logout');

    // EMAIL VERIFICATION ROUTES
    Route::get('/email/verify', function () {
        return view('auth.verify-email');
    })->name('verification.notice');

    Route::get('/email/verify/{id}/{hash}', function (EmailVerificationRequest $request) {
        $request->fulfill();
        return redirect()->route('welcome')->with('success', 'Xác thực email thành công! Bạn có thể sử dụng đầy đủ tính năng.');
    })->middleware('signed')->name('verification.verify');

    Route::post('/email/verification-notification', function (Request $request) {
        $request->user()->sendEmailVerificationNotification();
        return back()->with('success', 'Đường dẫn xác thực mới đã được gửi vào hòm thư email của bạn!');
    })->middleware('throttle:6,1')->name('verification.send');

    // CHECKOUT BẮT BUỘC ĐÃ XÁC THỰC EMAIL
    Route::get('/checkout', fn() => view('checkout.index'))
        ->middleware('verified')
        ->name('checkout.index');

    // USER ROUTES
    Route::prefix('user')->name('user.')->group(function () {
        Route::get('/profile', [ProfileController::class, 'show'])->name('profile');
        Route::put('/profile', [ProfileController::class, 'update'])->name('profile.update');

        Route::get('/wishlist', [WishlistController::class, 'index'])->name('wishlist.index');
        Route::post('/wishlist/{product}', [WishlistController::class, 'store'])->name('wishlist.store');
        Route::delete('/wishlist/{product}', [WishlistController::class, 'destroy'])->name('wishlist.destroy');

        Route::get('/tickets', [UserTicketController::class, 'index'])->name('tickets.index');
        Route::get('/tickets/create', [UserTicketController::class, 'create'])->name('tickets.create');
        Route::post('/tickets', [UserTicketController::class, 'store'])->name('tickets.store');

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

    Route::middleware('roles:admin,editor,manager')->group(function () {
        Route::get('/categories', [CategoryController::class, 'index'])->name('categories.index');
        Route::get('/categories/create', [CategoryController::class, 'create'])->name('categories.create');
        Route::post('/categories', [CategoryController::class, 'store'])->name('categories.store');
        Route::get('/categories/{category}/edit', [CategoryController::class, 'edit'])->name('categories.edit');
        Route::put('/categories/{category}', [CategoryController::class, 'update'])->name('categories.update');

        Route::get('/products', [ProductController::class, 'index'])->name('products.index');
        Route::get('/products/create', [ProductController::class, 'create'])->name('products.create');
        Route::post('/products', [ProductController::class, 'store'])->name('products.store');
        Route::get('/products/{product}/edit', [ProductController::class, 'edit'])->name('products.edit');
        Route::put('/products/{product}', [ProductController::class, 'update'])->name('products.update');
    });

    Route::middleware('admin')->group(function () {
        Route::delete('/categories/{category}', [CategoryController::class, 'destroy'])->name('categories.destroy');
        Route::delete('/products/{product}', [ProductController::class, 'destroy'])->name('products.destroy');
    });

    // KHU VỰC QUẢN TRỊ ADMIN
    Route::middleware('admin')->prefix('admin')->name('admin.')->group(function () {
        Route::get('/dashboard', [AdminController::class, 'dashboard'])->name('dashboard');
        Route::post('/orders/{order}/status', [AdminController::class, 'updateOrderStatus'])->name('orders.updateStatus');
        Route::post('/orders/{order}/create-ghn', [AdminController::class, 'createGhnOrder'])->name('orders.createGhn');

        // Báo cáo doanh thu
        Route::get('/reports/revenue.csv', [AdminController::class, 'exportRevenueCsv'])->name('reports.revenue.csv');
        Route::get('/reports/revenue/excel', [AdminController::class, 'exportRevenueExcel'])->name('reports.revenue.excel');
        Route::get('/reports/revenue/print', [AdminController::class, 'printRevenueReport'])->name('reports.revenue.print');

        // Module Thống kê Tài chính
        Route::prefix('finance')->name('finance.')->group(function () {
            Route::get('/', [FinanceController::class, 'index'])->name('index');
            Route::get('/transactions', [FinanceController::class, 'transactions'])->name('transactions');
            Route::get('/export', [FinanceController::class, 'export'])->name('export');
            Route::patch('/orders/{order}/status', [FinanceController::class, 'updateStatus'])->name('update-status');
        });

        Route::get('/tickets', [AdminTicketController::class, 'index'])->name('tickets.index');
        Route::put('/tickets/{ticket}', [AdminTicketController::class, 'update'])->name('tickets.update');
        Route::get('/inventory', [InventoryController::class, 'index'])->name('inventory.index');

        Route::get('/users', [UserController::class, 'index'])->name('users.index');
        Route::post('/users', [UserController::class, 'store'])->name('users.store');
        Route::put('/users/{user}', [UserController::class, 'update'])->name('users.update');
        Route::delete('/users/{user}', [UserController::class, 'destroy'])->name('users.destroy');

        Route::get('/payment-transactions', [PaymentTransactionController::class, 'index'])->name('payment-transactions.index');
        Route::get('/chat/users', [AdminChatController::class, 'getUsers'])->name('chat.users');
        Route::get('/chat/messages/{userId}', [AdminChatController::class, 'getMessages'])->name('chat.messages');
        Route::post('/chat/send', [AdminChatController::class, 'send'])->name('chat.send');
    });
});