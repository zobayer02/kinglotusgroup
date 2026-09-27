# Stage 3 Authentication, Authorization, Proxy, and Application Security Evidence

- **Date:** 2026-09-27
- **Decision:** `PASS (Local Implementation & Verification)`
- **Deployment Status:** `CPANEL STAGING NOT VERIFIED` (External CDN/WAF and staging host controls blocked until host provisioning)
- **Baseline commit:** `a494b61abc6f4092f7101efab758133ec4de17f4`
- **Branch:** `security/production-hardening`
- **Dirty Tree Note:** Approved uncommitted Stage 0–2 working tree preserved and extended. No files were reset or discarded. No commits or pushes have been executed.

---

## 1. Summary of Changes

### A. Environment and Session Cookie Hardening
- Session cookies remain environment-driven. Default `SESSION_DOMAIN` remains empty (null) to enforce strict host-only cookie isolation for the subdomain.
- In `config/session.php`, `secure` defaults to HTTPS in production, `http_only` is enforced (`true`), and `same_site` is set to `lax`.
- Assessment of `__Host-` session cookie: Modern browsers enforce that cookies with `__Host-` prefix must be sent over HTTPS, have no `Domain` attribute, and have `Path=/`. While compatible with cPanel under HTTPS on the live subdomain, plain HTTP environments (e.g. local dev) reject `__Host-` cookies. Documented in `.env.example` as `SESSION_COOKIE=__Host-klg_session` for production HTTPS.
- Production encryption key (`APP_KEY`) remains strictly uncommitted and environment-provided.

### B. Proxy and Edge Request Trust Policy
- Removed `trustProxies('*')` in `bootstrap/app.php`.
- Implemented environment-configurable trusted proxy policy via `TRUSTED_PROXIES`.
- For direct cPanel hosting (default), no proxy headers are trusted (`at: []`). Client IP resolution resolves strictly from `REMOTE_ADDR`.
- Spoofed `X-Forwarded-For`, `X-Forwarded-Proto`, and `Forwarded` headers are completely ignored when untrusted, preventing IP-spoofing rate limit bypasses and HTTPS detection spoofing.

### C. Authentication and Targeted Throttling
- Admin login in `AuthenticatedSessionController` now enforces a 3-tier rate limiting architecture:
  1. Combined Account + Trusted IP throttle (5 attempts, 15m lockout).
  2. Account-level throttle across distributed IPs (15 attempts, 15m lockout).
  3. Network IP-level throttle protecting against credential stuffing (20 attempts, 15m lockout).
- Rate limits are cleared upon successful authentication.
- Targeted rate limiting applied to `POST /forgot-password` (5 attempts / 15m) and `POST /reset-password` (5 attempts / 15m). Public informational endpoints retain targeted limits (`throttle:60,1` on `/faq/items` and `/valued-shareholders/items`) without applying blanket limits to normal public pages.
- Removed hardcoded default migration secret `'kli_2026_migrate_aiven'` from `routes/web.php`. `/admin/system/migrate` now fails closed unless an explicit secret is set in `.env` or the requester is an authenticated super administrator.

### D. Anti-Enumeration & Password Reset Hardening
- `PasswordResetController::store` returns an identical, indistinguishable response (`Password::RESET_LINK_SENT`) regardless of whether the email address exists in the database.
- Single-use reset tokens enforced: token record is purged upon successful password update; replay attempts fail.
- Expiration enforced: reset tokens expire after 60 minutes.

### E. Unified Strong Password Policy
- Created `App\Support\SecurityPolicy` providing standardized strong password rules across the application:
  - Minimum 8 characters
  - Mixed uppercase and lowercase letters
  - Numbers
  - Symbols
- Applied consistently across:
  - Admin profile password updates (`ProfileController`)
  - Password resets (`PasswordResetController`)
  - Interactive CLI creation (`CreateAdminCommand`)
  - CLI rotation (`RotateAdminPasswordCommand`)

### F. Administrator TOTP Multi-Factor Authentication (MFA)
- Added database migration `2026_07_09_000001_add_two_factor_columns_to_admins_table.php` on the `auth` connection (`two_factor_secret`, `two_factor_recovery_codes`, `two_factor_confirmed_at`).
- Model attributes `two_factor_secret` and `two_factor_recovery_codes` are encrypted at rest using Laravel's `encrypted` and `encrypted:array` casts, and added to `$hidden` to prevent accidental serialization.
- Created `App\Support\TotpService` implementing standard RFC 6238 TOTP (HMAC-SHA1, 30s period, 6 digits) and Base32 encoding/decoding with zero external dependencies. Verified against official RFC 6238 test vectors.
- Created `TwoFactorProfileController` for enrollment, confirmation, disabling, and recovery code regeneration. All security-sensitive actions require `current_password` confirmation.
- Created `TwoFactorAuthController` and view `resources/views/auth/two-factor-challenge.blade.php` for login MFA challenges with support for 6-digit TOTP codes and single-use emergency recovery codes.
- Burning/consuming recovery codes upon authentication verified.

### G. Session Revocation & Suspicious Login Auditing
- Password update or reset increments `session_version`, immediately invalidating all other active sessions via `EnsureAdminAuthenticated`.
- Suspicious login detection: authentications from a new or changed IP address trigger security audit warnings.
- Monolog processor `RedactSensitiveDataProcessor` registered in logging channels to automatically scrub passwords, tokens, recovery codes, cookies, and secret keys from context arrays.

