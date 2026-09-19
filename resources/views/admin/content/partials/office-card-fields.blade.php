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

<div class="project-editor-card office-card-enhanced" data-office-editor-card>
    <div class="project-editor-card-head office-card-head">
        <div class="office-card-title-group">
            <span class="office-badge">Branch</span>
            <div>
                <h3 class="project-editor-card-title">{{ $label }}</h3>
                <p class="project-editor-card-meta">This office branch card appears in the office grid on the public website.</p>
            </div>
        </div>

        <button class="project-editor-remove office-remove-btn" type="button" data-office-remove-card data-confirm-message="Are you sure you want to remove this office card? This change will be saved when you submit the form." aria-label="Remove office card">
            <svg width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                <path d="M3 6h18M19 6v14a2 2 0 0 1-2 2H7a2 2 0 0 1-2-2V6m3 0V4a2 2 0 0 1 2-2h4a2 2 0 0 1 2 2v2"></path>
                <line x1="10" y1="11" x2="10" y2="17"></line>
                <line x1="14" y1="11" x2="14" y2="17"></line>
            </svg>
            <span>Remove</span>
        </button>
    </div>

    <div class="field-group">
        <label class="field-label" for="{{ $nameId }}">Office block name</label>
        <div class="input-icon-group">
            <span class="input-icon">
                <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                    <rect x="4" y="2" width="16" height="20" rx="2" ry="2"></rect>
                    <line x1="9" y1="6" x2="9" y2="6.01"></line>
                    <line x1="15" y1="6" x2="15" y2="6.01"></line>
                    <line x1="9" y1="10" x2="9" y2="10.01"></line>
                    <line x1="15" y1="10" x2="15" y2="10.01"></line>
                    <line x1="9" y1="14" x2="9" y2="14.01"></line>
                    <line x1="15" y1="14" x2="15" y2="14.01"></line>
                    <line x1="9" y1="18" x2="15" y2="18"></line>
                </svg>
            </span>
            <input class="field-input field-input--with-icon" id="{{ $nameId }}" type="text" name="office_cards[{{ $index }}][name]" value="{{ $office['name'] ?? '' }}" placeholder="Head Office">
        </div>
        @if ($showErrors)
            @error($errorPrefix.'.name')
                <span class="field-error">{{ $message }}</span>
            @enderror
        @endif
    </div>

    <div class="field-group">
        <label class="field-label" for="{{ $mapId }}">Office Google Maps link</label>
        <div class="input-icon-group">
            <span class="input-icon input-icon--danger">
                <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                    <path d="M21 10c0 7-9 13-9 13s-9-6-9-13a9 9 0 0 1 18 0z"></path>
                    <circle cx="12" cy="10" r="3"></circle>
                </svg>
            </span>
            <input class="field-input field-input--with-icon" id="{{ $mapId }}" type="url" name="office_cards[{{ $index }}][map_url]" value="{{ $office['map_url'] ?? '' }}" placeholder="https://www.google.com/maps/place/...">
        </div>
        <span class="field-hint">Clicking the public button will open this office location in Google Maps.</span>
        @if ($showErrors)
            @error($errorPrefix.'.map_url')
                <span class="field-error">{{ $message }}</span>
            @enderror
        @endif
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
            <div class="input-icon-group">
                <span class="input-icon input-icon--success">
                    <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                        <path d="M22 16.92v3a2 2 0 0 1-2.18 2 19.79 19.79 0 0 1-8.63-3.07 19.5 19.5 0 0 1-6-6 19.79 19.79 0 0 1-3.07-8.67A2 2 0 0 1 4.11 2h3a2 2 0 0 1 2 1.72 12.84 12.84 0 0 0 .7 2.81 2 2 0 0 1-.45 2.11L8.09 9.91a16 16 0 0 0 6 6l1.27-1.27a2 2 0 0 1 2.11-.45 12.84 12.84 0 0 0 2.81.7A2 2 0 0 1 22 16.92z"></path>
                    </svg>
                </span>
                <input class="field-input field-input--with-icon" id="{{ $phoneId }}" type="text" name="office_cards[{{ $index }}][phone]" value="{{ $office['phone'] ?? '' }}" placeholder="+8801700000000">
            </div>
            <span class="field-hint">This number opens the visitor's dial pad on supported devices.</span>
            @if ($showErrors)
                @error($errorPrefix.'.phone')
                    <span class="field-error">{{ $message }}</span>
                @enderror
            @endif
        </div>

        <div class="field-group">
            <label class="field-label" for="{{ $emailId }}">Email address</label>
            <div class="input-icon-group">
                <span class="input-icon input-icon--info">
                    <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                        <path d="M4 4h16c1.1 0 2 .9 2 2v12c0 1.1-.9 2-2 2H4c-1.1 0-2-.9-2-2V6c0-1.1.9-2 2-2z"></path>
                        <polyline points="22,6 12,13 2,6"></polyline>
                    </svg>
                </span>
                <input class="field-input field-input--with-icon" id="{{ $emailId }}" type="email" name="office_cards[{{ $index }}][email]" value="{{ $office['email'] ?? '' }}" placeholder="office@example.com">
            </div>
            <span class="field-hint">This email opens the visitor's default mail app.</span>
            @if ($showErrors)
                @error($errorPrefix.'.email')
                    <span class="field-error">{{ $message }}</span>
                @enderror
            @endif
        </div>
    </div>
</div>
