:root {
    --section-surface: #d9e6ef;
    --section-title-size: clamp(1.95rem, 3.2vw, 3.15rem);
    --section-title-size-mobile: clamp(1.75rem, 6vw, 2.1rem);
    --frame-glass-surface:
        linear-gradient(180deg, rgba(255, 255, 255, 0.12) 0%, rgba(255, 255, 255, 0.04) 100%),
        linear-gradient(180deg, rgba(220, 236, 245, 0.94) 0%, rgba(197, 220, 233, 0.9) 100%);
    --nav-surface:
        linear-gradient(180deg, rgba(255, 255, 255, 0.82) 0%, rgba(247, 250, 252, 0.56) 22%, rgba(230, 239, 245, 0.3) 100%);
    --nav-shell:
        linear-gradient(135deg, rgba(255, 255, 255, 0.94) 0%, rgba(232, 241, 247, 0.9) 42%, rgba(208, 222, 232, 0.84) 100%);
}

.floating-nav {
    position: relative;
    overflow: hidden;
    isolation: isolate;
    display: grid;
    grid-template-columns: 1fr auto 1fr;
    align-items: center;
    gap: 24px;
    padding: 13px 18px;
    border-radius: 28px;
    transition:
        width 0.32s ease,
        padding 0.32s ease,
        border-radius 0.32s ease,
        top 0.32s ease,
        box-shadow 0.32s ease;
}

.floating-nav::before,
.floating-nav::after {
    content: "";
    position: absolute;
    inset: 0;
    border-radius: inherit;
    pointer-events: none;
    transform-origin: center;
    animation: navShellSpread 0.95s cubic-bezier(0.16, 1, 0.3, 1) both;
    transition:
        border-radius 0.32s ease,
        box-shadow 0.32s ease,
        border-color 0.32s ease,
        background 0.32s ease;
}

.floating-nav::before {
    background: var(--nav-surface);
    z-index: 1;
}

.floating-nav::after {
    border: 1px solid rgba(255, 255, 255, 0.46);
    background: var(--nav-shell);
    backdrop-filter: blur(18px) saturate(160%);
    -webkit-backdrop-filter: blur(18px) saturate(160%);
    box-shadow:
        0 18px 50px rgba(12, 22, 30, 0.16),
        inset 0 1px 0 rgba(255, 255, 255, 0.8);
    z-index: 0;
}

