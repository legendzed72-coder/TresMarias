<x-app-layout>
    <div class="flex" x-data="transactionManagement()" x-init="init()" x-cloak>
        <x-admin-sidebar />
        
        <div class="flex-1 bg-cream-50">
            <div class="max-w-7xl mx-auto px-6 py-8">
                {{-- Header --}}
                <div class="flex justify-between items-center mb-8">
                    <div>
                        <h1 class="text-3xl font-bold text-bark-900">{{ __('Transactions & Billing') }}</h1>
                        <p class="text-bark-600 mt-1">{{ __('View all transaction payments, receipts, and billing information') }}</p>
                    </div>
                    <button 
                        onclick="openPlaceOrderModal()"
                        class="px-6 py-3 bg-green-500 hover:bg-green-600 text-white rounded-lg font-semibold transition shadow-sm flex items-center gap-2"
                    >
                        <span>➕</span> {{ __('Place Order') }}
                    </button>
                </div>

                {{-- KPI Cards --}}
                <div class="grid grid-cols-1 md:grid-cols-4 gap-4 mb-8">
                    <div class="bg-white rounded-xl border border-cream-200 p-6 shadow-sm">
                        <div class="flex items-center justify-between">
                            <div>
                                <p class="text-sm text-bark-600">{{ __('Total Transactions') }}</p>
                                <p class="text-2xl font-bold text-bark-900 mt-2" id="totalTransactions">0</p>
                            </div>
                            <svg class="w-10 h-10 text-blue-500" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" d="M12 8c-1.657 0-3 .895-3 2s1.343 2 3 2 3 .895 3 2-1.343 2-3 2m0-8c1.11 0 2.08.402 2.599 1M12 8V7m0 1v8m0 0v1m0-1c-1.11 0-2.08-.402-2.599-1M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/>
                            </svg>
                        </div>
                    </div>

                    <div class="bg-white rounded-xl border border-cream-200 p-6 shadow-sm">
                        <div class="flex items-center justify-between">
                            <div>
                                <p class="text-sm text-bark-600">{{ __('Total Sales') }}</p>
                                <p class="text-2xl font-bold text-bark-900 mt-2" id="totalSales">₱0.00</p>
                            </div>
                            <svg class="w-10 h-10 text-green-500" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" d="M12 8c-1.657 0-3 .895-3 2s1.343 2 3 2 3 .895 3 2-1.343 2-3 2m0-8c1.11 0 2.08.402 2.599 1M12 8V7m0 1v8m0 0v1m0-1c-1.11 0-2.08-.402-2.599-1M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/>
                            </svg>
                        </div>
                    </div>

                    <div class="bg-white rounded-xl border border-cream-200 p-6 shadow-sm">
                        <div class="flex items-center justify-between">
                            <div>
                                <p class="text-sm text-bark-600">{{ __('Card Payments') }}</p>
                                <p class="text-2xl font-bold text-bark-900 mt-2" id="cardTransactions">0</p>
                            </div>
                            <svg class="w-10 h-10 text-purple-500" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" d="M3 10h18M7 15h10M7 20h10M5 5h14a2 2 0 012 2v10a2 2 0 01-2 2H5a2 2 0 01-2-2V7a2 2 0 012-2z"/>
                            </svg>
                        </div>
                    </div>

                    <div class="bg-white rounded-xl border border-cream-200 p-6 shadow-sm">
                        <div class="flex items-center justify-between">
                            <div>
                                <p class="text-sm text-bark-600">{{ __('Online Payments') }}</p>
                                <p class="text-2xl font-bold text-bark-900 mt-2" id="onlineTransactions">0</p>
                            </div>
                            <svg class="w-10 h-10 text-blue-500" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" d="M9.663 17h4.673M12 3v1m6.364 1.636l-.707.707M21 12a9 9 0 11-18 0 9 9 0 0118 0m-5.36 4.364l-.707-.707M9 12a3 3 0 11-6 0 3 3 0 016 0z"/>
                            </svg>
                        </div>
                    </div>
                </div>

                {{-- Filters --}}
                <div class="bg-white rounded-xl border border-cream-200 p-6 mb-8 shadow-sm">
                    <div class="grid grid-cols-1 md:grid-cols-4 gap-4">
                        <input 
                            type="text" 
                            x-model="search"
                            placeholder="{{ __('Search by order#, customer...') }}"
                            class="px-4 py-2 border border-cream-300 rounded-lg focus:ring-2 focus:ring-bark-300 focus:border-transparent"
                        >
                        <select x-model="paymentFilter" class="px-4 py-2 border border-cream-300 rounded-lg focus:ring-2 focus:ring-bark-300 focus:border-transparent">
                            <option value="">{{ __('All Payment Methods') }}</option>
                            <option value="cod">{{ __('💵 Cash on Delivery (COD)') }}</option>
                            <option value="online">{{ __('💳 Online Payment') }}</option>
                            <option value="card">{{ __('💳 Card Payment') }}</option>
                            <option value="cash">{{ __('💵 Cash') }}</option>
                            <option value="gcash">{{ __('🏦 GCash') }}</option>
                            <option value="maya">{{ __('📱 Maya (Online)') }}</option>
                        </select>
                        <select x-model="statusFilter" class="px-4 py-2 border border-cream-300 rounded-lg focus:ring-2 focus:ring-bark-300 focus:border-transparent">
                            <option value="">{{ __('All Statuses') }}</option>
                            <option value="completed">{{ __('Completed') }}</option>
                            <option value="pending">{{ __('Pending') }}</option>
                            <option value="cancelled">{{ __('Cancelled') }}</option>
                        </select>
                        <button 
                            onclick="transactionApp.fetchTransactions()"
                            class="px-4 py-2 bg-bark-300 text-cream-50 rounded-lg hover:bg-bark-400 transition font-semibold"
                        >
                            {{ __('Search') }}
                        </button>
                    </div>
                </div>

                {{-- Transactions Table --}}
                <div class="bg-white rounded-xl border border-cream-200 overflow-hidden shadow-sm">
                    <div class="overflow-x-auto">
                        <table class="w-full">
                            <thead class="bg-cream-100 border-b border-cream-200">
                                <tr class="text-left text-sm font-semibold text-bark-900">
                                    <th class="px-6 py-4">{{ __('Order #') }}</th>
                                    <th class="px-6 py-4">{{ __('Customer') }}</th>
                                    <th class="px-6 py-4">{{ __('Payment Method') }}</th>
                                    <th class="px-6 py-4">{{ __('Amount') }}</th>
                                    <th class="px-6 py-4">{{ __('Status') }}</th>
                                    <th class="px-6 py-4">{{ __('Date') }}</th>
                                    <th class="px-6 py-4">{{ __('Actions') }}</th>
                                </tr>
                            </thead>
                            <tbody class="divide-y divide-cream-200" id="transactionsTableBody">
                                <tr>
                                    <td colspan="7" class="px-6 py-8 text-center text-bark-600">
                                        {{ __('Loading transactions...') }}
                                    </td>
                                </tr>
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </div>
</x-app-layout>

