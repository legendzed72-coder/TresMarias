<x-app-layout>
    <x-slot name="header">
        <div class="flex items-center justify-between">
            <div>
                <h2 class="font-serif font-bold text-2xl text-bark-600 leading-tight">
                    {{ __('Manage Products') }}
                </h2>
                <p class="mt-1 text-sm text-muted">{{ __('Add, edit, and manage your bakery products') }}</p>
            </div>
            <button @click="openCreateModal()"
                class="inline-flex items-center gap-2 px-5 py-2.5 bg-bark-300 hover:bg-bark-400 text-cream-50 font-semibold text-sm rounded-xl shadow-sm transition duration-200">
                <svg class="w-5 h-5" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M12 4v16m8-8H4"/>
                </svg>
                {{ __('Add Product') }}
            </button>
        </div>
    </x-slot>

    <div class="flex">
        {{-- Sidebar --}}
        <x-admin-sidebar />

        {{-- Main Content --}}
        <div x-data="adminProducts()" x-init="init()" class="flex-1 py-8">
        <div class="max-w-6xl mx-auto px-4 sm:px-6 lg:px-8 space-y-6">

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
                    <input type="text" x-model="search" placeholder="Search products..."
                        class="w-full pl-12 pr-4 py-3 rounded-2xl border border-bark-200/20 bg-cream-50 text-bark-600 placeholder-bark-200 focus:outline-none focus:ring-2 focus:ring-bark-300/40 focus:border-bark-300 transition-all font-medium text-sm shadow-sm">
                </div>
                <select x-model="filterCategory"
                    class="px-4 py-3 rounded-2xl border border-bark-200/20 bg-cream-50 text-bark-600 focus:outline-none focus:ring-2 focus:ring-bark-300/40 focus:border-bark-300 transition-all font-medium text-sm shadow-sm">
                    <option value="">All Categories</option>
                    <template x-for="cat in categories" :key="cat.id">
                        <option :value="cat.id" x-text="cat.name"></option>
                    </template>
                </select>
            </div>

            {{-- Stats Row --}}
            <div class="grid grid-cols-2 sm:grid-cols-4 gap-4">
                <div class="bg-cream-50 rounded-2xl ring-1 ring-bark-200/10 p-4 text-center">
                    <p class="text-xs font-semibold text-muted uppercase tracking-wider">Total</p>
                    <p class="text-2xl font-bold text-bark-600 mt-1" x-text="products.length"></p>
                </div>
                <div class="bg-cream-50 rounded-2xl ring-1 ring-bark-200/10 p-4 text-center">
                    <p class="text-xs font-semibold text-muted uppercase tracking-wider">Active</p>
                    <p class="text-2xl font-bold text-leaf-500 mt-1" x-text="products.filter(p => p.is_active).length"></p>
                </div>
                <div class="bg-cream-50 rounded-2xl ring-1 ring-bark-200/10 p-4 text-center">
                    <p class="text-xs font-semibold text-muted uppercase tracking-wider">Low Stock</p>
                    <p class="text-2xl font-bold text-gold-500 mt-1" x-text="products.filter(p => p.stock_quantity <= p.min_stock_level && p.stock_quantity > 0).length"></p>
                </div>
                <div class="bg-cream-50 rounded-2xl ring-1 ring-bark-200/10 p-4 text-center">
                    <p class="text-xs font-semibold text-muted uppercase tracking-wider">Out of Stock</p>
                    <p class="text-2xl font-bold text-red-500 mt-1" x-text="products.filter(p => p.stock_quantity === 0).length"></p>
                </div>
            </div>

            {{-- Loading --}}
            <div x-show="loading" x-transition class="flex justify-center py-16">
                <div class="flex flex-col items-center gap-4">
                    <svg class="animate-spin h-10 w-10 text-bark-300" fill="none" viewBox="0 0 24 24">
                        <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle>
                        <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4z"></path>
                    </svg>
                    <span class="text-bark-200 font-medium text-sm">Loading products...</span>
                </div>
            </div>

            {{-- Products Table --}}
            <div x-show="!loading" x-cloak class="bg-cream-50 rounded-2xl ring-1 ring-bark-200/10 shadow-sm shadow-bark-200/5 overflow-hidden">
                <div class="overflow-x-auto">
                    <table class="w-full text-left">
                        <thead>
                            <tr class="border-b border-bark-200/10">
                                <th class="px-6 py-4 text-xs font-bold text-bark-400 uppercase tracking-wider">Product</th>
                                <th class="px-6 py-4 text-xs font-bold text-bark-400 uppercase tracking-wider hidden sm:table-cell">Category</th>
                                <th class="px-6 py-4 text-xs font-bold text-bark-400 uppercase tracking-wider">Price</th>
                                <th class="px-6 py-4 text-xs font-bold text-bark-400 uppercase tracking-wider hidden md:table-cell">Cost</th>
                                <th class="px-6 py-4 text-xs font-bold text-bark-400 uppercase tracking-wider">Stock</th>
                                <th class="px-6 py-4 text-xs font-bold text-bark-400 uppercase tracking-wider hidden lg:table-cell">Status</th>
                                <th class="px-6 py-4 text-xs font-bold text-bark-400 uppercase tracking-wider text-right">Actions</th>
                            </tr>
                        </thead>
                        <tbody class="divide-y divide-bark-200/10">
                            <template x-for="product in filteredProducts" :key="product.id">
                                <tr class="hover:bg-cream-100/50 transition duration-150">
                                    {{-- Product Name & Image --}}
                                    <td class="px-6 py-4">
                                        <div class="flex items-center gap-3">
                                            <div class="w-10 h-10 rounded-xl bg-cream-200 overflow-hidden flex-shrink-0">
                                                <img :src="product.image_url || '/images/bread-placeholder.jpg'"
                                                     :alt="product.name"
                                                     class="w-full h-full object-cover"
                                                     onerror="this.src='data:image/svg+xml,<svg xmlns=%22http://www.w3.org/2000/svg%22 viewBox=%220 0 40 40%22><rect fill=%22%23f7ecdc%22 width=%2240%22 height=%2240%22/><text x=%2250%25%22 y=%2250%25%22 dominant-baseline=%22middle%22 text-anchor=%22middle%22 font-size=%2216%22>🍞</text></svg>'">
                                            </div>
                                            <div class="min-w-0">
                                                <p class="font-semibold text-bark-600 text-sm truncate" x-text="product.name"></p>
                                                <p class="text-xs text-muted truncate max-w-[200px]" x-text="product.description"></p>
                                            </div>
                                        </div>
                                    </td>
                                    {{-- Category --}}
                                    <td class="px-6 py-4 hidden sm:table-cell">
                                        <span class="inline-flex px-2.5 py-1 bg-cream-200/60 text-bark-500 text-xs font-semibold rounded-lg"
                                              x-text="product.category && product.category.name ? product.category.name : '—'"></span>
                                    </td>
                                    {{-- Price --}}
                                    <td class="px-6 py-4">
                                        <span class="font-bold text-bark-600 text-sm">₱<span x-text="parseFloat(product.price).toFixed(2)"></span></span>
                                    </td>
                                    {{-- Cost --}}
                                    <td class="px-6 py-4 hidden md:table-cell">
                                        <span class="text-muted text-sm" x-text="product.cost_price ? '₱' + parseFloat(product.cost_price).toFixed(2) : '—'"></span>
                                    </td>
                                    {{-- Stock --}}
                                    <td class="px-6 py-4">
                                        <div class="flex items-center gap-2">
                                            <span class="w-2 h-2 rounded-full flex-shrink-0"
                                                  :class="product.stock_quantity === 0 ? 'bg-red-500' : product.stock_quantity <= product.min_stock_level ? 'bg-gold-400' : 'bg-leaf-400'"></span>
                                            <span class="text-sm font-semibold"
                                                  :class="product.stock_quantity === 0 ? 'text-red-500' : product.stock_quantity <= product.min_stock_level ? 'text-gold-500' : 'text-bark-600'"
                                                  x-text="product.stock_quantity"></span>
                                        </div>
                                    </td>
                                    {{-- Status --}}
                                    <td class="px-6 py-4 hidden lg:table-cell">
                                        <div class="flex flex-col gap-1">
                                            <span class="inline-flex items-center gap-1 text-xs font-semibold"
                                                  :class="product.is_active ? 'text-leaf-500' : 'text-muted'">
                                                <span class="w-1.5 h-1.5 rounded-full" :class="product.is_active ? 'bg-leaf-400' : 'bg-bark-200'"></span>
                                                <span x-text="product.is_active ? 'Active' : 'Inactive'"></span>
                                            </span>
                                            <span x-show="product.available_for_preorder" class="text-[10px] font-semibold text-gold-500">Pre-order</span>
                                        </div>
                                    </td>
                                    {{-- Actions --}}
                                    <td class="px-6 py-4 text-right">
                                        <div class="flex items-center justify-end gap-1">
                                            <button @click="openEditModal(product)"
                                                class="p-2 rounded-xl hover:bg-cream-200/50 text-bark-300 hover:text-bark-500 transition duration-150"
                                                title="Edit">
                                                <svg class="w-4 h-4" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                                                    <path stroke-linecap="round" stroke-linejoin="round" d="M16.862 4.487l1.687-1.688a1.875 1.875 0 112.652 2.652L10.582 16.07a4.5 4.5 0 01-1.897 1.13L6 18l.8-2.685a4.5 4.5 0 011.13-1.897l8.932-8.931zm0 0L19.5 7.125M18 14v4.75A2.25 2.25 0 0115.75 21H5.25A2.25 2.25 0 013 18.75V8.25A2.25 2.25 0 015.25 6H10"/>
                                                </svg>
                                            </button>
                                            <button @click="toggleArchive(product)"
                                                class="p-2 rounded-xl hover:bg-amber-50 transition duration-150"
                                                :class="product.is_archived ? 'text-amber-600 hover:text-amber-700' : 'text-amber-400 hover:text-amber-500'"
                                                :title="product.is_archived ? 'Unarchive' : 'Archive'">
                                                <svg v-if="product.is_archived" class="w-4 h-4" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                                                    <path stroke-linecap="round" stroke-linejoin="round" d="M12 21v-8.97m0 0l3.368 3.368m-3.368-3.368L8.632 12.03M21 12a9 9 0 11-18 0 9 9 0 0118 0zm-5-4v8m0 0H8m8 0v-8"/>
                                                </svg>
                                                <svg v-else class="w-4 h-4" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                                                    <path stroke-linecap="round" stroke-linejoin="round" d="M20.25 7.5l-.625 10.632a2.25 2.25 0 01-2.247 2.118H6.622a2.25 2.25 0 01-2.247-2.118L3.75 7.5m6 4.5v2.25m3-2.25v2.25m3-6h2.25A2.25 2.25 0 0121 9.75v.081c0 .195-.02.39-.06.576m-1.921 7.518a2.25 2.25 0 01-2.248 2.118H5.75a2.25 2.25 0 01-2.248-2.118m16.5-7.496h-2.25m0 0l-.4-9.428a2.25 2.25 0 00-2.25-2.072H9.75a2.25 2.25 0 00-2.25 2.072l-.4 9.428m16.5 0a2.25 2.25 0 00-1.902-2.23m-13.5 2.23a2.25 2.25 0 001.902-2.23"/>
                                                </svg>
                                            </button>
                                            <button @click="confirmDelete(product)"
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
                <div x-show="filteredProducts.length === 0 && !loading" class="text-center py-16">
                    <div class="text-5xl mb-4">🍞</div>
                    <h3 class="font-serif font-bold text-bark-500 text-lg mb-2">No products found</h3>
                    <p class="text-muted text-sm mb-4">Try adjusting your search or add a new product.</p>
                    <button @click="openCreateModal()"
                        class="inline-flex items-center gap-2 px-5 py-2.5 bg-bark-300 hover:bg-bark-400 text-cream-50 font-semibold text-sm rounded-xl shadow-sm transition duration-200">
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M12 4v16m8-8H4"/>
                        </svg>
                        Add Product
                    </button>
                </div>
            </div>
        </div>

        {{-- Create/Edit Product Modal --}}
        <div x-show="showModal" x-transition:enter="transition ease-out duration-200"
             x-transition:enter-start="opacity-0" x-transition:enter-end="opacity-100"
             x-transition:leave="transition ease-in duration-150"
             x-transition:leave-start="opacity-100" x-transition:leave-end="opacity-0"
             class="fixed inset-0 z-50 flex items-center justify-center p-4" style="display:none"
             @keydown.escape.window="showModal = false">
            <div class="absolute inset-0 bg-bark-600/40 backdrop-blur-sm" @click="showModal = false"></div>
            <div x-show="showModal"
                 x-transition:enter="transition ease-out duration-200 transform"
                 x-transition:enter-start="scale-90 opacity-0"
                 x-transition:enter-end="scale-100 opacity-100"
                 x-transition:leave="transition ease-in duration-150 transform"
                 x-transition:leave-start="scale-100 opacity-100"
                 x-transition:leave-end="scale-90 opacity-0"
                 class="relative bg-cream-50 rounded-3xl w-full max-w-2xl max-h-[90vh] overflow-y-auto shadow-2xl">

                {{-- Modal Header --}}
                <div class="sticky top-0 bg-cream-50 px-6 py-5 border-b border-bark-200/10 flex items-center justify-between z-10 rounded-t-3xl">
                    <h3 class="font-serif font-bold text-xl text-bark-600"
                        x-text="editingProduct ? 'Edit Product' : 'Add New Product'"></h3>
                    <button @click="showModal = false"
                        class="w-8 h-8 flex items-center justify-center rounded-full hover:bg-cream-200/50 text-bark-300 hover:text-bark-500 transition">
                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/>
                        </svg>
                    </button>
                </div>

                {{-- Modal Form --}}
                <form @submit.prevent="saveProduct()" class="p-6 space-y-5">
                    {{-- Validation Errors --}}
                    <div x-show="Object.keys(validationErrors).length > 0" x-cloak
                        class="p-4 rounded-2xl bg-red-50 border border-red-200/40">
                        <p class="text-sm font-semibold text-red-600 mb-2">Please fix the following errors:</p>
                        <ul class="list-disc list-inside text-sm text-red-500 space-y-1">
                            <template x-for="(errors, field) in validationErrors" :key="field">
                                <template x-for="error in errors" :key="error">
                                    <li x-text="error"></li>
                                </template>
                            </template>
                        </ul>
                    </div>

                    <div class="grid grid-cols-1 sm:grid-cols-2 gap-5">
                        {{-- Name --}}
                        <div class="sm:col-span-2">
                            <label class="block text-sm font-semibold text-bark-500 mb-1.5">Product Name *</label>
                            <input type="text" x-model="form.name" required
                                class="w-full px-4 py-3 rounded-2xl border border-bark-200/20 bg-white text-bark-600 placeholder-bark-200 focus:outline-none focus:ring-2 focus:ring-bark-300/40 focus:border-bark-300 transition-all text-sm"
                                placeholder="e.g. Classic Pandesal">
                        </div>

                        {{-- Unit Type --}}
                        <div>
                            <label class="block text-sm font-semibold text-bark-500 mb-1.5">Unit Type</label>
                            <select x-model="form.unit_type"
                                class="w-full px-4 py-3 rounded-2xl border border-bark-200/20 bg-white text-bark-600 focus:outline-none focus:ring-2 focus:ring-bark-300/40 focus:border-bark-300 transition-all text-sm">
                                <option value="piece">Piece</option>
                                <option value="pack">Pack</option>
                                <option value="box">Box</option>
                                <option value="loaf">Loaf</option>
                                <option value="whole">Whole</option>
                                <option value="dozen">Dozen</option>
                                <option value="kg">Kilogram</option>
                            </select>
                        </div>

                        {{-- Price --}}
                        <div>
                            <label class="block text-sm font-semibold text-bark-500 mb-1.5">Price (₱) *</label>
                            <input type="number" x-model="form.price" step="0.01" min="0" required
                                class="w-full px-4 py-3 rounded-2xl border border-bark-200/20 bg-white text-bark-600 placeholder-bark-200 focus:outline-none focus:ring-2 focus:ring-bark-300/40 focus:border-bark-300 transition-all text-sm"
                                placeholder="0.00">
                        </div>

                        {{-- Cost Price --}}
                        <div>
                            <label class="block text-sm font-semibold text-bark-500 mb-1.5">Cost Price (₱)</label>
                            <input type="number" x-model="form.cost_price" step="0.01" min="0"
                                class="w-full px-4 py-3 rounded-2xl border border-bark-200/20 bg-white text-bark-600 placeholder-bark-200 focus:outline-none focus:ring-2 focus:ring-bark-300/40 focus:border-bark-300 transition-all text-sm"
                                placeholder="0.00">
                        </div>

                        {{-- Stock Quantity --}}
                        <div>
                            <label class="block text-sm font-semibold text-bark-500 mb-1.5">Stock Quantity</label>
                            <input type="number" x-model="form.stock_quantity" min="0"
                                class="w-full px-4 py-3 rounded-2xl border border-bark-200/20 bg-white text-bark-600 placeholder-bark-200 focus:outline-none focus:ring-2 focus:ring-bark-300/40 focus:border-bark-300 transition-all text-sm"
                                placeholder="0">
                        </div>

                        {{-- Min Stock Level --}}
                        <div>
                            <label class="block text-sm font-semibold text-bark-500 mb-1.5">Min Stock Level</label>
                            <input type="number" x-model="form.min_stock_level" min="0"
                                class="w-full px-4 py-3 rounded-2xl border border-bark-200/20 bg-white text-bark-600 placeholder-bark-200 focus:outline-none focus:ring-2 focus:ring-bark-300/40 focus:border-bark-300 transition-all text-sm"
                                placeholder="10">
                        </div>

                        {{-- Preorder Hours --}}
                        <div>
                            <label class="block text-sm font-semibold text-bark-500 mb-1.5">Pre-order Hours Needed</label>
                            <input type="number" x-model="form.preorder_hours_needed" min="0"
                                class="w-full px-4 py-3 rounded-2xl border border-bark-200/20 bg-white text-bark-600 placeholder-bark-200 focus:outline-none focus:ring-2 focus:ring-bark-300/40 focus:border-bark-300 transition-all text-sm"
                                placeholder="24">
                        </div>

                        {{-- Description --}}
                        <div class="sm:col-span-2">
                            <label class="block text-sm font-semibold text-bark-500 mb-1.5">Description</label>
                            <textarea x-model="form.description" rows="3"
                                class="w-full px-4 py-3 rounded-2xl border border-bark-200/20 bg-white text-bark-600 placeholder-bark-200 focus:outline-none focus:ring-2 focus:ring-bark-300/40 focus:border-bark-300 transition-all text-sm resize-none"
                                placeholder="Describe the product..."></textarea>
                        </div>

                        {{-- Image Upload --}}
                        <div class="sm:col-span-2">
                            <label class="block text-sm font-semibold text-bark-500 mb-1.5">Product Image</label>
                            <div class="flex flex-col gap-4">
                                {{-- Image Preview --}}
                                <div x-show="imagePreview" x-cloak class="relative inline-block">
                                    <img :src="imagePreview" alt="Preview" class="w-32 h-32 object-cover rounded-2xl border-2 border-bark-200/20">
                                    <button type="button" @click="imagePreview = ''; form.image_file = null" 
                                        class="absolute -top-2 -right-2 w-7 h-7 bg-red-500 hover:bg-red-600 text-white rounded-full flex items-center justify-center transition">
                                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/>
                                        </svg>
                                    </button>
                                </div>
                                {{-- File Input --}}
                                <input type="file" accept="image/*" 
                                    @change="handleImageUpload($event)"
                                    class="px-4 py-3 rounded-2xl border border-bark-200/20 bg-white text-bark-600 focus:outline-none focus:ring-2 focus:ring-bark-300/40 focus:border-bark-300 transition-all text-sm file:mr-3 file:py-2 file:px-4 file:rounded-lg file:border-0 file:bg-bark-300 file:text-cream-50 file:font-semibold file:cursor-pointer hover:file:bg-bark-400">
                            </div>
                        </div>

                        {{-- Allergens --}}
                        <div class="sm:col-span-2">
                            <label class="block text-sm font-semibold text-bark-500 mb-1.5">Allergens</label>
                            <div class="flex flex-wrap gap-2">
                                <template x-for="allergen in allergenOptions" :key="allergen">
                                    <label class="inline-flex items-center gap-1.5 px-3 py-1.5 rounded-xl cursor-pointer transition-all text-sm font-medium"
                                           :class="form.allergens.includes(allergen) ? 'bg-bark-300 text-cream-50' : 'bg-cream-200 text-bark-500 hover:bg-cream-300'">
                                        <input type="checkbox" :value="allergen" class="hidden"
                                               @change="toggleAllergen(allergen)">
                                        <span x-text="allergen" class="capitalize"></span>
                                    </label>
                                </template>
                            </div>
                        </div>

                        {{-- Toggles --}}
                        <div class="sm:col-span-2 flex flex-wrap gap-6">
                            <label class="flex items-center gap-3 cursor-pointer">
                                <div class="relative">
                                    <input type="checkbox" x-model="form.is_active" class="sr-only peer">
                                    <div class="w-10 h-6 bg-bark-200/40 rounded-full peer-checked:bg-leaf-400 transition-colors"></div>
                                    <div class="absolute left-0.5 top-0.5 w-5 h-5 bg-white rounded-full shadow peer-checked:translate-x-4 transition-transform"></div>
                                </div>
                                <span class="text-sm font-semibold text-bark-500">Active</span>
                            </label>
                            <label class="flex items-center gap-3 cursor-pointer">
                                <div class="relative">
                                    <input type="checkbox" x-model="form.available_for_preorder" class="sr-only peer">
                                    <div class="w-10 h-6 bg-bark-200/40 rounded-full peer-checked:bg-gold-400 transition-colors"></div>
                                    <div class="absolute left-0.5 top-0.5 w-5 h-5 bg-white rounded-full shadow peer-checked:translate-x-4 transition-transform"></div>
                                </div>
                                <span class="text-sm font-semibold text-bark-500">Available for Pre-order</span>
                            </label>
                        </div>
                    </div>

                    {{-- Submit --}}
                    <div class="flex items-center justify-end gap-3 pt-3 border-t border-bark-200/10">
                        <button type="button" @click="showModal = false"
                            class="px-5 py-2.5 text-bark-500 font-semibold text-sm rounded-xl hover:bg-cream-200/50 transition duration-200">
                            Cancel
                        </button>
                        <button type="submit" :disabled="saving"
                            class="inline-flex items-center gap-2 px-6 py-2.5 bg-bark-300 hover:bg-bark-400 disabled:opacity-50 text-cream-50 font-semibold text-sm rounded-xl shadow-sm transition duration-200">
                            <svg x-show="saving" class="animate-spin h-4 w-4" fill="none" viewBox="0 0 24 24">
                                <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle>
                                <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4z"></path>
                            </svg>
                            <span x-text="saving ? 'Saving...' : (editingProduct ? 'Update Product' : 'Create Product')"></span>
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
                <h3 class="font-serif font-bold text-lg text-bark-600 mb-2">Delete Product</h3>
                <p class="text-muted text-sm mb-6">Are you sure you want to delete <strong class="text-bark-600" x-text="deletingProduct && deletingProduct.name ? deletingProduct.name : ''"></strong>? This action cannot be undone.</p>
                <div class="flex items-center justify-center gap-3">
                    <button @click="showDeleteModal = false"
                        class="px-5 py-2.5 text-bark-500 font-semibold text-sm rounded-xl hover:bg-cream-200/50 transition duration-200">
                        Cancel
                    </button>
                    <button @click="deleteProduct()" :disabled="saving"
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
        function adminProducts() {
            return {
                products: [],
                categories: [
                    { id: 1, name: 'Bread' },
                    { id: 2, name: 'Pastries' },
                    { id: 3, name: 'Cakes' },
                    { id: 4, name: 'Cookies' },
                    { id: 5, name: 'Donuts' },
                    { id: 6, name: 'Buns' },
                    { id: 7, name: 'Croissants' },
                    { id: 8, name: 'Desserts' },
                ],
                search: '',
                filterCategory: '',
                loading: true,
                saving: false,
                showModal: false,
                showDeleteModal: false,
                editingProduct: null,
                deletingProduct: null,
                successMsg: '',
                errorMsg: '',
                validationErrors: {},
                allergenOptions: ['wheat', 'dairy', 'egg', 'soy', 'nuts', 'gluten'],
                form: {
                    name: '',
                    description: '',
                    price: '',
                    cost_price: '',
                    stock_quantity: 0,
                    min_stock_level: 10,
                    unit_type: 'piece',
                    is_active: true,
                    available_for_preorder: true,
                    preorder_hours_needed: 24,
                    allergens: [],
                },
                imagePreview: '',

                async init() {
                    await Promise.all([this.fetchProducts(), this.fetchCategories()]);
                },

                async fetchProducts() {
                    this.loading = true;
                    try {
                        const res = await fetch('/api/products', {
                            headers: { 'Accept': 'application/json' },
                            credentials: 'same-origin',
                        });
                        if (!res.ok) throw new Error('Failed to load products');
                        const data = await res.json();
                        this.products = data.data || data || [];
                    } catch (e) {
                        console.error('Error loading products:', e);
                        this.showError('Failed to load products. Please refresh the page.');
                    } finally {
                        this.loading = false;
                    }
                },

                async fetchCategories() {
                    try {
                        const res = await fetch('/api/admin/categories', {
                            headers: { 'Accept': 'application/json' },
                            credentials: 'same-origin',
                        });
                        if (!res.ok) throw new Error('Failed to load categories');
                        const data = await res.json();
                        this.categories = data.data || data || [];
                    } catch (e) {
                        console.error('Error loading categories:', e);
                    }
                },

                get filteredProducts() {
                    return this.products.filter(p => {
                        const matchSearch = !this.search ||
                            p.name.toLowerCase().includes(this.search.toLowerCase()) ||
                            (p.description && p.description.toLowerCase().includes(this.search.toLowerCase()));
                        const matchCategory = !this.filterCategory || p.category_id == this.filterCategory;
                        return matchSearch && matchCategory;
                    });
                },

                resetForm() {
                    this.form = {
                        name: '',
                        description: '',
                        price: '',
                        cost_price: '',
                        stock_quantity: 0,
                        min_stock_level: 10,
                        unit_type: 'piece',
                        is_active: true,
                        available_for_preorder: true,
                        preorder_hours_needed: 24,
                        allergens: [],
                    };
                    this.imagePreview = '';
                    this.validationErrors = {};
                },

                openCreateModal() {
                    this.editingProduct = null;
                    this.resetForm();
                    this.showModal = true;
                },

                openEditModal(product) {
                    this.editingProduct = product;
                    this.form = {
                        name: product.name,
                        description: product.description || '',
                        price: product.price,
                        cost_price: product.cost_price || '',
                        stock_quantity: product.stock_quantity,
                        min_stock_level: product.min_stock_level,
                        unit_type: product.unit_type || 'piece',
                        is_active: !!product.is_active,
                        available_for_preorder: !!product.available_for_preorder,
                        preorder_hours_needed: product.preorder_hours_needed || 24,
                        allergens: Array.isArray(product.allergens) ? [...product.allergens] : [],
                    };
                    this.imagePreview = product.image_url ? product.image_url : '';
                    this.validationErrors = {};
                    this.showModal = true;
                },

                handleImageUpload(event) {
                    const file = event.target.files?.[0];
                    if (file) {
                        this.form.image_file = file;
                        const reader = new FileReader();
                        reader.onload = (e) => {
                            this.imagePreview = e.target.result;
                        };
                        reader.readAsDataURL(file);
                    }
                },

                toggleAllergen(allergen) {
                    const idx = this.form.allergens.indexOf(allergen);
                    if (idx > -1) {
                        this.form.allergens.splice(idx, 1);
                    } else {
                        this.form.allergens.push(allergen);
                    }
                },

                async saveProduct() {
                    this.saving = true;
                    this.validationErrors = {};
                    this.clearMessages();

                    const url = this.editingProduct
                        ? `/api/products/${this.editingProduct.id}`
                        : '/api/products';
                    const method = this.editingProduct ? 'POST' : 'POST';

                    const tokenElement = document.querySelector('meta[name="csrf-token"]');
                    const token = tokenElement ? tokenElement.content : '';

                    try {
                        const formData = new FormData();
                        
                        // Add form fields
                        formData.append('name', this.form.name);
                        formData.append('description', this.form.description);
                        formData.append('price', this.form.price);
                        formData.append('cost_price', this.form.cost_price);
                        formData.append('stock_quantity', this.form.stock_quantity);
                        formData.append('min_stock_level', this.form.min_stock_level);
                        formData.append('unit_type', this.form.unit_type);
                        formData.append('is_active', this.form.is_active ? 1 : 0);
                        formData.append('available_for_preorder', this.form.available_for_preorder ? 1 : 0);
                        formData.append('preorder_hours_needed', this.form.preorder_hours_needed);
                        formData.append('allergens', JSON.stringify(this.form.allergens));
                        
                        // Add image file if present
                        if (this.form.image_file) {
                            formData.append('image', this.form.image_file);
                        }
                        
                        // For PUT requests, add method override
                        if (this.editingProduct) {
                            formData.append('_method', 'PUT');
                        }

                        const res = await fetch(url, {
                            method: 'POST',
                            headers: {
                                'Accept': 'application/json',
                                'X-CSRF-TOKEN': token,
                            },
                            body: formData,
                        });

                        if (res.status === 422) {
                            const data = await res.json();
                            this.validationErrors = data.errors || {};
                            return;
                        }

                        if (!res.ok) throw new Error('Failed to save product');

                        this.showModal = false;
                        this.showSuccess(this.editingProduct ? 'Product updated successfully!' : 'Product created successfully!');
                        await this.fetchProducts();
                    } catch (e) {
                        console.error('Error saving product:', e);
                        this.showError('Failed to save product. Please try again.');
                    } finally {
                        this.saving = false;
                    }
                },

                confirmDelete(product) {
                    this.deletingProduct = product;
                    this.showDeleteModal = true;
                },

                async deleteProduct() {
                    if (!this.deletingProduct) return;
                    this.saving = true;
                    this.clearMessages();

                    const tokenElement = document.querySelector('meta[name="csrf-token"]');
                    const token = tokenElement ? tokenElement.content : '';

                    try {
                        const res = await fetch(`/api/products/${this.deletingProduct.id}`, {
                            method: 'DELETE',
                            headers: {
                                'Accept': 'application/json',
                                'X-CSRF-TOKEN': token,
                            },
                        });

                        if (!res.ok) throw new Error('Failed to delete product');

                        this.showDeleteModal = false;
                        this.deletingProduct = null;
                        this.showSuccess('Product deleted successfully!');
                        await this.fetchProducts();
                    } catch (e) {
                        console.error('Error deleting product:', e);
                        this.showError('Failed to delete product. Please try again.');
                    } finally {
                        this.saving = false;
                    }
                },

                async toggleArchive(product) {
                    const isArchiving = !product.is_archived;
                    const endpoint = isArchiving ? 'archive' : 'unarchive';
                    
                    const tokenElement = document.querySelector('meta[name="csrf-token"]');
                    const token = tokenElement ? tokenElement.content : '';

                    try {
                        const res = await fetch(`/api/products/${product.id}/${endpoint}`, {
                            method: 'PATCH',
                            headers: {
                                'Accept': 'application/json',
                                'X-CSRF-TOKEN': token,
                            },
                        });

                        if (!res.ok) throw new Error(`Failed to ${endpoint} product`);

                        product.is_archived = isArchiving;
                        this.showSuccess(isArchiving ? 'Product archived successfully!' : 'Product unarchived successfully!');
                        await this.fetchProducts();
                    } catch (e) {
                        console.error(`Error ${endpoint}ing product:`, e);
                        this.showError(`Failed to ${endpoint} product. Please try again.`);
                    }
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
        </div>
        </div>
        </div>
    @endpush
</x-app-layout>
