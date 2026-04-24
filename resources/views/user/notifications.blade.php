<x-app-layout>
    <x-slot name="header">
        <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-3">
            <div>
                <h2 class="font-serif font-bold text-xl text-bark-600 leading-tight">{{ __('Notifications') }}</h2>
                <p class="mt-1 text-sm text-muted">View your latest updates and order alerts.</p>
            </div>
            <div class="flex items-center gap-3">
                <span class="inline-flex items-center gap-2 rounded-full bg-cream-100 px-3 py-2 text-sm font-semibold text-bark-600 ring-1 ring-bark-200/30">
                    {{ $unreadCount }} unread
                </span>
            </div>
        </div>
    </x-slot>

    <div class="py-10">
        <div class="max-w-6xl mx-auto px-4 sm:px-6 lg:px-8 space-y-6">
            @if(session('success'))
                <div class="rounded-2xl bg-leaf-50 border border-leaf-200 p-4 text-leaf-700">
                    {{ session('success') }}
                </div>
            @endif

            @forelse($notifications as $notification)
                <article class="rounded-3xl border border-bark-200/10 bg-white shadow-sm shadow-bark-200/10 p-6 transition hover:shadow-md {{ $notification->read_at ? '' : 'ring-2 ring-leaf-300/40 bg-cream-50' }}">
                    <div class="flex flex-col sm:flex-row sm:items-start sm:justify-between gap-4">
                        <div class="min-w-0">
                            <div class="flex items-center gap-2 text-sm text-muted">
                                <span>{{ $notification->created_at->diffForHumans() }}</span>
                                @if(!$notification->read_at)
                                    <span class="inline-flex items-center rounded-full bg-leaf-100 text-leaf-700 px-2 py-0.5 text-[11px] font-semibold">Unread</span>
                                @else
                                    <span class="inline-flex items-center rounded-full bg-bark-100 text-bark-600 px-2 py-0.5 text-[11px] font-semibold">Read</span>
                                @endif
                            </div>
                            <h3 class="mt-3 text-lg font-semibold text-bark-700">{{ $notification->data['message'] ?? __('Notification') }}</h3>
                            @if(!empty($notification->data['order_number']))
                                <p class="mt-2 text-sm text-muted">Order #{{ $notification->data['order_number'] }}</p>
                            @endif
                            @if(!empty($notification->data['order_id']))
                                <a href="{{ route('orders.show', $notification->data['order_id']) }}" class="mt-3 inline-flex items-center text-sm font-semibold text-blue-600 hover:text-blue-700">
                                    View order details
                                </a>
                            @endif
                        </div>

                        <div class="flex shrink-0 items-center gap-2">
                            @if(!$notification->read_at)
                                <form method="POST" action="{{ route('notifications.mark-read', $notification->id) }}">
                                    @csrf
                                    @method('PATCH')
                                    <button type="submit" class="rounded-2xl bg-bark-900 px-4 py-2 text-sm font-semibold text-white hover:bg-bark-700 transition">
                                        Mark read
                                    </button>
                                </form>
                            @endif
                        </div>
                    </div>
                </article>
            @empty
                <div class="rounded-3xl border border-dashed border-bark-200/30 bg-cream-50 p-10 text-center">
                    <p class="text-lg font-semibold text-bark-700">No notifications yet</p>
                    <p class="mt-2 text-sm text-muted">When you place an order or your order status updates, notifications will appear here.</p>
                    <a href="{{ route('products.catalog') }}" class="mt-4 inline-flex items-center rounded-full bg-bark-900 px-5 py-2 text-sm font-semibold text-white hover:bg-bark-700 transition">
                        Browse products
                    </a>
                </div>
            @endforelse

            @if($notifications->hasPages())
                <div class="flex justify-center pt-2">
                    {{ $notifications->links() }}
                </div>
            @endif
        </div>
    </div>
</x-app-layout>
