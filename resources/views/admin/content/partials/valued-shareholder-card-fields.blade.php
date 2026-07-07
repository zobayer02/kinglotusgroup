@php
    $shareholder = $shareholder ?? [];
    $index = $index ?? 0;
    $showErrors = $showErrors ?? true;
    $label = $label ?? 'Shareholder Card';
    $errorPrefix = 'shareholders.'.$index;
    $nameId = 'shareholder-name-'.$index;
    $positionId = 'shareholder-position-'.$index;
    $imageId = 'shareholder-image-'.$index;
@endphp

<div class="project-editor-card" data-shareholder-card>
    <div class="project-editor-card-head">
        <div>
            <h3 class="project-editor-card-title">{{ $label }}</h3>
            <p class="project-editor-card-meta">This card appears inside the public Our Valued Shareholders slider.</p>
        </div>

        <div class="project-editor-card-actions">
            <button class="project-editor-remove" type="button" data-shareholder-remove-card data-confirm-message="Are you sure you want to remove this shareholder card? This change will be saved when you submit the form." aria-label="Remove shareholder card">Remove</button>
        </div>
    </div>

    <input type="hidden" name="shareholders[{{ $index }}][image_path]" value="{{ $shareholder['image_path'] ?? '' }}">

    <div class="field-group">
        <label class="field-label" for="{{ $imageId }}">Shareholder image</label>
        <input class="field-file" id="{{ $imageId }}" type="file" name="shareholder_images[{{ $index }}]" accept="image/*" data-webp-input>
        <span class="field-hint">Any uploaded image will be converted to WebP automatically. Max file size: 6 MB.</span>
        <span class="upload-status" data-upload-status>
            <span class="upload-spinner" aria-hidden="true"></span>
            <span data-upload-status-text>Select an image to convert to WebP.</span>
        </span>
        @if (!empty($shareholder['image_url']))
            <div class="thumbnail-preview leadership-member-preview">
                <img src="{{ $shareholder['image_url'] }}" alt="Shareholder image preview" loading="lazy" decoding="async">
            </div>
        @endif
        @if ($showErrors)
            @error('shareholder_images.'.$index)
                <span class="field-error">{{ $message }}</span>
            @enderror
            @error($errorPrefix.'.image_path')
                <span class="field-error">{{ $message }}</span>
            @enderror
        @endif
    </div>

    <div class="field-group">
        <label class="field-label" for="{{ $nameId }}">Shareholder name</label>
        <input class="field-input" id="{{ $nameId }}" type="text" name="shareholders[{{ $index }}][name]" value="{{ $shareholder['name'] ?? '' }}" placeholder="Shareholder name">
        @if ($showErrors)
            @error($errorPrefix.'.name')
                <span class="field-error">{{ $message }}</span>
            @enderror
        @endif
    </div>

    <div class="field-group">
        <label class="field-label" for="{{ $positionId }}">Shareholder position</label>
        <input class="field-input" id="{{ $positionId }}" type="text" name="shareholders[{{ $index }}][position]" value="{{ $shareholder['position'] ?? '' }}" placeholder="Shareholder position">
        @if ($showErrors)
            @error($errorPrefix.'.position')
                <span class="field-error">{{ $message }}</span>
            @enderror
        @endif
    </div>
</div>
