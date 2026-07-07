@extends('admin.layouts.app')

@section('title', 'Profile | King Lotus International')

@push('styles')
    <style>
        .settings-grid {
            display: grid;
            gap: 14px;
            grid-template-columns: minmax(0, 1fr) minmax(0, 1fr);
            align-items: start;
        }

        .settings-card {
            display: grid;
            gap: 12px;
            align-content: start;
            padding: 14px 14px 12px;
            border-radius: 20px;
            position: relative;
            overflow: hidden;
        }

        .settings-card::before {
            content: "";
            position: absolute;
            inset: 0 auto auto 0;
            width: 100%;
            height: 3px;
            background: linear-gradient(90deg, rgba(63, 127, 228, 0.72) 0%, rgba(63, 127, 228, 0) 72%);
            pointer-events: none;
        }

        .settings-card-head {
            display: grid;
            gap: 6px;
            padding: 1px 1px 2px;
        }

        .settings-card-head h2 {
            margin: 0;
            font-size: 0.94rem;
            line-height: 1.05;
            letter-spacing: -0.03em;
        }

        .form-stack {
            display: grid;
            gap: 10px;
        }

        .field-group {
            display: grid;
            gap: 5px;
        }

        .field-label {
            font-size: 0.8rem;
            font-weight: 600;
            color: var(--ink-900);
        }

        .field-input {
            width: 100%;
            min-height: 44px;
            padding: 0 13px;
            border: 1px solid rgba(175, 191, 207, 0.62);
            border-radius: 15px;
            background: rgba(255, 255, 255, 0.82);
            color: var(--ink-900);
            outline: none;
            transition: border-color 0.2s ease, box-shadow 0.2s ease, transform 0.2s ease;
        }

        .field-input:focus {
            border-color: rgba(47, 111, 219, 0.5);
            box-shadow: 0 0 0 4px rgba(47, 111, 219, 0.12);
            transform: translateY(-1px);
        }

        .field-input[readonly] {
            background: rgba(239, 244, 250, 0.86);
            color: var(--ink-700);
            cursor: default;
        }

        .password-field {
            position: relative;
        }

        .password-field .field-input {
            padding-right: 46px;
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
            border: 0;
            border-radius: 999px;
            background: transparent;
            color: rgba(15, 31, 40, 0.58);
            cursor: pointer;
            transform: translateY(-50%);
            transition: background-color 0.2s ease, color 0.2s ease;
        }

        .password-toggle:hover {
            background: rgba(47, 111, 219, 0.08);
            color: var(--accent);
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

        .field-error {
            font-size: 0.88rem;
            color: #bf4a40;
        }

        .field-help {
            margin: 0;
            font-size: 0.8rem;
            color: var(--ink-700);
            line-height: 1.48;
        }

        .submit-button {
            min-height: 44px;
            padding: 0 16px;
            border: 1px solid transparent;
            border-radius: 15px;
            background: linear-gradient(180deg, #3f7fe4 0%, #2f6fdb 100%);
            color: #ffffff;
            font-weight: 700;
            cursor: pointer;
            box-shadow: 0 14px 24px rgba(47, 111, 219, 0.16);
            transition: transform 0.2s ease, box-shadow 0.2s ease, background-color 0.2s ease;
        }

        .submit-button:hover {
            transform: translateY(-1px);
            box-shadow: 0 16px 26px rgba(47, 111, 219, 0.2);
        }

        .secondary-button {
            min-height: 42px;
            padding: 0 16px;
            border: 1px solid rgba(164, 186, 214, 0.58);
            border-radius: 15px;
            background: rgba(255, 255, 255, 0.76);
            color: var(--ink-900);
            font: inherit;
            font-weight: 700;
            cursor: pointer;
            transition: transform 0.2s ease, border-color 0.2s ease, box-shadow 0.2s ease;
        }

        .secondary-button:hover {
            transform: translateY(-1px);
            border-color: rgba(47, 111, 219, 0.42);
            box-shadow: 0 12px 20px rgba(47, 111, 219, 0.08);
        }

        .session-tools {
            display: grid;
            gap: 8px;
            padding-top: 6px;
            border-top: 1px solid rgba(175, 191, 207, 0.32);
        }

        @media (max-width: 1100px) {
            .settings-grid {
                grid-template-columns: 1fr;
            }
        }

        @media (max-width: 720px) {
            .settings-grid,
            .settings-card,
            .form-stack {
                gap: 16px;
            }

            .field-input {
                min-height: 52px;
                border-radius: 16px;
            }

            .submit-button {
                width: 100%;
                min-height: 52px;
                border-radius: 16px;
            }
        }
    </style>
@endpush

@section('content')
    <header class="admin-header">
        <div class="admin-header-copy">
            <p class="admin-kicker">Account Settings</p>
            <h1 class="admin-title">Profile</h1>
            <p class="admin-subtitle">Update your admin profile information and change your password from one place.</p>
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

    <section class="settings-grid">
        <article class="admin-card settings-card">
            <div class="settings-card-head">
                <p class="section-kicker">Profile Update</p>
                <h2>Basic Information</h2>
                <p class="field-help">Update Super Admin information like name and mobile number. Email will remain fixed.</p>
            </div>

            <form class="form-stack" action="{{ route('admin.profile.update') }}" method="post">
                @csrf
                @method('patch')

                <div class="field-group">
                    <label class="field-label" for="full_name">Name</label>
                    <input class="field-input" id="full_name" type="text" name="full_name" value="{{ old('full_name', $admin->full_name) }}" placeholder="Enter full name" required>
                    @error('full_name')
                        <span class="field-error">{{ $message }}</span>
                    @enderror
                </div>

                <div class="field-group">
                    <label class="field-label" for="position">Position</label>
                    <input class="field-input" id="position" type="text" value="{{ $admin->name }}" readonly>
                </div>

                <div class="field-group">
                    <label class="field-label" for="email">Email address</label>
                    <input class="field-input" id="email" type="email" value="{{ $admin->email }}" readonly>
                </div>

                <div class="field-group">
                    <label class="field-label" for="mobile">Mobile number</label>
                    <input class="field-input" id="mobile" type="text" name="mobile" value="{{ old('mobile', $admin->mobile) }}" placeholder="Enter mobile number">
                    @error('mobile')
                        <span class="field-error">{{ $message }}</span>
                    @enderror
                </div>

                <button class="submit-button" type="submit">Save Profile</button>
            </form>
        </article>

        <article class="admin-card settings-card">
            <div class="settings-card-head">
                <p class="section-kicker">Security</p>
                <h2>Change Password</h2>
                <p class="field-help">Use your current password before setting a new one.</p>
            </div>

            <form class="form-stack" action="{{ route('admin.profile.password.update') }}" method="post">
                @csrf
                @method('put')

                <div class="field-group">
                    <label class="field-label" for="current_password">Current password</label>
                    <div class="password-field">
                        <input class="field-input" id="current_password" type="password" name="current_password" required>
                        <button class="password-toggle" type="button" aria-label="Show password" data-password-toggle data-target="current_password" data-state="hidden">
                            <svg class="icon-eye-open" width="20" height="20" viewBox="0 0 24 24" fill="none" aria-hidden="true">
                                <path d="M2 12C4.5 7.8 8 5.7 12 5.7C16 5.7 19.5 7.8 22 12C19.5 16.2 16 18.3 12 18.3C8 18.3 4.5 16.2 2 12Z" stroke="currentColor" stroke-width="1.8" stroke-linejoin="round"></path>
                                <circle cx="12" cy="12" r="3.2" stroke="currentColor" stroke-width="1.8"></circle>
                            </svg>
                            <svg class="icon-eye-closed" width="20" height="20" viewBox="0 0 24 24" fill="none" aria-hidden="true">
                                <path d="M3 3L21 21" stroke="currentColor" stroke-width="1.8" stroke-linecap="round"></path>
                                <path d="M10.58 10.58C10.21 10.95 10 11.46 10 12C10 13.1 10.9 14 12 14C12.54 14 13.05 13.79 13.42 13.42" stroke="currentColor" stroke-width="1.8" stroke-linecap="round"></path>
                                <path d="M6.72 6.72C4.76 8.02 3.15 9.82 2 12C4.5 16.2 8 18.3 12 18.3C13.75 18.3 15.41 17.9 16.92 17.14" stroke="currentColor" stroke-width="1.8" stroke-linecap="round" stroke-linejoin="round"></path>
                                <path d="M9.12 5.97C10.04 5.79 11 5.7 12 5.7C16 5.7 19.5 7.8 22 12C21.27 13.22 20.47 14.29 19.58 15.23" stroke="currentColor" stroke-width="1.8" stroke-linecap="round" stroke-linejoin="round"></path>
                            </svg>
                        </button>
                    </div>
                    @error('current_password')
                        <span class="field-error">{{ $message }}</span>
                    @enderror
                </div>

                <div class="field-group">
                    <label class="field-label" for="password">New password</label>
                    <div class="password-field">
                        <input class="field-input" id="password" type="password" name="password" required>
                        <button class="password-toggle" type="button" aria-label="Show password" data-password-toggle data-target="password" data-state="hidden">
                            <svg class="icon-eye-open" width="20" height="20" viewBox="0 0 24 24" fill="none" aria-hidden="true">
                                <path d="M2 12C4.5 7.8 8 5.7 12 5.7C16 5.7 19.5 7.8 22 12C19.5 16.2 16 18.3 12 18.3C8 18.3 4.5 16.2 2 12Z" stroke="currentColor" stroke-width="1.8" stroke-linejoin="round"></path>
                                <circle cx="12" cy="12" r="3.2" stroke="currentColor" stroke-width="1.8"></circle>
                            </svg>
                            <svg class="icon-eye-closed" width="20" height="20" viewBox="0 0 24 24" fill="none" aria-hidden="true">
                                <path d="M3 3L21 21" stroke="currentColor" stroke-width="1.8" stroke-linecap="round"></path>
                                <path d="M10.58 10.58C10.21 10.95 10 11.46 10 12C10 13.1 10.9 14 12 14C12.54 14 13.05 13.79 13.42 13.42" stroke="currentColor" stroke-width="1.8" stroke-linecap="round"></path>
                                <path d="M6.72 6.72C4.76 8.02 3.15 9.82 2 12C4.5 16.2 8 18.3 12 18.3C13.75 18.3 15.41 17.9 16.92 17.14" stroke="currentColor" stroke-width="1.8" stroke-linecap="round" stroke-linejoin="round"></path>
                                <path d="M9.12 5.97C10.04 5.79 11 5.7 12 5.7C16 5.7 19.5 7.8 22 12C21.27 13.22 20.47 14.29 19.58 15.23" stroke="currentColor" stroke-width="1.8" stroke-linecap="round" stroke-linejoin="round"></path>
                            </svg>
                        </button>
                    </div>
                    @error('password')
                        <span class="field-error">{{ $message }}</span>
                    @enderror
                </div>

                <div class="field-group">
                    <label class="field-label" for="password_confirmation">Confirm new password</label>
                    <div class="password-field">
                        <input class="field-input" id="password_confirmation" type="password" name="password_confirmation" required>
                        <button class="password-toggle" type="button" aria-label="Show password" data-password-toggle data-target="password_confirmation" data-state="hidden">
                            <svg class="icon-eye-open" width="20" height="20" viewBox="0 0 24 24" fill="none" aria-hidden="true">
                                <path d="M2 12C4.5 7.8 8 5.7 12 5.7C16 5.7 19.5 7.8 22 12C19.5 16.2 16 18.3 12 18.3C8 18.3 4.5 16.2 2 12Z" stroke="currentColor" stroke-width="1.8" stroke-linejoin="round"></path>
                                <circle cx="12" cy="12" r="3.2" stroke="currentColor" stroke-width="1.8"></circle>
                            </svg>
                            <svg class="icon-eye-closed" width="20" height="20" viewBox="0 0 24 24" fill="none" aria-hidden="true">
                                <path d="M3 3L21 21" stroke="currentColor" stroke-width="1.8" stroke-linecap="round"></path>
                                <path d="M10.58 10.58C10.21 10.95 10 11.46 10 12C10 13.1 10.9 14 12 14C12.54 14 13.05 13.79 13.42 13.42" stroke="currentColor" stroke-width="1.8" stroke-linecap="round"></path>
                                <path d="M6.72 6.72C4.76 8.02 3.15 9.82 2 12C4.5 16.2 8 18.3 12 18.3C13.75 18.3 15.41 17.9 16.92 17.14" stroke="currentColor" stroke-width="1.8" stroke-linecap="round" stroke-linejoin="round"></path>
                                <path d="M9.12 5.97C10.04 5.79 11 5.7 12 5.7C16 5.7 19.5 7.8 22 12C21.27 13.22 20.47 14.29 19.58 15.23" stroke="currentColor" stroke-width="1.8" stroke-linecap="round" stroke-linejoin="round"></path>
                            </svg>
                        </button>
                    </div>
                </div>

                <button class="submit-button" type="submit">Update Password</button>
            </form>

            <div class="session-tools">
                <p class="field-help">End all other active admin sessions while keeping this device signed in.</p>
                <form action="{{ route('admin.profile.logout-other-devices') }}" method="post">
                    @csrf
                    <button class="secondary-button" type="submit">Log Out Other Devices</button>
                </form>
            </div>
        </article>
    </section>
@endsection

@push('scripts')
    <script>
        document.addEventListener('DOMContentLoaded', () => {
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
        });
    </script>
@endpush
