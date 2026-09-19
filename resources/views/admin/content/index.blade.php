@extends('admin.layouts.app')

@section('title', 'Content Management | King Lotus International')

@push('styles')
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/jodit@4.2.47/es2021/jodit.min.css">
    <style>
        .content-shell {
            display: grid;
            gap: 18px;
        }

        .content-overview {
            display: grid;
            gap: 14px;
        }

        .content-overview-copy {
            max-width: 720px;
        }

        .module-grid {
            display: flex;
            flex-wrap: wrap;
            gap: 10px;
            align-items: center;
        }

        .module-button {
            display: inline-flex;
            align-items: center;
            justify-content: flex-start;
            gap: 10px;
            min-height: 52px;
            padding: 8px 16px;
            border: 1px solid rgba(164, 186, 214, 0.6);
            border-radius: 16px;
            background: linear-gradient(180deg, rgba(243, 248, 253, 0.92) 0%, rgba(228, 238, 248, 0.84) 100%);
            color: inherit;
            cursor: pointer;
            transition: transform 0.22s ease, border-color 0.22s ease, box-shadow 0.22s ease, background 0.22s ease;
        }

        .module-button:hover,
        .module-button.is-active,
        .module-button[aria-expanded="true"] {
            transform: translateY(-2px);
            border-color: rgba(87, 138, 219, 0.7);
            box-shadow: 0 14px 26px rgba(38, 74, 116, 0.1);
            color: inherit;
        }

        .module-button.is-active,
        .module-button[aria-expanded="true"] {
            border-color: rgba(47, 111, 219, 0.85);
            background: linear-gradient(180deg, rgba(235, 244, 255, 0.98) 0%, rgba(218, 233, 250, 0.95) 100%);
        }

        .module-btn-icon {
            display: inline-flex;
            align-items: center;
            justify-content: center;
            width: 28px;
            height: 28px;
            border-radius: 8px;
            background: rgba(87, 138, 219, 0.12);
            color: #2f6fdb;
            flex-shrink: 0;
            transition: all 0.2s ease;
            pointer-events: none;
        }

        .module-button:hover .module-btn-icon,
        .module-button.is-active .module-btn-icon,
        .module-button[aria-expanded="true"] .module-btn-icon {
            background: rgba(87, 138, 219, 0.22);
            color: #1e52ad;
        }

        .module-title {
            margin: 0;
            font-size: 0.88rem;
            font-weight: 700;
            line-height: 1.2;
            white-space: nowrap;
            pointer-events: none;
        }

        .editor-panel {
            display: grid;
            gap: 16px;
            padding: 20px;
        }

        .editor-panel[hidden] {
            display: none;
        }

        .editor-header {
            display: flex;
            align-items: flex-start;
            justify-content: space-between;
            gap: 14px;
        }

        .editor-copy {
            max-width: 660px;
        }

        .editor-status {
            display: inline-flex;
            align-items: center;
            gap: 7px;
            padding: 8px 12px;
            border-radius: 999px;
            background: rgba(29, 138, 99, 0.12);
            color: var(--success);
            font-size: 0.82rem;
            font-weight: 700;
            white-space: nowrap;
        }

        .editor-status.is-hidden {
            background: rgba(191, 74, 64, 0.1);
            color: var(--danger);
        }

        .editor-status::before {
            content: "";
            width: 8px;
            height: 8px;
            border-radius: 999px;
            background: currentColor;
        }

        .editor-form {
            display: grid;
            gap: 12px;
        }

        .field-group {
            display: grid;
            gap: 6px;
        }

        .field-label {
            font-size: 0.86rem;
            font-weight: 600;
            color: var(--ink-900);
        }

        .field-input,
        .field-textarea,
        .field-file {
            width: 100%;
            border: 1px solid rgba(175, 191, 207, 0.62);
            border-radius: 16px;
            background: rgba(255, 255, 255, 0.8);
            color: var(--ink-900);
            outline: none;
            transition: border-color 0.2s ease, box-shadow 0.2s ease, transform 0.2s ease;
        }

        .field-input {
            min-height: 48px;
            padding: 0 14px;
        }

        .field-textarea {
            min-height: 156px;
            padding: 12px 14px;
            resize: vertical;
            line-height: 1.65;
        }

        .field-textarea--compact {
            min-height: 104px;
        }

        .field-file {
            min-height: 48px;
            padding: 10px 14px;
        }

        .field-input:focus,
        .field-textarea:focus,
        .field-file:focus {
            border-color: rgba(47, 111, 219, 0.5);
            box-shadow: 0 0 0 4px rgba(47, 111, 219, 0.12);
            transform: translateY(-1px);
        }

        .field-error {
            font-size: 0.8rem;
            color: #bf4a40;
        }

        .field-hint {
            font-size: 0.78rem;
            color: var(--ink-700);
        }

        .jodit-container {
            border: 1px solid rgba(175, 191, 207, 0.62) !important;
            border-radius: 18px !important;
            overflow: visible;
            background: rgba(255, 255, 255, 0.84) !important;
            box-shadow:
                inset 0 1px 0 rgba(255, 255, 255, 0.54),
                0 10px 22px rgba(47, 111, 219, 0.05) !important;
        }

        .jodit-container:not(.jodit_inline) .jodit-workplace {
            min-height: 190px;
            background: transparent !important;
            overflow: hidden;
            border-radius: 0 0 18px 18px;
        }

        .jodit-container .jodit-toolbar__box,
        .jodit-container .jodit-status-bar {
            background: linear-gradient(180deg, rgba(248, 251, 254, 0.98) 0%, rgba(237, 244, 249, 0.92) 100%) !important;
        }

        .jodit-container .jodit-status-bar {
            padding-right: 12px !important;
            padding-bottom: 8px !important;
        }

        .field-group .jodit-container {
            margin-bottom: 8px;
        }

        .field-group .field-hint {
            display: block;
            margin-top: 2px;
        }

        .jodit-container .jodit-resizer,
        .jodit-container [class*="resizer"] {
            opacity: 1 !important;
            visibility: visible !important;
        }

        .jodit-container .jodit-wysiwyg {
            padding: 14px !important;
            font-size: 0.92rem !important;
            line-height: 1.65 !important;
            color: var(--ink-900) !important;
        }

        .jodit-container.jodit_focus {
            border-color: rgba(47, 111, 219, 0.5) !important;
            box-shadow:
                0 0 0 4px rgba(47, 111, 219, 0.12),
                inset 0 1px 0 rgba(255, 255, 255, 0.54) !important;
        }

        .jodit-container .jodit-placeholder {
            color: rgba(16, 33, 44, 0.42) !important;
        }

        .upload-status {
            display: none;
            align-items: center;
            gap: 8px;
            min-height: 40px;
            padding: 0 12px;
            border: 1px solid rgba(175, 191, 207, 0.62);
            border-radius: 14px;
            background: rgba(255, 255, 255, 0.7);
            color: var(--ink-800);
            font-size: 0.82rem;
            font-weight: 600;
        }

        .upload-status.is-visible {
            display: inline-flex;
        }

        .upload-status.is-processing {
            color: #2f6fdb;
            border-color: rgba(47, 111, 219, 0.35);
            background: rgba(47, 111, 219, 0.08);
        }

        .upload-spinner {
            width: 16px;
            height: 16px;
            border: 2px solid rgba(47, 111, 219, 0.2);
            border-top-color: currentColor;
            border-radius: 999px;
            animation: uploadSpin 0.7s linear infinite;
            flex: none;
        }

        .upload-status:not(.is-processing) .upload-spinner {
            border-color: rgba(29, 138, 99, 0.18);
            border-top-color: #1d8a63;
        }

        .submit-button.is-loading {
            pointer-events: none;
            opacity: 0.9;
        }

        .submit-button-label,
        .submit-button-loading {
            display: inline-flex;
            align-items: center;
            gap: 10px;
        }

        .submit-button-loading {
            display: none;
        }

        .submit-button.is-loading .submit-button-label {
            display: none;
        }

        .submit-button.is-loading .submit-button-loading {
            display: inline-flex;
        }

        @keyframes uploadSpin {
            to {
                transform: rotate(360deg);
            }
        }

        .media-grid {
            display: grid;
            gap: 14px;
            grid-template-columns: repeat(2, minmax(0, 1fr));
        }

        .notice-media-grid {
            grid-template-columns: minmax(0, 0.9fr) minmax(0, 1.1fr);
            align-items: start;
        }

        .notice-media-copy {
            display: grid;
            gap: 8px;
        }

        .notice-media-note {
            margin: 0;
            font-size: 0.8rem;
            line-height: 1.6;
            color: var(--ink-700);
        }

        .notice-media-note strong {
            color: var(--ink-900);
        }

        .notice-hero-preview {
            max-width: 360px;
            aspect-ratio: 16 / 9;
        }

        .notice-hero-preview-group {
            justify-items: center;
        }

        .thumbnail-preview {
            position: relative;
            overflow: hidden;
            border: 1px solid rgba(175, 191, 207, 0.62);
            border-radius: 22px;
            background: rgba(255, 255, 255, 0.55);
            aspect-ratio: 16 / 10;
        }

        .thumbnail-preview img {
            width: 100%;
            height: 100%;
            object-fit: cover;
            display: block;
        }

        .about-thumbnail-preview {
            max-width: 240px;
            aspect-ratio: 4 / 3;
            margin-left: auto;
            margin-right: auto;
        }

        .toggle-bar {
            display: flex;
            align-items: center;
            gap: 10px;
            min-height: 48px;
            padding: 0 14px;
            border: 1px solid rgba(175, 191, 207, 0.62);
            border-radius: 16px;
            background: rgba(255, 255, 255, 0.76);
        }

        .toggle-switch {
            position: relative;
            flex: none;
            width: 42px;
            height: 24px;
        }

        .toggle-switch input {
            position: absolute;
            inset: 0;
            opacity: 0;
            margin: 0;
        }

        .toggle-track {
            position: absolute;
            inset: 0;
            border-radius: 999px;
            background: rgba(136, 152, 167, 0.28);
            transition: background-color 0.2s ease;
        }

        .toggle-thumb {
            position: absolute;
            top: 2px;
            left: 2px;
            width: 20px;
            height: 20px;
            border-radius: 999px;
            background: #ffffff;
            box-shadow: 0 8px 18px rgba(24, 43, 56, 0.16);
            transition: transform 0.2s ease;
        }

        .toggle-switch input:checked + .toggle-track {
            background: rgba(47, 111, 219, 0.9);
        }

        .toggle-switch input:checked + .toggle-track .toggle-thumb {
            transform: translateX(18px);
        }

        .toggle-copy {
            display: grid;
            gap: 2px;
        }

        .toggle-title {
            font-size: 0.86rem;
            font-weight: 700;
        }

        .toggle-meta {
            font-size: 0.78rem;
            color: var(--ink-700);
        }

        .editor-actions {
            display: flex;
            justify-content: flex-end;
        }

        .editor-subsection {
            display: grid;
            gap: 16px;
            padding-top: 20px;
            border-top: 1px solid rgba(175, 191, 207, 0.45);
        }

        .why-form {
            gap: 18px;
        }

        .why-intro-grid,
        .why-media-grid {
            display: grid;
            grid-template-columns: repeat(2, minmax(0, 1fr));
            gap: 18px;
            align-items: start;
        }

        .why-surface-card {
            display: grid;
            gap: 10px;
            padding: 18px;
            border: 1px solid rgba(175, 191, 207, 0.48);
            border-radius: 24px;
            background:
                linear-gradient(180deg, rgba(255, 255, 255, 0.82) 0%, rgba(244, 249, 253, 0.72) 100%);
            box-shadow:
                inset 0 1px 0 rgba(255, 255, 255, 0.55),
                0 18px 40px rgba(85, 116, 152, 0.08);
        }

        .why-video-panel {
            align-self: start;
        }

        .why-video-panel .field-input {
            min-height: 56px;
        }

        .why-thumb-panel {
            gap: 14px;
        }

        .why-thumb-panel .field-file {
            min-height: 58px;
            padding: 10px 14px;
        }

        .why-thumb-panel .field-hint {
            line-height: 1.55;
        }

        .why-thumb-preview-wrap {
            display: grid;
            gap: 8px;
            justify-items: center;
            padding: 10px 12px 12px;
            border: 1px solid rgba(175, 191, 207, 0.34);
            border-radius: 20px;
            background: rgba(255, 255, 255, 0.46);
        }

        .why-thumb-preview {
            width: min(100%, 260px);
            aspect-ratio: 4 / 3;
            border-radius: 20px;
            margin-left: auto;
            margin-right: auto;
            box-shadow: 0 14px 24px rgba(52, 77, 110, 0.1);
        }

        .why-thumb-preview-caption {
            font-size: 0.76rem;
            line-height: 1.5;
            color: var(--ink-700);
            text-align: center;
        }

        .why-actions-bar {
            display: flex;
            justify-content: flex-end;
            align-items: center;
            margin-top: 4px;
            padding-top: 18px;
            border-top: 1px solid rgba(175, 191, 207, 0.38);
        }

        .why-editor-actions {
            margin-top: 0;
        }

        .projects-form {
            gap: 20px;
        }

        .projects-header-grid {
            display: grid;
            grid-template-columns: repeat(2, minmax(0, 1fr));
            gap: 18px;
            align-items: start;
        }

        .projects-group {
            display: grid;
            gap: 16px;
            padding: 18px;
            border: 1px solid rgba(175, 191, 207, 0.4);
            border-radius: 28px;
            background: rgba(255, 255, 255, 0.38);
        }

        .projects-group-head {
            display: flex;
            align-items: center;
            justify-content: space-between;
            gap: 16px;
        }

        .projects-group-title {
            margin: 0;
            font-size: 1.08rem;
            font-weight: 700;
            color: var(--ink-900);
        }

        .projects-group-meta {
            margin: 4px 0 0;
            font-size: 0.88rem;
            color: var(--ink-700);
        }

        .projects-card-list {
            display: grid;
            grid-template-columns: repeat(2, minmax(0, 1fr));
            gap: 12px;
            align-items: start;
        }

        .project-editor-card {
            display: grid;
            gap: 10px;
            padding: 14px;
            border: 1px solid rgba(175, 191, 207, 0.48);
            border-radius: 20px;
            background: linear-gradient(180deg, rgba(255, 255, 255, 0.82) 0%, rgba(244, 249, 253, 0.72) 100%);
            box-shadow:
                inset 0 1px 0 rgba(255, 255, 255, 0.55),
                0 12px 24px rgba(85, 116, 152, 0.06);
        }

        .project-editor-card-title {
            margin: 0;
            font-size: 0.94rem;
            font-weight: 700;
            color: var(--ink-900);
        }

        .project-editor-card-head {
            display: flex;
            align-items: start;
            justify-content: space-between;
            gap: 12px;
        }

        .project-editor-card-actions {
            display: flex;
            align-items: flex-start;
            justify-content: flex-end;
            gap: 12px;
            flex-wrap: wrap;
        }

        .project-editor-card.is-locked {
            background: linear-gradient(180deg, rgba(252, 253, 255, 0.96) 0%, rgba(241, 246, 251, 0.86) 100%);
        }

        .project-editor-card-meta {
            margin: 2px 0 0;
            font-size: 0.8rem;
            color: var(--ink-700);
            line-height: 1.4;
        }

        .project-editor-order-field {
            display: grid;
            gap: 4px;
            min-width: 96px;
        }

        .project-editor-order-label {
            font-size: 0.72rem;
            font-weight: 700;
            letter-spacing: 0.04em;
            text-transform: uppercase;
            color: var(--ink-700);
        }

        .project-editor-order-input {
            min-height: 34px;
            padding: 0 10px;
            border-radius: 12px;
        }

        .project-editor-lock-note {
            margin: -2px 0 2px;
            font-size: 0.8rem;
            color: var(--ink-700);
            line-height: 1.4;
        }

        .project-editor-remove,
        .project-editor-toggle,
        .project-editor-add {
            display: inline-flex;
            align-items: center;
            justify-content: center;
            min-height: 36px;
            padding: 0 12px;
            border: 1px solid rgba(164, 186, 214, 0.58);
            border-radius: 12px;
            background: rgba(255, 255, 255, 0.82);
            color: var(--ink-900);
            font-size: 0.8rem;
            font-weight: 700;
            transition: transform 0.18s ease, border-color 0.18s ease, box-shadow 0.18s ease, background-color 0.18s ease;
            cursor: pointer;
        }

        .project-editor-remove {
            align-self: end;
        }

        .project-editor-remove:hover,
        .project-editor-toggle:hover,
        .project-editor-add:hover {
            transform: translateY(-1px);
            border-color: rgba(47, 111, 219, 0.42);
            box-shadow: 0 14px 24px rgba(47, 111, 219, 0.08);
            background: rgba(255, 255, 255, 0.96);
        }

        .project-editor-toggle[aria-pressed="true"] {
            border-color: rgba(47, 111, 219, 0.42);
            background: rgba(47, 111, 219, 0.08);
            color: #1f55ab;
        }

        .project-editor-card.is-locked [data-project-edit-field] {
            background: rgba(245, 249, 253, 0.92);
            color: rgba(18, 25, 38, 0.78);
            cursor: default;
        }

        .project-editor-card .field-textarea {
            min-height: 118px;
        }

        .project-card-preview-wrap {
            display: grid;
            gap: 6px;
            justify-items: center;
            padding: 8px 10px 10px;
            border: 1px solid rgba(175, 191, 207, 0.34);
            border-radius: 16px;
            background: rgba(255, 255, 255, 0.46);
        }

        .project-card-preview {
            width: min(100%, 180px);
            aspect-ratio: 4 / 3;
            margin-left: auto;
            margin-right: auto;
            border-radius: 16px;
            box-shadow: 0 10px 18px rgba(52, 77, 110, 0.08);
        }

        .project-card-preview-caption {
            font-size: 0.72rem;
            line-height: 1.4;
            color: var(--ink-700);
            text-align: center;
        }

        .office-editor-grid {
            display: grid;
            grid-template-columns: minmax(0, 0.9fr) minmax(0, 1.1fr);
            gap: 18px;
            align-items: start;
        }

        .office-editor-grid--compact {
            grid-template-columns: repeat(2, minmax(0, 1fr));
        }

        .office-editor-panel {
            padding: 16px 18px;
            border: 1px solid rgba(175, 191, 207, 0.38);
            border-radius: 22px;
            background: linear-gradient(180deg, rgba(255, 255, 255, 0.52) 0%, rgba(243, 248, 253, 0.74) 100%);
        }

        .office-editor-note {
            display: block;
            font-size: 0.84rem;
            line-height: 1.6;
            color: var(--ink-700);
        }

        [data-office-editor-card] .field-textarea--compact {
            min-height: 150px;
        }

        .gallery-featured-grid {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(250px, 1fr));
            gap: 14px;
        }

        .gallery-featured-slot .thumbnail-preview {
            aspect-ratio: 4 / 3;
        }

        .gallery-featured-slot {
            gap: 8px;
            padding: 12px;
            border-radius: 18px;
        }

        .gallery-featured-slot .project-editor-card-title {
            font-size: 0.9rem;
        }

        .gallery-featured-slot .project-editor-card-meta {
            font-size: 0.76rem;
        }

        .gallery-featured-slot .field-file,
        .gallery-album-image-card .field-file {
            min-height: 42px;
            padding: 8px 12px;
        }

        .gallery-featured-slot .field-hint,
        .gallery-album-image-card .field-hint {
            line-height: 1.45;
        }

        .gallery-preview-wrap {
            display: grid;
            gap: 6px;
            justify-items: center;
            padding: 8px 10px 10px;
            border: 1px solid rgba(175, 191, 207, 0.34);
            border-radius: 16px;
            background: rgba(255, 255, 255, 0.46);
        }

        .gallery-preview-title {
            font-size: 0.76rem;
            font-weight: 700;
            color: var(--ink-900);
            text-align: center;
        }

        .gallery-preview-image {
            width: min(100%, 190px);
            aspect-ratio: 4 / 3;
            margin-left: auto;
            margin-right: auto;
            border-radius: 16px;
            box-shadow: 0 10px 18px rgba(52, 77, 110, 0.08);
        }

        .gallery-preview-caption {
            font-size: 0.72rem;
            line-height: 1.4;
            color: var(--ink-700);
            text-align: center;
        }

        .gallery-master-detail {
            display: grid;
            grid-template-columns: 290px minmax(0, 1fr);
            gap: 20px;
            align-items: start;
        }

        .gallery-album-sidebar {
            display: flex;
            flex-direction: column;
            gap: 12px;
            padding: 16px;
            border-radius: 20px;
            border: 1px solid rgba(175, 191, 207, 0.45);
            background: linear-gradient(180deg, rgba(255, 255, 255, 0.88) 0%, rgba(244, 249, 253, 0.76) 100%);
            box-shadow: 0 8px 24px rgba(85, 116, 152, 0.05);
            position: sticky;
            top: 20px;
        }

        .gallery-album-sidebar-head {
            display: flex;
            align-items: center;
            justify-content: space-between;
            gap: 10px;
            padding-bottom: 12px;
            border-bottom: 1px solid rgba(175, 191, 207, 0.35);
        }

        .gallery-album-sidebar-title {
            margin: 0;
            font-size: 0.95rem;
            font-weight: 700;
            color: var(--ink-900);
        }

        .gallery-album-sidebar-count {
            display: inline-block;
            font-size: 0.72rem;
            color: var(--ink-500);
            font-weight: 600;
        }

        .gallery-btn-add-album {
            display: inline-flex;
            align-items: center;
            gap: 6px;
            padding: 6px 12px;
            border-radius: 12px;
            border: 1px solid rgba(47, 111, 219, 0.3);
            background: rgba(47, 111, 219, 0.1);
            color: var(--accent);
            font-size: 0.78rem;
            font-weight: 700;
            cursor: pointer;
            transition: all 0.2s ease;
        }

        .gallery-btn-add-album:hover {
            background: var(--accent);
            color: #ffffff;
        }

        .gallery-album-nav {
            display: flex;
            flex-direction: column;
            gap: 6px;
            max-height: 480px;
            overflow-y: auto;
            padding-right: 2px;
        }

        .gallery-album-nav-item {
            display: flex;
            align-items: center;
            gap: 10px;
            width: 100%;
            padding: 10px 12px;
            border-radius: 14px;
            border: 1px solid transparent;
            background: transparent;
            color: var(--ink-900);
            text-align: left;
            cursor: pointer;
            transition: all 0.2s ease;
            position: relative;
        }

        .gallery-album-nav-item:hover {
            background: rgba(239, 244, 250, 0.65);
            border-color: rgba(175, 191, 207, 0.3);
        }

        .gallery-album-nav-item.is-active {
            background: #ffffff;
            border-color: rgba(47, 111, 219, 0.35);
            box-shadow: 0 4px 14px rgba(47, 111, 219, 0.12);
        }

        .gallery-album-nav-item.is-active .gallery-album-nav-item-icon {
            background: rgba(47, 111, 219, 0.12);
            color: var(--accent);
        }

        .gallery-album-nav-item.has-error {
            border-color: rgba(191, 74, 64, 0.45);
            background: rgba(191, 74, 64, 0.05);
        }

        .gallery-album-nav-item.has-error .gallery-album-nav-item-icon {
            background: rgba(191, 74, 64, 0.15);
            color: #bf4a40;
        }

        .gallery-album-nav-item-icon {
            display: flex;
            align-items: center;
            justify-content: center;
            width: 32px;
            height: 32px;
            border-radius: 10px;
            background: rgba(239, 244, 250, 0.9);
            color: var(--ink-500);
            flex-shrink: 0;
            transition: all 0.2s ease;
        }

        .gallery-album-nav-item-copy {
            display: flex;
            flex-direction: column;
            gap: 2px;
            overflow: hidden;
            text-align: left;
        }

        .gallery-album-nav-title {
            font-size: 0.86rem;
            font-weight: 600;
            white-space: nowrap;
            overflow: hidden;
            text-overflow: ellipsis;
            color: var(--ink-900);
        }

        .gallery-album-nav-count {
            font-size: 0.72rem;
            color: var(--ink-500);
        }

        .gallery-album-nav-empty {
            padding: 16px 8px;
            text-align: center;
            font-size: 0.8rem;
            color: var(--ink-500);
        }

        .gallery-album-workspace {
            min-width: 0;
        }

        .gallery-album-workspace-empty {
            display: flex;
            flex-direction: column;
            align-items: center;
            justify-content: center;
            min-height: 280px;
            padding: 36px 20px;
            border: 2px dashed rgba(175, 191, 207, 0.5);
            border-radius: 20px;
            text-align: center;
            background: rgba(255, 255, 255, 0.4);
            gap: 8px;
        }

        .gallery-album-workspace-empty-icon {
            font-size: 2rem;
            line-height: 1;
            margin-bottom: 4px;
        }

        .gallery-album-workspace-empty-title {
            font-size: 1.05rem;
            font-weight: 700;
            color: var(--ink-900);
        }

        .gallery-album-workspace-empty-desc {
            font-size: 0.85rem;
            color: var(--ink-500);
            max-width: 320px;
            margin-bottom: 8px;
        }

        .gallery-album-card {
            display: none;
            gap: 16px;
            padding: 16px;
            border-radius: 20px;
        }

        .gallery-album-card.is-active {
            display: grid;
        }

        .gallery-album-card .gallery-album-photos-group {
            gap: 14px;
            padding: 16px;
            border-radius: 18px;
            background: rgba(255, 255, 255, 0.65);
            border: 1px solid rgba(175, 191, 207, 0.38);
        }

        .gallery-album-image-list {
            display: grid;
            grid-template-columns: repeat(auto-fill, minmax(170px, 1fr));
            gap: 14px;
        }

        .gallery-photo-tile {
            position: relative;
            display: flex;
            flex-direction: column;
            border-radius: 14px;
            overflow: hidden;
            border: 1px solid rgba(175, 191, 207, 0.45);
            background: #ffffff;
            box-shadow: 0 4px 12px rgba(85, 116, 152, 0.06);
            transition: transform 0.2s ease, box-shadow 0.2s ease;
        }

        .gallery-photo-tile:hover {
            transform: translateY(-2px);
            box-shadow: 0 8px 20px rgba(85, 116, 152, 0.12);
        }

        .gallery-photo-tile-preview {
            position: relative;
            width: 100%;
            aspect-ratio: 4 / 3;
            background: #eef4f8;
            overflow: hidden;
            display: flex;
            align-items: center;
            justify-content: center;
        }

        .gallery-photo-tile-preview img {
            width: 100%;
            height: 100%;
            object-fit: cover;
            display: block;
        }

        .gallery-photo-tile-placeholder {
            display: flex;
            flex-direction: column;
            align-items: center;
            gap: 4px;
            color: var(--ink-500);
            font-size: 0.72rem;
        }

        .gallery-photo-tile-badge {
            position: absolute;
            top: 6px;
            left: 6px;
            padding: 2px 7px;
            border-radius: 7px;
            background: rgba(15, 31, 40, 0.75);
            color: #ffffff;
            font-size: 0.68rem;
            font-weight: 700;
            backdrop-filter: blur(4px);
        }

        .gallery-photo-tile-remove {
            position: absolute;
            top: 6px;
            right: 6px;
            width: 24px;
            height: 24px;
            border-radius: 7px;
            border: 0;
            background: rgba(191, 74, 64, 0.88);
            color: #ffffff;
            display: flex;
            align-items: center;
            justify-content: center;
            cursor: pointer;
            transition: background-color 0.2s ease, transform 0.2s ease;
            backdrop-filter: blur(4px);
        }

        .gallery-photo-tile-remove:hover {
            background: #bf4a40;
            transform: scale(1.1);
        }

        .gallery-photo-tile-actions {
            padding: 8px 10px;
            display: flex;
            flex-direction: column;
            gap: 4px;
            background: #ffffff;
        }

        .gallery-photo-tile-btn {
            display: inline-flex;
            align-items: center;
            justify-content: center;
            gap: 5px;
            width: 100%;
            min-height: 30px;
            padding: 0 8px;
            border-radius: 8px;
            border: 1px solid rgba(164, 186, 214, 0.55);
            background: rgba(239, 244, 250, 0.7);
            color: var(--ink-900);
            font-size: 0.74rem;
            font-weight: 600;
            cursor: pointer;
            transition: all 0.2s ease;
        }

        .gallery-photo-tile-btn:hover {
            background: rgba(47, 111, 219, 0.1);
            border-color: rgba(47, 111, 219, 0.4);
            color: var(--accent);
        }

        .gallery-photo-tile-file {
            position: absolute;
            opacity: 0;
            width: 0.1px;
            height: 0.1px;
            pointer-events: none;
        }

        @media (max-width: 900px) {
            .gallery-master-detail {
                grid-template-columns: 1fr;
            }

            .gallery-album-sidebar {
                position: static;
            }
        }

        .shareholder-review-list {
            display: flex;
            flex-direction: column;
            gap: 10px;
        }

        .shareholder-review-draft-host {
            margin-bottom: 12px;
        }

        .shareholder-review-draft-host[hidden] {
            display: none;
        }

        .review-accordion-card {
            border-radius: 14px;
            border: 1px solid rgba(175, 191, 207, 0.45);
            background: #ffffff;
            overflow: hidden;
            box-shadow: 0 2px 8px rgba(85, 116, 152, 0.04);
            transition: border-color 0.2s ease, box-shadow 0.2s ease;
            padding: 0;
            display: flex;
            flex-direction: column;
        }

        .review-accordion-card:hover {
            border-color: rgba(47, 111, 219, 0.35);
            box-shadow: 0 4px 14px rgba(85, 116, 152, 0.08);
        }

        .review-accordion-card.is-expanded {
            border-color: rgba(47, 111, 219, 0.45);
            box-shadow: 0 6px 20px rgba(47, 111, 219, 0.1);
        }

        .review-accordion-card.is-draft {
            border: 2px dashed rgba(47, 111, 219, 0.55);
            background: rgba(47, 111, 219, 0.02);
        }

        .review-card-summary {
            display: flex;
            align-items: center;
            justify-content: space-between;
            gap: 12px;
            padding: 10px 14px;
            cursor: pointer;
            user-select: none;
            background: #ffffff;
            border-bottom: 1px solid transparent;
            transition: background 0.2s ease, border-bottom-color 0.2s ease;
        }

        .review-accordion-card.is-expanded .review-card-summary {
            background: rgba(239, 244, 250, 0.55);
            border-bottom-color: rgba(175, 191, 207, 0.3);
        }

        .review-summary-left {
            display: flex;
            align-items: center;
            gap: 10px;
            min-width: 0;
            flex: 1;
        }

        .review-summary-chevron {
            display: flex;
            align-items: center;
            justify-content: center;
            color: var(--ink-500);
            transition: transform 0.22s cubic-bezier(0.25, 1, 0.5, 1), color 0.2s ease;
            flex-shrink: 0;
        }

        .review-accordion-card.is-expanded .review-summary-chevron {
            transform: rotate(180deg);
            color: var(--accent);
        }

        .review-summary-badge {
            display: inline-flex;
            align-items: center;
            padding: 3px 8px;
            border-radius: 7px;
            background: rgba(15, 31, 40, 0.08);
            color: var(--ink-900);
            font-size: 0.72rem;
            font-weight: 700;
            white-space: nowrap;
            flex-shrink: 0;
        }

        .review-accordion-card.is-expanded .review-summary-badge {
            background: var(--accent);
            color: #ffffff;
        }

        .review-summary-thumb {
            width: 56px;
            aspect-ratio: 16 / 9;
            border-radius: 6px;
            background: #eef4f8;
            overflow: hidden;
            flex-shrink: 0;
            display: flex;
            align-items: center;
            justify-content: center;
            border: 1px solid rgba(175, 191, 207, 0.35);
        }

        .review-summary-thumb img {
            width: 100%;
            height: 100%;
            object-fit: cover;
            display: block;
        }

        .review-summary-thumb-placeholder {
            color: var(--ink-400);
            display: flex;
            align-items: center;
            justify-content: center;
        }

        .review-summary-info {
            display: flex;
            flex-direction: column;
            gap: 2px;
            min-width: 0;
            overflow: hidden;
        }

        .review-summary-name {
            font-size: 0.86rem;
            font-weight: 700;
            color: var(--ink-900);
            white-space: nowrap;
            overflow: hidden;
            text-overflow: ellipsis;
        }

        .review-summary-url {
            font-size: 0.74rem;
            color: var(--ink-500);
            white-space: nowrap;
            overflow: hidden;
            text-overflow: ellipsis;
        }

        .review-summary-right {
            display: flex;
            align-items: center;
            gap: 8px;
            flex-shrink: 0;
        }

        .review-cover-tag {
            display: inline-flex;
            align-items: center;
            padding: 2px 8px;
            border-radius: 6px;
            font-size: 0.68rem;
            font-weight: 600;
            letter-spacing: 0.2px;
        }

        .review-cover-tag.is-custom {
            background: rgba(39, 174, 96, 0.12);
            color: #1e824c;
            border: 1px solid rgba(39, 174, 96, 0.3);
        }

        .review-cover-tag.is-youtube {
            background: rgba(229, 57, 53, 0.1);
            color: #c62828;
            border: 1px solid rgba(229, 57, 53, 0.25);
        }

        .review-cover-tag.is-none {
            background: rgba(140, 155, 170, 0.12);
            color: var(--ink-500);
        }

        .review-btn-toggle {
            display: inline-flex;
            align-items: center;
            justify-content: center;
            min-height: 28px;
            padding: 0 10px;
            border-radius: 7px;
            border: 1px solid rgba(175, 191, 207, 0.5);
            background: #ffffff;
            color: var(--ink-800);
            font-size: 0.74rem;
            font-weight: 600;
            cursor: pointer;
            transition: all 0.2s ease;
        }

        .review-btn-toggle:hover {
            border-color: var(--accent);
            color: var(--accent);
            background: rgba(47, 111, 219, 0.05);
        }

        .review-card-body {
            display: grid;
            grid-template-rows: 0fr;
            transition: grid-template-rows 0.22s cubic-bezier(0.25, 1, 0.5, 1);
            background: #ffffff;
            overflow: hidden;
        }

        .review-accordion-card.is-expanded .review-card-body {
            grid-template-rows: 1fr;
        }

        .review-card-body-inner {
            min-height: 0;
            padding: 16px;
            opacity: 0;
            visibility: hidden;
            transition: opacity 0.2s ease, visibility 0.2s ease;
        }

        .review-accordion-card.is-expanded .review-card-body-inner {
            opacity: 1;
            visibility: visible;
        }

        .review-card-editor-grid {
            display: grid;
            grid-template-columns: 240px minmax(0, 1fr);
            gap: 18px;
            align-items: start;
        }

        .review-live-preview-box {
            position: relative;
            width: 100%;
            aspect-ratio: 16 / 9;
            border-radius: 10px;
            background: #0f1f28;
            overflow: hidden;
            display: flex;
            align-items: center;
            justify-content: center;
            box-shadow: 0 4px 12px rgba(0, 0, 0, 0.15);
        }

        .review-live-preview-img {
            width: 100%;
            height: 100%;
            object-fit: cover;
            display: block;
        }

        .review-live-preview-placeholder {
            display: flex;
            flex-direction: column;
            align-items: center;
            justify-content: center;
            gap: 6px;
            padding: 12px;
            text-align: center;
            color: rgba(255, 255, 255, 0.6);
            font-size: 0.72rem;
        }

        .review-play-badge {
            position: absolute;
            width: 38px;
            height: 38px;
            border-radius: 50%;
            background: rgba(229, 57, 53, 0.92);
            color: #ffffff;
            display: flex;
            align-items: center;
            justify-content: center;
            pointer-events: none;
            box-shadow: 0 4px 10px rgba(0, 0, 0, 0.3);
            transition: transform 0.2s ease;
        }

        .review-thumbnail-controls {
            margin-top: 10px;
            display: flex;
            flex-direction: column;
            gap: 6px;
        }

        .review-thumbnail-remove-btn {
            font-size: 0.72rem;
            padding: 4px 8px;
            text-align: center;
            background: transparent;
            color: #bf4a40;
            border: 1px dashed rgba(191, 74, 64, 0.4);
            border-radius: 6px;
            cursor: pointer;
            transition: all 0.2s ease;
        }

        .review-thumbnail-remove-btn:hover {
            background: rgba(191, 74, 64, 0.08);
            border-color: #bf4a40;
        }

        .review-card-fields-pane {
            display: flex;
            flex-direction: column;
            gap: 12px;
        }

        .review-video-link-preview {
            color: var(--accent);
            font-size: 0.74rem;
            text-decoration: underline;
            font-weight: 600;
        }

        @media (max-width: 768px) {
            .review-card-editor-grid {
                grid-template-columns: 1fr;
            }

            .review-summary-url {
                display: none;
            }
        }

        /* Leadership Founder Card */
        .leadership-founder-card {
            border-radius: 14px;
            border: 1px solid rgba(175, 191, 207, 0.45);
            background: #ffffff;
            padding: 20px;
            box-shadow: 0 2px 8px rgba(85, 116, 152, 0.04);
            display: grid;
            grid-template-columns: 180px minmax(0, 1fr);
            gap: 24px;
            align-items: start;
        }

        .leadership-founder-photo-col {
            display: flex;
            flex-direction: column;
            align-items: center;
            gap: 10px;
            text-align: center;
        }

        .leadership-founder-photo-frame {
            width: 150px;
            height: 150px;
            border-radius: 18px;
            background: #eef4f8;
            border: 1px solid rgba(175, 191, 207, 0.5);
            overflow: hidden;
            display: flex;
            align-items: center;
            justify-content: center;
            box-shadow: 0 4px 12px rgba(15, 31, 40, 0.06);
            position: relative;
        }

        .leadership-founder-photo-frame img {
            width: 100%;
            height: 100%;
            object-fit: cover;
            display: block;
        }

        .leadership-photo-placeholder {
            color: var(--ink-400);
            display: flex;
            flex-direction: column;
            align-items: center;
            justify-content: center;
            gap: 6px;
        }

        .leadership-photo-picker-btn {
            display: inline-flex;
            align-items: center;
            justify-content: center;
            gap: 6px;
            padding: 7px 14px;
            border-radius: 8px;
            background: #ffffff;
            border: 1px solid rgba(175, 191, 207, 0.6);
            color: var(--ink-800);
            font-size: 0.76rem;
            font-weight: 600;
            cursor: pointer;
            transition: all 0.2s ease;
            box-shadow: 0 1px 3px rgba(0, 0, 0, 0.04);
        }

        .leadership-photo-picker-btn:hover {
            border-color: var(--accent);
            color: var(--accent);
            background: rgba(47, 111, 219, 0.05);
        }

        .leadership-photo-remove-btn {
            display: inline-flex;
            align-items: center;
            justify-content: center;
            gap: 5px;
            padding: 6px 12px;
            border-radius: 8px;
            background: #fff1f0;
            border: 1px solid rgba(229, 57, 53, 0.3);
            color: #e53935;
            font-size: 0.74rem;
            font-weight: 600;
            cursor: pointer;
            transition: all 0.2s ease;
        }

        .leadership-photo-remove-btn:hover {
            background: #e53935;
            color: #ffffff;
            border-color: #e53935;
        }

        .leadership-photo-file-input {
            position: absolute;
            opacity: 0;
            width: 0.1px;
            height: 0.1px;
            pointer-events: none;
        }

        .leadership-founder-fields-col {
            display: flex;
            flex-direction: column;
            gap: 16px;
            min-width: 0;
        }

        .leadership-founder-row {
            display: grid;
            grid-template-columns: repeat(2, minmax(0, 1fr));
            gap: 16px;
        }

        /* Leadership Board Member Accordion List */
        .leadership-member-accordion-list {
            display: flex;
            flex-direction: column;
            gap: 10px;
        }

        .leadership-member-accordion-card {
            border-radius: 14px;
            border: 1px solid rgba(175, 191, 207, 0.45);
            background: #ffffff;
            overflow: hidden;
            box-shadow: 0 2px 8px rgba(85, 116, 152, 0.04);
            transition: border-color 0.2s ease, box-shadow 0.2s ease;
            padding: 0;
            display: flex;
            flex-direction: column;
        }

        .leadership-member-accordion-card:hover {
            border-color: rgba(47, 111, 219, 0.35);
            box-shadow: 0 4px 14px rgba(85, 116, 152, 0.08);
        }

        .leadership-member-accordion-card.is-expanded {
            border-color: rgba(47, 111, 219, 0.45);
            box-shadow: 0 6px 20px rgba(47, 111, 219, 0.1);
        }

        .leadership-member-summary {
            display: flex;
            align-items: center;
            justify-content: space-between;
            gap: 12px;
            padding: 10px 14px;
            cursor: pointer;
            user-select: none;
            background: #ffffff;
            border-bottom: 1px solid transparent;
            transition: background 0.2s ease, border-bottom-color 0.2s ease;
        }

        .leadership-member-accordion-card.is-expanded .leadership-member-summary {
            background: rgba(239, 244, 250, 0.55);
            border-bottom-color: rgba(175, 191, 207, 0.3);
        }

        .leadership-summary-left {
            display: flex;
            align-items: center;
            gap: 12px;
            min-width: 0;
            flex: 1;
        }

        .leadership-summary-chevron {
            display: flex;
            align-items: center;
            justify-content: center;
            color: var(--ink-500);
            transition: transform 0.22s cubic-bezier(0.25, 1, 0.5, 1), color 0.2s ease;
            flex-shrink: 0;
        }

        .leadership-member-accordion-card.is-expanded .leadership-summary-chevron {
            transform: rotate(180deg);
            color: var(--accent);
        }

        .leadership-summary-badge {
            display: inline-flex;
            align-items: center;
            padding: 3px 8px;
            border-radius: 7px;
            background: rgba(15, 31, 40, 0.08);
            color: var(--ink-900);
            font-size: 0.72rem;
            font-weight: 700;
            white-space: nowrap;
            flex-shrink: 0;
        }

        .leadership-member-accordion-card.is-expanded .leadership-summary-badge {
            background: var(--accent);
            color: #ffffff;
        }

        .leadership-summary-avatar {
            width: 44px;
            height: 44px;
            border-radius: 50%;
            background: #eef4f8;
            overflow: hidden;
            flex-shrink: 0;
            display: flex;
            align-items: center;
            justify-content: center;
            border: 1px solid rgba(175, 191, 207, 0.4);
        }

        .leadership-summary-avatar img {
            width: 100%;
            height: 100%;
            object-fit: cover;
            display: block;
        }

        .leadership-avatar-placeholder {
            color: var(--ink-400);
            display: flex;
            align-items: center;
            justify-content: center;
        }

        .leadership-summary-info {
            display: flex;
            flex-direction: column;
            gap: 2px;
            min-width: 0;
            overflow: hidden;
        }

        .leadership-summary-name {
            font-size: 0.88rem;
            font-weight: 700;
            color: var(--ink-900);
            white-space: nowrap;
            overflow: hidden;
            text-overflow: ellipsis;
        }

        .leadership-summary-position {
            font-size: 0.76rem;
            color: var(--ink-500);
            white-space: nowrap;
            overflow: hidden;
            text-overflow: ellipsis;
        }

        .leadership-summary-right {
            display: flex;
            align-items: center;
            gap: 8px;
            flex-shrink: 0;
        }

        .leadership-btn-toggle {
            display: inline-flex;
            align-items: center;
            justify-content: center;
            min-height: 28px;
            padding: 0 10px;
            border-radius: 7px;
            border: 1px solid rgba(175, 191, 207, 0.5);
            background: #ffffff;
            color: var(--ink-800);
            font-size: 0.74rem;
            font-weight: 600;
            cursor: pointer;
            transition: all 0.2s ease;
        }

        .leadership-btn-toggle:hover {
            border-color: var(--accent);
            color: var(--accent);
            background: rgba(47, 111, 219, 0.05);
        }

        .leadership-btn-done {
            display: inline-flex;
            align-items: center;
            justify-content: center;
            min-height: 32px;
            padding: 0 14px;
            border-radius: 8px;
            border: 1px solid rgba(47, 111, 219, 0.3);
            background: rgba(47, 111, 219, 0.08);
            color: var(--accent);
            font-size: 0.78rem;
            font-weight: 600;
            cursor: pointer;
            transition: all 0.2s ease;
        }

        .leadership-btn-done:hover {
            background: var(--accent);
            color: #ffffff;
        }

        .leadership-member-body {
            display: grid;
            grid-template-rows: 0fr;
            transition: grid-template-rows 0.22s cubic-bezier(0.25, 1, 0.5, 1);
            background: #ffffff;
            overflow: hidden;
        }

        .leadership-member-accordion-card.is-expanded .leadership-member-body {
            grid-template-rows: 1fr;
        }

        .leadership-member-body-inner {
            min-height: 0;
            padding: 18px;
            opacity: 0;
            visibility: hidden;
            transition: opacity 0.2s ease, visibility 0.2s ease;
        }

        .leadership-member-accordion-card.is-expanded .leadership-member-body-inner {
            opacity: 1;
            visibility: visible;
        }

        .leadership-member-editor-grid {
            display: grid;
            grid-template-columns: 150px minmax(0, 1fr);
            gap: 20px;
            align-items: start;
        }

        .leadership-photo-editor-box {
            display: flex;
            flex-direction: column;
            align-items: center;
            gap: 10px;
        }

        .leadership-photo-tile-wrapper {
            display: flex;
            flex-direction: column;
            align-items: center;
            gap: 10px;
            text-align: center;
            width: 100%;
        }

        .leadership-photo-tile {
            width: 110px;
            height: 110px;
            border-radius: 16px;
            background: #eef4f8;
            border: 1px solid rgba(175, 191, 207, 0.4);
            overflow: hidden;
            display: flex;
            align-items: center;
            justify-content: center;
            box-shadow: 0 3px 8px rgba(15, 31, 40, 0.06);
        }

        .leadership-photo-tile img {
            width: 100%;
            height: 100%;
            object-fit: cover;
            display: block;
        }

        .leadership-photo-tile-placeholder {
            color: var(--ink-400);
            display: flex;
            align-items: center;
            justify-content: center;
        }

        .leadership-photo-upload-actions {
            display: flex;
            flex-direction: column;
            align-items: center;
            gap: 6px;
            width: 100%;
        }

        .leadership-member-fields-col {
            display: flex;
            flex-direction: column;
            gap: 14px;
            min-width: 0;
        }

        .leadership-member-card-footer {
            display: flex;
            align-items: center;
            justify-content: flex-end;
            gap: 10px;
            padding-top: 14px;
            margin-top: 14px;
            border-top: 1px solid rgba(175, 191, 207, 0.25);
        }

        .leadership-group-actions {
            display: flex;
            align-items: center;
            gap: 8px;
            flex-wrap: wrap;
        }

        .leadership-btn-text {
            padding: 0 10px;
            min-height: 32px;
            font-size: 0.74rem;
            font-weight: 600;
            background: #ffffff;
            border: 1px solid rgba(175, 191, 207, 0.5);
            color: var(--ink-800);
            border-radius: 8px;
            cursor: pointer;
            transition: all 0.2s ease;
        }

        .leadership-btn-text:hover {
            border-color: var(--accent);
            color: var(--accent);
            background: rgba(47, 111, 219, 0.05);
        }

        .leadership-founder-preview,
        .leadership-member-preview {
            width: 180px;
            aspect-ratio: 1 / 1;
            border-radius: 24px;
            margin-left: auto;
            margin-right: auto;
        }

        /* Shareholder Search & Infinite Scroll */
        .shareholder-search-toolbar {
            display: flex;
            align-items: center;
            gap: 10px;
            margin-bottom: 14px;
            flex-wrap: wrap;
        }

        .shareholder-search-box {
            position: relative;
            flex: 1;
            min-width: 240px;
        }

        .shareholder-search-icon {
            position: absolute;
            left: 12px;
            top: 50%;
            transform: translateY(-50%);
            color: var(--ink-400);
            pointer-events: none;
            display: flex;
            align-items: center;
        }

        .shareholder-search-input {
            padding-left: 38px !important;
            padding-right: 36px !important;
            min-height: 38px;
            font-size: 0.84rem;
        }

        .shareholder-search-clear-btn {
            position: absolute;
            right: 10px;
            top: 50%;
            transform: translateY(-50%);
            display: inline-flex;
            align-items: center;
            justify-content: center;
            width: 22px;
            height: 22px;
            border-radius: 50%;
            background: rgba(15, 31, 40, 0.08);
            border: none;
            color: var(--ink-700);
            cursor: pointer;
            padding: 0;
            transition: background 0.15s ease, color 0.15s ease;
        }

        .shareholder-search-clear-btn:hover {
            background: rgba(15, 31, 40, 0.16);
            color: var(--ink-900);
        }

        .shareholder-search-feedback {
            font-size: 0.78rem;
            color: var(--ink-500);
            font-weight: 600;
        }

        .shareholder-infinite-loader {
            display: flex;
            align-items: center;
            justify-content: center;
            gap: 10px;
            padding: 14px;
            color: var(--ink-500);
            font-size: 0.82rem;
            font-weight: 600;
            background: rgba(239, 244, 250, 0.5);
            border-radius: 10px;
            border: 1px dashed rgba(175, 191, 207, 0.45);
            margin-top: 10px;
        }

        .shareholder-new-card-host {
            margin-bottom: 12px;
        }

        .shareholder-empty-state {
            padding: 24px;
            text-align: center;
            color: var(--ink-500);
            font-size: 0.88rem;
            background: #ffffff;
            border-radius: 12px;
            border: 1px dashed rgba(175, 191, 207, 0.45);
        }

        .review-editor-card-actions {
            display: inline-flex;
            align-items: center;
            gap: 10px;
            flex-wrap: wrap;
            justify-content: flex-end;
        }

        .shareholder-review-editor-card.is-draft {
            border-color: rgba(63, 127, 228, 0.32);
            background: linear-gradient(180deg, rgba(255, 255, 255, 0.68) 0%, rgba(238, 245, 255, 0.88) 100%);
            box-shadow: 0 18px 34px rgba(63, 127, 228, 0.08);
        }

        .review-thumbnail-preview {
            aspect-ratio: 16 / 9;
        }

        .review-thumbnail-preview-wrap {
            display: grid;
            gap: 10px;
        }

        .review-thumbnail-preview-wrap.is-collapsed .thumbnail-preview {
            display: none;
        }

        .review-thumbnail-actions {
            display: flex;
            gap: 10px;
            flex-wrap: wrap;
        }

        .review-thumbnail-remove {
            justify-self: start;
        }

        .submit-button {
            min-height: 46px;
            padding: 0 18px;
            border: 0;
            border-radius: 16px;
            background: linear-gradient(180deg, #3f7fe4 0%, #2f6fdb 100%);
            color: #ffffff;
            font-weight: 700;
            cursor: pointer;
            box-shadow: 0 14px 24px rgba(47, 111, 219, 0.16);
            transition: transform 0.2s ease, box-shadow 0.2s ease;
        }

        .submit-button:hover {
            transform: translateY(-1px);
            box-shadow: 0 16px 28px rgba(47, 111, 219, 0.18);
        }

        @media (max-width: 840px) {
            .media-grid,
            .notice-media-grid {
                grid-template-columns: 1fr;
            }

            .office-editor-grid {
                grid-template-columns: 1fr;
            }

            .why-intro-grid,
            .why-media-grid,
            .projects-header-grid,
            .projects-card-list,
            .leadership-founder-card,
            .leadership-founder-row,
            .leadership-member-editor-grid,
            .gallery-featured-grid,
            .gallery-album-image-list,
            .shareholder-review-list {
                grid-template-columns: 1fr;
            }

            .editor-header {
                flex-direction: column;
                align-items: flex-start;
            }
        }

        @media (max-width: 720px) {
            .editor-panel {
                padding: 18px;
            }

            .module-button {
                flex: 1 1 calc(50% - 10px);
                min-height: 46px;
            }

            .submit-button {
                width: 100%;
            }
        }

        @media (max-width: 480px) {
            .module-button {
                flex: 1 1 100%;
            }
        }

        /* ==========================================================================
           FOOTER SUB-NAV & TAB SYSTEM
           ========================================================================== */
        .footer-management-panel {
            padding: 24px;
            border-radius: 24px;
            background: #ffffff;
            box-shadow: 0 10px 30px rgba(15, 23, 42, 0.05);
            border: 1px solid rgba(226, 232, 240, 0.8);
        }

        .footer-header-badges {
            display: flex;
            align-items: center;
            gap: 10px;
        }

        .footer-subnav {
            display: flex;
            flex-wrap: wrap;
            gap: 8px;
            padding: 6px;
            border-radius: 16px;
            background: #f1f5f9;
            border: 1px solid rgba(203, 213, 225, 0.6);
            margin: 10px 0 20px;
        }

        .footer-subnav-btn {
            position: relative;
            display: inline-flex;
            align-items: center;
            gap: 10px;
            padding: 10px 18px;
            border: none;
            border-radius: 12px;
            background: transparent;
            color: #475569;
            font-size: 0.88rem;
            font-weight: 600;
            cursor: pointer;
            transition: all 0.2s cubic-bezier(0.4, 0, 0.2, 1);
        }

        .footer-subnav-btn:hover {
            color: #0f172a;
            background: rgba(255, 255, 255, 0.6);
        }

        .footer-subnav-btn.is-active {
            background: #ffffff;
            color: #1d4ed8;
            font-weight: 700;
            box-shadow: 0 4px 12px rgba(15, 23, 42, 0.06), 0 1px 2px rgba(15, 23, 42, 0.04);
        }

        .footer-subnav-icon {
            display: inline-flex;
            align-items: center;
            justify-content: center;
            color: currentColor;
        }

        .footer-tab-badge {
            display: inline-flex;
            align-items: center;
            justify-content: center;
            min-width: 20px;
            height: 20px;
            padding: 0 6px;
            border-radius: 999px;
            background: #e2e8f0;
            color: #334155;
            font-size: 0.75rem;
            font-weight: 700;
        }

        .footer-subnav-btn.is-active .footer-tab-badge {
            background: #dbeafe;
            color: #1d4ed8;
        }

        .footer-tab-err-dot {
            width: 7px;
            height: 7px;
            border-radius: 999px;
            background: #ef4444;
            box-shadow: 0 0 0 2px #ffffff;
        }

        .footer-tab-panel {
            display: grid;
            gap: 18px;
            animation: fadeInTab 0.2s ease-out;
        }

        .footer-tab-panel[hidden] {
            display: none !important;
        }

        @keyframes fadeInTab {
            from {
                opacity: 0;
                transform: translateY(4px);
            }
            to {
                opacity: 1;
                transform: translateY(0);
            }
        }

        .subtab-header {
            display: flex;
            align-items: flex-start;
            justify-content: space-between;
            gap: 16px;
            padding-bottom: 14px;
            border-bottom: 1px solid rgba(226, 232, 240, 0.9);
            margin-bottom: 4px;
        }

        .subtab-header-copy {
            display: grid;
            gap: 4px;
        }

        .subtab-kicker {
            font-size: 0.72rem;
            font-weight: 800;
            text-transform: uppercase;
            letter-spacing: 0.08em;
            color: #3b82f6;
        }

        .subtab-title {
            margin: 0;
            font-size: 1.12rem;
            font-weight: 800;
            color: #0f172a;
            letter-spacing: -0.01em;
        }

        .subtab-subtitle {
            margin: 0;
            font-size: 0.84rem;
            color: #64748b;
            line-height: 1.5;
        }

        /* Input Icon Prefixes */
        .input-icon-group {
            position: relative;
            display: flex;
            align-items: center;
            width: 100%;
        }

        .input-icon {
            position: absolute;
            left: 14px;
            display: inline-flex;
            align-items: center;
            justify-content: center;
            color: #64748b;
            pointer-events: none;
            z-index: 2;
        }

        .input-icon--brand-youtube { color: #ef4444; }
        .input-icon--brand-facebook { color: #1877f2; }
        .input-icon--brand-email { color: #f59e0b; }
        .input-icon--brand-map { color: #10b981; }
        .input-icon--danger { color: #ef4444; }
        .input-icon--success { color: #10b981; }
        .input-icon--info { color: #0284c7; }

        .field-input--with-icon {
            padding-left: 44px !important;
        }

        /* Office Branch Card Polish */
        .office-card-enhanced {
            background: #ffffff;
            border: 1px solid rgba(203, 213, 225, 0.8);
            border-radius: 18px;
            padding: 18px 20px;
            box-shadow: 0 4px 14px rgba(15, 23, 42, 0.03);
            transition: all 0.2s ease;
        }

        .office-card-enhanced:hover {
            border-color: rgba(96, 165, 250, 0.6);
            box-shadow: 0 8px 22px rgba(37, 99, 235, 0.06);
        }

        .office-card-head {
            display: flex;
            align-items: center;
            justify-content: space-between;
            gap: 14px;
            margin-bottom: 16px;
            padding-bottom: 12px;
            border-bottom: 1px solid rgba(241, 245, 249, 1);
        }

        .office-card-title-group {
            display: flex;
            align-items: center;
            gap: 10px;
        }

        .office-badge {
            display: inline-flex;
            align-items: center;
            padding: 4px 9px;
            border-radius: 8px;
            background: #eff6ff;
            color: #1e40af;
            font-size: 0.72rem;
            font-weight: 700;
            text-transform: uppercase;
            letter-spacing: 0.04em;
        }

        .office-remove-btn {
            display: inline-flex;
            align-items: center;
            gap: 6px;
            color: #ef4444 !important;
            border-color: rgba(239, 68, 68, 0.25) !important;
            background: rgba(254, 242, 242, 0.7) !important;
            border-radius: 10px !important;
            font-size: 0.78rem !important;
            font-weight: 700 !important;
            padding: 6px 12px !important;
            min-height: 32px !important;
            cursor: pointer;
            transition: all 0.15s ease;
        }

        .office-remove-btn:hover {
            background: #ef4444 !important;
            color: #ffffff !important;
            border-color: #ef4444 !important;
            transform: translateY(-1px);
        }

        .office-add-btn-top {
            display: inline-flex;
            align-items: center;
            gap: 8px;
            background: linear-gradient(135deg, #2563eb 0%, #1d4ed8 100%);
            color: #ffffff !important;
            border: none;
            border-radius: 12px;
            padding: 8px 16px;
            font-size: 0.84rem;
            font-weight: 700;
            cursor: pointer;
            box-shadow: 0 4px 12px rgba(37, 99, 235, 0.2);
            transition: all 0.2s ease;
        }

        .office-add-btn-top:hover {
            transform: translateY(-1px);
            box-shadow: 0 6px 16px rgba(37, 99, 235, 0.3);
            background: linear-gradient(135deg, #1d4ed8 0%, #1e40af 100%);
        }

        /* Sticky Action Bar */
        .footer-sticky-bar {
            position: sticky;
            bottom: 16px;
            z-index: 20;
            display: flex;
            align-items: center;
            justify-content: space-between;
            gap: 16px;
            margin-top: 24px;
            padding: 14px 20px;
            border-radius: 18px;
            background: rgba(255, 255, 255, 0.95);
            backdrop-filter: blur(12px);
            -webkit-backdrop-filter: blur(12px);
            border: 1px solid rgba(203, 213, 225, 0.8);
            box-shadow: 0 12px 32px rgba(15, 23, 42, 0.12), 0 2px 6px rgba(15, 23, 42, 0.04);
        }

        .footer-sticky-status {
            display: flex;
            align-items: center;
            gap: 16px;
            flex-wrap: wrap;
        }

        .footer-sync-indicator {
            display: inline-flex;
            align-items: center;
            gap: 8px;
            font-size: 0.82rem;
            font-weight: 600;
            color: #10b981;
        }

        .footer-sync-indicator .footer-sync-dot {
            width: 8px;
            height: 8px;
            border-radius: 999px;
            background: #10b981;
            box-shadow: 0 0 0 3px rgba(16, 185, 129, 0.2);
        }

        .footer-sync-indicator.is-dirty {
            color: #f59e0b;
        }

        .footer-sync-indicator.is-dirty .footer-sync-dot {
            background: #f59e0b;
            box-shadow: 0 0 0 3px rgba(245, 158, 11, 0.2);
        }

        .footer-view-live-link {
            display: inline-flex;
            align-items: center;
            gap: 6px;
            font-size: 0.82rem;
            font-weight: 600;
            color: #64748b;
            text-decoration: none;
            padding: 5px 12px;
            border-radius: 8px;
            background: #f1f5f9;
            transition: all 0.15s ease;
        }

        .footer-view-live-link:hover {
            color: #0f172a;
            background: #e2e8f0;
        }

        .footer-save-btn {
            min-width: 190px;
            height: 44px;
            background: linear-gradient(135deg, #1e3a8a 0%, #2563eb 100%) !important;
            color: #ffffff !important;
            border-radius: 12px !important;
            font-weight: 700 !important;
            box-shadow: 0 4px 14px rgba(37, 99, 235, 0.25) !important;
        }

        .footer-save-btn:hover {
            box-shadow: 0 6px 18px rgba(37, 99, 235, 0.35) !important;
            transform: translateY(-1px);
        }

        @media (max-width: 768px) {
            .footer-subnav {
                flex-direction: column;
            }
            .footer-subnav-btn {
                width: 100%;
                justify-content: flex-start;
            }
            .footer-sticky-bar {
                flex-direction: column;
                align-items: stretch;
            }
            .footer-save-btn {
                width: 100%;
            }
        }
    </style>
@endpush

@section('content')
    @php
        $aboutFieldNames = ['title', 'subtitle', 'description', 'left_video_url', 'right_video_url', 'left_thumbnail', 'right_thumbnail'];
        $whyFieldNames = ['why_title', 'why_description', 'feature_points', 'cta_label', 'cta_url', 'video_url', 'thumbnail'];
        $projectModuleHasErrors = collect($errors->keys())->contains(
            fn ($field) => str_starts_with($field, 'projects_')
                || str_starts_with($field, 'top_project_cards.')
                || str_starts_with($field, 'bottom_project_cards.')
                || str_starts_with($field, 'top_project_card_images.')
                || str_starts_with($field, 'bottom_project_card_images.')
        );
        $galleryFieldNames = ['gallery_section_title', 'gallery_section_subtitle', 'gallery_view_all_label', 'gallery_page_title', 'gallery_page_subtitle'];
        $galleryModuleHasErrors = collect($errors->keys())->contains(
            fn ($field) => in_array($field, $galleryFieldNames, true)
                || str_starts_with($field, 'featured_images.')
                || str_starts_with($field, 'featured_image_uploads.')
                || str_starts_with($field, 'albums.')
                || str_starts_with($field, 'album_image_uploads.')
        );
        $reviewFieldNames = ['review_section_title', 'review_section_subtitle'];
        $reviewModuleHasErrors = collect($errors->keys())->contains(
            fn ($field) => in_array($field, $reviewFieldNames, true)
                || str_starts_with($field, 'shareholder_reviews.')
                || str_starts_with($field, 'shareholder_review_thumbnails.')
        );
        $leadershipFieldNames = ['section_title', 'founder_name', 'founder_position', 'founder_description', 'founder_image', 'founder_image_path', 'is_visible'];
        $leadershipModuleHasErrors = collect($errors->keys())->contains(
            fn ($field) => in_array($field, $leadershipFieldNames, true)
                || str_starts_with($field, 'board_members.')
                || str_starts_with($field, 'board_member_images.')
        );
        $valuedShareholderFieldNames = ['shareholder_section_title', 'shareholder_section_visible'];
        $valuedShareholderModuleHasErrors = collect($errors->keys())->contains(
            fn ($field) => in_array($field, $valuedShareholderFieldNames, true)
                || str_starts_with($field, 'shareholders.')
                || str_starts_with($field, 'shareholder_images.')
        );
        $footerFieldNames = ['youtube_url', 'facebook_url', 'contact_email', 'contact_phone', 'location_title', 'location_subtitle', 'location_map_url', 'office_section_title', 'office_section_subtitle', 'terms_title', 'terms_subtitle', 'terms_intro', 'terms_content'];
        $footerModuleHasErrors = collect($errors->keys())->contains(
            fn ($field) => in_array($field, $footerFieldNames, true) || str_starts_with($field, 'office_cards.')
        );
        $activeFooterSubtab = 'social';
        if ($errors->has('location_title') || $errors->has('location_subtitle') || $errors->has('location_map_url')) {
            $activeFooterSubtab = 'location';
        } elseif ($errors->has('office_section_title') || $errors->has('office_section_subtitle') || collect($errors->keys())->contains(fn ($f) => str_starts_with($f, 'office_cards.'))) {
            $activeFooterSubtab = 'offices';
        } elseif ($errors->has('terms_title') || $errors->has('terms_subtitle') || $errors->has('terms_intro') || $errors->has('terms_content')) {
            $activeFooterSubtab = 'terms';
        } elseif ($errors->has('youtube_url') || $errors->has('facebook_url') || $errors->has('contact_email') || $errors->has('contact_phone')) {
            $activeFooterSubtab = 'social';
        }
        $topProjectCards = old('top_project_cards', $projectSection?->topCardsForEditor() ?? []);
        $bottomProjectCards = old('bottom_project_cards', $projectSection?->bottomCardsForEditor() ?? []);
        $featuredGalleryImages = collect(old('featured_images', $gallerySection?->featuredImagesForEditor() ?? \App\Models\GallerySection::emptyFeaturedImagesForEditor()))
            ->values()
            ->all();
        $galleryAlbums = collect(old('albums', $gallerySection?->albumsForEditor() ?? []))
            ->map(function ($album): array {
                $data = is_array($album) ? $album : [];
                $data['images'] = collect($data['images'] ?? [])->values()->all();

                return $data;
            })
            ->values()
            ->all();
        $shareholderReviews = collect(old('shareholder_reviews', $shareholderReviewSection?->reviewsForEditor() ?? []))
            ->values()
            ->all();
        $boardMembers = collect(old('board_members', $leadershipSection?->boardMembersForEditor() ?? []))
            ->values()
            ->all();
        $valuedShareholdersTotal = \App\Models\ValuedShareholder::query()->count();
        $valuedShareholders = \App\Models\ValuedShareholder::query()
            ->orderBy('sort_order')
            ->orderBy('id', 'desc')
            ->take(20)
            ->get()
            ->map->toEditorArray()
            ->all();
        $officeCards = old('office_cards', $footerSetting?->officeCardsForEditor() ?? []);
        $activeModule = $footerModuleHasErrors
            ? 'footer'
            : ($valuedShareholderModuleHasErrors
                ? 'valued-shareholders'
                : ($leadershipModuleHasErrors
                ? 'leadership'
                : ($reviewModuleHasErrors
                ? 'reviews'
                : ($galleryModuleHasErrors
                    ? 'gallery'
                    : ($projectModuleHasErrors
                    ? 'projects'
                    : (collect($whyFieldNames)->contains(fn ($field) => $errors->has($field))
                    ? 'why'
                    : (collect($aboutFieldNames)->contains(fn ($field) => $errors->has($field)) ? 'about' : 'notice')))))));
    @endphp

    <header class="admin-header">
        <div class="admin-header-copy">
            <p class="admin-kicker">Website Controls</p>
            <h1 class="admin-title">Content Management</h1>
            <p class="admin-subtitle">Manage the homepage notice ticker, About section, projects, gallery, reviews, leadership, valued shareholders, location, and footer content from one place.</p>
        </div>

        <div class="admin-profile">
            <div class="admin-avatar">{{ $admin->displayInitials() }}</div>
            <div class="admin-profile-copy">
                <p class="admin-profile-name">{{ $admin->displayName() }}</p>
                <p class="admin-profile-email" data-autofit-text data-max-size="16" data-min-size="10">{{ $admin->email }}</p>
                <p class="admin-profile-email">{{ $admin->name }}</p>
            </div>
        </div>
    </header>

    <section class="content-shell">
        <article class="admin-card content-overview">
            <div class="content-overview-copy">
                <p class="section-kicker">Modules</p>
                <h2>Choose what you want to manage.</h2>
                <p class="admin-subtitle">Click a module button below, then update only that section. About, Why, Projects, Gallery, Reviews, Leadership, Valued Shareholders, Location, and Footer content all stay editable from here.</p>
            </div>

            <div class="module-grid">
                <button class="module-button {{ $activeModule === 'notice' ? 'is-active' : '' }}" type="button" aria-expanded="{{ $activeModule === 'notice' ? 'true' : 'false' }}" aria-controls="notice-editor-panel" data-module-toggle="notice-editor-panel">
                    <span class="module-btn-icon">
                        <svg width="15" height="15" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M18 8A6 6 0 0 0 6 8c0 7-3 9-3 9h18s-3-2-3-9"></path><path d="M13.73 21a2 2 0 0 1-3.46 0"></path></svg>
                    </span>
                    <span class="module-title">Notice</span>
                </button>

                <button class="module-button {{ $activeModule === 'about' ? 'is-active' : '' }}" type="button" aria-expanded="{{ $activeModule === 'about' ? 'true' : 'false' }}" aria-controls="about-editor-panel" data-module-toggle="about-editor-panel">
                    <span class="module-btn-icon">
                        <svg width="15" height="15" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><circle cx="12" cy="12" r="10"></circle><line x1="12" y1="16" x2="12" y2="12"></line><line x1="12" y1="8" x2="12.01" y2="8"></line></svg>
                    </span>
                    <span class="module-title">About</span>
                </button>

                <button class="module-button {{ $activeModule === 'projects' ? 'is-active' : '' }}" type="button" aria-expanded="{{ $activeModule === 'projects' ? 'true' : 'false' }}" aria-controls="projects-editor-panel" data-module-toggle="projects-editor-panel">
                    <span class="module-btn-icon">
                        <svg width="15" height="15" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><rect x="2" y="7" width="20" height="14" rx="2" ry="2"></rect><path d="M16 21V5a2 2 0 0 0-2-2h-4a2 2 0 0 0-2 2v16"></path></svg>
                    </span>
                    <span class="module-title">Projects</span>
                </button>

                <button class="module-button {{ $activeModule === 'gallery' ? 'is-active' : '' }}" type="button" aria-expanded="{{ $activeModule === 'gallery' ? 'true' : 'false' }}" aria-controls="gallery-editor-panel" data-module-toggle="gallery-editor-panel">
                    <span class="module-btn-icon">
                        <svg width="15" height="15" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><rect x="3" y="3" width="18" height="18" rx="2" ry="2"></rect><circle cx="8.5" cy="8.5" r="1.5"></circle><polyline points="21 15 16 10 5 21"></polyline></svg>
                    </span>
                    <span class="module-title">Gallery</span>
                </button>

                <button class="module-button {{ $activeModule === 'reviews' ? 'is-active' : '' }}" type="button" aria-expanded="{{ $activeModule === 'reviews' ? 'true' : 'false' }}" aria-controls="reviews-editor-panel" data-module-toggle="reviews-editor-panel">
                    <span class="module-btn-icon">
                        <svg width="15" height="15" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><polygon points="12 2 15.09 8.26 22 9.27 17 14.14 18.18 21.02 12 17.77 5.82 21.02 7 14.14 2 9.27 8.91 8.26 12 2"></polygon></svg>
                    </span>
                    <span class="module-title">Reviews</span>
                </button>

                <button class="module-button {{ $activeModule === 'leadership' ? 'is-active' : '' }}" type="button" aria-expanded="{{ $activeModule === 'leadership' ? 'true' : 'false' }}" aria-controls="leadership-editor-panel" data-module-toggle="leadership-editor-panel">
                    <span class="module-btn-icon">
                        <svg width="15" height="15" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M17 21v-2a4 4 0 0 0-4-4H5a4 4 0 0 0-4 4v2"></path><circle cx="9" cy="7" r="4"></circle><path d="M23 21v-2a4 4 0 0 0-3-3.87"></path><path d="M16 3.13a4 4 0 0 1 0 7.75"></path></svg>
                    </span>
                    <span class="module-title">Leadership</span>
                </button>

                <button class="module-button {{ $activeModule === 'valued-shareholders' ? 'is-active' : '' }}" type="button" aria-expanded="{{ $activeModule === 'valued-shareholders' ? 'true' : 'false' }}" aria-controls="valued-shareholders-editor-panel" data-module-toggle="valued-shareholders-editor-panel">
                    <span class="module-btn-icon">
                        <svg width="15" height="15" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M12 2l3.09 6.26L22 9.27l-5 4.87 1.18 6.88L12 17.77l-6.18 3.25L7 14.14 2 9.27l6.91-1.01L12 2z"></path></svg>
                    </span>
                    <span class="module-title">Shareholders</span>
                </button>

                <button class="module-button {{ $activeModule === 'footer' ? 'is-active' : '' }}" type="button" aria-expanded="{{ $activeModule === 'footer' ? 'true' : 'false' }}" aria-controls="footer-editor-panel" data-module-toggle="footer-editor-panel">
                    <span class="module-btn-icon">
                        <svg width="15" height="15" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><circle cx="12" cy="12" r="10"></circle><line x1="2" y1="12" x2="22" y2="12"></line><path d="M12 2a15.3 15.3 0 0 1 4 10 15.3 15.3 0 0 1-4 10 15.3 15.3 0 0 1-4-10 15.3 15.3 0 0 1 4-10z"></path></svg>
                    </span>
                    <span class="module-title">Footer & Location</span>
                </button>
            </div>
        </article>

        <article class="admin-card editor-panel" id="notice-editor-panel" data-module-panel @if($activeModule !== 'notice') hidden @endif>
            <div class="editor-header">
                <div class="editor-copy">
                    <p class="section-kicker">Notice Editor</p>
                    <h2>Public Notice Ticker</h2>
                    <p class="admin-subtitle">Write the notice here and update the homepage hero background image. If visibility is on, the public website will show the sliding ticker.</p>
                </div>

                <span class="editor-status {{ $notice?->is_active ? '' : 'is-hidden' }}">
                    {{ $notice?->is_active ? 'Visible on website' : 'Hidden on website' }}
                </span>
            </div>

            <form class="editor-form" action="{{ route('admin.content.notice.update') }}" method="post" enctype="multipart/form-data">
                @csrf
                @method('patch')

                <label class="toggle-bar">
                    <span class="toggle-switch">
                        <input type="checkbox" name="is_active" value="1" @checked(old('is_active', $notice?->exists ? $notice->is_active : true))>
                        <span class="toggle-track" aria-hidden="true">
                            <span class="toggle-thumb"></span>
                        </span>
                    </span>

                    <span class="toggle-copy">
                        <span class="toggle-title">Show notice on website</span>
                        <span class="toggle-meta">Turn this off if you want to save the message without showing it publicly.</span>
                    </span>
                </label>

                <div class="field-group">
                    <label class="field-label" for="message">Notice message</label>
                    <textarea class="field-textarea" id="message" name="message" placeholder="Write the public notice here" required>{{ old('message', $notice?->message) }}</textarea>
                    @error('message')
                        <span class="field-error">{{ $message }}</span>
                    @enderror
                </div>

                <div class="media-grid notice-media-grid">
                    <div class="field-group">
                        <label class="field-label" for="hero-background">Hero background image</label>
                        <input type="hidden" name="hero_background_path" value="{{ old('hero_background_path', $notice?->hero_background_path) }}">
                        <input class="field-file" id="hero-background" type="file" name="hero_background" accept="image/*">
                        <div class="notice-media-copy">
                            <p class="notice-media-note"><strong>Recommended size:</strong> 1920 x 1080 px or larger.</p>
                            <p class="notice-media-note">Keep the main subject centered because the hero image uses cover mode and may crop on mobile and desktop.</p>
                        </div>
                        @error('hero_background')
                            <span class="field-error">{{ $message }}</span>
                        @enderror
                    </div>

                    <div class="field-group notice-hero-preview-group">
                        <span class="field-label">Current hero preview</span>
                        <div class="thumbnail-preview notice-hero-preview">
                            <img
                                src="{{ filled(old('hero_background_path', $notice?->hero_background_path)) ? asset(ltrim((string) old('hero_background_path', $notice?->hero_background_path), '/')) : asset('images/beautiful-rustic-house-landscape.webp') }}"
                                alt="Hero background preview"
                                loading="lazy"
                                decoding="async"
                            >
                        </div>
                        <span class="field-hint">If you do not upload a new image, the current hero background will stay unchanged.</span>
                    </div>
                </div>

                <div class="editor-actions">
                    <button class="submit-button" type="submit">Save Notice</button>
                </div>
            </form>
        </article>

        <article class="admin-card editor-panel" id="about-editor-panel" data-module-panel @if($activeModule !== 'about') hidden @endif>
            <div class="editor-header">
                <div class="editor-copy">
                    <p class="section-kicker">About Editor</p>
                    <h2>Homepage About Section</h2>
                    <p class="admin-subtitle">Set the title, description, two YouTube video links, and two thumbnails. Uploaded thumbnails are saved as WebP automatically.</p>
                </div>

                <span class="editor-status {{ $aboutSection?->hasRenderableContent() ? '' : 'is-hidden' }}">
                    {{ $aboutSection?->hasRenderableContent() ? 'Ready for website' : 'No public content yet' }}
                </span>
            </div>

            <form class="editor-form" action="{{ route('admin.content.about.update') }}" method="post" enctype="multipart/form-data" data-about-form>
                @csrf
                @method('patch')

                <div class="field-group">
                    <label class="field-label" for="about-title">About title</label>
                    <input class="field-input" id="about-title" type="text" name="title" value="{{ old('title', $aboutSection?->title) }}" placeholder="About King Lotus">
                    @error('title')
                        <span class="field-error">{{ $message }}</span>
                    @enderror
                </div>

                <div class="field-group">
                    <label class="field-label" for="about-subtitle">About subtitle</label>
                    <input class="field-input" id="about-subtitle" type="text" name="subtitle" value="{{ old('subtitle', $aboutSection?->subtitle) }}" placeholder="A Luxury & Signature Destination in Cox's Bazar.">
                    @error('subtitle')
                        <span class="field-error">{{ $message }}</span>
                    @enderror
                </div>

                <div class="field-group">
                    <label class="field-label" for="about-description">About description</label>
                    <textarea class="field-textarea" id="about-description" name="description" placeholder="Write the public About description here">{{ old('description', $aboutSection?->description) }}</textarea>
                    @error('description')
                        <span class="field-error">{{ $message }}</span>
                    @enderror
                </div>

                <div class="media-grid">
                    <div class="field-group">
                        <label class="field-label" for="left-video-url">Left YouTube video link</label>
                        <input class="field-input" id="left-video-url" type="url" name="left_video_url" value="{{ old('left_video_url', $aboutSection?->left_video_url) }}" placeholder="https://www.youtube.com/watch?v=...">
                        @error('left_video_url')
                            <span class="field-error">{{ $message }}</span>
                        @enderror
                    </div>

                    <div class="field-group">
                        <label class="field-label" for="right-video-url">Right YouTube video link</label>
                        <input class="field-input" id="right-video-url" type="url" name="right_video_url" value="{{ old('right_video_url', $aboutSection?->right_video_url) }}" placeholder="https://www.youtube.com/watch?v=...">
                        @error('right_video_url')
                            <span class="field-error">{{ $message }}</span>
                        @enderror
                    </div>
                </div>

                <div class="media-grid">
                    <div class="field-group">
                        <label class="field-label" for="left-thumbnail">Left thumbnail image</label>
                        <input class="field-file" id="left-thumbnail" type="file" name="left_thumbnail" accept="image/*" data-webp-input>
                        <span class="field-hint">Any uploaded image will be converted to WebP automatically. Max file size: 6 MB.</span>
                        <span class="upload-status" data-upload-status>
                            <span class="upload-spinner" aria-hidden="true"></span>
                            <span data-upload-status-text>Select an image to convert to WebP.</span>
                        </span>
                        @if ($aboutSection?->leftThumbnailUrl())
                            <div class="thumbnail-preview about-thumbnail-preview">
                                <img src="{{ $aboutSection->leftThumbnailUrl() }}" alt="Left about video thumbnail" loading="lazy" decoding="async">
                            </div>
                        @endif
                        @error('left_thumbnail')
                            <span class="field-error">{{ $message }}</span>
                        @enderror
                    </div>

                    <div class="field-group">
                        <label class="field-label" for="right-thumbnail">Right thumbnail image</label>
                        <input class="field-file" id="right-thumbnail" type="file" name="right_thumbnail" accept="image/*" data-webp-input>
                        <span class="field-hint">Any uploaded image will be converted to WebP automatically. Max file size: 6 MB.</span>
                        <span class="upload-status" data-upload-status>
                            <span class="upload-spinner" aria-hidden="true"></span>
                            <span data-upload-status-text>Select an image to convert to WebP.</span>
                        </span>
                        @if ($aboutSection?->rightThumbnailUrl())
                            <div class="thumbnail-preview about-thumbnail-preview">
                                <img src="{{ $aboutSection->rightThumbnailUrl() }}" alt="Right about video thumbnail" loading="lazy" decoding="async">
                            </div>
                        @endif
                        @error('right_thumbnail')
                            <span class="field-error">{{ $message }}</span>
                        @enderror
                    </div>
                </div>

                <div class="editor-actions">
                    <button class="submit-button" type="submit" data-about-submit>
                        <span class="submit-button-label">Save About</span>
                        <span class="submit-button-loading">
                            <span class="upload-spinner" aria-hidden="true"></span>
                            <span>Converting to WebP...</span>
                        </span>
                    </button>
                </div>
            </form>

            <div class="editor-subsection">
                <div class="editor-header">
                    <div class="editor-copy">
                        <p class="section-kicker">Why Editor</p>
                        <h2>Why King Lotus Group Section</h2>
                        <p class="admin-subtitle">Set the section title, description, bullet points, CTA button, YouTube video link, and thumbnail. Uploaded thumbnails are saved as WebP automatically.</p>
                    </div>

                    <span class="editor-status {{ $whySection?->hasRenderableContent() ? '' : 'is-hidden' }}">
                        {{ $whySection?->hasRenderableContent() ? 'Ready for website' : 'No public content yet' }}
                    </span>
                </div>

                <form class="editor-form why-form" action="{{ route('admin.content.why.update') }}" method="post" enctype="multipart/form-data" data-webp-form>
                    @csrf
                    @method('patch')

                    <div class="field-group">
                        <label class="field-label" for="why-title">Section title</label>
                        <input class="field-input" id="why-title" type="text" name="why_title" value="{{ old('why_title', $whySection?->title) }}" placeholder="Why King Lotus Group">
                        @error('why_title')
                            <span class="field-error">{{ $message }}</span>
                        @enderror
                    </div>

                    <div class="field-group">
                        <label class="field-label" for="why-description">Description</label>
                        <textarea class="field-textarea" id="why-description" name="why_description" placeholder="Write the Why section description here">{{ old('why_description', $whySection?->description) }}</textarea>
                        @error('why_description')
                            <span class="field-error">{{ $message }}</span>
                        @enderror
                    </div>

                    <div class="field-group">
                        <label class="field-label" for="feature-points">Feature bullet points</label>
                        <textarea class="field-textarea" id="feature-points" name="feature_points" placeholder="One point per line">{{ old('feature_points', $whySection?->feature_points) }}</textarea>
                        <span class="field-hint">Write one feature per line. Each line becomes a bullet point on the public website.</span>
                        @error('feature_points')
                            <span class="field-error">{{ $message }}</span>
                        @enderror
                    </div>

                    <div class="why-intro-grid">
                        <div class="field-group why-surface-card">
                            <label class="field-label" for="cta-label">Button label</label>
                            <input class="field-input" id="cta-label" type="text" name="cta_label" value="{{ old('cta_label', $whySection?->cta_label) }}" placeholder="See Details">
                            @error('cta_label')
                                <span class="field-error">{{ $message }}</span>
                            @enderror
                        </div>

                        <div class="field-group why-surface-card">
                            <label class="field-label" for="cta-url">Button URL</label>
                            <input class="field-input" id="cta-url" type="url" name="cta_url" value="{{ old('cta_url', $whySection?->cta_url) }}" placeholder="https://example.com/details">
                            <span class="field-hint">Leave this empty if the button should open the Terms and Conditions page.</span>
                            @error('cta_url')
                                <span class="field-error">{{ $message }}</span>
                            @enderror
                        </div>
                    </div>

                    <div class="why-media-grid">
                        <div class="field-group why-surface-card why-video-panel">
                            <label class="field-label" for="why-video-url">YouTube video link</label>
                            <input class="field-input" id="why-video-url" type="url" name="video_url" value="{{ old('video_url', $whySection?->video_url) }}" placeholder="https://www.youtube.com/watch?v=...">
                            <span class="field-hint">Paste the public YouTube video URL used for the right-side preview card.</span>
                            @error('video_url')
                                <span class="field-error">{{ $message }}</span>
                            @enderror
                        </div>

                        <div class="field-group why-surface-card why-thumb-panel">
                            <label class="field-label" for="why-thumbnail">Thumbnail image</label>
                            <input class="field-file" id="why-thumbnail" type="file" name="thumbnail" accept="image/*" data-webp-input>
                            <span class="field-hint">Any uploaded image will be converted to WebP automatically. Max file size: 6 MB.</span>
                            <span class="upload-status" data-upload-status>
                                <span class="upload-spinner" aria-hidden="true"></span>
                                <span data-upload-status-text>Select an image to convert to WebP.</span>
                            </span>
                            @if ($whySection?->thumbnailUrl())
                                <div class="why-thumb-preview-wrap">
                                    <span class="field-label">Current preview</span>
                                    <div class="thumbnail-preview why-thumb-preview">
                                        <img src="{{ $whySection->thumbnailUrl() }}" alt="Why section thumbnail" loading="lazy" decoding="async">
                                    </div>
                                    <span class="why-thumb-preview-caption">Compact preview only. Full image will still be used on the website.</span>
                                </div>
                            @endif
                            @error('thumbnail')
                                <span class="field-error">{{ $message }}</span>
                            @enderror
                        </div>
                    </div>

                    <div class="why-actions-bar">
                        <div class="editor-actions why-editor-actions">
                        <button class="submit-button" type="submit" data-webp-submit>
                            <span class="submit-button-label">Save Why Section</span>
                            <span class="submit-button-loading">
                                <span class="upload-spinner" aria-hidden="true"></span>
                                <span>Converting to WebP...</span>
                            </span>
                        </button>
                        </div>
                    </div>
                </form>
            </div>
        </article>

        <article class="admin-card editor-panel" id="projects-editor-panel" data-module-panel @if($activeModule !== 'projects') hidden @endif>
            <div class="editor-header">
                <div class="editor-copy">
                    <p class="section-kicker">Projects Editor</p>
                    <h2>Project Section</h2>
                    <p class="admin-subtitle">Create top cards and bottom cards separately. Admin can add or remove cards as needed, and the public layout will render from those two groups.</p>
                </div>

                <span class="editor-status {{ $projectSection?->hasRenderableContent() ? '' : 'is-hidden' }}">
                    {{ $projectSection?->hasRenderableContent() ? 'Ready for website' : 'No public content yet' }}
                </span>
            </div>

            <form class="editor-form projects-form" action="{{ route('admin.content.projects.update') }}" method="post" enctype="multipart/form-data" data-webp-form>
                @csrf
                @method('patch')

                <div class="projects-header-grid">
                    <div class="field-group project-editor-card">
                        <h3 class="project-editor-card-title">Top Section</h3>
                        <p class="project-editor-card-meta">Large heading and action button shown above the upper card group.</p>

                        <label class="field-label" for="projects-top-title">Top heading</label>
                        <input class="field-input" id="projects-top-title" type="text" name="projects_top_title" value="{{ old('projects_top_title', $projectSection?->top_title) }}">
                        @error('projects_top_title')
                            <span class="field-error">{{ $message }}</span>
                        @enderror
                    </div>

                    <div class="field-group project-editor-card">
                        <h3 class="project-editor-card-title">Bottom Section</h3>
                        <p class="project-editor-card-meta">Large heading shown above the lower staggered card group.</p>

                        <label class="field-label" for="projects-bottom-title">Bottom heading</label>
                        <input class="field-input" id="projects-bottom-title" type="text" name="projects_bottom_title" value="{{ old('projects_bottom_title', $projectSection?->bottom_title) }}">
                        @error('projects_bottom_title')
                            <span class="field-error">{{ $message }}</span>
                        @enderror
                    </div>
                </div>

                <div class="projects-group">
                    <div class="projects-group-head">
                        <div>
                            <h3 class="projects-group-title">Top Cards</h3>
                            <p class="projects-group-meta">This group renders the upper row. First card becomes the large left card, and the rest become the animated accordion cards.</p>
                        </div>

                        <button class="project-editor-add" type="button" data-project-add="top">Add Top Card</button>
                    </div>

                    <div class="projects-card-list" data-project-card-list="top" data-next-index="{{ count($topProjectCards) }}">
                        @foreach ($topProjectCards as $index => $card)
                            @include('admin.content.partials.project-card-fields', [
                                'label' => 'Top Card '.($index + 1),
                                'meta' => 'Used in the upper card group on the homepage.',
                                'prefix' => 'top_project_cards',
                                'imagePrefix' => 'top_project_card_images',
                                'index' => $index,
                                'card' => $card,
                            ])
                        @endforeach
                    </div>
                </div>

                <div class="projects-group">
                    <div class="projects-group-head">
                        <div>
                            <h3 class="projects-group-title">Bottom Cards</h3>
                            <p class="projects-group-meta">This group renders the lower staggered stack of cards. Add as many cards as needed.</p>
                        </div>

                        <button class="project-editor-add" type="button" data-project-add="bottom">Add Bottom Card</button>
                    </div>

                    <div class="projects-card-list" data-project-card-list="bottom" data-next-index="{{ count($bottomProjectCards) }}">
                        @foreach ($bottomProjectCards as $index => $card)
                            @include('admin.content.partials.project-card-fields', [
                                'label' => 'Bottom Card '.($index + 1),
                                'meta' => 'Used in the lower card group on the homepage.',
                                'prefix' => 'bottom_project_cards',
                                'imagePrefix' => 'bottom_project_card_images',
                                'index' => $index,
                                'card' => $card,
                            ])
                        @endforeach
                    </div>
                </div>

                <div class="editor-actions">
                    <button class="submit-button" type="submit" data-webp-submit>
                        <span class="submit-button-label">Save Our Projects</span>
                        <span class="submit-button-loading">
                            <span class="upload-spinner" aria-hidden="true"></span>
                            <span>Converting to WebP...</span>
                        </span>
                    </button>
                </div>
            </form>

            <template id="top-project-card-template">
                @include('admin.content.partials.project-card-fields', [
                    'label' => 'Top Card __NUMBER__',
                    'meta' => 'Used in the upper card group on the homepage.',
                    'prefix' => 'top_project_cards',
                    'imagePrefix' => 'top_project_card_images',
                    'index' => '__INDEX__',
                    'card' => ['title' => '', 'location' => '', 'rating' => '4.7/5', 'link_url' => '', 'order' => '__NUMBER__', 'image_path' => '', 'image_url' => null],
                    'showErrors' => false,
                ])
            </template>

            <template id="bottom-project-card-template">
                @include('admin.content.partials.project-card-fields', [
                    'label' => 'Bottom Card __NUMBER__',
                    'meta' => 'Used in the lower card group on the homepage.',
                    'prefix' => 'bottom_project_cards',
                    'imagePrefix' => 'bottom_project_card_images',
                    'index' => '__INDEX__',
                    'card' => ['title' => '', 'location' => '', 'rating' => '4.7/5', 'link_url' => '', 'order' => '__NUMBER__', 'image_path' => '', 'image_url' => null],
                    'showErrors' => false,
                ])
            </template>

            <template id="office-card-template">
                @include('admin.content.partials.office-card-fields', [
                    'label' => 'Office __NUMBER__',
                    'index' => '__INDEX__',
                    'office' => [
                        'name' => '',
                        'address' => '',
                        'map_url' => \App\Models\FooterSetting::DEFAULT_LOCATION_PLACE_URL,
                        'phone' => '',
                        'email' => '',
                    ],
                    'showErrors' => false,
                ])
            </template>
        </article>

        <article class="admin-card editor-panel" id="gallery-editor-panel" data-module-panel @if($activeModule !== 'gallery') hidden @endif>
            <div class="editor-header">
                <div class="editor-copy">
                    <p class="section-kicker">Gallery Editor</p>
                    <h2>Homepage Gallery and Albums</h2>
                    <p class="admin-subtitle">Manage the featured 7-image gallery below the projects section and the album content shown on the dedicated gallery page.</p>
                </div>

                <span class="editor-status {{ $gallerySection?->hasRenderableContent() ? '' : 'is-hidden' }}">
                    {{ $gallerySection?->hasRenderableContent() ? 'Gallery section ready' : 'No gallery content yet' }}
                </span>
            </div>

            <form class="editor-form" action="{{ route('admin.content.gallery.update') }}" method="post" enctype="multipart/form-data" data-webp-form>
                @csrf
                @method('patch')

                <div class="media-grid">
                    <div class="field-group">
                        <label class="field-label" for="gallery-section-title">Section title</label>
                        <input class="field-input" id="gallery-section-title" type="text" name="gallery_section_title" value="{{ old('gallery_section_title', $gallerySection?->section_title ?: \App\Models\GallerySection::DEFAULT_SECTION_TITLE) }}" placeholder="Recent Gallery">
                        @error('gallery_section_title')
                            <span class="field-error">{{ $message }}</span>
                        @enderror
                    </div>

                    <div class="field-group">
                        <label class="field-label" for="gallery-section-subtitle">Section subtitle</label>
                        <input class="field-input" id="gallery-section-subtitle" type="text" name="gallery_section_subtitle" value="{{ old('gallery_section_subtitle', $gallerySection?->section_subtitle ?: \App\Models\GallerySection::DEFAULT_SECTION_SUBTITLE) }}" placeholder="Featured Moments">
                        @error('gallery_section_subtitle')
                            <span class="field-error">{{ $message }}</span>
                        @enderror
                    </div>
                </div>

                <div class="media-grid">
                    <div class="field-group">
                        <label class="field-label" for="gallery-view-all-label">View all button label</label>
                        <input class="field-input" id="gallery-view-all-label" type="text" name="gallery_view_all_label" value="{{ old('gallery_view_all_label', $gallerySection?->view_all_label ?: \App\Models\GallerySection::DEFAULT_VIEW_ALL_LABEL) }}" placeholder="View All">
                        @error('gallery_view_all_label')
                            <span class="field-error">{{ $message }}</span>
                        @enderror
                    </div>

                    <div class="field-group">
                        <label class="field-label" for="gallery-page-title">Gallery page title</label>
                        <input class="field-input" id="gallery-page-title" type="text" name="gallery_page_title" value="{{ old('gallery_page_title', $gallerySection?->page_title ?: \App\Models\GallerySection::DEFAULT_PAGE_TITLE) }}" placeholder="Gallery Albums">
                        @error('gallery_page_title')
                            <span class="field-error">{{ $message }}</span>
                        @enderror
                    </div>
                </div>

                <div class="field-group">
                    <label class="field-label" for="gallery-page-subtitle">Gallery page subtitle</label>
                    <input class="field-input" id="gallery-page-subtitle" type="text" name="gallery_page_subtitle" value="{{ old('gallery_page_subtitle', $gallerySection?->page_subtitle ?: \App\Models\GallerySection::DEFAULT_PAGE_SUBTITLE) }}" placeholder="Short introduction shown on the gallery page">
                    @error('gallery_page_subtitle')
                        <span class="field-error">{{ $message }}</span>
                    @enderror
                </div>

                <div class="projects-group">
                    <div class="projects-group-head">
                        <div>
                            <h3 class="projects-group-title">Featured Gallery Images</h3>
                            <p class="projects-group-meta">These 7 fixed slots control the homepage gallery mosaic. No extra featured cards can be added here.</p>
                        </div>
                    </div>

                    <div class="gallery-featured-grid">
                        @foreach ($featuredGalleryImages as $index => $slot)
                            @include('admin.content.partials.gallery-featured-slot-fields', [
                                'index' => $index,
                                'slot' => $slot,
                                'label' => 'Featured Slot '.($index + 1),
                            ])
                        @endforeach
                    </div>
                </div>

                <div class="editor-subsection">
                    <div class="editor-header">
                        <div class="editor-copy">
                            <p class="section-kicker">Album Editor</p>
                            <h2>Gallery Page Albums</h2>
                            <p class="admin-subtitle">Create albums and upload the related images that will appear on the public gallery page.</p>
                        </div>
                    </div>

                    <div class="gallery-master-detail">
                        <aside class="gallery-album-sidebar">
                            <div class="gallery-album-sidebar-head">
                                <div>
                                    <h3 class="gallery-album-sidebar-title">Albums</h3>
                                    <span class="gallery-album-sidebar-count" data-gallery-total-albums-badge>{{ count($galleryAlbums) }} total</span>
                                </div>
                                <button class="gallery-btn-add-album" type="button" data-gallery-add-album>
                                    <svg width="13" height="13" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round">
                                        <line x1="12" y1="5" x2="12" y2="19"></line>
                                        <line x1="5" y1="12" x2="19" y2="12"></line>
                                    </svg>
                                    <span>New Album</span>
                                </button>
                            </div>

                            <nav class="gallery-album-nav" data-gallery-album-nav>
                                @forelse ($galleryAlbums as $index => $album)
                                    @php($albumTitle = trim((string) ($album['title'] ?? '')))
                                    @php($imageCount = count($album['images'] ?? []))
                                    <button class="gallery-album-nav-item @if($loop->first) is-active @endif" type="button" data-gallery-album-tab="{{ $index }}">
                                        <div class="gallery-album-nav-item-icon">
                                            <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                                                <path d="M22 19a2 2 0 0 1-2 2H4a2 2 0 0 1-2-2V5a2 2 0 0 1 2-2h5l2 3h9a2 2 0 0 1 2 2z"></path>
                                            </svg>
                                        </div>
                                        <div class="gallery-album-nav-item-copy">
                                            <span class="gallery-album-nav-title" data-gallery-nav-title>{{ $albumTitle !== '' ? $albumTitle : 'Album '.($index + 1) }}</span>
                                            <span class="gallery-album-nav-count" data-gallery-nav-count>{{ $imageCount }} {{ \Illuminate\Support\Str::plural('photo', $imageCount) }}</span>
                                        </div>
                                    </button>
                                @empty
                                    <div class="gallery-album-nav-empty" data-gallery-nav-empty>
                                        <span>No albums yet. Click "+ New Album" to create one.</span>
                                    </div>
                                @endforelse
                            </nav>
                        </aside>

                        <div class="gallery-album-workspace">
                            <div class="gallery-album-workspace-empty" data-gallery-workspace-empty @if(count($galleryAlbums) > 0) style="display: none;" @endif>
                                <div class="gallery-album-workspace-empty-icon" aria-hidden="true">📁</div>
                                <div class="gallery-album-workspace-empty-title">No albums yet</div>
                                <p class="gallery-album-workspace-empty-desc">Create an album using the sidebar to start uploading and organizing photos.</p>
                                <button class="gallery-btn-add-album" type="button" data-gallery-add-album>+ Create First Album</button>
                            </div>

                            <div class="gallery-album-list" data-gallery-album-list data-next-index="{{ count($galleryAlbums) }}">
                                @foreach ($galleryAlbums as $index => $album)
                                    @include('admin.content.partials.gallery-album-fields', [
                                        'label' => 'Album '.($index + 1),
                                        'index' => $index,
                                        'album' => $album,
                                    ])
                                @endforeach
                            </div>
                        </div>
                    </div>
                </div>

                <div class="editor-actions">
                    <button class="submit-button" type="submit" data-webp-submit>
                        <span class="submit-button-label">Save Gallery Content</span>
                        <span class="submit-button-loading">
                            <span class="upload-spinner" aria-hidden="true"></span>
                            <span>Converting to WebP...</span>
                        </span>
                    </button>
                </div>
            </form>

            <template id="gallery-album-template">
                @include('admin.content.partials.gallery-album-fields', [
                    'label' => 'Album __ALBUM_NUMBER__',
                    'index' => '__ALBUM_INDEX__',
                    'album' => [
                        'title' => '',
                        'subtitle' => '',
                        'images' => [],
                    ],
                    'showErrors' => false,
                ])
            </template>

            <template id="gallery-album-image-template">
                @include('admin.content.partials.gallery-album-image-fields', [
                    'label' => 'Image __IMAGE_NUMBER__',
                    'albumIndex' => '__ALBUM_INDEX__',
                    'imageIndex' => '__IMAGE_INDEX__',
                    'image' => [
                        'image_path' => '',
                        'image_url' => null,
                    ],
                    'showErrors' => false,
                ])
            </template>
        </article>

        <article class="admin-card editor-panel" id="reviews-editor-panel" data-module-panel @if($activeModule !== 'reviews') hidden @endif>
            <div class="editor-header">
                <div class="editor-copy">
                    <p class="section-kicker">Reviews Editor</p>
                    <h2>Shareholder Review Videos</h2>
                    <p class="admin-subtitle">Manage the shareholder review section shown below the homepage gallery. Add video cards with optional custom thumbnails.</p>
                </div>

                <span class="editor-status {{ $shareholderReviewSection?->hasRenderableContent() ? '' : 'is-hidden' }}">
                    {{ $shareholderReviewSection?->hasRenderableContent() ? 'Reviews section ready' : 'No review content yet' }}
                </span>
            </div>

            <form class="editor-form" action="{{ route('admin.content.reviews.update') }}" method="post" enctype="multipart/form-data" data-webp-form data-review-form>
                @csrf
                @method('patch')

                <div class="media-grid">
                    <div class="field-group">
                        <label class="field-label" for="review-section-title">Section title</label>
                        <input class="field-input" id="review-section-title" type="text" name="review_section_title" value="{{ old('review_section_title', $shareholderReviewSection?->section_title ?: \App\Models\ShareholderReviewSection::DEFAULT_TITLE) }}" placeholder="Shareholder Reviews">
                        @error('review_section_title')
                            <span class="field-error">{{ $message }}</span>
                        @enderror
                    </div>

                    <div class="field-group">
                        <label class="field-label" for="review-section-subtitle">Section subtitle</label>
                        <input class="field-input" id="review-section-subtitle" type="text" name="review_section_subtitle" value="{{ old('review_section_subtitle', $shareholderReviewSection?->section_subtitle ?: \App\Models\ShareholderReviewSection::DEFAULT_SUBTITLE) }}" placeholder="Real stories from King Lotus Group shareholders.">
                        @error('review_section_subtitle')
                            <span class="field-error">{{ $message }}</span>
                        @enderror
                    </div>
                </div>

                <div class="projects-group">
                    <div class="projects-group-head" style="flex-wrap: wrap; gap: 12px;">
                        <div>
                            <div style="display: flex; align-items: center; gap: 8px;">
                                <h3 class="projects-group-title">Review Cards</h3>
                                <span class="gallery-album-sidebar-count" data-review-count-badge>{{ count($shareholderReviews) }} total</span>
                            </div>
                            <p class="projects-group-meta">Manage video reviews shown on the public website. Click any row to expand or edit.</p>
                        </div>

                        <div style="display: flex; align-items: center; gap: 8px; flex-wrap: wrap;">
                            <button class="gallery-btn-add-album" type="button" data-review-expand-all style="padding: 0 10px; font-size: 0.74rem; background: #ffffff; border: 1px solid rgba(175, 191, 207, 0.5); color: var(--ink-800);">Expand All</button>
                            <button class="gallery-btn-add-album" type="button" data-review-collapse-all style="padding: 0 10px; font-size: 0.74rem; background: #ffffff; border: 1px solid rgba(175, 191, 207, 0.5); color: var(--ink-800);">Collapse All</button>
                            <button class="project-editor-add" type="button" data-review-add>+ Add Review</button>
                        </div>
                    </div>

                    <div class="shareholder-review-draft-host" data-review-draft-host hidden></div>

                    <div class="shareholder-review-list" data-review-card-list data-next-index="{{ count($shareholderReviews) }}">
                        @foreach ($shareholderReviews as $index => $review)
                            @include('admin.content.partials.shareholder-review-card-fields', [
                                'label' => 'Review '.($index + 1),
                                'index' => $index,
                                'review' => $review,
                            ])
                        @endforeach
                    </div>

                    <div class="editor-actions" style="margin-top: 20px;">
                        <button class="submit-button" type="submit" data-webp-submit>
                            <span class="submit-button-label">Save Reviews</span>
                            <span class="submit-button-loading">
                                <span class="upload-spinner" aria-hidden="true"></span>
                                <span>Converting to WebP...</span>
                            </span>
                        </button>
                    </div>
                </div>

            </form>

            <template id="shareholder-review-card-template">
                @include('admin.content.partials.shareholder-review-card-fields', [
                    'label' => 'Review __NUMBER__',
                    'index' => '__INDEX__',
                    'review' => [
                        'name' => '',
                        'video_url' => '',
                        'thumbnail_path' => '',
                        'thumbnail_url' => null,
                    ],
                    'showErrors' => false,
                ])
            </template>

            <template id="shareholder-review-draft-template">
                @include('admin.content.partials.shareholder-review-card-fields', [
                    'label' => 'New Review',
                    'index' => '__INDEX__',
                    'review' => [
                        'name' => '',
                        'video_url' => '',
                        'thumbnail_path' => '',
                        'thumbnail_url' => null,
                    ],
                    'showErrors' => false,
                    'isDraft' => true,
                ])
            </template>
        </article>

        <article class="admin-card editor-panel" id="leadership-editor-panel" data-module-panel @if($activeModule !== 'leadership') hidden @endif>
            <div class="editor-header">
                <div class="editor-copy">
                    <p class="section-kicker">Leadership Editor</p>
                    <h2>Founder and Board Members Section</h2>
                    <p class="admin-subtitle">Manage the founder profile block shown above the footer and the animated Board Members slider. Title, founder details, board member cards, and website visibility all update from here.</p>
                </div>

                <span class="editor-status {{ $leadershipSection?->shouldDisplayOnWebsite() ? '' : 'is-hidden' }}">
                    {{ $leadershipSection?->shouldDisplayOnWebsite() ? 'Visible on website' : 'Hidden on website' }}
                </span>
            </div>

            <form class="editor-form" action="{{ route('admin.content.leadership.update') }}" method="post" enctype="multipart/form-data" data-webp-form>
                @csrf
                @method('patch')

                <label class="toggle-bar">
                    <span class="toggle-switch">
                        <input type="checkbox" name="is_visible" value="1" @checked(old('is_visible', $leadershipSection?->exists ? $leadershipSection->is_visible : true))>
                        <span class="toggle-track" aria-hidden="true">
                            <span class="toggle-thumb"></span>
                        </span>
                    </span>

                    <span class="toggle-copy">
                        <span class="toggle-title">Show leadership section on website</span>
                        <span class="toggle-meta">Turn this off if you want to save the founder and board member content without showing it publicly.</span>
                    </span>
                </label>

                <div class="field-group">
                    <label class="field-label" for="leadership-section-title">Section title</label>
                    <input class="field-input" id="leadership-section-title" type="text" name="section_title" value="{{ old('section_title', $leadershipSection?->section_title ?: \App\Models\LeadershipSection::DEFAULT_SECTION_TITLE) }}" placeholder="Board Members">
                    @error('section_title')
                        <span class="field-error">{{ $message }}</span>
                    @enderror
                </div>

                <div class="projects-group">
                    <div class="projects-group-head">
                        <div>
                            <h3 class="projects-group-title">Founder Feature</h3>
                            <p class="projects-group-meta">This feature block appears at the top of the section with the Founder & CEO label, name, position, and image.</p>
                        </div>
                    </div>

                    <input type="hidden" name="founder_image_path" value="{{ old('founder_image_path', $leadershipSection?->founder_image_path) }}">

                    <div class="leadership-founder-card">
                        <div class="leadership-founder-photo-col">
                            <div class="leadership-founder-photo-frame" data-leadership-founder-photo-frame>
                                @if ($leadershipSection?->founderImageUrl())
                                    <img src="{{ $leadershipSection->founderImageUrl() }}" alt="Founder photo preview" loading="lazy" decoding="async">
                                @else
                                    <div class="leadership-photo-placeholder" aria-hidden="true">
                                        <svg width="40" height="40" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.6" stroke-linecap="round" stroke-linejoin="round">
                                            <path d="M20 21v-2a4 4 0 0 0-4-4H8a4 4 0 0 0-4 4v2"></path>
                                            <circle cx="12" cy="7" r="4"></circle>
                                        </svg>
                                    </div>
                                @endif
                            </div>

                            <div class="leadership-photo-upload-actions">
                                <label class="leadership-photo-picker-btn" for="founder-image">
                                    <svg width="15" height="15" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true">
                                        <path d="M23 19a2 2 0 0 1-2 2H3a2 2 0 0 1-2-2V8a2 2 0 0 1 2-2h4l2-3h6l2 3h4a2 2 0 0 1 2 2z"></path>
                                        <circle cx="12" cy="13" r="4"></circle>
                                    </svg>
                                    <span data-leadership-founder-btn-label>{{ $leadershipSection?->founderImageUrl() ? 'Change Photo' : 'Upload Photo' }}</span>
                                </label>
                                <input class="leadership-photo-file-input" id="founder-image" type="file" name="founder_image" accept="image/*" data-webp-input data-leadership-founder-file>

                                <span class="field-hint">WebP auto-converted &bull; Max 6 MB</span>
                                <span class="upload-status" data-upload-status>
                                    <span class="upload-spinner" aria-hidden="true"></span>
                                    <span data-upload-status-text>Select an image to convert to WebP.</span>
                                </span>
                            </div>

                            @error('founder_image')
                                <span class="field-error">{{ $message }}</span>
                            @enderror
                        </div>

                        <div class="leadership-founder-fields-col">
                            <div class="leadership-founder-row">
                                <div class="field-group">
                                    <label class="field-label" for="founder-name">Founder name</label>
                                    <input class="field-input" id="founder-name" type="text" name="founder_name" value="{{ old('founder_name', $leadershipSection?->founder_name) }}" placeholder="Founder name">
                                    @error('founder_name')
                                        <span class="field-error">{{ $message }}</span>
                                    @enderror
                                </div>

                                <div class="field-group">
                                    <label class="field-label" for="founder-position">Founder position</label>
                                    <input class="field-input" id="founder-position" type="text" name="founder_position" value="{{ old('founder_position', $leadershipSection?->founder_position) }}" placeholder="Founder & CEO">
                                    @error('founder_position')
                                        <span class="field-error">{{ $message }}</span>
                                    @enderror
                                </div>
                            </div>

                            <div class="field-group">
                                <label class="field-label" for="founder-description">Founder description</label>
                                <textarea class="field-textarea field-textarea--compact" id="founder-description" name="founder_description" maxlength="200" placeholder="Short founder description shown below the position">{{ old('founder_description', $leadershipSection?->founder_description) }}</textarea>
                                <span class="field-hint">Maximum 200 characters (<span data-founder-char-count>{{ strlen(old('founder_description', $leadershipSection?->founder_description ?? '')) }}</span>/200). This text appears below the founder position on the public website.</span>
                                @error('founder_description')
                                    <span class="field-error">{{ $message }}</span>
                                @enderror
                            </div>
                        </div>
                    </div>
                </div>

                <div class="projects-group">
                    <div class="projects-group-head" style="flex-wrap: wrap; gap: 12px;">
                        <div>
                            <div style="display: flex; align-items: center; gap: 8px;">
                                <h3 class="projects-group-title">Board Member Cards</h3>
                                <span class="gallery-album-sidebar-count" data-leadership-member-count-badge>{{ count($boardMembers) }} total</span>
                            </div>
                            <p class="projects-group-meta">These cards appear in the animated slider under the founder feature block on the public website. Click any row to expand or edit.</p>
                        </div>

                        <div class="leadership-group-actions">
                            <button class="leadership-btn-text" type="button" data-leadership-expand-all>Expand All</button>
                            <button class="leadership-btn-text" type="button" data-leadership-collapse-all>Collapse All</button>
                            <button class="project-editor-add" type="button" data-leadership-member-add>+ Add Board Member</button>
                        </div>
                    </div>

                    <div class="leadership-member-accordion-list" data-leadership-member-list data-next-index="{{ count($boardMembers) }}">
                        @foreach ($boardMembers as $index => $member)
                            @include('admin.content.partials.leadership-member-fields', [
                                'label' => 'Board Member '.($index + 1),
                                'index' => $index,
                                'member' => $member,
                            ])
                        @endforeach
                    </div>
                </div>

                <div class="editor-actions">
                    <button class="submit-button" type="submit" data-webp-submit>
                        <span class="submit-button-label">Save Leadership Section</span>
                        <span class="submit-button-loading">
                            <span class="upload-spinner" aria-hidden="true"></span>
                            <span>Converting to WebP...</span>
                        </span>
                    </button>
                </div>
            </form>

            <template id="leadership-member-card-template">
                @include('admin.content.partials.leadership-member-fields', [
                    'label' => 'Board Member __NUMBER__',
                    'index' => '__INDEX__',
                    'member' => [
                        'name' => '',
                        'position' => '',
                        'image_path' => '',
                        'image_url' => null,
                    ],
                    'showErrors' => false,
                    'isExpanded' => true,
                ])
            </template>
        </article>

        <article class="admin-card editor-panel" id="valued-shareholders-editor-panel" data-module-panel @if($activeModule !== 'valued-shareholders') hidden @endif>
            <div class="editor-header">
                <div class="editor-copy">
                    <p class="section-kicker">Valued Shareholders Editor</p>
                    <h2>Our Valued Shareholders Section</h2>
                    <p class="admin-subtitle">Manage the shareholder directory with instant search and infinite scroll. Section title and website visibility save below; each shareholder card saves independently.</p>
                </div>

                <span class="editor-status {{ $valuedShareholderSection?->shouldDisplayOnWebsite() ? '' : 'is-hidden' }}">
                    {{ $valuedShareholderSection?->shouldDisplayOnWebsite() ? 'Visible on website' : 'Hidden on website' }}
                </span>
            </div>

            <form class="editor-form" action="{{ route('admin.content.valued-shareholders.update') }}" method="post">
                @csrf
                @method('patch')

                <label class="toggle-bar">
                    <span class="toggle-switch">
                        <input type="checkbox" name="shareholder_section_visible" value="1" @checked(old('shareholder_section_visible', $valuedShareholderSection?->exists ? $valuedShareholderSection->is_visible : true))>
                        <span class="toggle-track" aria-hidden="true">
                            <span class="toggle-thumb"></span>
                        </span>
                    </span>

                    <span class="toggle-copy">
                        <span class="toggle-title">Show valued shareholders section on website</span>
                        <span class="toggle-meta">Turn this off if you want to save the cards without showing them publicly.</span>
                    </span>
                </label>

                <div class="field-group">
                    <label class="field-label" for="shareholder-section-title">Section title</label>
                    <input class="field-input" id="shareholder-section-title" type="text" name="shareholder_section_title" value="{{ old('shareholder_section_title', $valuedShareholderSection?->section_title ?: \App\Models\ValuedShareholderSection::DEFAULT_SECTION_TITLE) }}" placeholder="Our Valued Shareholders">
                    @error('shareholder_section_title')
                        <span class="field-error">{{ $message }}</span>
                    @enderror
                </div>

                <div class="editor-actions" style="margin-bottom: 24px;">
                    <button class="submit-button" type="submit">
                        <span class="submit-button-label">Save Section Title &amp; Visibility</span>
                    </button>
                </div>
            </form>

            <div class="projects-group" data-shareholder-directory-root
                 data-items-url="{{ route('admin.content.valued-shareholders.items') }}"
                 data-store-url="{{ route('admin.content.valued-shareholders.store') }}"
                 data-base-url="{{ url('/admin/content-management/valued-shareholders/items') }}">
                <div class="projects-group-head" style="flex-wrap: wrap; gap: 12px;">
                    <div>
                        <div style="display: flex; align-items: center; gap: 8px;">
                            <h3 class="projects-group-title">Shareholder Directory</h3>
                            <span class="gallery-album-sidebar-count" data-shareholder-total-badge>{{ $valuedShareholdersTotal }} total</span>
                        </div>
                        <p class="projects-group-meta">Search, edit, or add shareholders. Scroll down to automatically load more.</p>
                    </div>

                    <div class="leadership-group-actions">
                        <button class="project-editor-add" type="button" data-shareholder-add-btn>+ Add Shareholder</button>
                    </div>
                </div>

                {{-- Search Toolbar --}}
                <div class="shareholder-search-toolbar">
                    <div class="shareholder-search-box">
                        <span class="shareholder-search-icon" aria-hidden="true">
                            <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.2" stroke-linecap="round" stroke-linejoin="round">
                                <circle cx="11" cy="11" r="8"></circle>
                                <line x1="21" y1="21" x2="16.65" y2="16.65"></line>
                            </svg>
                        </span>
                        <input type="text" class="field-input shareholder-search-input" placeholder="Search shareholder by name or position..." data-shareholder-search-input autocomplete="off">
                        <button type="button" class="shareholder-search-clear-btn" data-shareholder-clear-btn style="display: none;" title="Clear search" aria-label="Clear search">
                            <svg width="12" height="12" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5" stroke-linecap="round">
                                <line x1="18" y1="6" x2="6" y2="18"></line>
                                <line x1="6" y1="6" x2="18" y2="18"></line>
                            </svg>
                        </button>
                    </div>
                    <span class="shareholder-search-feedback" data-shareholder-search-feedback></span>
                </div>

                {{-- New Shareholder Draft Host --}}
                <div class="shareholder-new-card-host" data-shareholder-new-host style="display: none;"></div>

                {{-- Accordion List Container --}}
                <div class="leadership-member-accordion-list" data-shareholder-list data-current-page="1" data-has-more="{{ $valuedShareholdersTotal > 20 ? 'true' : 'false' }}">
                    @forelse ($valuedShareholders as $index => $shareholder)
                        @include('admin.content.partials.valued-shareholder-card-fields', [
                            'label' => 'Shareholder #'.($index + 1),
                            'index' => $index,
                            'shareholder' => $shareholder,
                        ])
                    @empty
                        <div class="shareholder-empty-state" data-shareholder-empty-state>
                            <p>No shareholders found yet. Click <strong>+ Add Shareholder</strong> above to create one.</p>
                        </div>
                    @endforelse
                </div>

                {{-- Infinite Scroll Sentinel --}}
                <div data-shareholder-sentinel style="min-height: 20px;">
                    <div class="shareholder-infinite-loader" data-shareholder-loader style="{{ $valuedShareholdersTotal > 20 ? '' : 'display: none;' }}">
                        <span class="upload-spinner" aria-hidden="true"></span>
                        <span data-shareholder-loader-text>Loading more shareholders...</span>
                    </div>
                </div>
            </div>

            <template id="valued-shareholder-card-template">
                @include('admin.content.partials.valued-shareholder-card-fields', [
                    'label' => 'Shareholder #__NUMBER__',
                    'shareholder' => [
                        'id' => '__ID__',
                        'name' => '',
                        'position' => '',
                        'image_path' => '',
                        'image_url' => null,
                    ],
                    'isExpanded' => false,
                ])
            </template>

            <template id="valued-shareholder-new-template">
                @include('admin.content.partials.valued-shareholder-card-fields', [
                    'label' => 'New Shareholder',
                    'shareholder' => [
                        'id' => null,
                        'name' => '',
                        'position' => '',
                        'image_path' => '',
                        'image_url' => null,
                    ],
                    'isExpanded' => true,
                ])
            </template>
        </article>

        <article class="admin-card editor-panel footer-management-panel" id="footer-editor-panel" data-module-panel @if($activeModule !== 'footer') hidden @endif>
            <div class="editor-header footer-panel-header">
                <div class="editor-copy">
                    <p class="section-kicker">Footer & Location</p>
                    <h2>Footer, Location & Legal Management</h2>
                    <p class="admin-subtitle">Organized tab-by-tab. Seamlessly update social links, map location, branch offices, and legal terms without losing unsaved fields.</p>
                </div>

                <div class="footer-header-badges">
                    <span class="editor-status {{ ($footerSetting?->hasPublicLinks() || $footerSetting?->hasLocationContent() || $footerSetting?->hasOfficeContent() || $footerSetting?->hasTermsContent()) ? '' : 'is-hidden' }}">
                        {{ (($footerSetting?->hasPublicLinks() || $footerSetting?->hasLocationContent() || $footerSetting?->hasOfficeContent() || $footerSetting?->hasTermsContent()) ? 'Ready for website' : 'No footer content yet') }}
                    </span>
                </div>
            </div>

            <!-- Sub-tab Navigation Bar -->
            <div class="footer-subnav" role="tablist" aria-label="Footer and Location Sections">
                <button type="button" role="tab" class="footer-subnav-btn {{ $activeFooterSubtab === 'social' ? 'is-active' : '' }}" data-footer-tab-target="social" aria-selected="{{ $activeFooterSubtab === 'social' ? 'true' : 'false' }}">
                    <span class="footer-subnav-icon">
                        <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><circle cx="12" cy="12" r="10"></circle><line x1="2" y1="12" x2="22" y2="12"></line><path d="M12 2a15.3 15.3 0 0 1 4 10 15.3 15.3 0 0 1-4 10 15.3 15.3 0 0 1-4-10 15.3 15.3 0 0 1 4-10z"></path></svg>
                    </span>
                    <span class="footer-subnav-label">Social & Contact Links</span>
                    @if ($errors->has('youtube_url') || $errors->has('facebook_url') || $errors->has('contact_email') || $errors->has('contact_phone'))
                        <span class="footer-tab-err-dot" title="Validation errors in this section"></span>
                    @endif
                </button>

                <button type="button" role="tab" class="footer-subnav-btn {{ $activeFooterSubtab === 'location' ? 'is-active' : '' }}" data-footer-tab-target="location" aria-selected="{{ $activeFooterSubtab === 'location' ? 'true' : 'false' }}">
                    <span class="footer-subnav-icon">
                        <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M21 10c0 7-9 13-9 13s-9-6-9-13a9 9 0 0 1 18 0z"></path><circle cx="12" cy="10" r="3"></circle></svg>
                    </span>
                    <span class="footer-subnav-label">Location Map Section</span>
                    @if ($errors->has('location_title') || $errors->has('location_subtitle') || $errors->has('location_map_url'))
                        <span class="footer-tab-err-dot" title="Validation errors in this section"></span>
                    @endif
                </button>

                <button type="button" role="tab" class="footer-subnav-btn {{ $activeFooterSubtab === 'offices' ? 'is-active' : '' }}" data-footer-tab-target="offices" aria-selected="{{ $activeFooterSubtab === 'offices' ? 'true' : 'false' }}">
                    <span class="footer-subnav-icon">
                        <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><rect x="4" y="2" width="16" height="20" rx="2" ry="2"></rect><path d="M9 22v-4h6v4"></path><path d="M8 6h.01"></path><path d="M16 6h.01"></path><path d="M8 10h.01"></path><path d="M16 10h.01"></path><path d="M8 14h.01"></path><path d="M16 14h.01"></path></svg>
                    </span>
                    <span class="footer-subnav-label">Office Branches</span>
                    <span class="footer-tab-badge" data-office-counter>{{ count($officeCards) }}</span>
                    @if ($errors->has('office_section_title') || $errors->has('office_section_subtitle') || collect($errors->keys())->contains(fn ($f) => str_starts_with($f, 'office_cards.')))
                        <span class="footer-tab-err-dot" title="Validation errors in this section"></span>
                    @endif
                </button>

                <button type="button" role="tab" class="footer-subnav-btn {{ $activeFooterSubtab === 'terms' ? 'is-active' : '' }}" data-footer-tab-target="terms" aria-selected="{{ $activeFooterSubtab === 'terms' ? 'true' : 'false' }}">
                    <span class="footer-subnav-icon">
                        <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M14 2H6a2 2 0 0 0-2 2v16a2 2 0 0 0 2 2h12a2 2 0 0 0 2-2V8z"></path><polyline points="14 2 14 8 20 8"></polyline><line x1="16" y1="13" x2="8" y2="13"></line><line x1="16" y1="17" x2="8" y2="17"></line><polyline points="10 9 9 9 8 9"></polyline></svg>
                    </span>
                    <span class="footer-subnav-label">Terms & Conditions</span>
                    @if ($errors->has('terms_title') || $errors->has('terms_subtitle') || $errors->has('terms_intro') || $errors->has('terms_content'))
                        <span class="footer-tab-err-dot" title="Validation errors in this section"></span>
                    @endif
                </button>
            </div>

            <form class="editor-form footer-unified-form" id="footer-management-form" action="{{ route('admin.content.footer.update') }}" method="post">
                @csrf
                @method('patch')

                <!-- Sub-tab 1: Social & Contact -->
                <div class="footer-tab-panel {{ $activeFooterSubtab === 'social' ? 'is-active' : '' }}" data-footer-tab-panel="social" @if($activeFooterSubtab !== 'social') hidden @endif>
                    <div class="subtab-header">
                        <div class="subtab-header-copy">
                            <span class="subtab-kicker">Connect Channels</span>
                            <h3 class="subtab-title">Social & Direct Contact Links</h3>
                            <p class="subtab-subtitle">These links power the YouTube, Facebook, Email, and Phone contact actions across the public footer.</p>
                        </div>
                    </div>

                    <div class="media-grid">
                        <div class="field-group">
                            <label class="field-label" for="footer-youtube-url">YouTube Channel Link</label>
                            <div class="input-icon-group">
                                <span class="input-icon input-icon--brand-youtube">
                                    <svg width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M22.54 6.42a2.78 2.78 0 0 0-1.94-2C18.88 4 12 4 12 4s-6.88 0-8.6.46a2.78 2.78 0 0 0-1.94 2A29 29 0 0 0 1 11.75a29 29 0 0 0 .46 5.33A2.78 2.78 0 0 0 3.4 19c1.72.46 8.6.46 8.6.46s6.88 0 8.6-.46a2.78 2.78 0 0 0 1.94-2 29 29 0 0 0 .46-5.25 29 29 0 0 0-.46-5.33z"></path><polygon points="9.75 15.02 15.5 11.75 9.75 8.48 9.75 15.02"></polygon></svg>
                                </span>
                                <input class="field-input field-input--with-icon" id="footer-youtube-url" type="url" name="youtube_url" value="{{ old('youtube_url', $footerSetting?->youtube_url) }}" placeholder="https://www.youtube.com/@yourchannel">
                            </div>
                            <span class="field-hint">Used for video link in footer branding.</span>
                            @error('youtube_url')
                                <span class="field-error">{{ $message }}</span>
                            @enderror
                        </div>

                        <div class="field-group">
                            <label class="field-label" for="footer-facebook-url">Facebook Page Link</label>
                            <div class="input-icon-group">
                                <span class="input-icon input-icon--brand-facebook">
                                    <svg width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M18 2h-3a5 5 0 0 0-5 5v3H7v4h3v8h4v-8h3l1-4h-4V7a1 1 0 0 1 1-1h3z"></path></svg>
                                </span>
                                <input class="field-input field-input--with-icon" id="footer-facebook-url" type="url" name="facebook_url" value="{{ old('facebook_url', $footerSetting?->facebook_url) }}" placeholder="https://www.facebook.com/yourpage">
                            </div>
                            <span class="field-hint">Used for official Facebook page icon.</span>
                            @error('facebook_url')
                                <span class="field-error">{{ $message }}</span>
                            @enderror
                        </div>
                    </div>

                    <div class="media-grid">
                        <div class="field-group">
                            <label class="field-label" for="footer-contact-email">Contact Email</label>
                            <div class="input-icon-group">
                                <span class="input-icon input-icon--brand-email">
                                    <svg width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M4 4h16c1.1 0 2 .9 2 2v12c0 1.1-.9 2-2 2H4c-1.1 0-2-.9-2-2V6c0-1.1.9-2 2-2z"></path><polyline points="22,6 12,13 2,6"></polyline></svg>
                                </span>
                                <input class="field-input field-input--with-icon" id="footer-contact-email" type="email" name="contact_email" value="{{ old('contact_email', $footerSetting?->contact_email) }}" placeholder="contact@example.com">
                            </div>
                            <span class="field-hint">Clicking this icon on the public website opens the visitor's default mail app.</span>
                            @error('contact_email')
                                <span class="field-error">{{ $message }}</span>
                            @enderror
                        </div>

                        <div class="field-group">
                            <label class="field-label" for="footer-contact-phone">Contact Phone Numbers</label>
                            <textarea class="field-textarea field-textarea--compact" id="footer-contact-phone" name="contact_phone" placeholder="+8801700000000&#10;+8801800000000&#10;+8801900000000">{{ old('contact_phone', $footerSetting?->contact_phone) }}</textarea>
                            <span class="field-hint">Add one phone number per line. Clicking the phone icon on the public website will let visitors choose which number to call.</span>
                            @error('contact_phone')
                                <span class="field-error">{{ $message }}</span>
                            @enderror
                        </div>
                    </div>
                </div>

                <!-- Sub-tab 2: Location Map Section -->
                <div class="footer-tab-panel {{ $activeFooterSubtab === 'location' ? 'is-active' : '' }}" data-footer-tab-panel="location" @if($activeFooterSubtab !== 'location') hidden @endif>
                    <div class="subtab-header">
                        <div class="subtab-header-copy">
                            <span class="subtab-kicker">Interactive Map</span>
                            <h3 class="subtab-title">Location Section Above Footer</h3>
                            <p class="subtab-subtitle">This section appears right above the public website footer, highlighting your headquarters and navigation map.</p>
                        </div>
                    </div>

                    <div class="field-group">
                        <label class="field-label" for="location-title">Section Title</label>
                        <input class="field-input" id="location-title" type="text" name="location_title" value="{{ old('location_title', $footerSetting?->location_title ?: 'Visit Our Location') }}" placeholder="Visit Our Location">
                        @error('location_title')
                            <span class="field-error">{{ $message }}</span>
                        @enderror
                    </div>

                    <div class="field-group">
                        <label class="field-label" for="location-subtitle">Subtitle Text</label>
                        <input class="field-input" id="location-subtitle" type="text" name="location_subtitle" value="{{ old('location_subtitle', $footerSetting?->location_subtitle ?: 'Open our Google Maps location to plan your arrival and explore the surrounding destination.') }}" placeholder="Short subtitle shown under the location title">
                        @error('location_subtitle')
                            <span class="field-error">{{ $message }}</span>
                        @enderror
                    </div>

                    <div class="field-group">
                        <label class="field-label" for="location-map-url">Google Maps Link</label>
                        <div class="input-icon-group">
                            <span class="input-icon input-icon--brand-map">
                                <svg width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M21 10c0 7-9 13-9 13s-9-6-9-13a9 9 0 0 1 18 0z"></path><circle cx="12" cy="10" r="3"></circle></svg>
                            </span>
                            <input class="field-input field-input--with-icon" id="location-map-url" type="url" name="location_map_url" value="{{ old('location_map_url', $footerSetting?->location_map_url ?: \App\Models\FooterSetting::DEFAULT_LOCATION_PLACE_URL) }}" placeholder="https://www.google.com/maps/place/...">
                        </div>
                        <span class="field-hint">Use a public Google Maps share link. The public website will show a button that opens this location in a new tab.</span>
                        @error('location_map_url')
                            <span class="field-error">{{ $message }}</span>
                        @enderror
                    </div>
                </div>

                <!-- Sub-tab 3: Office Branches -->
                <div class="footer-tab-panel {{ $activeFooterSubtab === 'offices' ? 'is-active' : '' }}" data-footer-tab-panel="offices" @if($activeFooterSubtab !== 'offices') hidden @endif>
                    <div class="subtab-header">
                        <div class="subtab-header-copy">
                            <span class="subtab-kicker">Branch Network</span>
                            <h3 class="subtab-title">Office Branches Below Location</h3>
                            <p class="subtab-subtitle">Manage branch offices, addresses, contact details, and custom Google Maps coordinates for each regional office.</p>
                        </div>

                        <button class="project-editor-add office-add-btn-top" type="button" data-office-add>
                            <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><line x1="12" y1="5" x2="12" y2="19"></line><line x1="5" y1="12" x2="19" y2="12"></line></svg>
                            <span>Add New Office</span>
                        </button>
                    </div>

                    <div class="media-grid">
                        <div class="field-group">
                            <label class="field-label" for="office-section-title">Section Title</label>
                            <input class="field-input" id="office-section-title" type="text" name="office_section_title" value="{{ old('office_section_title', $footerSetting?->office_section_title ?: \App\Models\FooterSetting::DEFAULT_OFFICE_SECTION_TITLE) }}" placeholder="Get A Quote - No Cost, No Commitment">
                            @error('office_section_title')
                                <span class="field-error">{{ $message }}</span>
                            @enderror
                        </div>

                        <div class="field-group">
                            <label class="field-label" for="office-section-subtitle">Section Subtitle</label>
                            <input class="field-input" id="office-section-subtitle" type="text" name="office_section_subtitle" value="{{ old('office_section_subtitle', $footerSetting?->office_section_subtitle ?: \App\Models\FooterSetting::DEFAULT_OFFICE_SECTION_SUBTITLE) }}" placeholder="Transparent & Competitive Rates">
                            @error('office_section_subtitle')
                                <span class="field-error">{{ $message }}</span>
                            @enderror
                        </div>
                    </div>

                    <div class="projects-group-head office-branches-head">
                        <div>
                            <h4 class="projects-group-title">Configured Branches</h4>
                            <p class="projects-group-meta">Each branch card below appears inside the office section on the public website.</p>
                        </div>
                    </div>

                    <div class="projects-card-list" data-office-card-list data-next-index="{{ count($officeCards) }}">
                        @foreach ($officeCards as $index => $office)
                            @include('admin.content.partials.office-card-fields', [
                                'label' => 'Office '.($index + 1),
                                'index' => $index,
                                'office' => $office,
                            ])
                        @endforeach
                    </div>
                </div>

                <!-- Sub-tab 4: Terms & Conditions -->
                <div class="footer-tab-panel {{ $activeFooterSubtab === 'terms' ? 'is-active' : '' }}" data-footer-tab-panel="terms" @if($activeFooterSubtab !== 'terms') hidden @endif>
                    <div class="subtab-header">
                        <div class="subtab-header-copy">
                            <span class="subtab-kicker">Legal & Policy</span>
                            <h3 class="subtab-title">Terms & Conditions Page Content</h3>
                            <p class="subtab-subtitle">This rich content appears on the dedicated public Terms and Conditions page (/terms).</p>
                        </div>
                    </div>

                    <div class="field-group">
                        <label class="field-label" for="terms-title">Page Title</label>
                        <input class="field-input" id="terms-title" type="text" name="terms_title" value="{{ old('terms_title', $footerSetting?->terms_title) }}" placeholder="Terms and Conditions">
                        @error('terms_title')
                            <span class="field-error">{{ $message }}</span>
                        @enderror
                    </div>

                    <div class="field-group">
                        <label class="field-label" for="terms-subtitle">Subtitle Text</label>
                        <input class="field-input" id="terms-subtitle" type="text" name="terms_subtitle" value="{{ old('terms_subtitle', $footerSetting?->terms_subtitle) }}" placeholder="Short subtitle shown below the page title">
                        @error('terms_subtitle')
                            <span class="field-error">{{ $message }}</span>
                        @enderror
                    </div>

                    <div class="field-group">
                        <label class="field-label" for="terms-content">Terms Content</label>
                        <textarea class="field-textarea" id="terms-content" name="terms_content" data-jodit-editor placeholder="Write the full terms and conditions content here. Use formatting, headings, and lists as needed.">{{ old('terms_content', trim(collect([$footerSetting?->terms_intro, $footerSetting?->terms_content])->filter()->implode("\n\n"))) }}</textarea>
                        <span class="field-hint">Formatted text, headings, and bullet points will appear cleanly on the public Terms & Conditions page.</span>
                        @error('terms_content')
                            <span class="field-error">{{ $message }}</span>
                        @enderror
                    </div>
                </div>

                <!-- Unified Sticky Action Bar -->
                <div class="footer-sticky-bar">
                    <div class="footer-sticky-status">
                        <span class="footer-sync-indicator" data-footer-sync-indicator>
                            <span class="footer-sync-dot"></span>
                            <span class="footer-sync-text">All changes synced</span>
                        </span>
                    </div>

                    <div class="footer-sticky-actions">
                        <button class="submit-button footer-save-btn" type="submit">
                            <span class="submit-button-label">
                                <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M19 21H5a2 2 0 0 1-2-2V5a2 2 0 0 1 2-2h11l5 5v11a2 2 0 0 1-2 2z"></path><polyline points="17 21 17 13 7 13 7 21"></polyline><polyline points="7 3 7 8 15 8"></polyline></svg>
                                <span>Save Footer Settings</span>
                            </span>
                            <span class="submit-button-loading">
                                <span class="upload-spinner"></span>
                                <span>Saving Changes...</span>
                            </span>
                        </button>
                    </div>
                </div>
            </form>
        </article>
    </section>
@endsection

@push('scripts')
    <script src="https://cdn.jsdelivr.net/npm/jodit@4.2.47/es2021/jodit.min.js"></script>
    <script>
        document.addEventListener('DOMContentLoaded', () => {
            const buttons = Array.from(document.querySelectorAll('[data-module-toggle]'));
            const panels = Array.from(document.querySelectorAll('[data-module-panel]'));
            const webpForms = Array.from(document.querySelectorAll('[data-about-form], [data-webp-form]'));
            const topProjectTemplate = document.getElementById('top-project-card-template');
            const bottomProjectTemplate = document.getElementById('bottom-project-card-template');
            const officeCardTemplate = document.getElementById('office-card-template');
            const galleryAlbumTemplate = document.getElementById('gallery-album-template');
            const galleryAlbumImageTemplate = document.getElementById('gallery-album-image-template');
            const shareholderReviewTemplate = document.getElementById('shareholder-review-card-template');
            const shareholderReviewDraftTemplate = document.getElementById('shareholder-review-draft-template');
            const leadershipMemberTemplate = document.getElementById('leadership-member-card-template');
            const valuedShareholderTemplate = document.getElementById('valued-shareholder-card-template');

            const setActivePanel = (targetId, allowClose = true) => {
                const targetPanel = document.getElementById(targetId);
                const targetButton = buttons.find((button) => button.dataset.moduleToggle === targetId);
                const isOpen = targetButton?.getAttribute('aria-expanded') === 'true';
                const shouldClose = allowClose && isOpen;

                buttons.forEach((button) => {
                    const isTarget = button.dataset.moduleToggle === targetId;
                    const expanded = shouldClose ? false : isTarget;
                    button.setAttribute('aria-expanded', expanded ? 'true' : 'false');
                    button.classList.toggle('is-active', expanded);
                });

                panels.forEach((panel) => {
                    panel.hidden = shouldClose ? true : panel.id !== targetId;
                });

                if (!shouldClose && targetPanel) {
                    targetPanel.hidden = false;
                    if (targetId === 'footer-editor-panel') {
                        setTimeout(() => window.dispatchEvent(new Event('resize')), 50);
                    }
                }
            };

            buttons.forEach((button) => {
                button.addEventListener('click', () => setActivePanel(button.dataset.moduleToggle));
            });

            if (!webpForms.length) {
                // continue
            }

            const bindUploadStatus = (input) => {
                const status = input.parentElement?.querySelector('[data-upload-status]');
                const statusText = status?.querySelector('[data-upload-status-text]');

                if (!status || !statusText || input.dataset.uploadBound === 'true') {
                    return;
                }

                input.dataset.uploadBound = 'true';
                input.addEventListener('change', () => {
                    if (!input.files?.length) {
                        status.classList.remove('is-visible', 'is-processing');
                        statusText.textContent = 'Select an image to convert to WebP.';
                        return;
                    }

                    status.classList.add('is-visible');
                    status.classList.remove('is-processing');
                    statusText.textContent = 'Image ready. It will convert to WebP when you save.';

                    const reviewCard = input.closest('[data-review-editor-card]');
                    const removeThumbnailInput = reviewCard?.querySelector('[data-review-remove-thumbnail-value]');

                    if (removeThumbnailInput) {
                        removeThumbnailInput.value = '0';
                    }
                });
            };

            const extractYoutubeIdFromUrl = (url) => {
                if (!url) return null;
                const match = url.match(/(?:youtu\.be\/|youtube\.com\/(?:embed\/|v\/|watch\?v=|watch\?.+&v=|shorts\/))([\w-]{11})/);
                return match ? match[1] : null;
            };

            const updateReviewCardThumbnails = (card, url) => {
                if (!card || !url) return;

                const previewBox = card.querySelector('[data-review-preview-box]');
                if (previewBox) {
                    let img = previewBox.querySelector('.review-live-preview-img');
                    const placeholder = previewBox.querySelector('.review-live-preview-placeholder');
                    if (!img) {
                        img = document.createElement('img');
                        img.className = 'review-live-preview-img';
                        img.alt = 'Video thumbnail preview';
                        img.loading = 'lazy';
                        img.decoding = 'async';
                        previewBox.insertBefore(img, previewBox.firstChild);
                    }
                    img.src = url;
                    if (placeholder) placeholder.remove();
                }

                const summaryThumb = card.querySelector('[data-review-summary-thumb]');
                if (summaryThumb) {
                    let thumbImg = summaryThumb.querySelector('img');
                    const thumbPlaceholder = summaryThumb.querySelector('.review-summary-thumb-placeholder');
                    if (!thumbImg) {
                        thumbImg = document.createElement('img');
                        thumbImg.alt = 'Video thumbnail preview';
                        thumbImg.loading = 'lazy';
                        thumbImg.decoding = 'async';
                        summaryThumb.appendChild(thumbImg);
                    }
                    thumbImg.src = url;
                    if (thumbPlaceholder) thumbPlaceholder.remove();
                }
            };

            const resetReviewCardThumbnailsToPlaceholder = (card) => {
                if (!card) return;

                const previewBox = card.querySelector('[data-review-preview-box]');
                if (previewBox) {
                    const img = previewBox.querySelector('.review-live-preview-img');
                    if (img) img.remove();
                    if (!previewBox.querySelector('.review-live-preview-placeholder')) {
                        const ph = document.createElement('div');
                        ph.className = 'review-live-preview-placeholder';
                        ph.innerHTML = `
                            <svg width="34" height="34" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.6" stroke-linecap="round" stroke-linejoin="round">
                                <polygon points="23 7 16 12 23 17 23 7"></polygon>
                                <rect x="1" y="5" width="15" height="14" rx="2" ry="2"></rect>
                            </svg>
                            <span>Enter a YouTube link or upload a custom cover</span>
                        `;
                        previewBox.insertBefore(ph, previewBox.firstChild);
                    }
                }

                const summaryThumb = card.querySelector('[data-review-summary-thumb]');
                if (summaryThumb) {
                    const img = summaryThumb.querySelector('img');
                    if (img) img.remove();
                    if (!summaryThumb.querySelector('.review-summary-thumb-placeholder')) {
                        summaryThumb.innerHTML = `
                            <div class="review-summary-thumb-placeholder">
                                <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                                    <polygon points="23 7 16 12 23 17 23 7"></polygon>
                                    <rect x="1" y="5" width="15" height="14" rx="2" ry="2"></rect>
                                </svg>
                            </div>
                        `;
                    }
                }
            };

            const removeReviewThumbnailPreview = (card) => {
                if (!card) {
                    return;
                }

                const removeThumbnailInput = card.querySelector('[data-review-remove-thumbnail-value]');
                const thumbnailPathInput = card.querySelector('[data-review-thumbnail-path]');
                const fileInput = card.querySelector('[name^="shareholder_review_thumbnails["]');
                const removeBtn = card.querySelector('[data-review-remove-thumbnail]');
                const fileLabel = card.querySelector('[data-review-file-label]');
                const coverTag = card.querySelector('[data-review-cover-tag]');
                const status = card.querySelector('[data-upload-status]');
                const statusText = status?.querySelector('[data-upload-status-text]');

                if (removeThumbnailInput) {
                    removeThumbnailInput.value = '1';
                }

                if (thumbnailPathInput) {
                    thumbnailPathInput.value = '';
                }

                if (fileInput) {
                    fileInput.value = '';
                }

                if (removeBtn) {
                    removeBtn.style.display = 'none';
                }

                if (fileLabel) {
                    fileLabel.textContent = 'Upload Custom Cover';
                }

                // Fallback to YouTube cover if available
                const videoInput = card.querySelector('[data-review-video-input]');
                const youtubeId = extractYoutubeIdFromUrl(videoInput?.value?.trim());

                if (youtubeId) {
                    const thumbUrl = `https://img.youtube.com/vi/${youtubeId}/hqdefault.jpg`;
                    updateReviewCardThumbnails(card, thumbUrl);
                    if (coverTag) {
                        coverTag.textContent = 'YouTube Auto';
                        coverTag.className = 'review-cover-tag is-youtube';
                    }
                } else {
                    resetReviewCardThumbnailsToPlaceholder(card);
                    if (coverTag) {
                        coverTag.textContent = 'No Cover';
                        coverTag.className = 'review-cover-tag is-none';
                    }
                }

                if (status && statusText) {
                    status.classList.remove('is-visible', 'is-processing');
                    statusText.textContent = 'Custom cover removed. Save to apply the change.';
                    status.classList.add('is-visible');
                }
            };

            const toggleReviewThumbnailPreview = (card) => {
                // Maintained for compatibility
            };

            const bindReviewThumbnailRemoval = (card) => {
                // Delegated in main click handler
            };

            const bindReviewThumbnailToggle = (card) => {
                // Delegated in main click handler
            };

            webpForms.forEach((form) => {
                const submitButton = form.querySelector('[data-about-submit], [data-webp-submit]');

                form.querySelectorAll('[data-webp-input]').forEach((input) => {
                    bindUploadStatus(input);
                });

                form.addEventListener('submit', () => {
                    const webpInputs = Array.from(form.querySelectorAll('[data-webp-input]'));

                    submitButton?.classList.add('is-loading');

                    webpInputs.forEach((input) => {
                        if (!input.files?.length) {
                            return;
                        }

                        const status = input.parentElement?.querySelector('[data-upload-status]');
                        const statusText = status?.querySelector('[data-upload-status-text]');

                        if (!status || !statusText) {
                            return;
                        }

                        status.classList.add('is-visible', 'is-processing');
                        statusText.textContent = 'Converting to WebP and uploading...';
                    });
                });
            });

            const syncProjectCardLabels = (list, type) => {
                if (!list) {
                    return;
                }

                list.querySelectorAll('[data-project-editor-card]').forEach((card, index) => {
                    const title = card.querySelector('.project-editor-card-title');

                    if (title) {
                        title.textContent = `${type === 'top' ? 'Top' : 'Bottom'} Card ${index + 1}`;
                    }
                });
            };

            const setProjectCardEditing = (card, editable) => {
                if (!card) {
                    return;
                }

                card.dataset.projectEditing = editable ? 'true' : 'false';
                card.classList.toggle('is-editing', editable);
                card.classList.toggle('is-locked', !editable);

                const toggleButton = card.querySelector('[data-project-toggle-edit]');

                if (toggleButton) {
                    toggleButton.textContent = editable ? 'Done' : 'Edit';
                    toggleButton.setAttribute('aria-pressed', editable ? 'true' : 'false');
                }

                card.querySelectorAll('[data-project-edit-field]').forEach((field) => {
                    if (editable) {
                        field.removeAttribute('readonly');
                        return;
                    }

                    field.setAttribute('readonly', 'readonly');
                });

                card.querySelectorAll('[data-project-file-control]').forEach((field) => {
                    field.hidden = !editable;
                });

                card.querySelectorAll('[data-project-edit-note]').forEach((note) => {
                    note.hidden = editable;
                });
            };

            const initializeProjectCard = (card, editable = null) => {
                if (!card) {
                    return;
                }

                const initialEditing = editable ?? card.dataset.projectEditingStart === 'true';
                setProjectCardEditing(card, initialEditing);
            };

            const createProjectCard = (type) => {
                const list = document.querySelector(`[data-project-card-list="${type}"]`);
                const template = type === 'top' ? topProjectTemplate : bottomProjectTemplate;

                if (!list || !template) {
                    return;
                }

                const index = Number(list.dataset.nextIndex || list.children.length || 0);
                const number = list.children.length + 1;
                const html = template.innerHTML
                    .replaceAll('__INDEX__', String(index))
                    .replaceAll('__NUMBER__', String(number));

                list.insertAdjacentHTML('beforeend', html);
                list.dataset.nextIndex = String(index + 1);
                list.querySelectorAll('[data-webp-input]').forEach((input) => bindUploadStatus(input));
                syncProjectCardLabels(list, type);
                initializeProjectCard(list.lastElementChild, true);
            };

            const markFooterDirty = () => {
                const indicator = document.querySelector('[data-footer-sync-indicator]');
                if (indicator && !indicator.classList.contains('is-dirty')) {
                    indicator.classList.add('is-dirty');
                    const text = indicator.querySelector('.footer-sync-text');
                    if (text) {
                        text.textContent = 'Unsaved changes';
                    }
                }
            };

            const syncOfficeCardLabels = (list) => {
                if (!list) {
                    return;
                }

                const cards = list.querySelectorAll('[data-office-editor-card]');
                cards.forEach((card, index) => {
                    const title = card.querySelector('.project-editor-card-title');

                    if (title) {
                        title.textContent = `Office ${index + 1}`;
                    }
                });

                const counter = document.querySelector('[data-office-counter]');
                if (counter) {
                    counter.textContent = String(cards.length);
                }
            };

            const createOfficeCard = () => {
                const list = document.querySelector('[data-office-card-list]');

                if (!list || !officeCardTemplate) {
                    return;
                }

                const index = Number(list.dataset.nextIndex || list.children.length || 0);
                const number = list.children.length + 1;
                const html = officeCardTemplate.innerHTML
                    .replaceAll('__INDEX__', String(index))
                    .replaceAll('__NUMBER__', String(number));

                list.insertAdjacentHTML('beforeend', html);
                list.dataset.nextIndex = String(index + 1);
                syncOfficeCardLabels(list);
                markFooterDirty();
            };

            const syncLeadershipMemberLabels = (list) => {
                if (!list) {
                    return;
                }

                const cards = list.querySelectorAll('[data-leadership-member-card]');
                const countBadge = document.querySelector('[data-leadership-member-count-badge]');
                if (countBadge) {
                    countBadge.textContent = `${cards.length} total`;
                }

                cards.forEach((card, index) => {
                    const badge = card.querySelector('.leadership-summary-badge');
                    if (badge) {
                        badge.textContent = `Board Member ${index + 1}`;
                    }
                });
            };

            const createLeadershipMemberCard = () => {
                const list = document.querySelector('[data-leadership-member-list]');

                if (!list || !leadershipMemberTemplate) {
                    return;
                }

                const index = Number(list.dataset.nextIndex || list.children.length || 0);
                const number = list.children.length + 1;
                const html = leadershipMemberTemplate.innerHTML
                    .replaceAll('__INDEX__', String(index))
                    .replaceAll('__NUMBER__', String(number));

                list.insertAdjacentHTML('beforeend', html);
                list.dataset.nextIndex = String(index + 1);

                const newCard = list.lastElementChild;
                if (newCard) {
                    newCard.classList.add('is-expanded');
                    newCard.querySelector('.leadership-member-summary')?.setAttribute('aria-expanded', 'true');
                    const toggleLabel = newCard.querySelector('.leadership-btn-toggle-label');
                    if (toggleLabel) {
                        toggleLabel.textContent = 'Close';
                    }
                    newCard.querySelectorAll('[data-webp-input]').forEach((input) => bindUploadStatus(input));
                    newCard.scrollIntoView({ behavior: 'smooth', block: 'center' });
                    newCard.querySelector('[data-leadership-member-name-input]')?.focus();
                }

                syncLeadershipMemberLabels(list);
            };

            const initValuedShareholderDirectory = () => {
                const root = document.querySelector('[data-shareholder-directory-root]');
                if (!root) return;

                const itemsUrl = root.dataset.itemsUrl;
                const storeUrl = root.dataset.storeUrl;
                const baseUrl = root.dataset.baseUrl;
                const searchInput = root.querySelector('[data-shareholder-search-input]');
                const clearBtn = root.querySelector('[data-shareholder-clear-btn]');
                const searchFeedback = root.querySelector('[data-shareholder-search-feedback]');
                const totalBadge = root.querySelector('[data-shareholder-total-badge]');
                const addBtn = root.querySelector('[data-shareholder-add-btn]');
                const newHost = root.querySelector('[data-shareholder-new-host]');
                const list = root.querySelector('[data-shareholder-list]');
                const sentinel = root.querySelector('[data-shareholder-sentinel]');
                const loader = root.querySelector('[data-shareholder-loader]');
                const cardTemplate = document.getElementById('valued-shareholder-card-template');
                const newTemplate = document.getElementById('valued-shareholder-new-template');

                let currentPage = parseInt(list?.dataset.currentPage || '1', 10);
                let hasMore = list?.dataset.hasMore === 'true';
                let isLoading = false;
                let currentSearch = '';
                let debounceTimer = null;

                const escapeHtml = (text) => {
                    const div = document.createElement('div');
                    div.textContent = text || '';
                    return div.innerHTML;
                };

                const reindexShareholderCards = () => {
                    if (!list) return;
                    const cards = list.querySelectorAll('[data-shareholder-card]');
                    cards.forEach((card, index) => {
                        const badge = card.querySelector('[data-shareholder-badge]');
                        if (badge) {
                            badge.textContent = `Shareholder #${index + 1}`;
                        }
                    });
                };

                const updateShareholderCardUI = (card, item) => {
                    if (!card || !item) return;

                    card.dataset.shareholderId = String(item.id || '');

                    const badge = card.querySelector('[data-shareholder-badge]');
                    if (badge && !item.id) {
                        badge.textContent = 'New Shareholder';
                    }

                    const summaryName = card.querySelector('[data-shareholder-summary-name]');
                    if (summaryName) {
                        summaryName.textContent = item.name ? item.name : 'Unnamed Shareholder';
                    }

                    const summaryPosition = card.querySelector('[data-shareholder-summary-position]');
                    if (summaryPosition) {
                        summaryPosition.textContent = item.position ? item.position : 'No position set';
                    }

                    const nameInput = card.querySelector('[data-shareholder-name-input]');
                    if (nameInput) {
                        nameInput.value = item.name || '';
                    }

                    const posInput = card.querySelector('[data-shareholder-position-input]');
                    if (posInput) {
                        posInput.value = item.position || '';
                    }

                    const fileInput = card.querySelector('[data-shareholder-file-input]');
                    const pickerLabel = card.querySelector('.leadership-photo-picker-btn');
                    const inputId = item.id ? `shareholder-img-${item.id}` : `shareholder-img-new-${Date.now()}`;
                    if (fileInput) fileInput.id = inputId;
                    if (pickerLabel) pickerLabel.setAttribute('for', inputId);

                    const summaryAvatar = card.querySelector('[data-shareholder-summary-avatar]');
                    const editorPreview = card.querySelector('[data-shareholder-editor-avatar-preview]');
                    const photoBtnLabel = card.querySelector('[data-shareholder-photo-btn-label]');
                    const removePhotoBtn = card.querySelector('[data-shareholder-remove-photo-btn]');
                    const removeImgInput = card.querySelector('[data-shareholder-remove-image-input]');

                    if (item.image_url) {
                        const imgHtml = `<img src="${item.image_url}" alt="${escapeHtml(item.name || 'Shareholder')} preview" loading="lazy" decoding="async">`;
                        if (summaryAvatar) summaryAvatar.innerHTML = imgHtml;
                        if (editorPreview) editorPreview.innerHTML = `<img src="${item.image_url}" alt="Shareholder photo" loading="lazy" decoding="async">`;
                        if (photoBtnLabel) photoBtnLabel.textContent = 'Change Photo';
                        if (removePhotoBtn) removePhotoBtn.style.display = 'inline-flex';
                    } else {
                        const avatarPlaceholder = `<div class="leadership-avatar-placeholder" aria-hidden="true"><svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.2" stroke-linecap="round" stroke-linejoin="round"><path d="M20 21v-2a4 4 0 0 0-4-4H8a4 4 0 0 0-4 4v2"></path><circle cx="12" cy="7" r="4"></circle></svg></div>`;
                        const tilePlaceholder = `<div class="leadership-photo-tile-placeholder" aria-hidden="true"><svg width="28" height="28" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.8" stroke-linecap="round" stroke-linejoin="round"><path d="M20 21v-2a4 4 0 0 0-4-4H8a4 4 0 0 0-4 4v2"></path><circle cx="12" cy="7" r="4"></circle></svg></div>`;
                        if (summaryAvatar) summaryAvatar.innerHTML = avatarPlaceholder;
                        if (editorPreview) editorPreview.innerHTML = tilePlaceholder;
                        if (photoBtnLabel) photoBtnLabel.textContent = 'Upload Photo (Optional)';
                        if (removePhotoBtn) removePhotoBtn.style.display = 'none';
                    }

                    if (removeImgInput) removeImgInput.value = '0';
                    if (fileInput) fileInput.value = '';
                    const status = card.querySelector('[data-upload-status]');
                    if (status) status.classList.remove('is-visible', 'is-processing');

                    const saveLabel = card.querySelector('[data-shareholder-save-label]');
                    if (saveLabel) saveLabel.textContent = item.id ? 'Save Changes' : 'Add Shareholder';

                    const saveSpinner = card.querySelector('[data-shareholder-save-spinner]');
                    if (saveSpinner) saveSpinner.style.display = 'none';

                    const saveBtn = card.querySelector('[data-shareholder-save-btn]');
                    if (saveBtn) saveBtn.disabled = false;
                };

                const createShareholderCard = (item) => {
                    if (!cardTemplate) return null;
                    const temp = document.createElement('div');
                    temp.innerHTML = cardTemplate.innerHTML.trim();
                    const card = temp.firstElementChild;
                    if (!card) return null;

                    updateShareholderCardUI(card, item);
                    return card;
                };

                const performSearch = async (query) => {
                    if (isLoading) return;
                    currentSearch = query.trim();
                    currentPage = 1;
                    isLoading = true;

                    if (clearBtn) {
                        clearBtn.style.display = currentSearch ? 'inline-flex' : 'none';
                    }

                    if (searchFeedback) {
                        searchFeedback.textContent = currentSearch ? 'Searching...' : '';
                    }

                    if (loader) {
                        loader.style.display = 'inline-flex';
                    }

                    try {
                        const url = new URL(itemsUrl, window.location.origin);
                        if (currentSearch) url.searchParams.set('search', currentSearch);
                        url.searchParams.set('page', '1');

                        const response = await fetch(url.toString(), {
                            headers: {
                                'Accept': 'application/json',
                                'X-Requested-With': 'XMLHttpRequest',
                            },
                        });

                        if (!response.ok) throw new Error('Search failed');

                        const result = await response.json();
                        list.innerHTML = '';

                        if (!result.data || result.data.length === 0) {
                            if (currentSearch) {
                                list.innerHTML = `<div class="shareholder-empty-state"><p>No shareholders found matching "<strong>${escapeHtml(currentSearch)}</strong>".</p></div>`;
                                if (searchFeedback) searchFeedback.textContent = 'No matching shareholders found.';
                            } else {
                                list.innerHTML = `<div class="shareholder-empty-state"><p>No shareholders found yet. Click <strong>+ Add Shareholder</strong> above to create one.</p></div>`;
                                if (searchFeedback) searchFeedback.textContent = '';
                            }
                        } else {
                            result.data.forEach((item) => {
                                const card = createShareholderCard(item);
                                if (card) list.appendChild(card);
                            });
                            reindexShareholderCards();

                            if (searchFeedback) {
                                searchFeedback.textContent = currentSearch
                                    ? `Found ${result.total} matching shareholder${result.total === 1 ? '' : 's'}`
                                    : '';
                            }
                        }

                        currentPage = result.current_page;
                        hasMore = result.has_more;
                        list.dataset.currentPage = String(currentPage);
                        list.dataset.hasMore = hasMore ? 'true' : 'false';

                        if (totalBadge && !currentSearch) {
                            totalBadge.textContent = `${result.total} total`;
                        }
                    } catch (err) {
                        console.error(err);
                        if (searchFeedback) searchFeedback.textContent = 'Failed to load shareholders.';
                    } finally {
                        isLoading = false;
                        if (loader) {
                            loader.style.display = hasMore ? 'inline-flex' : 'none';
                        }
                    }
                };

                const loadMore = async () => {
                    if (isLoading || !hasMore) return;
                    isLoading = true;

                    if (loader) {
                        loader.style.display = 'inline-flex';
                    }

                    const nextPage = currentPage + 1;

                    try {
                        const url = new URL(itemsUrl, window.location.origin);
                        if (currentSearch) url.searchParams.set('search', currentSearch);
                        url.searchParams.set('page', String(nextPage));

                        const response = await fetch(url.toString(), {
                            headers: {
                                'Accept': 'application/json',
                                'X-Requested-With': 'XMLHttpRequest',
                            },
                        });

                        if (!response.ok) throw new Error('Load more failed');

                        const result = await response.json();

                        if (result.data && result.data.length > 0) {
                            result.data.forEach((item) => {
                                const card = createShareholderCard(item);
                                if (card) list.appendChild(card);
                            });
                            reindexShareholderCards();
                        }

                        currentPage = result.current_page;
                        hasMore = result.has_more;
                        list.dataset.currentPage = String(currentPage);
                        list.dataset.hasMore = hasMore ? 'true' : 'false';
                    } catch (err) {
                        console.error(err);
                    } finally {
                        isLoading = false;
                        if (loader) {
                            loader.style.display = hasMore ? 'inline-flex' : 'none';
                        }
                    }
                };

                // Infinite Scroll Observer
                if (sentinel && 'IntersectionObserver' in window) {
                    const observer = new IntersectionObserver((entries) => {
                        const entry = entries[0];
                        if (entry && entry.isIntersecting && !isLoading && hasMore) {
                            loadMore();
                        }
                    }, { rootMargin: '200px' });
                    observer.observe(sentinel);
                }

                // Search input handlers
                if (searchInput) {
                    searchInput.addEventListener('input', () => {
                        if (clearBtn) {
                            clearBtn.style.display = searchInput.value.trim() ? 'inline-flex' : 'none';
                        }
                        if (debounceTimer) clearTimeout(debounceTimer);
                        debounceTimer = setTimeout(() => {
                            performSearch(searchInput.value);
                        }, 350);
                    });

                    searchInput.addEventListener('keydown', (e) => {
                        if (e.key === 'Enter') {
                            e.preventDefault();
                            if (debounceTimer) clearTimeout(debounceTimer);
                            performSearch(searchInput.value);
                        }
                    });
                }

                if (clearBtn) {
                    clearBtn.addEventListener('click', () => {
                        if (searchInput) {
                            searchInput.value = '';
                            searchInput.focus();
                        }
                        clearBtn.style.display = 'none';
                        if (debounceTimer) clearTimeout(debounceTimer);
                        performSearch('');
                    });
                }

                // Add Shareholder
                if (addBtn && newHost && newTemplate) {
                    addBtn.addEventListener('click', () => {
                        if (newHost.firstElementChild) {
                            newHost.scrollIntoView({ behavior: 'smooth', block: 'center' });
                            newHost.querySelector('[data-shareholder-name-input]')?.focus();
                            return;
                        }

                        newHost.innerHTML = newTemplate.innerHTML.trim();
                        newHost.style.display = 'block';
                        const newCard = newHost.firstElementChild;
                        if (newCard) {
                            newCard.classList.add('is-expanded');
                            newCard.querySelector('.leadership-member-summary')?.setAttribute('aria-expanded', 'true');
                            const toggleLabel = newCard.querySelector('.leadership-btn-toggle-label');
                            if (toggleLabel) toggleLabel.textContent = 'Close';
                            newHost.scrollIntoView({ behavior: 'smooth', block: 'center' });
                            newCard.querySelector('[data-shareholder-name-input]')?.focus();
                        }
                    });
                }

                // Global event delegation for directory interactions
                root.addEventListener('click', async (event) => {
                    // 1. Remove Button (ALWAYS HANDLE FIRST!)
                    const removeBtn = event.target.closest('[data-shareholder-remove-btn]');
                    if (removeBtn) {
                        const card = removeBtn.closest('[data-shareholder-card]');
                        if (!card) return;

                        const id = card.dataset.shareholderId;
                        if (!id) {
                            newHost.innerHTML = '';
                            newHost.style.display = 'none';
                            return;
                        }

                        const confirmed = await (window.adminConfirm?.({
                            title: 'Remove Shareholder',
                            message: removeBtn.dataset.confirmMessage || 'Are you sure you want to remove this shareholder? This change will be saved immediately.',
                            confirmLabel: 'Remove',
                            cancelLabel: 'Keep',
                        }) ?? Promise.resolve(window.confirm(removeBtn.dataset.confirmMessage || 'Are you sure you want to remove this shareholder?')));

                        if (!confirmed) return;

                        const csrfToken = document.querySelector('meta[name="csrf-token"]')?.getAttribute('content')
                            || document.querySelector('input[name="_token"]')?.value || '';

                        try {
                            const res = await fetch(`${baseUrl}/${id}`, {
                                method: 'DELETE',
                                headers: {
                                    'X-CSRF-TOKEN': csrfToken,
                                    'Accept': 'application/json',
                                    'X-Requested-With': 'XMLHttpRequest',
                                },
                            });

                            const data = await res.json();

                            if (!res.ok) {
                                const errMsg = data.message || 'Failed to remove shareholder.';
                                window.showAdminToast ? window.showAdminToast(errMsg, 'error') : alert(errMsg);
                                return;
                            }

                            card.remove();
                            reindexShareholderCards();

                            if (totalBadge && data.total !== undefined) {
                                totalBadge.textContent = `${data.total} total`;
                            }

                            if (list.children.length === 0) {
                                list.innerHTML = `<div class="shareholder-empty-state"><p>No shareholders found yet. Click <strong>+ Add Shareholder</strong> above to create one.</p></div>`;
                            }

                            if (window.showAdminToast) {
                                window.showAdminToast(data.message || 'Shareholder removed successfully.');
                            }
                        } catch (err) {
                            console.error(err);
                            window.showAdminToast ? window.showAdminToast('Failed to remove shareholder.', 'error') : alert('Failed to remove shareholder.');
                        }
                        return;
                    }

                    // 1b. Remove Photo Button
                    const removePhotoBtn = event.target.closest('[data-shareholder-remove-photo-btn]');
                    if (removePhotoBtn) {
                        const card = removePhotoBtn.closest('[data-shareholder-card]');
                        if (!card) return;

                        const fileInput = card.querySelector('[data-shareholder-file-input]');
                        const removeImgInput = card.querySelector('[data-shareholder-remove-image-input]');
                        const summaryAvatar = card.querySelector('[data-shareholder-summary-avatar]');
                        const editorPreview = card.querySelector('[data-shareholder-editor-avatar-preview]');
                        const photoBtnLabel = card.querySelector('[data-shareholder-photo-btn-label]');

                        if (fileInput) fileInput.value = '';
                        if (removeImgInput) removeImgInput.value = '1';

                        const avatarPlaceholder = `<div class="leadership-avatar-placeholder" aria-hidden="true"><svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.2" stroke-linecap="round" stroke-linejoin="round"><path d="M20 21v-2a4 4 0 0 0-4-4H8a4 4 0 0 0-4 4v2"></path><circle cx="12" cy="7" r="4"></circle></svg></div>`;
                        const tilePlaceholder = `<div class="leadership-photo-tile-placeholder" aria-hidden="true"><svg width="28" height="28" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.8" stroke-linecap="round" stroke-linejoin="round"><path d="M20 21v-2a4 4 0 0 0-4-4H8a4 4 0 0 0-4 4v2"></path><circle cx="12" cy="7" r="4"></circle></svg></div>`;
                        if (summaryAvatar) summaryAvatar.innerHTML = avatarPlaceholder;
                        if (editorPreview) editorPreview.innerHTML = tilePlaceholder;
                        if (photoBtnLabel) photoBtnLabel.textContent = 'Upload Photo (Optional)';
                        removePhotoBtn.style.display = 'none';
                        return;
                    }

                    // 2. Save Button
                    const saveBtn = event.target.closest('[data-shareholder-save-btn]');
                    if (saveBtn) {
                        const card = saveBtn.closest('[data-shareholder-card]');
                        if (!card) return;

                        const id = card.dataset.shareholderId;
                        const nameInput = card.querySelector('[data-shareholder-name-input]');
                        const posInput = card.querySelector('[data-shareholder-position-input]');
                        const fileInput = card.querySelector('[data-shareholder-file-input]');
                        const removeImgInput = card.querySelector('[data-shareholder-remove-image-input]');
                        const nameError = card.querySelector('[data-shareholder-name-error]');
                        const saveLabel = card.querySelector('[data-shareholder-save-label]');
                        const saveSpinner = card.querySelector('[data-shareholder-save-spinner]');

                        if (nameError) {
                            nameError.textContent = '';
                            nameError.style.display = 'none';
                        }

                        const nameVal = nameInput?.value?.trim() || '';
                        if (!nameVal) {
                            if (nameError) {
                                nameError.textContent = 'Shareholder name is required.';
                                nameError.style.display = 'block';
                            }
                            nameInput?.focus();
                            return;
                        }

                        const fd = new FormData();
                        fd.append('name', nameVal);
                        fd.append('position', posInput?.value?.trim() || '');
                        if (fileInput?.files?.length) {
                            fd.append('image', fileInput.files[0]);
                        }
                        if (removeImgInput && removeImgInput.value === '1') {
                            fd.append('remove_image', '1');
                        }

                        const csrfToken = document.querySelector('meta[name="csrf-token"]')?.getAttribute('content')
                            || document.querySelector('input[name="_token"]')?.value || '';
                        if (csrfToken) {
                            fd.append('_token', csrfToken);
                        }

                        saveBtn.disabled = true;
                        if (saveLabel) saveLabel.textContent = 'Saving...';
                        if (saveSpinner) saveSpinner.style.display = 'inline-block';

                        const targetUrl = id ? `${baseUrl}/${id}` : storeUrl;

                        try {
                            const res = await fetch(targetUrl, {
                                method: 'POST',
                                headers: {
                                    'X-CSRF-TOKEN': csrfToken,
                                    'Accept': 'application/json',
                                    'X-Requested-With': 'XMLHttpRequest',
                                },
                                body: fd,
                            });

                            const data = await res.json();

                            if (!res.ok) {
                                if (res.status === 422 && data.errors) {
                                    if (data.errors.name && nameError) {
                                        nameError.textContent = data.errors.name[0];
                                        nameError.style.display = 'block';
                                        nameInput?.focus();
                                    }
                                    if (data.errors.image) {
                                        window.showAdminToast ? window.showAdminToast(data.errors.image[0], 'error') : alert(data.errors.image[0]);
                                    }
                                } else {
                                    const errMsg = data.message || 'Failed to save shareholder.';
                                    window.showAdminToast ? window.showAdminToast(errMsg, 'error') : alert(errMsg);
                                }
                                return;
                            }

                            if (id) {
                                updateShareholderCardUI(card, data.item);
                                card.classList.remove('is-expanded');
                                card.querySelector('.leadership-member-summary')?.setAttribute('aria-expanded', 'false');
                                const toggleLabel = card.querySelector('.leadership-btn-toggle-label');
                                if (toggleLabel) toggleLabel.textContent = 'Edit';
                                if (window.showAdminToast) {
                                    window.showAdminToast(data.message || 'Shareholder updated successfully.');
                                }
                            } else {
                                const createdCard = createShareholderCard(data.item);
                                if (createdCard) {
                                    list.prepend(createdCard);
                                    reindexShareholderCards();
                                }
                                newHost.innerHTML = '';
                                newHost.style.display = 'none';

                                const emptyState = list.querySelector('[data-shareholder-empty-state], .shareholder-empty-state');
                                if (emptyState) emptyState.remove();

                                if (totalBadge && data.total !== undefined) {
                                    totalBadge.textContent = `${data.total} total`;
                                }

                                if (window.showAdminToast) {
                                    window.showAdminToast(data.message || 'Shareholder added successfully.');
                                }
                            }
                        } catch (err) {
                            console.error(err);
                            window.showAdminToast ? window.showAdminToast('A network error occurred. Please try again.', 'error') : alert('A network error occurred.');
                        } finally {
                            saveBtn.disabled = false;
                            if (saveLabel) saveLabel.textContent = id ? 'Save Changes' : 'Add Shareholder';
                            if (saveSpinner) saveSpinner.style.display = 'none';
                        }
                        return;
                    }

                    // 3. Cancel Button in card footer
                    const cancelBtn = event.target.closest('[data-shareholder-close-btn]');
                    if (cancelBtn) {
                        const card = cancelBtn.closest('[data-shareholder-card]');
                        if (card) {
                            if (card.closest('[data-shareholder-new-host]')) {
                                newHost.innerHTML = '';
                                newHost.style.display = 'none';
                            } else {
                                card.classList.remove('is-expanded');
                                card.querySelector('.leadership-member-summary')?.setAttribute('aria-expanded', 'false');
                                const toggleLabel = card.querySelector('.leadership-btn-toggle-label');
                                if (toggleLabel) toggleLabel.textContent = 'Edit';
                            }
                        }
                        return;
                    }

                    // 4. Accordion Header or Toggle Button
                    const toggleBtn = event.target.closest('[data-shareholder-toggle-btn]');
                    const toggleSummary = event.target.closest('[data-shareholder-toggle-accordion]');

                    if (toggleBtn || toggleSummary) {
                        if (event.target.closest('input') || event.target.closest('label') || event.target.closest('button:not([data-shareholder-toggle-btn])')) {
                            return;
                        }
                        const card = event.target.closest('[data-shareholder-card]');
                        if (card) {
                            const isExpanded = card.classList.toggle('is-expanded');
                            card.querySelector('.leadership-member-summary')?.setAttribute('aria-expanded', isExpanded ? 'true' : 'false');
                            const toggleLabel = card.querySelector('.leadership-btn-toggle-label');
                            if (toggleLabel) {
                                toggleLabel.textContent = isExpanded ? 'Close' : 'Edit';
                            }
                        }
                        return;
                    }
                });

                // Live typing updates
                root.addEventListener('input', (event) => {
                    const nameInput = event.target.closest('[data-shareholder-name-input]');
                    if (nameInput) {
                        const card = nameInput.closest('[data-shareholder-card]');
                        const summaryName = card?.querySelector('[data-shareholder-summary-name]');
                        if (summaryName) {
                            summaryName.textContent = nameInput.value.trim() || 'Unnamed Shareholder';
                        }
                        return;
                    }

                    const posInput = event.target.closest('[data-shareholder-position-input]');
                    if (posInput) {
                        const card = posInput.closest('[data-shareholder-card]');
                        const summaryPos = card?.querySelector('[data-shareholder-summary-position]');
                        if (summaryPos) {
                            summaryPos.textContent = posInput.value.trim() || 'No position set';
                        }
                        return;
                    }
                });

                // Image selection preview
                root.addEventListener('change', (event) => {
                    const fileInput = event.target.closest('[data-shareholder-file-input]');
                    if (!fileInput || !fileInput.files || !fileInput.files[0]) return;

                    const file = fileInput.files[0];
                    const card = fileInput.closest('[data-shareholder-card]');
                    if (!card) return;

                    if (file.size > 6 * 1024 * 1024) {
                        if (window.showAdminToast) {
                            window.showAdminToast('Image file size exceeds 6MB limit.', 'error');
                        } else {
                            alert('Image file size exceeds 6MB limit.');
                        }
                        fileInput.value = '';
                        return;
                    }

                    const reader = new FileReader();
                    reader.onload = (e) => {
                        const url = e.target.result;
                        const summaryAvatar = card.querySelector('[data-shareholder-summary-avatar]');
                        const editorPreview = card.querySelector('[data-shareholder-editor-avatar-preview]');
                        const photoBtnLabel = card.querySelector('[data-shareholder-photo-btn-label]');
                        const status = card.querySelector('[data-upload-status]');
                        const statusText = status?.querySelector('[data-upload-status-text]');

                        if (summaryAvatar) {
                            summaryAvatar.innerHTML = `<img src="${url}" alt="Preview" loading="lazy" decoding="async">`;
                        }
                        if (editorPreview) {
                            editorPreview.innerHTML = `<img src="${url}" alt="Shareholder photo" loading="lazy" decoding="async">`;
                        }
                        if (photoBtnLabel) {
                            photoBtnLabel.textContent = 'Change Photo';
                        }
                        const removeImgInput = card.querySelector('[data-shareholder-remove-image-input]');
                        const removePhotoBtn = card.querySelector('[data-shareholder-remove-photo-btn]');
                        if (removeImgInput) removeImgInput.value = '0';
                        if (removePhotoBtn) removePhotoBtn.style.display = 'inline-flex';
                        if (status) {
                            status.classList.add('is-visible');
                            status.classList.remove('is-processing');
                        }
                        if (statusText) {
                            statusText.textContent = 'Image ready. It will convert to WebP when you click Save.';
                        }
                    };
                    reader.readAsDataURL(file);
                });

                // Accessibility: Enter or Space on summary toggles expansion
                root.addEventListener('keydown', (event) => {
                    if (event.key === 'Enter' || event.key === ' ') {
                        const summary = event.target.closest('[data-shareholder-toggle-accordion]');
                        if (summary && event.target === summary) {
                            event.preventDefault();
                            const card = summary.closest('[data-shareholder-card]');
                            if (card) {
                                const isExpanded = card.classList.toggle('is-expanded');
                                summary.setAttribute('aria-expanded', isExpanded ? 'true' : 'false');
                                const toggleLabel = card.querySelector('.leadership-btn-toggle-label');
                                if (toggleLabel) {
                                    toggleLabel.textContent = isExpanded ? 'Close' : 'Edit';
                                }
                            }
                        }
                    }
                });

                reindexShareholderCards();
            };

            const escapeGalleryHtml = (str) => {
                const div = document.createElement('div');
                div.textContent = str;
                return div.innerHTML;
            };

            const syncGalleryAlbumNav = (forceActiveIndex = null) => {
                const list = document.querySelector('[data-gallery-album-list]');
                const nav = document.querySelector('[data-gallery-album-nav]');
                const totalBadge = document.querySelector('[data-gallery-total-albums-badge]');
                const emptyWorkspace = document.querySelector('[data-gallery-workspace-empty]');

                if (!list || !nav) {
                    return;
                }

                const cards = Array.from(list.querySelectorAll('[data-gallery-album-card]'));

                if (totalBadge) {
                    totalBadge.textContent = `${cards.length} total`;
                }

                if (emptyWorkspace) {
                    emptyWorkspace.style.display = cards.length === 0 ? 'flex' : 'none';
                }

                if (cards.length === 0) {
                    nav.innerHTML = '<div class="gallery-album-nav-empty" data-gallery-nav-empty><span>No albums yet. Click "+ New Album" to create one.</span></div>';
                    return;
                }

                let activeCard = null;
                if (forceActiveIndex !== null && forceActiveIndex !== undefined) {
                    activeCard = cards.find((c) => String(c.dataset.galleryAlbumIndex) === String(forceActiveIndex));
                }
                if (!activeCard) {
                    const cardWithError = cards.find((c) => c.querySelector('.field-error'));
                    activeCard = cardWithError || cards.find((c) => c.classList.contains('is-active')) || cards[0];
                }

                const activeIndex = activeCard ? activeCard.dataset.galleryAlbumIndex : null;

                cards.forEach((card) => {
                    const isCurrent = card === activeCard;
                    card.classList.toggle('is-active', isCurrent);
                });

                nav.innerHTML = '';

                cards.forEach((card, i) => {
                    const idx = card.dataset.galleryAlbumIndex || String(i);
                    const titleInput = card.querySelector('input[name*="[title]"]');
                    const rawTitle = titleInput?.value?.trim();
                    const displayTitle = rawTitle ? rawTitle : `Album ${i + 1}`;
                    const imgCount = card.querySelectorAll('[data-gallery-album-image-card]').length;
                    const isCurrent = String(idx) === String(activeIndex);
                    const hasError = !!card.querySelector('.field-error');

                    const item = document.createElement('button');
                    item.type = 'button';
                    item.className = `gallery-album-nav-item ${isCurrent ? 'is-active' : ''} ${hasError ? 'has-error' : ''}`;
                    item.dataset.galleryAlbumTab = String(idx);
                    item.innerHTML = `
                        <div class="gallery-album-nav-item-icon">
                            <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                                <path d="M22 19a2 2 0 0 1-2 2H4a2 2 0 0 1-2-2V5a2 2 0 0 1 2-2h5l2 3h9a2 2 0 0 1 2 2z"></path>
                            </svg>
                        </div>
                        <div class="gallery-album-nav-item-copy">
                            <span class="gallery-album-nav-title" data-gallery-nav-title>${escapeGalleryHtml(displayTitle)}</span>
                            <span class="gallery-album-nav-count" data-gallery-nav-count>${imgCount} ${imgCount === 1 ? 'photo' : 'photos'}</span>
                        </div>
                    `;

                    item.addEventListener('click', () => {
                        syncGalleryAlbumNav(idx);
                    });

                    nav.appendChild(item);
                });
            };

            const syncGalleryAlbumLabels = (list) => {
                if (!list) {
                    return;
                }

                list.querySelectorAll('[data-gallery-album-card]').forEach((card, index) => {
                    const title = card.querySelector('.project-editor-card-title');

                    if (title) {
                        title.textContent = `Album ${index + 1}`;
                    }
                });

                syncGalleryAlbumNav();
            };

            const syncGalleryAlbumImageLabels = (list) => {
                if (!list) {
                    return;
                }

                list.querySelectorAll('[data-gallery-album-image-card]').forEach((card, index) => {
                    const title = card.querySelector('.project-editor-card-title');

                    if (title) {
                        title.textContent = `Image ${index + 1}`;
                    }
                });

                const albumCard = list.closest('[data-gallery-album-card]');
                if (albumCard) {
                    const idx = albumCard.dataset.galleryAlbumIndex;
                    const navItem = document.querySelector(`[data-gallery-album-tab="${idx}"]`);
                    const countEl = navItem?.querySelector('[data-gallery-nav-count]');
                    if (countEl) {
                        const count = list.querySelectorAll('[data-gallery-album-image-card]').length;
                        countEl.textContent = `${count} ${count === 1 ? 'photo' : 'photos'}`;
                    }
                }
            };

            const createGalleryAlbum = () => {
                const list = document.querySelector('[data-gallery-album-list]');

                if (!list || !galleryAlbumTemplate) {
                    return;
                }

                const albumIndex = Number(list.dataset.nextIndex || list.children.length || 0);
                const albumNumber = list.children.length + 1;
                const html = galleryAlbumTemplate.innerHTML
                    .replaceAll('__ALBUM_INDEX__', String(albumIndex))
                    .replaceAll('__ALBUM_NUMBER__', String(albumNumber));

                list.insertAdjacentHTML('beforeend', html);
                list.dataset.nextIndex = String(albumIndex + 1);
                list.querySelectorAll('[data-webp-input]').forEach((input) => bindUploadStatus(input));
                syncGalleryAlbumLabels(list);
                syncGalleryAlbumNav(albumIndex);

                const newCard = list.querySelector(`[data-gallery-album-index="${albumIndex}"]`);
                newCard?.querySelector('input[name*="[title]"]')?.focus();
            };

            const createGalleryAlbumImage = (albumCard) => {
                if (!albumCard || !galleryAlbumImageTemplate) {
                    return;
                }

                const albumIndex = Number(albumCard.dataset.galleryAlbumIndex || 0);
                const list = albumCard.querySelector('[data-gallery-album-image-list]');

                if (!list) {
                    return;
                }

                const imageIndex = Number(list.dataset.nextIndex || list.children.length || 0);
                const imageNumber = list.children.length + 1;
                const html = galleryAlbumImageTemplate.innerHTML
                    .replaceAll('__ALBUM_INDEX__', String(albumIndex))
                    .replaceAll('__IMAGE_INDEX__', String(imageIndex))
                    .replaceAll('__IMAGE_NUMBER__', String(imageNumber));

                list.insertAdjacentHTML('beforeend', html);
                list.dataset.nextIndex = String(imageIndex + 1);
                list.querySelectorAll('[data-webp-input]').forEach((input) => bindUploadStatus(input));
                syncGalleryAlbumImageLabels(list);
            };

            const syncReviewCardLabels = (list) => {
                if (!list) {
                    return;
                }

                const cards = list.querySelectorAll('[data-review-editor-card]');
                const countBadge = document.querySelector('[data-review-count-badge]');

                if (countBadge) {
                    countBadge.textContent = `${cards.length} total`;
                }

                cards.forEach((card, index) => {
                    const title = card.querySelector('.project-editor-card-title');

                    if (title) {
                        title.textContent = `Review ${index + 1}`;
                    }
                });
            };

            const initializeReviewCard = (card) => {
                if (!card) {
                    return;
                }

                card.querySelectorAll('[data-webp-input]').forEach((input) => bindUploadStatus(input));
            };

            const syncReviewDraftHostVisibility = (host) => {
                if (!host) {
                    return;
                }

                host.hidden = !host.querySelector('[data-review-editor-card]');
            };

            const finalizeReviewDraft = ({ shouldFocus = true } = {}) => {
                const list = document.querySelector('[data-review-card-list]');
                const draftHost = document.querySelector('[data-review-draft-host]');

                if (!list || !draftHost) {
                    return true;
                }

                const draftCard = draftHost.querySelector('[data-review-editor-card]');

                if (!draftCard) {
                    return true;
                }

                const videoInput = draftCard.querySelector('[name^="shareholder_reviews["][name$="[video_url]"]');

                if (!videoInput || !String(videoInput.value || '').trim()) {
                    if (shouldFocus && videoInput) {
                        window.alert('Please enter a valid video link before saving this review.');
                        videoInput.focus();
                    }

                    return false;
                }

                const saveDraftButton = draftCard.querySelector('[data-review-save-draft]');
                const removeButton = draftCard.querySelector('[data-review-remove-card]');
                const title = draftCard.querySelector('.project-editor-card-title');

                draftCard.removeAttribute('data-review-draft');
                draftCard.classList.remove('is-draft');

                if (saveDraftButton) {
                    saveDraftButton.remove();
                }

                if (removeButton) {
                    removeButton.textContent = 'Remove';
                    removeButton.dataset.confirmMessage = 'Are you sure you want to remove this review? This change will be saved when you submit the form.';
                }

                if (title) {
                    title.textContent = `Review ${list.querySelectorAll('[data-review-editor-card]').length + 1}`;
                }

                draftCard.classList.remove('is-expanded');
                const summary = draftCard.querySelector('.review-card-summary');
                if (summary) {
                    summary.setAttribute('aria-expanded', 'false');
                }
                const toggleLabel = draftCard.querySelector('.review-btn-toggle-label');
                if (toggleLabel) {
                    toggleLabel.textContent = 'Edit';
                }

                list.appendChild(draftCard);
                syncReviewCardLabels(list);

                syncReviewDraftHostVisibility(draftHost);

                return true;
            };

            const createReviewCard = () => {
                const list = document.querySelector('[data-review-card-list]');
                const draftHost = document.querySelector('[data-review-draft-host]');

                if (!list || !draftHost || !shareholderReviewDraftTemplate) {
                    return;
                }

                const existingDraft = draftHost.querySelector('[data-review-editor-card]');

                if (existingDraft) {
                    existingDraft.scrollIntoView({ behavior: 'smooth', block: 'start' });
                    existingDraft.querySelector('input, textarea, select')?.focus();
                    return;
                }

                const index = Number(list.dataset.nextIndex || list.children.length || 0);
                const html = shareholderReviewDraftTemplate.innerHTML
                    .replaceAll('__INDEX__', String(index));

                draftHost.innerHTML = html;
                draftHost.hidden = false;
                list.dataset.nextIndex = String(index + 1);

                const draftCard = draftHost.querySelector('[data-review-editor-card]');
                initializeReviewCard(draftCard);
                draftCard?.scrollIntoView({ behavior: 'smooth', block: 'start' });
                draftCard?.querySelector('input, textarea, select')?.focus();
            };

            document.querySelectorAll('[data-project-add]').forEach((button) => {
                button.addEventListener('click', () => {
                    createProjectCard(button.dataset.projectAdd);
                });
            });

            document.querySelectorAll('[data-office-add]').forEach((button) => {
                button.addEventListener('click', () => {
                    createOfficeCard();
                });
            });

            document.querySelectorAll('[data-gallery-add-album]').forEach((button) => {
                button.addEventListener('click', () => {
                    createGalleryAlbum();
                });
            });

            document.querySelectorAll('[data-review-add]').forEach((button) => {
                button.addEventListener('click', () => {
                    createReviewCard();
                });
            });

            document.querySelectorAll('[data-leadership-member-add]').forEach((button) => {
                button.addEventListener('click', () => {
                    createLeadershipMemberCard();
                });
            });

            document.querySelectorAll('[data-project-card-list]').forEach((list) => {
                syncProjectCardLabels(list, list.dataset.projectCardList);
                list.querySelectorAll('[data-project-editor-card]').forEach((card) => initializeProjectCard(card));
            });

            document.querySelectorAll('[data-office-card-list]').forEach((list) => {
                syncOfficeCardLabels(list);
            });

            document.querySelectorAll('[data-gallery-album-list]').forEach((list) => {
                syncGalleryAlbumLabels(list);
            });

            document.querySelectorAll('[data-gallery-album-image-list]').forEach((list) => {
                syncGalleryAlbumImageLabels(list);
            });

            document.querySelectorAll('[data-review-card-list]').forEach((list) => {
                syncReviewCardLabels(list);
                list.querySelectorAll('[data-review-editor-card]').forEach((card) => initializeReviewCard(card));
            });

            document.querySelectorAll('[data-leadership-member-list]').forEach((list) => {
                syncLeadershipMemberLabels(list);
            });

            initValuedShareholderDirectory();

            document.addEventListener('input', (event) => {
                const titleInput = event.target.closest('[data-gallery-album-title-input]');
                if (titleInput) {
                    const card = titleInput.closest('[data-gallery-album-card]');
                    if (!card) return;

                    const idx = card.dataset.galleryAlbumIndex;
                    const navItem = document.querySelector(`[data-gallery-album-tab="${idx}"]`);
                    const titleEl = navItem?.querySelector('[data-gallery-nav-title]');

                    if (titleEl) {
                        const cards = Array.from(document.querySelectorAll('[data-gallery-album-card]'));
                        const cardIndex = cards.indexOf(card);
                        const val = titleInput.value.trim();
                        titleEl.textContent = val !== '' ? val : `Album ${cardIndex + 1}`;
                    }
                    return;
                }

                const reviewNameInput = event.target.closest('[data-review-name-input]');
                if (reviewNameInput) {
                    const card = reviewNameInput.closest('[data-review-editor-card]');
                    const summaryName = card?.querySelector('[data-review-summary-name]');
                    if (summaryName) {
                        const val = reviewNameInput.value.trim();
                        summaryName.textContent = val !== '' ? val : 'Unassigned Reviewer';
                    }
                    return;
                }

                const reviewVideoInput = event.target.closest('[data-review-video-input]');
                if (reviewVideoInput) {
                    const card = reviewVideoInput.closest('[data-review-editor-card]');
                    const val = reviewVideoInput.value.trim();

                    const summaryUrl = card?.querySelector('[data-review-summary-url]');
                    if (summaryUrl) {
                        summaryUrl.textContent = val !== '' ? val : 'No video URL added';
                    }

                    const extLink = card?.querySelector('[data-review-external-link]');
                    if (extLink) {
                        extLink.href = val || '#';
                        extLink.style.display = val ? 'inline' : 'none';
                    }

                    const removeCustomThumbBtn = card?.querySelector('[data-review-remove-thumbnail]');
                    const hasCustomThumb = removeCustomThumbBtn && removeCustomThumbBtn.style.display !== 'none';

                    if (!hasCustomThumb) {
                        const youtubeId = extractYoutubeIdFromUrl(val);
                        const coverTag = card?.querySelector('[data-review-cover-tag]');

                        if (youtubeId) {
                            const thumbUrl = `https://img.youtube.com/vi/${youtubeId}/hqdefault.jpg`;
                            updateReviewCardThumbnails(card, thumbUrl);
                            if (coverTag) {
                                coverTag.textContent = 'YouTube Auto';
                                coverTag.className = 'review-cover-tag is-youtube';
                            }
                        } else {
                            resetReviewCardThumbnailsToPlaceholder(card);
                            if (coverTag) {
                                coverTag.textContent = 'No Cover';
                                coverTag.className = 'review-cover-tag is-none';
                            }
                        }
                    }
                    return;
                }

                const leadershipMemberNameInput = event.target.closest('[data-leadership-member-name-input]');
                if (leadershipMemberNameInput) {
                    const card = leadershipMemberNameInput.closest('[data-leadership-member-card]');
                    const summaryName = card?.querySelector('[data-leadership-summary-name]');
                    if (summaryName) {
                        const val = leadershipMemberNameInput.value.trim();
                        summaryName.textContent = val !== '' ? val : 'Unnamed Board Member';
                    }
                    return;
                }

                const leadershipMemberPositionInput = event.target.closest('[data-leadership-member-position-input]');
                if (leadershipMemberPositionInput) {
                    const card = leadershipMemberPositionInput.closest('[data-leadership-member-card]');
                    const summaryPos = card?.querySelector('[data-leadership-summary-position]');
                    if (summaryPos) {
                        const val = leadershipMemberPositionInput.value.trim();
                        summaryPos.textContent = val !== '' ? val : 'No position set';
                    }
                    return;
                }

                const founderDescriptionInput = event.target.closest('#founder-description');
                if (founderDescriptionInput) {
                    const counter = document.querySelector('[data-founder-char-count]');
                    if (counter) {
                        counter.textContent = String(founderDescriptionInput.value.length);
                    }
                    return;
                }
            });

            document.addEventListener('change', (event) => {
                const fileInput = event.target.closest('.gallery-photo-tile-file');
                if (fileInput && fileInput.files && fileInput.files[0]) {
                    const file = fileInput.files[0];
                    if (file.type.startsWith('image/')) {
                        const tile = fileInput.closest('[data-gallery-album-image-card]');
                        const previewContainer = tile?.querySelector('.gallery-photo-tile-preview');
                        if (previewContainer) {
                            const reader = new FileReader();
                            reader.onload = (e) => {
                                let img = previewContainer.querySelector('img');
                                const placeholder = previewContainer.querySelector('.gallery-photo-tile-placeholder');

                                if (!img) {
                                    img = document.createElement('img');
                                    img.alt = 'Album photo preview';
                                    img.loading = 'lazy';
                                    img.decoding = 'async';
                                    previewContainer.insertBefore(img, previewContainer.firstChild);
                                }

                                img.src = e.target.result;

                                if (placeholder) {
                                    placeholder.remove();
                                }

                                const btnSpan = tile.querySelector('.gallery-photo-tile-btn span');
                                if (btnSpan) {
                                    btnSpan.textContent = 'Change Photo';
                                }
                            };
                            reader.readAsDataURL(file);
                        }
                    }
                    return;
                }

                const reviewFileInput = event.target.closest('[data-review-file-input]');
                if (reviewFileInput && reviewFileInput.files && reviewFileInput.files[0]) {
                    const file = reviewFileInput.files[0];
                    if (file.type.startsWith('image/')) {
                        const card = reviewFileInput.closest('[data-review-editor-card]');
                        const reader = new FileReader();
                        reader.onload = (e) => {
                            updateReviewCardThumbnails(card, e.target.result);

                            const coverTag = card?.querySelector('[data-review-cover-tag]');
                            if (coverTag) {
                                coverTag.textContent = 'Custom Cover';
                                coverTag.className = 'review-cover-tag is-custom';
                            }

                            const removeBtn = card?.querySelector('[data-review-remove-thumbnail]');
                            if (removeBtn) {
                                removeBtn.style.display = 'inline-block';
                            }

                            const fileLabel = card?.querySelector('[data-review-file-label]');
                            if (fileLabel) {
                                fileLabel.textContent = 'Change Custom Cover';
                            }

                            const removeHiddenInput = card?.querySelector('[data-review-remove-thumbnail-value]');
                            if (removeHiddenInput) {
                                removeHiddenInput.value = '0';
                            }
                        };
                        reader.readAsDataURL(file);
                    }
                    return;
                }

                const founderFileInput = event.target.closest('[data-leadership-founder-file]');
                if (founderFileInput && founderFileInput.files && founderFileInput.files[0]) {
                    const file = founderFileInput.files[0];
                    if (file.type.startsWith('image/')) {
                        const reader = new FileReader();
                        reader.onload = (e) => {
                            const frame = document.querySelector('[data-leadership-founder-photo-frame]');
                            if (frame) {
                                let img = frame.querySelector('img');
                                const placeholder = frame.querySelector('.leadership-photo-placeholder');
                                if (!img) {
                                    img = document.createElement('img');
                                    img.alt = 'Founder photo preview';
                                    img.loading = 'lazy';
                                    img.decoding = 'async';
                                    frame.insertBefore(img, frame.firstChild);
                                }
                                img.src = e.target.result;
                                if (placeholder) {
                                    placeholder.style.display = 'none';
                                }
                            }
                            const label = document.querySelector('[data-leadership-founder-btn-label]');
                            if (label) {
                                label.textContent = 'Change Photo';
                            }
                        };
                        reader.readAsDataURL(file);
                    }
                    return;
                }

                const memberFileInput = event.target.closest('[data-leadership-member-file-input]');
                if (memberFileInput && memberFileInput.files && memberFileInput.files[0]) {
                    const file = memberFileInput.files[0];
                    if (file.type.startsWith('image/')) {
                        const card = memberFileInput.closest('[data-leadership-member-card]');
                        const reader = new FileReader();
                        reader.onload = (e) => {
                            const tile = card?.querySelector('[data-leadership-editor-avatar-preview]');
                            if (tile) {
                                let img = tile.querySelector('img');
                                const placeholder = tile.querySelector('.leadership-photo-tile-placeholder');
                                if (!img) {
                                    img = document.createElement('img');
                                    img.alt = 'Board member photo preview';
                                    img.loading = 'lazy';
                                    img.decoding = 'async';
                                    tile.insertBefore(img, tile.firstChild);
                                }
                                img.src = e.target.result;
                                if (placeholder) {
                                    placeholder.style.display = 'none';
                                }
                            }

                            const summaryAvatar = card?.querySelector('[data-leadership-summary-avatar]');
                            if (summaryAvatar) {
                                let sImg = summaryAvatar.querySelector('img');
                                const sPlaceholder = summaryAvatar.querySelector('.leadership-avatar-placeholder');
                                if (!sImg) {
                                    sImg = document.createElement('img');
                                    sImg.alt = 'Board member';
                                    sImg.loading = 'lazy';
                                    sImg.decoding = 'async';
                                    summaryAvatar.insertBefore(sImg, summaryAvatar.firstChild);
                                }
                                sImg.src = e.target.result;
                                if (sPlaceholder) {
                                    sPlaceholder.style.display = 'none';
                                }
                            }

                            const btnLabel = card?.querySelector('[data-leadership-photo-btn-label]');
                            if (btnLabel) {
                                btnLabel.textContent = 'Change Photo';
                            }
                        };
                        reader.readAsDataURL(file);
                    }
                    return;
                }
            });

            document.addEventListener('click', async (event) => {
                const leadershipExpandAllBtn = event.target.closest('[data-leadership-expand-all]');
                if (leadershipExpandAllBtn) {
                    document.querySelectorAll('[data-leadership-member-list] [data-leadership-member-card]').forEach((card) => {
                        card.classList.add('is-expanded');
                        card.querySelector('.leadership-member-summary')?.setAttribute('aria-expanded', 'true');
                        const label = card.querySelector('.leadership-btn-toggle-label');
                        if (label) label.textContent = 'Close';
                    });
                    return;
                }

                const leadershipCollapseAllBtn = event.target.closest('[data-leadership-collapse-all]');
                if (leadershipCollapseAllBtn) {
                    document.querySelectorAll('[data-leadership-member-list] [data-leadership-member-card]').forEach((card) => {
                        card.classList.remove('is-expanded');
                        card.querySelector('.leadership-member-summary')?.setAttribute('aria-expanded', 'false');
                        const label = card.querySelector('.leadership-btn-toggle-label');
                        if (label) label.textContent = 'Edit';
                    });
                    return;
                }

                const leadershipToggleBtn = event.target.closest('[data-leadership-member-toggle-button]');
                if (leadershipToggleBtn) {
                    const card = leadershipToggleBtn.closest('[data-leadership-member-card]');
                    if (card) {
                        const isExpanded = card.classList.toggle('is-expanded');
                        const summary = card.querySelector('.leadership-member-summary');
                        if (summary) {
                            summary.setAttribute('aria-expanded', isExpanded ? 'true' : 'false');
                        }
                        const toggleLabel = card.querySelector('.leadership-btn-toggle-label');
                        if (toggleLabel) {
                            toggleLabel.textContent = isExpanded ? 'Close' : 'Edit';
                        }
                    }
                    return;
                }

                const leadershipToggleAccordion = event.target.closest('[data-leadership-toggle-accordion]');
                if (leadershipToggleAccordion && !event.target.closest('button, a, input, label')) {
                    const card = leadershipToggleAccordion.closest('[data-leadership-member-card]');
                    if (card) {
                        const isExpanded = card.classList.toggle('is-expanded');
                        leadershipToggleAccordion.setAttribute('aria-expanded', isExpanded ? 'true' : 'false');
                        const toggleLabel = card.querySelector('.leadership-btn-toggle-label');
                        if (toggleLabel) {
                            toggleLabel.textContent = isExpanded ? 'Close' : 'Edit';
                        }
                    }
                    return;
                }

                const leadershipDoneBtn = event.target.closest('[data-leadership-member-done]');
                if (leadershipDoneBtn) {
                    const card = leadershipDoneBtn.closest('[data-leadership-member-card]');
                    if (card) {
                        card.classList.remove('is-expanded');
                        const summary = card.querySelector('.leadership-member-summary');
                        if (summary) {
                            summary.setAttribute('aria-expanded', 'false');
                        }
                        const toggleLabel = card.querySelector('.leadership-btn-toggle-label');
                        if (toggleLabel) {
                            toggleLabel.textContent = 'Edit';
                        }
                    }
                    return;
                }

                const reviewExpandAllBtn = event.target.closest('[data-review-expand-all]');
                if (reviewExpandAllBtn) {
                    document.querySelectorAll('[data-review-card-list] [data-review-editor-card]').forEach((card) => {
                        card.classList.add('is-expanded');
                        card.querySelector('.review-card-summary')?.setAttribute('aria-expanded', 'true');
                        const label = card.querySelector('.review-btn-toggle-label');
                        if (label) label.textContent = 'Close';
                    });
                    return;
                }

                const reviewCollapseAllBtn = event.target.closest('[data-review-collapse-all]');
                if (reviewCollapseAllBtn) {
                    document.querySelectorAll('[data-review-card-list] [data-review-editor-card]').forEach((card) => {
                        card.classList.remove('is-expanded');
                        card.querySelector('.review-card-summary')?.setAttribute('aria-expanded', 'false');
                        const label = card.querySelector('.review-btn-toggle-label');
                        if (label) label.textContent = 'Edit';
                    });
                    return;
                }

                const reviewSaveDraftBtn = event.target.closest('[data-review-save-draft]');
                if (reviewSaveDraftBtn) {
                    finalizeReviewDraft({ shouldFocus: true });
                    return;
                }

                const reviewToggleBtn = event.target.closest('[data-review-toggle-button]');
                if (reviewToggleBtn) {
                    const card = reviewToggleBtn.closest('[data-review-editor-card]');
                    if (card) {
                        const isExpanded = card.classList.toggle('is-expanded');
                        const summary = card.querySelector('.review-card-summary');
                        if (summary) {
                            summary.setAttribute('aria-expanded', isExpanded ? 'true' : 'false');
                        }
                        const toggleLabel = card.querySelector('.review-btn-toggle-label');
                        if (toggleLabel) {
                            toggleLabel.textContent = isExpanded ? 'Close' : 'Edit';
                        }
                    }
                    return;
                }

                const reviewToggleAccordion = event.target.closest('[data-review-toggle-accordion]');
                if (reviewToggleAccordion && !event.target.closest('button, a, input, label')) {
                    const card = reviewToggleAccordion.closest('[data-review-editor-card]');
                    if (card) {
                        const isExpanded = card.classList.toggle('is-expanded');
                        reviewToggleAccordion.setAttribute('aria-expanded', isExpanded ? 'true' : 'false');
                        const toggleLabel = card.querySelector('.review-btn-toggle-label');
                        if (toggleLabel) {
                            toggleLabel.textContent = isExpanded ? 'Close' : 'Edit';
                        }
                    }
                    return;
                }
                const editButton = event.target.closest('[data-project-toggle-edit]');

                if (editButton) {
                    const card = editButton.closest('[data-project-editor-card]');

                    if (card) {
                        setProjectCardEditing(card, card.dataset.projectEditing !== 'true');
                    }

                    return;
                }

                const officeRemoveButton = event.target.closest('[data-office-remove-card]');

                if (officeRemoveButton) {
                    const confirmed = await (window.adminConfirm?.({
                        title: 'Remove office',
                        message: officeRemoveButton.dataset.confirmMessage || 'Are you sure you want to remove this office card?',
                        confirmLabel: 'Remove',
                        cancelLabel: 'Keep',
                    }) ?? Promise.resolve(window.confirm(officeRemoveButton.dataset.confirmMessage || 'Are you sure you want to remove this office card?')));

                    if (!confirmed) {
                        return;
                    }

                    const card = officeRemoveButton.closest('[data-office-editor-card]');
                    const list = card?.closest('[data-office-card-list]');

                    card?.remove();

                    if (list) {
                        syncOfficeCardLabels(list);
                        markFooterDirty();
                    }

                    return;
                }

                const reviewRemoveButton = event.target.closest('[data-review-remove-card]');

                if (reviewRemoveButton) {
                    const card = reviewRemoveButton.closest('[data-review-editor-card]');
                    const isDraft = card?.dataset.reviewDraft === 'true';
                    const confirmed = await (window.adminConfirm?.({
                        title: isDraft ? 'Discard draft review' : 'Remove review',
                        message: reviewRemoveButton.dataset.confirmMessage || 'Are you sure you want to remove this review?',
                        confirmLabel: isDraft ? 'Discard' : 'Remove',
                        cancelLabel: isDraft ? 'Keep editing' : 'Keep',
                    }) ?? Promise.resolve(window.confirm(reviewRemoveButton.dataset.confirmMessage || 'Are you sure you want to remove this review?')));

                    if (!confirmed) {
                        return;
                    }

                    const list = card?.closest('[data-review-card-list]');
                    const draftHost = card?.closest('[data-review-draft-host]');

                    card?.remove();

                    if (list) {
                        syncReviewCardLabels(list);
                    }

                    if (draftHost) {
                        syncReviewDraftHostVisibility(draftHost);
                    }

                    return;
                }

                const leadershipMemberRemoveButton = event.target.closest('[data-leadership-member-remove-card]');

                if (leadershipMemberRemoveButton) {
                    const confirmed = await (window.adminConfirm?.({
                        title: 'Remove board member',
                        message: leadershipMemberRemoveButton.dataset.confirmMessage || 'Are you sure you want to remove this board member card?',
                        confirmLabel: 'Remove',
                        cancelLabel: 'Keep',
                    }) ?? Promise.resolve(window.confirm(leadershipMemberRemoveButton.dataset.confirmMessage || 'Are you sure you want to remove this board member card?')));

                    if (!confirmed) {
                        return;
                    }

                    const card = leadershipMemberRemoveButton.closest('[data-leadership-member-card]');
                    const list = card?.closest('[data-leadership-member-list]');

                    card?.remove();

                    if (list) {
                        syncLeadershipMemberLabels(list);
                    }

                    return;
                }

                const reviewThumbnailRemoveButton = event.target.closest('[data-review-remove-thumbnail]');

                if (reviewThumbnailRemoveButton) {
                    const card = reviewThumbnailRemoveButton.closest('[data-review-editor-card]');
                    removeReviewThumbnailPreview(card);

                    return;
                }

                const galleryAddImageButton = event.target.closest('[data-gallery-add-image]');

                if (galleryAddImageButton) {
                    createGalleryAlbumImage(galleryAddImageButton.closest('[data-gallery-album-card]'));
                    return;
                }

                const galleryRemoveImageButton = event.target.closest('[data-gallery-remove-image]');

                if (galleryRemoveImageButton) {
                    const confirmed = await (window.adminConfirm?.({
                        title: 'Remove album image',
                        message: galleryRemoveImageButton.dataset.confirmMessage || 'Are you sure you want to remove this album image?',
                        confirmLabel: 'Remove',
                        cancelLabel: 'Keep',
                    }) ?? Promise.resolve(window.confirm(galleryRemoveImageButton.dataset.confirmMessage || 'Are you sure you want to remove this album image?')));

                    if (!confirmed) {
                        return;
                    }

                    const card = galleryRemoveImageButton.closest('[data-gallery-album-image-card]');
                    const list = card?.closest('[data-gallery-album-image-list]');

                    card?.remove();

                    if (list) {
                        syncGalleryAlbumImageLabels(list);
                    }

                    return;
                }

                const galleryRemoveAlbumButton = event.target.closest('[data-gallery-remove-album]');

                if (galleryRemoveAlbumButton) {
                    const confirmed = await (window.adminConfirm?.({
                        title: 'Remove album',
                        message: galleryRemoveAlbumButton.dataset.confirmMessage || 'Are you sure you want to remove this album?',
                        confirmLabel: 'Remove',
                        cancelLabel: 'Keep',
                    }) ?? Promise.resolve(window.confirm(galleryRemoveAlbumButton.dataset.confirmMessage || 'Are you sure you want to remove this album?')));

                    if (!confirmed) {
                        return;
                    }

                    const card = galleryRemoveAlbumButton.closest('[data-gallery-album-card]');
                    const list = card?.closest('[data-gallery-album-list]');

                    card?.remove();

                    if (list) {
                        syncGalleryAlbumLabels(list);
                    }

                    return;
                }

                const removeButton = event.target.closest('[data-project-remove-card]');

                if (!removeButton) {
                    return;
                }

                const confirmed = await (window.adminConfirm?.({
                    title: 'Remove card',
                    message: removeButton.dataset.confirmMessage || 'Are you sure you want to remove this card?',
                    confirmLabel: 'Remove',
                    cancelLabel: 'Keep',
                }) ?? Promise.resolve(window.confirm(removeButton.dataset.confirmMessage || 'Are you sure you want to remove this card?')));

                if (!confirmed) {
                    return;
                }

                const card = removeButton.closest('[data-project-editor-card]');
                const list = card?.closest('[data-project-card-list]');
                const type = list?.dataset.projectCardList;

                card?.remove();

                if (list && type) {
                    syncProjectCardLabels(list, type);
                }
            });

            document.addEventListener('keydown', (event) => {
                if (event.key === 'Enter' || event.key === ' ') {
                    const target = event.target;
                    if (target && target.matches && target.matches('[data-review-toggle-accordion]')) {
                        event.preventDefault();
                        const card = target.closest('[data-review-editor-card]');
                        if (card) {
                            const isExpanded = card.classList.toggle('is-expanded');
                            target.setAttribute('aria-expanded', isExpanded ? 'true' : 'false');
                            const toggleLabel = card.querySelector('.review-btn-toggle-label');
                            if (toggleLabel) {
                                toggleLabel.textContent = isExpanded ? 'Close' : 'Edit';
                            }
                        }
                    }
                }
            });

            document.querySelectorAll('[data-review-form]').forEach((form) => {
                form.addEventListener('submit', (event) => {
                    if (!finalizeReviewDraft({ shouldFocus: true })) {
                        event.preventDefault();
                    }
                });
            });

            if (window.Jodit) {
                const sharedJoditButtons = [
                    'bold',
                    'italic',
                    'underline',
                    '|',
                    'ul',
                    'ol',
                    '|',
                    'font',
                    'fontsize',
                    'brush',
                    'paragraph',
                    '|',
                    'align',
                    '|',
                    'link',
                    'undo',
                    'redo',
                    '|',
                    'eraser',
                    'source'
                ];

                document.addEventListener('keydown', (event) => {
                if (event.key === 'Enter' || event.key === ' ') {
                    const summary = event.target.closest('[data-leadership-toggle-accordion]');
                    if (summary && event.target === summary) {
                        event.preventDefault();
                        const card = summary.closest('[data-leadership-member-card]');
                        if (card) {
                            const isExpanded = card.classList.toggle('is-expanded');
                            summary.setAttribute('aria-expanded', isExpanded ? 'true' : 'false');
                            const toggleLabel = card.querySelector('.leadership-btn-toggle-label');
                            if (toggleLabel) {
                                toggleLabel.textContent = isExpanded ? 'Close' : 'Edit';
                            }
                        }
                    }
                }
            });

            document.querySelectorAll('[data-jodit-editor]').forEach((textarea) => {
                    new Jodit(textarea, {
                        height: 300,
                        toolbarAdaptive: false,
                        showStatusbar: true,
                        useSearch: false,
                        buttons: sharedJoditButtons,
                        buttonsMD: sharedJoditButtons,
                        buttonsSM: sharedJoditButtons,
                        buttonsXS: sharedJoditButtons,
                        showCharsCounter: false,
                        showWordsCounter: false,
                        showXPathInStatusbar: false,
                        askBeforePasteHTML: false,
                        askBeforePasteFromWord: false,
                        toolbarSticky: false,
                        uploader: {
                            insertImageAsBase64URI: false,
                        },
                    });
                });
            }

            // Footer Sub-tab Switcher & Actions
            const footerTabButtons = Array.from(document.querySelectorAll('[data-footer-tab-target]'));
            const footerTabPanels = Array.from(document.querySelectorAll('[data-footer-tab-panel]'));
            const footerForm = document.getElementById('footer-management-form');

            const switchFooterTab = (targetTab) => {
                footerTabButtons.forEach((btn) => {
                    const isActive = btn.dataset.footerTabTarget === targetTab;
                    btn.classList.toggle('is-active', isActive);
                    btn.setAttribute('aria-selected', isActive ? 'true' : 'false');
                });

                footerTabPanels.forEach((panel) => {
                    const isTarget = panel.dataset.footerTabPanel === targetTab;
                    panel.classList.toggle('is-active', isTarget);
                    panel.hidden = !isTarget;
                });

                if (targetTab === 'terms') {
                    setTimeout(() => {
                        window.dispatchEvent(new Event('resize'));
                    }, 50);
                }
            };

            footerTabButtons.forEach((btn) => {
                btn.addEventListener('click', () => {
                    switchFooterTab(btn.dataset.footerTabTarget);
                });
            });

            if (footerForm) {
                footerForm.addEventListener('input', markFooterDirty);
                footerForm.addEventListener('change', markFooterDirty);
                footerForm.addEventListener('submit', () => {
                    const saveBtn = footerForm.querySelector('.footer-save-btn');
                    if (saveBtn) {
                        saveBtn.classList.add('is-loading');
                    }
                });
            }
        });
    </script>
@endpush
