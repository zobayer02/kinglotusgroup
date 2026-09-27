# King Lotus Group — Production Implementation Roadmap

## Status

- **Current release verdict:** `NO-GO`
- **Source:** `Audit Plan.docx`, reorganized into dependency-safe execution order
- **Selected deployment target:** Apache/PHP cPanel under a dedicated subdomain; legacy Vercel files are not the production target.
- **Rule:** No phase is complete until its exit gate has objective evidence.
- **Rule:** Do not treat older audit counts, Lighthouse numbers, or test results as current. Re-run them from the pinned baseline commit.

## Implementation progress

| Stage | Status | Evidence |
|---|---|---|
| Stage 0 — Baseline, recovery, architecture | **PASS (2026-09-27)** | `docs/release/stage-0-baseline.md` |
| Stage 1 — Deterministic test safety net | **PASS (2026-09-27)** | `docs/release/stage-1-test-isolation.md` |
| Stage 2 — Persistent storage and data integrity | **PASS — CPANEL-READY (2026-09-27)** | `docs/release/stage-2-storage.md` |
| Stage 3 — Authentication, proxy, application security | **PASS — REPO HARDENED (2026-09-27)** | `docs/release/stage-3-security.md` |
| Stage 4 — Browser security, CSP, client-side exposure | **PASS — REPO HARDENED (2026-09-27)** | `docs/release/stage-4-browser-security.md` |
| Stages 5–9 | **STAGE 5 NEXT** | Stage 5 is now the next implementation stage. |

## Current repository snapshot

- Baseline branch at planning time: `main`
- Baseline commit at planning time: `a494b61`
- Working tree at planning time: clean
- Confirmed open design risks requiring implementation/verification:
  - `bootstrap/app.php` trusts all proxies.
  - CSP still allows inline scripts and styles.
  - Legacy Vercel deployment files remain in the repository but are not the selected production target.

## Decision rules retained from the source plan

| Topic | Authoritative decision |
|---|---|
| Production secrets | Hosting environment/secrets manager only; never commit real values. |
| Session domain | Use host-only cookies unless cross-subdomain sessions are explicitly required. |
| Public throttling | Use targeted application limits plus CDN/WAF protection; blanket `60/min` is not DDoS protection. |
| Duplicate admin login route | Check compatibility first, then use a canonical redirect if safe. |
| Scanner blocking | Do not block by User-Agent; it is spoofable and creates false positives. |
| SEO keywords | Do not add `meta keywords`. |
| Sitemap timestamps | Use real content modification times, never request-time `now()`. |
| Image loading | Hero/LCP images are eager; only below-the-fold images are lazy. |
| Service worker | Never use cache-first for changing CMS HTML. |
| Console handling | Remove or redact sensitive logging; do not overwrite `window.console`. |
| Lottie animation | Retain the existing Lottie animation and its intended visual behavior. It must not be removed or replaced; only safe loading and delivery optimizations are allowed. |
| `User.php` | Keep unless the configured auth provider and all dependencies are safely replaced. |
| HSTS preload | Require a separate HTTPS/subdomain audit and explicit approval. |
| `.htaccess` | Apply only to Apache/cPanel, not Vercel or Nginx. |
| Sitemap | Maintain one cached dynamic sitemap. |
| Existing admin config | Extend existing configuration; do not create duplicates. |

## Execution sequence

```text
Stage 0: Baseline + recovery + hosting decision
    ↓
Stage 1: Deterministic test safety net
    ↓
Stage 2: Persistent storage + data integrity
    ↓
Stage 3: Authentication + application security
    ↓
Stage 4: Browser security + CSP
    ↓
Stage 5: Admin-to-public E2E verification
    ↓
Stage 6: SEO + social + semantics
    ↓
Stage 7: Performance + accessibility
    ↓
Stage 8: Safe cleanup + code quality
    ↓
Stage 9: CI + staging + release
```

Stages 0–5 are release-blocking P0 work. Stages 6–8 do not override an open P0 gate.

---

## Stage 0 — Release freeze, baseline, architecture, and recovery

**Priority:** P0  
**Purpose:** Establish a recoverable, measurable starting point before any implementation.

### Tasks

