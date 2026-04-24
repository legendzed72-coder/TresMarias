<x-app-layout>
    <x-slot name="header">
        <div class="flex items-center justify-between">
            <h2 class="font-serif font-bold text-2xl text-bark-600 leading-tight">Notifications</h2>
            <a href="{{ route('admin.notifications.create') }}" class="px-4 py-2 bg-blue-600 hover:bg-blue-700 text-white rounded-lg transition">
                <i class="fas fa-bell mr-2"></i>Send Notification
            </a>
        </div>
    </x-slot>

    <div class="flex">
        {{-- Sidebar --}}
        <x-admin-sidebar />

        {{-- Main Content --}}
        <div class="flex-1 py-8">
        <div class="max-w-4xl mx-auto px-4 sm:px-6 lg:px-8">
            @if (session('success'))
                <div class="mb-6 p-4 bg-green-50 border border-green-200 rounded-lg text-green-700">
                    <i class="fas fa-check-circle mr-2"></i>{{ session('success') }}
                </div>
            @endif

            <div class="space-y-4">
                @forelse($notifications as $notification)
                    <div class="bg-white rounded-lg shadow p-6 border-l-4 {{ $notification->is_read ? 'border-gray-300 opacity-75' : match($notification->type) {
                        'success' => 'border-green-500',
                        'warning' => 'border-yellow-500',
                        'danger' => 'border-red-500',
                        default => 'border-blue-500'
                    } }}">
                        <div class="flex items-start justify-between">
                            <div class="flex items-start flex-1">
                                <div class="flex-shrink-0 pt-1">
                                    <i class="{{ $notification->icon }} text-2xl {{ match($notification->type) {
                                        'success' => 'text-green-600',
                                        'warning' => 'text-yellow-600',
                                        'danger' => 'text-red-600',
                                        default => 'text-blue-600'
                                    } }}"></i>
                                </div>
                                <div class="ml-4 flex-1">
                                    <div class="flex items-center">
                                        <h3 class="text-lg font-bold text-gray-900">{{ $notification->title }}</h3>
                                        @if(!$notification->is_read)
                                            <span class="ml-2 px-2 py-1 bg-blue-100 text-blue-800 text-xs font-bold rounded">NEW</span>
                                        @endif
                                    </div>
                                    <p class="mt-2 text-gray-700">{{ $notification->message }}</p>
                                    <p class="mt-2 text-sm text-gray-500">
                                        <i class="fas fa-user-circle mr-1"></i>
                                        From: <strong>{{ $notification->admin->name }}</strong> 
                                        • {{ $notification->created_at->diffForHumans() }}
                                    </p>
                                </div>
                            </div>
                            <div class="flex-shrink-0 ml-4 flex gap-2">
                                @if(!$notification->is_read)
                                    <form action="{{ route('admin.notifications.mark-read', $notification->id) }}" method="POST" style="display: inline;">
                                        @csrf
                                        @method('PUT')
                                        <button type="submit" class="px-3 py-2 text-sm bg-blue-100 text-blue-600 hover:bg-blue-200 rounded transition">
                                            <i class="fas fa-check mr-1"></i>Mark Read
                                        </button>
                                    </form>
                                @endif
                                <form action="{{ route('admin.notifications.destroy', $notification->id) }}" method="POST" style="display: inline;" onsubmit="return confirm('Are you sure?');">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit" class="px-3 py-2 text-sm bg-red-100 text-red-600 hover:bg-red-200 rounded transition">
                                        <i class="fas fa-trash mr-1"></i>Delete
                                    </button>
                                </form>
                            </div>
                        </div>
                    </div>
                @empty
                    <div class="bg-white rounded-lg shadow p-12 text-center">
                        <i class="fas fa-inbox text-4xl text-gray-300 mb-4"></i>
                        <p class="text-gray-500 text-lg">No notifications yet</p>
                    </div>
                @endforelse
            </div>

            @if($notifications->hasPages())
                <div class="mt-8">
                    {{ $notifications->links() }}
                </div>
            @endif
        </div>
        </div>
        </div>
</x-app-layout>
