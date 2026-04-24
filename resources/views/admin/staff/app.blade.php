<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
    <head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <meta name="csrf-token" content="{{ csrf_token() }}">
        <meta name="theme-color" content="#8b4513">

        <title>{{ config('app.name', 'Tres Marias') }} — Staff</title>

        <!-- Fonts -->
        <link rel="preconnect" href="https://fonts.googleapis.com">
        <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
        <link href="https://fonts.googleapis.com/css2?family=Fraunces:wght@600;700;800&family=Manrope:wght@400;500;600;700;800&display=swap" rel="stylesheet">

        <!-- Favicon -->
        <link rel="icon" type="image/png" sizes="32x32" href="{{ asset('favicon-32x32.png') }}">
        <link rel="icon" type="image/png" sizes="16x16" href="{{ asset('favicon-16x16.png') }}">
        <link rel="apple-touch-icon" sizes="180x180" href="{{ asset('apple-touch-icon.png') }}">

        @vite(['resources/css/app.css', 'resources/js/app.js'])
    </head>
    <body class="font-sans antialiased">
        <div class="min-h-screen bg-cream-200">

            {{-- Staff Navigation --}}
            <nav x-data="{ open: false }" class="bg-cream-50 border-b border-bark-200/15 shadow-sm shadow-bark-200/5">
                <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
                    <div class="flex justify-between h-16">
                        <div class="flex">
                            <div class="shrink-0 flex items-center">
                                <a href="{{ route('staff.dashboard') }}" class="flex items-center gap-2">
                                    <x-application-logo class="block h-9 w-auto" />
                                    <span class="hidden sm:inline text-xs font-bold text-bark-300 bg-bark-100 px-2 py-0.5 rounded-full uppercase tracking-wider">Staff</span>
                                </a>
                            </div>

                            <div class="hidden space-x-8 sm:-my-px sm:ms-10 sm:flex">
                                <x-nav-link :href="route('staff.dashboard')" :active="request()->routeIs('staff.dashboard')">
                                    {{ __('Dashboard') }}
                                </x-nav-link>
                                <x-nav-link :href="route('staff.pos')" :active="request()->routeIs('staff.pos')">
                                    {{ __('POS') }}
                                </x-nav-link>
                                <x-nav-link :href="route('staff.orders')" :active="request()->routeIs('staff.orders')">
                                    {{ __('Orders') }}
                                </x-nav-link>
                                <x-nav-link :href="route('staff.inventory')" :active="request()->routeIs('staff.inventory')">
                                    {{ __('Inventory') }}
                                </x-nav-link>
                                <x-nav-link :href="route('staff.deliveries')" :active="request()->routeIs('staff.deliveries')">
                                    {{ __('Deliveries') }}
                                </x-nav-link>
                            </div>
                        </div>

                        <div class="hidden sm:flex sm:items-center sm:ms-6">
                            <x-dropdown align="right" width="48">
                                <x-slot name="trigger">
                                    <button class="inline-flex items-center px-3 py-2 border border-transparent text-sm leading-4 font-semibold rounded-xl text-bark-500 bg-cream-50 hover:text-bark-600 hover:bg-cream-200/50 focus:outline-none transition ease-in-out duration-200">
                                        <div>{{ Auth::user()->name }}</div>
                                        <div class="ms-1">
                                            <svg class="fill-current h-4 w-4" viewBox="0 0 20 20">
                                                <path fill-rule="evenodd" d="M5.293 7.293a1 1 0 011.414 0L10 10.586l3.293-3.293a1 1 0 111.414 1.414l-4 4a1 1 0 01-1.414 0l-4-4a1 1 0 010-1.414z" clip-rule="evenodd" />
                                            </svg>
                                        </div>
                                    </button>
                                </x-slot>
                                <x-slot name="content">
                                    <x-dropdown-link :href="route('profile.edit')">
                                        {{ __('Profile') }}
                                    </x-dropdown-link>
                                    <form method="POST" action="{{ route('logout') }}">
                                        @csrf
                                        <x-dropdown-link :href="route('logout')" onclick="event.preventDefault(); this.closest('form').submit();">
                                            {{ __('Log Out') }}
                                        </x-dropdown-link>
                                    </form>
                                </x-slot>
                            </x-dropdown>
                        </div>

                        <div class="-me-2 flex items-center sm:hidden">
                            <button @click="open = ! open" class="inline-flex items-center justify-center p-2 rounded-xl text-bark-300 hover:text-bark-500 hover:bg-cream-200/50 focus:outline-none transition duration-200">
                                <svg class="h-6 w-6" stroke="currentColor" fill="none" viewBox="0 0 24 24">
                                    <path :class="{'hidden': open, 'inline-flex': ! open }" class="inline-flex" stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6h16M4 12h16M4 18h16" />
                                    <path :class="{'hidden': ! open, 'inline-flex': open }" class="hidden" stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12" />
                                </svg>
                            </button>
                        </div>
                    </div>
                </div>

                <div :class="{'block': open, 'hidden': ! open}" class="hidden sm:hidden">
                    <div class="pt-2 pb-3 space-y-1">
                        <x-responsive-nav-link :href="route('staff.dashboard')" :active="request()->routeIs('staff.dashboard')">
                            {{ __('Dashboard') }}
                        </x-responsive-nav-link>
                        <x-responsive-nav-link :href="route('staff.pos')" :active="request()->routeIs('staff.pos')">
                            {{ __('POS') }}
                        </x-responsive-nav-link>
                        <x-responsive-nav-link :href="route('staff.orders')" :active="request()->routeIs('staff.orders')">
                            {{ __('Orders') }}
                        </x-responsive-nav-link>
                        <x-responsive-nav-link :href="route('staff.inventory')" :active="request()->routeIs('staff.inventory')">
                            {{ __('Inventory') }}
                        </x-responsive-nav-link>
                        <x-responsive-nav-link :href="route('staff.deliveries')" :active="request()->routeIs('staff.deliveries')">
                            {{ __('Deliveries') }}
                        </x-responsive-nav-link>
                    </div>

                    <div class="pt-4 pb-1 border-t border-bark-200/15">
                        <div class="px-4">
                            <div class="font-semibold text-base text-bark-600">{{ Auth::user()->name }}</div>
                            <div class="font-medium text-sm text-muted">{{ Auth::user()->email }}</div>
                        </div>
                        <div class="mt-3 space-y-1">
                            <x-responsive-nav-link :href="route('profile.edit')">
                                {{ __('Profile') }}
                            </x-responsive-nav-link>
                            <form method="POST" action="{{ route('logout') }}">
                                @csrf
                                <x-responsive-nav-link :href="route('logout')" onclick="event.preventDefault(); this.closest('form').submit();">
                                    {{ __('Log Out') }}
                                </x-responsive-nav-link>
                            </form>
                        </div>
                    </div>
                </div>
            </nav>

            @isset($header)
                <header class="bg-cream-50 shadow-sm shadow-bark-200/10 border-b border-bark-200/10">
                    <div class="max-w-7xl mx-auto py-6 px-4 sm:px-6 lg:px-8">
                        {{ $header }}
                    </div>
                </header>
            @endisset

            <main>
                {{ $slot }}
            </main>
        </div>

        @stack('scripts')
    </body>
</html>
