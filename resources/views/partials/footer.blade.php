@php
    $youtubeUrl = $footerSetting?->youtube_url;
    $facebookUrl = $footerSetting?->facebook_url;
    $emailHref = $footerSetting?->emailHref();
    $phoneOptions = $footerSetting?->phoneOptions() ?? [];
    $hasFloatingLinks = $youtubeUrl || $facebookUrl || $emailHref || count($phoneOptions);
@endphp

<footer class="site-footer" id="book">
    <div class="footer-shell">
        <div class="footer-top">
            <div class="footer-copy">
                <p class="footer-eyebrow">Exclusive Share Ownership</p>
                <h2 class="footer-heading">Become a shareholder in premium hospitality.</h2>
                <p>Join King Lotus International through exclusive hotel share opportunities designed for investors seeking trusted ownership access and long-term hospitality value.</p>
            </div>
        </div>

        <div class="footer-main">
            <div class="footer-brand">
                <div class="footer-brand-mark" aria-label="King Lotus Group">
                    <span class="footer-brand-top">KING LOTUS</span>
                    <span class="footer-brand-bottom">
                        <span class="footer-brand-line" aria-hidden="true"></span>
                        <span>GROUP</span>
                        <span class="footer-brand-line" aria-hidden="true"></span>
                    </span>
                </div>
            </div>

            @if ($youtubeUrl || $facebookUrl || $emailHref || count($phoneOptions))
                <div class="footer-socials" aria-label="Social links">
                    @if ($youtubeUrl)
                        <a class="footer-social" href="{{ $youtubeUrl }}" aria-label="YouTube" target="_blank" rel="noopener noreferrer">
                            <svg width="18" height="18" viewBox="0 0 24 24" fill="none" aria-hidden="true">
                                <path d="M21.2 8.4C21 7.66 20.42 7.08 19.68 6.88C18.32 6.5 12 6.5 12 6.5C12 6.5 5.68 6.5 4.32 6.88C3.58 7.08 3 7.66 2.8 8.4C2.42 9.76 2.42 12 2.42 12C2.42 12 2.42 14.24 2.8 15.6C3 16.34 3.58 16.92 4.32 17.12C5.68 17.5 12 17.5 12 17.5C12 17.5 18.32 17.5 19.68 17.12C20.42 16.92 21 16.34 21.2 15.6C21.58 14.24 21.58 12 21.58 12C21.58 12 21.58 9.76 21.2 8.4Z" stroke="currentColor" stroke-width="1.7" stroke-linejoin="round"></path>
                                <path d="M10 9.4L15.2 12L10 14.6V9.4Z" fill="currentColor"></path>
                            </svg>
                        </a>
                    @endif

                    @if ($facebookUrl)
                        <a class="footer-social" href="{{ $facebookUrl }}" aria-label="Facebook" target="_blank" rel="noopener noreferrer">
                            <svg width="18" height="18" viewBox="0 0 24 24" fill="currentColor" aria-hidden="true">
                                <path d="M13.5 21V12.75H16.25L16.7 9.6H13.5V7.58C13.5 6.72 13.77 6.13 15 6.13H16.82V3.3C16.51 3.26 15.43 3.17 14.18 3.17C11.57 3.17 9.8 4.72 9.8 7.58V9.6H7V12.75H9.8V21H13.5Z"></path>
                            </svg>
                        </a>
                    @endif

                    @if ($emailHref)
                        <a class="footer-social" href="{{ $emailHref }}" aria-label="Email">
                            <svg width="18" height="18" viewBox="0 0 24 24" fill="none" aria-hidden="true">
                                <path d="M4 6H20V18H4V6Z" stroke="currentColor" stroke-width="1.8" stroke-linejoin="round"></path>
                                <path d="M4 8L12 13L20 8" stroke="currentColor" stroke-width="1.8" stroke-linecap="round" stroke-linejoin="round"></path>
                            </svg>
                        </a>
                    @endif

                    @if (count($phoneOptions))
                        <button class="footer-social" type="button" aria-label="Phone numbers" data-phone-trigger aria-haspopup="dialog" aria-expanded="false">
                            <svg width="18" height="18" viewBox="0 0 24 24" fill="none" aria-hidden="true">
                                <path d="M7.34 4H10.1L11.2 8.4L9.46 10.14C10.37 11.96 11.84 13.43 13.66 14.34L15.4 12.6L19.8 13.7V16.46C19.8 17.31 19.11 18 18.26 18C10.94 18 5 12.06 5 4.74C5 3.89 5.69 3.2 6.54 3.2H7.34V4Z" stroke="currentColor" stroke-width="1.8" stroke-linecap="round" stroke-linejoin="round"></path>
                            </svg>
                        </button>
                    @endif
                </div>
            @endif
        </div>

        <div class="footer-bottom" id="contact" data-nav-section="contact">
            <div>
                <span class="footer-bottom-copy-line">King Lotus International.</span>
                <span class="footer-bottom-copy-line">All rights reserved &copy; {{ now()->year }}.</span>
                <span class="footer-bottom-copy-line">Developed by <a href="https://nexzox.com" target="_blank" rel="noopener noreferrer">NexZox</a></span>
            </div>
            <div class="footer-meta">
                <a href="#">Privacy Policy</a>
                <a href="{{ route('terms.show') }}">Terms and Conditions</a>
            </div>
        </div>
    </div>