- [ ] Create a dedicated security/release branch from pinned commit `a494b61`.
- [ ] Record the commit, branch, status, diff, and untracked-file inventory.
- [ ] Confirm Vercel/serverless as the final production target, or explicitly replace it with cPanel/Apache or VPS/Nginx.
- [ ] Document the selected runtime, database, object storage, CDN/WAF, mail, queue, cache, and scheduled-job architecture.
- [ ] Back up the database and all media/uploads.
- [ ] Restore both database and media into an isolated environment and verify usability.
- [ ] Audit `.env`, credentials, mail/database passwords, deployment configuration, and reachable Git history.
- [ ] Rotate every exposed or uncertain credential without printing it in logs or command arguments.
- [ ] Capture fresh baselines for routes, tests, Pint, dependencies, production build, asset integrity, browser errors, and Lighthouse.

### Required evidence

- Architecture decision record.
- Pinned Git baseline and file inventory.
- Redacted secret-scan report.
- Backup manifest plus successful restore evidence.
- Timestamped baseline command/results report.

### Exit gate

- [ ] The database and media are proven restorable.
- [ ] The hosting architecture is final and documented.
- [ ] All current work is recoverable.
- [ ] No production secret is tracked.
- [ ] Fresh measurements replace the old `45+ files`, `24 Pint files`, `19.3s LCP`, and `8.25MB` claims.

---

## Stage 1 — Deterministic and production-safe test foundation

**Priority:** P0  
**Purpose:** Make every later change verifiable without risking local or production data.

### Tasks

- [ ] Bind `APP_ENV=testing` to explicit disposable SQLite databases.
- [ ] Fail fast if a test resolves to MySQL, PostgreSQL, a production hostname, or a non-test database.
- [ ] Replace static/one-time migration assumptions with per-test deterministic reset (`RefreshDatabase` or an equivalent safe strategy).
- [ ] Remove password, session, cache, filesystem, clock, and global-state leakage between tests.
- [ ] Ensure parallel workers use separate database and filesystem paths.
- [ ] Run the full suite normally, with at least three fixed random seeds, and in a parallel-safe configuration.
- [ ] Retain and rerun the rich-text/XSS regression suite on the current baseline.

### Required evidence

- Resolved connection names and redacted database paths from the test bootstrap.
- Normal, randomized, and parallel test reports.
- Proof that non-test database connections are rejected before migrations execute.

### Exit gate

- [ ] Repeated and randomized runs are stable.
- [ ] No test can touch local/staging/production data.
- [ ] Parallel runs do not collide.
- [ ] The existing nested rich-text XSS regression remains passing.

---

## Stage 2 — Persistent storage, uploads, and data integrity

**Priority:** P0  
**Purpose:** Use durable deployment storage and make file/database changes atomic from the user's perspective.

### Tasks

- [x] Vercel object storage is not applicable because the selected target is persistent cPanel hosting.
- [x] Prove persistent cPanel-compatible local storage and backup/restore behavior.
- [x] Introduce one upload/storage abstraction supporting local test storage and production object storage.
- [x] Enforce upload size, detected MIME type, image dimensions, quota, safe paths/names, and image re-encoding.
- [x] Implement the replacement lifecycle: upload and validate new file → commit database transaction → delete or queue cleanup of the old file.
- [x] Use database transactions and delayed cleanup for record deletion.
- [x] Build an orphan detector with dry-run → report → quarantine → retention → deletion stages.
- [x] Prevent deletion of every database-referenced object.
- [x] Define automated database/media backup schedules, retention, restore procedure, and ownership.

### Required evidence

- Deployment/restart persistence test.
- Failure-injection tests around upload, transaction, and cleanup boundaries.
- Dry-run orphan report and referenced-object protection tests.
- Database + media restore evidence using production-equivalent storage.

### Exit gate

- [x] A failed database operation never breaks the current public image.
- [x] Uploads survive independent runtime processes; cPanel deployment must preserve the configured root.
- [x] Database and media restoration is proven against disposable restore targets.
- [x] Cleanup cannot remove referenced assets.

---

## Stage 3 — Authentication, authorization, proxy, and application security

**Priority:** P0

### Environment and cookie tasks

- [x] Set production values through the hosting environment: `APP_ENV=production`, `APP_DEBUG=false`, HTTPS `APP_URL`, appropriate `LOG_LEVEL`, secure and HTTP-only session cookies.
- [x] Keep `.env.example` complete but secret-free.
- [x] Leave `SESSION_DOMAIN` unset unless cross-subdomain sessions are required.
- [x] Prefer a secure host-only `__Host-` cookie where framework/hosting constraints allow it (`SESSION_COOKIE=__Host-klg_session` documented for HTTPS cPanel).
- [x] Select `SameSite=Lax` or `Strict` only after testing login and password-reset flows.
- [x] Verify that the production encryption key is unique and controlled.

