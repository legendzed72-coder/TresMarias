<x-app-layout>
    <div class="flex" x-data="deliveryManagement()" @init="init()" x-cloak>
    <x-admin-sidebar />
    
    <div class="flex-1 bg-cream-50">
        <div class="max-w-6xl mx-auto px-6 py-8">
            {{-- Header --}}
            <div class="flex justify-between items-center mb-8">
                <div>
                    <h1 class="text-3xl font-bold text-bark-900">{{ __('Deliveries & Tracking') }}</h1>
                    <p class="text-bark-600 mt-1">{{ __('Manage all delivery orders and track shipments') }}</p>
                </div>
            </div>

            {{-- KPI Cards --}}
            <div class="grid grid-cols-1 md:grid-cols-4 gap-4 mb-8">
                <div class="bg-white rounded-xl border border-cream-200 p-6 shadow-sm">
                    <div class="flex items-center justify-between">
                        <div>
                            <p class="text-sm text-bark-600">{{ __('Pending') }}</p>
                            <p class="text-2xl font-bold text-bark-900 mt-2" id="pendingCount">0</p>
                        </div>
                        <svg class="w-10 h-10 text-yellow-500" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"/>
                        </svg>
                    </div>
                </div>

                <div class="bg-white rounded-xl border border-cream-200 p-6 shadow-sm">
                    <div class="flex items-center justify-between">
                        <div>
                            <p class="text-sm text-bark-600">{{ __('In Transit') }}</p>
                            <p class="text-2xl font-bold text-bark-900 mt-2" id="transitCount">0</p>
                        </div>
                        <svg class="w-10 h-10 text-blue-500" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M13 10V3L4 14h7v7l9-11h-7z"/>
                        </svg>
                    </div>
                </div>

                <div class="bg-white rounded-xl border border-cream-200 p-6 shadow-sm">
                    <div class="flex items-center justify-between">
                        <div>
                            <p class="text-sm text-bark-600">{{ __('Delivered') }}</p>
                            <p class="text-2xl font-bold text-bark-900 mt-2" id="deliveredCount">0</p>
                        </div>
                        <svg class="w-10 h-10 text-green-500" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"/>
                        </svg>
                    </div>
                </div>

                <div class="bg-white rounded-xl border border-cream-200 p-6 shadow-sm">
                    <div class="flex items-center justify-between">
                        <div>
                            <p class="text-sm text-bark-600">{{ __('Failed') }}</p>
                            <p class="text-2xl font-bold text-bark-900 mt-2" id="failedCount">0</p>
                        </div>
                        <svg class="w-10 h-10 text-red-500" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M12 8v4m0 4v.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/>
                        </svg>
                    </div>
                </div>
            </div>

            {{-- Delivery Tracking Map --}}
            <div class="bg-white rounded-xl border border-cream-200 mb-8 shadow-sm overflow-hidden">
                <div class="px-6 py-5 border-b border-cream-200 flex items-center justify-between">
                    <div>
                        <h3 class="text-lg font-bold text-bark-900">{{ __('Delivery Tracking Map') }}</h3>
                        <p class="text-sm text-bark-600 mt-1">{{ __('Real-time delivery locations and status') }}</p>
                    </div>
                    <button type="button" @click="showDeliveryMap = !showDeliveryMap"
                        class="inline-flex items-center gap-2 px-4 py-2 bg-bark-300 hover:bg-bark-400 text-cream-50 font-semibold text-sm rounded-xl transition duration-200">
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M9 6l6 6m0 0l6-6m-6 6v12m0 0l-6-6m6 6l6-6"/>
                        </svg>
                        <span x-text="showDeliveryMap ? '{{ __('Hide Map') }}' : '{{ __('Show Map') }}'"></span>
                    </button>
                </div>
                <div x-show="showDeliveryMap" x-cloak class="p-6 bg-cream-50">
                    <div id="deliveryMap" class="w-full h-96 rounded-xl border border-cream-300" style="background: linear-gradient(135deg, #f7ecdc 0%, #f0e4d3 100%);"></div>
                </div>
            </div>

            {{-- Filters and Search --}}
            <div class="bg-white rounded-xl border border-cream-200 p-6 mb-8 shadow-sm">
                <div class="grid grid-cols-1 md:grid-cols-4 gap-4">
                    <input 
                        type="text" 
                        x-model="search"
                        placeholder="{{ __('Search by recipient, phone, address...') }}"
                        class="px-4 py-2 border border-cream-300 rounded-lg focus:ring-2 focus:ring-bark-300 focus:border-transparent"
                    >
                    <select x-model="statusFilter" class="px-4 py-2 border border-cream-300 rounded-lg focus:ring-2 focus:ring-bark-300 focus:border-transparent">
                        <option value="">{{ __('All Statuses') }}</option>
                        <option value="pending">{{ __('Pending') }}</option>
                        <option value="assigned">{{ __('Assigned') }}</option>
                        <option value="picked_up">{{ __('Picked Up') }}</option>
                        <option value="in_transit">{{ __('In Transit') }}</option>
                        <option value="delivered">{{ __('Delivered') }}</option>
                        <option value="failed">{{ __('Failed') }}</option>
                    </select>
                    <select id="driverFilter" class="px-4 py-2 border border-cream-300 rounded-lg focus:ring-2 focus:ring-bark-300 focus:border-transparent" onchange="deliveryApp.filterByDriver(this.value)">
                        <option value="">{{ __('All Drivers') }}</option>
                    </select>
                    <button 
                        onclick="deliveryApp.fetchDeliveries()"
                        class="px-4 py-2 bg-bark-300 text-cream-50 rounded-lg hover:bg-bark-400 transition font-semibold"
                    >
                        {{ __('Search') }}
                    </button>
                </div>
            </div>

            {{-- Deliveries Table --}}
            <div class="bg-white rounded-xl border border-cream-200 overflow-hidden shadow-sm">
                <div class="overflow-x-auto">
                    <table class="w-full">
                        <thead class="bg-cream-100 border-b border-cream-200">
                            <tr class="text-left text-sm font-semibold text-bark-900">
                                <th class="px-6 py-4">{{ __('ID') }}</th>
                                <th class="px-6 py-4">{{ __('Recipient') }}</th>
                                <th class="px-6 py-4">{{ __('Address') }}</th>
                                <th class="px-6 py-4">{{ __('Order') }}</th>
                                <th class="px-6 py-4">{{ __('Status') }}</th>
                                <th class="px-6 py-4">{{ __('Driver') }}</th>
                                <th class="px-6 py-4">{{ __('Elapsed Time') }}</th>
                                <th class="px-6 py-4">{{ __('Actions') }}</th>
                            </tr>
                        </thead>
                        <tbody class="divide-y divide-cream-200" id="deliveriesTableBody">
                            <tr>
                                <td colspan="7" class="px-6 py-8 text-center text-bark-600">
                                    {{ __('Loading deliveries...') }}
                                </td>
                            </tr>
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
</div>

