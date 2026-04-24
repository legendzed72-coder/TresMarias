<x-app-layout>
    <x-slot name="header">
        <h2 class="font-serif font-bold text-xl text-bark-600 leading-tight">
            {{ __('Settings') }}
        </h2>
    </x-slot>

    <div x-data="settingsPage()" class="py-8 sm:py-10">
        <div class="max-w-5xl mx-auto px-4 sm:px-6 lg:px-8">

            {{-- Page Title --}}
            <div class="mb-8">
                <h1 class="text-2xl font-serif font-bold text-bark-600">Settings</h1>
                <p class="mt-1 text-sm text-muted">Manage your preferences, delivery details, and account.</p>
            </div>

            <div class="flex flex-col lg:flex-row gap-8">

                {{-- Sidebar Navigation --}}
                <nav class="lg:w-56 shrink-0">
                    <div class="lg:sticky lg:top-24 flex lg:flex-col gap-1 overflow-x-auto lg:overflow-visible bg-cream-100/50 lg:bg-transparent rounded-2xl lg:rounded-none p-1.5 lg:p-0 ring-1 ring-bark-200/10 lg:ring-0">
                        <template x-for="item in [
                            { key: 'notifications', label: 'Notifications', icon: 'bell' },
                            { key: 'delivery', label: 'Delivery', icon: 'truck' },
                            { key: 'appearance', label: 'Appearance', icon: 'palette' },
                            { key: 'account', label: 'Account', icon: 'user' }
                        ]" :key="item.key">
                            <button @click="tab = item.key"
                                    :class="tab === item.key
                                        ? 'bg-cream-50 text-bark-600 shadow-sm shadow-bark-200/10 ring-1 ring-bark-200/10'
                                        : 'text-muted hover:text-bark-500 hover:bg-cream-50/60'"
                                    class="flex items-center gap-3 px-4 py-3 text-sm font-semibold rounded-xl transition-all duration-200 whitespace-nowrap">
                                {{-- Bell --}}
                                <svg x-show="item.icon === 'bell'" class="w-4.5 h-4.5 shrink-0" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" d="M14.857 17.082a23.848 23.848 0 0 0 5.454-1.31A8.967 8.967 0 0 1 18 9.75V9A6 6 0 0 0 6 9v.75a8.967 8.967 0 0 1-2.312 6.022c1.733.64 3.56 1.085 5.455 1.31m5.714 0a24.255 24.255 0 0 1-5.714 0m5.714 0a3 3 0 1 1-5.714 0" />
                                </svg>
                                {{-- Truck --}}
                                <svg x-show="item.icon === 'truck'" class="w-4.5 h-4.5 shrink-0" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" d="M8.25 18.75a1.5 1.5 0 0 1-3 0m3 0a1.5 1.5 0 0 0-3 0m3 0h6m-9 0H3.375a1.125 1.125 0 0 1-1.125-1.125V14.25m17.25 4.5a1.5 1.5 0 0 1-3 0m3 0a1.5 1.5 0 0 0-3 0m3 0h1.125c.621 0 1.143-.504 1.025-1.113a9.004 9.004 0 0 0-5.68-6.638A8.972 8.972 0 0 0 12 5.25c-1.005 0-1.97.164-2.87.467M2.25 14.25V5.625c0-.621.504-1.125 1.125-1.125h11.25c.621 0 1.125.504 1.125 1.125v4.874" />
                                </svg>
                                {{-- Palette --}}
                                <svg x-show="item.icon === 'palette'" class="w-4.5 h-4.5 shrink-0" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" d="M9.53 16.122a3 3 0 0 0-5.78 1.128 2.25 2.25 0 0 1-2.4 2.245 4.5 4.5 0 0 0 8.4-2.245c0-.399-.078-.78-.22-1.128Zm0 0a15.998 15.998 0 0 0 3.388-1.62m-5.043-.025a15.994 15.994 0 0 1 1.622-3.395m3.42 3.42a15.995 15.995 0 0 0 4.764-4.648l3.876-5.814a1.151 1.151 0 0 0-1.597-1.597L14.146 6.32a15.996 15.996 0 0 0-4.649 4.763m3.42 3.42a6.776 6.776 0 0 0-3.42-3.42" />
                                </svg>
                                {{-- User --}}
                                <svg x-show="item.icon === 'user'" class="w-4.5 h-4.5 shrink-0" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" d="M15.75 6a3.75 3.75 0 1 1-7.5 0 3.75 3.75 0 0 1 7.5 0ZM4.501 20.118a7.5 7.5 0 0 1 14.998 0A17.933 17.933 0 0 1 12 21.75c-2.676 0-5.216-.584-7.499-1.632Z" />
                                </svg>
                                <span x-text="item.label"></span>
                            </button>
                        </template>
                    </div>
                </nav>

                {{-- Content Area --}}
                <div class="flex-1 min-w-0">

                    {{-- ═══════════ Notifications ═══════════ --}}
                    <div x-show="tab === 'notifications'" x-transition:enter="transition ease-out duration-200" x-transition:enter-start="opacity-0 translate-y-2" x-transition:enter-end="opacity-100 translate-y-0" class="space-y-6">

                        {{-- Email Notifications --}}
                        <div class="bg-cream-50 rounded-2xl shadow-sm shadow-bark-200/10 ring-1 ring-bark-200/10 overflow-hidden">
                            <div class="px-6 py-5 border-b border-bark-200/10">
                                <h2 class="text-base font-serif font-bold text-bark-600">Email Notifications</h2>
                                <p class="text-sm text-muted mt-0.5">Choose which emails you'd like to receive.</p>
                            </div>
                            <div class="divide-y divide-bark-200/8">
                                <label class="flex items-center justify-between px-6 py-4 cursor-pointer hover:bg-cream-100/50 transition">
                                    <div>
                                        <p class="text-sm font-semibold text-bark-600">Order Updates</p>
                                        <p class="text-xs text-muted mt-0.5">Get notified when your order status changes.</p>
                                    </div>
                                    <div class="relative shrink-0 ml-4">
                                        <input type="checkbox" x-model="notifications.orderUpdates" @change="saveSettings()" class="sr-only peer">
                                        <div class="w-11 h-6 bg-bark-200/30 rounded-full peer peer-checked:after:translate-x-full peer-checked:after:border-white after:content-[''] after:absolute after:top-[2px] after:start-[2px] after:bg-white after:border-bark-200/20 after:border after:rounded-full after:h-5 after:w-5 after:transition-all peer-checked:bg-leaf-400"></div>
                                    </div>
                                </label>
                                <label class="flex items-center justify-between px-6 py-4 cursor-pointer hover:bg-cream-100/50 transition">
                                    <div>
                                        <p class="text-sm font-semibold text-bark-600">Delivery Alerts</p>
                                        <p class="text-xs text-muted mt-0.5">Receive updates about your delivery status.</p>
                                    </div>
                                    <div class="relative shrink-0 ml-4">
                                        <input type="checkbox" x-model="notifications.deliveryAlerts" @change="saveSettings()" class="sr-only peer">
                                        <div class="w-11 h-6 bg-bark-200/30 rounded-full peer peer-checked:after:translate-x-full peer-checked:after:border-white after:content-[''] after:absolute after:top-[2px] after:start-[2px] after:bg-white after:border-bark-200/20 after:border after:rounded-full after:h-5 after:w-5 after:transition-all peer-checked:bg-leaf-400"></div>
                                    </div>
                                </label>
                                <label class="flex items-center justify-between px-6 py-4 cursor-pointer hover:bg-cream-100/50 transition">
                                    <div>
                                        <p class="text-sm font-semibold text-bark-600">Promotions & Offers</p>
                                        <p class="text-xs text-muted mt-0.5">Stay updated on special deals and new products.</p>
                                    </div>
                                    <div class="relative shrink-0 ml-4">
                                        <input type="checkbox" x-model="notifications.promotions" @change="saveSettings()" class="sr-only peer">
                                        <div class="w-11 h-6 bg-bark-200/30 rounded-full peer peer-checked:after:translate-x-full peer-checked:after:border-white after:content-[''] after:absolute after:top-[2px] after:start-[2px] after:bg-white after:border-bark-200/20 after:border after:rounded-full after:h-5 after:w-5 after:transition-all peer-checked:bg-leaf-400"></div>
                                    </div>
                                </label>
                                <label class="flex items-center justify-between px-6 py-4 cursor-pointer hover:bg-cream-100/50 transition">
                                    <div>
                                        <p class="text-sm font-semibold text-bark-600">Newsletter</p>
                                        <p class="text-xs text-muted mt-0.5">Weekly updates about our bakery and recipes.</p>
                                    </div>
                                    <div class="relative shrink-0 ml-4">
                                        <input type="checkbox" x-model="notifications.newsletter" @change="saveSettings()" class="sr-only peer">
                                        <div class="w-11 h-6 bg-bark-200/30 rounded-full peer peer-checked:after:translate-x-full peer-checked:after:border-white after:content-[''] after:absolute after:top-[2px] after:start-[2px] after:bg-white after:border-bark-200/20 after:border after:rounded-full after:h-5 after:w-5 after:transition-all peer-checked:bg-leaf-400"></div>
                                    </div>
                                </label>
                            </div>
                        </div>

                        {{-- Push Notifications --}}
                        <div class="bg-cream-50 rounded-2xl shadow-sm shadow-bark-200/10 ring-1 ring-bark-200/10 overflow-hidden">
                            <div class="px-6 py-5 border-b border-bark-200/10">
                                <h2 class="text-base font-serif font-bold text-bark-600">Push Notifications</h2>
                                <p class="text-sm text-muted mt-0.5">Manage real-time browser notifications.</p>
                            </div>
                            <label class="flex items-center justify-between px-6 py-4 cursor-pointer hover:bg-cream-100/50 transition">
                                <div>
                                    <p class="text-sm font-semibold text-bark-600">Enable Push Notifications</p>
                                    <p class="text-xs text-muted mt-0.5">Receive instant alerts in your browser.</p>
                                </div>
                                <div class="relative shrink-0 ml-4">
                                    <input type="checkbox" x-model="notifications.pushEnabled" @change="togglePush()" class="sr-only peer">
                                    <div class="w-11 h-6 bg-bark-200/30 rounded-full peer peer-checked:after:translate-x-full peer-checked:after:border-white after:content-[''] after:absolute after:top-[2px] after:start-[2px] after:bg-white after:border-bark-200/20 after:border after:rounded-full after:h-5 after:w-5 after:transition-all peer-checked:bg-leaf-400"></div>
                                </div>
                            </label>
                        </div>
                    </div>

                    {{-- ═══════════ Delivery ═══════════ --}}
                    <div x-show="tab === 'delivery'" x-transition:enter="transition ease-out duration-200" x-transition:enter-start="opacity-0 translate-y-2" x-transition:enter-end="opacity-100 translate-y-0" class="space-y-6">

                        {{-- Default Address --}}
                        <div class="bg-cream-50 rounded-2xl shadow-sm shadow-bark-200/10 ring-1 ring-bark-200/10 overflow-hidden">
                            <div class="px-6 py-5 border-b border-bark-200/10">
                                <h2 class="text-base font-serif font-bold text-bark-600">Default Address</h2>
                                <p class="text-sm text-muted mt-0.5">Your preferred delivery address for faster checkout.</p>
                            </div>
                            <form @submit.prevent="saveAddress()" class="p-6 space-y-5">
                                <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                                    <div>
                                        <x-input-label for="address_line" value="Street Address" />
                                        <x-text-input id="address_line" type="text" class="mt-1 block w-full" x-model="delivery.addressLine" placeholder="123 Bakery Street" />
                                    </div>
                                    <div>
                                        <x-input-label for="barangay" value="Barangay" />
                                        <x-text-input id="barangay" type="text" class="mt-1 block w-full" x-model="delivery.barangay" placeholder="Barangay Name" />
                                    </div>
                                    <div>
                                        <x-input-label for="city" value="City / Municipality" />
                                        <x-text-input id="city" type="text" class="mt-1 block w-full" x-model="delivery.city" placeholder="City" />
                                    </div>
                                    <div>
                                        <x-input-label for="phone" value="Contact Number" />
                                        <x-text-input id="phone" type="tel" class="mt-1 block w-full" x-model="delivery.phone" placeholder="09XX XXX XXXX" />
                                    </div>
                                </div>

                                <div>
                                    <x-input-label for="landmark" value="Landmark / Notes" />
                                    <textarea id="landmark" x-model="delivery.landmark" rows="3" placeholder="Near the church, gate is blue, etc."
                                        class="mt-1 block w-full rounded-xl border-bark-200/30 bg-white shadow-sm focus:border-bark-300 focus:ring-bark-300/30 text-sm text-bark-600 placeholder-bark-200/60"></textarea>
                                </div>

                                <div class="flex items-center gap-3">
                                    <x-primary-button type="submit">
                                        {{ __('Save Address') }}
                                    </x-primary-button>
                                    <p x-show="addressSaved" x-transition class="text-sm text-leaf-500 font-medium">Saved!</p>
                                </div>
                            </form>
                        </div>

                        {{-- Delivery Preferences --}}
                        <div class="bg-cream-50 rounded-2xl shadow-sm shadow-bark-200/10 ring-1 ring-bark-200/10 overflow-hidden">
                            <div class="px-6 py-5 border-b border-bark-200/10">
                                <h2 class="text-base font-serif font-bold text-bark-600">Delivery Preferences</h2>
                                <p class="text-sm text-muted mt-0.5">Customize how your deliveries are handled.</p>
                            </div>
                            <div class="divide-y divide-bark-200/8">
                                <label class="flex items-center justify-between px-6 py-4 cursor-pointer hover:bg-cream-100/50 transition">
                                    <div>
                                        <p class="text-sm font-semibold text-bark-600">Leave at Door</p>
                                        <p class="text-xs text-muted mt-0.5">Rider can leave the order at your door if you're not available.</p>
                                    </div>
                                    <div class="relative shrink-0 ml-4">
                                        <input type="checkbox" x-model="delivery.leaveAtDoor" @change="saveSettings()" class="sr-only peer">
                                        <div class="w-11 h-6 bg-bark-200/30 rounded-full peer peer-checked:after:translate-x-full peer-checked:after:border-white after:content-[''] after:absolute after:top-[2px] after:start-[2px] after:bg-white after:border-bark-200/20 after:border after:rounded-full after:h-5 after:w-5 after:transition-all peer-checked:bg-leaf-400"></div>
                                    </div>
                                </label>
                                <label class="flex items-center justify-between px-6 py-4 cursor-pointer hover:bg-cream-100/50 transition">
                                    <div>
                                        <p class="text-sm font-semibold text-bark-600">SMS Delivery Updates</p>
                                        <p class="text-xs text-muted mt-0.5">Receive text messages when your order is on the way.</p>
                                    </div>
                                    <div class="relative shrink-0 ml-4">
                                        <input type="checkbox" x-model="delivery.smsUpdates" @change="saveSettings()" class="sr-only peer">
                                        <div class="w-11 h-6 bg-bark-200/30 rounded-full peer peer-checked:after:translate-x-full peer-checked:after:border-white after:content-[''] after:absolute after:top-[2px] after:start-[2px] after:bg-white after:border-bark-200/20 after:border after:rounded-full after:h-5 after:w-5 after:transition-all peer-checked:bg-leaf-400"></div>
                                    </div>
                                </label>
                            </div>
                        </div>
                    </div>

                    {{-- ═══════════ Appearance ═══════════ --}}
                    <div x-show="tab === 'appearance'" x-transition:enter="transition ease-out duration-200" x-transition:enter-start="opacity-0 translate-y-2" x-transition:enter-end="opacity-100 translate-y-0" class="space-y-6">

                        {{-- Language --}}
                        <div class="bg-cream-50 rounded-2xl shadow-sm shadow-bark-200/10 ring-1 ring-bark-200/10 overflow-hidden">
                            <div class="px-6 py-5 border-b border-bark-200/10">
                                <h2 class="text-base font-serif font-bold text-bark-600">Language</h2>
                                <p class="text-sm text-muted mt-0.5">Select your preferred language.</p>
                            </div>
                            <div class="p-6">
                                <div class="grid grid-cols-1 sm:grid-cols-3 gap-3">
                                    <template x-for="lang in [
                                        { code: 'en', label: 'English', flag: '🇺🇸' },
                                        { code: 'fil', label: 'Filipino', flag: '🇵🇭' },
                                        { code: 'ceb', label: 'Cebuano', flag: '🇵🇭' }
                                    ]" :key="lang.code">
                                        <button @click="appearance.language = lang.code; saveSettings()"
                                                :class="appearance.language === lang.code
                                                    ? 'ring-2 ring-bark-300 bg-cream-100'
                                                    : 'ring-1 ring-bark-200/10 bg-cream-100/40 hover:bg-cream-100'"
                                                class="flex items-center gap-3 p-4 rounded-xl transition cursor-pointer">
                                            <span class="text-2xl" x-text="lang.flag"></span>
                                            <span class="text-sm font-semibold text-bark-600" x-text="lang.label"></span>
                                            <svg x-show="appearance.language === lang.code" class="w-5 h-5 text-leaf-500 ml-auto" fill="currentColor" viewBox="0 0 20 20">
                                                <path fill-rule="evenodd" d="M10 18a8 8 0 1 0 0-16 8 8 0 0 0 0 16Zm3.857-9.809a.75.75 0 0 0-1.214-.882l-3.483 4.79-1.88-1.88a.75.75 0 1 0-1.06 1.061l2.5 2.5a.75.75 0 0 0 1.137-.089l4-5.5Z" clip-rule="evenodd" />
                                            </svg>
                                        </button>
                                    </template>
                                </div>
                            </div>
                        </div>

                        {{-- Display --}}
                        <div class="bg-cream-50 rounded-2xl shadow-sm shadow-bark-200/10 ring-1 ring-bark-200/10 overflow-hidden">
                            <div class="px-6 py-5 border-b border-bark-200/10">
                                <h2 class="text-base font-serif font-bold text-bark-600">Display</h2>
                                <p class="text-sm text-muted mt-0.5">Customize how the app looks and feels.</p>
                            </div>
                            <div class="divide-y divide-bark-200/8">
                                <label class="flex items-center justify-between px-6 py-4 cursor-pointer hover:bg-cream-100/50 transition">
                                    <div>
                                        <p class="text-sm font-semibold text-bark-600">Show Product Prices</p>
                                        <p class="text-xs text-muted mt-0.5">Display prices on product cards in the catalog.</p>
                                    </div>
                                    <div class="relative shrink-0 ml-4">
                                        <input type="checkbox" x-model="appearance.showPrices" @change="saveSettings()" class="sr-only peer">
                                        <div class="w-11 h-6 bg-bark-200/30 rounded-full peer peer-checked:after:translate-x-full peer-checked:after:border-white after:content-[''] after:absolute after:top-[2px] after:start-[2px] after:bg-white after:border-bark-200/20 after:border after:rounded-full after:h-5 after:w-5 after:transition-all peer-checked:bg-leaf-400"></div>
                                    </div>
                                </label>
                                <label class="flex items-center justify-between px-6 py-4 cursor-pointer hover:bg-cream-100/50 transition">
                                    <div>
                                        <p class="text-sm font-semibold text-bark-600">Compact Order View</p>
                                        <p class="text-xs text-muted mt-0.5">Show orders in a more condensed layout.</p>
                                    </div>
                                    <div class="relative shrink-0 ml-4">
                                        <input type="checkbox" x-model="appearance.compactOrders" @change="saveSettings()" class="sr-only peer">
                                        <div class="w-11 h-6 bg-bark-200/30 rounded-full peer peer-checked:after:translate-x-full peer-checked:after:border-white after:content-[''] after:absolute after:top-[2px] after:start-[2px] after:bg-white after:border-bark-200/20 after:border after:rounded-full after:h-5 after:w-5 after:transition-all peer-checked:bg-leaf-400"></div>
                                    </div>
                                </label>
                            </div>
                        </div>
                    </div>

                    {{-- ═══════════ Account ═══════════ --}}
                    <div x-show="tab === 'account'" x-transition:enter="transition ease-out duration-200" x-transition:enter-start="opacity-0 translate-y-2" x-transition:enter-end="opacity-100 translate-y-0" class="space-y-6">

                        {{-- Account Management --}}
                        <div class="bg-cream-50 rounded-2xl shadow-sm shadow-bark-200/10 ring-1 ring-bark-200/10 overflow-hidden">
                            <div class="px-6 py-5 border-b border-bark-200/10">
                                <h2 class="text-base font-serif font-bold text-bark-600">Account Management</h2>
                                <p class="text-sm text-muted mt-0.5">Manage your profile and security.</p>
                            </div>
                            <div class="divide-y divide-bark-200/8">
                                <a href="{{ route('profile.edit') }}" class="flex items-center justify-between px-6 py-4 hover:bg-cream-100/50 transition group">
                                    <div class="flex items-center gap-4">
                                        <div class="shrink-0 w-9 h-9 rounded-lg bg-bark-300/10 flex items-center justify-center">
                                            <svg class="w-4.5 h-4.5 text-bark-300" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" d="m16.862 4.487 1.687-1.688a1.875 1.875 0 1 1 2.652 2.652L10.582 16.07a4.5 4.5 0 0 1-1.897 1.13L6 18l.8-2.685a4.5 4.5 0 0 1 1.13-1.897l8.932-8.931Zm0 0L19.5 7.125M18 14v4.75A2.25 2.25 0 0 1 15.75 21H5.25A2.25 2.25 0 0 1 3 18.75V8.25A2.25 2.25 0 0 1 5.25 6H10" />
                                            </svg>
                                        </div>
                                        <div>
                                            <p class="text-sm font-semibold text-bark-600">Edit Profile</p>
                                            <p class="text-xs text-muted mt-0.5">Update your name and email address.</p>
                                        </div>
                                    </div>
                                    <svg class="w-5 h-5 text-bark-200 group-hover:text-bark-400 transition shrink-0" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" d="m8.25 4.5 7.5 7.5-7.5 7.5" />
                                    </svg>
                                </a>
                                <a href="{{ route('profile.edit') }}" class="flex items-center justify-between px-6 py-4 hover:bg-cream-100/50 transition group">
                                    <div class="flex items-center gap-4">
                                        <div class="shrink-0 w-9 h-9 rounded-lg bg-gold-400/10 flex items-center justify-center">
                                            <svg class="w-4.5 h-4.5 text-gold-500" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" d="M16.5 10.5V6.75a4.5 4.5 0 1 0-9 0v3.75m-.75 11.25h10.5a2.25 2.25 0 0 0 2.25-2.25v-6.75a2.25 2.25 0 0 0-2.25-2.25H6.75a2.25 2.25 0 0 0-2.25 2.25v6.75a2.25 2.25 0 0 0 2.25 2.25Z" />
                                            </svg>
                                        </div>
                                        <div>
                                            <p class="text-sm font-semibold text-bark-600">Change Password</p>
                                            <p class="text-xs text-muted mt-0.5">Update your password and security settings.</p>
                                        </div>
                                    </div>
                                    <svg class="w-5 h-5 text-bark-200 group-hover:text-bark-400 transition shrink-0" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" d="m8.25 4.5 7.5 7.5-7.5 7.5" />
                                    </svg>
                                </a>
                                <a href="{{ route('my-orders') }}" class="flex items-center justify-between px-6 py-4 hover:bg-cream-100/50 transition group">
                                    <div class="flex items-center gap-4">
                                        <div class="shrink-0 w-9 h-9 rounded-lg bg-leaf-400/10 flex items-center justify-center">
                                            <svg class="w-4.5 h-4.5 text-leaf-400" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" d="M9 12h3.75M9 15h3.75M9 18h3.75m3 .75H18a2.25 2.25 0 0 0 2.25-2.25V6.108c0-1.135-.845-2.098-1.976-2.192a48.424 48.424 0 0 0-1.123-.08m-5.801 0c-.065.21-.1.433-.1.664 0 .414.336.75.75.75h4.5a.75.75 0 0 0 .75-.75 2.25 2.25 0 0 0-.1-.664m-5.8 0A2.251 2.251 0 0 1 13.5 2.25H15c1.012 0 1.867.668 2.15 1.586m-5.8 0c-.376.023-.75.05-1.124.08C9.095 4.01 8.25 4.973 8.25 6.108V8.25m0 0H4.875c-.621 0-1.125.504-1.125 1.125v11.25c0 .621.504 1.125 1.125 1.125h9.75c.621 0 1.125-.504 1.125-1.125V9.375c0-.621-.504-1.125-1.125-1.125H8.25ZM6.75 12h.008v.008H6.75V12Zm0 3h.008v.008H6.75V15Zm0 3h.008v.008H6.75V18Z" />
                                            </svg>
                                        </div>
                                        <div>
                                            <p class="text-sm font-semibold text-bark-600">Order History</p>
                                            <p class="text-xs text-muted mt-0.5">View all your past and current orders.</p>
                                        </div>
                                    </div>
                                    <svg class="w-5 h-5 text-bark-200 group-hover:text-bark-400 transition shrink-0" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" d="m8.25 4.5 7.5 7.5-7.5 7.5" />
                                    </svg>
                                </a>
                            </div>
                        </div>

                        {{-- Danger Zone --}}
                        <div class="bg-cream-50 rounded-2xl shadow-sm shadow-bark-200/10 ring-1 ring-red-200/20 overflow-hidden">
                            <div class="px-6 py-5 border-b border-red-200/20">
                                <h2 class="text-base font-serif font-bold text-red-600">Danger Zone</h2>
                                <p class="text-sm text-red-400 mt-0.5">Irreversible actions. Please be careful.</p>
                            </div>
                            <div class="divide-y divide-red-200/15">
                                <div class="flex items-center justify-between px-6 py-4">
                                    <div>
                                        <p class="text-sm font-semibold text-bark-600">Clear All Settings</p>
                                        <p class="text-xs text-muted mt-0.5">Reset all preferences to their default values.</p>
                                    </div>
                                    <button @click="if(confirm('Reset all settings to defaults?')) resetSettings()"
                                            class="px-4 py-2 text-sm font-semibold text-red-600 bg-white border border-red-200 rounded-xl hover:bg-red-50 transition shrink-0 ml-4">
                                        Reset
                                    </button>
                                </div>
                                <div class="flex items-center justify-between px-6 py-4">
                                    <div>
                                        <p class="text-sm font-semibold text-bark-600">Delete Account</p>
                                        <p class="text-xs text-muted mt-0.5">Permanently delete your account and all data.</p>
                                    </div>
                                    <a href="{{ route('profile.edit') }}"
                                       class="px-4 py-2 text-sm font-semibold text-white bg-red-500 rounded-xl hover:bg-red-600 transition shrink-0 ml-4">
                                        Delete
                                    </a>
                                </div>
                            </div>
                        </div>
                    </div>

                </div>
            </div>
        </div>
    </div>

    @push('scripts')
    <script>
    function settingsPage() {
        const storageKey = 'tres_marias_settings';

        const defaults = {
            notifications: {
                orderUpdates: true,
                deliveryAlerts: true,
                promotions: false,
                newsletter: false,
                pushEnabled: false,
            },
            delivery: {
                addressLine: '',
                barangay: '',
                city: '',
                phone: '',
                landmark: '',
                leaveAtDoor: false,
                smsUpdates: true,
            },
            appearance: {
                language: 'en',
                showPrices: true,
                compactOrders: false,
            },
        };

        let saved = {};
        try {
            saved = JSON.parse(localStorage.getItem(storageKey) || '{}');
        } catch (e) {
            saved = {};
        }

        return {
            tab: 'notifications',
            addressSaved: false,

            notifications: { ...defaults.notifications, ...(saved.notifications || {}) },
            delivery: { ...defaults.delivery, ...(saved.delivery || {}) },
            appearance: { ...defaults.appearance, ...(saved.appearance || {}) },

            saveSettings() {
                const data = {
                    notifications: { ...this.notifications },
                    delivery: { ...this.delivery },
                    appearance: { ...this.appearance },
                };
                localStorage.setItem(storageKey, JSON.stringify(data));
            },

            saveAddress() {
                this.saveSettings();
                this.addressSaved = true;
                setTimeout(() => this.addressSaved = false, 2500);
            },

            togglePush() {
                if (this.notifications.pushEnabled && 'Notification' in window) {
                    Notification.requestPermission().then(permission => {
                        if (permission !== 'granted') {
                            this.notifications.pushEnabled = false;
                        }
                        this.saveSettings();
                    });
                } else {
                    this.saveSettings();
                }
            },

            resetSettings() {
                this.notifications = { ...defaults.notifications };
                this.delivery = { ...defaults.delivery };
                this.appearance = { ...defaults.appearance };
                localStorage.removeItem(storageKey);
            },
        };
    }
    </script>
    @endpush
</x-app-layout>
