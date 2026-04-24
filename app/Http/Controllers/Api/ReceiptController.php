<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Order;
use App\Models\Product;
use Illuminate\Http\Request;
use Illuminate\Http\JsonResponse;

class ReceiptController extends Controller
{
    /**
     * Generate order receipt/invoice
     */
    public function orderReceipt(Order $order): JsonResponse
    {
        $receipt = [
            'receipt_number' => 'RCP-' . str_pad($order->id, 6, '0', STR_PAD_LEFT),
            'date' => $order->created_at->format('Y-m-d H:i:s'),
            'customer_name' => $order->user->name ?? 'Guest',
            'customer_email' => $order->user->email ?? 'N/A',
            'order_id' => $order->id,
            'status' => $order->status,
            'items' => $order->items->map(fn($item) => [
                'product_name' => $item->product_name,
                'quantity' => $item->quantity,
                'unit_price' => $item->unit_price,
                'subtotal' => $item->subtotal,
            ]),
            'subtotal' => $order->subtotal,
            'tax' => $order->tax ?? 0,
            'delivery_fee' => $order->delivery_fee ?? 0,
            'total' => $order->total,
            'payment_method' => $order->payment_method,
            'fulfillment_type' => $order->fulfillment_type,
        ];

        return response()->json([
            'success' => true,
            'data' => $receipt
        ]);
    }

    /**
     * Get product transaction history
     */
    public function productHistory(Product $product): JsonResponse
    {
        $transactions = [
            'product' => [
                'id' => $product->id,
                'name' => $product->name,
                'current_stock' => $product->stock_quantity,
            ],
            'recent_sales' => $product->orderItems()
                ->with('order')
                ->latest('created_at')
                ->take(10)
                ->get()
                ->map(fn($item) => [
                    'order_id' => $item->order_id,
                    'quantity' => $item->quantity,
                    'unit_price' => $item->unit_price,
                    'ordered_at' => $item->order->created_at->format('Y-m-d H:i:s'),
                ]),
            'total_sold' => $product->orderItems()->sum('quantity'),
            'revenue' => $product->orderItems()->sum('subtotal'),
        ];

        return response()->json([
            'success' => true,
            'data' => $transactions
        ]);
    }

    /**
     * Get inventory transaction log
     */
    public function inventoryHistory(Product $product): JsonResponse
    {
        $logs = $product->inventoryLogs()
            ->latest('created_at')
            ->get()
            ->map(fn($log) => [
                'type' => $log->transaction_type,
                'quantity_change' => $log->quantity_change,
                'reason' => $log->reason,
                'recorded_at' => $log->created_at->format('Y-m-d H:i:s'),
                'recorded_by' => $log->user->name ?? 'System',
            ]);

        return response()->json([
            'success' => true,
            'data' => $logs
        ]);
    }

    /**
     * Export order receipt as PDF (placeholder)
     */
    public function exportReceipt(Order $order)
    {
        // In production, use a PDF library like dompdf
        return response()->download(
            storage_path('receipts/order_' . $order->id . '.pdf'),
            'receipt_' . $order->id . '.pdf'
        );
    }
}
