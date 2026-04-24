<nav x-data="{ open: false }" class="bg-cream-50 border-b border-bark-200/15 shadow-sm shadow-bark-200/5">
    <!-- Primary Navigation Menu -->
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        <div class="flex justify-between h-16">
            <div class="flex">
                <!-- Logo -->
                <div class="shrink-0 flex items-center">
                    <a href="{{ route('home') }}">
                        <x-application-logo class="block h-9 w-auto" />
                    </a>
                </div>


            </div>

            <!-- Settings Dropdown -->
            <div class="hidden sm:flex sm:items-center sm:ms-6">
                <!-- Notification Bell -->
                <div x-data="notificationBell()" x-init="fetchUnread()" class="relative me-3">
                    <button @click="open = !open" class="relative inline-flex items-center p-2 rounded-xl text-bark-400 hover:text-bark-600 hover:bg-cream-200/50 focus:outline-none transition duration-200">
                        <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 17h5l-1.405-1.405A2.032 2.032 0 0118 14.158V11a6.002 6.002 0 00-4-5.659V5a2 2 0 10-4 0v.341C7.67 6.165 6 8.388 6 11v3.159c0 .538-.214 1.055-.595 1.436L4 17h5m6 0v1a3 3 0 11-6 0v-1m6 0H9" />
                        </svg>
                        <span x-show="unreadCount > 0" x-text="unreadCount > 9 ? '9+' : unreadCount"
                              class="absolute -top-1 -right-1 inline-flex items-center justify-center w-5 h-5 text-xs font-bold text-white bg-red-500 rounded-full"
                              x-cloak></span>
                    </button>

                    <!-- Notification Dropdown -->
                    <div x-show="open" @click.away="open = false"
                         x-transition:enter="transition ease-out duration-200"
                         x-transition:enter-start="opacity-0 scale-95"
                         x-transition:enter-end="opacity-100 scale-100"
                         x-transition:leave="transition ease-in duration-100"
                         x-transition:leave-start="opacity-100 scale-100"
                         x-transition:leave-end="opacity-0 scale-95"
                         class="absolute right-0 mt-2 w-80 bg-white rounded-xl shadow-lg border border-bark-200/20 z-50 overflow-hidden"
                         x-cloak>
                        <div class="flex items-center justify-between px-4 py-3 border-b border-bark-200/15 bg-cream-50">
                            <h3 class="text-sm font-semibold text-bark-600">Notifications</h3>
                            <button x-show="unreadCount > 0" @click="markAllRead()" class="text-xs text-accent hover:underline">
                                Mark all read
                            </button>
                        </div>
                        <a href="{{ route('notifications.index') }}" class="block px-4 py-3 text-sm font-medium text-blue-600 hover:bg-cream-100/50">
                            View all notifications
                        </a>
                        <div class="max-h-72 overflow-y-auto">
                            <template x-if="notifications.length === 0">
                                <div class="px-4 py-6 text-center text-sm text-muted">
                                    No notifications yet
                                </div>
                            </template>
                            <template x-for="n in notifications" :key="n.id">
                                <a :href="n.data.order_id ? '/orders/' + n.data.order_id : '#'"
                                   @click="markRead(n)"
                                   class="block px-4 py-3 hover:bg-cream-100/50 border-b border-bark-200/10 transition"
                                   :class="{ 'bg-cream-100/30': !n.read_at }">
                                    <div class="flex items-start gap-3">
                                        <div class="shrink-0 mt-0.5">
                                            <span x-show="!n.read_at" class="inline-block w-2 h-2 bg-accent rounded-full"></span>
                                            <span x-show="n.read_at" class="inline-block w-2 h-2 bg-bark-200 rounded-full"></span>
                                        </div>
                                        <div class="flex-1 min-w-0">
                                            <p class="text-sm text-bark-600" x-text="n.data.message"></p>
                                            <p class="text-xs text-muted mt-1" x-text="timeAgo(n.created_at)"></p>
                                        </div>
                                    </div>
                                </a>
                            </template>
                        </div>
                    </div>
                </div>

                <x-dropdown align="right" width="48">
                    <x-slot name="trigger">
                        <button class="inline-flex items-center px-3 py-2 border border-transparent text-sm leading-4 font-semibold rounded-xl text-bark-500 bg-cream-50 hover:text-bark-600 hover:bg-cream-200/50 focus:outline-none transition ease-in-out duration-200">
                            <div>{{ Auth::user()->name }}</div>

                            <div class="ms-1">
                                <svg class="fill-current h-4 w-4" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20">
                                    <path fill-rule="evenodd" d="M5.293 7.293a1 1 0 011.414 0L10 10.586l3.293-3.293a1 1 0 111.414 1.414l-4 4a1 1 0 01-1.414 0l-4-4a1 1 0 010-1.414z" clip-rule="evenodd" />
                                </svg>
                            </div>
                        </button>
                    </x-slot>

                    <x-slot name="content">
                        @if(Auth::user()->role === 'admin')
                            <x-dropdown-link :href="route('admin.dashboard')">
                                {{ __('Dashboard') }}
                            </x-dropdown-link>
                            <x-dropdown-link :href="route('staff.deliveries')">
                                {{ __('Deliveries') }}
                            </x-dropdown-link>
                        @else
                            <x-dropdown-link :href="route('track-order')">
                                {{ __('🚚 Track Order') }}
                            </x-dropdown-link>
                            <x-dropdown-link :href="route('my-orders')">
                                {{ __('📦 My Orders') }}
                            </x-dropdown-link>
                        @endif

                        <x-dropdown-link :href="route('profile.edit')">
                            {{ __('Profile') }}
                        </x-dropdown-link>

                        <!-- Authentication -->
                        <form method="POST" action="{{ route('logout') }}">
                            @csrf

                            <x-dropdown-link :href="route('logout')"
                                    onclick="event.preventDefault();
                                                this.closest('form').submit();">
                                {{ __('Log Out') }}
                            </x-dropdown-link>
                        </form>
                    </x-slot>
                </x-dropdown>
            </div>

            <!-- Hamburger -->
            <div class="-me-2 flex items-center sm:hidden">
                <button @click="open = ! open" class="inline-flex items-center justify-center p-2 rounded-xl text-bark-300 hover:text-bark-500 hover:bg-cream-200/50 focus:outline-none focus:bg-cream-200/50 focus:text-bark-500 transition duration-200 ease-in-out">
                    <svg class="h-6 w-6" stroke="currentColor" fill="none" viewBox="0 0 24 24">
                        <path :class="{'hidden': open, 'inline-flex': ! open }" class="inline-flex" stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6h16M4 12h16M4 18h16" />
                        <path :class="{'hidden': ! open, 'inline-flex': open }" class="hidden" stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12" />
                    </svg>
                </button>
            </div>
        </div>
    </div>

    <!-- Responsive Navigation Menu -->
    <div :class="{'block': open, 'hidden': ! open}" class="hidden sm:hidden">

        <!-- Responsive Settings Options -->
        <div class="pt-4 pb-1 border-t border-bark-200/15">
            <div class="px-4">
                <div class="font-semibold text-base text-bark-600">{{ Auth::user()->name }}</div>
                <div class="font-medium text-sm text-muted">{{ Auth::user()->email }}</div>
            </div>

            <div class="mt-3 space-y-1">
                @if(Auth::user()->role === 'admin')
                    <x-responsive-nav-link :href="route('admin.dashboard')">
                        {{ __('Dashboard') }}
                    </x-responsive-nav-link>
                    <x-responsive-nav-link :href="route('staff.deliveries')">
                        {{ __('Deliveries') }}
                    </x-responsive-nav-link>
                @else
                    <x-responsive-nav-link :href="route('track-order')">
                        {{ __('🚚 Track Order') }}
                    </x-responsive-nav-link>
                    <x-responsive-nav-link :href="route('my-orders')">
                        {{ __('📦 My Orders') }}
                    </x-responsive-nav-link>
                @endif

                <x-responsive-nav-link :href="route('profile.edit')">
                    {{ __('Profile') }}
                </x-responsive-nav-link>

                <!-- Authentication -->
                <form method="POST" action="{{ route('logout') }}">
                    @csrf

                    <x-responsive-nav-link :href="route('logout')"
                            onclick="event.preventDefault();
                                        this.closest('form').submit();">
                        {{ __('Log Out') }}
                    </x-responsive-nav-link>
                </form>
            </div>
        </div>
    </div>
