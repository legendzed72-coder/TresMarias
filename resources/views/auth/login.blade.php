@extends('layouts.guest')

@section('content')
    <h2 class="guest-heading">Welcome back 🍞</h2>
    <p class="guest-subtext">Sign in to your Tres Marias account to continue ordering fresh baked goods.</p>

    <!-- Session Status -->
    @if (session('status'))
        <div class="auth-status">{{ session('status') }}</div>
    @endif

    <form method="POST" action="{{ route('login') }}">
        @csrf

        <!-- Email Address -->
        <div class="field-group">
            <label class="auth-label" for="email">{{ __('Email') }}</label>
            <input id="email" class="auth-input" type="email" name="email" value="{{ old('email') }}" required autofocus autocomplete="username" placeholder="you@example.com">
            @if ($errors->get('email'))
                <div class="auth-error">
                    <ul>
                        @foreach ($errors->get('email') as $message)
                            <li>{{ $message }}</li>
                        @endforeach
                    </ul>
                </div>
            @endif
        </div>

        <!-- Password -->
        <div class="field-group">
            <label class="auth-label" for="password">{{ __('Password') }}</label>
            <input id="password" class="auth-input" type="password" name="password" required autocomplete="current-password" placeholder="Enter your password">
            @if ($errors->get('password'))
                <div class="auth-error">
                    <ul>
                        @foreach ($errors->get('password') as $message)
                            <li>{{ $message }}</li>
                        @endforeach
                    </ul>
                </div>
            @endif
        </div>

        <!-- Remember Me -->
        <div class="field-group" style="margin-bottom: 1.5rem;">
            <label class="auth-checkbox-label" for="remember_me">
                <input id="remember_me" type="checkbox" name="remember">
                <span>{{ __('Remember me') }}</span>
            </label>
        </div>

        <button type="submit" class="auth-btn" style="width: 100%;">{{ __('Log in') }}</button>

        <div style="display: flex; align-items: center; justify-content: space-between; flex-wrap: wrap; gap: 0.5rem; margin-top: 1rem;">
            @if (Route::has('password.request'))
                <a class="auth-link" href="{{ route('password.request') }}">{{ __('Forgot your password?') }}</a>
            @endif
        </div>
    </form>

    <div class="auth-divider">or</div>

    <p class="guest-footer-note" style="margin-top: 0;">
        Don't have an account? <a class="auth-link" href="{{ route('register') }}">Create one now</a>
    </p>
@endsection
