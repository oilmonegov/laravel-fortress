---
description: "Laravel Fortress Part 14 — Infrastructure & Operations. 16 sections, 208 checks covering Config, deployment, CI/CD, Docker, backups, Git hygiene."
---

# Fortress: Infrastructure & Operations

> Part XIV of The Laravel Fortress — 16 sections · 208 checks
> https://github.com/oilmonegov/laravel-fortress/blob/main/parts/14-infrastructure-operations.md

When writing or reviewing code, enforce these rules.
Check `.fortress.yml` in the project root for overrides.

## Project Awareness

Before enforcing rules, read `composer.json` and `package.json` to detect the project's PHP version,
Laravel version, and installed packages. Skip rules whose conditions don't match the detected stack.

## Rules

### Environment Variables

[F-P14-001] **WARNING** — `APP_DEBUG=false` in production
[F-P14-002] **WARNING** — `APP_ENV=production` in production
[F-P14-003] **WARNING** — Never commit `.env` to version control
[F-P14-004] **WARNING** — Use `php artisan env:encrypt` for deployment
[F-P14-005] **WARNING** — Never use `env()` outside `config/` files
[F-P14-006] **WARNING** — Cache config in production
[F-P14-007] **WARNING** — Cache routes in production

### APP_KEY Security

[F-P14-008] **WARNING** — Rotate `APP_KEY` periodically
[F-P14-009] **WARNING** — Use `APP_PREVIOUS_KEYS` during rotation
[F-P14-010] **WARNING** — Re-encrypt database columns after key rotation

### Secrets Management

[F-P14-011] **WARNING** — No secrets in code, config files, or version control
[F-P14-012] **WARNING** — Consider external secret managers for production
[F-P14-013] **WARNING** — Audit `.env.example` for real values

### What to Check For

[F-P14-014] **WARNING** — Unused imports
[F-P14-015] **WARNING** — Unused model scopes
[F-P14-016] **WARNING** — Unused enum cases
[F-P14-017] **WARNING** — Unused Vue components
[F-P14-018] **WARNING** — Unused composables
[F-P14-019] **WARNING** — Orphaned routes
[F-P14-020] **WARNING** — Unreachable controller methods
[F-P14-021] **WARNING** — Dead config keys
[F-P14-022] **WARNING** — Unused middleware
[F-P14-023] **WARNING** — Duplicate enums
[F-P14-024] **WARNING** — Commented-out code
[F-P14-025] **WARNING** — TODO/FIXME comments older than 30 days

### Tools

[F-P14-026] **WARNING** — PHPStan level 8+
[F-P14-027] **WARNING** — Laravel Pint
[F-P14-028] **WARNING** — ESLint / `vue-tsc`
[F-P14-029] **WARNING** — `composer audit`
[F-P14-030] **WARNING** — `npm audit`

### Dependency & Supply Chain Security

[F-P14-031] **WARNING** — Run `composer audit` regularly
[F-P14-032] **WARNING** — Run `npm audit` regularly
[F-P14-033] **WARNING** — Commit lock files
[F-P14-034] **WARNING** — Review dependency updates
[F-P14-035] **WARNING** — Minimize dependencies
[F-P14-036] **WARNING** — Pin major versions
[F-P14-037] **WARNING** — Verify package integrity
[F-P14-038] **WARNING** — No typosquatting

### Automated

[F-P14-039] **WARNING** — vendor/bin/pint --dirty
[F-P14-040] **WARNING** — vendor/bin/phpstan analyse
[F-P14-041] **WARNING** — php artisan test --compact
[F-P14-042] **WARNING** — npm run build
[F-P14-043] **WARNING** — composer audit
[F-P14-044] **WARNING** — npm audit

### Security

[F-P14-045] **WARNING** — No hardcoded secrets, API keys, or credentials
[F-P14-046] **WARNING** — Authorization on every new controller method
[F-P14-047] **WARNING** — CSRF protection on every state-changing route
[F-P14-048] **WARNING** — Input validation via Form Request
[F-P14-049] **WARNING** — No raw SQL with user input
[F-P14-050] **WARNING** — No `v-html` / `{!! !!}` with user data
[F-P14-051] **WARNING** — No `$guarded = []` or missing `$fillable`
[F-P14-052] **WARNING** — Sensitive fields not in `$fillable`

### Data Integrity

[F-P14-053] **WARNING** — Multi-model writes wrapped in DB transactions
[F-P14-054] **WARNING** — lockForUpdate()
[F-P14-055] **WARNING** — No float/bcmath arithmetic on money
[F-P14-056] **WARNING** — Idempotency for retryable operations
[F-P14-057] **WARNING** — State machine transitions via `transitionTo()`, not raw updates

### Quality

[F-P14-058] **WARNING** — declare(strict_types=1)
[F-P14-059] **WARNING** — Explicit return types on all new methods
[F-P14-060] **WARNING** — Enum references, not hardcoded strings
[F-P14-061] **WARNING** — Early returns / guard clauses (no deep nesting)
[F-P14-062] **WARNING** — Modern PHP syntax (match, enums, readonly, named args)
[F-P14-063] **WARNING** — Factory-based test setup
[F-P14-064] **WARNING** — Feature test for every new action
[F-P14-065] **WARNING** — No N+1 queries (eager loading where needed)

