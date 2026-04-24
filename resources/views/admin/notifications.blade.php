<x-app-layout>
    <div class="flex" x-data="notificationManager()" @init="init()" x-cloak>
        <x-admin-sidebar />
        
        <div class="flex-1 bg-cream-50">
            <div class="max-w-6xl mx-auto px-6 py-8">
                {{-- Header --}}
                <div class="flex justify-between items-center mb-8">
                    <div>
                        <h1 class="text-3xl font-bold text-bark-900">{{ __('Notifications') }}</h1>
                        <p class="text-bark-600 mt-1">{{ __('View all notifications and updates from your bakery operations') }}</p>
                    </div>
                    <div class="flex items-center gap-3">
                        <button type="button" @click="markAllAsRead()" x-show="unreadCount > 0" x-cloak
                            class="px-4 py-2 bg-bark-300 hover:bg-bark-400 text-cream-50 font-semibold rounded-lg transition">
                            {{ __('Mark All as Read') }}
                        </button>
                        <button type="button" @click="clearAll()" x-show="notifications.length > 0" x-cloak
                            class="px-4 py-2 border border-red-400 hover:bg-red-50 text-red-600 font-semibold rounded-lg transition">
                            {{ __('Clear All') }}
                        </button>
                    </div>
                </div>

                {{-- Stats Cards --}}
                <div class="grid grid-cols-1 md:grid-cols-4 gap-4 mb-8">
                    <div class="bg-white rounded-xl border border-cream-200 p-6 shadow-sm">
                        <div class="flex items-center justify-between">
                            <div>
                                <p class="text-sm text-bark-600">{{ __('Total') }}</p>
                                <p class="text-2xl font-bold text-bark-900 mt-2" x-text="notifications.length">0</p>
                            </div>
                            <svg class="w-10 h-10 text-bark-300" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" d="M15 17h5l-1.405-1.405A2.032 2.032 0 0118 15.5V11a6.002 6.002 0 00-4-5.659V5a2 2 0 10-4 0v.341C7.67 6.165 6 8.388 6 11v4.5a2.032 2.032 0 01-.595 1.41L4 17h5m6 0v1a3 3 0 11-6 0v-1m6 0H9"/>
                            </svg>
                        </div>
                    </div>

                    <div class="bg-white rounded-xl border border-cream-200 p-6 shadow-sm">
                        <div class="flex items-center justify-between">
                            <div>
                                <p class="text-sm text-bark-600">{{ __('Unread') }}</p>
                                <p class="text-2xl font-bold text-blue-600 mt-2" x-text="unreadCount">0</p>
                            </div>
                            <svg class="w-10 h-10 text-blue-400" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" d="M3 8l7.89 5.26a2 2 0 002.22 0L21 8M5 19h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z"/>
                            </svg>
                        </div>
                    </div>

                    <div class="bg-white rounded-xl border border-cream-200 p-6 shadow-sm">
                        <div class="flex items-center justify-between">
                            <div>
                                <p class="text-sm text-bark-600">{{ __('Orders') }}</p>
                                <p class="text-2xl font-bold text-gold-600 mt-2" x-text="notifications.filter(n => n.type === 'order').length">0</p>
                            </div>
                            <svg class="w-10 h-10 text-gold-400" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" d="M16 11V7a4 4 0 00-8 0v4M5 9h14l1 12H4L5 9z"/>
                            </svg>
                        </div>
                    </div>

                    <div class="bg-white rounded-xl border border-cream-200 p-6 shadow-sm">
                        <div class="flex items-center justify-between">
                            <div>
                                <p class="text-sm text-bark-600">{{ __('Alerts') }}</p>
                                <p class="text-2xl font-bold text-red-600 mt-2" x-text="notifications.filter(n => n.type === 'alert').length">0</p>
                            </div>
                            <svg class="w-10 h-10 text-red-400" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" d="M12 9v2m0 4v2m0 4v2M7.08 6.47a9 9 0 1114.84 0M9 12a3 3 0 106 0 3 3 0 00-6 0z"/>
                            </svg>
                        </div>
                    </div>
                </div>

                {{-- Filters --}}
                <div class="bg-white rounded-xl border border-cream-200 p-6 mb-8 shadow-sm">
                    <div class="grid grid-cols-1 md:grid-cols-3 gap-4">
                        <select x-model="filterType" @change="renderNotifications()"
                            class="px-4 py-2 border border-cream-300 rounded-lg focus:ring-2 focus:ring-bark-300 focus:border-transparent">
                            <option value="">{{ __('All Types') }}</option>
                            <option value="order">{{ __('Order Updates') }}</option>
                            <option value="delivery">{{ __('Delivery Updates') }}</option>
                            <option value="alert">{{ __('Alerts') }}</option>
                            <option value="system">{{ __('System Messages') }}</option>
                        </select>
                        <select x-model="filterStatus" @change="renderNotifications()"
                            class="px-4 py-2 border border-cream-300 rounded-lg focus:ring-2 focus:ring-bark-300 focus:border-transparent">
                            <option value="">{{ __('All Status') }}</option>
                            <option value="unread">{{ __('Unread') }}</option>
                            <option value="read">{{ __('Read') }}</option>
                        </select>
                        <input type="text" x-model="searchQuery" @input="renderNotifications()" 
                            placeholder="{{ __('Search notifications...') }}"
                            class="px-4 py-2 border border-cream-300 rounded-lg focus:ring-2 focus:ring-bark-300 focus:border-transparent">
                    </div>
                </div>

                {{-- Notifications List --}}
                <div class="space-y-3" id="notificationsContainer">
                    {{-- Notifications will be rendered here --}}
                </div>
            </div>
        </div>
    </div>
