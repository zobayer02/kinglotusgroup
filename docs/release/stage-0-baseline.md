# Stage 0 Baseline and Recovery Evidence

- **Date:** 2026-09-27
- **Decision:** `PASS` for Stage 0; production remains `NO-GO`
- **Baseline commit:** `a494b61abc6f4092f7101efab758133ec4de17f4`
- **Implementation branch:** `security/production-hardening`
- **Architecture:** Vercel/serverless; see `architecture-decision.md`

## 1. Freeze and inventory

| Check | Result |
|---|---|
| Dedicated implementation branch | PASS — `security/production-hardening` |
| Baseline commit pinned | PASS |
| Repository file count | 291 |
| Routes | 47 total; 22 GET-capable; 29 admin; 14 public GET-capable |
| Local environment | Laravel 12.62.0, PHP 8.2.12, Composer 2.8.11 |
| Production-style asset build | PASS — 487 modules transformed |
| Build warning | Admin editor JavaScript chunk is 535.06 kB minified |

The working tree was clean before the roadmap/evidence files were added. Generated build, backup, and Lighthouse artifacts are ignored by Git.

## 2. Recovery proof

Backups are retained locally under the ignored directory:

`storage/app/release-backups/stage0-20260927-131943`

| Artifact | Backup result | Restore verification |
|---|---|---|
| Auth database | 238,756 bytes | PASS — 10 tables, 80 rows, exact per-table row counts |
| Content database | 45,731 bytes | PASS — 12 tables, 16 rows, exact per-table row counts |
| `public/uploads` | 26,734,817-byte archive | PASS — 106 files, 26,761,798 source bytes, SHA-256 match |

Database restores used newly created local disposable databases. Only those disposable restore databases and the temporary media extraction directory were removed after verification. Original databases and uploads were not changed.

## 3. Secret exposure checks

| Check | Result |
|---|---|
| `.env` ignored | PASS |
| Tracked `.env` / `.env.*` files | None |
| Historical `.env` paths | None found |
| Current-tree high-confidence secret patterns outside `.env` | None found |
| Git-history high-confidence secret patterns | None found |
| `.env.example` | Missing — must be added with placeholders only |

This is a local repository scan, not proof of hosting-provider configuration or credential rotation. Production environment values remain unverified and must never be copied into evidence files.

## 4. Static, test, build, and dependency baseline

| Gate | Result |
|---|---|
| Composer manifest validation | PASS |
| PHP syntax | PASS — 95 files |
| Normal PHPUnit run | PASS — 51 tests, 290 assertions |
| Randomized PHPUnit seed `20260927` | FAIL — 1 failed, 50 passed, 284 assertions |
| Laravel Pint | FAIL — 22 files |
| Vite production build | PASS |
| Composer advisory audit | PASS — 0 advisories, 0 abandoned packages |
| npm advisory audit | PASS — 0 vulnerabilities across 158 dependencies |

### Randomized failure

`Tests\Feature\SecurityHardeningTest::admin can update profile name email and mobile` failed because a prior randomized test changed shared admin password state. The profile request then received `Your current password is incorrect.`

This confirms that the suite's one-time migration/static database setup is not order-independent. Stage 1 must isolate/reset state per test and prove at least three randomized seeds plus parallel-safe execution.

## 5. Local HTTP and rendered asset baseline

| Check | Result |
|---|---|
| Selected public/auth/health pages | PASS — 10/10 returned HTTP 200 |
| Rendered local assets | PASS — 109 checked, 0 failures |
| Browser console errors reported by Lighthouse | 0 |
| `Content-Security-Policy` | Present |
| `Content-Security-Policy-Report-Only` | Missing |
| `X-Content-Type-Options` | Present |
| `Referrer-Policy` | Present |
| `Permissions-Policy` | Present |
| `X-Frame-Options` | Present |
| `Strict-Transport-Security` on local HTTP | Missing, expected until HTTPS staging verification |

Authenticated browser E2E was not performed in Stage 0. The in-app browser automation runtime was unavailable, so Stage 0 used local HTTP checks and Lighthouse. Authenticated E2E remains mandatory in Stage 5.

## 6. Lighthouse baseline

Reports and final screenshots are retained under the ignored directory:

`storage/app/release-baselines/stage0-20260927`

| Profile | Performance | Accessibility | Best Practices | SEO | LCP | CLS | TBT | Transfer |
|---|---:|---:|---:|---:|---:|---:|---:|---:|
| Mobile | 59 | 95 | 100 | 92 | 19.3 s | 0 | 110 ms | 8,329 KiB |
| Desktop | 76 | 95 | 100 | 92 | 2.8 s | 0 | 20 ms | 8,921 KiB |

Lighthouse 13.0.3 warned that Node 22.18.0 is slightly below its declared `>=22.19` engine requirement. Reports completed successfully, but the release pipeline must pin a supported Node version.

## 7. Confirmed blockers carried forward

1. Randomized tests are not isolated.
2. Vercel uploads are not on durable object storage.
3. `trustProxies('*')` remains.
4. CSP still permits inline scripts/styles and has no report-only migration stage.
5. `.env.example` is missing.
6. Pint fails in 22 files.
7. Mobile LCP is 19.3 seconds and transfer is over 8 MiB.
8. Production-equivalent staging, WAF/origin protection, mail delivery, MFA, monitoring, and rollback remain unverified.

## 8. Stage 0 exit gate

| Requirement | Decision |
|---|---|
| Database and media proven restorable | PASS |
| Hosting architecture final and documented | PASS |
| Current work recoverable | PASS |
| No production secret tracked in the inspected repository/history | PASS (local evidence) |
| Fresh baseline replaces old measurements | PASS |

**Next:** Stage 1 — deterministic and production-safe test foundation. Do not start Stage 2 until normal, multiple randomized, and parallel-safe test runs pass without touching non-test databases.
