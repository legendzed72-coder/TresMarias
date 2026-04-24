<x-app-layout>
    <x-slot name="header">
        <div class="flex items-center justify-between">
            <div>
                <h2 class="font-serif font-bold text-2xl text-bark-600 leading-tight">
                    {{ __('Manage Ingredients') }}
                </h2>
                <p class="mt-1 text-sm text-muted">{{ __('Track and manage your bakery ingredients and stock levels') }}</p>
            </div>
            <button @click="openCreateModal()"
                class="inline-flex items-center gap-2 px-5 py-2.5 bg-bark-300 hover:bg-bark-400 text-cream-50 font-semibold text-sm rounded-xl shadow-sm transition duration-200">
                <svg class="w-5 h-5" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M12 4v16m8-8H4"/>
                </svg>
                {{ __('Add Ingredient') }}
            </button>
        </div>
    </x-slot>

    <div class="flex">
        {{-- Sidebar --}}
        <x-admin-sidebar />

        {{-- Main Content --}}
        <div x-data="adminIngredients()" x-init="init()" class="flex-1 py-8">
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

            {{-- Search --}}
            <div class="flex flex-col sm:flex-row gap-4">
                <div class="relative flex-1 max-w-md">
                    <svg class="absolute left-4 top-1/2 -translate-y-1/2 w-5 h-5 text-bark-200" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"/>
                    </svg>
                    <input type="text" x-model="search" placeholder="Search ingredients..."
                        class="w-full pl-12 pr-4 py-3 rounded-2xl border border-bark-200/20 bg-cream-50 text-bark-600 placeholder-bark-200 focus:outline-none focus:ring-2 focus:ring-bark-300/40 focus:border-bark-300 transition-all font-medium text-sm shadow-sm">
                </div>
                <select x-model="filterStock"
                    class="px-4 py-3 rounded-2xl border border-bark-200/20 bg-cream-50 text-bark-600 focus:outline-none focus:ring-2 focus:ring-bark-300/40 focus:border-bark-300 transition-all font-medium text-sm shadow-sm">
                    <option value="">All Stock Levels</option>
                    <option value="low">Low Stock</option>
                    <option value="ok">In Stock</option>
                </select>
            </div>

            {{-- Stats Row --}}
            <div class="grid grid-cols-2 sm:grid-cols-4 gap-4">
                <div class="bg-cream-50 rounded-2xl ring-1 ring-bark-200/10 p-4 text-center">
                    <p class="text-xs font-semibold text-muted uppercase tracking-wider">Total</p>
                    <p class="text-2xl font-bold text-bark-600 mt-1" x-text="ingredients.length"></p>
                </div>
                <div class="bg-cream-50 rounded-2xl ring-1 ring-bark-200/10 p-4 text-center">
                    <p class="text-xs font-semibold text-muted uppercase tracking-wider">In Stock</p>
                    <p class="text-2xl font-bold text-leaf-500 mt-1" x-text="ingredients.filter(i => !i.is_low_stock).length"></p>
                </div>
                <div class="bg-cream-50 rounded-2xl ring-1 ring-bark-200/10 p-4 text-center">
                    <p class="text-xs font-semibold text-muted uppercase tracking-wider">Low Stock</p>
                    <p class="text-2xl font-bold text-gold-500 mt-1" x-text="ingredients.filter(i => i.is_low_stock && parseFloat(i.stock_quantity) > 0).length"></p>
                </div>
                <div class="bg-cream-50 rounded-2xl ring-1 ring-bark-200/10 p-4 text-center">
                    <p class="text-xs font-semibold text-muted uppercase tracking-wider">Out of Stock</p>
                    <p class="text-2xl font-bold text-red-500 mt-1" x-text="ingredients.filter(i => parseFloat(i.stock_quantity) === 0).length"></p>
                </div>
            </div>

            {{-- Loading --}}
            <div x-show="loading" x-transition class="flex justify-center py-16">
                <div class="flex flex-col items-center gap-4">
                    <svg class="animate-spin h-10 w-10 text-bark-300" fill="none" viewBox="0 0 24 24">
                        <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle>
                        <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4z"></path>
                    </svg>
                    <span class="text-bark-200 font-medium text-sm">Loading ingredients...</span>
                </div>
            </div>

            {{-- Ingredients Table --}}
            <div x-show="!loading" x-cloak class="bg-cream-50 rounded-2xl ring-1 ring-bark-200/10 shadow-sm shadow-bark-200/5 overflow-hidden">
                <div class="overflow-x-auto">
                    <table class="w-full text-left">
                        <thead>
                            <tr class="border-b border-bark-200/10">
                                <th class="px-6 py-4 text-xs font-bold text-bark-400 uppercase tracking-wider">Name</th>
                                <th class="px-6 py-4 text-xs font-bold text-bark-400 uppercase tracking-wider">Unit</th>
                                <th class="px-6 py-4 text-xs font-bold text-bark-400 uppercase tracking-wider">Stock</th>
                                <th class="px-6 py-4 text-xs font-bold text-bark-400 uppercase tracking-wider hidden sm:table-cell">Min Level</th>
                                <th class="px-6 py-4 text-xs font-bold text-bark-400 uppercase tracking-wider hidden md:table-cell">Cost/Unit</th>
                                <th class="px-6 py-4 text-xs font-bold text-bark-400 uppercase tracking-wider hidden lg:table-cell">Supplier</th>
                                <th class="px-6 py-4 text-xs font-bold text-bark-400 uppercase tracking-wider text-right">Actions</th>
                            </tr>
                        </thead>
                        <tbody class="divide-y divide-bark-200/10">
                            <template x-for="ingredient in filteredIngredients" :key="ingredient.id">
                                <tr class="hover:bg-cream-100/50 transition duration-150">
                                    <td class="px-6 py-4">
                                        <span class="font-semibold text-bark-600 text-sm" x-text="ingredient.name"></span>
                                    </td>
                                    <td class="px-6 py-4">
                                        <span class="inline-flex px-2.5 py-1 bg-cream-200/60 text-bark-500 text-xs font-semibold rounded-lg"
                                              x-text="ingredient.unit_type || '—'"></span>
                                    </td>
                                    <td class="px-6 py-4">
                                        <div class="flex items-center gap-2">
                                            <span class="w-2 h-2 rounded-full flex-shrink-0"
                                                  :class="parseFloat(ingredient.stock_quantity) === 0 ? 'bg-red-500' : ingredient.is_low_stock ? 'bg-gold-400' : 'bg-leaf-400'"></span>
                                            <span class="text-sm font-semibold"
                                                  :class="parseFloat(ingredient.stock_quantity) === 0 ? 'text-red-500' : ingredient.is_low_stock ? 'text-gold-500' : 'text-bark-600'"
                                                  x-text="parseFloat(ingredient.stock_quantity).toFixed(1)"></span>
                                        </div>
                                    </td>
                                    <td class="px-6 py-4 hidden sm:table-cell">
                                        <span class="text-muted text-sm" x-text="parseFloat(ingredient.min_stock_level).toFixed(1)"></span>
                                    </td>
                                    <td class="px-6 py-4 hidden md:table-cell">
                                        <span class="text-muted text-sm" x-text="ingredient.cost_per_unit ? '₱' + parseFloat(ingredient.cost_per_unit).toFixed(2) : '—'"></span>
                                    </td>
                                    <td class="px-6 py-4 hidden lg:table-cell">
                                        <span class="text-muted text-sm" x-text="ingredient.supplier || '—'"></span>
                                    </td>
                                    <td class="px-6 py-4 text-right">
                                        <div class="flex items-center justify-end gap-2">
                                            <button @click="openEditModal(ingredient)"
                                                class="p-2 rounded-xl hover:bg-cream-200/50 text-bark-300 hover:text-bark-500 transition duration-150"
                                                title="Edit">
                                                <svg class="w-4 h-4" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                                                    <path stroke-linecap="round" stroke-linejoin="round" d="M16.862 4.487l1.687-1.688a1.875 1.875 0 112.652 2.652L10.582 16.07a4.5 4.5 0 01-1.897 1.13L6 18l.8-2.685a4.5 4.5 0 011.13-1.897l8.932-8.931zm0 0L19.5 7.125M18 14v4.75A2.25 2.25 0 0115.75 21H5.25A2.25 2.25 0 013 18.75V8.25A2.25 2.25 0 015.25 6H10"/>
                                                </svg>
                                            </button>
                                            <button @click="confirmDelete(ingredient)"
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
                <div x-show="filteredIngredients.length === 0 && !loading" class="text-center py-16">
                    <div class="text-5xl mb-4">🧂</div>
                    <h3 class="font-serif font-bold text-bark-500 text-lg mb-2">No ingredients found</h3>
                    <p class="text-muted text-sm mb-4">Try adjusting your search or add a new ingredient.</p>
                    <button @click="openCreateModal()"
                        class="inline-flex items-center gap-2 px-5 py-2.5 bg-bark-300 hover:bg-bark-400 text-cream-50 font-semibold text-sm rounded-xl shadow-sm transition duration-200">
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M12 4v16m8-8H4"/>
                        </svg>
                        Add Ingredient
                    </button>
                </div>
            </div>
        </div>

        {{-- Create/Edit Ingredient Modal --}}
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
                 class="relative bg-cream-50 rounded-3xl w-full max-w-lg max-h-[90vh] overflow-y-auto shadow-2xl">

                <div class="sticky top-0 bg-cream-50 px-6 py-5 border-b border-bark-200/10 flex items-center justify-between z-10 rounded-t-3xl">
                    <h3 class="font-serif font-bold text-xl text-bark-600"
                        x-text="editingIngredient ? 'Edit Ingredient' : 'Add New Ingredient'"></h3>
                    <button @click="showModal = false"
                        class="w-8 h-8 flex items-center justify-center rounded-full hover:bg-cream-200/50 text-bark-300 hover:text-bark-500 transition">
                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/>
                        </svg>
                    </button>
                </div>

                <form @submit.prevent="saveIngredient()" class="p-6 space-y-5">
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
                        <div class="sm:col-span-2">
                            <label class="block text-sm font-semibold text-bark-500 mb-1.5">Ingredient Name *</label>
                            <input type="text" x-model="form.name" required
                                class="w-full px-4 py-3 rounded-2xl border border-bark-200/20 bg-white text-bark-600 placeholder-bark-200 focus:outline-none focus:ring-2 focus:ring-bark-300/40 focus:border-bark-300 transition-all text-sm"
                                placeholder="e.g. All-Purpose Flour">
                        </div>

                        <div>
                            <label class="block text-sm font-semibold text-bark-500 mb-1.5">Unit Type *</label>
                            <select x-model="form.unit_type" required
                                class="w-full px-4 py-3 rounded-2xl border border-bark-200/20 bg-white text-bark-600 focus:outline-none focus:ring-2 focus:ring-bark-300/40 focus:border-bark-300 transition-all text-sm">
                                <option value="">Select unit</option>
                                <option value="kg">Kilogram (kg)</option>
                                <option value="g">Gram (g)</option>
                                <option value="L">Liter (L)</option>
                                <option value="mL">Milliliter (mL)</option>
                                <option value="piece">Piece</option>
                                <option value="pack">Pack</option>
                                <option value="dozen">Dozen</option>
                                <option value="cup">Cup</option>
                                <option value="tbsp">Tablespoon</option>
                                <option value="tsp">Teaspoon</option>
                            </select>
                        </div>

                        <div>
                            <label class="block text-sm font-semibold text-bark-500 mb-1.5">Stock Quantity *</label>
                            <input type="number" x-model="form.stock_quantity" step="0.001" min="0" required
                                class="w-full px-4 py-3 rounded-2xl border border-bark-200/20 bg-white text-bark-600 placeholder-bark-200 focus:outline-none focus:ring-2 focus:ring-bark-300/40 focus:border-bark-300 transition-all text-sm"
                                placeholder="0.000">
                        </div>

                        <div>
                            <label class="block text-sm font-semibold text-bark-500 mb-1.5">Min Stock Level</label>
                            <input type="number" x-model="form.min_stock_level" step="0.001" min="0"
                                class="w-full px-4 py-3 rounded-2xl border border-bark-200/20 bg-white text-bark-600 placeholder-bark-200 focus:outline-none focus:ring-2 focus:ring-bark-300/40 focus:border-bark-300 transition-all text-sm"
                                placeholder="10.000">
                        </div>

                        <div>
                            <label class="block text-sm font-semibold text-bark-500 mb-1.5">Cost per Unit (₱)</label>
                            <input type="number" x-model="form.cost_per_unit" step="0.01" min="0"
                                class="w-full px-4 py-3 rounded-2xl border border-bark-200/20 bg-white text-bark-600 placeholder-bark-200 focus:outline-none focus:ring-2 focus:ring-bark-300/40 focus:border-bark-300 transition-all text-sm"
                                placeholder="0.00">
                        </div>

                        <div class="sm:col-span-2">
                            <label class="block text-sm font-semibold text-bark-500 mb-1.5">Supplier</label>
                            <input type="text" x-model="form.supplier"
                                class="w-full px-4 py-3 rounded-2xl border border-bark-200/20 bg-white text-bark-600 placeholder-bark-200 focus:outline-none focus:ring-2 focus:ring-bark-300/40 focus:border-bark-300 transition-all text-sm"
                                placeholder="e.g. Local flour mill">
                        </div>
                    </div>

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
                            <span x-text="saving ? 'Saving...' : (editingIngredient ? 'Update Ingredient' : 'Create Ingredient')"></span>
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
                <h3 class="font-serif font-bold text-lg text-bark-600 mb-2">Delete Ingredient</h3>
                <p class="text-muted text-sm mb-6">Are you sure you want to delete <strong class="text-bark-600" x-text="deletingIngredient && deletingIngredient.name ? deletingIngredient.name : ''"></strong>? This action cannot be undone.</p>
                <div class="flex items-center justify-center gap-3">
                    <button @click="showDeleteModal = false"
                        class="px-5 py-2.5 text-bark-500 font-semibold text-sm rounded-xl hover:bg-cream-200/50 transition duration-200">
                        Cancel
                    </button>
                    <button @click="deleteIngredient()" :disabled="saving"
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
        function adminIngredients() {
            return {
                ingredients: [],
                search: '',
                filterStock: '',
                loading: true,
                saving: false,
                showModal: false,
                showDeleteModal: false,
                editingIngredient: null,
                deletingIngredient: null,
                successMsg: '',
                errorMsg: '',
                validationErrors: {},
                form: {
                    name: '',
                    unit_type: '',
                    stock_quantity: 0,
                    min_stock_level: 10,
                    cost_per_unit: '',
                    supplier: '',
                },

                async init() {
                    await this.fetchIngredients();
                },

                async fetchIngredients() {
                    this.loading = true;
                    try {
                        const res = await fetch('/api/admin/ingredients', {
                            headers: { 'Accept': 'application/json' },
                            credentials: 'same-origin',
                        });
                        if (!res.ok) throw new Error('Failed to load ingredients');
                        const data = await res.json();
                        this.ingredients = data.data || data || [];
                    } catch (e) {
                        console.error('Error loading ingredients:', e);
                        this.showError('Failed to load ingredients. Please refresh the page.');
                    } finally {
                        this.loading = false;
                    }
                },

                get filteredIngredients() {
                    return this.ingredients.filter(i => {
                        const matchSearch = !this.search ||
                            i.name.toLowerCase().includes(this.search.toLowerCase()) ||
                            (i.supplier && i.supplier.toLowerCase().includes(this.search.toLowerCase()));
                        const matchStock = !this.filterStock ||
                            (this.filterStock === 'low' && i.is_low_stock) ||
                            (this.filterStock === 'ok' && !i.is_low_stock);
                        return matchSearch && matchStock;
                    });
                },

                resetForm() {
                    this.form = {
                        name: '',
                        unit_type: '',
                        stock_quantity: 0,
                        min_stock_level: 10,
                        cost_per_unit: '',
                        supplier: '',
                    };
                    this.validationErrors = {};
                },

                openCreateModal() {
                    this.editingIngredient = null;
                    this.resetForm();
                    this.showModal = true;
                },

                openEditModal(ingredient) {
                    this.editingIngredient = ingredient;
                    this.form = {
                        name: ingredient.name,
                        unit_type: ingredient.unit_type || '',
                        stock_quantity: ingredient.stock_quantity,
                        min_stock_level: ingredient.min_stock_level,
                        cost_per_unit: ingredient.cost_per_unit || '',
                        supplier: ingredient.supplier || '',
                    };
                    this.validationErrors = {};
                    this.showModal = true;
                },

                async saveIngredient() {
                    this.saving = true;
                    this.validationErrors = {};
                    this.clearMessages();

                    const url = this.editingIngredient
                        ? `/api/ingredients/${this.editingIngredient.id}`
                        : '/api/ingredients';
                    const method = this.editingIngredient ? 'PUT' : 'POST';
                    const tokenElement = document.querySelector('meta[name="csrf-token"]');
                    const token = tokenElement ? tokenElement.content : '';

                    try {
                        const res = await fetch(url, {
                            method,
                            headers: {
                                'Content-Type': 'application/json',
                                'Accept': 'application/json',
                                'X-CSRF-TOKEN': token,
                            },
                            body: JSON.stringify(this.form),
                        });

                        if (res.status === 422) {
                            const data = await res.json();
                            this.validationErrors = data.errors || {};
                            return;
                        }

                        if (!res.ok) throw new Error('Failed to save ingredient');

                        this.showModal = false;
                        this.showSuccess(this.editingIngredient ? 'Ingredient updated successfully!' : 'Ingredient created successfully!');
                        await this.fetchIngredients();
                    } catch (e) {
                        console.error('Error saving ingredient:', e);
                        this.showError('Failed to save ingredient. Please try again.');
                    } finally {
                        this.saving = false;
                    }
                },

                confirmDelete(ingredient) {
                    this.deletingIngredient = ingredient;
                    this.showDeleteModal = true;
                },

                async deleteIngredient() {
                    if (!this.deletingIngredient) return;
                    this.saving = true;
                    this.clearMessages();

                    const tokenElement = document.querySelector('meta[name="csrf-token"]');
                    const token = tokenElement ? tokenElement.content : '';

                    try {
                        const res = await fetch(`/api/ingredients/${this.deletingIngredient.id}`, {
                            method: 'DELETE',
                            headers: {
                                'Accept': 'application/json',
                                'X-CSRF-TOKEN': token,
                            },
                        });

                        if (!res.ok) throw new Error('Failed to delete ingredient');

                        this.showDeleteModal = false;
                        this.deletingIngredient = null;
                        this.showSuccess('Ingredient deleted successfully!');
                        await this.fetchIngredients();
                    } catch (e) {
                        console.error('Error deleting ingredient:', e);
                        this.showError('Failed to delete ingredient. Please try again.');
                    } finally {
                        this.saving = false;
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
