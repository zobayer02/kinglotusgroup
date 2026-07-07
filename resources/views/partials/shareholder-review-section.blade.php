@php
    use App\Models\ShareholderReviewSection;

    $reviewTitle = filled($shareholderReviewSection?->section_title)
        ? $shareholderReviewSection->section_title
        : ShareholderReviewSection::DEFAULT_TITLE;
    $reviewSubtitle = filled($shareholderReviewSection?->section_subtitle)
        ? $shareholderReviewSection->section_subtitle
        : ShareholderReviewSection::DEFAULT_SUBTITLE;
    $reviewViewAllLabel = ShareholderReviewSection::DEFAULT_VIEW_ALL_LABEL;
    $reviews = array_reverse($shareholderReviewSection?->reviews() ?? []);
    $featuredReviews = array_values(array_filter($reviews, static function (array $review): bool {
        return filled($review['thumbnail_path'] ?? null) && filled($review['thumbnail_url'] ?? null);
    }));
@endphp

@if ($featuredReviews)
    @once
        @push('styles')
            <style>
                .shareholder-review-section {
                    padding: 18px 28px 34px;
                    background: var(--section-surface);
                }

                .shareholder-review-shell {
                    width: 100%;
                    max-width: 1240px;
                    margin: 0 auto;
                    padding: 18px 18px 30px;
                    overflow: hidden;
                    opacity: 0;
                    transform: translateY(28px);
                    transition:
                        opacity 0.82s cubic-bezier(0.16, 1, 0.3, 1),
                        transform 0.82s cubic-bezier(0.16, 1, 0.3, 1);
                }

                .shareholder-review-section.is-visible .shareholder-review-shell {
                    opacity: 1;
                    transform: none;
                }

                .shareholder-review-head {
                    display: grid;
                    justify-items: center;
                    gap: 10px;
                    text-align: center;
                }

                .shareholder-review-kicker {
                    margin: 0;
                    color: rgba(16, 33, 44, 0.62);
                    font-size: 0.9rem;
                    font-weight: 700;
                    letter-spacing: 0.16em;
                    text-transform: uppercase;
                }

                .shareholder-review-title {
                    margin: 0;
                    max-width: 820px;
                    font-family: var(--font-primary);
                    font-size: var(--section-title-size);
                    font-weight: 400;
                    line-height: 0.98;
                    color: #101214;
                }

                .shareholder-review-showcase {
                    position: relative;
                    width: min(100%, 1180px);
                    max-width: none;
                    height: 350px;
                    margin: 22px auto 0;
                    overflow: hidden;
                    touch-action: pan-y;
                    user-select: none;
                }

                .shareholder-review-showcase.is-dragging {
                    cursor: grabbing;
                }

                .shareholder-review-showcase.is-animating .shareholder-review-slide,
                .shareholder-review-showcase.is-dragging .shareholder-review-slide {
                    transition: none;
                }

                .shareholder-review-slide {
                    position: absolute;
                    top: 50%;
                    left: 50%;
                    width: 198px;
                    opacity: 0;
                    pointer-events: none;
                    transform: translate(-50%, -50%);
                    transition:
                        transform 0.9s cubic-bezier(0.22, 1, 0.36, 1),
                        opacity 0.8s ease,
                        filter 0.8s ease;
                }

                .shareholder-review-slide.is-hidden-left {
                    opacity: 0;
                    filter: blur(1px);
                    transform: translate(calc(-50% - 610px), -22%) rotate(-16deg);
                }

                .shareholder-review-slide.is-hidden-right,
                .shareholder-review-slide.is-hidden {
                    opacity: 0;
                    filter: blur(1px);
                    transform: translate(calc(-50% + 610px), -22%) rotate(16deg);
                }

                .shareholder-review-slide.is-far-left {
                    opacity: 1;
                    z-index: 1;
                    pointer-events: auto;
                    transform: translate(calc(-50% - 454px), -27%) rotate(-10deg);
                }

                .shareholder-review-slide.is-left {
                    opacity: 1;
                    z-index: 2;
                    pointer-events: auto;
                    transform: translate(calc(-50% - 227px), -40%) rotate(-5deg);
                }

                .shareholder-review-slide.is-center {
                    opacity: 1;
                    z-index: 4;
                    pointer-events: auto;
                    transform: translate(-50%, -44%) rotate(0deg);
                }

                .shareholder-review-slide.is-right {
                    opacity: 1;
                    z-index: 2;
                    pointer-events: auto;
                    transform: translate(calc(-50% + 227px), -40%) rotate(5deg);
                }

                .shareholder-review-slide.is-far-right {
                    opacity: 1;
                    z-index: 1;
                    pointer-events: auto;
                    transform: translate(calc(-50% + 454px), -27%) rotate(10deg);
                }

                .shareholder-review-card {
                    position: relative;
                    display: block;
                    width: 100%;
                    color: #101724;
                }

                .shareholder-review-visual {
                    position: relative;
                    overflow: hidden;
                    border-radius: 10px;
                    background: transparent;
                }

                .shareholder-review-visual::after {
                    content: "";
                    position: absolute;
                    inset: 0;
                    background: linear-gradient(180deg, rgba(8, 18, 30, 0.04) 0%, rgba(8, 18, 30, 0.18) 100%);
                    pointer-events: none;
                }

                .shareholder-review-card:hover .shareholder-review-media img {
                    transform: scale(1.04);
                }

                .shareholder-review-card:hover .shareholder-review-copy {
                    transform: translateY(-1px);
                }

                .shareholder-review-media {
                    position: relative;
                    width: 100%;
                    aspect-ratio: 1;
                    overflow: hidden;
                    background: rgba(12, 80, 93, 0.12);
                    border-radius: 10px;
                    color: #101724;
                }

                .shareholder-review-media img,
                .shareholder-review-media iframe {
                    width: 100%;
                    height: 100%;
                    display: block;
                    border: 0;
                    object-fit: cover;
                    border-radius: 10px;
                    transition: transform 0.58s cubic-bezier(0.16, 1, 0.3, 1);
                }

                .shareholder-review-media iframe {
                    pointer-events: none;
                }

                .shareholder-review-card-body {
                    position: absolute;
                    inset: 0;
                    z-index: 2;
                    pointer-events: none;
                }

                .shareholder-review-card.has-thumbnail .shareholder-review-card-body {
                    display: block;
                }

                .shareholder-review-play {
                    position: absolute;
                    top: 50%;
                    left: 50%;
                    z-index: 2;
                    width: 0;
                    height: 0;
                    border-top: 18px solid transparent;
                    border-bottom: 18px solid transparent;
                    border-left: 28px solid #ffffff;
                    filter: drop-shadow(0 10px 18px rgba(0, 0, 0, 0.32));
                    transform: translate(-38%, -50%);
                    transition: border-left-color 0.22s ease, transform 0.22s ease;
                }

                .shareholder-review-card:hover .shareholder-review-play {
                    border-left-color: #ff0000;
                    transform: translate(-38%, -50%) scale(1.05);
                }

                .shareholder-review-hit {
                    position: absolute;
                    inset: 0;
                    z-index: 3;
                    border: 0;
                    background: transparent;
                    cursor: pointer;
                }

                .shareholder-review-copy {
                    padding-top: 7px;
                    text-align: center;
                    transition: transform 0.22s ease;
                }

                .shareholder-review-card.is-name-empty::after {
                    content: "";
                    display: block;
                    height: 28px;
                }

                .shareholder-review-name {
                    margin: 0;
                    color: #111827;
                    font-family: var(--font-secondary);
                    font-size: 0.74rem;
                    font-weight: 700;
                    line-height: 1.12;
                }

                .shareholder-review-meta {
                    display: inline-block;
                    margin-top: 2px;
                    font-family: var(--font-secondary);
                    font-size: 0.38rem;
                    font-weight: 600;
                    color: rgba(17, 24, 39, 0.42);
                    text-transform: uppercase;
                    letter-spacing: 0.08em;
                }

                .shareholder-review-control {
                    position: absolute;
                    top: 62%;
                    z-index: 8;
                    display: inline-flex;
                    align-items: center;
                    justify-content: center;
                    width: 40px;
                    height: 40px;
                    border: 1px solid rgba(255, 255, 255, 0.7);
                    border-radius: 999px;
                    background:
                        linear-gradient(145deg, rgba(255, 255, 255, 0.78), rgba(255, 255, 255, 0.28));
                    color: #12202c;
                    box-shadow:
                        inset 0 1px 0 rgba(255, 255, 255, 0.84),
                        0 14px 28px rgba(24, 43, 56, 0.12);
                    backdrop-filter: blur(14px);
                    -webkit-backdrop-filter: blur(14px);
                    cursor: pointer;
                    transform: translateY(-50%);
                    transition: transform 0.22s ease, background-color 0.22s ease, color 0.22s ease;
                }

                .shareholder-review-control:hover {
                    transform: translateY(-50%) scale(1.04);
                    background: #0c505d;
                    color: #ffffff;
                }

                .shareholder-review-control--prev {
                    left: 0;
                }

                .shareholder-review-control--next {
                    right: 0;
                }

                .shareholder-review-control svg {
                    width: 16px;
                    height: 16px;
                }

                .shareholder-review-dots {
                    display: flex;
                    justify-content: center;
                    gap: 6px;
                    margin-top: -18px;
                }

                .shareholder-review-dot {
                    width: 5px;
                    height: 5px;
                    border-radius: 999px;
                    border: 0;
                    padding: 0;
                    background: rgba(18, 25, 38, 0.18);
                    cursor: pointer;
                    transition: transform 0.22s ease, background-color 0.22s ease;
                }

                .shareholder-review-dot.is-active {
                    background: #1db7d6;
                    transform: scale(1.15);
                }

                .shareholder-review-actions {
                    display: flex;
                    justify-content: center;
                    margin-top: 14px;
                }

                .shareholder-review-view-all {
                    display: inline-flex;
                    align-items: center;
                    justify-content: center;
                    min-height: 50px;
                    padding: 0 26px;
                    border-radius: 999px;
                    border: 1px solid rgba(18, 25, 38, 0.72);
                    background: rgba(255, 255, 255, 0.9);
                    color: #121926;
                    font-family: var(--font-secondary);
                    font-size: 0.96rem;
                    font-weight: 600;
                    box-shadow: 0 12px 24px rgba(16, 33, 44, 0.08);
                    transition:
                        transform 0.22s ease,
                        background-color 0.22s ease,
                        border-color 0.22s ease,
                        color 0.22s ease,
                        box-shadow 0.22s ease;
                }

                .shareholder-review-view-all:hover {
                    transform: translateY(-2px);
                    border-color: #0c505d;
                    background: #0c505d;
                    color: #ffffff;
                    box-shadow: none;
                }

                .shareholder-review-section.is-visible .shareholder-review-head {
                    animation: navDropIn 0.58s ease both;
                }

                .shareholder-review-section.is-visible .shareholder-review-showcase {
                    animation: navDropIn 0.68s ease 120ms both;
                }

                .shareholder-review-modal[hidden] {
                    display: none;
                }

                .shareholder-review-modal {
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

                .shareholder-review-modal-dialog {
                    position: relative;
                    width: min(960px, 100%);
                    border-radius: 28px;
                    box-shadow: 0 32px 90px rgba(0, 0, 0, 0.32);
                }

                .shareholder-review-modal-media {
                    overflow: hidden;
                    border-radius: 28px;
                    border: 1px solid rgba(255, 255, 255, 0.2);
                    background: rgba(7, 12, 20, 0.92);
                }

                .shareholder-review-modal-frame {
                    width: 100%;
                    aspect-ratio: 16 / 9;
                    border: 0;
                    display: block;
                }

                .shareholder-review-modal-close {
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

                .shareholder-review-modal-close:hover {
                    background: #0c505d;
                    border-color: #0c505d;
                    transform: translateY(-1px);
                }

                @media (max-width: 980px) {
                    .shareholder-review-showcase {
                        width: min(100%, 860px);
                        height: 310px;
                    }

                    .shareholder-review-slide {
                        width: 148px;
                    }

                    .shareholder-review-slide.is-far-left { transform: translate(calc(-50% - 340px), -27%) rotate(-10deg); }
                    .shareholder-review-slide.is-left { transform: translate(calc(-50% - 170px), -40%) rotate(-5deg); }
                    .shareholder-review-slide.is-center { transform: translate(-50%, -44%) rotate(0deg); }
                    .shareholder-review-slide.is-right { transform: translate(calc(-50% + 170px), -40%) rotate(5deg); }
                    .shareholder-review-slide.is-far-right { transform: translate(calc(-50% + 340px), -27%) rotate(10deg); }
                    .shareholder-review-slide.is-hidden-left { transform: translate(calc(-50% - 500px), -24%) rotate(-15deg); }
                    .shareholder-review-slide.is-hidden-right,
                    .shareholder-review-slide.is-hidden { transform: translate(calc(-50% + 500px), -24%) rotate(15deg); }
                }

                @media (max-width: 768px) {
                    .shareholder-review-section {
                        padding: 14px 18px 28px;
                    }

                    .shareholder-review-shell {
                        padding: 18px 8px 24px;
                    }

                    .shareholder-review-title {
                        font-size: var(--section-title-size-mobile);
                    }

                    .shareholder-review-showcase {
                        height: 320px;
                    }

                    .shareholder-review-slide {
                        width: min(78vw, 260px);
                    }

                    .shareholder-review-slide.is-far-left,
                    .shareholder-review-slide.is-left,
                    .shareholder-review-slide.is-right,
                    .shareholder-review-slide.is-far-right {
                        opacity: 0;
                        pointer-events: none;
                    }

                    .shareholder-review-slide.is-center {
                        opacity: 1;
                        pointer-events: auto;
                        transform: translate(-50%, -45%) rotate(0deg);
                    }

                    .shareholder-review-slide.is-hidden-left {
                        transform: translate(calc(-50% - 250px), -26%) rotate(-14deg);
                    }

                    .shareholder-review-slide.is-hidden-right,
                    .shareholder-review-slide.is-hidden {
                        transform: translate(calc(-50% + 250px), -26%) rotate(14deg);
                    }

                    .shareholder-review-control {
                        top: 62%;
                        width: 34px;
                        height: 34px;
                    }

                    .shareholder-review-copy {
                        padding-top: 10px;
                    }

                    .shareholder-review-name {
                        font-size: 0.64rem;
                    }

                    .shareholder-review-meta {
                        font-size: 0.34rem;
                    }

                    .shareholder-review-dots {
                        margin-top: 8px;
                    }

                    .shareholder-review-view-all {
                        width: 100%;
                    }
                }
            </style>
        @endpush
    @endonce

    <section class="shareholder-review-section" id="shareholder-reviews">
        <div class="shareholder-review-shell">
            <div class="shareholder-review-head">
                <p class="shareholder-review-kicker">{{ $reviewSubtitle }}</p>
                <h2 class="shareholder-review-title">{{ $reviewTitle }}</h2>
            </div>

            <div class="shareholder-review-showcase" data-shareholder-review-showcase>
                <button class="shareholder-review-control shareholder-review-control--prev" type="button" data-shareholder-review-prev aria-label="Previous review">
                    <svg viewBox="0 0 24 24" fill="none" aria-hidden="true">
                        <path d="M15 18L9 12L15 6" stroke="currentColor" stroke-width="2.4" stroke-linecap="round" stroke-linejoin="round"/>
                    </svg>
                </button>

                @foreach ($featuredReviews as $review)
                    @php
                        $hasThumbnail = filled($review['thumbnail_path'] ?? null) && filled($review['thumbnail_url'] ?? null);
                        $hasName = filled($review['name'] ?? null);
                        $reviewLabel = $hasName ? $review['name'].' review' : 'shareholder review';
                    @endphp
                    <article class="shareholder-review-slide" data-shareholder-review-slide>
                        <div class="shareholder-review-card {{ $hasThumbnail ? 'has-thumbnail' : 'has-video-preview' }} {{ $hasName ? 'has-name' : 'is-name-empty' }}">
                            <div class="shareholder-review-visual">
                                <div class="shareholder-review-media">
                                    @if ($hasThumbnail)
                                        <img src="{{ $review['thumbnail_url'] }}" alt="{{ $reviewLabel }} thumbnail" loading="lazy" decoding="async">
                                    @elseif ($review['preview_embed_url'])
                                        <iframe src="{{ $review['preview_embed_url'] }}" loading="lazy" allow="encrypted-media; picture-in-picture" title="{{ $reviewLabel }} preview"></iframe>
                                    @endif
                                </div>

                                @if ($hasThumbnail)
                                    <div class="shareholder-review-card-body">
                                        <span class="shareholder-review-play" aria-hidden="true"></span>
                                    </div>
                                @endif

                                <button class="shareholder-review-hit" type="button" data-shareholder-review-video="{{ $review['embed_url'] }}" aria-label="Play {{ $reviewLabel }}"></button>
                            </div>

                            @if ($hasName)
                                <div class="shareholder-review-copy">
                                    <p class="shareholder-review-name">{{ $review['name'] }}</p>
                                    <span class="shareholder-review-meta">Watch Review</span>
                                </div>
                            @endif
                        </div>
                    </article>
                @endforeach

                <button class="shareholder-review-control shareholder-review-control--next" type="button" data-shareholder-review-next aria-label="Next review">
                    <svg viewBox="0 0 24 24" fill="none" aria-hidden="true">
                        <path d="M9 18L15 12L9 6" stroke="currentColor" stroke-width="2.4" stroke-linecap="round" stroke-linejoin="round"/>
                    </svg>
                </button>
            </div>

            <div class="shareholder-review-dots" data-shareholder-review-dots></div>

            <div class="shareholder-review-actions">
                <a class="shareholder-review-view-all" href="{{ route('reviews.index') }}">{{ $reviewViewAllLabel }}</a>
            </div>
        </div>
    </section>

    <div class="shareholder-review-modal" id="shareholder-review-video-modal" hidden>
        <div class="shareholder-review-modal-dialog">
            <button class="shareholder-review-modal-close" type="button" data-shareholder-review-close aria-label="Close video">
                <svg viewBox="0 0 24 24" fill="none" aria-hidden="true">
                    <path d="M6 6L18 18M18 6L6 18" stroke="currentColor" stroke-width="2.4" stroke-linecap="round"/>
                </svg>
            </button>
            <div class="shareholder-review-modal-media">
                <iframe
                    class="shareholder-review-modal-frame"
                    id="shareholder-review-video-frame"
                    src=""
                    allow="autoplay; encrypted-media; picture-in-picture"
                    allowfullscreen
                    title="Shareholder review video"
                ></iframe>
            </div>
        </div>
    </div>

    @once
        @push('scripts')
            <script>
                document.addEventListener('DOMContentLoaded', () => {
                    const modal = document.getElementById('shareholder-review-video-modal');
                    const frame = document.getElementById('shareholder-review-video-frame');

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

                    document.querySelectorAll('[data-shareholder-review-showcase]').forEach((showcase) => {
                        const slides = Array.from(showcase.querySelectorAll('[data-shareholder-review-slide]'));
                        const dotsRoot = showcase.parentElement.querySelector('[data-shareholder-review-dots]');
                        const previousButton = showcase.querySelector('[data-shareholder-review-prev]');
                        const nextButton = showcase.querySelector('[data-shareholder-review-next]');

                        if (!slides.length) {
                            return;
                        }

                        let activeIndex = Math.min(2, slides.length - 1);
                        let currentPosition = activeIndex;
                        let targetPosition = currentPosition;
                        let intervalId = null;
                        let isPaused = false;
                        let dragPointerId = null;
                        let dragStartX = 0;
                        let dragStartPosition = 0;
                        let dragCurrentX = 0;
                        let dragMoved = false;
                        let pressedSlideIndex = null;
                        let settleFrameId = null;
                        let suppressClick = false;
                        let clickSuppressTimeoutId = null;

                        const shouldStayPaused = () => {
                            return isPaused || showcase.matches(':hover') || showcase.contains(document.activeElement);
                        };

                        const clearClickSuppression = () => {
                            if (clickSuppressTimeoutId) {
                                window.clearTimeout(clickSuppressTimeoutId);
                                clickSuppressTimeoutId = null;
                            }

                            suppressClick = false;
                        };

                        const suppressNextClick = () => {
                            suppressClick = true;

                            if (clickSuppressTimeoutId) {
                                window.clearTimeout(clickSuppressTimeoutId);
                            }

                            clickSuppressTimeoutId = window.setTimeout(() => {
                                suppressClick = false;
                                clickSuppressTimeoutId = null;
                            }, 220);
                        };

                        const getDragThreshold = () => (window.innerWidth <= 768 ? 12 : 26);
                        const getPixelsPerStep = () => {
                            if (window.innerWidth <= 768) {
                                return 125;
                            }

                            if (window.innerWidth <= 1080) {
                                return 170;
                            }

                            return 220;
                        };

                        const getNearestIndex = (value) => {
                            const total = slides.length;

                            if (total === 0) {
                                return 0;
                            }

                            return ((Math.round(value) % total) + total) % total;
                        };

                        const normalizePosition = (value) => {
                            const total = slides.length;

                            if (total === 0) {
                                return 0;
                            }

                            return ((value % total) + total) % total;
                        };

                        const getShortestDeltaToIndex = (fromPosition, targetIndex) => {
                            const total = slides.length;
                            let delta = targetIndex - normalizePosition(fromPosition);

                            if (delta > total / 2) {
                                delta -= total;
                            } else if (delta < -total / 2) {
                                delta += total;
                            }

                            return delta;
                        };

                        const getWrappedDifference = (index, position) => {
                            const total = slides.length;
                            let diff = index - position;

                            if (diff > total / 2) {
                                diff -= total;
                            } else if (diff < -total / 2) {
                                diff += total;
                            }

                            return diff;
                        };

                        const getMotionAnchors = () => {
                            if (window.innerWidth <= 768) {
                                return [
                                    { diff: -3, x: -320, y: -26, rotate: -10, opacity: 0, blur: 1, z: 0, interactive: false },
                                    { diff: -2, x: -260, y: -30, rotate: -8, opacity: 0, blur: 0.9, z: 0, interactive: false },
                                    { diff: -1, x: -185, y: -38, rotate: -4, opacity: 0, blur: 0.55, z: 1, interactive: false },
                                    { diff: 0, x: 0, y: -45, rotate: 0, opacity: 1, blur: 0, z: 4, interactive: true },
                                    { diff: 1, x: 185, y: -38, rotate: 4, opacity: 0, blur: 0.55, z: 1, interactive: false },
                                    { diff: 2, x: 260, y: -30, rotate: 8, opacity: 0, blur: 0.9, z: 0, interactive: false },
                                    { diff: 3, x: 320, y: -26, rotate: 10, opacity: 0, blur: 1, z: 0, interactive: false },
                                ];
                            }

                            if (window.innerWidth <= 1080) {
                                return [
                                    { diff: -3, x: -500, y: -24, rotate: -15, opacity: 0, blur: 1, z: 0, interactive: false },
                                    { diff: -2, x: -340, y: -27, rotate: -10, opacity: 1, blur: 0.2, z: 1, interactive: true },
                                    { diff: -1, x: -170, y: -40, rotate: -5, opacity: 1, blur: 0, z: 2, interactive: true },
                                    { diff: 0, x: 0, y: -44, rotate: 0, opacity: 1, blur: 0, z: 4, interactive: true },
                                    { diff: 1, x: 170, y: -40, rotate: 5, opacity: 1, blur: 0, z: 2, interactive: true },
                                    { diff: 2, x: 340, y: -27, rotate: 10, opacity: 1, blur: 0.2, z: 1, interactive: true },
                                    { diff: 3, x: 500, y: -24, rotate: 15, opacity: 0, blur: 1, z: 0, interactive: false },
                                ];
                            }

                            return [
                                { diff: -3, x: -610, y: -22, rotate: -16, opacity: 0, blur: 1, z: 0, interactive: false },
                                { diff: -2, x: -454, y: -27, rotate: -10, opacity: 1, blur: 0.2, z: 1, interactive: true },
                                { diff: -1, x: -227, y: -40, rotate: -5, opacity: 1, blur: 0, z: 2, interactive: true },
                                { diff: 0, x: 0, y: -44, rotate: 0, opacity: 1, blur: 0, z: 4, interactive: true },
                                { diff: 1, x: 227, y: -40, rotate: 5, opacity: 1, blur: 0, z: 2, interactive: true },
                                { diff: 2, x: 454, y: -27, rotate: 10, opacity: 1, blur: 0.2, z: 1, interactive: true },
                                { diff: 3, x: 610, y: -22, rotate: 16, opacity: 0, blur: 1, z: 0, interactive: false },
                            ];
                        };

                        const interpolate = (from, to, progress) => from + ((to - from) * progress);

                        const getInterpolatedState = (diff) => {
                            const anchors = getMotionAnchors();

                            if (diff <= anchors[0].diff) {
                                return anchors[0];
                            }

                            if (diff >= anchors[anchors.length - 1].diff) {
                                return anchors[anchors.length - 1];
                            }

                            for (let index = 0; index < anchors.length - 1; index += 1) {
                                const left = anchors[index];
                                const right = anchors[index + 1];

                                if (diff >= left.diff && diff <= right.diff) {
                                    const progress = (diff - left.diff) / (right.diff - left.diff);

                                    return {
                                        x: interpolate(left.x, right.x, progress),
                                        y: interpolate(left.y, right.y, progress),
                                        rotate: interpolate(left.rotate, right.rotate, progress),
                                        opacity: interpolate(left.opacity, right.opacity, progress),
                                        blur: interpolate(left.blur, right.blur, progress),
                                        z: interpolate(left.z, right.z, progress),
                                        interactive: progress < 0.5 ? left.interactive : right.interactive,
                                    };
                                }
                            }

                            return anchors[anchors.length - 1];
                        };

                        const dots = slides.map((_, index) => {
                            if (!dotsRoot) {
                                return null;
                            }

                            const dot = document.createElement('button');
                            dot.className = 'shareholder-review-dot';
                            dot.type = 'button';
                            dot.setAttribute('aria-label', `Show review ${index + 1}`);
                            dot.addEventListener('click', () => {
                                animateToIndex(index);
                                restart();
                            });
                            dotsRoot.appendChild(dot);

                            return dot;
                        });

                        const render = () => {
                            slides.forEach((slide, index) => {
                                const diff = getWrappedDifference(index, normalizePosition(currentPosition));
                                const state = getInterpolatedState(diff);
                                slide.className = 'shareholder-review-slide';
                                slide.style.transform = `translate(calc(-50% + ${state.x}px), ${state.y}%) rotate(${state.rotate}deg)`;
                                slide.style.opacity = `${state.opacity}`;
                                slide.style.filter = `blur(${state.blur}px)`;
                                slide.style.zIndex = `${Math.round(state.z)}`;
                                slide.style.pointerEvents = state.interactive ? 'auto' : 'none';
                            });

                            activeIndex = getNearestIndex(normalizePosition(currentPosition));

                            dots.forEach((dot, index) => {
                                dot?.classList.toggle('is-active', index === activeIndex);
                            });
                        };

                        const stopSettleAnimation = () => {
                            if (settleFrameId) {
                                window.cancelAnimationFrame(settleFrameId);
                                settleFrameId = null;
                            }

                            showcase.classList.remove('is-animating');
                        };

                        const startSettleAnimation = () => {
                            stopSettleAnimation();
                            showcase.classList.add('is-animating');

                            const tick = () => {
                                const delta = targetPosition - currentPosition;

                                if (Math.abs(delta) < 0.001) {
                                    currentPosition = targetPosition;
                                    render();
                                    showcase.classList.remove('is-animating');
                                    settleFrameId = null;
                                    return;
                                }

                                currentPosition += delta * 0.16;
                                render();
                                settleFrameId = window.requestAnimationFrame(tick);
                            };

                            settleFrameId = window.requestAnimationFrame(tick);
                        };

                        const animateToIndex = (index) => {
                            targetPosition = currentPosition + getShortestDeltaToIndex(currentPosition, index);
                            startSettleAnimation();
                        };

                        const animateBy = (direction) => {
                            targetPosition = Math.round(currentPosition) + direction;
                            startSettleAnimation();
                        };

                        const handleTapAction = (index) => {
                            if (index === null || index < 0 || index >= slides.length) {
                                return false;
                            }

                            const videoTrigger = slides[index]?.querySelector('[data-shareholder-review-video]');
                            openModal(videoTrigger?.dataset.shareholderReviewVideo ?? '');
                            return true;
                        };

                        const endDrag = (shouldCommit) => {
                            if (dragPointerId === null) {
                                return;
                            }

                            const deltaX = dragCurrentX - dragStartX;
                            const exceededThreshold = Math.abs(deltaX) >= getDragThreshold();

                            if (showcase.hasPointerCapture?.(dragPointerId)) {
                                showcase.releasePointerCapture(dragPointerId);
                            }

                            showcase.classList.remove('is-dragging');
                            dragPointerId = null;
                            dragStartX = 0;
                            dragStartPosition = 0;
                            dragCurrentX = 0;
                            const tappedSlideIndex = pressedSlideIndex;
                            pressedSlideIndex = null;

                            if (shouldCommit && (exceededThreshold || dragMoved)) {
                                suppressNextClick();
                            } else {
                                clearClickSuppression();
                            }

                            targetPosition = Math.round(currentPosition);
                            dragMoved = false;

                            if (shouldCommit && !exceededThreshold && tappedSlideIndex !== null) {
                                if (handleTapAction(tappedSlideIndex)) {
                                    suppressNextClick();
                                } else {
                                    startSettleAnimation();
                                }
                            } else {
                                startSettleAnimation();
                            }

                            resume();
                            restart();
                        };

                        const handlePointerDown = (event) => {
                            if (event.button !== undefined && event.button !== 0) {
                                return;
                            }

                            if (event.target.closest('[data-shareholder-review-prev], [data-shareholder-review-next]')) {
                                return;
                            }

                            dragPointerId = event.pointerId;
                            dragStartX = event.clientX;
                            dragStartPosition = currentPosition;
                            dragCurrentX = event.clientX;
                            dragMoved = false;
                            pressedSlideIndex = slides.indexOf(event.target.closest('[data-shareholder-review-slide]'));
                            stopSettleAnimation();
                            showcase.classList.add('is-dragging');
                            pause();
                            showcase.setPointerCapture?.(event.pointerId);
                        };

                        const handlePointerMove = (event) => {
                            if (dragPointerId !== event.pointerId) {
                                return;
                            }

                            dragCurrentX = event.clientX;

                            const deltaX = dragCurrentX - dragStartX;

                            if (Math.abs(deltaX) > 6) {
                                event.preventDefault();
                            }

                            if (Math.abs(deltaX) < 3) {
                                return;
                            }

                            dragMoved = true;
                            currentPosition = dragStartPosition - (deltaX / getPixelsPerStep());
                            render();
                        };

                        const start = () => {
                            if (slides.length < 2 || intervalId) {
                                return;
                            }

                            intervalId = window.setInterval(() => {
                                if (shouldStayPaused()) {
                                    return;
                                }

                                animateBy(-1);
                            }, 3000);
                        };

                        const stop = () => {
                            if (intervalId) {
                                window.clearInterval(intervalId);
                                intervalId = null;
                            }
                        };

                        const restart = () => {
                            stop();
                            if (!isPaused) {
                                start();
                            }
                        };

                        const move = (direction) => {
                            animateBy(direction);
                            restart();
                        };

                        const pause = () => {
                            isPaused = true;
                            stop();
                        };

                        const resume = () => {
                            isPaused = false;
                            start();
                        };

                        showcase.addEventListener('mouseenter', pause);
                        showcase.addEventListener('mouseleave', resume);
                        showcase.addEventListener('pointerenter', pause);
                        showcase.addEventListener('pointerleave', resume);
                        showcase.addEventListener('focusin', pause);
                        showcase.addEventListener('focusout', resume);
                        showcase.addEventListener('pointerdown', handlePointerDown);
                        showcase.addEventListener('pointermove', handlePointerMove);
                        showcase.addEventListener('pointerup', () => endDrag(true));
                        showcase.addEventListener('pointercancel', () => endDrag(false));
                        showcase.addEventListener('lostpointercapture', () => endDrag(false));
                        showcase.addEventListener('dragstart', (event) => event.preventDefault());
                        showcase.addEventListener('click', (event) => {
                            if (!suppressClick) {
                                return;
                            }

                            event.preventDefault();
                            event.stopPropagation();
                            clearClickSuppression();
                        }, true);

                        previousButton?.addEventListener('click', () => move(-1));
                        nextButton?.addEventListener('click', () => move(1));

                        slides.forEach((slide) => {
                            slide.addEventListener('mouseenter', pause);
                            slide.addEventListener('mouseleave', resume);
                            slide.addEventListener('pointerenter', pause);
                            slide.addEventListener('pointerleave', resume);
                        });

                        render();
                        start();
                    });

                    if (!modal || !frame) {
                        return;
                    }

                    modal.addEventListener('click', (event) => {
                        if (event.target === modal || event.target.closest('[data-shareholder-review-close]')) {
                            closeModal();
                        }
                    });

                    document.addEventListener('keydown', (event) => {
                        if (event.key === 'Escape' && !modal.hidden) {
                            closeModal();
                        }
                    });
                });
            </script>
        @endpush
    @endonce
@endif
