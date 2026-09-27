# Stage 1 Deterministic Test Isolation Evidence

- **Date:** 2026-09-27
- **Decision:** `PASS`
- **Baseline branch:** `security/production-hardening`

## Implementation

- Forced the default, auth, and content test connections to isolated in-memory SQLite databases.
- Added a pre-migration fail-closed guard requiring all three application connections to use in-memory SQLite.
- Replaced the one-time static migration pattern with Laravel `RefreshDatabase`.
- Enlisted all three connections in each test transaction and rollback.
- Recreated the canonical super-admin baseline inside every test transaction.
- Added regression tests for safe connection configuration and rejection of a non-SQLite application connection.

## Verification

| Run | Result |
|---|---|
| Previously failing random seed `20260927` | PASS — 53 tests, 297 assertions |
| Normal order | PASS — 53 tests, 297 assertions |
| Random seed `92701` | PASS — 53 tests, 297 assertions |
| Random seed `92702` | PASS — 53 tests, 297 assertions |
| Three full suites executed concurrently | PASS — no database/file collision |
| Hostile process environment declaring MySQL production-style names | PASS — test bootstrap forced isolated SQLite; 2 guard tests, 7 assertions |
| Pint on changed test files | PASS |
| PHP syntax and `git diff --check` | PASS |

The suite uses per-process in-memory databases, so concurrent workers do not share SQLite files. Paratest is not currently installed; CI may add it later, but separate concurrent full-suite processes already verified collision resistance.

## Exit gate

| Requirement | Decision |
|---|---|
| Repeated normal/randomized tests pass | PASS |
| Tests cannot use local/staging/production databases | PASS |
| Concurrent test processes do not collide | PASS |
| Existing nested rich-text XSS regression remains passing | PASS |

**Next:** Stage 2 — persistent storage, upload lifecycle, and data integrity.
