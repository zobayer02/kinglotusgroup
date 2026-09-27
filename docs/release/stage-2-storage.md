# Stage 2 Persistent Storage and Data Integrity Evidence

- **Date:** 2026-09-27
- **Decision:** `PASS — CPANEL-READY`
- **Branch:** `security/production-hardening`
- **Selected target:** Apache/PHP cPanel under a dedicated subdomain

## Implementation

- Added a managed upload disk with a configurable persistent local root and optional S3 compatibility.
- Added a secret-free `.env.example` for the cPanel upload root, public URL, visibility, and application quota.
- Centralized managed path validation and public URL generation.
- Enforced an 8 MB per-file limit, detected image type, bounded dimensions/pixels, decode-memory limits, WebP re-encoding, and a configurable total-storage quota.
- Delayed replacement and deletion cleanup until after database persistence succeeds. Rollback removes newly written files and preserves the previous object.
- Wrapped destructive record deletion in a database transaction before delayed media cleanup.
- Added safe legacy-copy and orphan-audit commands. Both default to dry-run; legacy sources are retained.
- Added report, quarantine, retention, and explicit purge stages. Referenced objects are protected during quarantine and purge.
- Updated public models and admin previews to resolve upload URLs through the configured disk.

## cPanel production values

Set these in the non-public production `.env`; never commit or paste real credentials into chat:

```dotenv
APP_ENV=production
APP_DEBUG=false
APP_URL=https://subdomain.example.com
UPLOADS_DRIVER=local
UPLOADS_URL=https://subdomain.example.com
UPLOADS_LOCAL_ROOT=/home/CPANEL_ACCOUNT/path-to-app/public
UPLOADS_MAX_TOTAL_BYTES=1073741824
UPLOADS_VISIBILITY=public
```

Replace the example host, account, and path with the actual cPanel values. Keep the application quota below the cPanel account limit.

## Deployment and persistence procedure

1. Point the subdomain document root to the Laravel `public` directory.
2. Preserve `public/uploads` during every deployment and ensure the PHP account can write it without using `0777`.
3. Deploy application files and the prebuilt `public/build` directory.
4. Configure production `.env`, then run `php artisan optimize:clear` and `php artisan optimize`.
5. Run `php artisan uploads:migrate-legacy`; review the dry-run before any `--execute` operation.
6. Run `php artisan uploads:audit-orphans`; review its dry-run before quarantine or purge.
7. Upload a test image, restart the selected PHP version/PHP-FPM from cPanel, and confirm the same URL and file remain available.
8. Schedule database and `public/uploads` backups with an off-account copy, retention policy, owner, and periodic disposable restore drill.

### Backup ownership and retention

- The cPanel account owner operates and monitors daily database and media backups.
- The release owner verifies successful backup logs monthly and completes a disposable restore drill at least quarterly and before major releases.
- Retain at minimum 7 daily, 4 weekly, and 3 monthly recovery points, with at least one encrypted off-account copy.
- Record restore date, archive checksum, restored table/row counts, restored media count, and the person who approved the result.

## Verified evidence

| Check | Result |
|---|---|
| Managed upload lifecycle, rollback, quota, URL, orphan, purge, and migration tests | PASS — 11 tests, 40 assertions |
| Full application suite, normal order | PASS — 64 tests, 337 assertions |
| Full application suite, random seed `20260927` | PASS — 64 tests, 337 assertions |
| Composer validation and advisory audit | PASS — 0 known advisories |
| Production asset build | PASS — existing large admin-editor chunk warning remains |
| Public HTTP regression | PASS — 10 pages and 109 local assets |
| Cross-process persistent-disk probe | PASS — written, verified from a new PHP process, then removed |
| Legacy migration command | PASS — dry-run only; source retained |
| Orphan inventory | PASS — dry-run only; no files changed |
| Database backup and disposable restore | PASS — exact table/row verification in Stage 0 evidence |
| Media backup and disposable restore | PASS — 106 files and SHA-256 match in Stage 0 evidence |
| Referenced-object quarantine/purge protection | PASS |

## Exit gate

- A failed database operation does not replace the current public image.
- The selected cPanel persistent filesystem survives independent PHP processes/runtime restarts.
- Database and media restoration has been proven against disposable local targets.
- Cleanup and purge cannot remove referenced assets.

Actual subdomain DNS, HTTPS, PHP restart, cron scheduling, and off-account backup-job confirmation remain deployment/release checks and must be re-verified on cPanel before production traffic is enabled.
