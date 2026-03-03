[← Previous Part](11-testing-quality-assurance.md) | [Full Checklist](../checklist.md) | [Next Part →](13-logging-monitoring-audit.md)

# Part XII — APIs, Queues & Integration

**16 sections · 136 checks**

- [22. Queue & Job Safety](#22-queue-job-safety)
- [45. Webhook Receiving & Replay Prevention](#45-webhook-receiving-replay-prevention)
- [46. Multi-Tenancy & Data Isolation](#46-multi-tenancy-data-isolation)
- [47. Console Command Safety](#47-console-command-safety)
- [48. Scheduling & Cron Safety](#48-scheduling-cron-safety)
- [50. Notification Channel Safety](#50-notification-channel-safety)
- [56. Dependency Injection Discipline](#56-dependency-injection-discipline)
- [58. HTTP Client & External API Safety](#58-http-client-external-api-safety)
- [59. Feature Flags & Rollout Safety](#59-feature-flags-rollout-safety)
- [62. Redis Security](#62-redis-security)
- [87. Image & Media Processing Security](#87-image-media-processing-security)
- [88. Search & Full-Text Safety](#88-search-full-text-safety)
- [197. API Versioning Strategies](#197-api-versioning-strategies)
- [198. Circuit Breaker Pattern](#198-circuit-breaker-pattern)
- [199. Webhook Sending Best Practices](#199-webhook-sending-best-practices)
- [200. Message Queue Reliability Patterns](#200-message-queue-reliability-patterns)

---

## 22. Queue & Job Safety

- [ ] **All external HTTP calls are queued** — Email, webhooks, API calls, PDF generation.
- [ ] **Set `$tries`, `$maxExceptions`, `$timeout`** — Prevent infinite retries and zombie jobs.

```php
class ProcessPayment implements ShouldQueue
{
    public int $tries = 3;
    public int $maxExceptions = 2;
    public int $timeout = 60;
    public int $backoff = 30;

    public function handle(): void { /* ... */ }

    public function failed(\Throwable $exception): void
    {
        // Notify team, log, create incident
    }
}
```

- [ ] **Implement `failed()` method** — Handle permanent failures with logging/alerting.
- [ ] **Use `ShouldBeUnique` for non-concurrent jobs** — Prevent duplicate processing.
- [ ] **Use `ShouldBeEncrypted` for jobs with sensitive data** — Encrypt the serialized payload.
- [ ] **Rate limit job dispatching** — Use `Bus::batch()` with `allowFailures()` for bulk operations.
- [ ] **Monitor queue depth** — Alert when queues back up beyond threshold.
- [ ] **Idempotent job design** — Jobs may run more than once. Ensure repeated execution is safe.
- [ ] **Don't serialize Eloquent models with sensitive data** — Jobs serialize model IDs, then re-query. Be aware of what's serialized.

---


## 45. Webhook Receiving & Replay Prevention

### Signature Verification

- [ ] **Verify webhook signatures before processing** — Use HMAC-SHA256. Reject unsigned or invalid requests.

```php
public function handle(Request $request): Response
{
    $payload = $request->getContent();
    $signature = $request->header('X-Webhook-Signature');
    $secret = config('services.provider.webhook_secret');

    $expected = hash_hmac('sha256', $payload, $secret);

    if (! hash_equals($expected, $signature)) {
        abort(403, 'Invalid signature');
    }

    // Process webhook...
}
```

- [ ] **Use `hash_equals()` for timing-safe comparison** — Not `===`.

### Replay Attacks

- [ ] **Check timestamp freshness** — Reject webhooks older than 5 minutes (or provider's recommended tolerance).

```php
$timestamp = $request->header('X-Webhook-Timestamp');
if (abs(time() - (int) $timestamp) > 300) {
    abort(403, 'Webhook too old');
}
```

- [ ] **Idempotency via event ID** — Store processed webhook IDs. Reject duplicates.

```php
$eventId = $request->header('X-Webhook-Id');
if (WebhookLog::where('event_id', $eventId)->exists()) {
    return response()->json(['status' => 'already_processed'], 200);
}
WebhookLog::create(['event_id' => $eventId, 'payload' => $payload]);
```

### Processing Safety

- [ ] **Queue webhook processing** — Return 200 immediately, process async. Providers retry on timeout.
- [ ] **Return 200 even for ignored events** — Non-2xx triggers retries. Silently ignore events you don't handle.
- [ ] **Log all webhook attempts** — Both successful and failed. Include payload hash (not full payload for PII reasons).
- [ ] **Allowlist webhook source IPs** — If the provider publishes IP ranges.

---


## 46. Multi-Tenancy & Data Isolation

### Query Scoping

- [ ] **Global scope or middleware-based tenant filtering** — Every query must be scoped to the current tenant. One missed scope = cross-tenant data leak.

```php
// Global scope approach
class TenantScope implements Scope
{
    public function apply(Builder $builder, Model $model): void
    {
        $builder->where('tenant_id', app('tenant')->id);
    }
}

// Middleware approach — set tenant context
public function handle(Request $request, Closure $next): Response
{
    $tenant = Tenant::findByDomain($request->getHost());
    app()->instance('tenant', $tenant);
    return $next($request);
}
```

- [ ] **Test cross-tenant isolation** — Explicitly verify that tenant A cannot access tenant B's data.
- [ ] **Audit raw queries for missing tenant filters** — `DB::select()` and `DB::table()` bypass global scopes.

### Shared Resources

- [ ] **Tenant-specific encryption keys** — Don't use the same `APP_KEY` for encrypting all tenants' data (if required by compliance).
- [ ] **Tenant-specific cache prefixes** — `Cache::tags(['tenant:' . $tenantId])` or config-level prefix.
- [ ] **Queue jobs carry tenant context** — Serialize the tenant ID, restore context in `handle()`.
- [ ] **Scheduled commands run per-tenant** — Iterate tenants, set context, then execute.

---


## 47. Console Command Safety

### Input Validation

- [ ] **Validate arguments and options** — Console input is user input. Validate with `$this->argument()` checks.
- [ ] **Use confirmations for destructive commands** — `$this->confirm('Are you sure?')` or `--force` flag.

```php
public function handle(): int
{
    if (! $this->option('force') && ! $this->confirm('This will delete all expired records. Continue?')) {
        return self::FAILURE;
    }

    // Proceed...
    return self::SUCCESS;
}
```

- [ ] **Return proper exit codes** — `self::SUCCESS` (0), `self::FAILURE` (1), `self::INVALID` (2).

### Production Safety

- [ ] **Gate dangerous commands behind environment checks** — `if (app()->isProduction() && ! $this->option('force'))`.
- [ ] **Never run `migrate:fresh` or `db:wipe` in production** — Use `migrate` only.
- [ ] **Log all command executions** — Who ran what, when, with what arguments.
- [ ] **Use `--no-interaction` in CI/CD** — Prevents prompts from hanging deployments.
- [ ] **No `dd()` or `dump()` in commands** — Use `$this->info()`, `$this->warn()`, `$this->error()`.

---


## 48. Scheduling & Cron Safety

### Overlap Prevention

- [ ] **Use `->withoutOverlapping()`** — Prevents a scheduled job from running if the previous instance is still running.

```php
$schedule->command('reports:generate')
    ->dailyAt('02:00')
    ->withoutOverlapping(30)  // Lock expires after 30 minutes
    ->onOneServer();          // Only one server in multi-server
```

- [ ] **Use `->onOneServer()`** — In load-balanced environments, ensures only one server runs the job.

### Timezone & Timing

- [ ] **Specify timezone explicitly** — `->timezone('UTC')` or set in `scheduleTimezone()` method.
- [ ] **Use `->evenInMaintenanceMode()`** — For critical jobs (backups, health checks) that must run during maintenance.
- [ ] **Monitor schedule health** — Use `->pingBefore()` / `->thenPing()` or Laravel's schedule health check.

### Failure Handling

- [ ] **Use `->onFailure()` for alerting** — Send notification or log when a scheduled task fails.
- [ ] **Use `->emailOutputOnFailure()`** — Get command output when something breaks.
- [ ] **Test scheduled commands independently** — `php artisan schedule:test` to verify timing.

---


## 50. Notification Channel Safety

### Database Notifications

- [ ] **Don't store sensitive data in notification payloads** — Notification data is stored as JSON in the `notifications` table. Anyone with DB access can read it.
- [ ] **Set a retention policy** — Old notifications accumulate. Prune with `model:prune` or a scheduled cleanup.
- [ ] **Index the `notifiable_id` column** — For performance on `$user->notifications` queries.

### Mail Notifications

- [ ] **Queue all mail notifications** — `implements ShouldQueue` on the notification class.
- [ ] **Use Markdown mail templates** — Consistent branding, automatic text fallback.
- [ ] **Test mail rendering** — `$notification->toMail($user)->render()` in tests.

### SMS / Third-Party Channels

- [ ] **Rate limit SMS notifications** — SMS is expensive. Prevent loops.
- [ ] **Never send secrets via SMS** — SMS is unencrypted and interceptable (SS7 attacks).
- [ ] **Handle delivery failures gracefully** — Channel failures should not break the application.
- [ ] **Use the `via()` method for per-user channel preferences** — Don't blast all channels.

```php
public function via(object $notifiable): array
{
    return $notifiable->prefers_sms ? ['vonage'] : ['mail', 'database'];
}
```

---


## 56. Dependency Injection Discipline

### Constructor Injection

- [ ] **Inject dependencies via constructor, not resolved inline** — Makes dependencies explicit and testable.

```php
// GOOD — explicit dependency, easily mocked
final class PostJournalEntry
{
    public function __construct(
        private readonly PeriodLockCheckerInterface $lockChecker,
        private readonly ExchangeRateGatewayInterface $rateGateway,
    ) {}
}

// BAD — hidden dependencies, hard to test
final class PostJournalEntry
{
    public function handle(): void
    {
        $checker = app(PeriodLockCheckerInterface::class); // Hidden
        $rate = resolve(ExchangeRateGatewayInterface::class); // Hidden
    }
}
```

### Anti-Patterns

- [ ] **No `app()` / `resolve()` in business logic** — Acceptable in service providers, tests, and commands. Nowhere else.
- [ ] **No static facade calls in domain/action classes** — `Cache::get()`, `Log::info()` are acceptable in infrastructure code, not domain logic. Inject the contract instead.
- [ ] **No `new` for services** — `new PaymentGateway()` in business logic creates tight coupling. Bind to interface and inject.
- [ ] **No God constructors** — If a class has more than 4-5 constructor dependencies, it's doing too much. Extract a sub-service.

### Interface Segregation

- [ ] **Inject narrow interfaces, not broad ones** — `PeriodLockCheckerInterface` not `AccountingServiceInterface` with 30 methods.
- [ ] **One implementation per interface (usually)** — If an interface only has one implementation, ensure it's there for testability (fakes/mocks) or future extension, not just ceremony.

### Laravel Container Features

- [ ] **Use contextual binding for different implementations** — `$this->app->when(A::class)->needs(I::class)->give(B::class)`.
- [ ] **Use `Scoped` singletons for request-scoped state** — `$this->app->scoped(TenantContext::class)`.
- [ ] **Register bindings in providers, not scattered across bootstrap** — Central, discoverable location.

---


## 58. HTTP Client & External API Safety

### Outbound HTTP Calls

- [ ] **Set timeouts on all HTTP calls** — Prevent hanging requests from blocking workers.

```php
Http::timeout(10)->connectTimeout(5)->get('https://api.example.com/data');
```

- [ ] **Retry with backoff** — `Http::retry(3, 100)->get(...)`.
- [ ] **Queue external API calls** — Don't make users wait for third-party responses.
- [ ] **Circuit breaker for unreliable APIs** — After N failures, stop calling and return a fallback.

### Response Handling

- [ ] **Check response status** — `$response->successful()`, `$response->failed()`, `$response->throw()`.
- [ ] **Don't trust external response data** — Validate and sanitize. External APIs can return unexpected structures.
- [ ] **Log external API failures** — With request URL (redacted), status code, and response body (truncated).
- [ ] **Never log credentials in requests** — Redact `Authorization` headers, API keys, tokens.

### Saloon / HTTP Client Patterns

- [ ] **Use connector classes, not raw `Http::get()`** — Centralize base URL, auth, headers in a connector.
- [ ] **Mock external APIs in tests** — `Http::fake()` or Saloon's mock client. Never call real APIs in test suites.
- [ ] **Handle rate limits from external APIs** — Respect `Retry-After` headers. Implement exponential backoff.

---


## 59. Feature Flags & Rollout Safety

### Implementation

- [ ] **Use a structured system** — Laravel Pennant, `spatie/laravel-settings`, or config-based flags. Not ad-hoc `if` checks.
- [ ] **Feature flags are temporary** — Remove the flag and dead code path after full rollout. Feature flags are not permanent config.
- [ ] **Test both flag states** — Every feature behind a flag needs tests for on AND off.
- [ ] **Default to off for new features** — Fail closed. New features are disabled until explicitly enabled.

### Rollout Safety

- [ ] **Gradual rollout with percentage** — 10% → 25% → 50% → 100%. Monitor between each step.
- [ ] **Instant kill switch** — Every feature flag can be turned off immediately without a deploy.
- [ ] **Audit flag changes** — Who enabled what, when.

---


## 62. Redis Security

### Connection Security

- [ ] **Require authentication** — Set `REDIS_PASSWORD` in `.env`. Don't run Redis without a password.
- [ ] **Use TLS for Redis connections in production** — `REDIS_SCHEME=tls` or `rediss://` URL scheme.
- [ ] **Bind Redis to localhost or private network** — Never expose Redis to the internet.
- [ ] **Use separate Redis databases for different purposes** — Sessions on DB 0, cache on DB 1, queues on DB 2. Prevents `FLUSHDB` from wiping sessions when clearing cache.

```php
// config/database.php
'redis' => [
    'default' => ['database' => env('REDIS_DB', 0)],
    'cache' => ['database' => env('REDIS_CACHE_DB', 1)],
    'session' => ['database' => env('REDIS_SESSION_DB', 2)],
    'queue' => ['database' => env('REDIS_QUEUE_DB', 3)],
],
```

### Data Safety

- [ ] **Set `maxmemory-policy` on Redis** — Prevent Redis from consuming all memory. Use `allkeys-lru` for cache, `noeviction` for queues.
- [ ] **Don't store sensitive data in Redis without encryption** — Redis stores data in plaintext. Use `ShouldBeEncrypted` for jobs or encrypt manually.
- [ ] **Monitor Redis memory usage** — Alert when approaching `maxmemory`.
- [ ] **Use `SCAN` instead of `KEYS *`** — `KEYS` blocks Redis. `SCAN` is non-blocking.

### Session Safety via Redis

- [ ] **`session.gc_maxlifetime` matches Laravel `session.lifetime`** — Prevents premature session eviction.
- [ ] **Redis persistence configured** — `RDB` or `AOF` depending on durability requirements. Queue data loss is unacceptable.

---


## 87. Image & Media Processing Security

- [ ] **Validate MIME type server-side** — Don't trust file extension or client-provided Content-Type.
- [ ] **Strip EXIF data from uploaded images** — EXIF contains GPS coordinates, camera info, and sometimes thumbnails of other images.

```php
// Using Intervention Image
$image = Image::read($file)->stripMetadata()->save();
```

- [ ] **Limit image dimensions** — Reject images larger than 10000x10000 pixels (decompression bomb prevention).
- [ ] **Resize images server-side** — Don't serve 4000x3000 originals when 400x300 thumbnails suffice.
- [ ] **SVG files can contain JavaScript** — Treat SVGs as untrusted HTML. Sanitize or reject.
- [ ] **Queue image processing** — Resizing, thumbnail generation, format conversion are CPU-intensive.
- [ ] **Serve user-uploaded images from a separate domain** — Prevents cookie leakage and XSS escalation.

---


## 88. Search & Full-Text Safety

- [ ] **Sanitize search queries** — Escape special characters in search syntax (`+`, `-`, `*`, `"`, `~`).
- [ ] **Limit search query length** — `max:200` in validation. Long queries are DoS vectors.
- [ ] **Rate limit search endpoints** — Expensive queries can be used for DoS.
- [ ] **Use database full-text indexes** — Not `LIKE '%term%'` which can't use indexes.
- [ ] **Never expose raw search engine errors to users** — Elasticsearch, Algolia errors may reveal index structure.
- [ ] **Paginate search results** — Never return all matches.
- [ ] **Highlight matches safely** — If highlighting user query terms in results, escape the HTML.

---


## 197. API Versioning Strategies

- [ ] **URI versioning is simplest** — `/api/v1/users`, `/api/v2/users`.

```php
Route::prefix('api/v1')->group(function () {
    Route::apiResource('journal-entries', V1\JournalEntryController::class);
});

Route::prefix('api/v2')->group(function () {
    Route::apiResource('journal-entries', V2\JournalEntryController::class);
});
```

- [ ] **Header versioning for cleaner URLs** — `Accept: application/vnd.app.v2+json`. More RESTful but harder to test.
- [ ] **Version transformers, not models** — The underlying model is the same; the API Resource formats it differently per version.
- [ ] **Deprecation notices** — Sunset header: `Sunset: Sat, 01 Jan 2027 00:00:00 GMT`. Inform consumers of timeline.
- [ ] **Backward compatibility within a version** — Adding fields is fine. Removing or renaming fields is a breaking change.
- [ ] **Run both versions' tests** — Until v1 is fully sunset, both test suites must pass.
- [ ] **Maximum 2 active versions** — More than 2 active versions is a maintenance burden.
- [ ] **Document migration guide** — Per-endpoint changes from v1 to v2.

---


## 198. Circuit Breaker Pattern

- [ ] **Wrap external service calls in a circuit breaker** — Prevent cascade failures when a dependency is down.

```php
class CircuitBreaker
{
    private int $failures = 0;
    private int $threshold = 5;
    private ?CarbonImmutable $openUntil = null;

    public function call(Closure $action): mixed
    {
        if ($this->isOpen()) {
            throw new CircuitOpenException('Service unavailable');
        }

        try {
            $result = $action();
            $this->reset();
            return $result;
        } catch (Throwable $e) {
            $this->recordFailure();
            throw $e;
        }
    }

    private function isOpen(): bool
    {
        return $this->openUntil !== null && now()->lt($this->openUntil);
    }

    private function recordFailure(): void
    {
        $this->failures++;
        if ($this->failures >= $this->threshold) {
            $this->openUntil = now()->addSeconds(30); // Open for 30s
        }
    }

    private function reset(): void
    {
        $this->failures = 0;
        $this->openUntil = null;
    }
}
```

- [ ] **Three states** — **Closed** (normal), **Open** (failing fast), **Half-Open** (testing recovery).
- [ ] **Configurable thresholds** — Failure count, timeout duration, half-open test count.
- [ ] **Per-service breakers** — Each external dependency has its own circuit breaker.
- [ ] **Fallback behavior** — When the circuit is open, return cached data, a default response, or a graceful error.
- [ ] **Monitor circuit state** — Dashboard showing which circuits are open and for how long.
- [ ] **Cache-backed state for multi-process** — Store circuit state in Redis so all workers share it.

---


## 199. Webhook Sending Best Practices

- [ ] **Sign webhook payloads** — HMAC-SHA256 with a per-subscriber secret.

```php
$payload = json_encode($data);
$signature = hash_hmac('sha256', $payload, $subscriber->webhook_secret);
$response = Http::withHeaders([
    'X-Signature-256' => "sha256={$signature}",
    'X-Webhook-ID' => $webhookId,
    'X-Webhook-Timestamp' => now()->unix(),
])->post($subscriber->webhook_url, $data);
```

- [ ] **Include a unique event ID** — For receiver deduplication.
- [ ] **Include a timestamp** — For receiver freshness checks.
- [ ] **Retry with exponential backoff** — 5s, 30s, 5m, 30m, 2h. Max 5 retries.
- [ ] **Log delivery attempts** — Status code, response time, retry count per webhook delivery.
- [ ] **Disable endpoints after repeated failures** — After 10 consecutive failures, mark the endpoint as inactive. Notify the subscriber.
- [ ] **Async delivery** — Send webhooks via queued jobs, not synchronously during the request.
- [ ] **Payload size limits** — Don't send 10MB payloads. Send a summary with a link to fetch full data.
- [ ] **Verify subscriber URL** — Block internal IPs and private networks (SSRF prevention, §106).

---


## 200. Message Queue Reliability Patterns

- [ ] **At-least-once delivery** — Assume messages may be delivered more than once. Make handlers idempotent.
- [ ] **Dead letter queue (DLQ)** — Messages that fail after all retries go to a DLQ for manual inspection.

```php
class ProcessSettlement implements ShouldQueue
{
    public int $tries = 5;
    public array $backoff = [5, 30, 300, 1800, 7200];

    public function failed(Throwable $exception): void
    {
        // Notify ops team
        Log::critical('Settlement processing failed permanently', [
            'settlement_id' => $this->settlement->id,
            'exception' => $exception->getMessage(),
        ]);
    }
}
```

- [ ] **Job batching for related operations** — `Bus::batch([...])` with `then`, `catch`, `finally` callbacks.
- [ ] **Unique jobs** — `ShouldBeUnique` prevents duplicate job processing.
- [ ] **Job chaining for sequential processing** — `Bus::chain([StepOne::class, StepTwo::class])->dispatch()`.
- [ ] **Timeout protection** — `public int $timeout = 60;` kills jobs that run too long.
- [ ] **Monitor queue depth** — Alert when the queue backlog exceeds a threshold (e.g., > 1000 pending jobs).
- [ ] **Separate queues by priority** — `--queue=critical,default,low`. Critical jobs process first.
- [ ] **Graceful shutdown** — `php artisan queue:restart` after deployments to pick up new code.
- [ ] **Horizon for monitoring** — Laravel Horizon provides real-time dashboards for Redis-based queues.

---

*End of The Laravel Fortress — 200 sections, 14 parts, 2,000+ checks.*


---

[← Previous Part](11-testing-quality-assurance.md) | [Full Checklist](../checklist.md) | [Next Part →](13-logging-monitoring-audit.md)
