@php
    use App\Models\FooterSetting;

    $defaultLocationTitle = 'Visit Our Location';
    $defaultLocationSubtitle = 'Open our Google Maps location to plan your arrival and explore the surrounding destination.';
    $defaultLocationMapUrl = FooterSetting::DEFAULT_LOCATION_PLACE_URL;
    $locationTitle = filled($footerSetting?->location_title) ? $footerSetting->location_title : $defaultLocationTitle;
    $locationSubtitle = filled($footerSetting?->location_subtitle) ? $footerSetting->location_subtitle : $defaultLocationSubtitle;
    $locationMapUrl = filled($footerSetting?->location_map_url) ? $footerSetting->location_map_url : $defaultLocationMapUrl;
    $locationEmbedUrl = $footerSetting?->locationEmbedUrl() ?: FooterSetting::DEFAULT_LOCATION_EMBED_URL;
    $locationPlaceName = $footerSetting?->locationPlaceName() ?: 'King lotus international Ltd.';
@endphp

@once
    @push('styles')
        <style>
            .location-section {
                position: relative;
                padding: 18px 28px 0;
                scroll-margin-top: 148px;
                background: var(--section-surface);
            }

            .location-shell {
                position: relative;
                width: 100%;
                max-width: 1240px;
                margin: 0 auto;
                opacity: 0;
                transform: translateY(34px) scale(0.985);
                transition:
                    opacity 0.8s cubic-bezier(0.16, 1, 0.3, 1),
                    transform 0.8s cubic-bezier(0.16, 1, 0.3, 1);
            }

            .location-section.is-visible .location-shell,
            .location-section.is-visible .location-copy,
            .location-section.is-visible .location-map-card {
                opacity: 1;
                transform: none;
            }

            .location-section.is-visible .location-copy {
                transition-delay: 0.12s;
            }

            .location-section.is-visible .location-map-card {
                transition-delay: 0.24s;
            }

            .location-shell::before,
            .location-shell::after {
                display: none;
            }

            .location-grid {
                position: relative;
                z-index: 0;
                display: grid;
                grid-template-columns: minmax(0, 1.15fr) minmax(300px, 0.85fr);
                gap: 28px;
                align-items: center;
                padding: 34px 38px;
            }

            .location-copy,
            .location-map-card {
                opacity: 0;
                transform: translateY(24px);
                transition:
                    opacity 0.8s cubic-bezier(0.16, 1, 0.3, 1),
                    transform 0.8s cubic-bezier(0.16, 1, 0.3, 1);
                will-change: opacity, transform;
            }

            .location-copy {
                max-width: 720px;
            }

            .location-eyebrow {
                margin: 0 0 14px;
                font-size: 0.9rem;
                font-weight: 700;
                letter-spacing: 0.16em;
                text-transform: uppercase;
                color: rgba(16, 33, 44, 0.62);
            }

            .location-heading {
                margin: 0;
                font-family: var(--font-primary);
                font-size: var(--section-title-size);
                font-weight: 400;
                line-height: 0.98;
                color: #101214;
            }

            .location-subtitle {
                margin: 16px 0 0;
                max-width: 660px;
                font-size: 1rem;
                line-height: 1.75;
                color: rgba(16, 33, 44, 0.74);
            }

            .location-actions {
                display: flex;
                align-items: center;
                gap: 14px;
                flex-wrap: wrap;
                margin-top: 24px;
            }

            .location-button {
                display: inline-flex;
                align-items: center;
                justify-content: center;
                gap: 10px;
                min-height: 52px;
                padding: 0 22px;
                border-radius: 999px;
                border: 1px solid rgba(18, 25, 38, 0.72);
                background: rgba(255, 255, 255, 0.9);
                color: #121926;
                font-family: var(--font-secondary);
                font-size: 0.96rem;
                font-weight: 600;
                box-shadow: 0 12px 24px rgba(16, 33, 44, 0.08);
                transition: transform 0.2s ease, box-shadow 0.2s ease, background-color 0.2s ease, border-color 0.2s ease, color 0.2s ease;
            }

            .location-button:hover {
                transform: translateY(-2px);
                border-color: #0c505d;
                background: #0c505d;
                color: #ffffff;
                box-shadow: 0 22px 36px rgba(12, 80, 93, 0.22);
            }

            .location-pill {
                display: inline-flex;
                align-items: center;
                gap: 8px;
                min-height: 44px;
                padding: 0 16px;
                border: 1px solid rgba(172, 189, 202, 0.72);
                border-radius: 999px;
                background: rgba(255, 255, 255, 0.56);
                color: rgba(16, 33, 44, 0.7);
                font-size: 0.9rem;
            }

            .location-map-card {
                position: relative;
                z-index: 0;
                overflow: hidden;
                min-height: 340px;
                padding: 0;
                border-radius: 28px;
                border: 1px solid rgba(169, 183, 194, 0.48);
                background: rgba(255, 255, 255, 0.7);
                box-shadow: 0 20px 36px rgba(24, 43, 56, 0.12);
            }

            .location-map-frame {
                display: block;
                position: relative;
                z-index: 0;
                width: 100%;
                height: 100%;
                min-height: 340px;
                border: 0;
                background: #dbe6ee;
            }

            .location-map-label {
                position: absolute;
                top: 16px;
                left: 50%;
                z-index: 1;
                display: inline-flex;
                align-items: center;
                justify-content: center;
                gap: 10px;
                max-width: calc(100% - 32px);
                min-height: 42px;
                padding: 10px 14px;
                border: 1px solid rgba(255, 255, 255, 0.28);
                border-radius: 18px;
                background:
                    linear-gradient(180deg, rgba(255, 255, 255, 0.22) 0%, rgba(255, 255, 255, 0.08) 100%);
                backdrop-filter: blur(18px) saturate(145%);
                -webkit-backdrop-filter: blur(18px) saturate(145%);
                box-shadow:
                    inset 0 1px 0 rgba(255, 255, 255, 0.34),
                    0 10px 22px rgba(16, 33, 44, 0.16);
                color: #121926;
                transform: translateX(-50%);
            }

            .location-map-label svg {
                flex: 0 0 auto;
                color: #0c505d;
            }

            .location-map-label span {
                display: block;
                overflow: hidden;
                font-size: 0.92rem;
                font-weight: 700;
                line-height: 1.3;
                text-align: center;
                text-overflow: ellipsis;
                white-space: nowrap;
            }

            @media (max-width: 980px) {
                .location-grid {
                    grid-template-columns: 1fr;
                }
            }

            @media (max-width: 768px) {
                .location-section {
                    padding: 14px 18px 0;
                    scroll-margin-top: 138px;
                }

                .location-grid {
                    gap: 22px;
                    padding: 24px 18px;
                }

                .location-copy {
                    max-width: none;
                    text-align: center;
                }

                .location-eyebrow,
                .location-heading {
                    text-align: center;
                }

                .location-heading {
                    font-size: var(--section-title-size-mobile);
                }

                .location-subtitle {
                    font-size: 0.95rem;
                    text-align: justify;
                    text-justify: inter-word;
                }

                .location-map-card {
                    border-radius: 24px;
                    min-height: 300px;
                }

                .location-map-frame {
                    min-height: 300px;
                }

                .location-map-label {
                    top: 12px;
                    left: 50%;
                    max-width: calc(100% - 24px);
                    min-height: 38px;
                    padding: 8px 12px;
                    border-radius: 16px;
                }

                .location-map-label span {
                    font-size: 0.86rem;
                }

                .location-button {
                    width: 100%;
                    justify-content: center;
                }
            }
        </style>
    @endpush
