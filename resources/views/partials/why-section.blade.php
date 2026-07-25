@once
    @push('styles')
        <style>
            .why-section {
                position: relative;
                z-index: 0;
                overflow: clip;
                scroll-margin-top: 168px;
                padding: 20px 28px 28px;
                background: var(--section-surface);
            }

            .why-shell {
                width: 100%;
                max-width: 1240px;
                margin: 0 auto;
                padding: 44px 24px 22px;
                opacity: 0;
                transform: translateY(22px);
            }

            .why-grid {
                display: grid;
                grid-template-columns: minmax(0, 1fr) minmax(320px, 560px);
                align-items: stretch;
                gap: 42px;
            }

            .why-copy {
                opacity: 0;
                transform: translateX(-18px);
            }

            .why-title {
                margin: 0 0 18px;
                font-family: var(--font-primary);
                font-size: var(--section-title-size);
                font-weight: 400;
                line-height: 1.02;
                color: #121926;
            }

            .why-description {
                margin: 0 0 22px;
                font-family: var(--font-secondary);
                font-size: clamp(1rem, 1.5vw, 1.12rem);
                line-height: 1.78;
                color: rgba(17, 25, 38, 0.76);
                text-align: justify;
            }

            .why-points {
                display: grid;
                gap: 14px;
                margin: 0;
                padding: 0;
                list-style: none;
            }

            .why-point {
                display: grid;
                grid-template-columns: 18px minmax(0, 1fr);
                align-items: start;
                gap: 12px;
                font-family: var(--font-secondary);
                font-size: clamp(0.98rem, 1.45vw, 1.08rem);
                line-height: 1.6;
                color: #1a2432;
            }

            .why-point::before {
                content: "✦";
                font-size: 1rem;
                line-height: 1.2;
                color: #0c505d;
            }

            .why-actions {
                margin-top: 26px;
            }

            .why-button {
                display: inline-flex;
                align-items: center;
                justify-content: center;
                min-height: 50px;
                padding: 0 22px;
                border-radius: 16px;
                background: #16202d;
                color: #ffffff;
                font-weight: 600;
                transition: transform 0.22s ease, background-color 0.22s ease, box-shadow 0.22s ease;
                box-shadow: 0 18px 36px rgba(22, 32, 45, 0.14);
            }

            .why-button:hover {
                background: #0c505d;
                transform: translateY(-1px);
                box-shadow: 0 22px 40px rgba(12, 80, 93, 0.2);
            }

            .why-media-wrap {
                display: flex;
                align-items: center;
                padding-top: 22px;
                opacity: 0;
                transform: translateX(18px);
            }

            .why-media-card {
                position: relative;
                display: block;
                width: 100%;
                aspect-ratio: 16 / 10;
                overflow: hidden;
                border: 0;
                border-radius: 30px;
                padding: 0;
                background: linear-gradient(180deg, rgba(255, 255, 255, 0.18) 0%, rgba(255, 255, 255, 0.04) 100%);
                box-shadow:
                    0 26px 54px rgba(24, 43, 56, 0.16),
                    inset 0 1px 0 rgba(255, 255, 255, 0.46);
                cursor: pointer;
                transition: transform 0.32s ease, box-shadow 0.28s ease;
            }

            .why-media-card:hover {
                transform: scale(1.03);
                box-shadow:
                    0 32px 62px rgba(24, 43, 56, 0.2),
                    inset 0 1px 0 rgba(255, 255, 255, 0.54);
            }

            .why-media-card img {
                width: 100%;
                height: 100%;
                object-fit: cover;
                display: block;
            }

            .why-media-card::after {
                content: "";
                position: absolute;
                inset: 0;
                background: linear-gradient(180deg, rgba(8, 18, 30, 0.1) 0%, rgba(8, 18, 30, 0.28) 100%);
            }

            .why-play {
                position: absolute;
                top: 50%;
                left: 50%;
                z-index: 1;
                width: 0;
                height: 0;
                transform: translate(-50%, -50%);
            }

            .why-play::before {
                content: "";
                position: absolute;
                top: 0;
                left: 0;
                width: 0;
                height: 0;
                border-top: 22px solid transparent;
                border-bottom: 22px solid transparent;
                border-left: 34px solid #ffffff;
                transform: translate(-40%, -50%);
                filter: drop-shadow(0 10px 24px rgba(6, 28, 39, 0.32));
                transition: border-left-color 0.22s ease, transform 0.22s ease;
            }

            .why-media-card:hover .why-play::before {
                border-left-color: #ff0000;
                transform: translate(-40%, -50%) scale(1.06);
            }

            .why-section.is-visible .why-shell {
                animation: navShellSpread 0.9s cubic-bezier(0.16, 1, 0.3, 1) both;
            }

            .why-section.is-visible .why-copy {
                animation: navDropIn 0.62s ease 120ms both;
            }

            .why-section.is-visible .why-media-wrap {
                animation: navDropIn 0.62s ease 220ms both;
            }

            .why-modal[hidden] {
                display: none;
            }

            .why-modal {
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

            .why-modal-dialog {
                position: relative;
                width: min(960px, 100%);
                border-radius: 28px;
                box-shadow: 0 32px 90px rgba(0, 0, 0, 0.32);
            }

            .why-modal-media {
                overflow: hidden;
                border-radius: 28px;
                border: 1px solid rgba(255, 255, 255, 0.2);
                background: rgba(7, 12, 20, 0.92);
            }

            .why-modal-frame {
                width: 100%;
                aspect-ratio: 16 / 9;
                border: 0;
                display: block;
            }

            .why-modal-close {
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

            .why-modal-close:hover {
                background: #ff0000;
                border-color: #ff0000;
                transform: translateY(-1px);
            }

            .why-modal-close svg {
                width: 18px;
                height: 18px;
            }

            @media (max-width: 980px) {
                .why-grid {
                    grid-template-columns: 1fr;
                }
            }

            @media (max-width: 768px) {
                .why-section {
                    padding: 18px;
                }

                .why-shell {
                    padding: 20px 8px 16px;
                }

                .why-grid {
                    gap: 22px;
                }

                .why-title {
                    text-align: center;
                }

                .why-actions {
                    display: flex;
                    justify-content: center;
                }

                .why-play {
                    transform: translate(-50%, -50%);
                }

                .why-play::before {
                    border-top-width: 20px;
                    border-bottom-width: 20px;
                    border-left-width: 30px;
                }
            }
        </style>
    @endpush
@endonce

@php
    $whyPoints = $whySection->featurePoints();
    $whyThumbnail = $whySection->thumbnailUrl();
    $whyEmbed = $whySection->videoEmbedUrl();
    $whyCtaUrl = filled($whySection->cta_url) ? $whySection->cta_url : route('terms.show');
@endphp

<section class="why-section" id="why-king-lotus">
    <div class="why-shell">
        <div class="why-grid">
            <div class="why-copy">
                <h2 class="why-title">{{ $whySection->title }}</h2>
                <p class="why-description">{{ $whySection->description }}</p>

                @if ($whyPoints)
                    <ul class="why-points" aria-label="Why choose King Lotus Group">
                        @foreach ($whyPoints as $point)
                            <li class="why-point">{{ $point }}</li>
                        @endforeach
                    </ul>
                @endif

                @if (filled($whySection->cta_label))
                    <div class="why-actions">
                        <a class="why-button" href="{{ $whyCtaUrl }}">{{ $whySection->cta_label }}</a>
                    </div>
                @endif
            </div>

            @if ($whyThumbnail && $whyEmbed)
                <div class="why-media-wrap">
                    <button class="why-media-card" type="button" data-why-video-embed="{{ $whyEmbed }}" aria-label="Play why section video">
                        <img src="{{ $whyThumbnail }}" alt="Why section video thumbnail" loading="lazy" decoding="async">
                        <span class="why-play" aria-hidden="true"></span>
                    </button>
                </div>
            @endif
        </div>
    </div>
</section>

<div class="why-modal" id="why-video-modal" hidden>
    <div class="why-modal-dialog">
        <button class="why-modal-close" type="button" data-close-why-modal aria-label="Close video">
            <svg viewBox="0 0 24 24" fill="none" aria-hidden="true">
                <path d="M6 6L18 18M18 6L6 18" stroke="currentColor" stroke-width="2.4" stroke-linecap="round" />
            </svg>
        </button>
        <div class="why-modal-media">
            <iframe
                class="why-modal-frame"
                id="why-video-frame"
                src=""
                allow="autoplay; encrypted-media; picture-in-picture"
                allowfullscreen
                title="Why section video"
            ></iframe>
        </div>
    </div>
</div>

@once
    @push('scripts')
        <script>
            document.addEventListener('DOMContentLoaded', () => {
                const modal = document.getElementById('why-video-modal');
                const frame = document.getElementById('why-video-frame');

                if (!modal || !frame) {
                    return;
                }

                const closeModal = () => {
                    modal.hidden = true;
                    frame.src = '';
                    document.body.style.overflow = '';
                };

                document.querySelectorAll('[data-why-video-embed]').forEach((button) => {
                    button.addEventListener('click', () => {
                        frame.src = button.dataset.whyVideoEmbed;
                        modal.hidden = false;
                        document.body.style.overflow = 'hidden';
                    });
                });

                modal.addEventListener('click', (event) => {
                    if (event.target === modal || event.target.closest('[data-close-why-modal]')) {
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
