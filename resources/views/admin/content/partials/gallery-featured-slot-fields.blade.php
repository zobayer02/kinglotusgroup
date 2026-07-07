@php
    $slot = $slot ?? [];
    $index = $index ?? 0;
    $showErrors = $showErrors ?? true;
    $label = $label ?? 'Featured Image';
    $inputId = 'featured-gallery-image-'.$index;
    $slotNumber = $index + 1;
@endphp

<div class="project-editor-card gallery-featured-slot">
    <div class="project-editor-card-head">
        <div>
            <h3 class="project-editor-card-title">{{ $label }}</h3>
            <p class="project-editor-card-meta">This fixed slot appears in the homepage gallery mosaic.</p>
        </div>
    </div>

    <input type="hidden" name="featured_images[{{ $index }}][order]" value="{{ $slot['order'] ?? $slotNumber }}">
    <input type="hidden" name="featured_images[{{ $index }}][image_path]" value="{{ $slot['image_path'] ?? '' }}">

    <div class="field-group">
        <label class="field-label" for="{{ $inputId }}">Upload image</label>
        <input class="field-file" id="{{ $inputId }}" type="file" name="featured_image_uploads[{{ $index }}]" accept="image/*" data-webp-input>
        <span class="field-hint">Upload a featured image for slot {{ $slotNumber }}. New upload replaces the current image.</span>
        <span class="upload-status" data-upload-status>
            <span class="upload-spinner" aria-hidden="true"></span>
            <span data-upload-status-text>Select an image to convert to WebP.</span>
        </span>
        @if (!empty($slot['image_url']))
            <div class="gallery-preview-wrap">
                <span class="gallery-preview-title">Current preview</span>
                <div class="thumbnail-preview gallery-preview-image">
                    <img src="{{ $slot['image_url'] }}" alt="Featured gallery image slot {{ $slotNumber }}" loading="lazy" decoding="async">
                </div>
                <span class="gallery-preview-caption">Compact preview only. Full image stays unchanged on the website.</span>
            </div>
        @endif
        @if ($showErrors)
            @error('featured_image_uploads.'.$index)
                <span class="field-error">{{ $message }}</span>
            @enderror
            @error('featured_images.'.$index.'.image_path')
                <span class="field-error">{{ $message }}</span>
            @enderror
        @endif
    </div>
</div>
