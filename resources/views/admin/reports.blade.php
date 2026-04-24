<x-app-layout>
    <x-slot name="header">
        <div>
            <h2 class="font-serif font-bold text-2xl text-bark-600 leading-tight">
                {{ __('Reports') }}
            </h2>
            <p class="mt-1 text-sm text-muted">{{ __('View sales performance and inventory status') }}</p>
        </div>
    </x-slot>

    <div class="flex">
        {{-- Sidebar --}}
        <x-admin-sidebar />

        {{-- Main Content --}}
        <div x-data="adminReports()" x-init="init()" class="flex-1 py-8">
        <div class="max-w-6xl mx-auto px-4 sm:px-6 lg:px-8 space-y-8">

            {{-- Error Message --}}
            <div x-show="errorMsg" x-transition x-cloak
                class="p-4 rounded-2xl bg-red-100 border border-red-300/30 text-red-600 font-semibold text-sm flex items-center gap-3">
                <svg class="w-5 h-5 flex-shrink-0" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M12 9v3.75m9.303 3.376c-.866 1.5.217 3.374 1.948 3.374H2.697c1.73 0 2.813-1.874 1.948-3.374L13.949 3.378c-.866-1.5-3.032-1.5-3.898 0L2.697 16.126zM12 15.75h.007v.008H12v-.008z"/>
                </svg>
                <span x-text="errorMsg"></span>
            </div>

            {{-- Date Range Filter --}}
            <div class="bg-cream-50 rounded-2xl ring-1 ring-bark-200/10 shadow-sm shadow-bark-200/5 p-6">
                <h3 class="font-serif font-bold text-lg text-bark-600 mb-4">{{ __('Sales Report') }}</h3>
                <div class="flex flex-col sm:flex-row gap-4 items-end">
                    <div class="flex-1">
                        <label class="block text-sm font-semibold text-bark-500 mb-1.5">Start Date</label>
                        <input type="date" x-model="startDate"
                            class="w-full px-4 py-3 rounded-2xl border border-bark-200/20 bg-white text-bark-600 focus:outline-none focus:ring-2 focus:ring-bark-300/40 focus:border-bark-300 transition-all text-sm">
                    </div>
                    <div class="flex-1">
                        <label class="block text-sm font-semibold text-bark-500 mb-1.5">End Date</label>
                        <input type="date" x-model="endDate"
                            class="w-full px-4 py-3 rounded-2xl border border-bark-200/20 bg-white text-bark-600 focus:outline-none focus:ring-2 focus:ring-bark-300/40 focus:border-bark-300 transition-all text-sm">
                    </div>
                    <button @click="fetchSalesReport()" :disabled="loadingSales"
                        class="inline-flex items-center gap-2 px-6 py-3 bg-bark-300 hover:bg-bark-400 disabled:opacity-50 text-cream-50 font-semibold text-sm rounded-2xl shadow-sm transition duration-200">
                        <svg x-show="loadingSales" class="animate-spin h-4 w-4" fill="none" viewBox="0 0 24 24">
                            <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle>
                            <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4z"></path>
                        </svg>
                        Generate Report
                    </button>
                </div>
            </div>

            {{-- Sales KPIs --}}
            <div class="grid grid-cols-1 sm:grid-cols-3 gap-5">
                <div class="bg-cream-50 rounded-2xl ring-1 ring-bark-200/10 shadow-sm shadow-bark-200/5 p-6">
                    <div class="flex items-center gap-4">
                        <div class="flex-shrink-0 w-12 h-12 rounded-xl bg-leaf-300/30 flex items-center justify-center">
                            <svg class="w-6 h-6 text-leaf-500" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" d="M12 6v12m-3-2.818l.879.659c1.171.879 3.07.879 4.242 0 1.172-.879 1.172-2.303 0-3.182C13.536 12.219 12.768 12 12 12c-.725 0-1.45-.22-2.003-.659-1.106-.879-1.106-2.303 0-3.182s2.9-.879 4.006 0l.415.33M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/>
                            </svg>
                        </div>
                        <div>
                            <p class="text-sm font-medium text-muted">{{ __('Total Sales') }}</p>
                            <p class="text-2xl font-bold text-bark-600">₱<span x-text="formatNumber(salesData.total_sales)"></span></p>
                        </div>
                    </div>
                </div>

                <div class="bg-cream-50 rounded-2xl ring-1 ring-bark-200/10 shadow-sm shadow-bark-200/5 p-6">
                    <div class="flex items-center gap-4">
                        <div class="flex-shrink-0 w-12 h-12 rounded-xl bg-bark-100 flex items-center justify-center">
                            <svg class="w-6 h-6 text-bark-400" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" d="M2.25 3h1.386c.51 0 .955.343 1.087.835l.383 1.437M7.5 14.25a3 3 0 00-3 3h15.75m-12.75-3h11.218c1.121-2.3 2.1-4.684 2.924-7.138a60.114 60.114 0 00-16.536-1.84M7.5 14.25L5.106 5.272M6 20.25a.75.75 0 11-1.5 0 .75.75 0 011.5 0zm12.75 0a.75.75 0 11-1.5 0 .75.75 0 011.5 0z"/>
                            </svg>
                        </div>
                        <div>
                            <p class="text-sm font-medium text-muted">{{ __('Total Orders') }}</p>
                            <p class="text-2xl font-bold text-bark-600" x-text="salesData.total_orders"></p>
                        </div>
                    </div>
                </div>

                <div class="bg-cream-50 rounded-2xl ring-1 ring-bark-200/10 shadow-sm shadow-bark-200/5 p-6">
                    <div class="flex items-center gap-4">
                        <div class="flex-shrink-0 w-12 h-12 rounded-xl bg-gold-300/30 flex items-center justify-center">
                            <svg class="w-6 h-6 text-gold-500" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" d="M3 3v18h18M9 17V9m4 8v-5m4 5V5"/>
                            </svg>
                        </div>
                        <div>
                            <p class="text-sm font-medium text-muted">{{ __('Avg. Order Value') }}</p>
                            <p class="text-2xl font-bold text-bark-600">₱<span x-text="formatNumber(salesData.average_order)"></span></p>
                        </div>
                    </div>
                </div>
            </div>

            {{-- Inventory Status --}}
            <div class="bg-cream-50 rounded-2xl ring-1 ring-bark-200/10 shadow-sm shadow-bark-200/5 overflow-hidden">
                <div class="px-6 py-5 border-b border-bark-200/10 flex items-center justify-between">
                    <h3 class="font-serif font-bold text-lg text-bark-600">{{ __('Inventory Status') }}</h3>
                    <span class="text-xs font-semibold text-muted bg-cream-300/60 px-2.5 py-1 rounded-full"
                          x-text="inventoryData.length + ' products'"></span>
                </div>

                {{-- Loading --}}
                <div x-show="loadingInventory" x-transition class="flex justify-center py-12">
                    <svg class="animate-spin h-8 w-8 text-bark-300" fill="none" viewBox="0 0 24 24">
                        <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle>
                        <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4z"></path>
                    </svg>
                </div>

                <div x-show="!loadingInventory" x-cloak>
                    {{-- Inventory Summary --}}
                    <div class="grid grid-cols-2 gap-4 p-6 border-b border-bark-200/10">
                        <div class="text-center">
                            <p class="text-xs font-semibold text-muted uppercase tracking-wider">OK Stock</p>
                            <p class="text-2xl font-bold text-leaf-500 mt-1" x-text="inventoryData.filter(i => i.status === 'ok').length"></p>
                        </div>
                        <div class="text-center">
                            <p class="text-xs font-semibold text-muted uppercase tracking-wider">Low Stock</p>
                            <p class="text-2xl font-bold text-red-500 mt-1" x-text="inventoryData.filter(i => i.status === 'low').length"></p>
                        </div>
                    </div>

                    {{-- Low Stock Items Table --}}
                    <template x-if="inventoryData.filter(i => i.status === 'low').length > 0">
                        <div>
                            <div class="px-6 py-3 bg-red-50/50">
                                <p class="text-xs font-bold text-red-500 uppercase tracking-wider">Low Stock Items — Needs Attention</p>
                            </div>
                            <div class="overflow-x-auto">
                                <table class="w-full text-sm">
                                    <thead>
                                        <tr class="bg-cream-200/40">
                                            <th class="px-6 py-3 text-left font-semibold text-muted text-xs uppercase tracking-wider">Product</th>
                                            <th class="px-6 py-3 text-right font-semibold text-muted text-xs uppercase tracking-wider">Current Stock</th>
                                            <th class="px-6 py-3 text-right font-semibold text-muted text-xs uppercase tracking-wider">Min Level</th>
                                        </tr>
                                    </thead>
                                    <tbody class="divide-y divide-bark-200/10">
                                        <template x-for="item in inventoryData.filter(i => i.status === 'low')" :key="item.name">
                                            <tr class="hover:bg-cream-100/50 transition duration-150">
                                                <td class="px-6 py-3">
                                                    <span class="font-semibold text-bark-600" x-text="item.name"></span>
                                                </td>
                                                <td class="px-6 py-3 text-right">
                                                    <span class="font-bold text-red-500" x-text="item.stock"></span>
                                                </td>
                                                <td class="px-6 py-3 text-right">
                                                    <span class="text-muted" x-text="item.min_level"></span>
                                                </td>
                                            </tr>
                                        </template>
                                    </tbody>
                                </table>
                            </div>
                        </div>
                    </template>

                    {{-- All Good State --}}
                    <div x-show="inventoryData.filter(i => i.status === 'low').length === 0 && inventoryData.length > 0" class="p-10 text-center">
                        <div class="text-4xl mb-3">✅</div>
                        <p class="font-serif font-bold text-bark-500 text-lg">All products are well-stocked</p>
                        <p class="text-muted text-sm mt-1">No items below minimum stock level.</p>
                    </div>

                    {{-- Empty State --}}
                    <div x-show="inventoryData.length === 0 && !loadingInventory" class="p-10 text-center">
                        <div class="text-4xl mb-3">📦</div>
                        <p class="text-muted font-medium">No inventory data available.</p>
                    </div>
                </div>
            </div>

            {{-- Dashboard Quick Stats --}}
            <div class="bg-cream-50 rounded-2xl ring-1 ring-bark-200/10 shadow-sm shadow-bark-200/5 overflow-hidden">
                <div class="px-6 py-5 border-b border-bark-200/10">
                    <h3 class="font-serif font-bold text-lg text-bark-600">{{ __('Today\'s Snapshot') }}</h3>
                </div>

                <div x-show="loadingDashboard" class="flex justify-center py-12">
                    <svg class="animate-spin h-8 w-8 text-bark-300" fill="none" viewBox="0 0 24 24">
                        <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle>
                        <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4z"></path>
                    </svg>
                </div>

                <div x-show="!loadingDashboard" x-cloak class="grid grid-cols-2 sm:grid-cols-4 gap-4 p-6">
                    <div class="text-center">
                        <p class="text-xs font-semibold text-muted uppercase tracking-wider">Today's Sales</p>
                        <p class="text-2xl font-bold text-bark-600 mt-1">₱<span x-text="formatNumber(dashboardData.today_sales)"></span></p>
                    </div>
                    <div class="text-center">
                        <p class="text-xs font-semibold text-muted uppercase tracking-wider">Today's Orders</p>
                        <p class="text-2xl font-bold text-bark-600 mt-1" x-text="dashboardData.today_orders"></p>
                    </div>
                    <div class="text-center">
                        <p class="text-xs font-semibold text-muted uppercase tracking-wider">Pending Orders</p>
                        <p class="text-2xl font-bold text-gold-500 mt-1" x-text="dashboardData.pending_orders"></p>
                    </div>
                    <div class="text-center">
                        <p class="text-xs font-semibold text-muted uppercase tracking-wider">Low Stock Products</p>
                        <p class="text-2xl font-bold mt-1"
                           :class="dashboardData.low_stock_products > 0 ? 'text-red-500' : 'text-leaf-500'"
                           x-text="dashboardData.low_stock_products"></p>
                    </div>
                </div>
            </div>

            {{-- Revenue Reports Quick Link --}}
            <div class="bg-gradient-to-r from-blue-50 to-purple-50 rounded-2xl ring-1 ring-blue-200/30 shadow-sm shadow-blue-200/10 p-6">
                <div class="flex flex-col sm:flex-row items-center justify-between gap-4">
                    <div>
                        <h3 class="font-serif font-bold text-lg text-blue-900">📊 Revenue & Income Reports</h3>
                        <p class="text-sm text-blue-700 mt-1">Track product revenue by day, week, month, and year with detailed profit analysis.</p>
                    </div>
                    <a href="{{ route('admin.revenue.index') }}" class="px-6 py-3 bg-blue-600 hover:bg-blue-700 text-white font-semibold rounded-2xl transition duration-200 whitespace-nowrap">View Revenue Reports →</a>
                </div>
            </div>
        </div>
    </div>

    @push('scripts')
    <script>
        function adminReports() {
            return {
                startDate: new Date(new Date().getFullYear(), new Date().getMonth(), 1).toISOString().split('T')[0],
                endDate: new Date().toISOString().split('T')[0],
                salesData: { total_sales: 0, total_orders: 0, average_order: 0 },
                inventoryData: [],
                dashboardData: { today_sales: 0, today_orders: 0, low_stock_products: 0, pending_orders: 0 },
                loadingSales: false,
                loadingInventory: false,
                loadingDashboard: false,
                errorMsg: '',

                async init() {
                    await Promise.all([
                        this.fetchSalesReport(),
                        this.fetchInventoryReport(),
                        this.fetchDashboard(),
                    ]);
                },

                async fetchSalesReport() {
                    this.loadingSales = true;
                    try {
                        const res = await fetch(`/api/reports/sales?start_date=${this.startDate}&end_date=${this.endDate}`, {
                            headers: { 'Accept': 'application/json' }
                        });
                        if (!res.ok) throw new Error('Failed to load sales report');
                        this.salesData = await res.json();
                    } catch (e) {
                        console.error('Error loading sales report:', e);
                        this.errorMsg = 'Failed to load sales report.';
                        setTimeout(() => this.errorMsg = '', 5000);
                    } finally {
                        this.loadingSales = false;
                    }
                },

                async fetchInventoryReport() {
                    this.loadingInventory = true;
                    try {
                        const res = await fetch('/api/reports/inventory', {
                            headers: { 'Accept': 'application/json' }
                        });
                        if (!res.ok) throw new Error('Failed to load inventory report');
                        this.inventoryData = await res.json();
                    } catch (e) {
                        console.error('Error loading inventory report:', e);
                    } finally {
                        this.loadingInventory = false;
                    }
                },

                async fetchDashboard() {
                    this.loadingDashboard = true;
                    try {
                        const res = await fetch('/api/reports/dashboard', {
                            headers: { 'Accept': 'application/json' }
                        });
                        if (!res.ok) throw new Error('Failed to load dashboard');
                        this.dashboardData = await res.json();
                    } catch (e) {
                        console.error('Error loading dashboard:', e);
                    } finally {
                        this.loadingDashboard = false;
                    }
                },

                formatNumber(val) {
                    return parseFloat(val || 0).toLocaleString('en-PH', { minimumFractionDigits: 2, maximumFractionDigits: 2 });
                },
            };
        }
    </script>
        </div>
        </div>
        </div>
    @endpush
</x-app-layout>
