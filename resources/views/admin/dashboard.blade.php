<x-app-layout>
    <x-slot name="header">
        <div class="flex items-center justify-between">
            <div>
                <h2 class="font-serif font-bold text-2xl text-bark-600 leading-tight">
                    {{ __('Admin Dashboard') }}
                </h2>
                <p class="mt-1 text-sm text-muted">{{ now()->format('l, F j, Y') }}</p>
            </div>
            <div class="flex items-center gap-3">
                <a href="{{ route('admin.products') }}"
                   class="inline-flex items-center gap-2 px-4 py-2 bg-cream-50 ring-1 ring-bark-200/15 hover:bg-cream-200/50 text-bark-500 font-semibold text-sm rounded-xl transition duration-200">
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M20 7l-8-4-8 4m16 0l-8 4m8-4v10l-8 4m0-10L4 7m8 4v10M4 7v10l8 4"/>
                    </svg>
                    {{ __('Products') }}
                </a>
                <a href="{{ route('admin.reports') }}"
                   class="inline-flex items-center gap-2 px-4 py-2 bg-bark-300 hover:bg-bark-400 text-cream-50 font-semibold text-sm rounded-xl shadow-sm transition duration-200">
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M3 3v18h18M9 17V9m4 8v-5m4 5V5"/>
                    </svg>
                    {{ __('Reports') }}
                </a>
            </div>
        </div>
    </x-slot>

    <div class="flex">
        {{-- Sidebar --}}
        <x-admin-sidebar />

        {{-- Main Content --}}
        <div class="flex-1 py-8">
        <div class="max-w-6xl mx-auto px-4 sm:px-6 lg:px-8 space-y-8">

            {{-- KPI Summary Cards --}}
            <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-5">

                {{-- Today's Sales --}}
                <div class="bg-cream-50 rounded-2xl ring-1 ring-bark-200/10 shadow-sm shadow-bark-200/5 p-6">
                    <div class="flex items-center gap-4">
                        <div class="flex-shrink-0 w-12 h-12 rounded-xl bg-leaf-300/30 flex items-center justify-center">
                            <svg class="w-6 h-6 text-leaf-500" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" d="M12 6v12m-3-2.818l.879.659c1.171.879 3.07.879 4.242 0 1.172-.879 1.172-2.303 0-3.182C13.536 12.219 12.768 12 12 12c-.725 0-1.45-.22-2.003-.659-1.106-.879-1.106-2.303 0-3.182s2.9-.879 4.006 0l.415.33M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/>
                            </svg>
                        </div>
                        <div>
                            <p class="text-sm font-medium text-muted">{{ __("Today's Sales") }}</p>
                            <p class="text-2xl font-bold text-bark-600">₱{{ number_format($todaySales, 2) }}</p>
                            <p class="text-xs text-muted mt-0.5">{{ $todayOrders }} {{ __('orders') }}</p>
                        </div>
                    </div>
                </div>

                {{-- Monthly Sales --}}
                <div class="bg-cream-50 rounded-2xl ring-1 ring-bark-200/10 shadow-sm shadow-bark-200/5 p-6">
                    <div class="flex items-center gap-4">
                        <div class="flex-shrink-0 w-12 h-12 rounded-xl bg-bark-100 flex items-center justify-center">
                            <svg class="w-6 h-6 text-bark-400" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" d="M2.25 18L9 11.25l4.306 4.307a11.95 11.95 0 015.814-5.519l2.74-1.22m0 0l-5.94-2.28m5.94 2.28l-2.28 5.941"/>
                            </svg>
                        </div>
                        <div>
                            <p class="text-sm font-medium text-muted">{{ __('This Month') }}</p>
                            <p class="text-2xl font-bold text-bark-600">₱{{ number_format($monthSales, 2) }}</p>
                            <p class="text-xs mt-0.5">
                                @if($lastMonthSales > 0)
                                    @php $change = (($monthSales - $lastMonthSales) / $lastMonthSales) * 100; @endphp
                                    <span class="{{ $change >= 0 ? 'text-leaf-500' : 'text-red-500' }}">
                                        {{ $change >= 0 ? '+' : '' }}{{ number_format($change, 1) }}%
                                    </span>
                                    <span class="text-muted">{{ __('vs last month') }}</span>
                                @else
                                    <span class="text-muted">{{ $monthOrders }} {{ __('orders') }}</span>
                                @endif
                            </p>
                        </div>
                    </div>
                </div>

                {{-- Pending Orders --}}
                <div class="bg-cream-50 rounded-2xl ring-1 ring-bark-200/10 shadow-sm shadow-bark-200/5 p-6">
                    <div class="flex items-center gap-4">
                        <div class="flex-shrink-0 w-12 h-12 rounded-xl bg-gold-300/30 flex items-center justify-center">
                            <svg class="w-6 h-6 text-gold-500" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"/>
                            </svg>
                        </div>
                        <div>
                            <p class="text-sm font-medium text-muted">{{ __('Pending Orders') }}</p>
                            <p class="text-2xl font-bold text-bark-600">{{ $pendingOrders }}</p>
                            <p class="text-xs text-muted mt-0.5">{{ $activeOrders }} {{ __('in progress') }}</p>
                        </div>
                    </div>
                </div>

                {{-- Low Stock Alert --}}
                <div class="bg-cream-50 rounded-2xl ring-1 ring-bark-200/10 shadow-sm shadow-bark-200/5 p-6">
                    <div class="flex items-center gap-4">
                        <div class="flex-shrink-0 w-12 h-12 rounded-xl {{ $lowStockCount > 0 ? 'bg-red-100' : 'bg-leaf-300/30' }} flex items-center justify-center">
                            <svg class="w-6 h-6 {{ $lowStockCount > 0 ? 'text-red-500' : 'text-leaf-500' }}" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" d="M12 9v3.75m-9.303 3.376c-.866 1.5.217 3.374 1.948 3.374h14.71c1.73 0 2.813-1.874 1.948-3.374L13.949 3.378c-.866-1.5-3.032-1.5-3.898 0L2.697 16.126zM12 15.75h.007v.008H12v-.008z"/>
                            </svg>
                        </div>
                        <div>
                            <p class="text-sm font-medium text-muted">{{ __('Low Stock') }}</p>
                            <p class="text-2xl font-bold text-bark-600">{{ $lowStockCount }}</p>
                            <p class="text-xs mt-0.5">
                                <span class="{{ $lowStockCount > 0 ? 'text-red-500' : 'text-leaf-500' }}">
                                    {{ $lowStockCount > 0 ? __('Needs attention') : __('All stocked') }}
                                </span>
                            </p>
                        </div>
                    </div>
                </div>
            </div>

            {{-- Sales Chart --}}
            <div class="bg-cream-50 rounded-2xl ring-1 ring-bark-200/10 shadow-sm shadow-bark-200/5 overflow-hidden">
                <div class="px-6 py-5 border-b border-bark-200/10">
                    <h3 class="font-serif font-bold text-lg text-bark-600">{{ __('Sales Overview — Last 7 Days') }}</h3>
                </div>
                <div class="p-6">
                    <canvas id="salesChart" height="100"></canvas>
                </div>
            </div>

            <div class="grid grid-cols-1 lg:grid-cols-3 gap-8">

                {{-- Recent Orders --}}
                <div class="lg:col-span-2">
                    <div class="bg-cream-50 rounded-2xl ring-1 ring-bark-200/10 shadow-sm shadow-bark-200/5 overflow-hidden">
                        <div class="px-6 py-5 border-b border-bark-200/10 flex items-center justify-between">
                            <h3 class="font-serif font-bold text-lg text-bark-600">{{ __('Recent Orders') }}</h3>
                            <span class="text-xs font-semibold text-muted bg-cream-300/60 px-2.5 py-1 rounded-full">
                                {{ __('Latest 10') }}
                            </span>
                        </div>

                        @if($recentOrders->isEmpty())
                            <div class="p-10 text-center">
                                <svg class="mx-auto w-12 h-12 text-bark-200" fill="none" stroke="currentColor" stroke-width="1.5" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" d="M2.25 3h1.386c.51 0 .955.343 1.087.835l.383 1.437M7.5 14.25a3 3 0 00-3 3h15.75m-12.75-3h11.218c1.121-2.3 2.1-4.684 2.924-7.138a60.114 60.114 0 00-16.536-1.84M7.5 14.25L5.106 5.272M6 20.25a.75.75 0 11-1.5 0 .75.75 0 011.5 0zm12.75 0a.75.75 0 11-1.5 0 .75.75 0 011.5 0z"/>
                                </svg>
                                <p class="mt-3 text-muted font-medium">{{ __('No orders yet') }}</p>
                            </div>
                        @else
                            <div class="overflow-x-auto">
                                <table class="w-full text-sm">
                                    <thead>
                                        <tr class="bg-cream-200/40">
                                            <th class="px-6 py-3 text-left font-semibold text-muted text-xs uppercase tracking-wider">{{ __('Order') }}</th>
                                            <th class="px-6 py-3 text-left font-semibold text-muted text-xs uppercase tracking-wider">{{ __('Customer') }}</th>
                                            <th class="px-6 py-3 text-left font-semibold text-muted text-xs uppercase tracking-wider">{{ __('Type') }}</th>
                                            <th class="px-6 py-3 text-left font-semibold text-muted text-xs uppercase tracking-wider">{{ __('Status') }}</th>
                                            <th class="px-6 py-3 text-right font-semibold text-muted text-xs uppercase tracking-wider">{{ __('Total') }}</th>
                                            <th class="px-6 py-3 text-right font-semibold text-muted text-xs uppercase tracking-wider">{{ __('Date') }}</th>
                                        </tr>
                                    </thead>
                                    <tbody class="divide-y divide-bark-200/10">
                                        @foreach($recentOrders as $order)
                                            <tr class="hover:bg-cream-100/50 transition duration-150">
                                                <td class="px-6 py-3.5">
                                                    <span class="font-semibold text-bark-600">{{ $order->order_number }}</span>
                                                </td>
                                                <td class="px-6 py-3.5 text-muted">
                                                    {{ $order->user?->name ?? __('Walk-in') }}
                                                </td>
                                                <td class="px-6 py-3.5">
                                                    <span class="inline-flex items-center px-2 py-0.5 rounded-full text-xs font-semibold {{ $order->type === 'pos' ? 'bg-bark-100 text-bark-400' : 'bg-gold-300/30 text-gold-500' }}">
                                                        {{ strtoupper($order->type) }}
                                                    </span>
                                                </td>
                                                <td class="px-6 py-3.5">
                                                    @php
                                                        $statusColors = [
                                                            'pending'          => 'bg-gold-300/30 text-gold-500',
                                                            'confirmed'        => 'bg-bark-100 text-bark-400',
                                                            'preparing'        => 'bg-gold-300/30 text-gold-500',
                                                            'ready'            => 'bg-leaf-300/30 text-leaf-500',
                                                            'out_for_delivery' => 'bg-bark-100 text-bark-300',
                                                            'completed'        => 'bg-leaf-300/30 text-leaf-500',
                                                            'cancelled'        => 'bg-red-100 text-red-600',
                                                        ];
                                                        $color = $statusColors[$order->status] ?? 'bg-bark-100 text-bark-400';
                                                    @endphp
                                                    <span class="inline-flex items-center px-2 py-0.5 rounded-full text-xs font-semibold {{ $color }}">
                                                        {{ ucfirst(str_replace('_', ' ', $order->status)) }}
                                                    </span>
                                                </td>
                                                <td class="px-6 py-3.5 text-right font-bold text-bark-600">
                                                    ₱{{ number_format($order->total, 2) }}
                                                </td>
                                                <td class="px-6 py-3.5 text-right text-xs text-muted">
                                                    {{ $order->created_at->diffForHumans() }}
                                                </td>
                                            </tr>
                                        @endforeach
                                    </tbody>
                                </table>
                            </div>
                        @endif
                    </div>
                </div>

                {{-- Sidebar --}}
                <div class="space-y-6">

                    {{-- Quick Stats --}}
                    <div class="bg-cream-50 rounded-2xl ring-1 ring-bark-200/10 shadow-sm shadow-bark-200/5 p-6">
                        <h3 class="font-serif font-bold text-lg text-bark-600 mb-4">{{ __('Overview') }}</h3>
                        <div class="space-y-3">
                            <div class="flex items-center justify-between px-3 py-2.5 rounded-xl bg-cream-200/40">
                                <span class="text-sm font-medium text-muted">{{ __('Active Products') }}</span>
                                <span class="text-sm font-bold text-bark-600">{{ $totalProducts }}</span>
                            </div>
                            <div class="flex items-center justify-between px-3 py-2.5 rounded-xl bg-cream-200/40">
                                <span class="text-sm font-medium text-muted">{{ __('Categories') }}</span>
                                <span class="text-sm font-bold text-bark-600">{{ $totalCategories }}</span>
                            </div>
                            <div class="flex items-center justify-between px-3 py-2.5 rounded-xl bg-cream-200/40">
                                <span class="text-sm font-medium text-muted">{{ __('Customers') }}</span>
                                <span class="text-sm font-bold text-bark-600">{{ $totalCustomers }}</span>
                            </div>
                        </div>
                    </div>

                    {{-- Top Products --}}
                    <div class="bg-cream-50 rounded-2xl ring-1 ring-bark-200/10 shadow-sm shadow-bark-200/5 p-6">
                        <h3 class="font-serif font-bold text-lg text-bark-600 mb-4">{{ __('Top Products') }}</h3>
                        @if($topProducts->isEmpty())
                            <p class="text-sm text-muted text-center py-4">{{ __('No sales this month yet') }}</p>
                        @else
                            <div class="space-y-3">
                                @foreach($topProducts as $product)
                                    <div class="flex items-center justify-between gap-3">
                                        <div class="min-w-0">
                                            <p class="text-sm font-semibold text-bark-600 truncate">{{ $product->name }}</p>
                                            <p class="text-xs text-muted">{{ $product->total_sold }} {{ __('sold') }}</p>
                                        </div>
                                        <span class="text-sm font-bold text-bark-300 flex-shrink-0">
                                            ₱{{ number_format($product->total_revenue, 2) }}
                                        </span>
                                    </div>
                                @endforeach
                            </div>
                        @endif
                    </div>

                    {{-- Admin Quick Links --}}
                    <div class="bg-cream-50 rounded-2xl ring-1 ring-bark-200/10 shadow-sm shadow-bark-200/5 p-6">
                        <h3 class="font-serif font-bold text-lg text-bark-600 mb-4">{{ __('Management') }}</h3>
                        <div class="space-y-3">
                            <a href="{{ route('admin.products') }}"
                               class="flex items-center gap-3 p-3 rounded-xl bg-cream-200/50 hover:bg-cream-300/50 transition duration-150 group">
                                <div class="w-10 h-10 rounded-lg bg-bark-300 flex items-center justify-center flex-shrink-0">
                                    <svg class="w-5 h-5 text-cream-50" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" d="M20 7l-8-4-8 4m16 0l-8 4m8-4v10l-8 4m0-10L4 7m8 4v10M4 7v10l8 4"/>
                                    </svg>
                                </div>
                                <div class="min-w-0">
                                    <p class="text-sm font-semibold text-bark-600 group-hover:text-bark-500">{{ __('Products') }}</p>
                                    <p class="text-xs text-muted">{{ __('Manage inventory & pricing') }}</p>
                                </div>
                            </a>

                            <a href="{{ route('admin.reports') }}"
                               class="flex items-center gap-3 p-3 rounded-xl bg-cream-200/50 hover:bg-cream-300/50 transition duration-150 group">
                                <div class="w-10 h-10 rounded-lg bg-bark-400 flex items-center justify-center flex-shrink-0">
                                    <svg class="w-5 h-5 text-cream-50" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" d="M3 3v18h18M9 17V9m4 8v-5m4 5V5"/>
                                    </svg>
                                </div>
                                <div class="min-w-0">
                                    <p class="text-sm font-semibold text-bark-600 group-hover:text-bark-500">{{ __('Reports') }}</p>
                                    <p class="text-xs text-muted">{{ __('Sales & inventory reports') }}</p>
                                </div>
                            </a>

                            <a href="{{ route('admin.customers.index') }}"
                               class="flex items-center gap-3 p-3 rounded-xl bg-cream-200/50 hover:bg-cream-300/50 transition duration-150 group">
                                <div class="w-10 h-10 rounded-lg bg-leaf-500 flex items-center justify-center flex-shrink-0">
                                    <svg class="w-5 h-5 text-cream-50" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" d="M15 19.128a9.38 9.38 0 002.625.372 9.337 9.337 0 004.121-.952 4.125 4.125 0 00-7.533-2.493M15 19.128v-.003c0-1.113-.285-2.16-.786-3.07M15 19.128v.106A12.318 12.318 0 018.624 21c-2.331 0-4.512-.645-6.374-1.766l-.001-.109a6.375 6.375 0 0111.964-3.07M12 6.375a3.375 3.375 0 11-6.75 0 3.375 3.375 0 016.75 0zm8.25 2.25a2.625 2.625 0 11-5.25 0 2.625 2.625 0 015.25 0z"/>
                                    </svg>
                                </div>
                                <div class="min-w-0">
                                    <p class="text-sm font-semibold text-bark-600 group-hover:text-bark-500">{{ __('Customers') }}</p>
                                    <p class="text-xs text-muted">{{ __('Customer analytics & profiles') }}</p>
                                </div>
                            </a>
                        </div>
                    </div>
                </div>
            </div>

            {{-- Low Stock & Pending Deliveries --}}
            <div class="grid grid-cols-1 lg:grid-cols-2 gap-8">

                {{-- Low Stock Products --}}
                <div class="bg-cream-50 rounded-2xl ring-1 ring-bark-200/10 shadow-sm shadow-bark-200/5 overflow-hidden">
                    <div class="px-6 py-5 border-b border-bark-200/10 flex items-center justify-between">
                        <h3 class="font-serif font-bold text-lg text-bark-600">{{ __('Low Stock Alert') }}</h3>
                        @if($lowStockCount > 0)
                            <span class="inline-flex items-center px-2.5 py-1 rounded-full text-xs font-semibold bg-red-100 text-red-600">
                                {{ $lowStockCount }} {{ __('items') }}
                            </span>
                        @endif
                    </div>

                    @if($lowStockProducts->isEmpty())
                        <div class="p-10 text-center">
                            <svg class="mx-auto w-12 h-12 text-leaf-300" fill="none" stroke="currentColor" stroke-width="1.5" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" d="M9 12.75L11.25 15 15 9.75M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/>
                            </svg>
                            <p class="mt-3 text-muted font-medium">{{ __('All products are well stocked!') }}</p>
                        </div>
                    @else
                        <div class="divide-y divide-bark-200/10">
                            @foreach($lowStockProducts as $product)
                                <div class="px-6 py-3.5 flex items-center justify-between hover:bg-cream-100/50 transition duration-150">
                                    <div class="min-w-0">
                                        <p class="text-sm font-semibold text-bark-600 truncate">{{ $product->name }}</p>
                                        <p class="text-xs text-muted">{{ $product->category?->name }}</p>
                                    </div>
                                    <div class="text-right flex-shrink-0 ml-4">
                                        <span class="inline-flex items-center px-2 py-0.5 rounded-full text-xs font-bold {{ $product->stock_quantity <= 0 ? 'bg-red-100 text-red-600' : 'bg-gold-300/30 text-gold-500' }}">
                                            {{ $product->stock_quantity }} / {{ $product->min_stock_level }}
                                        </span>
                                        <p class="text-xs text-muted mt-0.5">{{ __('stock / min') }}</p>
                                    </div>
                                </div>
                            @endforeach
                        </div>
                    @endif
                </div>

                {{-- Pending Deliveries --}}
                <div class="bg-cream-50 rounded-2xl ring-1 ring-bark-200/10 shadow-sm shadow-bark-200/5 overflow-hidden">
                    <div class="px-6 py-5 border-b border-bark-200/10">
                        <h3 class="font-serif font-bold text-lg text-bark-600">{{ __('Pending Deliveries') }}</h3>
                    </div>

                    @if($pendingDeliveries->isEmpty())
                        <div class="p-10 text-center">
                            <svg class="mx-auto w-12 h-12 text-bark-200" fill="none" stroke="currentColor" stroke-width="1.5" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" d="M8.25 18.75a1.5 1.5 0 01-3 0m3 0a1.5 1.5 0 00-3 0m3 0h6m-9 0H3.375a1.125 1.125 0 01-1.125-1.125V14.25m17.25 4.5a1.5 1.5 0 01-3 0m3 0a1.5 1.5 0 00-3 0m3 0H6.375c-.621 0-1.125-.504-1.125-1.125V14.25m17.25 0V6.169a2.25 2.25 0 00-.659-1.591l-3.42-3.42A2.25 2.25 0 0016.58 .5H6.75A2.25 2.25 0 004.5 2.75v11.5"/>
                            </svg>
                            <p class="mt-3 text-muted font-medium">{{ __('No pending deliveries') }}</p>
                        </div>
                    @else
                        <div class="divide-y divide-bark-200/10">
                            @foreach($pendingDeliveries as $delivery)
                                <div class="px-6 py-3.5 hover:bg-cream-100/50 transition duration-150">
                                    <div class="flex items-center justify-between">
                                        <div class="min-w-0">
                                            <span class="text-sm font-semibold text-bark-600">
                                                {{ $delivery->order?->order_number ?? '—' }}
                                            </span>
                                            <p class="text-xs text-muted mt-0.5">
                                                {{ $delivery->order?->user?->name ?? __('Unknown') }}
                                            </p>
                                        </div>
                                        <span class="inline-flex items-center px-2 py-0.5 rounded-full text-xs font-semibold {{ $delivery->status === 'pending' ? 'bg-gold-300/30 text-gold-500' : 'bg-bark-100 text-bark-400' }}">
                                            {{ ucfirst($delivery->status) }}
                                        </span>
                                    </div>
                                </div>
                            @endforeach
                        </div>
                    @endif
                </div>
            </div>

        </div>
    </div>

        </div>
        </div>
        </div>

    <script id="chart-data-dashboard" type="application/json">
        {!! json_encode([
            'chartLabels' => $chartLabels,
            'chartSales' => $chartSales,
            'chartOrders' => $chartOrders,
            'labelSales' => __('Sales (₱)'),
            'labelOrders' => __('Orders'),
        ], JSON_UNESCAPED_UNICODE | JSON_HEX_TAG | JSON_HEX_AMP) !!}
    </script>
    @vite('resources/js/admin-dashboard-chart.js')
</x-app-layout>