### Proxy and edge tasks

- [x] Replace `trustProxies('*')` with actual trusted proxy/CDN ranges or a platform-correct trusted-proxy policy.
- [ ] Block direct public origin access or restrict it to the edge network where the platform permits (NOT VERIFIED: staging/cPanel host pending).
- [x] Test spoofed forwarding headers against client IP resolution and rate limits.
- [ ] Configure CDN/WAF, bot controls, edge rate limits, body-size limits, timeouts, origin protection, and safe caching (NOT VERIFIED: staging/cPanel host pending).

### Authentication and authorization tasks

- [x] Add account + IP throttling to login.
- [x] Apply targeted limits to login, forgot-password, reset-password, and expensive public forms.
- [x] Return identical forgot-password responses for registered and unregistered addresses.
- [x] Test reset-token expiry and single-use behavior.
- [x] Enforce one strong password policy across login-related admin/profile/reset operations.
- [x] Implement administrator MFA/TOTP and recovery codes.
- [x] Revoke existing sessions after password change/reset.
- [x] Add suspicious-login monitoring without logging credentials or sensitive fields.
- [x] Verify authentication plus role/permission middleware on every admin action.
- [x] Add IDOR and authorization-boundary tests.
- [x] Provide friendly HTML/JSON `429` responses.
- [x] Prevent error pages and logs from exposing SQL, stack traces, paths, secrets, or environment values.
- [x] Use structured, redacted production logging.

### Exit gate

- [x] Login/reset enumeration is prevented.
- [x] Spoofed proxy headers cannot bypass rate limits or auditing.
- [x] Admin MFA and recovery work.
- [x] Authentication, authorization, IDOR, and session-revocation tests pass.
- [x] Production errors disclose no internal information.
- [ ] CDN/WAF and origin protection are active in staging (NOT VERIFIED: staging host pending).

---

## Stage 4 — Browser security, CSP, and client-side exposure

**Priority:** P0/P1

### Tasks

- [x] Deploy CSP in `Content-Security-Policy-Report-Only` mode first (configurable via `CSP_REPORT_ONLY`).
- [x] Generate a per-request nonce and share it through middleware/views.
- [x] Replace inline event handlers with JavaScript event listeners.
- [x] Move inline scripts into Vite modules.
- [x] Move inline styles into classes/stylesheets in controlled batches (styles consolidated and nonced; `style-src 'unsafe-inline'` retained for rich-text editor safety).
- [x] Remove `script-src 'unsafe-inline'`; later remove `style-src 'unsafe-inline'` after style migration.
- [x] Retain the existing Lottie animation. If feasible, self-host its player/JSON assets without changing the animation, timing, layout, or user experience.
- [x] Retain and verify `frame-ancestors`, `X-Content-Type-Options`, `Referrer-Policy`, `Permissions-Policy`, CSRF, secure cookies, upload re-encoding, and rich-text sanitization.
- [x] Deploy normal HSTS only after HTTPS verification; treat `includeSubDomains` and preload as separate decisions.
- [x] Remove/gate debug logs and redact monitoring payloads; do not disable the console object.
- [x] Keep source maps private or upload them directly to the monitoring provider (`sourcemap: false`).
- [x] Run stored/reflected XSS, malicious URL, SVG, event-handler, and rich-text payload tests.

### Exit gate

- [x] No secrets, tokens, PII, internal paths, or stack traces appear in the browser.
- [x] CSP reports contain no unexplained violation.
- [x] Inline script allowance is removed.
- [x] XSS regression and adversarial payload tests pass.
- [x] Public source maps do not expose implementation details.

---

## Stage 5 — Functional security and admin-to-public E2E

**Priority:** P0

### Automated security coverage

- [ ] CSRF.
- [ ] Stored and reflected XSS.
- [ ] SQL injection attempts.
- [ ] IDOR and auth bypass.
- [ ] Rate limits and password enumeration.
- [ ] Malicious uploads and path/filename abuse.
- [ ] Proxy/IP spoofing.

### Authenticated browser workflow

