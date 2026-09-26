@php
    use App\Models\ProspectusSection;

    $sectionTitle = filled($prospectusSection?->section_title)
        ? $prospectusSection->section_title
        : ProspectusSection::DEFAULT_SECTION_TITLE;
    $sectionSubtitle = filled($prospectusSection?->section_subtitle)
        ? $prospectusSection->section_subtitle
        : ProspectusSection::DEFAULT_SECTION_SUBTITLE;
    $brochures = $prospectusSection?->brochures() ?? [];
    $totalBrochures = count($brochures);
@endphp

@if (! empty($brochures))
    @once
        @push('styles')
            <style>
                .prospectus-section {
                    padding: 34px 28px 48px;
                    background: var(--section-surface);
                    position: relative;
                }

                .prospectus-shell {
                    width: 100%;
                    max-width: 1240px;
                    margin: 0 auto;
                    display: grid;
                    gap: 28px;
                    opacity: 0;
                    transform: translateY(22px);
                }

                .prospectus-head {
                    display: flex;
                    align-items: center;
                    justify-content: center;
                    text-align: center;
                    width: 100%;
                    opacity: 0;
                    transform: translateY(-12px);
                }

                .prospectus-head-copy {
                    display: grid;
                    gap: 8px;
                    max-width: 840px;
                    margin: 0 auto;
                    text-align: center;
                    justify-items: center;
                }

                .prospectus-kicker {
                    margin: 0;
                    color: #000000;
                    font-size: 0.88rem;
                    font-weight: 700;
                    letter-spacing: 0.16em;
                    text-transform: uppercase;
                }

                .prospectus-title {
                    margin: 0;
                    font-family: var(--font-primary);
                    font-size: var(--section-title-size);
                    font-weight: 400;
                    line-height: 1.05;
                    color: #000000;
                }

                /* 3D Coverflow Stage */
                .prospectus-coverflow-wrapper {
                    position: relative;
                    width: 100%;
                    opacity: 0;
                    transform: translateY(18px);
                }

                .prospectus-section.is-visible .prospectus-shell {
                    opacity: 1;
                    transform: none;
                    animation: navShellSpread 0.9s cubic-bezier(0.16, 1, 0.3, 1) both;
                }

                .prospectus-section.is-visible .prospectus-head {
                    opacity: 1;
                    transform: none;
                    animation: navDropIn 0.58s ease both;
                }

                .prospectus-section.is-visible .prospectus-coverflow-wrapper {
                    opacity: 1;
                    transform: none;
                    animation: navDropIn 0.68s ease 120ms both;
                }

                .prospectus-section.is-visible .prospectus-bottom-meta {
                    opacity: 1;
                    transform: none;
                    animation: navDropIn 0.58s ease 220ms both;
                }

                @media (prefers-reduced-motion: reduce) {
                    .prospectus-shell,
                    .prospectus-head,
                    .prospectus-coverflow-wrapper,
                    .prospectus-bottom-meta {
                        animation: none !important;
                        opacity: 1 !important;
                        transform: none !important;
                    }
                }

                .prospectus-coverflow-stage {
                    position: relative;
                    width: 100%;
                    min-height: 520px;
                    height: clamp(500px, 58vh, 600px);
                    display: flex;
                    align-items: center;
                    justify-content: center;
                    perspective: 1300px;
                    -webkit-perspective: 1300px;
                    perspective-origin: 50% 50%;
                    overflow: hidden;
                    padding: 24px 0 36px;
                    user-select: none;
                    -webkit-user-select: none;
                    touch-action: pan-y;
                }

                .prospectus-coverflow-track {
                    position: relative;
                    width: 100%;
                    height: 100%;
                    display: flex;
                    align-items: center;
                    justify-content: center;
                    transform-style: preserve-3d;
                    -webkit-transform-style: preserve-3d;
                }

                /* 3D Cards */
                .prospectus-card {
                    position: absolute;
                    width: clamp(260px, 25vw, 340px);
                    aspect-ratio: 1080 / 1527;
                    max-height: 485px;
                    border-radius: 20px;
                    overflow: hidden;
                    background: transparent;
                    border: none;
                    box-shadow: none !important;
                    cursor: pointer;
                    transform-style: preserve-3d;
                    -webkit-transform-style: preserve-3d;
                    backface-visibility: hidden;
                    -webkit-backface-visibility: hidden;
                    will-change: transform;
                    transition: transform 0.65s cubic-bezier(0.25, 1, 0.5, 1);
                }

                .prospectus-card.is-active {
                    border: none;
                    box-shadow: none !important;
                }

                .prospectus-card img {
                    width: 100%;
                    height: 100%;
                    object-fit: cover;
                    display: block;
                    pointer-events: none;
                    border-radius: 20px;
                    box-shadow: none !important;
                }

                /* Floating Stage Navigation Buttons */
                .prospectus-stage-nav-btn {
                    position: absolute;
                    top: 50%;
                    transform: translateY(-50%);
                    width: 48px;
                    height: 48px;
                    border-radius: 999px;
                    border: 1px solid rgba(16, 33, 44, 0.12);
                    background: rgba(255, 255, 255, 0.94);
                    color: #101214;
                    display: inline-flex;
                    align-items: center;
                    justify-content: center;
                    box-shadow: 0 8px 24px rgba(16, 33, 44, 0.18);
                    cursor: pointer;
                    z-index: 30;
                    transition: all 0.22s cubic-bezier(0.16, 1, 0.3, 1);
                    backdrop-filter: blur(8px);
                    -webkit-backdrop-filter: blur(8px);
                }

                .prospectus-stage-nav-btn:hover {
                    background: #0c505d;
                    color: #ffffff;
                    border-color: #0c505d;
                    transform: translateY(-50%) scale(1.1);
                    box-shadow: 0 12px 28px rgba(12, 80, 93, 0.3);
                }

                .prospectus-stage-nav-btn svg {
                    width: 20px;
                    height: 20px;
                }

                .prospectus-stage-nav--prev {
                    left: 16px;
                }

                .prospectus-stage-nav--next {
                    right: 16px;
                }

                .prospectus-bottom-meta {
                    display: flex;
                    align-items: center;
                    justify-content: center;
                    gap: 12px;
                    padding: 8px 4px 0;
                    flex-wrap: wrap;
                    opacity: 0;
                    transform: translateY(12px);
                }

                .prospectus-dots {
                    display: flex;
                    align-items: center;
                    gap: 7px;
                }

                .prospectus-dot {
                    width: 8px;
                    height: 8px;
                    border-radius: 999px;
                    background: rgba(16, 33, 44, 0.22);
                    border: none;
                    padding: 0;
                    cursor: pointer;
                    transition: all 0.26s cubic-bezier(0.25, 1, 0.5, 1);
                }

                .prospectus-dot.is-active {
                    width: 26px;
                    background: #0c505d;
                }



                @media (max-width: 768px) {
                    .prospectus-coverflow-stage {
                        min-height: 440px;
                        height: 460px;
                        padding: 16px 0 24px;
                    }
                    .prospectus-card {
                        width: 220px;
                        max-height: 380px;
                        border-radius: 18px;
                    }
                    .prospectus-stage-nav-btn {
                        width: 40px;
                        height: 40px;
                    }
                    .prospectus-stage-nav--prev { left: 6px; }
                    .prospectus-stage-nav--next { right: 6px; }
                }

                /* Lightbox Modal */
                .prospectus-lightbox {
                    position: fixed;
                    inset: 0;
                    z-index: 99999;
                    background: rgba(10, 16, 22, 0.94);
                    backdrop-filter: blur(14px);
                    -webkit-backdrop-filter: blur(14px);
                    display: flex;
                    flex-direction: column;
                    opacity: 0;
                    visibility: hidden;
                    transition: opacity 0.28s ease, visibility 0.28s ease;
                }

                .prospectus-lightbox.is-open {
                    opacity: 1;
                    visibility: visible;
                }

                .prospectus-lightbox-header {
                    display: flex;
                    align-items: center;
                    justify-content: space-between;
                    padding: 14px 24px;
                    color: #ffffff;
                    border-bottom: 1px solid rgba(255, 255, 255, 0.12);
                    background: rgba(15, 24, 32, 0.7);
                }

                .prospectus-lightbox-title {
                    font-size: 1.05rem;
                    font-weight: 600;
                    color: #ffffff;
                    margin: 0;
                }

                .prospectus-lightbox-counter {
                    font-size: 0.88rem;
                    color: rgba(255, 255, 255, 0.7);
                    margin-left: 12px;
                }

                .prospectus-lightbox-actions {
                    display: flex;
                    align-items: center;
                    gap: 12px;
                }

                .prospectus-lightbox-zoom-group {
                    display: inline-flex;
                    align-items: center;
                    gap: 6px;
                    background: rgba(255, 255, 255, 0.08);
                    padding: 3px 8px;
                    border-radius: 999px;
                    border: 1px solid rgba(255, 255, 255, 0.16);
                }

                .prospectus-lightbox-zoom-level {
                    font-size: 0.84rem;
                    font-weight: 600;
                    color: rgba(255, 255, 255, 0.9);
                    min-width: 44px;
                    text-align: center;
                    font-variant-numeric: tabular-nums;
                    user-select: none;
                }

                .prospectus-lightbox-btn {
                    display: inline-flex;
                    align-items: center;
                    justify-content: center;
                    width: 38px;
                    height: 38px;
                    border-radius: 999px;
                    border: 1px solid rgba(255, 255, 255, 0.2);
                    background: rgba(255, 255, 255, 0.1);
                    color: #ffffff;
                    cursor: pointer;
                    transition: all 0.2s ease;
                }

                .prospectus-lightbox-btn:hover {
                    background: rgba(255, 255, 255, 0.25);
                    transform: scale(1.06);
                }

                .prospectus-lightbox-btn:disabled {
                    opacity: 0.35;
                    cursor: not-allowed;
                    transform: none !important;
                }

                .prospectus-lightbox-content {
                    flex: 1;
                    position: relative;
                    display: flex;
                    align-items: center;
                    justify-content: center;
                    padding: 18px;
                    overflow: hidden;
                    touch-action: none;
                }

                .prospectus-lightbox-img {
                    max-width: 92vw;
                    max-height: 84vh;
                    object-fit: contain;
                    border-radius: 12px;
                    box-shadow: 0 20px 60px rgba(0, 0, 0, 0.55);
                    transition: opacity 0.24s ease;
                    user-select: none;
                    -webkit-user-drag: none;
                    cursor: zoom-in;
                    transform-origin: center center;
                    touch-action: none;
                    will-change: transform;
                }

                .prospectus-lightbox-img.is-zoomed {
                    cursor: grab;
                }

                .prospectus-lightbox-img.is-dragging {
                    cursor: grabbing !important;
                }

                .prospectus-lightbox-nav {
                    position: absolute;
                    top: 50%;
                    transform: translateY(-50%);
                    width: 52px;
                    height: 52px;
                    border-radius: 999px;
                    border: 1px solid rgba(255, 255, 255, 0.3);
                    background: rgba(14, 25, 34, 0.85);
                    color: #ffffff;
                    display: inline-flex;
                    align-items: center;
                    justify-content: center;
                    cursor: pointer;
                    z-index: 10;
                    transition: all 0.2s ease;
                    backdrop-filter: blur(6px);
                }

                .prospectus-lightbox-nav:hover {
                    background: #0c505d;
                    border-color: #0c505d;
                    transform: translateY(-50%) scale(1.08);
                }

                .prospectus-lightbox-nav--prev {
                    left: 24px;
                }

                .prospectus-lightbox-nav--next {
                    right: 24px;
                }

                @media (max-width: 640px) {
                    .prospectus-lightbox-nav--prev { left: 8px; width: 42px; height: 42px; }
                    .prospectus-lightbox-nav--next { right: 8px; width: 42px; height: 42px; }
                    .prospectus-lightbox-header { padding: 10px 12px; gap: 8px; }
                    .prospectus-lightbox-actions { gap: 6px; }
                    .prospectus-lightbox-zoom-group { padding: 2px 4px; gap: 3px; }
                    .prospectus-lightbox-btn { width: 32px; height: 32px; }
                    .prospectus-lightbox-zoom-level { font-size: 0.74rem; min-width: 36px; }
                }
            </style>
        @endpush
    @endonce

    <section class="prospectus-section" id="prospectus" data-nav-section="prospectus" aria-label="{{ $sectionTitle }}">
        <div class="prospectus-shell">
            <div class="prospectus-head">
                <div class="prospectus-head-copy">
                    <p class="prospectus-kicker">INVESTMENT &amp; PROSPECTUS</p>
                    <h2 class="prospectus-title">{{ $sectionTitle }}</h2>
                </div>
            </div>

            {{-- 3D Coverflow Carousel Stage --}}
            <div class="prospectus-coverflow-wrapper">
                <div class="prospectus-coverflow-stage" id="prospectus-coverflow-stage" tabindex="0" role="region" aria-label="3D Prospectus Coverflow">
                    <button class="prospectus-stage-nav-btn prospectus-stage-nav--prev" id="prospectus-stage-prev" type="button" aria-label="Previous brochure page">
                        <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.4" stroke-linecap="round" stroke-linejoin="round">
                            <polyline points="15 18 9 12 15 6"></polyline>
                        </svg>
                    </button>

                    <div class="prospectus-coverflow-track" id="prospectus-coverflow-track">
                        @foreach ($brochures as $index => $item)
                            <article 
                                class="prospectus-card {{ $index === 0 ? 'is-active' : '' }}" 
                                data-index="{{ $index }}"
                                data-img-url="{{ $item['image_url'] }}"
                                data-title="{{ $item['title'] }}"
                                data-subtitle="{{ $item['subtitle'] }}"
                                tabindex="0"
                                role="button"
                                aria-label="View {{ $item['title'] }} (Page {{ $index + 1 }} of {{ $totalBrochures }})"
                            >
                                <img 
                                    src="{{ $item['image_url'] }}" 
                                    alt="{{ $item['title'] ?: 'King Lotus International Prospectus Page ' . ($index + 1) }}"
                                    loading="{{ $index < 3 ? 'eager' : 'lazy' }}"
                                >
                            </article>
                        @endforeach
                    </div>

                    <button class="prospectus-stage-nav-btn prospectus-stage-nav--next" id="prospectus-stage-next" type="button" aria-label="Next brochure page">
                        <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.4" stroke-linecap="round" stroke-linejoin="round">
                            <polyline points="9 18 15 12 9 6"></polyline>
                        </svg>
                    </button>
                </div>
            </div>

            <div class="prospectus-bottom-meta">
                <div class="prospectus-dots" id="prospectus-dots" aria-label="Prospectus page dots">
                    @foreach ($brochures as $index => $item)
                        <button class="prospectus-dot {{ $index === 0 ? 'is-active' : '' }}" type="button" data-dot-index="{{ $index }}" aria-label="Go to slide {{ $index + 1 }}"></button>
                    @endforeach
                </div>
            </div>
        </div>
    </section>

    {{-- Interactive Fullscreen Lightbox Modal --}}
    <div class="prospectus-lightbox" id="prospectus-lightbox" role="dialog" aria-modal="true" aria-hidden="true">
        <div class="prospectus-lightbox-header">
            <div>
                <span class="prospectus-lightbox-title" id="lightbox-title">King Lotus International Prospectus</span>
                <span class="prospectus-lightbox-counter" id="lightbox-counter">1 / {{ $totalBrochures }}</span>
            </div>
            <div class="prospectus-lightbox-actions">
                <div class="prospectus-lightbox-zoom-group" role="group" aria-label="Zoom controls">
                    <button class="prospectus-lightbox-btn" id="lightbox-zoom-out" type="button" aria-label="Zoom out" title="Zoom Out (-)">
                        <svg width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.2" stroke-linecap="round" stroke-linejoin="round">
                            <circle cx="11" cy="11" r="8"></circle>
                            <line x1="21" y1="21" x2="16.65" y2="16.65"></line>
                            <line x1="8" y1="11" x2="14" y2="11"></line>
                        </svg>
                    </button>
                    <span class="prospectus-lightbox-zoom-level" id="lightbox-zoom-level">100%</span>
                    <button class="prospectus-lightbox-btn" id="lightbox-zoom-in" type="button" aria-label="Zoom in" title="Zoom In (+)">
                        <svg width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.2" stroke-linecap="round" stroke-linejoin="round">
                            <circle cx="11" cy="11" r="8"></circle>
                            <line x1="21" y1="21" x2="16.65" y2="16.65"></line>
                            <line x1="11" y1="8" x2="11" y2="14"></line>
                            <line x1="8" y1="11" x2="14" y2="11"></line>
                        </svg>
                    </button>
                    <button class="prospectus-lightbox-btn" id="lightbox-zoom-reset" type="button" aria-label="Reset zoom" title="Reset Zoom (100%)">
                        <svg width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.2" stroke-linecap="round" stroke-linejoin="round">
                            <path d="M3 12a9 9 0 1 0 9-9 9.75 9.75 0 0 0-6.74 2.74L3 8"></path>
                            <path d="M3 3v5h5"></path>
                        </svg>
                    </button>
                </div>

                <button class="prospectus-lightbox-btn" id="lightbox-close" type="button" aria-label="Close modal" title="Close (Esc)">
                    <svg width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.2">
                        <line x1="18" y1="6" x2="6" y2="18"></line>
                        <line x1="6" y1="6" x2="18" y2="18"></line>
                    </svg>
                </button>
            </div>
        </div>

        <div class="prospectus-lightbox-content" id="lightbox-stage">
            <button class="prospectus-lightbox-nav prospectus-lightbox-nav--prev" id="lightbox-prev" type="button" aria-label="Previous page">
                <svg width="22" height="22" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.2">
                    <polyline points="15 18 9 12 15 6"></polyline>
                </svg>
            </button>

            <img class="prospectus-lightbox-img" id="lightbox-img" src="" alt="Prospectus Page Preview">

            <button class="prospectus-lightbox-nav prospectus-lightbox-nav--next" id="lightbox-next" type="button" aria-label="Next page">
                <svg width="22" height="22" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.2">
                    <polyline points="9 18 15 12 9 6"></polyline>
                </svg>
            </button>
        </div>
    </div>

    @once
        @push('scripts')
            <script>
                document.addEventListener('DOMContentLoaded', function () {
                    const section = document.querySelector('.prospectus-section');
                    if (!section) return;

                    // Reveal animation
                    const observer = new IntersectionObserver((entries) => {
                        entries.forEach(entry => {
                            if (entry.isIntersecting) {
                                section.classList.add('is-visible');
                                startAutoplay();
                            } else {
                                stopAutoplay();
                            }
                        });
                    }, { threshold: 0.15 });
                    observer.observe(section);

                    const stage = document.getElementById('prospectus-coverflow-stage');
                    const cards = Array.from(document.querySelectorAll('.prospectus-card'));
                    const dots = Array.from(document.querySelectorAll('.prospectus-dot'));
                    const totalCards = cards.length;
                    if (totalCards === 0) return;

                    const prevBtn = document.getElementById('prospectus-prev-btn');
                    const nextBtn = document.getElementById('prospectus-next-btn');
                    const stagePrevBtn = document.getElementById('prospectus-stage-prev');
                    const stageNextBtn = document.getElementById('prospectus-stage-next');

                    let currentIndex = 0;
                    let autoplayTimer = null;
                    let isHovered = false;
                    let touchStartX = 0;
                    let touchEndX = 0;

                    function updateCoverflow() {
                        const isMobile = window.innerWidth <= 640;
                        const isTablet = window.innerWidth > 640 && window.innerWidth <= 1024;
                        
                        const stepX = isMobile ? 60 : (isTablet ? 72 : 80);
                        const rotateAngle = isMobile ? 32 : 38;
                        const depthStep = isMobile ? 120 : 160;
                        const maxVisible = isMobile ? 1 : 2;

                        cards.forEach((card, index) => {
                            let offset = index - currentIndex;

                            if (totalCards > 2) {
                                if (offset > totalCards / 2) offset -= totalCards;
                                else if (offset < -totalCards / 2) offset += totalCards;
                            }

                            const absOffset = Math.abs(offset);
                            const sign = Math.sign(offset);

                            if (offset === 0) {
                                card.classList.add('is-active');
                                card.style.transform = 'translateX(0%) translateZ(0px) rotateY(0deg) scale(1.06)';
                                card.style.zIndex = '20';
                                card.style.opacity = '1';
                                card.style.filter = 'none';
                                card.style.boxShadow = 'none';
                                card.style.pointerEvents = 'auto';
                                card.setAttribute('aria-hidden', 'false');
                            } else if (absOffset <= maxVisible) {
                                card.classList.remove('is-active');
                                const rotY = -sign * (rotateAngle + (absOffset - 1) * 6);
                                const posX = sign * (stepX * absOffset);
                                const posZ = -absOffset * depthStep;
                                const scale = 1 - (absOffset * 0.14);

                                card.style.transform = `translateX(${posX}%) translateZ(${posZ}px) rotateY(${rotY}deg) scale(${scale})`;
                                card.style.zIndex = String(15 - absOffset);
                                card.style.opacity = '1';
                                card.style.filter = 'none';
                                card.style.boxShadow = 'none';
                                card.style.pointerEvents = 'auto';
                                card.setAttribute('aria-hidden', 'false');
                            } else {
                                card.classList.remove('is-active');
                                const posX = sign * (stepX * (maxVisible + 1));
                                const posZ = -(maxVisible + 1) * depthStep;
                                const rotY = -sign * 48;

                                card.style.transform = `translateX(${posX}%) translateZ(${posZ}px) rotateY(${rotY}deg) scale(0.5)`;
                                card.style.zIndex = '0';
                                card.style.opacity = '0';
                                card.style.filter = 'none';
                                card.style.boxShadow = 'none';
                                card.style.pointerEvents = 'none';
                                card.setAttribute('aria-hidden', 'true');
                            }
                        });

                        dots.forEach((dot, idx) => {
                            dot.classList.toggle('is-active', idx === currentIndex);
                        });
                    }

                    function goToSlide(idx) {
                        currentIndex = (idx + totalCards) % totalCards;
                        updateCoverflow();
                    }

                    function nextSlide() {
                        goToSlide(currentIndex + 1);
                    }

                    function prevSlide() {
                        goToSlide(currentIndex - 1);
                    }

                    function startAutoplay() {
                        stopAutoplay();
                        if (totalCards > 1) {
                            autoplayTimer = setInterval(() => {
                                if (!isHovered && (!lightbox || !lightbox.classList.contains('is-open'))) {
                                    nextSlide();
                                }
                            }, 3500);
                        }
                    }

                    function stopAutoplay() {
                        if (autoplayTimer) {
                            clearInterval(autoplayTimer);
                            autoplayTimer = null;
                        }
                    }

                    // Card Click Handlers
                    cards.forEach((card, idx) => {
                        card.addEventListener('click', (e) => {
                            if (idx === currentIndex) {
                                openLightbox(idx);
                            } else {
                                goToSlide(idx);
                            }
                        });

                        card.addEventListener('keydown', (e) => {
                            if (e.key === 'Enter' || e.key === ' ') {
                                e.preventDefault();
                                if (idx === currentIndex) {
                                    openLightbox(idx);
                                } else {
                                    goToSlide(idx);
                                }
                            }
                        });
                    });

                    // Navigation buttons
                    if (prevBtn) prevBtn.addEventListener('click', prevSlide);
                    if (nextBtn) nextBtn.addEventListener('click', nextSlide);
                    if (stagePrevBtn) stagePrevBtn.addEventListener('click', prevSlide);
                    if (stageNextBtn) stageNextBtn.addEventListener('click', nextSlide);

                    // Dots navigation
                    dots.forEach((dot) => {
                        dot.addEventListener('click', () => {
                            const targetIdx = parseInt(dot.getAttribute('data-dot-index'), 10);
                            goToSlide(targetIdx);
                        });
                    });

                    // Pause on hover
                    if (stage) {
                        stage.addEventListener('mouseenter', () => {
                            isHovered = true;
                        });
                        stage.addEventListener('mouseleave', () => {
                            isHovered = false;
                        });

                        // Touch swipe support
                        stage.addEventListener('touchstart', (e) => {
                            touchStartX = e.changedTouches[0].screenX;
                            stopAutoplay();
                        }, { passive: true });

                        stage.addEventListener('touchend', (e) => {
                            touchEndX = e.changedTouches[0].screenX;
                            const diff = touchEndX - touchStartX;
                            if (Math.abs(diff) > 40) {
                                if (diff < 0) nextSlide();
                                else prevSlide();
                            }
                            startAutoplay();
                        }, { passive: true });

                        // Keyboard arrows
                        stage.addEventListener('keydown', (e) => {
                            if (e.key === 'ArrowLeft') {
                                e.preventDefault();
                                prevSlide();
                            } else if (e.key === 'ArrowRight') {
                                e.preventDefault();
                                nextSlide();
                            }
                        });
                    }

                    // Window resize listener to recalibrate 3D coverflow
                    window.addEventListener('resize', updateCoverflow);

                    // Lightbox Modal Logic
                    const lightbox = document.getElementById('prospectus-lightbox');
                    const lightboxImg = document.getElementById('lightbox-img');
                    const lightboxTitle = document.getElementById('lightbox-title');
                    const lightboxCounter = document.getElementById('lightbox-counter');
                    const lightboxClose = document.getElementById('lightbox-close');
                    const lightboxPrev = document.getElementById('lightbox-prev');
                    const lightboxNext = document.getElementById('lightbox-next');
                    const lightboxStage = document.getElementById('lightbox-stage');
                    const lightboxZoomIn = document.getElementById('lightbox-zoom-in');
                    const lightboxZoomOut = document.getElementById('lightbox-zoom-out');
                    const lightboxZoomReset = document.getElementById('lightbox-zoom-reset');
                    const lightboxZoomLevel = document.getElementById('lightbox-zoom-level');

                    let currentLightboxIdx = 0;
                    let zoom = 1.0;
                    const minZoom = 1.0;
                    const maxZoom = 4.0;
                    const zoomStep = 0.5;
                    let panX = 0;
                    let panY = 0;
                    let isDragging = false;
                    let dragStartX = 0;
                    let dragStartY = 0;
                    let initialPanX = 0;
                    let initialPanY = 0;
                    let hasDragged = false;
                    let initialPinchDistance = 0;
                    let initialPinchZoom = 1.0;

                    function applyZoom(animate = true) {
                        if (zoom <= 1.0) {
                            zoom = 1.0;
                            panX = 0;
                            panY = 0;
                            lightboxImg.classList.remove('is-zoomed');
                            lightboxImg.style.cursor = 'zoom-in';
                        } else {
                            lightboxImg.classList.add('is-zoomed');
                            lightboxImg.style.cursor = isDragging ? 'grabbing' : 'grab';
                        }

                        if (animate) {
                            lightboxImg.style.transition = 'transform 0.22s cubic-bezier(0.2, 0, 0.2, 1), opacity 0.24s ease';
                        } else {
                            lightboxImg.style.transition = 'none';
                        }

                        lightboxImg.style.transform = `translate(${panX}px, ${panY}px) scale(${zoom})`;

                        if (lightboxZoomLevel) {
                            lightboxZoomLevel.textContent = `${Math.round(zoom * 100)}%`;
                        }
                        if (lightboxZoomOut) {
                            lightboxZoomOut.disabled = zoom <= 1.0;
                        }
                        if (lightboxZoomIn) {
                            lightboxZoomIn.disabled = zoom >= maxZoom;
                        }
                    }

                    function setZoom(newZoom, targetPanX = null, targetPanY = null) {
                        const clamped = Math.max(minZoom, Math.min(maxZoom, Math.round(newZoom * 10) / 10));
                        if (clamped <= 1.0) {
                            zoom = 1.0;
                            panX = 0;
                            panY = 0;
                        } else {
                            zoom = clamped;
                            if (targetPanX !== null && targetPanY !== null) {
                                panX = targetPanX;
                                panY = targetPanY;
                            }
                        }
                        applyZoom(true);
                    }

                    function zoomIn() {
                        setZoom(zoom + zoomStep);
                    }

                    function zoomOut() {
                        setZoom(zoom - zoomStep);
                    }

                    function resetZoom() {
                        setZoom(1.0);
                    }

                    function openLightbox(idx) {
                        stopAutoplay();
                        resetZoom();
                        currentLightboxIdx = (idx + totalCards) % totalCards;
                        const card = cards[currentLightboxIdx];
                        if (!card) return;

                        const imgUrl = card.getAttribute('data-img-url');
                        const title = card.getAttribute('data-title');
                        
                        lightboxImg.style.opacity = '0';
                        lightboxImg.src = imgUrl;
                        lightboxImg.onload = () => {
                            lightboxImg.style.opacity = '1';
                        };
                        lightboxTitle.textContent = title || 'King Lotus International Prospectus';
                        lightboxCounter.textContent = `${currentLightboxIdx + 1} / ${totalCards}`;

                        lightbox.classList.add('is-open');
                        lightbox.setAttribute('aria-hidden', 'false');
                        document.body.style.overflow = 'hidden';
                    }

                    function closeLightbox() {
                        resetZoom();
                        lightbox.classList.remove('is-open');
                        lightbox.setAttribute('aria-hidden', 'true');
                        document.body.style.overflow = '';
                        startAutoplay();
                    }

                    function navLightbox(direction) {
                        resetZoom();
                        currentLightboxIdx = (currentLightboxIdx + direction + totalCards) % totalCards;
                        const card = cards[currentLightboxIdx];
                        if (!card) return;

                        const imgUrl = card.getAttribute('data-img-url');
                        const title = card.getAttribute('data-title');

                        lightboxImg.style.opacity = '0';
                        lightboxImg.src = imgUrl;
                        lightboxImg.onload = () => {
                            lightboxImg.style.opacity = '1';
                        };
                        lightboxTitle.textContent = title || 'King Lotus International Prospectus';
                        lightboxCounter.textContent = `${currentLightboxIdx + 1} / ${totalCards}`;

                        // Also sync active slide underneath
                        goToSlide(currentLightboxIdx);
                    }

                    // Mouse drag panning when zoomed
                    lightboxImg.addEventListener('mousedown', (e) => {
                        if (zoom <= 1.0) return;
                        e.preventDefault();
                        isDragging = true;
                        hasDragged = false;
                        dragStartX = e.clientX;
                        dragStartY = e.clientY;
                        initialPanX = panX;
                        initialPanY = panY;
                        lightboxImg.classList.add('is-dragging');
                    });

                    window.addEventListener('mousemove', (e) => {
                        if (!isDragging || zoom <= 1.0) return;
                        const dx = e.clientX - dragStartX;
                        const dy = e.clientY - dragStartY;
                        if (Math.abs(dx) > 3 || Math.abs(dy) > 3) {
                            hasDragged = true;
                        }
                        panX = initialPanX + dx;
                        panY = initialPanY + dy;
                        applyZoom(false);
                    });

                    window.addEventListener('mouseup', () => {
                        if (isDragging) {
                            isDragging = false;
                            lightboxImg.classList.remove('is-dragging');
                            applyZoom(true);
                        }
                    });

                    // Click image to zoom in (if at 100%), double click to toggle zoom
                    lightboxImg.addEventListener('click', (e) => {
                        e.stopPropagation();
                        if (zoom <= 1.0) {
                            setZoom(2.0);
                        }
                    });

                    lightboxImg.addEventListener('dblclick', (e) => {
                        e.preventDefault();
                        e.stopPropagation();
                        if (zoom > 1.0) {
                            resetZoom();
                        } else {
                            setZoom(2.2);
                        }
                    });

                    // Mouse wheel to zoom in and out
                    lightboxStage.addEventListener('wheel', (e) => {
                        e.preventDefault();
                        if (e.deltaY < 0) {
                            setZoom(zoom + 0.3);
                        } else {
                            setZoom(zoom - 0.3);
                        }
                    }, { passive: false });

                    // Mobile touch pinch-to-zoom and drag
                    lightboxStage.addEventListener('touchstart', (e) => {
                        if (e.touches.length === 1 && zoom > 1.0) {
                            isDragging = true;
                            dragStartX = e.touches[0].clientX;
                            dragStartY = e.touches[0].clientY;
                            initialPanX = panX;
                            initialPanY = panY;
                        } else if (e.touches.length === 2) {
                            isDragging = false;
                            initialPinchDistance = Math.hypot(
                                e.touches[0].clientX - e.touches[1].clientX,
                                e.touches[0].clientY - e.touches[1].clientY
                            );
                            initialPinchZoom = zoom;
                        }
                    }, { passive: true });

                    lightboxStage.addEventListener('touchmove', (e) => {
                        if (e.touches.length === 1 && isDragging && zoom > 1.0) {
                            const dx = e.touches[0].clientX - dragStartX;
                            const dy = e.touches[0].clientY - dragStartY;
                            panX = initialPanX + dx;
                            panY = initialPanY + dy;
                            applyZoom(false);
                        } else if (e.touches.length === 2 && initialPinchDistance > 0) {
                            const currentDist = Math.hypot(
                                e.touches[0].clientX - e.touches[1].clientX,
                                e.touches[0].clientY - e.touches[1].clientY
                            );
                            const factor = currentDist / initialPinchDistance;
                            setZoom(initialPinchZoom * factor);
                        }
                    }, { passive: true });

                    lightboxStage.addEventListener('touchend', (e) => {
                        if (e.touches.length === 0) {
                            isDragging = false;
                            initialPinchDistance = 0;
                        }
                    }, { passive: true });

                    // Zoom buttons
                    if (lightboxZoomIn) lightboxZoomIn.addEventListener('click', (e) => { e.stopPropagation(); zoomIn(); });
                    if (lightboxZoomOut) lightboxZoomOut.addEventListener('click', (e) => { e.stopPropagation(); zoomOut(); });
                    if (lightboxZoomReset) lightboxZoomReset.addEventListener('click', (e) => { e.stopPropagation(); resetZoom(); });

                    if (lightboxClose) lightboxClose.addEventListener('click', closeLightbox);
                    if (lightboxPrev) lightboxPrev.addEventListener('click', (e) => { e.stopPropagation(); navLightbox(-1); });
                    if (lightboxNext) lightboxNext.addEventListener('click', (e) => { e.stopPropagation(); navLightbox(1); });

                    if (lightboxStage) {
                        lightboxStage.addEventListener('click', (e) => {
                            if (e.target === lightboxStage) closeLightbox();
                        });
                    }

                    document.addEventListener('keydown', (e) => {
                        if (!lightbox || !lightbox.classList.contains('is-open')) return;
                        if (e.key === 'Escape') closeLightbox();
                        else if (e.key === '+' || e.key === '=') zoomIn();
                        else if (e.key === '-' || e.key === '_') zoomOut();
                        else if (e.key === '0') resetZoom();
                        else if (e.key === 'ArrowLeft' && zoom <= 1.0) navLightbox(-1);
                        else if (e.key === 'ArrowRight' && zoom <= 1.0) navLightbox(1);
                    });

                    // Initial 3D Coverflow layout setup
                    updateCoverflow();
                    startAutoplay();
                });
            </script>
        @endpush
    @endonce
@endif
