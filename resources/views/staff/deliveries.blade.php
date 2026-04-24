<x-app-layout>
    <x-slot name="header">
        <div class="flex items-center justify-between">
            <div>
                <h2 class="font-serif font-bold text-2xl text-bark-600 leading-tight">
                    {{ __('Deliveries') }}
                </h2>
                <p class="mt-1 text-sm text-muted">{{ __('Track and manage delivery orders') }}</p>
            </div>
        </div>
    </x-slot>

    <div x-data="deliveryManager()" x-init="init()" class="py-8">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 space-y-6">

            {{-- Success/Error Messages --}}
            <div x-show="successMsg" x-transition x-cloak
                class="p-4 rounded-2xl bg-leaf-300/20 border border-leaf-400/30 text-leaf-500 font-semibold text-sm flex items-center gap-3">
                <svg class="w-5 h-5 flex-shrink-0" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"/>
                </svg>
                <span x-text="successMsg"></span>
            </div>
            <div x-show="errorMsg" x-transition x-cloak
                class="p-4 rounded-2xl bg-red-100 border border-red-300/30 text-red-600 font-semibold text-sm flex items-center gap-3">
                <svg class="w-5 h-5 flex-shrink-0" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M12 9v3.75m9.303 3.376c-.866 1.5.217 3.374 1.948 3.374H2.697c1.73 0 2.813-1.874 1.948-3.374L13.949 3.378c-.866-1.5-3.032-1.5-3.898 0L2.697 16.126zM12 15.75h.007v.008H12v-.008z"/>
                </svg>
                <span x-text="errorMsg"></span>
            </div>

            {{-- Filters --}}
            <div class="flex flex-col sm:flex-row gap-4">
                <div class="relative flex-1 max-w-md">
                    <svg class="absolute left-4 top-1/2 -translate-y-1/2 w-5 h-5 text-bark-200" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"/>
                    </svg>
                    <input type="text" x-model="search" placeholder="Search by name, phone, address..."
                        class="w-full pl-12 pr-4 py-3 rounded-2xl border border-bark-200/20 bg-cream-50 text-bark-600 placeholder-bark-200 focus:outline-none focus:ring-2 focus:ring-bark-300/40 focus:border-bark-300 transition-all font-medium text-sm shadow-sm">
                </div>
                <select x-model="filterStatus"
                    class="px-4 py-3 rounded-2xl border border-bark-200/20 bg-cream-50 text-bark-600 focus:outline-none focus:ring-2 focus:ring-bark-300/40 focus:border-bark-300 transition-all font-medium text-sm shadow-sm">
                    <option value="">All Statuses</option>
                    <option value="pending">Pending</option>
                    <option value="assigned">Assigned</option>
                    <option value="picked_up">Picked Up</option>
                    <option value="in_transit">In Transit</option>
                    <option value="delivered">Delivered</option>
                    <option value="failed">Failed</option>
                </select>
            </div>

            {{-- Stats Row --}}
            <div class="grid grid-cols-2 sm:grid-cols-3 lg:grid-cols-6 gap-4">
                <div class="bg-cream-50 rounded-2xl ring-1 ring-bark-200/10 p-4 text-center">
                    <p class="text-xs font-semibold text-muted uppercase tracking-wider">Total</p>
                    <p class="text-2xl font-bold text-bark-600 mt-1" x-text="deliveries.length"></p>
                </div>
                <div class="bg-cream-50 rounded-2xl ring-1 ring-bark-200/10 p-4 text-center">
                    <p class="text-xs font-semibold text-muted uppercase tracking-wider">Pending</p>
                    <p class="text-2xl font-bold text-gold-500 mt-1" x-text="deliveries.filter(d => d.status === 'pending').length"></p>
                </div>
                <div class="bg-cream-50 rounded-2xl ring-1 ring-bark-200/10 p-4 text-center">
                    <p class="text-xs font-semibold text-muted uppercase tracking-wider">In Transit</p>
                    <p class="text-2xl font-bold text-blue-500 mt-1" x-text="deliveries.filter(d => d.status === 'in_transit').length"></p>
                </div>
                <div class="bg-cream-50 rounded-2xl ring-1 ring-bark-200/10 p-4 text-center">
                    <p class="text-xs font-semibold text-muted uppercase tracking-wider">Delivered</p>
                    <p class="text-2xl font-bold text-leaf-500 mt-1" x-text="deliveries.filter(d => d.status === 'delivered').length"></p>
                </div>
                <div class="bg-cream-50 rounded-2xl ring-1 ring-bark-200/10 p-4 text-center">
                    <p class="text-xs font-semibold text-muted uppercase tracking-wider">Failed</p>
                    <p class="text-2xl font-bold text-red-500 mt-1" x-text="deliveries.filter(d => d.status === 'failed').length"></p>
                </div>
                <div class="bg-cream-50 rounded-2xl ring-1 ring-bark-200/10 p-4 text-center">
                    <p class="text-xs font-semibold text-muted uppercase tracking-wider">Assigned</p>
                    <p class="text-2xl font-bold text-bark-400 mt-1" x-text="deliveries.filter(d => d.status === 'assigned' || d.status === 'picked_up').length"></p>
                </div>
            </div>

            {{-- Loading --}}
            <div x-show="loading" x-transition class="flex justify-center py-16">
                <div class="flex flex-col items-center gap-4">
                    <svg class="animate-spin h-10 w-10 text-bark-300" fill="none" viewBox="0 0 24 24">
                        <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle>
                        <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4z"></path>
                    </svg>
                    <span class="text-bark-200 font-medium text-sm">Loading deliveries...</span>
                </div>
            </div>

            {{-- Deliveries Table --}}
            <div x-show="!loading" x-cloak class="bg-cream-50 rounded-2xl ring-1 ring-bark-200/10 shadow-sm shadow-bark-200/5 overflow-hidden">
                <div class="overflow-x-auto">
                    <table class="w-full text-left">
                        <thead>
                            <tr class="border-b border-bark-200/10">
                                <th class="px-6 py-4 text-xs font-bold text-bark-400 uppercase tracking-wider">Order</th>
                                <th class="px-6 py-4 text-xs font-bold text-bark-400 uppercase tracking-wider">Recipient</th>
                                <th class="px-6 py-4 text-xs font-bold text-bark-400 uppercase tracking-wider hidden md:table-cell">Address</th>
                                <th class="px-6 py-4 text-xs font-bold text-bark-400 uppercase tracking-wider">Status</th>
                                <th class="px-6 py-4 text-xs font-bold text-bark-400 uppercase tracking-wider hidden lg:table-cell">Date</th>
                                <th class="px-6 py-4 text-xs font-bold text-bark-400 uppercase tracking-wider text-right">Actions</th>
                            </tr>
                        </thead>
                        <tbody class="divide-y divide-bark-200/10">
                            <template x-for="delivery in filteredDeliveries" :key="delivery.id">
                                <tr class="hover:bg-cream-100/50 transition duration-150">
                                    {{-- Order --}}
                                    <td class="px-6 py-4">
                                        <div>
                                            <p class="font-semibold text-bark-600 text-sm" x-text="delivery.order?.order_number || '#' + delivery.order_id"></p>
                                            <p class="text-xs text-muted" x-text="delivery.order?.user?.name || ''"></p>
                                        </div>
                                    </td>
                                    {{-- Recipient --}}
                                    <td class="px-6 py-4">
                                        <div>
                                            <p class="font-semibold text-bark-600 text-sm" x-text="delivery.recipient_name"></p>
                                            <p class="text-xs text-muted" x-text="delivery.phone"></p>
                                        </div>
                                    </td>
                                    {{-- Address --}}
                                    <td class="px-6 py-4 hidden md:table-cell">
                                        <div class="max-w-[250px]">
                                            <p class="text-sm text-bark-600 truncate" x-text="delivery.address"></p>
                                            <p class="text-xs text-muted" x-text="delivery.city + (delivery.postal_code ? ', ' + delivery.postal_code : '')"></p>
                                        </div>
                                    </td>
                                    {{-- Status --}}
                                    <td class="px-6 py-4">
                                        <span class="inline-flex items-center gap-1.5 px-2.5 py-1 text-xs font-semibold rounded-lg"
                                              :class="statusClass(delivery.status)">
                                            <span class="w-1.5 h-1.5 rounded-full" :class="statusDotClass(delivery.status)"></span>
                                            <span x-text="formatStatus(delivery.status)"></span>
                                        </span>
                                    </td>
                                    {{-- Date --}}
                                    <td class="px-6 py-4 hidden lg:table-cell">
                                        <div>
                                            <p class="text-sm text-bark-600" x-text="formatDate(delivery.created_at)"></p>
                                            <p x-show="delivery.delivered_at" class="text-xs text-leaf-500" x-text="'Delivered: ' + formatDate(delivery.delivered_at)"></p>
                                        </div>
                                    </td>
                                    {{-- Actions --}}
                                    <td class="px-6 py-4 text-right">
                                        <div class="flex items-center justify-end gap-2">
                                            <button @click="openViewModal(delivery)"
                                                class="p-2 rounded-xl hover:bg-cream-200/50 text-bark-300 hover:text-bark-500 transition duration-150"
                                                title="View Details">
                                                <svg class="w-4 h-4" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                                                    <path stroke-linecap="round" stroke-linejoin="round" d="M2.036 12.322a1.012 1.012 0 010-.639C3.423 7.51 7.36 4.5 12 4.5c4.638 0 8.573 3.007 9.963 7.178.07.207.07.431 0 .639C20.577 16.49 16.64 19.5 12 19.5c-4.638 0-8.573-3.007-9.963-7.178z"/>
                                                    <path stroke-linecap="round" stroke-linejoin="round" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"/>
                                                </svg>
                                            </button>
                                            <button @click="openStatusModal(delivery)"
                                                class="p-2 rounded-xl hover:bg-cream-200/50 text-bark-300 hover:text-bark-500 transition duration-150"
                                                title="Update Status">
                                                <svg class="w-4 h-4" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                                                    <path stroke-linecap="round" stroke-linejoin="round" d="M16.862 4.487l1.687-1.688a1.875 1.875 0 112.652 2.652L10.582 16.07a4.5 4.5 0 01-1.897 1.13L6 18l.8-2.685a4.5 4.5 0 011.13-1.897l8.932-8.931zm0 0L19.5 7.125M18 14v4.75A2.25 2.25 0 0115.75 21H5.25A2.25 2.25 0 013 18.75V8.25A2.25 2.25 0 015.25 6H10"/>
                                                </svg>
                                            </button>
                                            <button @click="confirmDelete(delivery)"
                                                class="p-2 rounded-xl hover:bg-red-50 text-bark-200 hover:text-red-500 transition duration-150"
                                                title="Delete">
                                                <svg class="w-4 h-4" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                                                    <path stroke-linecap="round" stroke-linejoin="round" d="M14.74 9l-.346 9m-4.788 0L9.26 9m9.968-3.21c.342.052.682.107 1.022.166m-1.022-.165L18.16 19.673a2.25 2.25 0 01-2.244 2.077H8.084a2.25 2.25 0 01-2.244-2.077L4.772 5.79m14.456 0a48.108 48.108 0 00-3.478-.397m-12 .562c.34-.059.68-.114 1.022-.165m0 0a48.11 48.11 0 013.478-.397m7.5 0v-.916c0-1.18-.91-2.164-2.09-2.201a51.964 51.964 0 00-3.32 0c-1.18.037-2.09 1.022-2.09 2.201v.916m7.5 0a48.667 48.667 0 00-7.5 0"/>
                                                </svg>
                                            </button>
                                        </div>
                                    </td>
                                </tr>
                            </template>
                        </tbody>
                    </table>
                </div>

                {{-- Empty State --}}
                <div x-show="filteredDeliveries.length === 0 && !loading" class="text-center py-16">
                    <div class="text-5xl mb-4">🚚</div>
                    <h3 class="font-serif font-bold text-bark-500 text-lg mb-2">No deliveries found</h3>
                    <p class="text-muted text-sm">Try adjusting your search or status filter.</p>
                </div>
            </div>
        </div>

        {{-- View Delivery Detail Modal --}}
        <div x-show="showViewModal" x-transition:enter="transition ease-out duration-200"
             x-transition:enter-start="opacity-0" x-transition:enter-end="opacity-100"
             x-transition:leave="transition ease-in duration-150"
             x-transition:leave-start="opacity-100" x-transition:leave-end="opacity-0"
             class="fixed inset-0 z-50 flex items-center justify-center p-4" style="display:none"
             @keydown.escape.window="showViewModal = false">
            <div class="absolute inset-0 bg-bark-600/40 backdrop-blur-sm" @click="showViewModal = false"></div>
            <div x-show="showViewModal"
                 x-transition:enter="transition ease-out duration-200 transform"
                 x-transition:enter-start="scale-90 opacity-0"
                 x-transition:enter-end="scale-100 opacity-100"
                 x-transition:leave="transition ease-in duration-150 transform"
                 x-transition:leave-start="scale-100 opacity-100"
                 x-transition:leave-end="scale-90 opacity-0"
                 class="relative bg-cream-50 rounded-3xl w-full max-w-lg max-h-[90vh] overflow-y-auto shadow-2xl">

                <div class="sticky top-0 bg-cream-50 px-6 py-5 border-b border-bark-200/10 flex items-center justify-between z-10 rounded-t-3xl">
                    <h3 class="font-serif font-bold text-xl text-bark-600">Delivery Details</h3>
                    <button @click="showViewModal = false"
                        class="w-8 h-8 flex items-center justify-center rounded-full hover:bg-cream-200/50 text-bark-300 hover:text-bark-500 transition">
                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/>
                        </svg>
                    </button>
                </div>

                <div class="p-6 space-y-4" x-show="viewingDelivery">
                    <div class="grid grid-cols-2 gap-4">
                        <div>
                            <p class="text-xs font-semibold text-muted uppercase tracking-wider">Order</p>
                            <p class="text-sm font-bold text-bark-600 mt-1" x-text="viewingDelivery?.order?.order_number || '#' + viewingDelivery?.order_id"></p>
                        </div>
                        <div>
                            <p class="text-xs font-semibold text-muted uppercase tracking-wider">Status</p>
                            <span class="inline-flex items-center gap-1.5 px-2.5 py-1 text-xs font-semibold rounded-lg mt-1"
                                  :class="statusClass(viewingDelivery?.status)">
                                <span class="w-1.5 h-1.5 rounded-full" :class="statusDotClass(viewingDelivery?.status)"></span>
                                <span x-text="formatStatus(viewingDelivery?.status)"></span>
                            </span>
                        </div>
                    </div>

                    <div class="border-t border-bark-200/10 pt-4">
                        <p class="text-xs font-semibold text-muted uppercase tracking-wider mb-2">Recipient</p>
                        <div class="space-y-1">
                            <p class="text-sm font-semibold text-bark-600" x-text="viewingDelivery?.recipient_name"></p>
                            <p class="text-sm text-muted" x-text="viewingDelivery?.phone"></p>
                        </div>
                    </div>

                    <div class="border-t border-bark-200/10 pt-4">
                        <p class="text-xs font-semibold text-muted uppercase tracking-wider mb-2">Delivery Address</p>
                        <div class="space-y-1">
                            <p class="text-sm text-bark-600" x-text="viewingDelivery?.address"></p>
                            <p class="text-sm text-muted">
                                <span x-text="viewingDelivery?.city"></span>
                                <span x-show="viewingDelivery?.postal_code" x-text="', ' + viewingDelivery?.postal_code"></span>
                            </p>
                        </div>
                    </div>

                    <div class="border-t border-bark-200/10 pt-4 grid grid-cols-2 gap-4">
                        <div>
                            <p class="text-xs font-semibold text-muted uppercase tracking-wider">Created</p>
                            <p class="text-sm text-bark-600 mt-1" x-text="formatDate(viewingDelivery?.created_at)"></p>
                        </div>
                        <div x-show="viewingDelivery?.delivered_at">
                            <p class="text-xs font-semibold text-muted uppercase tracking-wider">Delivered</p>
                            <p class="text-sm text-leaf-500 mt-1" x-text="formatDate(viewingDelivery?.delivered_at)"></p>
                        </div>
                    </div>

                    <div x-show="viewingDelivery?.delivery_notes" class="border-t border-bark-200/10 pt-4">
                        <p class="text-xs font-semibold text-muted uppercase tracking-wider mb-2">Notes</p>
                        <p class="text-sm text-bark-600 bg-cream-100/50 rounded-xl p-3" x-text="viewingDelivery?.delivery_notes"></p>
                    </div>
                </div>
            </div>
        </div>

        {{-- Update Status Modal --}}
        <div x-show="showStatusModal" x-transition:enter="transition ease-out duration-200"
             x-transition:enter-start="opacity-0" x-transition:enter-end="opacity-100"
             x-transition:leave="transition ease-in duration-150"
             x-transition:leave-start="opacity-100" x-transition:leave-end="opacity-0"
             class="fixed inset-0 z-50 flex items-center justify-center p-4" style="display:none"
             @keydown.escape.window="showStatusModal = false">
            <div class="absolute inset-0 bg-bark-600/40 backdrop-blur-sm" @click="showStatusModal = false"></div>
            <div x-show="showStatusModal"
                 x-transition:enter="transition ease-out duration-200 transform"
                 x-transition:enter-start="scale-90 opacity-0"
                 x-transition:enter-end="scale-100 opacity-100"
                 x-transition:leave="transition ease-in duration-150 transform"
                 x-transition:leave-start="scale-100 opacity-100"
                 x-transition:leave-end="scale-90 opacity-0"
                 class="relative bg-cream-50 rounded-3xl w-full max-w-md shadow-2xl">

                <div class="px-6 py-5 border-b border-bark-200/10 flex items-center justify-between rounded-t-3xl">
                    <h3 class="font-serif font-bold text-xl text-bark-600">Update Delivery</h3>
                    <button @click="showStatusModal = false"
                        class="w-8 h-8 flex items-center justify-center rounded-full hover:bg-cream-200/50 text-bark-300 hover:text-bark-500 transition">
                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/>
                        </svg>
                    </button>
                </div>

                <form @submit.prevent="updateStatus()" class="p-6 space-y-5">
                    <div>
                        <p class="text-sm text-muted mb-1">Delivery for</p>
                        <p class="font-semibold text-bark-600" x-text="editingDelivery?.recipient_name"></p>
                        <p class="text-xs text-muted" x-text="editingDelivery?.order?.order_number || '#' + editingDelivery?.order_id"></p>
                    </div>

                    <div>
                        <label class="block text-sm font-semibold text-bark-500 mb-1.5">Status</label>
                        <select x-model="statusForm.status" required
                            class="w-full px-4 py-3 rounded-2xl border border-bark-200/20 bg-white text-bark-600 focus:outline-none focus:ring-2 focus:ring-bark-300/40 focus:border-bark-300 transition-all text-sm">
                            <option value="pending">Pending</option>
                            <option value="assigned">Assigned</option>
                            <option value="picked_up">Picked Up</option>
                            <option value="in_transit">In Transit</option>
                            <option value="delivered">Delivered</option>
                            <option value="failed">Failed</option>
                        </select>
                    </div>

                    <div>
                        <label class="block text-sm font-semibold text-bark-500 mb-1.5">Delivery Notes</label>
                        <textarea x-model="statusForm.delivery_notes" rows="3"
                            class="w-full px-4 py-3 rounded-2xl border border-bark-200/20 bg-white text-bark-600 placeholder-bark-200 focus:outline-none focus:ring-2 focus:ring-bark-300/40 focus:border-bark-300 transition-all text-sm resize-none"
                            placeholder="Optional notes..."></textarea>
                    </div>

                    <div class="flex items-center justify-end gap-3 pt-3 border-t border-bark-200/10">
                        <button type="button" @click="showStatusModal = false"
                            class="px-5 py-2.5 text-bark-500 font-semibold text-sm rounded-xl hover:bg-cream-200/50 transition duration-200">
                            Cancel
                        </button>
                        <button type="submit" :disabled="saving"
                            class="inline-flex items-center gap-2 px-6 py-2.5 bg-bark-300 hover:bg-bark-400 disabled:opacity-50 text-cream-50 font-semibold text-sm rounded-xl shadow-sm transition duration-200">
                            <svg x-show="saving" class="animate-spin h-4 w-4" fill="none" viewBox="0 0 24 24">
                                <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle>
                                <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4z"></path>
                            </svg>
                            <span x-text="saving ? 'Updating...' : 'Update Status'"></span>
                        </button>
                    </div>
                </form>
            </div>
        </div>

        {{-- Delete Confirmation Modal --}}
        <div x-show="showDeleteModal" x-transition:enter="transition ease-out duration-200"
             x-transition:enter-start="opacity-0" x-transition:enter-end="opacity-100"
             x-transition:leave="transition ease-in duration-150"
             x-transition:leave-start="opacity-100" x-transition:leave-end="opacity-0"
             class="fixed inset-0 z-50 flex items-center justify-center p-4" style="display:none">
            <div class="absolute inset-0 bg-bark-600/40 backdrop-blur-sm" @click="showDeleteModal = false"></div>
            <div x-show="showDeleteModal"
                 x-transition:enter="transition ease-out duration-200 transform"
                 x-transition:enter-start="scale-90 opacity-0"
                 x-transition:enter-end="scale-100 opacity-100"
                 class="relative bg-cream-50 rounded-3xl w-full max-w-md p-6 shadow-2xl text-center">
                <div class="w-14 h-14 mx-auto mb-4 rounded-full bg-red-100 flex items-center justify-center">
                    <svg class="w-7 h-7 text-red-500" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M14.74 9l-.346 9m-4.788 0L9.26 9m9.968-3.21c.342.052.682.107 1.022.166m-1.022-.165L18.16 19.673a2.25 2.25 0 01-2.244 2.077H8.084a2.25 2.25 0 01-2.244-2.077L4.772 5.79m14.456 0a48.108 48.108 0 00-3.478-.397m-12 .562c.34-.059.68-.114 1.022-.165m0 0a48.11 48.11 0 013.478-.397m7.5 0v-.916c0-1.18-.91-2.164-2.09-2.201a51.964 51.964 0 00-3.32 0c-1.18.037-2.09 1.022-2.09 2.201v.916m7.5 0a48.667 48.667 0 00-7.5 0"/>
                    </svg>
                </div>
                <h3 class="font-serif font-bold text-lg text-bark-600 mb-2">Delete Delivery</h3>
                <p class="text-muted text-sm mb-6">Are you sure you want to delete the delivery for <strong class="text-bark-600" x-text="deletingDelivery?.recipient_name"></strong>? This action cannot be undone.</p>
                <div class="flex items-center justify-center gap-3">
                    <button @click="showDeleteModal = false"
                        class="px-5 py-2.5 text-bark-500 font-semibold text-sm rounded-xl hover:bg-cream-200/50 transition duration-200">
                        Cancel
                    </button>
                    <button @click="deleteDelivery()" :disabled="saving"
                        class="inline-flex items-center gap-2 px-6 py-2.5 bg-red-500 hover:bg-red-600 disabled:opacity-50 text-white font-semibold text-sm rounded-xl shadow-sm transition duration-200">
                        <svg x-show="saving" class="animate-spin h-4 w-4" fill="none" viewBox="0 0 24 24">
                            <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle>
                            <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4z"></path>
                        </svg>
                        Delete
                    </button>
                </div>
            </div>
        </div>
    </div>

    @push('scripts')
    <script>
        function deliveryManager() {
            return {
                deliveries: [],
                search: '',
                filterStatus: '',
                loading: true,
                saving: false,
                showViewModal: false,
                showStatusModal: false,
                showDeleteModal: false,
                viewingDelivery: null,
                editingDelivery: null,
                deletingDelivery: null,
                successMsg: '',
                errorMsg: '',
                statusForm: {
                    status: '',
                    delivery_notes: '',
                },

                async init() {
                    await this.fetchDeliveries();
                },

                async fetchDeliveries() {
                    this.loading = true;
                    try {
                        const res = await fetch('/api/deliveries', {
                            headers: { 'Accept': 'application/json' }
                        });
                        if (!res.ok) throw new Error('Failed to load deliveries');
                        const data = await res.json();
                        this.deliveries = data.data || data || [];
                    } catch (e) {
                        console.error('Error loading deliveries:', e);
                        this.showError('Failed to load deliveries. Please refresh the page.');
                    } finally {
                        this.loading = false;
                    }
                },

                get filteredDeliveries() {
                    return this.deliveries.filter(d => {
                        const matchSearch = !this.search ||
                            d.recipient_name.toLowerCase().includes(this.search.toLowerCase()) ||
                            (d.phone && d.phone.toLowerCase().includes(this.search.toLowerCase())) ||
                            (d.address && d.address.toLowerCase().includes(this.search.toLowerCase())) ||
                            (d.city && d.city.toLowerCase().includes(this.search.toLowerCase()));
                        const matchStatus = !this.filterStatus || d.status === this.filterStatus;
                        return matchSearch && matchStatus;
                    });
                },

                openViewModal(delivery) {
                    this.viewingDelivery = delivery;
                    this.showViewModal = true;
                },

                openStatusModal(delivery) {
                    this.editingDelivery = delivery;
                    this.statusForm.status = delivery.status;
                    this.statusForm.delivery_notes = delivery.delivery_notes || '';
                    this.showStatusModal = true;
                },

                async updateStatus() {
                    if (!this.editingDelivery) return;
                    this.saving = true;
                    this.clearMessages();

                    const token = document.querySelector('meta[name="csrf-token"]')?.content;

                    try {
                        const res = await fetch(`/api/deliveries/${this.editingDelivery.id}`, {
                            method: 'PUT',
                            headers: {
                                'Content-Type': 'application/json',
                                'Accept': 'application/json',
                                'X-CSRF-TOKEN': token,
                            },
                            body: JSON.stringify(this.statusForm),
                        });

                        if (!res.ok) throw new Error('Failed to update delivery');

                        this.showStatusModal = false;
                        this.showSuccess('Delivery updated successfully!');
                        await this.fetchDeliveries();
                    } catch (e) {
                        console.error('Error updating delivery:', e);
                        this.showError('Failed to update delivery. Please try again.');
                    } finally {
                        this.saving = false;
                    }
                },

                confirmDelete(delivery) {
                    this.deletingDelivery = delivery;
                    this.showDeleteModal = true;
                },

                async deleteDelivery() {
                    if (!this.deletingDelivery) return;
                    this.saving = true;
                    this.clearMessages();

                    const token = document.querySelector('meta[name="csrf-token"]')?.content;

                    try {
                        const res = await fetch(`/api/deliveries/${this.deletingDelivery.id}`, {
                            method: 'DELETE',
                            headers: {
                                'Accept': 'application/json',
                                'X-CSRF-TOKEN': token,
                            },
                        });

                        if (!res.ok) throw new Error('Failed to delete delivery');

                        this.showDeleteModal = false;
                        this.deletingDelivery = null;
                        this.showSuccess('Delivery deleted successfully!');
                        await this.fetchDeliveries();
                    } catch (e) {
                        console.error('Error deleting delivery:', e);
                        this.showError('Failed to delete delivery. Please try again.');
                    } finally {
                        this.saving = false;
                    }
                },

                statusClass(status) {
                    const classes = {
                        pending: 'bg-gold-100/60 text-gold-600',
                        assigned: 'bg-blue-100/60 text-blue-600',
                        picked_up: 'bg-purple-100/60 text-purple-600',
                        in_transit: 'bg-blue-100/60 text-blue-600',
                        delivered: 'bg-leaf-300/20 text-leaf-500',
                        failed: 'bg-red-100/60 text-red-600',
                    };
                    return classes[status] || 'bg-cream-200/60 text-bark-500';
                },

                statusDotClass(status) {
                    const classes = {
                        pending: 'bg-gold-400',
                        assigned: 'bg-blue-400',
                        picked_up: 'bg-purple-400',
                        in_transit: 'bg-blue-500',
                        delivered: 'bg-leaf-400',
                        failed: 'bg-red-500',
                    };
                    return classes[status] || 'bg-bark-200';
                },

                formatStatus(status) {
                    if (!status) return '';
                    return status.replace(/_/g, ' ').replace(/\b\w/g, c => c.toUpperCase());
                },

                formatDate(dateStr) {
                    if (!dateStr) return '';
                    const date = new Date(dateStr);
                    return date.toLocaleDateString('en-PH', {
                        year: 'numeric', month: 'short', day: 'numeric',
                        hour: '2-digit', minute: '2-digit',
                    });
                },

                showSuccess(msg) {
                    this.successMsg = msg;
                    this.errorMsg = '';
                    setTimeout(() => this.successMsg = '', 4000);
                },

                showError(msg) {
                    this.errorMsg = msg;
                    this.successMsg = '';
                    setTimeout(() => this.errorMsg = '', 5000);
                },

                clearMessages() {
                    this.successMsg = '';
                    this.errorMsg = '';
                },
            };
        }
    </script>
    @endpush
</x-app-layout>
