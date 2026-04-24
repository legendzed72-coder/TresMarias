<x-app-layout>
    <div class="flex" x-data="staffManagement()" @init="init()" x-cloak>
        <x-admin-sidebar />
        
        <div class="flex-1 bg-cream-50">
            <div class="max-w-7xl mx-auto px-6 py-8">
                {{-- Header --}}
                <div class="flex justify-between items-center mb-8">
                    <div>
                        <h1 class="text-3xl font-bold text-bark-900">{{ __('Staff Management') }}</h1>
                        <p class="text-bark-600 mt-1">{{ __('Manage staff and admin accounts') }}</p>
                    </div>
                    <button 
                        @click="new_staff_form = !new_staff_form"
                        class="px-6 py-3 bg-green-500 hover:bg-green-600 text-white rounded-lg font-semibold transition shadow-sm flex items-center gap-2"
                    >
                        <span>➕</span> {{ __('Add Staff') }}
                    </button>
                </div>

                {{-- KPI Cards --}}
                <div class="grid grid-cols-1 md:grid-cols-3 gap-4 mb-8">
                    <div class="bg-white rounded-xl border border-cream-200 p-6 shadow-sm">
                        <div class="flex items-center justify-between">
                            <div>
                                <p class="text-sm text-bark-600">{{ __('Total Staff') }}</p>
                                <p class="text-2xl font-bold text-bark-900 mt-2" id="totalStaff">0</p>
                            </div>
                            <svg class="w-10 h-10 text-blue-500" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" d="M12 4.354a4 4 0 110 5.292M15 21H3.75A2.25 2.25 0 011.5 18.75V2.25A2.25 2.25 0 013.75 0h16.5A2.25 2.25 0 0122.5 2.25v16.5A2.25 2.25 0 0120.25 21"/>
                            </svg>
                        </div>
                    </div>

                    <div class="bg-white rounded-xl border border-cream-200 p-6 shadow-sm">
                        <div class="flex items-center justify-between">
                            <div>
                                <p class="text-sm text-bark-600">{{ __('Admins') }}</p>
                                <p class="text-2xl font-bold text-bark-900 mt-2" id="totalAdmins">0</p>
                            </div>
                            <svg class="w-10 h-10 text-purple-500" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" d="M9 12l2 2 4-4m7.528-4.528a9 9 0 11-18.528 0m18.528 0A9 9 0 003 12m18 0a9 9 0 01-9 9m9-9a9 9 0 00-9-9m9 9H3"/>
                            </svg>
                        </div>
                    </div>

                    <div class="bg-white rounded-xl border border-cream-200 p-6 shadow-sm">
                        <div class="flex items-center justify-between">
                            <div>
                                <p class="text-sm text-bark-600">{{ __('Delivery Drivers') }}</p>
                                <p class="text-2xl font-bold text-bark-900 mt-2" id="totalDrivers">0</p>
                            </div>
                            <svg class="w-10 h-10 text-green-500" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" d="M13 10V3L4 14h7v7l9-11h-7z"/>
                            </svg>
                        </div>
                    </div>
                </div>

                {{-- Create Staff Form --}}
                <div x-show="new_staff_form" x-transition:enter="transition ease-out duration-200"
                     x-transition:enter-start="opacity-0 transform scale-95"
                     x-transition:enter-end="opacity-100 transform scale-100"
                     class="bg-white rounded-xl border border-cream-200 p-8 mb-8 shadow-sm">
                    <h2 class="text-2xl font-bold text-bark-900 mb-6">{{ __('Create New Staff Account') }}</h2>
                    
                    <form @submit.prevent="createStaff()" class="space-y-6">
                        <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                            <div>
                                <label class="block text-sm font-semibold text-bark-900 mb-2">{{ __('Full Name') }} *</label>
                                <input 
                                    type="text" 
                                    x-model="form.name"
                                    placeholder="Enter staff name"
                                    required
                                    class="w-full px-4 py-2 border border-cream-300 rounded-lg focus:ring-2 focus:ring-bark-300 focus:border-transparent"
                                >
                            </div>

                            <div>
                                <label class="block text-sm font-semibold text-bark-900 mb-2">{{ __('Email') }} *</label>
                                <input 
                                    type="email" 
                                    x-model="form.email"
                                    placeholder="email@example.com"
                                    required
                                    class="w-full px-4 py-2 border border-cream-300 rounded-lg focus:ring-2 focus:ring-bark-300 focus:border-transparent"
                                >
                            </div>

                            <div>
                                <label class="block text-sm font-semibold text-bark-900 mb-2">{{ __('Password') }} *</label>
                                <input 
                                    type="password" 
                                    x-model="form.password"
                                    placeholder="Enter password"
                                    required
                                    class="w-full px-4 py-2 border border-cream-300 rounded-lg focus:ring-2 focus:ring-bark-300 focus:border-transparent"
                                >
                            </div>

                            <div>
                                <label class="block text-sm font-semibold text-bark-900 mb-2">{{ __('Confirm Password') }} *</label>
                                <input 
                                    type="password" 
                                    x-model="form.password_confirmation"
                                    placeholder="Confirm password"
                                    required
                                    class="w-full px-4 py-2 border border-cream-300 rounded-lg focus:ring-2 focus:ring-bark-300 focus:border-transparent"
                                >
                            </div>

                            <div>
                                <label class="block text-sm font-semibold text-bark-900 mb-2">{{ __('Role') }} *</label>
                                <select x-model="form.role" required class="w-full px-4 py-2 border border-cream-300 rounded-lg focus:ring-2 focus:ring-bark-300 focus:border-transparent">
                                    <option value="">{{ __('Select Role...') }}</option>
                                    <option value="staff">👤 {{ __('Staff') }}</option>
                                    <option value="admin">⚙️ {{ __('Admin') }}</option>
                                </select>
                            </div>

                            <div>
                                <label class="block text-sm font-semibold text-bark-900 mb-2">{{ __('Assignment Type') }}</label>
                                <select x-model="form.assignment_type" class="w-full px-4 py-2 border border-cream-300 rounded-lg focus:ring-2 focus:ring-bark-300 focus:border-transparent">
                                    <option value="general">{{ __('General Staff') }}</option>
                                    <option value="delivery_driver">🚚 {{ __('Delivery Driver') }}</option>
                                    <option value="pos_operator">🏪 {{ __('POS Operator') }}</option>
                                </select>
                            </div>
                        </div>

                        <div class="flex items-center gap-4 pt-4 border-t border-cream-200">
                            <button 
                                type="button"
                                @click="new_staff_form = false; resetForm();"
                                class="px-6 py-2 text-bark-600 hover:bg-cream-100 rounded-lg transition font-semibold"
                            >
                                {{ __('Cancel') }}
                            </button>
                            <button 
                                type="submit"
                                :disabled="submitting"
                                class="px-6 py-2 bg-green-500 hover:bg-green-600 disabled:opacity-50 text-white rounded-lg transition font-semibold"
                            >
                                <span x-text="submitting ? '{{ __('Creating...') }}' : '{{ __('Create Account') }}'"></span>
                            </button>
                        </div>
                    </form>
                </div>

                {{-- Filters --}}
                <div class="bg-white rounded-xl border border-cream-200 p-6 mb-8 shadow-sm">
                    <div class="grid grid-cols-1 md:grid-cols-3 gap-4">
                        <input 
                            type="text" 
                            x-model="search"
                            placeholder="{{ __('Search by name or email...') }}"
                            class="px-4 py-2 border border-cream-300 rounded-lg focus:ring-2 focus:ring-bark-300 focus:border-transparent"
                        >
                        <select x-model="roleFilter" class="px-4 py-2 border border-cream-300 rounded-lg focus:ring-2 focus:ring-bark-300 focus:border-transparent">
                            <option value="">{{ __('All Roles') }}</option>
                            <option value="staff">👤 {{ __('Staff') }}</option>
                            <option value="admin">⚙️ {{ __('Admin') }}</option>
                        </select>
                        <button 
                            @click="fetchStaff()"
                            class="px-4 py-2 bg-bark-300 text-cream-50 rounded-lg hover:bg-bark-400 transition font-semibold"
                        >
                            {{ __('Search') }}
                        </button>
                    </div>
                </div>

                {{-- Staff Table --}}
                <div class="bg-white rounded-xl border border-cream-200 overflow-hidden shadow-sm">
                    <div class="overflow-x-auto">
                        <table class="w-full">
                            <thead class="bg-cream-100 border-b border-cream-200">
                                <tr class="text-left text-sm font-semibold text-bark-900">
                                    <th class="px-6 py-4">{{ __('Name') }}</th>
                                    <th class="px-6 py-4">{{ __('Email') }}</th>
                                    <th class="px-6 py-4">{{ __('Role') }}</th>
                                    <th class="px-6 py-4">{{ __('Type') }}</th>
                                    <th class="px-6 py-4">{{ __('Created') }}</th>
                                    <th class="px-6 py-4">{{ __('Actions') }}</th>
                                </tr>
                            </thead>
                            <tbody class="divide-y divide-cream-200" id="staffTableBody">
                                <tr>
                                    <td colspan="6" class="px-6 py-8 text-center text-bark-600">
                                        {{ __('Loading staff...') }}
                                    </td>
                                </tr>
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </div>

    {{-- Edit Modal --}}
    <div id="editModal" class="fixed inset-0 bg-black/50 hidden items-center justify-center z-50">
        <div class="bg-white rounded-xl shadow-lg w-full max-w-md mx-4">
            <div class="p-6 border-b border-cream-200">
                <h2 class="text-xl font-bold text-bark-900">{{ __('Edit Staff Account') }}</h2>
            </div>
            <div class="p-6 space-y-4">
                <div>
                    <label class="block text-sm font-semibold text-bark-900 mb-2">{{ __('Name') }}</label>
                    <input 
                        type="text" 
                        id="editName"
                        class="w-full px-4 py-2 border border-cream-300 rounded-lg focus:ring-2 focus:ring-bark-300 focus:border-transparent"
                    >
                </div>
                <div>
                    <label class="block text-sm font-semibold text-bark-900 mb-2">{{ __('Email') }}</label>
                    <input 
                        type="email" 
                        id="editEmail"
                        class="w-full px-4 py-2 border border-cream-300 rounded-lg focus:ring-2 focus:ring-bark-300 focus:border-transparent"
                    >
                </div>
                <div>
                    <label class="block text-sm font-semibold text-bark-900 mb-2">{{ __('Role') }}</label>
                    <select id="editRole" class="w-full px-4 py-2 border border-cream-300 rounded-lg focus:ring-2 focus:ring-bark-300 focus:border-transparent">
                        <option value="staff">👤 {{ __('Staff') }}</option>
                        <option value="admin">⚙️ {{ __('Admin') }}</option>
                    </select>
                </div>
            </div>
            <div class="p-6 border-t border-cream-200 flex justify-end gap-3">
                <button 
                    onclick="document.getElementById('editModal').classList.add('hidden')"
                    class="px-4 py-2 text-bark-600 hover:bg-cream-100 rounded-lg transition"
                >
                    {{ __('Cancel') }}
                </button>
                <button 
                    onclick="staffApp.updateStaff()"
                    class="px-4 py-2 bg-blue-500 text-white rounded-lg hover:bg-blue-600 transition font-semibold"
                >
                    {{ __('Update') }}
                </button>
            </div>
        </div>
    </div>

    {{-- Delete Confirmation --}}
    <div id="deleteModal" class="fixed inset-0 bg-black/50 hidden items-center justify-center z-50">
        <div class="bg-white rounded-xl shadow-lg w-full max-w-md mx-4 p-6">
            <h2 class="text-xl font-bold text-red-600 mb-4">{{ __('Delete Staff Account?') }}</h2>
            <p class="text-bark-600 mb-6">{{ __('Are you sure? This action cannot be undone.') }}</p>
            <div class="flex justify-end gap-3">
                <button 
                    onclick="document.getElementById('deleteModal').classList.add('hidden')"
                    class="px-4 py-2 text-bark-600 hover:bg-cream-100 rounded-lg transition"
                >
                    {{ __('Cancel') }}
                </button>
                <button 
                    onclick="staffApp.confirmDelete()"
                    class="px-4 py-2 bg-red-500 text-white rounded-lg hover:bg-red-600 transition font-semibold"
                >
                    {{ __('Delete') }}
                </button>
            </div>
        </div>
    </div>

    @push('scripts')
    <script>
        let staffApp;

        function staffManagement() {
            return {
                staff: [],
                search: '',
                roleFilter: '',
                new_staff_form: false,
                submitting: false,
                editing_id: null,
                deleting_id: null,
                form: {
                    name: '',
                    email: '',
                    password: '',
                    password_confirmation: '',
                    role: 'staff',
                    assignment_type: 'general',
                },

                async init() {
                    staffApp = this;
                    await this.fetchStaff();
                    this.updateStats();
                },

                async fetchStaff() {
                    try {
                        const params = new URLSearchParams();
                        if (this.search) params.append('search', this.search);
                        if (this.roleFilter) params.append('role', this.roleFilter);

                        const res = await fetch(`/api/admin/staff-list?${params}`, {
                            headers: { 'Accept': 'application/json' },
                            credentials: 'same-origin',
                        });
                        
                        if (!res.ok) throw new Error('Failed to load staff');
                        const data = await res.json();
                        this.staff = data || [];
                        this.renderTable();
                        this.updateStats();
                    } catch (e) {
                        console.error('Error loading staff:', e);
                        alert('{{ __("Failed to load staff") }}');
                    }
                },

                updateStats() {
                    const admins = this.staff.filter(s => s.role === 'admin').length;
                    const drivers = this.staff.filter(s => s.assignment_type === 'delivery_driver').length;
                    
                    document.getElementById('totalStaff').textContent = this.staff.length;
                    document.getElementById('totalAdmins').textContent = admins;
                    document.getElementById('totalDrivers').textContent = drivers;
                },

                renderTable() {
                    const tbody = document.getElementById('staffTableBody');
                    if (this.staff.length === 0) {
                        tbody.innerHTML = `
                            <tr>
                                <td colspan="6" class="px-6 py-8 text-center text-bark-600">
                                    {{ __('No staff found') }}
                                </td>
                            </tr>
                        `;
                        return;
                    }

                    tbody.innerHTML = this.staff.map(s => {
                        const roleIcon = s.role === 'admin' ? '⚙️' : '👤';
                        const typeEmoji = {
                            'delivery_driver': '🚚',
                            'pos_operator': '🏪',
                            'general': '👤'
                        }[s.assignment_type] || '';

                        return `
                            <tr class="hover:bg-cream-50 transition">
                                <td class="px-6 py-4 font-semibold text-bark-900">${s.name}</td>
                                <td class="px-6 py-4 text-sm text-bark-600">${s.email}</td>
                                <td class="px-6 py-4">
                                    <span class="inline-flex items-center gap-1 px-3 py-1 rounded-full text-xs font-semibold ${s.role === 'admin' ? 'bg-purple-100 text-purple-800' : 'bg-blue-100 text-blue-800'}">
                                        ${roleIcon} ${s.role.charAt(0).toUpperCase() + s.role.slice(1)}
                                    </span>
                                </td>
                                <td class="px-6 py-4 text-sm">${typeEmoji} ${s.assignment_type ? s.assignment_type.replace(/_/g, ' ').toUpperCase() : 'N/A'}</td>
                                <td class="px-6 py-4 text-sm text-bark-600">${new Date(s.created_at).toLocaleDateString()}</td>
                                <td class="px-6 py-4">
                                    <button onclick="staffApp.openEdit(${s.id})" class="text-blue-600 hover:text-blue-800 text-sm font-semibold mr-3">
                                        {{ __('Edit') }}
                                    </button>
                                    <button onclick="staffApp.openDelete(${s.id})" class="text-red-600 hover:text-red-800 text-sm font-semibold">
                                        {{ __('Delete') }}
                                    </button>
                                </td>
                            </tr>
                        `;
                    }).join('');
                },

                async createStaff() {
                    if (this.form.password !== this.form.password_confirmation) {
                        alert('{{ __("Passwords do not match") }}');
                        return;
                    }

                    this.submitting = true;
                    try {
                        const res = await fetch('/api/admin/staff', {
                            method: 'POST',
                            headers: {
                                'Content-Type': 'application/json',
                                'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content')
                            },
                            credentials: 'same-origin',
                            body: JSON.stringify(this.form),
                        });

                        const data = await res.json();
                        
                        if (res.ok) {
                            alert('{{ __("Staff account created successfully!") }}');
                            this.new_staff_form = false;
                            this.resetForm();
                            await this.fetchStaff();
                        } else {
                            alert('Error: ' + (data.message || '{{ __("Failed to create staff") }}'));
                        }
                    } catch (e) {
                        console.error('Error creating staff:', e);
                        alert('{{ __("An error occurred") }}');
                    } finally {
                        this.submitting = false;
                    }
                },

                openEdit(staffId) {
                    const staff = this.staff.find(s => s.id === staffId);
                    if (!staff) return;

                    this.editing_id = staffId;
                    document.getElementById('editName').value = staff.name;
                    document.getElementById('editEmail').value = staff.email;
                    document.getElementById('editRole').value = staff.role;
                    document.getElementById('editModal').classList.remove('hidden');
                    document.getElementById('editModal').style.display = 'flex';
                },

                async updateStaff() {
                    try {
                        const res = await fetch(`/api/admin/staff/${this.editing_id}`, {
                            method: 'PUT',
                            headers: {
                                'Content-Type': 'application/json',
                                'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content')
                            },
                            credentials: 'same-origin',
                            body: JSON.stringify({
                                name: document.getElementById('editName').value,
                                email: document.getElementById('editEmail').value,
                                role: document.getElementById('editRole').value,
                            }),
                        });

                        if (res.ok) {
                            alert('{{ __("Staff updated successfully!") }}');
                            document.getElementById('editModal').classList.add('hidden');
                            await this.fetchStaff();
                        } else {
                            const data = await res.json();
                            alert('Error: ' + (data.message || '{{ __("Failed to update staff") }}'));
                        }
                    } catch (e) {
                        console.error('Error updating staff:', e);
                        alert('{{ __("An error occurred") }}');
                    }
                },

                openDelete(staffId) {
                    this.deleting_id = staffId;
                    document.getElementById('deleteModal').classList.remove('hidden');
                    document.getElementById('deleteModal').style.display = 'flex';
                },

                async confirmDelete() {
                    try {
                        const res = await fetch(`/api/admin/staff/${this.deleting_id}`, {
                            method: 'DELETE',
                            headers: {
                                'Content-Type': 'application/json',
                                'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content')
                            },
                            credentials: 'same-origin',
                        });

                        if (res.ok) {
                            alert('{{ __("Staff deleted successfully!") }}');
                            document.getElementById('deleteModal').classList.add('hidden');
                            await this.fetchStaff();
                        } else {
                            const data = await res.json();
                            alert('Error: ' + (data.message || '{{ __("Failed to delete staff") }}'));
                        }
                    } catch (e) {
                        console.error('Error deleting staff:', e);
                        alert('{{ __("An error occurred") }}');
                    }
                },

                resetForm() {
                    this.form = {
                        name: '',
                        email: '',
                        password: '',
                        password_confirmation: '',
                        role: 'staff',
                        assignment_type: 'general',
                    };
                },
            };
        }
    </script>
    @endpush
</x-app-layout>