</footer>

@if ($hasFloatingLinks)
    <div class="floating-quick-actions" data-floating-actions>
        <div class="floating-quick-actions__menu" data-share-menu hidden>
            @if ($youtubeUrl)
                <a class="floating-quick-actions__item" href="{{ $youtubeUrl }}" aria-label="YouTube" target="_blank" rel="noopener noreferrer">
                    <svg width="18" height="18" viewBox="0 0 24 24" fill="none" aria-hidden="true">
                        <path d="M21.2 8.4C21 7.66 20.42 7.08 19.68 6.88C18.32 6.5 12 6.5 12 6.5C12 6.5 5.68 6.5 4.32 6.88C3.58 7.08 3 7.66 2.8 8.4C2.42 9.76 2.42 12 2.42 12C2.42 12 2.42 14.24 2.8 15.6C3 16.34 3.58 16.92 4.32 17.12C5.68 17.5 12 17.5 12 17.5C12 17.5 18.32 17.5 19.68 17.12C20.42 16.92 21 16.34 21.2 15.6C21.58 14.24 21.58 12 21.58 12C21.58 12 21.58 9.76 21.2 8.4Z" stroke="currentColor" stroke-width="1.7" stroke-linejoin="round"></path>
                        <path d="M10 9.4L15.2 12L10 14.6V9.4Z" fill="currentColor"></path>
                    </svg>
                </a>
            @endif

            @if ($facebookUrl)
                <a class="floating-quick-actions__item" href="{{ $facebookUrl }}" aria-label="Facebook" target="_blank" rel="noopener noreferrer">
                    <svg width="18" height="18" viewBox="0 0 24 24" fill="currentColor" aria-hidden="true">
                        <path d="M13.5 21V12.75H16.25L16.7 9.6H13.5V7.58C13.5 6.72 13.77 6.13 15 6.13H16.82V3.3C16.51 3.26 15.43 3.17 14.18 3.17C11.57 3.17 9.8 4.72 9.8 7.58V9.6H7V12.75H9.8V21H13.5Z"></path>
                    </svg>
                </a>
            @endif

            @if ($emailHref)
                <a class="floating-quick-actions__item" href="{{ $emailHref }}" aria-label="Email">
                    <svg width="18" height="18" viewBox="0 0 24 24" fill="none" aria-hidden="true">
                        <path d="M4 6H20V18H4V6Z" stroke="currentColor" stroke-width="1.8" stroke-linejoin="round"></path>
                        <path d="M4 8L12 13L20 8" stroke="currentColor" stroke-width="1.8" stroke-linecap="round" stroke-linejoin="round"></path>
                    </svg>
                </a>
            @endif

            @if (count($phoneOptions))
                <button class="floating-quick-actions__item" type="button" aria-label="Phone numbers" data-phone-trigger aria-haspopup="dialog" aria-expanded="false">
                    <svg width="18" height="18" viewBox="0 0 24 24" fill="none" aria-hidden="true">
                        <path d="M7.34 4H10.1L11.2 8.4L9.46 10.14C10.37 11.96 11.84 13.43 13.66 14.34L15.4 12.6L19.8 13.7V16.46C19.8 17.31 19.11 18 18.26 18C10.94 18 5 12.06 5 4.74C5 3.89 5.69 3.2 6.54 3.2H7.34V4Z" stroke="currentColor" stroke-width="1.8" stroke-linecap="round" stroke-linejoin="round"></path>
                    </svg>
                </button>
            @endif
        </div>

        <button class="floating-quick-actions__button is-share" type="button" aria-label="Open support links" aria-expanded="false" data-share-toggle>
            <svg width="22" height="22" viewBox="0 0 24 24" fill="none" aria-hidden="true">
                <path d="M6.5 12.5V11.6C6.5 7.95 9.28 5 12.7 5C16.12 5 18.9 7.95 18.9 11.6V12.5" stroke="currentColor" stroke-width="2" stroke-linecap="round"></path>
                <rect x="4.5" y="11" width="2.9" height="5.8" rx="1.45" stroke="currentColor" stroke-width="2"></rect>
                <rect x="17.6" y="11" width="2.9" height="5.8" rx="1.45" stroke="currentColor" stroke-width="2"></rect>
                <path d="M18.8 17.1C18.15 18.85 16.46 20 14.55 20H12.9" stroke="currentColor" stroke-width="2" stroke-linecap="round"></path>
                <rect x="10.9" y="18.7" width="3.7" height="2.1" rx="1.05" fill="currentColor"></rect>
                <circle cx="11.1" cy="12.95" r="0.9" fill="currentColor"></circle>
                <circle cx="14.3" cy="12.95" r="0.9" fill="currentColor"></circle>
            </svg>
        </button>

        <button class="floating-quick-actions__button is-top" type="button" aria-label="Back to top" data-scroll-top>
            <svg width="24" height="24" viewBox="0 0 24 24" fill="none" aria-hidden="true">
                <path d="M12 19V5" stroke="currentColor" stroke-width="2.4" stroke-linecap="round"></path>
                <path d="M6 11L12 5L18 11" stroke="currentColor" stroke-width="2.4" stroke-linecap="round" stroke-linejoin="round"></path>
            </svg>
        </button>
    </div>
