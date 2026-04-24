<!-- Notification Bell Component for Layout -->
<div x-data="notificationCenter()" @click.away="isOpen = false" class="relative">
    <!-- Notification Bell Button -->
    <button @click="isOpen = !isOpen; if(!isOpen) clearInterval(pollInterval)" 
        class="relative p-2 text-gray-600 hover:text-gray-900 transition rounded-lg hover:bg-gray-100"
        title="Notifications">
        <i class="fas fa-bell text-xl"></i>
        <span x-show="unreadCount > 0" 
            class="absolute top-0 right-0 inline-flex items-center justify-center px-2 py-1 text-xs font-bold leading-none text-white transform translate-x-1/2 -translate-y-1/2 bg-red-600 rounded-full"
            x-text="unreadCount"></span>
    </button>

    <!-- Dropdown Menu -->
    <div x-show="isOpen" x-cloak
        class="absolute right-0 mt-2 w-80 bg-white rounded-lg shadow-lg z-50 border border-gray-200">
        
        <!-- Header -->
        <div class="px-6 py-4 border-b border-gray-200 flex items-center justify-between">
            <h3 class="font-bold text-gray-900">Notifications</h3>
            <a href="{{ route('admin.notifications.index') }}" class="text-blue-600 hover:text-blue-700 text-sm font-medium">
                View All
            </a>
        </div>

        <!-- Notifications List -->
        <div class="max-h-96 overflow-y-auto">
            <template x-if="notifications.length > 0">
                <div>
                    <template x-for="notification in notifications" :key="notification.id">
                        <div class="px-6 py-4 border-b border-gray-100 hover:bg-gray-50 cursor-pointer transition"
                            @click="window.location.href = notification.url">
                            <div class="flex items-start gap-3">
                                <i :class="notification.icon" :class="getIconColor(notification.type)" class="mt-1"></i>
                                <div class="flex-1">
                                    <p class="font-bold text-sm text-gray-900">{{ notification.title }}</p>
                                    <p class="text-xs text-gray-600 mt-1" x-text="notification.message.substring(0, 100) + (notification.message.length > 100 ? '...' : '')"></p>
                                    <p class="text-xs text-gray-400 mt-2" x-text="notification.created_at"></p>
                                </div>
                                <span x-show="!notification.is_read" class="w-2 h-2 bg-blue-600 rounded-full"></span>
                            </div>
                        </div>
                    </template>
                </div>
            </template>

            <template x-if="notifications.length === 0">
                <div class="px-6 py-8 text-center text-gray-500">
                    <i class="fas fa-inbox text-2xl mb-2 block"></i>
                    No notifications
                </div>
            </template>
        </div>

        <!-- Footer -->
        <div class="px-6 py-3 border-t border-gray-200 text-center">
            <a href="{{ route('admin.notifications.create') }}" class="text-blue-600 hover:text-blue-700 text-sm font-medium">
                <i class="fas fa-plus mr-1"></i>Send Notification
            </a>
        </div>
    </div>
</div>

<script>
function notificationCenter() {
    return {
        isOpen: false,
        notifications: [],
        unreadCount: 0,
        pollInterval: null,

        init() {
            this.loadNotifications();
            // Poll for new notifications every 10 seconds
            this.pollInterval = setInterval(() => {
                if (this.isOpen) {
                    this.loadNotifications();
                }
            }, 10000);
        },

        async loadNotifications() {
            try {
                const response = await fetch('{{ route("admin.notifications.recent", 5) }}');
                const data = await response.json();
                this.notifications = data;
                
                // Get unread count
                const countResponse = await fetch('{{ route("admin.notifications.unread-count") }}');
                const countData = await countResponse.json();
                this.unreadCount = countData.unread_count;
            } catch (error) {
                console.error('Failed to load notifications:', error);
            }
        },

        getIconColor(type) {
            return {
                'success': 'text-green-600',
                'warning': 'text-yellow-600',
                'danger': 'text-red-600',
                'info': 'text-blue-600'
            }[type] || 'text-gray-600';
        }
    };
}
</script>
