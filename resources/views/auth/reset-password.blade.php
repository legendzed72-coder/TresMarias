@extends('layouts.guest')

@section('content')
    <h2 class="guest-heading">Set a new password</h2>
    <p class="guest-subtext">Choose a strong new password for your Tres Marias account.</p>

    <form method="POST" action="{{ route('password.update') }}">
        @csrf

        <input type="hidden" name="token" value="{{ $request->route('token') }}">

        <div class="field-group">
            <label class="auth-label" for="email">{{ __('Email') }}</label>
            <input id="email" class="auth-input" type="email" name="email" value="{{ old('email', $request->email) }}" required autofocus autocomplete="username">
            @if ($errors->get('email'))
                <div class="auth-error"><ul>@foreach ($errors->get('email') as $message)<li>{{ $message }}</li>@endforeach</ul></div>
            @endif
        </div>

        <div class="field-group">
            <label class="auth-label" for="password">{{ __('Password') }}</label>
            <input id="password" class="auth-input" type="password" name="password" required autocomplete="new-password" placeholder="New password">
            @if ($errors->get('password'))
                <div class="auth-error"><ul>@foreach ($errors->get('password') as $message)<li>{{ $message }}</li>@endforeach</ul></div>
            @endif
        </div>

        <div class="field-group" style="margin-bottom: 1.5rem;">
            <label class="auth-label" for="password_confirmation">{{ __('Confirm Password') }}</label>
            <input id="password_confirmation" class="auth-input" type="password" name="password_confirmation" required autocomplete="new-password" placeholder="Confirm new password">
            @if ($errors->get('password_confirmation'))
                <div class="auth-error"><ul>@foreach ($errors->get('password_confirmation') as $message)<li>{{ $message }}</li>@endforeach</ul></div>
            @endif
        </div>

        <button type="submit" class="auth-btn" style="width: 100%;">{{ __('Reset Password') }}</button>
    </form>
@endsection
