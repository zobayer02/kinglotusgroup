@extends('layouts.app')

@section('title', 'Frequently Asked Questions | King Lotus International')

@push('styles')
    <style>
        @include('partials.chrome-styles')

        .faq-page {
            background: var(--section-surface);
            min-height: 100vh;
        }

        /* Nav bar clearance on desktop & responsive screens */
        .faq-hero {
            padding: clamp(110px, 11vw, 140px) 28px 48px;
            box-sizing: border-box;
        }

        .faq-shell {
            width: 100%;
            max-width: 1240px;
            margin: 0 auto;
            display: grid;
            gap: 26px;
        }

        .faq-card {
            position: relative;
            overflow: hidden;
            width: calc(100% - 56px);
            max-width: 1000px;
            margin: 0 auto;
            border-radius: 34px;
            border: 1px solid rgba(190, 205, 214, 0.68);
            background:
                linear-gradient(180deg, rgba(255, 255, 255, 0.22) 0%, rgba(255, 255, 255, 0.08) 100%),
                linear-gradient(180deg, rgba(220, 236, 245, 0.9) 0%, rgba(205, 225, 236, 0.84) 100%);
            box-shadow:
                0 28px 80px rgba(24, 43, 56, 0.12),
                inset 0 1px 0 rgba(255, 255, 255, 0.5);
            padding: 24px;
        }

        .faq-content {
            padding: clamp(36px, 4vw, 56px) clamp(16px, 3.5vw, 48px) clamp(28px, 4vw, 44px);
        }

        .faq-header {
            display: grid;
            justify-items: center;
            text-align: center;
            gap: 12px;
            margin-bottom: 34px;
        }

        .faq-kicker {
            margin: 0;
            font-family: var(--font-secondary);
            font-size: 0.92rem;
            font-weight: 700;
            letter-spacing: 0.18em;
            text-transform: uppercase;
            color: #000000;
            text-align: center;
        }

        .faq-title {
            margin: 0;
            font-family: var(--font-primary);
            font-size: clamp(2rem, 3.8vw, 3.6rem);
            font-weight: 500;
            line-height: 1.04;
            color: #000000;
            text-align: center;
        }

        .faq-subtitle {
            max-width: 680px;
            margin: 0;
            font-family: var(--font-secondary);
            font-size: clamp(1rem, 1.45vw, 1.15rem);
            line-height: 1.68;
            color: #000000;
            text-align: center;
        }

        /* Search input bar */
        .faq-search-wrap {
            max-width: 620px;
            margin: 0 auto 32px;
            position: relative;
        }

        .faq-search-box {
            display: flex;
            align-items: center;
            min-height: 52px;
            border-radius: 999px;
            border: 1px solid rgba(12, 80, 93, 0.3);
            background: rgba(255, 255, 255, 0.88);
            box-shadow: 0 10px 24px rgba(18, 33, 44, 0.05);
            padding: 0 18px;
            transition: border-color 0.2s ease, box-shadow 0.2s ease;
        }

        .faq-search-box:focus-within {
            border-color: #0c505d;
            box-shadow: 0 0 0 4px rgba(12, 80, 93, 0.15);
        }

        .faq-search-icon {
            display: inline-flex;
            align-items: center;
            justify-content: center;
            color: #0c505d;
            margin-right: 12px;
            flex-shrink: 0;
        }

        .faq-search-input {
            flex: 1;
            min-width: 0;
            border: none;
            outline: none;
            background: transparent;
            font-family: var(--font-secondary);
            font-size: 0.98rem;
            color: #000000;
        }

        .faq-search-input::placeholder {
            color: rgba(16, 33, 44, 0.48);
        }

        .faq-search-clear {
            display: none;
            align-items: center;
            justify-content: center;
            width: 26px;
            height: 26px;
            border-radius: 50%;
            border: none;
            background: rgba(16, 33, 44, 0.1);
            color: #000000;
            cursor: pointer;
            margin-left: 8px;
            transition: background 0.18s ease;
        }

        .faq-search-clear:hover {
            background: rgba(12, 80, 93, 0.22);
            color: #0c505d;
        }

        /* FAQ Accordion List - Box Shadows Removed */
        .faq-list {
            display: grid;
            gap: 16px;
        }

        .faq-item {
            border-radius: 20px;
            border: 1px solid rgba(12, 80, 93, 0.26);
            background: rgba(255, 255, 255, 0.72);
            box-shadow: none !important;
            overflow: hidden;
            transition:
                border-color 0.24s ease,
                background-color 0.24s ease,
                transform 0.24s ease;
        }

        .faq-item:hover {
            border-color: rgba(12, 80, 93, 0.52);
            background: rgba(255, 255, 255, 0.88);
            transform: translateY(-2px);
            box-shadow: none !important;
        }

        .faq-item.is-open {
            border-color: #0c505d;
            background: rgba(255, 255, 255, 0.96);
            box-shadow: none !important;
        }

        .faq-question-btn {
            display: flex;
            align-items: center;
            justify-content: space-between;
            gap: 16px;
            width: 100%;
            padding: 20px 24px;
            border: none;
            background: transparent;
            cursor: pointer;
            text-align: left;
            font-family: var(--font-primary);
            font-size: clamp(1.02rem, 1.5vw, 1.18rem);
            font-weight: 600;
            line-height: 1.35;
            color: #000000;
            user-select: none;
        }

        .faq-question-btn:focus-visible {
            outline: 2px solid #0c505d;
            outline-offset: -2px;
            border-radius: 20px;
        }

        .faq-icon-indicator {
            display: inline-flex;
            align-items: center;
            justify-content: center;
            width: 34px;
            height: 34px;
            border-radius: 50%;
            background: rgba(12, 80, 93, 0.1);
            color: #0c505d;
            flex-shrink: 0;
            transition: transform 0.26s cubic-bezier(0.16, 1, 0.3, 1), background-color 0.22s ease, color 0.22s ease;
        }

        .faq-item.is-open .faq-icon-indicator {
            transform: rotate(180deg);
            background: #0c505d;
            color: #ffffff;
        }

        .faq-answer-panel {
            max-height: 0;
            overflow: hidden;
            box-shadow: none !important;
            transition: max-height 0.32s cubic-bezier(0.16, 1, 0.3, 1), opacity 0.28s ease;
            opacity: 0;
        }

        .faq-item.is-open .faq-answer-panel {
            opacity: 1;
        }

        .faq-answer-content {
            padding: 0 24px 22px;
            font-family: var(--font-secondary);
            font-size: clamp(0.96rem, 1.35vw, 1.05rem);
            line-height: 1.82;
            color: #000000;
            text-align: justify;
            text-justify: inter-word;
            box-shadow: none !important;
        }

        .faq-answer-content p {
            margin: 0 0 12px;
        }

        .faq-answer-content p:last-child {
            margin-bottom: 0;
        }

        /* Fade-in animation for scroll pagination loaded items */
        @keyframes faqFadeIn {
            from {
                opacity: 0;
                transform: translateY(12px);
            }
            to {
                opacity: 1;
                transform: translateY(0);
            }
        }

        .faq-item.is-appearing {
            animation: faqFadeIn 0.32s ease forwards;
        }

        @keyframes faqSpin {
            to { transform: rotate(360deg); }
        }

        .faq-loader-spinner {
            width: 18px;
            height: 18px;
            border: 2px solid rgba(12, 80, 93, 0.2);
            border-top-color: #0c505d;
            border-radius: 50%;
            display: inline-block;
            animation: faqSpin 0.75s linear infinite;
        }

        /* Empty state */
        .faq-empty-state {
            display: none;
            text-align: center;
            padding: 48px 24px;
            border-radius: 22px;
            border: 1.5px dashed rgba(12, 80, 93, 0.32);
            background: rgba(255, 255, 255, 0.5);
        }

        .faq-empty-icon {
            width: 52px;
            height: 52px;
            border-radius: 50%;
            background: rgba(12, 80, 93, 0.1);
            color: #0c505d;
            display: inline-flex;
            align-items: center;
            justify-content: center;
            margin-bottom: 14px;
        }

        .faq-empty-title {
            margin: 0 0 8px;
            font-family: var(--font-primary);
            font-size: 1.25rem;
            color: #000000;
        }

        .faq-empty-desc {
            margin: 0;
            font-size: 0.94rem;
            line-height: 1.6;
            color: #000000;
        }

        /* Footer CTA */
        .faq-cta-box {
            margin-top: 38px;
            padding: 24px 28px;
            border-radius: 24px;
            background: rgba(255, 255, 255, 0.78);
            border: 1px solid rgba(12, 80, 93, 0.22);
            display: flex;
            align-items: center;
            justify-content: space-between;
            gap: 20px;
            box-shadow: 0 12px 28px rgba(18, 33, 44, 0.05);
        }

        .faq-cta-title {
            margin: 0 0 4px;
            font-family: var(--font-primary);
            font-size: 1.15rem;
            font-weight: 600;
            color: #000000;
        }

        .faq-cta-desc {
            margin: 0;
            font-size: 0.94rem;
            color: #000000;
        }

        .faq-cta-btn {
            display: inline-flex;
            align-items: center;
            gap: 8px;
            min-height: 46px;
            padding: 0 24px;
            border-radius: 999px;
            border: 1px solid #0c505d;
            background: #0c505d;
            color: #ffffff;
            font-family: var(--font-secondary);
            font-size: 0.94rem;
            font-weight: 600;
            cursor: pointer;
            white-space: nowrap;
            transition: background 0.2s ease, transform 0.2s ease, box-shadow 0.2s ease;
        }

        .faq-cta-btn:hover {
            background: #083c46;
            transform: translateY(-1px);
            box-shadow: 0 8px 20px rgba(12, 80, 93, 0.25);
        }

        @media (max-width: 768px) {
            .faq-hero {
                padding: 108px 14px 24px;
            }

            .faq-card {
                width: 100%;
                border-radius: 26px;
                padding: 16px;
            }

            .faq-content {
                padding: 24px 8px 16px;
            }

            .faq-question-btn {
                padding: 16px 18px;
                font-size: 1rem;
            }

            .faq-answer-content {
                padding: 0 18px 18px;
            }

            .faq-cta-box {
                flex-direction: column;
                text-align: center;
                gap: 16px;
                padding: 20px 18px;
            }

            .faq-cta-btn {
                width: 100%;
                justify-content: center;
            }
        }

        @media (max-width: 520px) {
            .faq-hero {
                padding: 104px 10px 20px;
            }

            .faq-card {
                border-radius: 20px;
                padding: 12px;
            }

            .faq-content {
                padding: 18px 4px 12px;
            }

            .faq-search-box {
                min-height: 48px;
                padding: 0 14px;
            }
        }
    </style>
