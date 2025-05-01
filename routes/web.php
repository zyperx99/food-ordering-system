<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Customer\RestaurantController;
use App\Http\Controllers\Customer\OrderController;
use App\Http\Controllers\Manager\OrderController as ManagerOrderController;
use App\Http\Controllers\Admin\RestaurantController as AdminRestaurantController;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| contains the "web" middleware group. Now create something great!
|
*/

Route::get('/', function () {
    return view('welcome');
});

// Default Dashboard (shared or redirect logic can go here later)
Route::get('/dashboard', function () {
    return view('dashboard');
})->middleware(['auth'])->name('dashboard');

// Auth routes (Breeze)
require __DIR__.'/auth.php';

// Admin Routes
Route::middleware(['auth', 'role:admin'])->prefix('admin')->name('admin.')->group(function () {
    Route::get('/restaurants', [AdminRestaurantController::class, 'index'])->name('restaurants.index');
    Route::post('/restaurants/{restaurant}/approve', [AdminRestaurantController::class, 'approve'])->name('restaurants.approve');
    Route::post('/restaurants/{restaurant}/disable', [AdminRestaurantController::class, 'disable'])->name('restaurants.disable');
});

// Manager Routes
Route::middleware(['auth', 'role:manager'])->prefix('manager')->name('manager.')->group(function () {
    Route::get('/dashboard', function () {
        return view('manager.dashboard');
    })->name('dashboard');

    Route::get('/orders', [ManagerOrderController::class, 'index'])->name('orders.index');
    Route::post('/orders/{order}/accept', [ManagerOrderController::class, 'accept'])->name('orders.accept');
    Route::post('/orders/{order}/reject', [ManagerOrderController::class, 'reject'])->name('orders.reject');
});

// Customer Routes
Route::middleware(['auth', 'role:customer'])->prefix('customer')->group(function () {
    Route::get('/dashboard', function () {
        return 'Customer Panel';
    })->name('customer.dashboard');
    Route::get('/restaurants', [RestaurantController::class, 'index'])->name('customer.restaurants');
    Route::get('/restaurant/{id}', [RestaurantController::class, 'show'])->name('customer.restaurant.show');
    Route::post('/order/store', [OrderController::class, 'store'])->name('customer.order.store');
    Route::get('/order/success', function () {
        return view('customer.orders.success');
    })->name('customer.order.success');
    Route::get('/payment/success', [OrderController::class, 'paymentSuccess'])->name('customer.payment.success');
    
});
