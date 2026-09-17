<?php

use App\Http\Controllers\CartController;
use App\Http\Controllers\CategoryController;
use App\Http\Controllers\ChatController;
use App\Http\Controllers\CheckoutController;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\OrderController;
use App\Http\Controllers\PaymentController;
use App\Http\Controllers\ProductController;
use App\Http\Controllers\ProfileController;
use App\Http\Controllers\ReviewController;
use Illuminate\Support\Facades\Route;

Route::get('/', function () {
    return redirect()->route('products.index');
});

Route::get('/about', function () {
    return view('about');
})->name('about');

Route::get('/dashboard', [DashboardController::class, 'index'])
    ->middleware('auth')
    ->name('dashboard');

// Profile bawaan Breeze
Route::middleware('auth')->group(function () {
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');
});

// =====================================================
// KHUSUS SELLER - taruh route /create & /edit SEBELUM route {product}
// =====================================================
Route::middleware(['auth', 'seller'])->group(function () {
    Route::get('/products/create', [ProductController::class, 'create'])->name('products.create');
    Route::post('/products', [ProductController::class, 'store'])->name('products.store');
    Route::get('/products/{product}/edit', [ProductController::class, 'edit'])->name('products.edit');
    Route::put('/products/{product}', [ProductController::class, 'update'])->name('products.update');
    Route::delete('/products/{product}', [ProductController::class, 'destroy'])->name('products.destroy');

    Route::get('/categories/create', [CategoryController::class, 'create'])->name('categories.create');
    Route::post('/categories', [CategoryController::class, 'store'])->name('categories.store');
    Route::get('/categories/{category}/edit', [CategoryController::class, 'edit'])->name('categories.edit');
    Route::put('/categories/{category}', [CategoryController::class, 'update'])->name('categories.update');
    Route::delete('/categories/{category}', [CategoryController::class, 'destroy'])->name('categories.destroy');

    Route::get('/seller/orders', [OrderController::class, 'sellerIndex'])->name('orders.sellerIndex');
    Route::patch('/orders/{order}/status', [OrderController::class, 'updateStatus'])->name('orders.updateStatus');
});

// =====================================================
// PUBLIC - guest bisa eksplor tanpa login (taruh SETELAH /products/create di atas)
// =====================================================
Route::get('/products', [ProductController::class, 'index'])->name('products.index');
Route::get('/products/{product}', [ProductController::class, 'show'])->name('products.show');
Route::get('/categories', [CategoryController::class, 'index'])->name('categories.index');

// PUBLIC - halaman konfirmasi QR (diakses dari HP hasil scan)
Route::get('/pay/confirm/{token}', [PaymentController::class, 'confirmPage'])->name('payments.confirm');
Route::post('/pay/confirm/{token}', [PaymentController::class, 'confirm'])->name('payments.doConfirm');

// =====================================================
// WAJIB LOGIN (buyer & seller) - order, pembayaran, keranjang, checkout, review, chat
// =====================================================
Route::middleware('auth')->group(function () {
    Route::get('/orders', [OrderController::class, 'index'])->name('orders.index');
    Route::get('/orders/{order}', [OrderController::class, 'show'])->name('orders.show');
    Route::delete('/orders/{order}/cancel', [OrderController::class, 'cancel'])->name('orders.cancel');
    Route::post('/orders/{order}/reviews', [ReviewController::class, 'store'])->name('reviews.store');

    Route::get('/payments/{order}/generate', [PaymentController::class, 'generate'])->name('payments.generate');
    Route::get('/payments/{payment}/status', [PaymentController::class, 'checkStatus'])->name('payments.status');

    Route::get('/cart', [CartController::class, 'index'])->name('cart.index');
    Route::post('/cart/{product}', [CartController::class, 'add'])->name('cart.add');
    Route::put('/cart/item/{cartItem}', [CartController::class, 'updateQuantity'])->name('cart.updateQuantity');
    Route::delete('/cart/item/{cartItem}', [CartController::class, 'remove'])->name('cart.remove');

    Route::get('/checkout', [CheckoutController::class, 'form'])->name('checkout.form');
    Route::post('/checkout', [CheckoutController::class, 'process'])->name('checkout.process');

    Route::get('/chat', [ChatController::class, 'index'])->name('chat.index');
    Route::post('/chat/start/{product}', [ChatController::class, 'start'])->name('chat.start');
    Route::get('/chat/{conversation}', [ChatController::class, 'show'])->name('chat.show');
    Route::post('/chat/{conversation}/messages', [ChatController::class, 'sendMessage'])->name('chat.send');
    Route::get('/chat/{conversation}/fetch', [ChatController::class, 'fetchMessages'])->name('chat.fetch');
});

require __DIR__.'/auth.php';