</x-app-layout>

<script>
function notificationManager() {
    return {
        notifications: [],
        filterType: '',
        filterStatus: '',
        searchQuery: '',
        unreadCount: 0,
        timerInterval: null,
        currentTime: new Date(),

        async init() {
            await this.fetchNotifications();
            this.renderNotifications();
            this.calculateStats();
            // Update time every second
            this.timerInterval = setInterval(() => {
                this.currentTime = new Date();
                this.renderNotifications();
            }, 1000);
        },

        async fetchNotifications() {
            try {
                const res = await fetch('/api/admin/notifications', {
                    headers: { 'Accept': 'application/json' },
                    credentials: 'same-origin',
                });
                if (!res.ok) throw new Error('Failed to load notifications');
                const data = await res.json();
                this.notifications = data.data || data || [];
            } catch (e) {
                console.error('Error loading notifications:', e);
                this.loadSampleNotifications();
            }
        },

        loadSampleNotifications() {
            const now = new Date();
            const createTime = (minutesAgo) => {
                return new Date(now.getTime() - minutesAgo * 60000).toISOString();
            };

            this.notifications = [
                {
                    id: 1,
                    type: 'order',
                    title: 'New Order Received',
                    message: 'Order #ORD-12345 from Maria Santos - 5x Pandesal, 2x Ube Cake',
                    read: false,
                    created_at: createTime(2),
                    icon: '📦',
                    color: 'bg-gold-100 text-gold-800 border-gold-300',
                },
                {
                    id: 2,
                    type: 'delivery',
                    title: 'Delivery Status Update',
                    message: 'Order #ORD-12344 is now out for delivery with Juan Cruz',
                    read: false,
                    created_at: createTime(15),
                    icon: '🚚',
                    color: 'bg-blue-100 text-blue-800 border-blue-300',
                },
                {
                    id: 3,
                    type: 'alert',
                    title: 'Low Stock Warning',
                    message: 'Pandesal stock is running low (only 5 units left). Consider restocking soon.',
                    read: false,
                    created_at: createTime(45),
                    icon: '⚠️',
                    color: 'bg-orange-100 text-orange-800 border-orange-300',
                },
                {
                    id: 4,
                    type: 'order',
                    title: 'Order Delivered',
                    message: 'Order #ORD-12343 has been successfully delivered to Juan Dela Cruz',
                    read: true,
                    created_at: createTime(120),
                    icon: '✓',
                    color: 'bg-green-100 text-green-800 border-green-300',
                },
                {
                    id: 5,
                    type: 'system',
                    title: 'System Update',
                    message: 'Daily sales report is ready. Check the reports section for details.',
                    read: true,
                    created_at: createTime(180),
                    icon: '📊',
                    color: 'bg-purple-100 text-purple-800 border-purple-300',
                },
            ];
        },

        calculateStats() {
            this.unreadCount = this.notifications.filter(n => !n.read).length;
        },

        formatTime(timestamp) {
            if (!timestamp) return '';
            
            const created = new Date(timestamp);
            const now = this.currentTime || new Date();
            const elapsed = Math.floor((now.getTime() - created.getTime()) / 1000);

            if (elapsed < 60) {
                return elapsed + 's ago';
            } else if (elapsed < 3600) {
                const minutes = Math.floor(elapsed / 60);
                return minutes + 'm ago';
            } else if (elapsed < 86400) {
                const hours = Math.floor(elapsed / 3600);
                return hours + 'h ago';
            } else {
                const days = Math.floor(elapsed / 86400);
                return days + 'd ago';
            }
        },

        getFilteredNotifications() {
            let filtered = this.notifications;

            if (this.filterType) {
                filtered = filtered.filter(n => n.type === this.filterType);
            }

            if (this.filterStatus === 'unread') {
                filtered = filtered.filter(n => !n.read);
            } else if (this.filterStatus === 'read') {
                filtered = filtered.filter(n => n.read);
            }

            if (this.searchQuery) {
                filtered = filtered.filter(n => 
                    n.title.toLowerCase().includes(this.searchQuery.toLowerCase()) ||
                    n.message.toLowerCase().includes(this.searchQuery.toLowerCase())
                );
            }

            return filtered.sort((a, b) => new Date(b.created_at) - new Date(a.created_at));
        },

        renderNotifications() {
            const container = document.getElementById('notificationsContainer');
            const filtered = this.getFilteredNotifications();

            if (filtered.length === 0) {
                container.innerHTML = `
                    <div class="bg-white rounded-xl border border-cream-200 p-12 text-center shadow-sm">
                        <div class="text-5xl mb-4">🔔</div>
                        <h3 class="text-xl font-bold text-bark-900 mb-2">{{ __('No Notifications Yet') }}</h3>
                        <p class="text-bark-600 mb-6">{{ __('All caught up! You\'ll see notifications here as new orders and updates come in.') }}</p>
                        <button onclick="location.reload()" class="px-6 py-2.5 bg-bark-300 hover:bg-bark-400 text-cream-50 font-semibold rounded-lg transition">
                            {{ __('Refresh') }}
                        </button>
                    </div>
                `;
                return;
            }

            container.innerHTML = filtered.map(n => `
                <div class="bg-white rounded-xl border border-cream-200 shadow-sm hover:shadow-md transition ${!n.read ? 'ring-2 ring-bark-300' : ''}">
                    <div class="p-6">
                        <div class="flex items-start gap-4">
                            <div class="text-3xl flex-shrink-0">${n.icon}</div>
                            <div class="flex-1 min-w-0">
                                <div class="flex items-center gap-3 mb-1">
                                    <h3 class="font-bold text-lg text-bark-900">${n.title}</h3>
                                    ${!n.read ? '<span class="inline-block w-2 h-2 rounded-full bg-blue-500"></span>' : ''}
                                </div>
                                <p class="text-bark-600 mb-3">${n.message}</p>
                                <div class="flex items-center justify-between">
                                    <span class="text-xs text-bark-500">${this.formatTime(n.created_at)}</span>
                                    <div class="flex items-center gap-2">
                                        ${!n.read ? `<button onclick="notificationApp.markAsRead(${n.id})" class="text-sm text-bark-600 hover:text-bark-900 font-semibold">Mark as Read</button>` : ''}
                                        <button onclick="notificationApp.deleteNotification(${n.id})" class="text-sm text-red-600 hover:text-red-800 font-semibold">Delete</button>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            `).join('');
        },

        markAsRead(notificationId) {
            const notification = this.notifications.find(n => n.id === notificationId);
            if (notification) {
                notification.read = true;
                this.calculateStats();
                this.renderNotifications();
            }
        },

        markAllAsRead() {
            this.notifications.forEach(n => n.read = true);
            this.calculateStats();
            this.renderNotifications();
        },

        deleteNotification(notificationId) {
            this.notifications = this.notifications.filter(n => n.id !== notificationId);
            this.calculateStats();
            this.renderNotifications();
        },

        clearAll() {
            if (confirm('{{ __("Are you sure you want to clear all notifications?") }}')) {
                this.notifications = [];
                this.calculateStats();
                this.renderNotifications();
            }
        }
    };
}

let notificationApp;
notificationApp = notificationManager();
</script>

<style>
    [x-cloak] { display: none; }
</style>
