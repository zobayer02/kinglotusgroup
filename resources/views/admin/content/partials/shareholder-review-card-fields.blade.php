@php
    $review = $review ?? [];
    $index = $index ?? 0;
    $showErrors = $showErrors ?? true;
    $label = $label ?? 'Review';
    $isDraft = $isDraft ?? false;
    $errorPrefix = 'shareholder_reviews.'.$index;
    $nameId = 'shareholder-review-name-'.$index;
    $videoId = 'shareholder-review-video-'.$index;
    $thumbnailId = 'shareholder-review-thumbnail-'.$index;
@endphp

<div class="project-editor-card shareholder-review-editor-card{{ $isDraft ? ' is-draft' : '' }}" data-review-editor-card @if($isDraft) data-review-draft="true" @endif>
    <div class="project-editor-card-head">
        <div>
            <h3 class="project-editor-card-title">{{ $label }}</h3>
            <p class="project-editor-card-meta">{{ $isDraft ? 'Fill in the review details here, then save it to the list below.' : 'This video card appears in the shareholder review section on the public website.' }}</p>
        </div>

        <div class="review-editor-card-actions">
            @if ($isDraft)
                <button class="project-editor-add" type="button" data-review-save-draft>Save Review</button>
            @endif
            <button class="project-editor-remove" type="button" data-review-remove-card data-confirm-message="{{ $isDraft ? 'Discard this review draft?' : 'Are you sure you want to remove this review? This change will be saved when you submit the form.' }}">{{ $isDraft ? 'Cancel' : 'Remove' }}</button>
        </div>
    </div>

    <input type="hidden" name="shareholder_reviews[{{ $index }}][thumbnail_path]" value="{{ $review['thumbnail_path'] ?? '' }}" data-review-thumbnail-path>
    <input type="hidden" name="shareholder_reviews[{{ $index }}][remove_thumbnail]" value="0" data-review-remove-thumbnail-value>

    <div class="office-editor-grid office-editor-grid--compact">
        <div class="field-group">
            <label class="field-label" for="{{ $nameId }}">Shareholder name</label>
            <input class="field-input" id="{{ $nameId }}" type="text" name="shareholder_reviews[{{ $index }}][name]" value="{{ $review['name'] ?? '' }}" placeholder="Shareholder name">
            @if ($showErrors)
                @error($errorPrefix.'.name')
                    <span class="field-error">{{ $message }}</span>
                @enderror
            @endif
        </div>

        <div class="field-group">
            <label class="field-label" for="{{ $videoId }}">Video link</label>
            <input class="field-input" id="{{ $videoId }}" type="url" name="shareholder_reviews[{{ $index }}][video_url]" value="{{ $review['video_url'] ?? '' }}" placeholder="https://www.youtube.com/watch?v=...">
            @if ($showErrors)
                @error($errorPrefix.'.video_url')
                    <span class="field-error">{{ $message }}</span>
                @enderror
            @endif
        </div>
    </div>

    <div class="field-group">
        <label class="field-label" for="{{ $thumbnailId }}">Optional thumbnail</label>
        <input class="field-file" id="{{ $thumbnailId }}" type="file" name="shareholder_review_thumbnails[{{ $index }}]" accept="image/*" data-webp-input>
        <span class="field-hint">Upload a thumbnail if you want a custom cover. Without a thumbnail, the public card will show a preview from the video.</span>
        <span class="upload-status" data-upload-status>
            <span class="upload-spinner" aria-hidden="true"></span>
            <span data-upload-status-text>Select an image to convert to WebP.</span>
        </span>
        @if (!empty($review['thumbnail_url']))
            <div class="review-thumbnail-preview-wrap is-collapsed" data-review-thumbnail-preview>
                <div class="thumbnail-preview review-thumbnail-preview">
                    <img src="{{ $review['thumbnail_url'] }}" alt="Shareholder review thumbnail preview" loading="lazy" decoding="async">
                </div>
                <div class="review-thumbnail-actions">
                    <button class="project-editor-add" type="button" data-review-toggle-thumbnail data-show-label="Show Thumbnail" data-hide-label="Hide Thumbnail">Show Thumbnail</button>
                    <button class="project-editor-remove review-thumbnail-remove" type="button" data-review-remove-thumbnail>Remove Thumbnail</button>
                </div>
            </div>
        @endif
        @if ($showErrors)
            @error('shareholder_review_thumbnails.'.$index)
                <span class="field-error">{{ $message }}</span>
            @enderror
            @error($errorPrefix.'.thumbnail_path')
                <span class="field-error">{{ $message }}</span>
            @enderror
        @endif
    </div>
</div>
