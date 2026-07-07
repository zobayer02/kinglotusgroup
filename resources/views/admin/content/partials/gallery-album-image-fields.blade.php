@php
    $image = $image ?? [];
    $albumIndex = $albumIndex ?? 0;
    $imageIndex = $imageIndex ?? 0;
    $showErrors = $showErrors ?? true;
    $label = $label ?? 'Album Image';
    $inputId = 'gallery-album-'.$albumIndex.'-image-'.$imageIndex;
    $errorPrefix = 'albums.'.$albumIndex.'.images.'.$imageIndex;
@endphp

<div class="project-editor-card gallery-album-image-card" data-gallery-album-image-card>
    <div class="project-editor-card-head">
        <div>
            <h4 class="project-editor-card-title">{{ $label }}</h4>
            <p class="project-editor-card-meta">This image appears inside the selected album on the gallery page.</p>
        </div>

        <button class="project-editor-remove" type="button" data-gallery-remove-image data-confirm-message="Are you sure you want to remove this album image? This change will be saved when you submit the form.">Remove</button>
    </div>

    <input type="hidden" name="albums[{{ $albumIndex }}][images][{{ $imageIndex }}][image_path]" value="{{ $image['image_path'] ?? '' }}">

    <div class="field-group">
        <label class="field-label" for="{{ $inputId }}">Album image upload</label>
        <input class="field-file" id="{{ $inputId }}" type="file" name="album_image_uploads[{{ $albumIndex }}][{{ $imageIndex }}]" accept="image/*" data-webp-input>
        <span class="field-hint">Upload a new image to replace the current album image.</span>
        <span class="upload-status" data-upload-status>
            <span class="upload-spinner" aria-hidden="true"></span>
            <span data-upload-status-text>Select an image to convert to WebP.</span>
        </span>
        @if (!empty($image['image_url']))
            <div class="gallery-preview-wrap">
                <span class="gallery-preview-title">Current preview</span>
                <div class="thumbnail-preview gallery-preview-image">
                    <img src="{{ $image['image_url'] }}" alt="Gallery album image preview" loading="lazy" decoding="async">
                </div>
                <span class="gallery-preview-caption">Compact preview only. Full image stays unchanged on the website.</span>
            </div>
        @endif
        @if ($showErrors)
            @error('album_image_uploads.'.$albumIndex.'.'.$imageIndex)
                <span class="field-error">{{ $message }}</span>
            @enderror
            @error($errorPrefix.'.image_path')
                <span class="field-error">{{ $message }}</span>
            @enderror
        @endif
    </div>
</div>
