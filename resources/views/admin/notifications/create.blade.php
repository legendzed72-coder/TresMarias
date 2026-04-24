<x-app-layout>
    <x-slot name="header">
        <div class="flex items-center justify-between">
            <h2 class="font-serif font-bold text-2xl text-bark-600 leading-tight">Send Notification</h2>
            <a href="{{ route('admin.notifications.index') }}" class="px-4 py-2 bg-gray-200 hover:bg-gray-300 rounded-lg transition">
                <i class="fas fa-arrow-left mr-2"></i>Back
            </a>
        </div>
    </x-slot>

    <div class="py-8">
        <div class="max-w-2xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="bg-white rounded-lg shadow p-8">
                <form method="POST" action="{{ route('admin.notifications.store') }}" class="space-y-6">
                    @csrf

                    <!-- Title -->
                    <div>
                        <label class="block text-sm font-medium text-gray-700">Notification Title</label>
                        <input type="text" name="title" required 
                            class="mt-2 w-full px-4 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500 @error('title') border-red-500 @enderror"
                            value="{{ old('title') }}" placeholder="e.g., New Product Available">
                        @error('title')
                            <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                        @enderror
                    </div>

                    <!-- Message -->
                    <div>
                        <label class="block text-sm font-medium text-gray-700">Message</label>
                        <textarea name="message" required rows="5"
                            class="mt-2 w-full px-4 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500 @error('message') border-red-500 @enderror"
                            placeholder="Enter your notification message here...">{{ old('message') }}</textarea>
                        @error('message')
                            <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                        @enderror
                    </div>

                    <!-- Type -->
                    <div>
                        <label class="block text-sm font-medium text-gray-700">Notification Type</label>
                        <select name="type" required
                            class="mt-2 w-full px-4 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500 @error('type') border-red-500 @enderror">
                            <option value="">Select a type</option>
                            <option value="info" @selected(old('type') == 'info')>ℹ️ Informational</option>
                            <option value="success" @selected(old('type') == 'success')>✅ Success</option>
                            <option value="warning" @selected(old('type') == 'warning')>⚠️ Warning</option>
                            <option value="danger" @selected(old('type') == 'danger')>❌ Danger</option>
                        </select>
                        @error('type')
                            <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                        @enderror
                    </div>

                    <!-- Recipient Type -->
                    <div x-data="{ recipientType: 'all' }">
                        <label class="block text-sm font-medium text-gray-700">Send To</label>
                        <div class="mt-2 space-y-3">
                            <label class="flex items-center">
                                <input type="radio" name="recipient_type" value="all" x-model="recipientType" class="w-4 h-4">
                                <span class="ml-2 text-gray-700">All Users</span>
                            </label>
                            <label class="flex items-center">
                                <input type="radio" name="recipient_type" value="role" x-model="recipientType" class="w-4 h-4">
                                <span class="ml-2 text-gray-700">Specific Role</span>
                            </label>
                            <label class="flex items-center">
                                <input type="radio" name="recipient_type" value="specific" x-model="recipientType" class="w-4 h-4">
                                <span class="ml-2 text-gray-700">Specific Users</span>
                            </label>
                        </div>

                        <!-- Role Selection -->
                        <div x-show="recipientType === 'role'" class="mt-4">
                            <select name="role"
                                class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500">
                                <option value="">Select role</option>
                                <option value="customer">Customer</option>
                                <option value="staff">Staff</option>
                            </select>
                            @error('role')
                                <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                            @enderror
                        </div>

                        <!-- User Selection -->
                        <div x-show="recipientType === 'specific'" class="mt-4">
                            <label class="block text-sm font-medium text-gray-700 mb-2">Select Users</label>
                            <div class="border border-gray-300 rounded-lg p-4 max-h-64 overflow-y-auto bg-gray-50">
                                @forelse($users as $user)
                                    <label class="flex items-center mb-2">
                                        <input type="checkbox" name="user_ids[]" value="{{ $user->id }}" class="w-4 h-4">
                                        <span class="ml-2 text-gray-700">
                                            {{ $user->name }} <span class="text-sm text-gray-500">({{ ucfirst($user->role) }})</span>
                                        </span>
                                    </label>
                                @empty
                                    <p class="text-gray-500">No users available</p>
                                @endforelse
                            </div>
                            @error('user_ids')
                                <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                            @enderror
                        </div>
                    </div>

                    <!-- Action URL (Optional) -->
                    <div>
                        <label class="block text-sm font-medium text-gray-700">Action URL (Optional)</label>
                        <input type="text" name="action_url" 
                            class="mt-2 w-full px-4 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500"
                            value="{{ old('action_url') }}" placeholder="e.g., /shop or /my-orders">
                        <p class="mt-1 text-sm text-gray-500">Users can click the notification to go to this URL</p>
                        @error('action_url')
                            <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                        @enderror
                    </div>

                    <!-- Schedule (Optional) -->
                    <div>
                        <label class="block text-sm font-medium text-gray-700">Schedule (Optional)</label>
                        <input type="datetime-local" name="scheduled_at" 
                            class="mt-2 w-full px-4 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500"
                            value="{{ old('scheduled_at') }}">
                        <p class="mt-1 text-sm text-gray-500">Leave empty to send immediately</p>
                        @error('scheduled_at')
                            <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                        @enderror
                    </div>

                    <!-- Submit -->
                    <div class="flex gap-4">
                        <button type="submit" class="flex-1 px-6 py-3 bg-blue-600 hover:bg-blue-700 text-white font-bold rounded-lg transition">
                            <i class="fas fa-paper-plane mr-2"></i>Send Notification
                        </button>
                        <a href="{{ route('admin.notifications.index') }}" class="flex-1 px-6 py-3 bg-gray-200 hover:bg-gray-300 text-gray-800 font-bold rounded-lg transition text-center">
                            Cancel
                        </a>
                    </div>
                </form>
            </div>
        </div>
    </div>

    @push('scripts')
    <script defer src="https://cdn.jsdelivr.net/npm/alpinejs@3.x.x/dist/cdn.min.js"></script>
    @endpush
</x-app-layout>
