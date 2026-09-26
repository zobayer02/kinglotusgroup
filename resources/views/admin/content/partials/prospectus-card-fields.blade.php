@php
    $brochure = $brochure ?? [];
    $index = $index ?? 0;
    $showErrors = $showErrors ?? true;
    $label = $label ?? ('Page ' . ($index + 1));
    $errorPrefix = 'brochures.'.$index;
    $titleId = 'brochures-title-'.$index;
    $subtitleId = 'brochures-subtitle-'.$index;
    $imageId = 'brochures-image-'.$index;
    $existingPath = $brochure['image_path'] ?? '';
    $existingUrl = filled($existingPath) ? asset(ltrim($existingPath, '/')) : null;
@endphp

<div class="project-editor-card prospectus-card-item" data-prospectus-editor-card>
    <div class="project-editor-card-head">
        <div>
            <h3 class="project-editor-card-title">{{ $label }}</h3>
            <p class="project-editor-card-meta">Prospectus flyer / brochure page for the public slider.</p>
        </div>
        <button class="project-editor-remove" type="button" data-prospectus-remove-card data-confirm-message="Are you sure you want to remove this brochure page?">
            <svg width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                <path d="M3 6h18M19 6v14a2 2 0 0 1-2 2H7a2 2 0 0 1-2-2V6m3 0V4a2 2 0 0 1 2-2h4a2 2 0 0 1 2 2v2"></path>
                <line x1="10" y1="11" x2="10" y2="17"></line>
                <line x1="14" y1="11" x2="14" y2="17"></line>
            </svg>
            <span>Remove</span>
        </button>
    </div>
    <div class="field-grid field-grid--2">
        <div class="field-group">
            <label class="field-label" for="{{ $titleId }}">Card Title / Heading</label>
            <input class="field-input" id="{{ $titleId }}" type="text" name="brochures[{{ $index }}][title]" value="{{ $brochure['title'] ?? '' }}" placeholder="5-Star Luxury in Royal">
            @if ($showErrors)
                @error($errorPrefix.'.title')
                    <span class="field-error">{{ $message }}</span>
                @enderror
            @endif
        </div>
        <div class="field-group">
            <label class="field-label" for="{{ $subtitleId }}">Card Subtitle / Description</label>
            <input class="field-input" id="{{ $subtitleId }}" type="text" name="brochures[{{ $index }}][subtitle]" value="{{ $brochure['subtitle'] ?? '' }}" placeholder="সংক্ষিপ্ত বিবরণ বা সাবটাইটেল">
            @if ($showErrors)
                @error($errorPrefix.'.subtitle')
                    <span class="field-error">{{ $message }}</span>
                @enderror
            @endif
        </div>
    </div>
    <div class="field-group">
        <label class="field-label" for="{{ $imageId }}">Brochure Page Image</label>
        <input type="hidden" name="brochures[{{ $index }}][image_path]" value="{{ $existingPath }}">
        <input class="field-input file-input" id="{{ $imageId }}" type="file" name="brochure_images[{{ $index }}]" accept="image/*">
        @if ($existingUrl)
            <div class="image-preview" style="margin-top: 8px;">
                <img src="{{ $existingUrl }}" alt="{{ $brochure['title'] ?? 'Brochure' }}" style="max-height: 120px; border-radius: 8px; border: 1px solid rgba(0,0,0,0.1);">
                <span class="field-hint" style="display: block; margin-top: 4px;">Current image: {{ basename($existingPath) }}</span>
            </div>
        @endif
        @if ($showErrors)
            @error('brochure_images.'.$index)
                <span class="field-error">{{ $message }}</span>
            @enderror
        @endif
    </div>
</div>
