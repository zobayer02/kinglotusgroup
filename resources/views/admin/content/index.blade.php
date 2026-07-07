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
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(148px, 148px));
            justify-content: start;
            gap: 12px;
        }

        .module-button {
            display: inline-flex;
            align-items: center;
            justify-content: center;
            width: 148px;
            min-height: 56px;
            padding: 8px 14px;
            border: 1px solid rgba(164, 186, 214, 0.6);
            border-radius: 16px;
            background: linear-gradient(180deg, rgba(243, 248, 253, 0.92) 0%, rgba(228, 238, 248, 0.84) 100%);
            color: inherit;
            cursor: pointer;
            transition: transform 0.22s ease, border-color 0.22s ease, box-shadow 0.22s ease;
        }

        .module-button:hover,
        .module-button.is-active,
        .module-button[aria-expanded="true"] {
            transform: translateY(-2px);
            border-color: rgba(87, 138, 219, 0.58);
            box-shadow: 0 14px 26px rgba(38, 74, 116, 0.1);
        }

        .module-title {
            margin: 0;
            font-size: 0.92rem;
            font-weight: 700;
            line-height: 1.18;
            text-align: center;
            text-wrap: balance;
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

        .gallery-album-list,
        .gallery-album-image-list {
            display: grid;
            gap: 14px;
        }

        .gallery-album-card {
            gap: 14px;
            padding: 12px;
            border-radius: 18px;
        }

        .gallery-album-image-list {
            grid-template-columns: repeat(auto-fit, minmax(220px, 1fr));
        }

        .gallery-album-image-card {
            gap: 8px;
            padding: 12px;
            border-radius: 18px;
        }

        .gallery-album-card .projects-group {
            gap: 12px;
            padding: 14px;
            border-radius: 20px;
        }

        .gallery-album-card .projects-group-title {
            font-size: 0.94rem;
        }

        .gallery-album-card .projects-group-meta {
            font-size: 0.78rem;
        }

        .gallery-album-image-card .project-editor-card-title {
            font-size: 0.84rem;
        }

        .gallery-album-image-card .project-editor-card-meta {
            font-size: 0.74rem;
        }

        .shareholder-review-list {
            display: grid;
            grid-template-columns: repeat(2, minmax(0, 1fr));
            gap: 18px;
            align-items: start;
        }

        .shareholder-review-draft-host[hidden] {
            display: none;
        }

        .shareholder-review-draft-host {
            margin-bottom: 18px;
        }

        .leadership-founder-grid {
            display: grid;
            grid-template-columns: minmax(0, 0.9fr) minmax(0, 1.1fr);
            gap: 18px;
            align-items: start;
        }

        .leadership-founder-preview,
        .leadership-member-preview {
            width: 180px;
            aspect-ratio: 1 / 1;
            border-radius: 24px;
            margin-left: auto;
            margin-right: auto;
        }

        .leadership-member-list {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(280px, 1fr));
            gap: 18px;
            align-items: start;
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
            .leadership-founder-grid,
            .leadership-member-list,
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
                width: 148px;
                min-height: 56px;
                padding: 8px 14px;
                border-radius: 16px;
            }

            .submit-button {
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
        $valuedShareholders = collect(old('shareholders', $valuedShareholderSection?->shareholdersForEditor() ?? []))
            ->values()
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
                    <span class="module-title">Notice</span>
                </button>

                <button class="module-button {{ $activeModule === 'about' ? 'is-active' : '' }}" type="button" aria-expanded="{{ $activeModule === 'about' ? 'true' : 'false' }}" aria-controls="about-editor-panel" data-module-toggle="about-editor-panel">
                    <span class="module-title">About</span>
                </button>

                <button class="module-button {{ $activeModule === 'projects' ? 'is-active' : '' }}" type="button" aria-expanded="{{ $activeModule === 'projects' ? 'true' : 'false' }}" aria-controls="projects-editor-panel" data-module-toggle="projects-editor-panel">
                    <span class="module-title">Projects</span>
                </button>

                <button class="module-button {{ $activeModule === 'gallery' ? 'is-active' : '' }}" type="button" aria-expanded="{{ $activeModule === 'gallery' ? 'true' : 'false' }}" aria-controls="gallery-editor-panel" data-module-toggle="gallery-editor-panel">
                    <span class="module-title">Gallery</span>
                </button>

                <button class="module-button {{ $activeModule === 'reviews' ? 'is-active' : '' }}" type="button" aria-expanded="{{ $activeModule === 'reviews' ? 'true' : 'false' }}" aria-controls="reviews-editor-panel" data-module-toggle="reviews-editor-panel">
                    <span class="module-title">Reviews</span>
                </button>

                <button class="module-button {{ $activeModule === 'leadership' ? 'is-active' : '' }}" type="button" aria-expanded="{{ $activeModule === 'leadership' ? 'true' : 'false' }}" aria-controls="leadership-editor-panel" data-module-toggle="leadership-editor-panel">
                    <span class="module-title">Leadership</span>
                </button>

                <button class="module-button {{ $activeModule === 'valued-shareholders' ? 'is-active' : '' }}" type="button" aria-expanded="{{ $activeModule === 'valued-shareholders' ? 'true' : 'false' }}" aria-controls="valued-shareholders-editor-panel" data-module-toggle="valued-shareholders-editor-panel">
                    <span class="module-title">Shareholders</span>
                </button>

                <button class="module-button {{ $activeModule === 'footer' ? 'is-active' : '' }}" type="button" aria-expanded="{{ $activeModule === 'footer' ? 'true' : 'false' }}" aria-controls="footer-editor-panel" data-module-toggle="footer-editor-panel">
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

                    <div class="projects-group-head">
                        <div>
                            <h3 class="projects-group-title">Albums</h3>
                            <p class="projects-group-meta">Each album becomes its own section on the gallery page.</p>
                        </div>

                        <button class="project-editor-add" type="button" data-gallery-add-album>Add Album</button>
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
                    <div class="projects-group-head">
                        <div>
                            <h3 class="projects-group-title">Review Cards</h3>
                            <p class="projects-group-meta">Each card uses the shareholder name, a public YouTube video link, and an optional thumbnail.</p>
                        </div>

                        <button class="project-editor-add" type="button" data-review-add>Add Review</button>
                    </div>

                    <div class="shareholder-review-draft-host" data-review-draft-host hidden></div>

                    <div class="editor-actions">
                        <button class="submit-button" type="submit" data-webp-submit>
                            <span class="submit-button-label">Save Reviews</span>
                            <span class="submit-button-loading">
                                <span class="upload-spinner" aria-hidden="true"></span>
                                <span>Converting to WebP...</span>
                            </span>
                        </button>
                    </div>

                    <div class="shareholder-review-list" data-review-card-list data-next-index="{{ count($shareholderReviews) }}">
                        @foreach ($shareholderReviews as $index => $review)
                            @include('admin.content.partials.shareholder-review-card-fields', [
                                'label' => 'Review '.($index + 1),
                                'index' => $index,
                                'review' => $review,
                            ])
                        @endforeach
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

                    <div class="leadership-founder-grid">
                        <div class="project-editor-card">
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

                            <div class="field-group">
                                <label class="field-label" for="founder-description">Founder description</label>
                                <textarea class="field-textarea field-textarea--compact" id="founder-description" name="founder_description" maxlength="200" placeholder="Short founder description shown below the position">{{ old('founder_description', $leadershipSection?->founder_description) }}</textarea>
                                <span class="field-hint">Maximum 200 characters. This text appears below the founder position on the public website.</span>
                                @error('founder_description')
                                    <span class="field-error">{{ $message }}</span>
                                @enderror
                            </div>
                        </div>

                        <div class="project-editor-card">
                            <div class="field-group">
                                <label class="field-label" for="founder-image">Founder image</label>
                                <input class="field-file" id="founder-image" type="file" name="founder_image" accept="image/*" data-webp-input>
                                <span class="field-hint">Any uploaded image will be converted to WebP automatically. Max file size: 6 MB.</span>
                                <span class="upload-status" data-upload-status>
                                    <span class="upload-spinner" aria-hidden="true"></span>
                                    <span data-upload-status-text>Select an image to convert to WebP.</span>
                                </span>
                                @if ($leadershipSection?->founderImageUrl())
                                    <div class="thumbnail-preview leadership-founder-preview">
                                        <img src="{{ $leadershipSection->founderImageUrl() }}" alt="Founder image preview" loading="lazy" decoding="async">
                                    </div>
                                @endif
                                @error('founder_image')
                                    <span class="field-error">{{ $message }}</span>
                                @enderror
                            </div>
                        </div>
                    </div>
                </div>

                <div class="projects-group">
                    <div class="projects-group-head">
                        <div>
                            <h3 class="projects-group-title">Board Member Cards</h3>
                            <p class="projects-group-meta">These cards appear in the animated slider under the founder feature block on the public website.</p>
                        </div>

                        <button class="project-editor-add" type="button" data-leadership-member-add>Add Board Member</button>
                    </div>

                    <div class="leadership-member-list" data-leadership-member-list data-next-index="{{ count($boardMembers) }}">
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
                ])
            </template>
        </article>

        <article class="admin-card editor-panel" id="valued-shareholders-editor-panel" data-module-panel @if($activeModule !== 'valued-shareholders') hidden @endif>
            <div class="editor-header">
                <div class="editor-copy">
                    <p class="section-kicker">Valued Shareholders Editor</p>
                    <h2>Our Valued Shareholders Section</h2>
                    <p class="admin-subtitle">Manage the additional shareholder card slider shown below the Board Members section. Title, cards, and website visibility all update from here.</p>
                </div>

                <span class="editor-status {{ $valuedShareholderSection?->shouldDisplayOnWebsite() ? '' : 'is-hidden' }}">
                    {{ $valuedShareholderSection?->shouldDisplayOnWebsite() ? 'Visible on website' : 'Hidden on website' }}
                </span>
            </div>

            <form class="editor-form" action="{{ route('admin.content.valued-shareholders.update') }}" method="post" enctype="multipart/form-data" data-webp-form>
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

                <div class="projects-group">
                    <div class="projects-group-head">
                        <div>
                            <h3 class="projects-group-title">Shareholder Cards</h3>
                            <p class="projects-group-meta">These cards appear in the animated slider below the Board Members section on the public website.</p>
                        </div>

                        <button class="project-editor-add" type="button" data-shareholder-add>Add Shareholder</button>
                    </div>

                    <div class="leadership-member-list" data-shareholder-card-list data-next-index="{{ count($valuedShareholders) }}">
                        @foreach ($valuedShareholders as $index => $shareholder)
                            @include('admin.content.partials.valued-shareholder-card-fields', [
                                'label' => 'Shareholder '.($index + 1),
                                'index' => $index,
                                'shareholder' => $shareholder,
                            ])
                        @endforeach
                    </div>
                </div>

                <div class="editor-actions">
                    <button class="submit-button" type="submit" data-webp-submit>
                        <span class="submit-button-label">Save Valued Shareholders Section</span>
                        <span class="submit-button-loading">
                            <span class="upload-spinner" aria-hidden="true"></span>
                            <span>Converting to WebP...</span>
                        </span>
                    </button>
                </div>
            </form>

            <template id="valued-shareholder-card-template">
                @include('admin.content.partials.valued-shareholder-card-fields', [
                    'label' => 'Shareholder __NUMBER__',
                    'index' => '__INDEX__',
                    'shareholder' => [
                        'name' => '',
                        'position' => '',
                        'image_path' => '',
                        'image_url' => null,
                    ],
                    'showErrors' => false,
                ])
            </template>
        </article>

        <article class="admin-card editor-panel" id="footer-editor-panel" data-module-panel @if($activeModule !== 'footer') hidden @endif>
                <div class="editor-header">
                <div class="editor-copy">
                    <p class="section-kicker">Footer Editor</p>
                    <h2>Footer, Location, and Legal Content</h2>
                    <p class="admin-subtitle">Set the public footer links, the new location section above the footer, and the Terms and Conditions page content from this module.</p>
                </div>

                <span class="editor-status {{ ($footerSetting?->hasPublicLinks() || $footerSetting?->hasLocationContent() || $footerSetting?->hasOfficeContent() || $footerSetting?->hasTermsContent()) ? '' : 'is-hidden' }}">
                    {{ (($footerSetting?->hasPublicLinks() || $footerSetting?->hasLocationContent() || $footerSetting?->hasOfficeContent() || $footerSetting?->hasTermsContent()) ? 'Ready for website' : 'No footer content yet') }}
                </span>
            </div>

            <form class="editor-form" action="{{ route('admin.content.footer.update') }}" method="post">
                @csrf
                @method('patch')

                <div class="media-grid">
                    <div class="field-group">
                        <label class="field-label" for="footer-youtube-url">YouTube channel link</label>
                        <input class="field-input" id="footer-youtube-url" type="url" name="youtube_url" value="{{ old('youtube_url', $footerSetting?->youtube_url) }}" placeholder="https://www.youtube.com/@yourchannel">
                        @error('youtube_url')
                            <span class="field-error">{{ $message }}</span>
                        @enderror
                    </div>

                    <div class="field-group">
                        <label class="field-label" for="footer-facebook-url">Facebook page link</label>
                        <input class="field-input" id="footer-facebook-url" type="url" name="facebook_url" value="{{ old('facebook_url', $footerSetting?->facebook_url) }}" placeholder="https://www.facebook.com/yourpage">
                        @error('facebook_url')
                            <span class="field-error">{{ $message }}</span>
                        @enderror
                    </div>
                </div>

                <div class="media-grid">
                    <div class="field-group">
                        <label class="field-label" for="footer-contact-email">Contact email</label>
                        <input class="field-input" id="footer-contact-email" type="email" name="contact_email" value="{{ old('contact_email', $footerSetting?->contact_email) }}" placeholder="contact@example.com">
                        <span class="field-hint">Clicking this icon on the public website opens the visitor's default mail app.</span>
                        @error('contact_email')
                            <span class="field-error">{{ $message }}</span>
                        @enderror
                    </div>

                    <div class="field-group">
                        <label class="field-label" for="footer-contact-phone">Contact phone numbers</label>
                        <textarea class="field-textarea field-textarea--compact" id="footer-contact-phone" name="contact_phone" placeholder="+8801700000000&#10;+8801800000000&#10;+8801900000000">{{ old('contact_phone', $footerSetting?->contact_phone) }}</textarea>
                        <span class="field-hint">Add one phone number per line. Clicking the phone icon on the public website will let visitors choose which number to call.</span>
                        @error('contact_phone')
                            <span class="field-error">{{ $message }}</span>
                        @enderror
                    </div>
                </div>

                <div class="editor-actions">
                    <button class="submit-button" type="submit">Save Footer Links</button>
                </div>

                <div class="editor-subsection">
                    <div class="editor-header">
                        <div class="editor-copy">
                            <p class="section-kicker">Location Editor</p>
                            <h2>Location Section Above Footer</h2>
                            <p class="admin-subtitle">This section appears right above the public website footer. Add a strong title, short subtitle, and Google Maps link.</p>
                        </div>

                        <span class="editor-status {{ $footerSetting?->hasLocationContent() ? '' : 'is-hidden' }}">
                            {{ $footerSetting?->hasLocationContent() ? 'Location section ready' : 'No location content yet' }}
                        </span>
                    </div>

                    <div class="field-group">
                        <label class="field-label" for="location-title">Section title</label>
                        <input class="field-input" id="location-title" type="text" name="location_title" value="{{ old('location_title', $footerSetting?->location_title ?: 'Visit Our Location') }}" placeholder="Visit Our Location">
                        @error('location_title')
                            <span class="field-error">{{ $message }}</span>
                        @enderror
                    </div>

                    <div class="field-group">
                        <label class="field-label" for="location-subtitle">Subtitle text</label>
                        <input class="field-input" id="location-subtitle" type="text" name="location_subtitle" value="{{ old('location_subtitle', $footerSetting?->location_subtitle ?: 'Open our Google Maps location to plan your arrival and explore the surrounding destination.') }}" placeholder="Short subtitle shown under the location title">
                        @error('location_subtitle')
                            <span class="field-error">{{ $message }}</span>
                        @enderror
                    </div>

                    <div class="field-group">
                        <label class="field-label" for="location-map-url">Google Maps link</label>
                        <input class="field-input" id="location-map-url" type="url" name="location_map_url" value="{{ old('location_map_url', $footerSetting?->location_map_url ?: \App\Models\FooterSetting::DEFAULT_LOCATION_PLACE_URL) }}" placeholder="https://www.google.com/maps/place/...">
                        <span class="field-hint">Use a public Google Maps share link. The public website will show a button that opens this location in a new tab.</span>
                        @error('location_map_url')
                            <span class="field-error">{{ $message }}</span>
                        @enderror
                    </div>

                    <div class="editor-actions">
                        <button class="submit-button" type="submit">Save Location Section</button>
                    </div>
                </div>

                <div class="editor-subsection">
                    <div class="editor-header">
                        <div class="editor-copy">
                            <p class="section-kicker">Office Editor</p>
                            <h2>Office Section Below Location</h2>
                            <p class="admin-subtitle">This framed public block appears below the location section. Add as many offices as needed with their address and Google Maps link.</p>
                        </div>

                        <span class="editor-status {{ $footerSetting?->hasOfficeContent() ? '' : 'is-hidden' }}">
                            {{ $footerSetting?->hasOfficeContent() ? 'Office section ready' : 'No office content yet' }}
                        </span>
                    </div>

                    <div class="field-group">
                        <label class="field-label" for="office-section-title">Section title</label>
                        <input class="field-input" id="office-section-title" type="text" name="office_section_title" value="{{ old('office_section_title', $footerSetting?->office_section_title ?: \App\Models\FooterSetting::DEFAULT_OFFICE_SECTION_TITLE) }}" placeholder="Get A Quote - No Cost, No Commitment">
                        @error('office_section_title')
                            <span class="field-error">{{ $message }}</span>
                        @enderror
                    </div>

                    <div class="field-group">
                        <label class="field-label" for="office-section-subtitle">Section subtitle</label>
                        <input class="field-input" id="office-section-subtitle" type="text" name="office_section_subtitle" value="{{ old('office_section_subtitle', $footerSetting?->office_section_subtitle ?: \App\Models\FooterSetting::DEFAULT_OFFICE_SECTION_SUBTITLE) }}" placeholder="Transparent & Competitive Rates">
                        @error('office_section_subtitle')
                            <span class="field-error">{{ $message }}</span>
                        @enderror
                    </div>

                    <div class="projects-group-head">
                        <div>
                            <h3 class="projects-group-title">Office Cards</h3>
                            <p class="projects-group-meta">Each office card appears inside the office section on the public website.</p>
                        </div>

                        <button class="project-editor-add" type="button" data-office-add>Add Office</button>
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

                    <div class="editor-actions">
                        <button class="submit-button" type="submit">Save Office Section</button>
                    </div>
                </div>

                <div class="editor-subsection">
                    <div class="editor-header">
                        <div class="editor-copy">
                            <p class="section-kicker">Terms Editor</p>
                            <h2>Terms and Conditions Page</h2>
                            <p class="admin-subtitle">This content appears in the middle of the public Terms and Conditions page between the website navbar and footer.</p>
                        </div>

                        <span class="editor-status {{ $footerSetting?->hasTermsContent() ? '' : 'is-hidden' }}">
                            {{ $footerSetting?->hasTermsContent() ? 'Terms page ready' : 'No terms content yet' }}
                        </span>
                    </div>

                    <div class="field-group">
                        <label class="field-label" for="terms-title">Page title</label>
                        <input class="field-input" id="terms-title" type="text" name="terms_title" value="{{ old('terms_title', $footerSetting?->terms_title) }}" placeholder="Terms and Conditions">
                        @error('terms_title')
                            <span class="field-error">{{ $message }}</span>
                        @enderror
                    </div>

                    <div class="field-group">
                        <label class="field-label" for="terms-subtitle">Subtitle text</label>
                        <input class="field-input" id="terms-subtitle" type="text" name="terms_subtitle" value="{{ old('terms_subtitle', $footerSetting?->terms_subtitle) }}" placeholder="Short subtitle shown below the page title">
                        @error('terms_subtitle')
                            <span class="field-error">{{ $message }}</span>
                        @enderror
                    </div>

                    <div class="field-group">
                        <label class="field-label" for="terms-content">Terms content</label>
                        <textarea class="field-textarea" id="terms-content" name="terms_content" data-jodit-editor placeholder="Write the full terms and conditions content here. Use formatting, headings, and lists as needed.">{{ old('terms_content', trim(collect([$footerSetting?->terms_intro, $footerSetting?->terms_content])->filter()->implode("\n\n"))) }}</textarea>
                        <span class="field-hint">Each paragraph break you add here will be preserved on the public Terms and Conditions page.</span>
                        @error('terms_content')
                            <span class="field-error">{{ $message }}</span>
                        @enderror
                    </div>

                    <div class="editor-actions">
                        <button class="submit-button" type="submit">Save Footer and Terms</button>
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

            const removeReviewThumbnailPreview = (card) => {
                if (!card) {
                    return;
                }

                const removeThumbnailInput = card.querySelector('[data-review-remove-thumbnail-value]');
                const thumbnailPathInput = card.querySelector('[data-review-thumbnail-path]');
                const preview = card.querySelector('[data-review-thumbnail-preview]');
                const fileInput = card.querySelector('[name^="shareholder_review_thumbnails["]');
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

                preview?.remove();

                if (status && statusText) {
                    status.classList.remove('is-visible', 'is-processing');
                    statusText.textContent = 'Thumbnail removed. Save to apply the change.';
                    status.classList.add('is-visible');
                }
            };

            const toggleReviewThumbnailPreview = (card) => {
                const preview = card?.querySelector('[data-review-thumbnail-preview]');
                const button = card?.querySelector('[data-review-toggle-thumbnail]');

                if (!preview || !button) {
                    return;
                }

                const isCollapsed = preview.classList.toggle('is-collapsed');
                button.textContent = isCollapsed
                    ? (button.dataset.showLabel || 'Show Thumbnail')
                    : (button.dataset.hideLabel || 'Hide Thumbnail');
            };

            const bindReviewThumbnailRemoval = (card) => {
                const button = card?.querySelector('[data-review-remove-thumbnail]');

                if (!button || button.dataset.boundReviewThumbnailRemove === 'true') {
                    return;
                }

                button.dataset.boundReviewThumbnailRemove = 'true';
                button.addEventListener('click', () => {
                    removeReviewThumbnailPreview(card);
                });
            };

            const bindReviewThumbnailToggle = (card) => {
                const button = card?.querySelector('[data-review-toggle-thumbnail]');

                if (!button || button.dataset.boundReviewThumbnailToggle === 'true') {
                    return;
                }

                button.dataset.boundReviewThumbnailToggle = 'true';
                button.addEventListener('click', () => {
                    toggleReviewThumbnailPreview(card);
                });
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

            const syncOfficeCardLabels = (list) => {
                if (!list) {
                    return;
                }

                list.querySelectorAll('[data-office-editor-card]').forEach((card, index) => {
                    const title = card.querySelector('.project-editor-card-title');

                    if (title) {
                        title.textContent = `Office ${index + 1}`;
                    }
                });
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
            };

            const syncLeadershipMemberLabels = (list) => {
                if (!list) {
                    return;
                }

                list.querySelectorAll('[data-leadership-member-card]').forEach((card, index) => {
                    const title = card.querySelector('.project-editor-card-title');

                    if (title) {
                        title.textContent = `Board Member ${index + 1}`;
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
                list.querySelectorAll('[data-webp-input]').forEach((input) => bindUploadStatus(input));
                syncLeadershipMemberLabels(list);
            };

            const syncValuedShareholderLabels = (list) => {
                if (!list) {
                    return;
                }

                list.querySelectorAll('[data-shareholder-card]').forEach((card, index) => {
                    const title = card.querySelector('.project-editor-card-title');

                    if (title) {
                        title.textContent = `Shareholder ${index + 1}`;
                    }
                });
            };

            const createValuedShareholderCard = () => {
                const list = document.querySelector('[data-shareholder-card-list]');

                if (!list || !valuedShareholderTemplate) {
                    return;
                }

                const index = Number(list.dataset.nextIndex || list.children.length || 0);
                const number = list.children.length + 1;
                const html = valuedShareholderTemplate.innerHTML
                    .replaceAll('__INDEX__', String(index))
                    .replaceAll('__NUMBER__', String(number));

                list.insertAdjacentHTML('beforeend', html);
                list.dataset.nextIndex = String(index + 1);
                list.querySelectorAll('[data-webp-input]').forEach((input) => bindUploadStatus(input));
                syncValuedShareholderLabels(list);
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

                list.querySelectorAll('[data-review-editor-card]').forEach((card, index) => {
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
                bindReviewThumbnailRemoval(card);
                bindReviewThumbnailToggle(card);
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
                const meta = draftCard.querySelector('.project-editor-card-meta');

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

                if (meta) {
                    meta.textContent = 'This video card appears in the shareholder review section on the public website.';
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

            document.querySelectorAll('[data-shareholder-add]').forEach((button) => {
                button.addEventListener('click', () => {
                    createValuedShareholderCard();
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

            document.querySelectorAll('[data-shareholder-card-list]').forEach((list) => {
                syncValuedShareholderLabels(list);
            });

            document.addEventListener('click', async (event) => {
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

                const shareholderRemoveButton = event.target.closest('[data-shareholder-remove-card]');

                if (shareholderRemoveButton) {
                    const confirmed = await (window.adminConfirm?.({
                        title: 'Remove shareholder card',
                        message: shareholderRemoveButton.dataset.confirmMessage || 'Are you sure you want to remove this shareholder card?',
                        confirmLabel: 'Remove',
                        cancelLabel: 'Keep',
                    }) ?? Promise.resolve(window.confirm(shareholderRemoveButton.dataset.confirmMessage || 'Are you sure you want to remove this shareholder card?')));

                    if (!confirmed) {
                        return;
                    }

                    const card = shareholderRemoveButton.closest('[data-shareholder-card]');
                    const list = card?.closest('[data-shareholder-card-list]');

                    card?.remove();

                    if (list) {
                        syncValuedShareholderLabels(list);
                    }

                    return;
                }

                const reviewSaveDraftButton = event.target.closest('[data-review-save-draft]');

                if (reviewSaveDraftButton) {
                    finalizeReviewDraft();

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
        });
    </script>
@endpush
