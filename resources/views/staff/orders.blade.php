<x-app-layout>
    <x-slot name="header">
        <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-3">
            <div>
                <h2 class="font-serif font-bold text-2xl text-bark-600 leading-tight">
                    {{ __('Order Tracking') }}
                </h2>
                <p class="mt-1 text-sm text-muted">Monitor active orders, update status, and keep customers informed in real time.</p>
            </div>
            <button type="button" @click="loadOrders()" class="inline-flex items-center rounded-full bg-bark-900 px-4 py-2 text-sm font-semibold text-white hover:bg-bark-700 transition">
                Refresh now
            </button>
        </div>
    </x-slot>

    <div x-data="staffOrdersPage()" x-init="init()" class="py-8">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 space-y-6">
            <div class="grid grid-cols-1 sm:grid-cols-2 xl:grid-cols-4 gap-6">
                <template x-for="card in [
                    { label: 'Pending', count: statusCounts.pending, color: 'bg-gold-50 text-gold-700' },
                    { label: 'Confirmed', count: statusCounts.confirmed, color: 'bg-sky-50 text-sky-700' },
                    { label: 'Preparing', count: statusCounts.preparing, color: 'bg-blue-50 text-blue-700' },
                    { label: 'Ready', count: statusCounts.ready, color: 'bg-leaf-50 text-leaf-700' },
                    { label: 'Delivery', count: statusCounts.out_for_delivery, color: 'bg-violet-50 text-violet-700' },
                    { label: 'Completed', count: statusCounts.completed, color: 'bg-emerald-50 text-emerald-700' },
                    { label: 'Cancelled', count: statusCounts.cancelled, color: 'bg-red-50 text-red-700' }
                ]" :key="card.label">
                    <div class="rounded-3xl p-5 ring-1 ring-bark-200/10 shadow-sm shadow-bark-200/5" :class="card.color">
                        <p class="text-sm font-semibold uppercase tracking-[0.2em]" x-text="card.label"></p>
                        <p class="mt-3 text-3xl font-semibold" x-text="card.count"></p>
                    </div>
                </template>
            </div>

            <div class="bg-cream-50 rounded-3xl ring-1 ring-bark-200/10 shadow-sm shadow-bark-200/5 overflow-hidden">
                <div class="px-6 py-5 border-b border-bark-200/10 flex flex-col gap-3 sm:flex-row sm:items-center sm:justify-between">
                    <div>
                        <h3 class="font-serif font-bold text-lg text-bark-600">Current Orders</h3>
                        <p class="text-xs text-muted mt-1">The live order queue updates every 15 seconds.</p>
                    </div>
                    <div class="flex flex-wrap items-center gap-2 text-xs text-bark-500">
                        <button type="button" @click="filter = 'active'" :class="filter === 'active' ? 'bg-bark-900 text-white' : 'bg-cream-100 text-bark-600'" class="rounded-full px-3 py-2 transition">Active</button>
                        <button type="button" @click="filter = 'completed'" :class="filter === 'completed' ? 'bg-bark-900 text-white' : 'bg-cream-100 text-bark-600'" class="rounded-full px-3 py-2 transition">Completed</button>
                        <button type="button" @click="filter = 'cancelled'" :class="filter === 'cancelled' ? 'bg-bark-900 text-white' : 'bg-cream-100 text-bark-600'" class="rounded-full px-3 py-2 transition">Cancelled</button>
                        <button type="button" @click="filter = 'all'" :class="filter === 'all' ? 'bg-bark-900 text-white' : 'bg-cream-100 text-bark-600'" class="rounded-full px-3 py-2 transition">All</button>
                    </div>
                </div>

                <div class="overflow-x-auto">
                    <table class="w-full text-sm">
                        <thead>
                            <tr class="bg-cream-200/40 text-left text-xs uppercase tracking-wider text-muted">
                                <th class="px-6 py-3">Order</th>
                                <th class="px-6 py-3">Customer</th>
                                <th class="px-6 py-3">Type</th>
                                <th class="px-6 py-3">Status</th>
                                <th class="px-6 py-3">Total</th>
                                <th class="px-6 py-3">Action</th>
                            </tr>
                        </thead>
                        <tbody class="divide-y divide-bark-200/10">
                            <template x-if="filteredOrders.length === 0">
                                <tr>
                                    <td colspan="6" class="px-6 py-8 text-center text-sm text-muted">No orders match the selected filter.</td>
                                </tr>
                            </template>
                            <template x-for="order in filteredOrders" :key="order.id">
                                <tr class="hover:bg-cream-100/60 transition">
                                    <td class="px-6 py-4 font-semibold text-bark-700" x-text="order.order_number"></td>
                                    <td class="px-6 py-4 text-bark-600" x-text="order.user?.name || 'Guest'"></td>
                                    <td class="px-6 py-4 text-bark-600" x-text="capitalizeType(order.type)"></td>
                                    <td class="px-6 py-4">
                                        <span class="inline-flex rounded-full px-3 py-1 text-xs font-semibold" :class="statusPill(order.status)">
                                            <span x-text="getStatusLabel(order.status)"></span>
                                        </span>
                                    </td>
                                    <td class="px-6 py-4 text-bark-600" x-text="`₱${parseFloat(order.total).toFixed(2)}`"></td>
                                    <td class="px-6 py-4 space-x-2">
                                        <button type="button" @click="updateStatus(order, nextStatus(order))" x-show="canAdvance(order) && nextStatus(order)" class="rounded-full bg-bark-900 px-3 py-2 text-xs font-semibold text-white hover:bg-bark-700 transition">
                                            <span x-text="nextStatus(order) ? `Move to ${getStatusLabel(nextStatus(order))}` : 'Update' "></span>
                                        </button>
                                        <button type="button" @click="updateStatus(order, 'cancelled')" x-show="order.status !== 'cancelled' && order.status !== 'completed'" class="rounded-full bg-red-500 px-3 py-2 text-xs font-semibold text-white hover:bg-red-600 transition">
                                            Cancel
                                        </button>
                                    </td>
                                </tr>
                            </template>
                        </tbody>
                    </table>
                </div>
            </div>

            <div class="bg-cream-50 rounded-3xl ring-1 ring-bark-200/10 shadow-sm shadow-bark-200/5 p-6">
                <h3 class="font-serif font-bold text-lg text-bark-600">Live Order Status</h3>
                <p class="text-sm text-muted mt-1">This dashboard automatically refreshes every 15 seconds.</p>
                <div class="mt-6 grid grid-cols-1 gap-4 lg:grid-cols-3">
                    <template x-for="step in trackingSteps" :key="step.key">
                        <div class="rounded-3xl border border-bark-200/10 p-4">
                            <div class="flex items-center justify-between gap-3 text-sm font-semibold text-bark-700">
                                <span x-text="step.label"></span>
                                <span x-text="statusCounts[step.key] || 0"></span>
                            </div>
                            <div class="mt-3 h-2 rounded-full bg-bark-100 overflow-hidden">
                                <div class="h-full rounded-full bg-bark-900" :style="`width: ${statusCounts[step.key] ? Math.min(100, (statusCounts[step.key] / Math.max(1, orders.length)) * 100) : 0}%`"></div>
                            </div>
                        </div>
                    </template>
                </div>
            </div>
        </div>
    </div>

    @push('scripts')
    <script>
        function staffOrdersPage() {
            return {
                orders: [],
                filter: 'active',
                polling: null,
                loading: false,

                init() {
                    this.loadOrders();
                    this.polling = setInterval(() => this.loadOrders(), 15000);
                },

                async loadOrders() {
                    this.loading = true;
                    try {
                        const response = await fetch('/api/orders', {
                            headers: { 'Accept': 'application/json' },
                            credentials: 'same-origin',
                        });
                        this.orders = await response.json();
                    } catch (error) {
                        console.error('Failed to load orders', error);
                    } finally {
                        this.loading = false;
                    }
                },

                get filteredOrders() {
                    if (this.filter === 'all') {
                        return this.orders;
                    }
                    if (this.filter === 'active') {
                        return this.orders.filter(order => ['pending', 'confirmed', 'preparing', 'ready', 'out_for_delivery'].includes(order.status));
                    }
                    return this.orders.filter(order => order.status === this.filter);
                },

                get statusCounts() {
                    return this.orders.reduce((counts, order) => {
                        counts[order.status] = (counts[order.status] || 0) + 1;
                        return counts;
                    }, {});
                },

                get trackingSteps() {
                    return [
                        { key: 'pending', label: 'Pending' },
                        { key: 'confirmed', label: 'Confirmed' },
                        { key: 'preparing', label: 'Preparing' },
                        { key: 'ready', label: 'Ready' },
                        { key: 'out_for_delivery', label: 'Delivery' },
                        { key: 'completed', label: 'Completed' },
                    ];
                },

                getStatusLabel(status) {
                    return {
                        pending: 'Pending',
                        confirmed: 'Confirmed',
                        preparing: 'Preparing',
                        ready: 'Ready',
                        out_for_delivery: 'Out for Delivery',
                        completed: 'Completed',
                        cancelled: 'Cancelled',
                    }[status] || status;
                },

                statusPill(status) {
                    return {
                        pending: 'bg-gold-100 text-gold-700',
                        confirmed: 'bg-sky-100 text-sky-700',
                        preparing: 'bg-blue-100 text-blue-700',
                        ready: 'bg-leaf-100 text-leaf-700',
                        out_for_delivery: 'bg-violet-100 text-violet-700',
                        completed: 'bg-emerald-100 text-emerald-700',
                        cancelled: 'bg-red-100 text-red-700',
                    }[status] || 'bg-bark-100 text-bark-700';
                },

                canAdvance(order) {
                    return !['completed', 'cancelled'].includes(order.status) && this.nextStatus(order);
                },

                nextStatus(order) {
                    return {
                        pending: 'confirmed',
                        confirmed: 'preparing',
                        preparing: 'ready',
                        ready: 'out_for_delivery',
                        out_for_delivery: 'completed',
                    }[order.status] || null;
                },

                getXsrfToken() {
                    const match = document.cookie.match(/XSRF-TOKEN=([^;]+)/);
                    return match ? decodeURIComponent(match[1]) : '';
                },

                async updateStatus(order, newStatus) {
                    if (!newStatus) {
                        return;
                    }

                    try {
                        const response = await fetch(`/api/orders/${order.id}/status`, {
                            method: 'PATCH',
                            headers: {
                                'Accept': 'application/json',
                                'Content-Type': 'application/json',
                                'X-XSRF-TOKEN': this.getXsrfToken(),
                            },
                            credentials: 'same-origin',
                            body: JSON.stringify({ status: newStatus }),
                        });

                        if (!response.ok) {
                            throw new Error('Unable to update status');
                        }

                        await this.loadOrders();
                    } catch (error) {
                        console.error('Failed to update order', error);
                    }
                },
            };
        }
    </script>
    @endpush
</x-app-layout>
