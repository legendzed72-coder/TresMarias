<x-app-layout>
    <x-slot name="header">
        <div class="flex items-center justify-between">
            <div>
                <h2 class="font-serif font-bold text-2xl text-bark-600 leading-tight">
                    {{ __('Manage Users') }}
                </h2>
                <p class="mt-1 text-sm text-muted">{{ __('Manage system users and permissions') }}</p>
            </div>
            <button @click="openCreateModal()"
                class="inline-flex items-center gap-2 px-5 py-2.5 bg-bark-300 hover:bg-bark-400 text-cream-50 font-semibold text-sm rounded-xl shadow-sm transition duration-200">
                <svg class="w-5 h-5" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M12 4v16m8-8H4"/>
                </svg>
                {{ __('Add User') }}
            </button>
        </div>
    </x-slot>

    <div class="flex">
        {{-- Sidebar --}}
        <x-admin-sidebar />

        {{-- Main Content --}}
        <div x-data="adminUsers()" x-init="init()" class="flex-1 py-8">
        <div class="max-w-6xl mx-auto px-4 sm:px-6 lg:px-8 space-y-6">

            {{-- KPI Summary --}}
            <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-5">
                <div class="bg-cream-50 rounded-2xl ring-1 ring-bark-200/10 shadow-sm shadow-bark-200/5 p-6">
                    <div class="flex items-center gap-4">
                        <div class="flex-shrink-0 w-12 h-12 rounded-xl bg-bark-100 flex items-center justify-center">
                            <svg class="w-6 h-6 text-bark-400" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" d="M15 19.128a9.38 9.38 0 002.625.372 9.337 9.337 0 004.121-.952 4.125 4.125 0 00-7.533-2.493M15 19.128v-.003c0-1.113-.285-2.16-.786-3.07M15 19.128v.106A12.318 12.318 0 018.624 21c-2.331 0-4.512-.645-6.374-1.766l-.001-.109a6.375 6.375 0 0111.964-3.07M12 6.375a3.375 3.375 0 11-6.75 0 3.375 3.375 0 016.75 0zm8.25 2.25a2.625 2.625 0 11-5.25 0 2.625 2.625 0 015.25 0z"/>
                            </svg>
                        </div>
                        <div>
                            <p class="text-sm font-medium text-muted">{{ __('Total Users') }}</p>
                            <p class="text-2xl font-bold text-bark-600">{{ $totalUsers }}</p>
                        </div>
                    </div>
                </div>

                <div class="bg-cream-50 rounded-2xl ring-1 ring-bark-200/10 shadow-sm shadow-bark-200/5 p-6">
                    <div class="flex items-center gap-4">
                        <div class="flex-shrink-0 w-12 h-12 rounded-xl bg-leaf-300/30 flex items-center justify-center">
                            <svg class="w-6 h-6 text-leaf-500" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" d="M13 10V3L4 14h7v7l9-11h-7z"/>
                            </svg>
                        </div>
                        <div>
                            <p class="text-sm font-medium text-muted">{{ __('Admins') }}</p>
                            <p class="text-2xl font-bold text-bark-600">{{ $adminUsers }}</p>
                        </div>
                    </div>
                </div>

                <div class="bg-cream-50 rounded-2xl ring-1 ring-bark-200/10 shadow-sm shadow-bark-200/5 p-6">
                    <div class="flex items-center gap-4">
                        <div class="flex-shrink-0 w-12 h-12 rounded-xl bg-gold-300/30 flex items-center justify-center">
                            <svg class="w-6 h-6 text-gold-500" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"/>
                            </svg>
                        </div>
                        <div>
                            <p class="text-sm font-medium text-muted">{{ __('Staff') }}</p>
                            <p class="text-2xl font-bold text-bark-600">{{ $staffUsers }}</p>
                        </div>
                    </div>
                </div>

                <div class="bg-cream-50 rounded-2xl ring-1 ring-bark-200/10 shadow-sm shadow-bark-200/5 p-6">
                    <div class="flex items-center gap-4">
                        <div class="flex-shrink-0 w-12 h-12 rounded-xl bg-bark-100 flex items-center justify-center">
                            <svg class="w-6 h-6 text-bark-400" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" d="M12 4.354a4 4 0 110 5.292M15 10H9"/>
                            </svg>
                        </div>
                        <div>
                            <p class="text-sm font-medium text-muted">{{ __('Customers') }}</p>
                            <p class="text-2xl font-bold text-bark-600">{{ $customerUsers }}</p>
                        </div>
                    </div>
                </div>
            </div>

            {{-- Users Table --}}
            <div class="bg-cream-50 rounded-2xl ring-1 ring-bark-200/10 shadow-sm shadow-bark-200/5 overflow-hidden">
                <div class="px-6 py-5 border-b border-bark-200/10 flex items-center justify-between">
                    <h3 class="font-serif font-bold text-lg text-bark-600">{{ __('All Users') }}</h3>
                    <input type="text" x-model="search" placeholder="{{ __('Search users...') }}"
                        class="px-4 py-2 rounded-xl border border-bark-200/20 bg-white text-sm text-bark-600 placeholder-bark-200 focus:outline-none focus:ring-2 focus:ring-bark-300/40 focus:border-bark-300 transition-all">
                </div>

                <div class="overflow-x-auto">
                    <table class="w-full text-sm">
                        <thead>
                            <tr class="bg-cream-200/40">
                                <th class="px-6 py-3 text-left font-semibold text-muted text-xs uppercase tracking-wider">{{ __('Name') }}</th>
                                <th class="px-6 py-3 text-left font-semibold text-muted text-xs uppercase tracking-wider">{{ __('Email') }}</th>
                                <th class="px-6 py-3 text-left font-semibold text-muted text-xs uppercase tracking-wider">{{ __('Role') }}</th>
                                <th class="px-6 py-3 text-left font-semibold text-muted text-xs uppercase tracking-wider">{{ __('Status') }}</th>
                                <th class="px-6 py-3 text-left font-semibold text-muted text-xs uppercase tracking-wider">{{ __('Joined') }}</th>
                                <th class="px-6 py-3 text-right font-semibold text-muted text-xs uppercase tracking-wider">{{ __('Actions') }}</th>
                            </tr>
                        </thead>
                        <tbody class="divide-y divide-bark-200/10">
                            <template x-for="user in filteredUsers" :key="user.id">
                                <tr class="hover:bg-cream-100/50 transition duration-150">
                                    <td class="px-6 py-3.5">
                                        <span class="font-semibold text-bark-600" x-text="user.name"></span>
                                    </td>
                                    <td class="px-6 py-3.5 text-muted text-sm">
                                        <span x-text="user.email"></span>
                                    </td>
                                    <td class="px-6 py-3.5">
                                        <span class="inline-flex px-2.5 py-1 rounded-full text-xs font-semibold"
                                              :class="user.role === 'admin' ? 'bg-red-100 text-red-600' : user.role === 'staff' ? 'bg-blue-100 text-blue-600' : 'bg-green-100 text-green-600'">
                                            <span x-text="user.role.charAt(0).toUpperCase() + user.role.slice(1)"></span>
                                        </span>
                                    </td>
                                    <td class="px-6 py-3.5">
                                        <span class="inline-flex px-2.5 py-1 rounded-full text-xs font-semibold bg-leaf-300/30 text-leaf-500">
                                            {{ __('Active') }}
                                        </span>
                                    </td>
                                    <td class="px-6 py-3.5 text-muted text-sm">
                                        <span x-text="formatDate(user.created_at)"></span>
                                    </td>
                                    <td class="px-6 py-3.5 text-right space-x-2">
                                        <button @click="openDeleteModal(user)"
                                            class="p-2 rounded-xl hover:bg-red-100/50 text-red-300 hover:text-red-500 transition duration-150 inline-flex"
                                            title="Delete user"
                                            :disabled="user.id === {{ auth()->user()->id }}">
                                            <svg class="w-4 h-4" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"/>
                                            </svg>
                                        </button>
                                    </td>
                                </tr>
                            </template>
                        </tbody>
                    </table>
                </div>

                <div x-show="users.length === 0 && !loading" class="text-center py-16">
                    <h3 class="font-serif font-bold text-bark-500 text-lg mb-2">{{ __('No users found') }}</h3>
                    <p class="text-muted">{{ __('Get started by creating your first user') }}</p>
                </div>
            </div>
        </div>
        </div>
        </div>
    </div>

    {{-- Create User Modal --}}
    <div x-show="showModal" x-transition @click.away="closeModal()" class="fixed inset-0 bg-black/50 z-50 flex items-center justify-center p-4">
        <div class="bg-white rounded-2xl shadow-xl max-w-md w-full">
            <div class="px-6 py-4 border-b border-bark-200/10">
                <h3 class="font-serif font-bold text-lg text-bark-600">{{ __('Create New User') }}</h3>
            </div>

            <form @submit.prevent="saveUser()" class="p-6 space-y-4">
                <div>
                    <label class="block text-sm font-semibold text-bark-600 mb-1">{{ __('Name') }}</label>
                    <input type="text" x-model="editingUser.name" required
                        class="w-full px-4 py-2 rounded-xl border border-bark-200/20 focus:ring-2 focus:ring-bark-300/40 focus:border-bark-300 transition-all">
                </div>

                <div>
                    <label class="block text-sm font-semibold text-bark-600 mb-1">{{ __('Email') }}</label>
                    <input type="email" x-model="editingUser.email" required
                        class="w-full px-4 py-2 rounded-xl border border-bark-200/20 focus:ring-2 focus:ring-bark-300/40 focus:border-bark-300 transition-all">
                </div>

                <div>
                    <label class="block text-sm font-semibold text-bark-600 mb-1">{{ __('Role') }}</label>
                    <select x-model="editingUser.role" required
                        class="w-full px-4 py-2 rounded-xl border border-bark-200/20 focus:ring-2 focus:ring-bark-300/40 focus:border-bark-300 transition-all">
                        <option value="">{{ __('Select a role') }}</option>
                        <option value="admin">{{ __('Admin') }}</option>
                        <option value="staff">{{ __('Staff') }}</option>
                        <option value="customer">{{ __('Customer') }}</option>
                    </select>
                </div>

                <div>
                    <label class="block text-sm font-semibold text-bark-600 mb-1">{{ __('Password') }}</label>
                    <input type="password" x-model="editingUser.password" required
                        class="w-full px-4 py-2 rounded-xl border border-bark-200/20 focus:ring-2 focus:ring-bark-300/40 focus:border-bark-300 transition-all">
                </div>

                <div class="flex gap-3 pt-4">
                    <button type="button" @click="closeModal()"
                        class="flex-1 px-4 py-2 rounded-xl border border-bark-200/20 text-bark-600 font-semibold hover:bg-cream-50 transition">
                        {{ __('Cancel') }}
                    </button>
                    <button type="submit" :disabled="loading"
                        class="flex-1 px-4 py-2 rounded-xl bg-bark-300 hover:bg-bark-400 disabled:opacity-50 text-cream-50 font-semibold transition">
                        <span x-show="!loading">{{ __('Create') }}</span>
                        <span x-show="loading">{{ __('Creating...') }}</span>
                    </button>
                </div>
            </form>
        </div>
    </div>

    {{-- Delete Confirmation Modal --}}
    <div x-show="showDeleteModal" x-transition @click.away="showDeleteModal = false" class="fixed inset-0 bg-black/50 z-50 flex items-center justify-center p-4">
        <div class="bg-white rounded-2xl shadow-xl max-w-md w-full">
            <div class="px-6 py-4 border-b border-red-200/20">
                <h3 class="font-serif font-bold text-lg text-red-600">{{ __('Delete User') }}</h3>
            </div>

            <div class="p-6">
                <p class="text-muted mb-4">
                    {{ __('Are you sure you want to delete') }} <strong x-text="deletingUser.name"></strong>?
                </p>
                <p class="text-xs text-red-500 mb-4">{{ __('This action cannot be undone.') }}</p>

                <div class="flex gap-3">
                    <button @click="showDeleteModal = false"
                        class="flex-1 px-4 py-2 rounded-xl border border-bark-200/20 text-bark-600 font-semibold hover:bg-cream-50 transition">
                        {{ __('Cancel') }}
                    </button>
                    <button @click="deleteUser()" :disabled="loading"
                        class="flex-1 px-4 py-2 rounded-xl bg-red-500 hover:bg-red-600 disabled:opacity-50 text-white font-semibold transition">
                        {{ __('Delete') }}
                    </button>
                </div>
            </div>
        </div>
    </div>

    @push('scripts')
    <script>
        function adminUsers() {
            return {
                users: [],
                search: '',
                loading: false,
                showModal: false,
                showDeleteModal: false,
                editingUser: { id: null, name: '', email: '', role: '', password: '' },
                deletingUser: null,

                async init() {
                    await this.fetchUsers();
                },

                async fetchUsers() {
                    this.loading = true;
                    try {
                        const res = await fetch('/api/users', {
                            headers: { 'Accept': 'application/json' }
                        });
                        if (!res.ok) throw new Error('Failed to fetch users');
                        this.users = await res.json();
                    } catch (e) {
                        console.error('Error:', e);
                    } finally {
                        this.loading = false;
                    }
                },

                get filteredUsers() {
                    return this.users.filter(u =>
                        u.name.toLowerCase().includes(this.search.toLowerCase()) ||
                        u.email.toLowerCase().includes(this.search.toLowerCase())
                    );
                },

                openCreateModal() {
                    this.editingUser = { id: null, name: '', email: '', role: 'customer', password: '' };
                    this.showModal = true;
                },

                closeModal() {
                    this.showModal = false;
                    this.editingUser = { id: null, name: '', email: '', role: '', password: '' };
                },

                async saveUser() {
                    if (!this.editingUser.name || !this.editingUser.email || !this.editingUser.role || !this.editingUser.password) {
                        alert('Please fill all required fields');
                        return;
                    }

                    this.loading = true;
                    try {
                        const payload = {
                            name: this.editingUser.name,
                            email: this.editingUser.email,
                            role: this.editingUser.role,
                            password: this.editingUser.password
                        };

                        const csrfToken = document.querySelector('meta[name="csrf-token"]')?.content || '';

                        const res = await fetch('/api/users', {
                            method: 'POST',
                            headers: {
                                'Content-Type': 'application/json',
                                'Accept': 'application/json',
                                'X-CSRF-TOKEN': csrfToken
                            },
                            body: JSON.stringify(payload)
                        });

                        const data = await res.json();
                        
                        if (!res.ok) {
                            const errorMsg = data.message || data.error || 'Failed to create user';
                            throw new Error(errorMsg);
                        }

                        await this.fetchUsers();
                        this.closeModal();
                        alert('User created successfully');
                    } catch (e) {
                        console.error('Error:', e);
                        alert(e.message || "{{ __('Error creating user') }}");
                    } finally {
                        this.loading = false;
                    }
                },

                openDeleteModal(user) {
                    this.deletingUser = user;
                    this.showDeleteModal = true;
                },

                async deleteUser() {
                    this.loading = true;
                    try {
                        const csrfToken = document.querySelector('meta[name="csrf-token"]')?.content || '';

                        const res = await fetch(`/api/users/${this.deletingUser.id}`, {
                            method: 'DELETE',
                            headers: {
                                'Accept': 'application/json',
                                'X-CSRF-TOKEN': csrfToken
                            }
                        });

                        const data = await res.json();
                        
                        if (!res.ok) {
                            const errorMsg = data.message || data.error || 'Failed to delete user';
                            throw new Error(errorMsg);
                        }

                        await this.fetchUsers();
                        this.showDeleteModal = false;
                        alert('User deleted successfully');
                    } catch (e) {
                        console.error('Error:', e);
                        alert(e.message || "{{ __('Error deleting user') }}");
                    } finally {
                        this.loading = false;
                    }
                },

                formatDate(date) {
                    return new Date(date).toLocaleDateString('en-PH', {
                        year: 'numeric',
                        month: 'short',
                        day: 'numeric'
                    });
                }
            };
        }
    </script>
    @endpush

</x-app-layout>
