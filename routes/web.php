<?php

use App\Http\Controllers\CartController;
use App\Http\Controllers\CheckoutController;
use App\Http\Controllers\LanguageController;
use App\Http\Controllers\OrderController;
use App\Http\Controllers\PostController;
use App\Http\Controllers\ProfileController;
use App\Http\Controllers\ShopController;
use App\Http\Controllers\ShopOwnerController;
use App\Http\Controllers\WelcomeController;
use Illuminate\Support\Facades\Route;
use App\Models\Order;

// Public routes
Route::get('/', [WelcomeController::class, 'index'])->name('welcome');
Route::get('/auth', fn() => view('auth.combined.auth'))->name('auth.combined');
Route::get('/language/{locale}', [LanguageController::class, 'switch'])->name('language.switch');

// Shop routes (public & authenticated) - CORRECT ORDER: specific routes BEFORE parameterized routes
Route::get('/shops', [ShopController::class, 'index'])->name('shops.index');
Route::get('/shops/create', [ShopController::class, 'create'])->name('shops.create')->middleware('auth');
Route::post('/shops', [ShopController::class, 'store'])->name('shops.store')->middleware('auth');
Route::get('/shops/{shop}', [ShopController::class, 'show'])->name('shops.show');
Route::get('/shops/{shop}/edit', [ShopController::class, 'edit'])->name('shops.edit')->middleware('auth');
Route::patch('/shops/{shop}', [ShopController::class, 'update'])->name('shops.update')->middleware('auth');
Route::delete('/shops/{shop}', [ShopController::class, 'destroy'])->name('shops.destroy')->middleware('auth');

// Authenticated routes
Route::middleware('auth')->group(function () {
    // Profile
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');

    // Posts
    Route::resource('posts', PostController::class);

    // Cart
    Route::get('/cart', [CartController::class, 'index'])->name('cart.index');
    Route::post('/cart/product/{product}', [CartController::class, 'addProduct'])->name('cart.addProduct');
    Route::post('/cart/post/{post}', [CartController::class, 'addFromPost'])->name('cart.addFromPost');
    Route::patch('/cart/{cartItem}', [CartController::class, 'update'])->name('cart.update');
    Route::delete('/cart/{cartItem}', [CartController::class, 'destroy'])->name('cart.destroy');

    // Checkout & Orders
    Route::get('/checkout', [CheckoutController::class, 'index'])->name('checkout.index');
    Route::post('/checkout', [CheckoutController::class, 'store'])->name('checkout.store');
    Route::get('/orders', [OrderController::class, 'index'])->name('orders.index');
    Route::get('/orders/{order}', [OrderController::class, 'show'])->name('orders.show');
    Route::get('/orders/{order}/track', [OrderController::class, 'track'])->name('orders.track');

    // Shop Owner Dashboard
    Route::prefix('shop-owner')->name('shop-owner.')->group(function () {
        Route::get('/dashboard', [ShopOwnerController::class, 'dashboard'])->name('dashboard');

        // Products
        Route::get('/products', [ShopOwnerController::class, 'products'])->name('products');
        Route::get('/products/create', [ShopOwnerController::class, 'createProduct'])->name('products.create');
        Route::post('/products', [ShopOwnerController::class, 'storeProduct'])->name('products.store');
        Route::get('/products/{product}/edit', [ShopOwnerController::class, 'editProduct'])->name('products.edit');
        Route::patch('/products/{product}', [ShopOwnerController::class, 'updateProduct'])->name('products.update');
        Route::delete('/products/{product}', [ShopOwnerController::class, 'destroyProduct'])->name('products.destroy');

        // Orders
        Route::get('/orders', [ShopOwnerController::class, 'orders'])->name('orders');
        Route::post('/orders/{approval}/approve', [ShopOwnerController::class, 'approveOrder'])->name('orders.approve');
    });
});

// Dashboard route (after authentication)
Route::get('/dashboard', function () {
    $user = auth()->user();
    
    if ($user->isAdmin()) {
        return redirect('/admin');
    }
    
    if ($user->isShopOwner()) {
        return redirect()->route('shop-owner.dashboard');
    }
    
    return redirect()->route('welcome');
})->middleware(['auth'])->name('dashboard');

// Test route (optional - remove in production)
Route::get('/test-colors', function () {
    return view('test-colors');
});

// routes/web.php
Route::get('/orders/{order}/status', function (Order $order) {
    return response()->json([
        'status' => $order->status,
    ]);
})->name('orders.status.ajax');

Route::post('/orders/{order}/next-status', function (Order $order) {
    $stages = ['packaging', 'sorting', 'shipping', 'delivering', 'delivered'];
    $currentStatus = $order->status;
    
    // Find the next stage
    $currentIndex = array_search($currentStatus, $stages);
    
    // If not in stages (like 'approved' or 'pending'), start at packaging
    if ($currentIndex === false) {
        $nextStatus = 'packaging';
    } else {
        $nextStatus = $stages[$currentIndex + 1] ?? 'delivered';
    }

    $order->update(['status' => $nextStatus]);

    return back()->with('success', 'Order moved to ' . $nextStatus);
})->name('orders.next-status');

Route::post('/orders/{order}/reset-status', function (App\Models\Order $order) {
    // Reset to your starting status
    $order->update(['status' => 'approved']);

    return back()->with('success', 'Order status has been reset.');
})->name('orders.reset-status');

require __DIR__.'/auth.php';