<x-app-layout>
    <x-slot name="header">
        <h2 class="font-serif font-bold text-xl text-bark-600 leading-tight">
            {{ __('My Orders') }}
        </h2>
    </x-slot>

    <div x-data="ordersPage(@js($orders ?? []))" x-init="init()" class="py-10">
        <div class="max-w-6xl mx-auto px-4 sm:px-6 lg:px-8 space-y-8">

            {{-- Orders Header --}}
            <div class="relative overflow-hidden bg-gradient-to-br from-bark-300 to-bark-400 rounded-3xl shadow-xl shadow-bark-400/20 p-8 sm:p-10 text-white">
                <div class="absolute -top-10 -right-10 w-40 h-40 rounded-full bg-white/5"></div>
                <div class="absolute -bottom-6 -left-6 w-28 h-28 rounded-full bg-white/5"></div>

                <div class="relative flex items-center justify-between">
                    <div class="flex items-center gap-5">
                        <div class="shrink-0 w-16 h-16 rounded-2xl bg-cream-100/20 border-2 border-white/30 flex items-center justify-center shadow-lg">
                            <svg class="w-8 h-8 text-white" fill="none" stroke="currentColor" stroke-width="1.5" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" d="M15.75 10.5V6a3.75 3.75 0 1 0-7.5 0v4.5m11.356-1.993 1.263 12c.07.665-.45 1.243-1.119 1.243H4.25a1.125 1.125 0 0 1-1.12-1.243l1.264-12A1.125 1.125 0 0 1 5.513 7.5h12.974c.576 0 1.059.435 1.119 1.007ZM8.625 10.5a.375.375 0 1 1-.75 0 .375.375 0 0 1 .75 0Zm7.5 0a.375.375 0 1 1-.75 0 .375.375 0 0 1 .75 0Z" />
                            </svg>
                        </div>
                        <div>
                            <h3 class="text-2xl font-serif font-bold tracking-tight">Order History</h3>
                            <p class="mt-1 text-cream-200/80 text-sm font-medium">View and track all your orders.</p>
                        </div>
                    </div>
                </div>
            </div>

            {{-- Filter Tabs --}}
            <div class="flex gap-1 bg-cream-100/50 rounded-2xl p-1.5 ring-1 ring-bark-200/10 overflow-x-auto">
                <button @click="filter = 'all'"
                        :class="filter === 'all' ? 'bg-cream-50 text-bark-600 shadow-sm shadow-bark-200/10' : 'text-muted hover:text-bark-500 hover:bg-cream-50/50'"
                        class="px-4 py-2.5 text-sm font-semibold rounded-xl transition-all duration-200 whitespace-nowrap">
                    All Orders
                </button>
                <button @click="filter = 'pending'"
                        :class="filter === 'pending' ? 'bg-cream-50 text-bark-600 shadow-sm shadow-bark-200/10' : 'text-muted hover:text-bark-500 hover:bg-cream-50/50'"
                        class="px-4 py-2.5 text-sm font-semibold rounded-xl transition-all duration-200 whitespace-nowrap">
                    Pending
                </button>
                <button @click="filter = 'completed'"
                        :class="filter === 'completed' ? 'bg-cream-50 text-bark-600 shadow-sm shadow-bark-200/10' : 'text-muted hover:text-bark-500 hover:bg-cream-50/50'"
                        class="px-4 py-2.5 text-sm font-semibold rounded-xl transition-all duration-200 whitespace-nowrap">
                    Completed
                </button>
                <button @click="filter = 'cancelled'"
                        :class="filter === 'cancelled' ? 'bg-cream-50 text-bark-600 shadow-sm shadow-bark-200/10' : 'text-muted hover:text-bark-500 hover:bg-cream-50/50'"
                        class="px-4 py-2.5 text-sm font-semibold rounded-xl transition-all duration-200 whitespace-nowrap">
                    Cancelled
                </button>
            </div>

            {{-- Orders List --}}
            <div class="space-y-4">
                <template x-for="order in filteredOrders" :key="order.id">
                    <div @click="selectedOrder = order" :class="selectedOrder?.id === order.id ? 'ring-2 ring-bark-300 bg-cream-100' : 'ring-1 ring-bark-200/10 bg-cream-50 hover:bg-cream-100/50'"
                         class="rounded-2xl shadow-sm shadow-bark-200/10 p-6 cursor-pointer transition-all duration-200">

                        <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-4">
                            {{-- Order Info --}}
                            <div class="flex-1">
                                <div class="flex items-center gap-3 mb-2">
                                    <h3 class="text-lg font-serif font-bold text-bark-600" x-text="`Order ${order.order_number}`"></h3>
                                    <span :class="getStatusColor(order.status)"
                                          class="px-3 py-1 text-xs font-semibold rounded-full"
                                          x-text="capitalizeStatus(order.status)"></span>
                                </div>
                                <div class="grid grid-cols-2 sm:grid-cols-4 gap-4 text-sm">
                                    <div>
                                        <p class="text-muted text-xs">Order Date</p>
                                        <p class="text-bark-600 font-semibold" x-text="formatDate(order.created_at)"></p>
                                    </div>
                                    <div>
                                        <p class="text-muted text-xs">Type</p>
                                        <p class="text-bark-600 font-semibold" x-text="capitalizeType(order.type)"></p>
                                    </div>
                                    <div>
                                        <p class="text-muted text-xs">Items</p>
                                        <p class="text-bark-600 font-semibold" x-text="`${order.items.length} item${order.items.length !== 1 ? 's' : ''}`"></p>
                                    </div>
                                    <div>
                                        <p class="text-muted text-xs">Payment</p>
                                        <p class="text-bark-600 font-semibold" x-text="capitalizeStatus(order.payment_status)"></p>
                                    </div>
                                </div>
                            </div>

                            {{-- Total and Action --}}
                            <div class="flex flex-col sm:flex-col-reverse sm:items-end gap-3">
                                <div class="text-right">
                                    <p class="text-muted text-xs">Total</p>
                                    <p class="text-2xl font-serif font-bold text-bark-600" x-text="`₱${parseFloat(order.total).toFixed(2)}`"></p>
                                </div>
                                <svg :class="selectedOrder?.id === order.id ? 'rotate-90' : 'rotate-0'" class="w-5 h-5 text-bark-200 transition-transform duration-200" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" d="m8.25 4.5 7.5 7.5-7.5 7.5" />
                                </svg>
                            </div>
                        </div>

                        {{-- Expanded Details --}}
                        <div x-show="selectedOrder?.id === order.id" x-transition:enter="transition ease-out duration-200" x-transition:enter-start="opacity-0 -translate-y-2" x-transition:enter-end="opacity-100 translate-y-0" class="mt-6 pt-6 border-t border-bark-200/20 space-y-4">

                            {{-- Items --}}
                            <div>
                                <h4 class="text-sm font-semibold text-bark-600 mb-3">Order Items</h4>
                                <div class="space-y-2">
                                    <template x-for="item in order.items" :key="item.id">
                                        <div class="flex items-center justify-between p-3 bg-bark-50/50 rounded-lg">
                                            <div class="flex-1">
                                                <p class="text-sm font-semibold text-bark-600" x-text="item.product ? item.product.name : 'Item'"></p>
                                                <p class="text-xs text-muted" x-text="`Qty: ${item.quantity}`"></p>
                                            </div>
                                            <p class="text-sm font-semibold text-bark-600" x-text="`₱${parseFloat(item.subtotal).toFixed(2)}`"></p>
                                        </div>
                                    </template>
                                </div>
                            </div>

                            {{-- Summary --}}
                            <div class="space-y-2 p-4 bg-bark-50/50 rounded-lg">
                                <div class="flex justify-between text-sm">
                                    <span class="text-muted">Subtotal:</span>
                                    <span class="text-bark-600 font-semibold" x-text="`₱${parseFloat(order.subtotal).toFixed(2)}`"></span>
                                </div>
                                <div class="flex justify-between text-sm" x-show="order.tax > 0">
                                    <span class="text-muted">Tax:</span>
                                    <span class="text-bark-600 font-semibold" x-text="`₱${parseFloat(order.tax).toFixed(2)}`"></span>
                                </div>
                                <div class="flex justify-between text-sm" x-show="order.delivery_fee > 0">
                                    <span class="text-muted">Delivery Fee:</span>
                                    <span class="text-bark-600 font-semibold" x-text="`₱${parseFloat(order.delivery_fee).toFixed(2)}`"></span>
                                </div>
                                <div class="flex justify-between text-sm" x-show="order.discount > 0">
                                    <span class="text-muted">Discount:</span>
                                    <span class="text-leaf-500 font-semibold" x-text="`-₱${parseFloat(order.discount).toFixed(2)}`"></span>
                                </div>
                                <div class="flex justify-between text-sm font-semibold pt-2 border-t border-bark-200/20">
                                    <span class="text-bark-600">Total:</span>
                                    <span class="text-bark-700" x-text="`₱${parseFloat(order.total).toFixed(2)}`"></span>
                                </div>
                            </div>

                            {{-- Order Tracking --}}
                            <div class="space-y-4">
                                <div class="flex items-center justify-between">
                                    <div>
                                        <h4 class="text-sm font-semibold text-bark-600">Order Tracking</h4>
                                        <p class="text-xs text-muted">Realtime status updates refresh every 15 seconds.</p>
                                    </div>
                                    <span class="text-xs font-semibold uppercase tracking-[0.15em] text-bark-500" x-text="order.status.replace(/_/g, ' ')" />
                                </div>

                                <div class="grid grid-cols-2 sm:grid-cols-3 xl:grid-cols-6 gap-2">
                                    <template x-for="step in trackingSteps" :key="step.key">
                                        <div class="rounded-2xl p-3 text-center border transition" :class="isStepComplete(order, step.key) ? 'border-emerald-200 bg-emerald-50 text-emerald-700' : 'border-bark-200 bg-cream-100 text-bark-500'">
                                            <div class="text-[10px] font-semibold uppercase tracking-[0.2em]" x-text="step.label"></div>
                                            <div class="mt-2 text-sm font-semibold" x-text="step.key === order.status ? 'Current' : (isStepComplete(order, step.key) ? 'Done' : 'Pending')"></div>
                                        </div>
                                    </template>
                                </div>

                                <div class="grid grid-cols-1 gap-3 sm:grid-cols-2">
                                    <div class="flex items-center gap-3 text-sm text-bark-600">
                                        <svg class="w-4 h-4 text-bark-300" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" d="M12 6v6h4.5m4.5 0a9 9 0 1 1-18 0 9 9 0 0 1 18 0Z" />
                                        </svg>
                                        <span x-text="order.scheduled_at ? `Scheduled: ${formatDateTime(order.scheduled_at)}` : 'No schedule set'" />
                                    </div>
                                    <div class="flex items-center gap-3 text-sm text-bark-600" x-show="order.fulfillment_type">
                                        <svg class="w-4 h-4 text-bark-300" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" d="M8.25 18.75a1.5 1.5 0 0 1-3 0m3 0a1.5 1.5 0 0 0-3 0m3 0h6m-9 0H3.375a1.125 1.125 0 0 1-1.125-1.125V14.25m17.25 4.5a1.5 1.5 0 0 1-3 0m3 0a1.5 1.5 0 0 0-3 0m3 0h1.125c.621 0 1.143-.504 1.025-1.113a9.004 9.004 0 0 0-5.68-6.638A8.972 8.972 0 0 0 12 5.25c-1.005 0-1.97.164-2.87.467M2.25 14.25V5.625c0-.621.504-1.125 1.125-1.125h11.25c.621 0 1.125.504 1.125 1.125v4.874" />
                                        </svg>
                                        <span x-text="`${capitalizeType(order.fulfillment_type)} Fulfillment`" />
                                    </div>
                                </div>
                            </div>

                            {{-- Special Instructions --}}
                            <div x-show="order.special_instructions" class="p-4 bg-gold-50/50 rounded-lg border border-gold-200/20">
                                <p class="text-xs text-muted mb-2">Special Instructions</p>
                                <p class="text-sm text-bark-600" x-text="order.special_instructions"></p>
                            </div>

                            {{-- Actions --}}
                            <div class="flex gap-3 pt-4">
                                <button @click="reorderItems(order)" class="flex-1 px-4 py-2 text-sm font-semibold text-white bg-bark-300 rounded-xl hover:bg-bark-400 transition">
                                    Reorder
                                </button>
                                <button @click="downloadInvoice(order)" class="flex-1 px-4 py-2 text-sm font-semibold text-bark-600 border border-bark-300 rounded-xl hover:bg-cream-100 transition">
                                    Invoice
                                </button>
                            </div>
                        </div>
                    </div>
                </template>

                {{-- No Orders --}}
                <div x-show="filteredOrders.length === 0" class="text-center py-12">
                    <svg class="w-16 h-16 text-bark-200 mx-auto mb-4" fill="none" stroke="currentColor" stroke-width="1.5" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M15.75 10.5V6a3.75 3.75 0 1 0-7.5 0v4.5m11.356-1.993 1.263 12c.07.665-.45 1.243-1.119 1.243H4.25a1.125 1.125 0 0 1-1.12-1.243l1.264-12A1.125 1.125 0 0 1 5.513 7.5h12.974c.576 0 1.059.435 1.119 1.007ZM8.625 10.5a.375.375 0 1 1-.75 0 .375.375 0 0 1 .75 0Zm7.5 0a.375.375 0 1 1-.75 0 .375.375 0 0 1 .75 0Z" />
                    </svg>
                    <p class="text-bark-600 font-semibold mb-1">No orders found</p>
                    <p class="text-muted text-sm" x-text="filter === 'all' ? 'You haven\'t placed any orders yet.' : `You have no ${filter} orders.`"></p>
                    <a href="{{ route('home') }}" class="inline-block mt-4 px-6 py-2 text-sm font-semibold text-white bg-bark-300 rounded-xl hover:bg-bark-400 transition">
                        Continue Shopping
                    </a>
                </div>
            </div>
        </div>
    </div>

    @push('scripts')
    <script>
    function ordersPage(initialOrders = []) {
        return {
            filter: 'all',
            selectedOrder: null,
            orders: initialOrders,

            get filteredOrders() {
                if (this.filter === 'all') {
                    return this.orders;
                }
                return this.orders.filter(order => order.status === this.filter);
            },

            formatDate(date) {
                return new Date(date).toLocaleDateString('en-US', {
                    month: 'short',
                    day: 'numeric',
                    year: 'numeric'
                });
            },

            formatDateTime(date) {
                return new Date(date).toLocaleDateString('en-US', {
                    month: 'short',
                    day: 'numeric',
                    year: 'numeric',
                    hour: '2-digit',
                    minute: '2-digit'
                });
            },

            capitalizeStatus(status) {
                return status.charAt(0).toUpperCase() + status.slice(1).toLowerCase();
            },

            capitalizeType(type) {
                if (type === 'pos') return 'POS';
                return type.charAt(0).toUpperCase() + type.slice(1).toLowerCase();
            },

            getStatusColor(status) {
                const colors = {
                    'pending': 'bg-gold-100 text-gold-700',
                    'confirmed': 'bg-sky-100 text-sky-700',
                    'preparing': 'bg-blue-100 text-blue-700',
                    'ready': 'bg-leaf-100 text-leaf-700',
                    'out_for_delivery': 'bg-violet-100 text-violet-700',
                    'completed': 'bg-emerald-100 text-emerald-700',
                    'cancelled': 'bg-red-100 text-red-700',
                };
                return colors[status] || 'bg-bark-100 text-bark-700';
            },

            getStatusDot(status) {
                const colors = {
                    'pending': 'bg-gold-400',
                    'confirmed': 'bg-sky-400',
                    'preparing': 'bg-blue-400',
                    'ready': 'bg-leaf-400',
                    'out_for_delivery': 'bg-violet-400',
                    'completed': 'bg-emerald-400',
                    'cancelled': 'bg-red-400',
                };
                return colors[status] || 'bg-bark-300';
            },

            init() {
                this.loadOrders();
                this.polling = setInterval(() => this.loadOrders(), 15000);
            },

            async loadOrders() {
                try {
                    const response = await fetch('/api/my-orders', {
                        headers: { 'Accept': 'application/json' },
                        credentials: 'same-origin',
                    });
                    const data = await response.json();
                    this.orders = data;

                    if (this.selectedOrder) {
                        const fresh = this.orders.find(o => o.id === this.selectedOrder.id);
                        if (fresh) {
                            this.selectedOrder = fresh;
                        }
                    }
                } catch (e) {
                    console.error('Failed to refresh orders', e);
                }
            },

            get trackingSteps() {
                return [
                    { key: 'pending', label: 'Pending' },
                    { key: 'confirmed', label: 'Confirmed' },
                    { key: 'preparing', label: 'Preparing' },
                    { key: 'ready', label: 'Ready' },
                    { key: 'out_for_delivery', label: 'On Delivery' },
                    { key: 'completed', label: 'Completed' },
                ];
            },

            isStepComplete(order, step) {
                const orderIndex = this.trackingSteps.findIndex(s => s.key === order.status);
                const stepIndex = this.trackingSteps.findIndex(s => s.key === step);
                return stepIndex <= orderIndex;
            },

            orderStepLabel(step) {
                return this.trackingSteps.find(s => s.key === step)?.label || step;
            },

            reorderItems(order) {
                if (confirm(`Reorder items from ${order.order_number}?`)) {
                    fetch(`/orders/${order.id}/reorder`, {
                        method: 'POST',
                        headers: {
                            'Content-Type': 'application/json',
                            'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content')
                        }
                    })
                    .then(response => response.json())
                    .then(data => {
                        if (data.message) {
                            alert(`Success: ${data.message}\nOrder number: ${data.order_number}`);
                            // Refresh the page to show the new order
                            window.location.reload();
                        } else {
                            alert('Error reordering items: ' + (data.error || 'Unknown error'));
                        }
                    })
                    .catch(error => {
                        console.error('Error:', error);
                        alert('An error occurred while reordering. Please try again.');
                    });
                }
            },

            downloadInvoice(order) {
                alert(`Downloading invoice for ${order.order_number}`);
            }
        };
    }
    window.ordersPage = ordersPage;

    // Order modal functions
    function openOrderModal() {
        document.getElementById('orderModal').style.display = 'flex';
    }

    function closeOrderModal() {
        document.getElementById('orderModal').style.display = 'none';
    }

    function openMapLink() {
        const address = document.getElementById('mapAddressText').textContent;
        window.open(`https://maps.google.com/?q=${encodeURIComponent(address)}`, '_blank');
    }
    </script>
    @endpush

    <style>
        /* Custom scrollbar styling for order modal */
        #quickOrderForm::-webkit-scrollbar {
            width: 8px;
        }

        #quickOrderForm::-webkit-scrollbar-track {
            background: #f3f4f6;
            border-radius: 10px;
        }

        #quickOrderForm::-webkit-scrollbar-thumb {
            background: #8b6f47;
            border-radius: 10px;
        }

        #quickOrderForm::-webkit-scrollbar-thumb:hover {
            background: #6b5537;
        }

        /* Firefox scrollbar */
        #quickOrderForm {
            scrollbar-color: #8b6f47 #f3f4f6;
            scrollbar-width: thin;
        }
    </style>

    {{-- Quick Order Modal --}}
    <div id="orderModal" x-show="showOrderModal" x-transition class="fixed inset-0 bg-black/50 flex items-center justify-center p-4 z-50">
        <div class="bg-white rounded-xl shadow-2xl max-w-md w-full flex flex-col max-h-[90vh]">
            {{-- Modal Header --}}
            <div class="flex justify-between items-center p-6 border-b border-cream-200 flex-shrink-0">
                <h2 class="text-xl font-serif font-bold text-bark-600" id="orderModalTitle">Order</h2>
                <button onclick="closeOrderModal()" class="text-2xl text-muted hover:text-bark-600 transition">✕</button>
            </div>

            {{-- Modal Body (Scrollable) --}}
            <form id="quickOrderForm" class="flex-1 overflow-y-auto p-6 space-y-4 scrollbar-thin scrollbar-thumb-bark-300 scrollbar-track-cream-100">
                {{-- Quantity --}}
                <div>
                    <label class="block text-sm font-semibold text-bark-600 mb-2">Quantity</label>
                    <input type="number" id="orderQuantity" min="1" value="1" class="w-full px-3 py-2 border border-cream-300 rounded-lg focus:ring-2 focus:ring-bark-300 focus:border-transparent">
                </div>

                {{-- Fulfillment Type --}}
                <div>
                    <label class="block text-sm font-semibold text-bark-600 mb-3">Fulfillment Type</label>
                    <div class="flex gap-4">
                        <label class="flex items-center cursor-pointer">
                            <input type="radio" name="fulfillment" value="pickup" checked class="mr-2">
                            <span class="text-sm">🏪 Pickup</span>
                        </label>
                        <label class="flex items-center cursor-pointer">
                            <input type="radio" name="fulfillment" value="delivery" class="mr-2">
                            <span class="text-sm">🚚 Delivery (+₱20)</span>
                        </label>
                    </div>
                </div>

                {{-- Delivery Section --}}
                <div id="deliverySection" class="space-y-3">
                    <div>
                        <label class="block text-sm font-semibold text-bark-600 mb-2">Delivery Address</label>
                        <input type="text" id="deliveryAddress" placeholder="Enter your delivery address" class="w-full px-3 py-2 border border-cream-300 rounded-lg focus:ring-2 focus:ring-bark-300 focus:border-transparent">
                    </div>

                    {{-- Delivery Map --}}
                    <div>
                        <label class="block text-sm font-semibold text-bark-600 mb-2">Delivery Location</label>
                        <div id="deliveryMap" class="w-full h-56 rounded-lg border-2 border-cream-300 bg-gradient-to-br from-purple-500 to-purple-700 flex flex-col items-center justify-center text-white relative overflow-hidden">
                            <div class="text-center z-10">
                                <div class="text-4xl mb-2">📍</div>
                                <div class="font-semibold mb-1">Delivery Location Set</div>
                                <div class="text-sm">Address: <strong id="mapAddressText">tesda aparri cagayan</strong></div>
                                <div class="text-xs mt-2 opacity-90">Estimated delivery fee: ₱50</div>
                            </div>
                            <button type="button" onclick="openMapLink()" class="absolute bottom-3 right-3 bg-white text-gray-700 px-3 py-1 rounded text-xs font-semibold hover:bg-gray-100 transition">
                                🗺️ View on Maps
                            </button>
                        </div>
                    </div>
                </div>

                {{-- Special Instructions --}}
                <div>
                    <label class="block text-sm font-semibold text-bark-600 mb-2">Special Instructions</label>
                    <textarea id="orderNotes" placeholder="Any special requests?" class="w-full px-3 py-2 border border-cream-300 rounded-lg focus:ring-2 focus:ring-bark-300 focus:border-transparent h-20 resize-none"></textarea>
                </div>

                {{-- Order Summary --}}
                <div class="bg-cream-100 p-4 rounded-lg space-y-2">
                    <div class="flex justify-between text-sm">
                        <span class="text-muted">Subtotal:</span>
                        <span class="font-semibold text-bark-600" id="orderSubtotal">₱7.00</span>
                    </div>
                    <div class="flex justify-between text-sm">
                        <span class="text-muted">Delivery:</span>
                        <span class="font-semibold text-bark-600" id="orderDelivery">₱50.00</span>
                    </div>
                    <div class="flex justify-between text-sm font-bold pt-2 border-t border-cream-200">
                        <span class="text-bark-600">Total:</span>
                        <span class="text-lg text-bark-600" id="orderTotal">₱57.00</span>
                    </div>
                </div>

                {{-- Actions --}}
                <div class="flex gap-3 pt-2">
                    <button type="button" onclick="closeOrderModal()" class="flex-1 px-4 py-2 bg-cream-200 text-bark-600 font-semibold rounded-lg hover:bg-cream-300 transition">Cancel</button>
                    <button type="submit" class="flex-1 px-4 py-2 bg-leaf-500 text-white font-semibold rounded-lg hover:bg-leaf-600 transition">Place Order</button>
                </div>
            </form>

            {{-- Modal Footer (Fixed below scrollable content) --}}
            <div class="border-t border-cream-200 p-4 flex-shrink-0 bg-cream-50">
                <div class="flex gap-3">
                    <button type="button" onclick="closeOrderModal()" class="flex-1 px-4 py-2.5 bg-cream-200 text-bark-600 font-semibold rounded-lg hover:bg-cream-300 transition text-sm">Cancel</button>
                    <button type="submit" form="quickOrderForm" class="flex-1 px-4 py-2.5 bg-leaf-500 text-white font-semibold rounded-lg hover:bg-leaf-600 transition text-sm">Place Order</button>
                </div>
            </div>
        </div>
    </div>
</x-app-layout>
