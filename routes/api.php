<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Api\{
    AuthController,
    ProductController,
    OrderController,
    PosController,
    InventoryController,
    ReportController,
    CategoryController,
    DeliveryController,
    NotificationController,
    FavoriteController,
    RatingController,
    ReceiptController
};

// Public routes
Route::post('/register', [AuthController::class, 'register']);
Route::post('/login', [AuthController::class, 'login']);

// Public product browsing (no auth required)
Route::get('/products', [ProductController::class, 'index']);
Route::get('/products/{product}', [ProductController::class, 'show']);
Route::get('/categories', [ProductController::class, 'categories']);

// Public ratings
Route::get('/products/{product}/ratings', [RatingController::class, 'getProductRatings']);

// Public order tracking (no auth required)
Route::get('/track-order/{orderNumber}', [OrderController::class, 'trackByNumber']);

// Protected routes (using 'web' guard for session-based auth)
Route::middleware(['web', 'auth:web'])->group(function () {
    
    // Favorites/Wishlist
    Route::get('/favorites', [FavoriteController::class, 'index']);
    Route::post('/favorites/{product}/toggle', [FavoriteController::class, 'toggle']);
    Route::get('/favorites/{product}/check', [FavoriteController::class, 'check']);
    Route::delete('/favorites/{product}', [FavoriteController::class, 'destroy']);
    Route::get('/products/{product}/likes-count', [FavoriteController::class, 'count']);

    // Ratings & Reviews
    Route::post('/products/{product}/ratings', [RatingController::class, 'submitRating']);
    Route::get('/products/{product}/my-rating', [RatingController::class, 'getUserRating']);
    Route::delete('/products/{product}/ratings', [RatingController::class, 'deleteRating']);
    
    // User
    Route::get('/user', [AuthController::class, 'user']);
    Route::post('/logout', [AuthController::class, 'logout']);
    
    // Customer Orders
    Route::get('/my-orders', [OrderController::class, 'myOrders']);
    Route::post('/preorders', [OrderController::class, 'storePreorder']);
    Route::post('/quick-order', [OrderController::class, 'quickOrder']);
    Route::get('/orders/{order}', [OrderController::class, 'show']);

    // Notifications
    Route::get('/notifications', [NotificationController::class, 'index']);
    Route::get('/notifications/unread', [NotificationController::class, 'unread']);
    Route::patch('/notifications/{id}/read', [NotificationController::class, 'markAsRead']);
    Route::post('/notifications/read-all', [NotificationController::class, 'markAllAsRead']);
    
    // Staff/Admin Routes
    Route::middleware('role:staff,admin')->group(function () {
        
        // POS System
        Route::prefix('pos')->group(function () {
            Route::post('/orders', [PosController::class, 'store']);
            Route::post('/sync-offline', [PosController::class, 'syncOffline']);
            Route::get('/daily-report', [PosController::class, 'dailyReport']);
            Route::get('/products', [PosController::class, 'products']);
        });
        
        // Order Management
        Route::get('/orders', [OrderController::class, 'index']);
        Route::patch('/orders/{order}/status', [OrderController::class, 'updateStatus']);
        Route::get('/preorders/today', [OrderController::class, 'todayPreorders']);
        
        // Inventory
        Route::get('/inventory/alerts', [InventoryController::class, 'alerts']);
        Route::post('/inventory/adjust', [InventoryController::class, 'adjust']);
        Route::get('/inventory/logs', [InventoryController::class, 'logs']);
        Route::get('/inventory/report', [InventoryController::class, 'report']);
        
        // Deliveries
        Route::apiResource('deliveries', DeliveryController::class);
    });
    
    // Admin Only
    Route::middleware('role:admin')->group(function () {
        // Admin listing routes (separate from public routes)
        Route::get('/admin/customers/chart-data', [App\Http\Controllers\Admin\CustomerController::class, 'chartData']);
        Route::get('/admin/customers/recent', [App\Http\Controllers\Admin\CustomerController::class, 'getRecentCustomers']);
        Route::get('/admin/customers', [App\Http\Controllers\Admin\CustomerController::class, 'getAll']);
        Route::get('/admin/products', [ProductController::class, 'getAll']);
        Route::get('/admin/orders', [OrderController::class, 'index']);
        Route::get('/admin/staff', [App\Http\Controllers\Admin\CustomerController::class, 'getStaff']);
        
        // Staff Management
        Route::post('/admin/staff', [App\Http\Controllers\Admin\StaffController::class, 'store']);
        Route::get('/admin/staff-list', [App\Http\Controllers\Admin\StaffController::class, 'list']);
        Route::put('/admin/staff/{user}', [App\Http\Controllers\Admin\StaffController::class, 'update']);
        Route::delete('/admin/staff/{user}', [App\Http\Controllers\Admin\StaffController::class, 'destroy']);

        Route::apiResource('products', ProductController::class)->except(['index', 'show']);
        
        // Product Archive/Unarchive
        Route::patch('/products/{product}/archive', [ProductController::class, 'archive']);
        Route::patch('/products/{product}/unarchive', [ProductController::class, 'unarchive']);
        
        // Receipts
        Route::get('/receipts/order/{order}', [ReceiptController::class, 'orderReceipt']);
        Route::get('/receipts/product/{product}', [ReceiptController::class, 'productHistory']);
        Route::get('/receipts/inventory/{product}', [ReceiptController::class, 'inventoryHistory']);
        Route::get('/receipts/export/{order}', [ReceiptController::class, 'exportReceipt']);
        
        Route::get('/reports/sales', [ReportController::class, 'sales']);
        Route::get('/reports/inventory', [ReportController::class, 'inventory']);
        Route::get('/reports/dashboard', [ReportController::class, 'dashboard']);
    });
});