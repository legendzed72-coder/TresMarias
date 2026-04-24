<?php
// app/Services/InventoryService.php

namespace App\Services;

use App\Models\Product;
use App\Models\Ingredient;
use App\Models\InventoryLog;
use Illuminate\Support\Facades\DB;

class InventoryService
{
    public function getLowStockAlerts(): array
    {
        $lowStockProducts = Product::whereColumn('stock_quantity', '<=', 'min_stock_level')
            ->where('is_active', true)
            ->get();

        $lowStockIngredients = Ingredient::whereColumn('stock_quantity', '<=', 'min_stock_level')
            ->get();

        return [
            'products' => $lowStockProducts,
            'ingredients' => $lowStockIngredients,
            'total_count' => $lowStockProducts->count() + $lowStockIngredients->count()
        ];
    }

    public function adjustStock(string $type, int $id, float $quantity, string $reason, int $userId): void
    {
        DB::transaction(function () use ($type, $id, $quantity, $reason, $userId) {
            if ($type === 'product') {
                $item = Product::findOrFail($id);
            } else {
                $item = Ingredient::findOrFail($id);
            }

            $oldStock = $item->stock_quantity;
            
            if ($quantity > 0) {
                $item->addStock($quantity, $reason, $userId);
            } else {
                $item->deductStock(abs($quantity), $reason, $userId);
            }

            // Log adjustment
            InventoryLog::create([
                $type . '_id' => $id,
                'type' => 'adjustment',
                'quantity' => abs($quantity),
                'running_stock' => $item->fresh()->stock_quantity,
                'reference_type' => 'adjustment',
                'reason' => "Manual adjustment: {$reason} (Old: {$oldStock})",
                'user_id' => $userId,
            ]);
        });
    }

    public function getStockMovementReport(int $productId, ?string $startDate = null, ?string $endDate = null): array
    {
        $query = InventoryLog::where('product_id', $productId);
        
        if ($startDate) {
            $query->whereDate('created_at', '>=', $startDate);
        }
        if ($endDate) {
            $query->whereDate('created_at', '<=', $endDate);
        }

        $logs = $query->with('user')->orderBy('created_at', 'desc')->get();

        return [
            'product' => Product::find($productId),
            'current_stock' => $logs->first()?->running_stock ?? 0,
            'total_in' => $logs->where('type', 'in')->sum('quantity'),
            'total_out' => $logs->where('type', 'out')->sum('quantity'),
            'logs' => $logs
        ];
    }
}