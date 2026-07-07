@extends('layouts.app')

@section('title', \App\Models\ShareholderReviewSection::DEFAULT_PAGE_TITLE . ' | King Lotus International')

@push('styles')
    <style>
        @include('partials.chrome-styles')

        .review-page {
            min-height: 100vh;
            padding: 28px;
            background: var(--section-surface);
        }

        .review-page-shell {
            width: 100%;
            max-width: 1240px;
            margin: 0 auto;
            display: grid;
            gap: 38px;
        }

        .review-page-header {
            padding-top: 118px;
            display: grid;
            gap: 18px;
            justify-items: center;
            text-align: center;
        }

        .review-page-kicker {
            margin: 0;
            color: rgba(16, 33, 44, 0.62);
            font-size: 0.92rem;
            font-weight: 700;
            letter-spacing: 0.16em;
            text-transform: uppercase;
        }

        .review-page-title {
            margin: 0;
            max-width: 980px;
            font-family: var(--font-primary);
            font-size: var(--section-title-size);
            font-weight: 400;
            line-height: 0.94;
            color: #101214;
        }

        .review-page-subtitle {
            margin: 0;
            max-width: 760px;
            font-family: var(--font-secondary);
            font-size: clamp(1rem, 1.5vw, 1.12rem);
            line-height: 1.8;
            text-align: center;
            color: rgba(16, 33, 44, 0.76);
        }

        .review-card-grid {
            display: grid;
            grid-template-columns: repeat(4, minmax(0, 1fr));
            gap: 18px;
        }

        .review-card {
            --review-card-start: translateY(58px) scale(0.965);
            position: relative;
            overflow: hidden;
            min-height: 236px;
            border: 1px solid rgba(178, 193, 204, 0.58);
            border-radius: 26px;
            background: rgba(255, 255, 255, 0.42);
            color: #101214;
            box-shadow: 0 18px 38px rgba(18, 33, 44, 0.1);
            cursor: pointer;
            opacity: 0;
            transform: var(--review-card-start);
            transition: border-color 0.18s ease, box-shadow 0.18s ease;
            will-change: transform, opacity;
        }

        .review-card:hover,
        .review-card:focus-visible {
            outline: none;
            border-color: rgba(12, 80, 93, 0.48);
            box-shadow: 0 18px 32px rgba(12, 80, 93, 0.14);
        }

        .review-card:nth-child(4n + 1) {
            --review-card-start: translateX(-82px) scale(0.965);
        }

        .review-card:nth-child(4n + 2) {
            --review-card-start: translateY(-72px) scale(0.965);
        }

        .review-card:nth-child(4n + 3) {
            --review-card-start: translateX(82px) scale(0.965);
        }

        .review-card:nth-child(4n) {
            --review-card-start: translateY(72px) scale(0.965);
        }

        .review-card-grid.is-visible .review-card {
            animation: review-card-reveal 0.72s cubic-bezier(0.16, 1, 0.3, 1) both;
        }

        .review-card-grid.is-visible .review-card:nth-child(4n + 1) {
            animation-delay: 0.06s;
        }

        .review-card-grid.is-visible .review-card:nth-child(4n + 2) {
            animation-delay: 0.14s;
        }

        .review-card-grid.is-visible .review-card:nth-child(4n + 3) {
            animation-delay: 0.22s;
        }

        .review-card-grid.is-visible .review-card:nth-child(4n) {
            animation-delay: 0.3s;
        }

        .review-card-media {
            position: absolute;
            inset: 0;
            transform: scale(1.035);
            transition: transform 0.24s ease;
            will-change: transform;
        }

        .review-card-media img,
        .review-card-media iframe {
            width: 100%;
            height: 100%;
            display: block;
            border: 0;
            object-fit: cover;
        }

        .review-card-media iframe {
            pointer-events: none;
        }

        .review-card:hover .review-card-media,
        .review-card:focus-visible .review-card-media {
            transform: scale(1);
        }

        .review-card::after {
            content: "";
            position: absolute;
            inset: 0;
            background: linear-gradient(180deg, rgba(5, 12, 17, 0.08) 0%, rgba(5, 12, 17, 0.82) 100%);
            pointer-events: none;
        }

        .review-card-copy {
            position: absolute;
            inset: auto 0 0;
            z-index: 1;
            display: grid;
            gap: 8px;
            padding: 24px;
            color: #ffffff;
        }

        .review-card-title {
            margin: 0;
            font-family: var(--font-primary);
            font-size: clamp(1.8rem, 2.5vw, 2.8rem);
            font-weight: 600;
            line-height: 1;
        }

        .review-card-subtitle {
            margin: 0;
            font-size: 0.76rem;
            line-height: 1.2;
            white-space: nowrap;
            color: rgba(255, 255, 255, 0.8);
        }

        .review-card-play {
            position: absolute;
            top: 50%;
            left: 50%;
            z-index: 1;
            width: 0;
            height: 0;
            border-top: 18px solid transparent;
            border-bottom: 18px solid transparent;
            border-left: 28px solid #ffffff;
            filter: drop-shadow(0 10px 18px rgba(0, 0, 0, 0.32));
            transform: translate(-38%, -50%);
            pointer-events: none;
        }

        .review-card-empty {
            padding: 28px;
            border: 1px solid rgba(178, 193, 204, 0.58);
            border-radius: 26px;
            background: rgba(255, 255, 255, 0.44);
            color: rgba(16, 33, 44, 0.72);
            font-size: 1rem;
            line-height: 1.7;
            text-align: center;
        }

        @keyframes review-card-reveal {
            from {
                opacity: 0;
                transform: var(--review-card-start);
            }

            to {
                opacity: 1;
                transform: translate3d(0, 0, 0) scale(1);
            }
        }

        .review-video-modal[hidden] {
            display: none;
        }

        .review-video-modal {
            position: fixed;
            inset: 0;
            z-index: 1600;
            display: grid;
            place-items: center;
            padding: 24px;
            background: rgba(9, 15, 24, 0.62);
            backdrop-filter: blur(12px);
            -webkit-backdrop-filter: blur(12px);
        }

        .review-video-dialog {
            position: relative;
            width: min(960px, 100%);
            border-radius: 28px;
            box-shadow: 0 32px 90px rgba(0, 0, 0, 0.32);
        }

        .review-video-media {
            overflow: hidden;
            border-radius: 28px;
            border: 1px solid rgba(255, 255, 255, 0.2);
            background: rgba(7, 12, 20, 0.92);
        }

        .review-video-frame {
            width: 100%;
            aspect-ratio: 16 / 9;
            border: 0;
            display: block;
        }

        .review-video-close {
            position: absolute;
            top: -18px;
            right: -18px;
            z-index: 1;
            display: inline-flex;
            align-items: center;
            justify-content: center;
            width: 42px;
            height: 42px;
            border: 1px solid rgba(255, 255, 255, 0.22);
            border-radius: 999px;
            background: rgba(7, 12, 20, 0.52);
            color: #ffffff;
            cursor: pointer;
            transition: background-color 0.22s ease, border-color 0.22s ease, transform 0.22s ease;
        }

        .review-video-close:hover {
            background: #0c505d;
            border-color: #0c505d;
            transform: translateY(-1px);
        }

        @media (max-width: 980px) {
            .review-card-grid {
                grid-template-columns: repeat(2, minmax(0, 1fr));
            }
        }

        @media (max-width: 768px) {
            .review-page {
                padding: 22px 18px;
            }

            .review-page-header {
                padding-top: 96px;
            }

            .review-card-grid {
                grid-template-columns: 1fr;
            }

            .review-card {
                min-height: 214px;
            }

            .review-video-modal {
                padding: 14px;
            }

            .review-video-close {
                top: -12px;
                right: -6px;
            }
        }
    </style>
