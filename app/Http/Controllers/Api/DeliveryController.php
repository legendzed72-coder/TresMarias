<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Delivery;
use Illuminate\Http\Request;

class DeliveryController extends Controller
{
    public function index(Request $request)
    {
        $query = Delivery::with('order.user', 'driver');

        if ($request->filled('status')) {
            $query->where('status', $request->status);
        }

        if ($request->filled('search')) {
            $search = $request->search;
            $query->where(function ($q) use ($search) {
                $q->where('recipient_name', 'like', "%{$search}%")
                  ->orWhere('phone', 'like', "%{$search}%")
                  ->orWhere('address', 'like', "%{$search}%")
                  ->orWhere('city', 'like', "%{$search}%");
            });
        }

        return response()->json($query->latest()->get());
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'order_id'       => 'required|exists:orders,id',
            'recipient_name' => 'required|string|max:255',
            'phone'          => 'required|string|max:50',
            'address'        => 'required|string',
            'city'           => 'required|string|max:255',
            'postal_code'    => 'nullable|string|max:20',
            'latitude'       => 'nullable|numeric',
            'longitude'      => 'nullable|numeric',
            'status'         => 'sometimes|in:pending,assigned,picked_up,in_transit,delivered,failed',
            'driver_id'      => 'nullable|exists:users,id',
            'delivery_notes' => 'nullable|string',
        ]);

        $delivery = Delivery::create($validated);
        return response()->json($delivery->load('order.user'), 201);
    }

    public function show(Delivery $delivery)
    {
        return response()->json($delivery->load('order.user', 'driver'));
    }

    public function update(Request $request, Delivery $delivery)
    {
        $validated = $request->validate([
            'recipient_name' => 'sometimes|string|max:255',
            'phone'          => 'sometimes|string|max:50',
            'address'        => 'sometimes|string',
            'city'           => 'sometimes|string|max:255',
            'postal_code'    => 'nullable|string|max:20',
            'status'         => 'sometimes|in:pending,assigned,picked_up,in_transit,delivered,failed',
            'driver_id'      => 'nullable|exists:users,id',
            'delivery_notes' => 'nullable|string',
        ]);

        if ($request->status === 'delivered' && !$delivery->delivered_at) {
            $validated['delivered_at'] = now();
        }

        $delivery->update($validated);
        return response()->json($delivery->load('order.user', 'driver'));
    }

    public function destroy(Delivery $delivery)
    {
        $delivery->delete();
        return response()->json(['message' => 'Delivery deleted']);
    }
}