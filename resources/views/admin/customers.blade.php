<x-app-layout>
    <x-slot name="header">
        <div class="flex items-center justify-between">
            <div>
                <h2 class="font-serif font-bold text-2xl text-bark-600 leading-tight">
                    {{ __('Customers') }}
                </h2>
                <p class="mt-1 text-sm text-muted">{{ __('Customer insights and order analytics') }}</p>
            </div>
            <button @click="refreshData()" :disabled="loading" 
                class="inline-flex items-center gap-2 px-4 py-2 bg-bark-300 hover:bg-bark-400 disabled:opacity-50 text-cream-50 font-semibold text-sm rounded-xl shadow-sm transition duration-200"
                title="Refresh data">
                <svg class="w-4 h-4" :class="{ 'animate-spin': loading }" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M4 4v5h.582m15.356 2A8.001 8.001 0 004.582 9m0 0H9m11 11v-5h-.581m0 0a8.003 8.003 0 01-15.357-2m15.357 2H15"/>
                </svg>
                Refresh
            </button>
        </div>
    </x-slot>

    <div class="flex">
        {{-- Sidebar --}}
        <x-admin-sidebar />

        {{-- Main Content --}}
        <div class="flex-1 py-8" x-data="customerAnalytics()" x-init="init()">
        <div class="max-w-6xl mx-auto px-4 sm:px-6 lg:px-8 space-y-8">

            {{-- Real-Time Monitoring Dashboard --}}
            <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
                
                {{-- Real-Time Total Spent Card --}}
                <div class="lg:col-span-2 bg-gradient-to-br from-gold-400 to-bark-300 rounded-2xl shadow-lg shadow-bark-200/20 p-8 text-white overflow-hidden relative">
                    <div class="absolute top-0 right-0 w-40 h-40 bg-white/10 rounded-full -mr-20 -mt-20"></div>
                    <div class="absolute bottom-0 left-0 w-32 h-32 bg-white/10 rounded-full -ml-16 -mb-16"></div>
                    
                    <div class="relative z-10">
                        <div class="flex items-center justify-between mb-8">
                            <div class="flex-1">
                                <p class="text-white/90 text-sm font-semibold uppercase tracking-wide">Recent Customers Total Revenue</p>
                                <h2 class="font-serif text-5xl font-bold mt-3" :class="{ 'animate-pulse': updateAnimation }" x-text="formatTotalSpent()"></h2>
                            </div>
                            <div class="text-right">
                                <div class="inline-flex items-center gap-2 px-4 py-2 bg-white/20 rounded-xl backdrop-blur-sm">
                                    <div class="w-2.5 h-2.5 rounded-full bg-white animate-pulse"></div>
                                    <span class="font-bold text-sm text-white">Live</span>
                                </div>
                            </div>
                        </div>
                        
                        <div class="grid grid-cols-2 gap-6 mt-8 pt-8 border-t border-white/20">
                            <div>
                                <p class="text-white/70 text-xs font-medium uppercase tracking-wide">Total Orders</p>
                                <p class="text-4xl font-bold mt-2 text-white" x-text="totalOrdersDisplay"></p>
                            </div>
                            <div>
                                <p class="text-white/70 text-xs font-medium uppercase tracking-wide">Active Customers</p>
                                <p class="text-4xl font-bold mt-2 text-white" x-text="recentCustomers.length"></p>
                            </div>
                        </div>
                    </div>
                </div>

                {{-- Connection & Status Card --}}
                <div class="bg-cream-50 rounded-2xl ring-1 ring-bark-200/10 shadow-sm shadow-bark-200/5 p-6">
                    <h3 class="font-serif font-bold text-2xl text-bark-600 mb-6">System Status</h3>
                    
                    <div class="space-y-4">
                        <!-- Connection Status -->
                        <div class="bg-gradient-to-br from-cream-100 to-cream-50 rounded-lg p-4 ring-1 ring-bark-200/20">
                            <p class="text-xs text-bark-500 uppercase tracking-widest font-bold mb-3 block">Connection Status</p>
                            <div class="flex items-center gap-3 h-12">
                                <div class="w-3 h-3 rounded-full animate-pulse flex-shrink-0" :class="getConnectionStatusColor()"></div>
                                <span class="font-bold text-xl" :class="connectionStatus === 'connected' ? 'text-leaf-600' : connectionStatus === 'error' ? 'text-red-600' : 'text-yellow-600'" x-text="getConnectionStatusText()"></span>
                            </div>
                        </div>
                        
                        <!-- Last Update -->
                        <div class="bg-gradient-to-br from-cream-100 to-cream-50 rounded-lg p-4 ring-1 ring-bark-200/20">
                            <p class="text-xs text-bark-500 uppercase tracking-widest font-bold mb-3 block">Last Updated</p>
                            <p class="text-xl font-bold text-bark-600 h-8 flex items-center" x-text="getLastUpdateText()"></p>
                        </div>
                        
                        <!-- Refresh Rate -->
                        <div class="bg-gradient-to-br from-cream-100 to-cream-50 rounded-lg p-4 ring-1 ring-bark-200/20">
                            <p class="text-xs text-bark-500 uppercase tracking-widest font-bold mb-3 block">Refresh Interval</p>
                            <p class="text-xl font-bold text-bark-600 h-8 flex items-center">Every 10s</p>
                        </div>
                        
                        <!-- Refresh Button -->
                        <button @click="refreshData()" :disabled="loading" 
                            class="w-full inline-flex items-center justify-center gap-2 px-4 py-3 bg-bark-300 hover:bg-bark-400 disabled:opacity-60 disabled:cursor-not-allowed text-white font-bold text-base rounded-lg shadow-md hover:shadow-lg transition-all duration-200 mt-4">
                            <svg class="w-5 h-5" :class="{ 'animate-spin': loading }" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" d="M4 4v5h.582m15.356 2A8.001 8.001 0 004.582 9m0 0H9m11 11v-5h-.581m0 0a8.003 8.003 0 01-15.357-2m15.357 2H15"/>
                            </svg>
                            <span class="text-base font-bold" x-text="loading ? 'Refreshing...' : 'Refresh Now'"></span>
                        </button>
                    </div>
                </div>
            </div>

            {{-- Customer Distribution Map --}}
            <div class="bg-cream-50 rounded-2xl ring-1 ring-bark-200/10 shadow-sm shadow-bark-200/5 overflow-hidden">
                <div class="px-6 py-5 border-b border-bark-200/10">
                    <h3 class="font-serif font-bold text-lg text-bark-600">Customer Distribution</h3>
                    <p class="text-xs text-muted mt-1">Top customers by total spending</p>
                </div>
                
                <div class="p-6">
                    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-5 gap-3">
                        <template x-for="(customer, index) in recentCustomers.slice(0, 5)" :key="customer.id">
                            <div class="p-4 bg-white rounded-xl ring-1 ring-bark-200/10 hover:ring-bark-300/20 hover:shadow-md transition-all">
                                <div class="flex items-center justify-between mb-3">
                                    <div class="w-8 h-8 rounded-full text-center leading-8 font-bold text-sm text-white" :class="index === 0 ? 'bg-gold-500' : index === 1 ? 'bg-bark-300' : 'bg-leaf-400'" x-text="index + 1"></div>
                                </div>
                                <p class="font-semibold text-bark-600 text-sm truncate" x-text="customer.name"></p>
                                <p class="text-xs text-muted mt-1 truncate" x-text="customer.email"></p>
                                
                                <div class="mt-2 space-y-1.5">
                                    <div class="flex items-center gap-1.5 text-xs">
                                        <svg class="w-3.5 h-3.5 text-bark-400" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" d="M3 5a2 2 0 012-2h3.28a1 1 0 01.948.684l1.498 4.493a1 1 0 01-.502 1.21l-2.257 1.13a11.042 11.042 0 005.516 5.516l1.13-2.257a1 1 0 011.21-.502l4.493 1.498a1 1 0 01.684.949V19a2 2 0 01-2 2h-1C9.716 21 3 14.284 3 6V5z"/>
                                        </svg>
                                        <span class="text-muted truncate" x-text="customer.phone || 'N/A'"></span>
                                    </div>
                                    <div class="flex items-center gap-1.5 text-xs">
                                        <svg class="w-3.5 h-3.5 text-bark-400" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" d="M16 11V7a4 4 0 00-8 0v4M5 9h14l1 12H4L5 9z"/>
                                        </svg>
                                        <span class="font-semibold text-bark-600" x-text="`${customer.total_orders} orders`"></span>
                                    </div>
                                </div>
                                
                                <div class="mt-3 pt-3 border-t border-bark-200/10">
                                    <p class="text-lg font-bold text-bark-600" x-text="customer.total_spent_formatted"></p>
                                    <p class="text-xs text-leaf-600 font-semibold mt-1" x-text="customer.last_order_formatted"></p>
                                </div>
                            </div>
                        </template>
                    </div>
                </div>
            </div>

            {{-- KPI Summary Cards --}}
            <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-5">
                <div class="bg-cream-50 rounded-2xl ring-1 ring-bark-200/10 shadow-sm shadow-bark-200/5 p-6 hover:shadow-md hover:ring-bark-200/20 transition-all duration-200">
                    <div class="flex items-center gap-4">
                        <div class="flex-shrink-0 w-12 h-12 rounded-xl bg-bark-100 flex items-center justify-center">
                            <svg class="w-6 h-6 text-bark-400" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24" aria-hidden="true">
                                <path stroke-linecap="round" stroke-linejoin="round" d="M15 19.128a9.38 9.38 0 002.625.372 9.337 9.337 0 004.121-.952 4.125 4.125 0 00-7.533-2.493M15 19.128v-.003c0-1.113-.285-2.16-.786-3.07M15 19.128v.106A12.318 12.318 0 018.624 21c-2.331 0-4.512-.645-6.374-1.766l-.001-.109a6.375 6.375 0 0111.964-3.07M12 6.375a3.375 3.375 0 11-6.75 0 3.375 3.375 0 016.75 0zm8.25 2.25a2.625 2.625 0 11-5.25 0 2.625 2.625 0 015.25 0z"/>
                            </svg>
                        </div>
                        <div class="flex-1">
                            <p class="text-sm font-medium text-muted">{{ __('Total Customers') }}</p>
                            <p class="text-2xl font-bold text-bark-600">{{ $totalCustomers }}</p>
                            <p class="text-xs text-leaf-500 font-semibold mt-0.5">{{ $newCustomersThisMonth }} {{ __('new this month') }}</p>
                        </div>
                    </div>
                </div>

                <div class="bg-cream-50 rounded-2xl ring-1 ring-bark-200/10 shadow-sm shadow-bark-200/5 p-6 hover:shadow-md hover:ring-bark-200/20 transition-all duration-200">
                    <div class="flex items-center gap-4">
                        <div class="flex-shrink-0 w-12 h-12 rounded-xl bg-leaf-300/30 flex items-center justify-center">
                            <svg class="w-6 h-6 text-leaf-500" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24" aria-hidden="true">
                                <path stroke-linecap="round" stroke-linejoin="round" d="M19 7.5v3m0 0v3m0-3h3m-3 0h-3m-2.25-4.125a3.375 3.375 0 11-6.75 0 3.375 3.375 0 016.75 0zM4 19.235v-.11a6.375 6.375 0 0112.75 0v.109A12.318 12.318 0 0110.374 21c-2.331 0-4.512-.645-6.374-1.766z"/>
                            </svg>
                        </div>
                        <div class="flex-1">
                            <p class="text-sm font-medium text-muted">{{ __('Repeat Customers') }}</p>
                            <p class="text-2xl font-bold text-bark-600">{{ $repeatCustomers }}</p>
                            <p class="text-xs mt-0.5">
                                <span class="text-leaf-500 font-semibold">{{ $repeatRate }}%</span>
                                <span class="text-muted">{{ __('repeat rate') }}</span>
                            </p>
                        </div>
                    </div>
                </div>

                <div class="bg-cream-50 rounded-2xl ring-1 ring-bark-200/10 shadow-sm shadow-bark-200/5 p-6 hover:shadow-md hover:ring-bark-200/20 transition-all duration-200">
                    <div class="flex items-center gap-4">
                        <div class="flex-shrink-0 w-12 h-12 rounded-xl bg-gold-300/30 flex items-center justify-center">
                            <svg class="w-6 h-6 text-gold-500" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24" aria-hidden="true">
                                <path stroke-linecap="round" stroke-linejoin="round" d="M12 6v12m-3-2.818l.879.659c1.171.879 3.07.879 4.242 0 1.172-.879 1.172-2.303 0-3.182C13.536 12.219 12.768 12 12 12c-.725 0-1.45-.22-2.003-.659-1.106-.879-1.106-2.303 0-3.182s2.9-.879 4.006 0l.415.33M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/>
                            </svg>
                        </div>
                        <div class="flex-1">
                            <p class="text-sm font-medium text-muted">{{ __('Avg. Order Value') }}</p>
                            <p class="text-2xl font-bold text-bark-600">₱{{ number_format($avgOrderValue, 2) }}</p>
                        </div>
                    </div>
                </div>

                <div class="bg-cream-50 rounded-2xl ring-1 ring-bark-200/10 shadow-sm shadow-bark-200/5 p-6 hover:shadow-md hover:ring-bark-200/20 transition-all duration-200">
                    <div class="flex items-center gap-4">
                        <div class="flex-shrink-0 w-12 h-12 rounded-xl bg-bark-100 flex items-center justify-center">
                            <svg class="w-6 h-6 text-bark-400" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24" aria-hidden="true">
                                <path stroke-linecap="round" stroke-linejoin="round" d="M3 3v18h18M9 17V9m4 8v-5m4 5V5"/>
                            </svg>
                        </div>
                        <div class="flex-1">
                            <p class="text-sm font-medium text-muted">{{ __('New This Month') }}</p>
                            <p class="text-2xl font-bold text-bark-600">{{ $newCustomersThisMonth }}</p>
                            <p class="text-xs text-muted mt-0.5">{{ __('joined recently') }}</p>
                        </div>
                    </div>
                </div>
            </div>

            <div class="grid grid-cols-1 lg:grid-cols-2 gap-8">

                {{-- Customer Growth Chart --}}
                <div class="bg-cream-50 rounded-2xl ring-1 ring-bark-200/10 shadow-sm shadow-bark-200/5 overflow-hidden">
                    <div class="px-6 py-5 border-b border-bark-200/10 flex items-center justify-between">
                        <div>
                            <h3 class="font-serif font-bold text-lg text-bark-600">{{ __('Customer Growth') }}</h3>
                            <p class="text-xs text-muted mt-1">{{ __('New customers over the last 6 months') }}</p>
                        </div>
                        <div x-show="loading" class="flex items-center gap-2">
                            <svg class="w-4 h-4 animate-spin text-bark-300" fill="none" viewBox="0 0 24 24">
                                <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle>
                                <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4z"></path>
                            </svg>
                        </div>
                    </div>
                    <div class="p-6" x-show="!loading">
                        <canvas id="customerGrowthChart" height="200"></canvas>
                    </div>
                    <div x-show="loading" class="p-6 flex items-center justify-center min-h-[300px]">
                        <div class="text-center">
                            <svg class="w-8 h-8 animate-spin text-bark-300 mx-auto mb-2" fill="none" viewBox="0 0 24 24">
                                <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle>
                                <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4z"></path>
                            </svg>
                            <p class="text-sm text-muted">{{ __('Loading chart...') }}</p>
                        </div>
                    </div>
                </div>

                {{-- Order Frequency Distribution --}}
                <div class="bg-cream-50 rounded-2xl ring-1 ring-bark-200/10 shadow-sm shadow-bark-200/5 overflow-hidden">
                    <div class="px-6 py-5 border-b border-bark-200/10 flex items-center justify-between">
                        <div>
                            <h3 class="font-serif font-bold text-lg text-bark-600">{{ __('Order Frequency') }}</h3>
                            <p class="text-xs text-muted mt-1">{{ __('Distribution of customer order counts') }}</p>
                        </div>
                        <div x-show="loading" class="flex items-center gap-2">
                            <svg class="w-4 h-4 animate-spin text-bark-300" fill="none" viewBox="0 0 24 24">
                                <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle>
                                <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4z"></path>
                            </svg>
                        </div>
                    </div>
                    <div class="p-6" x-show="!loading">
                        <canvas id="frequencyChart" height="200"></canvas>
                    </div>
                    <div x-show="loading" class="p-6 flex items-center justify-center min-h-[300px]">
                        <div class="text-center">
                            <svg class="w-8 h-8 animate-spin text-bark-300 mx-auto mb-2" fill="none" viewBox="0 0 24 24">
                                <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle>
                                <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4z"></path>
                            </svg>
                            <p class="text-sm text-muted">{{ __('Loading chart...') }}</p>
                        </div>
                    </div>
                </div>
            </div>

            {{-- Top Customers Table --}}
            <div class="bg-cream-50 rounded-2xl ring-1 ring-bark-200/10 shadow-sm shadow-bark-200/5 overflow-hidden">
                <div class="px-6 py-5 border-b border-bark-200/10 flex flex-col sm:flex-row items-start sm:items-center justify-between gap-4">
                    <div>
                        <h3 class="font-serif font-bold text-lg text-bark-600">{{ __('Top Customers') }}</h3>
                        <p class="text-xs text-muted mt-1">{{ __('Ranked by total spend') }}</p>
                    </div>
                    <span class="text-xs font-semibold text-muted bg-cream-300/60 px-3 py-1.5 rounded-full whitespace-nowrap">
                        {{ __('Top') }} {{ $topCustomers->count() }}
                    </span>
                </div>

                @if($topCustomers->isEmpty())
                    <div class="p-12 text-center">
                        <svg class="mx-auto w-12 h-12 text-bark-200 mb-3" fill="none" stroke="currentColor" stroke-width="1.5" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M15 19.128a9.38 9.38 0 002.625.372 9.337 9.337 0 004.121-.952 4.125 4.125 0 00-7.533-2.493M15 19.128v-.003c0-1.113-.285-2.16-.786-3.07M15 19.128v.106A12.318 12.318 0 018.624 21c-2.331 0-4.512-.645-6.374-1.766l-.001-.109a6.375 6.375 0 0111.964-3.07M12 6.375a3.375 3.375 0 11-6.75 0 3.375 3.375 0 016.75 0zm8.25 2.25a2.625 2.625 0 11-5.25 0 2.625 2.625 0 015.25 0z"/>
                        </svg>
                        <p class="text-muted font-medium">{{ __('No customer data available yet') }}</p>
                        <p class="text-sm text-muted mt-1">{{ __('Customer data will appear as orders are placed.') }}</p>
                    </div>
                @else
                    <div class="overflow-x-auto">
                        <table class="w-full text-sm" role="grid" aria-label="Top customers by spend">
                            <thead>
                                <tr class="bg-cream-200/40">
                                    <th class="px-6 py-3 text-left font-semibold text-muted text-xs uppercase tracking-wider">#</th>
                                    <th class="px-6 py-3 text-left font-semibold text-muted text-xs uppercase tracking-wider">{{ __('Customer') }}</th>
                                    <th class="px-6 py-3 text-left font-semibold text-muted text-xs uppercase tracking-wider hidden sm:table-cell">{{ __('Email') }}</th>
                                    <th class="px-6 py-3 text-right font-semibold text-muted text-xs uppercase tracking-wider">{{ __('Orders') }}</th>
                                    <th class="px-6 py-3 text-right font-semibold text-muted text-xs uppercase tracking-wider">{{ __('Total Spent') }}</th>
                                    <th class="px-6 py-3 text-right font-semibold text-muted text-xs uppercase tracking-wider hidden md:table-cell">{{ __('Avg Order') }}</th>
                                    <th class="px-6 py-3 text-right font-semibold text-muted text-xs uppercase tracking-wider hidden lg:table-cell">{{ __('Last Order') }}</th>
                                    <th class="px-6 py-3 text-right font-semibold text-muted text-xs uppercase tracking-wider">{{ __('Action') }}</th>
                                </tr>
                            </thead>
                            <tbody class="divide-y divide-bark-200/10">
                                @foreach($topCustomers as $index => $customer)
                                    <tr class="hover:bg-cream-100/50 transition duration-150">
                                        <td class="px-6 py-3.5">
                                            <span class="inline-flex items-center justify-center w-7 h-7 rounded-full text-xs font-bold
                                                @if($index < 3)
                                                    bg-gold-300/30 text-gold-500
                                                @else
                                                    bg-cream-200 text-bark-400
                                                @endif">
                                                {{ $index + 1 }}
                                            </span>
                                        </td>
                                        <td class="px-6 py-3.5">
                                            <span class="font-semibold text-bark-600">{{ $customer->name }}</span>
                                        </td>
                                        <td class="px-6 py-3.5 text-muted hidden sm:table-cell text-xs">
                                            <span title="{{ $customer->email }}" class="truncate block">{{ $customer->email }}</span>
                                        </td>
                                        <td class="px-6 py-3.5 text-right">
                                            <span class="inline-flex px-2.5 py-1 bg-cream-200/60 text-bark-500 text-xs font-semibold rounded-lg">
                                                {{ $customer->total_orders ?? 0 }}
                                            </span>
                                        </td>
                                        <td class="px-6 py-3.5 text-right font-bold text-bark-600">
                                            ₱{{ number_format($customer->total_spent ?? 0, 2) }}
                                        </td>
                                        <td class="px-6 py-3.5 text-right text-muted hidden md:table-cell text-sm">
                                            ₱{{ number_format($customer->avg_order_value ?? 0, 2) }}
                                        </td>
                                        <td class="px-6 py-3.5 text-right text-xs text-muted hidden lg:table-cell whitespace-nowrap">
                                            {{ $customer->last_order_at ? \Carbon\Carbon::parse($customer->last_order_at)->diffForHumans() : '—' }}
                                        </td>
                                        <td class="px-6 py-3.5 text-right">
                                            <a href="{{ route('admin.customers.show', $customer) }}"
                                               class="p-2 rounded-xl hover:bg-cream-200/50 text-bark-300 hover:text-bark-500 transition duration-150 inline-flex"
                                               aria-label="View {{ $customer->name }} details"
                                               title="View Details">
                                                <svg class="w-4 h-4" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                                                    <path stroke-linecap="round" stroke-linejoin="round" d="M2.036 12.322a1.012 1.012 0 010-.639C3.423 7.51 7.36 4.5 12 4.5c4.638 0 8.573 3.007 9.963 7.178.07.207.07.431 0 .639C20.577 16.49 16.64 19.5 12 19.5c-4.638 0-8.573-3.007-9.963-7.178z"/>
                                                    <path stroke-linecap="round" stroke-linejoin="round" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"/>
                                                </svg>
                                            </a>
                                        </td>
                                    </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                @endif
            </div>

            {{-- Recent Customers Table --}}
            <div class="bg-cream-50 rounded-2xl ring-1 ring-bark-200/10 shadow-sm shadow-bark-200/5 overflow-hidden">
                <div class="px-6 py-5 border-b border-bark-200/10 flex flex-col sm:flex-row items-start sm:items-center justify-between gap-4">
                    <div class="flex-1">
                        <div class="flex items-center gap-3 mb-2">
                            <h3 class="font-serif font-bold text-lg text-bark-600">{{ __('Recent Customers') }}</h3>
                            <!-- Connection Status Indicator -->
                            <div class="flex items-center gap-1.5 px-2.5 py-1 rounded-lg" :class="connectionStatus === 'connected' ? 'bg-leaf-100' : connectionStatus === 'error' ? 'bg-red-100' : 'bg-yellow-100'">
                                <div class="w-2 h-2 rounded-full animate-pulse" :class="getConnectionStatusColor()"></div>
                                <span class="text-xs font-semibold" :class="connectionStatus === 'connected' ? 'text-leaf-700' : connectionStatus === 'error' ? 'text-red-700' : 'text-yellow-700'" x-text="getConnectionStatusText()"></span>
                            </div>
                        </div>
                        <div class="flex items-center gap-4">
                            <p class="text-xs text-muted">{{ __('Customers with recent orders') }}</p>
                            <span class="text-xs px-2 py-1 bg-cream-200/50 text-bark-600 rounded-full" x-text="`Last update: ${getLastUpdateText()}`"></span>
                        </div>
                    </div>
                    <div class="w-full sm:w-auto flex gap-3">
                        <div class="flex-1 sm:flex-initial relative">
                            <svg class="absolute left-3 top-1/2 -translate-y-1/2 w-4 h-4 text-bark-200 pointer-events-none" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"/>
                            </svg>
                            <input type="text" x-model="recentSearch" placeholder="{{ __('Search customers...') }}"
                                class="w-full pl-10 pr-4 py-2 rounded-xl border border-bark-200/20 bg-white text-sm text-bark-600 placeholder-bark-200 focus:outline-none focus:ring-2 focus:ring-bark-300/40 focus:border-bark-300 transition-all">
                        </div>
                    </div>
                </div>

                <!-- Error Message -->
                <template x-if="errorMessage && connectionStatus === 'error'">
                    <div class="px-6 py-3 bg-red-50 border-b border-red-200/20">
                        <p class="text-sm text-red-700"><strong>Error:</strong> <span x-text="errorMessage"></span></p>
                    </div>
                </template>

                <template x-if="recentLoading">
                    <div class="p-8">
                        <div class="space-y-3">
                            <div class="h-12 bg-gradient-to-r from-cream-100 via-cream-50 to-cream-100 rounded-lg animate-pulse"></div>
                            <div class="h-12 bg-gradient-to-r from-cream-100 via-cream-50 to-cream-100 rounded-lg animate-pulse"></div>
                            <div class="h-12 bg-gradient-to-r from-cream-100 via-cream-50 to-cream-100 rounded-lg animate-pulse"></div>
                            <div class="h-12 bg-gradient-to-r from-cream-100 via-cream-50 to-cream-100 rounded-lg animate-pulse"></div>
                            <div class="h-12 bg-gradient-to-r from-cream-100 via-cream-50 to-cream-100 rounded-lg animate-pulse"></div>
                        </div>
                    </div>
                </template>

                <template x-if="!recentLoading && recentCustomers.length === 0">
                    <div class="p-12 text-center">
                        <svg class="mx-auto w-12 h-12 text-bark-200 mb-3" fill="none" stroke="currentColor" stroke-width="1.5" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M12 8v4m0 4v.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/>
                        </svg>
                        <p class="text-muted font-medium">{{ __('No recent orders') }}</p>
                        <p class="text-sm text-muted mt-1">{{ __('Orders will appear here as customers place them.') }}</p>
                    </div>
                </template>

                <template x-if="!recentLoading && recentCustomers.length > 0">
                    <div class="overflow-x-auto">
                        <table class="w-full text-sm" role="grid" aria-label="Recent customers">
                            <thead>
                                <tr class="bg-cream-200/40">
                                    <th class="px-6 py-3 text-left font-semibold text-muted text-xs uppercase tracking-wider">{{ __('Customer') }}</th>
                                    <th class="px-6 py-3 text-left font-semibold text-muted text-xs uppercase tracking-wider hidden sm:table-cell">{{ __('Email') }}</th>
                                    <th class="px-6 py-3 text-right font-semibold text-muted text-xs uppercase tracking-wider">{{ __('Orders') }}</th>
                                    <th class="px-6 py-3 text-right font-semibold text-muted text-xs uppercase tracking-wider">{{ __('Total Spent') }}</th>
                                    <th class="px-6 py-3 text-right font-semibold text-muted text-xs uppercase tracking-wider">{{ __('Last Order') }}</th>
                                </tr>
                            </thead>
                            <tbody class="divide-y divide-bark-200/10">
                                <template x-for="customer in getFilteredCustomers()" :key="customer.id">
                                    <tr class="hover:bg-cream-100/50 transition duration-150" :class="{ 'bg-leaf-50': customer.isNew }">
                                        <td class="px-6 py-3.5">
                                            <div class="flex items-center gap-2">
                                                <span class="font-semibold text-bark-600" x-text="customer.name"></span>
                                                <template x-if="customer.isNew">
                                                    <span class="inline-flex px-2 py-0.5 bg-leaf-100 text-leaf-700 text-xs font-bold rounded-full">NEW</span>
                                                </template>
                                            </div>
                                        </td>
                                        <td class="px-6 py-3.5 text-muted hidden sm:table-cell text-xs">
                                            <span :title="customer.email" class="truncate block" x-text="customer.email"></span>
                                        </td>
                                        <td class="px-6 py-3.5 text-right">
                                            <span class="inline-flex px-2.5 py-1 bg-cream-200/60 text-bark-500 text-xs font-semibold rounded-lg" x-text="customer.total_orders || 0"></span>
                                        </td>
                                        <td class="px-6 py-3.5 text-right font-bold text-bark-600" x-text="customer.total_spent_formatted"></td>
                                        <td class="px-6 py-3.5 text-right text-xs text-muted whitespace-nowrap" x-text="customer.last_order_formatted"></td>
                                    </tr>
                                </template>
                                <template x-if="getFilteredCustomers().length === 0 && recentCustomers.length > 0">
                                    <tr>
                                        <td colspan="5" class="px-6 py-8 text-center text-muted">
                                            No customers match your search.
                                        </td>
                                    </tr>
                                </template>
                            </tbody>
                        </table>
                    </div>
                </template>
            </div>
        </div>
        </div>
        </div>

    @push('scripts')
        @vite('resources/js/customer-analytics.js')
    @endpush
</x-app-layout>
