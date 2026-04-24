@extends('layouts.guest')

@section('content')
    <h2 class="guest-heading">Confirm your password 🔒</h2>
    <p class="guest-subtext">This is a secure area. Please confirm your password before continuing.</p>

    <form method="POST" action="{{ route('password.confirm') }}">
        @csrf

        <div class="field-group" style="margin-bottom: 1.5rem;">
            <label class="auth-label" for="password">{{ __('Password') }}</label>
            <input id="password" class="auth-input" type="password" name="password" required autocomplete="current-password" placeholder="Enter your password">
            @if ($errors->get('password'))
                <div class="auth-error"><ul>@foreach ($errors->get('password') as $message)<li>{{ $message }}</li>@endforeach</ul></div>
            @endif
        </div>

        <button type="submit" class="auth-btn" style="width: 100%;">{{ __('Confirm') }}</button>
    </form>
@endsection
