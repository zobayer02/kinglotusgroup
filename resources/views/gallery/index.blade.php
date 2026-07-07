@extends('layouts.app')

@section('title', (($gallerySection?->page_title) ?: \App\Models\GallerySection::DEFAULT_PAGE_TITLE) . ' | King Lotus International')

@push('styles')
    <style>
        @include('partials.chrome-styles')

        .gallery-page {
            min-height: 100vh;
            padding: 28px;
            background: var(--section-surface);
        }

        .gallery-page-shell {
            width: 100%;
            max-width: 1240px;
            margin: 0 auto;
            display: grid;
            gap: 38px;
        }

        .gallery-page-header {
            padding-top: 118px;
            display: grid;
            gap: 18px;
            justify-items: center;
            text-align: center;
        }

        .gallery-page-kicker {
            margin: 0;
            color: rgba(16, 33, 44, 0.62);
            font-size: 0.92rem;
            font-weight: 700;
            letter-spacing: 0.16em;
            text-transform: uppercase;
        }

        .gallery-page-title {
            margin: 0;
            max-width: 980px;
            font-family: var(--font-primary);
            font-size: var(--section-title-size);
            font-weight: 400;
            line-height: 0.94;
            color: #101214;
        }

        .gallery-page-subtitle {
            margin: 0;
            max-width: 760px;
            font-family: var(--font-secondary);
            font-size: clamp(1rem, 1.5vw, 1.12rem);
            line-height: 1.8;
            text-align: center;
            color: rgba(16, 33, 44, 0.76);
        }

        .gallery-album-cards {
            display: grid;
            grid-template-columns: repeat(3, minmax(0, 1fr));
            gap: 18px;
        }

        .gallery-album-card {
            --gallery-album-card-start: translateY(54px) scale(0.97);
            position: relative;
            overflow: hidden;
            min-height: 300px;
            border: 1px solid rgba(178, 193, 204, 0.58);
            border-radius: 26px;
            padding: 0;
            background: rgba(255, 255, 255, 0.42);
            color: #101214;
            box-shadow: 0 18px 38px rgba(18, 33, 44, 0.1);
            cursor: pointer;
            text-align: left;
            opacity: 0;
            transform: var(--gallery-album-card-start);
            transition:
                opacity 0.88s cubic-bezier(0.16, 1, 0.3, 1),
                transform 0.88s cubic-bezier(0.16, 1, 0.3, 1),
                transform 0.24s ease,
                border-color 0.24s ease,
                box-shadow 0.24s ease;
            will-change: opacity, transform;
        }

        .gallery-album-card:hover,
        .gallery-album-card.is-active {
            transform: translateY(-4px);
            border-color: rgba(12, 80, 93, 0.48);
            box-shadow: 0 24px 46px rgba(12, 80, 93, 0.16);
        }

        .gallery-album-card:focus-visible {
            outline: none;
            border-color: #0c505d;
            box-shadow:
                0 24px 46px rgba(12, 80, 93, 0.16),
                0 0 0 4px rgba(12, 80, 93, 0.14);
        }

        .gallery-album-card:nth-child(3n + 1) {
            --gallery-album-card-start: translateX(-88px) scale(0.97);
        }

        .gallery-album-card:nth-child(3n + 2) {
            --gallery-album-card-start: translateY(-76px) scale(0.97);
        }

        .gallery-album-card:nth-child(3n) {
            --gallery-album-card-start: translateX(88px) scale(0.97);
        }

        .gallery-album-cards.is-visible .gallery-album-card {
            opacity: 1;
            transform: none;
        }

        .gallery-album-cards.is-visible .gallery-album-card:nth-child(3n + 1) {
            transition-delay: 0.08s;
        }

        .gallery-album-cards.is-visible .gallery-album-card:nth-child(3n + 2) {
            transition-delay: 0.18s;
        }

        .gallery-album-cards.is-visible .gallery-album-card:nth-child(3n) {
            transition-delay: 0.28s;
        }

        .gallery-album-cards.is-visible .gallery-album-card:hover,
        .gallery-album-cards.is-visible .gallery-album-card.is-active {
            transform: translateY(-4px);
        }

        .gallery-album-cover {
            position: absolute;
            inset: 0;
        }

        .gallery-album-cover img {
            width: 100%;
            height: 100%;
            display: block;
            object-fit: cover;
            transition: transform 0.72s cubic-bezier(0.16, 1, 0.3, 1);
        }

        .gallery-album-card:hover .gallery-album-cover img,
        .gallery-album-card.is-active .gallery-album-cover img {
            transform: scale(1.06);
        }

        .gallery-album-placeholder {
            display: block;
            width: 100%;
            height: 100%;
            background:
                linear-gradient(135deg, rgba(12, 80, 93, 0.9), rgba(23, 63, 50, 0.9)),
                linear-gradient(180deg, rgba(255, 255, 255, 0.18), transparent);
        }

        .gallery-album-card::after {
            content: "";
            position: absolute;
            inset: 0;
            background: linear-gradient(180deg, rgba(5, 12, 17, 0.08) 0%, rgba(5, 12, 17, 0.76) 100%);
        }

        .gallery-album-card-copy {
            position: absolute;
            inset: auto 0 0;
            z-index: 1;
            display: grid;
            gap: 8px;
            padding: 24px;
            color: #ffffff;
        }

        .gallery-album-count {
            width: fit-content;
            padding: 7px 11px;
            border-radius: 999px;
            background: rgba(255, 255, 255, 0.18);
            color: rgba(255, 255, 255, 0.88);
            font-size: 0.8rem;
            font-weight: 700;
            letter-spacing: 0.1em;
            text-transform: uppercase;
            backdrop-filter: blur(12px);
            -webkit-backdrop-filter: blur(12px);
        }

        .gallery-album-card-title {
            margin: 0;
            font-family: var(--font-primary);
            font-size: clamp(1.45rem, 2.05vw, 2.25rem);
            font-weight: 600;
            line-height: 1;
        }

        .gallery-album-card-subtitle {
            margin: 0;
            max-width: 420px;
            font-size: 0.98rem;
            line-height: 1.55;
            color: rgba(255, 255, 255, 0.8);
        }

        .gallery-album-empty {
            padding: 28px;
            border: 1px solid rgba(178, 193, 204, 0.58);
            border-radius: 26px;
            background: rgba(255, 255, 255, 0.44);
            color: rgba(16, 33, 44, 0.72);
            font-size: 1rem;
            line-height: 1.7;
            text-align: center;
        }

        .gallery-album-panels {
            display: grid;
            gap: 32px;
        }

        .gallery-album-panel {
            display: grid;
            gap: 20px;
            scroll-margin-top: 140px;
        }

        .gallery-album-panel[hidden] {
            display: none;
        }

        .gallery-album-head {
            display: grid;
            gap: 10px;
            justify-items: center;
            text-align: center;
        }

        .gallery-album-title {
            margin: 0;
            font-family: var(--font-primary);
            font-size: clamp(1.7rem, 2.6vw, 3rem);
            font-weight: 600;
            line-height: 1;
            color: #121926;
        }

        .gallery-album-subtitle {
            margin: 0;
            max-width: 720px;
            font-size: 1rem;
            line-height: 1.75;
            text-align: center;
            color: rgba(16, 33, 44, 0.72);
        }

        .gallery-album-grid {
            display: grid;
            grid-template-columns: repeat(4, minmax(0, 1fr));
            gap: 14px;
        }

        .gallery-album-image {
            --gallery-album-image-start: translateY(56px) scale(0.97);
            position: relative;
            overflow: hidden;
            aspect-ratio: 4 / 5;
            min-height: 0;
            border-radius: 20px;
            background: rgba(255, 255, 255, 0.54);
            box-shadow: 0 18px 32px rgba(18, 33, 44, 0.1);
            opacity: 0;
            transform: var(--gallery-album-image-start);
            transition:
                opacity 0.82s cubic-bezier(0.16, 1, 0.3, 1),
                transform 0.82s cubic-bezier(0.16, 1, 0.3, 1),
                box-shadow 0.22s ease;
            will-change: opacity, transform;
        }

        .gallery-album-image-button {
            display: block;
            width: 100%;
            height: 100%;
            padding: 0;
            border: 0;
            background: transparent;
            cursor: zoom-in;
        }

        .gallery-album-image img {
            width: 100%;
            height: 100%;
            object-fit: cover;
            display: block;
            transition: transform 0.72s cubic-bezier(0.16, 1, 0.3, 1);
        }

        .gallery-album-image:hover img {
            transform: scale(1.06);
        }

        .gallery-album-image:nth-child(4n + 1) {
            --gallery-album-image-start: translateX(-72px) scale(0.97);
        }

        .gallery-album-image:nth-child(4n + 2) {
            --gallery-album-image-start: translateY(-64px) scale(0.97);
        }

        .gallery-album-image:nth-child(4n + 3) {
            --gallery-album-image-start: translateY(64px) scale(0.97);
        }

        .gallery-album-image:nth-child(4n) {
            --gallery-album-image-start: translateX(72px) scale(0.97);
        }

        .gallery-album-panel.is-visible .gallery-album-image {
            opacity: 1;
            transform: none;
        }

        .gallery-album-panel.is-visible .gallery-album-image:nth-child(1) { transition-delay: 0.06s; }
        .gallery-album-panel.is-visible .gallery-album-image:nth-child(2) { transition-delay: 0.12s; }
        .gallery-album-panel.is-visible .gallery-album-image:nth-child(3) { transition-delay: 0.18s; }
        .gallery-album-panel.is-visible .gallery-album-image:nth-child(4) { transition-delay: 0.24s; }
        .gallery-album-panel.is-visible .gallery-album-image:nth-child(5) { transition-delay: 0.3s; }
        .gallery-album-panel.is-visible .gallery-album-image:nth-child(6) { transition-delay: 0.36s; }
        .gallery-album-panel.is-visible .gallery-album-image:nth-child(7) { transition-delay: 0.42s; }
        .gallery-album-panel.is-visible .gallery-album-image:nth-child(n + 8) { transition-delay: 0.48s; }

        .gallery-lightbox[hidden] {
            display: none;
        }

        .gallery-lightbox {
            position: fixed;
            inset: 0;
            z-index: 1600;
            display: grid;
            place-items: center;
            padding: 28px;
            background: rgba(7, 16, 24, 0.78);
            backdrop-filter: blur(16px);
            -webkit-backdrop-filter: blur(16px);
        }

        .gallery-lightbox-dialog {
            position: relative;
            width: min(100%, 1120px);
            display: grid;
            gap: 18px;
            justify-items: center;
        }

        .gallery-lightbox-close,
        .gallery-lightbox-nav {
            display: inline-flex;
            align-items: center;
            justify-content: center;
            border: 1px solid rgba(255, 255, 255, 0.22);
            border-radius: 999px;
            background: rgba(255, 255, 255, 0.12);
            color: #ffffff;
            backdrop-filter: blur(12px);
            -webkit-backdrop-filter: blur(12px);
            cursor: pointer;
            transition: transform 0.22s ease, background-color 0.22s ease, border-color 0.22s ease;
        }

        .gallery-lightbox-close {
            position: absolute;
            top: 0;
            right: 0;
            width: 46px;
            height: 46px;
            z-index: 2;
        }

        .gallery-lightbox-nav {
            position: absolute;
            top: 50%;
            width: 54px;
            height: 54px;
            z-index: 2;
            transform: translateY(-50%);
        }

        .gallery-lightbox-nav--prev {
            left: 10px;
        }

        .gallery-lightbox-nav--next {
            right: 10px;
        }

        .gallery-lightbox-close:hover,
        .gallery-lightbox-nav:hover {
            background: rgba(12, 80, 93, 0.9);
            border-color: rgba(12, 80, 93, 0.9);
        }

        .gallery-lightbox-frame {
            position: relative;
            width: 100%;
            min-height: min(72vh, 760px);
            padding: 22px 74px;
            border-radius: 28px;
            border: 1px solid rgba(255, 255, 255, 0.16);
            background: rgba(255, 255, 255, 0.08);
            box-shadow: 0 32px 60px rgba(0, 0, 0, 0.32);
        }

        .gallery-lightbox-stage {
            width: 100%;
            height: 100%;
            min-height: min(66vh, 700px);
            display: grid;
            place-items: center;
        }

        .gallery-lightbox-image {
            max-width: 100%;
            max-height: min(66vh, 700px);
            width: auto;
            height: auto;
            border-radius: 22px;
            object-fit: contain;
            box-shadow: 0 24px 44px rgba(0, 0, 0, 0.28);
        }

        .gallery-lightbox-meta {
            display: grid;
            gap: 6px;
            justify-items: center;
            text-align: center;
            color: #ffffff;
        }

        .gallery-lightbox-title {
            margin: 0;
            font-family: var(--font-primary);
            font-size: clamp(1.5rem, 2.3vw, 2.2rem);
            line-height: 1;
        }

        .gallery-lightbox-counter {
            margin: 0;
            font-size: 0.96rem;
            color: rgba(255, 255, 255, 0.72);
        }

        body.gallery-lightbox-open {
            overflow: hidden;
        }

        @media (max-width: 960px) {
            .gallery-album-cards {
                grid-template-columns: repeat(2, minmax(0, 1fr));
            }

            .gallery-album-grid {
                grid-template-columns: repeat(2, minmax(0, 1fr));
            }
        }

        @media (max-width: 768px) {
            .gallery-page {
                padding: 18px;
            }

            .gallery-page-header {
                padding-top: 96px;
            }

            .gallery-page-title {
                font-size: var(--section-title-size-mobile);
            }

            .gallery-page-header {
                text-align: center;
                justify-items: center;
            }

            .gallery-page-subtitle {
                text-align: center;
            }

            .gallery-album-cards {
                grid-template-columns: 1fr;
            }

            .gallery-album-card {
                min-height: 260px;
            }

            .gallery-album-grid {
                grid-template-columns: 1fr;
            }

            .gallery-album-image {
                aspect-ratio: 4 / 5;
            }

            .gallery-lightbox {
                padding: 16px;
            }

            .gallery-lightbox-frame {
                min-height: min(68vh, 620px);
                padding: 56px 18px 18px;
                border-radius: 22px;
            }

            .gallery-lightbox-stage {
                min-height: min(52vh, 520px);
            }

            .gallery-lightbox-image {
                max-height: min(52vh, 520px);
                border-radius: 18px;
            }

            .gallery-lightbox-close {
                top: 12px;
                right: 12px;
                width: 42px;
                height: 42px;
            }

            .gallery-lightbox-nav {
                top: auto;
                bottom: 12px;
                transform: none;
                width: 46px;
                height: 46px;
            }

            .gallery-lightbox-nav--prev {
                left: 12px;
            }

            .gallery-lightbox-nav--next {
                right: 12px;
            }
        }
    </style>