</nav>

<script>
function notificationBell() {
    return {
        open: false,
        notifications: [],
        unreadCount: 0,
        polling: null,

        getXsrfToken() {
            const match = document.cookie.match(/XSRF-TOKEN=([^;]+)/);
            return decodeURIComponent(match ? match[1] : '');
        },

        fetchUnread() {
            fetch('/api/notifications/unread', {
                headers: {
                    'Accept': 'application/json',
                    'X-XSRF-TOKEN': this.getXsrfToken(),
                },
                credentials: 'same-origin',
            })
            .then(r => r.json())
            .then(data => {
                this.unreadCount = data.count;
                this.notifications = data.notifications;
            })
            .catch(() => {});

            // Also fetch all recent notifications for the dropdown
            fetch('/api/notifications', {
                headers: {
                    'Accept': 'application/json',
                    'X-XSRF-TOKEN': this.getXsrfToken(),
                },
                credentials: 'same-origin',
            })
            .then(r => r.json())
            .then(data => { this.notifications = data; })
            .catch(() => {});

            // Poll every 30 seconds
            this.polling = setInterval(() => this.fetchUnread(), 30000);
        },

        markRead(notification) {
            if (notification.read_at) return;
            fetch('/api/notifications/' + notification.id + '/read', {
                method: 'PATCH',
                headers: {
                    'Accept': 'application/json',
                    'X-XSRF-TOKEN': this.getXsrfToken(),
                },
                credentials: 'same-origin',
            })
            .then(() => {
                notification.read_at = new Date().toISOString();
                this.unreadCount = Math.max(0, this.unreadCount - 1);
            })
            .catch(() => {});
        },

        markAllRead() {
            fetch('/api/notifications/read-all', {
                method: 'POST',
                headers: {
                    'Accept': 'application/json',
                    'X-XSRF-TOKEN': this.getXsrfToken(),
                },
                credentials: 'same-origin',
            })
            .then(() => {
                this.notifications.forEach(n => n.read_at = new Date().toISOString());
                this.unreadCount = 0;
            })
            .catch(() => {});
        },

        timeAgo(dateStr) {
            const seconds = Math.floor((new Date() - new Date(dateStr)) / 1000);
            if (seconds < 60) return 'Just now';
            const minutes = Math.floor(seconds / 60);
            if (minutes < 60) return minutes + 'm ago';
            const hours = Math.floor(minutes / 60);
            if (hours < 24) return hours + 'h ago';
            const days = Math.floor(hours / 24);
            return days + 'd ago';
        }
    };
}
</script>
