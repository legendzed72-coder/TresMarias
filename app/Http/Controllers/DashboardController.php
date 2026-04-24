<?php

namespace App\Http\Controllers;

use App\Models\Order;
use App\Models\Product;
use App\Models\Category;
use Illuminate\Http\Request;

class DashboardController extends Controller
{
    public function index(Request $request)
    {
        $user = $request->user();

        $recentOrders = Order::where('user_id', $user->id)
            ->with('items.product')
            ->latest()
            ->take(5)
            ->get();

        $orderStats = [
            'total' => Order::where('user_id', $user->id)->count(),
            'pending' => Order::where('user_id', $user->id)->whereIn('status', ['pending', 'confirmed', 'preparing'])->count(),
            'completed' => Order::where('user_id', $user->id)->where('status', 'completed')->count(),
        ];

        $featuredProducts = Product::active()
            ->with('category')
            ->inRandomOrder()
            ->take(6)
            ->get();

        $categories = Category::active()
            ->withCount(['products' => fn($q) => $q->where('is_active', true)])
            ->orderBy('sort_order')
            ->get();

        return view('user.dashboard', compact(
            'recentOrders',
            'orderStats',
            'featuredProducts',
            'categories',
        ));
    }
}
