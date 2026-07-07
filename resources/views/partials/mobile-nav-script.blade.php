<script>
    document.addEventListener('DOMContentLoaded', () => {
        const nav = document.querySelector('[data-mobile-nav]');
        const toggle = nav?.querySelector('[data-nav-toggle]');
        if (nav && toggle) {
            const mobileQuery = window.matchMedia('(max-width: 768px)');
            const navLinks = Array.from(nav.querySelectorAll('[data-nav-link]'));
            const navSections = Array.from(document.querySelectorAll('[data-nav-section]'));
            const locationSection = document.querySelector('.location-section');
            const enableScrollSpy = nav.dataset.scrollspy === 'true';
            let touchStartX = 0;
            let touchStartY = 0;
            let isTouching = false;

            const syncNavState = () => {
                nav.classList.toggle('is-scrolled', window.scrollY > 18);
            };

            const syncLocationLayer = () => {
                if (!locationSection) {
                    return;
                }

                const navRect = nav.getBoundingClientRect();
                const locationRect = locationSection.getBoundingClientRect();
                const isOverLocation = locationRect.top < navRect.bottom + 24 && locationRect.bottom > navRect.top;

                nav.classList.toggle('is-over-location', isOverLocation);
            };

            const setCurrentLink = (sectionId) => {
                navLinks.forEach((link) => {
                    link.classList.toggle('is-current', link.dataset.navLink === sectionId);
                });
            };

            const getScrollOffset = () => {
                return nav.getBoundingClientRect().height + 18;
            };

            const scrollToSection = (targetId) => {
                const target = document.getElementById(targetId);

                if (!target) {
                    return;
                }

                const top = window.scrollY + target.getBoundingClientRect().top - getScrollOffset();

                window.scrollTo({
                    top: Math.max(0, top),
                    behavior: 'smooth',
                });
            };

            const setOpen = (open) => {
                nav.classList.toggle('is-open', open);
                toggle.setAttribute('aria-expanded', String(open));
            };

            const toggleNav = () => {
                setOpen(!nav.classList.contains('is-open'));
            };

            toggle.addEventListener('click', (event) => {
                event.preventDefault();
                event.stopPropagation();
                toggleNav();
            });

            navLinks.forEach((link) => {
                link.addEventListener('click', (event) => {
                    const href = link.getAttribute('href') ?? '';

                    if (!href.startsWith('#')) {
                        return;
                    }

                    const targetId = href.slice(1);

                    if (!targetId) {
                        return;
                    }

                    event.preventDefault();
                    setCurrentLink(link.dataset.navLink ?? targetId);
                    setOpen(false);
                    scrollToSection(targetId);
                });
            });

            nav.addEventListener('click', (event) => {
                if (!mobileQuery.matches) {
                    return;
                }

                if (event.target.closest('a, button')) {
                    return;
                }

                toggleNav();
            });

            document.addEventListener('click', (event) => {
                if (!mobileQuery.matches || !nav.classList.contains('is-open')) {
                    return;
                }

                if (nav.contains(event.target)) {
                    return;
                }

                setOpen(false);
            });

            nav.addEventListener('touchstart', (event) => {
                if (!mobileQuery.matches || !event.touches.length) {
                    return;
                }

                const touch = event.touches[0];
                touchStartX = touch.clientX;
                touchStartY = touch.clientY;
                isTouching = true;
            }, { passive: true });

            nav.addEventListener('touchend', (event) => {
                if (!mobileQuery.matches || !isTouching || !event.changedTouches.length) {
                    isTouching = false;
                    return;
                }

                const touch = event.changedTouches[0];
                const deltaX = touch.clientX - touchStartX;
                const deltaY = touch.clientY - touchStartY;

                if (Math.abs(deltaY) > 36 && Math.abs(deltaY) > Math.abs(deltaX)) {
                    setOpen(deltaY > 0);
                }

                isTouching = false;
            }, { passive: true });

            mobileQuery.addEventListener?.('change', (event) => {
                if (!event.matches) {
                    setOpen(false);
                }

                syncNavState();
            });

            if (enableScrollSpy && navSections.length) {
                const syncCurrentSection = () => {
                    const offset = getScrollOffset() + 40;
                    let activeSectionId = 'home';

                    navSections.forEach((section) => {
                        const rect = section.getBoundingClientRect();

                        if (rect.top <= offset) {
                            activeSectionId = section.dataset.navSection ?? section.id ?? activeSectionId;
                        }
                    });

                    setCurrentLink(activeSectionId);
                };

                window.addEventListener('scroll', syncCurrentSection, { passive: true });
                window.addEventListener('resize', syncCurrentSection);
                syncCurrentSection();
            }

            window.addEventListener('scroll', syncNavState, { passive: true });
            window.addEventListener('scroll', syncLocationLayer, { passive: true });
            window.addEventListener('resize', syncLocationLayer);
            syncNavState();
            syncLocationLayer();
        }

        const revealSection = (section) => {
            section.classList.add('is-visible');
        };

        const revealSections = document.querySelectorAll('.site-footer, .valued-shareholders-section, .leadership-section, .office-section, .location-section, .shareholder-review-section, .gallery-section, .notice-banner, .about-section, .why-section, .projects-section');

        if (!revealSections.length) {
            return;
        }

        if (window.matchMedia('(prefers-reduced-motion: reduce)').matches || !('IntersectionObserver' in window)) {
            revealSections.forEach(revealSection);
            return;
        }

        const sectionObserver = new IntersectionObserver((entries, observer) => {
            entries.forEach((entry) => {
                if (!entry.isIntersecting) {
                    return;
                }

                revealSection(entry.target);
                observer.unobserve(entry.target);
            });
        }, {
            threshold: 0.18,
            rootMargin: '0px 0px -8% 0px',
        });

        revealSections.forEach((section) => sectionObserver.observe(section));
    });
</script>
