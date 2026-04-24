<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Order;
use App\Models\Product;
use App\Models\User;
use App\Models\Category;
use App\Models\Delivery;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\DB;

class DashboardController extends Controller
{
    public function index()
    {
        $today = Carbon::today();
        $startOfMonth = Carbon::now()->startOfMonth();
        $startOfLastMonth = Carbon::now()->subMonth()->startOfMonth();
        $endOfLastMonth = Carbon::now()->subMonth()->endOfMonth();

        // Today's stats
        $todaySales = Order::whereDate('created_at', $today)
            ->where('status', 'completed')
            ->sum('total');

        $todayOrders = Order::whereDate('created_at', $today)
            ->where('status', 'completed')
            ->count();

        // Month stats
        $monthSales = Order::where('created_at', '>=', $startOfMonth)
            ->where('status', 'completed')
            ->sum('total');

        $monthOrders = Order::where('created_at', '>=', $startOfMonth)
            ->where('status', 'completed')
            ->count();

        // Last month for comparison
        $lastMonthSales = Order::whereBetween('created_at', [$startOfLastMonth, $endOfLastMonth])
            ->where('status', 'completed')
            ->sum('total');

        // Pending & active orders
        $pendingOrders = Order::where('status', 'pending')->count();
        $activeOrders = Order::whereIn('status', ['confirmed', 'preparing', 'ready', 'out_for_delivery'])->count();

        // Low stock products
        $lowStockProducts = Product::whereColumn('stock_quantity', '<=', 'min_stock_level')
            ->where('is_active', true)
            ->with('category')
            ->orderBy('stock_quantity')
            ->take(10)
            ->get();

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

        // Top selling products (this month)
        $topProducts = Product::select('products.id', 'products.name')
            ->join('order_items', 'products.id', '=', 'order_items.product_id')
            ->join('orders', 'order_items.order_id', '=', 'orders.id')
            ->where('orders.status', 'completed')
            ->where('orders.created_at', '>=', $startOfMonth)
            ->selectRaw('SUM(order_items.quantity) as total_sold')
            ->selectRaw('SUM(order_items.subtotal) as total_revenue')
            ->groupBy('products.id', 'products.name')
            ->orderByDesc('total_sold')
            ->take(5)
            ->get();

        // Sales chart data (last 7 days)
        $salesChart = Order::where('status', 'completed')
            ->where('created_at', '>=', Carbon::now()->subDays(6)->startOfDay())
            ->select(DB::raw('DATE(created_at) as date'), DB::raw('SUM(total) as total'), DB::raw('COUNT(*) as count'))
            ->groupBy('date')
            ->orderBy('date')
            ->get()
            ->keyBy('date');

        $chartLabels = [];
        $chartSales = [];
        $chartOrders = [];
        for ($i = 6; $i >= 0; $i--) {
            $date = Carbon::now()->subDays($i)->format('Y-m-d');
            $chartLabels[] = Carbon::parse($date)->format('M d');
            $chartSales[] = (float) ($salesChart[$date]->total ?? 0);
            $chartOrders[] = (int) ($salesChart[$date]->count ?? 0);
        }

        // Summary counts
        $totalProducts = Product::where('is_active', true)->count();
        $totalCategories = Category::where('is_active', true)->count();
        $totalCustomers = User::where('role', 'customer')->count();
        $lowStockCount = Product::whereColumn('stock_quantity', '<=', 'min_stock_level')
            ->where('is_active', true)
            ->count();

        return view('admin.dashboard', compact(
            'todaySales',
            'todayOrders',
            'monthSales',
            'monthOrders',
            'lastMonthSales',
            'pendingOrders',
            'activeOrders',
            'lowStockProducts',
            'lowStockCount',
            'recentOrders',
            'pendingDeliveries',
            'topProducts',
            'chartLabels',
            'chartSales',
            'chartOrders',
            'totalProducts',
            'totalCategories',
            'totalCustomers',
        ));
    }

