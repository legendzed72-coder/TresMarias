<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Order;
use App\Models\User;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Auth;

class CustomerController extends Controller
{
    public function index()
    {
        $startOfMonth = Carbon::now()->startOfMonth();

        // Total customers (users with role = customer or who have orders)
        $totalCustomers = User::whereHas('orders')->count();
        $newCustomersThisMonth = User::whereHas('orders')
            ->where('created_at', '>=', $startOfMonth)
            ->count();

        // Repeat customers (more than 1 order)
        $repeatCustomers = User::whereHas('orders', function ($q) {
            $q->where('status', 'completed');
        }, '>', 1)->count();

        $repeatRate = $totalCustomers > 0 ? round(($repeatCustomers / $totalCustomers) * 100, 1) : 0;

        // Average order value
        $avgOrderValue = Order::where('status', 'completed')->avg('total') ?? 0;

        // Top customers by total spent
        $topCustomerIds = DB::table('orders')
            ->where('status', 'completed')
            ->whereNotNull('user_id')
            ->select('user_id')
            ->selectRaw('COUNT(id) as total_orders')
            ->selectRaw('SUM(total) as total_spent')
            ->selectRaw('MAX(created_at) as last_order_at')
            ->selectRaw('AVG(total) as avg_order_value')
            ->groupBy('user_id')
            ->orderByDesc('total_spent')
            ->limit(20);

        $topCustomers = User::joinSub($topCustomerIds, 'order_stats', function ($join) {
                $join->on('users.id', '=', 'order_stats.user_id');
            })
            ->select('users.*', 'order_stats.total_orders', 'order_stats.total_spent', 'order_stats.last_order_at', 'order_stats.avg_order_value')
            ->orderByDesc('order_stats.total_spent')
            ->get();

        // Recent ordering customers
        $recentCustomerIds = DB::table('orders')
            ->whereNotNull('user_id')
            ->select('user_id')
            ->selectRaw('COUNT(id) as total_orders')
            ->selectRaw('SUM(total) as total_spent')
            ->selectRaw('MAX(created_at) as last_order_at')
            ->groupBy('user_id')
            ->orderByDesc('last_order_at')
            ->limit(10);

        $recentCustomers = User::joinSub($recentCustomerIds, 'order_stats', function ($join) {
                $join->on('users.id', '=', 'order_stats.user_id');
            })
            ->select('users.*', 'order_stats.total_orders', 'order_stats.total_spent', 'order_stats.last_order_at')
            ->orderByDesc('order_stats.last_order_at')
            ->get();

        // Customer growth chart (last 6 months)
        $customerGrowth = User::whereHas('orders')
            ->where('created_at', '>=', Carbon::now()->subMonths(5)->startOfMonth())
            ->select(
                DB::raw("DATE_FORMAT(created_at, '%Y-%m') as month"),
                DB::raw('COUNT(*) as count')
            )
            ->groupBy('month')
            ->orderBy('month')
            ->get()
            ->keyBy('month');

        $growthLabels = [];
        $growthData = [];
        for ($i = 5; $i >= 0; $i--) {
            $month = Carbon::now()->subMonths($i)->format('Y-m');
            $growthLabels[] = Carbon::now()->subMonths($i)->format('M Y');
            $growthData[] = (int) ($customerGrowth[$month]->count ?? 0);
        }

        // Order frequency distribution
        $frequencyDistribution = DB::table('orders')
            ->where('status', 'completed')
            ->whereNotNull('user_id')
            ->select('user_id', DB::raw('COUNT(*) as order_count'))
            ->groupBy('user_id')
            ->get()
            ->groupBy(function ($item) {
                if ($item->order_count == 1) return '1 order';
                if ($item->order_count <= 3) return '2-3 orders';
                if ($item->order_count <= 5) return '4-5 orders';
                if ($item->order_count <= 10) return '6-10 orders';
                return '10+ orders';
            })
            ->map->count();

        $frequencyLabels = ['1 order', '2-3 orders', '4-5 orders', '6-10 orders', '10+ orders'];
        $frequencyData = [];
        foreach ($frequencyLabels as $label) {
            $frequencyData[] = $frequencyDistribution[$label] ?? 0;
        }

        return view('admin.customers', compact(
            'totalCustomers',
            'newCustomersThisMonth',
            'repeatCustomers',
            'repeatRate',
            'avgOrderValue',
            'topCustomers',
            'recentCustomers',
            'growthLabels',
            'growthData',
            'frequencyLabels',
            'frequencyData'
        ));
    }