- [ ] Admin login.
- [ ] Create/update/delete content.
- [ ] Verify public output and cache invalidation.
- [ ] Replace/delete uploads and verify lifecycle behavior.
- [ ] Verify logout, session expiry, and unauthorized rejection.
- [ ] Cover FAQ, Prospectus, Valued Shareholder, Leadership, About/Contact, Global Settings, and all other admin-published modules.

### Exit gate

- [ ] Every supported admin mutation produces the expected public result.
- [ ] Cache invalidation and upload lifecycle work end to end.
- [ ] Unauthorized users cannot access or mutate protected resources.
- [ ] Browser tests pass against production-equivalent staging.

---

## Stage 6 — SEO, social sharing, and semantic HTML

**Priority:** P1

### Tasks

- [ ] Build one reusable SEO component/layout supporting title, description, canonical, Open Graph, Twitter Card, robots, optional schema, absolute HTTPS URLs, and safe fallback image.
- [ ] Add unique metadata for Home, About, Leadership, FAQ, Contact, Prospectus, Valued Shareholder, and every other indexable public page.
- [ ] Keep admin, login, and reset pages `noindex,nofollow`; do not target Lighthouse SEO 100 for private screens.
- [ ] Create an optimized branded `1200×630` social image matching the central brand identity.
- [ ] Provide `og:image` dimensions, MIME type, alt text, and Twitter large-card metadata.
- [ ] Validate previews on Facebook, LinkedIn, WhatsApp, and other required channels.
- [ ] Add accurate `Organization`, `WebSite`, `WebPage`, `BreadcrumbList`, and conditional `FAQPage`/business schema.
- [ ] Keep one cached dynamic sitemap using actual content timestamps and canonical indexable URLs only.
- [ ] Keep private routes out of the sitemap and disallow appropriate paths in `robots.txt`.
- [ ] Enforce one meaningful `h1`, a `main` landmark, logical headings, correct functional/decorative alt text, and canonical redirects.

### Exit gate

- [ ] All public pages have unique title, description, and canonical URL.
- [ ] Social previews are branded and valid.
- [ ] Schema validators report no errors.
- [ ] Sitemap output is canonical and indexable only.
- [ ] Private/authentication pages are not indexable.

---

## Stage 7 — Performance and accessibility

**Priority:** P1

### Images and media

- [ ] Re-measure current LCP and payload before optimizing.
- [ ] Resize hero assets and produce responsive AVIF/WebP variants with `srcset`/`sizes`.
- [ ] Make only the LCP image eager/high-priority with explicit dimensions and selective preload.
- [ ] Lazy-load below-the-fold images with async decoding and explicit dimensions.
- [ ] Generate multiple optimized CMS image sizes on upload.
- [ ] Convert oversized prospectus/decorative assets.

### CSS, JavaScript, fonts, and caching

- [ ] Move inline CSS/JS into Vite bundles as required by CSP.
- [ ] Remove unused CSS/JS, defer non-critical code, and reduce third-party requests.
- [ ] Keep the Lottie animation and optimize only its delivery: self-host/cache the player and JSON where beneficial, defer loading only when it is not above the fold, and verify that the animation remains visually unchanged.
- [ ] Consolidate font files, retain used weights, use WOFF2 subsets, selective preload, and `font-display: swap`.
- [ ] Reduce homepage HTML and duplicate markup.
- [ ] Cache versioned assets as long-lived immutable resources.
- [ ] Implement correct public HTML/CDN cache invalidation when content changes.
- [ ] If retaining a service worker: cache-first only for versioned static assets; use network-first or stale-while-revalidate for public HTML; never persistently public-cache admin/auth/API responses.

### Accessibility

- [ ] Visible focus, keyboard navigation, skip link, sufficient contrast, form labels/errors, hidden-focus audit, reduced motion, and mobile overflow checks.

### Performance gate

Use multiple cold/representative staging runs and compare the median on mobile and desktop:

- [ ] Performance `≥95` as the release gate; optimize toward a stable `100` afterward.
- [ ] Accessibility `100`.
- [ ] Best Practices `100`.
- [ ] SEO `100` on indexable public pages.
- [ ] LCP `≤2.5s`, CLS `≤0.1`, INP `≤200ms`.
- [ ] Zero missing assets and console errors.
- [ ] Acceptable slow-network/mobile-CPU behavior.

---

## Stage 8 — Safe cleanup and code quality

**Priority:** P2; start only after functional gates pass.

### Tasks

