@php
    $member = $member ?? [];
    $index = $index ?? 0;
    $showErrors = $showErrors ?? true;
    $label = $label ?? 'Board Member';
    $errorPrefix = 'board_members.'.$index;
    $nameId = 'board-member-name-'.$index;
    $positionId = 'board-member-position-'.$index;
    $imageId = 'board-member-image-'.$index;
    $hasErrors = $showErrors && ($errors->has('board_members.'.$index.'.*') || $errors->has('board_member_images.'.$index));
    $isInitiallyExpanded = isset($isExpanded) ? (bool) $isExpanded : $hasErrors;
    $memberName = $member['name'] ?? '';
    $memberPosition = $member['position'] ?? '';
    $imageUrl = $member['image_url'] ?? null;
@endphp

<div class="leadership-member-accordion-card {{ $isInitiallyExpanded ? 'is-expanded' : '' }}" data-leadership-member-card>
    <div class="leadership-member-summary" data-leadership-toggle-accordion tabindex="0" role="button" aria-expanded="{{ $isInitiallyExpanded ? 'true' : 'false' }}">
        <div class="leadership-summary-left">
            <span class="leadership-summary-chevron" aria-hidden="true">
                <svg width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.2" stroke-linecap="round" stroke-linejoin="round">
                    <polyline points="6 9 12 15 18 9"></polyline>
                </svg>
            </span>

            <span class="leadership-summary-badge">{{ $label }}</span>

            <div class="leadership-summary-avatar" data-leadership-summary-avatar>
                @if (!empty($imageUrl))
                    <img src="{{ $imageUrl }}" alt="{{ $memberName ? $memberName.' preview' : 'Board member' }}" loading="lazy" decoding="async">
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
                <span class="leadership-summary-name" data-leadership-summary-name>{{ $memberName ?: 'Unnamed Board Member' }}</span>
                <span class="leadership-summary-position" data-leadership-summary-position>{{ $memberPosition ?: 'No position set' }}</span>
            </div>
        </div>

        <div class="leadership-summary-right">
            <button class="leadership-btn-toggle" type="button" data-leadership-member-toggle-button aria-label="Toggle board member editor">
                <span class="leadership-btn-toggle-label">{{ $isInitiallyExpanded ? 'Close' : 'Edit' }}</span>
            </button>
            <button class="project-editor-remove" type="button" data-leadership-member-remove-card data-confirm-message="Are you sure you want to remove this board member card? This change will be saved when you submit the form." aria-label="Remove board member">Remove</button>
        </div>
    </div>

    <div class="leadership-member-body">
        <div class="leadership-member-body-inner">
            <input type="hidden" name="board_members[{{ $index }}][image_path]" value="{{ $member['image_path'] ?? '' }}">

            <div class="leadership-member-editor-grid">
                <div class="leadership-photo-editor-box">
                    <div class="leadership-photo-tile-wrapper">
                        <div class="leadership-photo-tile" data-leadership-editor-avatar-preview>
                            @if (!empty($imageUrl))
                                <img src="{{ $imageUrl }}" alt="Board member photo" loading="lazy" decoding="async">
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
                                <span data-leadership-photo-btn-label>{{ !empty($imageUrl) ? 'Change Photo' : 'Upload Photo' }}</span>
                            </label>
                            <input class="leadership-photo-file-input" id="{{ $imageId }}" type="file" name="board_member_images[{{ $index }}]" accept="image/*" data-webp-input data-leadership-member-file-input>

                            <span class="field-hint">WebP auto-converted &bull; Max 6 MB</span>
                            <span class="upload-status" data-upload-status>
                                <span class="upload-spinner" aria-hidden="true"></span>
                                <span data-upload-status-text>Select an image to convert to WebP.</span>
                            </span>

                            @if ($showErrors)
                                @error('board_member_images.'.$index)
                                    <span class="field-error">{{ $message }}</span>
                                @enderror
                                @error($errorPrefix.'.image_path')
                                    <span class="field-error">{{ $message }}</span>
                                @enderror
                            @endif
                        </div>
                    </div>
                </div>

                <div class="leadership-member-fields-col">
                    <div class="field-group">
                        <label class="field-label" for="{{ $nameId }}">Member name</label>
                        <input class="field-input" id="{{ $nameId }}" type="text" name="board_members[{{ $index }}][name]" value="{{ $memberName }}" placeholder="e.g. MD. Nurul Amin" data-leadership-member-name-input>
                        @if ($showErrors)
                            @error($errorPrefix.'.name')
                                <span class="field-error">{{ $message }}</span>
                            @enderror
                        @endif
                    </div>

                    <div class="field-group">
                        <label class="field-label" for="{{ $positionId }}">Member position</label>
                        <input class="field-input" id="{{ $positionId }}" type="text" name="board_members[{{ $index }}][position]" value="{{ $memberPosition }}" placeholder="e.g. Sales & Marketing Director" data-leadership-member-position-input>
                        @if ($showErrors)
                            @error($errorPrefix.'.position')
                                <span class="field-error">{{ $message }}</span>
                            @enderror
                        @endif
                    </div>
                </div>
            </div>

            <div class="leadership-member-card-footer">
                <button class="leadership-btn-done" type="button" data-leadership-member-done>Done</button>
            </div>
        </div>
    </div>
</div>
