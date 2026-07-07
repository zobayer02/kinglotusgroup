@php
    use App\Models\GallerySection;

    $galleryTitle = filled($gallerySection?->section_title)
        ? $gallerySection->section_title
        : GallerySection::DEFAULT_SECTION_TITLE;
    $gallerySubtitle = filled($gallerySection?->section_subtitle)
        ? $gallerySection->section_subtitle
        : GallerySection::DEFAULT_SECTION_SUBTITLE;
    $viewAllLabel = filled($gallerySection?->view_all_label)
        ? $gallerySection->view_all_label
        : GallerySection::DEFAULT_VIEW_ALL_LABEL;
    $featuredImages = $gallerySection?->featuredImages() ?? [];
@endphp

@if (! empty($featuredImages))
    @once
        @push('styles')
            <style>
                .gallery-section {
                    padding: 10px 28px 18px;
                    background: var(--section-surface);
                }

                .gallery-shell {
                    width: 100%;
                    max-width: 1020px;
                    margin: 0 auto;
                    display: grid;
                    gap: 28px;
                    opacity: 0;
                    transform: translateY(26px);
                    transition:
                        opacity 0.82s cubic-bezier(0.16, 1, 0.3, 1),
                        transform 0.82s cubic-bezier(0.16, 1, 0.3, 1);
                }

                .gallery-section.is-visible .gallery-shell {
                    opacity: 1;
                    transform: none;
                }

                .gallery-head {
                    display: grid;
                    justify-items: center;
                    gap: 10px;
                    text-align: center;
                }

                .gallery-kicker {
                    margin: 0;
                    color: rgba(16, 33, 44, 0.62);
                    font-size: 0.9rem;
                    font-weight: 700;
                    letter-spacing: 0.16em;
                    text-transform: uppercase;
                }

                .gallery-title {
                    margin: 0;
                    font-family: var(--font-primary);
                    font-size: var(--section-title-size);
                    font-weight: 400;
                    line-height: 0.98;
                    color: #101214;
                }

                .gallery-mosaic {
                    display: grid;
                    grid-template-columns: 1.15fr 1.2fr 1.35fr 1.2fr 1.15fr;
                    grid-template-areas:
                        ". slot2 slot4 slot5 ."
                        "slot1 slot3 slot4 slot6 slot7"
                        "slot1 slot3 slot4 slot6 slot7";
                    gap: 10px;
                    align-items: stretch;
                }

                .gallery-card {
                    --gallery-card-start: translateY(34px) scale(0.985);
                    position: relative;
                    overflow: hidden;
                    min-height: 148px;
                    border-radius: 20px;
                    opacity: 0;
                    transform: var(--gallery-card-start);
                    transition:
                        opacity 0.92s cubic-bezier(0.16, 1, 0.3, 1),
                        transform 0.92s cubic-bezier(0.16, 1, 0.3, 1),
                        box-shadow 0.22s ease;
                    box-shadow: 0 18px 36px rgba(18, 33, 44, 0.12);
                    will-change: opacity, transform;
                }

                .gallery-card img {
                    width: 100%;
                    height: 100%;
                    object-fit: cover;
                    display: block;
                    transition: transform 0.72s cubic-bezier(0.16, 1, 0.3, 1);
                }

                .gallery-card:hover img {
                    transform: scale(1.06);
                }

                .gallery-card--slot-1 { --gallery-card-start: translateX(-96px) scale(0.96); grid-area: slot1; min-height: 156px; }
                .gallery-card--slot-2 { --gallery-card-start: translateY(-82px) scale(0.96); grid-area: slot2; min-height: 96px; }
                .gallery-card--slot-3 { --gallery-card-start: translateY(82px) scale(0.96); grid-area: slot3; min-height: 96px; }
                .gallery-card--slot-4 { --gallery-card-start: translateY(-98px) scale(0.96); grid-area: slot4; min-height: 264px; }
                .gallery-card--slot-5 { --gallery-card-start: translateY(-82px) scale(0.96); grid-area: slot5; min-height: 96px; }
                .gallery-card--slot-6 { --gallery-card-start: translateY(82px) scale(0.96); grid-area: slot6; min-height: 96px; }
                .gallery-card--slot-7 { --gallery-card-start: translateX(96px) scale(0.96); grid-area: slot7; min-height: 156px; }

                @media (min-width: 981px) {
                    .gallery-card--slot-1,
                    .gallery-card--slot-7 {
                        top: -92px;
                    }
                }

                .gallery-section.is-visible .gallery-card {
                    opacity: 1;
                    transform: none;
                }

                .gallery-section.is-visible .gallery-card--slot-1 { transition-delay: 0.08s; }
                .gallery-section.is-visible .gallery-card--slot-2 { transition-delay: 0.14s; }
                .gallery-section.is-visible .gallery-card--slot-3 { transition-delay: 0.2s; }
                .gallery-section.is-visible .gallery-card--slot-4 { transition-delay: 0.26s; }
                .gallery-section.is-visible .gallery-card--slot-5 { transition-delay: 0.32s; }
                .gallery-section.is-visible .gallery-card--slot-6 { transition-delay: 0.38s; }
                .gallery-section.is-visible .gallery-card--slot-7 { transition-delay: 0.44s; }

                .gallery-actions {
                    display: flex;
                    justify-content: center;
                }

                .gallery-view-all {
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

                .gallery-view-all:hover {
                    transform: translateY(-2px);
                    border-color: #0c505d;
                    background: #0c505d;
                    color: #ffffff;
                    box-shadow: none;
                }

                @media (max-width: 980px) {
                    .gallery-mosaic {
                        grid-template-columns: repeat(2, minmax(0, 1fr));
                        grid-template-areas:
                            "slot1 slot2"
                            "slot3 slot4"
                            "slot5 slot6"
                            "slot7 slot7";
                    }

                    .gallery-card--slot-4 {
                        min-height: 240px;
                    }
                }

                @media (max-width: 768px) {
                    .gallery-section {
                        padding: 6px 18px 14px;
                    }

                    .gallery-shell {
                        gap: 20px;
                    }

                    .gallery-title {
                        font-size: var(--section-title-size-mobile);
                    }

                    .gallery-mosaic {
                        gap: 12px;
                    }

                    .gallery-card {
                        min-height: 148px;
                        border-radius: 20px;
                    }

                    .gallery-card--slot-4 {
                        min-height: 220px;
                    }

                    .gallery-view-all {
                        width: 100%;
                    }
                }
            </style>
        @endpush
    @endonce

    <section class="gallery-section" id="gallery" data-nav-section="gallery">
        <div class="gallery-shell">
            <div class="gallery-head">
                <p class="gallery-kicker">{{ $gallerySubtitle }}</p>
                <h2 class="gallery-title">{{ $galleryTitle }}</h2>
            </div>

            <div class="gallery-mosaic">
                @foreach ($featuredImages as $index => $image)
                    <article class="gallery-card gallery-card--slot-{{ $index + 1 }}">
                        <img src="{{ $image['image_url'] }}" alt="Featured gallery image {{ $index + 1 }}" loading="lazy" decoding="async">
                    </article>
                @endforeach
            </div>

            <div class="gallery-actions">
                <a class="gallery-view-all" href="{{ route('gallery.index') }}">{{ $viewAllLabel }}</a>
            </div>
        </div>
    </section>
@endif
