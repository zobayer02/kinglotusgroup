@if ($notice && filled($notice->message))
    @push('styles')
        <style>
            .notice-banner {
                padding: 0 28px 28px;
                background: var(--section-surface);
            }

            .notice-banner-shell {
                position: relative;
                isolation: isolate;
                max-width: 1240px;
                margin: 0 auto;
                border-radius: 22px;
                overflow: hidden;
                opacity: 0;
                transform: translateY(22px);
            }

            .notice-banner-shell::before,
            .notice-banner-shell::after {
                content: "";
                position: absolute;
                inset: 0;
                border-radius: inherit;
                pointer-events: none;
                opacity: 0;
                transform-origin: center;
                transform: scaleX(0.26);
            }

            .notice-banner-shell::before {
                background:
                    linear-gradient(180deg, rgba(255, 255, 255, 0.42) 0%, rgba(255, 255, 255, 0.14) 22%, rgba(255, 255, 255, 0) 56%);
                z-index: 0;
            }

            .notice-banner-shell::after {
                border: 1px solid rgba(178, 196, 211, 0.62);
                background: linear-gradient(180deg, rgba(244, 249, 253, 0.72) 0%, rgba(228, 238, 246, 0.58) 100%);
                box-shadow:
                    0 16px 42px rgba(34, 61, 86, 0.1),
                    inset 0 1px 0 rgba(255, 255, 255, 0.78);
                backdrop-filter: blur(18px) saturate(150%);
                -webkit-backdrop-filter: blur(18px) saturate(150%);
                z-index: 1;
            }

            .notice-banner-inner {
                position: relative;
                z-index: 2;
                display: flex;
                align-items: center;
                min-height: 64px;
                padding: 0 18px;
                color: #000000;
                overflow: hidden;
                opacity: 0;
                transform: translateY(-12px);
            }

            .notice-banner-marquee {
                flex: 1;
                overflow: hidden;
                cursor: grab;
                user-select: none;
                -webkit-user-select: none;
                touch-action: pan-y;
            }

            .notice-banner-marquee.is-dragging {
                cursor: grabbing;
            }

            .notice-banner-track {
                display: flex;
                width: max-content;
                will-change: transform;
                user-select: none;
                -webkit-user-select: none;
            }

            .notice-banner-group {
                display: flex;
                align-items: center;
                gap: 16px;
                padding-right: 16px;
                white-space: nowrap;
            }

            .notice-banner-text {
                font-size: 1rem;
                font-weight: 600;
                line-height: 1.4;
            }

            .notice-banner-divider {
                width: 1px;
                height: 20px;
                background: rgba(16, 33, 44, 0.24);
                flex: none;
            }

            .notice-banner.is-visible .notice-banner-shell {
                animation: navDropIn 0.6s ease both;
            }

            .notice-banner.is-visible .notice-banner-shell::before,
            .notice-banner.is-visible .notice-banner-shell::after {
                animation: navShellSpread 0.95s cubic-bezier(0.16, 1, 0.3, 1) both;
            }

            .notice-banner.is-visible .notice-banner-inner {
                animation: navDropIn 0.48s ease 120ms both;
            }

            @media (max-width: 768px) {
                .notice-banner {
                    padding: 0 18px 18px;
                }

                .notice-banner-inner {
                    min-height: 56px;
                    padding: 0 14px;
                }

                .notice-banner-text {
                    font-size: 0.9rem;
                }
            }
        </style>
    @endpush

    <section class="notice-banner" aria-label="Website notice">
        <div class="notice-banner-shell" data-notice-banner-shell>
            <div class="notice-banner-inner">
                <div class="notice-banner-marquee">
                    <div class="notice-banner-track">
                        @for ($i = 0; $i < 4; $i++)
                            <div class="notice-banner-group">
                                <span class="notice-banner-text">{{ $notice->message }}</span>
                                <span class="notice-banner-divider"></span>
                            </div>
                        @endfor
                    </div>
                </div>
            </div>
        </div>
    </section>

    @push('scripts')
        <script>
            document.addEventListener('DOMContentLoaded', () => {
                document.querySelectorAll('[data-notice-banner-shell]').forEach((shell) => {
                    const marquee = shell.querySelector('.notice-banner-marquee');
                    const track = shell.querySelector('.notice-banner-track');

                    if (!track || !marquee) {
                        return;
                    }

                    let currentOffset = 0;
                    let lastFrameTime = 0;
                    let animationFrameId = 0;
                    const normalSpeed = 78;
                    const slowSpeed = 34;
                    let currentSpeed = normalSpeed;
                    let targetSpeed = normalSpeed;
                    let loopWidth = 1;

                    const updateLoopWidth = () => {
                        const firstGroup = track.querySelector('.notice-banner-group');
                        if (firstGroup && firstGroup.offsetWidth > 0) {
                            loopWidth = firstGroup.offsetWidth;
                        } else {
                            const totalGroups = track.querySelectorAll('.notice-banner-group').length || 1;
                            loopWidth = Math.max(track.scrollWidth / totalGroups, 1);
                        }
                        currentOffset = ((currentOffset % loopWidth) + loopWidth) % loopWidth;
                    };

                    let isDragging = false;
                    let isMouseDown = false;
                    let startX = 0;
                    let startY = 0;
                    let startOffset = 0;
                    let lastX = 0;
                    let lastTime = 0;
                    let dragVelocity = 0;
                    let inertiaVelocity = 0;
                    let isHorizontalDrag = null;
                    let isPausedAfterDrag = false;
                    let resumeTimer = null;

                    const setOffset = (offset) => {
                        currentOffset = ((offset % loopWidth) + loopWidth) % loopWidth;
                        track.style.transform = `translate3d(-${currentOffset}px, 0, 0)`;
                    };

                    const onTouchStart = (e) => {
                        if (e.touches.length !== 1) return;
                        const touch = e.touches[0];
                        isDragging = true;
                        startX = touch.clientX;
                        startY = touch.clientY;
                        lastX = touch.clientX;
                        lastTime = performance.now();
                        startOffset = currentOffset;
                        dragVelocity = 0;
                        inertiaVelocity = 0;
                        isHorizontalDrag = null;
                        if (resumeTimer) clearTimeout(resumeTimer);
                    };

                    const onTouchMove = (e) => {
                        if (!isDragging || e.touches.length !== 1) return;
                        const touch = e.touches[0];
                        const deltaX = touch.clientX - startX;
                        const deltaY = touch.clientY - startY;

                        if (isHorizontalDrag === null) {
                            const absX = Math.abs(deltaX);
                            const absY = Math.abs(deltaY);
                            if (absX > 4 || absY > 4) {
                                isHorizontalDrag = absX >= absY;
                            }
                        }

                        if (!isHorizontalDrag) {
                            return;
                        }

                        if (e.cancelable) {
                            e.preventDefault();
                        }

                        const now = performance.now();
                        const dt = now - lastTime;
                        if (dt > 10) {
                            dragVelocity = (lastX - touch.clientX) / (dt / 1000);
                            lastX = touch.clientX;
                            lastTime = now;
                        }

                        setOffset(startOffset - deltaX);
                    };

                    const onTouchEnd = () => {
                        if (!isDragging) return;
                        isDragging = false;
                        lastFrameTime = performance.now();

                        if (isHorizontalDrag) {
                            if (Math.abs(dragVelocity) > 80) {
                                inertiaVelocity = Math.max(-1200, Math.min(1200, dragVelocity));
                            }
                            isPausedAfterDrag = true;
                            if (resumeTimer) clearTimeout(resumeTimer);
                            resumeTimer = setTimeout(() => {
                                isPausedAfterDrag = false;
                                lastFrameTime = performance.now();
                            }, 1800);
                        }
                    };

                    marquee.addEventListener('touchstart', onTouchStart, { passive: true });
                    marquee.addEventListener('touchmove', onTouchMove, { passive: false });
                    marquee.addEventListener('touchend', onTouchEnd, { passive: true });
                    marquee.addEventListener('touchcancel', onTouchEnd, { passive: true });

                    // Mouse drag support
                    const onMouseDown = (e) => {
                        if (e.button !== 0) return;
                        isMouseDown = true;
                        startX = e.clientX;
                        lastX = e.clientX;
                        lastTime = performance.now();
                        startOffset = currentOffset;
                        dragVelocity = 0;
                        inertiaVelocity = 0;
                        marquee.classList.add('is-dragging');
                        if (resumeTimer) clearTimeout(resumeTimer);
                    };

                    const onMouseMove = (e) => {
                        if (!isMouseDown) return;
                        e.preventDefault();
                        const deltaX = e.clientX - startX;
                        const now = performance.now();
                        const dt = now - lastTime;
                        if (dt > 10) {
                            dragVelocity = (lastX - e.clientX) / (dt / 1000);
                            lastX = e.clientX;
                            lastTime = now;
                        }
                        setOffset(startOffset - deltaX);
                    };

                    const onMouseUp = () => {
                        if (!isMouseDown) return;
                        isMouseDown = false;
                        marquee.classList.remove('is-dragging');
                        lastFrameTime = performance.now();

                        if (Math.abs(dragVelocity) > 80) {
                            inertiaVelocity = Math.max(-1200, Math.min(1200, dragVelocity));
                        }
                        isPausedAfterDrag = true;
                        if (resumeTimer) clearTimeout(resumeTimer);
                        resumeTimer = setTimeout(() => {
                            isPausedAfterDrag = false;
                            lastFrameTime = performance.now();
                        }, 1800);
                    };

                    marquee.addEventListener('mousedown', onMouseDown);
                    window.addEventListener('mousemove', onMouseMove);
                    window.addEventListener('mouseup', onMouseUp);

                    const animate = (timestamp) => {
                        if (!lastFrameTime) {
                            lastFrameTime = timestamp;
                        }

                        const deltaSeconds = Math.min((timestamp - lastFrameTime) / 1000, 0.1);
                        lastFrameTime = timestamp;

                        if (isDragging || isMouseDown) {
                            // Being dragged by user
                        } else if (Math.abs(inertiaVelocity) > 15) {
                            setOffset(currentOffset + inertiaVelocity * deltaSeconds);
                            inertiaVelocity *= Math.pow(0.88, deltaSeconds * 60);
                        } else if (isPausedAfterDrag) {
                            // Paused after drag so user can easily read
                        } else {
                            currentSpeed += (targetSpeed - currentSpeed) * Math.min(deltaSeconds * 6, 1);
                            setOffset(currentOffset + currentSpeed * deltaSeconds);
                        }

                        animationFrameId = window.requestAnimationFrame(animate);
                    };

                    const enableSlow = () => {
                        targetSpeed = slowSpeed;
                    };

                    const disableSlow = () => {
                        targetSpeed = normalSpeed;
                    };

                    updateLoopWidth();
                    animationFrameId = window.requestAnimationFrame(animate);

                    shell.addEventListener('pointerenter', enableSlow);
                    shell.addEventListener('pointerleave', disableSlow);

                    window.addEventListener('resize', updateLoopWidth);

                    window.addEventListener('pagehide', () => {
                        if (animationFrameId) {
                            window.cancelAnimationFrame(animationFrameId);
                        }
                        if (resumeTimer) {
                            clearTimeout(resumeTimer);
                        }
                        window.removeEventListener('mousemove', onMouseMove);
                        window.removeEventListener('mouseup', onMouseUp);
                    }, { once: true });
                });
            });
        </script>
    @endpush
@endif
