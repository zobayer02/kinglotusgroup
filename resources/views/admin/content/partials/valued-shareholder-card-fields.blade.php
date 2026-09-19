@php
    $shareholder = $shareholder ?? [];
    $id = $shareholder['id'] ?? null;
    $index = $index ?? 0;
    $name = $shareholder['name'] ?? '';
    $position = $shareholder['position'] ?? '';
    $imageUrl = $shareholder['image_url'] ?? null;
    $label = $label ?? ('Shareholder #'.($index + 1));
    $imageId = 'shareholder-img-'.($id ?: 'new-'.$index);
    $isExpanded = $isExpanded ?? false;
@endphp

<div class="leadership-member-accordion-card {{ $isExpanded ? 'is-expanded' : '' }}" data-shareholder-card data-shareholder-id="{{ $id ?? '' }}">
    <div class="leadership-member-summary" data-shareholder-toggle-accordion tabindex="0" role="button" aria-expanded="{{ $isExpanded ? 'true' : 'false' }}">
        <div class="leadership-summary-left">
            <span class="leadership-summary-chevron" aria-hidden="true">
                <svg width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.2" stroke-linecap="round" stroke-linejoin="round">
                    <polyline points="6 9 12 15 18 9"></polyline>
                </svg>
            </span>

            <span class="leadership-summary-badge" data-shareholder-badge>{{ $label }}</span>

            <div class="leadership-summary-avatar" data-shareholder-summary-avatar>
                @if (!empty($imageUrl))
                    <img src="{{ $imageUrl }}" alt="{{ $name ? $name.' preview' : 'Shareholder' }}" loading="lazy" decoding="async">
                @else
                    <div class="leadership-avatar-placeholder" aria-hidden="true">
                        <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                            <path d="M20 21v-2a4 4 0 0 0-4-4H8a4 4 0 0 0-4 4v2"></path>
                            <circle cx="12" cy="7" r="4"></circle>
                        </svg>
                    </div>
                @endif
            </div>

            <div class="leadership-summary-info">
                <span class="leadership-summary-name" data-shareholder-summary-name>{{ $name ?: 'Unnamed Shareholder' }}</span>
                <span class="leadership-summary-position" data-shareholder-summary-position>{{ $position ?: 'No position set' }}</span>
            </div>
        </div>

        <div class="leadership-summary-right">
            <button class="leadership-btn-toggle" type="button" data-shareholder-toggle-btn aria-label="Toggle edit shareholder">
                <span class="leadership-btn-toggle-label">{{ $isExpanded ? 'Close' : 'Edit' }}</span>
            </button>
            <button class="project-editor-remove" type="button" data-shareholder-remove-btn data-confirm-message="Are you sure you want to remove this shareholder? This change will be saved immediately." aria-label="Remove shareholder">Remove</button>
        </div>
    </div>

    <div class="leadership-member-body">
        <div class="leadership-member-body-inner">
            <div class="leadership-member-editor-grid">
                <div class="leadership-photo-editor-box">
                    <div class="leadership-photo-tile-wrapper">
                        <div class="leadership-photo-tile" data-shareholder-editor-avatar-preview>
                            @if (!empty($imageUrl))
                                <img src="{{ $imageUrl }}" alt="Shareholder photo" loading="lazy" decoding="async">
                            @else
                                <div class="leadership-photo-tile-placeholder" aria-hidden="true">
                                    <svg width="28" height="28" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.8" stroke-linecap="round" stroke-linejoin="round">
                                        <path d="M20 21v-2a4 4 0 0 0-4-4H8a4 4 0 0 0-4 4v2"></path>
                                        <circle cx="12" cy="7" r="4"></circle>
                                    </svg>
                                </div>
                            @endif
                        </div>

                        <div class="leadership-photo-upload-actions">
                            <label class="leadership-photo-picker-btn" for="{{ $imageId }}">
                                <svg width="15" height="15" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true">
                                    <path d="M23 19a2 2 0 0 1-2 2H3a2 2 0 0 1-2-2V8a2 2 0 0 1 2-2h4l2-3h6l2 3h4a2 2 0 0 1 2 2z"></path>
                                    <circle cx="12" cy="13" r="4"></circle>
                                </svg>
                                <span data-shareholder-photo-btn-label>{{ !empty($imageUrl) ? 'Change Photo' : 'Upload Photo (Optional)' }}</span>
                            </label>
                            <input class="leadership-photo-file-input" id="{{ $imageId }}" type="file" accept="image/*" data-webp-input data-shareholder-file-input>

                            <button type="button" class="leadership-photo-remove-btn" data-shareholder-remove-photo-btn style="{{ !empty($imageUrl) ? 'display: inline-flex;' : 'display: none;' }}">
                                <svg width="13" height="13" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true">
                                    <line x1="18" y1="6" x2="6" y2="18"></line>
                                    <line x1="6" y1="6" x2="18" y2="18"></line>
                                </svg>
                                <span>Remove Photo</span>
                            </button>
                            <input type="hidden" data-shareholder-remove-image-input value="0">

                            <span class="field-hint">Photo is optional &bull; WebP auto-converted &bull; Max 6 MB</span>
                            <span class="upload-status" data-upload-status>
                                <span class="upload-spinner" aria-hidden="true"></span>
                                <span data-upload-status-text>Select an image to convert to WebP.</span>
                            </span>
                        </div>
                    </div>
                </div>

                <div class="leadership-member-fields-col">
                    <div class="field-group">
                        <label class="field-label">Shareholder name <span style="color: #e53935;">*</span></label>
                        <input class="field-input" type="text" value="{{ $name }}" placeholder="e.g. A.S.M. Zobayer" data-shareholder-name-input required>
                        <span class="field-error" data-shareholder-name-error style="display: none;"></span>
                    </div>

                    <div class="field-group">
                        <label class="field-label">Shareholder position</label>
                        <input class="field-input" type="text" value="{{ $position }}" placeholder="e.g. Full-Stack Software Developer" data-shareholder-position-input>
                    </div>
                </div>
            </div>

            <div class="leadership-member-card-footer">
                <button class="leadership-btn-text" type="button" data-shareholder-close-btn>Cancel</button>
                <button class="submit-button" type="button" data-shareholder-save-btn style="min-height: 32px; padding: 0 16px; font-size: 0.78rem; border-radius: 8px;">
                    <span data-shareholder-save-label>{{ $id ? 'Save Changes' : 'Add Shareholder' }}</span>
                    <span class="upload-spinner" data-shareholder-save-spinner style="display: none;" aria-hidden="true"></span>
                </button>
            </div>
        </div>
    </div>
</div>
