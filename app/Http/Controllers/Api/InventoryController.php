<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Product;
use App\Models\InventoryLog;
use App\Services\InventoryService;
use Illuminate\Http\Request;

class InventoryController extends Controller
{
    public function alerts()
    {
        $lowStock = Product::where('stock_quantity', '<=', 10)->get();
        return response()->json($lowStock);
    }

    public function adjust(Request $request)
    {
        $request->validate([
            'product_id' => 'required|exists:products,id',
            'quantity' => 'required|integer',
            'reason' => 'required|string',
        ]);

        $inventoryService = new InventoryService();
        $inventoryService->adjustStock('product', $request->product_id, $request->quantity, $request->reason, $request->user()->id);

        return response()->json(['message' => 'Inventory adjusted']);
    }

    public function logs(Request $request)
    {
        $query = InventoryLog::with('product', 'user');

        if ($request->has('product_id')) {
            $query->where('product_id', $request->product_id);
        }

        return response()->json($query->latest()->paginate(50));
    }

    public function report(Request $request)
    {
        $startDate = $request->get('start_date', now()->startOfMonth());
        $endDate = $request->get('end_date', now()->endOfMonth());

        $logs = InventoryLog::whereBetween('created_at', [$startDate, $endDate])
            ->with('product')
            ->get();

        return response()->json($logs);
    }
}