@endonce

<section class="location-section" id="location">
    <div class="location-shell">
        <div class="location-grid">
            <div class="location-copy">
                <p class="location-eyebrow">Location</p>
                <h2 class="location-heading">{{ $locationTitle }}</h2>
                <p class="location-subtitle">{{ $locationSubtitle }}</p>

                <div class="location-actions">
                    <a class="location-button" href="{{ $locationMapUrl }}" target="_blank" rel="noopener noreferrer">
                        <svg width="18" height="18" viewBox="0 0 24 24" fill="none" aria-hidden="true">
                            <path d="M12 21C15.3137 17.1 18 14.4183 18 10.75C18 7.02208 15.3137 4 12 4C8.68629 4 6 7.02208 6 10.75C6 14.4183 8.68629 17.1 12 21Z" stroke="currentColor" stroke-width="1.8" stroke-linejoin="round"></path>
                            <circle cx="12" cy="10.75" r="2.45" stroke="currentColor" stroke-width="1.8"></circle>
                        </svg>
                        <span>Open Google Maps</span>
                    </a>
                </div>
            </div>

            <div class="location-map-card">
                <div class="location-map-label">
                    <svg width="18" height="18" viewBox="0 0 24 24" fill="none" aria-hidden="true">
                        <path d="M12 21C15.3137 17.1 18 14.4183 18 10.75C18 7.02208 15.3137 4 12 4C8.68629 4 6 7.02208 6 10.75C6 14.4183 8.68629 17.1 12 21Z" stroke="currentColor" stroke-width="1.8" stroke-linejoin="round"></path>
                        <circle cx="12" cy="10.75" r="2.45" stroke="currentColor" stroke-width="1.8"></circle>
                    </svg>
                    <span>{{ $locationPlaceName }}</span>
                </div>

                <iframe
                    class="location-map-frame"
                    src="{{ $locationEmbedUrl }}"
                    loading="lazy"
                    referrerpolicy="no-referrer-when-downgrade"
                    allowfullscreen
                    title="Google Map location preview"
                ></iframe>
            </div>
        </div>
    </div>
</section>
