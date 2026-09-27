# ADR-001: Production Hosting Architecture

- **Status:** Accepted
- **Date:** 2026-09-27
- **Baseline commit:** `a494b61abc6f4092f7101efab758133ec4de17f4`
- **Implementation branch:** `security/production-hardening`

## Decision

King Lotus Group will deploy to an Apache/PHP cPanel account under a dedicated subdomain. The subdomain document root must point to the Laravel `public` directory; application source, `.env`, databases, backups, and logs must not be web-accessible.

The repository still contains historical Vercel deployment files, but Vercel/serverless is no longer the selected production target. Their removal can be handled during release packaging after compatibility checks.

## Required production layout

| Component | Decision |
|---|---|
| Web runtime | cPanel Apache with PHP 8.2 or newer |
| Subdomain document root | Absolute path to `<application>/public` |
| Database | cPanel/managed MySQL with separate auth and content databases and least-privilege credentials |
| Media storage | Persistent `public/uploads` on the cPanel filesystem through the managed `uploads` disk |
| Upload root | `UPLOADS_LOCAL_ROOT` set to the absolute Laravel public-directory path |
| Upload URL | `UPLOADS_URL` set to the HTTPS subdomain origin |
| Session/cache/queue | Database-backed until deliberately replaced |
| Secrets | cPanel environment or non-public `.env`; never committed |
| Build | Vite production assets built before deployment and shipped in `public/build` |
| Backup | Scheduled database dumps plus `public/uploads`, with off-account retention and restore drills |

## Constraints

- The deployment process must preserve `public/uploads`; code releases must never replace or delete it.
- `public/uploads` must be writable by the PHP account using owner/group permissions such as `0755` or `0775`, never world-writable `0777`.
- The upload root must remain inside the intended subdomain public directory or a controlled persistent directory exposed through a reviewed symlink.
- Apache must honor `public/.htaccess` and `public/uploads/.htaccess`.
- Production must use HTTPS, `APP_ENV=production`, and `APP_DEBUG=false`.
- Keep the existing Lottie animation, appearance, timing, and behavior.
- HSTS preload requires a separate HTTPS/subdomain audit and approval.
- Production remains `NO-GO` until the later security and release stages pass.

## Consequences

- Stage 2 uses durable cPanel filesystem storage rather than mandatory object storage.
- PHP/runtime restarts do not affect uploaded media.
- Application-level size, type, dimension, path, lifecycle, and quota controls remain enforced.
- cPanel account quota is the hard storage ceiling; the application quota must be lower to preserve operational headroom.
- Database and media backups must be copied off-account so a single hosting failure cannot remove both production and backups.