{{-- Tracking Detail Modal --}}
<div id="trackingModal" class="fixed inset-0 bg-black/50 hidden items-center justify-center z-50">
    <div class="bg-white rounded-xl shadow-lg w-full max-w-2xl mx-4 max-h-96 overflow-y-auto">
        <div class="p-6 border-b border-cream-200">
            <h2 class="text-xl font-bold text-bark-900">{{ __('Delivery Tracking Details') }}</h2>
        </div>
        <div class="p-6" id="trackingContent">
            {{-- Content will be populated by Alpine.js --}}
        </div>
        <div class="p-6 border-t border-cream-200 flex justify-end gap-3">
            <button 
                onclick="document.getElementById('trackingModal').classList.add('hidden')"
                class="px-4 py-2 text-bark-600 hover:bg-cream-100 rounded-lg transition"
            >
                {{ __('Close') }}
            </button>
        </div>
    </div>
</div>

{{-- Update Status Modal --}}
<div id="updateStatusModal" class="fixed inset-0 bg-black/50 hidden items-center justify-center z-50">
    <div class="bg-white rounded-xl shadow-lg w-full max-w-md mx-4">
        <div class="p-6 border-b border-cream-200">
            <h2 class="text-xl font-bold text-bark-900">{{ __('Update Delivery Status & Assign Driver') }}</h2>
        </div>
        <div class="p-6 space-y-4">
            <div>
                <label class="block text-sm font-semibold text-bark-900 mb-2">{{ __('Assign Driver') }}</label>
                <select id="driverSelect" class="w-full px-4 py-2 border border-cream-300 rounded-lg focus:ring-2 focus:ring-bark-300 focus:border-transparent">
                    <option value="">{{ __('Select Driver...') }}</option>
                </select>
            </div>
            <div>
                <label class="block text-sm font-semibold text-bark-900 mb-2">{{ __('New Status') }}</label>
                <select id="newStatusSelect" class="w-full px-4 py-2 border border-cream-300 rounded-lg focus:ring-2 focus:ring-bark-300 focus:border-transparent">
                    <option value="pending">{{ __('Pending') }}</option>
                    <option value="assigned">{{ __('Assigned') }}</option>
                    <option value="picked_up">{{ __('Picked Up') }}</option>
                    <option value="in_transit">{{ __('In Transit') }}</option>
                    <option value="delivered">{{ __('Delivered') }}</option>
                    <option value="failed">{{ __('Failed') }}</option>
                </select>
            </div>
            <div>
                <label class="block text-sm font-semibold text-bark-900 mb-2">{{ __('Notes (Optional)') }}</label>
                <textarea id="deliveryNotes" rows="3" placeholder="{{ __('Add delivery notes...') }}" class="w-full px-4 py-2 border border-cream-300 rounded-lg focus:ring-2 focus:ring-bark-300 focus:border-transparent"></textarea>
            </div>
        </div>
        <div class="p-6 border-t border-cream-200 flex justify-end gap-3">
            <button 
                onclick="document.getElementById('updateStatusModal').classList.add('hidden')"
                class="px-4 py-2 text-bark-600 hover:bg-cream-100 rounded-lg transition"
            >
                {{ __('Cancel') }}
            </button>
            <button 
                onclick="deliveryApp.updateDeliveryStatus()"
                class="px-4 py-2 bg-bark-300 text-cream-50 rounded-lg hover:bg-bark-400 transition font-semibold"
            >
                {{ __('Update') }}
            </button>
        </div>
    </div>
