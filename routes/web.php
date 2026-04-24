<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\ProfileController;
use App\Http\Controllers\PasswordController;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\Admin\DashboardController as AdminDashboardController;
use App\Http\Controllers\Admin\CustomerController;
use App\Http\Controllers\Admin\RevenueController;
use App\Http\Controllers\Admin\NotificationController;
use App\Http\Controllers\Staff\DashboardController as StaffDashboardController;
use App\Http\Controllers\User\NotificationController as UserNotificationController;
use App\Http\Controllers\User\OrderController;

/*
|--------------------------------------------------------------------------
| Public Routes
|--------------------------------------------------------------------------
*/

Route::get('/', fn () => view('homepage'))->name('home');
Route::view('/about', 'about')->name('about');
Route::get('/contact', fn () => view('contact'))->name('contact');
Route::get('/products', fn () => view('product'))->name('products.catalog');

/*
|--------------------------------------------------------------------------
| Authenticated Routes
|--------------------------------------------------------------------------
*/

Route::middleware('auth')->group(function () {

    // Profile & Account
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');
    Route::put('/password', [PasswordController::class, 'update'])->name('password.change');

    // User Notifications
    Route::get('/notifications', [UserNotificationController::class, 'index'])->name('notifications.index');
    Route::patch('/notifications/{id}/read', [UserNotificationController::class, 'markAsRead'])->name('notifications.mark-read');

    // Wishlist
    Route::get('/wishlist', fn () => view('wishlist'))->name('wishlist');

    // Customer Routes (requires verified email)
    Route::middleware('verified')->group(function () {
        Route::get('/dashboard', fn () => redirect()->route('home'))->name('dashboard');
        Route::get('/shop', fn () => view('product'))->name('products');
        Route::get('/my-orders', [OrderController::class, 'index'])->name('my-orders');
        Route::get('/orders/{order}', [OrderController::class, 'show'])->name('orders.show');
        Route::post('/orders/{order}/reorder', [OrderController::class, 'reorder'])->name('orders.reorder');
        Route::get('/track-order', fn () => view('customer.track-order'))->name('track-order');
    });

    // Staff & Admin Routes
    Route::middleware(['role:staff,admin'])->prefix('staff')->name('staff.')->group(function () {
        Route::get('/dashboard', [StaffDashboardController::class, 'index'])->name('dashboard');
        Route::get('/pos', fn () => view('staff.pos'))->name('pos');
        Route::get('/orders', fn () => view('staff.orders'))->name('orders');
        Route::get('/inventory', fn () => view('staff.inventory'))->name('inventory');
        Route::get('/deliveries', fn () => view('staff.deliveries'))->name('deliveries');
    });

    // Admin-Only Routes
    Route::middleware(['verified', 'role:admin'])->prefix('admin')->name('admin.')->group(function () {
        Route::get('/dashboard', [AdminDashboardController::class, 'index'])->name('dashboard');
        Route::get('/dashboard/chart-sales', [AdminDashboardController::class, 'chartSale'])->name('chartsale');
        Route::get('/products', fn () => view('admin.products'))->name('products');
        Route::get('/reports', fn () => view('admin.reports'))->name('reports');
        Route::get('/deliveries', fn () => view('admin.deliveries'))->name('deliveries');
        Route::get('/transactions', fn () => view('admin.transactions'))->name('transactions');
        Route::get('/staff', fn () => view('admin.staff'))->name('staff');
        Route::get('/customers', [CustomerController::class, 'index'])->name('customers.index');
        Route::get('/customers/{customer}', [CustomerController::class, 'show'])->name('customers.show');

        // Revenue Reports
        Route::prefix('revenue')->name('revenue.')->group(function () {
            Route::get('/', [RevenueController::class, 'index'])->name('index');
            Route::get('/daily', [RevenueController::class, 'daily'])->name('daily');
            Route::get('/weekly', [RevenueController::class, 'weekly'])->name('weekly');
            Route::get('/monthly', [RevenueController::class, 'monthly'])->name('monthly');
            Route::get('/yearly', [RevenueController::class, 'yearly'])->name('yearly');
            Route::get('/chart-data', [RevenueController::class, 'chartData'])->name('chartData');
        });

        // Notifications
        Route::prefix('notifications')->name('notifications.')->group(function () {
            Route::get('/', [NotificationController::class, 'index'])->name('index');
            Route::get('/create', [NotificationController::class, 'create'])->name('create');
            Route::post('/', [NotificationController::class, 'store'])->name('store');
            Route::get('/{notification}', [NotificationController::class, 'show'])->name('show');
            Route::put('/{notification}/read', [NotificationController::class, 'markAsRead'])->name('mark-read');
            Route::delete('/{notification}', [NotificationController::class, 'destroy'])->name('destroy');
            Route::get('/api/unread-count', [NotificationController::class, 'getUnreadCount'])->name('unread-count');
            Route::get('/api/recent/{limit?}', [NotificationController::class, 'getRecent'])->name('recent');
        });
    });
});