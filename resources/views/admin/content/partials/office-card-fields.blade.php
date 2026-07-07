@php
    $office = $office ?? [];
    $index = $index ?? 0;
    $showErrors = $showErrors ?? true;
    $label = $label ?? 'Office';
    $errorPrefix = 'office_cards.'.$index;
    $nameId = 'office-cards-name-'.$index;
    $mapId = 'office-cards-map-'.$index;
    $addressId = 'office-cards-address-'.$index;
    $phoneId = 'office-cards-phone-'.$index;
    $emailId = 'office-cards-email-'.$index;
@endphp

<div class="project-editor-card" data-office-editor-card>
    <div class="project-editor-card-head">
        <div>
            <h3 class="project-editor-card-title">{{ $label }}</h3>
            <p class="project-editor-card-meta">This office card appears below the location section on the public website.</p>
        </div>

        <button class="project-editor-remove" type="button" data-office-remove-card data-confirm-message="Are you sure you want to remove this office card? This change will be saved when you submit the form." aria-label="Remove office card">Remove</button>
    </div>

    <div class="office-editor-grid">
        <div class="field-group">
            <label class="field-label" for="{{ $nameId }}">Office block name</label>
            <input class="field-input" id="{{ $nameId }}" type="text" name="office_cards[{{ $index }}][name]" value="{{ $office['name'] ?? '' }}" placeholder="Head Office">
            @if ($showErrors)
                @error($errorPrefix.'.name')
                    <span class="field-error">{{ $message }}</span>
                @enderror
            @endif
        </div>

        <div class="field-group office-editor-panel">
            <label class="field-label" for="{{ $mapId }}">Office Google Maps link</label>
            <input class="field-input" id="{{ $mapId }}" type="url" name="office_cards[{{ $index }}][map_url]" value="{{ $office['map_url'] ?? '' }}" placeholder="https://www.google.com/maps/place/...">
            <span class="office-editor-note">Clicking the public button will open this office location in a new tab.</span>
            @if ($showErrors)
                @error($errorPrefix.'.map_url')
                    <span class="field-error">{{ $message }}</span>
                @enderror
            @endif
        </div>
    </div>

    <div class="field-group">
        <label class="field-label" for="{{ $addressId }}">Office address</label>
        <textarea class="field-textarea field-textarea--compact" id="{{ $addressId }}" name="office_cards[{{ $index }}][address]" placeholder="Office address shown inside the office card">{{ $office['address'] ?? '' }}</textarea>
        @if ($showErrors)
            @error($errorPrefix.'.address')
                <span class="field-error">{{ $message }}</span>
            @enderror
        @endif
    </div>

    <div class="office-editor-grid office-editor-grid--compact">
        <div class="field-group">
            <label class="field-label" for="{{ $phoneId }}">Mobile number</label>
            <input class="field-input" id="{{ $phoneId }}" type="text" name="office_cards[{{ $index }}][phone]" value="{{ $office['phone'] ?? '' }}" placeholder="+8801700000000">
            <span class="field-hint">This number opens the visitor's dial pad on supported devices.</span>
            @if ($showErrors)
                @error($errorPrefix.'.phone')
                    <span class="field-error">{{ $message }}</span>
                @enderror
            @endif
        </div>

        <div class="field-group">
            <label class="field-label" for="{{ $emailId }}">Email address</label>
            <input class="field-input" id="{{ $emailId }}" type="email" name="office_cards[{{ $index }}][email]" value="{{ $office['email'] ?? '' }}" placeholder="office@example.com">
            <span class="field-hint">This email opens the visitor's default mail app.</span>
            @if ($showErrors)
                @error($errorPrefix.'.email')
                    <span class="field-error">{{ $message }}</span>
                @enderror
            @endif
        </div>
    </div>
</div>