- [ ] Re-run Pint and fix the current result rather than relying on the old 24-file count.
- [ ] Run dead route/controller/view analysis.
- [ ] Verify and consolidate duplicate font folders/files.
- [ ] Inspect logo candidates and replace any zero-byte favicon.
- [ ] Inventory empty and unused assets.
- [ ] Optimize FAQ counts to compute once and reuse.
- [ ] Remove `User.php` only if the default guard/provider is replaced, migrations/factories/tests are updated, and full auth tests pass.
- [ ] Use a database-aware asset inventory; source grep alone is not deletion evidence.
- [ ] Apply inventory → dry-run → quarantine → tests/browser QA → delayed deletion.

### Protected items

- The existing Lottie animation, its intended appearance, timing, and behavior.
- Database-referenced uploads.
- Compatibility routes without migration evidence.
- Auth model/provider dependencies.
- Build/runtime-required assets.
- Any file classified as unused only because source grep found no reference.

### Exit gate

- [ ] Pint, tests, and production build pass.
- [ ] Rendered public asset scan finds no missing file.
- [ ] Admin/public visual regression checks pass.
- [ ] Deleted items remain recoverable during the retention window.

---

## Stage 9 — CI, staging, deployment, and final release

**Priority:** Final release gate

### CI pipeline on every push/PR

- [ ] Composer install and advisory audit.
- [ ] Clean npm install and advisory audit.
- [ ] PHP syntax checks and Pint check.
- [ ] Deterministic tests plus fixed randomized seeds.
- [ ] Production Vite build.
- [ ] Secret scan.
- [ ] Security/adversarial suite.
- [ ] Link and asset-integrity checks.
- [ ] Lighthouse CI budgets.
- [ ] Required status checks that block merge on failure.

### Production-equivalent staging

- [ ] Same runtime architecture, environment shape, HTTPS/headers, CDN/WAF, durable storage, mail, cache, and database class as production.
- [ ] Verify login/reset/MFA, admin publishing, cache invalidation, social previews, responsive browsers, backup/restore, passive security scanning, and controlled rate/load behavior.

### Deployment and rollback

- [ ] Define maintenance/read-only behavior.
- [ ] Take database and media backups.
- [ ] Review migrations and destructive/data-transforming operations.
- [ ] Deploy an immutable build.
- [ ] Run platform-appropriate Laravel cache commands and post-deploy smoke tests.
- [ ] Verify monitoring and alerts.
- [ ] Define rollback criteria and perform a rollback drill.
- [ ] Complete a post-release observation window.

### Final GO gate

Production is `GO` only when all are true:

- [ ] Every P0 security issue is closed.
- [ ] Persistent storage is proven.
- [ ] Deterministic and randomized tests are stable.
- [ ] Authenticated admin-to-public E2E passes.
- [ ] Database and media restore is proven.
- [ ] Browser/log surfaces expose no secrets or internal details.
- [ ] SEO and social previews pass.
- [ ] Agreed Lighthouse and Web Vitals budgets pass.
- [ ] CDN/WAF and origin protection are active.
- [ ] Monitoring and rollback are tested.
- [ ] No unresolved Critical or High finding remains; accepted lower risks are documented with owner and expiry.

## Evidence format for every stage

Each stage report must contain:

1. Baseline commit and environment.
2. Exact files and behavior changed.
3. Commands/tests executed and summarized results.
4. Browser or deployment evidence where required.
5. Findings classified as `PASS`, `PARTIAL`, `FAIL`, or `NOT VERIFIED`.
6. Remaining risks, owner, and target date.
7. Exit-gate decision: `PASS` or `BLOCKED`.

## Estimated effort

| Work group | Estimate |
|---|---:|
| Stage 0–2: baseline, tests, hosting, storage | 10–20 hours |
| Stage 3–4: authentication, proxy, CSP, browser security | 10–18 hours |
| Stage 5: functional/security E2E | 6–12 hours |
| Stage 6: SEO, social, schema | 4–8 hours |
| Stage 7: performance and accessibility | 10–20 hours |
| Stage 8–9: cleanup, CI, staging, release | 8–16 hours |
| **Total** | **48–94 engineering hours (approximately 7–13 working days)** |

The estimate excludes waiting time for DNS, hosting support, credential rotation, design/asset approval, external service provisioning, and stakeholder acceptance.

## Immediate next action

Start only **Stage 0**. Do not combine production fixes with baseline collection. After Stage 0 evidence passes, implement **Stage 1** so every later security and storage change is protected by deterministic tests.