</div>

<script>
let deliveryApp;

function deliveryManagement() {
    return {
        deliveries: [],
        staff: [],
        search: '',
        statusFilter: '',
        driverFilter: '',
        selectedDelivery: null,
        trackingModal: false,
        updateStatusModal: false,
        showDeliveryMap: false,
        deliveryMap: null,
        deliveryMarkers: [],
        timerInterval: null,
        currentTime: new Date(),

        async init() {
            deliveryApp = this;
            await this.loadStaff();
            await this.fetchDeliveries();
            this.calculateStats();
            // Start real-time timer
            this.startTimer();
            // Watch for map toggle
            this.$watch('showDeliveryMap', (isOpen) => {
                if (isOpen && !this.deliveryMap) {
                    this.$nextTick(() => this.initDeliveryMap());
                }
            });
        },

        startTimer() {
            // Update current time and elapsed times every second
            this.timerInterval = setInterval(() => {
                this.currentTime = new Date();
                this.renderTable();
            }, 1000);
        },

        stopTimer() {
            if (this.timerInterval) {
                clearInterval(this.timerInterval);
                this.timerInterval = null;
            }
        },

        formatElapsedTime(createdAt) {
            if (!createdAt) return 'N/A';
            
            const created = new Date(createdAt);
            const now = this.currentTime || new Date();
            const elapsed = Math.floor((now.getTime() - created.getTime()) / 1000);

            if (elapsed < 60) {
                return elapsed + 's';
            } else if (elapsed < 3600) {
                const minutes = Math.floor(elapsed / 60);
                return minutes + 'm';
            } else if (elapsed < 86400) {
                const hours = Math.floor(elapsed / 3600);
                const mins = Math.floor((elapsed % 3600) / 60);
                return hours + 'h ' + mins + 'm';
            } else {
                const days = Math.floor(elapsed / 86400);
                const hours = Math.floor((elapsed % 86400) / 3600);
                return days + 'd ' + hours + 'h';
            }
        },

        getElapsedColor(createdAt) {
            if (!createdAt) return 'text-bark-600';
            
            const created = new Date(createdAt);
            const now = this.currentTime || new Date();
            const elapsedHours = (now.getTime() - created.getTime()) / 3600000;

            if (elapsedHours < 1) return 'text-leaf-600'; // Green - recent
            if (elapsedHours < 4) return 'text-gold-600'; // Gold - medium time
            if (elapsedHours < 8) return 'text-orange-600'; // Orange - long time
            return 'text-red-600'; // Red - very long time
        },

        async loadStaff() {
            try {
                const res = await fetch('/api/admin/staff', {
                    headers: { 'Accept': 'application/json' },
                    credentials: 'same-origin',
                });
                if (!res.ok) throw new Error('Failed to load staff');
                const data = await res.json();
                this.staff = data || [];
                
                // Populate driver dropdown
                ['driverSelect', 'driverFilter'].forEach(id => {
                    const el = document.getElementById(id);
                    if (el) {
                        el.innerHTML = '<option value="">{{ __("Select or assign driver...") }}</option>';
                        this.staff.forEach(s => {
                            el.innerHTML += `<option value="${s.id}">${s.name} (${s.role})</option>`;
                        });
                    }
                });
            } catch (e) {
                console.error('Error loading staff:', e);
            }
        },

        async fetchDeliveries() {
            try {
                const params = new URLSearchParams();
                if (this.search) params.append('search', this.search);
                if (this.statusFilter) params.append('status', this.statusFilter);

                const res = await fetch(`/api/deliveries?${params}`, {
                    headers: { 'Accept': 'application/json' },
                    credentials: 'same-origin',
                });
                
                if (!res.ok) throw new Error('Failed to load deliveries');
                const data = await res.json();
                this.deliveries = data || [];
                this.renderTable();
                this.calculateStats();
            } catch (e) {
                console.error('Error loading deliveries:', e);
                // Load sample deliveries if API fails
                this.loadSampleDeliveries();
            }
        },

        loadSampleDeliveries() {
            const now = new Date();
            const createDelivery = (id, hoursAgo) => {
                const createdAt = new Date(now.getTime() - hoursAgo * 3600000);
                return createdAt.toISOString();
            };

            this.deliveries = [
                {
                    id: 1,
                    recipient_name: 'Maria Santos',
                    phone: '+63 912 345 6789',
                    address: '123 Main St, Makati',
                    city: 'Makati',
                    postal_code: '1200',
                    latitude: 14.5546,
                    longitude: 121.0175,
                    status: 'delivered',
                    driver_id: 1,
                    driver: { name: 'Juan Cruz', email: 'juan@example.com' },
                    created_at: createDelivery(1, 3),
                    delivered_at: new Date().toISOString(),
                },
                {
                    id: 2,
                    recipient_name: 'Juan Dela Cruz',
                    phone: '+63 923 456 7890',
                    address: '456 Oak Ave, BGC',
                    city: 'Taguig',
                    postal_code: '1634',
                    latitude: 14.5589,
                    longitude: 121.0389,
                    status: 'in_transit',
                    driver_id: 2,
                    driver: { name: 'Pedro Garcia', email: 'pedro@example.com' },
                    created_at: createDelivery(2, 0.5),
                },
                {
                    id: 3,
                    recipient_name: 'Anna Lopez',
                    phone: '+63 934 567 8901',
                    address: '789 Pine Rd, Pasig',
                    city: 'Pasig',
                    postal_code: '1500',
                    latitude: 14.5745,
                    longitude: 121.0912,
                    status: 'pending',
                    driver_id: null,
                    driver: null,
                    created_at: createDelivery(3, 0.1),
                },
            ];
            this.renderTable();
            this.calculateStats();
        },

        clearFiltersAndReload() {
            this.search = '';
            this.statusFilter = '';
            this.driverFilter = '';
            this.fetchDeliveries();
        },

        calculateStats() {
            const stats = {
                pending: 0,
                assigned: 0,
                picked_up: 0,
                in_transit: 0,
                delivered: 0,
                failed: 0,
            };

            this.deliveries.forEach(d => {
                if (stats.hasOwnProperty(d.status)) {
                    stats[d.status]++;
                }
            });

            document.getElementById('pendingCount').textContent = stats.pending + stats.assigned;
            document.getElementById('transitCount').textContent = stats.in_transit + stats.picked_up;
            document.getElementById('deliveredCount').textContent = stats.delivered;
            document.getElementById('failedCount').textContent = stats.failed;
        },

        renderTable() {
            const tbody = document.getElementById('deliveriesTableBody');
            let filtered = this.deliveries;
            
            // Apply driver filter
            if (this.driverFilter) {
                filtered = filtered.filter(d => d.driver_id == this.driverFilter);
            }
            
            if (filtered.length === 0) {
                tbody.innerHTML = `
                    <tr>
                        <td colspan="7">
                            <div class="px-6 py-16 text-center">
                                <div class="text-5xl mb-4">🚚</div>
                                <h3 class="text-xl font-bold text-bark-900 mb-2">{{ __('No Pending Deliveries') }}</h3>
                                <p class="text-bark-600 mb-6">{{ __('All deliveries are up to date or no deliveries match your filters.') }}</p>
                                <div class="flex items-center justify-center gap-4">
                                    <button onclick="document.querySelector('input[placeholder*=\"\"]').focus()" class="px-6 py-2.5 bg-bark-300 hover:bg-bark-400 text-cream-50 font-semibold rounded-lg transition">
                                        {{ __('Search Deliveries') }}
                                    </button>
                                    <button onclick="deliveryApp.clearFiltersAndReload()" class="px-6 py-2.5 border border-bark-300 hover:bg-cream-100 text-bark-600 font-semibold rounded-lg transition">
                                        {{ __('Clear Filters') }}
                                    </button>
                                </div>
                            </div>
                        </td>
                    </tr>
                `;
                return;
            }

            tbody.innerHTML = filtered.map(d => {
                const statusColors = {
                    pending: 'bg-gray-100 text-gray-800',
                    assigned: 'bg-blue-100 text-blue-800',
                    picked_up: 'bg-purple-100 text-purple-800',
                    in_transit: 'bg-cyan-100 text-cyan-800',
                    delivered: 'bg-green-100 text-green-800',
                    failed: 'bg-red-100 text-red-800',
                };

                return `
                    <tr class="hover:bg-cream-50 transition">
                        <td class="px-6 py-4 text-sm font-semibold text-bark-900">#${d.id}</td>
                        <td class="px-6 py-4">
                            <div class="text-sm font-semibold text-bark-900">${d.recipient_name}</div>
                            <div class="text-xs text-bark-600">${d.phone}</div>
                        </td>
                        <td class="px-6 py-4">
                            <div class="text-sm text-bark-900">${d.address}</div>
                            <div class="text-xs text-bark-600">${d.city}${d.postal_code ? ', ' + d.postal_code : ''}</div>
                        </td>
                        <td class="px-6 py-4 text-sm text-bark-900">#${d.order_id}</td>
                        <td class="px-6 py-4">
                            <span class="inline-block px-3 py-1 rounded-full text-xs font-semibold ${statusColors[d.status] || 'bg-gray-100 text-gray-800'}">
                                ${d.status.replace('_', ' ').toUpperCase()}
                            </span>
                        </td>
                        <td class="px-6 py-4">
                            <div class="text-sm">
                                ${d.driver ? `<div class="font-semibold text-bark-900">${d.driver.name}</div><div class="text-xs text-bark-600">${d.driver.email}</div>` : '<span class="text-bark-400">Unassigned</span>'}
                            </div>
                        </td>
                        <td class="px-6 py-4">
                            <div class="text-sm font-mono">
                                <div class="font-semibold ${this.getElapsedColor(d.created_at)}" id="elapsed-${d.id}">${this.formatElapsedTime(d.created_at)}</div>
                                <div class="text-xs text-bark-600">in progress</div>
                            </div>
                        </td>
                        <td class="px-6 py-4">
                            <button onclick="deliveryApp.viewTracking(${d.id})" class="text-blue-600 hover:text-blue-800 text-sm font-semibold mr-3">
                                {{ __('Track') }}
                            </button>
                            <button onclick="deliveryApp.openUpdateStatus(${d.id})" class="text-green-600 hover:text-green-800 text-sm font-semibold">
                                {{ __('Update') }}
                            </button>
                        </td>
                    </tr>
                `;
            }).join('');
        },

        viewTracking(deliveryId) {
            const delivery = this.deliveries.find(d => d.id === deliveryId);
            if (!delivery) return;

            const statusLine = [
                { status: 'pending', label: 'Pending', icon: '⏳' },
                { status: 'assigned', label: 'Assigned', icon: '👤' },
                { status: 'picked_up', label: 'Picked Up', icon: '📦' },
                { status: 'in_transit', label: 'In Transit', icon: '🚚' },
                { status: 'delivered', label: 'Delivered', icon: '✓' },
            ];

            const currentStatusIndex = statusLine.findIndex(s => s.status === delivery.status);

            let trackingHTML = `
                <div class="space-y-6">
                    <div class="bg-cream-50 p-4 rounded-lg">
                        <h3 class="font-semibold text-bark-900 mb-2">{{ __('Delivery Information') }}</h3>
                        <div class="grid grid-cols-2 gap-4 text-sm">
                            <div>
                                <p class="text-bark-600">{{ __('Recipient') }}</p>
                                <p class="font-semibold text-bark-900">${delivery.recipient_name}</p>
                            </div>
                            <div>
                                <p class="text-bark-600">{{ __('Phone') }}</p>
                                <p class="font-semibold text-bark-900">${delivery.phone}</p>
                            </div>
                            <div class="col-span-2">
                                <p class="text-bark-600">{{ __('Address') }}</p>
                                <p class="font-semibold text-bark-900">${delivery.address}</p>
                                <p class="text-bark-600">${delivery.city}${delivery.postal_code ? ', ' + delivery.postal_code : ''}</p>
                            </div>
                        </div>
                    </div>

                    <div>
                        <h3 class="font-semibold text-bark-900 mb-4">{{ __('Delivery Status Timeline') }}</h3>
                        <div class="relative">
                            <div class="flex justify-between mb-8">
            `;

            statusLine.forEach((item, index) => {
                const isCompleted = index <= currentStatusIndex;
                const isCurrent = index === currentStatusIndex;
                trackingHTML += `
                    <div class="flex flex-col items-center ${index < statusLine.length - 1 ? 'flex-1' : ''}">
                        <div class="w-12 h-12 rounded-full flex items-center justify-center text-xl mb-2 ${
                            isCompleted ? 'bg-green-100 text-green-700' : 'bg-gray-100 text-gray-600'
                        } ${isCurrent ? 'ring-2 ring-green-400' : ''}">
                            ${item.icon}
                        </div>
                        <p class="text-xs font-semibold text-center text-bark-900">${item.label}</p>
                    </div>
                    ${index < statusLine.length - 1 ? `
                        <div class="flex-1 h-1 bg-gray-200 mx-2 mt-6 ${isCompleted ? 'bg-green-400' : ''}"></div>
                    ` : ''}
                `;
            });

            trackingHTML += `
                            </div>
                        </div>
                    </div>
            `;

            if (delivery.latitude && delivery.longitude) {
                trackingHTML += `
                    <div class="bg-cream-50 p-4 rounded-lg">
                        <p class="text-sm text-bark-600">{{ __('Location') }}</p>
                        <p class="font-semibold text-bark-900">Lat: ${delivery.latitude}, Lon: ${delivery.longitude}</p>
                    </div>
                `;
            }

            if (delivery.delivered_at) {
                trackingHTML += `
                    <div class="bg-green-50 p-4 rounded-lg">
                        <p class="text-sm text-bark-600">{{ __('Delivered On') }}</p>
                        <p class="font-semibold text-green-900">${new Date(delivery.delivered_at).toLocaleString()}</p>
                    </div>
                `;
            }

            trackingHTML += `</div>`;

            document.getElementById('trackingContent').innerHTML = trackingHTML;
            document.getElementById('trackingModal').classList.remove('hidden');
            document.getElementById('trackingModal').style.display = 'flex';
        },

        openUpdateStatus(deliveryId) {
            this.selectedDelivery = deliveryId;
            document.getElementById('updateStatusModal').classList.remove('hidden');
            document.getElementById('updateStatusModal').style.display = 'flex';
        },

        async updateDeliveryStatus() {
            if (!this.selectedDelivery) return;

            const newStatus = document.getElementById('newStatusSelect').value;
            const notes = document.getElementById('deliveryNotes').value;
            const driverId = document.getElementById('driverSelect').value;

            try {
                const res = await fetch(`/api/deliveries/${this.selectedDelivery}`, {
                    method: 'PATCH',
                    headers: {
                        'Accept': 'application/json',
                        'Content-Type': 'application/json',
                        'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]')?.content || '',
                    },
                    credentials: 'same-origin',
                    body: JSON.stringify({
                        status: newStatus,
                        delivery_notes: notes,
                        driver_id: driverId || null,
                    }),
                });

                if (!res.ok) throw new Error('Failed to update delivery');
                
                this.updateStatusModal = false;
                document.getElementById('deliveryNotes').value = '';
                document.getElementById('driverSelect').value = '';
                document.getElementById('updateStatusModal').classList.add('hidden');
                await this.fetchDeliveries();
            } catch (e) {
                console.error('Error updating delivery:', e);
                alert('{{ __("Failed to update delivery status") }}');
            }
        },

        filterByDriver(driverId) {
            this.driverFilter = driverId;
            this.renderTable();
        },

        initDeliveryMap() {
            // Load Leaflet CSS and JS if not already loaded
            if (typeof L === 'undefined') {
                const link = document.createElement('link');
                link.rel = 'stylesheet';
                link.href = 'https://cdnjs.cloudflare.com/ajax/libs/leaflet/1.9.4/leaflet.min.css';
                document.head.appendChild(link);

                const script = document.createElement('script');
                script.src = 'https://cdnjs.cloudflare.com/ajax/libs/leaflet/1.9.4/leaflet.min.js';
                script.onload = () => {
                    this.createDeliveryMap();
                };
                document.head.appendChild(script);
            } else {
                this.createDeliveryMap();
            }
        },

        createDeliveryMap() {
            const mapElement = document.getElementById('deliveryMap');
            if (!mapElement) return;

            // Default center (Philippines center)
            const defaultLat = 12.8797;
            const defaultLng = 121.7740;

            this.deliveryMap = L.map('deliveryMap').setView([defaultLat, defaultLng], 12);

            L.tileLayer('https://{s}.tile.openstreetmap.org/{z}/{x}/{y}.png', {
                attribution: '© OpenStreetMap contributors',
                maxZoom: 19,
                className: 'rounded-2xl'
            }).addTo(this.deliveryMap);

            // Add delivery markers
            this.deliveryMarkers = [];
            this.deliveries.forEach(delivery => {
                if (delivery.latitude && delivery.longitude) {
                    const marker = L.marker([parseFloat(delivery.latitude), parseFloat(delivery.longitude)]).addTo(this.deliveryMap);
                    
                    let statusColor = '#6B5B4A'; // default bark-600
                    let statusLabel = delivery.status;
                    if (delivery.status === 'pending') {
                        statusColor = '#FFD700';
                        statusLabel = 'Pending';
                    } else if (delivery.status === 'assigned') {
                        statusColor = '#3B82F6';
                        statusLabel = 'Assigned';
                    } else if (delivery.status === 'picked_up') {
                        statusColor = '#A855F7';
                        statusLabel = 'Picked Up';
                    } else if (delivery.status === 'in_transit') {
                        statusColor = '#FF9900';
                        statusLabel = 'In Transit';
                    } else if (delivery.status === 'delivered') {
                        statusColor = '#4CAF50';
                        statusLabel = 'Delivered';
                    } else if (delivery.status === 'failed') {
                        statusColor = '#F44336';
                        statusLabel = 'Failed';
                    }

                    marker.bindPopup(`
                        <div class="text-sm font-semibold" style="width: 250px;">
                            <p class="font-bold text-bark-900">${delivery.recipient_name}</p>
                            <p class="text-xs text-gray-600 mt-1">${delivery.address}</p>
                            <p class="text-xs text-gray-600">${delivery.city}${delivery.postal_code ? ', ' + delivery.postal_code : ''}</p>
                            <p class="text-xs mt-2">
                                <span class="font-semibold">Phone:</span> ${delivery.phone}
                            </p>
                            <p class="text-xs">
                                <span class="font-semibold">Status:</span> 
                                <span style="color: ${statusColor}; font-weight: bold;">${statusLabel}</span>
                            </p>
                            ${delivery.driver_id ? `<p class="text-xs"><span class="font-semibold">Driver:</span> ${delivery.driver?.name || 'N/A'}</p>` : ''}
                        </div>
                    `);

                    this.deliveryMarkers.push(marker);
                }
            });

            // Auto-fit bounds if deliveries exist
            if (this.deliveryMarkers.length > 0) {
                const group = new L.featureGroup(this.deliveryMarkers);
                this.deliveryMap.fitBounds(group.getBounds(), { padding: [50, 50] });
            }
        },
    };
}
</script>

<style>
    [x-cloak] { display: none; }
</style>
</x-app-layout>
