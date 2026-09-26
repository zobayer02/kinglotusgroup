@php
    use App\Models\LeadershipSection;

    $sectionTitle = filled($leadershipSection?->section_title)
        ? $leadershipSection->section_title
        : LeadershipSection::DEFAULT_SECTION_TITLE;
    $founderName = trim((string) ($leadershipSection?->founder_name ?? ''));
    $founderPosition = trim((string) ($leadershipSection?->founder_position ?? ''));
    $founderDescription = trim((string) ($leadershipSection?->founder_description ?? ''));
    $founderImageUrl = $leadershipSection?->founderImageUrl();
    $boardMembers = $leadershipSection?->boardMembers() ?? [];
@endphp

@if ($leadershipSection && $leadershipSection->shouldDisplayOnWebsite())
    @once
        @push('styles')
            <style>
                .leadership-section {
                    padding: 48px 28px 28px;
                    background: var(--section-surface);
                }

                .leadership-shell {
                    width: 100%;
                    max-width: 1240px;
                    margin: 0 auto;
                    display: grid;
                    gap: 28px;
                    opacity: 0;
                    transform: translateY(24px);
                    transition:
                        opacity 0.82s cubic-bezier(0.16, 1, 0.3, 1),
                        transform 0.82s cubic-bezier(0.16, 1, 0.3, 1);
                }

                .leadership-section.is-visible .leadership-shell {
                    opacity: 1;
                    transform: none;
                }

                .leadership-head {
                    display: grid;
                    justify-items: center;
                    gap: 10px;
                    text-align: center;
                    opacity: 0;
                    transform: translateY(-38px);
                }

                .leadership-title {
                    margin: 0;
                    font-family: var(--font-primary);
                    font-size: var(--section-title-size);
                    font-weight: 400;
                    line-height: 0.98;
                    color: #000000;
                }

                .leadership-founder {
                    display: grid;
                    grid-template-columns: minmax(220px, 0.52fr) minmax(0, 1.05fr);
                    gap: 28px;
                    align-items: center;
                    padding: 8px 0 0;
                }

                .leadership-founder-copy {
                    opacity: 0;
                    transform: translateX(42px);
                    text-align: center;
                }

                .leadership-founder-name {
                    margin: 0;
                    font-family: var(--font-primary);
                    font-size: clamp(2.1rem, 3.3vw, 3.1rem);
                    font-weight: 400;
                    line-height: 1;
                    color: #000000;
                }

                .leadership-founder-position {
                    margin: 14px 0 0;
                    display: block;
                    font-family: var(--font-primary);
                    font-size: clamp(1rem, 1.45vw, 1.14rem);
                    line-height: 1.7;
                    color: #000000;
                }

                .leadership-founder-description {
                    width: min(100%, 420px);
                    margin: 14px auto 0;
                    font-family: var(--font-primary);
                    font-size: 0.98rem;
                    line-height: 1.75;
                    color: #000000;
                    text-align: justify;
                    text-align-last: center;
                }

                .leadership-founder-frame {
                    position: relative;
                    width: min(100%, 280px);
                    justify-self: start;
                    padding: 14px 14px 16px;
                    border-radius: 32px;
                    border: 1px solid rgba(12, 80, 93, 0.34);
                    background: #ffffff;
                    opacity: 0;
                    transform: translateX(-42px);
                }

                .leadership-founder-frame::before {
                    content: "";
                    position: absolute;
                    inset: 6px;
                    border: 1.5px dashed rgba(12, 80, 93, 0.54);
                    border-radius: 28px;
                    pointer-events: none;
                }

                .leadership-founder-frame img {
                    display: block;
                    width: 100%;
                    aspect-ratio: 4 / 5;
                    object-fit: cover;
                    border-radius: 28px;
                    user-select: none;
                    -webkit-user-drag: none;
                }

                .leadership-founder-placeholder {
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
                    color: rgba(16, 33, 44, 0.48);
                    background:
                        linear-gradient(180deg, rgba(255, 255, 255, 0.32) 0%, rgba(223, 234, 242, 0.72) 100%);
                }

                .leadership-card-placeholder {
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

                .leadership-avatar-icon {
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

                .leadership-avatar-icon svg {
                    width: 24px;
                    height: 24px;
                }

                .leadership-placeholder-copy {
                    display: flex;
                    flex-direction: column;
                    align-items: center;
                    justify-content: center;
                    gap: 2px;
                    text-align: center;
                    line-height: 1.1;
                }

                .leadership-placeholder-brand {
                    font-family: var(--font-secondary);
                    font-size: 0.66rem;
                    font-weight: 700;
                    letter-spacing: 0.14em;
                    text-transform: uppercase;
                    color: rgba(255, 255, 255, 0.88);
                }

                .leadership-placeholder-sub {
                    font-family: var(--font-secondary);
                    font-size: 0.52rem;
                    font-weight: 600;
                    letter-spacing: 0.2em;
                    text-transform: uppercase;
                    color: rgba(255, 255, 255, 0.58);
                }

                @keyframes leadershipAppearUp {
                    from {
                        opacity: 0;
                        transform: translateY(-38px);
                    }

                    to {
                        opacity: 1;
                        transform: translateY(0);
                    }
                }

                @keyframes leadershipAppearLeft {
                    from {
                        opacity: 0;
                        transform: translateX(-42px);
                    }

                    to {
                        opacity: 1;
                        transform: translateX(0);
                    }
                }

                @keyframes leadershipAppearRight {
                    from {
                        opacity: 0;
                        transform: translateX(42px);
                    }

                    to {
                        opacity: 1;
                        transform: translateX(0);
                    }
                }

                @keyframes leadershipAppearDown {
                    from {
                        opacity: 0;
                        transform: translateY(38px);
                    }

                    to {
                        opacity: 1;
                        transform: translateY(0);
                    }
                }

                .leadership-section.is-visible .leadership-head,
                .leadership-section.is-visible .leadership-founder-copy,
                .leadership-section.is-visible .leadership-founder-frame {
                    opacity: 1;
                    transform: none;
                }

                .leadership-section.is-visible .leadership-head {
                    animation: leadershipAppearUp 1s cubic-bezier(0.16, 1, 0.3, 1) both;
                }

                .leadership-section.is-visible .leadership-founder-copy {
                    animation: leadershipAppearRight 1.08s cubic-bezier(0.16, 1, 0.3, 1) 180ms both;
                }

                .leadership-section.is-visible .leadership-founder-frame {
                    animation: leadershipAppearLeft 1.08s cubic-bezier(0.16, 1, 0.3, 1) 120ms both;
                }

                .leadership-showcase {
                    position: relative;
                    width: min(100%, 1240px);
                    margin: 18px auto 0;
                    padding: 12px 0 18px;
                    overflow: hidden;
                    overflow-y: hidden;
                    cursor: grab;
                    touch-action: pan-y;
                    user-select: none;
                    opacity: 0;
                    transform: translateY(38px);
                }

                .leadership-showcase.is-dragging {
                    cursor: grabbing;
                }

                .leadership-track {
                    display: flex;
                    align-items: stretch;
                    gap: 16px;
                    width: max-content;
                    will-change: transform;
                }

                .leadership-section.is-visible .leadership-showcase {
                    animation: leadershipAppearDown 1.14s cubic-bezier(0.16, 1, 0.3, 1) 320ms both;
                }

                .leadership-slide {
                    position: relative;
                    display: flex;
                    align-items: stretch;
                    flex: 0 0 172px;
                    width: 172px;
                    opacity: 1;
                    pointer-events: auto;
                    transform: none;
                    scroll-snap-align: center;
                }

                .leadership-card {
                    display: flex;
                    flex-direction: column;
                    width: 100%;
                    min-height: 100%;
                    color: #000000;
                    position: relative;
                    padding: 10px 10px 12px;
                    border-radius: 20px;
                    border: 1px solid rgba(12, 80, 93, 0.28);
                    background: #ffffff;
                    box-shadow: 0 8px 22px rgba(18, 33, 44, 0.05);
                    transition:
                        transform 0.24s cubic-bezier(0.16, 1, 0.3, 1),
                        box-shadow 0.24s ease,
                        border-color 0.24s ease;
                }

                .leadership-card:hover {
                    transform: translateY(-3px);
                    border-color: rgba(12, 80, 93, 0.54);
                    box-shadow: 0 14px 30px rgba(12, 80, 93, 0.12);
                }

                .leadership-card::before {
                    content: "";
                    position: absolute;
                    inset: 4px;
                    border: 1.2px dashed rgba(12, 80, 93, 0.4);
                    border-radius: 17px;
                    pointer-events: none;
                }

                .leadership-card-visual {
                    position: relative;
                    overflow: hidden;
                    border-radius: 15px;
                    border: 1px solid rgba(255, 255, 255, 0.78);
                    background: linear-gradient(180deg, #3b78a4 0%, #2b6c99 100%);
                    box-shadow: inset 0 1px 0 rgba(255, 255, 255, 0.22);
                }

                .leadership-card-visual img {
                    display: block;
                    width: 100%;
                    aspect-ratio: 4 / 5;
                    object-fit: cover;
                    transition: transform 0.44s cubic-bezier(0.16, 1, 0.3, 1);
                    user-select: none;
                    -webkit-user-drag: none;
                }

                .leadership-card:hover .leadership-card-visual img {
                    transform: scale(1.05);
                }

                .leadership-card-copy {
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

                .leadership-card-name {
                    margin: 0;
                    width: 100%;
                    font-family: var(--font-primary);
                    font-size: 0.92rem;
                    font-weight: 500;
                    line-height: 1.15;
                    color: #000000;
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

                .leadership-card-name.is-name-medium {
                    font-size: 0.82rem;
                    line-height: 1.14;
                }

                .leadership-card-name.is-name-long {
                    font-size: 0.74rem;
                    line-height: 1.12;
                }

                .leadership-card-name.is-name-xlong {
                    font-size: 0.66rem;
                    line-height: 1.1;
                }

                .leadership-card-position {
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
                    color: #000000;
                    overflow-wrap: anywhere;
                }

                @media (max-width: 1080px) {
                    .leadership-founder {
                        grid-template-columns: 1fr;
                    }

                    .leadership-founder-copy {
                        text-align: center;
                    }

                    .leadership-founder-frame {
                        width: min(100%, 240px);
                        margin: 0 auto;
                        justify-self: center;
                    }

                    .leadership-showcase {
                        width: min(100%, 960px);
                    }

                    .leadership-slide {
                        flex-basis: 160px;
                        width: 160px;
                    }

                    .leadership-card {
                        padding: 9px 9px 11px;
                        border-radius: 18px;
                    }

                    .leadership-card::before {
                        inset: 3px;
                        border-radius: 15px;
                    }

                    .leadership-card-visual,
                    .leadership-card-placeholder {
                        border-radius: 14px;
                    }

                    .leadership-card-copy {
                        height: 62px;
                        min-height: 62px;
                        max-height: 62px;
                        padding-top: 6px;
                    }

                    .leadership-card-name {
                        font-size: 0.86rem;
                        max-height: 32px;
                    }

                    .leadership-card-name.is-name-medium {
                        font-size: 0.78rem;
                    }

                    .leadership-card-name.is-name-long {
                        font-size: 0.70rem;
                    }

                    .leadership-card-name.is-name-xlong {
                        font-size: 0.62rem;
                    }

                    .leadership-card-position {
                        font-size: 0.70rem;
                        max-height: 26px;
                    }
                }

                @media (max-width: 768px) {
                    .leadership-section {
                        padding: 34px 18px 24px;
                    }

                    .leadership-founder {
                        gap: 22px;
                        padding: 8px 0 0;
                    }

                    .leadership-title {
                        font-size: var(--section-title-size-mobile);
                    }

                    .leadership-founder-name {
                        font-size: 2rem;
                    }

                    .leadership-founder-position {
                        font-size: 0.95rem;
                    }

                    .leadership-showcase {
                        width: min(100%, 100%);
                        padding: 10px 0 18px;
                    }

                    .leadership-track {
                        gap: 12px;
                    }

                    .leadership-slide {
                        flex-basis: 146px;
                        width: 146px;
                    }

                    .leadership-card {
                        padding: 8px 8px 10px;
                        border-radius: 16px;
                    }

                    .leadership-card::before {
                        inset: 3px;
                        border-radius: 13px;
                    }

                    .leadership-card-visual,
                    .leadership-card-placeholder {
                        border-radius: 13px;
                    }

                    .leadership-card-copy {
                        height: 58px;
                        min-height: 58px;
                        max-height: 58px;
                        padding-top: 5px;
                    }

                    .leadership-card-name {
                        font-size: 0.82rem;
                        max-height: 30px;
                    }

                    .leadership-card-name.is-name-medium {
                        font-size: 0.74rem;
                    }

                    .leadership-card-name.is-name-long {
                        font-size: 0.66rem;
                    }

                    .leadership-card-name.is-name-xlong {
                        font-size: 0.58rem;
                    }

                    .leadership-card-position {
                        font-size: 0.66rem;
                        max-height: 24px;
                    }
                }
            </style>
        @endpush
    @endonce

    <section class="leadership-section" id="leadership">
        <div class="leadership-shell">
            @if (filled($sectionTitle))
                <div class="leadership-head">
                    <h2 class="leadership-title">{{ $sectionTitle }}</h2>
                </div>
            @endif

            @if ($founderName !== '' || $founderPosition !== '' || $founderImageUrl)
                <div class="leadership-founder">
                    <div class="leadership-founder-frame">
                        @if ($founderImageUrl)
                            <img src="{{ $founderImageUrl }}" alt="{{ $founderName !== '' ? $founderName : 'Founder and CEO' }}" loading="lazy" decoding="async" draggable="false">
                        @else
                            <div class="leadership-founder-placeholder">No Image</div>
                        @endif
                    </div>

                    <div class="leadership-founder-copy">
                        @if ($founderName !== '')
                            <h3 class="leadership-founder-name">{{ $founderName }}</h3>
                        @endif
                        @if ($founderPosition !== '')
                            <p class="leadership-founder-position">{{ $founderPosition }}</p>
                        @endif
                        @if ($founderDescription !== '')
                            <p class="leadership-founder-description">{{ $founderDescription }}</p>
                        @endif
                    </div>
                </div>
            @endif

            @if ($boardMembers)
                <div class="leadership-showcase" data-leadership-showcase>
                    <div class="leadership-track" data-leadership-track>
                        @foreach ($boardMembers as $member)
                            @php
                                $mName = trim($member['name'] ?? '');
                                $mNameLen = mb_strlen($mName);
                                $mNameClass = $mNameLen > 34 ? 'is-name-xlong' : ($mNameLen > 22 ? 'is-name-long' : ($mNameLen > 15 ? 'is-name-medium' : ''));
                            @endphp
                            <article class="leadership-slide" data-leadership-slide>
                                <div class="leadership-card">
                                    <div class="leadership-card-visual">
                                        @if (!empty($member['image_url']))
                                            <img src="{{ $member['image_url'] }}" alt="{{ $mName ?: 'Board member' }}" loading="lazy" decoding="async" draggable="false" onerror="this.style.display='none'; if(this.nextElementSibling) this.nextElementSibling.style.display='flex';">
                                            <div class="leadership-card-placeholder" aria-label="Board member avatar" style="display: none;">
                                                <div class="leadership-avatar-icon" aria-hidden="true">
                                                    <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.6" stroke-linecap="round" stroke-linejoin="round">
                                                        <path d="M20 21v-2a4 4 0 0 0-4-4H8a4 4 0 0 0-4 4v2"></path>
                                                        <circle cx="12" cy="7" r="4"></circle>
                                                    </svg>
                                                </div>
                                                <div class="leadership-placeholder-copy">
                                                    <span class="leadership-placeholder-brand">King Lotus</span>
                                                    <span class="leadership-placeholder-sub">International</span>
                                                </div>
                                            </div>
                                        @else
                                            <div class="leadership-card-placeholder" aria-label="Board member avatar">
                                                <div class="leadership-avatar-icon" aria-hidden="true">
                                                    <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.6" stroke-linecap="round" stroke-linejoin="round">
                                                        <path d="M20 21v-2a4 4 0 0 0-4-4H8a4 4 0 0 0-4 4v2"></path>
                                                        <circle cx="12" cy="7" r="4"></circle>
                                                    </svg>
                                                </div>
                                                <div class="leadership-placeholder-copy">
                                                    <span class="leadership-placeholder-brand">King Lotus</span>
                                                    <span class="leadership-placeholder-sub">International</span>
                                                </div>
                                            </div>
                                        @endif
                                    </div>
                                    <div class="leadership-card-copy">
                                        @if (!empty($mName))
                                            <h3 class="leadership-card-name {{ $mNameClass }}">{{ $mName }}</h3>
                                        @endif
                                        @if (!empty($member['position']))
                                            <span class="leadership-card-position">{{ $member['position'] }}</span>
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
                    document.querySelectorAll('[data-leadership-showcase]').forEach((showcase) => {
                        const track = showcase.querySelector('[data-leadership-track]');
                        const slides = Array.from(showcase.querySelectorAll('[data-leadership-slide]'));

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
                                const nameEl = slide.querySelector('.leadership-card-name');
                                if (nameEl) {
                                    nameEl.style.fontSize = '';
                                    nameEl.style.lineHeight = '';

                                    const copyContainer = nameEl.closest('.leadership-card-copy');
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

                            const originalSlides = Array.from(track.querySelectorAll('[data-leadership-slide]'));
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

                            syncCardTypography(Array.from(track.querySelectorAll('[data-leadership-slide]')));

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
