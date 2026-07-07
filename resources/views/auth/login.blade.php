@extends('layouts.app')

@section('title', 'Login | King Lotus International')

@push('styles')
    <style>
        @include('partials.chrome-styles')
        :root {
            --field-border: rgba(155, 173, 185, 0.42);
            --field-focus: rgba(9, 84, 97, 0.22);
            --primary: #0c505d;
            --primary-dark: #083c46;
            --panel-text: #10212c;
            --panel-soft: rgba(16, 33, 44, 0.62);
        }

        .login-page {
            background: var(--section-surface);
        }

        .login-hero {
            padding: 28px;
            background: var(--section-surface);
        }

        .login-shell {
            display: block;
            position: relative;
            z-index: 1;
            width: 100%;
            max-width: 1240px;
            margin: 0 auto;
            height: clamp(760px, calc(100vh - 56px), 980px);
            min-height: 0;
            overflow: hidden;
            padding: 28px;
            border-radius: 34px;
            border: 1px solid rgba(190, 205, 214, 0.68);
            background:
                linear-gradient(180deg, rgba(255, 255, 255, 0.08) 0%, rgba(255, 255, 255, 0.02) 100%),
                url('{{ asset('images/beautiful-rustic-house-landscape.webp') }}') center center / cover no-repeat;
            box-shadow:
                0 28px 80px rgba(24, 43, 56, 0.12),
                inset 0 1px 0 rgba(255, 255, 255, 0.4);
        }

        .login-stage {
            display: flex;
            align-items: center;
            justify-content: center;
            height: 100%;
            min-height: 0;
            padding: 122px 18px 28px;
        }

        .login-card {
            display: grid;
            grid-template-columns: minmax(400px, 1.08fr) minmax(360px, 0.92fr);
            width: min(100%, 840px);
            gap: 0;
            padding: 0;
            border: 0;
            border-radius: 32px;
            background: transparent;
            box-shadow: none;
            backdrop-filter: none;
            -webkit-backdrop-filter: none;
            overflow: hidden;
            opacity: 0;
            transform: translateY(28px) scale(0.97);
            animation: loginCardAppear 0.78s cubic-bezier(0.16, 1, 0.3, 1) 180ms forwards;
        }

        .login-visual {
            position: relative;
            min-height: 460px;
            padding: 24px 28px;
            border-radius: 0;
            overflow: hidden;
            display: flex;
            flex-direction: column;
            justify-content: center;
            color: #ffffff;
            background:
                linear-gradient(180deg, rgba(6, 18, 24, 0.12) 0%, rgba(6, 18, 24, 0.58) 100%);
            box-shadow:
                inset 0 0 0 1px rgba(255, 255, 255, 0.16),
                inset 0 1px 0 rgba(255, 255, 255, 0.22);
            backdrop-filter: blur(6px);
            -webkit-backdrop-filter: blur(6px);
            opacity: 0;
            transform: translateX(-24px);
            animation: loginPanelSlideLeft 0.72s cubic-bezier(0.16, 1, 0.3, 1) 320ms forwards;
        }

        .login-visual::after {
            content: "";
            position: absolute;
            inset: 0;
            background:
                radial-gradient(circle at 22% 24%, rgba(126, 242, 223, 0.18), transparent 36%),
                linear-gradient(180deg, rgba(4, 10, 14, 0.05) 0%, rgba(4, 10, 14, 0.22) 100%);
            pointer-events: none;
        }

        .login-visual > * {
            position: relative;
            z-index: 1;
        }

        .visual-brand {
            font-family: var(--font-primary);
            font-size: 0.9rem;
            font-weight: 600;
            letter-spacing: 0.12em;
            text-transform: uppercase;
        }

        .visual-copy {
            max-width: 360px;
            margin-top: 34px;
        }

        .visual-title {
            margin: 0 0 18px;
            font-family: var(--font-primary);
            font-size: clamp(1.75rem, 2.7vw, 3.2rem);
            font-weight: 600;
            line-height: 0.98;
            letter-spacing: 0;
            text-transform: uppercase;
        }

        .visual-description {
            margin: 0 0 12px;
            font-size: 0.92rem;
            line-height: 1.6;
            color: rgba(255, 255, 255, 0.9);
        }

        .login-panel {
            position: relative;
            isolation: isolate;
            display: flex;
            align-items: center;
            justify-content: center;
            padding: 28px 30px;
            background:
                linear-gradient(180deg, rgba(221, 236, 248, 0.82) 0%, rgba(194, 220, 241, 0.76) 100%);
            backdrop-filter: blur(38px) saturate(150%) brightness(1.06);
            -webkit-backdrop-filter: blur(38px) saturate(150%) brightness(1.06);
            box-shadow:
                inset 0 1px 0 rgba(255, 255, 255, 0.34),
                inset 0 0 0 1px rgba(128, 167, 204, 0.22);
            opacity: 0;
            transform: translateX(24px);
            animation: loginPanelSlideRight 0.72s cubic-bezier(0.16, 1, 0.3, 1) 360ms forwards;
        }

        .login-panel::before {
            content: "";
            position: absolute;
            top: 0;
            left: 0;
            bottom: 0;
            width: 1px;
            background: linear-gradient(180deg, rgba(255, 255, 255, 0.26) 0%, rgba(255, 255, 255, 0.08) 100%);
        }

        .login-panel::after {
            content: "";
            position: absolute;
            inset: 0;
            background:
                linear-gradient(180deg, rgba(245, 250, 255, 0.24) 0%, rgba(205, 224, 240, 0.16) 100%);
            backdrop-filter: blur(30px) saturate(135%);
            -webkit-backdrop-filter: blur(30px) saturate(135%);
            pointer-events: none;
        }

        .login-panel-inner {
            width: 100%;
            max-width: 300px;
            position: relative;
            z-index: 1;
            font-family: var(--font-primary);
        }

        .login-panel-inner input,
        .login-panel-inner button,
        .login-panel-inner label,
        .login-panel-inner p,
        .login-panel-inner a {
            font-family: inherit;
        }

        .login-kicker {
            margin: 0 0 10px;
            font-size: 0.74rem;
            font-weight: 600;
            letter-spacing: 0.14em;
            text-transform: uppercase;
            color: #0b6574;
        }

        .login-title {
            margin: 0;
            font-size: clamp(1.6rem, 2vw, 2.35rem);
            font-weight: 600;
            line-height: 1.04;
            letter-spacing: 0;
            color: var(--panel-text);
            text-transform: uppercase;
        }

        .login-subtitle {
            margin: 12px 0 20px;
            font-size: 0.9rem;
            line-height: 1.6;
            color: var(--panel-soft);
        }

        .login-form {
            display: grid;
            gap: 14px;
        }

        .field-group {
            display: grid;
            gap: 9px;
        }

        .field-label {
            font-size: 0.95rem;
            font-weight: 600;
            color: var(--panel-text);
        }

        .field-input,
        .field-password {
            width: 100%;
            min-height: 56px;
            padding: 0 16px;
            border-radius: 16px;
            border: 1px solid rgba(171, 188, 199, 0.5);
            background: rgba(255, 255, 255, 0.62);
            color: var(--panel-text);
            font: inherit;
            outline: none;
            transition:
                border-color 0.2s ease,
                box-shadow 0.2s ease,
                transform 0.2s ease;
        }

        .field-input:focus,
        .field-password:focus-within {
            border-color: rgba(12, 80, 93, 0.38);
            box-shadow: 0 0 0 4px var(--field-focus);
            transform: translateY(-1px);
        }

        .field-password {
            position: relative;
            display: flex;
            align-items: center;
            gap: 0;
            padding-right: 56px;
        }

        .field-password input {
            flex: 1;
            min-width: 0;
            width: 100%;
            height: 100%;
            padding: 0;
            border: 0;
            outline: none;
            color: inherit;
            font: inherit;
            line-height: 1;
            background: transparent;
        }

        .password-toggle {
            position: absolute;
            top: 50%;
            right: 10px;
            display: inline-flex;
            align-items: center;
            justify-content: center;
            width: 36px;
            height: 36px;
            border: 0;
            padding: 0;
            border-radius: 999px;
            background: transparent;
            color: rgba(16, 33, 44, 0.58);
            cursor: pointer;
            transform: translateY(-50%);
            transition:
                background-color 0.2s ease,
                color 0.2s ease;
        }

        .password-toggle svg {
            display: block;
        }

        .password-toggle .icon-eye-open {
            display: none;
        }

        .password-toggle[data-state="visible"] .icon-eye-open {
            display: block;
        }

        .password-toggle[data-state="visible"] .icon-eye-closed {
            display: none;
        }

        .password-toggle:hover {
            background: rgba(12, 80, 93, 0.08);
            color: #0c505d;
        }

        .login-meta {
            display: flex;
            align-items: center;
            justify-content: flex-start;
            gap: 16px;
            font-size: 0.92rem;
        }

        .remember-me {
            display: inline-flex;
            align-items: center;
            gap: 10px;
            color: var(--panel-soft);
        }

        .remember-me input {
            width: 16px;
            height: 16px;
            accent-color: #0c505d;
        }

        .primary-button {
            display: inline-flex;
            align-items: center;
            justify-content: center;
            gap: 10px;
            width: 100%;
            min-height: 56px;
            border-radius: 16px;
            border: 1px solid transparent;
            font: inherit;
            font-weight: 700;
            cursor: pointer;
            transition:
                transform 0.2s ease,
                box-shadow 0.2s ease,
                background-color 0.2s ease,
                border-color 0.2s ease;
        }

        .primary-button {
            background: var(--primary);
            color: #ffffff;
            box-shadow: 0 16px 34px rgba(12, 80, 93, 0.24);
        }

        .primary-button:hover {
            background: var(--primary-dark);
            transform: translateY(-1px);
        }

        .login-error {
            margin-bottom: 16px;
            padding: 14px 16px;
            border-radius: 18px;
            border: 1px solid rgba(191, 74, 64, 0.2);
            background: rgba(191, 74, 64, 0.1);
            color: #8d342d;
            line-height: 1.6;
        }

        .signup-copy {
            margin: 18px 0 0;
            text-align: center;
            font-size: clamp(0.68rem, 2.35vw, 0.95rem);
            line-height: 1.45;
            color: var(--panel-soft);
            width: 100%;
        }

        .signup-copy a,
        .signup-copy button {
            font-weight: 700;
            color: #0c505d;
            font: inherit;
            padding: 0;
            border: 0;
            background: transparent;
            cursor: pointer;
        }

        .signup-copy a:hover,
        .signup-copy button:hover {
            text-decoration: underline;
        }

        @keyframes loginCardAppear {
            0% {
                opacity: 0;
                transform: translateY(28px) scale(0.97);
            }
            100% {
                opacity: 1;
                transform: translateY(0) scale(1);
            }
        }

        @keyframes loginPanelSlideLeft {
            0% {
                opacity: 0;
                transform: translateX(-24px);
            }
            100% {
                opacity: 1;
                transform: translateX(0);
            }
        }

        @keyframes loginPanelSlideRight {
            0% {
                opacity: 0;
                transform: translateX(24px);
            }
            100% {
                opacity: 1;
                transform: translateX(0);
            }
        }

        @media (max-width: 1100px) {
            .login-shell {
                height: auto;
                min-height: 0;
                padding: 24px;
            }

            .login-stage {
                height: auto;
                padding: 124px 0 20px;
            }

            .login-card {
                grid-template-columns: 1fr;
                width: min(100%, 720px);
            }

            .login-visual {
                min-height: 340px;
            }

            .login-panel {
                padding: 26px 26px 28px;
            }

            .login-panel::before {
                top: 0;
                left: 24px;
                right: 24px;
                bottom: auto;
                width: auto;
                height: 1px;
            }

        }

        @media (max-width: 920px) {
            .login-shell {
                padding: 20px;
                border-radius: 30px;
            }

            .nav-links,
            .nav-actions {
                gap: 10px;
            }

            .nav-link {
                padding: 10px 12px;
                font-size: 0.92rem;
            }

            .brand-top {
                font-size: 2rem;
            }

            .login-stage {
                padding-top: 118px;
            }

            .login-card {
                width: min(100%, 640px);
                border-radius: 26px;
            }

            .login-visual {
                min-height: 300px;
                padding: 22px 22px 24px;
            }

            .visual-copy {
                max-width: 100%;
            }

            .visual-title {
                font-size: clamp(1.65rem, 5vw, 2.5rem);
            }

            .login-panel {
                padding: 24px 22px 26px;
            }

            .login-panel-inner {
                max-width: 100%;
            }

        }

        @media (max-width: 768px) {
            .login-hero,
            .site-footer {
                padding: 18px 18px 18px;
            }

            .login-shell {
                min-height: auto;
                padding: 16px;
                border-radius: 26px;
            }

            .login-stage {
                min-height: auto;
                padding: 118px 0 0;
            }

            .login-card {
                grid-template-columns: 1fr;
                width: 100%;
                border-radius: 22px;
                overflow: hidden;
            }

            .login-visual {
                display: none !important;
            }

            .login-panel {
                min-height: auto;
                padding: 22px 18px 24px;
                border-radius: 22px;
            }

            .login-panel::before {
                display: none;
            }

            .login-meta,
            .footer-form {
                flex-direction: column;
                align-items: stretch;
            }
        }

        @media (max-width: 520px) {
            .login-hero {
                padding: 12px;
            }

            .login-shell {
                padding: 12px;
                border-radius: 22px;
            }

            .floating-nav {
                width: min(calc(100vw - 20px), 360px);
                padding: 13px 12px 18px;
            }

            .floating-nav.is-scrolled {
                width: min(calc(100vw - 24px), 348px);
            }

            .login-card {
                border-radius: 18px;
            }

            .login-panel {
                padding: 18px 16px 20px;
                border-radius: 18px;
            }

            .brand-top {
                font-size: 1.55rem;
            }

            .brand-bottom {
                gap: 7px;
                font-size: 0.78rem;
            }

            .brand-line {
                width: 42px;
                height: 2px;
            }

            .visual-title {
                font-size: clamp(1.55rem, 9vw, 2.1rem);
            }

            .visual-description,
            .login-subtitle,
            .signup-copy {
                font-size: clamp(0.62rem, 2.7vw, 0.8rem);
            }

            .login-kicker,
            .field-label {
                font-size: 0.72rem;
            }

            .field-input,
            .field-password,
            .primary-button {
                min-height: 50px;
                border-radius: 14px;
            }

            .remember-me,
            .login-meta {
                font-size: 0.84rem;
            }

            .footer-brand-top {
                font-size: 1.7rem;
            }

            .footer-brand-bottom {
                font-size: 0.78rem;
                gap: 8px;
            }

            .footer-brand-line {
                width: 42px;
            }
        }

        @media (prefers-reduced-motion: reduce) {
            .login-card,
            .login-visual,
            .login-panel {
                animation: none !important;
                opacity: 1 !important;
                transform: none !important;
            }
        }
    </style>