@endpush

@section('content')
    @php
        $pageTitle = filled($gallerySection?->page_title) ? $gallerySection->page_title : \App\Models\GallerySection::DEFAULT_PAGE_TITLE;
        $pageSubtitle = filled($gallerySection?->page_subtitle) ? $gallerySection->page_subtitle : \App\Models\GallerySection::DEFAULT_PAGE_SUBTITLE;
        $albums = $gallerySection?->pageAlbums() ?? [];
    @endphp

    <div class="gallery-page">
        <div class="gallery-page-shell">
            @include('partials.navbar')

            <section class="gallery-page-header">
                <p class="gallery-page-kicker">Gallery</p>
                <h1 class="gallery-page-title">{{ $pageTitle }}</h1>
                <p class="gallery-page-subtitle">{{ $pageSubtitle }}</p>
            </section>

            @if ($albums)
                <section class="gallery-album-cards" aria-label="Gallery albums">
                    @foreach ($albums as $index => $album)
                        @php
                            $albumTitle = $album['title'] ?: 'Album '.($index + 1);
                            $coverImage = $album['images'][0]['image_url'] ?? null;
                            $imageCount = count($album['images'] ?? []);
                            $panelId = 'album-panel-'.($index + 1);
                        @endphp

                        <button
                            class="gallery-album-card"
                            type="button"
                            aria-expanded="false"
                            aria-controls="{{ $panelId }}"
                            data-gallery-album-trigger="{{ $panelId }}"
                        >
                            <span class="gallery-album-cover" aria-hidden="true">
                                @if ($coverImage)
                                    <img src="{{ $coverImage }}" alt="" loading="lazy" decoding="async">
                                @else
                                    <span class="gallery-album-placeholder"></span>
                                @endif
                            </span>
                            <span class="gallery-album-card-copy">
                                <span class="gallery-album-count">{{ $imageCount }} {{ $imageCount === 1 ? 'Image' : 'Images' }}</span>
                                <span class="gallery-album-card-title">{{ $albumTitle }}</span>
                                @if (filled($album['subtitle'] ?? null))
                                    <span class="gallery-album-card-subtitle">{{ $album['subtitle'] }}</span>
                                @endif
                            </span>
                        </button>
                    @endforeach
                </section>

                <div class="gallery-album-panels">
                    @foreach ($albums as $index => $album)
                        @php
                            $albumTitle = $album['title'] ?: 'Album '.($index + 1);
                            $panelId = 'album-panel-'.($index + 1);
                        @endphp

                        <section class="gallery-album-panel" id="{{ $panelId }}" data-gallery-album-panel hidden>
                            <div class="gallery-album-head">
                                <h2 class="gallery-album-title">{{ $albumTitle }}</h2>
                                @if (filled($album['subtitle'] ?? null))
                                    <p class="gallery-album-subtitle">{{ $album['subtitle'] }}</p>
                                @endif
                            </div>

                            @if (! empty($album['images']))
                                <div class="gallery-album-grid">
                                    @foreach ($album['images'] as $imageIndex => $image)
                                        <article class="gallery-album-image">
                                            <button
                                                class="gallery-album-image-button"
                                                type="button"
                                                data-gallery-lightbox-trigger
                                                data-gallery-lightbox-album="{{ $panelId }}"
                                                data-gallery-lightbox-index="{{ $imageIndex }}"
                                                aria-label="Open {{ $albumTitle }} image {{ $imageIndex + 1 }}"
                                            >
                                                <img src="{{ $image['image_url'] }}" alt="{{ $albumTitle . ' image ' . ($imageIndex + 1) }}" loading="lazy" decoding="async">
                                            </button>
                                        </article>
                                    @endforeach
                                </div>
                            @else
                                <p class="gallery-album-empty">No images have been added to this album yet.</p>
                            @endif
                        </section>
                    @endforeach
                </div>
            @else
                <p class="gallery-album-empty">No gallery albums have been published yet.</p>
            @endif

            <div class="gallery-lightbox" data-gallery-lightbox hidden>
                <div class="gallery-lightbox-dialog" role="dialog" aria-modal="true" aria-label="Gallery image viewer">
                    <div class="gallery-lightbox-frame">
                        <button class="gallery-lightbox-close" type="button" data-gallery-lightbox-close aria-label="Close image viewer">
                            <svg viewBox="0 0 24 24" fill="none" aria-hidden="true">
                                <path d="M6 6L18 18M18 6L6 18" stroke="currentColor" stroke-linecap="round" stroke-linejoin="round" stroke-width="1.8"/>
                            </svg>
                        </button>

                        <button class="gallery-lightbox-nav gallery-lightbox-nav--prev" type="button" data-gallery-lightbox-prev aria-label="Previous image">
                            <svg viewBox="0 0 24 24" fill="none" aria-hidden="true">
                                <path d="M15 6L9 12L15 18" stroke="currentColor" stroke-linecap="round" stroke-linejoin="round" stroke-width="1.8"/>
                            </svg>
                        </button>

                        <button class="gallery-lightbox-nav gallery-lightbox-nav--next" type="button" data-gallery-lightbox-next aria-label="Next image">
                            <svg viewBox="0 0 24 24" fill="none" aria-hidden="true">
                                <path d="M9 6L15 12L9 18" stroke="currentColor" stroke-linecap="round" stroke-linejoin="round" stroke-width="1.8"/>
                            </svg>
                        </button>

                        <div class="gallery-lightbox-stage">
                            <img class="gallery-lightbox-image" src="" alt="" data-gallery-lightbox-image>
                        </div>
                    </div>

                    <div class="gallery-lightbox-meta">
                        <p class="gallery-lightbox-title" data-gallery-lightbox-title></p>
                        <p class="gallery-lightbox-counter" data-gallery-lightbox-counter></p>
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
            const triggers = Array.from(document.querySelectorAll('[data-gallery-album-trigger]'));
            const panels = Array.from(document.querySelectorAll('[data-gallery-album-panel]'));
            const albumCards = document.querySelector('.gallery-album-cards');
            const lightbox = document.querySelector('[data-gallery-lightbox]');
            const lightboxImage = lightbox?.querySelector('[data-gallery-lightbox-image]');
            const lightboxTitle = lightbox?.querySelector('[data-gallery-lightbox-title]');
            const lightboxCounter = lightbox?.querySelector('[data-gallery-lightbox-counter]');
            const lightboxClose = lightbox?.querySelector('[data-gallery-lightbox-close]');
            const lightboxPrev = lightbox?.querySelector('[data-gallery-lightbox-prev]');
            const lightboxNext = lightbox?.querySelector('[data-gallery-lightbox-next]');
            const imageTriggers = Array.from(document.querySelectorAll('[data-gallery-lightbox-trigger]'));

            let activeAlbumId = null;
            let activeImageIndex = 0;

            const albumImagesMap = panels.reduce((carry, panel) => {
                carry[panel.id] = Array.from(panel.querySelectorAll('[data-gallery-lightbox-trigger]'));

                return carry;
            }, {});

            if (!triggers.length || !panels.length) {
                return;
            }

            if (albumCards) {
                if (window.matchMedia('(prefers-reduced-motion: reduce)').matches || !('IntersectionObserver' in window)) {
                    albumCards.classList.add('is-visible');
                } else {
                    const albumCardObserver = new IntersectionObserver((entries, observer) => {
                        entries.forEach((entry) => {
                            if (!entry.isIntersecting) {
                                return;
                            }

                            entry.target.classList.add('is-visible');
                            observer.unobserve(entry.target);
                        });
                    }, {
                        threshold: 0.18,
                        rootMargin: '0px 0px -8% 0px',
                    });

                    albumCardObserver.observe(albumCards);
                }
            }

            const openAlbum = (targetId, shouldScroll = true) => {
                panels.forEach((panel) => {
                    const isActive = panel.id === targetId;
                    panel.hidden = !isActive;
                    panel.classList.remove('is-visible');

                    if (isActive) {
                        window.requestAnimationFrame(() => {
                            panel.classList.add('is-visible');
                        });
                    }
                });

                triggers.forEach((trigger) => {
                    const isActive = trigger.dataset.galleryAlbumTrigger === targetId;
                    trigger.classList.toggle('is-active', isActive);
                    trigger.setAttribute('aria-expanded', String(isActive));
                });

                const activePanel = document.getElementById(targetId);

                if (shouldScroll && activePanel) {
                    activePanel.scrollIntoView({
                        behavior: 'smooth',
                        block: 'start',
                    });
                }
            };

            const syncLightbox = () => {
                if (!lightbox || !lightboxImage || !activeAlbumId) {
                    return;
                }

                const albumImages = albumImagesMap[activeAlbumId] ?? [];
                const currentTrigger = albumImages[activeImageIndex];

                if (!currentTrigger) {
                    return;
                }

                const image = currentTrigger.querySelector('img');
                const panel = document.getElementById(activeAlbumId);
                const albumTitleText = panel?.querySelector('.gallery-album-title')?.textContent?.trim() || 'Gallery Album';

                if (!image) {
                    return;
                }

                lightboxImage.src = image.currentSrc || image.src;
                lightboxImage.alt = image.alt || albumTitleText;
                lightboxTitle.textContent = albumTitleText;
                lightboxCounter.textContent = `${activeImageIndex + 1} / ${albumImages.length}`;
            };

            const openLightbox = (albumId, imageIndex) => {
                if (!lightbox || !albumImagesMap[albumId]?.length) {
                    return;
                }

                activeAlbumId = albumId;
                activeImageIndex = imageIndex;
                syncLightbox();
                lightbox.hidden = false;
                document.body.classList.add('gallery-lightbox-open');
            };

            const closeLightbox = () => {
                if (!lightbox) {
                    return;
                }

                lightbox.hidden = true;
                document.body.classList.remove('gallery-lightbox-open');
            };

            const stepLightbox = (direction) => {
                if (!activeAlbumId) {
                    return;
                }

                const albumImages = albumImagesMap[activeAlbumId] ?? [];

                if (!albumImages.length) {
                    return;
                }

                activeImageIndex = (activeImageIndex + direction + albumImages.length) % albumImages.length;
                syncLightbox();
            };

            triggers.forEach((trigger) => {
                trigger.addEventListener('click', () => {
                    openAlbum(trigger.dataset.galleryAlbumTrigger);
                });
            });

            imageTriggers.forEach((trigger) => {
                trigger.addEventListener('click', () => {
                    openLightbox(
                        trigger.dataset.galleryLightboxAlbum,
                        Number(trigger.dataset.galleryLightboxIndex || 0),
                    );
                });
            });

            lightboxClose?.addEventListener('click', closeLightbox);
            lightboxPrev?.addEventListener('click', () => stepLightbox(-1));
            lightboxNext?.addEventListener('click', () => stepLightbox(1));

            lightbox?.addEventListener('click', (event) => {
                if (event.target === lightbox) {
                    closeLightbox();
                }
            });

            document.addEventListener('keydown', (event) => {
                if (lightbox?.hidden) {
                    return;
                }

                if (event.key === 'Escape') {
                    closeLightbox();
                }

                if (event.key === 'ArrowLeft') {
                    stepLightbox(-1);
                }

                if (event.key === 'ArrowRight') {
                    stepLightbox(1);
                }
            });

            const hashTarget = window.location.hash.slice(1);

            if (hashTarget && panels.some((panel) => panel.id === hashTarget)) {
                openAlbum(hashTarget, false);
            }
        });
    </script>
@endpush
