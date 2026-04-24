@extends('layouts.guest')

@section('content')
    <h2 class="guest-heading">Create your account 🧁</h2>
    <p class="guest-subtext">Join Tres Marias and start ordering fresh breads and pastries delivered to your doorstep.</p>

    <form method="POST" action="{{ route('register') }}">
        @csrf

        <!-- Name -->
        <div class="field-group">
            <label class="auth-label" for="name">{{ __('Name') }}</label>
            <input id="name" class="auth-input" type="text" name="name" value="{{ old('name') }}" required autofocus autocomplete="name" placeholder="Your full name">
            @if ($errors->get('name'))
                <div class="auth-error">
                    <ul>
                        @foreach ($errors->get('name') as $message)
                            <li>{{ $message }}</li>
                        @endforeach
                    </ul>
                </div>
            @endif
        </div>

        <!-- Email Address -->
        <div class="field-group">
            <label class="auth-label" for="email">{{ __('Email') }}</label>
            <input id="email" class="auth-input" type="email" name="email" value="{{ old('email') }}" required autocomplete="username" placeholder="you@example.com">
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
            <input id="password" class="auth-input" type="password" name="password" required autocomplete="new-password" placeholder="Create a password">
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

        <!-- Confirm Password -->
        <div class="field-group" style="margin-bottom: 1.5rem;">
            <label class="auth-label" for="password_confirmation">{{ __('Confirm Password') }}</label>
            <input id="password_confirmation" class="auth-input" type="password" name="password_confirmation" required autocomplete="new-password" placeholder="Confirm your password">
            @if ($errors->get('password_confirmation'))
                <div class="auth-error">
                    <ul>
                        @foreach ($errors->get('password_confirmation') as $message)
                            <li>{{ $message }}</li>
                        @endforeach
                    </ul>
                </div>
            @endif
        </div>

        <button type="submit" class="auth-btn" style="width: 100%;">{{ __('Create account') }}</button>
    </form>

    <div class="auth-divider">or</div>

    <p class="guest-footer-note" style="margin-top: 0;">
        Already have an account? <a class="auth-link" href="{{ route('login') }}">Sign in</a>
    </p>
@endsection