@endpush

@section('content')
    <div class="login-page">
        <section class="login-hero">
            <div class="login-shell">
                @include('partials.navbar')

                <div class="login-stage">
                    <div class="login-card">
                        <div class="login-visual" aria-hidden="true">
                            <div class="visual-brand">King Lotus International</div>

                            <div class="visual-copy">
                                <h1 class="visual-title">Your Private Gateway to Hotel Ownership</h1>
                                <p class="visual-description">Secure access. Trusted updates. Premium hospitality investment.</p>
                            </div>
                        </div>

                        <div class="login-panel">
                            <div class="login-panel-inner">
                                <p class="login-kicker">Member Access</p>
                                <h2 class="login-title">Welcome Back</h2>
                                <p class="login-subtitle">Sign in to access your dashboard, investment profile, and shareholder tools.</p>

                                @if (session('error'))
                                    <div class="login-error">{{ session('error') }}</div>
                                @elseif ($errors->any())
                                    <div class="login-error">{{ $errors->first() }}</div>
                                @endif

                                <form class="login-form" action="{{ route('login.store') }}" method="post">
                                    @csrf

                                    <div class="field-group">
                                        <label class="field-label" for="email">Email or mobile number</label>
                                        <input class="field-input" id="email" type="text" name="email" value="{{ old('email') }}" placeholder="Enter your email or mobile number" autocomplete="username" inputmode="email" required>
                                    </div>

                                    <div class="field-group">
                                        <label class="field-label" for="password">Password</label>
                                        <div class="field-password">
                                            <input id="password" type="password" name="password" placeholder="Enter your password" autocomplete="current-password" required>
                                            <button class="password-toggle" type="button" aria-label="Show password" data-password-toggle data-state="hidden">
                                                <svg class="icon-eye-open" width="20" height="20" viewBox="0 0 24 24" fill="none" aria-hidden="true">
                                                    <path d="M2 12C4.5 7.8 8 5.7 12 5.7C16 5.7 19.5 7.8 22 12C19.5 16.2 16 18.3 12 18.3C8 18.3 4.5 16.2 2 12Z" stroke="currentColor" stroke-width="1.8" stroke-linejoin="round"></path>
                                                    <circle cx="12" cy="12" r="3.2" stroke="currentColor" stroke-width="1.8"></circle>
                                                </svg>
                                                <svg class="icon-eye-closed" width="20" height="20" viewBox="0 0 24 24" fill="none" aria-hidden="true">
                                                    <path d="M3 3L21 21" stroke="currentColor" stroke-width="1.8" stroke-linecap="round"></path>
                                                    <path d="M10.58 10.58C10.21 10.95 10 11.46 10 12C10 13.1 10.9 14 12 14C12.54 14 13.05 13.79 13.42 13.42" stroke="currentColor" stroke-width="1.8" stroke-linecap="round"></path>
                                                    <path d="M6.72 6.72C4.76 8.02 3.15 9.82 2 12C4.5 16.2 8 18.3 12 18.3C13.75 18.3 15.41 17.9 16.92 17.14" stroke="currentColor" stroke-width="1.8" stroke-linecap="round" stroke-linejoin="round"></path>
                                                    <path d="M9.12 5.97C10.04 5.79 11 5.7 12 5.7C16 5.7 19.5 7.8 22 12C21.27 13.22 20.47 14.29 19.58 15.23" stroke="currentColor" stroke-width="1.8" stroke-linecap="round" stroke-linejoin="round"></path>
                                                </svg>
                                            </button>
                                        </div>
                                    </div>

                                    <div class="login-meta">
                                        <label class="remember-me" for="remember">
                                            <input id="remember" type="checkbox" name="remember" value="1">
                                            <span>Remember me</span>
                                        </label>
                                    </div>

                                    <button class="primary-button" type="submit">Sign In</button>

                                </form>

                                <p class="signup-copy">Don’t have an account?<br><a href="#">Contact King Lotus Group</a></p>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </section>

        @include('partials.footer')
    </div>
@endsection

@push('scripts')
    @include('partials.mobile-nav-script')

    <script>
        document.addEventListener('DOMContentLoaded', () => {
            const toggle = document.querySelector('[data-password-toggle]');
            const input = document.getElementById('password');
            const contactLink = document.querySelector('.signup-copy a');
            const phoneTrigger = document.querySelector('[data-phone-trigger]');

            if (toggle && input) {
                toggle.addEventListener('click', () => {
                    const isPassword = input.type === 'password';
                    input.type = isPassword ? 'text' : 'password';
                    toggle.setAttribute('aria-label', isPassword ? 'Hide password' : 'Show password');
                    toggle.setAttribute('data-state', isPassword ? 'visible' : 'hidden');
                });
            }

            if (contactLink && phoneTrigger) {
                contactLink.addEventListener('click', (event) => {
                    event.preventDefault();
                    phoneTrigger.click();
                });
            }

        });
    </script>
@endpush
