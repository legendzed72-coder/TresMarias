<x-app-layout>
    <x-slot name="header">
        <div class="flex items-center justify-between">
            <div>
                <h2 class="font-serif font-bold text-2xl text-bark-600 leading-tight">
                    {{ __('Point of Sale') }}
                </h2>
                <p class="mt-1 text-sm text-muted">{{ __('Fast checkout for in-store purchases') }}</p>
            </div>
            <div class="flex items-center gap-3">
                <button @click="printDailyReport()" class="px-4 py-2 bg-bark-300 text-cream-50 rounded-lg hover:bg-bark-400 text-sm font-semibold transition">
                    📊 Daily Report
                </button>
                <div x-show="offlineMode" class="flex items-center gap-2 px-3 py-1 bg-orange-100 text-orange-800 rounded-full text-xs font-semibold">
                    <span class="inline-block w-2 h-2 bg-orange-500 rounded-full"></span>
                    Offline Mode
                </div>
            </div>
        </div>
    </x-slot>

    <div class="py-6 bg-cream-50 min-h-screen" x-data="posSystem()" x-init="init()" x-cloak>
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
                
                {{-- Products Section (Left) --}}
                <div class="lg:col-span-2 space-y-4">
                    {{-- Category Filter --}}
                    <div class="bg-white rounded-xl p-4 shadow-sm border border-cream-200">
                        <div class="flex gap-2 flex-wrap">
                            <button @click="selectedCategory = null" :class="selectedCategory === null ? 'bg-bark-300 text-cream-50' : 'bg-cream-100 text-bark-600'" class="px-4 py-2 rounded-lg font-semibold text-sm transition hover:shadow-sm">
                                All Items
                            </button>
                            <template x-for="category in categories" :key="category.id">
                                <button @click="selectedCategory = category.id" :class="selectedCategory === category.id ? 'bg-bark-300 text-cream-50' : 'bg-cream-100 text-bark-600'" class="px-4 py-2 rounded-lg font-semibold text-sm transition hover:shadow-sm">
                                    <span x-text="category.name"></span>
                                </button>
                            </template>
                        </div>
                    </div>

                    {{-- Products Grid --}}
                    <div class="bg-white rounded-xl p-4 shadow-sm border border-cream-200">
                        <div class="grid grid-cols-2 md:grid-cols-3 lg:grid-cols-4 gap-3 max-h-96 overflow-y-auto">
                            <template x-for="product in filteredProducts" :key="product.id">
                                <button @click="addToCart(product)" class="relative rounded-lg border-2 border-cream-200 hover:border-bark-300 p-3 text-left transition hover:shadow-md group overflow-hidden">
                                    <div class="absolute inset-0 bg-bark-300/5 group-hover:bg-bark-300/10 transition"></div>
                                    <div class="relative">
                                        <div class="text-sm font-bold text-bark-900 line-clamp-2" x-text="product.name"></div>
                                        <div class="text-xs text-bark-600 mt-1">₱<span x-text="parseFloat(product.price).toFixed(2)"></span></div>
                                        <div class="text-xs text-leaf-600 mt-0.5 font-semibold" :class="product.stock_quantity <= 0 ? 'text-red-600' : ''">
                                            <span x-show="product.stock_quantity > 0" x-text="`Stock: ${product.stock_quantity}`"></span>
                                            <span x-show="product.stock_quantity <= 0">Out of Stock</span>
                                        </div>
                                    </div>
                                </button>
                            </template>
                        </div>
                    </div>
                </div>

                {{-- Cart & Payment Section (Right) --}}
                <div class="space-y-4">
                    {{-- Cart Items --}}
                    <div class="bg-white rounded-xl shadow-sm border border-cream-200 overflow-hidden flex flex-col max-h-96">
                        <div class="bg-bark-300 text-cream-50 px-4 py-3 font-bold text-lg flex items-center justify-between">
                            <span>🛒 Cart (<span x-text="cart.length"></span>)</span>
                            <button @click="cart = []" x-show="cart.length > 0" class="text-xs bg-red-500 hover:bg-red-600 px-2 py-1 rounded transition">Clear</button>
                        </div>
                        <div class="flex-1 overflow-y-auto p-3 space-y-2">
                            <template x-for="(item, index) in cart" :key="index">
                                <div class="bg-cream-50 rounded p-2 border border-cream-200">
                                    <div class="flex justify-between items-start gap-2">
                                        <div class="flex-1 text-sm">
                                            <div class="font-semibold text-bark-900" x-text="item.name"></div>
                                            <div class="text-xs text-bark-600">₱<span x-text="parseFloat(item.price).toFixed(2)"></span></div>
                                        </div>
                                        <button @click="removeFromCart(index)" class="text-red-500 hover:text-red-700 font-bold">×</button>
                                    </div>
                                    <div class="flex items-center gap-1 mt-1">
                                        <button @click="decrementItem(index)" class="px-2 py-0.5 bg-bark-200 hover:bg-bark-300 text-cream-50 rounded text-xs">−</button>
                                        <input type="number" x-model.number="item.quantity" @change="updateCartTotal()" class="w-8 text-center border border-cream-200 rounded text-xs" min="1">
                                        <button @click="incrementItem(index)" class="px-2 py-0.5 bg-bark-300 hover:bg-bark-400 text-cream-50 rounded text-xs">+</button>
                                        <span class="text-xs text-bark-600 ml-auto">₱<span x-text="(parseFloat(item.price) * item.quantity).toFixed(2)"></span></span>
                                    </div>
                                </div>
                            </template>
                            <div x-show="cart.length === 0" class="text-center py-8 text-muted">
                                <p class="text-sm">No items in cart</p>
                            </div>
                        </div>
                    </div>

                    {{-- Order Summary --}}
                    <div class="bg-white rounded-xl p-4 shadow-sm border border-cream-200 space-y-3">
                        <div class="space-y-2 text-sm border-b border-cream-200 pb-3">
                            <div class="flex justify-between">
                                <span class="text-bark-600">Subtotal:</span>
                                <span class="font-semibold">₱<span x-text="(parseFloat(subtotal) || 0).toFixed(2)"></span></span>
                            </div>
                            <div class="flex justify-between">
                                <span class="text-bark-600">Tax (12%):</span>
                                <span class="font-semibold">₱<span x-text="(parseFloat(tax) || 0).toFixed(2)"></span></span>
                            </div>
                            <div class="flex justify-between">
                                <span class="text-bark-600">Delivery:</span>
                                <select @change="updateCartTotal()" x-model="fulfillmentType" class="text-xs px-2 py-1 border border-cream-200 rounded">
                                    <option value="pickup">Pickup</option>
                                    <option value="delivery">Delivery (+₱50)</option>
                                </select>
                            </div>
                            <div x-show="fulfillmentType === 'delivery'" class="text-xs text-bark-600">Fee: ₱<span x-text="(parseFloat(deliveryFee) || 0).toFixed(2)"></span></div>
                        </div>
                        <div class="flex justify-between items-center text-lg font-bold bg-bark-100 p-3 rounded-lg">
                            <span>Total:</span>
                            <span class="text-bark-900">₱<span x-text="(parseFloat(total) || 0).toFixed(2)"></span></span>
                        </div>
                    </div>

                    {{-- Payment Section --}}
                    <div class="bg-white rounded-xl p-4 shadow-sm border border-cream-200 space-y-3">
                        <div>
                            <label class="block text-sm font-semibold text-bark-900 mb-2">Payment Method</label>
                            <select x-model="paymentMethod" class="w-full px-3 py-2 border border-cream-200 rounded-lg focus:ring-2 focus:ring-bark-300">
                                <option value="cash">💵 Cash</option>
                                <option value="card">💳 Card</option>
                                <option value="gcash">🏦 GCash</option>
                                <option value="maya">📱 Maya</option>
                                <option value="cod">📦 Cash on Delivery</option>
                                <option value="online">💻 Online Payment</option>
                            </select>
                        </div>
                        <button @click="processPayment()" :disabled="cart.length === 0 || processing" class="w-full py-3 bg-leaf-500 hover:bg-leaf-600 disabled:opacity-50 disabled:cursor-not-allowed text-white font-bold rounded-lg transition shadow-sm flex items-center justify-center gap-2">
                            <span x-show="!processing">✓ Complete Order</span>
                            <span x-show="processing" class="animate-spin">⟳</span>
                        </button>
                    </div>

                    {{-- Quick Stats --}}
                    <div class="bg-white rounded-xl p-3 shadow-sm border border-cream-200 text-xs">
                        <div class="space-y-1">
                            <div class="flex justify-between">
                                <span class="text-muted">Today's Sales:</span>
                                <span class="font-bold">₱<span x-text="(parseFloat(todaySales) || 0).toFixed(2)"></span></span>
                            </div>
                            <div class="flex justify-between">
                                <span class="text-muted">Orders:</span>
                                <span class="font-bold" x-text="todayOrders"></span>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    {{-- Receipt Modal --}}
    <div id="receiptModal" class="fixed inset-0 bg-black/50 hidden items-center justify-center z-50 p-4">
        <div class="bg-white rounded-xl shadow-lg w-full max-w-md max-h-96 overflow-y-auto">
            <div class="bg-bark-300 text-cream-50 px-6 py-4 flex justify-between items-center">
                <h2 class="font-bold text-lg">Order Receipt</h2>
                <button onclick="document.getElementById('receiptModal').classList.add('hidden')" class="text-cream-50 hover:text-cream-200">✕</button>
            </div>
            <div class="p-6 text-sm space-y-4" id="receiptContent"></div>
            <div class="p-6 border-t border-cream-200 flex gap-3">
                <button onclick="window.print()" class="flex-1 px-4 py-2 bg-bark-300 text-cream-50 rounded-lg hover:bg-bark-400 font-semibold transition">🖨️ Print</button>
                <button onclick="document.getElementById('receiptModal').classList.add('hidden')" class="flex-1 px-4 py-2 bg-cream-200 text-bark-600 rounded-lg hover:bg-cream-300 font-semibold transition">Close</button>
            </div>
        </div>
    </div>

    <script>
    function posSystem() {
        return {
            cart: [],
            products: [],
            categories: [],
            selectedCategory: null,
            paymentMethod: 'cash',
            fulfillmentType: 'pickup',
            processing: false,
            offlineMode: false,
            todaySales: 0,
            todayOrders: 0,
            subtotal: 0,
            tax: 0,
            deliveryFee: 0,
            total: 0,

            get filteredProducts() {
                return this.selectedCategory 
                    ? this.products.filter(p => p.category_id === this.selectedCategory)
                    : this.products;
            },

            async init() {
                await this.loadProducts();
                await this.loadCategories();
                await this.loadTodayStats();
                this.checkOfflineMode();
                setInterval(() => this.loadTodayStats(), 60000); // Refresh every minute
            },

            async loadProducts() {
                try {
                    const res = await fetch('/api/products', {
                        headers: { 'Accept': 'application/json' },
                        credentials: 'same-origin'
                    });
                    const data = await res.json();
                    this.products = Array.isArray(data) ? data : (data.data || []);
                } catch (e) {
                    console.error('Error loading products:', e);
                    this.offlineMode = true;
                }
            },

            async loadCategories() {
                try {
                    const res = await fetch('/api/categories', {
                        headers: { 'Accept': 'application/json' },
                        credentials: 'same-origin'
                    });
                    const data = await res.json();
                    this.categories = Array.isArray(data) ? data : (data.data || []);
                } catch (e) {
                    console.error('Error loading categories:', e);
                }
            },

            async loadTodayStats() {
                try {
                    const res = await fetch('/api/pos/daily-report', {
                        headers: { 'Accept': 'application/json' },
                        credentials: 'same-origin'
                    });
                    if (res.ok) {
                        const data = await res.json();
                        this.todaySales = data.total_sales || 0;
                        this.todayOrders = data.total_orders || 0;
                    }
                } catch (e) {
                    console.error('Error loading stats:', e);
                }
            },

            addToCart(product) {
                if (product.stock_quantity <= 0) return;
                
                const existing = this.cart.find(item => item.id === product.id);
                if (existing) {
                    if (existing.quantity < product.stock_quantity) {
                        existing.quantity++;
                    }
                } else {
                    this.cart.push({
                        id: product.id,
                        name: product.name,
                        price: product.price,
                        quantity: 1
                    });
                }
                this.updateCartTotal();
            },

            removeFromCart(index) {
                this.cart.splice(index, 1);
                this.updateCartTotal();
            },

            incrementItem(index) {
                this.cart[index].quantity++;
                this.updateCartTotal();
            },

            decrementItem(index) {
                if (this.cart[index].quantity > 1) {
                    this.cart[index].quantity--;
                } else {
                    this.removeFromCart(index);
                }
                this.updateCartTotal();
            },

            updateCartTotal() {
                this.subtotal = this.cart.reduce((sum, item) => sum + (parseFloat(item.price) * item.quantity), 0);
                this.tax = this.subtotal * 0.12;
                this.deliveryFee = this.fulfillmentType === 'delivery' ? 50 : 0;
                this.total = this.subtotal + this.tax + this.deliveryFee;
            },

            async processPayment() {
                if (this.cart.length === 0) return;

                this.processing = true;
                try {
                    const payload = {
                        items: this.cart.map(item => ({
                            product_id: item.id,
                            quantity: item.quantity
                        })),
                        payment_method: this.paymentMethod,
                        payment_status: 'paid',
                        fulfillment_type: this.fulfillmentType,
                        placed_by_admin: true
                    };

                    const csrfToken = document.querySelector('meta[name="csrf-token"]')?.getAttribute('content');
                    
                    const res = await fetch('/api/pos/orders', {
                        method: 'POST',
                        headers: {
                            'Content-Type': 'application/json',
                            'X-CSRF-TOKEN': csrfToken,
                            'Accept': 'application/json'
                        },
                        credentials: 'same-origin',
                        body: JSON.stringify(payload)
                    });

                    const data = await res.json();

                    if (res.ok) {
                        this.showReceipt(data.order || data);
                        this.cart = [];
                        this.updateCartTotal();
                        this.loadTodayStats();
                    } else {
                        alert('Error: ' + (data.message || 'Failed to process order'));
                    }
                } catch (e) {
                    console.error('Payment error:', e);
                    alert('Payment failed: ' + e.message);
                } finally {
                    this.processing = false;
                }
            },

            showReceipt(order) {
                let html = `
                    <div class="text-center border-b border-dashed pb-3 mb-3">
                        <div class="font-bold text-lg">TRES MARIAS</div>
                        <div class="text-xs text-muted">CAKES & PASTRIES</div>
                        <div class="text-xs text-muted mt-1">Receipt</div>
                    </div>
                    <div class="space-y-1 text-xs pb-3 border-b border-dashed">
                        <div><strong>Order #:</strong> ${order?.order_number || 'N/A'}</div>
                        <div><strong>Date:</strong> ${new Date().toLocaleString()}</div>
                        <div><strong>Payment:</strong> ${(order?.payment_method || 'UNKNOWN').toUpperCase()}</div>
                    </div>
                    <div class="space-y-2 text-xs pb-3 border-b border-dashed">
                        <div class="font-bold">Items:</div>
                        ${(order?.items || []).map(item => `
                            <div class="flex justify-between">
                                <span>${item.product?.name || 'Item'} x${item.quantity}</span>
                                <span>₱${(parseFloat(item.subtotal) || 0).toFixed(2)}</span>
                            </div>
                        `).join('')}
                    </div>
                    <div class="space-y-1 text-xs">
                        <div class="flex justify-between"><span>Subtotal:</span><span>₱${(parseFloat(order?.subtotal) || 0).toFixed(2)}</span></div>
                        <div class="flex justify-between"><span>Tax (12%):</span><span>₱${(parseFloat(order?.tax) || 0).toFixed(2)}</span></div>
                        ${order?.delivery_fee ? `<div class="flex justify-between"><span>Delivery:</span><span>₱${(parseFloat(order.delivery_fee) || 0).toFixed(2)}</span></div>` : ''}
                        <div class="flex justify-between font-bold text-sm pt-2 border-t"><span>TOTAL:</span><span>₱${(parseFloat(order?.total) || 0).toFixed(2)}</span></div>
                    </div>
                    <div class="text-center text-xs text-muted mt-4">Thank you for your purchase!</div>
                `;
                document.getElementById('receiptContent').innerHTML = html;
                document.getElementById('receiptModal').classList.remove('hidden');
                document.getElementById('receiptModal').style.display = 'flex';
            },

            printDailyReport() {
                alert(`Today's Report:\n\nTotal Sales: ₱${(parseFloat(this.todaySales) || 0).toFixed(2)}\nOrders: ${this.todayOrders}`);
            },

            checkOfflineMode() {
                window.addEventListener('offline', () => this.offlineMode = true);
                window.addEventListener('online', () => this.offlineMode = false);
            }
        };
    }
    </script>

    <style>
        [x-cloak] { display: none; }
        @media print {
            body { background: white; }
            #receiptModal { position: absolute; }
        }
    </style>
</x-app-layout>
