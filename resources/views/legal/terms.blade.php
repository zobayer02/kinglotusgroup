@extends('layouts.app')

@section('title', ($footerSetting?->terms_title ?: 'Terms and Conditions') . ' | King Lotus International')

@push('styles')
    <style>
        @include('partials.chrome-styles')

        .terms-page {
            background: var(--section-surface);
            min-height: 100vh;
            padding: 28px;
        }

        .terms-shell {
            width: 100%;
            max-width: 1240px;
            margin: 0 auto;
            display: grid;
            gap: 26px;
        }

        .terms-card {
            position: relative;
            overflow: hidden;
            width: calc(100% - 56px);
            margin: 0 auto;
            border-radius: 34px;
            border: 1px solid rgba(190, 205, 214, 0.68);
            background:
                linear-gradient(180deg, rgba(255, 255, 255, 0.22) 0%, rgba(255, 255, 255, 0.08) 100%),
                linear-gradient(180deg, rgba(220, 236, 245, 0.9) 0%, rgba(205, 225, 236, 0.84) 100%);
            box-shadow:
                0 28px 80px rgba(24, 43, 56, 0.12),
                inset 0 1px 0 rgba(255, 255, 255, 0.5);
            padding: 24px;
        }

        .terms-content {
            padding: clamp(74px, 10vw, 104px) clamp(18px, 4vw, 54px) clamp(28px, 4vw, 46px);
        }

        .terms-header {
            display: grid;
            justify-items: center;
            text-align: center;
            gap: 0;
            margin-bottom: 26px;
        }

        .terms-kicker {
            margin: 0 0 14px;
            font-family: var(--font-secondary);
            font-size: 0.92rem;
            font-weight: 700;
            letter-spacing: 0.18em;
            text-transform: uppercase;
            color: #0c505d;
            text-align: center;
        }

        .terms-title {
            margin: 0 0 16px;
            font-family: var(--font-primary);
            font-size: clamp(2rem, 4vw, 4.2rem);
            font-weight: 500;
            line-height: 0.98;
            color: #121926;
            text-align: center;
        }

        .terms-intro {
            margin: 0 0 28px;
            font-family: var(--font-secondary);
            font-size: clamp(1rem, 1.45vw, 1.16rem);
            line-height: 1.78;
            color: rgba(17, 25, 38, 0.76);
            text-align: justify;
            text-justify: inter-word;
        }

        .terms-intro p,
        .terms-intro ul,
        .terms-intro ol,
        .terms-intro blockquote,
        .terms-body p,
        .terms-body ul,
        .terms-body ol,
        .terms-body blockquote,
        .terms-body h2,
        .terms-body h3 {
            margin: 0 0 14px;
        }

        .terms-intro ul,
        .terms-intro ol,
        .terms-body ul,
        .terms-body ol {
            padding-left: 24px;
        }

        .terms-intro li,
        .terms-body li {
            margin-bottom: 8px;
            text-align: justify;
            text-justify: inter-word;
        }

        .terms-body h2,
        .terms-body h3 {
            font-family: var(--font-primary);
            color: #121926;
            line-height: 1.08;
        }

        .terms-body blockquote,
        .terms-intro blockquote {
            padding-left: 16px;
            border-left: 3px solid rgba(12, 80, 93, 0.3);
        }

        .terms-subtitle {
            max-width: none;
            margin: 0;
            font-family: var(--font-secondary);
            font-size: clamp(1.04rem, 1.7vw, 1.28rem);
            font-weight: 600;
            line-height: 1.6;
            color: rgba(17, 25, 38, 0.78);
            text-align: center;
        }

        .terms-body {
            display: grid;
            gap: 16px;
            font-family: var(--font-secondary);
            font-size: clamp(0.98rem, 1.35vw, 1.08rem);
            line-height: 1.85;
            color: rgba(17, 25, 38, 0.8);
            text-align: justify;
            text-justify: inter-word;
        }

        .terms-body p {
            text-align: justify;
            text-justify: inter-word;
            white-space: pre-line;
        }

        .terms-body,
        .terms-intro {
            max-width: none;
            width: 100%;
        }

        .terms-page .site-footer {
            padding-top: 0;
            background: transparent;
        }

        .terms-page .location-section {
            padding-top: 104px;
            background: transparent;
        }

        .terms-page .office-section {
            padding-top: 0;
            background: transparent;
        }

        .terms-page .office-frame,
        .terms-page .location-shell,
        .terms-page .footer-shell {
            box-shadow: none;
        }

        @media (max-width: 768px) {
            .terms-page {
                padding: 18px;
            }

            .terms-card {
                width: 100%;
                padding: 16px;
                border-radius: 28px;
            }

            .terms-content {
                padding: 42px 8px 10px;
            }

            .terms-page .location-section {
                padding-top: 118px;
            }
        }
    </style>
@endpush

@section('content')
    <div class="terms-page">
        <div class="terms-shell">
            @include('partials.navbar')

            <section class="terms-card">
                <div class="terms-content">
                    <div class="terms-header">
                        <p class="terms-kicker">Legal</p>
                        <h1 class="terms-title">{{ $footerSetting?->terms_title ?: 'Terms and Conditions' }}</h1>

                        @if (filled($footerSetting?->terms_subtitle))
                            <p class="terms-subtitle">{{ $footerSetting->terms_subtitle }}</p>
                        @endif
                    </div>

                    <div class="terms-body">
                        @php($sanitizedTermsHtml = $footerSetting?->sanitizedTermsHtml())

                        @if (filled($sanitizedTermsHtml))
                            {!! $sanitizedTermsHtml !!}
                        @else
                            <p>This page is ready. Update the Terms and Conditions text from the admin portal footer section.</p>
                        @endif
                    </div>
                </div>
            </section>
        </div>

        @include('partials.location-section', ['footerSetting' => $footerSetting])
        @include('partials.office-section', ['footerSetting' => $footerSetting])
        @include('partials.footer')
    </div>
@endsection

@push('scripts')
    @include('partials.mobile-nav-script')
@endpush