{{-- Place Order Modal --}}
<div id="placeOrderModal" class="hidden fixed inset-0 bg-black bg-opacity-50 flex items-center justify-center z-50 p-4">
    <div class="bg-white rounded-xl shadow-lg w-full max-w-2xl mx-auto">
        <div class="flex justify-between items-center p-6 border-b border-cream-200">
            <h2 class="text-2xl font-bold text-bark-900">{{ __('Place New Order') }}</h2>
            <button onclick="closePlaceOrderModal()" class="text-gray-500 hover:text-gray-700 text-2xl">✕</button>
        </div>
        
        <form id="placeOrderForm" class="p-6 space-y-4" onsubmit="submitPlaceOrder(event)">
            <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                {{-- Customer Selection --}}
                <div class="relative">
                    <label class="block text-sm font-semibold text-bark-900 mb-2">{{ __('Customer') }} *</label>
                    <input 
                        type="text" 
                        id="customerSearch"
                        placeholder="{{ __('Search customer...') }}"
                        class="w-full px-4 py-2 border border-cream-300 rounded-lg focus:ring-2 focus:ring-bark-300 focus:border-transparent"
                        required
                    >
                    <input type="hidden" id="selectedCustomerId" name="customer_id">
                    <div id="customerDropdown" class="hidden absolute top-full left-0 right-0 mt-1 border border-cream-300 rounded-lg bg-white shadow-lg max-h-48 overflow-y-auto z-10"></div>
                </div>

                {{-- Product Selection --}}
                <div>
                    <label class="block text-sm font-semibold text-bark-900 mb-2">{{ __('Product') }} *</label>
                    <select id="productSelect" class="w-full px-4 py-2 border border-cream-300 rounded-lg focus:ring-2 focus:ring-bark-300 focus:border-transparent" required onchange="updateProductPrice()">
                        <option value="">{{ __('Select a product...') }}</option>
                    </select>
                    <div id="productStockInfo" class="mt-2 p-3 rounded-lg text-sm hidden"
                        :class="document.getElementById('productSelect')?.selectedOptions[0]?.dataset?.stock == 0 ? 'bg-red-50 text-red-700' : 'bg-leaf-50 text-leaf-700'">
                        <span id="stockText">Select a product to see stock</span>
                    </div>
                </div>

                {{-- Quantity --}}
                <div>
                    <label class="block text-sm font-semibold text-bark-900 mb-2">{{ __('Quantity') }} *</label>
                    <input 
                        type="number" 
                        id="orderQuantity"
                        name="quantity"
                        min="1"
                        value="1"
                        class="w-full px-4 py-2 border border-cream-300 rounded-lg focus:ring-2 focus:ring-bark-300 focus:border-transparent"
                        required
                        onchange="updateOrderTotal()"
                    >
                </div>

                {{-- Price --}}
                <div>
                    <label class="block text-sm font-semibold text-bark-900 mb-2">{{ __('Unit Price') }}</label>
                    <input 
                        type="text" 
                        id="unitPrice"
                        class="w-full px-4 py-2 border border-cream-300 rounded-lg bg-cream-100 text-bark-900"
                        readonly
                    >
                </div>

                {{-- Payment Method --}}
                <div>
                    <label class="block text-sm font-semibold text-bark-900 mb-2">{{ __('Payment Method') }} *</label>
                    <select id="paymentMethod" name="payment_method" class="w-full px-4 py-2 border border-cream-300 rounded-lg focus:ring-2 focus:ring-bark-300 focus:border-transparent" required>
                        <option value="cod">💵 {{ __('Cash on Delivery (COD)') }}</option>
                        <option value="online">💳 {{ __('Online Payment') }}</option>
                        <option value="card">💳 {{ __('Card Payment') }}</option>
                        <option value="cash">💵 {{ __('Cash') }}</option>
                        <option value="gcash">🏦 {{ __('GCash') }}</option>
                        <option value="maya">📱 {{ __('Maya') }}</option>
                    </select>
                </div>

                {{-- Fulfillment Type --}}
                <div>
                    <label class="block text-sm font-semibold text-bark-900 mb-2">{{ __('Fulfillment Type') }} *</label>
                    <select id="fulfillmentType" name="fulfillment_type" class="w-full px-4 py-2 border border-cream-300 rounded-lg focus:ring-2 focus:ring-bark-300 focus:border-transparent" required onchange="updateOrderTotal()">
                        <option value="pickup">🏪 {{ __('Pickup') }}</option>
                        <option value="delivery">🚚 {{ __('Delivery (+₱50)') }}</option>
                    </select>
                </div>
            </div>

            {{-- Special Instructions --}}
            <div>
                <label class="block text-sm font-semibold text-bark-900 mb-2">{{ __('Special Instructions') }}</label>
                <textarea 
                    id="specialInstructions"
                    name="special_instructions"
                    placeholder="{{ __('Any special requests...') }}"
                    class="w-full px-4 py-2 border border-cream-300 rounded-lg focus:ring-2 focus:ring-bark-300 focus:border-transparent"
                    rows="3"
                ></textarea>
            </div>

            {{-- Order Summary --}}
            <div class="bg-cream-50 p-4 rounded-lg space-y-2">
                <div class="flex justify-between">
                    <span class="text-bark-600">{{ __('Subtotal:') }}</span>
                    <span class="font-semibold text-bark-900" id="orderSubtotal">₱0.00</span>
                </div>
                <div class="flex justify-between">
                    <span class="text-bark-600">{{ __('Tax (12%):') }}</span>
                    <span class="font-semibold text-bark-900" id="orderTax">₱0.00</span>
                </div>
                <div class="flex justify-between">
                    <span class="text-bark-600">{{ __('Delivery Fee:') }}</span>
                    <span class="font-semibold text-bark-900" id="orderDelivery">₱0.00</span>
                </div>
                <div class="flex justify-between pt-2 border-t border-cream-300">
                    <span class="text-bark-900 font-bold">{{ __('Total:') }}</span>
                    <span class="font-bold text-lg text-bark-900" id="orderTotal">₱0.00</span>
                </div>
            </div>

            {{-- Buttons --}}
            <div class="flex gap-4 pt-4">
                <button 
                    type="button"
                    onclick="closePlaceOrderModal()"
                    class="flex-1 px-4 py-2 bg-gray-300 text-gray-800 rounded-lg hover:bg-gray-400 transition font-semibold"
                >
                    {{ __('Cancel') }}
                </button>
                <button 
                    type="submit"
                    class="flex-1 px-4 py-2 bg-green-500 text-white rounded-lg hover:bg-green-600 transition font-semibold"
                >
                    {{ __('Place Order') }}
                </button>
            </div>
        </form>
    </div>
