@php
    $member = $member ?? [];
    $index = $index ?? 0;
    $showErrors = $showErrors ?? true;
    $label = $label ?? 'Board Member';
    $errorPrefix = 'board_members.'.$index;
    $nameId = 'board-member-name-'.$index;
    $positionId = 'board-member-position-'.$index;
    $imageId = 'board-member-image-'.$index;
@endphp

<div class="project-editor-card" data-leadership-member-card>
    <div class="project-editor-card-head">
        <div>
            <h3 class="project-editor-card-title">{{ $label }}</h3>
            <p class="project-editor-card-meta">This card appears inside the public Board Members slider.</p>
        </div>

        <div class="project-editor-card-actions">
            <button class="project-editor-remove" type="button" data-leadership-member-remove-card data-confirm-message="Are you sure you want to remove this board member card? This change will be saved when you submit the form." aria-label="Remove board member">Remove</button>
        </div>
    </div>

    <input type="hidden" name="board_members[{{ $index }}][image_path]" value="{{ $member['image_path'] ?? '' }}">

    <div class="field-group">
        <label class="field-label" for="{{ $imageId }}">Member image</label>
        <input class="field-file" id="{{ $imageId }}" type="file" name="board_member_images[{{ $index }}]" accept="image/*" data-webp-input>
        <span class="field-hint">Any uploaded image will be converted to WebP automatically. Max file size: 6 MB.</span>
        <span class="upload-status" data-upload-status>
            <span class="upload-spinner" aria-hidden="true"></span>
            <span data-upload-status-text>Select an image to convert to WebP.</span>
        </span>
        @if (!empty($member['image_url']))
            <div class="thumbnail-preview leadership-member-preview">
                <img src="{{ $member['image_url'] }}" alt="Board member image preview" loading="lazy" decoding="async">
            </div>
        @endif
        @if ($showErrors)
            @error('board_member_images.'.$index)
                <span class="field-error">{{ $message }}</span>
            @enderror
            @error($errorPrefix.'.image_path')
                <span class="field-error">{{ $message }}</span>
            @enderror
        @endif
    </div>

    <div class="field-group">
        <label class="field-label" for="{{ $nameId }}">Member name</label>
        <input class="field-input" id="{{ $nameId }}" type="text" name="board_members[{{ $index }}][name]" value="{{ $member['name'] ?? '' }}" placeholder="Board member name">
        @if ($showErrors)
            @error($errorPrefix.'.name')
                <span class="field-error">{{ $message }}</span>
            @enderror
        @endif
    </div>

    <div class="field-group">
        <label class="field-label" for="{{ $positionId }}">Member position</label>
        <input class="field-input" id="{{ $positionId }}" type="text" name="board_members[{{ $index }}][position]" value="{{ $member['position'] ?? '' }}" placeholder="Board member position">
        @if ($showErrors)
            @error($errorPrefix.'.position')
                <span class="field-error">{{ $message }}</span>
            @enderror
        @endif
    </div>
</div>