@endpush

@section('content')
    @php
        $pageTitle = filled($shareholderReviewSection?->section_title)
            ? $shareholderReviewSection->section_title
            : \App\Models\ShareholderReviewSection::DEFAULT_PAGE_TITLE;
        $pageSubtitle = filled($shareholderReviewSection?->section_subtitle)
            ? $shareholderReviewSection->section_subtitle
            : \App\Models\ShareholderReviewSection::DEFAULT_PAGE_SUBTITLE;
        $reviews = array_reverse($shareholderReviewSection?->reviews() ?? []);
    @endphp

    <div class="review-page">
        <div class="review-page-shell">
            @include('partials.navbar')

            <section class="review-page-header">
                <p class="review-page-kicker">Reviews</p>
                <h1 class="review-page-title">{{ $pageTitle }}</h1>
                <p class="review-page-subtitle">{{ $pageSubtitle }}</p>
            </section>

            @if ($reviews)
                <section class="review-card-grid" aria-label="Shareholder review videos">
                    @foreach ($reviews as $index => $review)
                        @php
                            $hasName = filled($review['name'] ?? null);
                            $reviewLabel = $hasName ? $review['name'].' review' : 'shareholder review';
                        @endphp

                        <button
                            class="review-card has-video-preview"
                            type="button"
                            data-review-video="{{ $review['embed_url'] }}"
                            aria-label="Play {{ $reviewLabel }}"
                        >
                            <span class="review-card-media" aria-hidden="true">
                                @if ($review['preview_embed_url'])
                                    <iframe src="{{ $review['preview_embed_url'] }}" loading="lazy" allow="encrypted-media; picture-in-picture" title="{{ $reviewLabel }} preview"></iframe>
                                @endif
                            </span>

                            <span class="review-card-play" aria-hidden="true"></span>

                            <span class="review-card-copy">
                                @if ($hasName)
                                    <span class="review-card-title">{{ $review['name'] }}</span>
                                @endif
                                <span class="review-card-subtitle">Click to watch the full shareholder story.</span>
                            </span>
                        </button>
                    @endforeach
                </section>
            @else
                <p class="review-card-empty">No shareholder review videos have been published yet.</p>
            @endif

            <div class="review-video-modal" data-review-video-modal hidden>
                <div class="review-video-dialog" role="dialog" aria-modal="true" aria-label="Shareholder review video player">
                    <button class="review-video-close" type="button" data-review-video-close aria-label="Close video">
                        <svg viewBox="0 0 24 24" fill="none" aria-hidden="true">
                            <path d="M6 6L18 18M18 6L6 18" stroke="currentColor" stroke-width="2.4" stroke-linecap="round"/>
                        </svg>
                    </button>
                    <div class="review-video-media">
                        <iframe
                            class="review-video-frame"
                            data-review-video-frame
                            src=""
                            allow="autoplay; encrypted-media; picture-in-picture"
                            allowfullscreen
                            title="Shareholder review video"
                        ></iframe>
                    </div>
                </div>
            </div>
        </div>

        @include('partials.footer')
    </div>
