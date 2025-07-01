<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Admin\AuthController;
use App\Http\Controllers\Admin\DashboardController;
use App\Http\Controllers\Admin\CategoryController;
use App\Http\Controllers\Admin\ProductController;
use App\Http\Controllers\Admin\OrderController as AdminOrderController;
use App\Http\Controllers\Admin\UserController;
use App\Http\Controllers\Admin\CouponController;

// Client Controllers
use App\Http\Controllers\Client\HomeController;
use App\Http\Controllers\Client\ProductController as ClientProductController;
use App\Http\Controllers\Client\CartController;
use App\Http\Controllers\Client\OrderController;
use App\Http\Controllers\Client\AuthController as ClientAuthController;
use App\Http\Controllers\Client\ChatBotController;
use App\Http\Controllers\Client\ProfileController;
use App\Http\Controllers\Client\AddressController;
use App\Http\Controllers\Client\WishlistController;
use App\Http\Controllers\Client\ReviewController;

// Client Routes
Route::get('/', [HomeController::class, 'index'])->name('client.home');
Route::get('/about', [HomeController::class, 'about'])->name('client.about');
Route::get('/contact', [HomeController::class, 'contact'])->name('client.contact');
Route::post('/contact', [HomeController::class, 'contactStore'])->name('client.contact.store');

// Client Auth Routes
Route::get('/login', [ClientAuthController::class, 'showLoginForm'])->name('login'); // Laravel auth default
Route::post('/login', [ClientAuthController::class, 'login'])->name('login.post');
Route::get('/client-login', [ClientAuthController::class, 'showLoginForm'])->name('client.login'); // Client specific (redirect tới login)
Route::get('/client-auth-login', [ClientAuthController::class, 'showLoginForm'])->name('client.auth.login'); // Alternative route name
Route::get('/register', [ClientAuthController::class, 'showRegisterForm'])->name('client.register');
Route::post('/register', [ClientAuthController::class, 'register']);
Route::post('/logout', [ClientAuthController::class, 'logout'])->name('client.logout');

// Protected Client Routes
Route::middleware('auth:web')->group(function () {
    Route::get('/profile', [ProfileController::class, 'index'])->name('client.profile');
    Route::get('/profile/show', [ProfileController::class, 'show'])->name('client.profile.show');
    Route::get('/profile/edit', [ProfileController::class, 'edit'])->name('client.profile.edit');
    Route::put('/profile', [ProfileController::class, 'update'])->name('client.profile.update');
    Route::get('/profile/remove-avatar', [ProfileController::class, 'removeAvatar'])->name('client.profile.removeAvatar');
    
    // Addresses
    Route::resource('addresses', AddressController::class, ['as' => 'client']);
    Route::get('/addresses/{address}/set-default', [AddressController::class, 'setDefault'])->name('client.addresses.setDefault');
});

// Placeholder routes for missing pages
Route::get('/terms', function () { return view('client.terms'); })->name('client.terms');
Route::get('/privacy', function () { return view('client.privacy'); })->name('client.privacy');
Route::get('/password/reset', function () { return view('client.auth.passwords.email'); })->name('client.password.request');
// Wishlist routes
Route::middleware('auth:web')->group(function () {
    Route::get('/wishlist', [WishlistController::class, 'index'])->name('client.wishlist');
    Route::post('/wishlist/add/{product}', [WishlistController::class, 'add'])->name('client.wishlist.add');
    Route::delete('/wishlist/remove/{product}', [WishlistController::class, 'remove'])->name('client.wishlist.remove');
});

// Reviews routes  
Route::middleware('auth:web')->group(function () {
    Route::get('/reviews', [ReviewController::class, 'index'])->name('client.reviews');
    Route::post('/products/{product}/reviews', [ReviewController::class, 'store'])->name('client.reviews.store');
    Route::put('/reviews/{review}', [ReviewController::class, 'update'])->name('client.reviews.update');
    Route::delete('/reviews/{review}', [ReviewController::class, 'destroy'])->name('client.reviews.destroy');
});

// Products
Route::get('/products', [ClientProductController::class, 'index'])->name('client.products.index');
Route::get('/products/{product}', [ClientProductController::class, 'show'])->name('client.products.show');
Route::get('/category/{category}', [ClientProductController::class, 'category'])->name('client.products.category');
Route::get('/search', [ClientProductController::class, 'search'])->name('client.products.search');

// Cart
Route::get('/cart', [CartController::class, 'index'])->name('client.cart.index');
Route::post('/cart/add/{product}', [CartController::class, 'add'])->name('client.cart.add');
Route::put('/cart/{id}', [CartController::class, 'update'])->name('client.cart.update');
Route::delete('/cart/{id}', [CartController::class, 'remove'])->name('client.cart.remove');
Route::delete('/cart', [CartController::class, 'clear'])->name('client.cart.clear');
Route::get('/cart/count', [CartController::class, 'count'])->name('client.cart.count');

// Checkout & Orders
Route::get('/checkout', [OrderController::class, 'checkout'])->name('client.checkout');
Route::post('/checkout/apply-coupon', [OrderController::class, 'applyCoupon'])->name('client.checkout.apply-coupon');
Route::post('/checkout', [OrderController::class, 'store'])->name('client.checkout.store');
Route::get('/order-success/{order}', [OrderController::class, 'success'])->name('client.order.success');

