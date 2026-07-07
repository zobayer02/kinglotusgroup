<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>@yield('title', 'Admin Portal | King Lotus International')</title>
    <link rel="icon" type="image/png" href="{{ asset('favicon.png') }}">
    <style>
        @import url('https://fonts.googleapis.com/css2?family=Fraunces:opsz,wght@9..144,400;9..144,500;9..144,600;9..144,700&family=Outfit:wght@400;500;600;700;800&display=swap');

        :root {
            color-scheme: light;
            --page-bg: #d9e6ef;
            --ink-900: #0f1f28;
            --ink-700: rgba(15, 31, 40, 0.74);
            --ink-500: rgba(15, 31, 40, 0.54);
            --line-soft: rgba(155, 174, 185, 0.42);
            --line-strong: rgba(255, 255, 255, 0.52);
            --surface-card: linear-gradient(180deg, rgba(245, 250, 253, 0.72) 0%, rgba(228, 239, 246, 0.56) 100%);
            --surface-soft: rgba(255, 255, 255, 0.54);
            --accent: #2f6fdb;
            --accent-strong: #1f56b3;
            --accent-soft: rgba(47, 111, 219, 0.14);
            --success: #1d8a63;
            --danger: #bf4a40;
            --sidebar-width: 220px;
            --sidebar-collapsed-width: 72px;
            --shadow-soft: 0 12px 28px rgba(24, 43, 56, 0.07);
        }

        * {
            box-sizing: border-box;
        }

        html, body {
            margin: 0;
            min-height: 100%;
        }

        body {
            min-height: 100vh;
            font-family: "Outfit", "Segoe UI", Tahoma, sans-serif;
            color: var(--ink-900);
            background:
                radial-gradient(circle at top left, rgba(255, 255, 255, 0.92), transparent 34%),
                radial-gradient(circle at top right, rgba(248, 251, 255, 0.9), transparent 30%),
                linear-gradient(180deg, #f8fbff 0%, #f3f7fb 34%, #edf2f7 100%);
        }

        a {
            color: inherit;
            text-decoration: none;
        }

        button,
        input {
            font: inherit;
        }

        img,
        svg {
            max-width: 100%;
        }

        .admin-shell {
            position: relative;
            display: grid;
            grid-template-columns: var(--sidebar-width) minmax(0, 1fr);
            gap: 10px;
            width: 100%;
            min-height: 100vh;
            padding: 10px;
            overflow-x: clip;
            transition: grid-template-columns 0.22s cubic-bezier(0.22, 1, 0.36, 1), gap 0.22s ease;
        }

        .admin-panel,
        .admin-sidebar,
        .admin-card,
        .admin-header {
            border: 1px solid var(--line-strong);
            background: var(--surface-card);
            box-shadow: var(--shadow-soft);
            backdrop-filter: blur(22px) saturate(160%);
            -webkit-backdrop-filter: blur(22px) saturate(160%);
        }

        .admin-sidebar {
            position: sticky;
            top: 10px;
            display: flex;
            flex-direction: column;
            min-height: calc(100vh - 20px);
            padding: 14px;
            border-radius: 22px;
            overflow: hidden;
            transition:
                padding 0.22s cubic-bezier(0.22, 1, 0.36, 1),
                border-radius 0.22s ease,
                transform 0.22s cubic-bezier(0.22, 1, 0.36, 1),
                opacity 0.16s ease,
                visibility 0.16s ease;
        }

        .admin-sidebar::before,
        .admin-panel::before {
            content: "";
            position: absolute;
            inset: 0;
            background:
                radial-gradient(circle at top left, rgba(255, 255, 255, 0.34), transparent 30%),
                linear-gradient(180deg, rgba(255, 255, 255, 0.18) 0%, rgba(255, 255, 255, 0) 42%);
            pointer-events: none;
        }

        .sidebar-brand,
        .sidebar-nav,
        .sidebar-footer,
        .panel-inner {
            position: relative;
            z-index: 1;
        }

        .sidebar-brand {
            display: grid;
            gap: 10px;
            justify-items: center;
            text-align: center;
            padding-top: 10px;
            margin-bottom: 18px;
            transition: opacity 0.16s ease, transform 0.16s ease, margin 0.18s ease, padding 0.18s ease;
        }

        .sidebar-mark {
            display: inline-flex;
            flex-direction: column;
            gap: 4px;
            width: fit-content;
            align-items: center;
            font-family: "Fraunces", "Times New Roman", serif;
        }

        .sidebar-mark-top {
            font-size: clamp(1.18rem, 1.55vw, 1.72rem);
            line-height: 0.88;
            letter-spacing: 0.04em;
            text-transform: uppercase;
        }

        .sidebar-mark-bottom {
            display: inline-flex;
            align-items: center;
            gap: 6px;
            font-size: 0.6rem;
            letter-spacing: 0.18em;
            text-transform: uppercase;
        }

        .sidebar-mark-line {
            width: 30px;
            height: 2px;
            border-radius: 999px;
            background: currentColor;
        }

        .sidebar-nav {
            display: grid;
            gap: 6px;
            margin-top: 34px;
            transition: margin-top 0.18s ease;
        }

        .sidebar-link,
        .sidebar-button {
            display: flex;
            align-items: center;
            gap: 9px;
            min-height: 44px;
            padding: 0 12px;
            border: 1px solid transparent;
            border-radius: 14px;
            font-size: 0.82rem;
            font-weight: 600;
            color: var(--ink-700);
            background: transparent;
            transition: transform 0.2s ease, background-color 0.2s ease, border-color 0.2s ease, color 0.2s ease;
        }

        .sidebar-link span:last-child,
        .sidebar-button span:last-child {
            overflow: hidden;
            white-space: nowrap;
            max-width: 160px;
            opacity: 1;
            transform: translateX(0);
            transition: max-width 0.18s ease, opacity 0.12s ease, transform 0.16s ease;
        }

        .sidebar-link:hover,
        .sidebar-button:hover,
        .sidebar-link.is-current {
            transform: translateY(-1px);
            color: var(--accent);
            background: rgba(224, 236, 255, 0.9);
            border-color: rgba(138, 174, 234, 0.55);
        }

        .sidebar-link.icon-danger,
        .sidebar-button.icon-danger {
            color: var(--danger);
        }

        .sidebar-button.icon-danger:hover {
            color: #d94b3d;
            background: rgba(255, 232, 229, 0.9);
            border-color: rgba(217, 75, 61, 0.55);
        }

        .sidebar-icon {
            display: inline-flex;
            align-items: center;
            justify-content: center;
            width: 26px;
            height: 26px;
            border-radius: 9px;
            background: rgba(255, 255, 255, 0.48);
            transition: background-color 0.2s ease, color 0.2s ease, box-shadow 0.2s ease;
        }

        .sidebar-link:hover .sidebar-icon,
        .sidebar-button:hover .sidebar-icon,
        .sidebar-link.is-current .sidebar-icon {
            background: linear-gradient(180deg, rgba(238, 245, 255, 0.98) 0%, rgba(219, 233, 255, 0.94) 100%);
            color: var(--accent);
            box-shadow: inset 0 1px 0 rgba(255, 255, 255, 0.72);
        }

        .sidebar-button.icon-danger:hover .sidebar-icon {
            background: linear-gradient(180deg, rgba(255, 243, 241, 0.98) 0%, rgba(255, 228, 224, 0.94) 100%);
            color: #d94b3d;
        }

        .admin-panel {
            position: relative;
            min-width: 0;
            border-radius: 22px;
            padding: 10px;
        }

        .sidebar-toggle {
            display: inline-flex;
            align-items: center;
            justify-content: center;
            width: 28px;
            height: 28px;
            border: 1px solid rgba(181, 203, 233, 0.55);
            border-radius: 999px;
            background: linear-gradient(180deg, rgba(248, 251, 255, 0.96) 0%, rgba(232, 240, 252, 0.92) 100%);
            color: var(--accent);
            box-shadow: 0 8px 18px rgba(47, 111, 219, 0.1);
            cursor: pointer;
            transition: transform 0.22s ease, box-shadow 0.22s ease, background-color 0.22s ease;
        }

        .sidebar-toggle-edge {
            position: absolute;
            top: 82px;
            left: calc((var(--sidebar-width) / 2) + 10px);
            z-index: 70;
            transform: translateX(-50%);
            transition: top 0.22s cubic-bezier(0.22, 1, 0.36, 1), left 0.22s cubic-bezier(0.22, 1, 0.36, 1);
        }

        .sidebar-toggle-edge svg {
            transition: transform 0.18s ease;
        }

        .sidebar-footer {
            margin-top: auto;
            padding-top: 12px;
        }

        .sidebar-form {
            margin: 0;
        }

        .sidebar-button {
            width: 100%;
            border: 1px solid transparent;
            text-align: left;
            cursor: pointer;
        }

        .admin-panel.admin-panel-plain {
            border: 0;
            padding: 0;
            background: transparent;
            box-shadow: none;
            backdrop-filter: none;
            -webkit-backdrop-filter: none;
        }

        .admin-panel.admin-panel-plain::before {
            display: none;
        }

        .panel-inner {
            display: grid;
            gap: 10px;
        }

        .admin-shell.is-sidebar-collapsed {
            grid-template-columns: var(--sidebar-collapsed-width) minmax(0, 1fr);
            gap: 10px;
        }

        .admin-shell.is-sidebar-collapsed .admin-sidebar {
            padding: 10px 7px;
            align-items: center;
            opacity: 1;
            visibility: visible;
            pointer-events: auto;
            transform: none;
        }

        .admin-shell.is-sidebar-collapsed .sidebar-brand {
            opacity: 0;
            transform: translateY(-12px);
            pointer-events: none;
            height: 0;
            min-height: 0;
            margin-bottom: 0;
            padding-top: 0;
            overflow: hidden;
        }

        .admin-shell.is-sidebar-collapsed .sidebar-nav {
            width: 100%;
            margin-top: 38px;
            justify-items: center;
        }

        .admin-shell.is-sidebar-collapsed .sidebar-link,
        .admin-shell.is-sidebar-collapsed .sidebar-button {
            justify-content: center;
            width: 100%;
            min-height: 42px;
            padding: 0;
            border-radius: 14px;
        }

        .admin-shell.is-sidebar-collapsed .sidebar-link span:last-child,
        .admin-shell.is-sidebar-collapsed .sidebar-button span:last-child {
            max-width: 0;
            opacity: 0;
            transform: translateX(-6px);
        }

        .admin-shell.is-sidebar-collapsed .sidebar-footer {
            width: 100%;
            padding-top: 10px;
        }

        .admin-shell.is-sidebar-collapsed .sidebar-form {
            width: 100%;
        }

        .admin-shell.is-sidebar-collapsed .sidebar-toggle-edge svg {
            transform: rotate(180deg);
        }

        .admin-shell.is-sidebar-collapsed .sidebar-toggle-edge {
            top: 16px;
            left: calc((var(--sidebar-collapsed-width) / 2) + 10px);
        }

        .admin-header {
            display: flex;
            align-items: center;
            justify-content: space-between;
            flex-wrap: wrap;
            gap: 10px;
            padding: 12px 14px;
            border-radius: 18px;
        }

        .admin-header-copy {
            display: grid;
            gap: 4px;
            min-width: 0;
        }

        .admin-kicker {
            margin: 0;
            font-size: 0.68rem;
            font-weight: 600;
            letter-spacing: 0.18em;
            text-transform: uppercase;
            color: var(--ink-700);
        }

        .admin-title {
            margin: 0;
            font-size: clamp(1.26rem, 1.72vw, 1.92rem);
            line-height: 0.94;
            letter-spacing: -0.04em;
        }

        .admin-subtitle {
            margin: 0;
            max-width: 520px;
            font-size: 0.82rem;
            color: var(--ink-700);
            line-height: 1.55;
            overflow-wrap: anywhere;
        }

        .admin-profile {
            display: flex;
            align-items: center;
            gap: 9px;
            min-width: min(100%, 248px);
            max-width: 100%;
            padding: 8px 10px;
            border-radius: 16px;
            background: linear-gradient(180deg, rgba(239, 246, 255, 0.84) 0%, rgba(225, 237, 255, 0.72) 100%);
            border: 1px solid rgba(179, 206, 243, 0.58);
        }

        .admin-avatar {
            display: inline-flex;
            align-items: center;
            justify-content: center;
            width: 40px;
            height: 40px;
            border-radius: 999px;
            background: linear-gradient(135deg, #3f7fe4 0%, #6aa9ff 100%);
            color: #ffffff;
            font-size: 0.9rem;
            font-weight: 700;
            box-shadow: inset 0 1px 0 rgba(255, 255, 255, 0.28);
        }

        .admin-profile-copy {
            min-width: 0;
            flex: 1;
        }

        .admin-profile-name,
        .admin-profile-email {
            margin: 0;
            min-width: 0;
        }

        .admin-profile-name {
            font-size: 0.92rem;
            font-weight: 700;
        }

        .admin-profile-email {
            margin-top: 2px;
            font-size: 0.8rem;
            color: var(--ink-700);
            white-space: nowrap;
            line-height: 1.35;
        }

        .admin-content {
            display: grid;
            gap: 10px;
        }

        .admin-grid,
        .admin-grid-double {
            display: grid;
            gap: 10px;
        }

        .admin-grid {
            grid-template-columns: repeat(4, minmax(0, 1fr));
        }

        .admin-grid-double {
            grid-template-columns: minmax(0, 1.25fr) minmax(0, 0.75fr);
        }

        .admin-card {
            border-radius: 18px;
            padding: 12px;
        }

        .admin-card h2,
        .admin-card h3,
        .admin-card p {
            margin-top: 0;
        }

        .section-kicker {
            margin-bottom: 4px;
            font-size: 0.66rem;
            font-weight: 600;
            letter-spacing: 0.16em;
            text-transform: uppercase;
            color: var(--ink-700);
        }

        .metric-card {
            display: grid;
            gap: 6px;
        }

        .metric-value {
            font-size: clamp(1.24rem, 1.55vw, 1.72rem);
            line-height: 0.92;
            letter-spacing: -0.05em;
        }

        .metric-label {
            margin: 0;
            font-size: 0.8rem;
            font-weight: 600;
        }

        .metric-meta {
            margin: 0;
            font-size: 0.78rem;
            color: var(--ink-700);
            line-height: 1.45;
        }

        .overview-list,
        .table-list,
        .admin-list,
        .quick-actions {
            display: grid;
            gap: 6px;
        }

        .overview-item,
        .table-item,
        .admin-item,
        .quick-action {
            display: flex;
            align-items: flex-start;
            justify-content: space-between;
            gap: 8px;
            padding: 10px;
            border-radius: 14px;
            border: 1px solid var(--line-soft);
            background: rgba(255, 255, 255, 0.38);
        }

        .overview-item p,
        .table-item p,
        .admin-item p,
        .quick-action p {
            margin: 4px 0 0;
            font-size: 0.78rem;
            color: var(--ink-700);
            line-height: 1.45;
        }

        .status-pill {
            display: inline-flex;
            align-items: center;
            gap: 6px;
            padding: 4px 8px;
            border-radius: 999px;
            background: rgba(29, 138, 99, 0.12);
            color: var(--success);
            font-size: 0.72rem;
            font-weight: 700;
            white-space: nowrap;
        }

        .status-pill::before {
            content: "";
            width: 7px;
            height: 7px;
            border-radius: 999px;
            background: currentColor;
        }

        .table-badge {
            display: inline-flex;
            align-items: center;
            justify-content: center;
            min-width: 30px;
            height: 30px;
            border-radius: 10px;
            background: var(--accent-soft);
            color: var(--accent);
            font-weight: 700;
        }

        .empty-state {
            color: var(--ink-700);
            line-height: 1.7;
        }

        .quick-action {
            gap: 12px;
        }

        .quick-action strong {
            display: block;
        }

        .quick-action-arrow {
            color: var(--accent);
            font-size: 1.1rem;
        }

        .inline-highlight {
            color: var(--accent);
            font-weight: 700;
        }

        .flash-error {
            margin: 0 0 18px;
            padding: 14px 16px;
            border-radius: 18px;
            border: 1px solid rgba(191, 74, 64, 0.24);
            background: rgba(191, 74, 64, 0.08);
            color: #8d342d;
        }

        .admin-shell-backdrop {
            display: none;
        }

        .admin-toast {
            position: fixed;
            top: 28px;
            right: 28px;
            z-index: 90;
            display: flex;
            align-items: flex-start;
            gap: 12px;
            width: min(380px, calc(100vw - 28px));
            padding: 16px 18px;
            border-radius: 22px;
            box-shadow: 0 18px 42px rgba(24, 43, 56, 0.16);
            backdrop-filter: blur(18px);
            -webkit-backdrop-filter: blur(18px);
            opacity: 0;
            transform: translateY(-14px) scale(0.98);
            pointer-events: none;
            transition: opacity 0.28s ease, transform 0.28s ease;
        }

        .admin-toast.is-visible {
            opacity: 1;
            transform: translateY(0) scale(1);
        }

        .admin-toast.is-success {
            border: 1px solid rgba(29, 138, 99, 0.22);
            background: linear-gradient(180deg, rgba(244, 255, 250, 0.96) 0%, rgba(230, 249, 240, 0.94) 100%);
            color: #176f51;
        }

        .admin-toast.is-error {
            border: 1px solid rgba(191, 74, 64, 0.24);
            background: linear-gradient(180deg, rgba(255, 247, 246, 0.96) 0%, rgba(255, 233, 230, 0.94) 100%);
            color: #8d342d;
        }

        .admin-toast-icon {
            display: inline-flex;
            align-items: center;
            justify-content: center;
            width: 34px;
            height: 34px;
            border-radius: 999px;
            flex-shrink: 0;
        }

        .admin-toast.is-success .admin-toast-icon {
            background: rgba(29, 138, 99, 0.12);
        }

        .admin-toast.is-error .admin-toast-icon {
            background: rgba(191, 74, 64, 0.12);
        }

        .admin-toast-title {
            margin: 0;
            font-size: 0.98rem;
            font-weight: 700;
        }

        .admin-toast-text {
            margin: 2px 0 0;
        }

        .admin-toast-close {
            margin-left: auto;
            width: 30px;
            height: 30px;
            border: 0;
            border-radius: 999px;
            background: transparent;
            color: currentColor;
            opacity: 0.72;
            cursor: pointer;
            transition: background-color 0.2s ease, opacity 0.2s ease;
        }

        .admin-toast-close:hover {
            background: rgba(255, 255, 255, 0.24);
            opacity: 1;
        }

        .admin-confirm {
            position: fixed;
            inset: 0;
            z-index: 120;
            display: grid;
            place-items: center;
            padding: 20px;
            background: rgba(11, 23, 32, 0.34);
            backdrop-filter: blur(8px);
            -webkit-backdrop-filter: blur(8px);
        }

        .admin-confirm[hidden] {
            display: none;
        }

        .admin-confirm-panel {
            width: min(460px, calc(100vw - 32px));
            padding: 24px;
            border: 1px solid rgba(255, 255, 255, 0.52);
            border-radius: 28px;
            background: linear-gradient(180deg, rgba(249, 252, 255, 0.96) 0%, rgba(235, 243, 251, 0.94) 100%);
            box-shadow: 0 30px 80px rgba(14, 28, 38, 0.18);
        }

        .admin-confirm-title {
            margin: 0;
            font-size: 1.28rem;
            font-weight: 700;
            color: var(--ink-900);
        }

        .admin-confirm-text {
            margin: 10px 0 0;
            color: var(--ink-700);
            line-height: 1.65;
        }

        .admin-confirm-actions {
            display: flex;
            justify-content: flex-end;
            gap: 12px;
            margin-top: 22px;
        }

        .admin-confirm-button {
            display: inline-flex;
            align-items: center;
            justify-content: center;
            min-height: 46px;
            padding: 0 18px;
            border-radius: 16px;
            border: 1px solid rgba(164, 186, 214, 0.58);
            background: rgba(255, 255, 255, 0.92);
            color: var(--ink-900);
            font-weight: 700;
            cursor: pointer;
            transition: transform 0.18s ease, box-shadow 0.18s ease, border-color 0.18s ease;
        }

        .admin-confirm-button:hover {
            transform: translateY(-1px);
            box-shadow: 0 14px 24px rgba(47, 111, 219, 0.08);
        }

        .admin-confirm-button--primary {
            border-color: rgba(47, 111, 219, 0.32);
            background: linear-gradient(180deg, #3f7fe4 0%, #2f6fdb 100%);
            color: #ffffff;
            box-shadow: 0 16px 28px rgba(47, 111, 219, 0.18);
        }

        @media (max-width: 1280px) {
            .admin-grid {
                grid-template-columns: repeat(2, minmax(0, 1fr));
            }

            .admin-header {
                padding: 20px 22px;
            }
        }

        @media (max-width: 980px) {
            .admin-shell {
                grid-template-columns: 1fr;
                gap: 14px;
                padding: 14px;
            }

            .admin-sidebar {
                position: fixed;
                top: 14px;
                left: 14px;
                width: var(--sidebar-collapsed-width);
                min-height: calc(100vh - 28px);
                z-index: 65;
                padding: 18px 12px;
                border-radius: 30px;
            }

            .admin-shell.is-sidebar-collapsed .admin-sidebar {
                width: var(--sidebar-collapsed-width);
                min-height: calc(100vh - 28px);
            }

            .admin-shell:not(.is-sidebar-collapsed) .admin-sidebar {
                width: min(340px, calc(100vw - 28px));
                padding: 24px;
            }

            .admin-panel {
                grid-column: auto;
                width: 100%;
                margin-left: 0;
            }

            .admin-shell-backdrop {
                display: block;
                position: fixed;
                inset: 0;
                z-index: 55;
                border: 0;
                background: rgba(13, 27, 38, 0.26);
                opacity: 0;
                pointer-events: none;
                transition: opacity 0.28s ease;
            }

            .admin-shell:not(.is-sidebar-collapsed) .admin-shell-backdrop {
                opacity: 1;
                pointer-events: auto;
            }

            .admin-grid-double {
                grid-template-columns: 1fr;
            }

            .admin-header {
                flex-direction: column;
                align-items: flex-start;
                padding: 20px;
            }

            .admin-profile {
                width: 100%;
                min-width: 0;
            }

            .sidebar-toggle-edge {
                top: 26px;
                left: calc((var(--sidebar-collapsed-width) / 2) + 14px);
            }

            .admin-shell:not(.is-sidebar-collapsed) .sidebar-toggle-edge {
                top: 142px;
                left: calc((min(340px, calc(100vw - 28px)) / 2) + 14px);
            }
        }

        @media (max-width: 720px) {
            .admin-shell {
                --mobile-sidebar-width: min(280px, calc(100vw - 24px));
                grid-template-columns: 1fr;
                gap: 12px;
                padding: 12px;
            }

            .admin-sidebar,
            .admin-panel,
            .admin-card,
            .admin-header {
                border-radius: 24px;
            }

            .admin-sidebar,
            .admin-panel {
                padding: 18px;
            }

            .admin-sidebar {
                top: 12px;
                left: 12px;
                min-height: calc(100vh - 24px);
                width: var(--mobile-sidebar-width);
                padding: 22px 18px;
                transition: transform 0.32s ease, opacity 0.24s ease, visibility 0.24s ease;
            }

            .admin-shell:not(.is-sidebar-collapsed) .admin-sidebar {
                width: var(--mobile-sidebar-width);
                transform: translateX(0);
                opacity: 1;
                visibility: visible;
                pointer-events: auto;
            }

            .admin-panel {
                grid-column: auto;
                width: 100%;
                margin-left: 0;
                transition: none;
            }

            .admin-header,
            .admin-card {
                padding: 18px;
            }

            .admin-grid {
                grid-template-columns: 1fr;
            }

            .sidebar-brand {
                padding-top: 22px;
                margin-bottom: 32px;
            }

            .sidebar-mark-top {
                font-size: 1.85rem;
            }

            .sidebar-mark-bottom {
                font-size: 0.72rem;
                letter-spacing: 0.18em;
            }

            .sidebar-nav {
                margin-top: 38px;
                gap: 10px;
            }

            .sidebar-link,
            .sidebar-button {
                min-height: 58px;
                padding: 0 16px;
            }

            .sidebar-toggle-edge {
                position: fixed;
                top: 18px;
                left: 18px;
                transform: none;
            }

            .admin-avatar {
                width: 56px;
                height: 56px;
                font-size: 1.1rem;
            }

            .admin-title {
                font-size: clamp(2rem, 10vw, 2.55rem);
            }

            .admin-kicker {
                font-size: 0.75rem;
                letter-spacing: 0.14em;
            }

            .admin-profile {
                gap: 12px;
                padding: 12px 14px;
            }

            .admin-profile-name {
                font-size: 1.16rem;
            }

            .admin-toast {
                top: 14px;
                right: 12px;
                left: 12px;
                width: auto;
            }

            .admin-shell:not(.is-sidebar-collapsed) .sidebar-toggle-edge {
                top: 118px;
                left: calc(12px + (var(--mobile-sidebar-width) / 2));
                transform: translateX(-50%);
            }

            .admin-shell.is-sidebar-collapsed .admin-sidebar {
                transform: translateX(calc(-100% - 18px));
                opacity: 0;
                visibility: hidden;
                pointer-events: none;
            }

            .admin-shell.is-sidebar-collapsed .sidebar-toggle-edge {
                top: 18px;
                left: 18px;
                transform: none;
            }
        }
    </style>
    @stack('styles')
</head>
<body>
    @php($isPlainAdminPanel = request()->routeIs('admin.dashboard'))

    @if (session('success'))
        <div class="admin-toast is-success" data-admin-toast>
            <span class="admin-toast-icon" aria-hidden="true">
                <svg width="18" height="18" viewBox="0 0 24 24" fill="none">
                    <path d="M5 12.5L9.2 16.7L19 7" stroke="currentColor" stroke-width="2.2" stroke-linecap="round" stroke-linejoin="round"></path>
                </svg>
            </span>
            <div>
                <p class="admin-toast-title">Success</p>
                <p class="admin-toast-text">{{ session('success') }}</p>
            </div>
            <button class="admin-toast-close" type="button" aria-label="Close notification" data-admin-toast-close>
                <svg width="16" height="16" viewBox="0 0 24 24" fill="none">
                    <path d="M6 6L18 18" stroke="currentColor" stroke-width="1.9" stroke-linecap="round"></path>
                    <path d="M18 6L6 18" stroke="currentColor" stroke-width="1.9" stroke-linecap="round"></path>
                </svg>
            </button>
        </div>
    @endif

    @if (session('error'))
        <div class="admin-toast is-error" data-admin-toast>
            <span class="admin-toast-icon" aria-hidden="true">
                <svg width="18" height="18" viewBox="0 0 24 24" fill="none">
                    <path d="M12 7V13" stroke="currentColor" stroke-width="2.2" stroke-linecap="round"></path>
                    <circle cx="12" cy="17" r="1.2" fill="currentColor"></circle>
                    <path d="M10.29 3.86L1.82 18A2 2 0 0 0 3.53 21H20.47A2 2 0 0 0 22.18 18L13.71 3.86A2 2 0 0 0 10.29 3.86Z" stroke="currentColor" stroke-width="1.8" stroke-linejoin="round"></path>
                </svg>
            </span>
            <div>
                <p class="admin-toast-title">Update Failed</p>
                <p class="admin-toast-text">{{ session('error') }}</p>
            </div>
            <button class="admin-toast-close" type="button" aria-label="Close notification" data-admin-toast-close>
                <svg width="16" height="16" viewBox="0 0 24 24" fill="none">
                    <path d="M6 6L18 18" stroke="currentColor" stroke-width="1.9" stroke-linecap="round"></path>
                    <path d="M18 6L6 18" stroke="currentColor" stroke-width="1.9" stroke-linecap="round"></path>
                </svg>
            </button>
        </div>
    @endif

    <div class="admin-confirm" data-admin-confirm hidden>
        <div class="admin-confirm-panel" role="alertdialog" aria-modal="true" aria-labelledby="admin-confirm-title" aria-describedby="admin-confirm-text">
            <h2 class="admin-confirm-title" id="admin-confirm-title">Please confirm</h2>
            <p class="admin-confirm-text" id="admin-confirm-text">Are you sure you want to continue?</p>
            <div class="admin-confirm-actions">
                <button class="admin-confirm-button" type="button" data-admin-confirm-cancel>Cancel</button>
                <button class="admin-confirm-button admin-confirm-button--primary" type="button" data-admin-confirm-accept>Confirm</button>
            </div>
        </div>
    </div>

    <div class="admin-shell" data-admin-shell>
        <button class="sidebar-toggle sidebar-toggle-edge" type="button" aria-label="Toggle sidebar" aria-expanded="true" data-admin-sidebar-toggle>
            <svg width="18" height="18" viewBox="0 0 24 24" fill="none" aria-hidden="true">
                <path d="M9 6L15 12L9 18" stroke="currentColor" stroke-width="2.2" stroke-linecap="round" stroke-linejoin="round"></path>
            </svg>
        </button>

        <aside class="admin-sidebar">
            <div class="sidebar-brand">
                <div class="sidebar-mark" aria-label="King Lotus Group">
                    <span class="sidebar-mark-top">King Lotus</span>
                    <span class="sidebar-mark-bottom">
                        <span class="sidebar-mark-line" aria-hidden="true"></span>
                        <span>Group</span>
                        <span class="sidebar-mark-line" aria-hidden="true"></span>
                    </span>
                </div>
            </div>

            <nav class="sidebar-nav" aria-label="Admin navigation">
                <a class="sidebar-link {{ request()->routeIs('admin.dashboard') ? 'is-current' : '' }}" href="{{ route('admin.dashboard') }}">
                    <span class="sidebar-icon">
                        <svg width="22" height="22" viewBox="0 0 24 24" fill="none" aria-hidden="true">
                            <path d="M4 4H10V10H4V4Z" stroke="currentColor" stroke-width="1.8" stroke-linejoin="round"></path>
                            <path d="M14 4H20V7H14V4Z" stroke="currentColor" stroke-width="1.8" stroke-linejoin="round"></path>
                            <path d="M14 11H20V20H14V11Z" stroke="currentColor" stroke-width="1.8" stroke-linejoin="round"></path>
                            <path d="M4 14H10V20H4V14Z" stroke="currentColor" stroke-width="1.8" stroke-linejoin="round"></path>
                        </svg>
                    </span>
                    <span>Dashboard</span>
                </a>
                <a class="sidebar-link {{ request()->routeIs('admin.content.*') ? 'is-current' : '' }}" href="{{ route('admin.content.index') }}">
                    <span class="sidebar-icon">
                        <svg width="22" height="22" viewBox="0 0 24 24" fill="none" aria-hidden="true">
                            <path d="M4 6.5C4 5.12 7.58 4 12 4C16.42 4 20 5.12 20 6.5M4 6.5C4 7.88 7.58 9 12 9C16.42 9 20 7.88 20 6.5M4 6.5V17.5C4 18.88 7.58 20 12 20C16.42 20 20 18.88 20 17.5V6.5" stroke="currentColor" stroke-width="1.8" stroke-linejoin="round"></path>
                            <path d="M4 12C4 13.38 7.58 14.5 12 14.5C16.42 14.5 20 13.38 20 12" stroke="currentColor" stroke-width="1.8" stroke-linejoin="round"></path>
                        </svg>
                    </span>
                    <span>Content Management</span>
                </a>
                <a class="sidebar-link {{ request()->routeIs('admin.profile.*') ? 'is-current' : '' }}" href="{{ route('admin.profile.edit') }}">
                    <span class="sidebar-icon">
                        <svg width="22" height="22" viewBox="0 0 24 24" fill="none" aria-hidden="true">
                            <path d="M12 12C14.7614 12 17 9.76142 17 7C17 4.23858 14.7614 2 12 2C9.23858 2 7 4.23858 7 7C7 9.76142 9.23858 12 12 12Z" stroke="currentColor" stroke-width="1.8"></path>
                            <path d="M4 21C4 17.6863 7.58172 15 12 15C16.4183 15 20 17.6863 20 21" stroke="currentColor" stroke-width="1.8" stroke-linecap="round"></path>
                        </svg>
                    </span>
                    <span>Profile</span>
                </a>
            </nav>

            <div class="sidebar-footer">
                <form class="sidebar-form" action="{{ route('admin.logout') }}" method="post">
                    @csrf
                    <button class="sidebar-button icon-danger" type="submit">
                        <span class="sidebar-icon">
                            <svg width="22" height="22" viewBox="0 0 24 24" fill="none" aria-hidden="true">
                                <path d="M10 17L15 12L10 7" stroke="currentColor" stroke-width="1.8" stroke-linecap="round" stroke-linejoin="round"></path>
                                <path d="M15 12H3" stroke="currentColor" stroke-width="1.8" stroke-linecap="round"></path>
                                <path d="M13 4H18C19.1046 4 20 4.89543 20 6V18C20 19.1046 19.1046 20 18 20H13" stroke="currentColor" stroke-width="1.8" stroke-linecap="round"></path>
                            </svg>
                        </span>
                        <span>Logout</span>
                    </button>
                </form>
            </div>
        </aside>

        <button class="admin-shell-backdrop" type="button" aria-label="Close sidebar" data-admin-sidebar-backdrop></button>

        <main class="admin-panel {{ $isPlainAdminPanel ? 'admin-panel-plain' : '' }}">
            <div class="panel-inner">
                @yield('content')
            </div>
        </main>
    </div>

    <script>
        (() => {
            const shell = document.querySelector('[data-admin-shell]');
            const toggleButtons = document.querySelectorAll('[data-admin-sidebar-toggle]');
            const backdrop = document.querySelector('[data-admin-sidebar-backdrop]');
            const mobileQuery = window.matchMedia('(max-width: 980px)');
            const storageKey = 'kinglotus-admin-sidebar-collapsed';

            const applyCollapsedState = (collapsed) => {
                shell.classList.toggle('is-sidebar-collapsed', collapsed);
                toggleButtons.forEach((button) => {
                    button.setAttribute('aria-expanded', collapsed ? 'false' : 'true');
                });
                document.body.style.overflow = mobileQuery.matches && !collapsed ? 'hidden' : '';
            };

            const getDefaultCollapsed = () => {
                const stored = window.localStorage.getItem(storageKey);

                if (stored !== null) {
                    return stored === 'true';
                }

                return mobileQuery.matches;
            };

            const setCollapsed = (collapsed) => {
                applyCollapsedState(collapsed);
                window.localStorage.setItem(storageKey, collapsed ? 'true' : 'false');
            };

            if (shell) {
                applyCollapsedState(getDefaultCollapsed());
            }

            toggleButtons.forEach((button) => {
                button.addEventListener('click', () => {
                    setCollapsed(!shell.classList.contains('is-sidebar-collapsed'));
                });
            });

            backdrop?.addEventListener('click', () => {
                if (mobileQuery.matches) {
                    setCollapsed(true);
                }
            });

            mobileQuery.addEventListener('change', () => {
                if (window.localStorage.getItem(storageKey) === null) {
                    applyCollapsedState(getDefaultCollapsed());
                } else {
                    document.body.style.overflow = mobileQuery.matches && !shell.classList.contains('is-sidebar-collapsed') ? 'hidden' : '';
                }
            });

            window.addEventListener('keydown', (event) => {
                if (event.key === 'Escape' && mobileQuery.matches && !shell.classList.contains('is-sidebar-collapsed')) {
                    setCollapsed(true);
                }
            });

            const closeToast = (toast) => {
                toast.classList.remove('is-visible');
                window.setTimeout(() => toast.remove(), 280);
            };

            document.querySelectorAll('[data-admin-toast]').forEach((toast) => {
                requestAnimationFrame(() => {
                    toast.classList.add('is-visible');
                });

                const closeButton = toast.querySelector('[data-admin-toast-close]');

                if (closeButton) {
                    closeButton.addEventListener('click', () => closeToast(toast));
                }

                window.setTimeout(() => closeToast(toast), 3200);
            });

            const confirmRoot = document.querySelector('[data-admin-confirm]');
            const confirmTitle = confirmRoot?.querySelector('[data-admin-confirm-title], .admin-confirm-title');
            const confirmText = confirmRoot?.querySelector('[data-admin-confirm-text], .admin-confirm-text');
            const confirmCancel = confirmRoot?.querySelector('[data-admin-confirm-cancel]');
            const confirmAccept = confirmRoot?.querySelector('[data-admin-confirm-accept]');
            let confirmResolver = null;
            let lastFocusedElement = null;

            const closeConfirm = (accepted) => {
                if (!confirmRoot || !confirmResolver) {
                    return;
                }

                confirmRoot.hidden = true;
                document.body.style.overflow = mobileQuery.matches && !shell.classList.contains('is-sidebar-collapsed') ? 'hidden' : '';
                const resolver = confirmResolver;
                confirmResolver = null;
                resolver(accepted);
                lastFocusedElement?.focus?.();
            };

            window.adminConfirm = ({ title = 'Please confirm', message = 'Are you sure you want to continue?', confirmLabel = 'Confirm', cancelLabel = 'Cancel' } = {}) => {
                if (!confirmRoot || !confirmTitle || !confirmText || !confirmAccept || !confirmCancel) {
                    return Promise.resolve(window.confirm(message));
                }

                if (confirmResolver) {
                    confirmResolver(false);
                    confirmResolver = null;
                }

                lastFocusedElement = document.activeElement;
                confirmTitle.textContent = title;
                confirmText.textContent = message;
                confirmAccept.textContent = confirmLabel;
                confirmCancel.textContent = cancelLabel;
                confirmRoot.hidden = false;
                document.body.style.overflow = 'hidden';

                return new Promise((resolve) => {
                    confirmResolver = resolve;
                    window.requestAnimationFrame(() => confirmAccept.focus());
                });
            };

            confirmCancel?.addEventListener('click', () => closeConfirm(false));
            confirmAccept?.addEventListener('click', () => closeConfirm(true));
            confirmRoot?.addEventListener('click', (event) => {
                if (event.target === confirmRoot) {
                    closeConfirm(false);
                }
            });

            window.addEventListener('keydown', (event) => {
                if (event.key === 'Escape' && confirmResolver) {
                    event.preventDefault();
                    closeConfirm(false);
                }
            });

            document.addEventListener('submit', async (event) => {
                const form = event.target.closest('form[data-confirm-submit]');

                if (!form || form.dataset.confirmedSubmit === 'true') {
                    return;
                }

                event.preventDefault();

                const confirmed = await window.adminConfirm({
                    title: form.dataset.confirmTitle || 'Please confirm',
                    message: form.dataset.confirmMessage || 'Are you sure you want to continue?',
                    confirmLabel: form.dataset.confirmLabel || 'Confirm',
                    cancelLabel: form.dataset.cancelLabel || 'Cancel',
                });

                if (!confirmed) {
                    return;
                }

                form.dataset.confirmedSubmit = 'true';
                form.requestSubmit();
                window.setTimeout(() => {
                    delete form.dataset.confirmedSubmit;
                }, 0);
            });
        })();
    </script>

    @stack('scripts')

    <script>
        (() => {
            const fitText = (element) => {
                const min = Number(element.dataset.minSize || 10);
                const max = Number(element.dataset.maxSize || 16);

                element.style.whiteSpace = 'nowrap';
                element.style.fontSize = `${max}px`;

                while (element.scrollWidth > element.clientWidth && parseFloat(element.style.fontSize) > min) {
                    element.style.fontSize = `${parseFloat(element.style.fontSize) - 0.5}px`;
                }
            };

            const fitAll = () => {
                document.querySelectorAll('[data-autofit-text]').forEach((element) => fitText(element));
            };

            window.addEventListener('load', fitAll);
            window.addEventListener('resize', fitAll);
        })();
    </script>
</body>
</html>
