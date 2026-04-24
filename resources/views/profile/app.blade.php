<x-app-layout>
    <x-slot name="header">
        <h2 class="font-serif font-bold text-xl text-bark-600 leading-tight">
            {{ __('My Profile') }}
        </h2>
    </x-slot>

    <div class="py-10">
        <div class="max-w-4xl mx-auto px-4 sm:px-6 lg:px-8 space-y-8">

            {{-- Profile Header Card --}}
            <div class="relative overflow-hidden bg-gradient-to-br from-bark-300 to-bark-400 rounded-3xl shadow-xl shadow-bark-400/20 p-8 sm:p-10 text-white">
                {{-- Decorative circles --}}
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
                    <div class="text-center sm:text-left">
                        <h3 class="text-2xl font-serif font-bold tracking-tight">
                            {{ Auth::user()->name }}
                        </h3>
                        <p class="mt-1 text-cream-200/80 text-sm font-medium">
                            {{ Auth::user()->email }}
                        </p>
                        <span class="inline-block mt-3 px-3 py-1 text-xs font-bold uppercase tracking-widest rounded-full bg-white/15 border border-white/20 backdrop-blur-sm">
                            {{ Auth::user()->role ?? 'Customer' }}
                        </span>
                    </div>
                </div>
            </div>

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

                        <div class="mt-6 flex justify-end gap-3">
                            <x-secondary-button x-on:click="$dispatch('close')">
                                {{ __('Cancel') }}
                            </x-secondary-button>

                            <x-danger-button>
                                {{ __('Delete Account') }}
                            </x-danger-button>
                        </div>
                    </form>
                </x-modal>
            </section>

        </div>
    </div>
</x-app-layout>