@extends('layouts.app')

@section('title', 'Verify Code | King Lotus International')

@php
    $notice = $notice ?? \App\Models\SiteNotice::query()->active()->latest('updated_at')->first();
    $heroBackgroundUrl = $notice?->heroBackgroundUrl() ?: asset('images/beautiful-rustic-house-landscape.webp');
@endphp

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
            position: relative;
            min-height: 0;
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
            aspect-ratio: 16 / 9;
            height: auto;
            min-height: 0;
            overflow: hidden;
            border-radius: 34px;
            border: 1px solid rgba(190, 205, 214, 0.68);
            background:
                linear-gradient(180deg, rgba(255, 255, 255, 0.08) 0%, rgba(255, 255, 255, 0.02) 100%),
                url('{{ $heroBackgroundUrl }}') center center / cover no-repeat;
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
            padding: 104px 18px 24px;
        }

        .login-card {
            display: grid;
            grid-template-columns: minmax(400px, 1.08fr) minmax(360px, 0.92fr);
            width: min(100%, 820px);
            gap: 0;
            padding: 0;
            border: 0;
            border-radius: 32px;
            background: transparent;
            overflow: hidden;
            box-shadow: 0 32px 72px rgba(10, 24, 34, 0.35);
        }

        .login-visual {
            position: relative;
            min-height: 430px;
            padding: 28px 30px;
            display: flex;
            flex-direction: column;
            justify-content: center;
            color: #ffffff;
            background: linear-gradient(180deg, rgba(6, 18, 24, 0.25) 0%, rgba(6, 18, 24, 0.65) 100%);
            backdrop-filter: blur(8px);
            -webkit-backdrop-filter: blur(8px);
        }

        .visual-brand {
            font-family: var(--font-primary);
            font-size: 0.9rem;
            font-weight: 600;
            letter-spacing: 0.12em;
            text-transform: uppercase;
        }

        .visual-copy {
            margin-top: 24px;
        }

        .visual-title {
            margin: 0;
            font-family: var(--font-primary);
            font-size: clamp(1.8rem, 2.8vw, 2.5rem);
            font-weight: 600;
            line-height: 1.15;
            letter-spacing: -0.02em;
        }

        .login-panel {
            background: #ffffff;
            display: flex;
            align-items: center;
            padding: 34px 30px;
        }

        .login-panel-inner {
            width: 100%;
            max-width: 330px;
            margin: 0 auto;
        }

        .login-title {
            margin: 0;
            font-family: var(--font-primary);
            font-size: 1.55rem;
            font-weight: 600;
            color: var(--panel-text);
            line-height: 1.2;
        }

        .login-subtitle {
            margin: 8px 0 20px;
            font-size: 0.86rem;
            color: var(--panel-soft);
            line-height: 1.45;
        }

        .login-status {
            padding: 10px 14px;
            border-radius: 10px;
            background: rgba(12, 80, 93, 0.08);
            border: 1px solid rgba(12, 80, 93, 0.24);
            color: var(--primary);
            font-size: 0.82rem;
            line-height: 1.4;
            margin-bottom: 16px;
        }

        .login-error {
            padding: 10px 14px;
            border-radius: 10px;
            background: #fdf2f2;
            border: 1px solid #f8b4b4;
            color: #9b1c1c;
            font-size: 0.82rem;
            line-height: 1.4;
            margin-bottom: 16px;
        }

        .field-group {
            margin-bottom: 16px;
        }

        .field-label {
            display: block;
            margin-bottom: 6px;
            font-size: 0.82rem;
            font-weight: 500;
            color: var(--panel-text);
        }

        .field-input {
            width: 100%;
            height: 48px;
            padding: 0 14px;
            border: 1px solid var(--field-border);
            border-radius: 10px;
            font-size: 1.3rem;
            font-weight: 700;
            letter-spacing: 0.3em;
            text-align: center;
            color: var(--panel-text);
            background: #fbfcfd;
            box-sizing: border-box;
            transition: border-color 0.2s, box-shadow 0.2s;
            font-family: 'SFMono-Regular', Consolas, 'Liberation Mono', Menlo, monospace;
        }

        .field-input:focus {
            outline: none;
            border-color: var(--primary);
            box-shadow: 0 0 0 3px var(--field-focus);
            background: #ffffff;
        }

        .primary-button {
            display: flex;
            align-items: center;
            justify-content: center;
            width: 100%;
            height: 44px;
            border: none;
            border-radius: 10px;
            background: var(--primary);
            color: #ffffff;
            font-size: 0.92rem;
            font-weight: 600;
            cursor: pointer;
            transition: background-color 0.2s, transform 0.1s;
        }

        .primary-button:hover {
            background: var(--primary-dark);
        }

        .primary-button:active {
            transform: scale(0.99);
        }

        .login-back-wrap {
            margin-top: 18px;
            text-align: center;
            display: flex;
            flex-direction: column;
            gap: 8px;
        }

        .login-back-link {
            font-size: 0.82rem;
            color: var(--panel-soft);
            text-decoration: none;
            transition: color 0.15s;
            cursor: pointer;
            background: none;
            border: none;
            padding: 0;
        }

        .login-back-link:hover {
            color: var(--primary);
            text-decoration: underline;
        }

        .resend-form {
            display: inline;
        }

        @media (max-width: 900px) {
            .login-hero {
                padding: 108px 16px 24px;
                min-height: calc(100vh - 32px);
                min-height: 100dvh;
            }
            .login-shell {
                aspect-ratio: auto;
                min-height: auto;
                height: auto;
                padding: 24px 16px;
                border-radius: 28px;
                box-sizing: border-box;
            }
            .login-stage {
                width: 100%;
                min-height: auto;
                padding: 0;
                display: flex;
                align-items: center;
                justify-content: center;
            }
            .login-card {
                grid-template-columns: 1fr;
                width: 100%;
                max-width: 420px;
                margin: 0 auto;
                border-radius: 22px;
                overflow: hidden;
            }
            .login-visual {
                display: none !important;
            }
        }

        @media (max-width: 520px) {
            .login-hero {
                padding: 108px 12px 20px;
                min-height: calc(100vh - 24px);
                min-height: 100dvh;
            }
            .login-shell {
                padding: 14px 10px;
                border-radius: 22px;
                max-width: 100%;
            }
            .login-card {
                border-radius: 18px;
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
                                <h1 class="visual-title">Enter Verification Code</h1>
                            </div>
                        </div>

                        <div class="login-panel">
                            <div class="login-panel-inner">
                                <h2 class="login-title">Verify Code</h2>
                                <p class="login-subtitle">
                                    We sent a 6-digit verification code to<br>
                                    <strong>{{ $email }}</strong>.
                                </p>

                                @if (session('status'))
                                    <div class="login-status">{{ session('status') }}</div>
                                @endif

                                @if (session('error'))
                                    <div class="login-error">{{ session('error') }}</div>
                                @elseif ($errors->any())
                                    <div class="login-error">{{ $errors->first() }}</div>
                                @endif

                                <form action="{{ route('password.verify-otp.submit') }}" method="post">
                                    @csrf

                                    <div class="field-group">
                                        <label class="field-label" for="otp">6-Digit Code</label>
                                        <input class="field-input" id="otp" type="text" name="otp" inputmode="numeric" pattern="[0-9]*" maxlength="6" placeholder="------" required autofocus autocomplete="one-time-code">
                                    </div>

                                    <button class="primary-button" type="submit">Verify & Continue</button>

                                    <div class="login-back-wrap">
                                        <form action="{{ route('password.resend-otp') }}" method="post" class="resend-form">
                                            @csrf
                                            <button type="submit" class="login-back-link">Didn't receive the code? Resend Code</button>
                                        </form>

                                        <a href="{{ route('login') }}" class="login-back-link">&larr; Back to Sign In</a>
                                    </div>
                                </form>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </section>

        @include('partials.footer')
    </div>
@endsection
