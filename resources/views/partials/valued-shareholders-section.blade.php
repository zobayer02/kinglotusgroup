@php
    use App\Models\ValuedShareholderSection;

    $sectionTitle = filled($valuedShareholderSection?->section_title)
        ? $valuedShareholderSection->section_title
        : ValuedShareholderSection::DEFAULT_SECTION_TITLE;
    $shareholders = $valuedShareholderSection?->shareholders() ?? [];
@endphp

@if ($valuedShareholderSection && $valuedShareholderSection->shouldDisplayOnWebsite())
    @once
        @push('styles')
            <style>
                .valued-shareholders-section {
                    padding: 24px 28px 28px;
                    background: var(--section-surface);
                }

                .valued-shareholders-shell {
                    width: 100%;
                    max-width: 1240px;
                    margin: 0 auto;
                    display: grid;
                    gap: 22px;
                    opacity: 0;
                    transform: translateY(24px);
                    transition:
                        opacity 0.82s cubic-bezier(0.16, 1, 0.3, 1),
                        transform 0.82s cubic-bezier(0.16, 1, 0.3, 1);
                }

                .valued-shareholders-section.is-visible .valued-shareholders-shell {
                    opacity: 1;
                    transform: none;
                }

                .valued-shareholders-head {
                    display: grid;
                    justify-items: center;
                    gap: 10px;
                    text-align: center;
                    opacity: 0;
                    transform: translateY(-38px);
                }

                .valued-shareholders-title {
                    margin: 0;
                    font-family: var(--font-primary);
                    font-size: var(--section-title-size);
                    font-weight: 400;
                    line-height: 0.98;
                    color: #101214;
                }

                .valued-shareholders-showcase {
                    position: relative;
                    width: min(100%, 1240px);
                    margin: 0 auto;
                    padding: 12px 0 18px;
                    overflow: hidden;
                    overflow-y: hidden;
                    cursor: grab;
                    touch-action: pan-y;
                    user-select: none;
                    opacity: 0;
                    transform: translateY(38px);
                }

                .valued-shareholders-showcase.is-dragging {
                    cursor: grabbing;
                }

                .valued-shareholders-track {
                    display: flex;
                    align-items: stretch;
                    gap: 22px;
                    width: max-content;
                    will-change: transform;
                }

                @keyframes valuedShareholdersAppearUp {
                    from {
                        opacity: 0;
                        transform: translateY(-38px);
                    }

                    to {
                        opacity: 1;
                        transform: translateY(0);
                    }
                }

                @keyframes valuedShareholdersAppearDown {
                    from {
                        opacity: 0;
                        transform: translateY(38px);
                    }

                    to {
                        opacity: 1;
                        transform: translateY(0);
                    }
                }

                .valued-shareholders-section.is-visible .valued-shareholders-head,
                .valued-shareholders-section.is-visible .valued-shareholders-showcase {
                    opacity: 1;
                    transform: none;
                }

                .valued-shareholders-section.is-visible .valued-shareholders-head {
                    animation: valuedShareholdersAppearUp 1s cubic-bezier(0.16, 1, 0.3, 1) both;
                }

                .valued-shareholders-section.is-visible .valued-shareholders-showcase {
                    animation: valuedShareholdersAppearDown 1.14s cubic-bezier(0.16, 1, 0.3, 1) 180ms both;
                }

                .valued-shareholders-slide {
                    position: relative;
                    display: flex;
                    align-items: stretch;
                    flex: 0 0 214px;
                    width: 214px;
                    opacity: 1;
                    pointer-events: auto;
                    transform: none;
                }

                .valued-shareholders-card {
                    display: flex;
                    flex-direction: column;
                    width: 100%;
                    min-height: 100%;
                    color: #101724;
                    position: relative;
                    padding: 14px 14px 16px;
                    border-radius: 32px;
                    border: 1px solid rgba(12, 80, 93, 0.34);
                    background: #ffffff;
                }

                .valued-shareholders-card::before {
                    content: "";
                    position: absolute;
                    inset: 6px;
                    border: 1.5px dashed rgba(12, 80, 93, 0.54);
                    border-radius: 28px;
                    pointer-events: none;
                }

                .valued-shareholders-card-visual {
                    position: relative;
                    overflow: hidden;
                    border-radius: 28px;
                    border: 1px solid rgba(255, 255, 255, 0.78);
                    background: linear-gradient(180deg, #3b78a4 0%, #2b6c99 100%);
                    box-shadow: inset 0 1px 0 rgba(255, 255, 255, 0.22);
                }

                .valued-shareholders-card-visual img {
                    display: block;
                    width: 100%;
                    aspect-ratio: 4 / 5;
                    object-fit: cover;
                    transition: transform 0.58s cubic-bezier(0.16, 1, 0.3, 1);
                    user-select: none;
                    -webkit-user-drag: none;
                }

                .valued-shareholders-card:hover .valued-shareholders-card-visual img {
                    transform: scale(1.04);
                }

                .valued-shareholders-card-placeholder {
                    display: flex;
                    align-items: center;
                    justify-content: center;
                    width: 100%;
                    aspect-ratio: 4 / 5;
                    padding: 24px;
                    border-radius: 28px;
                    font-family: var(--font-secondary);
                    font-size: 1rem;
                    font-weight: 600;
                    letter-spacing: 0.08em;
                    text-transform: uppercase;
                    color: rgba(255, 255, 255, 0.82);
                    background: linear-gradient(180deg, #3b78a4 0%, #2b6c99 100%);
                }

                .valued-shareholders-card-copy {
                    padding-top: 16px;
                    position: relative;
                    display: grid;
                    align-content: start;
                    min-height: 108px;
                    text-align: center;
                }

                .valued-shareholders-card-name {
                    margin: 0;
                    font-family: var(--font-primary);
                    font-size: 1.1rem;
                    font-weight: 400;
                    line-height: 1.1;
                    color: #101214;
                    min-height: 2.22em;
                    display: -webkit-box;
                    -webkit-box-orient: vertical;
                    -webkit-line-clamp: 2;
                    overflow: hidden;
                }

                .valued-shareholders-card-position {
                    display: block;
                    margin-top: 6px;
                    font-family: var(--font-primary);
                    font-size: 0.92rem;
                    line-height: 1.42;
                    color: rgba(16, 33, 44, 0.68);
                    min-height: 4.26em;
                    display: -webkit-box;
                    -webkit-box-orient: vertical;
                    -webkit-line-clamp: 3;
                    overflow: hidden;
                    overflow-wrap: anywhere;
                    hyphens: auto;
                }

                .valued-shareholders-slide.is-position-long .valued-shareholders-card-position {
                    font-size: 0.84rem;
                    line-height: 1.36;
                }

                .valued-shareholders-slide.is-position-xlong .valued-shareholders-card-position {
                    font-size: 0.78rem;
                    line-height: 1.3;
                }

                @media (max-width: 1080px) {
                    .valued-shareholders-showcase {
                        width: min(100%, 960px);
                    }

                    .valued-shareholders-slide {
                        flex-basis: 194px;
                        width: 194px;
                    }

                    .valued-shareholders-card {
                        padding: 12px 12px 15px;
                        border-radius: 28px;
                    }

                    .valued-shareholders-card::before {
                        inset: 5px;
                        border-radius: 24px;
                    }

                    .valued-shareholders-card-visual,
                    .valued-shareholders-card-placeholder {
                        border-radius: 24px;
                    }
                }

                @media (max-width: 768px) {
                    .valued-shareholders-section {
                        padding: 22px 18px 24px;
                    }

                    .valued-shareholders-title {
                        font-size: var(--section-title-size-mobile);
                    }

                    .valued-shareholders-showcase {
                        width: min(100%, 100%);
                        padding: 10px 0 18px;
                    }

                    .valued-shareholders-track {
                        gap: 16px;
                    }

                    .valued-shareholders-slide {
                        flex-basis: min(72vw, 236px);
                        width: min(72vw, 236px);
                    }

                    .valued-shareholders-card-name {
                        font-size: 1rem;
                    }

                    .valued-shareholders-card-position {
                        font-size: 0.84rem;
                    }

                    .valued-shareholders-slide.is-position-long .valued-shareholders-card-position {
                        font-size: 0.79rem;
                    }

                    .valued-shareholders-slide.is-position-xlong .valued-shareholders-card-position {
                        font-size: 0.74rem;
                    }
                }
            </style>
        @endpush
    @endonce

    <section class="valued-shareholders-section" id="valued-shareholders">
        <div class="valued-shareholders-shell">
            @if (filled($sectionTitle))
                <div class="valued-shareholders-head">
                    <h2 class="valued-shareholders-title">{{ $sectionTitle }}</h2>
                </div>
            @endif

            @if ($shareholders)
                <div class="valued-shareholders-showcase" data-valued-shareholders-showcase>
                    <div class="valued-shareholders-track" data-valued-shareholders-track>
                        @foreach ($shareholders as $shareholder)
                            <article class="valued-shareholders-slide" data-valued-shareholders-slide>
                                <div class="valued-shareholders-card">
                                    <div class="valued-shareholders-card-visual">
                                        @if (!empty($shareholder['image_url']))
                                            <img src="{{ $shareholder['image_url'] }}" alt="{{ $shareholder['name'] ?: 'Shareholder' }}" loading="lazy" decoding="async" draggable="false">
                                        @else
                                            <div class="valued-shareholders-card-placeholder">No Image</div>
                                        @endif
                                    </div>
                                    <div class="valued-shareholders-card-copy">
                                        @if (!empty($shareholder['name']))
                                            <h3 class="valued-shareholders-card-name">{{ $shareholder['name'] }}</h3>
                                        @endif
                                        @if (!empty($shareholder['position']))
                                            <span class="valued-shareholders-card-position">{{ $shareholder['position'] }}</span>
                                        @endif
                                    </div>
                                </div>
                            </article>
                        @endforeach
                    </div>
                </div>
            @endif
        </div>
    </section>

    @once
        @push('scripts')
            <script>
                document.addEventListener('DOMContentLoaded', () => {
                    document.querySelectorAll('[data-valued-shareholders-showcase]').forEach((showcase) => {
                        const track = showcase.querySelector('[data-valued-shareholders-track]');
                        const slides = Array.from(showcase.querySelectorAll('[data-valued-shareholders-slide]'));

                        if (!track || !slides.length) {
                            return;
                        }

                        const baseMarkup = track.innerHTML;
                        let loopDistance = 0;
                        let targetOffset = 0;
                        let displayOffset = 0;
                        let velocity = 0;
                        let rafId = null;
                        let lastFrameTime = 0;
                        let isDragging = false;
                        let isHovering = false;
                        let isFocusWithin = false;
                        let activePointerId = null;
                        let dragStartX = 0;
                        let dragStartOffset = 0;

                        const syncCardTypography = (scopeSlides) => {
                            scopeSlides.forEach((slide) => {
                                slide.classList.remove('is-position-long', 'is-position-xlong');

                                const position = slide.querySelector('.valued-shareholders-card-position');

                                if (!position) {
                                    return;
                                }

                                const textLength = position.textContent.trim().length;

                                if (textLength >= 54) {
                                    slide.classList.add('is-position-xlong');
                                    return;
                                }

                                if (textLength >= 34) {
                                    slide.classList.add('is-position-long');
                                }
                            });
                        };

                        const normalizeOffset = (value) => {
                            if (!loopDistance) {
                                return 0;
                            }

                            return ((value % loopDistance) + loopDistance) % loopDistance;
                        };

                        const applyOffset = (value = displayOffset) => {
                            track.style.transform = `translate3d(${-normalizeOffset(value)}px, 0, 0)`;
                        };

                        const isPaused = () => isDragging || isHovering || isFocusWithin;

                        const animate = (timestamp) => {
                            if (!lastFrameTime) {
                                lastFrameTime = timestamp;
                            }

                            const delta = Math.min(timestamp - lastFrameTime, 48);
                            lastFrameTime = timestamp;

                            if (!isPaused() && loopDistance > 0) {
                                targetOffset += delta * 0.042;
                            }

                            if (loopDistance > 0) {
                                const displacement = targetOffset - displayOffset;
                                const springStrength = isDragging ? 0.18 : 0.11;
                                const damping = isDragging ? 0.68 : 0.8;

                                velocity += displacement * springStrength;
                                velocity *= damping;
                                displayOffset += velocity;
                                applyOffset();
                            }

                            rafId = window.requestAnimationFrame(animate);
                        };

                        const startAnimation = () => {
                            if (rafId) {
                                return;
                            }

                            lastFrameTime = 0;
                            rafId = window.requestAnimationFrame(animate);
                        };

                        const stopAnimation = () => {
                            if (!rafId) {
                                return;
                            }

                            window.cancelAnimationFrame(rafId);
                            rafId = null;
                            lastFrameTime = 0;
                        };

                        const rebuildTrack = () => {
                            track.innerHTML = baseMarkup;

                            const originalSlides = Array.from(track.querySelectorAll('[data-valued-shareholders-slide]'));
                            syncCardTypography(originalSlides);

                            if (!originalSlides.length) {
                                loopDistance = 0;
                                applyOffset();
                                return;
                            }

                            const firstSlide = originalSlides[0];
                            const lastSlide = originalSlides[originalSlides.length - 1];
                            const trackStyles = window.getComputedStyle(track);
                            const gap = Number.parseFloat(trackStyles.columnGap || trackStyles.gap || '0');
                            const originalWidth = (lastSlide.offsetLeft + lastSlide.offsetWidth) - firstSlide.offsetLeft;
                            const safeOriginalWidth = Math.max(originalWidth + gap, firstSlide.offsetWidth + gap, 1);
                            const minCopies = Math.max(2, Math.ceil((showcase.clientWidth * 2) / safeOriginalWidth) + 1);

                            for (let copyIndex = 1; copyIndex < minCopies; copyIndex += 1) {
                                originalSlides.forEach((slide) => {
                                    const clone = slide.cloneNode(true);
                                    clone.setAttribute('aria-hidden', 'true');
                                    track.appendChild(clone);
                                });
                            }

                            syncCardTypography(Array.from(track.querySelectorAll('[data-valued-shareholders-slide]')));

                            loopDistance = safeOriginalWidth;
                            targetOffset = normalizeOffset(targetOffset);
                            displayOffset = normalizeOffset(displayOffset);
                            velocity = 0;
                            applyOffset();
                        };

                        showcase.addEventListener('dragstart', (event) => {
                            event.preventDefault();
                        });

                        showcase.addEventListener('mouseenter', () => {
                            isHovering = true;
                        });
                        showcase.addEventListener('mouseleave', () => {
                            isHovering = false;
                        });
                        showcase.addEventListener('pointerenter', () => {
                            isHovering = true;
                        });
                        showcase.addEventListener('pointerleave', () => {
                            isHovering = false;
                        });
                        showcase.addEventListener('focusin', () => {
                            isFocusWithin = true;
                        });
                        showcase.addEventListener('focusout', () => {
                            isFocusWithin = showcase.contains(document.activeElement);
                        });

                        showcase.addEventListener('pointerdown', (event) => {
                            if (event.button !== undefined && event.button !== 0) {
                                return;
                            }

                            activePointerId = event.pointerId;
                            isDragging = true;
                            dragStartX = event.clientX;
                            dragStartOffset = targetOffset;
                            velocity = 0;
                            showcase.classList.add('is-dragging');
                            showcase.setPointerCapture?.(event.pointerId);
                        });

                        showcase.addEventListener('pointermove', (event) => {
                            if (!isDragging || activePointerId !== event.pointerId) {
                                return;
                            }

                            const deltaX = event.clientX - dragStartX;

                            if (Math.abs(deltaX) > 3) {
                                event.preventDefault();
                            }

                            targetOffset = dragStartOffset - deltaX;
                            displayOffset = targetOffset;
                            applyOffset(targetOffset);
                        });

                        const releaseDrag = (event) => {
                            if (!isDragging || (event && activePointerId !== event.pointerId)) {
                                return;
                            }

                            isDragging = false;
                            activePointerId = null;
                            targetOffset = normalizeOffset(targetOffset);
                            displayOffset = normalizeOffset(displayOffset);
                            showcase.classList.remove('is-dragging');
                            lastFrameTime = 0;
                        };

                        showcase.addEventListener('pointerup', releaseDrag);
                        showcase.addEventListener('pointercancel', releaseDrag);
                        showcase.addEventListener('lostpointercapture', releaseDrag);

                        document.addEventListener('visibilitychange', () => {
                            if (document.hidden) {
                                stopAnimation();
                                return;
                            }

                            startAnimation();
                        });

                        let resizeTimer = null;
                        window.addEventListener('resize', () => {
                            window.clearTimeout(resizeTimer);
                            resizeTimer = window.setTimeout(rebuildTrack, 120);
                        });

                        rebuildTrack();
                        startAnimation();
                    });
                });
            </script>
        @endpush
    @endonce
@endif
