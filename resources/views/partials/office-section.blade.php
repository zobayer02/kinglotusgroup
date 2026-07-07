@php
    use App\Models\FooterSetting;

    $officeSectionTitle = filled($footerSetting?->office_section_title)
        ? $footerSetting->office_section_title
        : FooterSetting::DEFAULT_OFFICE_SECTION_TITLE;
    $officeSectionSubtitle = filled($footerSetting?->office_section_subtitle)
        ? $footerSetting->office_section_subtitle
        : FooterSetting::DEFAULT_OFFICE_SECTION_SUBTITLE;
    $officeCards = $footerSetting?->officeCards() ?? [];

    if (empty($officeCards)) {
        $officeCards = [[
            'name' => FooterSetting::DEFAULT_OFFICE_NAME,
            'address' => FooterSetting::DEFAULT_OFFICE_ADDRESS,
            'map_url' => FooterSetting::DEFAULT_LOCATION_PLACE_URL,
            'phone' => '',
            'email' => '',
            'phone_href' => null,
            'email_href' => null,
        ]];
    }
@endphp

@once
    @push('styles')
        <style>
            .office-section {
                position: relative;
                padding: 14px 28px 0;
                background: var(--section-surface);
            }

            .office-shell {
                width: 100%;
                max-width: 1240px;
                margin: 0 auto;
                opacity: 0;
                transform: translateY(34px) scale(0.985);
                transition:
                    opacity 0.8s cubic-bezier(0.16, 1, 0.3, 1),
                    transform 0.8s cubic-bezier(0.16, 1, 0.3, 1);
            }

            .office-section.is-visible .office-shell,
            .office-section.is-visible .office-header,
            .office-section.is-visible .office-card {
                opacity: 1;
                transform: none;
            }

            .office-section.is-visible .office-header {
                transition-delay: 0.1s;
            }

            .office-section.is-visible .office-card:nth-child(1) {
                transition-delay: 0.2s;
            }

            .office-section.is-visible .office-card:nth-child(2) {
                transition-delay: 0.32s;
            }

            .office-section.is-visible .office-card:nth-child(n + 3) {
                transition-delay: 0.44s;
            }

            .office-frame {
                display: grid;
                gap: 26px;
                padding: 34px 38px;
                border-radius: 34px;
                border: 1px solid rgba(190, 205, 214, 0.72);
                background:
                    linear-gradient(180deg, rgba(255, 255, 255, 0.2) 0%, rgba(255, 255, 255, 0.08) 100%),
                    linear-gradient(180deg, rgba(233, 241, 247, 0.96) 0%, rgba(222, 234, 242, 0.94) 100%);
            }

            .office-header,
            .office-card {
                opacity: 0;
                transform: translateY(24px);
                transition:
                    opacity 0.8s cubic-bezier(0.16, 1, 0.3, 1),
                    transform 0.8s cubic-bezier(0.16, 1, 0.3, 1);
                will-change: opacity, transform;
            }

            .office-header {
                width: 100%;
                max-width: none;
            }

            .office-kicker {
                margin: 0 0 14px;
                color: rgba(16, 33, 44, 0.62);
                font-size: 0.92rem;
                font-weight: 700;
                letter-spacing: 0.16em;
                text-transform: uppercase;
            }

            .office-title {
                margin: 0;
                font-family: var(--font-primary);
                font-size: var(--section-title-size);
                font-weight: 400;
                line-height: 0.98;
                color: #101214;
            }

            .office-grid {
                display: grid;
                grid-template-columns: repeat(2, minmax(0, 1fr));
                gap: 20px;
            }

            .office-grid--single {
                grid-template-columns: minmax(0, 1fr);
            }

            .office-card {
                display: grid;
                justify-items: center;
                gap: 12px;
                padding: 22px 22px;
                border-radius: 28px;
                border: 1px solid rgba(214, 198, 156, 0.24);
                background:
                    linear-gradient(135deg, rgba(255, 255, 255, 0.08) 0%, rgba(255, 255, 255, 0) 38%),
                    radial-gradient(circle at top center, rgba(255, 241, 213, 0.14) 0%, rgba(255, 241, 213, 0) 30%),
                    radial-gradient(circle at bottom right, rgba(19, 52, 44, 0.42) 0%, rgba(19, 52, 44, 0) 34%),
                    linear-gradient(180deg, #385247 0%, #30493f 42%, #243a31 100%);
                text-align: center;
                box-shadow:
                    0 24px 42px rgba(18, 34, 29, 0.18),
                    inset 0 1px 0 rgba(255, 255, 255, 0.08);
            }

            .office-grid--single .office-card {
                width: min(100%, 860px);
                justify-self: center;
            }

            .office-card-icon {
                display: inline-flex;
                align-items: center;
                justify-content: center;
                width: 58px;
                height: 58px;
                border-radius: 18px;
                background: linear-gradient(180deg, rgba(255, 248, 230, 0.18) 0%, rgba(255, 248, 230, 0.08) 100%);
                color: #f0dfb3;
                box-shadow:
                    inset 0 1px 0 rgba(255, 255, 255, 0.12),
                    0 14px 26px rgba(15, 32, 27, 0.12);
            }

            .office-card-title {
                margin: 0;
                font-family: var(--font-primary);
                font-size: clamp(1.55rem, 2.35vw, 2.4rem);
                font-weight: 400;
                line-height: 1.02;
                color: #f8f2de;
            }

            .office-card-address {
                margin: 0;
                max-width: 560px;
                font-size: 0.95rem;
                line-height: 1.65;
                color: rgba(239, 231, 208, 0.82);
                white-space: pre-line;
            }

            .office-card-contact-list {
                display: grid;
                gap: 8px;
                width: 100%;
                max-width: 560px;
                margin: 0;
            }

            .office-card-contact {
                display: inline-flex;
                align-items: center;
                justify-content: center;
                gap: 10px;
                width: fit-content;
                max-width: 100%;
                margin: 0 auto;
                padding: 0;
                color: #f8f2de;
                font-family: var(--font-primary);
                font-size: clamp(0.96rem, 1.2vw, 1.16rem);
                font-weight: 600;
                line-height: 1.3;
                letter-spacing: 0.01em;
                transition:
                    transform 0.2s ease,
                    color 0.2s ease,
                    opacity 0.2s ease;
            }

            .office-card-contact:hover {
                transform: translateY(-1px);
                color: #fff7e5;
                opacity: 0.92;
            }

            .office-card-contact span:last-child {
                min-width: 0;
                overflow-wrap: anywhere;
            }

            .office-card-action {
                display: inline-flex;
                align-items: center;
                justify-content: center;
                gap: 10px;
                min-height: 48px;
                padding: 0 22px;
                border: 1px solid rgba(18, 25, 38, 0.72);
                border-radius: 999px;
                background: rgba(255, 255, 255, 0.9);
                color: #121926;
                font-family: var(--font-secondary);
                font-size: 0.94rem;
                font-weight: 600;
                box-shadow: 0 12px 24px rgba(16, 33, 44, 0.08);
                transition:
                    transform 0.2s ease,
                    box-shadow 0.2s ease,
                    background-color 0.2s ease,
                    border-color 0.2s ease,
                    color 0.2s ease;
            }

            .office-card-action:hover {
                transform: translateY(-2px);
                border-color: #d6bf87;
                background: #d6bf87;
                color: #233930;
                box-shadow: 0 20px 34px rgba(28, 45, 38, 0.2);
            }

            @media (max-width: 768px) {
                .office-section {
                    padding: 10px 18px 0;
                }

                .office-frame {
                    gap: 22px;
                    padding: 24px 18px;
                    border-radius: 28px;
                }

                .office-header,
                .office-card {
                    text-align: center;
                }

                .office-kicker,
                .office-title {
                    text-align: center;
                }

                .office-title {
                    font-size: var(--section-title-size-mobile);
                }

                .office-card {
                    padding: 20px 16px;
                    border-radius: 24px;
                }

                .office-card-address {
                    font-size: 0.92rem;
                    text-align: center;
                }

                .office-card-contact {
                    font-size: 0.92rem;
                }

                .office-card-action {
                    width: 100%;
                }
            }

            @media (max-width: 900px) {
                .office-grid {
                    grid-template-columns: 1fr;
                }
            }
        </style>
    @endpush
@endonce

<section class="office-section" id="office" data-nav-section="contact">
    <div class="office-shell">
        <div class="office-frame">
            <div class="office-header">
                <p class="office-kicker">{{ $officeSectionSubtitle }}</p>
                <h2 class="office-title">{{ $officeSectionTitle }}</h2>
            </div>

            <div class="office-grid {{ count($officeCards) === 1 ? 'office-grid--single' : '' }}">
                @foreach ($officeCards as $office)
                    <article class="office-card">
                        <span class="office-card-icon" aria-hidden="true">
                            <svg width="30" height="30" viewBox="0 0 24 24" fill="none">
                                <path d="M12 21C15.3137 17.1 18 14.4183 18 10.75C18 7.02208 15.3137 4 12 4C8.68629 4 6 7.02208 6 10.75C6 14.4183 8.68629 17.1 12 21Z" stroke="currentColor" stroke-width="1.8" stroke-linejoin="round"></path>
                                <circle cx="12" cy="10.75" r="2.45" stroke="currentColor" stroke-width="1.8"></circle>
                            </svg>
                        </span>

                        <h3 class="office-card-title">{{ $office['name'] ?: FooterSetting::DEFAULT_OFFICE_NAME }}</h3>
                        <p class="office-card-address">{{ $office['address'] ?: FooterSetting::DEFAULT_OFFICE_ADDRESS }}</p>

                        @if (filled($office['phone'] ?? null) || filled($office['email'] ?? null))
                            <div class="office-card-contact-list">
                                @if (filled($office['phone_href'] ?? null))
                                    <a class="office-card-contact" href="{{ $office['phone_href'] }}">
                                        <svg width="18" height="18" viewBox="0 0 24 24" fill="none" aria-hidden="true">
                                            <path d="M6.7 4.5H9.1C9.55 4.5 9.94 4.81 10.04 5.24L10.63 7.86C10.71 8.24 10.59 8.63 10.31 8.89L8.83 10.29C9.8 12.23 11.37 13.79 13.31 14.77L14.71 13.29C14.98 13.01 15.37 12.89 15.74 12.97L18.36 13.56C18.79 13.66 19.1 14.05 19.1 14.5V16.9C19.1 17.45 18.65 17.9 18.1 17.9C10.54 17.9 4.4 11.76 4.4 4.2C4.4 3.65 4.85 3.2 5.4 3.2H6.7C6.15 3.2 5.7 3.65 5.7 4.2C5.7 11.04 11.26 16.6 18.1 16.6V14.83L15.45 14.23L13.57 16.21L13.13 16.01C10.61 14.88 8.62 12.89 7.49 10.37L7.29 9.93L9.27 8.05L8.67 5.4H6.7V4.5Z" fill="currentColor"></path>
                                        </svg>
                                        <span>{{ $office['phone'] }}</span>
                                    </a>
                                @endif

                                @if (filled($office['email_href'] ?? null))
                                    <a class="office-card-contact" href="{{ $office['email_href'] }}">
                                        <svg width="18" height="18" viewBox="0 0 24 24" fill="none" aria-hidden="true">
                                            <path d="M4.75 6.5H19.25C19.66 6.5 20 6.84 20 7.25V16.75C20 17.16 19.66 17.5 19.25 17.5H4.75C4.34 17.5 4 17.16 4 16.75V7.25C4 6.84 4.34 6.5 4.75 6.5ZM5.58 8L12 12.83L18.42 8H5.58ZM18.5 9.06L12.45 13.61C12.18 13.81 11.82 13.81 11.55 13.61L5.5 9.06V16H18.5V9.06Z" fill="currentColor"></path>
                                        </svg>
                                        <span>{{ $office['email'] }}</span>
                                    </a>
                                @endif
                            </div>
                        @endif

                        <a class="office-card-action" href="{{ $office['map_url'] ?: FooterSetting::DEFAULT_LOCATION_PLACE_URL }}" target="_blank" rel="noopener noreferrer">
                            <svg width="18" height="18" viewBox="0 0 24 24" fill="none" aria-hidden="true">
                                <path d="M12 21C15.3137 17.1 18 14.4183 18 10.75C18 7.02208 15.3137 4 12 4C8.68629 4 6 7.02208 6 10.75C6 14.4183 8.68629 17.1 12 21Z" stroke="currentColor" stroke-width="1.8" stroke-linejoin="round"></path>
                                <circle cx="12" cy="10.75" r="2.45" stroke="currentColor" stroke-width="1.8"></circle>
                            </svg>
                            <span>Open Office Location</span>
                        </a>
                    </article>
                @endforeach
            </div>
        </div>
    </div>
</section>
