<?php
// app/Http/Controllers/Api/PosController.php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Services\PosService;
use App\Models\Category;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class PosController extends Controller
{
    protected $posService;

    public function __construct(PosService $posService)
    {
        $this->posService = $posService;
    }

    public function products(Request $request)
    {
        $products = \App\Models\Product::active()
            ->with('category')
            ->when($request->category_id, fn($q) => $q->where('category_id', $request->category_id))
            ->get()
            ->map(fn($p) => [
                'id' => $p->id,
                'name' => $p->name,
                'price' => $p->price,
                'stock' => $p->stock_quantity,
                'category' => $p->category->name,
                'image' => $p->image_url,
            ]);

        return response()->json([
            'products' => $products,
            'categories' => \App\Models\Category::active()->get(['id', 'name'])
        ]);
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'items' => 'required|array|min:1',
            'items.*.product_id' => 'required|exists:products,id',
            'items.*.quantity' => 'required|integer|min:1',
            'items.*.unit_price' => 'nullable|numeric',
            'items.*.notes' => 'nullable|string',
            'payment_method' => 'required|in:cod,online,cash,card,gcash,maya',
            'payment_status' => 'required|in:paid,pending',
            'pos_device_id' => 'nullable|string',
        ]);

        try {
            $order = $this->posService->createPosOrder($validated, Auth::id());
            
            return response()->json([
                'success' => true,
                'order' => $order,
                'receipt' => [
                    'order_number' => $order->order_number,
                    'items' => $order->items,
                    'total' => $order->total,
                    'payment_method' => $order->payment_method,
                    'cashier' => $order->cashier->name,
                    'created_at' => $order->created_at->format('Y-m-d H:i:s'),
                ]
            ], 201);

        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => $e->getMessage()
            ], 500);
        }
    }

    public function syncOffline(Request $request)
    {
        $validated = $request->validate([
            'transactions' => 'required|array',
            'transactions.*.local_id' => 'required|string',
            'transactions.*.items' => 'required|array',
            'transactions.*.payment_method' => 'required|string',
        ]);

        $results = $this->posService->processOfflineSync(
            $validated['transactions'], 
            Auth::id()
        );

        return response()->json([
            'synced' => collect($results)->where('status', 'synced')->count(),
            'failed' => collect($results)->where('status', 'failed')->count(),
            'results' => $results
        ]);
    }

    public function dailyReport(Request $request)
    {
        $date = $request->date ?? now()->toDateString();
        $report = $this->posService->getDailySalesReport($date);
        
        return response()->json($report);
    }
}