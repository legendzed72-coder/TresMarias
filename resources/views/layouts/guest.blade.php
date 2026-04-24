<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
    <head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <meta name="csrf-token" content="{{ csrf_token() }}">
        <meta name="theme-color" content="#8b4513">

        <title>{{ config('app.name', 'Tres Marias') }}</title>

        <!-- Fonts -->
        <link rel="preconnect" href="https://fonts.googleapis.com">
        <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
        <link href="https://fonts.googleapis.com/css2?family=Fraunces:wght@600;700;800&family=Manrope:wght@400;500;600;700;800&display=swap" rel="stylesheet">

        <!-- Favicon -->
        <link rel="icon" type="image/png" sizes="32x32" href="{{ asset('favicon-32x32.png') }}">
        <link rel="icon" type="image/png" sizes="16x16" href="{{ asset('favicon-16x16.png') }}">
        <link rel="apple-touch-icon" sizes="180x180" href="{{ asset('apple-touch-icon.png') }}">

        <!-- Scripts -->
        @vite(['resources/css/app.css', 'resources/js/app.js'])

        <style>
            :root {
                --bg: #f7ecdc;
                --surface: rgba(255, 249, 240, 0.84);
                --surface-strong: #fff8ee;
                --text: #2b1d16;
                --muted: #6e5a4c;
                --line: rgba(111, 73, 39, 0.12);
                --accent: #b65a2d;
                --accent-strong: #8f3f1b;
                --accent-soft: #f0c9a9;
                --gold: #e0a93b;
                --leaf: #5f8b5b;
                --shadow: 0 24px 60px rgba(84, 43, 14, 0.14);
                --radius-xl: 28px;
                --radius-lg: 20px;
                --radius-md: 14px;
            }

            * { box-sizing: border-box; }

            body.guest-body {
                margin: 0;
                min-height: 100vh;
                font-family: 'Manrope', sans-serif;
                color: var(--text);
                background:
                    radial-gradient(circle at top left, rgba(224, 169, 59, 0.24), transparent 30%),
                    radial-gradient(circle at 80% 10%, rgba(182, 90, 45, 0.14), transparent 22%),
                    linear-gradient(180deg, #fbf3e6 0%, #f5e7d4 48%, #f9f0e3 100%);
                overflow-x: hidden;
                display: flex;
                flex-direction: column;
                align-items: center;
                justify-content: center;
            }

            body.guest-body::before,
            body.guest-body::after {
                content: '';
                position: fixed;
                z-index: 0;
                border-radius: 999px;
                filter: blur(40px);
                opacity: 0.55;
            }

            body.guest-body::before {
                width: 240px;
                height: 240px;
                left: -80px;
                top: 160px;
                background: rgba(224, 169, 59, 0.25);
            }

            body.guest-body::after {
                width: 320px;
                height: 320px;
                right: -120px;
                top: 520px;
                background: rgba(182, 90, 45, 0.14);
            }

            .guest-wrapper {
                position: relative;
                z-index: 1;
                width: 100%;
                min-height: 100vh;
                display: flex;
                flex-direction: column;
                align-items: center;
                justify-content: center;
                padding: 2rem 1rem;
            }

            .guest-brand {
                display: inline-flex;
                align-items: center;
                gap: 0.9rem;
                text-decoration: none;
                color: var(--text);
                margin-bottom: 1.5rem;
            }

            .guest-brand-mark {
                width: 54px;
                height: 54px;
                border-radius: 18px;
                display: grid;
                place-items: center;
                font-size: 1.5rem;
                background: linear-gradient(145deg, #fff8ef 0%, #f4c58d 100%);
                box-shadow: inset 0 0 0 1px rgba(143, 63, 27, 0.08), 0 12px 22px rgba(143, 63, 27, 0.12);
            }

            .guest-brand-copy strong {
                display: block;
                font-family: 'Fraunces', serif;
                font-size: 1.3rem;
                letter-spacing: -0.03em;
            }

            .guest-brand-copy span {
                display: block;
                font-size: 0.78rem;
                color: var(--muted);
                text-transform: uppercase;
                letter-spacing: 0.12em;
                margin-top: 0.1rem;
            }

            .guest-card {
                width: 100%;
                max-width: 460px;
                padding: 2rem 2rem 2.2rem;
                border-radius: 32px;
                background: linear-gradient(180deg, rgba(255, 248, 238, 0.94), rgba(255, 244, 229, 0.88));
                border: 1px solid rgba(111, 73, 39, 0.1);
                box-shadow: var(--shadow);
                position: relative;
                overflow: hidden;
            }

            .guest-card::before {
                content: '';
                position: absolute;
                width: 180px;
                height: 180px;
                border-radius: 50%;
                right: -50px;
                top: -50px;
                background: radial-gradient(circle, rgba(224, 169, 59, 0.22) 0%, transparent 70%);
                pointer-events: none;
            }

            .guest-card > * {
                position: relative;
                z-index: 1;
            }

            .guest-heading {
                font-family: 'Fraunces', serif;
                font-size: 1.6rem;
                font-weight: 700;
                letter-spacing: -0.04em;
                line-height: 1.15;
                margin: 0 0 0.35rem;
            }

            .guest-subtext {
                font-size: 0.92rem;
                color: var(--muted);
                line-height: 1.65;
                margin: 0 0 1.5rem;
            }

            .auth-label {
                display: block;
                font-weight: 600;
                font-size: 0.88rem;
                color: var(--text);
                margin-bottom: 0.4rem;
            }

            .auth-input {
                width: 100%;
                padding: 0.78rem 1rem;
                border-radius: var(--radius-md);
                border: 1px solid rgba(111, 73, 39, 0.16);
                background: rgba(255, 255, 255, 0.66);
                font-family: 'Manrope', sans-serif;
                font-size: 0.94rem;
                color: var(--text);
                outline: none;
                transition: border-color 0.2s ease, box-shadow 0.2s ease;
            }

            .auth-input::placeholder {
                color: rgba(110, 90, 76, 0.5);
            }

            .auth-input:focus {
                border-color: var(--accent);
                box-shadow: 0 0 0 3px rgba(182, 90, 45, 0.12);
            }

            .auth-btn {
                display: inline-flex;
                align-items: center;
                justify-content: center;
                gap: 0.55rem;
                padding: 0.9rem 1.5rem;
                border-radius: 999px;
                font-family: 'Manrope', sans-serif;
                font-size: 0.94rem;
                font-weight: 800;
                border: none;
                cursor: pointer;
                color: #fff;
                background: linear-gradient(135deg, var(--accent) 0%, var(--accent-strong) 100%);
                box-shadow: 0 14px 28px rgba(143, 63, 27, 0.22);
                transition: transform 0.2s ease, box-shadow 0.2s ease;
            }

            .auth-btn:hover {
                transform: translateY(-1px);
                box-shadow: 0 18px 34px rgba(143, 63, 27, 0.28);
            }

            .auth-link {
                color: var(--accent-strong);
                font-size: 0.88rem;
                font-weight: 600;
                text-decoration: none;
                transition: color 0.2s ease;
            }

            .auth-link:hover {
                color: var(--accent);
                text-decoration: underline;
            }

            .auth-error {
                font-size: 0.82rem;
                color: #c53030;
                margin-top: 0.35rem;
            }

            .auth-error ul {
                list-style: none;
                padding: 0;
                margin: 0;
            }

            .auth-checkbox-label {
                display: inline-flex;
                align-items: center;
                gap: 0.5rem;
                font-size: 0.88rem;
                color: var(--muted);
                cursor: pointer;
            }

            .auth-checkbox-label input[type="checkbox"] {
                width: 18px;
                height: 18px;
                border-radius: 6px;
                border: 1px solid rgba(111, 73, 39, 0.24);
                accent-color: var(--accent);
            }

            .field-group {
                margin-bottom: 1.1rem;
            }

            .auth-divider {
                display: flex;
                align-items: center;
                gap: 0.75rem;
                margin: 1.2rem 0;
                color: var(--muted);
                font-size: 0.82rem;
            }

            .auth-divider::before,
            .auth-divider::after {
                content: '';
                flex: 1;
                height: 1px;
                background: rgba(111, 73, 39, 0.12);
            }

            .auth-status {
                font-size: 0.88rem;
                color: var(--leaf);
                font-weight: 600;
                margin-bottom: 0.75rem;
            }

            .guest-footer-note {
                margin-top: 1.2rem;
                text-align: center;
                font-size: 0.82rem;
                color: var(--muted);
            }

            @keyframes floatUp {
                from { opacity: 0; transform: translateY(18px); }
                to   { opacity: 1; transform: translateY(0); }
            }

            .guest-brand,
            .guest-card {
                animation: floatUp 0.7s ease both;
            }

            .guest-card {
                animation-delay: 0.08s;
            }

            @media (max-width: 520px) {
                .guest-card {
                    padding: 1.5rem 1.25rem 1.8rem;
                    border-radius: 24px;
                }

                .guest-heading {
                    font-size: 1.35rem;
                }
            }
        </style>
    </head>
    <body class="guest-body">
        <div class="guest-wrapper">
            <a href="/" class="guest-brand" aria-label="Tres Marias home">
                <span class="guest-brand-mark">🥖</span>
                <span class="guest-brand-copy">
                    <strong>Tres Marias</strong>
                    <span>Bakery delivery &amp; pre-orders</span>
                </span>
            </a>

            <div class="guest-card">
                @yield('content')
            </div>
        </div>
    </body>
</html>
