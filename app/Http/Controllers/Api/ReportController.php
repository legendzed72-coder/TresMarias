<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Order;
use App\Models\Product;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class ReportController extends Controller
{
    public function sales(Request $request)
    {
        $startDate = $request->input('start_date', now()->startOfMonth());
        $endDate = $request->input('end_date', now()->endOfMonth());

        $sales = Order::whereBetween('created_at', [$startDate, $endDate])
            ->where('status', 'completed')
            ->sum('total');

        $orders = Order::whereBetween('created_at', [$startDate, $endDate])
            ->where('status', 'completed')
            ->count();

        return response()->json([
            'total_sales' => $sales,
            'total_orders' => $orders,
            'average_order' => $orders > 0 ? $sales / $orders : 0,
        ]);
    }

    public function inventory(Request $request)
    {
        $products = Product::select('name', 'stock_quantity', 'min_stock_level')
            ->get()
            ->map(function ($product) {
                return [
                    'name' => $product->name,
                    'stock' => $product->stock_quantity,
                    'min_level' => $product->min_stock_level,
                    'status' => $product->stock_quantity <= $product->min_stock_level ? 'low' : 'ok',
                ];
            });

        return response()->json($products);
    }

    public function dashboard()
    {
        $todaySales = Order::whereDate('created_at', today())
            ->where('status', 'completed')
            ->sum('total');

        $todayOrders = Order::whereDate('created_at', today())
            ->where('status', 'completed')
            ->count();

        $lowStockProducts = Product::where('stock_quantity', '<=', 10)->count();

        $pendingOrders = Order::where('status', 'pending')->count();

        return response()->json([
            'today_sales' => $todaySales,
            'today_orders' => $todayOrders,
            'low_stock_products' => $lowStockProducts,
            'pending_orders' => $pendingOrders,
        ]);
    }
}