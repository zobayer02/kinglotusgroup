@once
    @push('styles')
        <style>
            .projects-section {
                padding: 18px 28px 56px;
                background: var(--section-surface);
            }

            .projects-shell {
                width: 100%;
                max-width: 1280px;
                margin: 0 auto;
                display: grid;
                gap: 52px;
                opacity: 0;
                transform: translateY(22px);
                transition: opacity 0.68s cubic-bezier(0.16, 1, 0.3, 1), transform 0.68s cubic-bezier(0.16, 1, 0.3, 1);
            }

            .projects-section.is-visible .projects-shell {
                opacity: 1;
                transform: none;
            }

            .projects-block {
                display: grid;
                gap: 26px;
            }

            .projects-shell > .projects-block:nth-child(2) {
                gap: 4px;
            }

            .projects-block-head {
                display: flex;
                align-items: end;
                justify-content: center;
                gap: 22px;
            }

            .projects-heading {
                margin: 0;
                max-width: 100%;
                font-family: var(--font-primary);
                font-weight: 400;
                line-height: 1.02;
                color: #121926;
                text-align: center;
            }

            .projects-heading-title,
            .projects-heading-subtitle {
                display: block;
            }

            .projects-heading-title {
                font-size: var(--section-title-size);
                white-space: nowrap;
            }

            .projects-heading-subtitle {
                margin-top: 8px;
                font-size: clamp(1.18rem, 1.8vw, 1.7rem);
                font-weight: 400;
                line-height: 1.08;
            }

            .projects-cta {
                display: inline-flex;
                align-items: center;
                justify-content: center;
                min-height: 48px;
                padding: 0 18px;
                border-radius: 14px;
                background: #191c22;
                color: #ffffff;
                font-family: var(--font-secondary);
                font-size: 0.96rem;
                font-weight: 600;
                transition: transform 0.22s ease, background-color 0.22s ease, box-shadow 0.22s ease;
            }

            .projects-cta:hover {
                transform: translateY(-1px);
                background: #0c505d;
                box-shadow: 0 16px 28px rgba(12, 80, 93, 0.18);
            }

            .projects-top-layout {
                display: grid;
                grid-template-columns: repeat(3, minmax(0, 1fr));
                gap: 20px;
                align-items: start;
                max-width: 1020px;
                margin: 0 auto;
            }

            .project-card {
                position: relative;
                display: block;
                overflow: hidden;
                border-radius: 22px;
                color: #ffffff;
                background:
                    linear-gradient(180deg, rgba(255, 255, 255, 0.08) 0%, rgba(255, 255, 255, 0.02) 100%),
                    linear-gradient(180deg, #d3dbe4 0%, #bcc7d3 100%);
                box-shadow: 0 18px 34px rgba(22, 28, 35, 0.12);
                isolation: isolate;
                transition: transform 0.28s ease, box-shadow 0.28s ease;
            }

            .project-card::before {
                content: "";
                position: absolute;
                inset: 0;
                background: linear-gradient(180deg, rgba(10, 17, 27, 0.06) 0%, rgba(10, 17, 27, 0.2) 46%, rgba(10, 17, 27, 0.74) 100%);
                z-index: 1;
            }

            .project-card img {
                width: 100%;
                height: 100%;
                object-fit: cover;
                display: block;
                transition: transform 0.7s cubic-bezier(0.16, 1, 0.3, 1), filter 0.36s ease;
            }

            .project-card:hover img {
                transform: scale(1.06);
            }

            .project-card--top {
                width: 100%;
                max-width: none;
                border-radius: 24px;
                background: transparent;
                box-shadow: none;
                color: #101724;
                overflow: visible;
            }

            .project-card--top:hover {
                transform: translateY(-4px);
                box-shadow: none;
            }

            .project-card--top::before {
                display: none;
            }

            .project-card--top img {
                height: auto;
                border-radius: 24px;
            }

            .project-card--top .project-card-body {
                position: static;
                padding: 16px 4px 0;
                gap: 10px;
                align-items: flex-start;
                justify-content: center;
                text-align: center;
            }

            .project-card--top .project-copy {
                width: 100%;
            }

            .project-card--top .project-name {
                color: #121926;
                font-size: clamp(1.08rem, 1.25vw, 1.34rem);
                font-weight: 600;
                line-height: 1.08;
            }

            .project-card--top .project-location {
                margin-top: 6px;
                color: rgba(18, 25, 38, 0.72);
                font-size: 0.94rem;
            }

            .project-card--top .project-location::before {
                color: #121926;
            }

            .project-card--top .project-arrow {
                display: none;
            }

            .project-card--feature,
            .project-card--tall {
                max-width: 285px;
                justify-self: center;
                margin-top: 68px;
            }

            .project-card--feature img,
            .project-card--tall img {
                aspect-ratio: auto;
            }

            .project-card--compact {
                max-width: 345px;
                justify-self: center;
            }

            .project-card--compact img {
                aspect-ratio: auto;
            }

            .project-card--compact .project-name {
                font-size: clamp(1.28rem, 1.58vw, 1.64rem);
            }

            .project-card-body {
                position: absolute;
                right: 14px;
                bottom: 14px;
                left: 14px;
                z-index: 2;
                display: flex;
                align-items: end;
                justify-content: space-between;
                gap: 12px;
            }

            .project-copy {
                min-width: 0;
            }

            .project-name {
                margin: 0;
                font-family: var(--font-secondary);
                font-size: clamp(1rem, 1.55vw, 1.12rem);
                font-weight: 500;
                line-height: 1.08;
                color: #ffffff;
            }

            .project-location {
                display: inline-flex;
                align-items: center;
                gap: 6px;
                margin-top: 4px;
                font-family: var(--font-secondary);
                font-size: 0.92rem;
                color: rgba(255, 255, 255, 0.82);
            }

            .project-location::before {
                content: "\25CE";
                font-size: 0.7rem;
            }

            .project-arrow {
                flex: none;
                display: inline-flex;
                align-items: center;
                justify-content: center;
                width: 34px;
                height: 34px;
                border-radius: 999px;
                background: rgba(255, 255, 255, 0.96);
                color: #111925;
                box-shadow: 0 10px 22px rgba(9, 17, 27, 0.22);
                transform: translateY(10px);
                opacity: 0;
                transition: transform 0.28s ease, opacity 0.28s ease;
            }

            .project-card:hover .project-arrow {
                transform: translateY(0);
                opacity: 1;
            }

            .project-arrow svg {
                width: 14px;
                height: 14px;
            }

            .projects-bottom-layout {
                position: relative;
                width: min(100%, 1180px);
                max-width: none;
                height: 350px;
                margin: 0 auto;
                padding-top: 0;
                overflow: hidden;
                touch-action: pan-y;
                user-select: none;
            }

            .projects-bottom-layout.is-dragging {
                cursor: grabbing;
            }

            .projects-bottom-layout.is-animating .project-showcase-slide,
            .projects-bottom-layout.is-dragging .project-showcase-slide {
                transition: none;
            }

            .project-showcase-control {
                position: absolute;
                top: 62%;
                z-index: 8;
                display: inline-flex;
                align-items: center;
                justify-content: center;
                width: 40px;
                height: 40px;
                border: 1px solid rgba(255, 255, 255, 0.7);
                border-radius: 999px;
                background:
                    linear-gradient(145deg, rgba(255, 255, 255, 0.78), rgba(255, 255, 255, 0.28));
                color: #12202c;
                box-shadow:
                    inset 0 1px 0 rgba(255, 255, 255, 0.84),
                    0 14px 28px rgba(24, 43, 56, 0.12);
                backdrop-filter: blur(14px);
                -webkit-backdrop-filter: blur(14px);
                cursor: pointer;
                transform: translateY(-50%);
                transition: transform 0.22s ease, background-color 0.22s ease, color 0.22s ease;
            }

            .project-showcase-control:hover {
                transform: translateY(-50%) scale(1.04);
                background: #0c505d;
                color: #ffffff;
            }

            .project-showcase-control--prev {
                left: 0;
            }

            .project-showcase-control--next {
                right: 0;
            }

            .project-showcase-control svg {
                width: 16px;
                height: 16px;
            }

            .project-showcase-slide {
                position: absolute;
                top: 50%;
                left: 50%;
                width: 198px;
                opacity: 0;
                pointer-events: none;
                transform: translate(-50%, -50%);
                transition:
                    transform 0.9s cubic-bezier(0.22, 1, 0.36, 1),
                    opacity 0.8s ease,
                    filter 0.8s ease;
            }

            .project-showcase-slide.is-hidden {
                opacity: 0;
                filter: blur(1px);
                transform: translate(calc(-50% + 610px), -22%) rotate(16deg);
            }

            .project-showcase-slide.is-hidden-left {
                opacity: 0;
                filter: blur(1px);
                transform: translate(calc(-50% - 610px), -22%) rotate(-16deg);
            }

            .project-showcase-slide.is-hidden-right {
                opacity: 0;
                filter: blur(1px);
                transform: translate(calc(-50% + 610px), -22%) rotate(16deg);
            }

            .project-showcase-slide.is-far-left {
                opacity: 1;
                z-index: 1;
                pointer-events: auto;
                transform: translate(calc(-50% - 454px), -27%) rotate(-10deg);
            }

            .project-showcase-slide.is-left {
                opacity: 1;
                z-index: 2;
                pointer-events: auto;
                transform: translate(calc(-50% - 227px), -40%) rotate(-5deg);
            }

            .project-showcase-slide.is-center {
                opacity: 1;
                z-index: 4;
                pointer-events: auto;
                transform: translate(-50%, -44%) rotate(0deg);
            }

            .project-showcase-slide.is-right {
                opacity: 1;
                z-index: 2;
                pointer-events: auto;
                transform: translate(calc(-50% + 227px), -40%) rotate(5deg);
            }

            .project-showcase-slide.is-far-right {
                opacity: 1;
                z-index: 1;
                pointer-events: auto;
                transform: translate(calc(-50% + 454px), -27%) rotate(10deg);
            }

            .project-showcase-link {
                display: block;
                color: #1b2430;
            }

            .project-showcase-visual {
                position: relative;
                overflow: hidden;
                border-radius: 10px;
                background: transparent;
            }

            .project-showcase-image {
                display: block;
                width: 100%;
                aspect-ratio: 1;
                object-fit: cover;
                border-radius: 10px;
                transition: transform 0.58s cubic-bezier(0.16, 1, 0.3, 1);
            }

            .project-showcase-link:hover .project-showcase-image {
                transform: scale(1.04);
            }

            .project-showcase-copy {
                padding-top: 7px;
                text-align: center;
            }

            .project-showcase-title {
                margin: 0;
                color: #111827;
                font-family: var(--font-secondary);
                font-size: 0.72rem;
                font-weight: 700;
                line-height: 1.12;
            }

            .projects-bottom-dots {
                display: flex;
                justify-content: center;
                gap: 6px;
                margin-top: -18px;
            }

            .projects-bottom-dot {
                width: 5px;
                height: 5px;
                border-radius: 999px;
                background: rgba(18, 25, 38, 0.18);
                transition: transform 0.22s ease, background-color 0.22s ease;
            }

            .projects-bottom-dot.is-active {
                background: #1db7d6;
                transform: scale(1.15);
            }

            .projects-section.is-visible .projects-block:nth-child(1) .projects-block-head {
                animation: navDropIn 0.58s ease both;
            }

            .projects-section.is-visible .projects-top-layout {
                animation: navDropIn 0.68s ease 120ms both;
            }

            .projects-section.is-visible .projects-block:nth-child(2) .projects-block-head {
                animation: navDropIn 0.58s ease 220ms both;
            }

            .projects-section.is-visible .projects-bottom-layout {
                animation: navDropIn 0.68s ease 320ms both;
            }

            @media (max-width: 1080px) {
                .projects-top-layout {
                    grid-template-columns: repeat(2, minmax(0, 1fr));
                    gap: 22px;
                    max-width: 860px;
                }

                .project-card--compact {
                    grid-column: 1 / -1;
                    max-width: 440px;
                    justify-self: center;
                }

                .project-card--feature,
                .project-card--tall {
                    max-width: 100%;
                    margin-top: 0;
                }

                .project-card--top img {
                    aspect-ratio: auto;
                }

                .project-card--top .project-name {
                    font-size: clamp(1.08rem, 2vw, 1.28rem);
                }

                .projects-bottom-layout {
                    width: min(100%, 860px);
                    height: 310px;
                    margin-top: 0;
                }

                .project-showcase-slide {
                    width: 148px;
                }

                .project-showcase-slide.is-far-left {
                    transform: translate(calc(-50% - 340px), -27%) rotate(-10deg);
                }

                .project-showcase-slide.is-left {
                    transform: translate(calc(-50% - 170px), -40%) rotate(-5deg);
                }

                .project-showcase-slide.is-center {
                    transform: translate(-50%, -44%) rotate(0deg);
                }

                .project-showcase-slide.is-right {
                    transform: translate(calc(-50% + 170px), -40%) rotate(5deg);
                }

                .project-showcase-slide.is-far-right {
                    transform: translate(calc(-50% + 340px), -27%) rotate(10deg);
                }

                .project-showcase-slide.is-hidden-left {
                    transform: translate(calc(-50% - 500px), -24%) rotate(-15deg);
                }

                .project-showcase-slide.is-hidden,
                .project-showcase-slide.is-hidden-right {
                    transform: translate(calc(-50% + 500px), -24%) rotate(15deg);
                }
            }

            @media (max-width: 768px) {
                .projects-section {
                    padding: 18px 18px 44px;
                }

                .projects-shell {
                    gap: 34px;
                }

                .projects-block {
                    gap: 18px;
                }

                .projects-shell > .projects-block:nth-child(2) {
                    gap: 2px;
                }

                .projects-block-head {
                    align-items: start;
                    flex-direction: column;
                }

                .projects-heading {
                    max-width: 100%;
                }

                .projects-heading-title {
                    font-size: var(--section-title-size-mobile);
                    white-space: normal;
                }

                .projects-bottom-layout {
                    width: min(100%, 420px);
                    height: 320px;
                    margin-top: 0;
                }

                .projects-top-layout {
                    grid-template-columns: 1fr;
                    max-width: 420px;
                }

                .project-card--top {
                    border-radius: 24px;
                    max-width: 100%;
                }

                .project-card--top img {
                    border-radius: 24px;
                    aspect-ratio: auto;
                }

                .project-card--feature,
                .project-card--tall,
                .project-card--compact {
                    margin-top: 0;
                }

                .project-card--top .project-card-body {
                    padding-top: 12px;
                }

                .project-card--top .project-name {
                    font-size: 1.16rem;
                }

                .project-showcase-slide {
                    width: min(78vw, 260px);
                }

                .project-showcase-slide.is-far-left,
                .project-showcase-slide.is-left,
                .project-showcase-slide.is-right,
                .project-showcase-slide.is-far-right,
                .project-showcase-slide.is-hidden-left,
                .project-showcase-slide.is-hidden,
                .project-showcase-slide.is-hidden-right {
                    opacity: 0;
                    pointer-events: none;
                }

                .project-showcase-slide.is-far-left {
                    transform: translate(calc(-50% - 150px), -38%) rotate(-9deg);
                }

                .project-showcase-slide.is-left {
                    transform: translate(calc(-50% - 75px), -50%) rotate(-4deg);
                }

                .project-showcase-slide.is-right {
                    transform: translate(calc(-50% + 75px), -50%) rotate(4deg);
                }

                .project-showcase-slide.is-far-right {
                    transform: translate(calc(-50% + 150px), -38%) rotate(9deg);
                }

                .project-showcase-slide.is-center {
                    opacity: 1;
                    pointer-events: auto;
                    transform: translate(-50%, -45%) rotate(0deg);
                }

                .project-showcase-slide.is-hidden-left {
                    transform: translate(calc(-50% - 250px), -26%) rotate(-14deg);
                }

                .project-showcase-slide.is-hidden,
                .project-showcase-slide.is-hidden-right {
                    transform: translate(calc(-50% + 250px), -26%) rotate(14deg);
                }

                .project-showcase-copy {
                    padding-top: 10px;
                }

                .project-showcase-title {
                    font-size: 0.64rem;
                }

                .projects-bottom-dots {
                    margin-top: 8px;
                }

                .project-showcase-control {
                    width: 34px;
                    height: 34px;
                }

                .project-showcase-control--prev {
                    left: 0;
                }

                .project-showcase-control--next {
                    right: 0;
                }
            }
        </style>
    @endpush
@endonce

@php
    $projectsTopCards = $projectSection->topCards();
    $projectsBottomCards = $projectSection->bottomCards();
    $projectsCtaLabel = filled($projectSection->top_button_label) ? $projectSection->top_button_label : 'Explore All';
    $projectsHeadingParts = preg_split('/\s+[—-]\s+/u', (string) $projectSection->top_title, 2);
    $projectsTitle = trim((string) ($projectsHeadingParts[0] ?? ''));
    $projectsSubtitle = trim((string) ($projectsHeadingParts[1] ?? ''));
@endphp

<section class="projects-section" id="projects" data-nav-section="projects">
    <div class="projects-shell">
        <div class="projects-block">
            <div class="projects-block-head">
                <h2 class="projects-heading">
                    <span class="projects-heading-title">{{ $projectsTitle }}</span>
                    @if (filled($projectsSubtitle))
                        <span class="projects-heading-subtitle">{{ $projectsSubtitle }}</span>
                    @endif
                </h2>
            </div>

            @if ($projectsTopCards)
                <div class="projects-top-layout">
                    @foreach (array_slice($projectsTopCards, 0, 3) as $card)
                        @php
                            $topVariantClass = match ($loop->index) {
                                0 => 'project-card--feature',
                                1 => 'project-card--compact',
                                default => 'project-card--tall',
                            };
                        @endphp
                        @include('partials.project-card', ['card' => $card, 'cardClass' => "project-card project-card--top {$topVariantClass}"])
                    @endforeach
                </div>
            @endif
        </div>

        <div class="projects-block">
            <div class="projects-block-head">
                <h2 class="projects-heading">
                    <span class="projects-heading-title">{{ $projectSection->bottom_title }}</span>
                </h2>
            </div>

            @if ($projectsBottomCards)
                <div class="projects-bottom-layout" data-project-showcase>
                    <button class="project-showcase-control project-showcase-control--prev" type="button" data-project-showcase-prev aria-label="Previous project">
                        <svg viewBox="0 0 24 24" fill="none" aria-hidden="true">
                            <path d="M15 18L9 12L15 6" stroke="currentColor" stroke-width="2.4" stroke-linecap="round" stroke-linejoin="round"/>
                        </svg>
                    </button>

                    @foreach ($projectsBottomCards as $card)
                        <div class="project-showcase-slide" data-project-showcase-slide>
                            <div class="project-showcase-link">
                                <div class="project-showcase-visual">
                                    @if (!empty($card['image_url']))
                                        <img class="project-showcase-image" src="{{ $card['image_url'] }}" alt="{{ $card['title'] ?: 'Project image' }}" loading="lazy" decoding="async">
                                    @endif
                                </div>
                                <div class="project-showcase-copy">
                                    @if (filled($card['title'] ?? null))
                                        <p class="project-showcase-title">{{ $card['title'] }}</p>
                                    @endif
                                </div>
                            </div>
                        </div>
                    @endforeach

                    <button class="project-showcase-control project-showcase-control--next" type="button" data-project-showcase-next aria-label="Next project">
                        <svg viewBox="0 0 24 24" fill="none" aria-hidden="true">
                            <path d="M9 18L15 12L9 6" stroke="currentColor" stroke-width="2.4" stroke-linecap="round" stroke-linejoin="round"/>
                        </svg>
                    </button>
                </div>
                <div class="projects-bottom-dots" data-project-showcase-dots></div>
            @endif
        </div>
    </div>
</section>

@once
    @push('scripts')
        <script>
            document.addEventListener('DOMContentLoaded', () => {
                document.querySelectorAll('[data-project-showcase]').forEach((showcase) => {
                    const slides = Array.from(showcase.querySelectorAll('[data-project-showcase-slide]'));
                    const dotsRoot = showcase.parentElement.querySelector('[data-project-showcase-dots]');
                    const previousButton = showcase.querySelector('[data-project-showcase-prev]');
                    const nextButton = showcase.querySelector('[data-project-showcase-next]');

                    if (!slides.length) {
                        return;
                    }

                    let activeIndex = Math.min(2, slides.length - 1);
                    let currentPosition = activeIndex;
                    let targetPosition = currentPosition;
                    let intervalId = null;
                    let isPaused = false;
                    let dragPointerId = null;
                    let dragStartX = 0;
                    let dragStartPosition = 0;
                    let dragCurrentX = 0;
                    let dragMoved = false;
                    let settleFrameId = null;
                    let suppressClick = false;
                    let clickSuppressTimeoutId = null;

                    const shouldStayPaused = () => {
                        return isPaused || showcase.matches(':hover') || showcase.contains(document.activeElement);
                    };

                    const clearClickSuppression = () => {
                        if (clickSuppressTimeoutId) {
                            window.clearTimeout(clickSuppressTimeoutId);
                            clickSuppressTimeoutId = null;
                        }

                        suppressClick = false;
                    };

                    const suppressNextClick = () => {
                        suppressClick = true;

                        if (clickSuppressTimeoutId) {
                            window.clearTimeout(clickSuppressTimeoutId);
                        }

                        clickSuppressTimeoutId = window.setTimeout(() => {
                            suppressClick = false;
                            clickSuppressTimeoutId = null;
                        }, 220);
                    };

                    const getDragThreshold = () => (window.innerWidth <= 768 ? 12 : 26);
                    const getPixelsPerStep = () => {
                        if (window.innerWidth <= 768) {
                            return 125;
                        }

                        if (window.innerWidth <= 1080) {
                            return 170;
                        }

                        return 220;
                    };

                    const getNearestIndex = (value) => {
                        const total = slides.length;

                        if (total === 0) {
                            return 0;
                        }

                        return ((Math.round(value) % total) + total) % total;
                    };

                    const normalizePosition = (value) => {
                        const total = slides.length;

                        if (total === 0) {
                            return 0;
                        }

                        return ((value % total) + total) % total;
                    };

                    const getShortestDeltaToIndex = (fromPosition, targetIndex) => {
                        const total = slides.length;
                        let delta = targetIndex - normalizePosition(fromPosition);

                        if (delta > total / 2) {
                            delta -= total;
                        } else if (delta < -total / 2) {
                            delta += total;
                        }

                        return delta;
                    };

                    const getWrappedDifference = (index, position) => {
                        const total = slides.length;
                        let diff = index - position;

                        if (diff > total / 2) {
                            diff -= total;
                        } else if (diff < -total / 2) {
                            diff += total;
                        }

                        return diff;
                    };

                    const getMotionAnchors = () => {
                        if (window.innerWidth <= 768) {
                            return [
                                { diff: -3, x: -320, y: -26, rotate: -10, opacity: 0, blur: 1, z: 0, interactive: false },
                                { diff: -2, x: -260, y: -30, rotate: -8, opacity: 0, blur: 0.9, z: 0, interactive: false },
                                { diff: -1, x: -185, y: -38, rotate: -4, opacity: 0, blur: 0.55, z: 1, interactive: false },
                                { diff: 0, x: 0, y: -45, rotate: 0, opacity: 1, blur: 0, z: 4, interactive: true },
                                { diff: 1, x: 185, y: -38, rotate: 4, opacity: 0, blur: 0.55, z: 1, interactive: false },
                                { diff: 2, x: 260, y: -30, rotate: 8, opacity: 0, blur: 0.9, z: 0, interactive: false },
                                { diff: 3, x: 320, y: -26, rotate: 10, opacity: 0, blur: 1, z: 0, interactive: false },
                            ];
                        }

                        if (window.innerWidth <= 1080) {
                            return [
                                { diff: -3, x: -500, y: -24, rotate: -15, opacity: 0, blur: 1, z: 0, interactive: false },
                                { diff: -2, x: -340, y: -27, rotate: -10, opacity: 1, blur: 0.2, z: 1, interactive: true },
                                { diff: -1, x: -170, y: -40, rotate: -5, opacity: 1, blur: 0, z: 2, interactive: true },
                                { diff: 0, x: 0, y: -44, rotate: 0, opacity: 1, blur: 0, z: 4, interactive: true },
                                { diff: 1, x: 170, y: -40, rotate: 5, opacity: 1, blur: 0, z: 2, interactive: true },
                                { diff: 2, x: 340, y: -27, rotate: 10, opacity: 1, blur: 0.2, z: 1, interactive: true },
                                { diff: 3, x: 500, y: -24, rotate: 15, opacity: 0, blur: 1, z: 0, interactive: false },
                            ];
                        }

                        return [
                            { diff: -3, x: -610, y: -22, rotate: -16, opacity: 0, blur: 1, z: 0, interactive: false },
                            { diff: -2, x: -454, y: -27, rotate: -10, opacity: 1, blur: 0.2, z: 1, interactive: true },
                            { diff: -1, x: -227, y: -40, rotate: -5, opacity: 1, blur: 0, z: 2, interactive: true },
                            { diff: 0, x: 0, y: -44, rotate: 0, opacity: 1, blur: 0, z: 4, interactive: true },
                            { diff: 1, x: 227, y: -40, rotate: 5, opacity: 1, blur: 0, z: 2, interactive: true },
                            { diff: 2, x: 454, y: -27, rotate: 10, opacity: 1, blur: 0.2, z: 1, interactive: true },
                            { diff: 3, x: 610, y: -22, rotate: 16, opacity: 0, blur: 1, z: 0, interactive: false },
                        ];
                    };

                    const interpolate = (from, to, progress) => from + ((to - from) * progress);

                    const getInterpolatedState = (diff) => {
                        const anchors = getMotionAnchors();

                        if (diff <= anchors[0].diff) {
                            return anchors[0];
                        }

                        if (diff >= anchors[anchors.length - 1].diff) {
                            return anchors[anchors.length - 1];
                        }

                        for (let index = 0; index < anchors.length - 1; index += 1) {
                            const left = anchors[index];
                            const right = anchors[index + 1];

                            if (diff >= left.diff && diff <= right.diff) {
                                const progress = (diff - left.diff) / (right.diff - left.diff);

                                return {
                                    x: interpolate(left.x, right.x, progress),
                                    y: interpolate(left.y, right.y, progress),
                                    rotate: interpolate(left.rotate, right.rotate, progress),
                                    opacity: interpolate(left.opacity, right.opacity, progress),
                                    blur: interpolate(left.blur, right.blur, progress),
                                    z: interpolate(left.z, right.z, progress),
                                    interactive: progress < 0.5 ? left.interactive : right.interactive,
                                };
                            }
                        }

                        return anchors[anchors.length - 1];
                    };

                    const dots = slides.map((_, index) => {
                        if (!dotsRoot) {
                            return null;
                        }

                        const dot = document.createElement('span');
                        dot.className = 'projects-bottom-dot';
                        dot.addEventListener('click', () => {
                            animateToIndex(index);
                            restart();
                        });
                        dotsRoot.appendChild(dot);
                        return dot;
                    });

                    const render = () => {
                        const motionPosition = normalizePosition(currentPosition);

                        slides.forEach((slide, index) => {
                            const diff = getWrappedDifference(index, motionPosition);
                            const state = getInterpolatedState(diff);
                            slide.className = 'project-showcase-slide';
                            slide.style.transform = `translate(calc(-50% + ${state.x}px), ${state.y}%) rotate(${state.rotate}deg)`;
                            slide.style.opacity = `${state.opacity}`;
                            slide.style.filter = `blur(${state.blur}px)`;
                            slide.style.zIndex = `${Math.round(state.z)}`;
                            slide.style.pointerEvents = state.interactive ? 'auto' : 'none';
                        });

                        activeIndex = getNearestIndex(motionPosition);

                        dots.forEach((dot, index) => {
                            if (!dot) {
                                return;
                            }

                            dot.classList.toggle('is-active', index === activeIndex);
                        });
                    };

                    const stopSettleAnimation = () => {
                        if (settleFrameId) {
                            window.cancelAnimationFrame(settleFrameId);
                            settleFrameId = null;
                        }

                        showcase.classList.remove('is-animating');
                    };

                    const startSettleAnimation = () => {
                        stopSettleAnimation();
                        showcase.classList.add('is-animating');

                        const tick = () => {
                            const delta = targetPosition - currentPosition;

                            if (Math.abs(delta) < 0.001) {
                                currentPosition = targetPosition;
                                render();
                                showcase.classList.remove('is-animating');
                                settleFrameId = null;
                                return;
                            }

                            currentPosition += delta * 0.16;
                            render();
                            settleFrameId = window.requestAnimationFrame(tick);
                        };

                        settleFrameId = window.requestAnimationFrame(tick);
                    };

                    const animateToIndex = (index) => {
                        targetPosition = currentPosition + getShortestDeltaToIndex(currentPosition, index);
                        startSettleAnimation();
                    };

                    const animateBy = (direction) => {
                        targetPosition = Math.round(currentPosition) + direction;
                        startSettleAnimation();
                    };

                    const endDrag = (shouldCommit) => {
                        if (dragPointerId === null) {
                            return;
                        }

                        const deltaX = dragCurrentX - dragStartX;
                        const exceededThreshold = Math.abs(deltaX) >= getDragThreshold();

                        if (showcase.hasPointerCapture?.(dragPointerId)) {
                            showcase.releasePointerCapture(dragPointerId);
                        }

                        showcase.classList.remove('is-dragging');
                        dragPointerId = null;
                        dragStartX = 0;
                        dragStartPosition = 0;
                        dragCurrentX = 0;

                        if (shouldCommit && (exceededThreshold || dragMoved)) {
                            suppressNextClick();
                        } else {
                            clearClickSuppression();
                        }

                        targetPosition = Math.round(currentPosition);
                        dragMoved = false;

                        startSettleAnimation();
                        resume();
                        restart();
                    };

                    const handlePointerDown = (event) => {
                        if (event.button !== undefined && event.button !== 0) {
                            return;
                        }

                        if (event.target.closest('[data-project-showcase-prev], [data-project-showcase-next]')) {
                            return;
                        }

                        dragPointerId = event.pointerId;
                        dragStartX = event.clientX;
                        dragStartPosition = currentPosition;
                        dragCurrentX = event.clientX;
                        dragMoved = false;
                        stopSettleAnimation();
                        showcase.classList.add('is-dragging');
                        pause();
                        showcase.setPointerCapture?.(event.pointerId);
                    };

                    const handlePointerMove = (event) => {
                        if (dragPointerId !== event.pointerId) {
                            return;
                        }

                        dragCurrentX = event.clientX;

                        const deltaX = dragCurrentX - dragStartX;

                        if (Math.abs(deltaX) > 6) {
                            event.preventDefault();
                        }

                        if (Math.abs(deltaX) < 3) {
                            return;
                        }

                        dragMoved = true;
                        currentPosition = dragStartPosition - (deltaX / getPixelsPerStep());
                        render();
                    };

                    const start = () => {
                        if (slides.length < 2 || intervalId) {
                            return;
                        }

                        intervalId = window.setInterval(() => {
                            if (shouldStayPaused()) {
                                return;
                            }

                            animateBy(-1);
                        }, 3000);
                    };

                    const stop = () => {
                        if (intervalId) {
                            window.clearInterval(intervalId);
                            intervalId = null;
                        }
                    };

                    const restart = () => {
                        stop();
                        if (!isPaused) {
                            start();
                        }
                    };

                    const move = (direction) => {
                        animateBy(direction);
                        restart();
                    };

                    const pause = () => {
                        isPaused = true;
                        stop();
                    };

                    const resume = () => {
                        isPaused = false;
                        start();
                    };

                    showcase.addEventListener('mouseenter', pause);
                    showcase.addEventListener('mouseleave', resume);
                    showcase.addEventListener('pointerenter', pause);
                    showcase.addEventListener('pointerleave', resume);
                    showcase.addEventListener('focusin', pause);
                    showcase.addEventListener('focusout', resume);
                    showcase.addEventListener('pointerdown', handlePointerDown);
                    showcase.addEventListener('pointermove', handlePointerMove);
                    showcase.addEventListener('pointerup', () => endDrag(true));
                    showcase.addEventListener('pointercancel', () => endDrag(false));
                    showcase.addEventListener('lostpointercapture', () => endDrag(false));
                    showcase.addEventListener('dragstart', (event) => event.preventDefault());
                    showcase.addEventListener('click', (event) => {
                        if (!suppressClick) {
                            return;
                        }

                        event.preventDefault();
                        event.stopPropagation();
                        clearClickSuppression();
                    }, true);

                    previousButton?.addEventListener('click', () => move(-1));
                    nextButton?.addEventListener('click', () => move(1));

                    slides.forEach((slide, index) => {
                        slide.addEventListener('mouseenter', pause);
                        slide.addEventListener('mouseleave', resume);
                        slide.addEventListener('pointerenter', pause);
                        slide.addEventListener('pointerleave', resume);

                        slide.addEventListener('click', (event) => {
                            event.preventDefault();
                            event.stopPropagation();
                        });
                    });

                    render();
                    start();
                });
            });
        </script>
    @endpush
@endonce
