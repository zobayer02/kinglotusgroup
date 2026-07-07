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
                color: #10212c;
                overflow: hidden;
                opacity: 0;
                transform: translateY(-12px);
            }

            .notice-banner-marquee {
                flex: 1;
                overflow: hidden;
            }

            .notice-banner-track {
                display: flex;
                width: max-content;
                will-change: transform;
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
                        @for ($i = 0; $i < 2; $i++)
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
                    const track = shell.querySelector('.notice-banner-track');

                    if (!track) {
                        return;
                    }

                    let currentOffset = 0;
                    let lastFrameTime = 0;
                    let animationFrameId = 0;
                    let normalSpeed = 78;
                    let slowSpeed = 34;
                    let currentSpeed = normalSpeed;
                    let targetSpeed = normalSpeed;
                    let loopWidth = Math.max(track.scrollWidth / 2, 1);

                    const updateLoopWidth = () => {
                        loopWidth = Math.max(track.scrollWidth / 2, 1);
                        currentOffset = ((currentOffset % loopWidth) + loopWidth) % loopWidth;
                    };

                    const animate = (timestamp) => {
                        if (!lastFrameTime) {
                            lastFrameTime = timestamp;
                        }

                        const deltaSeconds = (timestamp - lastFrameTime) / 1000;
                        lastFrameTime = timestamp;

                        currentSpeed += (targetSpeed - currentSpeed) * Math.min(deltaSeconds * 6, 1);
                        currentOffset = (currentOffset + currentSpeed * deltaSeconds) % loopWidth;
                        track.style.transform = `translateX(-${currentOffset}px)`;

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
                    shell.addEventListener('touchstart', enableSlow, { passive: true });
                    shell.addEventListener('touchend', disableSlow, { passive: true });
                    shell.addEventListener('touchcancel', disableSlow, { passive: true });

                    window.addEventListener('resize', updateLoopWidth);

                    window.addEventListener('pagehide', () => {
                        if (animationFrameId) {
                            window.cancelAnimationFrame(animationFrameId);
                        }
                    }, { once: true });
                });
            });
        </script>
    @endpush
@endif
