<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Order;
use App\Models\OrderItem;
use App\Models\Product;
use App\Models\User;
use App\Notifications\NewOrderForStaffNotification;
use App\Notifications\OrderPlacedNotification;
use App\Notifications\OrderStatusUpdatedNotification;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Notification;

class OrderController extends Controller
{
    public function myOrders(Request $request)
    {
        return response()->json($request->user()->orders()->with('items.product')->get());
    }

    public function storePreorder(Request $request)
    {
        $request->validate([
            'items' => 'required|array|min:1',
            'items.*.product_id' => 'required|exists:products,id',
            'items.*.quantity' => 'required|integer|min:1',
            'fulfillment_type' => 'nullable|string|in:pickup,delivery',
            'payment_method' => 'nullable|string|in:cod,online,cash,card,gcash,maya',
            'scheduled_at' => 'nullable|date',
            'special_instructions' => 'nullable|string|max:500',
        ]);

        $order = DB::transaction(function () use ($request) {
            // Calculate totals server-side
            $subtotal = 0;
            $itemsData = [];
            foreach ($request->items as $item) {
                $product = Product::findOrFail($item['product_id']);
                $price = $product->price;
                $qty = $item['quantity'];
                $subtotal += $price * $qty;
                $itemsData[] = [
                    'product_id' => $product->id,
                    'quantity' => $qty,
                    'unit_price' => $price,
                    'subtotal' => $price * $qty,
                ];
            }

            $deliveryFee = $request->fulfillment_type === 'delivery' ? 50.00 : 0;

            $order = Order::create([
                'user_id' => $request->user()->id,
                'type' => 'preorder',
                'status' => 'pending',
                'fulfillment_type' => $request->fulfillment_type ?? 'pickup',
                'scheduled_at' => $request->scheduled_at,
                'special_instructions' => $request->special_instructions,
                'subtotal' => $subtotal,
                'delivery_fee' => $deliveryFee,
                'total' => $subtotal + $deliveryFee,
            ]);

            foreach ($itemsData as $item) {
                OrderItem::create(array_merge($item, ['order_id' => $order->id]));
            }

            return $order;
        });

        // Notify the customer
        $request->user()->notify(new OrderPlacedNotification($order));

        // Notify all staff and admin users
        $staffAndAdmins = User::whereIn('role', ['staff', 'admin'])->get();
        Notification::send($staffAndAdmins, new NewOrderForStaffNotification($order));

        return response()->json([
            'message' => 'Preorder created successfully',
            'order_number' => $order->order_number,
        ], 201);
    }

    public function show(Order $order)
    {
        return response()->json($order->load('items.product'));
    }

    public function trackByNumber($orderNumber)
    {
        $order = Order::with([
            'items.product',
            'delivery.driver',
            'user'
        ])->where('order_number', $orderNumber)->first();

        if (!$order) {
            return response()->json([
                'message' => 'Order not found',
                'order_number' => $orderNumber
            ], 404);
        }

        // Format the response with delivery info
        return response()->json([
            'data' => [
                'id' => $order->id,
                'order_number' => $order->order_number,
                'status' => $order->status,
                'total' => $order->total,
                'created_at' => $order->created_at,
                'items' => $order->items->map(fn($item) => [
                    'id' => $item->id,
                    'quantity' => $item->quantity,
                    'product' => [
                        'name' => $item->product->name,
                    ],
                    'subtotal' => $item->subtotal,
                ]),
                'delivery' => $order->delivery ? [
                    'recipient_name' => $order->delivery->recipient_name,
                    'phone' => $order->delivery->phone,
                    'address' => $order->delivery->address,
                    'city' => $order->delivery->city,
                    'postal_code' => $order->delivery->postal_code,
                    'latitude' => $order->delivery->latitude,
                    'longitude' => $order->delivery->longitude,
                    'status' => $order->delivery->status,
                ] : null,
                'driver' => $order->delivery?->driver ? [
                    'name' => $order->delivery->driver->name,
                    'phone' => $order->delivery->driver->phone ?? 'N/A',
                    'email' => $order->delivery->driver->email,
                ] : null,
            ]
        ]);
    }

