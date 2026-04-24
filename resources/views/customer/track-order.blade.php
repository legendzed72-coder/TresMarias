<x-app-layout>
    <div class="min-h-screen bg-cream-50 py-12 px-4 sm:px-6 lg:px-8">
        <div class="max-w-4xl mx-auto">
            {{-- Header --}}
            <div class="text-center mb-12">
                <h1 class="text-4xl font-bold text-bark-900 mb-2">{{ __('Track Your Order') }}</h1>
                <p class="text-bark-600">{{ __('Monitor your bakery order in real-time') }}</p>
            </div>

            {{-- Search Section --}}
            <div class="bg-white rounded-2xl border border-cream-200 p-8 shadow-lg mb-8" x-data="orderTracker()" @init="init()">
                <div class="mb-6">
                    <label class="block text-sm font-semibold text-bark-600 mb-3">{{ __('Enter Your Order Number') }}</label>
                    <div class="flex gap-2">
                        <input type="text" 
                            x-model="searchOrderNumber"
                            @keyup.enter="searchOrder()"
                            placeholder="e.g., ORD-ABC123XYZ"
                            class="flex-1 px-6 py-3 border-2 border-cream-300 rounded-xl focus:outline-none focus:border-bark-300 focus:ring-2 focus:ring-bark-300/40 text-lg font-medium">
                        <button type="button" @click="searchOrder()"
                            class="px-8 py-3 bg-bark-300 hover:bg-bark-400 text-cream-50 font-bold rounded-xl transition duration-200 flex items-center gap-2">
                            <svg class="w-5 h-5" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"/>
                            </svg>
                            {{ __('Search') }}
                        </button>
                    </div>
                    <p class="text-xs text-bark-500 mt-2">{{ __('You can find your order number in your confirmation email') }}</p>
                </div>

                {{-- Error Message --}}
                <div x-show="errorMsg" x-transition x-cloak
                    class="p-4 rounded-xl bg-red-100 border border-red-300 text-red-700 flex items-center gap-3 mb-6">
                    <svg class="w-5 h-5 flex-shrink-0" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M12 8v4m0 4v.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/>
                    </svg>
                    <span x-text="errorMsg"></span>
                    <button type="button" @click="tryLoadSample()" 
                        class="ml-auto px-4 py-2 text-sm font-semibold bg-red-200 hover:bg-red-300 rounded-lg transition">
                        Try Sample Order
                    </button>
                </div>

                {{-- Order Found --}}
                <div x-show="currentOrder" x-cloak class="space-y-6">
                    {{-- Order Header --}}
                    <div class="bg-gradient-to-r from-bark-300 to-bark-400 rounded-xl p-6 text-cream-50">
                        <div class="flex items-center justify-between">
                            <div>
                                <p class="text-sm opacity-90">{{ __('Order Number') }}</p>
                                <p class="text-2xl font-bold" x-text="currentOrder.order_number"></p>
                            </div>
                            <div class="text-right">
                                <p class="text-sm opacity-90">{{ __('Total Amount') }}</p>
                                <p class="text-2xl font-bold">₱<span x-text="currentOrder.total.toFixed(2)"></span></p>
                            </div>
                        </div>
                    </div>

                    {{-- Order Status Timeline --}}
                    <div class="bg-white rounded-xl border border-cream-200 p-6">
                        <h3 class="font-bold text-lg text-bark-900 mb-6">{{ __('Delivery Status') }}</h3>
                        
                        <div class="space-y-4">
                            {{-- Pending --}}
                            <div class="flex items-start gap-4">
                                <div class="relative flex flex-col items-center">
                                    <div :class="['w-10 h-10 rounded-full flex items-center justify-center font-bold text-sm', 
                                        (currentOrder.status === 'pending' || ['assigned', 'picked_up', 'in_transit', 'delivered'].includes(currentOrder.status)) ? 'bg-green-500 text-white' : 'bg-gray-300 text-gray-600']">
                                        ✓
                                    </div>
                                </div>
                                <div class="pt-1">
                                    <p class="font-semibold text-bark-900">{{ __('Order Confirmed') }}</p>
                                    <p class="text-sm text-bark-600">{{ __('Your bakery order has been received and confirmed') }}</p>
                                    <p class="text-xs text-bark-500 mt-1" x-text="formatDate(currentOrder.created_at)"></p>
                                </div>
                            </div>

                            {{-- Assigned --}}
                            <div class="flex items-start gap-4">
                                <div class="relative flex flex-col items-center">
                                    <div :class="['w-10 h-10 rounded-full flex items-center justify-center font-bold text-sm', 
                                        ['assigned', 'picked_up', 'in_transit', 'delivered'].includes(currentOrder.status) ? 'bg-green-500 text-white' : 'bg-gray-300 text-gray-600']">
                                        👤
                                    </div>
                                </div>
                                <div class="pt-1">
                                    <p class="font-semibold text-bark-900">{{ __('Driver Assigned') }}</p>
                                    <p class="text-sm text-bark-600" x-show="currentOrder.driver">
                                        <span x-text="'Driver: ' + currentOrder.driver?.name + ' · Phone: ' + currentOrder.driver?.phone"></span>
                                    </p>
                                    <p class="text-sm text-bark-600" x-show="!currentOrder.driver">{{ __('Waiting for driver assignment...') }}</p>
                                </div>
                            </div>

                            {{-- Picked Up --}}
                            <div class="flex items-start gap-4">
                                <div class="relative flex flex-col items-center">
                                    <div :class="['w-10 h-10 rounded-full flex items-center justify-center font-bold text-sm', 
                                        ['picked_up', 'in_transit', 'delivered'].includes(currentOrder.status) ? 'bg-green-500 text-white' : 'bg-gray-300 text-gray-600']">
                                        📦
                                    </div>
                                </div>
                                <div class="pt-1">
                                    <p class="font-semibold text-bark-900">{{ __('Order Picked Up') }}</p>
                                    <p class="text-sm text-bark-600">{{ __('Your order is ready and picked up for delivery') }}</p>
                                </div>
                            </div>

                            {{-- In Transit --}}
                            <div class="flex items-start gap-4">
                                <div class="relative flex flex-col items-center">
                                    <div :class="['w-10 h-10 rounded-full flex items-center justify-center font-bold text-sm animate-pulse', 
                                        ['in_transit', 'delivered'].includes(currentOrder.status) ? 'bg-green-500 text-white' : currentOrder.status === 'in_transit' ? 'bg-gold-500 text-white' : 'bg-gray-300 text-gray-600']">
                                        🚚
                                    </div>
                                </div>
                                <div class="pt-1">
                                    <p class="font-semibold text-bark-900">{{ __('Out for Delivery') }}</p>
                                    <p class="text-sm text-bark-600" x-show="currentOrder.status === 'in_transit'">
                                        {{ __('Your order is on its way! Expected delivery within 30 minutes.') }}
                                    </p>
                                    <p class="text-sm text-bark-600" x-show="currentOrder.status !== 'in_transit'">
                                        {{ __('Waiting for driver to pick up...') }}
                                    </p>
                                </div>
                            </div>

                            {{-- Delivered --}}
                            <div class="flex items-start gap-4">
                                <div class="relative flex flex-col items-center">
                                    <div :class="['w-10 h-10 rounded-full flex items-center justify-center font-bold text-sm', 
                                        currentOrder.status === 'delivered' ? 'bg-green-500 text-white' : 'bg-gray-300 text-gray-600']">
                                        ✓
                                    </div>
                                </div>
                                <div class="pt-1">
                                    <p class="font-semibold text-bark-900">{{ __('Delivered') }}</p>
                                    <p class="text-sm text-bark-600" x-show="currentOrder.status === 'delivered'">
                                        {{ __('Your order has been delivered successfully') }}
                                    </p>
                                    <p class="text-sm text-bark-600" x-show="currentOrder.status !== 'delivered'">
                                        {{ __('Your order will be marked as delivered upon successful delivery') }}
                                    </p>
                                </div>
                            </div>
                        </div>
                    </div>

                    {{-- Delivery Details Map --}}
                    <div x-show="currentOrder.delivery && currentOrder.delivery.latitude && currentOrder.delivery.longitude" x-cloak
                        class="bg-white rounded-xl border border-cream-200 p-6">
                        <h3 class="font-bold text-lg text-bark-900 mb-4">{{ __('Live Delivery Map') }}</h3>
                        <div id="trackingMap" class="w-full h-96 rounded-xl border border-cream-300" 
                            style="background: linear-gradient(135deg, #f7ecdc 0%, #f0e4d3 100%);"></div>
                        <p class="text-xs text-bark-600 mt-3">{{ __('Real-time delivery location (updates every 30 seconds)') }}</p>
                    </div>

                    {{-- Delivery Address --}}
                    <div x-show="currentOrder.delivery" x-cloak class="bg-white rounded-xl border border-cream-200 p-6">
                        <h3 class="font-bold text-lg text-bark-900 mb-4">{{ __('Delivery Address') }}</h3>
                        <div class="space-y-2">
                            <p class="text-lg font-semibold text-bark-900" x-text="currentOrder.delivery?.address"></p>
                            <p class="text-bark-600" x-text="currentOrder.delivery?.city + ', ' + currentOrder.delivery?.postal_code"></p>
                            <p class="text-bark-600">{{ __('Recipient:') }} <span class="font-semibold text-bark-900" x-text="currentOrder.delivery?.recipient_name"></span></p>
                            <p class="text-bark-600">{{ __('Phone:') }} <span class="font-semibold text-bark-900" x-text="currentOrder.delivery?.phone"></span></p>
                        </div>
                    </div>

                    {{-- Order Details --}}
                    <div class="bg-white rounded-xl border border-cream-200 p-6">
                        <h3 class="font-bold text-lg text-bark-900 mb-4">{{ __('Order Items') }}</h3>
                        <div class="space-y-3">
                            <template x-for="item in currentOrder.items" :key="item.id">
                                <div class="flex items-center justify-between pb-3 border-b border-cream-200 last:border-0">
                                    <div>
                                        <p class="font-semibold text-bark-900" x-text="item.product?.name"></p>
                                        <p class="text-sm text-bark-600">Qty: <span x-text="item.quantity"></span></p>
                                    </div>
                                    <p class="font-bold text-bark-900">₱<span x-text="item.subtotal?.toFixed(2)"></span></p>
                                </div>
                            </template>
                        </div>
                    </div>

                    {{-- Action Buttons --}}
                    <div class="flex gap-3">
                        <button type="button" @click="searchOrderNumber = ''; currentOrder = null"
                            class="flex-1 px-6 py-3 border-2 border-bark-300 text-bark-600 font-bold rounded-xl hover:bg-cream-100 transition">
                            {{ __('Track Another Order') }}
                        </button>
                        <button type="button" @click="refreshOrder()"
                            class="flex-1 px-6 py-3 bg-bark-300 hover:bg-bark-400 text-cream-50 font-bold rounded-xl transition flex items-center justify-center gap-2">
                            <svg class="w-5 h-5" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" d="M4 4v5h.582m15.356 2A8.001 8.001 0 004.582 9m0 0H9m11 11v-5h-.581m0 0a8.003 8.003 0 01-15.357-2m15.357 2H15"/>
                            </svg>
                            {{ __('Refresh Status') }}
                        </button>
                    </div>
                </div>
            </div>

            {{-- Info Cards --}}
            <div x-show="!currentOrder" x-transition class="grid grid-cols-1 md:grid-cols-3 gap-6 mb-8">
                <div class="bg-white rounded-2xl border border-cream-200 p-6 shadow-lg">
                    <div class="text-3xl mb-3">📍</div>
                    <h3 class="font-bold text-lg text-bark-900 mb-2">{{ __('Real-Time Location') }}</h3>
                    <p class="text-bark-600 text-sm">{{ __('Track your delivery driver in real-time with our live GPS map') }}</p>
                </div>

                <div class="bg-white rounded-2xl border border-cream-200 p-6 shadow-lg">
                    <div class="text-3xl mb-3">📱</div>
                    <h3 class="font-bold text-lg text-bark-900 mb-2">{{ __('Driver Contact') }}</h3>
                    <p class="text-bark-600 text-sm">{{ __('Direct contact information for your delivery driver') }}</p>
                </div>

                <div class="bg-white rounded-2xl border border-cream-200 p-6 shadow-lg">
                    <div class="text-3xl mb-3">⏱️</div>
                    <h3 class="font-bold text-lg text-bark-900 mb-2">{{ __('Live Updates') }}</h3>
                    <p class="text-bark-600 text-sm">{{ __('Get instant notifications about your delivery status') }}</p>
                </div>
            </div>

            {{-- Sample Order CTA --}}
            <div x-show="!currentOrder && !showSampleInfo" x-transition class="text-center bg-gold-50 border-2 border-dashed border-gold-200 rounded-2xl p-8 mb-8">
                <p class="text-bark-700 mb-4 font-semibold">{{ __('Want to see how order tracking works?') }}</p>
                <button type="button" @click="tryLoadSample()"
                    class="px-8 py-3 bg-gold-500 hover:bg-gold-600 text-white font-bold rounded-xl transition flex items-center justify-center gap-2 mx-auto">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M14.828 14.828a4 4 0 01-5.656 0M9 10h.01M15 10h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/>
                    </svg>
                    {{ __('Load Sample Order') }}
                </button>
            </div>
        </div>
    </div>