// User Orders (require auth)
Route::middleware('auth:web')->group(function () {
    Route::get('/my-orders', [OrderController::class, 'myOrders'])->name('client.orders.index');
    Route::get('/orders/{order}', [OrderController::class, 'show'])->name('client.orders.show');
    Route::patch('/orders/{order}/cancel', [OrderController::class, 'cancel'])->name('client.orders.cancel');
});

// Chatbot Routes (public access)
Route::post('/chat', [ChatBotController::class, 'chat'])->name('client.chat');
Route::get('/chat/history', [ChatBotController::class, 'getChatHistory'])->name('client.chat.history');

// Basic login route (fallback) - đã thay thế bằng client.login ở trên
// Route::get('/login', function () {
//     return redirect()->route('admin.login');
// })->name('login');

// Admin Routes
Route::prefix('admin')->name('admin.')->group(function () {
    // Auth routes (no middleware)
    Route::get('login', [AuthController::class, 'showLoginForm'])->name('login');
    Route::post('login', [AuthController::class, 'login']);
    Route::post('logout', [AuthController::class, 'logout'])->name('logout');

    // Protected admin routes
    Route::middleware(['auth:admin', 'admin'])->group(function () {
        Route::get('/', [DashboardController::class, 'index'])->name('dashboard');
        
        // Categories
        Route::resource('categories', CategoryController::class);
        
        // Products
        Route::resource('products', ProductController::class);
        Route::post('products/{product}/duplicate', [ProductController::class, 'duplicate'])->name('products.duplicate');
        Route::patch('products/{product}/toggle-status', [ProductController::class, 'toggleStatus'])->name('products.toggle-status');
        Route::post('products/bulk-action', [ProductController::class, 'bulkAction'])->name('products.bulk-action');
        Route::get('products/export', [ProductController::class, 'export'])->name('products.export');
        Route::delete('products/{product}/remove-image/{index}', [ProductController::class, 'removeImage'])->name('products.remove-image');

        // Orders
        Route::resource('orders', AdminOrderController::class);
        Route::patch('orders/{order}/status', [AdminOrderController::class, 'updateStatus'])->name('orders.update-status');
        Route::patch('orders/{order}/payment-status', [AdminOrderController::class, 'updatePaymentStatus'])->name('orders.update-payment-status');
        Route::patch('orders/{order}/mark-paid', [AdminOrderController::class, 'markPaid'])->name('orders.mark-paid');
        Route::patch('orders/{order}/cancel', [AdminOrderController::class, 'cancel'])->name('orders.cancel');
        Route::get('orders/{order}/print', [AdminOrderController::class, 'print'])->name('orders.print');
        Route::post('orders/bulk-action', [AdminOrderController::class, 'bulkAction'])->name('orders.bulk-action');
        Route::get('orders/check-new', [AdminOrderController::class, 'checkNew'])->name('orders.check-new');
        
        // Users
        Route::resource('users', UserController::class);
        Route::patch('users/{user}/toggle-admin', [UserController::class, 'toggleAdmin'])->name('users.toggle-admin');
        Route::patch('users/{user}/toggle-status', [UserController::class, 'toggleStatus'])->name('users.toggle-status');
        Route::patch('users/{user}/verify-email', [UserController::class, 'verifyEmail'])->name('users.verify-email');
        Route::patch('users/{user}/reset-password', [UserController::class, 'resetPassword'])->name('users.reset-password');
        Route::post('users/bulk-action', [UserController::class, 'bulkAction'])->name('users.bulk-action');
        
        // Coupons
        Route::resource('coupons', CouponController::class);
        Route::patch('coupons/{coupon}/toggle-status', [CouponController::class, 'toggleStatus'])->name('coupons.toggle-status');
        Route::post('coupons/bulk-action', [CouponController::class, 'bulkAction'])->name('coupons.bulk-action');
    });
});

// Debug route
Route::get('/admin/dashboard-test', function() {
    $output = "<h1>Dashboard Debug Test</h1>";
    
    try {
        $output .= "<h3>Raw SQL:</h3>";
        $output .= "Total orders: " . \DB::table('orders')->count() . "<br>";
        $output .= "Pending orders: " . \DB::table('orders')->where('status', 'pending')->count() . "<br>";
        $output .= "Delivered orders: " . \DB::table('orders')->where('status', 'delivered')->count() . "<br>";
        $output .= "Revenue: " . \DB::table('orders')->where('payment_status', 'paid')->sum('total_amount') . "<br>";
        
        $output .= "<h3>Eloquent:</h3>";
        $output .= "Total orders: " . \App\Models\Order::count() . "<br>";
        $output .= "Pending orders: " . \App\Models\Order::where('status', 'pending')->count() . "<br>";
        $output .= "Delivered orders: " . \App\Models\Order::where('status', 'delivered')->count() . "<br>";
        $output .= "Revenue: " . \App\Models\Order::where('payment_status', 'paid')->sum('total_amount') . "<br>";
        
        $output .= "<h3>Database Connection:</h3>";
        $output .= "Connection: " . config('database.default') . "<br>";
        $output .= "Database: " . config('database.connections.mysql.database') . "<br>";
        
    } catch (Exception $e) {
        $output .= "<h3>ERROR:</h3>";
        $output .= $e->getMessage() . "<br>";
    }
    
    return $output;
});