</div>

{{-- Receipt Modal --}}
<div id="receiptModal" class="fixed inset-0 bg-black/50 hidden items-center justify-center z-50 p-4">
    <div class="bg-white rounded-xl shadow-lg w-full max-w-2xl max-h-96 overflow-y-auto">
        <div class="p-6 border-b border-cream-200 flex justify-between items-center">
            <h2 class="text-xl font-bold text-bark-900">{{ __('Receipt & Billing') }}</h2>
            <button 
                onclick="document.getElementById('receiptModal').classList.add('hidden')"
                class="text-bark-600 hover:text-bark-900"
            >
                ✕
            </button>
        </div>
        <div class="p-6" id="receiptContent">
            {{-- Receipt content will be populated by Alpine.js --}}
        </div>
        <div class="p-6 border-t border-cream-200 flex justify-end gap-3">
            <button 
                onclick="document.getElementById('receiptModal').classList.add('hidden')"
                class="px-4 py-2 bg-bark-300 text-cream-50 rounded-lg hover:bg-bark-400 transition font-semibold"
            >
                {{ __('Close') }}
            </button>
        </div>
    </div>
</div>

<script>
let transactionApp;

function transactionManagement() {
    return {
        transactions: [],
        search: '',
        paymentFilter: '',
        statusFilter: '',
        selectedTransaction: null,

        async init() {
            transactionApp = this;
            await this.fetchTransactions();
        },

        async fetchTransactions() {
            try {
                console.log('=== fetchTransactions called ===');
                const params = new URLSearchParams();
                if (this.search) params.append('search', this.search);
                if (this.paymentFilter) params.append('payment_method', this.paymentFilter);
                if (this.statusFilter) params.append('status', this.statusFilter);

                const url = `/api/admin/orders?${params}`;
                console.log('Fetching from:', url);

                const res = await fetch(url, {
                    headers: { 'Accept': 'application/json' },
                    credentials: 'same-origin',
                });
                
                console.log('Response status:', res.status, res.statusText);

                if (!res.ok) {
                    const text = await res.text();
                    throw new Error(`HTTP ${res.status}: ${text}`);
                }

                const data = await res.json();
                console.log('API Response:', data);

                this.transactions = Array.isArray(data) ? data : (data.data || []);
                console.log('Transactions loaded:', this.transactions.length, 'items');
                console.log('First transaction:', this.transactions[0]);
                
                this.renderTable();
                this.calculateStats();
                
            } catch (e) {
                console.error('=== ERROR in fetchTransactions ===', e);
                console.error('Stack trace:', e.stack);
                
                // Show error in table
                const tbody = document.getElementById('transactionsTableBody');
                if (tbody) {
                    tbody.innerHTML = `<tr><td colspan="7" class="px-6 py-8 text-center text-red-600"><strong>Error:</strong> ${e.message}</td></tr>`;
                }
            }
        },

        calculateStats() {
            let totalSales = 0;
            let cardCount = 0;
            let cashCount = 0;
            let onlineCount = 0;

            this.transactions.forEach(t => {
                totalSales += parseFloat(t.total || 0);
                if (t.payment_method === 'card') cardCount++;
                if (t.payment_method === 'cash') cashCount++;
                if (t.payment_method === 'gcash' || t.payment_method === 'maya') onlineCount++;
            });

            document.getElementById('totalTransactions').textContent = this.transactions.length;
            document.getElementById('totalSales').textContent = '₱' + totalSales.toFixed(2);
            document.getElementById('cardTransactions').textContent = cardCount;
            document.getElementById('onlineTransactions').textContent = onlineCount;
        },

        renderTable() {
            try {
                console.log('Starting renderTable with', this.transactions.length, 'transactions');
                const tbody = document.getElementById('transactionsTableBody');
                
                if (!tbody) {
                    console.error('ERROR: transactionsTableBody element not found!');
                    return;
                }

                if (this.transactions.length === 0) {
                    tbody.innerHTML = '<tr><td colspan="7" class="px-6 py-8 text-center text-bark-600">No transactions found</td></tr>';
                    console.log('No transactions to display');
                    return;
                }

                let html = '';
                for (let t of this.transactions) {
                    try {
                        let paymentBadge = '<span class="px-3 py-1 rounded-full text-xs font-semibold bg-gray-100 text-gray-800">Unknown</span>';
                        
                        if (t.payment_method === 'card') {
                            paymentBadge = '<span class="px-3 py-1 rounded-full text-xs font-semibold bg-purple-100 text-purple-800">💳 Card</span>';
                        } else if (t.payment_method === 'cash') {
                            paymentBadge = '<span class="px-3 py-1 rounded-full text-xs font-semibold bg-amber-100 text-amber-800">💵 Cash</span>';
                        } else if (t.payment_method === 'gcash') {
                            paymentBadge = '<span class="px-3 py-1 rounded-full text-xs font-semibold bg-blue-100 text-blue-800">🏦 GCash</span>';
                        } else if (t.payment_method === 'maya') {
                            paymentBadge = '<span class="px-3 py-1 rounded-full text-xs font-semibold bg-pink-100 text-pink-800">📱 Maya</span>';
                        } else if (t.payment_method === 'cod') {
                            paymentBadge = '<span class="px-3 py-1 rounded-full text-xs font-semibold bg-orange-100 text-orange-800">💵 COD</span>';
                        } else if (t.payment_method === 'online') {
                            paymentBadge = '<span class="px-3 py-1 rounded-full text-xs font-semibold bg-indigo-100 text-indigo-800">💳 Online</span>';
                        }

                        let statusClass = 'bg-gray-100 text-gray-800';
                        if (t.status === 'completed') statusClass = 'bg-green-100 text-green-800';
                        else if (t.status === 'pending') statusClass = 'bg-yellow-100 text-yellow-800';
                        else if (t.status === 'cancelled') statusClass = 'bg-red-100 text-red-800';

                        const statusBadge = `<span class="px-3 py-1 rounded-full text-xs font-semibold ${statusClass}">${(t.status || 'pending').toUpperCase()}</span>`;
                        const customerName = t.user?.name || t.customer?.name || 'N/A';
                        const customerEmail = t.user?.email || t.customer?.email || '';
                        const total = parseFloat(t.total || 0).toFixed(2);
                        const date = new Date(t.created_at).toLocaleDateString();

                        html += `<tr class="hover:bg-cream-50 transition">
                            <td class="px-6 py-4 text-sm font-semibold text-bark-900">${t.order_number || 'N/A'}</td>
                            <td class="px-6 py-4">
                                <div class="text-sm font-semibold text-bark-900">${customerName}</div>
                                <div class="text-xs text-bark-600">${customerEmail}</div>
                            </td>
                            <td class="px-6 py-4">${paymentBadge}</td>
                            <td class="px-6 py-4 text-sm font-semibold text-bark-900">₱${total}</td>
                            <td class="px-6 py-4">${statusBadge}</td>
                            <td class="px-6 py-4 text-sm text-bark-600">${date}</td>
                            <td class="px-6 py-4">
                                <button onclick="transactionApp.viewReceipt(${t.id})" class="text-blue-600 hover:text-blue-800 text-sm font-semibold mr-3">Receipt</button>
                                <button onclick="transactionApp.viewBilling(${t.id})" class="text-green-600 hover:text-green-800 text-sm font-semibold">Billing</button>
                            </td>
                        </tr>`;
                    } catch (rowError) {
                        console.error('Error rendering row for transaction:', t, rowError);
                    }
                }

                tbody.innerHTML = html;
                console.log('Table rendered successfully with', this.transactions.length, 'rows');
            } catch (e) {
                console.error('ERROR in renderTable:', e);
                const tbody = document.getElementById('transactionsTableBody');
                if (tbody) {
                    tbody.innerHTML = '<tr><td colspan="7" class="px-6 py-8 text-center text-bark-600">Error loading transactions</td></tr>';
                }
            }
        },

        viewReceipt(transactionId) {
            const transaction = this.transactions.find(t => t.id === transactionId);
            if (!transaction) return;
            this.selectedTransaction = transaction;
            document.getElementById('receiptContent').innerHTML = this.generateReceiptHTML(transaction);
            document.getElementById('receiptModal').classList.remove('hidden');
            document.getElementById('receiptModal').style.display = 'flex';
        },

        viewBilling(transactionId) {
            const transaction = this.transactions.find(t => t.id === transactionId);
            if (!transaction) return;
            this.selectedTransaction = transaction;
            document.getElementById('receiptContent').innerHTML = this.generateBillingHTML(transaction);
            document.getElementById('receiptModal').classList.remove('hidden');
            document.getElementById('receiptModal').style.display = 'flex';
        },

        generateReceiptHTML(transaction) {
            return `
                <div class="space-y-4">
                    <div class="text-center pb-4 border-b border-dashed border-bark-300">
                        <h3 class="text-lg font-bold text-bark-900">TRES MARIAS</h3>
                        <p class="text-xs text-bark-600">Order Receipt</p>
                    </div>
                    <div class="space-y-2 text-sm">
                        <div class="flex justify-between">
                            <span class="text-bark-600">Order#:</span>
                            <span class="font-semibold">${transaction.order_number}</span>
                        </div>
                        <div class="flex justify-between">
                            <span class="text-bark-600">Date:</span>
                            <span class="font-semibold">${new Date(transaction.created_at).toLocaleString()}</span>
                        </div>
                        <div class="flex justify-between">
                            <span class="text-bark-600">Customer:</span>
                            <span class="font-semibold">${transaction.user?.name || 'Guest'}</span>
                        </div>
                    </div>
                    <div class="py-4 border-y border-dashed border-bark-300">
                        <h4 class="font-bold mb-2">Items & Stock Impact</h4>
                        <div class="text-xs space-y-2">
                            ${transaction.items?.map(item => `
                                <div class="flex justify-between items-start gap-2 p-2 bg-cream-50 rounded">
                                    <div class="flex-1">
                                        <div class="font-semibold text-bark-900">${item.product?.name}</div>
                                        <div class="text-bark-600">
                                            Qty: <span class="font-bold">${item.quantity}</span> | 
                                            Stock: <span class="font-bold">${item.product?.stock_quantity || 0}</span>
                                        </div>
                                    </div>
                                    <div class="text-right">
                                        <div>₱${parseFloat(item.subtotal).toFixed(2)}</div>
                                        <div :class="item.product?.stock_quantity > 0 ? 'text-leaf-600' : 'text-red-600'" class="text-[10px] font-semibold">
                                            ${item.product?.stock_quantity > 0 ? '✓ In Stock' : '⚠ Low/Out'}
                                        </div>
                                    </div>
                                </div>
                            `).join('') || '<p class="text-bark-600">No items</p>'}
                        </div>
                    </div>
                    <div class="space-y-1 text-sm">
                        <div class="flex justify-between"><span class="text-bark-600">Subtotal:</span><span>₱${parseFloat(transaction.subtotal || 0).toFixed(2)}</span></div>
                        ${transaction.tax ? `<div class="flex justify-between"><span class="text-bark-600">Tax:</span><span>₱${parseFloat(transaction.tax).toFixed(2)}</span></div>` : ''}
                        ${transaction.delivery_fee ? `<div class="flex justify-between"><span class="text-bark-600">Delivery Fee:</span><span>₱${parseFloat(transaction.delivery_fee).toFixed(2)}</span></div>` : ''}
                        ${transaction.discount ? `<div class="flex justify-between text-green-600"><span>Discount:</span><span>-₱${parseFloat(transaction.discount).toFixed(2)}</span></div>` : ''}
                    </div>
                    <div class="pt-2 border-t border-dashed border-bark-300 flex justify-between font-bold">
                        <span class="text-lg">TOTAL:</span>
                        <span class="text-lg">₱${parseFloat(transaction.total).toFixed(2)}</span>
                    </div>
                    <div class="text-center text-xs text-bark-600 pt-4">
                        <p>Thank you for your purchase!</p>
                    </div>
                </div>
            `;
        },

        generateBillingHTML(transaction) {
            return `
                <div class="space-y-6">
                    <div class="border-b border-cream-200 pb-4">
                        <h3 class="text-lg font-bold mb-2">{{ __('Billing Information') }}</h3>
                        <div class="grid grid-cols-2 gap-4 text-sm">
                            <div>
                                <p class="text-bark-600">{{ __('Order Number') }}</p>
                                <p class="font-semibold">${transaction.order_number}</p>
                            </div>
                            <div>
                                <p class="text-bark-600">{{ __('Date') }}</p>
                                <p class="font-semibold">${new Date(transaction.created_at).toLocaleDateString()}</p>
                            </div>
                            <div>
                                <p class="text-bark-600">{{ __('Status') }}</p>
                                <p class="font-semibold">${transaction.status}</p>
                            </div>
                        </div>
                    </div>
                    <div class="border-b border-cream-200 pb-4">
                        <h3 class="text-lg font-bold mb-2">{{ __('Customer Details') }}</h3>
                        <div class="space-y-1 text-sm">
                            <p><span class="text-bark-600">Name:</span> <span class="font-semibold">${transaction.user?.name || 'N/A'}</span></p>
                            <p><span class="text-bark-600">Email:</span> <span class="font-semibold">${transaction.user?.email || 'N/A'}</span></p>
                        </div>
                    </div>
                    <div class="border-b border-cream-200 pb-4">
                        <h3 class="text-lg font-bold mb-2">{{ __('Items & Stock') }}</h3>
                        <div class="space-y-2 text-sm">
                            ${transaction.items?.map(item => `
                                <div class="flex justify-between items-center p-2 bg-cream-50 rounded">
                                    <div>
                                        <p class="font-semibold text-bark-900">${item.product?.name}</p>
                                        <p class="text-xs text-bark-600">Qty: ${item.quantity} | Current Stock: ${item.product?.stock_quantity || 0}</p>
                                    </div>
                                    <p class="font-semibold">₱${parseFloat(item.subtotal).toFixed(2)}</p>
                                </div>
                            `).join('') || '<p class="text-bark-600">No items</p>'}
                        </div>
                    </div>
                    <div class="space-y-2 text-sm">
                        <div class="flex justify-between"><span class="text-bark-600">Subtotal:</span><span>₱${parseFloat(transaction.subtotal || 0).toFixed(2)}</span></div>
                        ${transaction.tax ? `<div class="flex justify-between"><span class="text-bark-600">Tax:</span><span>₱${parseFloat(transaction.tax).toFixed(2)}</span></div>` : ''}
                        ${transaction.delivery_fee ? `<div class="flex justify-between"><span class="text-bark-600">Delivery Fee:</span><span>₱${parseFloat(transaction.delivery_fee).toFixed(2)}</span></div>` : ''}
                        ${transaction.discount ? `<div class="flex justify-between text-green-600"><span>Discount:</span><span>-₱${parseFloat(transaction.discount).toFixed(2)}</span></div>` : ''}
                        <div class="flex justify-between pt-2 border-t border-cream-300 font-bold"><span>Total Amount Due</span><span class="text-lg">₱${parseFloat(transaction.total).toFixed(2)}</span></div>
                    </div>
                </div>
            `;
        },


    };
}