@endpush

@section('content')
    <div class="faq-page">
        <section class="faq-hero">
            <div class="faq-shell">
                @include('partials.navbar')

                <article class="faq-card">
                    <div class="faq-content">
                        <header class="faq-header">
                            <p class="faq-kicker">Frequently Asked Questions</p>
                            <h1 class="faq-title">Everything You Need to Know</h1>
                            <p class="faq-subtitle">
                                Clear answers about King Lotus hotel share ownership, shareholder benefits, dividend payouts, and investment security.
                            </p>
                        </header>

                        <div class="faq-search-wrap">
                            <div class="faq-search-box">
                                <span class="faq-search-icon" aria-hidden="true">
                                    <svg width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                                        <circle cx="11" cy="11" r="8"></circle>
                                        <line x1="21" y1="21" x2="16.65" y2="16.65"></line>
                                    </svg>
                                </span>
                                <input type="text" class="faq-search-input" id="faqSearchInput" placeholder="Search a question or topic..." aria-label="Search frequently asked questions">
                                <button type="button" class="faq-search-clear" id="faqSearchClear" aria-label="Clear search">
                                    <svg width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round">
                                        <line x1="18" y1="6" x2="6" y2="18"></line>
                                        <line x1="6" y1="6" x2="18" y2="18"></line>
                                    </svg>
                                </button>
                            </div>
                        </div>

                        <div class="faq-list" id="faqList" data-items-url="{{ route('faq.items') }}">
                            @forelse ($faqs as $index => $faq)
                                <div class="faq-item" data-faq-item data-faq-id="{{ $faq->id }}" data-question="{{ strtolower($faq->question) }}" data-answer="{{ strtolower(strip_tags($faq->answer)) }}">
                                    <button class="faq-question-btn" type="button" aria-expanded="false" aria-controls="faq-answer-{{ $faq->id }}" data-faq-toggle>
                                        <span>{{ $faq->question }}</span>
                                        <span class="faq-icon-indicator" aria-hidden="true">
                                            <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round">
                                                <polyline points="6 9 12 15 18 9"></polyline>
                                            </svg>
                                        </span>
                                    </button>
                                    <div class="faq-answer-panel" id="faq-answer-{{ $faq->id }}" data-faq-panel>
                                        <div class="faq-answer-content">
                                            <p>{!! nl2br(e($faq->answer)) !!}</p>
                                        </div>
                                    </div>
                                </div>
                            @empty
                                <div class="faq-empty-state" id="faqInitialEmpty" style="display: block;">
                                    <span class="faq-empty-icon" aria-hidden="true">
                                        <svg width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><circle cx="12" cy="12" r="10"></circle><line x1="12" y1="8" x2="12" y2="12"></line><line x1="12" y1="16" x2="12.01" y2="16"></line></svg>
                                    </span>
                                    <h3 class="faq-empty-title">No FAQs Available</h3>
                                    <p class="faq-empty-desc">Frequently asked questions will be published here soon.</p>
                                </div>
                            @endforelse
                        </div>

                        {{-- Infinite Scroll Sentinel --}}
                        <div id="faqScrollSentinel" data-has-more="{{ $hasMore ? 'true' : 'false' }}" data-current-page="1" style="min-height: 24px; display: {{ $hasMore ? 'flex' : 'none' }}; justify-content: center; align-items: center; margin: 24px 0;">
                            <div id="faqScrollLoader" style="display: none; align-items: center; gap: 8px; color: #0c505d; font-size: 0.88rem; font-weight: 500;">
                                <span class="faq-loader-spinner" aria-hidden="true"></span>
                                <span>Loading more questions...</span>
                            </div>
                        </div>

                        <div class="faq-empty-state" id="faqSearchEmpty">
                            <span class="faq-empty-icon" aria-hidden="true">
                                <svg width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><circle cx="11" cy="11" r="8"></circle><line x1="21" y1="21" x2="16.65" y2="16.65"></line></svg>
                            </span>
                            <h3 class="faq-empty-title">No matching questions found</h3>
                            <p class="faq-empty-desc">Try searching with different keywords, or contact our support team directly.</p>
                        </div>

                        <div class="faq-cta-box">
                            <div>
                                <h3 class="faq-cta-title">Still have more questions?</h3>
                                <p class="faq-cta-desc">Our dedicated shareholder advisory team is available to assist you personally.</p>
                            </div>
                            @if (count($footerSetting?->phoneOptions() ?? []))
                                <button class="faq-cta-btn" type="button" data-phone-trigger aria-haspopup="dialog" aria-expanded="false">
                                    <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.2" stroke-linecap="round" stroke-linejoin="round">
                                        <path d="M22 16.92v3a2 2 0 0 1-2.18 2 19.79 19.79 0 0 1-8.63-3.07 19.5 19.5 0 0 1-6-6 19.79 19.79 0 0 1-3.07-8.67A2 2 0 0 1 4.11 2h3a2 2 0 0 1 2 1.72 12.84 12.84 0 0 0 .7 2.81 2 2 0 0 1-.45 2.11L8.09 9.91a16 16 0 0 0 6 6l1.27-1.27a2 2 0 0 1 2.11-.45 12.84 12.84 0 0 0 2.81.7A2 2 0 0 1 22 16.92z"></path>
                                    </svg>
                                    <span>Contact Advisory</span>
                                </button>
                            @else
                                <a class="faq-cta-btn" href="{{ route('home') }}#contact">
                                    <span>Contact Advisory</span>
                                </a>
                            @endif
                        </div>
                    </div>
                </article>
            </div>
        </section>

        @include('partials.footer')
    </div>