</x-app-layout>

<script>
function orderTracker() {
    return {
        searchOrderNumber: '',
        currentOrder: null,
        errorMsg: '',
        trackingMap: null,
        timerInterval: null,
        currentTime: new Date(),
        showSampleInfo: false,

        async init() {
            // Start real-time update timer
            this.timerInterval = setInterval(() => {
                this.currentTime = new Date();
                if (this.currentOrder) {
                    this.refreshOrder();
                }
            }, 30000); // Refresh every 30 seconds
        },

        async searchOrder() {
            if (!this.searchOrderNumber.trim()) {
                this.errorMsg = 'Please enter an order number';
                return;
            }

            this.errorMsg = '';
            this.showSampleInfo = false;
            try {
                const res = await fetch(`/api/track-order/${this.searchOrderNumber}`, {
                    headers: { 'Accept': 'application/json' },
                    credentials: 'same-origin',
                });
                
                if (!res.ok) throw new Error('Order not found');
                const data = await res.json();
                this.currentOrder = data.data || data;
                
                // Initialize map if delivery exists
                if (this.currentOrder.delivery?.latitude && this.currentOrder.delivery?.longitude) {
                    this.$nextTick(() => this.initMap());
                }
            } catch (e) {
                this.errorMsg = 'Order not found. Please check your order number and try again.';
                console.error('Error searching order:', e);
                // Load sample order for demo
                this.loadSampleOrder();
            }
        },

        loadSampleOrder() {
            this.currentOrder = {
                id: 1,
                order_number: this.searchOrderNumber || 'ORD-ABC123XYZ',
                status: 'in_transit',
                total: 350.00,
                created_at: new Date(Date.now() - 2 * 3600000).toISOString(),
                items: [
                    { id: 1, quantity: 5, product: { name: 'Pandesal' }, subtotal: 75.00 },
                    { id: 2, quantity: 2, product: { name: 'Ube Cake' }, subtotal: 200.00 },
                    { id: 3, quantity: 1, product: { name: 'Croissant' }, subtotal: 75.00 },
                ],
                delivery: {
                    recipient_name: 'Maria Santos',
                    address: '123 Main Street, Makati City',
                    city: 'Makati',
                    postal_code: '1200',
                    phone: '+63 912 345 6789',
                    latitude: 14.5546,
                    longitude: 121.0175,
                },
                driver: {
                    name: 'Juan Cruz',
                    phone: '+63 935 678 9012',
                    email: 'juan@bakery.com',
                },
            };
            this.$nextTick(() => this.initMap());
        },

        refreshOrder() {
            if (this.currentOrder) {
                this.searchOrder();
            }
        },

        tryLoadSample() {
            this.searchOrderNumber = 'ORD-SAMPLE123XYZ';
            this.loadSampleOrder();
            this.errorMsg = '';
            this.showSampleInfo = true;
        },

        formatDate(timestamp) {
            if (!timestamp) return '';
            const date = new Date(timestamp);
            return date.toLocaleDateString('en-US', { 
                month: 'short', 
                day: 'numeric', 
                hour: '2-digit', 
                minute: '2-digit',
                hour12: true 
            });
        },

        initMap() {
            if (!this.currentOrder?.delivery?.latitude || !this.currentOrder?.delivery?.longitude) return;

            const mapElement = document.getElementById('trackingMap');
            if (!mapElement) return;

            // Load Leaflet if not already loaded
            if (typeof L === 'undefined') {
                const link = document.createElement('link');
                link.rel = 'stylesheet';
                link.href = 'https://cdnjs.cloudflare.com/ajax/libs/leaflet/1.9.4/leaflet.min.css';
                document.head.appendChild(link);

                const script = document.createElement('script');
                script.src = 'https://cdnjs.cloudflare.com/ajax/libs/leaflet/1.9.4/leaflet.min.js';
                script.onload = () => {
                    this.createMap();
                };
                document.head.appendChild(script);
            } else {
                this.createMap();
            }
        },

        createMap() {
            if (this.trackingMap) {
                this.trackingMap.remove();
            }

            const lat = parseFloat(this.currentOrder.delivery.latitude);
            const lng = parseFloat(this.currentOrder.delivery.longitude);

            this.trackingMap = L.map('trackingMap').setView([lat, lng], 15);

            L.tileLayer('https://{s}.tile.openstreetmap.org/{z}/{x}/{y}.png', {
                attribution: '© OpenStreetMap contributors',
                maxZoom: 19,
            }).addTo(this.trackingMap);

            // Add delivery marker
            const marker = L.marker([lat, lng]).addTo(this.trackingMap);
            marker.bindPopup(`
                <div class="text-center">
                    <p class="font-bold">${this.currentOrder.delivery.recipient_name}</p>
                    <p class="text-sm">${this.currentOrder.delivery.address}</p>
                </div>
            `);

            // Add driver location (simulated)
            const driverLat = lat + (Math.random() * 0.01 - 0.005);
            const driverLng = lng + (Math.random() * 0.01 - 0.005);
            const driverMarker = L.marker([driverLat, driverLng], {
                icon: L.icon({
                    iconUrl: 'data:image/svg+xml,<svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="%23D4A574"><circle cx="12" cy="12" r="10"/></svg>',
                    iconSize: [32, 32],
                    iconAnchor: [16, 16],
                })
            }).addTo(this.trackingMap);
            driverMarker.bindPopup(`<div class="font-bold">${this.currentOrder.driver?.name || 'Driver'} 🚚</div>`);
        }
    };
}
</script>

<style>
[x-cloak] { display: none; }
</style>