function openPlaceOrderModal() {
    document.getElementById('placeOrderModal').classList.remove('hidden');
    loadProducts();
    loadCustomers();
}

function closePlaceOrderModal() {
    document.getElementById('placeOrderModal').classList.add('hidden');
    document.getElementById('placeOrderForm').reset();
    document.getElementById('selectedCustomerId').value = '';
    document.getElementById('customerSearch').value = '';
    updateOrderTotal();
}

let allCustomers = [];
let allProducts = [];

async function loadCustomers() {
    try {
        const res = await fetch('/api/admin/customers', {
            headers: { 'Accept': 'application/json' },
            credentials: 'same-origin'
        });
        const data = await res.json();
        allCustomers = Array.isArray(data) ? data : (data.data || []);
        setupCustomerSearch();
    } catch (e) {
        console.error('Error loading customers:', e);
    }
}

async function loadProducts() {
    try {
        const res = await fetch('/api/admin/products', {
            headers: { 'Accept': 'application/json' },
            credentials: 'same-origin'
        });
        const data = await res.json();
        allProducts = Array.isArray(data) ? data : (data.data || []);
        
        const selectEl = document.getElementById('productSelect');
        selectEl.innerHTML = '<option value="">{{ __("Select a product...") }}</option>';
        allProducts.forEach(p => {
            const stockStatus = p.stock_quantity === 0 ? '❌ Out of Stock' : 
                               p.stock_quantity <= p.min_stock_level ? '⚠️ Low Stock' : '✓ In Stock';
            selectEl.innerHTML += `<option value="${p.id}" data-price="${p.price}" data-stock="${p.stock_quantity}" data-min-stock="${p.min_stock_level}">${p.name} (₱${parseFloat(p.price).toFixed(2)}) - ${stockStatus}</option>`;
        });
    } catch (e) {
        console.error('Error loading products:', e);
    }
}

