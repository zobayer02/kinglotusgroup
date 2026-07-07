@php
    $imageUrl = $card['image_url'] ?? null;
@endphp

<div class="{{ $cardClass }}">
    @if ($imageUrl)
        <img src="{{ $imageUrl }}" alt="{{ $card['title'] ?: 'Project card image' }}" loading="lazy" decoding="async">
    @endif

    <div class="project-card-body">
        <div class="project-copy">
            @if (filled($card['title'] ?? null))
                <p class="project-name">{{ $card['title'] }}</p>
            @endif

            @if (filled($card['location'] ?? null))
                <span class="project-location">{{ $card['location'] }}</span>
            @endif
        </div>

        <span class="project-arrow" aria-hidden="true">
            <svg viewBox="0 0 24 24" fill="none">
                <path d="M8 16L16 8M16 8H9.5M16 8V14.5" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"/>
            </svg>
        </span>
    </div>
</div>
