<?php

namespace App\Http\Controllers\Staff;

use App\Http\Controllers\Controller;
use App\Models\Order;
use App\Models\Product;
use App\Models\Delivery;
use Illuminate\Support\Carbon;

class DashboardController extends Controller
{
    public function index()
    {
        $today = Carbon::today();

        // Today's stats
        $todaySales = Order::whereDate('created_at', $today)
            ->where('status', 'completed')
            ->sum('total');

        $todayOrders = Order::whereDate('created_at', $today)
            ->where('status', 'completed')
            ->count();

        // Order stats for pipeline
        $orderStats = [
            'pending' => Order::where('status', 'pending')->count(),
            'confirmed' => Order::where('status', 'confirmed')->count(),
            'preparing' => Order::where('status', 'preparing')->count(),
            'ready' => Order::where('status', 'ready')->count(),
            'out_for_delivery' => Order::where('status', 'out_for_delivery')->count(),
            'completed' => Order::where('status', 'completed')->count(),
        ];

        // Keep individual variables for backward compatibility
        $pendingOrders = $orderStats['pending'];
        $preparingOrders = $orderStats['preparing'];
        $readyOrders = $orderStats['ready'];
        $activeOrders = Order::whereIn('status', ['confirmed', 'preparing', 'ready', 'out_for_delivery'])->count();

        // Today's preorders
        $todayPreorders = Order::where('type', 'preorder')
            ->whereDate('scheduled_at', $today)
            ->whereNotIn('status', ['completed', 'cancelled'])
            ->with('user')
            ->orderBy('scheduled_at')
            ->get();

        // Low stock products
        $lowStockProducts = Product::whereColumn('stock_quantity', '<=', 'min_stock_level')
            ->where('is_active', true)
            ->with('category')
            ->orderBy('stock_quantity')
            ->take(8)
            ->get();

        $lowStockCount = Product::whereColumn('stock_quantity', '<=', 'min_stock_level')
            ->where('is_active', true)
            ->count();

        // Recent orders
        $recentOrders = Order::with(['user', 'items.product'])
            ->latest()
            ->take(10)
            ->get();

        // Pending deliveries
        $pendingDeliveries = Delivery::whereIn('status', ['pending', 'assigned'])
            ->with('order.user')
            ->latest()
            ->take(5)
            ->get();

        return view('admin.staff.dashboard', compact(
            'todaySales',
            'todayOrders',
            'orderStats',
            'pendingOrders',
            'preparingOrders',
            'readyOrders',
            'activeOrders',
            'todayPreorders',
            'lowStockProducts',
            'lowStockCount',
            'recentOrders',
            'pendingDeliveries',
        ));
    }
}
