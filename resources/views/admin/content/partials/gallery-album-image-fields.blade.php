@php
    $image = $image ?? [];
    $albumIndex = $albumIndex ?? 0;
    $imageIndex = $imageIndex ?? 0;
    $showErrors = $showErrors ?? true;
    $label = $label ?? 'Image '.($imageIndex + 1);
    $inputId = 'gallery-album-'.$albumIndex.'-image-'.$imageIndex;
    $errorPrefix = 'albums.'.$albumIndex.'.images.'.$imageIndex;
    $hasImage = !empty($image['image_url']);
@endphp

<div class="gallery-photo-tile" data-gallery-album-image-card>
    <div class="gallery-photo-tile-preview">
        @if ($hasImage)
            <img src="{{ $image['image_url'] }}" alt="Album photo preview" loading="lazy" decoding="async">
        @else
            <div class="gallery-photo-tile-placeholder">
                <svg width="26" height="26" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.6" stroke-linecap="round" stroke-linejoin="round">
                    <rect x="3" y="3" width="18" height="18" rx="2" ry="2"></rect>
                    <circle cx="8.5" cy="8.5" r="1.5"></circle>
                    <polyline points="21 15 16 10 5 21"></polyline>
                </svg>
                <span>No photo</span>
            </div>
        @endif

        <span class="gallery-photo-tile-badge project-editor-card-title">{{ $label }}</span>

        <button class="gallery-photo-tile-remove" type="button" data-gallery-remove-image data-confirm-message="Are you sure you want to remove this album image? This change will be saved when you submit the form." title="Remove this photo">
            <svg width="13" height="13" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.4" stroke-linecap="round" stroke-linejoin="round">
                <line x1="18" y1="6" x2="6" y2="18"></line>
                <line x1="6" y1="6" x2="18" y2="18"></line>
            </svg>
        </button>
    </div>

    <input type="hidden" name="albums[{{ $albumIndex }}][images][{{ $imageIndex }}][image_path]" value="{{ $image['image_path'] ?? '' }}">

    <div class="gallery-photo-tile-actions">
        <label class="gallery-photo-tile-btn" for="{{ $inputId }}">
            <svg width="13" height="13" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                <path d="M21 15v4a2 2 0 0 1-2 2H5a2 2 0 0 1-2-2v-4"></path>
                <polyline points="17 8 12 3 7 8"></polyline>
                <line x1="12" y1="3" x2="12" y2="15"></line>
            </svg>
            <span>{{ $hasImage ? 'Change Photo' : 'Select Photo' }}</span>
        </label>
        <input class="gallery-photo-tile-file" id="{{ $inputId }}" type="file" name="album_image_uploads[{{ $albumIndex }}][{{ $imageIndex }}]" accept="image/*" data-webp-input>

        <span class="upload-status" data-upload-status>
            <span class="upload-spinner" aria-hidden="true"></span>
            <span data-upload-status-text>Ready</span>
        </span>

        @if ($showErrors)
            @error('album_image_uploads.'.$albumIndex.'.'.$imageIndex)
                <span class="field-error" style="font-size: 0.74rem;">{{ $message }}</span>
            @enderror
            @error($errorPrefix.'.image_path')
                <span class="field-error" style="font-size: 0.74rem;">{{ $message }}</span>
            @enderror
        @endif
    </div>
</div>
