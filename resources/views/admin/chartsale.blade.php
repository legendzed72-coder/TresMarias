<x-app-layout>
    <x-slot name="header">
        <div class="flex items-center justify-between">
            <div>
                <h2 class="font-serif font-bold text-2xl text-bark-600 leading-tight">
                    {{ __('Sales Analytics') }}
                </h2>
                <p class="mt-1 text-sm text-muted">{{ __('Detailed sales performance & trends') }}</p>
            </div>
            <div class="flex items-center gap-3">
                <a href="{{ route('admin.dashboard') }}"
                   class="inline-flex items-center gap-2 px-4 py-2 bg-cream-50 ring-1 ring-bark-200/15 hover:bg-cream-200/50 text-bark-500 font-semibold text-sm rounded-xl transition duration-200">
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M10.5 19.5L3 12m0 0l7.5-7.5M3 12h18"/>
                    </svg>
                    {{ __('Dashboard') }}
                </a>
                <a href="{{ route('admin.reports') }}"
                   class="inline-flex items-center gap-2 px-4 py-2 bg-bark-300 hover:bg-bark-400 text-cream-50 font-semibold text-sm rounded-xl shadow-sm transition duration-200">
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M19.5 14.25v-2.625a3.375 3.375 0 00-3.375-3.375h-1.5A1.125 1.125 0 0113.5 7.125v-1.5a3.375 3.375 0 00-3.375-3.375H8.25m2.25 0H5.625c-.621 0-1.125.504-1.125 1.125v17.25c0 .621.504 1.125 1.125 1.125h12.75c.621 0 1.125-.504 1.125-1.125V11.25a9 9 0 00-9-9z"/>
                    </svg>
                    {{ __('Reports') }}
                </a>
            </div>
        </div>
    </x-slot>

    <div class="py-8">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 space-y-8">

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
                            <p class="text-sm font-medium text-muted">{{ __("Today") }}</p>
                            <p class="text-2xl font-bold text-bark-600">₱{{ number_format($todaySales, 2) }}</p>
                        </div>
                    </div>
                </div>

                {{-- This Week --}}
                <div class="bg-cream-50 rounded-2xl ring-1 ring-bark-200/10 shadow-sm shadow-bark-200/5 p-6">
                    <div class="flex items-center gap-4">
                        <div class="flex-shrink-0 w-12 h-12 rounded-xl bg-gold-300/30 flex items-center justify-center">
                            <svg class="w-6 h-6 text-gold-500" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" d="M6.75 3v2.25M17.25 3v2.25M3 18.75V7.5a2.25 2.25 0 012.25-2.25h13.5A2.25 2.25 0 0121 7.5v11.25m-18 0A2.25 2.25 0 005.25 21h13.5A2.25 2.25 0 0021 18.75m-18 0v-7.5A2.25 2.25 0 015.25 9h13.5A2.25 2.25 0 0121 11.25v7.5"/>
                            </svg>
                        </div>
                        <div>
                            <p class="text-sm font-medium text-muted">{{ __("This Week") }}</p>
                            <p class="text-2xl font-bold text-bark-600">₱{{ number_format($thisWeekSales, 2) }}</p>
                        </div>
                    </div>
                </div>

                {{-- This Month --}}
                <div class="bg-cream-50 rounded-2xl ring-1 ring-bark-200/10 shadow-sm shadow-bark-200/5 p-6">
                    <div class="flex items-center gap-4">
                        <div class="flex-shrink-0 w-12 h-12 rounded-xl bg-bark-100 flex items-center justify-center">
                            <svg class="w-6 h-6 text-bark-400" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" d="M2.25 18L9 11.25l4.306 4.307a11.95 11.95 0 015.814-5.519l2.74-1.22m0 0l-5.94-2.28m5.94 2.28l-2.28 5.941"/>
                            </svg>
                        </div>
                        <div>
                            <p class="text-sm font-medium text-muted">{{ __("This Month") }}</p>
                            <p class="text-2xl font-bold text-bark-600">₱{{ number_format($thisMonthSales, 2) }}</p>
                            <p class="text-xs text-muted mt-0.5">{{ $thisMonthOrders }} {{ __('orders') }}</p>
                        </div>
                    </div>
                </div>

                {{-- Average Order Value --}}
                <div class="bg-cream-50 rounded-2xl ring-1 ring-bark-200/10 shadow-sm shadow-bark-200/5 p-6">
                    <div class="flex items-center gap-4">
                        <div class="flex-shrink-0 w-12 h-12 rounded-xl bg-leaf-300/30 flex items-center justify-center">
                            <svg class="w-6 h-6 text-leaf-500" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" d="M3 13.125C3 12.504 3.504 12 4.125 12h2.25c.621 0 1.125.504 1.125 1.125v6.75C7.5 20.496 6.996 21 6.375 21h-2.25A1.125 1.125 0 013 19.875v-6.75zM9.75 8.625c0-.621.504-1.125 1.125-1.125h2.25c.621 0 1.125.504 1.125 1.125v11.25c0 .621-.504 1.125-1.125 1.125h-2.25a1.125 1.125 0 01-1.125-1.125V8.625zM16.5 4.125c0-.621.504-1.125 1.125-1.125h2.25C20.496 3 21 3.504 21 4.125v15.75c0 .621-.504 1.125-1.125 1.125h-2.25a1.125 1.125 0 01-1.125-1.125V4.125z"/>
                            </svg>
                        </div>
                        <div>
                            <p class="text-sm font-medium text-muted">{{ __("Avg. Order Value") }}</p>
                            <p class="text-2xl font-bold text-bark-600">₱{{ number_format($avgOrderValue, 2) }}</p>
                        </div>
                    </div>
                </div>
            </div>

            {{-- Main Sales Trend Chart with Period Toggle --}}
            <div class="bg-cream-50 rounded-2xl ring-1 ring-bark-200/10 shadow-sm shadow-bark-200/5 overflow-hidden">
                <div class="px-6 py-5 border-b border-bark-200/10 flex flex-col sm:flex-row sm:items-center sm:justify-between gap-3">
                    <h3 class="font-serif font-bold text-lg text-bark-600">{{ __('Sales Trend') }}</h3>
                    <div class="flex items-center gap-1 bg-cream-200/50 p-1 rounded-xl" x-data="{ period: 'daily' }">
                        <button @click="period = 'daily'; switchPeriod('daily')"
                                :class="period === 'daily' ? 'bg-bark-300 text-cream-50 shadow-sm' : 'text-bark-400 hover:text-bark-600'"
                                class="px-3 py-1.5 text-xs font-semibold rounded-lg transition duration-200">
                            {{ __('Daily') }}
                        </button>
                        <button @click="period = 'weekly'; switchPeriod('weekly')"
                                :class="period === 'weekly' ? 'bg-bark-300 text-cream-50 shadow-sm' : 'text-bark-400 hover:text-bark-600'"
                                class="px-3 py-1.5 text-xs font-semibold rounded-lg transition duration-200">
                            {{ __('Weekly') }}
                        </button>
                        <button @click="period = 'monthly'; switchPeriod('monthly')"
                                :class="period === 'monthly' ? 'bg-bark-300 text-cream-50 shadow-sm' : 'text-bark-400 hover:text-bark-600'"
                                class="px-3 py-1.5 text-xs font-semibold rounded-lg transition duration-200">
                            {{ __('Monthly') }}
                        </button>
                    </div>
                </div>
                <div class="p-6">
                    <canvas id="salesTrendChart" height="100"></canvas>
                </div>
            </div>

            {{-- Two-column: Category Breakdown & Order Type --}}
            <div class="grid grid-cols-1 lg:grid-cols-2 gap-8">

                {{-- Sales by Category --}}
                <div class="bg-cream-50 rounded-2xl ring-1 ring-bark-200/10 shadow-sm shadow-bark-200/5 overflow-hidden">
                    <div class="px-6 py-5 border-b border-bark-200/10">
                        <h3 class="font-serif font-bold text-lg text-bark-600">{{ __('Sales by Category') }}</h3>
                        <p class="text-xs text-muted mt-0.5">{{ __('This month') }}</p>
                    </div>
                    <div class="p-6">
                        @if(count($categoryLabels) > 0)
                            <div class="flex items-center justify-center" style="max-height: 320px;">
                                <canvas id="categoryChart"></canvas>
                            </div>
                        @else
                            <div class="py-10 text-center">
                                <p class="text-muted font-medium">{{ __('No sales data this month') }}</p>
                            </div>
                        @endif
                    </div>
                </div>

                {{-- Sales by Order Type --}}
                <div class="bg-cream-50 rounded-2xl ring-1 ring-bark-200/10 shadow-sm shadow-bark-200/5 overflow-hidden">
                    <div class="px-6 py-5 border-b border-bark-200/10">
                        <h3 class="font-serif font-bold text-lg text-bark-600">{{ __('POS vs Pre-orders') }}</h3>
                        <p class="text-xs text-muted mt-0.5">{{ __('This month') }}</p>
                    </div>
                    <div class="p-6">
                        @if($posSales > 0 || $preorderSales > 0)
                            <div class="flex items-center justify-center" style="max-height: 320px;">
                                <canvas id="orderTypeChart"></canvas>
                            </div>
                            <div class="mt-6 grid grid-cols-2 gap-4">
                                <div class="text-center p-3 rounded-xl bg-cream-200/40">
                                    <p class="text-xs font-medium text-muted">{{ __('POS Sales') }}</p>
                                    <p class="text-lg font-bold text-bark-600 mt-1">₱{{ number_format($posSales, 2) }}</p>
                                </div>
                                <div class="text-center p-3 rounded-xl bg-cream-200/40">
                                    <p class="text-xs font-medium text-muted">{{ __('Pre-orders') }}</p>
                                    <p class="text-lg font-bold text-bark-600 mt-1">₱{{ number_format($preorderSales, 2) }}</p>
                                </div>
                            </div>
                        @else
                            <div class="py-10 text-center">
                                <p class="text-muted font-medium">{{ __('No sales data this month') }}</p>
                            </div>
                        @endif
                    </div>
                </div>
            </div>

            {{-- Top Products Chart --}}
            <div class="bg-cream-50 rounded-2xl ring-1 ring-bark-200/10 shadow-sm shadow-bark-200/5 overflow-hidden">
                <div class="px-6 py-5 border-b border-bark-200/10">
                    <h3 class="font-serif font-bold text-lg text-bark-600">{{ __('Top Products by Revenue') }}</h3>
                    <p class="text-xs text-muted mt-0.5">{{ __('This month — Top 10') }}</p>
                </div>
                <div class="p-6">
                    @if(count($topProductLabels) > 0)
                        <canvas id="topProductsChart" height="80"></canvas>
                    @else
                        <div class="py-10 text-center">
                            <p class="text-muted font-medium">{{ __('No product sales data this month') }}</p>
                        </div>
                    @endif
                </div>
            </div>

            {{-- Orders Trend Chart --}}
            <div class="bg-cream-50 rounded-2xl ring-1 ring-bark-200/10 shadow-sm shadow-bark-200/5 overflow-hidden">
                <div class="px-6 py-5 border-b border-bark-200/10">
                    <h3 class="font-serif font-bold text-lg text-bark-600">{{ __('Daily Orders — Last 30 Days') }}</h3>
                </div>
                <div class="p-6">
                    <canvas id="ordersChart" height="80"></canvas>
                </div>
            </div>

        </div>
    </div>

    <script id="chart-data-chartsale" type="application/json">
        @json([
            'dailyLabels' => $dailyLabels,
            'dailySales' => $dailySales,
            'dailyOrders' => $dailyOrders,
            'weeklyLabels' => $weeklyLabels,
            'weeklySales' => $weeklySales,
            'weeklyOrders' => $weeklyOrders,
            'monthlyLabels' => $monthlyLabels,
            'monthlySales' => $monthlySales,
            'monthlyOrders' => $monthlyOrders,
            'categoryLabels' => $categoryLabels,
            'categorySales' => $categorySales,
            'posSales' => $posSales,
            'preorderSales' => $preorderSales,
            'topProductLabels' => $topProductLabels,
            'topProductRevenue' => $topProductRevenue,
            'topProductQty' => $topProductQty,
            'labelSales' => __('Sales (₱)'),
            'labelOrders' => __('Orders'),
            'labelPos' => __('POS'),
            'labelPreorders' => __('Pre-orders'),
            'labelRevenue' => __('Revenue (₱)'),
            'labelQtySold' => __('Qty Sold'),
        ])
    </script>
    @vite('resources/js/admin-chartsale.js')
</x-app-layout>
