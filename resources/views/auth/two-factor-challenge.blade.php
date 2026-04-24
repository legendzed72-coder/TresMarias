@extends('layouts.guest')

@section('content')
    <h2 class="guest-heading">Two-factor authentication 🔐</h2>
    <p class="guest-subtext">Enter the authentication code from your two-factor app to confirm access to your account.</p>

    <form method="POST" action="{{ route('two-factor.login') }}">
        @csrf

        <div class="field-group" style="margin-bottom: 1.5rem;">
            <label class="auth-label" for="code">{{ __('Code') }}</label>
            <input id="code" class="auth-input" type="text" name="code" required autofocus placeholder="6-digit code">
            @if ($errors->get('code'))
                <div class="auth-error"><ul>@foreach ($errors->get('code') as $message)<li>{{ $message }}</li>@endforeach</ul></div>
            @endif
        </div>

        <button type="submit" class="auth-btn" style="width: 100%;">{{ __('Log in') }}</button>
    </form>

    <div class="auth-divider">or use a recovery code</div>

    <form method="POST" action="{{ route('two-factor.login') }}">
        @csrf

        <div class="field-group" style="margin-bottom: 1.5rem;">
            <label class="auth-label" for="recovery_code">{{ __('Recovery Code') }}</label>
            <input id="recovery_code" class="auth-input" type="text" name="recovery_code" required placeholder="Enter recovery code">
            @if ($errors->get('recovery_code'))
                <div class="auth-error"><ul>@foreach ($errors->get('recovery_code') as $message)<li>{{ $message }}</li>@endforeach</ul></div>
            @endif
        </div>

        <button type="submit" class="auth-btn" style="width: 100%;">{{ __('Log in using recovery code') }}</button>
    </form>
@endsection

