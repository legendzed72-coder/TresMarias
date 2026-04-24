<?php
// app/Services/PosService.php

namespace App\Services;

use App\Models\Order;
use App\Models\OrderItem;
use App\Models\Product;
use App\Models\OfflineTransaction;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Auth;

class PosService
{
    public function createPosOrder(array $data, int $cashierId): Order
    {
        return DB::transaction(function () use ($data, $cashierId) {
            // Create order
            $order = Order::create([
                'type' => 'pos',
                'status' => 'completed',
                'payment_status' => $data['payment_status'] ?? 'paid',
                'payment_method' => $data['payment_method'],
                'cashier_id' => $cashierId,
                'pos_device_id' => $data['pos_device_id'] ?? null,
                'is_offline_synced' => $data['is_offline'] ?? false,
                'subtotal' => 0,
                'tax' => 0,
                'total' => 0,
            ]);

            // Add items
            foreach ($data['items'] as $item) {
                $product = Product::findOrFail($item['product_id']);
                
                OrderItem::create([
                    'order_id' => $order->id,
                    'product_id' => $product->id,
                    'quantity' => $item['quantity'],
                    'unit_price' => $item['unit_price'] ?? $product->price,
                    'subtotal' => ($item['unit_price'] ?? $product->price) * $item['quantity'],
                    'notes' => $item['notes'] ?? null,
                ]);

                // Deduct inventory immediately for POS
                $product->deductStock(
                    $item['quantity'],
                    "POS Sale #{$order->order_number}",
                    $cashierId
                );
            }

            // Calculate totals
            $order->calculateTotals();

            return $order->fresh(['items.product', 'cashier']);
        });
    }

    public function processOfflineSync(array $transactions, int $cashierId): array
    {
        $results = [];

        foreach ($transactions as $transaction) {
            try {
                // Check if already synced
                $existing = OfflineTransaction::where('local_id', $transaction['local_id'])->first();
                
                if ($existing && $existing->status === 'synced') {
                    $results[] = ['local_id' => $transaction['local_id'], 'status' => 'already_synced'];
                    continue;
                }

                DB::beginTransaction();

                // Create the order
                $order = $this->createPosOrder([
                    'payment_method' => $transaction['payment_method'],
                    'payment_status' => $transaction['payment_status'],
                    'pos_device_id' => $transaction['pos_device_id'],
                    'is_offline' => true,
                    'items' => $transaction['items'],
                ], $cashierId);

                // Record offline transaction
                OfflineTransaction::updateOrCreate(
                    ['local_id' => $transaction['local_id']],
                    [
                        'order_id' => $order->id,
                        'payload' => $transaction,
                        'status' => 'synced',
                        'synced_at' => now(),
                    ]
                );

                DB::commit();
                
                $results[] = [
                    'local_id' => $transaction['local_id'],
                    'status' => 'synced',
                    'order_id' => $order->id,
                    'order_number' => $order->order_number
                ];

            } catch (\Exception $e) {
                DB::rollBack();
                
                OfflineTransaction::updateOrCreate(
                    ['local_id' => $transaction['local_id']],
                    [
                        'payload' => $transaction,
                        'status' => 'failed',
                        'sync_error' => $e->getMessage(),
                    ]
                );

                $results[] = [
                    'local_id' => $transaction['local_id'],
                    'status' => 'failed',
                    'error' => $e->getMessage()
                ];
            }
        }

        return $results;
    }

    public function getDailySalesReport(?string $date = null): array
    {
        $date = $date ?? today()->toDateString();
        
        $orders = Order::pos()
            ->whereDate('created_at', $date)
            ->where('payment_status', 'paid')
            ->get();

        return [
            'date' => $date,
            'total_orders' => $orders->count(),
            'total_sales' => $orders->sum('total'),
            'total_items' => $orders->sum(fn($o) => $o->items->sum('quantity')),
            'by_payment_method' => $orders->groupBy('payment_method')
                ->map(fn($g) => [
                    'count' => $g->count(),
                    'amount' => $g->sum('total')
                ]),
            'hourly_breakdown' => $orders->groupBy(fn($o) => $o->created_at->format('H'))
                ->map(fn($g) => $g->sum('total')),
        ];
    }
}