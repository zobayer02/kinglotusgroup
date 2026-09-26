@extends('layouts.app')

@php
    use App\Models\ValuedShareholderSection;

    $pageTitle = filled($valuedShareholderSection?->section_title)
        ? $valuedShareholderSection->section_title
        : ValuedShareholderSection::DEFAULT_SECTION_TITLE;
@endphp

@section('title', $pageTitle . ' | King Lotus International')

@push('styles')
    <style>
        @include('partials.chrome-styles')

        .shareholder-directory-page {
            min-height: 100vh;
            padding: 28px;
            background: var(--section-surface);
        }

        .shareholder-directory-shell {
            width: 100%;
            max-width: 1280px;
            margin: 0 auto;
            display: grid;
            gap: 28px;
        }

        .shareholder-directory-header {
            padding-top: 114px;
            display: grid;
            gap: 14px;
            justify-items: center;
            text-align: center;
        }

        .shareholder-directory-kicker {
            margin: 0;
            color: #000000;
            font-size: 0.9rem;
            font-weight: 700;
            letter-spacing: 0.16em;
            text-transform: uppercase;
        }

        .shareholder-directory-title {
            margin: 0;
            max-width: 980px;
            font-family: var(--font-primary);
            font-size: var(--section-title-size);
            font-weight: 400;
            line-height: 0.98;
            color: #000000;
        }

        .shareholder-directory-subtitle {
            margin: 0;
            max-width: 740px;
            font-family: var(--font-secondary);
            font-size: clamp(0.98rem, 1.35vw, 1.08rem);
            line-height: 1.68;
            color: #000000;
        }

        .shareholder-search-panel {
            width: 100%;
            max-width: 640px;
            margin: 0 auto;
            display: grid;
            gap: 12px;
        }

        .shareholder-search-box {
            position: relative;
            display: flex;
            align-items: center;
            width: 100%;
            background: rgba(255, 255, 255, 0.9);
            border: 1px solid rgba(12, 80, 93, 0.26);
            border-radius: 999px;
            box-shadow:
                0 14px 34px rgba(18, 33, 44, 0.07),
                inset 0 1px 0 rgba(255, 255, 255, 0.95);
            backdrop-filter: blur(14px);
            -webkit-backdrop-filter: blur(14px);
            transition: border-color 0.22s ease, box-shadow 0.22s ease;
        }

        .shareholder-search-box:focus-within {
            border-color: #0c505d;
            box-shadow:
                0 16px 36px rgba(12, 80, 93, 0.14),
                0 0 0 3px rgba(12, 80, 93, 0.12);
        }

        .shareholder-search-icon {
            display: grid;
            place-items: center;
            width: 44px;
            height: 44px;
            padding-left: 18px;
            color: rgba(16, 33, 44, 0.48);
            flex-shrink: 0;
            pointer-events: none;
        }

        .shareholder-search-icon svg {
            width: 18px;
            height: 18px;
        }

        .shareholder-search-input {
            flex: 1;
            min-width: 0;
            height: 50px;
            padding: 0 10px;
            border: none;
            background: transparent;
            font-family: var(--font-secondary);
            font-size: 0.98rem;
            color: #000000;
            outline: none;
        }

        .shareholder-search-input::placeholder {
            color: rgba(16, 33, 44, 0.44);
        }

        .shareholder-search-clear {
            display: inline-flex;
            align-items: center;
            justify-content: center;
            width: 30px;
            height: 30px;
            margin-right: 12px;
            border: none;
            border-radius: 999px;
            background: rgba(16, 33, 44, 0.07);
            color: rgba(16, 33, 44, 0.6);
            cursor: pointer;
            transition: background-color 0.18s ease, color 0.18s ease, transform 0.18s ease;
        }

        .shareholder-search-clear:hover {
            background: rgba(12, 80, 93, 0.18);
            color: #0c505d;
            transform: scale(1.08);
        }

        .shareholder-search-clear svg {
            width: 13px;
            height: 13px;
        }


        /* Compact 6-Column Responsive Grid */
        .shareholder-grid-container {
            position: relative;
            width: 100%;
            min-height: 280px;
        }

        .shareholder-grid {
            display: grid;
            grid-template-columns: repeat(6, minmax(0, 1fr));
            gap: 16px;
            width: 100%;
        }

        /* Compact Card Styling */
        .shareholder-card {
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
            transition: transform 0.24s cubic-bezier(0.16, 1, 0.3, 1), box-shadow 0.24s ease, border-color 0.24s ease;
        }

        .shareholder-card:hover {
            transform: translateY(-3px);
            border-color: rgba(12, 80, 93, 0.54);
            box-shadow: 0 14px 30px rgba(12, 80, 93, 0.12);
        }

        .shareholder-card::before {
            content: "";
            position: absolute;
            inset: 4px;
            border: 1.2px dashed rgba(12, 80, 93, 0.4);
            border-radius: 17px;
            pointer-events: none;
        }

        .shareholder-card-visual {
            position: relative;
            overflow: hidden;
            border-radius: 15px;
            border: 1px solid rgba(255, 255, 255, 0.78);
            background: linear-gradient(180deg, #3b78a4 0%, #2b6c99 100%);
            box-shadow: inset 0 1px 0 rgba(255, 255, 255, 0.22);
        }

        .shareholder-card-visual img {
            display: block;
            width: 100%;
            aspect-ratio: 4 / 5;
            object-fit: cover;
            transition: transform 0.44s cubic-bezier(0.16, 1, 0.3, 1);
            user-select: none;
            -webkit-user-drag: none;
        }

        .shareholder-card:hover .shareholder-card-visual img {
            transform: scale(1.05);
        }

        .shareholder-card-placeholder {
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

        .shareholder-avatar-icon {
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

        .shareholder-avatar-icon svg {
            width: 24px;
            height: 24px;
        }

        .shareholder-placeholder-copy {
            display: flex;
            flex-direction: column;
            align-items: center;
            justify-content: center;
            gap: 2px;
            text-align: center;
            line-height: 1.1;
        }

        .shareholder-placeholder-brand {
            font-family: var(--font-secondary);
            font-size: 0.66rem;
            font-weight: 700;
            letter-spacing: 0.14em;
            text-transform: uppercase;
            color: rgba(255, 255, 255, 0.88);
        }

        .shareholder-placeholder-sub {
            font-family: var(--font-secondary);
            font-size: 0.52rem;
            font-weight: 600;
            letter-spacing: 0.2em;
            text-transform: uppercase;
            color: rgba(255, 255, 255, 0.58);
        }

        .shareholder-card-copy {
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

        .shareholder-card-name {
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

        .shareholder-card-name.is-name-medium {
            font-size: 0.82rem;
            line-height: 1.14;
        }

        .shareholder-card-name.is-name-long {
            font-size: 0.74rem;
            line-height: 1.12;
        }

        .shareholder-card-name.is-name-xlong {
            font-size: 0.66rem;
            line-height: 1.1;
        }

        .shareholder-card-position {
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

        /* Empty State */
        .shareholder-empty-state {
            display: grid;
            place-items: center;
            gap: 14px;
            padding: 56px 24px;
            text-align: center;
            border: 1.5px dashed rgba(178, 193, 204, 0.8);
            border-radius: 26px;
            background: rgba(255, 255, 255, 0.5);
        }

        .shareholder-empty-icon {
            width: 52px;
            height: 52px;
            border-radius: 999px;
            display: grid;
            place-items: center;
            background: rgba(12, 80, 93, 0.08);
            color: #0c505d;
        }

        .shareholder-empty-icon svg {
            width: 26px;
            height: 26px;
        }

        .shareholder-empty-title {
            margin: 0;
            font-family: var(--font-primary);
            font-size: 1.25rem;
            font-weight: 500;
            color: #000000;
        }

        .shareholder-empty-desc {
            margin: 0;
            max-width: 420px;
            font-size: 0.92rem;
            line-height: 1.6;
            color: #000000;
        }

        .shareholder-empty-reset {
            display: inline-flex;
            align-items: center;
            justify-content: center;
            min-height: 40px;
            padding: 0 20px;
            border-radius: 999px;
            border: 1px solid #0c505d;
            background: #0c505d;
            color: #ffffff;
            font-family: var(--font-secondary);
            font-size: 0.88rem;
            font-weight: 600;
            cursor: pointer;
            transition: background-color 0.2s ease, transform 0.2s ease;
        }

        .shareholder-empty-reset:hover {
            background: #093b45;
            transform: translateY(-1px);
        }

        /* Infinite Scroll Sentinel & End Note */
        .shareholder-loader-sentinel {
            display: flex;
            align-items: center;
            justify-content: center;
            padding: 34px 20px;
            min-height: 70px;
        }

        .shareholder-spinner {
            width: 32px;
            height: 32px;
            border: 3px solid rgba(12, 80, 93, 0.18);
            border-top-color: #0c505d;
            border-radius: 50%;
            animation: shareholderSpin 0.72s linear infinite;
        }

        .shareholder-end-note {
            text-align: center;
            font-size: 0.88rem;
            letter-spacing: 0.04em;
            color: #000000;
            padding: 24px 0 12px;
        }

        @keyframes shareholderSpin {
            to {
                transform: rotate(360deg);
            }
        }

        /* Responsive Breakpoints */
        @media (max-width: 1240px) {
            .shareholder-grid {
                grid-template-columns: repeat(5, minmax(0, 1fr));
                gap: 14px;
            }
        }

        @media (max-width: 992px) {
            .shareholder-grid {
                grid-template-columns: repeat(4, minmax(0, 1fr));
                gap: 14px;
            }
        }

        @media (max-width: 768px) {
            .shareholder-directory-page {
                padding: 22px 16px;
            }

            .shareholder-directory-header {
                padding-top: 96px;
                gap: 12px;
            }

            .shareholder-directory-title {
                font-size: var(--section-title-size-mobile);
            }

            .shareholder-grid {
                grid-template-columns: repeat(3, minmax(0, 1fr));
                gap: 12px;
            }

            .shareholder-card {
                padding: 9px 9px 11px;
                border-radius: 18px;
            }

            .shareholder-card::before {
                inset: 3px;
                border-radius: 15px;
            }

            .shareholder-card-visual,
            .shareholder-card-placeholder {
                border-radius: 14px;
            }

            .shareholder-card-copy {
                height: 62px;
                min-height: 62px;
                max-height: 62px;
                padding-top: 6px;
            }

            .shareholder-card-name {
                font-size: 0.86rem;
                max-height: 32px;
            }

            .shareholder-card-name.is-name-medium {
                font-size: 0.78rem;
            }

            .shareholder-card-name.is-name-long {
                font-size: 0.70rem;
            }

            .shareholder-card-name.is-name-xlong {
                font-size: 0.62rem;
            }

            .shareholder-card-position {
                font-size: 0.70rem;
                max-height: 26px;
            }
        }

        @media (max-width: 560px) {
            .shareholder-grid {
                grid-template-columns: repeat(2, minmax(0, 1fr));
                gap: 10px;
            }

            .shareholder-card {
                padding: 8px 8px 10px;
                border-radius: 16px;
            }

            .shareholder-card::before {
                inset: 3px;
                border-radius: 13px;
            }

            .shareholder-card-copy {
                height: 58px;
                min-height: 58px;
                max-height: 58px;
                padding-top: 5px;
            }

            .shareholder-card-name {
                font-size: 0.80rem;
                max-height: 30px;
            }

            .shareholder-card-name.is-name-medium {
                font-size: 0.74rem;
            }

            .shareholder-card-name.is-name-long {
                font-size: 0.66rem;
            }

            .shareholder-card-name.is-name-xlong {
                font-size: 0.60rem;
            }

            .shareholder-card-position {
                font-size: 0.66rem;
                max-height: 24px;
            }
        }

        @media (max-width: 360px) {
            .shareholder-grid {
                grid-template-columns: 1fr;
            }
        }
    </style>
@endpush

@section('content')
    <div class="shareholder-directory-page">
        <div class="shareholder-directory-shell">
            @include('partials.navbar')

            <header class="shareholder-directory-header" id="shareholder-directory-top">
                <p class="shareholder-directory-kicker">Partners &amp; Shareholders</p>
                <h1 class="shareholder-directory-title">{{ $pageTitle }}</h1>
                <p class="shareholder-directory-subtitle">
                    Honoring the esteemed partners and shareholders shaping the visionary growth and trusted future of King Lotus International.
                </p>
            </header>

            <section class="shareholder-search-panel" aria-label="Shareholder directory filters">
                <div class="shareholder-search-box">
                    <span class="shareholder-search-icon" aria-hidden="true">
                        <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.2" stroke-linecap="round" stroke-linejoin="round">
                            <circle cx="11" cy="11" r="8"></circle>
                            <line x1="21" y1="21" x2="16.65" y2="16.65"></line>
                        </svg>
                    </span>

                    <input
                        type="text"
                        class="shareholder-search-input"
                        id="shareholder-search-input"
                        value="{{ $initialSearch }}"
                        placeholder="Search by name or position..."
                        aria-label="Search shareholders by name or position"
                    >

                    <button
                        type="button"
                        class="shareholder-search-clear"
                        id="shareholder-search-clear"
                        aria-label="Clear search"
                        style="{{ filled($initialSearch) ? '' : 'display: none;' }}"
                    >
                        <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.4" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true">
                            <line x1="18" y1="6" x2="6" y2="18"></line>
                            <line x1="6" y1="6" x2="18" y2="18"></line>
                        </svg>
                    </button>
                </div>
            </section>

            <main class="shareholder-grid-container" id="shareholder-grid-container">
                <div class="shareholder-grid" id="shareholder-grid" aria-label="Shareholders Directory">
                    @foreach ($initialShareholders as $shareholder)
                        @php
                            $sName = trim($shareholder->name ?? '');
                            $nameLen = mb_strlen($sName);
                            $nameClass = $nameLen > 34 ? 'is-name-xlong' : ($nameLen > 22 ? 'is-name-long' : ($nameLen > 15 ? 'is-name-medium' : ''));
                        @endphp
                        <article class="shareholder-card" data-shareholder-card>
                            <div class="shareholder-card-visual">
                                @if (filled($shareholder->imageUrl()))
                                    <img
                                        src="{{ $shareholder->imageUrl() }}"
                                        alt="{{ $sName ?: 'Shareholder' }}"
                                        loading="lazy"
                                        decoding="async"
                                        draggable="false"
                                        onerror="this.style.display='none'; if(this.nextElementSibling) this.nextElementSibling.style.display='flex';"
                                    >
                                    <div class="shareholder-card-placeholder" aria-label="Shareholder avatar" style="display: none;">
                                        <div class="shareholder-avatar-icon" aria-hidden="true">
                                            <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.6" stroke-linecap="round" stroke-linejoin="round">
                                                <path d="M20 21v-2a4 4 0 0 0-4-4H8a4 4 0 0 0-4 4v2"></path>
                                                <circle cx="12" cy="7" r="4"></circle>
                                            </svg>
                                        </div>
                                        <div class="shareholder-placeholder-copy">
                                            <span class="shareholder-placeholder-brand">King Lotus</span>
                                            <span class="shareholder-placeholder-sub">International</span>
                                        </div>
                                    </div>
                                @else
                                    <div class="shareholder-card-placeholder" aria-label="Shareholder avatar">
                                        <div class="shareholder-avatar-icon" aria-hidden="true">
                                            <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.6" stroke-linecap="round" stroke-linejoin="round">
                                                <path d="M20 21v-2a4 4 0 0 0-4-4H8a4 4 0 0 0-4 4v2"></path>
                                                <circle cx="12" cy="7" r="4"></circle>
                                            </svg>
                                        </div>
                                        <div class="shareholder-placeholder-copy">
                                            <span class="shareholder-placeholder-brand">King Lotus</span>
                                            <span class="shareholder-placeholder-sub">International</span>
                                        </div>
                                    </div>
                                @endif
                            </div>

                            <div class="shareholder-card-copy">
                                @if (filled($sName))
                                    <h2 class="shareholder-card-name {{ $nameClass }}">{{ $sName }}</h2>
                                @endif
                                @if (filled($shareholder->position))
                                    <span class="shareholder-card-position">{{ $shareholder->position }}</span>
                                @endif
                            </div>
                        </article>
                    @endforeach
                </div>

                <div
                    class="shareholder-empty-state"
                    id="shareholder-empty-state"
                    style="{{ $initialShareholders->isEmpty() ? '' : 'display: none;' }}"
                >
                    <div class="shareholder-empty-icon" aria-hidden="true">
                        <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                            <circle cx="11" cy="11" r="8"></circle>
                            <line x1="21" y1="21" x2="16.65" y2="16.65"></line>
                            <line x1="8" y1="11" x2="14" y2="11"></line>
                        </svg>
                    </div>
                    <h3 class="shareholder-empty-title">No Shareholders Found</h3>
                    <p class="shareholder-empty-desc">
                        We couldn't find any shareholders matching your search. Please check the spelling or try searching another title.
                    </p>
                    <button type="button" class="shareholder-empty-reset" id="shareholder-empty-reset">
                        Reset Search
                    </button>
                </div>

                <div class="shareholder-loader-sentinel" id="shareholder-sentinel" style="{{ $hasMore ? '' : 'display: none;' }}">
                    <div class="shareholder-spinner" aria-label="Loading more shareholders"></div>
                </div>

                <div
                    class="shareholder-end-note"
                    id="shareholder-end-note"
                    style="{{ (! $hasMore && $initialShareholders->isNotEmpty()) ? '' : 'display: none;' }}"
                >
                    &bull; You have reached the end of the shareholders directory &bull;
                </div>
            </main>
        </div>

        @include('partials.footer')
    </div>
@endsection

@push('scripts')
    @include('partials.mobile-nav-script')
    <script>
        document.addEventListener('DOMContentLoaded', () => {
            const searchInput = document.getElementById('shareholder-search-input');
            const clearBtn = document.getElementById('shareholder-search-clear');
            const resetBtn = document.getElementById('shareholder-empty-reset');
            const grid = document.getElementById('shareholder-grid');
            const emptyState = document.getElementById('shareholder-empty-state');
            const sentinel = document.getElementById('shareholder-sentinel');
            const endNote = document.getElementById('shareholder-end-note');

            const itemsUrl = @json(route('shareholders.items'));
            let currentPage = 1;
            let hasMore = @json($hasMore);
            let isLoading = false;
            let searchDebounceTimer = null;
            let currentAbortController = null;
            let loadedShareholderIds = new Set(
                @json($initialShareholders->pluck('id')->all())
            );

            const escapeHtml = (text) => {
                if (!text) return '';
                const map = {
                    '&': '&amp;',
                    '<': '&lt;',
                    '>': '&gt;',
                    '"': '&quot;',
                    "'": '&#039;'
                };
                return String(text).replace(/[&<>"']/g, (m) => map[m]);
            };

            const autoFitShareholderNames = (root = document) => {
                const names = root.querySelectorAll('.shareholder-card-name');
                names.forEach((el) => {
                    el.style.fontSize = '';
                    el.style.lineHeight = '';

                    const copyBox = el.closest('.shareholder-card-copy');
                    const maxAllowed = copyBox ? Math.floor(copyBox.clientHeight * 0.54) : 34;
                    let size = parseFloat(window.getComputedStyle(el).fontSize);
                    const minSize = 9.5;

                    while (el.scrollHeight > maxAllowed && size > minSize) {
                        size -= 0.5;
                        el.style.fontSize = `${size}px`;
                        el.style.lineHeight = '1.12';
                    }
                });
            };

            const buildCardHtml = (item) => {
                const safeName = escapeHtml(item.name || '');
                const safePosition = escapeHtml(item.position || '');
                const imageTag = item.image_url
                    ? `<img src="${escapeHtml(item.image_url)}" alt="${safeName || 'Shareholder'}" loading="lazy" decoding="async" draggable="false" onerror="this.style.display='none'; if(this.nextElementSibling) this.nextElementSibling.style.display='flex';">
                       <div class="shareholder-card-placeholder" aria-label="Shareholder avatar" style="display: none;">
                           <div class="shareholder-avatar-icon" aria-hidden="true">
                               <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.6" stroke-linecap="round" stroke-linejoin="round">
                                   <path d="M20 21v-2a4 4 0 0 0-4-4H8a4 4 0 0 0-4 4v2"></path>
                                   <circle cx="12" cy="7" r="4"></circle>
                               </svg>
                           </div>
                           <div class="shareholder-placeholder-copy">
                               <span class="shareholder-placeholder-brand">King Lotus</span>
                               <span class="shareholder-placeholder-sub">International</span>
                           </div>
                       </div>`
                    : `<div class="shareholder-card-placeholder" aria-label="Shareholder avatar">
                        <div class="shareholder-avatar-icon" aria-hidden="true">
                            <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.6" stroke-linecap="round" stroke-linejoin="round">
                                <path d="M20 21v-2a4 4 0 0 0-4-4H8a4 4 0 0 0-4 4v2"></path>
                                <circle cx="12" cy="7" r="4"></circle>
                            </svg>
                        </div>
                        <div class="shareholder-placeholder-copy">
                            <span class="shareholder-placeholder-brand">King Lotus</span>
                            <span class="shareholder-placeholder-sub">International</span>
                        </div>
                    </div>`;

                const nameLen = safeName.length;
                const nameClass = nameLen > 34 ? 'is-name-xlong' : (nameLen > 22 ? 'is-name-long' : (nameLen > 15 ? 'is-name-medium' : ''));
                const nameTag = safeName ? `<h2 class="shareholder-card-name ${nameClass}">${safeName}</h2>` : '';
                const positionTag = safePosition ? `<span class="shareholder-card-position">${safePosition}</span>` : '';

                return `
                    <article class="shareholder-card" data-shareholder-card>
                        <div class="shareholder-card-visual">
                            ${imageTag}
                        </div>
                        <div class="shareholder-card-copy">
                            ${nameTag}
                            ${positionTag}
                        </div>
                    </article>
                `;
            };

            const updateUrlSearchParam = (term) => {
                const url = new URL(window.location.href);
                if (term) {
                    url.searchParams.set('search', term);
                } else {
                    url.searchParams.delete('search');
                }
                window.history.replaceState({}, '', url.toString());
            };

            const fetchPage = async (pageToFetch, reset = false) => {
                if (isLoading) return;
                isLoading = true;

                if (currentAbortController) {
                    currentAbortController.abort();
                }
                currentAbortController = new AbortController();

                const searchTerm = searchInput.value.trim();
                sentinel.style.display = 'flex';
                endNote.style.display = 'none';

                try {
                    const fetchUrl = new URL(itemsUrl, window.location.origin);
                    fetchUrl.searchParams.set('page', pageToFetch);
                    if (searchTerm) {
                        fetchUrl.searchParams.set('search', searchTerm);
                    }

                    const response = await fetch(fetchUrl.toString(), {
                        headers: {
                            'Accept': 'application/json',
                            'X-Requested-With': 'XMLHttpRequest'
                        },
                        signal: currentAbortController.signal
                    });

                    if (!response.ok) {
                        throw new Error(`Request failed with status ${response.status}`);
                    }

                    const result = await response.json();
                    const items = Array.isArray(result.data) ? result.data : [];
                    const total = Number(result.total) || 0;
                    hasMore = Boolean(result.has_more);
                    currentPage = Number(result.current_page) || pageToFetch;

                    if (reset) {
                        grid.innerHTML = '';
                        loadedShareholderIds.clear();
                    }

                    const fragment = document.createDocumentFragment();
                    const tempDiv = document.createElement('div');

                    items.forEach((item) => {
                        if (item.id && loadedShareholderIds.has(item.id)) {
                            return;
                        }
                        if (item.id) {
                            loadedShareholderIds.add(item.id);
                        }
                        tempDiv.innerHTML = buildCardHtml(item);
                        const cardNode = tempDiv.firstElementChild;
                        if (cardNode) {
                            fragment.appendChild(cardNode);
                        }
                    });

                    grid.appendChild(fragment);
                    autoFitShareholderNames(grid);

                    const currentLoadedCount = grid.querySelectorAll('[data-shareholder-card]').length;

                    if (currentLoadedCount === 0) {
                        emptyState.style.display = 'grid';
                        sentinel.style.display = 'none';
                        endNote.style.display = 'none';
                    } else {
                        emptyState.style.display = 'none';
                        sentinel.style.display = hasMore ? 'flex' : 'none';
                        endNote.style.display = hasMore ? 'none' : 'block';
                    }
                } catch (err) {
                    if (err.name !== 'AbortError') {
                        console.error('Failed to load shareholders:', err);
                    }
                } finally {
                    isLoading = false;
                }
            };

            const handleSearchChange = () => {
                const term = searchInput.value.trim();
                clearBtn.style.display = term.length > 0 ? 'inline-flex' : 'none';
                updateUrlSearchParam(term);

                if (searchDebounceTimer) {
                    clearTimeout(searchDebounceTimer);
                }

                searchDebounceTimer = setTimeout(() => {
                    fetchPage(1, true);
                }, 300);
            };

            searchInput.addEventListener('input', handleSearchChange);

            clearBtn.addEventListener('click', () => {
                searchInput.value = '';
                searchInput.focus();
                handleSearchChange();
            });

            if (resetBtn) {
                resetBtn.addEventListener('click', () => {
                    searchInput.value = '';
                    searchInput.focus();
                    handleSearchChange();
                });
            }

            const observer = new IntersectionObserver((entries) => {
                const first = entries[0];
                if (first && first.isIntersecting && hasMore && !isLoading) {
                    fetchPage(currentPage + 1, false);
                }
            }, {
                rootMargin: '250px',
                threshold: 0.05
            });

            observer.observe(sentinel);

            // Initial auto-fit for server-rendered cards
            autoFitShareholderNames(grid);

            let resizeTimer = null;
            window.addEventListener('resize', () => {
                clearTimeout(resizeTimer);
                resizeTimer = setTimeout(() => {
                    autoFitShareholderNames(grid);
                }, 150);
            });
        });
    </script>
@endpush
