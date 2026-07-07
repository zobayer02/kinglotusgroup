@extends('layouts.app')

@section('title', 'King Lotus International')

@php($heroBackgroundUrl = $notice?->heroBackgroundUrl() ?: asset('images/beautiful-rustic-house-landscape.webp'))
@php($shareholderPhoneOptions = $footerSetting?->phoneOptions() ?? [])

@push('styles')
    <style>
        @include('partials.chrome-styles')

        .hero {
            position: relative;
            min-height: 0;
            padding: 28px;
            background: var(--section-surface);
        }

        .hero-shell {
            display: block;
            position: relative;
            z-index: auto;
            width: 100%;
            max-width: 1240px;
            margin: 0 auto;
            height: clamp(760px, calc(100vh - 56px), 980px);
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

        @keyframes heroTextCycle {
            0% {
                opacity: 0;
                transform: translateY(24px);
            }
            7%, 22% {
                opacity: 1;
                transform: translateY(0);
            }
            29%, 100% {
                opacity: 0;
                transform: translateY(-18px);
            }
        }

        .book-button {
            display: inline-flex;
            align-items: center;
            justify-content: center;
            min-width: 138px;
            height: 52px;
            padding: 0 24px;
            border-radius: 16px;
            background: var(--button-bg);
            color: #fff;
            font-weight: 600;
            transition: background-color 0.25s ease, transform 0.25s ease;
        }

        .book-button:hover {
            background: var(--button-hover);
            transform: translateY(-1px);
        }

        .hero-copy {
            position: relative;
            width: min(600px, 48%);
            min-height: 420px;
            margin-top: 416px;
            margin-left: 24px;
            color: #101214;
            text-shadow: none;
            animation: heroCopyEnter 0.72s ease 1.2s both;
        }

        .hero-message-stage {
            position: relative;
            min-height: 0;
        }

        .hero-message {
            position: static;
            width: 100%;
            opacity: 1;
            transform: none;
            animation: none;
        }

        .hero-kicker {
            display: block;
            margin-bottom: 8px;
            font-family: var(--font-secondary);
            font-size: 1.1rem;
            font-weight: 600;
            line-height: 1.1;
            color: #101214;
        }

        .hero-title {
            display: block;
            max-width: 640px;
            font-family: var(--font-primary);
            font-size: 4.45rem;
            font-weight: 400;
            line-height: 0.95;
            letter-spacing: 0;
            text-transform: none;
            color: #ffffff;
        }

        .hero-title.gold {
            color: #ffffff;
        }

        .hero-title.mint {
            color: #ffffff;
        }

        .hero-title.white {
            color: #ffffff;
        }

        .hero-title.sky {
            color: #ffffff;
        }

        .hero-script {
            display: block;
            margin-top: 12px;
            font-family: var(--font-secondary);
            font-size: 1.26rem;
            font-style: normal;
            font-weight: 500;
            line-height: 1.55;
            letter-spacing: 0;
            text-transform: none;
            color: #ffffff;
            max-width: 560px;
        }

        .join-button {
            display: inline-flex;
            align-items: center;
            justify-content: center;
            min-width: 158px;
            min-height: 52px;
            padding: 0 26px;
            appearance: none;
            border: 1px solid rgba(255, 255, 255, 0.46);
            border-radius: 999px;
            background:
                linear-gradient(180deg, rgba(255, 255, 255, 0.34) 0%, rgba(255, 255, 255, 0.16) 100%);
            backdrop-filter: blur(14px) saturate(150%);
            -webkit-backdrop-filter: blur(14px) saturate(150%);
            color: #ffffff;
            font-family: var(--font-secondary);
            font-size: 1rem;
            font-weight: 500;
            text-decoration: none;
            cursor: pointer;
            box-shadow:
                0 16px 36px rgba(6, 28, 39, 0.18),
                inset 0 1px 0 rgba(255, 255, 255, 0.58);
            transition:
                background-color 0.25s ease,
                color 0.25s ease,
                transform 0.25s ease,
                box-shadow 0.25s ease,
                border-color 0.25s ease;
        }

        .join-button:hover {
            background:
                linear-gradient(180deg, rgba(255, 255, 255, 0.46) 0%, rgba(255, 255, 255, 0.24) 100%);
            color: #ffffff;
            transform: translateY(-3px);
            border-color: rgba(255, 255, 255, 0.7);
            box-shadow:
                0 22px 48px rgba(6, 28, 39, 0.24),
                inset 0 1px 0 rgba(255, 255, 255, 0.72);
        }

        @keyframes heroCopyEnter {
            from {
                opacity: 0;
                transform: translateY(18px);
            }
            to {
                opacity: 1;
                transform: translateY(0);
            }
        }

        @media (max-width: 1100px) {
            .book-button {
                min-width: 114px;
                height: 44px;
                padding: 0 16px;
                border-radius: 13px;
            }

            .hero-copy {
                width: min(520px, 52%);
                margin-top: 352px;
                margin-left: 12px;
            }

            .hero-shell {
                height: clamp(700px, calc(100vh - 48px), 900px);
            }

            .hero-message-stage {
                min-height: 0;
            }

            .hero-title {
                font-size: 3.35rem;
            }

            .hero-kicker {
                font-size: 0.98rem;
            }

            .hero-script {
                font-size: 1.08rem;
            }
        }

        @media (max-width: 768px) {
            .hero {
                padding: 18px;
            }

            .hero-shell {
                width: 100%;
                display: flex;
                flex-direction: column;
                align-items: center;
                justify-content: flex-start;
                height: auto;
                min-height: calc(100vh - 36px);
                border-radius: 28px;
            }

            .hero-copy {
                position: absolute;
                left: 50%;
                top: 68%;
                width: min(96vw, 372px);
                min-height: auto;
                margin: 0;
                padding: 0 10px;
                text-align: center;
                transform: translate(-50%, -50%);
                animation: none;
                opacity: 1;
                transition: top 0.3s ease;
            }

            .floating-nav.is-open ~ .hero-copy {
                top: 79%;
            }

            .hero-message-stage {
                min-height: 0;
                text-align: center;
            }

            .hero-kicker {
                font-size: 0.88rem;
            }

            .hero-title {
                max-width: 86%;
                margin-left: auto;
                margin-right: auto;
                font-size: 1.68rem;
                line-height: 1.06;
            }

            .hero-script {
                max-width: 80%;
                font-size: 0.86rem;
                line-height: 1.36;
                margin-left: auto;
                margin-right: auto;
            }

            .join-button {
                min-width: 156px;
                min-height: 50px;
                padding: 0 24px;
                font-size: 1rem;
                margin-top: 18px;
            }
        }

        @media (prefers-reduced-motion: reduce) {
            .hero-copy,
            .hero-message {
                animation: none !important;
                transition: none !important;
                opacity: 1;
                transform: none;
            }

            .hero-message {
                position: static;
                display: none;
            }

            .hero-message:first-child {
                display: block;
            }
        }
    </style>
@endpush

@section('content')
    <section class="hero" id="home" data-nav-section="home">
        <div class="hero-shell">
            @include('partials.navbar')

            <div class="hero-copy" aria-label="King Lotus Group highlights">
                <div class="hero-message-stage">
                    <p class="hero-message">
                        <span class="hero-title">Invest in Luxury Hotel Ownership</span>
                        <span class="hero-script">Discover exclusive hotel share opportunities with transparent ownership, premium hospitality value, and trusted guidance.</span>
                    </p>
                </div>

                @if (count($shareholderPhoneOptions))
                    <button class="join-button" type="button" data-phone-trigger aria-haspopup="dialog" aria-expanded="false">
                        Become a Shareholder
                    </button>
                @else
                    <a class="join-button" href="#book">Become a Shareholder</a>
                @endif
            </div>
        </div>
    </section>

    @includeWhen(isset($notice) && $notice, 'partials.notice-banner', ['notice' => $notice])
    @includeWhen(isset($aboutSection) && $aboutSection && $aboutSection->hasRenderableContent(), 'partials.about-section', ['aboutSection' => $aboutSection])
    @includeWhen(isset($whySection) && $whySection && $whySection->hasRenderableContent(), 'partials.why-section', ['whySection' => $whySection])
    @includeWhen(isset($projectSection) && $projectSection && $projectSection->hasRenderableContent(), 'partials.projects-section', ['projectSection' => $projectSection])
    @includeWhen(isset($gallerySection) && $gallerySection && $gallerySection->hasRenderableContent(), 'partials.gallery-section', ['gallerySection' => $gallerySection])
    @includeWhen(isset($shareholderReviewSection) && $shareholderReviewSection && $shareholderReviewSection->hasRenderableContent(), 'partials.shareholder-review-section', ['shareholderReviewSection' => $shareholderReviewSection])
    @include('partials.location-section', ['footerSetting' => $footerSetting])
    @include('partials.office-section', ['footerSetting' => $footerSetting])
    @includeWhen(isset($leadershipSection) && $leadershipSection && $leadershipSection->shouldDisplayOnWebsite(), 'partials.leadership-section', ['leadershipSection' => $leadershipSection])
    @includeWhen(isset($valuedShareholderSection) && $valuedShareholderSection && $valuedShareholderSection->shouldDisplayOnWebsite(), 'partials.valued-shareholders-section', ['valuedShareholderSection' => $valuedShareholderSection])

    @include('partials.footer')
@endsection

@push('scripts')
    @include('partials.mobile-nav-script')
@endpush