    public function index(Request $request)
    {
        $query = Order::with('items.product', 'user');

        if ($request->has('status')) {
            $query->where('status', $request->status);
        }

        if ($request->has('payment_method')) {
            $query->where('payment_method', $request->payment_method);
        }

        if ($request->has('search')) {
            $search = $request->search;
            $query->where(function ($q) use ($search) {
                $q->where('order_number', 'like', "%$search%")
                  ->orWhere('id', 'like', "%$search%")
                  ->orWhereHas('user', function ($q) use ($search) {
                      $q->where('name', 'like', "%$search%")
                        ->orWhere('email', 'like', "%$search%");
                  });
            });
        }

        return response()->json($query->latest()->get());
    }

    public function updateStatus(Request $request, Order $order)
    {
        $request->validate([
            'status' => 'required|in:pending,confirmed,preparing,ready,out_for_delivery,completed,cancelled',
        ]);

        $oldStatus = $order->status;
        $order->update(['status' => $request->status]);

        // Notify the customer about status change
        if ($order->user) {
            $order->user->notify(new OrderStatusUpdatedNotification($order, $oldStatus));
        }

        return response()->json($order);
    }

    public function todayPreorders()
    {
        return response()->json(
            Order::where('status', 'preorder')
                ->whereDate('created_at', today())
                ->with('items.product', 'user')
                ->get()
        );
    }

    public function quickOrder(Request $request)
    {
        $request->validate([
            'items' => 'required|array|min:1',
            'items.*.product_id' => 'required|exists:products,id',
            'items.*.quantity' => 'required|integer|min:1',
            'fulfillment_type' => 'nullable|string|in:pickup,delivery',
            'payment_method' => 'nullable|string|in:cod,online,cash,card,gcash,maya',
            'special_instructions' => 'nullable|string|max:500',
            'customer_id' => 'nullable|exists:users,id', // For admin-placed orders
            'placed_by_admin' => 'nullable|boolean',
        ]);

        $order = DB::transaction(function () use ($request) {
            $subtotal = 0;
            $itemsData = [];
            
            foreach ($request->items as $item) {
                $product = Product::findOrFail($item['product_id']);
                
                // Check stock
                if ($product->stock_quantity < $item['quantity']) {
                    throw new \Exception("Not enough stock for {$product->name}. Available: {$product->stock_quantity}");
                }
                
                $price = $product->price;
                $qty = $item['quantity'];
                $subtotal += $price * $qty;
                $itemsData[] = [
                    'product_id' => $product->id,
                    'quantity' => $qty,
                    'unit_price' => $price,
                    'subtotal' => $price * $qty,
                ];
            }

            $deliveryFee = $request->fulfillment_type === 'delivery' ? 50.00 : 0;
            
            // Calculate tax (12% VAT)
            $tax = $subtotal * 0.12;
            $total = $subtotal + $tax + $deliveryFee;

            // Determine user ID - admin can place orders on behalf of customers
            $userId = $request->customer_id ?? $request->user()->id;

            $order = Order::create([
                'user_id' => $userId,
                'type' => 'order',
                'status' => 'pending',
                'payment_method' => $request->payment_method ?? 'cash',
                'payment_status' => 'pending',
                'fulfillment_type' => $request->fulfillment_type ?? 'pickup',
                'special_instructions' => $request->special_instructions,
                'subtotal' => $subtotal,
                'tax' => $tax,
                'delivery_fee' => $deliveryFee,
                'total' => $total,
            ]);

            foreach ($itemsData as $item) {
                OrderItem::create(array_merge($item, ['order_id' => $order->id]));
            }

            // Deduct stock
            foreach ($request->items as $item) {
                $product = Product::findOrFail($item['product_id']);
                $product->deductStock($item['quantity'], 'Order created by admin', $request->user()->id);
            }

            return $order;
        });

        // Notify the customer
        $customer = User::find($order->user_id);
        if ($customer) {
            $customer->notify(new OrderPlacedNotification($order));
        }

        // Notify all staff and admin users
        $staffAndAdmins = User::whereIn('role', ['staff', 'admin'])->get();
        Notification::send($staffAndAdmins, new NewOrderForStaffNotification($order));

        return response()->json([
            'message' => 'Order created successfully',
            'order_number' => $order->order_number,
            'order' => $order->load('items.product')
        ], 201);
    }
}