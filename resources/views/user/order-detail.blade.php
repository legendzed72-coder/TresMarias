<x-app-layout>
    <x-slot name="header">
        <div class="flex items-center justify-between">
            <div>
                <h2 class="font-serif font-bold text-2xl text-bark-600 leading-tight">
                    {{ __('Order Details') }}
                </h2>
                <p class="mt-1 text-sm text-muted">Order #{{ $order->order_number }}</p>
            </div>
            <a href="{{ route('my-orders') }}" class="px-4 py-2 text-bark-600 hover:text-bark-700 text-sm font-semibold transition">
                ← Back to Orders
            </a>
        </div>
    </x-slot>

    <div class="py-8 bg-cream-50 min-h-screen">
        <div class="max-w-4xl mx-auto px-4 sm:px-6 lg:px-8 space-y-6">

            {{-- Order Header --}}
            <div class="bg-gradient-to-r from-bark-300 to-bark-400 rounded-xl p-6 text-cream-50">
                <div class="flex items-center justify-between">
                    <div>
                        <p class="text-sm opacity-90">{{ __('Order Number') }}</p>
                        <p class="text-2xl font-bold">{{ $order->order_number }}</p>
                    </div>
                    <div class="text-right">
                        <p class="text-sm opacity-90">{{ __('Total Amount') }}</p>
                        <p class="text-2xl font-bold">₱{{ number_format($order->total, 2) }}</p>
                    </div>
                </div>
            </div>

            {{-- Order Status --}}
            <div class="bg-white rounded-xl p-6 shadow-sm border border-cream-200">
                <div class="grid grid-cols-1 md:grid-cols-4 gap-4">
                    <div>
                        <p class="text-sm text-muted">Order Number</p>
                        <p class="text-lg font-bold text-bark-600">{{ $order->order_number }}</p>
                    </div>
                    <div>
                        <p class="text-sm text-muted">Order Date</p>
                        <p class="text-lg font-bold text-bark-600">{{ $order->created_at->format('M d, Y') }}</p>
                    </div>
                    <div>
                        <p class="text-sm text-muted">Order Type</p>
                        <span class="inline-block px-3 py-1 rounded-full text-xs font-semibold bg-blue-100 text-blue-700">
                            {{ ucfirst($order->type) }}
                        </span>
                    </div>
                    <div>
                        <p class="text-sm text-muted">Status</p>
                        <span class="inline-block px-3 py-1 rounded-full text-xs font-semibold
                            @if($order->status === 'completed')
                                bg-leaf-100 text-leaf-700
                            @elseif($order->status === 'pending')
                                bg-yellow-100 text-yellow-700
                            @elseif($order->status === 'cancelled')
                                bg-red-100 text-red-700
                            @else
                                bg-blue-100 text-blue-700
                            @endif">
                            {{ ucfirst($order->status) }}
                        </span>
                    </div>
                </div>
            </div>

            {{-- Order Items --}}
            <div class="bg-white rounded-xl overflow-hidden shadow-sm border border-cream-200">
                <div class="px-6 py-4 border-b border-cream-200 bg-cream-50">
                    <h3 class="font-bold text-bark-600">Order Items</h3>
                </div>
                <div class="divide-y divide-cream-200">
                    @foreach($order->items as $item)
                        <div class="p-6">
                            <div class="flex justify-between items-start">
                                <div class="flex-1">
                                    <h4 class="font-semibold text-bark-600">{{ $item->product?->name ?? 'Unknown Product' }}</h4>
                                    <p class="text-sm text-muted">Quantity: {{ $item->quantity }}</p>
                                </div>
                                <div class="text-right">
                                    <p class="font-semibold text-bark-600">₱{{ number_format($item->unit_price, 2) }}</p>
                                    <p class="text-sm text-muted">Subtotal: ₱{{ number_format($item->subtotal, 2) }}</p>
                                </div>
                            </div>
                            @if($item->notes)
                                <p class="text-sm text-muted mt-2 italic">Notes: {{ $item->notes }}</p>
                            @endif
                        </div>
                    @endforeach
                </div>
            </div>

            {{-- Order Summary --}}
            <div class="bg-white rounded-xl p-6 shadow-sm border border-cream-200">
                <h3 class="font-bold text-bark-600 mb-4">Order Summary</h3>
                <div class="space-y-2">
                    <div class="flex justify-between">
                        <span class="text-muted">Subtotal:</span>
                        <span class="font-semibold">₱{{ number_format($order->subtotal, 2) }}</span>
                    </div>
                    <div class="flex justify-between">
                        <span class="text-muted">Tax (12%):</span>
                        <span class="font-semibold">₱{{ number_format($order->tax, 2) }}</span>
                    </div>
                    @if($order->delivery_fee)
                        <div class="flex justify-between">
                            <span class="text-muted">Delivery Fee:</span>
                            <span class="font-semibold">₱{{ number_format($order->delivery_fee, 2) }}</span>
                        </div>
                    @endif
                    <div class="border-t border-cream-200 pt-2 flex justify-between">
                        <span class="font-bold text-bark-600">Total:</span>
                        <span class="font-bold text-lg text-bark-600">₱{{ number_format($order->total, 2) }}</span>
                    </div>
                </div>
            </div>

            {{-- Payment & Fulfillment Info --}}
            <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                <div class="bg-white rounded-xl p-6 shadow-sm border border-cream-200">
                    <h3 class="font-bold text-bark-600 mb-3">Payment Information</h3>
                    <p class="text-sm text-muted">Payment Method</p>
                    <p class="font-semibold text-bark-600 mb-4">{{ ucfirst(str_replace('_', ' ', $order->payment_method)) }}</p>
                    <p class="text-sm text-muted">Payment Status</p>
                    <p class="font-semibold text-bark-600">{{ ucfirst($order->payment_status) }}</p>
                </div>

                <div class="bg-white rounded-xl p-6 shadow-sm border border-cream-200">
                    <h3 class="font-bold text-bark-600 mb-3">
                        @if($order->type === 'pos')
                            POS Transaction
                        @else
                            Fulfillment Information
                        @endif
                    </h3>
                    @if($order->type === 'pos' && $order->cashier)
                        <p class="text-sm text-muted">Processed By</p>
                        <p class="font-semibold text-bark-600 mb-2">{{ $order->cashier->name }}</p>
                        <p class="text-sm text-muted">Device ID</p>
                        <p class="font-semibold text-bark-600">{{ $order->pos_device_id ?? 'N/A' }}</p>
                    @else
                        <p class="text-sm text-muted">Fulfillment Type</p>
                        <p class="font-semibold text-bark-600">{{ ucfirst($order->fulfillment_type) }}</p>
                    @endif
                </div>
            </div>

            {{-- Actions --}}
            <div class="flex gap-3">
                <button onclick="window.print()" class="flex-1 px-4 py-2 bg-bark-300 text-cream-50 rounded-lg hover:bg-bark-400 font-semibold transition">
                    Print Receipt
                </button>
                @if($order->type !== 'pos')
                    <form action="{{ route('orders.reorder', $order) }}" method="POST" class="flex-1">
                        @csrf
                        <button type="submit" class="w-full px-4 py-2 bg-leaf-500 text-white rounded-lg hover:bg-leaf-600 font-semibold transition">
                            Reorder
                        </button>
                    </form>
                @else
                    <a href="{{ route('staff.pos') }}" class="flex-1 px-4 py-2 bg-leaf-500 text-white rounded-lg hover:bg-leaf-600 font-semibold transition text-center">
                        Back to POS
                    </a>
                @endif
            </div>
        </div>
    </div>
</x-app-layout>
