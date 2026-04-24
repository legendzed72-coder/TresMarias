<x-app-layout>
    <x-slot name="header">
        <h2 class="font-serif font-bold text-xl text-bark-600 leading-tight">
            {{ __('My Profile') }}
        </h2>
    </x-slot>

    <div x-data="{ tab: 'overview', ...settingsData() }" class="py-10">
        <div class="max-w-4xl mx-auto px-4 sm:px-6 lg:px-8 space-y-8">

            {{-- Profile Header Card --}}
            <div class="relative overflow-hidden bg-gradient-to-br from-bark-300 to-bark-400 rounded-3xl shadow-xl shadow-bark-400/20 p-8 sm:p-10 text-white">
                <div class="absolute -top-10 -right-10 w-40 h-40 rounded-full bg-white/5"></div>
                <div class="absolute -bottom-6 -left-6 w-28 h-28 rounded-full bg-white/5"></div>

                <div class="relative flex flex-col sm:flex-row items-center gap-6">
                    {{-- Avatar --}}
                    <div class="shrink-0 w-24 h-24 rounded-full bg-cream-100/20 border-4 border-white/30 flex items-center justify-center shadow-lg">
                        <span class="text-3xl font-serif font-bold text-white uppercase select-none">
                            {{ substr(Auth::user()->name, 0, 1) }}
                        </span>
                    </div>

                    {{-- User Info --}}
                    <div class="text-center sm:text-left flex-1">
                        <h3 class="text-2xl font-serif font-bold tracking-tight">
                            {{ Auth::user()->name }}
                        </h3>
                        <p class="mt-1 text-cream-200/80 text-sm font-medium">
                            {{ Auth::user()->email }}
                        </p>
                        <div class="flex flex-wrap items-center justify-center sm:justify-start gap-3 mt-3">
                            <span class="inline-block px-3 py-1 text-xs font-bold uppercase tracking-widest rounded-full bg-white/15 border border-white/20 backdrop-blur-sm">
                                {{ Auth::user()->role ?? 'Customer' }}
                            </span>
                            <span class="text-xs text-cream-200/60">
                                Member since {{ Auth::user()->created_at->format('F Y') }}
                            </span>
                        </div>
                    </div>
                </div>
            </div>

            {{-- Tab Navigation --}}
            <div class="flex gap-1 bg-cream-100/50 rounded-2xl p-1.5 ring-1 ring-bark-200/10">
                <button @click="tab = 'overview'"
                        :class="tab === 'overview' ? 'bg-cream-50 text-bark-600 shadow-sm shadow-bark-200/10' : 'text-muted hover:text-bark-500 hover:bg-cream-50/50'"
                        class="flex-1 px-4 py-2.5 text-sm font-semibold rounded-xl transition-all duration-200">
                    Overview
                </button>
                <button @click="tab = 'edit'"
                        :class="tab === 'edit' ? 'bg-cream-50 text-bark-600 shadow-sm shadow-bark-200/10' : 'text-muted hover:text-bark-500 hover:bg-cream-50/50'"
                        class="flex-1 px-4 py-2.5 text-sm font-semibold rounded-xl transition-all duration-200">
                    Edit Profile
                </button>
                <button @click="tab = 'security'"
                        :class="tab === 'security' ? 'bg-cream-50 text-bark-600 shadow-sm shadow-bark-200/10' : 'text-muted hover:text-bark-500 hover:bg-cream-50/50'"
                        class="flex-1 px-4 py-2.5 text-sm font-semibold rounded-xl transition-all duration-200">
                    Security
                </button>
                <button @click="tab = 'settings'"
                        :class="tab === 'settings' ? 'bg-cream-50 text-bark-600 shadow-sm shadow-bark-200/10' : 'text-muted hover:text-bark-500 hover:bg-cream-50/50'"
                        class="flex-1 px-4 py-2.5 text-sm font-semibold rounded-xl transition-all duration-200">
                    Settings
                </button>
            </div>

            {{-- ═══════════════════════════════════════════ --}}
            {{-- Overview Tab --}}
            {{-- ═══════════════════════════════════════════ --}}
            <div x-show="tab === 'overview'" x-transition:enter="transition ease-out duration-200" x-transition:enter-start="opacity-0 translate-y-2" x-transition:enter-end="opacity-100 translate-y-0" class="space-y-6">

                {{-- Quick Stats --}}
                <div class="grid grid-cols-2 sm:grid-cols-4 gap-4">
                    <div class="bg-cream-50 rounded-2xl ring-1 ring-bark-200/10 p-5 text-center">
                        <div class="w-10 h-10 mx-auto mb-2 rounded-xl bg-bark-300/10 flex items-center justify-center">
                            <svg class="w-5 h-5 text-bark-300" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" d="M15.75 10.5V6a3.75 3.75 0 1 0-7.5 0v4.5m11.356-1.993 1.263 12c.07.665-.45 1.243-1.119 1.243H4.25a1.125 1.125 0 0 1-1.12-1.243l1.264-12A1.125 1.125 0 0 1 5.513 7.5h12.974c.576 0 1.059.435 1.119 1.007ZM8.625 10.5a.375.375 0 1 1-.75 0 .375.375 0 0 1 .75 0Zm7.5 0a.375.375 0 1 1-.75 0 .375.375 0 0 1 .75 0Z" />
                            </svg>
                        </div>
                        <p class="text-2xl font-serif font-bold text-bark-600">{{ Auth::user()->orders()->count() }}</p>
                        <p class="text-xs text-muted mt-1">Total Orders</p>
                    </div>

                    <div class="bg-cream-50 rounded-2xl ring-1 ring-bark-200/10 p-5 text-center">
                        <div class="w-10 h-10 mx-auto mb-2 rounded-xl bg-leaf-400/10 flex items-center justify-center">
                            <svg class="w-5 h-5 text-leaf-400" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" d="M9 12.75 11.25 15 15 9.75M21 12a9 9 0 1 1-18 0 9 9 0 0 1 18 0Z" />
                            </svg>
                        </div>
                        <p class="text-2xl font-serif font-bold text-bark-600">{{ Auth::user()->orders()->where('status', 'completed')->count() }}</p>
                        <p class="text-xs text-muted mt-1">Completed</p>
                    </div>

                    <div class="bg-cream-50 rounded-2xl ring-1 ring-bark-200/10 p-5 text-center">
                        <div class="w-10 h-10 mx-auto mb-2 rounded-xl bg-gold-400/10 flex items-center justify-center">
                            <svg class="w-5 h-5 text-gold-500" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" d="M12 6v6h4.5m4.5 0a9 9 0 1 1-18 0 9 9 0 0 1 18 0Z" />
                            </svg>
                        </div>
                        <p class="text-2xl font-serif font-bold text-bark-600">{{ Auth::user()->orders()->whereIn('status', ['pending', 'confirmed', 'preparing', 'ready'])->count() }}</p>
                        <p class="text-xs text-muted mt-1">In Progress</p>
                    </div>

                    <div class="bg-cream-50 rounded-2xl ring-1 ring-bark-200/10 p-5 text-center">
                        <div class="w-10 h-10 mx-auto mb-2 rounded-xl bg-bark-300/10 flex items-center justify-center">
                            <svg class="w-5 h-5 text-bark-300" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" d="M2.25 18.75a60.07 60.07 0 0 1 15.797 2.101c.727.198 1.453-.342 1.453-1.096V18.75M3.75 4.5v.75A.75.75 0 0 1 3 6h-.75m0 0v-.375c0-.621.504-1.125 1.125-1.125H20.25M2.25 6v9m18-10.5v.75c0 .414.336.75.75.75h.75m-1.5-1.5h.375c.621 0 1.125.504 1.125 1.125v9.75c0 .621-.504 1.125-1.125 1.125h-.375m1.5-1.5H21a.75.75 0 0 0-.75.75v.75m0 0H3.75m0 0h-.375a1.125 1.125 0 0 1-1.125-1.125V15m1.5 1.5v-.75A.75.75 0 0 0 3 15h-.75M15 10.5a3 3 0 1 1-6 0 3 3 0 0 1 6 0Zm3 0h.008v.008H18V10.5Zm-12 0h.008v.008H6V10.5Z" />
                            </svg>
                        </div>
                        <p class="text-2xl font-serif font-bold text-bark-600">₱{{ number_format(Auth::user()->orders()->where('status', 'completed')->sum('total'), 2) }}</p>
                        <p class="text-xs text-muted mt-1">Total Spent</p>
                    </div>
                </div>

                {{-- Account Details --}}
                <div class="bg-cream-50 rounded-2xl shadow-sm shadow-bark-200/10 ring-1 ring-bark-200/10 p-6 sm:p-8">
                    <div class="flex items-center gap-3 mb-6">
                        <div class="w-10 h-10 rounded-xl bg-bark-300/10 flex items-center justify-center">
                            <svg class="w-5 h-5 text-bark-300" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" d="M15.75 6a3.75 3.75 0 1 1-7.5 0 3.75 3.75 0 0 1 7.5 0ZM4.501 20.118a7.5 7.5 0 0 1 14.998 0A17.933 17.933 0 0 1 12 21.75c-2.676 0-5.216-.584-7.499-1.632Z" />
                            </svg>
                        </div>
                        <div>
                            <h2 class="text-lg font-serif font-bold text-bark-600">Account Details</h2>
                            <p class="text-sm text-muted">Your personal information at a glance.</p>
                        </div>
                    </div>

                    <dl class="grid grid-cols-1 sm:grid-cols-2 gap-x-8 gap-y-5">
                        <div>
                            <dt class="text-xs font-semibold uppercase tracking-wider text-muted">Full Name</dt>
                            <dd class="mt-1 text-sm font-medium text-bark-600">{{ Auth::user()->name }}</dd>
                        </div>
                        <div>
                            <dt class="text-xs font-semibold uppercase tracking-wider text-muted">Email Address</dt>
                            <dd class="mt-1 text-sm font-medium text-bark-600">{{ Auth::user()->email }}</dd>
                        </div>
                        <div>
                            <dt class="text-xs font-semibold uppercase tracking-wider text-muted">Role</dt>
                            <dd class="mt-1 text-sm font-medium text-bark-600 capitalize">{{ Auth::user()->role ?? 'Customer' }}</dd>
                        </div>
                        <div>
                            <dt class="text-xs font-semibold uppercase tracking-wider text-muted">Email Verified</dt>
                            <dd class="mt-1 text-sm font-medium">
                                @if(Auth::user()->hasVerifiedEmail())
                                    <span class="inline-flex items-center gap-1 text-leaf-500">
                                        <svg class="w-4 h-4" fill="currentColor" viewBox="0 0 20 20"><path fill-rule="evenodd" d="M10 18a8 8 0 1 0 0-16 8 8 0 0 0 0 16Zm3.857-9.809a.75.75 0 0 0-1.214-.882l-3.483 4.79-1.88-1.88a.75.75 0 1 0-1.06 1.061l2.5 2.5a.75.75 0 0 0 1.137-.089l4-5.5Z" clip-rule="evenodd" /></svg>
                                        Verified
                                    </span>
                                @else
                                    <span class="inline-flex items-center gap-1 text-red-500">
                                        <svg class="w-4 h-4" fill="currentColor" viewBox="0 0 20 20"><path fill-rule="evenodd" d="M18 10a8 8 0 1 1-16 0 8 8 0 0 1 16 0Zm-8-5a.75.75 0 0 1 .75.75v4.5a.75.75 0 0 1-1.5 0v-4.5A.75.75 0 0 1 10 5Zm0 10a1 1 0 1 0 0-2 1 1 0 0 0 0 2Z" clip-rule="evenodd" /></svg>
                                        Not Verified
                                    </span>
                                @endif
                            </dd>
                        </div>
                        <div>
                            <dt class="text-xs font-semibold uppercase tracking-wider text-muted">Member Since</dt>
                            <dd class="mt-1 text-sm font-medium text-bark-600">{{ Auth::user()->created_at->format('M d, Y') }}</dd>
                        </div>
                        <div>
                            <dt class="text-xs font-semibold uppercase tracking-wider text-muted">Last Updated</dt>
                            <dd class="mt-1 text-sm font-medium text-bark-600">{{ Auth::user()->updated_at->diffForHumans() }}</dd>
                        </div>
                    </dl>
                </div>

                {{-- Recent Orders --}}
                @php $recentOrders = Auth::user()->orders()->latest()->take(5)->get(); @endphp
                <div class="bg-cream-50 rounded-2xl shadow-sm shadow-bark-200/10 ring-1 ring-bark-200/10 p-6 sm:p-8">
                    <div class="flex items-center justify-between mb-6">
                        <div class="flex items-center gap-3">
                            <div class="w-10 h-10 rounded-xl bg-gold-400/10 flex items-center justify-center">
                                <svg class="w-5 h-5 text-gold-500" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" d="M9 12h3.75M9 15h3.75M9 18h3.75m3 .75H18a2.25 2.25 0 0 0 2.25-2.25V6.108c0-1.135-.845-2.098-1.976-2.192a48.424 48.424 0 0 0-1.123-.08m-5.801 0c-.065.21-.1.433-.1.664 0 .414.336.75.75.75h4.5a.75.75 0 0 0 .75-.75 2.25 2.25 0 0 0-.1-.664m-5.8 0A2.251 2.251 0 0 1 13.5 2.25H15c1.012 0 1.867.668 2.15 1.586m-5.8 0c-.376.023-.75.05-1.124.08C9.095 4.01 8.25 4.973 8.25 6.108V8.25m0 0H4.875c-.621 0-1.125.504-1.125 1.125v11.25c0 .621.504 1.125 1.125 1.125h9.75c.621 0 1.125-.504 1.125-1.125V9.375c0-.621-.504-1.125-1.125-1.125H8.25ZM6.75 12h.008v.008H6.75V12Zm0 3h.008v.008H6.75V15Zm0 3h.008v.008H6.75V18Z" />
                                </svg>
                            </div>
                            <h2 class="text-lg font-serif font-bold text-bark-600">Recent Orders</h2>
                        </div>
                        <a href="{{ route('my-orders') }}" class="text-sm font-semibold text-bark-300 hover:text-bark-400 transition">View All →</a>
                    </div>

                    @if($recentOrders->isEmpty())
                        <div class="text-center py-8">
                            <div class="w-16 h-16 mx-auto mb-4 rounded-2xl bg-cream-200/50 flex items-center justify-center">
                                <svg class="w-8 h-8 text-bark-200" fill="none" stroke="currentColor" stroke-width="1.5" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" d="M15.75 10.5V6a3.75 3.75 0 1 0-7.5 0v4.5m11.356-1.993 1.263 12c.07.665-.45 1.243-1.119 1.243H4.25a1.125 1.125 0 0 1-1.12-1.243l1.264-12A1.125 1.125 0 0 1 5.513 7.5h12.974c.576 0 1.059.435 1.119 1.007ZM8.625 10.5a.375.375 0 1 1-.75 0 .375.375 0 0 1 .75 0Zm7.5 0a.375.375 0 1 1-.75 0 .375.375 0 0 1 .75 0Z" />
                                </svg>
                            </div>
                            <p class="text-sm text-muted">No orders yet.</p>
                            <a href="{{ route('products') }}" class="inline-block mt-3 text-sm font-semibold text-bark-300 hover:text-bark-400 transition">Browse Products →</a>
                        </div>
                    @else
                        <div class="space-y-3">
                            @foreach($recentOrders as $order)
                                <a href="{{ route('orders.show', $order->id) }}" class="flex items-center justify-between p-4 rounded-xl bg-cream-100/40 hover:bg-cream-100 ring-1 ring-bark-200/5 transition group">
                                    <div class="flex items-center gap-4">
                                        <div class="shrink-0 w-10 h-10 rounded-lg bg-cream-200/60 flex items-center justify-center">
                                            <span class="text-xs font-bold text-bark-400">#{{ $loop->iteration }}</span>
                                        </div>
                                        <div>
                                            <p class="text-sm font-semibold text-bark-600">{{ $order->order_number }}</p>
                                            <p class="text-xs text-muted">{{ $order->created_at->format('M d, Y · g:i A') }}</p>
                                        </div>
                                    </div>
                                    <div class="flex items-center gap-4">
                                        @php
                                            $statusColors = [
                                                'pending' => 'bg-gold-400/15 text-gold-500',
                                                'confirmed' => 'bg-blue-50 text-blue-600',
                                                'preparing' => 'bg-bark-300/10 text-bark-300',
                                                'ready' => 'bg-leaf-400/10 text-leaf-500',
                                                'completed' => 'bg-leaf-400/15 text-leaf-500',
                                                'cancelled' => 'bg-red-50 text-red-500',
                                            ];
                                        @endphp
                                        <span class="px-2.5 py-1 text-xs font-bold uppercase tracking-wider rounded-lg {{ $statusColors[$order->status] ?? 'bg-cream-200 text-muted' }}">
                                            {{ $order->status }}
                                        </span>
                                        <span class="text-sm font-bold text-bark-600">₱{{ number_format($order->total, 2) }}</span>
                                        <svg class="w-4 h-4 text-bark-200 group-hover:text-bark-400 transition" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" d="m8.25 4.5 7.5 7.5-7.5 7.5" />
                                        </svg>
                                    </div>
                                </a>
                            @endforeach
                        </div>
                    @endif
                </div>
            </div>

            {{-- ═══════════════════════════════════════════ --}}
            {{-- Edit Profile Tab --}}
            {{-- ═══════════════════════════════════════════ --}}
            <div x-show="tab === 'edit'" x-transition:enter="transition ease-out duration-200" x-transition:enter-start="opacity-0 translate-y-2" x-transition:enter-end="opacity-100 translate-y-0" class="space-y-6">

                {{-- Update Profile Information --}}
                <section class="bg-cream-50 rounded-2xl shadow-sm shadow-bark-200/10 ring-1 ring-bark-200/10 p-6 sm:p-8">
                    <header class="mb-6">
                        <div class="flex items-center gap-3">
                            <div class="w-10 h-10 rounded-xl bg-bark-300/10 flex items-center justify-center">
                                <svg class="w-5 h-5 text-bark-300" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" d="M15.75 6a3.75 3.75 0 1 1-7.5 0 3.75 3.75 0 0 1 7.5 0ZM4.501 20.118a7.5 7.5 0 0 1 14.998 0A17.933 17.933 0 0 1 12 21.75c-2.676 0-5.216-.584-7.499-1.632Z" />
                                </svg>
                            </div>
                            <div>
                                <h2 class="text-lg font-serif font-bold text-bark-600">{{ __('Profile Information') }}</h2>
                                <p class="text-sm text-muted">{{ __("Update your account's name and email address.") }}</p>
                            </div>
                        </div>
                    </header>

                    <form id="send-verification" method="post" action="{{ route('verification.send') }}">
                        @csrf
                    </form>

                    <form method="post" action="{{ route('profile.update') }}" class="space-y-5 max-w-xl">
                        @csrf
                        @method('patch')

                        <div>
                            <x-input-label for="name" :value="__('Name')" />
                            <x-text-input id="name" name="name" type="text" class="mt-1 block w-full" :value="old('name', $user->name)" required autofocus autocomplete="name" />
                            <x-input-error class="mt-2" :messages="$errors->get('name')" />
                        </div>

                        <div>
                            <x-input-label for="email" :value="__('Email')" />
                            <x-text-input id="email" name="email" type="email" class="mt-1 block w-full" :value="old('email', $user->email)" required autocomplete="username" />
                            <x-input-error class="mt-2" :messages="$errors->get('email')" />

                            @if ($user instanceof \Illuminate\Contracts\Auth\MustVerifyEmail && ! $user->hasVerifiedEmail())
                                <div>
                                    <p class="text-sm mt-2 text-bark-600">
                                        {{ __('Your email address is unverified.') }}
                                        <button form="send-verification" class="underline text-sm text-bark-300 hover:text-bark-400 rounded-md focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-bark-300">
                                            {{ __('Click here to re-send the verification email.') }}
                                        </button>
                                    </p>

                                    @if (session('status') === 'verification-link-sent')
                                        <p class="mt-2 font-medium text-sm text-leaf-400">
                                            {{ __('A new verification link has been sent to your email address.') }}
                                        </p>
                                    @endif
                                </div>
                            @endif
                        </div>

                        <div class="flex items-center gap-4 pt-2">
                            <x-primary-button>{{ __('Save Changes') }}</x-primary-button>

                            @if (session('status') === 'profile-updated')
                                <p x-data="{ show: true }" x-show="show" x-transition x-init="setTimeout(() => show = false, 2000)" class="text-sm text-leaf-400 font-medium">
                                    {{ __('Saved.') }}
                                </p>
                            @endif
                        </div>
                    </form>
                </section>
            </div>

            {{-- ═══════════════════════════════════════════ --}}
            {{-- Security Tab --}}
            {{-- ═══════════════════════════════════════════ --}}
            <div x-show="tab === 'security'" x-transition:enter="transition ease-out duration-200" x-transition:enter-start="opacity-0 translate-y-2" x-transition:enter-end="opacity-100 translate-y-0" class="space-y-6">

                {{-- Update Password --}}
                <section class="bg-cream-50 rounded-2xl shadow-sm shadow-bark-200/10 ring-1 ring-bark-200/10 p-6 sm:p-8">
                    <header class="mb-6">
                        <div class="flex items-center gap-3">
                            <div class="w-10 h-10 rounded-xl bg-gold-400/10 flex items-center justify-center">
                                <svg class="w-5 h-5 text-gold-500" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" d="M16.5 10.5V6.75a4.5 4.5 0 1 0-9 0v3.75m-.75 11.25h10.5a2.25 2.25 0 0 0 2.25-2.25v-6.75a2.25 2.25 0 0 0-2.25-2.25H6.75a2.25 2.25 0 0 0-2.25 2.25v6.75a2.25 2.25 0 0 0 2.25 2.25Z" />
                                </svg>
                            </div>
                            <div>
                                <h2 class="text-lg font-serif font-bold text-bark-600">{{ __('Update Password') }}</h2>
                                <p class="text-sm text-muted">{{ __('Use a strong, unique password to keep your account safe.') }}</p>
                            </div>
                        </div>
                    </header>

                    <form method="post" action="{{ route('password.change') }}" class="space-y-5 max-w-xl">
                        @csrf
                        @method('put')

                        <div>
                            <x-input-label for="update_password_current_password" :value="__('Current Password')" />
                            <x-text-input id="update_password_current_password" name="current_password" type="password" class="mt-1 block w-full" autocomplete="current-password" />
                            <x-input-error :messages="$errors->updatePassword->get('current_password')" class="mt-2" />
                        </div>

                        <div>
                            <x-input-label for="update_password_password" :value="__('New Password')" />
                            <x-text-input id="update_password_password" name="password" type="password" class="mt-1 block w-full" autocomplete="new-password" />
                            <x-input-error :messages="$errors->updatePassword->get('password')" class="mt-2" />
                        </div>

                        <div>
                            <x-input-label for="update_password_password_confirmation" :value="__('Confirm Password')" />
                            <x-text-input id="update_password_password_confirmation" name="password_confirmation" type="password" class="mt-1 block w-full" autocomplete="new-password" />
                            <x-input-error :messages="$errors->updatePassword->get('password_confirmation')" class="mt-2" />
                        </div>

                        <div class="flex items-center gap-4 pt-2">
                            <x-primary-button>{{ __('Update Password') }}</x-primary-button>

                            @if (session('status') === 'password-updated')
                                <p x-data="{ show: true }" x-show="show" x-transition x-init="setTimeout(() => show = false, 2000)" class="text-sm text-leaf-400 font-medium">
                                    {{ __('Saved.') }}
                                </p>
                            @endif
                        </div>
                    </form>
                </section>

                {{-- Delete Account --}}
                <section class="bg-cream-50 rounded-2xl shadow-sm shadow-bark-200/10 ring-1 ring-bark-200/10 ring-red-500/5 p-6 sm:p-8">
                    <header class="mb-6">
                        <div class="flex items-center gap-3">
                            <div class="w-10 h-10 rounded-xl bg-red-50 flex items-center justify-center">
                                <svg class="w-5 h-5 text-red-500" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" d="M12 9v3.75m-9.303 3.376c-.866 1.5.217 3.374 1.948 3.374h14.71c1.73 0 2.813-1.874 1.948-3.374L13.949 3.378c-.866-1.5-3.032-1.5-3.898 0L2.697 16.126ZM12 15.75h.007v.008H12v-.008Z" />
                                </svg>
                            </div>
                            <div>
                                <h2 class="text-lg font-serif font-bold text-bark-600">{{ __('Delete Account') }}</h2>
                                <p class="text-sm text-muted">{{ __('Permanently remove your account and all associated data.') }}</p>
                            </div>
                        </div>
                    </header>

                    <p class="text-sm text-muted mb-5 max-w-xl">
                        {{ __('Once your account is deleted, all of its resources and data will be permanently deleted. Before deleting your account, please download any data or information that you wish to retain.') }}
                    </p>

                    <x-danger-button
                        x-data=""
                        x-on:click.prevent="$dispatch('open-modal', 'confirm-user-deletion')"
                    >{{ __('Delete Account') }}</x-danger-button>

                    <x-modal name="confirm-user-deletion" :show="$errors->userDeletion->isNotEmpty()" focusable>
                        <form method="post" action="{{ route('profile.destroy') }}" class="p-6">
                            @csrf
                            @method('delete')

                            <h2 class="text-lg font-serif font-bold text-bark-600">
                                {{ __('Are you sure you want to delete your account?') }}
                            </h2>

                            <p class="mt-2 text-sm text-muted">
                                {{ __('Once your account is deleted, all of its resources and data will be permanently deleted. Please enter your password to confirm you would like to permanently delete your account.') }}
                            </p>

                            <div class="mt-6">
                                <x-input-label for="password" value="{{ __('Password') }}" class="sr-only" />
                                <x-text-input
                                    id="password"
                                    name="password"
                                    type="password"
                                    class="mt-1 block w-3/4"
                                    placeholder="{{ __('Password') }}"
                                />
                                <x-input-error :messages="$errors->userDeletion->get('password')" class="mt-2" />
                            </div>

                            <div class="mt-6 flex justify-end">
                                <x-secondary-button x-on:click="$dispatch('close')">
                                    {{ __('Cancel') }}
                                </x-secondary-button>

                                <x-danger-button class="ms-3">
                                    {{ __('Delete Account') }}
                                </x-danger-button>
                            </div>
                        </form>
                    </x-modal>
                </section>
            </div>

            {{-- ═══════════════════════════════════════════ --}}
            {{-- Settings Tab --}}
            {{-- ═══════════════════════════════════════════ --}}
            <div x-show="tab === 'settings'" x-transition:enter="transition ease-out duration-200" x-transition:enter-start="opacity-0 translate-y-2" x-transition:enter-end="opacity-100 translate-y-0" class="space-y-6">

                {{-- Notification Preferences --}}
                <div class="bg-cream-50 rounded-2xl shadow-sm shadow-bark-200/10 ring-1 ring-bark-200/10 p-6 sm:p-8">
                    <div class="flex items-center gap-3 mb-6">
                        <div class="w-10 h-10 rounded-xl bg-bark-300/10 flex items-center justify-center">
                            <svg class="w-5 h-5 text-bark-300" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" d="M14.857 17.082a23.848 23.848 0 0 0 5.454-1.31A8.967 8.967 0 0 1 18 9.75V9A6 6 0 0 0 6 9v.75a8.967 8.967 0 0 1-2.312 6.022c1.733.64 3.56 1.085 5.455 1.31m5.714 0a24.255 24.255 0 0 1-5.714 0m5.714 0a3 3 0 1 1-5.714 0" />
                            </svg>
                        </div>
                        <div>
                            <h2 class="text-lg font-serif font-bold text-bark-600">Notification Preferences</h2>
                            <p class="text-sm text-muted">Choose which notifications you'd like to receive.</p>
                        </div>
                    </div>

                    <div class="space-y-4">
                        <label class="flex items-center justify-between p-4 rounded-xl bg-cream-100/40 ring-1 ring-bark-200/5 cursor-pointer hover:bg-cream-100 transition">
                            <div class="flex items-center gap-4">
                                <div class="shrink-0 w-10 h-10 rounded-lg bg-gold-400/10 flex items-center justify-center">
                                    <svg class="w-5 h-5 text-gold-500" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" d="M15.75 10.5V6a3.75 3.75 0 1 0-7.5 0v4.5m11.356-1.993 1.263 12c.07.665-.45 1.243-1.119 1.243H4.25a1.125 1.125 0 0 1-1.12-1.243l1.264-12A1.125 1.125 0 0 1 5.513 7.5h12.974c.576 0 1.059.435 1.119 1.007ZM8.625 10.5a.375.375 0 1 1-.75 0 .375.375 0 0 1 .75 0Zm7.5 0a.375.375 0 1 1-.75 0 .375.375 0 0 1 .75 0Z" />
                                    </svg>
                                </div>
                                <div>
                                    <p class="text-sm font-semibold text-bark-600">Order Updates</p>
                                    <p class="text-xs text-muted mt-0.5">Get notified when your order status changes.</p>
                                </div>
                            </div>
                            <div class="relative shrink-0">
                                <input type="checkbox" x-model="settingsNotif.orderUpdates" @change="saveSettings()" class="sr-only peer">
                                <div class="w-11 h-6 bg-bark-200/30 peer-focus:outline-none rounded-full peer peer-checked:after:translate-x-full peer-checked:after:border-white after:content-[''] after:absolute after:top-[2px] after:start-[2px] after:bg-white after:border-bark-200/20 after:border after:rounded-full after:h-5 after:w-5 after:transition-all peer-checked:bg-leaf-400"></div>
                            </div>
                        </label>

                        <label class="flex items-center justify-between p-4 rounded-xl bg-cream-100/40 ring-1 ring-bark-200/5 cursor-pointer hover:bg-cream-100 transition">
                            <div class="flex items-center gap-4">
                                <div class="shrink-0 w-10 h-10 rounded-lg bg-leaf-400/10 flex items-center justify-center">
                                    <svg class="w-5 h-5 text-leaf-400" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" d="M8.25 18.75a1.5 1.5 0 0 1-3 0m3 0a1.5 1.5 0 0 0-3 0m3 0h6m-9 0H3.375a1.125 1.125 0 0 1-1.125-1.125V14.25m17.25 4.5a1.5 1.5 0 0 1-3 0m3 0a1.5 1.5 0 0 0-3 0m3 0h1.125c.621 0 1.143-.504 1.025-1.113a9.004 9.004 0 0 0-5.68-6.638A8.972 8.972 0 0 0 12 5.25c-1.005 0-1.97.164-2.87.467M2.25 14.25V5.625c0-.621.504-1.125 1.125-1.125h11.25c.621 0 1.125.504 1.125 1.125v4.874" />
                                    </svg>
                                </div>
                                <div>
                                    <p class="text-sm font-semibold text-bark-600">Delivery Alerts</p>
                                    <p class="text-xs text-muted mt-0.5">Receive updates about your delivery status.</p>
                                </div>
                            </div>
                            <div class="relative shrink-0">
                                <input type="checkbox" x-model="settingsNotif.deliveryAlerts" @change="saveSettings()" class="sr-only peer">
                                <div class="w-11 h-6 bg-bark-200/30 peer-focus:outline-none rounded-full peer peer-checked:after:translate-x-full peer-checked:after:border-white after:content-[''] after:absolute after:top-[2px] after:start-[2px] after:bg-white after:border-bark-200/20 after:border after:rounded-full after:h-5 after:w-5 after:transition-all peer-checked:bg-leaf-400"></div>
                            </div>
                        </label>

                        <label class="flex items-center justify-between p-4 rounded-xl bg-cream-100/40 ring-1 ring-bark-200/5 cursor-pointer hover:bg-cream-100 transition">
                            <div class="flex items-center gap-4">
                                <div class="shrink-0 w-10 h-10 rounded-lg bg-bark-300/10 flex items-center justify-center">
                                    <svg class="w-5 h-5 text-bark-300" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" d="M9.568 3H5.25A2.25 2.25 0 0 0 3 5.25v4.318c0 .597.237 1.17.659 1.591l9.581 9.581c.699.699 1.78.872 2.607.33a18.095 18.095 0 0 0 5.223-5.223c.542-.827.369-1.908-.33-2.607L11.16 3.66A2.25 2.25 0 0 0 9.568 3Z" />
                                        <path stroke-linecap="round" stroke-linejoin="round" d="M6 6h.008v.008H6V6Z" />
                                    </svg>
                                </div>
                                <div>
                                    <p class="text-sm font-semibold text-bark-600">Promotions & Offers</p>
                                    <p class="text-xs text-muted mt-0.5">Stay updated on special deals and new products.</p>
                                </div>
                            </div>
                            <div class="relative shrink-0">
                                <input type="checkbox" x-model="settingsNotif.promotions" @change="saveSettings()" class="sr-only peer">
                                <div class="w-11 h-6 bg-bark-200/30 peer-focus:outline-none rounded-full peer peer-checked:after:translate-x-full peer-checked:after:border-white after:content-[''] after:absolute after:top-[2px] after:start-[2px] after:bg-white after:border-bark-200/20 after:border after:rounded-full after:h-5 after:w-5 after:transition-all peer-checked:bg-leaf-400"></div>
                            </div>
                        </label>

                        <label class="flex items-center justify-between p-4 rounded-xl bg-cream-100/40 ring-1 ring-bark-200/5 cursor-pointer hover:bg-cream-100 transition">
                            <div class="flex items-center gap-4">
                                <div class="shrink-0 w-10 h-10 rounded-lg bg-bark-300/10 flex items-center justify-center">
                                    <svg class="w-5 h-5 text-bark-300" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" d="M10.5 1.5H8.25A2.25 2.25 0 0 0 6 3.75v16.5a2.25 2.25 0 0 0 2.25 2.25h7.5A2.25 2.25 0 0 0 18 20.25V3.75a2.25 2.25 0 0 0-2.25-2.25H13.5m-3 0V3h3V1.5m-3 0h3m-3 18.75h3" />
                                    </svg>
                                </div>
                                <div>
                                    <p class="text-sm font-semibold text-bark-600">Push Notifications</p>
                                    <p class="text-xs text-muted mt-0.5">Receive real-time notifications in your browser.</p>
                                </div>
                            </div>
                            <div class="relative shrink-0">
                                <input type="checkbox" x-model="settingsNotif.pushEnabled" @change="togglePush()" class="sr-only peer">
                                <div class="w-11 h-6 bg-bark-200/30 peer-focus:outline-none rounded-full peer peer-checked:after:translate-x-full peer-checked:after:border-white after:content-[''] after:absolute after:top-[2px] after:start-[2px] after:bg-white after:border-bark-200/20 after:border after:rounded-full after:h-5 after:w-5 after:transition-all peer-checked:bg-leaf-400"></div>
                            </div>
                        </label>
                    </div>
                </div>

                {{-- Default Delivery Address --}}
                <div class="bg-cream-50 rounded-2xl shadow-sm shadow-bark-200/10 ring-1 ring-bark-200/10 p-6 sm:p-8">
                    <div class="flex items-center gap-3 mb-6">
                        <div class="w-10 h-10 rounded-xl bg-leaf-400/10 flex items-center justify-center">
                            <svg class="w-5 h-5 text-leaf-400" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" d="M15 10.5a3 3 0 1 1-6 0 3 3 0 0 1 6 0Z" />
                                <path stroke-linecap="round" stroke-linejoin="round" d="M19.5 10.5c0 7.142-7.5 11.25-7.5 11.25S4.5 17.642 4.5 10.5a7.5 7.5 0 1 1 15 0Z" />
                            </svg>
                        </div>
                        <div>
                            <h2 class="text-lg font-serif font-bold text-bark-600">Default Delivery Address</h2>
                            <p class="text-sm text-muted">Set your preferred delivery address for faster checkout.</p>
                        </div>
                    </div>

                    <form @submit.prevent="saveAddress()" class="space-y-5">
                        <div class="grid grid-cols-1 sm:grid-cols-2 gap-5">
                            <div>
                                <x-input-label for="settings_address" value="Street Address" />
                                <x-text-input id="settings_address" type="text" class="mt-1 block w-full" x-model="settingsDelivery.addressLine" placeholder="123 Bakery Street" />
                            </div>
                            <div>
                                <x-input-label for="settings_barangay" value="Barangay" />
                                <x-text-input id="settings_barangay" type="text" class="mt-1 block w-full" x-model="settingsDelivery.barangay" placeholder="Barangay Name" />
                            </div>
                            <div>
                                <x-input-label for="settings_city" value="City / Municipality" />
                                <x-text-input id="settings_city" type="text" class="mt-1 block w-full" x-model="settingsDelivery.city" placeholder="City" />
                            </div>
                            <div>
                                <x-input-label for="settings_phone" value="Contact Number" />
                                <x-text-input id="settings_phone" type="tel" class="mt-1 block w-full" x-model="settingsDelivery.phone" placeholder="09XX XXX XXXX" />
                            </div>
                        </div>

                        <div>
                            <x-input-label for="settings_landmark" value="Landmark / Notes" />
                            <textarea id="settings_landmark" x-model="settingsDelivery.landmark" rows="3" placeholder="Near the church, gate is blue, etc."
                                class="mt-1 block w-full rounded-xl border-bark-200/30 bg-white shadow-sm focus:border-bark-300 focus:ring-bark-300/30 text-sm text-bark-600 placeholder-bark-200/60"></textarea>
                        </div>

                        <div class="flex items-center gap-3">
                            <x-primary-button type="submit">{{ __('Save Address') }}</x-primary-button>
                            <p x-show="addressSaved" x-transition class="text-sm text-leaf-500 font-medium">Saved!</p>
                        </div>
                    </form>
                </div>

                {{-- Delivery Preferences --}}
                <div class="bg-cream-50 rounded-2xl shadow-sm shadow-bark-200/10 ring-1 ring-bark-200/10 p-6 sm:p-8">
                    <div class="flex items-center gap-3 mb-6">
                        <div class="w-10 h-10 rounded-xl bg-bark-300/10 flex items-center justify-center">
                            <svg class="w-5 h-5 text-bark-300" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" d="M12 6v6h4.5m4.5 0a9 9 0 1 1-18 0 9 9 0 0 1 18 0Z" />
                            </svg>
                        </div>
                        <div>
                            <h2 class="text-lg font-serif font-bold text-bark-600">Delivery Preferences</h2>
                            <p class="text-sm text-muted">Customize how your deliveries are handled.</p>
                        </div>
                    </div>

                    <div class="space-y-4">
                        <label class="flex items-center justify-between p-4 rounded-xl bg-cream-100/40 ring-1 ring-bark-200/5 cursor-pointer hover:bg-cream-100 transition">
                            <div>
                                <p class="text-sm font-semibold text-bark-600">Leave at Door</p>
                                <p class="text-xs text-muted mt-0.5">Rider can leave the order at your door if you're not available.</p>
                            </div>
                            <div class="relative shrink-0">
                                <input type="checkbox" x-model="settingsDelivery.leaveAtDoor" @change="saveSettings()" class="sr-only peer">
                                <div class="w-11 h-6 bg-bark-200/30 peer-focus:outline-none rounded-full peer peer-checked:after:translate-x-full peer-checked:after:border-white after:content-[''] after:absolute after:top-[2px] after:start-[2px] after:bg-white after:border-bark-200/20 after:border after:rounded-full after:h-5 after:w-5 after:transition-all peer-checked:bg-leaf-400"></div>
                            </div>
                        </label>

                        <label class="flex items-center justify-between p-4 rounded-xl bg-cream-100/40 ring-1 ring-bark-200/5 cursor-pointer hover:bg-cream-100 transition">
                            <div>
                                <p class="text-sm font-semibold text-bark-600">SMS Delivery Updates</p>
                                <p class="text-xs text-muted mt-0.5">Receive text messages when your order is on the way.</p>
                            </div>
                            <div class="relative shrink-0">
                                <input type="checkbox" x-model="settingsDelivery.smsUpdates" @change="saveSettings()" class="sr-only peer">
                                <div class="w-11 h-6 bg-bark-200/30 peer-focus:outline-none rounded-full peer peer-checked:after:translate-x-full peer-checked:after:border-white after:content-[''] after:absolute after:top-[2px] after:start-[2px] after:bg-white after:border-bark-200/20 after:border after:rounded-full after:h-5 after:w-5 after:transition-all peer-checked:bg-leaf-400"></div>
                            </div>
                        </label>
                    </div>
                </div>

                {{-- Language & Display --}}
                <div class="bg-cream-50 rounded-2xl shadow-sm shadow-bark-200/10 ring-1 ring-bark-200/10 p-6 sm:p-8">
                    <div class="flex items-center gap-3 mb-6">
                        <div class="w-10 h-10 rounded-xl bg-gold-400/10 flex items-center justify-center">
                            <svg class="w-5 h-5 text-gold-500" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" d="m10.5 21 5.25-11.25L21 21m-9-3h7.5M3 5.621a48.474 48.474 0 0 1 6-.371m0 0c1.12 0 2.233.038 3.334.114M9 5.25V3m3.334 2.364C11.176 10.658 7.69 15.08 3 17.502m9.334-12.138c.896.061 1.785.147 2.666.257m-4.589 8.495a18.023 18.023 0 0 1-3.827-5.802" />
                            </svg>
                        </div>
                        <div>
                            <h2 class="text-lg font-serif font-bold text-bark-600">Language & Display</h2>
                            <p class="text-sm text-muted">Customize how the app looks and feels.</p>
                        </div>
                    </div>

                    <div class="mb-6">
                        <p class="text-xs font-semibold uppercase tracking-wider text-muted mb-3">Language</p>
                        <div class="grid grid-cols-1 sm:grid-cols-3 gap-3">
                            <template x-for="lang in [
                                { code: 'en', label: 'English', flag: '🇺🇸' },
                                { code: 'fil', label: 'Filipino', flag: '🇵🇭' },
                                { code: 'ceb', label: 'Cebuano', flag: '🇵🇭' }
                            ]" :key="lang.code">
                                <button @click="settingsAppearance.language = lang.code; saveSettings()"
                                        :class="settingsAppearance.language === lang.code
                                            ? 'ring-2 ring-bark-300 bg-cream-100 border-bark-300'
                                            : 'ring-1 ring-bark-200/10 bg-cream-100/40 hover:bg-cream-100'"
                                        class="flex items-center gap-3 p-4 rounded-xl transition cursor-pointer">
                                    <span class="text-2xl" x-text="lang.flag"></span>
                                    <span class="text-sm font-semibold text-bark-600" x-text="lang.label"></span>
                                    <svg x-show="settingsAppearance.language === lang.code" class="w-5 h-5 text-leaf-500 ml-auto" fill="currentColor" viewBox="0 0 20 20">
                                        <path fill-rule="evenodd" d="M10 18a8 8 0 1 0 0-16 8 8 0 0 0 0 16Zm3.857-9.809a.75.75 0 0 0-1.214-.882l-3.483 4.79-1.88-1.88a.75.75 0 1 0-1.06 1.061l2.5 2.5a.75.75 0 0 0 1.137-.089l4-5.5Z" clip-rule="evenodd" />
                                    </svg>
                                </button>
                            </template>
                        </div>
                    </div>

                    <div class="space-y-4">
                        <p class="text-xs font-semibold uppercase tracking-wider text-muted">Display</p>
                        <label class="flex items-center justify-between p-4 rounded-xl bg-cream-100/40 ring-1 ring-bark-200/5 cursor-pointer hover:bg-cream-100 transition">
                            <div>
                                <p class="text-sm font-semibold text-bark-600">Show Product Prices</p>
                                <p class="text-xs text-muted mt-0.5">Display prices on product cards in the catalog.</p>
                            </div>
                            <div class="relative shrink-0">
                                <input type="checkbox" x-model="settingsAppearance.showPrices" @change="saveSettings()" class="sr-only peer">
                                <div class="w-11 h-6 bg-bark-200/30 peer-focus:outline-none rounded-full peer peer-checked:after:translate-x-full peer-checked:after:border-white after:content-[''] after:absolute after:top-[2px] after:start-[2px] after:bg-white after:border-bark-200/20 after:border after:rounded-full after:h-5 after:w-5 after:transition-all peer-checked:bg-leaf-400"></div>
                            </div>
                        </label>

                        <label class="flex items-center justify-between p-4 rounded-xl bg-cream-100/40 ring-1 ring-bark-200/5 cursor-pointer hover:bg-cream-100 transition">
                            <div>
                                <p class="text-sm font-semibold text-bark-600">Compact Order View</p>
                                <p class="text-xs text-muted mt-0.5">Show orders in a more condensed layout.</p>
                            </div>
                            <div class="relative shrink-0">
                                <input type="checkbox" x-model="settingsAppearance.compactOrders" @change="saveSettings()" class="sr-only peer">
                                <div class="w-11 h-6 bg-bark-200/30 peer-focus:outline-none rounded-full peer peer-checked:after:translate-x-full peer-checked:after:border-white after:content-[''] after:absolute after:top-[2px] after:start-[2px] after:bg-white after:border-bark-200/20 after:border after:rounded-full after:h-5 after:w-5 after:transition-all peer-checked:bg-leaf-400"></div>
                            </div>
                        </label>
                    </div>
                </div>

                {{-- Reset Settings --}}
                <div class="bg-cream-50 rounded-2xl shadow-sm shadow-bark-200/10 ring-1 ring-red-200/20 p-6 sm:p-8">
                    <div class="flex items-center justify-between">
                        <div class="flex items-center gap-3">
                            <div class="w-10 h-10 rounded-xl bg-red-50 flex items-center justify-center">
                                <svg class="w-5 h-5 text-red-500" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" d="M16.023 9.348h4.992v-.001M2.985 19.644v-4.992m0 0h4.992m-4.993 0 3.181 3.183a8.25 8.25 0 0 0 13.803-3.7M4.031 9.865a8.25 8.25 0 0 1 13.803-3.7l3.181 3.182" />
                                </svg>
                            </div>
                            <div>
                                <h2 class="text-lg font-serif font-bold text-bark-600">Reset All Settings</h2>
                                <p class="text-sm text-muted">Restore all preferences to their default values.</p>
                            </div>
                        </div>
                        <button @click="if(confirm('Reset all settings to defaults?')) resetSettings()"
                                class="px-4 py-2 text-sm font-semibold text-red-600 bg-white border border-red-200 rounded-xl hover:bg-red-50 transition">
                            Reset
                        </button>
                    </div>
                </div>
            </div>

        </div>
    </div>

    @push('scripts')
    <script>
    function settingsData() {
        const storageKey = 'tres_marias_settings';
        const defaults = {
            notifications: { orderUpdates: true, deliveryAlerts: true, promotions: false, pushEnabled: false },
            delivery: { addressLine: '', barangay: '', city: '', phone: '', landmark: '', leaveAtDoor: false, smsUpdates: true },
            appearance: { language: 'en', showPrices: true, compactOrders: false },
        };
        let saved = {};
        try { saved = JSON.parse(localStorage.getItem(storageKey) || '{}'); } catch (e) { saved = {}; }

        return {
            addressSaved: false,
            settingsNotif: { ...defaults.notifications, ...(saved.notifications || {}) },
            settingsDelivery: { ...defaults.delivery, ...(saved.delivery || {}) },
            settingsAppearance: { ...defaults.appearance, ...(saved.appearance || {}) },

            saveSettings() {
                localStorage.setItem(storageKey, JSON.stringify({
                    notifications: { ...this.settingsNotif },
                    delivery: { ...this.settingsDelivery },
                    appearance: { ...this.settingsAppearance },
                }));
            },
            saveAddress() {
                this.saveSettings();
                this.addressSaved = true;
                setTimeout(() => this.addressSaved = false, 2500);
            },
            togglePush() {
                if (this.settingsNotif.pushEnabled && 'Notification' in window) {
                    Notification.requestPermission().then(p => {
                        if (p !== 'granted') this.settingsNotif.pushEnabled = false;
                        this.saveSettings();
                    });
                } else { this.saveSettings(); }
            },
            resetSettings() {
                this.settingsNotif = { ...defaults.notifications };
                this.settingsDelivery = { ...defaults.delivery };
                this.settingsAppearance = { ...defaults.appearance };
                localStorage.removeItem(storageKey);
            },
        };
    }
    </script>
    @endpush
</x-app-layout>