### H. 429 Responses and Production Error Redaction
- Created `resources/views/errors/429.blade.php` matching the brand error layout.
- Configured `bootstrap/app.php` exception handler:
  - Throttled web requests render `errors.429`.
  - Throttled JSON/API requests return clean HTTP 429 JSON: `{"message": "Too many requests. Please slow down and try again later."}`.
  - Production unhandled exceptions return generic 500 JSON for API/JSON requests without disclosing SQL, stack traces, paths, or environment variables. Standard HTTP exceptions (404, 403, 401) preserve their status codes.

---

## 2. cPanel WAF, Edge, and Host Specification Requirements

Since cPanel hosting is the target deployment, the following edge and web server configurations are specified for the production hosting account:

| Control | Specification | Staging Status |
|---|---|---|
| **Trusted Proxies** | Default `TRUSTED_PROXIES=` empty for direct cPanel Apache. If Cloudflare is enabled in front of cPanel, set to Cloudflare IPv4/IPv6 CIDR blocks. | `NOT VERIFIED` (staging pending) |
| **Apache ModSecurity** | Enable OWASP ModSecurity Core Rule Set (CRS) on cPanel domain; verify no false positives on admin rich text editor. | `NOT VERIFIED` (staging pending) |
| **Max Request Body** | Enforce `LimitRequestBody 10485760` (10 MB) in Apache/cPanel to align with application 8 MB upload limits. | `NOT VERIFIED` (staging pending) |
| **Execution Timeouts** | PHP `max_execution_time = 60`, `max_input_time = 60` to mitigate slowloris/hung connections. | `NOT VERIFIED` (staging pending) |
| **Origin Protection** | If CDN/Cloudflare is active, configure `.htaccess` or cPanel IP Blocker to allow HTTP/HTTPS traffic only from edge proxy IPs. | `NOT VERIFIED` (staging pending) |
| **Origin Caching** | Disable public edge caching for `/admin/*`, `/login*`, and mutation endpoints; enable safe caching only for versioned build assets. | `NOT VERIFIED` (staging pending) |

---

## 3. Verification Commands and Results

| Command | Result | Details |
|---|---|---|
| `php artisan test tests/Unit/TotpServiceTest.php` | **PASS** | 5 tests, 28 assertions. Full RFC 6238 test vectors match. |
| `php artisan test tests/Feature/AuthenticationHardeningTest.php` | **PASS** | 18 tests, 84 assertions. All auth, proxy, MFA, and error tests pass. |
| `php artisan test --compact` | **PASS** | 87 tests, 449 assertions. Suite passes in normal order. |
| `php artisan test --compact --order-by=random --random-order-seed=20260927` | **PASS** | 87 tests, 449 assertions. Seed 20260927 stable. |
| `php artisan test --compact --order-by=random --random-order-seed=92701` | **PASS** | 87 tests, 449 assertions. Seed 92701 stable. |
| `php artisan test --compact --order-by=random --random-order-seed=92702` | **PASS** | 87 tests, 449 assertions. Seed 92702 stable. |
| `git ls-files "*.php" \| ForEach-Object { php -l $_ }` | **PASS** | All tracked PHP files report `No syntax errors detected`. |
| `composer validate --strict` | **PASS** | `composer.json` is valid. |
| `composer audit --locked` | **PASS** | 0 security vulnerability advisories found. |
| `npm run build` | **PASS** | 487 modules transformed; production build succeeds in 3.62s. |
| `git diff --check` | **PASS** | Zero whitespace or merge conflict errors. |

---

## 4. Stage 3 Exit Gate Evaluation

| Item | Requirement | Status | Notes |
|---|---|---|---|
| 1 | Login/reset user enumeration prevented | **PASS** | Indistinguishable response for existing and nonexistent accounts. |
| 2 | Spoofed proxy headers cannot bypass rate limits or auditing | **PASS** | `trustProxies('*')` removed; untrusted headers ignored. |
| 3 | Admin TOTP MFA with recovery codes and password confirmation | **PASS** | Enrollment, challenge, recovery burn, disable, and regeneration tested. |
| 4 | Authentication, authorization, IDOR, and session-revocation | **PASS** | Strict role guards, session version invalidation, and boundary tests pass. |
| 5 | Production errors disclose no internal details | **PASS** | Clean 429 and generic 500 error responses implemented and verified. |
| 6 | Sensitive logging redaction | **PASS** | Monolog processor strips passwords, tokens, and secrets from context. |
| 7 | Edge/CDN/WAF verification | **NOT VERIFIED** | Requires live cPanel/staging infrastructure with ModSecurity/WAF. |

### Exit-Gate Decision: `PASS (Repository-Local Hardening)`
All repository-local Stage 3 requirements, migrations, controllers, models, views, and unit/feature tests are complete and verified. External edge WAF/CDN verification is marked `NOT VERIFIED` until deployment to the cPanel staging environment.

### Safety Invariants
- **Lottie Animation:** Unchanged. Timing, layout, JSON assets, and appearance are completely intact.
- **Secrets:** No production secrets, tokens, recovery codes, or passwords were generated, committed, or logged.
- **Git Status:** Working tree preserved. No commits or pushes made.