function setupCustomerSearch() {
    const input = document.getElementById('customerSearch');
    const dropdown = document.getElementById('customerDropdown');
    
    input.addEventListener('input', function(e) {
        const query = e.target.value.toLowerCase();
        
        if (!query) {
            dropdown.classList.add('hidden');
            return;
        }
        
        const filtered = allCustomers.filter(c => 
            c.name.toLowerCase().includes(query) || 
            c.email.toLowerCase().includes(query)
        );
        
        if (filtered.length === 0) {
            dropdown.innerHTML = '<div class="p-2 text-bark-600">{{ __("No customers found") }}</div>';
        } else {
            dropdown.innerHTML = filtered.map(c => `
                <div 
                    class="p-2 hover:bg-cream-100 cursor-pointer border-b border-cream-200"
                    onclick="selectCustomer(${c.id}, '${c.name}')"
                >
                    <p class="font-semibold text-bark-900">${c.name}</p>
                    <p class="text-xs text-bark-600">${c.email}</p>
                </div>
            `).join('');
        }
        
        dropdown.classList.remove('hidden');
    });
}

function selectCustomer(id, name) {
    document.getElementById('selectedCustomerId').value = id;
    document.getElementById('customerSearch').value = name;
    document.getElementById('customerDropdown').classList.add('hidden');
}

