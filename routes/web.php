<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\HomeController;
use App\Http\Controllers\ProductController;
use App\Http\Controllers\CartController;
use App\Http\Controllers\CheckoutController;
use App\Http\Controllers\OrderController;
use App\Http\Controllers\AccountController;
use App\Http\Controllers\ReviewController;
use App\Http\Controllers\WishlistController;
use App\Http\Controllers\Admin;
use Illuminate\Support\Facades\Artisan;

// Safepay Webhook (No CSRF, No Auth)
Route::post('/checkout/safepay/webhook', [CheckoutController::class, 'safepayWebhook'])->name('checkout.safepay.webhook');

// ── Public Routes ────────────────────────────────────────────────────────────
Route::get('/linkstorage', function () {
    Artisan::call('storage:link');
    return 'Storage Link Created Successfully!';
});
Route::get('/',                             [HomeController::class, 'index'])->name('home');
Route::get('/products',                     [ProductController::class, 'index'])->name('products.index');
Route::get('/products/{slug}',              [ProductController::class, 'show'])->name('products.show');
Route::post('/search',                      [ProductController::class, 'search'])->name('products.search');

// ── Auth Routes ──────────────────────────────────────────────────────────────
Route::middleware('guest')->group(function () {
    Route::get('/login',     [AuthController::class, 'showLogin'])->name('login');
    Route::post('/login',    [AuthController::class, 'login'])->name('login.submit');
    Route::get('/register',  [AuthController::class, 'showRegister'])->name('register');
    Route::post('/register', [AuthController::class, 'register'])->name('register.submit');
});
Route::post('/logout', [AuthController::class, 'logout'])->name('logout')->middleware('auth');

// ── Cart Routes ──────────────────────────────────────────────────────────────
Route::prefix('cart')->name('cart.')->group(function () {
    Route::get('/',                   [CartController::class, 'index'])->name('index');
    Route::post('/add',               [CartController::class, 'add'])->name('add');
    Route::patch('/item/{cartItem}',  [CartController::class, 'update'])->name('update');
    Route::delete('/item/{cartItem}', [CartController::class, 'remove'])->name('remove');
    Route::post('/coupon',            [CartController::class, 'applyCoupon'])->name('coupon.apply');
    Route::delete('/coupon',          [CartController::class, 'removeCoupon'])->name('coupon.remove');
    Route::get('/count',              [CartController::class, 'count'])->name('count');
});

// ── Authenticated Customer Routes ─────────────────────────────────────────────
Route::middleware('auth')->group(function () {

    // Checkout
    Route::get('/checkout',                 [CheckoutController::class, 'index'])->name('checkout.index');
    Route::post('/checkout',                [CheckoutController::class, 'process'])->name('checkout.process');
    Route::get('/checkout/success/{order}', [CheckoutController::class, 'success'])->name('checkout.success');

    // Safepay Routes
    Route::post('/checkout/safepay/initiate', [CheckoutController::class, 'initiateSafepay'])->name('checkout.safepay.initiate');
    Route::get('/checkout/safepay/callback',  [CheckoutController::class, 'safepayCallback'])->name('checkout.safepay.callback');

    // Orders
    Route::get('/orders',                 [OrderController::class, 'index'])->name('orders.index');
    Route::get('/orders/{order}',         [OrderController::class, 'show'])->name('orders.show');
    Route::post('/orders/{order}/cancel', [OrderController::class, 'cancel'])->name('orders.cancel');

    // Account
    Route::prefix('account')->name('account.')->group(function () {
        Route::get('/dashboard',  [AccountController::class, 'dashboard'])->name('dashboard');
        Route::get('/profile',    [AccountController::class, 'profile'])->name('profile');
        Route::put('/profile',    [AccountController::class, 'updateProfile'])->name('profile.update');
        Route::put('/password',   [AccountController::class, 'updatePassword'])->name('password.update');
        Route::get('/wishlist',   [AccountController::class, 'wishlist'])->name('wishlist');
    });

    // Reviews
    Route::post('/products/{product}/reviews', [ReviewController::class, 'store'])->name('reviews.store');
    Route::delete('/reviews/{review}',         [ReviewController::class, 'destroy'])->name('reviews.destroy');

    // Wishlist
    Route::post('/wishlist/{product}/toggle', [WishlistController::class, 'toggle'])->name('wishlist.toggle');
});

// ── Admin Routes ──────────────────────────────────────────────────────────────
Route::prefix('admin')->name('admin.')->middleware(['auth', 'admin'])->group(function () {
    Route::get('/', [Admin\DashboardController::class, 'index'])->name('dashboard');

    // Products
    Route::resource('products', Admin\ProductController::class);
    Route::post('products/{product}/toggle-active',   [Admin\ProductController::class, 'toggleActive'])->name('products.toggle-active');
    Route::post('products/{product}/toggle-featured', [Admin\ProductController::class, 'toggleFeatured'])->name('products.toggle-featured');

    // Categories
    Route::resource('categories', Admin\CategoryController::class);

    // Orders
    Route::get('orders',                  [Admin\OrderController::class, 'index'])->name('orders.index');
    Route::get('orders/{order}',          [Admin\OrderController::class, 'show'])->name('orders.show');
    Route::patch('orders/{order}/status', [Admin\OrderController::class, 'updateStatus'])->name('orders.update-status');
    Route::delete('orders/{order}',       [Admin\OrderController::class, 'destroy'])->name('orders.destroy');

    // Users
    Route::get('users',                        [Admin\UserController::class, 'index'])->name('users.index');
    Route::get('users/{user}',                 [Admin\UserController::class, 'show'])->name('users.show');
    Route::post('users/{user}/toggle-role',    [Admin\UserController::class, 'toggleRole'])->name('users.toggle-role');
    Route::delete('users/{user}',              [Admin\UserController::class, 'destroy'])->name('users.destroy');

    // Coupons
    Route::resource('coupons', Admin\CouponController::class);
    Route::post('coupons/{coupon}/toggle', [Admin\CouponController::class, 'toggle'])->name('coupons.toggle');
});

// Cache Cleaner
Route::get('/clear', function () {
    Artisan::call('config:clear');
    Artisan::call('cache:clear');
    Artisan::call('view:clear');
    return "Cache Cleared!";
});