### Models

[F-P14-066] **WARNING** — $fillable
[F-P14-067] **WARNING** — SoftDeletes
[F-P14-068] **WARNING** — LogsActivity
[F-P14-069] **WARNING** — casts()
[F-P14-070] **WARNING** — Factory created (with useful states)
[F-P14-071] **WARNING** — $hidden

### Enums

[F-P14-072] **WARNING** — allowedTransitions()
[F-P14-073] **WARNING** — isTerminal()
[F-P14-074] **WARNING** — label()
[F-P14-075] **WARNING** — TypeScript mirror if used in frontend
[F-P14-076] **WARNING** — Registered in seeders if permission/role enum

### Events

[F-P14-077] **WARNING** — Audit log handler in reactor
[F-P14-078] **WARNING** — Metadata attached (via aggregate or `EmitsStoredEvents`)
[F-P14-079] **WARNING** — Data carried in event (not fetched from DB during replay)

### Routes

[F-P14-080] **WARNING** — Auth middleware applied
[F-P14-081] **WARNING** — 2FA middleware on destructive operations
[F-P14-082] **WARNING** — Rate limiting where appropriate
[F-P14-083] **WARNING** — Named route for Wayfinder / `route()` usage

### Unbounded Operations

[F-P14-084] **WARNING** — Never load unbounded result sets into memory
[F-P14-085] **WARNING** — Paginate all index/list endpoints
[F-P14-086] **WARNING** — Limit array sizes in validation
[F-P14-087] **WARNING** — Limit string lengths
[F-P14-088] **WARNING** — Limit file upload sizes

### N+1 Detection

[F-P14-089] **WARNING** — Enable `preventLazyLoading()` in development
[F-P14-090] **WARNING** — Use `preventSilentlyDiscardingAttributes()` in development

### Query Performance

[F-P14-091] **WARNING** — Use `select()` on queries
[F-P14-092] **WARNING** — Use `exists()` not `count() > 0`
[F-P14-093] **WARNING** — Use `value()` for single scalar values
[F-P14-094] **WARNING** — Use `pluck()` for single-column lists
[F-P14-095] **WARNING** — Use database aggregation
[F-P14-096] **WARNING** — Index WHERE and ORDER BY columns

### Memory Leaks in Long-Running Processes

[F-P14-097] **WARNING** — Disable query log in queue workers
[F-P14-098] **WARNING** — Flush event listeners in long loops
[F-P14-099] **WARNING** — Call `gc_collect_cycles()` in batch processing

### Application Configuration

[F-P14-100] **WARNING** — `APP_DEBUG=false`
[F-P14-101] **WARNING** — `APP_ENV=production`
[F-P14-102] **WARNING** — Cache everything
[F-P14-103] **WARNING** — Run `composer install --no-dev`
[F-P14-104] **WARNING** — Run `npm run build`

### Server Configuration

[F-P14-105] **WARNING** — Web server points to `/public` directory
[F-P14-106] **WARNING** — `.env` not accessible via HTTP
[F-P14-107] **WARNING** — `storage/` not accessible via HTTP
[F-P14-108] **WARNING** — PHP `expose_php = Off`
[F-P14-109] **WARNING** — PHP `display_errors = Off`
[F-P14-110] **WARNING** — File permissions: dirs 755, files 644

### Database

[F-P14-111] **WARNING** — Separate DB user for application
[F-P14-112] **WARNING** — No `DROP`, `ALTER`, `CREATE` permissions for app user
[F-P14-113] **WARNING** — Enable slow query log
[F-P14-114] **WARNING** — Regular backups

### Monitoring

[F-P14-115] **WARNING** — Exception tracker in production
[F-P14-116] **WARNING** — Uptime monitoring
[F-P14-117] **WARNING** — Queue monitoring
[F-P14-118] **WARNING** — Disk space monitoring

### Secret Prevention

[F-P14-119] **WARNING** — `.gitignore` includes `.env`, `storage/`, `vendor/`, `node_modules/`
[F-P14-120] **WARNING** — Pre-commit hooks scan for secrets
[F-P14-121] **WARNING** — Never commit API keys, passwords, or tokens
[F-P14-122] **WARNING** — If a secret is committed, rotate it immediately

### Branch Protection

[F-P14-123] **WARNING** — Protect main/master branch
[F-P14-124] **WARNING** — No `--force` push to shared branches
[F-P14-125] **WARNING** — No `--no-verify` commits

### Commit Hygiene

[F-P14-126] **WARNING** — Atomic commits
[F-P14-127] **WARNING** — Descriptive commit messages
[F-P14-128] **WARNING** — No generated files in commits

### Production Checks

[F-P14-129] **WARNING** — Laravel Debugbar disabled in production
[F-P14-130] **WARNING** — Laravel Telescope restricted in production
[F-P14-131] **WARNING** — Horizon dashboard gated
[F-P14-132] **WARNING** — No `dd()`, `dump()`, `var_dump()`, `print_r()` left in code
[F-P14-133] **WARNING** — No `ray()` calls left in code
[F-P14-134] **WARNING** — No `Log::debug()` with sensitive data
[F-P14-135] **WARNING** — `/phpinfo` route doesn't exist