function updateProductPrice() {
    const select = document.getElementById('productSelect');
    const option = select.options[select.selectedIndex];
    const price = option.dataset.price || 0;
    const stock = parseInt(option.dataset.stock) || 0;
    const minStock = parseInt(option.dataset['min-stock']) || 0;
    
    document.getElementById('unitPrice').value = '₱' + parseFloat(price).toFixed(2);
    
    // Show stock info
    const stockInfo = document.getElementById('productStockInfo');
    const stockText = document.getElementById('stockText');
    const quantityInput = document.getElementById('orderQuantity');
    
    if (option.value) {
        stockInfo.classList.remove('hidden');
        if (stock === 0) {
            stockText.innerHTML = '❌ <strong>Out of Stock!</strong> Cannot place order.';
            stockInfo.className = 'mt-2 p-3 rounded-lg text-sm bg-red-50 text-red-700';
            quantityInput.max = 0;
        } else if (stock <= minStock) {
            stockText.innerHTML = `⚠️ <strong>Low Stock:</strong> Only ${stock} units available (Min: ${minStock})`;
            stockInfo.className = 'mt-2 p-3 rounded-lg text-sm bg-gold-50 text-gold-700';
            quantityInput.max = stock;
        } else {
            stockText.innerHTML = `✓ <strong>In Stock:</strong> ${stock} units available`;
            stockInfo.className = 'mt-2 p-3 rounded-lg text-sm bg-leaf-50 text-leaf-700';
            quantityInput.max = stock;
        }
    } else {
        stockInfo.classList.add('hidden');
        quantityInput.max = '';
    }
    
    updateOrderTotal();
}

