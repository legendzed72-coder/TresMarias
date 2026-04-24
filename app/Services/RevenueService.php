<?php

namespace App\Services;

use App\Models\Order;
use App\Models\OrderItem;
use App\Models\Product;
use Illuminate\Support\Facades\DB;
use Carbon\Carbon;
use Illuminate\Pagination\Paginate;

class RevenueService
{
    /**
     * Get daily revenue grouped by product
     */
    public function getDailyRevenue($date = null, $limit = null)
    {
        $date = $date ? Carbon::parse($date) : Carbon::now();
        
        $query = OrderItem::selectRaw('
                products.id,
                products.name,
                products.image_url,
                COUNT(order_items.id) as total_quantity,
                SUM(order_items.quantity) as quantity_sold,
                SUM(order_items.unit_price * order_items.quantity) as revenue,
                AVG(order_items.unit_price) as avg_price,
                (SUM(order_items.unit_price * order_items.quantity) - SUM(products.cost_price * order_items.quantity)) as profit
            ')
            ->join('products', 'order_items.product_id', '=', 'products.id')
            ->join('orders', 'order_items.order_id', '=', 'orders.id')
            ->whereDate('orders.created_at', $date->toDateString())
            ->where('orders.status', '!=', 'cancelled')
            ->groupBy('products.id', 'products.name', 'products.image_url');
        
        if ($limit) {
            $query = $query->take($limit);
        }
        
        return $query->orderBy('revenue', 'desc')->get();
    }

    /**
     * Get weekly revenue grouped by product
     */
    public function getWeeklyRevenue($startDate = null, $limit = null)
    {
        $startDate = $startDate ? Carbon::parse($startDate)->startOfWeek() : Carbon::now()->startOfWeek();
        $endDate = (clone $startDate)->endOfWeek();
        
        $query = OrderItem::selectRaw('
                products.id,
                products.name,
                products.image_url,
                COUNT(order_items.id) as total_quantity,
                SUM(order_items.quantity) as quantity_sold,
                SUM(order_items.unit_price * order_items.quantity) as revenue,
                AVG(order_items.unit_price) as avg_price,
                (SUM(order_items.unit_price * order_items.quantity) - SUM(products.cost_price * order_items.quantity)) as profit
            ')
            ->join('products', 'order_items.product_id', '=', 'products.id')
            ->join('orders', 'order_items.order_id', '=', 'orders.id')
            ->whereBetween('orders.created_at', [$startDate, $endDate])
            ->where('orders.status', '!=', 'cancelled')
            ->groupBy('products.id', 'products.name', 'products.image_url');
        
        if ($limit) {
            $query = $query->take($limit);
        }
        
        return $query->orderBy('revenue', 'desc')->get();
    }

    /**
     * Get monthly revenue grouped by product
     */
    public function getMonthlyRevenue($month = null, $year = null, $limit = null)
    {
        if (!$month || !$year) {
            $now = Carbon::now();
            $month = $month ?? $now->month;
            $year = $year ?? $now->year;
        }
        
        $startDate = Carbon::createFromDate($year, $month, 1)->startOfMonth();
        $endDate = (clone $startDate)->endOfMonth();
        
        $query = OrderItem::selectRaw('
                products.id,
                products.name,
                products.image_url,
                COUNT(order_items.id) as total_quantity,
                SUM(order_items.quantity) as quantity_sold,
                SUM(order_items.unit_price * order_items.quantity) as revenue,
                AVG(order_items.unit_price) as avg_price,
                (SUM(order_items.unit_price * order_items.quantity) - SUM(products.cost_price * order_items.quantity)) as profit
            ')
            ->join('products', 'order_items.product_id', '=', 'products.id')
            ->join('orders', 'order_items.order_id', '=', 'orders.id')
            ->whereBetween('orders.created_at', [$startDate, $endDate])
            ->where('orders.status', '!=', 'cancelled')
            ->groupBy('products.id', 'products.name', 'products.image_url');
        
        if ($limit) {
            $query = $query->take($limit);
        }
        
        return $query->orderBy('revenue', 'desc')->get();
    }

    /**
     * Get yearly revenue grouped by product
     */
    public function getYearlyRevenue($year = null, $limit = null)
    {
        $year = $year ?? Carbon::now()->year;
        $startDate = Carbon::createFromDate($year, 1, 1)->startOfYear();
        $endDate = (clone $startDate)->endOfYear();
        
        $query = OrderItem::selectRaw('
                products.id,
                products.name,
                products.image_url,
                COUNT(order_items.id) as total_quantity,
                SUM(order_items.quantity) as quantity_sold,
                SUM(order_items.unit_price * order_items.quantity) as revenue,
                AVG(order_items.unit_price) as avg_price,
                (SUM(order_items.unit_price * order_items.quantity) - SUM(products.cost_price * order_items.quantity)) as profit
            ')
            ->join('products', 'order_items.product_id', '=', 'products.id')
            ->join('orders', 'order_items.order_id', '=', 'orders.id')
            ->whereBetween('orders.created_at', [$startDate, $endDate])
            ->where('orders.status', '!=', 'cancelled')
            ->groupBy('products.id', 'products.name', 'products.image_url');
        
        if ($limit) {
            $query = $query->take($limit);
        }
        
        return $query->orderBy('revenue', 'desc')->get();
    }

    /**
     * Get revenue overview for dashboard
     */
    public function getRevenueOverview($startDate = null, $endDate = null)
    {
        $startDate = $startDate ? Carbon::parse($startDate) : Carbon::now()->startOfMonth();
        $endDate = $endDate ? Carbon::parse($endDate) : Carbon::now();
        
        $totalRevenue = OrderItem::selectRaw('SUM(unit_price * quantity) as total')
            ->join('orders', 'order_items.order_id', '=', 'orders.id')
            ->whereBetween('orders.created_at', [$startDate, $endDate])
            ->where('orders.status', '!=', 'cancelled')
            ->first()
            ->total ?? 0;
        
        $totalProfit = OrderItem::selectRaw('SUM((order_items.unit_price - products.cost_price) * order_items.quantity) as total')
            ->join('products', 'order_items.product_id', '=', 'products.id')
            ->join('orders', 'order_items.order_id', '=', 'orders.id')
            ->whereBetween('orders.created_at', [$startDate, $endDate])
            ->where('orders.status', '!=', 'cancelled')
            ->first()
            ->total ?? 0;
        
        $totalOrders = Order::whereBetween('created_at', [$startDate, $endDate])
            ->where('status', '!=', 'cancelled')
            ->count();
        
        $totalItems = OrderItem::join('orders', 'order_items.order_id', '=', 'orders.id')
            ->whereBetween('orders.created_at', [$startDate, $endDate])
            ->where('orders.status', '!=', 'cancelled')
            ->sum('order_items.quantity');
        
        return [
            'total_revenue' => (float) $totalRevenue,
            'total_profit' => (float) $totalProfit,
            'profit_margin' => $totalRevenue > 0 ? (($totalProfit / $totalRevenue) * 100) : 0,
            'total_orders' => $totalOrders,
            'total_items_sold' => $totalItems,
            'avg_order_value' => $totalOrders > 0 ? ($totalRevenue / $totalOrders) : 0,
        ];
    }

    /**
     * Get daily trend data for charts
     */
    public function getDailyTrend($days = 30)
    {
        $startDate = Carbon::now()->subDays($days)->startOfDay();
        $endDate = Carbon::now()->endOfDay();
        
        return OrderItem::selectRaw('
                DATE(orders.created_at) as date,
                SUM(CASE WHEN orders.status != "cancelled" THEN (order_items.unit_price * order_items.quantity) ELSE 0 END) as revenue,
                COUNT(DISTINCT CASE WHEN orders.status != "cancelled" THEN orders.id ELSE NULL END) as orders
            ')
            ->join('orders', 'order_items.order_id', '=', 'orders.id')
            ->whereBetween('orders.created_at', [$startDate, $endDate])
            ->groupBy('date')
            ->orderBy('date', 'asc')
            ->get();
    }

    /**
     * Get top products by revenue
     */
    public function getTopProducts($period = 'month', $limit = 10)
    {
        $query = OrderItem::selectRaw('
                products.id,
                products.name,
                products.image_url,
                COUNT(order_items.id) as total_quantity,
                SUM(order_items.quantity) as quantity_sold,
                SUM(order_items.unit_price * order_items.quantity) as revenue,
                (SUM(order_items.unit_price * order_items.quantity) - SUM(products.cost_price * order_items.quantity)) as profit
            ')
            ->join('products', 'order_items.product_id', '=', 'products.id')
            ->join('orders', 'order_items.order_id', '=', 'orders.id')
            ->where('orders.status', '!=', 'cancelled');
        
        if ($period == 'day') {
            $query->whereDate('orders.created_at', Carbon::now());
        } elseif ($period == 'week') {
            $query->whereBetween('orders.created_at', [
                Carbon::now()->startOfWeek(),
                Carbon::now()->endOfWeek()
            ]);
        } elseif ($period == 'month') {
            $query->whereBetween('orders.created_at', [
                Carbon::now()->startOfMonth(),
                Carbon::now()->endOfMonth()
            ]);
        } elseif ($period == 'year') {
            $query->whereBetween('orders.created_at', [
                Carbon::now()->startOfYear(),
                Carbon::now()->endOfYear()
            ]);
        }
        
        return $query->groupBy('products.id', 'products.name', 'products.image_url')
            ->orderBy('revenue', 'desc')
            ->take($limit)
            ->get();
    }
}
