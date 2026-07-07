@php
    $album = $album ?? [];
    $index = $index ?? 0;
    $showErrors = $showErrors ?? true;
    $label = $label ?? 'Album';
    $titleId = 'gallery-album-title-'.$index;
    $subtitleId = 'gallery-album-subtitle-'.$index;
    $images = $album['images'] ?? [];
    $errorPrefix = 'albums.'.$index;
@endphp

<div class="project-editor-card gallery-album-card" data-gallery-album-card data-gallery-album-index="{{ $index }}">
    <div class="project-editor-card-head">
        <div>
            <h3 class="project-editor-card-title">{{ $label }}</h3>
            <p class="project-editor-card-meta">This album appears on the public gallery page with its own image collection.</p>
        </div>

        <button class="project-editor-remove" type="button" data-gallery-remove-album data-confirm-message="Are you sure you want to remove this album? This change will be saved when you submit the form.">Remove</button>
    </div>

    <div class="office-editor-grid office-editor-grid--compact">
        <div class="field-group">
            <label class="field-label" for="{{ $titleId }}">Album title</label>
            <input class="field-input" id="{{ $titleId }}" type="text" name="albums[{{ $index }}][title]" value="{{ $album['title'] ?? '' }}" placeholder="Summer Property Tour">
            @if ($showErrors)
                @error($errorPrefix.'.title')
                    <span class="field-error">{{ $message }}</span>
                @enderror
            @endif
        </div>

        <div class="field-group">
            <label class="field-label" for="{{ $subtitleId }}">Album subtitle</label>
            <input class="field-input" id="{{ $subtitleId }}" type="text" name="albums[{{ $index }}][subtitle]" value="{{ $album['subtitle'] ?? '' }}" placeholder="Short note shown above the album images">
            @if ($showErrors)
                @error($errorPrefix.'.subtitle')
                    <span class="field-error">{{ $message }}</span>
                @enderror
            @endif
        </div>
    </div>

    <div class="projects-group">
        <div class="projects-group-head">
            <div>
                <h4 class="projects-group-title">Album Images</h4>
                <p class="projects-group-meta">Add the related images for this album. You can create as many images as needed.</p>
            </div>

            <button class="project-editor-add" type="button" data-gallery-add-image>Add Image</button>
        </div>

        <div class="gallery-album-image-list" data-gallery-album-image-list data-next-index="{{ count($images) }}">
            @foreach ($images as $imageIndex => $image)
                @include('admin.content.partials.gallery-album-image-fields', [
                    'albumIndex' => $index,
                    'imageIndex' => $imageIndex,
                    'image' => $image,
                    'label' => 'Image '.($imageIndex + 1),
                    'showErrors' => $showErrors,
                ])
            @endforeach
        </div>
    </div>
</div>
