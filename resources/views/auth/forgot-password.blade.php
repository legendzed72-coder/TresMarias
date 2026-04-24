@extends('layouts.guest')

@section('content')
    <h2 class="guest-heading">Reset your password</h2>
    <p class="guest-subtext">No worries! Enter your email address and we'll send you a link to reset your password.</p>

    @if (session('status'))
        <div class="auth-status">{{ session('status') }}</div>
    @endif

    <form method="POST" action="{{ route('password.email') }}">
        @csrf

        <div class="field-group" style="margin-bottom: 1.5rem;">
            <label class="auth-label" for="email">{{ __('Email') }}</label>
            <input id="email" class="auth-input" type="email" name="email" value="{{ old('email') }}" required autofocus placeholder="you@example.com">
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

        <button type="submit" class="auth-btn" style="width: 100%;">{{ __('Email Password Reset Link') }}</button>
    </form>

    <p class="guest-footer-note">
        Remember your password? <a class="auth-link" href="{{ route('login') }}">Back to login</a>
    </p>
@endsection