@endsection

@push('scripts')
    @include('partials.mobile-nav-script')

    <script>
        document.addEventListener('DOMContentLoaded', () => {
            const faqList = document.getElementById('faqList');
            const itemsUrl = faqList?.dataset.itemsUrl || '{{ route('faq.items') }}';
            const sentinel = document.getElementById('faqScrollSentinel');
            const loader = document.getElementById('faqScrollLoader');
            const searchInput = document.getElementById('faqSearchInput');
            const clearBtn = document.getElementById('faqSearchClear');
            const searchEmpty = document.getElementById('faqSearchEmpty');
            const initialEmpty = document.getElementById('faqInitialEmpty');

            let currentPage = parseInt(sentinel?.dataset.currentPage || '1', 10);
            let hasMore = sentinel?.dataset.hasMore === 'true';
            let isLoading = false;
            let currentSearch = '';
            let searchDebounceTimer = null;
            const loadedFaqIds = new Set();

            // Track initially loaded items
            document.querySelectorAll('[data-faq-item]').forEach((item) => {
                const id = item.dataset.faqId;
                if (id) loadedFaqIds.add(id);
                bindFaqAccordion(item);
            });

            function bindFaqAccordion(item) {
                const btn = item.querySelector('[data-faq-toggle]');
                const panel = item.querySelector('[data-faq-panel]');
                if (!btn || !panel || btn.dataset.bound === 'true') return;

                btn.dataset.bound = 'true';
                btn.addEventListener('click', () => {
                    const isOpen = item.classList.contains('is-open');

                    // Smooth single-open accordion behavior
                    document.querySelectorAll('[data-faq-item]').forEach((other) => {
                        if (other !== item && other.classList.contains('is-open')) {
                            other.classList.remove('is-open');
                            const otherBtn = other.querySelector('[data-faq-toggle]');
                            const otherPanel = other.querySelector('[data-faq-panel]');
                            if (otherBtn) otherBtn.setAttribute('aria-expanded', 'false');
                            if (otherPanel) otherPanel.style.maxHeight = '0px';
                        }
                    });

                    if (isOpen) {
                        item.classList.remove('is-open');
                        btn.setAttribute('aria-expanded', 'false');
                        panel.style.maxHeight = '0px';
                    } else {
                        item.classList.add('is-open');
                        btn.setAttribute('aria-expanded', 'true');
                        panel.style.maxHeight = panel.scrollHeight + 'px';
                    }
                });
            }

            function escapeHtml(text) {
                const div = document.createElement('div');
                div.textContent = text || '';
                return div.innerHTML;
            }

            function createFaqElement(faq) {
                const item = document.createElement('div');
                item.className = 'faq-item is-appearing';
                item.dataset.faqItem = '';
                item.dataset.faqId = faq.id;
                item.dataset.question = (faq.question || '').toLowerCase();
                item.dataset.answer = (faq.answer || '').toLowerCase();

                const safeQuestion = escapeHtml(faq.question);
                const safeAnswer = escapeHtml(faq.answer).replace(/\n/g, '<br>');

                item.innerHTML = `
                    <button class="faq-question-btn" type="button" aria-expanded="false" aria-controls="faq-answer-${faq.id}" data-faq-toggle>
                        <span>${safeQuestion}</span>
                        <span class="faq-icon-indicator" aria-hidden="true">
                            <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round">
                                <polyline points="6 9 12 15 18 9"></polyline>
                            </svg>
                        </span>
                    </button>
                    <div class="faq-answer-panel" id="faq-answer-${faq.id}" data-faq-panel>
                        <div class="faq-answer-content">
                            <p>${safeAnswer}</p>
                        </div>
                    </div>
                `;

                bindFaqAccordion(item);
                return item;
            }

            // Scroll Pagination: Fetch next page
            async function fetchFaqs(pageToFetch, reset = false) {
                if (isLoading) return;
                isLoading = true;

                if (loader) loader.style.display = 'inline-flex';

                try {
                    const url = new URL(itemsUrl, window.location.origin);
                    url.searchParams.set('page', pageToFetch);
                    if (currentSearch) {
                        url.searchParams.set('search', currentSearch);
                    }

                    const response = await fetch(url.toString(), {
                        headers: {
                            'Accept': 'application/json',
                            'X-Requested-With': 'XMLHttpRequest'
                        }
                    });

                    if (!response.ok) throw new Error('Failed to load FAQs');

                    const json = await response.json();
                    const items = Array.isArray(json.data) ? json.data : [];
                    hasMore = Boolean(json.has_more);
                    currentPage = Number(json.current_page) || pageToFetch;

                    if (sentinel) {
                        sentinel.dataset.currentPage = String(currentPage);
                        sentinel.dataset.hasMore = hasMore ? 'true' : 'false';
                        sentinel.style.display = hasMore ? 'flex' : 'none';
                    }

                    if (reset) {
                        faqList.querySelectorAll('[data-faq-item]').forEach(el => el.remove());
                        loadedFaqIds.clear();
                    }

                    if (items.length > 0) {
                        const fragment = document.createDocumentFragment();
                        items.forEach((faq) => {
                            if (faq.id && loadedFaqIds.has(String(faq.id))) return;
                            if (faq.id) loadedFaqIds.add(String(faq.id));
                            fragment.appendChild(createFaqElement(faq));
                        });
                        faqList.appendChild(fragment);
                    }

                    const totalRendered = faqList.querySelectorAll('[data-faq-item]').length;
                    if (searchEmpty) {
                        searchEmpty.style.display = (totalRendered === 0 && currentSearch) ? 'block' : 'none';
                    }
                    if (initialEmpty) {
                        initialEmpty.style.display = (totalRendered === 0 && !currentSearch) ? 'block' : 'none';
                    }
                } catch (err) {
                    console.error('Error fetching FAQs:', err);
                } finally {
                    isLoading = false;
                    if (loader) loader.style.display = 'none';
                }
            }

            // IntersectionObserver for infinite scroll loading
            if (sentinel && 'IntersectionObserver' in window) {
                const observer = new IntersectionObserver((entries) => {
                    const entry = entries[0];
                    if (entry.isIntersecting && hasMore && !isLoading) {
                        fetchFaqs(currentPage + 1, false);
                    }
                }, {
                    root: null,
                    rootMargin: '240px 0px',
                    threshold: 0.1
                });

                observer.observe(sentinel);
            }

            // Search filtering with live query debounce
            if (searchInput) {
                searchInput.addEventListener('input', () => {
                    const term = searchInput.value.trim();
                    currentSearch = term;

                    if (clearBtn) {
                        clearBtn.style.display = term.length > 0 ? 'inline-flex' : 'none';
                    }

                    if (searchDebounceTimer) {
                        clearTimeout(searchDebounceTimer);
                    }

                    searchDebounceTimer = setTimeout(() => {
                        fetchFaqs(1, true);
                    }, 280);
                });

                if (clearBtn) {
                    clearBtn.addEventListener('click', () => {
                        searchInput.value = '';
                        currentSearch = '';
                        clearBtn.style.display = 'none';
                        fetchFaqs(1, true);
                        searchInput.focus();
                    });
                }
            }
        });
    </script>
@endpush