.floating-nav.is-over-location::before {
    background: linear-gradient(180deg, #ffffff 0%, #f4f8fb 48%, #e5eff5 100%);
}

.floating-nav.is-over-location::after {
    border-color: rgba(198, 214, 225, 0.9);
    background: linear-gradient(135deg, #ffffff 0%, #eef6fa 48%, #dceaf2 100%);
    backdrop-filter: none;
    -webkit-backdrop-filter: none;
    box-shadow:
        0 18px 44px rgba(12, 22, 30, 0.16),
        inset 0 1px 0 rgba(255, 255, 255, 0.9);
}

.nav-links,
.nav-actions,
.brand {
    position: relative;
    z-index: 2;
}

.nav-links,
.nav-actions {
    display: flex;
    align-items: center;
    gap: 14px;
    flex-wrap: nowrap;
    transition: gap 0.32s ease;
}

.nav-link {
    padding: 12px 14px;
    border: 1px solid transparent;
    border-radius: 999px;
    font-size: 0.98rem;
    font-weight: 500;
    color: #10212c;
    white-space: nowrap;
    transition:
        background-color 0.25s ease,
        border-color 0.25s ease,
        transform 0.25s ease,
        box-shadow 0.25s ease,
        padding 0.32s ease,
        font-size 0.32s ease,
        color 0.25s ease;
}

.nav-link:hover,
.nav-link.is-current {
    background: #0c505d;
    border-color: #0c505d;
    color: #ffffff;
    box-shadow: 0 12px 24px rgba(12, 80, 93, 0.24);
}

.nav-link:hover,
.nav-link:focus-visible {
    transform: translateY(-1px) scale(1.04);
}

.nav-link:focus-visible {
    outline: none;
    background: #0c505d;
    border-color: #0c505d;
    color: #ffffff;
    box-shadow:
        0 12px 24px rgba(12, 80, 93, 0.24),
        0 0 0 4px rgba(12, 80, 93, 0.14);
}

.brand {
    justify-self: center;
    display: inline-flex;
    flex-direction: column;
    align-items: center;
    gap: 2px;
    color: #0f1114;
    text-transform: uppercase;
    text-align: center;
    font-family: var(--font-primary);
    text-shadow: none;
    animation: brandCenterReveal 0.68s cubic-bezier(0.16, 1, 0.3, 1) 80ms both;
}

.brand-top {
    display: block;
    font-size: clamp(1.9rem, 3.1vw, 3.45rem);
    font-weight: 500;
    letter-spacing: 0.07em;
    line-height: 0.9;
    white-space: nowrap;
    transition: font-size 0.32s ease;
}

.brand-bottom {
    display: inline-flex;
    align-items: center;
    gap: 9px;
    font-size: clamp(0.92rem, 1.3vw, 1.18rem);
    font-weight: 500;
    letter-spacing: 0.24em;
    line-height: 1;
    white-space: nowrap;
    transition:
        font-size 0.32s ease,
        gap 0.32s ease;
}

.brand-line {
    width: clamp(76px, 5.8vw, 124px);
    height: 4px;
    border-radius: 999px;
    background: currentColor;
    opacity: 0.92;
    transition:
        width 0.32s ease,
        height 0.32s ease;
}

.nav-actions {
    justify-self: end;
    justify-content: flex-end;
}

.nav-toggle {
    display: none;
    align-items: center;
    justify-content: center;
    width: 44px;
    height: 44px;
    border: 0;
    border-radius: 999px;
    background: rgba(255, 255, 255, 0.46);
    color: #0f1114;
    box-shadow: 0 6px 14px rgba(12, 22, 30, 0.12);
    cursor: pointer;
    z-index: 3;
}

.nav-toggle svg {
    transition: transform 0.28s ease;
}

@keyframes brandCenterReveal {
    0% {
        opacity: 0;
        transform: translateY(-8px) scale(0.92);
    }
    100% {
        opacity: 1;
        transform: translateY(0) scale(1);
    }
}

@keyframes navShellSpread {
    0% {
        opacity: 0;
        transform: scaleX(0.26);
    }
    38% {
        opacity: 1;
    }
    100% {
        opacity: 1;
        transform: scaleX(1);
    }
}

@keyframes navDropIn {
    from {
        opacity: 0;
        transform: translateY(-12px);
    }
    to {
        opacity: 1;
        transform: translateY(0);
    }
}

@keyframes navPullElastic {
    0%, 100% {
        transform: translateX(-50%) translateY(0);
    }
    45% {
        transform: translateX(-50%) translateY(6px);
    }
    72% {
        transform: translateX(-50%) translateY(-2px);
    }
}

@keyframes footerSocialReveal {
    0% {
        opacity: 0;
        transform: translateY(18px) scale(0.96);
    }
    100% {
        opacity: 1;
        transform: translateY(0) scale(1);
    }
}

.site-footer {
    position: relative;
    padding: 20px 28px 28px;
    background: var(--section-surface);
}

.footer-shell {
    width: 100%;
    max-width: 1240px;
    margin: 0 auto;
    padding: 40px 48px 28px;
    border-radius: 34px;
    border: 1px solid rgba(190, 205, 214, 0.72);
    background: var(--frame-glass-surface);
    box-shadow:
        0 26px 70px rgba(24, 43, 56, 0.1),
        inset 0 1px 0 rgba(255, 255, 255, 0.58);
    opacity: 0;
    transform: translateY(36px) scale(0.985);
    transition:
        opacity 0.8s cubic-bezier(0.16, 1, 0.3, 1),
        transform 0.8s cubic-bezier(0.16, 1, 0.3, 1);
    will-change: opacity, transform;
}

.footer-top {
    display: grid;
    grid-template-columns: minmax(0, 1fr);
    gap: 0;
    justify-items: center;
    align-items: center;
    padding-bottom: 30px;
    border-bottom: 1px solid rgba(169, 183, 194, 0.42);
}

.footer-copy {
    max-width: 760px;
    text-align: center;
    opacity: 0;
    transform: translateY(24px);
    transition:
        opacity 0.8s cubic-bezier(0.16, 1, 0.3, 1),
        transform 0.8s cubic-bezier(0.16, 1, 0.3, 1);
    will-change: opacity, transform;
}

.footer-eyebrow {
    margin: 0 0 10px;
    font-size: 0.92rem;
    font-weight: 700;
    letter-spacing: 0.12em;
    text-transform: uppercase;
    color: rgba(16, 33, 44, 0.62);
}

.footer-heading {
    margin: 0;
    max-width: 760px;
    font-size: var(--section-title-size);
    font-weight: 400;
    line-height: 0.98;
    letter-spacing: 0;
    color: #101214;
    font-family: var(--font-primary);
}

.footer-copy p:last-child {
    margin: 16px 0 0;
    max-width: 760px;
    font-size: 1.02rem;
    line-height: 1.7;
    color: rgba(16, 33, 44, 0.72);
}

.footer-main {
    display: grid;
    grid-template-columns: 1fr;
    align-items: center;
    justify-items: center;
    gap: 24px;
    padding: 24px 0;
    border-bottom: 1px solid rgba(169, 183, 194, 0.42);
}

.footer-meta a,
.footer-bottom a {
    color: #10212c;
    transition: opacity 0.2s ease;
}

.footer-meta a:hover,
.footer-bottom a:hover {
    opacity: 0.68;
}

.footer-brand {
    justify-self: center;
    color: #101214;
    font-family: var(--font-primary);
    opacity: 0;
    transform: translateY(24px);
    transition:
        opacity 0.8s cubic-bezier(0.16, 1, 0.3, 1),
        transform 0.8s cubic-bezier(0.16, 1, 0.3, 1);
    will-change: opacity, transform;
}

.footer-brand-mark {
    display: inline-flex;
    flex-direction: column;
    align-items: center;
    gap: 4px;
    text-transform: uppercase;
    line-height: 1;
}

.footer-brand-top {
    font-size: clamp(2.1rem, 3.2vw, 4rem);
    font-weight: 400;
    letter-spacing: 0.06em;
    white-space: nowrap;
}

.footer-brand-bottom {
    display: inline-flex;
    align-items: center;
    gap: 10px;
    font-size: clamp(0.92rem, 1.15vw, 1.1rem);
    font-weight: 400;
    letter-spacing: 0.22em;
    white-space: nowrap;
}

.footer-brand-line {
    width: clamp(66px, 5.1vw, 108px);
    height: 4px;
    border-radius: 999px;
    background: currentColor;
    opacity: 0.9;
}

.footer-socials {
    display: flex;
    align-items: center;
    gap: 24px;
    flex-wrap: wrap;
    justify-self: center;
    justify-content: center;
    opacity: 0;
    transform: translateY(24px);
    transition:
        opacity 0.8s cubic-bezier(0.16, 1, 0.3, 1),
        transform 0.8s cubic-bezier(0.16, 1, 0.3, 1);
    will-change: opacity, transform;
}

.footer-social {
    display: inline-flex;
    align-items: center;
    justify-content: center;
    width: 42px;
    height: 42px;
    border-radius: 999px;
    border: 1px solid rgba(178, 191, 201, 0.56);
    background: rgba(255, 255, 255, 0.72);
    color: #10212c;
    cursor: pointer;
    transition:
        transform 0.12s ease,
        background-color 0.12s ease,
        border-color 0.12s ease,
        color 0.12s ease,
        box-shadow 0.12s ease,
        opacity 0.65s cubic-bezier(0.16, 1, 0.3, 1);
    opacity: 0;
    transform: translateY(18px) scale(0.96);
}

.footer-social:hover {
    transform: translateY(-1px) scale(1.04);
    background: #0c505d;
    border-color: #0c505d;
    color: #ffffff;
    box-shadow: 0 12px 24px rgba(12, 80, 93, 0.24);
}

.footer-social:focus-visible {
    transform: translateY(-1px) scale(1.04);
    background: #0c505d;
    border-color: #0c505d;
    color: #ffffff;
    box-shadow:
        0 12px 24px rgba(12, 80, 93, 0.24),
        0 0 0 4px rgba(12, 80, 93, 0.14);
    outline: none;
}

.footer-phone-picker[hidden] {
    display: none !important;
}

.floating-quick-actions {
    --floating-action-shift: 64px;
    position: fixed;
    right: 22px;
    bottom: 24px;
    z-index: 1100;
    display: flex;
    flex-direction: column;
    justify-items: end;
    gap: 14px;
}

.floating-quick-actions__menu[hidden] {
    display: none !important;
}

.floating-quick-actions__menu {
    display: grid;
    gap: 10px;
    margin-bottom: 2px;
    pointer-events: none;
    transform: translateY(0);
    transition: transform 0.28s cubic-bezier(0.16, 1, 0.3, 1);
}

.floating-quick-actions__menu.is-open,
.floating-quick-actions__menu.is-closing {
    pointer-events: auto;
}

.floating-quick-actions__button,
.floating-quick-actions__item {
    display: inline-flex;
    align-items: center;
    justify-content: center;
    width: 50px;
    height: 50px;
    border: 1px solid rgba(178, 191, 201, 0.56);
    border-radius: 999px;
    background:
        radial-gradient(circle at 30% 28%, rgba(255, 255, 255, 0.96) 0%, rgba(255, 255, 255, 0.72) 24%, rgba(255, 255, 255, 0.18) 62%, rgba(255, 255, 255, 0.08) 100%),
        linear-gradient(180deg, rgba(255, 255, 255, 0.44) 0%, rgba(214, 229, 238, 0.16) 100%);
    color: #10212c;
    backdrop-filter: blur(14px) saturate(150%);
    -webkit-backdrop-filter: blur(14px) saturate(150%);
    box-shadow:
        inset 0 1px 0 rgba(255, 255, 255, 0.92),
        inset 0 -10px 18px rgba(175, 197, 212, 0.18),
        0 12px 24px rgba(24, 43, 56, 0.12);
    cursor: pointer;
    transition: transform 0.18s ease, background-color 0.18s ease, border-color 0.18s ease, box-shadow 0.18s ease, opacity 0.18s ease;
}

.floating-quick-actions__button {
    position: relative;
    z-index: 2;
}

.floating-quick-actions__button.is-share {
    transform: translateY(0);
    transition:
        transform 0.28s cubic-bezier(0.16, 1, 0.3, 1),
        background-color 0.18s ease,
        border-color 0.18s ease,
        box-shadow 0.18s ease,
        color 0.18s ease,
        opacity 0.18s ease;
}

.floating-quick-actions__item {
    opacity: 0;
    transform: translateY(12px) scale(0.86);
}

.floating-quick-actions__menu.is-open .floating-quick-actions__item,
.floating-quick-actions__menu.is-closing .floating-quick-actions__item {
    animation-duration: 0.3s;
    animation-timing-function: cubic-bezier(0.16, 1, 0.3, 1);
    animation-fill-mode: both;
}

.floating-quick-actions__menu.is-open .floating-quick-actions__item {
    animation-name: floatingQuickActionIn;
}

.floating-quick-actions__menu.is-closing .floating-quick-actions__item {
    animation-name: floatingQuickActionOut;
}

.floating-quick-actions__menu.is-open .floating-quick-actions__item:nth-child(1),
.floating-quick-actions__menu.is-closing .floating-quick-actions__item:nth-child(4) {
    animation-delay: 0s;
}

.floating-quick-actions__menu.is-open .floating-quick-actions__item:nth-child(2),
.floating-quick-actions__menu.is-closing .floating-quick-actions__item:nth-child(3) {
    animation-delay: 0.04s;
}

.floating-quick-actions__menu.is-open .floating-quick-actions__item:nth-child(3),
.floating-quick-actions__menu.is-closing .floating-quick-actions__item:nth-child(2) {
    animation-delay: 0.08s;
}

.floating-quick-actions__menu.is-open .floating-quick-actions__item:nth-child(4),
.floating-quick-actions__menu.is-closing .floating-quick-actions__item:nth-child(1) {
    animation-delay: 0.12s;
}

.floating-quick-actions__button:hover,
.floating-quick-actions__button:focus-visible,
.floating-quick-actions__item:hover,
.floating-quick-actions__item:focus-visible {
    transform: translateY(-1px) scale(1.04);
    background: #0c505d;
    border-color: #0c505d;
    color: #ffffff;
    box-shadow: 0 12px 24px rgba(12, 80, 93, 0.24);
    outline: none;
}

.floating-quick-actions__button.is-top {
    position: absolute;
    right: 0;
    bottom: 0;
    z-index: 1;
    opacity: 0;
    pointer-events: none;
    transform: translateY(0) scale(0.9);
}

.floating-quick-actions__button.is-top.is-visible {
    opacity: 1;
    pointer-events: auto;
    transform: translateY(0) scale(1);
}

.floating-quick-actions.has-top-visible .floating-quick-actions__button.is-share {
    transform: translateY(calc(-1 * var(--floating-action-shift)));
}

.floating-quick-actions.has-top-visible .floating-quick-actions__menu {
    transform: translateY(calc(-1 * var(--floating-action-shift)));
}

.floating-quick-actions__button.is-top:hover,
.floating-quick-actions__button.is-top:focus-visible {
    background: #0c505d;
    border-color: #0c505d;
    color: #ffffff;
    box-shadow: 0 12px 24px rgba(12, 80, 93, 0.24);
}

@keyframes floatingQuickActionIn {
    from {
        opacity: 0;
        transform: translateY(12px) scale(0.86);
    }
    to {
        opacity: 1;
        transform: translateY(0) scale(1);
    }
}

@keyframes floatingQuickActionOut {
    from {
        opacity: 1;
        transform: translateY(0) scale(1);
    }
    to {
        opacity: 0;
        transform: translateY(12px) scale(0.86);
    }
}

.footer-phone-picker {
    position: fixed;
    inset: 0;
    z-index: 1200;
    visibility: hidden;
    pointer-events: none;
    transition: visibility 0s linear 0.56s;
}

.footer-phone-picker.is-ready {
    visibility: visible;
    pointer-events: auto;
    transition-delay: 0s;
}

.footer-phone-picker__backdrop {
    position: absolute;
    inset: 0;
    background: rgba(7, 20, 28, 0.42);
    backdrop-filter: blur(0);
    -webkit-backdrop-filter: blur(0);
    opacity: 0;
    transition:
        opacity 0.42s cubic-bezier(0.16, 1, 0.3, 1),
        backdrop-filter 0.42s cubic-bezier(0.16, 1, 0.3, 1),
        -webkit-backdrop-filter 0.42s cubic-bezier(0.16, 1, 0.3, 1);
}

.footer-phone-picker__dialog {
    --footer-phone-picker-closed-transform: translateY(26px) scale(0.9);
    --footer-phone-picker-open-transform: translateY(0) scale(1);
    position: relative;
    width: min(92vw, 420px);
    margin: min(14vh, 120px) auto 0;
    padding: 24px;
    border-radius: 28px;
    border: 1px solid rgba(190, 205, 214, 0.72);
    background: linear-gradient(180deg, rgba(241, 248, 252, 0.97) 0%, rgba(223, 236, 245, 0.94) 100%);
    box-shadow: 0 30px 70px rgba(16, 33, 44, 0.22);
    opacity: 0;
    transform: var(--footer-phone-picker-closed-transform);
    transition:
        opacity 0.58s cubic-bezier(0.16, 1, 0.3, 1),
        transform 0.58s cubic-bezier(0.16, 1, 0.3, 1);
}

.footer-phone-picker.is-open .footer-phone-picker__backdrop {
    opacity: 1;
    backdrop-filter: blur(3px);
    -webkit-backdrop-filter: blur(3px);
}

.footer-phone-picker.is-open .footer-phone-picker__dialog {
    opacity: 1;
    transform: var(--footer-phone-picker-open-transform);
}

.footer-phone-picker__close {
    position: absolute;
    top: 14px;
    right: 14px;
    width: 38px;
    height: 38px;
    border: 0;
    border-radius: 999px;
    background: rgba(255, 255, 255, 0.72);
    color: #10212c;
    font-size: 1.5rem;
    line-height: 1;
    cursor: pointer;
    transition: background-color 0.18s ease, color 0.18s ease, transform 0.18s ease;
}

.footer-phone-picker__close:hover,
.footer-phone-picker__close:focus-visible {
    background: #0c505d;
    color: #ffffff;
    transform: scale(1.04);
    outline: none;
}

.footer-phone-picker__eyebrow {
    margin: 0 0 6px;
    font-family: var(--font-primary);
    font-size: 0.8rem;
    font-weight: 700;
    letter-spacing: 0.18em;
    text-transform: uppercase;
    color: #0c505d;
}

.footer-phone-picker__title {
    margin: 0 0 18px;
    font-family: var(--font-primary);
    font-size: clamp(1.5rem, 3vw, 2.1rem);
    color: #10212c;
}

.footer-phone-picker__list {
    display: grid;
    gap: 12px;
}

.footer-phone-picker__option {
    display: flex;
    align-items: center;
    justify-content: space-between;
    gap: 16px;
    min-height: 58px;
    padding: 0 18px;
    border: 1px solid rgba(175, 191, 207, 0.62);
    border-radius: 18px;
    background: rgba(255, 255, 255, 0.84);
    color: #10212c;
    font-family: var(--font-primary);
    font-weight: 600;
    transition: transform 0.18s ease, border-color 0.18s ease, background-color 0.18s ease, box-shadow 0.18s ease;
}

.footer-phone-picker__option:hover,
.footer-phone-picker__option:focus-visible {
    transform: translateY(-1px);
    border-color: rgba(12, 80, 93, 0.42);
    background: rgba(255, 255, 255, 0.98);
    box-shadow: 0 12px 24px rgba(12, 80, 93, 0.12);
    outline: none;
}

body.footer-phone-picker-open {
    overflow: hidden;
}

.footer-bottom {
    display: flex;
    align-items: center;
    justify-content: center;
    flex-direction: column;
    gap: 20px;
    padding-top: 20px;
    text-align: center;
    font-size: 0.98rem;
    color: rgba(16, 33, 44, 0.72);
    opacity: 0;
    transform: translateY(24px);
    transition:
        opacity 0.8s cubic-bezier(0.16, 1, 0.3, 1),
        transform 0.8s cubic-bezier(0.16, 1, 0.3, 1);
    will-change: opacity, transform;
}

.footer-bottom-copy-line {
    display: inline;
}

.footer-meta {
    display: flex;
    align-items: center;
    justify-content: center;
    gap: 28px;
    flex-wrap: wrap;
    order: 1;
}

.footer-bottom > div:first-child {
    order: 2;
}

.site-footer.is-visible .footer-shell,
.site-footer.is-visible .footer-copy,
.site-footer.is-visible .footer-brand,
.site-footer.is-visible .footer-socials,
.site-footer.is-visible .footer-bottom {
    opacity: 1;
    transform: none;
}

.site-footer.is-visible .footer-social {
    opacity: 1;
    transform: none;
    transition-delay: 0s;
    animation: footerSocialReveal 0.65s cubic-bezier(0.16, 1, 0.3, 1) both;
}

.site-footer.is-visible .footer-shell {
    transition-delay: 0s;
}

.site-footer.is-visible .footer-copy {
    transition-delay: 0.12s;
}

.site-footer.is-visible .footer-brand {
    transition-delay: 0.18s;
}

.site-footer.is-visible .footer-socials {
    transition-delay: 0.24s;
}

.site-footer.is-visible .footer-bottom {
    transition-delay: 0.3s;
}

.site-footer.is-visible .footer-social:nth-child(1) {
    animation-delay: 0.34s;
}

.site-footer.is-visible .footer-social:nth-child(2) {
    animation-delay: 0.4s;
}

.site-footer.is-visible .footer-social:nth-child(3) {
    animation-delay: 0.46s;
}

@media (max-width: 1100px) {
    .floating-nav {
        gap: 14px;
        padding: 11px 12px;
    }

    .nav-links,
    .nav-actions {
        gap: 8px;
    }

    .nav-link {
        padding: 9px 11px;
        font-size: 0.86rem;
    }

    .brand-top {
        font-size: clamp(1.45rem, 2.2vw, 2.35rem);
    }

    .brand-bottom {
        font-size: clamp(0.72rem, 0.95vw, 0.92rem);
        gap: 6px;
        letter-spacing: 0.18em;
    }

    .brand-line {
        width: clamp(36px, 4vw, 74px);
        height: 3px;
    }

    .footer-shell {
        padding: 34px 34px 24px;
    }

    .footer-main {
        grid-template-columns: 1fr;
        justify-items: center;
    }

    .footer-brand,
    .footer-socials {
        justify-self: center;
    }

    .footer-brand-top {
        font-size: clamp(1.9rem, 4vw, 3rem);
    }

    .footer-brand-bottom {
        gap: 8px;
    }
}

@media (min-width: 769px) {
    .floating-nav {
        position: fixed;
        top: 48px;
        left: 50%;
        width: min(1172px, calc(100vw - 92px));
        transform: translateX(-50%);
        z-index: 1200;
    }

    .floating-nav.is-scrolled {
        top: 38px;
        width: min(1138px, calc(100vw - 112px));
        padding: 12px 17px;
        border-radius: 25px;
    }

    .floating-nav.is-scrolled::after {
        box-shadow:
            0 14px 34px rgba(12, 22, 30, 0.14),
            inset 0 1px 0 rgba(255, 255, 255, 0.72);
    }

    .floating-nav.is-scrolled .nav-links,
    .floating-nav.is-scrolled .nav-actions {
        gap: 12px;
    }

    .floating-nav.is-scrolled .nav-link {
        padding: 11px 13px;
        font-size: 0.95rem;
    }

    .floating-nav.is-scrolled .brand-top {
        font-size: clamp(1.82rem, 2.72vw, 3.02rem);
    }

    .floating-nav.is-scrolled .brand-bottom {
        font-size: clamp(0.86rem, 1.1vw, 1.04rem);
        gap: 8px;
    }

    .floating-nav.is-scrolled .brand-line {
        width: clamp(64px, 4.8vw, 108px);
        height: 3px;
    }

    .nav-links .nav-link,
    .nav-actions .nav-link {
        opacity: 0;
        transform: translateY(-12px);
        animation: navDropIn 0.48s ease both;
    }

    .nav-links .nav-link:nth-child(3) { animation-delay: 720ms; }
    .nav-actions .nav-link:nth-child(1) { animation-delay: 780ms; }
    .nav-links .nav-link:nth-child(2) { animation-delay: 860ms; }
    .nav-actions .nav-link:nth-child(2) { animation-delay: 940ms; }
    .nav-links .nav-link:nth-child(1) { animation-delay: 1020ms; }
    .nav-actions .nav-link:nth-child(3) { animation-delay: 1100ms; }
}

@media (max-width: 768px) {
    .floating-nav {
        display: flex;
        flex-direction: column;
        align-items: center;
        gap: 0;
        position: fixed;
        top: 18px;
        left: 50%;
        z-index: 1200;
        width: min(calc(100vw - 28px), 420px);
        padding: 16px 14px 20px;
        border-radius: 26px;
        overflow: visible;
        transform: translateX(-50%);
    }

    .floating-nav.is-scrolled {
        top: 12px;
        width: min(calc(100vw - 34px), 406px);
        padding: 15px 13px 19px;
        border-radius: 24px;
    }

    .brand {
        order: 1;
    }

    .nav-toggle {
        order: 2;
        display: inline-flex;
        position: absolute;
        left: 50%;
        bottom: -11px;
        margin-top: 0;
        width: 32px;
        height: 32px;
        padding: 0;
        border: 0;
        border-radius: 0;
        background: transparent;
        box-shadow: none;
        backdrop-filter: none;
        -webkit-backdrop-filter: none;
        transform: translateX(-50%);
        animation: navPullElastic 1.55s ease-in-out infinite;
    }

    .floating-nav.is-open .nav-toggle {
        transform: translateX(-50%);
        animation: navPullElastic 1.55s ease-in-out infinite;
    }

    .floating-nav::before,
    .floating-nav::after {
        border-radius: 26px;
    }

    .nav-links,
    .nav-actions {
        width: 100%;
        flex-direction: column;
        align-items: center;
        justify-content: center;
        gap: 10px;
        overflow: hidden;
        max-height: 0;
        opacity: 0;
        transform: translateY(-10px);
        pointer-events: none;
        transition:
            max-height 0.4s ease,
            opacity 0.3s ease,
            transform 0.3s ease,
            margin-top 0.3s ease;
    }

    .nav-links {
        order: 3;
        margin-top: 10px;
    }

    .nav-actions {
        order: 4;
        margin-top: 0;
    }

    .floating-nav.is-open .nav-links,
    .floating-nav.is-open .nav-actions {
        max-height: 260px;
        opacity: 1;
        transform: translateY(0);
        pointer-events: auto;
    }

    .floating-nav.is-open .nav-links {
        margin-top: 12px;
    }

    .floating-nav.is-open .nav-actions {
        margin-top: 6px;
    }

    .nav-link {
        padding: 10px 14px;
        font-size: 0.95rem;
        opacity: 0;
        transform: translateY(-8px);
        transition:
            opacity 0.28s ease,
            transform 0.28s ease,
            background-color 0.25s ease;
    }

    .floating-nav.is-open .nav-links .nav-link,
    .floating-nav.is-open .nav-actions .nav-link {
        opacity: 1;
        transform: translateY(0);
        animation: navDropIn 0.42s ease both;
    }

    .floating-nav.is-open .nav-links .nav-link:nth-child(1) { animation-delay: 60ms; }
    .floating-nav.is-open .nav-links .nav-link:nth-child(2) { animation-delay: 130ms; }
    .floating-nav.is-open .nav-links .nav-link:nth-child(3) { animation-delay: 200ms; }
    .floating-nav.is-open .nav-actions .nav-link:nth-child(1) { animation-delay: 270ms; }
    .floating-nav.is-open .nav-actions .nav-link:nth-child(2) { animation-delay: 340ms; }
    .floating-nav.is-open .nav-actions .nav-link:nth-child(3) { animation-delay: 410ms; }

    .floating-nav.is-open .nav-toggle svg {
        transform: rotate(180deg);
    }

    .site-footer {
        padding: 18px 18px 18px;
    }

    .footer-shell {
        padding: 28px 22px 22px;
        border-radius: 26px;
    }

    .footer-phone-picker__dialog {
        --footer-phone-picker-closed-transform: translateY(calc(-50% + 26px)) scale(0.9);
        --footer-phone-picker-open-transform: translateY(-50%) scale(1);
        width: min(92vw, 360px);
        margin: 0 auto;
        top: 50%;
        padding: 22px 18px 18px;
        border-radius: 24px;
    }

    .floating-quick-actions {
        --floating-action-shift: 58px;
        right: 16px;
        bottom: 18px;
        gap: 12px;
    }

    .floating-quick-actions__button,
    .floating-quick-actions__item {
        width: 46px;
        height: 46px;
    }

    .floating-quick-actions.has-top-visible .floating-quick-actions__button.is-share {
        transform: translateY(calc(-1 * var(--floating-action-shift)));
    }

    .floating-quick-actions.has-top-visible .floating-quick-actions__menu {
        transform: translateY(calc(-1 * var(--floating-action-shift)));
    }

    .footer-top {
        grid-template-columns: 1fr;
        gap: 22px;
        padding-bottom: 24px;
    }

    .footer-copy {
        text-align: center;
    }

    .footer-heading {
        font-size: var(--section-title-size-mobile);
        line-height: 1.06;
    }

    .footer-copy p:last-child {
        font-size: 0.95rem;
        line-height: 1.65;
    }

    .footer-main {
        grid-template-columns: 1fr;
        gap: 18px;
        justify-items: start;
    }

    .footer-socials,
    .footer-meta {
        gap: 16px;
    }

    .footer-brand,
    .footer-socials {
        justify-self: center;
    }

    .footer-bottom {
        flex-direction: column;
        align-items: center;
        gap: 12px;
        text-align: center;
    }

    .footer-meta {
        order: 1;
        flex-direction: column;
        justify-content: center;
        gap: 10px;
    }

    .footer-bottom > div:first-child {
        order: 2;
    }

    .footer-bottom-copy-line {
        display: block;
    }
}

@media (prefers-reduced-motion: reduce) {
    .floating-nav::before,
    .floating-nav::after {
        opacity: 1;
        transform: scaleX(1);
    }

    .brand,
    .nav-links .nav-link,
    .nav-actions .nav-link,
    .nav-toggle {
        animation: none !important;
        transition: none !important;
    }

    .brand,
    .nav-links .nav-link,
    .nav-actions .nav-link {
        opacity: 1;
        transform: none;
    }

    .footer-shell,
    .footer-copy,
    .footer-brand,
    .footer-socials,
    .footer-bottom,
    .footer-social {
        opacity: 1 !important;
        transform: none !important;
        transition: none !important;
    }
}
