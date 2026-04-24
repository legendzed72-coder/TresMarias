<x-app-layout>
    <x-slot name="header">
        <div class="flex items-center justify-between">
            <div>
                <h2 class="font-serif font-bold text-2xl text-bark-600 leading-tight">
                    {{ __('Staff Dashboard') }}
                </h2>
                <p class="mt-1 text-sm text-muted">{{ now()->format('l, F j, Y') }} — {{ __('Welcome back,') }} {{ Auth::user()->name }}!</p>
            </div>
            <div class="flex items-center gap-3">
                <button @click="refreshData()" :disabled="loading"
                    class="inline-flex items-center gap-2 px-4 py-2 bg-bark-300 hover:bg-bark-400 disabled:opacity-50 text-cream-50 font-semibold text-sm rounded-xl shadow-sm transition duration-200">
                    <svg class="w-4 h-4" :class="{ 'animate-spin': loading }" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M4 4v5h.582m15.356 2A8.001 8.001 0 004.582 9m0 0H9m11 11v-5h-.581m0 0a8.003 8.003 0 01-15.357-2m15.357 2H15"/>
                    </svg>
                    {{ __('Refresh') }}
                </button>
                <a href="{{ route('staff.pos') }}"
                   class="inline-flex items-center gap-2 px-4 py-2 bg-bark-300 hover:bg-bark-400 text-cream-50 font-semibold text-sm rounded-xl shadow-sm transition duration-200">
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M2.25 18.75a60.07 60.07 0 0115.797 2.101c.727.198 1.453-.342 1.453-1.096V18.75M3.75 4.5v.75A.75.75 0 013 6h-.75m0 0v-.375c0-.621.504-1.125 1.125-1.125H20.25M2.25 6v9m18-10.5v.75c0 .414.336.75.75.75h.75m-1.5-1.5h.375c.621 0 1.125.504 1.125 1.125v9.75c0 .621-.504 1.125-1.125 1.125h-.375m1.5-1.5H21a.75.75 0 00-.75.75v.75m0 0H3.75m0 0h-.375a1.125 1.125 0 01-1.125-1.125V15m1.5 1.5v-.75A.75.75 0 003 15h-.75M15 10.5a3 3 0 11-6 0 3 3 0 016 0zm3 0h.008v.008H18V10.5zm-12 0h.008v.008H6V10.5z"/>
                    </svg>
                    {{ __('Open POS') }}
                </a>
            </div>
        </div>
    </x-slot>

    <div class="flex">
        {{-- Sidebar --}}
        <x-admin-sidebar />

        {{-- Main Content --}}
        <div class="flex-1 py-8" x-data="staffDashboard()" x-init="init()">
        <div class="max-w-6xl mx-auto px-4 sm:px-6 lg:px-8 space-y-8">

            {{-- KPI Summary Cards --}}
            <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-5">

                {{-- Today's Sales --}}
                <div class="bg-cream-50 rounded-2xl ring-1 ring-bark-200/10 shadow-sm shadow-bark-200/5 p-6 hover:shadow-md hover:ring-bark-200/20 transition-all duration-200 cursor-pointer" @click="showSalesModal()">
                    <div class="flex items-center gap-4">
                        <div class="flex-shrink-0 w-12 h-12 rounded-xl bg-leaf-300/30 flex items-center justify-center">
                            <svg class="w-6 h-6 text-leaf-500" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24" aria-hidden="true">
                                <path stroke-linecap="round" stroke-linejoin="round" d="M12 6v12m-3-2.818l.879.659c1.171.879 3.07.879 4.242 0 1.172-.879 1.172-2.303 0-3.182C13.536 12.219 12.768 12 12 12c-.725 0-1.45-.22-2.003-.659-1.106-.879-1.106-2.303 0-3.182s2.9-.879 4.006 0l.415.33M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/>
                            </svg>
                        </div>
                        <div class="flex-1">
                            <p class="text-sm font-medium text-muted">{{ __("Today's Sales") }}</p>
                            <p class="text-2xl font-bold text-bark-600">₱{{ number_format($todaySales, 2) }}</p>
                            <p class="text-xs text-leaf-500 font-semibold mt-0.5">{{ $todayOrders }} {{ __('completed orders') }}</p>
                        </div>
                    </div>
                </div>

                {{-- Pending Orders --}}
                <div class="bg-cream-50 rounded-2xl ring-1 ring-bark-200/10 shadow-sm shadow-bark-200/5 p-6 hover:shadow-md hover:ring-bark-200/20 transition-all duration-200 cursor-pointer" @click="showPendingOrders()">
                    <div class="flex items-center gap-4">
                        <div class="flex-shrink-0 w-12 h-12 rounded-xl bg-gold-300/30 flex items-center justify-center">
                            <svg class="w-6 h-6 text-gold-500" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24" aria-hidden="true">
                                <path stroke-linecap="round" stroke-linejoin="round" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"/>
                            </svg>
                        </div>
                        <div class="flex-1">
                            <p class="text-sm font-medium text-muted">{{ __('Pending Orders') }}</p>
                            <p class="text-2xl font-bold text-bark-600">{{ $pendingOrders }}</p>
                            <p class="text-xs text-muted mt-0.5">{{ __('awaiting confirmation') }}</p>
                        </div>
                    </div>
                </div>

                {{-- Preparing / Ready --}}
                <div class="bg-cream-50 rounded-2xl ring-1 ring-bark-200/10 shadow-sm shadow-bark-200/5 p-6 hover:shadow-md hover:ring-bark-200/20 transition-all duration-200 cursor-pointer" @click="showKitchenOrders()">
                    <div class="flex items-center gap-4">
                        <div class="flex-shrink-0 w-12 h-12 rounded-xl bg-bark-100 flex items-center justify-center">
                            <svg class="w-6 h-6 text-bark-400" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24" aria-hidden="true">
                                <path stroke-linecap="round" stroke-linejoin="round" d="M15.362 5.214A8.252 8.252 0 0112 21 8.25 8.25 0 016.038 7.048 8.287 8.287 0 009 9.6a8.983 8.983 0 013.361-6.867 8.21 8.21 0 003 2.48z"/>
                                <path stroke-linecap="round" stroke-linejoin="round" d="M12 18a3.75 3.75 0 00.495-7.467 5.99 5.99 0 00-1.925 3.546 5.974 5.974 0 01-2.133-1A3.75 3.75 0 0012 18z"/>
                            </svg>
                        </div>
                        <div class="flex-1">
                            <p class="text-sm font-medium text-muted">{{ __('In Kitchen') }}</p>
                            <p class="text-2xl font-bold text-bark-600">{{ $preparingOrders }}</p>
                            <p class="text-xs mt-0.5">
                                <span class="text-leaf-500 font-semibold">{{ $readyOrders }}</span>
                                <span class="text-muted">{{ __('ready for pickup') }}</span>
                            </p>
                        </div>
                    </div>
                </div>

                {{-- Low Stock Alert --}}
                <div class="bg-cream-50 rounded-2xl ring-1 ring-bark-200/10 shadow-sm shadow-bark-200/5 p-6 hover:shadow-md hover:ring-bark-200/20 transition-all duration-200 cursor-pointer" @click="showLowStock()">
                    <div class="flex items-center gap-4">
                        <div class="flex-shrink-0 w-12 h-12 rounded-xl {{ $lowStockCount > 0 ? 'bg-red-100' : 'bg-leaf-300/30' }} flex items-center justify-center">
                            <svg class="w-6 h-6 {{ $lowStockCount > 0 ? 'text-red-500' : 'text-leaf-500' }}" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24" aria-hidden="true">
                                <path stroke-linecap="round" stroke-linejoin="round" d="M12 9v3.75m-9.303 3.376c-.866 1.5.217 3.374 1.948 3.374h14.71c1.73 0 2.813-1.874 1.948-3.374L13.949 3.378c-.866-1.5-3.032-1.5-3.898 0L2.697 16.126zM12 15.75h.007v.008H12v-.008z"/>
                            </svg>
                        </div>
                        <div class="flex-1">
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

            <div class="grid grid-cols-1 lg:grid-cols-3 gap-8">

                {{-- Recent Orders --}}
                <div class="lg:col-span-2">
                    <div class="bg-cream-50 rounded-2xl ring-1 ring-bark-200/10 shadow-sm shadow-bark-200/5 overflow-hidden">
                        <div class="px-6 py-5 border-b border-bark-200/10 flex flex-col sm:flex-row items-start sm:items-center justify-between gap-4">
                            <div>
                                <h3 class="font-serif font-bold text-lg text-bark-600">{{ __('Recent Orders') }}</h3>
                                <p class="text-xs text-muted mt-1">{{ __('Latest order activity') }}</p>
                            </div>
                            <div class="flex items-center gap-3">
                                <div class="relative">
                                    <svg class="absolute left-3 top-1/2 -translate-y-1/2 w-4 h-4 text-bark-200 pointer-events-none" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"/>
                                    </svg>
                                    <input type="text" x-model="orderSearch" placeholder="{{ __('Search orders...') }}"
                                        class="w-full pl-10 pr-4 py-2 rounded-xl border border-bark-200/20 bg-white text-sm text-bark-600 placeholder-bark-200 focus:outline-none focus:ring-2 focus:ring-bark-300/40 focus:border-bark-300 transition-all">
                                </div>
                                <a href="{{ route('staff.orders') }}" class="text-xs font-semibold text-bark-300 hover:text-bark-400 transition whitespace-nowrap">
                                    {{ __('View all') }} →
                                </a>
                            </div>
                        </div>

                        @if($recentOrders->isEmpty())
                            <div class="p-12 text-center">
                                <svg class="mx-auto w-12 h-12 text-bark-200 mb-3" fill="none" stroke="currentColor" stroke-width="1.5" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" d="M2.25 3h1.386c.51 0 .955.343 1.087.835l.383 1.437M7.5 14.25a3 3 0 00-3 3h15.75m-12.75-3h11.218c1.121-2.3 2.1-4.684 2.924-7.138a60.114 60.114 0 00-16.536-1.84M7.5 14.25L5.106 5.272M6 20.25a.75.75 0 11-1.5 0 .75.75 0 011.5 0zm12.75 0a.75.75 0 11-1.5 0 .75.75 0 011.5 0z"/>
                                </svg>
                                <p class="text-muted font-medium">{{ __('No orders yet') }}</p>
                                <p class="text-sm text-muted mt-1">{{ __('Orders will appear here as they are placed.') }}</p>
                            </div>
                        @else
                            <div class="overflow-x-auto">
                                <table class="w-full text-sm" role="grid" aria-label="Recent orders">
                                    <thead>
                                        <tr class="bg-cream-200/40">
                                            <th class="px-6 py-3 text-left font-semibold text-muted text-xs uppercase tracking-wider">{{ __('Order') }}</th>
                                            <th class="px-6 py-3 text-left font-semibold text-muted text-xs uppercase tracking-wider">{{ __('Customer') }}</th>
                                            <th class="px-6 py-3 text-left font-semibold text-muted text-xs uppercase tracking-wider">{{ __('Type') }}</th>
                                            <th class="px-6 py-3 text-left font-semibold text-muted text-xs uppercase tracking-wider">{{ __('Status') }}</th>
                                            <th class="px-6 py-3 text-right font-semibold text-muted text-xs uppercase tracking-wider">{{ __('Total') }}</th>
                                            <th class="px-6 py-3 text-right font-semibold text-muted text-xs uppercase tracking-wider">{{ __('Time') }}</th>
                                        </tr>
                                    </thead>
                                    <tbody class="divide-y divide-bark-200/10">
                                        @foreach($recentOrders as $order)
                                            <tr class="hover:bg-cream-100/50 transition duration-150" x-show="!orderSearch || '{{ strtolower($order->order_number) }}'.includes(orderSearch.toLowerCase()) || '{{ strtolower($order->user?->name ?? 'walk-in') }}'.includes(orderSearch.toLowerCase())">
                                                <td class="px-6 py-3.5">
                                                    <span class="font-semibold text-bark-600">{{ $order->order_number }}</span>
                                                </td>
                                                <td class="px-6 py-3.5 text-muted text-xs">
                                                    <span title="{{ $order->user?->name ?? __('Walk-in') }}" class="truncate block">{{ $order->user?->name ?? __('Walk-in') }}</span>
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
                                                <td class="px-6 py-3.5 text-right text-xs text-muted whitespace-nowrap">
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

                    {{-- Order Pipeline --}}
                    <div class="bg-cream-50 rounded-2xl ring-1 ring-bark-200/10 shadow-sm shadow-bark-200/5 overflow-hidden">
                        <div class="px-6 py-5 border-b border-bark-200/10">
                            <h3 class="font-serif font-bold text-lg text-bark-600">{{ __('Order Pipeline') }}</h3>
                            <p class="text-xs text-muted mt-1">{{ __('Current order status overview') }}</p>
                        </div>

                        <div class="p-6 space-y-4">
                            @php
                                $pipeline = [
                                    ['status' => 'pending', 'label' => __('Pending'), 'count' => $orderStats['pending'] ?? 0, 'color' => 'bg-gold-300/30 text-gold-500', 'icon' => 'M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z'],
                                    ['status' => 'confirmed', 'label' => __('Confirmed'), 'count' => $orderStats['confirmed'] ?? 0, 'color' => 'bg-bark-100 text-bark-400', 'icon' => 'M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z'],
                                    ['status' => 'preparing', 'label' => __('Preparing'), 'count' => $orderStats['preparing'] ?? 0, 'color' => 'bg-gold-300/30 text-gold-500', 'icon' => 'M11 4a2 2 0 114 0v1a1 1 0 001 1h3a1 1 0 011 1v3a1 1 0 01-1 1h-1a2 2 0 100 4h1a1 1 0 011 1v3a1 1 0 01-1 1h-3a1 1 0 01-1-1v-1a2 2 0 10-4 0v1a1 1 0 01-1 1H7a1 1 0 01-1-1v-3a1 1 0 011-1h1a2 2 0 100-4H7a1 1 0 01-1-1V7a1 1 0 011-1h3a1 1 0 001-1V4z'],
                                    ['status' => 'ready', 'label' => __('Ready'), 'count' => $orderStats['ready'] ?? 0, 'color' => 'bg-leaf-300/30 text-leaf-500', 'icon' => 'M5 13l4 4L19 7'],
                                    ['status' => 'out_for_delivery', 'label' => __('Out for Delivery'), 'count' => $orderStats['out_for_delivery'] ?? 0, 'color' => 'bg-bark-100 text-bark-300', 'icon' => 'M8 7v8a2 2 0 002 2h6M8 7V5a2 2 0 012-2h4.586a1 1 0 01.707.293l4.414 4.414a1 1 0 01.293.707V7M8 7H6a2 2 0 00-2 2v10a2 2 0 002 2h8a2 2 0 002-2v-2'],
                                    ['status' => 'completed', 'label' => __('Completed'), 'count' => $orderStats['completed'] ?? 0, 'color' => 'bg-leaf-300/30 text-leaf-500', 'icon' => 'M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z'],
                                ];
                            @endphp

                            @foreach($pipeline as $stage)
                                <div class="flex items-center justify-between p-3 rounded-xl bg-white ring-1 ring-bark-200/10 hover:ring-bark-300/20 transition-all duration-200 cursor-pointer group"
                                     x-on:click="showOrderModal('{{ $stage['status'] }}')">
                                    <div class="flex items-center gap-3">
                                        <div class="flex-shrink-0 w-8 h-8 rounded-lg {{ $stage['color'] }} flex items-center justify-center">
                                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="{{ $stage['icon'] }}"/>
                                            </svg>
                                        </div>
                                        <div>
                                            <p class="font-medium text-bark-600 text-sm">{{ $stage['label'] }}</p>
                                            <p class="text-xs text-muted">{{ $stage['count'] }} {{ __('orders') }}</p>
                                        </div>
                                    </div>
                                    <div class="flex items-center gap-2">
                                        <span class="text-lg font-bold text-bark-600">{{ $stage['count'] }}</span>
                                        <svg class="w-4 h-4 text-bark-200 group-hover:text-bark-300 transition" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"/>
                                        </svg>
                                    </div>
                                </div>
                            @endforeach
                        </div>
                    </div>

                    {{-- Quick Actions --}}
                    <div class="bg-cream-50 rounded-2xl ring-1 ring-bark-200/10 shadow-sm shadow-bark-200/5 p-6">
                        <h3 class="font-serif font-bold text-lg text-bark-600 mb-4">{{ __('Quick Actions') }}</h3>
                        <div class="space-y-3">
                            <a href="{{ route('staff.pos') }}"
                               class="flex items-center gap-3 p-3 rounded-xl bg-cream-200/50 hover:bg-cream-300/50 transition duration-150 group">
                                <div class="w-10 h-10 rounded-lg bg-bark-300 flex items-center justify-center flex-shrink-0">
                                    <svg class="w-5 h-5 text-cream-50" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" d="M2.25 18.75a60.07 60.07 0 0115.797 2.101c.727.198 1.453-.342 1.453-1.096V18.75M3.75 4.5v.75A.75.75 0 013 6h-.75m0 0v-.375c0-.621.504-1.125 1.125-1.125H20.25M2.25 6v9m18-10.5v.75c0 .414.336.75.75.75h.75m-1.5-1.5h.375c.621 0 1.125.504 1.125 1.125v9.75c0 .621-.504 1.125-1.125 1.125h-.375m1.5-1.5H21a.75.75 0 00-.75.75v.75m0 0H3.75m0 0h-.375a1.125 1.125 0 01-1.125-1.125V15m1.5 1.5v-.75A.75.75 0 003 15h-.75M15 10.5a3 3 0 11-6 0 3 3 0 016 0zm3 0h.008v.008H18V10.5zm-12 0h.008v.008H6V10.5z"/>
                                    </svg>
                                </div>
                                <div class="min-w-0">
                                    <p class="text-sm font-semibold text-bark-600 group-hover:text-bark-500">{{ __('Point of Sale') }}</p>
                                    <p class="text-xs text-muted">{{ __('Process walk-in orders') }}</p>
                                </div>
                            </a>

                            <a href="{{ route('staff.orders') }}"
                               class="flex items-center gap-3 p-3 rounded-xl bg-cream-200/50 hover:bg-cream-300/50 transition duration-150 group">
                                <div class="w-10 h-10 rounded-lg bg-gold-400 flex items-center justify-center flex-shrink-0">
                                    <svg class="w-5 h-5 text-cream-50" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" d="M9 12h3.75M9 15h3.75M9 18h3.75m3 .75H18a2.25 2.25 0 002.25-2.25V6.108c0-1.135-.845-2.098-1.976-2.192a48.424 48.424 0 00-1.123-.08m-5.801 0c-.065.21-.1.433-.1.664 0 .414.336.75.75.75h4.5a.75.75 0 00.75-.75 2.25 2.25 0 00-.1-.664m-5.8 0A2.251 2.251 0 0113.5 2.25H15a2.25 2.25 0 012.15 1.586m-5.8 0c-.376.023-.75.05-1.124.08C9.095 4.01 8.25 4.973 8.25 6.108V8.25m0 0H4.875c-.621 0-1.125.504-1.125 1.125v11.25c0 .621.504 1.125 1.125 1.125h9.75c.621 0 1.125-.504 1.125-1.125V9.375c0-.621-.504-1.125-1.125-1.125H8.25zM6.75 12h.008v.008H6.75V12zm0 3h.008v.008H6.75V15zm0 3h.008v.008H6.75V18z"/>
                                    </svg>
                                </div>
                                <div class="min-w-0">
                                    <p class="text-sm font-semibold text-bark-600 group-hover:text-bark-500">{{ __('Manage Orders') }}</p>
                                    <p class="text-xs text-muted">{{ __('Update order statuses') }}</p>
                                </div>
                            </a>

                            <a href="{{ route('staff.inventory') }}"
                               class="flex items-center gap-3 p-3 rounded-xl bg-cream-200/50 hover:bg-cream-300/50 transition duration-150 group">
                                <div class="w-10 h-10 rounded-lg bg-leaf-400 flex items-center justify-center flex-shrink-0">
                                    <svg class="w-5 h-5 text-cream-50" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" d="M20 7l-8-4-8 4m16 0l-8 4m8-4v10l-8 4m0-10L4 7m8 4v10M4 7v10l8 4"/>
                                    </svg>
                                </div>
                                <div class="min-w-0">
                                    <p class="text-sm font-semibold text-bark-600 group-hover:text-bark-500">{{ __('Inventory') }}</p>
                                    <p class="text-xs text-muted">{{ __('Check stock levels') }}</p>
                                </div>
                            </a>

                            <a href="{{ route('staff.deliveries') }}"
                               class="flex items-center gap-3 p-3 rounded-xl bg-cream-200/50 hover:bg-cream-300/50 transition duration-150 group">
                                <div class="w-10 h-10 rounded-lg bg-bark-400 flex items-center justify-center flex-shrink-0">
                                    <svg class="w-5 h-5 text-cream-50" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" d="M8.25 18.75a1.5 1.5 0 01-3 0m3 0a1.5 1.5 0 00-3 0m3 0h6m-9 0H3.375a1.125 1.125 0 01-1.125-1.125V14.25m17.25 4.5a1.5 1.5 0 01-3 0m3 0a1.5 1.5 0 00-3 0m3 0H6.375c-.621 0-1.125-.504-1.125-1.125V11.25m17.25 0V6.169a2.25 2.25 0 00-.659-1.591l-3.42-3.42A2.25 2.25 0 0016.58.5H6.75A2.25 2.25 0 004.5 2.75v8.5"/>
                                    </svg>
                                </div>
                                <div class="min-w-0">
                                    <p class="text-sm font-semibold text-bark-600 group-hover:text-bark-500">{{ __('Deliveries') }}</p>
                                    <p class="text-xs text-muted">{{ __('Track & assign deliveries') }}</p>
                                </div>
                            </a>
                        </div>
                    </div>
                </div>
            </div>

            {{-- Today's Preorders & Low Stock --}}
            <div class="grid grid-cols-1 lg:grid-cols-2 gap-8">

                {{-- Today's Preorders --}}
                <div class="bg-cream-50 rounded-2xl ring-1 ring-bark-200/10 shadow-sm shadow-bark-200/5 overflow-hidden">
                    <div class="px-6 py-5 border-b border-bark-200/10 flex items-center justify-between">
                        <h3 class="font-serif font-bold text-lg text-bark-600">{{ __("Today's Preorders") }}</h3>
                        @if($todayPreorders->isNotEmpty())
                            <span class="inline-flex items-center px-2.5 py-1 rounded-full text-xs font-semibold bg-gold-300/30 text-gold-500">
                                {{ $todayPreorders->count() }} {{ __('scheduled') }}
                            </span>
                        @endif
                    </div>

                    @if($todayPreorders->isEmpty())
                        <div class="p-10 text-center">
                            <svg class="mx-auto w-12 h-12 text-bark-200" fill="none" stroke="currentColor" stroke-width="1.5" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" d="M6.75 3v2.25M17.25 3v2.25M3 18.75V7.5a2.25 2.25 0 012.25-2.25h13.5A2.25 2.25 0 0121 7.5v11.25m-18 0A2.25 2.25 0 005.25 21h13.5A2.25 2.25 0 0021 18.75m-18 0v-7.5A2.25 2.25 0 015.25 9h13.5A2.25 2.25 0 0121 11.25v7.5"/>
                            </svg>
                            <p class="mt-3 text-muted font-medium">{{ __('No preorders scheduled for today') }}</p>
                        </div>
                    @else
                        <div class="divide-y divide-bark-200/10">
                            @foreach($todayPreorders as $preorder)
                                <div class="px-6 py-3.5 hover:bg-cream-100/50 transition duration-150">
                                    <div class="flex items-center justify-between">
                                        <div class="min-w-0">
                                            <span class="text-sm font-semibold text-bark-600">{{ $preorder->order_number }}</span>
                                            <p class="text-xs text-muted mt-0.5">{{ $preorder->user?->name ?? __('Guest') }}</p>
                                        </div>
                                        <div class="text-right flex-shrink-0 ml-4">
                                            @php
                                                $preorderStatusColors = [
                                                    'pending'   => 'bg-gold-300/30 text-gold-500',
                                                    'confirmed' => 'bg-bark-100 text-bark-400',
                                                    'preparing' => 'bg-gold-300/30 text-gold-500',
                                                    'ready'     => 'bg-leaf-300/30 text-leaf-500',
                                                ];
                                                $preorderColor = $preorderStatusColors[$preorder->status] ?? 'bg-bark-100 text-bark-400';
                                            @endphp
                                            <span class="inline-flex items-center px-2 py-0.5 rounded-full text-xs font-semibold {{ $preorderColor }}">
                                                {{ ucfirst($preorder->status) }}
                                            </span>
                                            <p class="text-xs text-muted mt-0.5">
                                                {{ $preorder->scheduled_at?->format('g:i A') ?? '—' }}
                                            </p>
                                        </div>
                                    </div>
                                </div>
                            @endforeach
                        </div>
                    @endif
                </div>

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
            </div>

            {{-- Pending Deliveries --}}
            <div class="bg-cream-50 rounded-2xl ring-1 ring-bark-200/10 shadow-sm shadow-bark-200/5 overflow-hidden">
                <div class="px-6 py-5 border-b border-bark-200/10 flex items-center justify-between">
                    <h3 class="font-serif font-bold text-lg text-bark-600">{{ __('Pending Deliveries') }}</h3>
                    <a href="{{ route('staff.deliveries') }}" class="text-xs font-semibold text-bark-300 hover:text-bark-400 transition">
                        {{ __('View all') }} →
                    </a>
                </div>

                @if($pendingDeliveries->isEmpty())
                    <div class="p-10 text-center">
                        <svg class="mx-auto w-12 h-12 text-bark-200" fill="none" stroke="currentColor" stroke-width="1.5" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M8.25 18.75a1.5 1.5 0 01-3 0m3 0a1.5 1.5 0 00-3 0m3 0h6m-9 0H3.375a1.125 1.125 0 01-1.125-1.125V14.25m17.25 4.5a1.5 1.5 0 01-3 0m3 0a1.5 1.5 0 00-3 0m3 0H6.375c-.621 0-1.125-.504-1.125-1.125V11.25m17.25 0V6.169a2.25 2.25 0 00-.659-1.591l-3.42-3.42A2.25 2.25 0 0016.58.5H6.75A2.25 2.25 0 004.5 2.75v8.5"/>
                        </svg>
                        <p class="mt-3 text-muted font-medium">{{ __('No pending deliveries') }}</p>
                    </div>
                @else
                    <div class="overflow-x-auto">
                        <table class="w-full text-sm">
                            <thead>
                                <tr class="bg-cream-200/40">
                                    <th class="px-6 py-3 text-left font-semibold text-muted text-xs uppercase tracking-wider">{{ __('Order') }}</th>
                                    <th class="px-6 py-3 text-left font-semibold text-muted text-xs uppercase tracking-wider">{{ __('Customer') }}</th>
                                    <th class="px-6 py-3 text-left font-semibold text-muted text-xs uppercase tracking-wider">{{ __('Status') }}</th>
                                    <th class="px-6 py-3 text-right font-semibold text-muted text-xs uppercase tracking-wider">{{ __('Created') }}</th>
                                </tr>
                            </thead>
                            <tbody class="divide-y divide-bark-200/10">
                                @foreach($pendingDeliveries as $delivery)
                                    <tr class="hover:bg-cream-100/50 transition duration-150">
                                        <td class="px-6 py-3.5">
                                            <span class="font-semibold text-bark-600">{{ $delivery->order?->order_number ?? '—' }}</span>
                                        </td>
                                        <td class="px-6 py-3.5 text-muted">
                                            {{ $delivery->order?->user?->name ?? __('Unknown') }}
                                        </td>
                                        <td class="px-6 py-3.5">
                                            <span class="inline-flex items-center px-2 py-0.5 rounded-full text-xs font-semibold {{ $delivery->status === 'pending' ? 'bg-gold-300/30 text-gold-500' : 'bg-bark-100 text-bark-400' }}">
                                                {{ ucfirst($delivery->status) }}
                                            </span>
                                        </td>
                                        <td class="px-6 py-3.5 text-right text-xs text-muted">
                                            {{ $delivery->created_at->diffForHumans() }}
                                        </td>
                                    </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                @endif
            </div>

        </div>
    </div>

    {{-- Order Details Modal --}}
    <div x-show="showModal" x-cloak
         x-transition:enter="transition ease-out duration-300"
         x-transition:enter-start="opacity-0"
         x-transition:enter-end="opacity-100"
         x-transition:leave="transition ease-in duration-200"
         x-transition:leave-start="opacity-100"
         x-transition:leave-end="opacity-0"
         class="fixed inset-0 z-50 overflow-y-auto"
         aria-labelledby="modal-title" role="dialog" aria-modal="true">
        <div class="flex items-end justify-center min-h-screen pt-4 px-4 pb-20 text-center sm:block sm:p-0">
            <div x-show="showModal"
                 x-transition:enter="transition ease-out duration-300"
                 x-transition:enter-start="opacity-0"
                 x-transition:enter-end="opacity-100"
                 x-transition:leave="transition ease-in duration-200"
                 x-transition:leave-start="opacity-100"
                 x-transition:leave-end="opacity-0"
                 class="fixed inset-0 bg-bark-600 bg-opacity-75 transition-opacity" aria-hidden="true"
                 x-on:click="showModal = false"></div>

            <span class="hidden sm:inline-block sm:align-middle sm:h-screen" aria-hidden="true">&#8203;</span>

            <div x-show="showModal"
                 x-transition:enter="transition ease-out duration-300"
                 x-transition:enter-start="opacity-0 translate-y-4 sm:translate-y-0 sm:scale-95"
                 x-transition:enter-end="opacity-100 translate-y-0 sm:scale-100"
                 x-transition:leave="transition ease-in duration-200"
                 x-transition:leave-start="opacity-100 translate-y-0 sm:scale-100"
                 x-transition:leave-end="opacity-0 translate-y-4 sm:translate-y-0 sm:scale-95"
                 class="inline-block align-bottom bg-white rounded-2xl text-left overflow-hidden shadow-xl transform transition-all sm:my-8 sm:align-middle sm:max-w-lg sm:w-full">
                <div class="bg-white px-4 pt-5 pb-4 sm:p-6 sm:pb-4">
                    <div class="sm:flex sm:items-start">
                        <div class="mt-3 text-center sm:mt-0 sm:text-left w-full">
                            <h3 class="text-lg leading-6 font-serif font-bold text-bark-600" id="modal-title">
                                {{ __('Orders in') }} <span x-text="modalStatus" class="capitalize"></span>
                            </h3>
                            <div class="mt-4">
                                <div x-show="loading" class="flex items-center justify-center py-8">
                                    <div class="animate-spin rounded-full h-8 w-8 border-b-2 border-bark-600"></div>
                                    <span class="ml-2 text-sm text-muted">{{ __('Loading...') }}</span>
                                </div>
                                <div x-show="!loading" x-html="modalContent" class="space-y-3 max-h-96 overflow-y-auto"></div>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="bg-cream-50 px-4 py-3 sm:px-6 sm:flex sm:flex-row-reverse">
                    <button type="button" x-on:click="showModal = false"
                            class="w-full inline-flex justify-center rounded-xl border border-transparent shadow-sm px-4 py-2 bg-bark-600 text-base font-medium text-white hover:bg-bark-500 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-bark-300 sm:ml-3 sm:w-auto sm:text-sm transition">
                        {{ __('Close') }}
                    </button>
                </div>
            </div>
        </div>
    </div>

    {{-- Pass translations to JavaScript --}}
    <script>
        window.translations = {
            refreshing: '@lang("Refreshing...")',
            orderDetailsHere: '@lang("Order details will be displayed here")',
            comingSoon: '@lang("This feature is coming soon")'
        };
    </script>

        </div>
        </div>
        </div>

    @vite('resources/js/staff-dashboard.js')
</x-app-layout>
