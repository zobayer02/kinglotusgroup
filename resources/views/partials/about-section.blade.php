@once
    @push('styles')
        <style>
            .about-section {
                padding: 20px 28px 28px;
                background: var(--section-surface);
            }

            .about-shell {
                width: 100%;
                max-width: 1240px;
                margin: 0 auto;
                padding: 20px 24px;
                opacity: 0;
                transform: translateY(22px);
            }

            .about-grid {
                display: grid;
                align-items: center;
                gap: 34px;
                grid-template-columns: minmax(180px, 260px) minmax(0, 1fr) minmax(180px, 260px);
            }

            .about-copy {
                text-align: center;
                color: #111827;
                opacity: 0;
                transform: translateY(-12px);
            }

            .about-title {
                margin: 0 0 12px;
                font-family: var(--font-primary);
                font-size: var(--section-title-size);
                font-weight: 400;
                line-height: 1.05;
                color: #121926;
                white-space: nowrap;
            }

            .about-subtitle {
                margin: 0 0 28px;
                font-family: var(--font-secondary);
                font-size: clamp(1rem, 1.9vw, 1.42rem);
                line-height: 1.45;
                color: rgba(17, 25, 38, 0.78);
            }

            .about-description {
                margin: 0;
                font-family: var(--font-secondary);
                font-size: clamp(1.02rem, 1.7vw, 1.32rem);
                line-height: 1.5;
                color: rgba(17, 25, 38, 0.72);
                text-align: justify;
                text-align-last: center;
            }

            .about-video-card {
                position: relative;
                display: block;
                width: 100%;
                max-width: 340px;
                aspect-ratio: 16 / 10;
                overflow: hidden;
                border: 0;
                border-radius: 28px;
                padding: 0;
                background:
                    linear-gradient(180deg, rgba(255, 255, 255, 0.22) 0%, rgba(255, 255, 255, 0.04) 100%);
                box-shadow:
                    0 24px 50px rgba(24, 43, 56, 0.16),
                    inset 0 1px 0 rgba(255, 255, 255, 0.46);
                cursor: pointer;
                opacity: 0;
                will-change: transform;
                transition:
                    opacity 0.52s ease,
                    transform 0.42s cubic-bezier(0.22, 1, 0.36, 1),
                    box-shadow 0.28s ease;
            }

            .about-video-card[data-side="left"] {
                justify-self: start;
                transform-origin: right center;
                transform: translateY(26px) rotate(-10deg);
            }

            .about-video-card[data-side="right"] {
                justify-self: end;
                transform-origin: left center;
                transform: translateY(26px) rotate(10deg);
            }

            .about-video-card:hover {
                box-shadow:
                    0 30px 58px rgba(24, 43, 56, 0.2),
                    inset 0 1px 0 rgba(255, 255, 255, 0.54);
            }

            .about-video-card[data-side="left"]:hover {
                transform: rotate(0deg);
            }

            .about-video-card[data-side="right"]:hover {
                transform: rotate(0deg);
            }

            .about-video-card img {
                width: 100%;
                height: 100%;
                object-fit: cover;
                display: block;
            }

            .about-video-card::after {
                content: "";
                position: absolute;
                inset: 0;
                background: linear-gradient(180deg, rgba(13, 23, 38, 0.08) 0%, rgba(13, 23, 38, 0.24) 100%);
            }

            .about-play {
                position: absolute;
                top: 50%;
                left: 50%;
                z-index: 1;
                width: 0;
                height: 0;
                border-top: 18px solid transparent;
                border-bottom: 18px solid transparent;
                border-left: 28px solid rgba(255, 255, 255, 0.96);
                transform: translate(-50%, -50%);
                filter: drop-shadow(0 8px 18px rgba(6, 28, 39, 0.32));
                transition: transform 0.25s ease, filter 0.25s ease;
            }

            .about-video-card:hover .about-play {
                border-left-color: rgba(255, 47, 47, 0.98);
                transform: translate(-50%, -50%) scale(1.06);
                filter: drop-shadow(0 12px 24px rgba(6, 28, 39, 0.36));
            }

            .about-section.is-visible .about-shell {
                animation: navShellSpread 0.9s cubic-bezier(0.16, 1, 0.3, 1) both;
            }

            .about-section.is-visible .about-copy {
                animation: navDropIn 0.62s ease 120ms both;
            }

            .about-section.is-visible .about-video-card[data-side="left"] {
                opacity: 1;
                transform: rotate(-10deg);
                transition-delay: 180ms;
            }

            .about-section.is-visible .about-video-card[data-side="right"] {
                opacity: 1;
                transform: rotate(10deg);
                transition-delay: 220ms;
            }

            .about-section.is-visible .about-video-card[data-side="left"]:hover {
                transform: rotate(0deg);
                transition-delay: 0s;
            }

            .about-section.is-visible .about-video-card[data-side="right"]:hover {
                transform: rotate(0deg);
                transition-delay: 0s;
            }

            .about-modal[hidden] {
                display: none;
            }

            .about-modal {
                position: fixed;
                inset: 0;
                z-index: 90;
                display: grid;
                place-items: center;
                padding: 24px;
                background: rgba(9, 15, 24, 0.56);
                backdrop-filter: blur(10px);
                -webkit-backdrop-filter: blur(10px);
            }

            .about-modal-dialog {
                position: relative;
                width: min(960px, 100%);
                border-radius: 28px;
                overflow: visible;
                border: 1px solid rgba(255, 255, 255, 0.2);
                background: transparent;
                box-shadow: 0 32px 90px rgba(0, 0, 0, 0.32);
                animation: navDropIn 0.28s ease both;
            }

            .about-modal-media {
                overflow: hidden;
                border-radius: 28px;
                border: 1px solid rgba(255, 255, 255, 0.2);
                background: rgba(7, 12, 20, 0.92);
            }

            .about-modal-frame {
                width: 100%;
                aspect-ratio: 16 / 9;
                border: 0;
                display: block;
            }

            .about-modal-close {
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

            .about-modal-close:hover {
                background: #ff0000;
                border-color: #ff0000;
                transform: translateY(-1px);
            }

            .about-modal-close svg {
                width: 18px;
                height: 18px;
            }

            @media (max-width: 960px) {
                .about-grid {
                    grid-template-columns: 1fr;
                }

                .about-copy {
                    order: -1;
                }

                .about-video-card {
                    max-width: 360px;
                    margin: 0 auto;
                }
            }

            @media (max-width: 768px) {
                .about-section {
                    padding: 18px;
                }

                .about-shell {
                    padding: 16px 8px;
                }

                .about-grid {
                    gap: 22px;
                }

                .about-video-card[data-side="left"],
                .about-video-card[data-side="right"] {
                    transform: translateY(22px);
                }

                .about-video-card[data-side="left"]:hover,
                .about-video-card[data-side="right"]:hover {
                    transform: none;
                }

                .about-section.is-visible .about-video-card[data-side="left"],
                .about-section.is-visible .about-video-card[data-side="right"] {
                    transform: none;
                }

                .about-section.is-visible .about-video-card[data-side="left"]:hover,
                .about-section.is-visible .about-video-card[data-side="right"]:hover {
                    transform: none;
                }

                .about-title {
                    font-size: var(--section-title-size-mobile);
                }
            }
        </style>
    @endpush
@endonce

@php
    $leftThumbnail = $aboutSection->leftThumbnailUrl();
    $rightThumbnail = $aboutSection->rightThumbnailUrl();
    $leftEmbed = $aboutSection->leftVideoEmbedUrl();
    $rightEmbed = $aboutSection->rightVideoEmbedUrl();
@endphp

<section class="about-section" id="about" data-nav-section="about">
    <div class="about-shell">
        <div class="about-grid">
            @if ($leftThumbnail && $leftEmbed)
                <button class="about-video-card" type="button" data-side="left" data-video-embed="{{ $leftEmbed }}" aria-label="Play left about video">
                    <img src="{{ $leftThumbnail }}" alt="About section left video thumbnail" loading="lazy" decoding="async">
                    <span class="about-play" aria-hidden="true"></span>
                </button>
            @else
                <div></div>
            @endif

            <div class="about-copy">
                <h2 class="about-title">{{ $aboutSection->title }}</h2>
                @if (filled($aboutSection->subtitle))
                    <p class="about-subtitle">{{ $aboutSection->subtitle }}</p>
                @endif
                <p class="about-description">{{ $aboutSection->description }}</p>
            </div>

            @if ($rightThumbnail && $rightEmbed)
                <button class="about-video-card" type="button" data-side="right" data-video-embed="{{ $rightEmbed }}" aria-label="Play right about video">
                    <img src="{{ $rightThumbnail }}" alt="About section right video thumbnail" loading="lazy" decoding="async">
                    <span class="about-play" aria-hidden="true"></span>
                </button>
            @else
                <div></div>
            @endif
        </div>
    </div>
</section>

<div class="about-modal" id="about-video-modal" hidden>
    <div class="about-modal-dialog">
        <button class="about-modal-close" type="button" data-close-about-modal aria-label="Close video">
            <svg viewBox="0 0 24 24" fill="none" aria-hidden="true">
                <path d="M6 6L18 18M18 6L6 18" stroke="currentColor" stroke-width="2.4" stroke-linecap="round" />
            </svg>
        </button>
        <div class="about-modal-media">
            <iframe
                class="about-modal-frame"
                id="about-video-frame"
                src=""
                allow="autoplay; encrypted-media; picture-in-picture"
                allowfullscreen
                title="About section video"
            ></iframe>
        </div>
    </div>
</div>

@once
    @push('scripts')
        <script>
            document.addEventListener('DOMContentLoaded', () => {
                const modal = document.getElementById('about-video-modal');
                const frame = document.getElementById('about-video-frame');

                if (!modal || !frame) {
                    return;
                }

                const closeModal = () => {
                    modal.hidden = true;
                    frame.src = '';
                    document.body.style.overflow = '';
                };

                document.querySelectorAll('[data-video-embed]').forEach((button) => {
                    button.addEventListener('click', () => {
                        frame.src = button.dataset.videoEmbed;
                        modal.hidden = false;
                        document.body.style.overflow = 'hidden';
                    });
                });

                modal.addEventListener('click', (event) => {
                    if (event.target === modal || event.target.closest('[data-close-about-modal]')) {
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
