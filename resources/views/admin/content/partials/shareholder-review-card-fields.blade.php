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
    $name = trim((string) ($review['name'] ?? ''));
    $videoUrl = trim((string) ($review['video_url'] ?? ''));
    $customThumbnailUrl = !empty($review['thumbnail_url']) ? $review['thumbnail_url'] : null;
    $youtubeId = \App\Models\ShareholderReviewSection::extractYoutubeId($videoUrl);
    $youtubeThumbnailUrl = $youtubeId ? 'https://img.youtube.com/vi/'.$youtubeId.'/hqdefault.jpg' : null;
    $previewThumbnailUrl = $customThumbnailUrl ?: $youtubeThumbnailUrl;
    $hasCustomThumbnail = !empty($customThumbnailUrl);
    $hasCardErrors = ($showErrors && ($errors->has($errorPrefix.'.*') || $errors->has('shareholder_review_thumbnails.'.$index)));
    $isInitiallyExpanded = $isDraft || $hasCardErrors;
@endphp

<div class="project-editor-card review-accordion-card{{ $isDraft ? ' is-draft' : '' }}{{ $isInitiallyExpanded ? ' is-expanded' : '' }}" data-review-editor-card @if($isDraft) data-review-draft="true" @endif data-review-index="{{ $index }}">
    <input type="hidden" name="shareholder_reviews[{{ $index }}][thumbnail_path]" value="{{ $review['thumbnail_path'] ?? '' }}" data-review-thumbnail-path>
    <input type="hidden" name="shareholder_reviews[{{ $index }}][remove_thumbnail]" value="0" data-review-remove-thumbnail-value>

    {{-- Collapsed Summary Bar --}}
    <div class="review-card-summary" data-review-toggle-accordion tabindex="0" role="button" aria-expanded="{{ $isInitiallyExpanded ? 'true' : 'false' }}">
        <div class="review-summary-left">
            <span class="review-summary-chevron" aria-hidden="true">
                <svg width="15" height="15" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round">
                    <polyline points="6 9 12 15 18 9"></polyline>
                </svg>
            </span>

            <span class="review-summary-badge project-editor-card-title">{{ $label }}</span>

            <div class="review-summary-thumb" data-review-summary-thumb>
                @if ($previewThumbnailUrl)
                    <img src="{{ $previewThumbnailUrl }}" alt="Video thumbnail preview" loading="lazy" decoding="async">
                @else
                    <div class="review-summary-thumb-placeholder">
                        <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                            <polygon points="23 7 16 12 23 17 23 7"></polygon>
                            <rect x="1" y="5" width="15" height="14" rx="2" ry="2"></rect>
                        </svg>
                    </div>
                @endif
            </div>

            <div class="review-summary-info">
                <span class="review-summary-name" data-review-summary-name>{{ $name !== '' ? $name : 'Unassigned Reviewer' }}</span>
                <span class="review-summary-url" data-review-summary-url>{{ $videoUrl !== '' ? $videoUrl : 'No video URL added' }}</span>
            </div>
        </div>

        <div class="review-summary-right">
            <span class="review-cover-tag {{ $hasCustomThumbnail ? 'is-custom' : ($youtubeThumbnailUrl ? 'is-youtube' : 'is-none') }}" data-review-cover-tag>
                {{ $hasCustomThumbnail ? 'Custom Cover' : ($youtubeThumbnailUrl ? 'YouTube Auto' : 'No Cover') }}
            </span>

            @if ($isDraft)
                <button class="project-editor-add" type="button" data-review-save-draft>Save Draft</button>
            @endif

            <button class="review-btn-toggle" type="button" data-review-toggle-button title="Toggle edit mode">
                <span class="review-btn-toggle-label">{{ $isInitiallyExpanded ? 'Close' : 'Edit' }}</span>
            </button>

            <button class="project-editor-remove" type="button" data-review-remove-card data-confirm-message="{{ $isDraft ? 'Discard this review draft?' : 'Are you sure you want to remove this review? This change will be saved when you submit the form.' }}" title="Remove review">
                {{ $isDraft ? 'Discard' : 'Remove' }}
            </button>
        </div>
    </div>

    {{-- Expanded Edit Body --}}
    <div class="review-card-body" data-review-card-body>
        <div class="review-card-body-inner">
            <div class="review-card-editor-grid">
                {{-- Left: 16:9 Live Video & Custom Cover Preview --}}
                <div class="review-card-media-pane">
                    <div class="review-live-preview-box" data-review-preview-box>
                        @if ($previewThumbnailUrl)
                            <img class="review-live-preview-img" src="{{ $previewThumbnailUrl }}" alt="Video thumbnail preview" loading="lazy" decoding="async">
                        @else
                            <div class="review-live-preview-placeholder">
                                <svg width="34" height="34" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.6" stroke-linecap="round" stroke-linejoin="round">
                                    <polygon points="23 7 16 12 23 17 23 7"></polygon>
                                    <rect x="1" y="5" width="15" height="14" rx="2" ry="2"></rect>
                                </svg>
                                <span>Enter a YouTube link or upload a custom cover</span>
                            </div>
                        @endif

                        <div class="review-play-badge" aria-hidden="true">
                            <svg width="18" height="18" viewBox="0 0 24 24" fill="currentColor">
                                <polygon points="5 3 19 12 5 21 5 3"></polygon>
                            </svg>
                        </div>
                    </div>

                    <div class="review-thumbnail-controls">
                        <label class="gallery-photo-tile-btn" for="{{ $thumbnailId }}">
                            <svg width="13" height="13" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                                <path d="M21 15v4a2 2 0 0 1-2 2H5a2 2 0 0 1-2-2v-4"></path>
                                <polyline points="17 8 12 3 7 8"></polyline>
                                <line x1="12" y1="3" x2="12" y2="15"></line>
                            </svg>
                            <span data-review-file-label>{{ $hasCustomThumbnail ? 'Change Custom Cover' : 'Upload Custom Cover' }}</span>
                        </label>
                        <input class="gallery-photo-tile-file" id="{{ $thumbnailId }}" type="file" name="shareholder_review_thumbnails[{{ $index }}]" accept="image/*" data-webp-input data-review-file-input>

                        <button class="review-thumbnail-remove-btn" type="button" data-review-remove-thumbnail @if(!$hasCustomThumbnail) style="display: none;" @endif>
                            Remove Custom Cover
                        </button>

                        <span class="upload-status" data-upload-status>
                            <span class="upload-spinner" aria-hidden="true"></span>
                            <span data-upload-status-text>Ready</span>
                        </span>

                        <span class="field-hint" style="margin-top: 2px; font-size: 0.72rem;">Optional: Custom cover overrides the automatic YouTube thumbnail. Max size: 6 MB.</span>
                    </div>
                </div>

                {{-- Right: Shareholder Name & Video Link --}}
                <div class="review-card-fields-pane">
                    <div class="field-group">
                        <label class="field-label" for="{{ $nameId }}">Shareholder name</label>
                        <input class="field-input" id="{{ $nameId }}" type="text" name="shareholder_reviews[{{ $index }}][name]" value="{{ $name }}" placeholder="e.g. Al-Haj Md. Rafiqul Islam" data-review-name-input>
                        @if ($showErrors)
                            @error($errorPrefix.'.name')
                                <span class="field-error">{{ $message }}</span>
                            @enderror
                        @endif
                    </div>

                    <div class="field-group">
                        <div style="display: flex; justify-content: space-between; align-items: baseline;">
                            <label class="field-label" for="{{ $videoId }}">YouTube video link</label>
                            <a class="review-video-link-preview" href="{{ $videoUrl ?: '#' }}" target="_blank" rel="noopener noreferrer" data-review-external-link @if(!$videoUrl) style="display: none;" @endif>
                                Test link ↗
                            </a>
                        </div>
                        <input class="field-input" id="{{ $videoId }}" type="url" name="shareholder_reviews[{{ $index }}][video_url]" value="{{ $videoUrl }}" placeholder="https://www.youtube.com/watch?v=..." data-review-video-input>
                        @if ($showErrors)
                            @error($errorPrefix.'.video_url')
                                <span class="field-error">{{ $message }}</span>
                            @enderror
                        @endif
                    </div>

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
        </div>
    </div>
</div>
