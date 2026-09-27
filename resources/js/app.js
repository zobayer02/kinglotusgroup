import './bootstrap';
import { initSiteNavigation } from './site-navigation';

// CSP-compliant global image error handler for avatars and shareholder photos
document.addEventListener('error', (event) => {
    if (event.target && event.target.tagName === 'IMG' && event.target.hasAttribute('data-fallback-placeholder')) {
        event.target.style.display = 'none';
        if (event.target.nextElementSibling) {
            event.target.nextElementSibling.style.display = 'flex';
        }
    }
}, true);

// CSP-compliant global dismiss handler for alert banners
document.addEventListener('click', (event) => {
    const closeBtn = event.target.closest('.profile-alert-close');
    if (closeBtn) {
        closeBtn.closest('.profile-alert-banner')?.remove();
    }
});

// Initialize mobile navigation and scroll animations
if (document.readyState === 'loading') {
    document.addEventListener('DOMContentLoaded', () => initSiteNavigation());
} else {
    initSiteNavigation();
}