    public function chartSale()
    {
        $now = Carbon::now();

        // --- Daily sales (last 30 days) ---
        $dailyData = Order::where('status', 'completed')
            ->where('created_at', '>=', $now->copy()->subDays(29)->startOfDay())
            ->select(DB::raw('DATE(created_at) as date'), DB::raw('SUM(total) as total'), DB::raw('COUNT(*) as count'))
            ->groupBy('date')
            ->orderBy('date')
            ->get()
            ->keyBy('date');

        $dailyLabels = [];
        $dailySales = [];
        $dailyOrders = [];
        for ($i = 29; $i >= 0; $i--) {
            $date = $now->copy()->subDays($i)->format('Y-m-d');
            $dailyLabels[] = Carbon::parse($date)->format('M d');
            $dailySales[] = (float) ($dailyData[$date]->total ?? 0);
            $dailyOrders[] = (int) ($dailyData[$date]->count ?? 0);
        }

        // --- Weekly sales (last 12 weeks) ---
        $weeklyData = Order::where('status', 'completed')
            ->where('created_at', '>=', $now->copy()->subWeeks(11)->startOfWeek())
            ->select(
                DB::raw('YEAR(created_at) as yr'),
                DB::raw('WEEK(created_at, 1) as wk'),
                DB::raw('SUM(total) as total'),
                DB::raw('COUNT(*) as count')
            )
            ->groupBy('yr', 'wk')
            ->orderBy('yr')
            ->orderBy('wk')
            ->get();

        $weeklyLabels = [];
        $weeklySales = [];
        $weeklyOrders = [];
        for ($i = 11; $i >= 0; $i--) {
            $weekStart = $now->copy()->subWeeks($i)->startOfWeek();
            $yr = (int) $weekStart->format('o');
            $wk = (int) $weekStart->format('W');
            $weeklyLabels[] = $weekStart->format('M d');
            $match = $weeklyData->first(fn($r) => (int) $r->yr === $yr && (int) $r->wk === $wk);
            $weeklySales[] = (float) ($match->total ?? 0);
            $weeklyOrders[] = (int) ($match->count ?? 0);
        }

        // --- Monthly sales (last 12 months) ---
        $monthlyData = Order::where('status', 'completed')
            ->where('created_at', '>=', $now->copy()->subMonths(11)->startOfMonth())
            ->select(
                DB::raw('YEAR(created_at) as yr'),
                DB::raw('MONTH(created_at) as mo'),
                DB::raw('SUM(total) as total'),
                DB::raw('COUNT(*) as count')
            )
            ->groupBy('yr', 'mo')
            ->orderBy('yr')
            ->orderBy('mo')
            ->get();

        $monthlyLabels = [];
        $monthlySales = [];
        $monthlyOrders = [];
        for ($i = 11; $i >= 0; $i--) {
            $month = $now->copy()->subMonths($i);
            $yr = (int) $month->format('Y');
            $mo = (int) $month->format('n');
            $monthlyLabels[] = $month->format('M Y');
            $match = $monthlyData->first(fn($r) => (int) $r->yr === $yr && (int) $r->mo === $mo);
            $monthlySales[] = (float) ($match->total ?? 0);
            $monthlyOrders[] = (int) ($match->count ?? 0);
        }

        // --- Sales by category (this month) ---
        $categoryData = DB::table('order_items')
            ->join('orders', 'order_items.order_id', '=', 'orders.id')
            ->join('products', 'order_items.product_id', '=', 'products.id')
            ->join('categories', 'products.category_id', '=', 'categories.id')
            ->where('orders.status', 'completed')
            ->where('orders.created_at', '>=', $now->copy()->startOfMonth())
            ->select('categories.name', DB::raw('SUM(order_items.subtotal) as total'))
            ->groupBy('categories.name')
            ->orderByDesc('total')
            ->get();

        $categoryLabels = $categoryData->pluck('name')->toArray();
        $categorySales = $categoryData->pluck('total')->map(fn($v) => (float) $v)->toArray();

        // --- Sales by order type (this month) ---
        $typeData = Order::where('status', 'completed')
            ->where('created_at', '>=', $now->copy()->startOfMonth())
            ->select('type', DB::raw('SUM(total) as total'), DB::raw('COUNT(*) as count'))
            ->groupBy('type')
            ->get()
            ->keyBy('type');

        $posSales = (float) ($typeData['pos']->total ?? 0);
        $preorderSales = (float) ($typeData['preorder']->total ?? 0);

        // --- Top 10 products (this month by revenue) ---
        $topProducts = Product::select('products.name')
            ->join('order_items', 'products.id', '=', 'order_items.product_id')
            ->join('orders', 'order_items.order_id', '=', 'orders.id')
            ->where('orders.status', 'completed')
            ->where('orders.created_at', '>=', $now->copy()->startOfMonth())
            ->selectRaw('SUM(order_items.subtotal) as total_revenue')
            ->selectRaw('SUM(order_items.quantity) as total_sold')
            ->groupBy('products.id', 'products.name')
            ->orderByDesc('total_revenue')
            ->take(10)
            ->get();

        $topProductLabels = $topProducts->pluck('name')->toArray();
        $topProductRevenue = $topProducts->pluck('total_revenue')->map(fn($v) => (float) $v)->toArray();
        $topProductQty = $topProducts->pluck('total_sold')->map(fn($v) => (int) $v)->toArray();

        // --- Summary KPIs ---
        $todaySales = Order::whereDate('created_at', $now->copy()->toDateString())
            ->where('status', 'completed')->sum('total');
        $thisWeekSales = Order::where('created_at', '>=', $now->copy()->startOfWeek())
            ->where('status', 'completed')->sum('total');
        $thisMonthSales = Order::where('created_at', '>=', $now->copy()->startOfMonth())
            ->where('status', 'completed')->sum('total');
        $thisMonthOrders = Order::where('created_at', '>=', $now->copy()->startOfMonth())
            ->where('status', 'completed')->count();
        $avgOrderValue = $thisMonthOrders > 0 ? $thisMonthSales / $thisMonthOrders : 0;

        return view('admin.chartsale', compact(
            'dailyLabels', 'dailySales', 'dailyOrders',
            'weeklyLabels', 'weeklySales', 'weeklyOrders',
            'monthlyLabels', 'monthlySales', 'monthlyOrders',
            'categoryLabels', 'categorySales',
            'posSales', 'preorderSales',
            'topProductLabels', 'topProductRevenue', 'topProductQty',
            'todaySales', 'thisWeekSales', 'thisMonthSales', 'thisMonthOrders', 'avgOrderValue',
        ));
    }
}
