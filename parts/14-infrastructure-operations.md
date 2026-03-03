[← Previous Part](13-logging-monitoring-audit.md) | [Full Checklist](../checklist.md)

# Part XIV — Infrastructure & Operations

**16 sections · 208 checks**

- [15. Configuration, Secrets & Environment](#15-configuration-secrets-environment)
- [17. Dead Code & Unused Artifacts](#17-dead-code-unused-artifacts)
- [18. Dependency & Supply Chain Security](#18-dependency-supply-chain-security)
- [23. PR / Code Review Checklist (Quick Reference)](#23-pr-code-review-checklist-quick-reference)
- [32. Memory, Performance & Resource Exhaustion](#32-memory-performance-resource-exhaustion)
- [37. Deployment & Production Hardening](#37-deployment-production-hardening)
- [38. Git & Version Control Hygiene](#38-git-version-control-hygiene)
- [51. Debug & Development Tool Leakage](#51-debug-development-tool-leakage)
- [53. Backup & Disaster Recovery](#53-backup-disaster-recovery)
- [66. Environment Parity](#66-environment-parity)
- [67. Docker & Container Security](#67-docker-container-security)
- [68. PDF Generation Security](#68-pdf-generation-security)
- [69. Temporary File & Directory Safety](#69-temporary-file-directory-safety)
- [70. Process & Exec Safety](#70-process-exec-safety)
- [99. CI/CD Pipeline Checks](#99-cicd-pipeline-checks)
- [100. Post-Incident Review Checklist](#100-post-incident-review-checklist)

---

## 15. Configuration, Secrets & Environment

### Environment Variables

- [ ] **`APP_DEBUG=false` in production** — Debug mode exposes stack traces, env vars, and SQL queries.
- [ ] **`APP_ENV=production` in production** — Affects error display, caching, and service resolution.
- [ ] **Never commit `.env` to version control** — `.env` is in `.gitignore` by default. Keep it that way.
- [ ] **Use `php artisan env:encrypt` for deployment** — Encrypted `.env` files can be safely committed.
- [ ] **Never use `env()` outside `config/` files** — `env()` returns `null` after `config:cache`.
- [ ] **Cache config in production** — `php artisan config:cache`.
- [ ] **Cache routes in production** — `php artisan route:cache`.

### APP_KEY Security

- [ ] **Rotate `APP_KEY` periodically** — It encrypts sessions, cookies, and encrypted attributes. Thousands of `APP_KEY` values were leaked to GitHub in 2025.
- [ ] **Use `APP_PREVIOUS_KEYS` during rotation** — Old keys are tried for decryption, enabling gradual rotation.
- [ ] **Re-encrypt database columns after key rotation** — `encrypted` cast data must be re-encrypted with the new key.

### Secrets Management

- [ ] **No secrets in code, config files, or version control** — Use environment variables or secret managers.
- [ ] **Consider external secret managers for production** — AWS Secrets Manager, HashiCorp Vault, Google Secrets Manager.
- [ ] **Audit `.env.example` for real values** — Example files sometimes contain real API keys by mistake.

---


## 17. Dead Code & Unused Artifacts

### What to Check For

- [ ] **Unused imports** — PHPStan / Pint detect these automatically.
- [ ] **Unused model scopes** — `scopeActive()` that nothing calls.
- [ ] **Unused enum cases** — Enum cases with no references.
- [ ] **Unused Vue components** — Components never imported.
- [ ] **Unused composables** — `useXxx()` files never imported.
- [ ] **Orphaned routes** — Routes pointing to deleted controllers.
- [ ] **Unreachable controller methods** — Methods not bound to any route.
- [ ] **Dead config keys** — Config values never read.
- [ ] **Unused middleware** — Registered but never applied.
- [ ] **Duplicate enums** — Two enums representing the same concept.
- [ ] **Commented-out code** — Delete it. Version control has the history.
- [ ] **TODO/FIXME comments older than 30 days** — Either fix them or create tickets.

### Tools

- [ ] **PHPStan level 8+** — Catches dead code, type errors, unused imports.
- [ ] **Laravel Pint** — Removes unused imports, enforces coding standards.
- [ ] **ESLint / `vue-tsc`** — Catches unused vars, imports, and type errors in frontend.
- [ ] **`composer audit`** — Checks for known vulnerabilities in dependencies.
- [ ] **`npm audit`** — Checks for known vulnerabilities in npm dependencies.

---


## 18. Dependency & Supply Chain Security

- [ ] **Run `composer audit` regularly** — Check for known CVEs in PHP dependencies.
- [ ] **Run `npm audit` regularly** — Check for known CVEs in Node dependencies.
- [ ] **Commit lock files** — Both `composer.lock` and `package-lock.json` / `pnpm-lock.yaml` must be committed.
- [ ] **Review dependency updates** — Don't blindly upgrade. Check changelogs for breaking changes.
- [ ] **Minimize dependencies** — Every dependency is an attack surface. Prefer Laravel built-ins.
- [ ] **Pin major versions** — Use `^` constraints in composer.json, but review major version bumps.
- [ ] **Verify package integrity** — `composer validate` and check package signatures.
- [ ] **No typosquatting** — Double-check package names before installing.

---


## 23. PR / Code Review Checklist (Quick Reference)

Every PR should pass these checks before merge:

### Automated

- [ ] `vendor/bin/pint --dirty` — Code style
- [ ] `vendor/bin/phpstan analyse` — Static analysis (level 8)
- [ ] `php artisan test --compact` — All tests pass
- [ ] `npm run build` — Frontend compiles
- [ ] `composer audit` — No known vulnerabilities
- [ ] `npm audit` — No known vulnerabilities

### Security

- [ ] No hardcoded secrets, API keys, or credentials
- [ ] Authorization on every new controller method
- [ ] CSRF protection on every state-changing route
- [ ] Input validation via Form Request
- [ ] No raw SQL with user input
- [ ] No `v-html` / `{!! !!}` with user data
- [ ] No `$guarded = []` or missing `$fillable`
- [ ] Sensitive fields not in `$fillable`

### Data Integrity

- [ ] Multi-model writes wrapped in DB transactions
- [ ] `lockForUpdate()` for status-check-then-update patterns
- [ ] No float/bcmath arithmetic on money
- [ ] Idempotency for retryable operations
- [ ] State machine transitions via `transitionTo()`, not raw updates

### Quality

- [ ] `declare(strict_types=1)` on new PHP files
- [ ] Explicit return types on all new methods
- [ ] Enum references, not hardcoded strings
- [ ] Early returns / guard clauses (no deep nesting)
- [ ] Modern PHP syntax (match, enums, readonly, named args)
- [ ] Factory-based test setup
- [ ] Feature test for every new action
- [ ] No N+1 queries (eager loading where needed)

### Models

- [ ] `$fillable` declared (no privilege escalation fields)
- [ ] `SoftDeletes` trait
- [ ] `LogsActivity` trait with `getActivitylogOptions()`
- [ ] `casts()` method with appropriate casts
- [ ] Factory created (with useful states)
- [ ] `$hidden` on sensitive attributes

### Enums

- [ ] `allowedTransitions()` if used as state machine
- [ ] `isTerminal()` method
- [ ] `label()` / `color()` for UI display
- [ ] TypeScript mirror if used in frontend
- [ ] Registered in seeders if permission/role enum

### Events

- [ ] Audit log handler in reactor
- [ ] Metadata attached (via aggregate or `EmitsStoredEvents`)
- [ ] Data carried in event (not fetched from DB during replay)

### Routes

- [ ] Auth middleware applied
- [ ] 2FA middleware on destructive operations
- [ ] Rate limiting where appropriate
- [ ] Named route for Wayfinder / `route()` usage

---


## 32. Memory, Performance & Resource Exhaustion

### Unbounded Operations

- [ ] **Never load unbounded result sets into memory** — Use `chunk()`, `lazy()`, `cursor()`, or pagination.

```php
// DANGEROUS — loads all records
$users = User::all();
foreach ($users as $user) { ... }

// SAFE — processes in chunks
User::query()->chunk(500, function ($users) {
    foreach ($users as $user) { ... }
});

// SAFE — lazy collection (one query, one row at a time)
User::query()->lazy()->each(function ($user) { ... });
```

- [ ] **Paginate all index/list endpoints** — Use `->paginate()` or `->cursorPaginate()`. Never return unbounded collections.
- [ ] **Limit array sizes in validation** — `'items' => 'array|max:100'` prevents memory bombs.
- [ ] **Limit string lengths** — `'description' => 'string|max:10000'`.
- [ ] **Limit file upload sizes** — Both in validation and `php.ini` (`upload_max_filesize`, `post_max_size`).

### N+1 Detection

- [ ] **Enable `preventLazyLoading()` in development** — Throws exceptions on N+1 queries.
- [ ] **Use `preventSilentlyDiscardingAttributes()` in development** — Throws when filling non-fillable attributes.

```php
// AppServiceProvider::boot()
Model::preventLazyLoading(! app()->isProduction());
Model::preventSilentlyDiscardingAttributes(! app()->isProduction());
Model::preventAccessingMissingAttributes(! app()->isProduction());
```

### Query Performance

- [ ] **Use `select()` on queries** — Don't fetch 50 columns when you need 3.
- [ ] **Use `exists()` not `count() > 0`** — `EXISTS` stops at first match.
- [ ] **Use `value()` for single scalar values** — `User::where('id', $id)->value('email')`.
- [ ] **Use `pluck()` for single-column lists** — Returns flat collection.
- [ ] **Use database aggregation** — `->sum()`, `->avg()`, `->count()` at query level, not PHP loops.
- [ ] **Index WHERE and ORDER BY columns** — Use `EXPLAIN` to verify index usage.

### Memory Leaks in Long-Running Processes

- [ ] **Disable query log in queue workers** — `DB::disableQueryLog()` in job constructors or AppServiceProvider.
- [ ] **Flush event listeners in long loops** — `Event::forgetPushed()` if using `Event::fakeFor()`.
- [ ] **Call `gc_collect_cycles()` in batch processing** — For very long-running processes.

---


## 37. Deployment & Production Hardening

### Application Configuration

- [ ] **`APP_DEBUG=false`** — Prevents stack traces, env vars, and SQL queries from leaking.
- [ ] **`APP_ENV=production`** — Affects error display and debug toolbar visibility.
- [ ] **Cache everything** — `config:cache`, `route:cache`, `view:cache`, `event:cache`.
- [ ] **Run `composer install --no-dev`** — No development dependencies in production.
- [ ] **Run `npm run build`** — Compile frontend assets.

### Server Configuration

- [ ] **Web server points to `/public` directory** — Never serve the project root.
- [ ] **`.env` not accessible via HTTP** — Webserver config blocks dotfiles.
- [ ] **`storage/` not accessible via HTTP** — Only `storage/app/public` via symlink.
- [ ] **PHP `expose_php = Off`** — Hides PHP version from `X-Powered-By` header.
- [ ] **PHP `display_errors = Off`** — Even if Laravel catches them, belt-and-suspenders.
- [ ] **File permissions: dirs 755, files 644** — Storage/cache dirs may need 775.

### Database

- [ ] **Separate DB user for application** — Not root. Minimal privileges (SELECT, INSERT, UPDATE, DELETE on app tables only).
- [ ] **No `DROP`, `ALTER`, `CREATE` permissions for app user** — Migrations run under a separate admin connection or CI/CD.
- [ ] **Enable slow query log** — For performance monitoring.
- [ ] **Regular backups** — Automated, tested, offsite.

### Monitoring

- [ ] **Exception tracker in production** — Sentry, Flare, Bugsnag, or equivalent.
- [ ] **Uptime monitoring** — External health check endpoint.
- [ ] **Queue monitoring** — Horizon dashboard or alerts on queue depth.
- [ ] **Disk space monitoring** — Log files and upload storage can fill disks.

---


## 38. Git & Version Control Hygiene

### Secret Prevention

- [ ] **`.gitignore` includes `.env`, `storage/`, `vendor/`, `node_modules/`** — Verify these are present.
- [ ] **Pre-commit hooks scan for secrets** — Use `gitleaks`, `trufflehog`, or `detect-secrets`.
- [ ] **Never commit API keys, passwords, or tokens** — Even in test files. Use `.env` or fakes.
- [ ] **If a secret is committed, rotate it immediately** — Removing from history with `git filter-branch` is insufficient. The key is compromised.

### Branch Protection

- [ ] **Protect main/master branch** — Require PR reviews, passing CI, no force push.
- [ ] **No `--force` push to shared branches** — Use `--force-with-lease` if absolutely necessary.
- [ ] **No `--no-verify` commits** — Pre-commit hooks exist for a reason. Fix the hook issue, don't skip it.

### Commit Hygiene

- [ ] **Atomic commits** — One logical change per commit.
- [ ] **Descriptive commit messages** — `fix: prevent double-posting via lockForUpdate` not `fix stuff`.
- [ ] **No generated files in commits** — `composer.lock` and `package-lock.json` yes, but not `vendor/`, `node_modules/`, or compiled assets.

---


## 51. Debug & Development Tool Leakage

### Production Checks

- [ ] **Laravel Debugbar disabled in production** — Check `DEBUGBAR_ENABLED=false` or uninstall in production.
- [ ] **Laravel Telescope restricted in production** — Gate access to admin users only via `TelescopeServiceProvider::gate()`.
- [ ] **Horizon dashboard gated** — `Horizon::auth()` restricts dashboard access. Default is no access in production.
- [ ] **No `dd()`, `dump()`, `var_dump()`, `print_r()` left in code** — Use static analysis or pre-commit hooks to catch these.
- [ ] **No `ray()` calls left in code** — Ray debugging calls left in production send data to the developer's machine.
- [ ] **No `Log::debug()` with sensitive data** — Debug-level logs should be suppressed in production via log level config.
- [ ] **`/phpinfo` route doesn't exist** — `phpinfo()` exposes server config, PHP extensions, environment variables.

### Error Page Leakage

- [ ] **Custom error pages for 404, 403, 500, 503** — Don't show the default Laravel error page in production. It may hint at framework version.
- [ ] **Error responses don't include exception class names** — The class `App\Exceptions\InsufficientFundsException` reveals internal architecture. Return generic messages.

---


## 53. Backup & Disaster Recovery

### Backup Strategy

- [ ] **Automated daily backups** — Use `spatie/laravel-backup` or equivalent.
- [ ] **Backup includes database AND files** — Uploads, storage, `.env` (encrypted).
- [ ] **Offsite backup storage** — S3, GCS, or separate server. Not the same disk as the app.
- [ ] **Backup encryption** — Encrypt backups at rest with `spatie/laravel-backup`'s encryption support.
- [ ] **Backup retention policy** — Keep daily for 7 days, weekly for 4 weeks, monthly for 12 months.

### Recovery Testing

- [ ] **Test restore procedure quarterly** — Untested backups are not backups.
- [ ] **Document recovery steps** — Who does what, in what order, with what credentials.
- [ ] **Recovery Time Objective (RTO) defined** — How long can you be down?
- [ ] **Recovery Point Objective (RPO) defined** — How much data can you lose?

### Monitoring

- [ ] **Alert on backup failure** — `spatie/laravel-backup` fires events on success/failure.
- [ ] **Monitor backup size trends** — Sudden drops may indicate a broken backup.
- [ ] **Alert on backup age** — If no backup in 48 hours, something is wrong.

---


## 66. Environment Parity

- [ ] **Development matches production stack versions** — Same PHP, MySQL, Redis, Node versions.
- [ ] **Same database engine in tests** — SQLite tests miss MySQL-specific behavior (JSON queries, full-text search, strict mode). Use MySQL in CI.
- [ ] **Same queue driver in staging** — `sync` driver hides timing bugs. Use Redis in staging.
- [ ] **Same cache driver in staging** — `array` cache hides race conditions. Use Redis.
- [ ] **Same session driver in staging** — `file` driver hides scaling issues.
- [ ] **PHP strict mode matches** — `error_reporting`, `display_errors`, `strict_types` should match.

---


## 67. Docker & Container Security

- [ ] **Run as non-root user** — `USER www-data` in Dockerfile. Not `root`.
- [ ] **Minimal base images** — `php:8.4-fpm-alpine` over `php:8.4-fpm`. Smaller attack surface.
- [ ] **No secrets in Dockerfiles or build args** — Use runtime env vars or secret managers.
- [ ] **Pin image versions** — `php:8.4.3-fpm-alpine` not `php:latest`. Reproducible builds.
- [ ] **Read-only filesystem where possible** — `--read-only` flag. Write only to `/tmp` and mounted volumes.
- [ ] **Scan images for vulnerabilities** — `docker scout`, Trivy, or Snyk.
- [ ] **Don't install dev dependencies in production images** — Separate build stages. `composer install --no-dev` in runtime stage.

---


## 68. PDF Generation Security

- [ ] **Sanitize HTML before PDF rendering** — PDF engines (wkhtmltopdf, Puppeteer, Dompdf) execute HTML/CSS. Untrusted HTML = XSS inside PDF generator.
- [ ] **Disable JavaScript in PDF engines** — `--disable-javascript` for wkhtmltopdf. Prevents script execution.
- [ ] **Disable network access in PDF engines** — `--disable-local-file-access` and `--disable-external-links`. Prevents SSRF.
- [ ] **Limit PDF page count** — Unbounded input can generate million-page PDFs (DoS).
- [ ] **Queue PDF generation** — PDF rendering is CPU-intensive. Don't block web requests.
- [ ] **Serve PDFs as downloads** — `Content-Disposition: attachment`. Not inline (which could execute embedded JavaScript in some viewers).

---


## 69. Temporary File & Directory Safety

- [ ] **Use `sys_get_temp_dir()` or Laravel's `storage_path('temp/')`** — Not hardcoded `/tmp`.
- [ ] **Delete temp files after use** — Use `try/finally` or `register_shutdown_function()`.
- [ ] **Unique temp file names** — Use `tempnam()` or `Str::uuid()`. Never predictable names.
- [ ] **Don't serve temp directories via HTTP** — Ensure web server config blocks access.
- [ ] **Set restrictive permissions** — `0600` for temp files. Not world-readable.
- [ ] **Schedule temp cleanup** — Cron job to remove temp files older than 24 hours.

---


## 70. Process & Exec Safety

- [ ] **Use Laravel Process facade over raw `exec()`/`shell_exec()`**.

```php
// SAFE — no shell interpretation
$result = Process::run(['ffmpeg', '-i', $inputPath, '-o', $outputPath]);

// DANGEROUS — shell interprets the string
exec("ffmpeg -i {$inputPath} -o {$outputPath}");
```

- [ ] **Set timeout on all external processes** — `Process::timeout(30)->run(...)`.
- [ ] **Check exit codes** — `$result->successful()`, `$result->exitCode()`.
- [ ] **Capture stderr separately** — `$result->errorOutput()` for debugging.
- [ ] **Never pass user input to process arguments without escaping** — Even with array syntax, validate inputs.
- [ ] **Log process executions** — Command, exit code, duration. For audit trail.

---


## 99. CI/CD Pipeline Checks

### Required Pipeline Stages

- [ ] **Lint** — Pint (PHP), ESLint (JS/TS), Prettier (formatting).
- [ ] **Static analysis** — PHPStan level 8, `vue-tsc --noEmit`.
- [ ] **Unit & feature tests** — `php artisan test --compact`.
- [ ] **Build** — `npm run build` succeeds.
- [ ] **Security audit** — `composer audit`, `npm audit`.
- [ ] **Migration check** — `php artisan migrate --pretend` shows no errors.

### Pipeline Safety

- [ ] **Fail fast** — Lint and static analysis run before slow tests.
- [ ] **Parallel test execution** — `php artisan test --parallel` for faster feedback.
- [ ] **Cache dependencies** — `composer install` and `npm ci` cached between runs.
- [ ] **No secrets in CI logs** — Mask env variables. `echo "::add-mask::$SECRET"` in GitHub Actions.
- [ ] **Branch protection** — PRs require passing pipeline and review.
- [ ] **No deploy from local** — Only CI/CD pipeline deploys to production.
- [ ] **Rollback plan** — Every deployment has a documented rollback procedure.

---


## 100. Post-Incident Review Checklist

After any production incident, verify these were addressed:

### Immediate

- [ ] **Incident documented** — What happened, when, duration, impact.
- [ ] **Root cause identified** — Not just symptoms.
- [ ] **Fix deployed** — With test coverage for the specific failure.
- [ ] **Affected data assessed** — Any corrupted records? Any data loss?
- [ ] **Users notified** — If they were affected.

### Prevention

- [ ] **Monitoring added** — Alert that would have caught this sooner.
- [ ] **Test added** — Regression test for the specific bug.
- [ ] **Checklist updated** — This document. Add a new check for the failure class.
- [ ] **Related code audited** — Same pattern likely exists elsewhere.
- [ ] **Runbook updated** — Steps to diagnose and fix this class of issue.

### Review

- [ ] **Timeline reconstructed** — From first symptom to full resolution.
- [ ] **Detection time measured** — How long before someone noticed?
- [ ] **Response time measured** — How long from detection to fix deployed?
- [ ] **Blame-free retrospective held** — Focus on systems, not individuals.
- [ ] **Action items assigned and tracked** — With owners and deadlines.

---


---

[← Previous Part](13-logging-monitoring-audit.md) | [Full Checklist](../checklist.md)