@endsection

@push('scripts')
    @include('partials.mobile-nav-script')
    <script>
        document.addEventListener('DOMContentLoaded', () => {
            const cardGrid = document.querySelector('.review-card-grid');
            const cards = Array.from(document.querySelectorAll('[data-review-video]'));
            const modal = document.querySelector('[data-review-video-modal]');
            const frame = modal?.querySelector('[data-review-video-frame]');
            const closeButton = modal?.querySelector('[data-review-video-close]');
            const revealGrid = (target) => {
                window.requestAnimationFrame(() => {
                    window.requestAnimationFrame(() => {
                        target.classList.add('is-visible');
                    });
                });
            };

            if (cardGrid) {
                if (window.matchMedia('(prefers-reduced-motion: reduce)').matches || !('IntersectionObserver' in window)) {
                    revealGrid(cardGrid);
                } else {
                    const observer = new IntersectionObserver((entries, instance) => {
                        entries.forEach((entry) => {
                            if (!entry.isIntersecting) {
                                return;
                            }

                            revealGrid(entry.target);
                            instance.unobserve(entry.target);
                        });
                    }, {
                        threshold: 0.18,
                        rootMargin: '0px 0px -8% 0px',
                    });

                    observer.observe(cardGrid);
                }
            }

            const openModal = (embedUrl) => {
                if (!modal || !frame || !embedUrl) {
                    return;
                }

                frame.src = embedUrl;
                modal.hidden = false;
                document.body.style.overflow = 'hidden';
            };

            const closeModal = () => {
                if (!modal || !frame) {
                    return;
                }

                modal.hidden = true;
                frame.src = '';
                document.body.style.overflow = '';
            };

            cards.forEach((card) => {
                card.addEventListener('click', () => {
                    openModal(card.dataset.reviewVideo);
                });
            });

            closeButton?.addEventListener('click', closeModal);

            modal?.addEventListener('click', (event) => {
                if (event.target === modal) {
                    closeModal();
                }
            });

            document.addEventListener('keydown', (event) => {
                if (event.key === 'Escape' && modal && !modal.hidden) {
                    closeModal();
                }
            });
        });
    </script>
@endpush


















