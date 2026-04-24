{{-- Admin Left Sidebar Navigation --}}
<aside class="w-64 bg-cream-50 border-r border-bark-200/10 shadow-sm shadow-bark-200/5 min-h-screen sticky top-0">
    <div class="p-6 space-y-2">
        {{-- Dashboard --}}
        <a href="{{ route('admin.dashboard') }}"
           class="flex items-center gap-3 px-4 py-3 rounded-xl transition duration-200 {{ request()->routeIs('admin.dashboard') ? 'bg-bark-300 text-cream-50' : 'text-bark-500 hover:bg-cream-200/50' }}">
            <svg class="w-5 h-5 flex-shrink-0" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" d="M3 12l2.423-2.423A6.981 6.981 0 0119.823 3H20a1 1 0 011 1v14a1 1 0 01-1 1h-1a6.981 6.981 0 01-5.4-2.577M9 19l3 3m0 0l3-3m-3 3V8m7-11h.01M5 20h14a2 2 0 002-2V4a2 2 0 00-2-2H5a2 2 0 00-2 2v14a2 2 0 002 2z"/>
            </svg>
            <span class="font-semibold text-sm">{{ __('Dashboard') }}</span>
        </a>

        {{-- Products --}}
        <a href="{{ route('admin.products') }}"
           class="flex items-center gap-3 px-4 py-3 rounded-xl transition duration-200 {{ request()->routeIs('admin.products') ? 'bg-bark-300 text-cream-50' : 'text-bark-500 hover:bg-cream-200/50' }}">
            <svg class="w-5 h-5 flex-shrink-0" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" d="M20 7l-8-4-8 4m16 0l-8 4m8-4v10l-8 4m0-10L4 7m8 4v10M4 7v10l8 4"/>
            </svg>
            <span class="font-semibold text-sm">{{ __('Products') }}</span>
        </a>

        {{-- Reports --}}
        <a href="{{ route('admin.reports') }}"
           class="flex items-center gap-3 px-4 py-3 rounded-xl transition duration-200 {{ request()->routeIs('admin.reports') ? 'bg-bark-300 text-cream-50' : 'text-bark-500 hover:bg-cream-200/50' }}">
            <svg class="w-5 h-5 flex-shrink-0" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" d="M3 3v18h18M9 17V9m4 8v-5m4 5V5"/>
            </svg>
            <span class="font-semibold text-sm">{{ __('Reports') }}</span>
        </a>

        {{-- Customers --}}
        <a href="{{ route('admin.customers.index') }}"
           class="flex items-center gap-3 px-4 py-3 rounded-xl transition duration-200 {{ request()->routeIs('admin.customers.*') ? 'bg-bark-300 text-cream-50' : 'text-bark-500 hover:bg-cream-200/50' }}">
            <svg class="w-5 h-5 flex-shrink-0" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" d="M15 19.128a9.38 9.38 0 002.625.372 9.337 9.337 0 004.121-.952 4.125 4.125 0 00-7.533-2.493M15 19.128v-.003c0-1.113-.285-2.16-.786-3.07M15 19.128v.106A12.318 12.318 0 018.624 21c-2.331 0-4.512-.645-6.374-1.766l-.001-.109a6.375 6.375 0 0111.964-3.07M12 6.375a3.375 3.375 0 11-6.75 0 3.375 3.375 0 016.75 0zm8.25 2.25a2.625 2.625 0 11-5.25 0 2.625 2.625 0 015.25 0z"/>
            </svg>
            <span class="font-semibold text-sm">{{ __('Customers') }}</span>
        </a>

        {{-- Notifications --}}
        <a href="{{ route('admin.notifications.index') }}"
           class="flex items-center gap-3 px-4 py-3 rounded-xl transition duration-200 {{ request()->routeIs('admin.notifications.*') ? 'bg-bark-300 text-cream-50' : 'text-bark-500 hover:bg-cream-200/50' }}">
            <svg class="w-5 h-5 flex-shrink-0" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" d="M15 17h5l-1.405-1.405A2.032 2.032 0 0118 14.158V11a6.002 6.002 0 00-4-5.659V5a2 2 0 10-4 0v.341C7.67 6.165 6 8.388 6 11v3.159c0 .538-.214 1.055-.595 1.436L4 17h5m6 0v1a3 3 0 11-6 0v-1m6 0H9"/>
            </svg>
            <span class="font-semibold text-sm">{{ __('Notifications') }}</span>
        </a>

        {{-- Deliveries & Tracking --}}
        <a href="{{ route('admin.deliveries') }}"
           class="flex items-center gap-3 px-4 py-3 rounded-xl transition duration-200 {{ request()->routeIs('admin.deliveries') ? 'bg-bark-300 text-cream-50' : 'text-bark-500 hover:bg-cream-200/50' }}">
            <svg class="w-5 h-5 flex-shrink-0" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" d="M8.25 18.75a1.5 1.5 0 01-3 0m3 0a1.5 1.5 0 00-3 0m3 0h6m-9 0H3.375a1.125 1.125 0 01-1.125-1.125V14.25m17.25 4.5a1.5 1.5 0 01-3 0m3 0a1.5 1.5 0 00-3 0m3 0H6.375c-.621 0-1.125-.504-1.125-1.125V14.25m17.25 0V6.169a2.25 2.25 0 00-.659-1.591l-3.42-3.42A2.25 2.25 0 0016.58.5H6.75A2.25 2.25 0 004.5 2.75v11.5"/>
            </svg>
            <span class="font-semibold text-sm">{{ __('Deliveries') }}</span>
        </a>

        {{-- Transactions & Billing --}}
        <a href="{{ route('admin.transactions') }}"
           class="flex items-center gap-3 px-4 py-3 rounded-xl transition duration-200 {{ request()->routeIs('admin.transactions') ? 'bg-bark-300 text-cream-50' : 'text-bark-500 hover:bg-cream-200/50' }}">
            <svg class="w-5 h-5 flex-shrink-0" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" d="M3 10h18M7 15h10M7 20h10M5 5h14a2 2 0 012 2v10a2 2 0 01-2 2H5a2 2 0 01-2-2V7a2 2 0 012-2z"/>
            </svg>
            <span class="font-semibold text-sm">{{ __('Transactions') }}</span>
        </a>

        {{-- Staff Management --}}
        <a href="{{ route('admin.staff') }}"
           class="flex items-center gap-3 px-4 py-3 rounded-xl transition duration-200 {{ request()->routeIs('admin.staff') ? 'bg-bark-300 text-cream-50' : 'text-bark-500 hover:bg-cream-200/50' }}">
            <svg class="w-5 h-5 flex-shrink-0" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" d="M18 9v3m0 0v3m0-3h3m-3 0h-3m-2-5a4 4 0 11-8 0 4 4 0 018 0zM3 20a6 6 0 0112 0v1H3v-1z"/>
            </svg>
            <span class="font-semibold text-sm">{{ __('Staff') }}</span>
        </a>
    </div>
</aside>