### Error Page Leakage

[F-P14-136] **WARNING** — Custom error pages for 404, 403, 500, 503
[F-P14-137] **WARNING** — Error responses don't include exception class names

### Backup Strategy

[F-P14-138] **WARNING** — Automated daily backups
[F-P14-139] **WARNING** — Backup includes database AND files
[F-P14-140] **WARNING** — Offsite backup storage
[F-P14-141] **WARNING** — Backup encryption
[F-P14-142] **WARNING** — Backup retention policy

### Recovery Testing

[F-P14-143] **WARNING** — Test restore procedure quarterly
[F-P14-144] **WARNING** — Document recovery steps
[F-P14-145] **WARNING** — Recovery Time Objective (RTO) defined
[F-P14-146] **WARNING** — Recovery Point Objective (RPO) defined

### Monitoring

[F-P14-147] **WARNING** — Alert on backup failure
[F-P14-148] **WARNING** — Monitor backup size trends
[F-P14-149] **WARNING** — Alert on backup age

### Environment Parity

[F-P14-150] **WARNING** — Development matches production stack versions
[F-P14-151] **WARNING** — Same database engine in tests
[F-P14-152] **WARNING** — Same queue driver in staging
[F-P14-153] **WARNING** — Same cache driver in staging
[F-P14-154] **WARNING** — Same session driver in staging
[F-P14-155] **WARNING** — PHP strict mode matches

### Docker & Container Security

[F-P14-156] **WARNING** — Run as non-root user
[F-P14-157] **WARNING** — Minimal base images
[F-P14-158] **WARNING** — No secrets in Dockerfiles or build args
[F-P14-159] **WARNING** — Pin image versions
[F-P14-160] **WARNING** — Read-only filesystem where possible
[F-P14-161] **WARNING** — Scan images for vulnerabilities
[F-P14-162] **WARNING** — Don't install dev dependencies in production images

### PDF Generation Security

[F-P14-163] **WARNING** — Sanitize HTML before PDF rendering
[F-P14-164] **WARNING** — Disable JavaScript in PDF engines
[F-P14-165] **WARNING** — Disable network access in PDF engines
[F-P14-166] **WARNING** — Limit PDF page count
[F-P14-167] **WARNING** — Queue PDF generation
[F-P14-168] **WARNING** — Serve PDFs as downloads

### Temporary File & Directory Safety

[F-P14-169] **WARNING** — Use `sys_get_temp_dir()` or Laravel's `storage_path('temp/')`
[F-P14-170] **WARNING** — Delete temp files after use
[F-P14-171] **WARNING** — Unique temp file names
[F-P14-172] **WARNING** — Don't serve temp directories via HTTP
[F-P14-173] **WARNING** — Set restrictive permissions
[F-P14-174] **WARNING** — Schedule temp cleanup

### Process & Exec Safety

[F-P14-175] **WARNING** — Use Laravel Process facade over raw `exec()`/`shell_exec()`
[F-P14-176] **WARNING** — Set timeout on all external processes
[F-P14-177] **WARNING** — Check exit codes
[F-P14-178] **WARNING** — Capture stderr separately
[F-P14-179] **WARNING** — Never pass user input to process arguments without escaping
[F-P14-180] **WARNING** — Log process executions

### Required Pipeline Stages

[F-P14-181] **WARNING** — Lint
[F-P14-182] **WARNING** — Static analysis
[F-P14-183] **WARNING** — Unit & feature tests
[F-P14-184] **WARNING** — Build
[F-P14-185] **WARNING** — Security audit
[F-P14-186] **WARNING** — Migration check

### Pipeline Safety

[F-P14-187] **WARNING** — Fail fast
[F-P14-188] **WARNING** — Parallel test execution
[F-P14-189] **WARNING** — Cache dependencies
[F-P14-190] **WARNING** — No secrets in CI logs
[F-P14-191] **WARNING** — Branch protection
[F-P14-192] **WARNING** — No deploy from local
[F-P14-193] **WARNING** — Rollback plan

### Immediate

[F-P14-194] **WARNING** — Incident documented
[F-P14-195] **WARNING** — Root cause identified
[F-P14-196] **WARNING** — Fix deployed
[F-P14-197] **WARNING** — Affected data assessed
[F-P14-198] **WARNING** — Users notified

### Prevention

[F-P14-199] **WARNING** — Monitoring added
[F-P14-200] **WARNING** — Test added
[F-P14-201] **WARNING** — Checklist updated
[F-P14-202] **WARNING** — Related code audited
[F-P14-203] **WARNING** — Runbook updated

### Review

[F-P14-204] **WARNING** — Timeline reconstructed
[F-P14-205] **WARNING** — Detection time measured
[F-P14-206] **WARNING** — Response time measured
[F-P14-207] **WARNING** — Blame-free retrospective held
[F-P14-208] **WARNING** — Action items assigned and tracked
