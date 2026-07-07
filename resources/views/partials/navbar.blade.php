@php
    $isHome = request()->routeIs('home');
    $isGallery = request()->routeIs('gallery.index');
    $isLogin = request()->routeIs('login');
    $homeUrl = route('home');
@endphp

<nav class="floating-nav" aria-label="Primary navigation" data-mobile-nav data-scrollspy="{{ $isHome ? 'true' : 'false' }}">
    <div class="nav-links">
        <a class="nav-link {{ $isHome ? 'is-current' : '' }}" href="{{ $isHome ? '#home' : route('home') }}" data-nav-link="home">Home</a>
        <a class="nav-link" href="{{ $isHome ? '#about' : $homeUrl . '#about' }}" data-nav-link="about">About Us</a>
        <a class="nav-link" href="{{ $isHome ? '#projects' : $homeUrl . '#projects' }}" data-nav-link="projects">Our Projects</a>
    </div>

    <a class="brand" href="{{ route('home') }}" aria-label="King Lotus Group">
        <span class="brand-top">King Lotus</span>
        <span class="brand-bottom">
            <span class="brand-line" aria-hidden="true"></span>
            <span>Group</span>
            <span class="brand-line" aria-hidden="true"></span>
        </span>
    </a>

    <div class="nav-actions">
        <a class="nav-link {{ $isGallery ? 'is-current' : '' }}" href="{{ $isHome ? '#gallery' : ($isGallery ? route('gallery.index') : $homeUrl . '#gallery') }}" data-nav-link="gallery">Gallery</a>
        <a class="nav-link" href="{{ $isHome ? '#office' : $homeUrl . '#office' }}" data-nav-link="contact">Contact Us</a>
        <a class="nav-link {{ $isLogin ? 'is-current' : '' }}" href="{{ route('login') }}">Login</a>
    </div>

    <button class="nav-toggle" type="button" aria-label="Expand navigation" aria-expanded="false" data-nav-toggle>
        <svg width="24" height="24" viewBox="0 0 24 24" fill="none" aria-hidden="true">
            <path d="M6 9L12 15L18 9" stroke="currentColor" stroke-width="3.2" stroke-linecap="round" stroke-linejoin="round"></path>
        </svg>
    </button>
</nav>