    public function chartData()
    {
        // Customer growth chart (last 6 months)
        $customerGrowth = User::whereHas('orders')
            ->where('created_at', '>=', Carbon::now()->subMonths(5)->startOfMonth())
            ->select(
                DB::raw("DATE_FORMAT(created_at, '%Y-%m') as month"),
                DB::raw('COUNT(*) as count')
            )
            ->groupBy('month')
            ->orderBy('month')
            ->get()
            ->keyBy('month');

        $growthLabels = [];
        $growthData = [];
        for ($i = 5; $i >= 0; $i--) {
            $month = Carbon::now()->subMonths($i)->format('Y-m');
            $growthLabels[] = Carbon::now()->subMonths($i)->format('M Y');
            $growthData[] = (int) ($customerGrowth[$month]->count ?? 0);
        }

        // Order frequency distribution
        $frequencyDistribution = DB::table('orders')
            ->where('status', 'completed')
            ->whereNotNull('user_id')
            ->select('user_id', DB::raw('COUNT(*) as order_count'))
            ->groupBy('user_id')
            ->get()
            ->groupBy(function ($item) {
                if ($item->order_count == 1) return '1 order';
                if ($item->order_count <= 3) return '2-3 orders';
                if ($item->order_count <= 5) return '4-5 orders';
                if ($item->order_count <= 10) return '6-10 orders';
                return '10+ orders';
            })
            ->map->count();

        $frequencyLabels = ['1 order', '2-3 orders', '4-5 orders', '6-10 orders', '10+ orders'];
        $frequencyData = [];
        foreach ($frequencyLabels as $label) {
            $frequencyData[] = $frequencyDistribution[$label] ?? 0;
        }

        return response()->json([
            'growthLabels' => $growthLabels,
            'growthData' => $growthData,
            'frequencyLabels' => $frequencyLabels,
            'frequencyData' => $frequencyData,
        ]);
    }

    public function show(User $customer)
    {
        $customer->loadCount('orders');

        // Order statistics
        $stats = Order::where('user_id', $customer->id)
            ->where('status', 'completed')
            ->selectRaw('COUNT(*) as total_orders')
            ->selectRaw('SUM(total) as total_spent')
            ->selectRaw('AVG(total) as avg_order')
            ->selectRaw('MIN(created_at) as first_order')
            ->selectRaw('MAX(created_at) as last_order')
            ->first();

        // All orders with items
        $orders = Order::where('user_id', $customer->id)
            ->with('items.product')
            ->latest()
            ->paginate(10);

        // Favorite products
        $favoriteProducts = DB::table('order_items')
            ->join('orders', 'order_items.order_id', '=', 'orders.id')
            ->join('products', 'order_items.product_id', '=', 'products.id')
            ->where('orders.user_id', $customer->id)
            ->where('orders.status', 'completed')
            ->select(
                'products.id',
                'products.name',
                'products.image_url',
                'products.price',
                DB::raw('SUM(order_items.quantity) as times_ordered'),
                DB::raw('SUM(order_items.subtotal) as total_spent')
            )
            ->groupBy('products.id', 'products.name', 'products.image_url', 'products.price')
            ->orderByDesc('times_ordered')
            ->take(5)
            ->get();

        // Monthly spending trend (last 6 months)
        $spendingTrend = Order::where('user_id', $customer->id)
            ->where('status', 'completed')
            ->where('created_at', '>=', Carbon::now()->subMonths(5)->startOfMonth())
            ->select(
                DB::raw("DATE_FORMAT(created_at, '%Y-%m') as month"),
                DB::raw('SUM(total) as total'),
                DB::raw('COUNT(*) as count')
            )
            ->groupBy('month')
            ->orderBy('month')
            ->get()
            ->keyBy('month');

        $trendLabels = [];
        $trendSpending = [];
        $trendOrders = [];
        for ($i = 5; $i >= 0; $i--) {
            $month = Carbon::now()->subMonths($i)->format('Y-m');
            $trendLabels[] = Carbon::now()->subMonths($i)->format('M Y');
            $trendSpending[] = (float) ($spendingTrend[$month]->total ?? 0);
            $trendOrders[] = (int) ($spendingTrend[$month]->count ?? 0);
        }

        return view('admin.staff.app', compact(
            'customer',
            'stats',
            'orders',
            'favoriteProducts',
            'trendLabels',
            'trendSpending',
            'trendOrders'
        ));
    }

    public function getAll()
    {
        return response()->json(User::select('id', 'name', 'email')->get());
    }

    public function getRecentCustomers()
    {
        try {
            Log::info('getRecentCustomers called - User: ' . Auth::id() . ', Role: ' . (Auth::user()?->role ?? 'none'));
            
            // Recent ordering customers with stats
            $recentCustomerIds = DB::table('orders')
                ->whereNotNull('user_id')
                ->select('user_id')
                ->selectRaw('COUNT(id) as total_orders')
                ->selectRaw('SUM(total) as total_spent')
                ->selectRaw('MAX(created_at) as last_order_at')
                ->groupBy('user_id')
                ->orderByDesc('last_order_at')
                ->limit(10);

            $recentCustomers = User::joinSub($recentCustomerIds, 'order_stats', function ($join) {
                    $join->on('users.id', '=', 'order_stats.user_id');
                })
                ->select(
                    'users.id',
                    'users.name',
                    'users.email',
                    'users.phone',
                    'order_stats.total_orders',
                    'order_stats.total_spent',
                    'order_stats.last_order_at'
                )
                ->orderByDesc('order_stats.last_order_at')
                ->get();

            return response()->json([
                'success' => true,
                'data' => $recentCustomers,
                'count' => $recentCustomers->count(),
                'timestamp' => now()->toIso8601String()
            ]);
        } catch (\Exception $e) {
            Log::error('Error in getRecentCustomers: ' . $e->getMessage());
            return response()->json([
                'success' => false,
                'message' => 'Failed to fetch recent customers',
                'error' => $e->getMessage()
            ], 500);
        }
    }

    public function getStaff()
    {
        return response()->json(User::where('role', 'staff')->orWhere('role', 'admin')->select('id', 'name', 'email', 'role')->get());
    }
}
