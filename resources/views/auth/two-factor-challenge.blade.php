@extends('layouts.app')

@section('title', 'Two-Factor Authentication | King Lotus International')

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
            grid-template-columns: minmax(380px, 1.05fr) minmax(360px, 0.95fr);
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

        .visual-kicker {
            font-size: 0.8rem;
            letter-spacing: 0.08em;
            text-transform: uppercase;
            color: rgba(255, 255, 255, 0.8);
        }

        .visual-title {
            margin: 8px 0 0;
            font-family: var(--font-primary);
            font-size: 2rem;
            line-height: 1.15;
            font-weight: 700;
        }

        .visual-desc {
            margin: 14px 0 0;
            font-size: 0.92rem;
            line-height: 1.6;
            color: rgba(255, 255, 255, 0.85);
        }

        .login-panel {
            padding: 36px 32px;
            background: rgba(255, 255, 255, 0.96);
            backdrop-filter: blur(16px);
            -webkit-backdrop-filter: blur(16px);
            display: flex;
            flex-direction: column;
            justify-content: center;
        }

        .panel-title {
            margin: 0;
            font-size: 1.6rem;
            font-weight: 700;
            color: var(--panel-text);
        }

        .panel-subtitle {
            margin: 8px 0 24px;
            font-size: 0.88rem;
            color: var(--panel-soft);
            line-height: 1.5;
        }

        .form-group {
            margin-bottom: 20px;
        }

        .form-label {
            display: block;
            margin-bottom: 8px;
            font-size: 0.82rem;
            font-weight: 600;
            color: var(--panel-text);
        }

        .form-input {
            width: 100%;
            height: 48px;
            padding: 0 16px;
            font-size: 1.1rem;
            letter-spacing: 0.15em;
            text-align: center;
            border-radius: 12px;
            border: 1px solid var(--field-border);
            background: #ffffff;
            box-sizing: border-box;
            font-family: monospace;
            transition: all 0.2s ease;
        }

        .form-input:focus {
            outline: none;
            border-color: var(--primary);
            box-shadow: 0 0 0 3px var(--field-focus);
        }

        .btn-submit {
            display: flex;
            align-items: center;
            justify-content: center;
            gap: 8px;
            width: 100%;
            height: 48px;
            border-radius: 12px;
            border: none;
            background: linear-gradient(135deg, var(--primary) 0%, var(--primary-dark) 100%);
            color: #ffffff;
            font-size: 0.95rem;
            font-weight: 600;
            cursor: pointer;
            box-shadow: 0 8px 20px rgba(12, 80, 93, 0.25);
            transition: all 0.2s ease;
        }

        .btn-submit:hover {
            transform: translateY(-1px);
            box-shadow: 0 12px 24px rgba(12, 80, 93, 0.35);
        }

        .toggle-btn {
            background: none;
            border: none;
            color: var(--primary);
            font-size: 0.84rem;
            font-weight: 600;
            cursor: pointer;
            padding: 8px 0;
            margin-top: 14px;
            text-align: center;
            display: block;
            width: 100%;
            text-decoration: underline;
        }

        .toggle-btn:hover {
            color: var(--primary-dark);
        }

        .back-link {
            display: block;
            text-align: center;
            margin-top: 14px;
            font-size: 0.82rem;
            color: var(--panel-soft);
            text-decoration: none;
        }

        .back-link:hover {
            color: var(--panel-text);
        }

        .error-alert {
            padding: 12px 14px;
            border-radius: 10px;
            background: rgba(239, 68, 68, 0.1);
            border: 1px solid rgba(239, 68, 68, 0.25);
            color: #b91c1c;
            font-size: 0.84rem;
            margin-bottom: 18px;
        }

        @media (max-width: 768px) {
            .login-card {
                grid-template-columns: 1fr;
            }
            .login-visual {
                display: none;
            }
        }
    </style>
@endpush

@section('content')
<main class="login-page">
    <section class="login-hero">
        <div class="login-shell">
            <div class="login-stage">
                <div class="login-card">
                    <div class="login-visual">
                        <div class="visual-brand">King Lotus Group</div>
                        <div class="visual-copy">
                            <span class="visual-kicker">Security Verification</span>
                            <h1 class="visual-title">Two-Factor Authentication</h1>
                            <p class="visual-desc">Protecting administrative access with time-based one-time passwords and hardened recovery credentials.</p>
                        </div>
                    </div>

                    <div class="login-panel">
                        <h2 class="panel-title">Verify Identity</h2>
                        <p class="panel-subtitle" id="challenge-subtitle">Enter the 6-digit code from your authenticator app.</p>

                        @if ($errors->any())
                            <div class="error-alert">
                                @foreach ($errors->all() as $error)
                                    <div>{{ $error }}</div>
                                @endforeach
                            </div>
                        @endif

                        <form action="{{ route('login.challenge.verify') }}" method="POST" id="challenge-form">
                            @csrf

                            <div class="form-group" id="totp-group">
                                <label class="form-label" for="code">Authentication Code</label>
                                <input class="form-input" id="code" type="text" name="code" inputmode="numeric" pattern="[0-9]*" maxlength="6" placeholder="000000" autocomplete="one-time-code" autofocus>
                            </div>

                            <div class="form-group" id="recovery-group" style="display: none;">
                                <label class="form-label" for="recovery_code">Recovery Code</label>
                                <input class="form-input" id="recovery_code" type="text" name="recovery_code" placeholder="XXXXX-XXXXX" autocomplete="off" style="letter-spacing: 0.08em;">
                            </div>

                            <button class="btn-submit" type="submit">Verify & Continue</button>

                            <button class="toggle-btn" type="button" id="toggle-recovery-btn">Use a recovery code</button>
                        </form>

                        <a href="{{ route('login') }}" class="back-link">&larr; Return to Sign In</a>
                    </div>
                </div>
            </div>
        </div>
    </section>
</main>
@endsection

@push('scripts')
<script>
    document.addEventListener('DOMContentLoaded', () => {
        const toggleBtn = document.getElementById('toggle-recovery-btn');
        const totpGroup = document.getElementById('totp-group');
        const recoveryGroup = document.getElementById('recovery-group');
        const subtitle = document.getElementById('challenge-subtitle');
        const codeInput = document.getElementById('code');
        const recoveryInput = document.getElementById('recovery_code');

        let isRecovery = false;

        toggleBtn.addEventListener('click', () => {
            isRecovery = !isRecovery;

            if (isRecovery) {
                totpGroup.style.display = 'none';
                recoveryGroup.style.display = 'block';
                codeInput.value = '';
                recoveryInput.focus();
                subtitle.textContent = 'Enter one of your emergency recovery codes.';
                toggleBtn.textContent = 'Use an authenticator code';
            } else {
                recoveryGroup.style.display = 'none';
                totpGroup.style.display = 'block';
                recoveryInput.value = '';
                codeInput.focus();
                subtitle.textContent = 'Enter the 6-digit code from your authenticator app.';
                toggleBtn.textContent = 'Use a recovery code';
            }
        });
    });
</script>
@endpush
