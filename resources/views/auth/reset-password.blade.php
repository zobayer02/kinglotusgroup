@extends('layouts.app')

@section('title', 'Set New Password | King Lotus International')

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
            min-height: 440px;
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
            font-size: clamp(1.75rem, 2.7vw, 3rem);
            font-weight: 600;
            line-height: 1.04;
            text-transform: uppercase;
        }

        .login-panel {
            position: relative;
            display: flex;
            align-items: center;
            justify-content: center;
            padding: 28px 32px;
            background: linear-gradient(180deg, rgba(221, 236, 248, 0.88) 0%, rgba(194, 220, 241, 0.82) 100%);
            backdrop-filter: blur(38px);
            -webkit-backdrop-filter: blur(38px);
        }

        .login-panel-inner {
            width: 100%;
            max-width: 320px;
        }

        .login-title {
            margin: 0 0 8px;
            font-size: clamp(1.5rem, 2vw, 2.1rem);
            font-weight: 600;
            line-height: 1.05;
            color: var(--panel-text);
            text-transform: uppercase;
        }

        .login-subtitle {
            margin: 0 0 18px;
            font-size: 0.84rem;
            line-height: 1.45;
            color: var(--panel-soft);
        }

        .login-error {
            padding: 12px 14px;
            border-radius: 12px;
            background: rgba(220, 38, 38, 0.1);
            border: 1px solid rgba(220, 38, 38, 0.28);
            color: #b91c1c;
            font-size: 0.84rem;
            margin-bottom: 16px;
        }

        .field-group {
            display: grid;
            gap: 6px;
            margin-bottom: 14px;
        }

        .field-label {
            font-size: 0.82rem;
            font-weight: 600;
            color: var(--panel-text);
        }

        .field-input {
            width: 100%;
            height: 44px;
            padding: 0 16px;
            border: 1.5px solid var(--field-border);
            border-radius: 12px;
            background: rgba(255, 255, 255, 0.9);
            font-size: 0.92rem;
            color: var(--panel-text);
            outline: none;
            transition: all 0.2s ease;
        }

        .field-input:focus {
            border-color: var(--primary);
            box-shadow: 0 0 0 3px var(--field-focus);
            background: #ffffff;
        }

        .primary-button {
            width: 100%;
            height: 46px;
            border-radius: 14px;
            background: var(--primary);
            color: #ffffff;
            font-size: 0.94rem;
            font-weight: 600;
            border: none;
            cursor: pointer;
            transition: all 0.2s ease;
            margin-top: 8px;
        }

        .primary-button:hover {
            background: var(--primary-dark);
            transform: translateY(-1px);
            box-shadow: 0 8px 18px rgba(12, 80, 93, 0.25);
        }

        @media (max-width: 768px) {
            .login-hero {
                padding: 112px 18px 24px;
                min-height: calc(100vh - 36px);
                min-height: 100dvh;
                display: flex;
                flex-direction: column;
                align-items: center;
                box-sizing: border-box;
            }
            .login-shell {
                aspect-ratio: auto;
                width: 100%;
                max-width: 480px;
                min-height: auto;
                margin: auto auto;
                padding: 18px 16px;
                border-radius: 26px;
                display: flex;
                flex-direction: column;
                justify-content: center;
                align-items: center;
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
                                <h1 class="visual-title">Update Password</h1>
                            </div>
                        </div>

                        <div class="login-panel">
                            <div class="login-panel-inner">
                                <h2 class="login-title">New Password</h2>
                                <p class="login-subtitle">Set your new password to regain access to your account.</p>

                                @if ($errors->any())
                                    <div class="login-error">{{ $errors->first() }}</div>
                                @endif

                                <form action="{{ route('password.update') }}" method="post">
                                    @csrf

                                    <input type="hidden" name="token" value="{{ $token }}">

                                    <div class="field-group">
                                        <label class="field-label" for="email">Email</label>
                                        <input class="field-input" id="email" type="email" name="email" value="{{ old('email', $email) }}" required readonly>
                                    </div>

                                    <div class="field-group">
                                        <label class="field-label" for="password">New Password</label>
                                        <input class="field-input" id="password" type="password" name="password" placeholder="At least 8 characters" required autofocus>
                                    </div>

                                    <div class="field-group">
                                        <label class="field-label" for="password_confirmation">Confirm Password</label>
                                        <input class="field-input" id="password_confirmation" type="password" name="password_confirmation" placeholder="Confirm new password" required>
                                    </div>

                                    <button class="primary-button" type="submit">Update Password</button>
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