@endif

@if (count($phoneOptions))
    <div class="footer-phone-picker" data-phone-picker hidden>
        <div class="footer-phone-picker__backdrop" data-phone-close></div>
        <div class="footer-phone-picker__dialog" role="dialog" aria-modal="true" aria-labelledby="footer-phone-picker-title">
            <button class="footer-phone-picker__close" type="button" aria-label="Close phone list" data-phone-close>&times;</button>
            <p class="footer-phone-picker__eyebrow">Call Us</p>
            <h3 class="footer-phone-picker__title" id="footer-phone-picker-title">Choose a phone number</h3>

            <div class="footer-phone-picker__list">
                @foreach ($phoneOptions as $phone)
                    <a class="footer-phone-picker__option" href="{{ $phone['href'] }}">
                        <span>{{ $phone['label'] }}</span>
                        <span>Call</span>
                    </a>
                @endforeach
            </div>
        </div>
    </div>
@endif

@once
    @push('scripts')
        <script>
            document.addEventListener('DOMContentLoaded', () => {
                const phoneTriggers = document.querySelectorAll('[data-phone-trigger]');
                const picker = document.querySelector('[data-phone-picker]');
                const shareToggle = document.querySelector('[data-share-toggle]');
                const shareMenu = document.querySelector('[data-share-menu]');
                const quickActions = document.querySelector('[data-floating-actions]');
                const scrollTopButton = document.querySelector('[data-scroll-top]');

                if (shareToggle && shareMenu && quickActions) {
                    let shareCloseTimer = null;

                    const setShareOpen = (open) => {
                        if (shareCloseTimer) {
                            window.clearTimeout(shareCloseTimer);
                            shareCloseTimer = null;
                        }

                        if (open) {
                            shareMenu.hidden = false;
                            shareMenu.classList.remove('is-closing');
                            shareMenu.classList.add('is-open');
                        } else {
                            shareMenu.classList.remove('is-open');
                            shareMenu.classList.add('is-closing');

                            shareCloseTimer = window.setTimeout(() => {
                                shareMenu.hidden = true;
                                shareMenu.classList.remove('is-closing');
                            }, 440);
                        }

                        shareToggle.setAttribute('aria-expanded', String(open));
                        quickActions.classList.toggle('is-share-open', open);
                    };

                    shareToggle.addEventListener('click', (event) => {
                        event.stopPropagation();
                        setShareOpen(shareMenu.hidden || shareMenu.classList.contains('is-closing'));
                    });

                    shareMenu.addEventListener('click', (event) => {
                        if (event.target.closest('a, button')) {
                            setShareOpen(false);
                        }
                    });

                    document.addEventListener('click', (event) => {
                        if (!quickActions.contains(event.target)) {
                            setShareOpen(false);
                        }
                    });
                }

                if (scrollTopButton) {
                    const syncTopButton = () => {
                        const visible = window.scrollY > 220;
                        scrollTopButton.classList.toggle('is-visible', visible);
                        quickActions?.classList.toggle('has-top-visible', visible);
                    };

                    scrollTopButton.addEventListener('click', () => {
                        window.scrollTo({
                            top: 0,
                            behavior: 'smooth',
                        });
                    });

                    window.addEventListener('scroll', syncTopButton, { passive: true });
                    syncTopButton();
                }

                if (!phoneTriggers.length || !picker) {
                    return;
                }

                const closeTargets = picker.querySelectorAll('[data-phone-close]');
                let phoneCloseTimer = null;
                let phoneOpenFrame = null;

                const setOpen = (open) => {
                    if (phoneCloseTimer) {
                        window.clearTimeout(phoneCloseTimer);
                        phoneCloseTimer = null;
                    }

                    if (phoneOpenFrame) {
                        window.cancelAnimationFrame(phoneOpenFrame);
                        phoneOpenFrame = null;
                    }

                    if (open) {
                        picker.hidden = false;
                        picker.classList.add('is-ready');
                        picker.classList.remove('is-open');

                        phoneOpenFrame = window.requestAnimationFrame(() => {
                            picker.classList.add('is-open');
                            phoneOpenFrame = null;
                        });
                    } else {
                        picker.classList.remove('is-open');

                        phoneCloseTimer = window.setTimeout(() => {
                            picker.hidden = true;
                            picker.classList.remove('is-ready');
                        }, 720);
                    }

                    phoneTriggers.forEach((trigger) => trigger.setAttribute('aria-expanded', String(open)));
                    document.body.classList.toggle('footer-phone-picker-open', open);
                };

                phoneTriggers.forEach((trigger) => {
                    trigger.addEventListener('click', () => setOpen(true));
                });

                closeTargets.forEach((node) => node.addEventListener('click', () => setOpen(false)));

                picker.addEventListener('click', (event) => {
                    if (event.target.closest('.footer-phone-picker__option')) {
                        setOpen(false);
                    }
                });

                document.addEventListener('keydown', (event) => {
                    if (event.key === 'Escape' && picker.classList.contains('is-open')) {
                        setOpen(false);
                    }
                });
            });
        </script>
    @endpush
@endonce
