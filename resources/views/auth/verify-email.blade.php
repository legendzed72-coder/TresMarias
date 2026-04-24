@extends('layouts.guest')

@section('content')
    <h2 class="guest-heading">Verify your email ✉️</h2>
    <p class="guest-subtext">Thanks for signing up! Please verify your email address by clicking the link we just sent you. Didn't receive it? We'll gladly send another.</p>

    @if (session('status') == 'verification-link-sent')
        <div class="auth-status">A new verification link has been sent to your email address.</div>
    @endif

    <div style="display: flex; align-items: center; justify-content: space-between; flex-wrap: wrap; gap: 0.75rem; margin-top: 0.5rem;">
        <form method="POST" action="{{ route('verification.send') }}">
            @csrf
            <button type="submit" class="auth-btn">{{ __('Resend Verification Email') }}</button>
        </form>

        <form method="POST" action="{{ route('logout') }}">
            @csrf
            <button type="submit" class="auth-link" style="background: none; border: none; cursor: pointer; font-family: inherit;">{{ __('Log Out') }}</button>
        </form>
    </div>
@endsection