function updateOrderTotal() {
    const quantity = parseInt(document.getElementById('orderQuantity').value) || 0;
    const priceText = document.getElementById('unitPrice').value.replace('₱', '');
    const price = parseFloat(priceText) || 0;
    const fulfillment = document.getElementById('fulfillmentType').value;
    
    const subtotal = price * quantity;
    const tax = subtotal * 0.12; // 12% VAT
    const delivery = fulfillment === 'delivery' ? 50 : 0;
    const total = subtotal + tax + delivery;
    
    document.getElementById('orderSubtotal').textContent = '₱' + subtotal.toFixed(2);
    document.getElementById('orderTax').textContent = '₱' + tax.toFixed(2);
    document.getElementById('orderDelivery').textContent = '₱' + delivery.toFixed(2);
    document.getElementById('orderTotal').textContent = '₱' + total.toFixed(2);
}

async function submitPlaceOrder(e) {
    e.preventDefault();
    
    const customerId = document.getElementById('selectedCustomerId').value;
    const productId = document.getElementById('productSelect').value;
    const quantity = parseInt(document.getElementById('orderQuantity').value);
    const paymentMethod = document.getElementById('paymentMethod').value;
    const fulfillmentType = document.getElementById('fulfillmentType').value;
    const specialInstructions = document.getElementById('specialInstructions').value;
    
    console.log('Form Data:', { customerId, productId, quantity, paymentMethod, fulfillmentType });
    
    if (!customerId) {
        alert('{{ __("Please select a customer") }}');
        return;
    }
    
    if (!productId) {
        alert('{{ __("Please select a product") }}');
        return;
    }

    if (!quantity || quantity < 1) {
        alert('{{ __("Please enter a valid quantity (minimum 1)") }}');
        return;
    }

    // Get product to check stock
    const product = allProducts.find(p => p.id == productId);
    if (!product) {
        alert('{{ __("Product not found") }}');
        return;
    }

    if (product.stock_quantity === 0) {
        alert('{{ __("This product is out of stock") }}');
        return;
    }

    if (quantity > product.stock_quantity) {
        alert(`{{ __("Insufficient stock. Available: ") }}${product.stock_quantity}`);
        return;
    }
    
    try {
        const csrfToken = document.querySelector('meta[name="csrf-token"]')?.getAttribute('content');
        
        const payload = {
            customer_id: parseInt(customerId),
            items: [{
                product_id: parseInt(productId),
                quantity: quantity
            }],
            fulfillment_type: fulfillmentType,
            payment_method: paymentMethod,
            special_instructions: specialInstructions,
            placed_by_admin: true
        };

        console.log('Sending payload:', payload);
        
        const res = await fetch('/api/quick-order', {
            method: 'POST',
            headers: {
                'Content-Type': 'application/json',
                'X-CSRF-TOKEN': csrfToken,
                'Accept': 'application/json'
            },
            credentials: 'same-origin',
            body: JSON.stringify(payload)
        });
        
        console.log('Response Status:', res.status);
        
        const text = await res.text();
        console.log('Response Text:', text);
        
        let data;
        try {
            data = JSON.parse(text);
        } catch (e) {
            console.error('Response was not JSON:', text);
            alert('Server error: Invalid response');
            return;
        }
        
        if (res.ok) {
            alert(`{{ __('Order placed successfully! Order #:') }} ${data.order_number}`);
            closePlaceOrderModal();
            setTimeout(() => transactionApp.fetchTransactions(), 500);
        } else {
            const errorMsg = data.message || data.error || data.errors || '{{ __("Failed to place order") }}';
            console.error('API Error:', data);
            alert('Error: ' + (typeof errorMsg === 'object' ? JSON.stringify(errorMsg) : errorMsg));
        }
    } catch (error) {
        console.error('Request Error:', error);
        alert('{{ __("An error occurred:") }} ' + error.message);
    }
}
</script>

<style>
    [x-cloak] { display: none; }
</style>
