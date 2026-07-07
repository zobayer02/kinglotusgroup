@php
    $card = $card ?? [];
    $label = $label ?? 'Project Card';
    $meta = $meta ?? 'This card controls a matching public image card.';
    $prefix = $prefix ?? 'top_project_cards';
    $imagePrefix = $imagePrefix ?? 'top_project_card_images';
    $index = $index ?? 0;
    $showErrors = $showErrors ?? true;
    $errorPrefix = $prefix.'.'.$index;
    $titleId = $prefix.'-title-'.$index;
    $locationId = $prefix.'-location-'.$index;
    $imageId = $prefix.'-image-'.$index;
    $orderId = $prefix.'-order-'.$index;
    $orderValue = isset($card['order']) && is_numeric($card['order'])
        ? max(1, (int) $card['order'])
        : (is_numeric($index) ? ((int) $index) + 1 : '');
    $hasCardErrors = $showErrors && (
        $errors->has($errorPrefix.'.order')
        || $errors->has($errorPrefix.'.title')
        || $errors->has($errorPrefix.'.location')
        || $errors->has($imagePrefix.'.'.$index)
    );
@endphp

<div class="project-editor-card" data-project-editor-card data-project-editing-start="{{ $hasCardErrors ? 'true' : 'false' }}">
    <div class="project-editor-card-head">
        <div>
            <h3 class="project-editor-card-title">{{ $label }}</h3>
            <p class="project-editor-card-meta">{{ $meta }}</p>
        </div>

        <div class="project-editor-card-actions">
            <button class="project-editor-toggle" type="button" data-project-toggle-edit aria-pressed="{{ $hasCardErrors ? 'true' : 'false' }}">
                {{ $hasCardErrors ? 'Done' : 'Edit' }}
            </button>

            <div class="project-editor-order-field">
                <label class="project-editor-order-label" for="{{ $orderId }}">Order</label>
                <input class="field-input project-editor-order-input" id="{{ $orderId }}" type="number" name="{{ $prefix }}[{{ $index }}][order]" value="{{ $orderValue }}" min="1" step="1" inputmode="numeric" placeholder="1" data-project-edit-field @if (! $hasCardErrors) readonly @endif>
                @if ($showErrors)
                    @error($errorPrefix.'.order')
                        <span class="field-error">{{ $message }}</span>
                    @enderror
                @endif
            </div>

            <button class="project-editor-remove" type="button" data-project-remove-card data-confirm-message="Are you sure you want to remove this card? This change will be saved when you submit the form." aria-label="Remove card">Remove</button>
        </div>
    </div>

    <input type="hidden" name="{{ $prefix }}[{{ $index }}][image_path]" value="{{ $card['image_path'] ?? '' }}">
    <p class="project-editor-lock-note" data-project-edit-note @if ($hasCardErrors) hidden @endif>Click Edit to update this card, including its image.</p>

    <div class="field-group">
        <label class="field-label" for="{{ $titleId }}">Card title</label>
        <input class="field-input" id="{{ $titleId }}" type="text" name="{{ $prefix }}[{{ $index }}][title]" value="{{ $card['title'] ?? '' }}" placeholder="Card title" data-project-edit-field @if (! $hasCardErrors) readonly @endif>
        @if ($showErrors)
            @error($errorPrefix.'.title')
                <span class="field-error">{{ $message }}</span>
            @enderror
        @endif
    </div>

    <div class="field-group">
        <label class="field-label" for="{{ $locationId }}">Location</label>
        <input class="field-input" id="{{ $locationId }}" type="text" name="{{ $prefix }}[{{ $index }}][location]" value="{{ $card['location'] ?? '' }}" placeholder="Location or city" data-project-edit-field @if (! $hasCardErrors) readonly @endif>
        @if ($showErrors)
            @error($errorPrefix.'.location')
                <span class="field-error">{{ $message }}</span>
            @enderror
        @endif
    </div>

    <div class="field-group">
        <label class="field-label" for="{{ $imageId }}">Card image</label>
        <input class="field-file" id="{{ $imageId }}" type="file" name="{{ $imagePrefix }}[{{ $index }}]" accept="image/*" data-webp-input data-project-file-control @if (! $hasCardErrors) hidden @endif>
        <span class="field-hint" data-project-file-control @if (! $hasCardErrors) hidden @endif>Any uploaded image will be converted to WebP automatically. Max file size: 6 MB.</span>
        <span class="upload-status" data-upload-status data-project-file-control @if (! $hasCardErrors) hidden @endif>
            <span class="upload-spinner" aria-hidden="true"></span>
            <span data-upload-status-text>Select an image to convert to WebP.</span>
        </span>
        @if (!empty($card['image_url']))
            <div class="project-card-preview-wrap">
                <span class="field-label">Current preview</span>
                <div class="thumbnail-preview project-card-preview">
                    <img src="{{ $card['image_url'] }}" alt="Project card image preview" loading="lazy" decoding="async">
                </div>
                <span class="project-card-preview-caption">Compact preview only. Full image will still be used on the website.</span>
            </div>
        @endif
        @if ($showErrors)
            @error($imagePrefix.'.'.$index)
                <span class="field-error">{{ $message }}</span>
            @enderror
        @endif
    </div>
</div>
