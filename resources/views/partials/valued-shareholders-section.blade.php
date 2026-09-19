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
                    gap: 16px;
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
                    flex: 0 0 172px;
                    width: 172px;
                    opacity: 1;
                    pointer-events: auto;
                    transform: none;
                }

                .valued-shareholders-card {
                    display: flex;
                    flex-direction: column;
                    width: 100%;
                    height: 100%;
                    min-height: 100%;
                    color: #101724;
                    position: relative;
                    padding: 10px 10px 12px;
                    border-radius: 20px;
                    border: 1px solid rgba(12, 80, 93, 0.28);
                    background: #ffffff;
                    box-shadow: 0 8px 22px rgba(18, 33, 44, 0.05);
                    box-sizing: border-box;
                    transition: transform 0.24s cubic-bezier(0.16, 1, 0.3, 1), box-shadow 0.24s ease, border-color 0.24s ease;
                }

                .valued-shareholders-card:hover {
                    transform: translateY(-3px);
                    border-color: rgba(12, 80, 93, 0.54);
                    box-shadow: 0 14px 30px rgba(12, 80, 93, 0.12);
                }

                .valued-shareholders-card::before {
                    content: "";
                    position: absolute;
                    inset: 4px;
                    border: 1.2px dashed rgba(12, 80, 93, 0.4);
                    border-radius: 17px;
                    pointer-events: none;
                }

                .valued-shareholders-card-visual {
                    position: relative;
                    overflow: hidden;
                    border-radius: 15px;
                    border: 1px solid rgba(255, 255, 255, 0.78);
                    background: linear-gradient(145deg, #1f4765 0%, #102a3f 100%);
                    box-shadow: inset 0 1px 0 rgba(255, 255, 255, 0.22);
                    width: 100%;
                    aspect-ratio: 4 / 5;
                    flex-shrink: 0;
                }

                .valued-shareholders-card-visual img {
                    display: block;
                    width: 100%;
                    height: 100%;
                    aspect-ratio: 4 / 5;
                    object-fit: cover;
                    transition: transform 0.44s cubic-bezier(0.16, 1, 0.3, 1);
                    user-select: none;
                    -webkit-user-drag: none;
                }

                .valued-shareholders-card:hover .valued-shareholders-card-visual img {
                    transform: scale(1.05);
                }

                .valued-shareholders-card-placeholder {
                    display: flex;
                    flex-direction: column;
                    align-items: center;
                    justify-content: center;
                    gap: 8px;
                    width: 100%;
                    aspect-ratio: 4 / 5;
                    padding: 12px;
                    border-radius: 15px;
                    background: linear-gradient(145deg, #1f4765 0%, #102a3f 100%);
                    box-shadow: inset 0 1px 0 rgba(255, 255, 255, 0.18);
                    color: rgba(255, 255, 255, 0.7);
                    user-select: none;
                }

                .valued-shareholders-avatar-icon {
                    width: 44px;
                    height: 44px;
                    border-radius: 50%;
                    background: rgba(255, 255, 255, 0.1);
                    border: 1px solid rgba(255, 255, 255, 0.2);
                    display: flex;
                    align-items: center;
                    justify-content: center;
                    color: rgba(255, 255, 255, 0.88);
                }

                .valued-shareholders-avatar-icon svg {
                    width: 24px;
                    height: 24px;
                }

                .valued-shareholders-placeholder-copy {
                    display: flex;
                    flex-direction: column;
                    align-items: center;
                    justify-content: center;
                    gap: 2px;
                    text-align: center;
                    line-height: 1.1;
                }

                .valued-shareholders-placeholder-brand {
                    font-family: var(--font-secondary);
                    font-size: 0.66rem;
                    font-weight: 700;
                    letter-spacing: 0.14em;
                    text-transform: uppercase;
                    color: rgba(255, 255, 255, 0.88);
                }

                .valued-shareholders-placeholder-sub {
                    font-family: var(--font-secondary);
                    font-size: 0.52rem;
                    font-weight: 600;
                    letter-spacing: 0.2em;
                    text-transform: uppercase;
                    color: rgba(255, 255, 255, 0.58);
                }

                .valued-shareholders-card-copy {
                    padding-top: 8px;
                    position: relative;
                    display: flex;
                    flex-direction: column;
                    justify-content: center;
                    align-items: center;
                    height: 66px;
                    min-height: 66px;
                    max-height: 66px;
                    overflow: hidden;
                    text-align: center;
                    box-sizing: border-box;
                }

                .valued-shareholders-card-name {
                    margin: 0;
                    width: 100%;
                    font-family: var(--font-primary);
                    font-size: 0.92rem;
                    font-weight: 500;
                    line-height: 1.15;
                    color: #101214;
                    max-height: 34px;
                    display: -webkit-box;
                    -webkit-box-orient: vertical;
                    -webkit-line-clamp: 2;
                    overflow: hidden;
                    text-overflow: ellipsis;
                    word-break: break-word;
                    overflow-wrap: break-word;
                    hyphens: auto;
                }

                .valued-shareholders-card-name.is-name-medium {
                    font-size: 0.82rem;
                    line-height: 1.14;
                }

                .valued-shareholders-card-name.is-name-long {
                    font-size: 0.74rem;
                    line-height: 1.12;
                }

                .valued-shareholders-card-name.is-name-xlong {
                    font-size: 0.66rem;
                    line-height: 1.1;
                }

                .valued-shareholders-card-position {
                    display: -webkit-box;
                    -webkit-box-orient: vertical;
                    -webkit-line-clamp: 2;
                    overflow: hidden;
                    text-overflow: ellipsis;
                    width: 100%;
                    margin-top: 2px;
                    font-family: var(--font-primary);
                    font-size: 0.74rem;
                    line-height: 1.22;
                    max-height: 28px;
                    color: rgba(16, 33, 44, 0.68);
                    overflow-wrap: anywhere;
                }

                @media (max-width: 1080px) {
                    .valued-shareholders-showcase {
                        width: min(100%, 960px);
                    }

                    .valued-shareholders-slide {
                        flex-basis: 160px;
                        width: 160px;
                    }

                    .valued-shareholders-card {
                        padding: 9px 9px 11px;
                        border-radius: 18px;
                    }

                    .valued-shareholders-card::before {
                        inset: 3px;
                        border-radius: 15px;
                    }

                    .valued-shareholders-card-visual,
                    .valued-shareholders-card-placeholder {
                        border-radius: 14px;
                    }

                    .valued-shareholders-card-copy {
                        height: 62px;
                        min-height: 62px;
                        max-height: 62px;
                        padding-top: 6px;
                    }

                    .valued-shareholders-card-name {
                        font-size: 0.86rem;
                        max-height: 32px;
                    }

                    .valued-shareholders-card-name.is-name-medium {
                        font-size: 0.78rem;
                    }

                    .valued-shareholders-card-name.is-name-long {
                        font-size: 0.70rem;
                    }

                    .valued-shareholders-card-name.is-name-xlong {
                        font-size: 0.62rem;
                    }

                    .valued-shareholders-card-position {
                        font-size: 0.70rem;
                        max-height: 26px;
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
                        gap: 12px;
                    }

                    .valued-shareholders-slide {
                        flex-basis: 146px;
                        width: 146px;
                    }

                    .valued-shareholders-card {
                        padding: 8px 8px 10px;
                        border-radius: 16px;
                    }

                    .valued-shareholders-card::before {
                        inset: 3px;
                        border-radius: 13px;
                    }

                    .valued-shareholders-card-visual,
                    .valued-shareholders-card-placeholder {
                        border-radius: 13px;
                    }

                    .valued-shareholders-card-copy {
                        height: 58px;
                        min-height: 58px;
                        max-height: 58px;
                        padding-top: 5px;
                    }

                    .valued-shareholders-card-name {
                        font-size: 0.80rem;
                        max-height: 30px;
                    }

                    .valued-shareholders-card-name.is-name-medium {
                        font-size: 0.74rem;
                    }

                    .valued-shareholders-card-name.is-name-long {
                        font-size: 0.66rem;
                    }

                    .valued-shareholders-card-name.is-name-xlong {
                        font-size: 0.60rem;
                    }

                    .valued-shareholders-card-position {
                        font-size: 0.66rem;
                        max-height: 24px;
                    }

                    .valued-shareholders-view-all {
                        width: 100%;
                    }
                }

                .valued-shareholders-actions {
                    display: flex;
                    justify-content: center;
                    margin-top: 14px;
                }

                .valued-shareholders-view-all {
                    display: inline-flex;
                    align-items: center;
                    justify-content: center;
                    gap: 10px;
                    min-height: 50px;
                    padding: 0 28px;
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

                .valued-shareholders-view-all:hover {
                    transform: translateY(-2px);
                    border-color: #0c505d;
                    background: #0c505d;
                    color: #ffffff;
                    box-shadow: none;
                }

                .valued-shareholders-view-all svg {
                    transition: transform 0.22s ease;
                }

                .valued-shareholders-view-all:hover svg {
                    transform: translateX(4px);
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
                                            <img src="{{ $shareholder['image_url'] }}" alt="{{ $shareholder['name'] ?: 'Shareholder' }}" loading="lazy" decoding="async" draggable="false" onerror="this.style.display='none'; if(this.nextElementSibling) this.nextElementSibling.style.display='flex';">
                                            <div class="valued-shareholders-card-placeholder" aria-label="Shareholder avatar" style="display: none;">
                                                <div class="valued-shareholders-avatar-icon" aria-hidden="true">
                                                    <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.6" stroke-linecap="round" stroke-linejoin="round">
                                                        <path d="M20 21v-2a4 4 0 0 0-4-4H8a4 4 0 0 0-4 4v2"></path>
                                                        <circle cx="12" cy="7" r="4"></circle>
                                                    </svg>
                                                </div>
                                                <div class="valued-shareholders-placeholder-copy">
                                                    <span class="valued-shareholders-placeholder-brand">King Lotus</span>
                                                    <span class="valued-shareholders-placeholder-sub">International</span>
                                                </div>
                                            </div>
                                        @else
                                            <div class="valued-shareholders-card-placeholder" aria-label="Shareholder avatar">
                                                <div class="valued-shareholders-avatar-icon" aria-hidden="true">
                                                    <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.6" stroke-linecap="round" stroke-linejoin="round">
                                                        <path d="M20 21v-2a4 4 0 0 0-4-4H8a4 4 0 0 0-4 4v2"></path>
                                                        <circle cx="12" cy="7" r="4"></circle>
                                                    </svg>
                                                </div>
                                                <div class="valued-shareholders-placeholder-copy">
                                                    <span class="valued-shareholders-placeholder-brand">King Lotus</span>
                                                    <span class="valued-shareholders-placeholder-sub">International</span>
                                                </div>
                                            </div>
                                        @endif
                                    </div>
                                    <div class="valued-shareholders-card-copy">
                                        @if (!empty($shareholder['name']))
                                            @php
                                                $vNameLen = mb_strlen(trim($shareholder['name']));
                                                $vNameClass = $vNameLen > 34 ? 'is-name-xlong' : ($vNameLen > 22 ? 'is-name-long' : ($vNameLen > 15 ? 'is-name-medium' : ''));
                                            @endphp
                                            <h3 class="valued-shareholders-card-name {{ $vNameClass }}">{{ $shareholder['name'] }}</h3>
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

                <div class="valued-shareholders-actions">
                    <a class="valued-shareholders-view-all" href="{{ route('shareholders.index') }}">
                        <span>Explore All Shareholders</span>
                        <svg viewBox="0 0 20 20" fill="none" aria-hidden="true" width="18" height="18">
                            <path d="M4.167 10h11.666m-4.166-4.167 4.166 4.167-4.166 4.167" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"/>
                        </svg>
                    </a>
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
                                const nameEl = slide.querySelector('.valued-shareholders-card-name');
                                if (nameEl) {
                                    nameEl.style.fontSize = '';
                                    nameEl.style.lineHeight = '';

                                    const copyContainer = nameEl.closest('.valued-shareholders-card-copy');
                                    const maxAllowedHeight = copyContainer ? Math.floor(copyContainer.clientHeight * 0.54) : 34;
                                    let size = parseFloat(window.getComputedStyle(nameEl).fontSize);
                                    const minSize = 9.5;

                                    while (nameEl.scrollHeight > maxAllowedHeight && size > minSize) {
                                        size -= 0.5;
                                        nameEl.style.fontSize = `${size}px`;
                                        nameEl.style.lineHeight = '1.12';
                                    }
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
