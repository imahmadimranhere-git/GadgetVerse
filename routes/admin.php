<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Admin\DashboardController;
use App\Http\Controllers\Admin\CategoryController;
use App\Http\Controllers\Admin\ProductController;
use App\Http\Controllers\Admin\BrandController;
use App\Http\Controllers\Admin\BannerController;
use App\Http\Controllers\Admin\ReviewController;
use App\Http\Controllers\Admin\CustomerController;
use App\Http\Controllers\Admin\OrderController;
use App\Http\Controllers\Admin\InventoryController;
use App\Http\Controllers\Admin\CouponController;

Route::middleware(['auth', 'admin'])->prefix('admin')->name('admin.')->group(function () {

    Route::get('/dashboard', [DashboardController::class, 'index'])->name('dashboard');

    Route::resource('categories', CategoryController::class);
    Route::resource('products', ProductController::class);
    Route::resource('brands', BrandController::class);
    Route::resource('banners', BannerController::class)->except(['show']);

    Route::get('/reviews', [ReviewController::class, 'index'])->name('reviews.index');
    Route::patch('/reviews/{review}/status', [ReviewController::class, 'updateStatus'])->name('reviews.updateStatus');
    Route::delete('/reviews/{review}', [ReviewController::class, 'destroy'])->name('reviews.destroy');

    Route::get('/customers', [CustomerController::class, 'index'])->name('customers.index');
    Route::patch('/customers/{customer}/toggle-block', [CustomerController::class, 'toggleBlock'])->name('customers.toggleBlock');

    Route::get('/orders', [OrderController::class, 'index'])->name('orders.index');
    Route::get('/orders/{order}', [OrderController::class, 'show'])->name('orders.show');
    Route::patch('/orders/{order}/status', [OrderController::class, 'updateStatus'])->name('orders.updateStatus');
    Route::patch('/orders/{order}/payment-status', [OrderController::class, 'updatePaymentStatus'])->name('orders.updatePaymentStatus');

    Route::get('/inventory', [InventoryController::class, 'index'])->name('inventory.index');
    Route::resource('coupons', CouponController::class);
});