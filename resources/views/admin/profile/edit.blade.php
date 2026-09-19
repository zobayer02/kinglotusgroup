@extends('admin.layouts.app')

@section('title', 'Profile & Security | King Lotus International')

@push('styles')
    <style>
        /* ==========================================================================
           PROFILE & SECURITY HERO SHOWCASE
           ========================================================================== */
        .profile-hero-card {
            display: flex;
            align-items: center;
            justify-content: space-between;
            gap: 20px;
            padding: 22px 26px;
            border-radius: 24px;
            margin-bottom: 18px;
            background: linear-gradient(135deg, rgba(255, 255, 255, 0.88) 0%, rgba(243, 248, 253, 0.78) 100%);
            border: 1px solid rgba(255, 255, 255, 0.7);
            box-shadow: 0 12px 28px rgba(24, 43, 56, 0.06);
            backdrop-filter: blur(20px);
            -webkit-backdrop-filter: blur(20px);
        }

        .profile-hero-inner {
            display: flex;
            align-items: center;
            gap: 20px;
        }

        .profile-hero-avatar-wrap {
            position: relative;
            flex-shrink: 0;
        }

        .profile-hero-avatar {
            display: flex;
            align-items: center;
            justify-content: center;
            width: 72px;
            height: 72px;
            border-radius: 22px;
            background: linear-gradient(135deg, #1e40af 0%, #3b82f6 100%);
            color: #ffffff;
            font-size: 1.65rem;
            font-weight: 800;
            letter-spacing: -0.02em;
            box-shadow: 0 10px 22px rgba(37, 99, 235, 0.28);
        }

        .profile-online-badge {
            position: absolute;
            bottom: -2px;
            right: -2px;
            width: 18px;
            height: 18px;
            border-radius: 999px;
            background: #10b981;
            border: 3px solid #ffffff;
            box-shadow: 0 2px 6px rgba(16, 185, 129, 0.4);
        }

        .profile-hero-copy {
            display: grid;
            gap: 6px;
        }

        .profile-hero-role-row {
            display: flex;
            align-items: center;
            gap: 8px;
            flex-wrap: wrap;
        }

        .profile-hero-kicker {
            font-size: 0.74rem;
            font-weight: 800;
            text-transform: uppercase;
            letter-spacing: 0.08em;
            color: #2563eb;
            margin: 0;
        }

        .profile-role-pill {
            display: inline-flex;
            align-items: center;
            gap: 5px;
            padding: 3px 9px;
            border-radius: 8px;
            background: rgba(37, 99, 235, 0.1);
            color: #1d4ed8;
            font-size: 0.74rem;
            font-weight: 700;
            letter-spacing: 0.02em;
        }

        .profile-status-pill {
            display: inline-flex;
            align-items: center;
            gap: 6px;
            padding: 3px 9px;
            border-radius: 8px;
            background: rgba(16, 185, 129, 0.1);
            color: #059669;
            font-size: 0.74rem;
            font-weight: 700;
        }

        .pulse-dot {
            width: 6px;
            height: 6px;
            border-radius: 999px;
            background: #10b981;
            box-shadow: 0 0 0 2px rgba(16, 185, 129, 0.25);
            animation: pulseSession 2s infinite ease-in-out;
        }

        @keyframes pulseSession {
            0%, 100% { transform: scale(1); opacity: 1; }
            50% { transform: scale(1.3); opacity: 0.7; }
        }

        .profile-hero-title {
            margin: 0;
            font-size: 1.55rem;
            font-weight: 800;
            letter-spacing: -0.02em;
            color: var(--ink-900);
            line-height: 1.15;
        }

        .profile-hero-meta {
            display: flex;
            align-items: center;
            gap: 10px;
            flex-wrap: wrap;
            font-size: 0.84rem;
            color: var(--ink-700);
        }

        .profile-meta-item {
            display: inline-flex;
            align-items: center;
            gap: 6px;
        }

        .profile-meta-separator {
            color: rgba(15, 31, 40, 0.3);
        }

        /* ==========================================================================
           ALERT BANNER
           ========================================================================== */
        .profile-alert-banner {
            display: flex;
            align-items: center;
            gap: 12px;
            padding: 12px 18px;
            border-radius: 16px;
            background: linear-gradient(135deg, rgba(236, 253, 245, 0.95) 0%, rgba(209, 250, 229, 0.9) 100%);
            border: 1px solid rgba(16, 185, 129, 0.35);
            color: #065f46;
            margin-bottom: 18px;
            box-shadow: 0 4px 14px rgba(16, 185, 129, 0.08);
            animation: slideDown 0.25s ease-out;
        }

        @keyframes slideDown {
            from { opacity: 0; transform: translateY(-8px); }
            to { opacity: 1; transform: translateY(0); }
        }

        .profile-alert-icon {
            display: flex;
            align-items: center;
            justify-content: center;
            width: 28px;
            height: 28px;
            border-radius: 999px;
            background: #10b981;
            color: #ffffff;
            flex-shrink: 0;
        }

        .profile-alert-content {
            flex: 1;
        }

        .profile-alert-text {
            margin: 0;
            font-size: 0.88rem;
            font-weight: 600;
        }

        .profile-alert-close {
            display: flex;
            align-items: center;
            justify-content: center;
            width: 24px;
            height: 24px;
            border-radius: 6px;
            border: none;
            background: transparent;
            color: #065f46;
            cursor: pointer;
            opacity: 0.6;
            transition: opacity 0.15s ease;
        }

        .profile-alert-close:hover {
            opacity: 1;
        }

        /* ==========================================================================
           SETTINGS TWO-COLUMN GRID & CARDS
           ========================================================================== */
        .settings-grid {
            display: grid;
            gap: 18px;
            grid-template-columns: minmax(0, 1fr) minmax(0, 1fr);
            align-items: start;
        }

        .settings-card {
            display: grid;
            gap: 16px;
            align-content: start;
            padding: 22px;
            border-radius: 22px;
            background: #ffffff;
            border: 1px solid rgba(226, 232, 240, 0.85);
            box-shadow: 0 10px 26px rgba(15, 23, 42, 0.04);
            position: relative;
            overflow: hidden;
            transition: border-color 0.2s ease, box-shadow 0.2s ease;
        }

        .settings-card:hover {
            border-color: rgba(147, 197, 253, 0.6);
            box-shadow: 0 14px 32px rgba(37, 99, 235, 0.06);
        }

        .settings-card-head {
            display: flex;
            align-items: flex-start;
            gap: 14px;
            padding-bottom: 14px;
            border-bottom: 1px solid rgba(241, 245, 249, 1);
        }

        .settings-icon-badge {
            display: flex;
            align-items: center;
            justify-content: center;
            width: 42px;
            height: 42px;
            border-radius: 12px;
            background: rgba(37, 99, 235, 0.08);
            color: #2563eb;
            flex-shrink: 0;
        }

        .settings-icon-badge--security {
            background: rgba(16, 185, 129, 0.1);
            color: #059669;
        }

        .settings-head-copy {
            display: grid;
            gap: 4px;
        }

        .settings-head-copy h2 {
            margin: 0;
            font-size: 1.15rem;
            font-weight: 800;
            color: var(--ink-900);
            letter-spacing: -0.01em;
        }

        .settings-head-copy .section-kicker {
            font-size: 0.72rem;
            font-weight: 800;
            text-transform: uppercase;
            letter-spacing: 0.08em;
            color: #3b82f6;
            margin: 0;
        }

        .form-stack {
            display: grid;
            gap: 14px;
        }

        .field-group {
            display: grid;
            gap: 6px;
        }

        .field-label-row {
            display: flex;
            align-items: center;
            justify-content: space-between;
            gap: 8px;
        }

        .field-label {
            font-size: 0.84rem;
            font-weight: 700;
            color: var(--ink-900);
        }

        .field-badge-lock {
            display: inline-flex;
            align-items: center;
            gap: 4px;
            padding: 2px 7px;
            border-radius: 6px;
            background: #f1f5f9;
            color: #64748b;
            font-size: 0.72rem;
            font-weight: 700;
            cursor: help;
        }

        .field-badge-verified {
            display: inline-flex;
            align-items: center;
            gap: 4px;
            padding: 2px 7px;
            border-radius: 6px;
            background: #eff6ff;
            color: #1e40af;
            font-size: 0.72rem;
            font-weight: 700;
        }

        /* ==========================================================================
           INPUTS & ICON PREFIXES
           ========================================================================== */
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

        .field-input {
            width: 100%;
            min-height: 48px;
            padding: 0 14px;
            border: 1px solid rgba(175, 191, 207, 0.62);
            border-radius: 14px;
            background: #ffffff;
            color: var(--ink-900);
            font-size: 0.92rem;
            outline: none;
            transition: all 0.2s ease;
        }

        .field-input--with-icon {
            padding-left: 44px !important;
        }

        .field-input:focus {
            border-color: rgba(47, 111, 219, 0.6);
            box-shadow: 0 0 0 4px rgba(47, 111, 219, 0.12);
            transform: translateY(-1px);
        }

        .field-input--readonly {
            background: #f8fafc !important;
            color: #475569 !important;
            border-color: rgba(203, 213, 225, 0.8) !important;
            cursor: not-allowed;
        }

        .field-hint {
            font-size: 0.78rem;
            color: #64748b;
            line-height: 1.4;
            margin-top: 1px;
        }

        .field-error {
            font-size: 0.8rem;
            color: #ef4444;
            font-weight: 600;
        }

        .field-help {
            margin: 0;
            font-size: 0.82rem;
            color: #64748b;
            line-height: 1.5;
        }

        /* Password input specific */
        .password-field .field-input {
            padding-right: 48px;
        }

        .password-toggle {
            position: absolute;
            top: 50%;
            right: 10px;
            display: inline-flex;
            align-items: center;
            justify-content: center;
            width: 32px;
            height: 32px;
            border: none;
            border-radius: 8px;
            background: transparent;
            color: #64748b;
            cursor: pointer;
            transform: translateY(-50%);
            transition: all 0.18s ease;
            z-index: 2;
        }

        .password-toggle:hover {
            background: rgba(37, 99, 235, 0.08);
            color: #2563eb;
        }

        .password-toggle .icon-eye-open {
            display: none;
        }

        .password-toggle[data-state="visible"] .icon-eye-open {
            display: block;
        }

        .password-toggle[data-state="visible"] .icon-eye-closed {
            display: none;
        }

        /* ==========================================================================
           PASSWORD STRENGTH METER & CRITERIA
           ========================================================================== */
        .password-strength-bar-wrap {
            display: none;
            flex-direction: column;
            gap: 4px;
            margin-top: 4px;
        }

        .password-strength-bar-wrap.is-visible {
            display: flex;
        }

        .password-strength-track {
            height: 5px;
            width: 100%;
            background: #e2e8f0;
            border-radius: 999px;
            overflow: hidden;
        }

        .password-strength-fill {
            height: 100%;
            width: 0%;
            border-radius: 999px;
            transition: width 0.3s ease, background-color 0.3s ease;
        }

        .password-strength-text {
            font-size: 0.74rem;
            font-weight: 700;
            color: #64748b;
        }

        .password-criteria-wrapper {
            display: grid;
            grid-template-rows: 0fr;
            opacity: 0;
            margin-top: 0;
            transition: grid-template-rows 0.35s cubic-bezier(0.16, 1, 0.3, 1),
                        opacity 0.25s ease,
                        margin-top 0.35s cubic-bezier(0.16, 1, 0.3, 1);
            pointer-events: none;
        }

        .password-criteria-wrapper.is-visible {
            grid-template-rows: 1fr;
            opacity: 1;
            margin-top: 6px;
            pointer-events: auto;
        }

        .password-criteria-inner {
            overflow: hidden;
        }

        .password-criteria-box {
            padding: 12px 14px;
            background: #f8fafc;
            border-radius: 12px;
            border: 1px solid rgba(203, 213, 225, 0.8);
            transform: translateY(-6px);
            transition: transform 0.35s cubic-bezier(0.16, 1, 0.3, 1);
        }

        .password-criteria-wrapper.is-visible .password-criteria-box {
            transform: translateY(0);
        }

        .password-criteria-title {
            margin: 0 0 6px;
            font-size: 0.74rem;
            font-weight: 800;
            color: #475569;
            text-transform: uppercase;
            letter-spacing: 0.05em;
        }

        .criteria-list {
            margin: 0;
            padding: 0;
            list-style: none;
            display: grid;
            gap: 4px;
            font-size: 0.8rem;
        }

        .criteria-list li {
            display: flex;
            align-items: center;
            gap: 6px;
            color: #64748b;
            transition: color 0.2s ease, font-weight 0.2s ease;
        }

        .criteria-list li .rule-icon {
            display: inline-flex;
            align-items: center;
            justify-content: center;
            width: 14px;
            height: 14px;
            font-weight: 800;
            font-size: 0.82rem;
        }

        .criteria-list li.is-passed {
            color: #10b981;
            font-weight: 700;
        }

        .criteria-list li.is-passed .rule-icon {
            color: #10b981;
        }

        /* ==========================================================================
           SUBMIT & SECONDARY BUTTONS
           ========================================================================== */
        .profile-submit-btn {
            display: inline-flex;
            align-items: center;
            justify-content: center;
            min-height: 46px;
            padding: 0 20px;
            border: none;
            border-radius: 14px;
            background: linear-gradient(135deg, #1e3a8a 0%, #2563eb 100%);
            color: #ffffff;
            font-size: 0.88rem;
            font-weight: 700;
            cursor: pointer;
            box-shadow: 0 4px 14px rgba(37, 99, 235, 0.25);
            transition: all 0.2s cubic-bezier(0.4, 0, 0.2, 1);
            margin-top: 4px;
        }

        .profile-submit-btn:hover {
            transform: translateY(-1px);
            box-shadow: 0 8px 20px rgba(37, 99, 235, 0.35);
        }

        .profile-submit-btn--security {
            background: linear-gradient(135deg, #065f46 0%, #059669 100%);
            box-shadow: 0 4px 14px rgba(5, 150, 105, 0.25);
        }

        .profile-submit-btn--security:hover {
            box-shadow: 0 8px 20px rgba(5, 150, 105, 0.35);
        }

        .submit-button-label,
        .submit-button-loading {
            display: inline-flex;
            align-items: center;
            gap: 8px;
        }

        .submit-button-loading {
            display: none;
        }

        .profile-submit-btn.is-loading .submit-button-label {
            display: none;
        }

        .profile-submit-btn.is-loading .submit-button-loading {
            display: inline-flex;
        }

        .upload-spinner {
            width: 16px;
            height: 16px;
            border: 2px solid rgba(255, 255, 255, 0.3);
            border-top-color: #ffffff;
            border-radius: 999px;
            animation: spin 0.7s linear infinite;
        }

        @keyframes spin {
            to { transform: rotate(360deg); }
        }

        /* ==========================================================================
           DEVICE & SESSION CONTROL CARD
           ========================================================================== */
        .session-tools-card {
            display: grid;
            gap: 10px;
            margin-top: 8px;
            padding: 16px;
            border-radius: 16px;
            background: linear-gradient(180deg, #f8fafc 0%, #f1f5f9 100%);
            border: 1px solid rgba(203, 213, 225, 0.9);
        }

        .session-tools-head {
            display: flex;
            align-items: center;
            justify-content: space-between;
            gap: 10px;
        }

        .session-tools-title-group {
            display: flex;
            align-items: center;
            gap: 7px;
            font-size: 0.84rem;
            font-weight: 700;
            color: #1e293b;
        }

        .session-badge-current {
            display: inline-flex;
            align-items: center;
            gap: 5px;
            padding: 2px 8px;
            border-radius: 6px;
            background: rgba(16, 185, 129, 0.12);
            color: #059669;
            font-size: 0.72rem;
            font-weight: 700;
        }

        .session-dot {
            width: 6px;
            height: 6px;
            border-radius: 999px;
            background: #10b981;
        }

        .session-tools-note {
            margin: 0;
            font-size: 0.8rem;
            color: #64748b;
            line-height: 1.45;
        }

        .logout-devices-btn {
            display: inline-flex;
            align-items: center;
            gap: 8px;
            min-height: 38px;
            padding: 0 14px;
            border: 1px solid rgba(239, 68, 68, 0.3);
            border-radius: 10px;
            background: #ffffff;
            color: #dc2626;
            font-size: 0.82rem;
            font-weight: 700;
            cursor: pointer;
            transition: all 0.18s ease;
        }

        .logout-devices-btn:hover {
            background: #fef2f2;
            border-color: #ef4444;
            transform: translateY(-1px);
        }

        /* ==========================================================================
           RESPONSIVE ADJUSTMENTS
           ========================================================================== */
        @media (max-width: 960px) {
            .settings-grid {
                grid-template-columns: 1fr;
            }

            .profile-hero-card {
                flex-direction: column;
                align-items: flex-start;
            }
        }

        @media (max-width: 640px) {
            .profile-hero-card {
                padding: 16px;
            }

            .profile-hero-inner {
                flex-direction: column;
                align-items: flex-start;
                gap: 12px;
            }

            .settings-card {
                padding: 16px;
            }

            .profile-submit-btn {
                width: 100%;
            }

            .logout-devices-btn {
                width: 100%;
                justify-content: center;
            }
        }
    </style>
@endpush

@section('content')
    <!-- Profile Hero Showcase -->
    <header class="profile-hero-card">
        <div class="profile-hero-inner">
            <div class="profile-hero-avatar-wrap">
                <div class="profile-hero-avatar">{{ $admin->displayInitials() }}</div>
                <span class="profile-online-badge" title="Active Admin Session"></span>
            </div>
            <div class="profile-hero-copy">
                <div class="profile-hero-role-row">
                    <span class="profile-hero-kicker">Account Settings</span>
                    <span class="profile-role-pill">
                        <svg width="12" height="12" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.2"><path d="M12 2l3.09 6.26L22 9.27l-5 4.87 1.18 6.88L12 17.77l-6.18 3.25L7 14.14 2 9.27l6.91-1.01L12 2z"></path></svg>
                        <span>{{ $admin->name }}</span>
                    </span>
                    <span class="profile-status-pill">
                        <span class="pulse-dot"></span>
                        <span>Active Session</span>
                    </span>
                </div>
                <h1 class="profile-hero-title">{{ $admin->displayName() }}</h1>
                <div class="profile-hero-meta">
                    <span class="profile-meta-item">
                        <svg width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M4 4h16c1.1 0 2 .9 2 2v12c0 1.1-.9 2-2 2H4c-1.1 0-2-.9-2-2V6c0-1.1.9-2 2-2z"></path><polyline points="22,6 12,13 2,6"></polyline></svg>
                        <span>{{ $admin->email }}</span>
                    </span>
                    <span class="profile-meta-separator">•</span>
                    <span class="profile-meta-item">
                        <svg width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M12 22s8-4 8-10V5l-8-3-8 3v7c0 6 8 10 8 10z"></path></svg>
                        <span>Security Protected</span>
                    </span>
                </div>
            </div>
        </div>
    </header>

    <!-- Flash Status Alert Banner -->
    @if (session('success') || session('status'))
        <div class="profile-alert-banner" role="alert">
            <div class="profile-alert-icon">
                <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5"><polyline points="20 6 9 17 4 12"></polyline></svg>
            </div>
            <div class="profile-alert-content">
                <p class="profile-alert-text">{{ session('success') ?: session('status') }}</p>
            </div>
            <button type="button" class="profile-alert-close" onclick="this.closest('.profile-alert-banner').remove()" aria-label="Close alert">
                <svg width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><line x1="18" y1="6" x2="6" y2="18"></line><line x1="6" y1="6" x2="18" y2="18"></line></svg>
            </button>
        </div>
    @endif

    <section class="settings-grid">
        <!-- Card 1: Basic Information -->
        <article class="admin-card settings-card">
            <div class="settings-card-head">
                <div class="settings-icon-badge">
                    <svg width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M20 21v-2a4 4 0 0 0-4-4H8a4 4 0 0 0-4 4v2"></path><circle cx="12" cy="7" r="4"></circle></svg>
                </div>
                <div class="settings-head-copy">
                    <p class="section-kicker">Personal Details</p>
                    <h2>Basic Information</h2>
                    <p class="field-help">Update your Super Admin profile details including name, email address, designation, and mobile number.</p>
                </div>
            </div>

            <form class="form-stack" action="{{ route('admin.profile.update') }}" method="post">
                @csrf
                @method('patch')

                <div class="field-group">
                    <label class="field-label" for="full_name">Full Name</label>
                    <div class="input-icon-group">
                        <span class="input-icon">
                            <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M20 21v-2a4 4 0 0 0-4-4H8a4 4 0 0 0-4 4v2"></path><circle cx="12" cy="7" r="4"></circle></svg>
                        </span>
                        <input class="field-input field-input--with-icon" id="full_name" type="text" name="full_name" value="{{ old('full_name', $admin->full_name) }}" placeholder="Enter full name" required>
                    </div>
                    @error('full_name')
                        <span class="field-error">{{ $message }}</span>
                    @enderror
                </div>

                <div class="field-group">
                    <label class="field-label" for="position">Position / Designation</label>
                    <div class="input-icon-group">
                        <span class="input-icon">
                            <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><rect x="2" y="7" width="20" height="14" rx="2" ry="2"></rect><path d="M16 21V5a2 2 0 0 0-2-2h-4a2 2 0 0 0-2 2v16"></path></svg>
                        </span>
                        <input class="field-input field-input--with-icon" id="position" type="text" name="name" value="{{ old('name', $admin->name) }}" placeholder="e.g. Super Admin">
                    </div>
                    @error('name')
                        <span class="field-error">{{ $message }}</span>
                    @enderror
                    <span class="field-hint">Your administrative title or designation displayed across the portal.</span>
                </div>

                <div class="field-group">
                    <label class="field-label" for="email">Email Address</label>
                    <div class="input-icon-group">
                        <span class="input-icon">
                            <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M4 4h16c1.1 0 2 .9 2 2v12c0 1.1-.9 2-2 2H4c-1.1 0-2-.9-2-2V6c0-1.1.9-2 2-2z"></path><polyline points="22,6 12,13 2,6"></polyline></svg>
                        </span>
                        <input class="field-input field-input--with-icon" id="email" type="email" name="email" value="{{ old('email', $admin->email) }}" placeholder="e.g. superadmin@kinglotusgroup.com" required>
                    </div>
                    @error('email')
                        <span class="field-error">{{ $message }}</span>
                    @enderror
                    <span class="field-hint">Primary email address used for signing into the portal and receiving admin notifications.</span>
                </div>

                <div class="field-group">
                    <label class="field-label" for="mobile">Mobile Number</label>
                    <div class="input-icon-group">
                        <span class="input-icon">
                            <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M22 16.92v3a2 2 0 0 1-2.18 2 19.79 19.79 0 0 1-8.63-3.07 19.5 19.5 0 0 1-6-6 19.79 19.79 0 0 1-3.07-8.67A2 2 0 0 1 4.11 2h3a2 2 0 0 1 2 1.72 12.84 12.84 0 0 0 .7 2.81 2 2 0 0 1-.45 2.11L8.09 9.91a16 16 0 0 0 6 6l1.27-1.27a2 2 0 0 1 2.11-.45 12.84 12.84 0 0 0 2.81.7A2 2 0 0 1 22 16.92z"></path></svg>
                        </span>
                        <input class="field-input field-input--with-icon" id="mobile" type="text" name="mobile" value="{{ old('mobile', $admin->mobile) }}" placeholder="e.g. 01700000000">
                    </div>
                    @error('mobile')
                        <span class="field-error">{{ $message }}</span>
                    @enderror
                </div>

                <button class="submit-button profile-submit-btn" type="submit">
                    <span class="submit-button-label">
                        <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M19 21H5a2 2 0 0 1-2-2V5a2 2 0 0 1 2-2h11l5 5v11a2 2 0 0 1-2 2z"></path><polyline points="17 21 17 13 7 13 7 21"></polyline><polyline points="7 3 7 8 15 8"></polyline></svg>
                        <span>Save Profile Changes</span>
                    </span>
                    <span class="submit-button-loading">
                        <span class="upload-spinner"></span>
                        <span>Saving...</span>
                    </span>
                </button>
            </form>
        </article>

        <!-- Card 2: Security & Password Management -->
        <article class="admin-card settings-card">
            <div class="settings-card-head">
                <div class="settings-icon-badge settings-icon-badge--security">
                    <svg width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M12 22s8-4 8-10V5l-8-3-8 3v7c0 6 8 10 8 10z"></path></svg>
                </div>
                <div class="settings-head-copy">
                    <p class="section-kicker">Access Protection</p>
                    <h2>Change Password</h2>
                    <p class="field-help">Use your current password before setting a new strong password.</p>
                </div>
            </div>

            <form class="form-stack" action="{{ route('admin.profile.password.update') }}" method="post">
                @csrf
                @method('put')

                <div class="field-group">
                    <label class="field-label" for="current_password">Current Password</label>
                    <div class="input-icon-group password-field">
                        <span class="input-icon">
                            <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><rect x="3" y="11" width="18" height="11" rx="2" ry="2"></rect><path d="M7 11V7a5 5 0 0 1 10 0v4"></path></svg>
                        </span>
                        <input class="field-input field-input--with-icon" id="current_password" type="password" name="current_password" placeholder="Enter current password" required autocomplete="current-password">
                        <button class="password-toggle" type="button" aria-label="Show password" data-password-toggle data-target="current_password" data-state="hidden">
                            <svg class="icon-eye-open" width="18" height="18" viewBox="0 0 24 24" fill="none" aria-hidden="true"><path d="M2 12C4.5 7.8 8 5.7 12 5.7C16 5.7 19.5 7.8 22 12C19.5 16.2 16 18.3 12 18.3C8 18.3 4.5 16.2 2 12Z" stroke="currentColor" stroke-width="1.8" stroke-linejoin="round"></path><circle cx="12" cy="12" r="3.2" stroke="currentColor" stroke-width="1.8"></circle></svg>
                            <svg class="icon-eye-closed" width="18" height="18" viewBox="0 0 24 24" fill="none" aria-hidden="true"><path d="M3 3L21 21" stroke="currentColor" stroke-width="1.8" stroke-linecap="round"></path><path d="M10.58 10.58C10.21 10.95 10 11.46 10 12C10 13.1 10.9 14 12 14C12.54 14 13.05 13.79 13.42 13.42" stroke="currentColor" stroke-width="1.8" stroke-linecap="round"></path><path d="M6.72 6.72C4.76 8.02 3.15 9.82 2 12C4.5 16.2 8 18.3 12 18.3C13.75 18.3 15.41 17.9 16.92 17.14" stroke="currentColor" stroke-width="1.8" stroke-linecap="round" stroke-linejoin="round"></path><path d="M9.12 5.97C10.04 5.79 11 5.7 12 5.7C16 5.7 19.5 7.8 22 12C21.27 13.22 20.47 14.29 19.58 15.23" stroke="currentColor" stroke-width="1.8" stroke-linecap="round" stroke-linejoin="round"></path></svg>
                        </button>
                    </div>
                    @error('current_password')
                        <span class="field-error">{{ $message }}</span>
                    @enderror
                </div>

                <div class="field-group">
                    <label class="field-label" for="password">New Password</label>
                    <div class="input-icon-group password-field">
                        <span class="input-icon">
                            <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M21 2l-2 2m-1.5 1.5L14 9l-1.5-1.5-3 3 1.5 1.5-4 4L2 22l6-5 4 4 1.5-1.5 3 3 1.5-1.5 3.5-3.5"></path><circle cx="15.5" cy="8.5" r="2.5"></circle></svg>
                        </span>
                        <input class="field-input field-input--with-icon" id="password" type="password" name="password" placeholder="Create a strong new password" required autocomplete="new-password">
                        <button class="password-toggle" type="button" aria-label="Show password" data-password-toggle data-target="password" data-state="hidden">
                            <svg class="icon-eye-open" width="18" height="18" viewBox="0 0 24 24" fill="none" aria-hidden="true"><path d="M2 12C4.5 7.8 8 5.7 12 5.7C16 5.7 19.5 7.8 22 12C19.5 16.2 16 18.3 12 18.3C8 18.3 4.5 16.2 2 12Z" stroke="currentColor" stroke-width="1.8" stroke-linejoin="round"></path><circle cx="12" cy="12" r="3.2" stroke="currentColor" stroke-width="1.8"></circle></svg>
                            <svg class="icon-eye-closed" width="18" height="18" viewBox="0 0 24 24" fill="none" aria-hidden="true"><path d="M3 3L21 21" stroke="currentColor" stroke-width="1.8" stroke-linecap="round"></path><path d="M10.58 10.58C10.21 10.95 10 11.46 10 12C10 13.1 10.9 14 12 14C12.54 14 13.05 13.79 13.42 13.42" stroke="currentColor" stroke-width="1.8" stroke-linecap="round"></path><path d="M6.72 6.72C4.76 8.02 3.15 9.82 2 12C4.5 16.2 8 18.3 12 18.3C13.75 18.3 15.41 17.9 16.92 17.14" stroke="currentColor" stroke-width="1.8" stroke-linecap="round" stroke-linejoin="round"></path><path d="M9.12 5.97C10.04 5.79 11 5.7 12 5.7C16 5.7 19.5 7.8 22 12C21.27 13.22 20.47 14.29 19.58 15.23" stroke="currentColor" stroke-width="1.8" stroke-linecap="round" stroke-linejoin="round"></path></svg>
                        </button>
                    </div>

                    <!-- Password Strength Meter -->
                    <div class="password-strength-bar-wrap" id="password-strength-bar-wrap">
                        <div class="password-strength-track">
                            <div class="password-strength-fill" id="password-strength-fill"></div>
                        </div>
                        <span class="password-strength-text" id="password-strength-text">Password Strength</span>
                    </div>

                    @if ($errors->has('password'))
                        <ul class="field-error-list" style="margin: 6px 0 0; padding-left: 18px; color: #ef4444; font-size: 0.84rem; line-height: 1.4;">
                            @foreach ($errors->get('password') as $error)
                                <li>{{ $error }}</li>
                            @endforeach
                        </ul>
                    @endif

                    <div class="password-criteria-wrapper" id="password-criteria-wrapper">
                        <div class="password-criteria-inner">
                            <div class="password-criteria-box">
                                <p class="password-criteria-title">Password Requirements:</p>
                                <ul class="criteria-list">
                                    <li data-rule="length"><span class="rule-icon">○</span><span>At least 8 characters</span></li>
                                    <li data-rule="upper"><span class="rule-icon">○</span><span>At least one uppercase letter (A-Z)</span></li>
                                    <li data-rule="lower"><span class="rule-icon">○</span><span>At least one lowercase letter (a-z)</span></li>
                                    <li data-rule="number"><span class="rule-icon">○</span><span>At least one number (0-9)</span></li>
                                    <li data-rule="symbol"><span class="rule-icon">○</span><span>At least one special symbol (!@#$%^&* etc.)</span></li>
                                </ul>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="field-group">
                    <label class="field-label" for="password_confirmation">Confirm New Password</label>
                    <div class="input-icon-group password-field">
                        <span class="input-icon">
                            <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><polyline points="20 6 9 17 4 12"></polyline></svg>
                        </span>
                        <input class="field-input field-input--with-icon" id="password_confirmation" type="password" name="password_confirmation" placeholder="Confirm your new password" required autocomplete="new-password">
                        <button class="password-toggle" type="button" aria-label="Show password" data-password-toggle data-target="password_confirmation" data-state="hidden">
                            <svg class="icon-eye-open" width="18" height="18" viewBox="0 0 24 24" fill="none" aria-hidden="true"><path d="M2 12C4.5 7.8 8 5.7 12 5.7C16 5.7 19.5 7.8 22 12C19.5 16.2 16 18.3 12 18.3C8 18.3 4.5 16.2 2 12Z" stroke="currentColor" stroke-width="1.8" stroke-linejoin="round"></path><circle cx="12" cy="12" r="3.2" stroke="currentColor" stroke-width="1.8"></circle></svg>
                            <svg class="icon-eye-closed" width="18" height="18" viewBox="0 0 24 24" fill="none" aria-hidden="true"><path d="M3 3L21 21" stroke="currentColor" stroke-width="1.8" stroke-linecap="round"></path><path d="M10.58 10.58C10.21 10.95 10 11.46 10 12C10 13.1 10.9 14 12 14C12.54 14 13.05 13.79 13.42 13.42" stroke="currentColor" stroke-width="1.8" stroke-linecap="round"></path><path d="M6.72 6.72C4.76 8.02 3.15 9.82 2 12C4.5 16.2 8 18.3 12 18.3C13.75 18.3 15.41 17.9 16.92 17.14" stroke="currentColor" stroke-width="1.8" stroke-linecap="round" stroke-linejoin="round"></path><path d="M9.12 5.97C10.04 5.79 11 5.7 12 5.7C16 5.7 19.5 7.8 22 12C21.27 13.22 20.47 14.29 19.58 15.23" stroke="currentColor" stroke-width="1.8" stroke-linecap="round" stroke-linejoin="round"></path></svg>
                        </button>
                    </div>
                </div>

                <button class="submit-button profile-submit-btn profile-submit-btn--security" type="submit">
                    <span class="submit-button-label">
                        <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M12 22s8-4 8-10V5l-8-3-8 3v7c0 6 8 10 8 10z"></path></svg>
                        <span>Update Password</span>
                    </span>
                    <span class="submit-button-loading">
                        <span class="upload-spinner"></span>
                        <span>Updating...</span>
                    </span>
                </button>
            </form>

            <!-- Device & Session Control Card -->
            <div class="session-tools-card">
                <div class="session-tools-head">
                    <div class="session-tools-title-group">
                        <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><rect x="2" y="3" width="20" height="14" rx="2" ry="2"></rect><line x1="8" y1="21" x2="16" y2="21"></line><line x1="12" y1="17" x2="12" y2="21"></line></svg>
                        <span class="session-tools-title">Device & Session Control</span>
                    </div>
                    <span class="session-badge-current">
                        <span class="session-dot"></span>
                        <span>This Device Active</span>
                    </span>
                </div>
                <p class="session-tools-note">Sign out of all other active browsers, mobile devices, or concurrent locations while keeping this session authenticated.</p>
                <form action="{{ route('admin.profile.logout-other-devices') }}" method="post">
                    @csrf
                    <button class="logout-devices-btn" type="submit">
                        <svg width="15" height="15" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M18.36 6.64a9 9 0 1 1-12.73 0"></path><line x1="12" y1="2" x2="12" y2="12"></line></svg>
                        <span>Log Out Other Devices</span>
                    </button>
                </form>
            </div>
        </article>
    </section>
@endsection

@push('scripts')
    <script>
        document.addEventListener('DOMContentLoaded', () => {
            // Password Visibility Toggle
            document.querySelectorAll('[data-password-toggle]').forEach((toggle) => {
                toggle.addEventListener('click', () => {
                    const input = document.getElementById(toggle.dataset.target);

                    if (!input) {
                        return;
                    }

                    const visible = input.type === 'password';
                    input.type = visible ? 'text' : 'password';
                    toggle.setAttribute('aria-label', visible ? 'Hide password' : 'Show password');
                    toggle.setAttribute('data-state', visible ? 'visible' : 'hidden');
                });
            });

            // Form Submit Loading State
            document.querySelectorAll('.form-stack').forEach((form) => {
                form.addEventListener('submit', () => {
                    const btn = form.querySelector('.profile-submit-btn');
                    if (btn) {
                        btn.classList.add('is-loading');
                    }
                });
            });

            // Password Strength & Criteria Validation
            const passwordInput = document.getElementById('password');
            const criteriaWrapper = document.getElementById('password-criteria-wrapper');
            const strengthWrap = document.getElementById('password-strength-bar-wrap');
            const strengthFill = document.getElementById('password-strength-fill');
            const strengthText = document.getElementById('password-strength-text');

            if (passwordInput && criteriaWrapper) {
                const rules = {
                    length: (val) => val.length >= 8,
                    upper: (val) => /[A-Z]/.test(val),
                    lower: (val) => /[a-z]/.test(val),
                    number: (val) => /[0-9]/.test(val),
                    symbol: (val) => /[^A-Za-z0-9]/.test(val),
                };

                let hideTimer = null;

                const checkCriteria = () => {
                    const val = passwordInput.value;
                    let passedCount = 0;
                    let totalCount = Object.keys(rules).length;

                    Object.entries(rules).forEach(([ruleName, checkFn]) => {
                        const item = criteriaWrapper.querySelector(`[data-rule="${ruleName}"]`);
                        if (!item) return;
                        const icon = item.querySelector('.rule-icon');
                        const passed = checkFn(val);

                        if (passed) {
                            passedCount++;
                            item.classList.add('is-passed');
                            if (icon) icon.textContent = '✓';
                        } else {
                            item.classList.remove('is-passed');
                            if (icon) icon.textContent = '○';
                        }
                    });

                    // Update strength bar
                    if (val.length > 0) {
                        strengthWrap.classList.add('is-visible');
                        const pct = (passedCount / totalCount) * 100;
                        strengthFill.style.width = pct + '%';

                        if (passedCount <= 2) {
                            strengthFill.style.backgroundColor = '#ef4444';
                            strengthText.style.color = '#ef4444';
                            strengthText.textContent = 'Strength: Weak';
                        } else if (passedCount <= 3) {
                            strengthFill.style.backgroundColor = '#f59e0b';
                            strengthText.style.color = '#f59e0b';
                            strengthText.textContent = 'Strength: Fair';
                        } else if (passedCount <= 4) {
                            strengthFill.style.backgroundColor = '#3b82f6';
                            strengthText.style.color = '#3b82f6';
                            strengthText.textContent = 'Strength: Good';
                        } else {
                            strengthFill.style.backgroundColor = '#10b981';
                            strengthText.style.color = '#10b981';
                            strengthText.textContent = 'Strength: Strong ✓';
                        }
                    } else {
                        strengthWrap.classList.remove('is-visible');
                    }

                    return passedCount === totalCount;
                };

                const showCriteria = () => {
                    if (hideTimer) {
                        clearTimeout(hideTimer);
                        hideTimer = null;
                    }
                    criteriaWrapper.classList.add('is-visible');
                };

                const hideCriteria = (delay = 0) => {
                    if (hideTimer) {
                        clearTimeout(hideTimer);
                        hideTimer = null;
                    }

                    if (delay > 0) {
                        hideTimer = setTimeout(() => {
                            criteriaWrapper.classList.remove('is-visible');
                            hideTimer = null;
                        }, delay);
                    } else {
                        criteriaWrapper.classList.remove('is-visible');
                    }
                };

                passwordInput.addEventListener('focus', () => {
                    const allPassed = checkCriteria();
                    if (!allPassed) {
                        showCriteria();
                    }
                });

                passwordInput.addEventListener('input', () => {
                    const allPassed = checkCriteria();
                    if (allPassed) {
                        hideCriteria(450);
                    } else {
                        showCriteria();
                    }
                });

                passwordInput.addEventListener('blur', () => {
                    const val = passwordInput.value;
                    const allPassed = checkCriteria();

                    if (val.trim() === '' || allPassed) {
                        hideCriteria(0);
                    }
                });

                checkCriteria();
            }
        });
    </script>
@endpush
