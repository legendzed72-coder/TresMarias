<?php

namespace App\Http\Controllers\User;

use App\Http\Controllers\Controller;
use App\Models\Order;
use Illuminate\Http\Request;

class OrderController extends Controller
{
    public function index(Request $request)
    {
        $orders = $request->user()->orders()
            ->with('items.product')
            ->orderBy('created_at', 'desc')
            ->get();

        return view('user.order', compact('orders'));
    }

    public function show(Order $order)
    {
        $user = auth()->user();
        
        // Allow:
        // 1. The customer who placed the order
        // 2. The cashier who created the POS order
        // 3. Admin/staff users viewing any order
        $isOwner = $order->user_id && $order->user_id === $user->id;
        $isCashier = $order->cashier_id && $order->cashier_id === $user->id;
        $isStaffOrAdmin = in_array($user->role, ['staff', 'admin']);
        
        if (!($isOwner || $isCashier || $isStaffOrAdmin)) {
            abort(403);
        }

        $order->load('items.product', 'cashier');

        return view('user.order-detail', compact('order'));
    }

    public function reorder(Order $order)
    {
        // Ensure the order belongs to the authenticated user
        if ($order->user_id !== auth()->id()) {
            abort(403);
        }

        // Create a new preorder with the same items
        $orderData = [
            'items' => $order->items->map(function ($item) {
                return [
                    'product_id' => $item->product_id,
                    'quantity' => $item->quantity,
                ];
            })->toArray(),
            'fulfillment_type' => $order->fulfillment_type,
            'special_instructions' => $order->special_instructions,
        ];

        // Use the API controller to create the order
        $apiController = app(\App\Http\Controllers\Api\OrderController::class);
        $request = new Request($orderData);
        $request->setUserResolver(function () {
            return auth()->user();
        });

        return $apiController->storePreorder($request);
    }
}
