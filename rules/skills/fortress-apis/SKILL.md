---
description: "Laravel Fortress Part 12 — APIs, Queues & Integration. 16 sections, 136 checks covering Jobs, webhooks, HTTP clients, Redis, feature flags, circuit breakers."
---

# Fortress: APIs, Queues & Integration

> Part XII of The Laravel Fortress — 16 sections · 136 checks
> https://github.com/chuxolab/laravel-fortress/blob/main/parts/12-apis-queues-integration.md

When writing or reviewing code, enforce these rules.
Check `.fortress.yml` in the project root for overrides.

## Project Awareness

Before enforcing rules, read `composer.json` and `package.json` to detect the project's PHP version,
Laravel version, and installed packages. Skip rules whose conditions don't match the detected stack.

## Rules

### Queue & Job Safety

[F-P12-001] **WARNING** — All external HTTP calls are queued
[F-P12-002] **WARNING** — Set `$tries`, `$maxExceptions`, `$timeout`
[F-P12-003] **WARNING** — Implement `failed()` method
[F-P12-004] **WARNING** — Use `ShouldBeUnique` for non-concurrent jobs
[F-P12-005] **WARNING** — Use `ShouldBeEncrypted` for jobs with sensitive data
[F-P12-006] **WARNING** — Rate limit job dispatching
[F-P12-007] **WARNING** — Monitor queue depth
[F-P12-008] **WARNING** — Idempotent job design
[F-P12-009] **WARNING** — Don't serialize Eloquent models with sensitive data

### Signature Verification

[F-P12-010] **WARNING** — Verify webhook signatures before processing
[F-P12-011] **WARNING** — Use `hash_equals()` for timing-safe comparison

### Replay Attacks

[F-P12-012] **WARNING** — Check timestamp freshness
[F-P12-013] **WARNING** — Idempotency via event ID

### Processing Safety

[F-P12-014] **WARNING** — Queue webhook processing
[F-P12-015] **WARNING** — Return 200 even for ignored events
[F-P12-016] **WARNING** — Log all webhook attempts
[F-P12-017] **WARNING** — Allowlist webhook source IPs

### Query Scoping

[F-P12-018] **WARNING** — Global scope or middleware-based tenant filtering
[F-P12-019] **WARNING** — Test cross-tenant isolation
[F-P12-020] **WARNING** — Audit raw queries for missing tenant filters

### Shared Resources

[F-P12-021] **WARNING** — Tenant-specific encryption keys
[F-P12-022] **WARNING** — Tenant-specific cache prefixes
[F-P12-023] **WARNING** — Queue jobs carry tenant context
[F-P12-024] **WARNING** — Scheduled commands run per-tenant

### Input Validation

[F-P12-025] **WARNING** — Validate arguments and options
[F-P12-026] **WARNING** — Use confirmations for destructive commands
[F-P12-027] **WARNING** — Return proper exit codes

### Production Safety

[F-P12-028] **WARNING** — Gate dangerous commands behind environment checks
[F-P12-029] **WARNING** — Never run `migrate:fresh` or `db:wipe` in production
[F-P12-030] **WARNING** — Log all command executions
[F-P12-031] **WARNING** — Use `--no-interaction` in CI/CD
[F-P12-032] **WARNING** — No `dd()` or `dump()` in commands

### Overlap Prevention

[F-P12-033] **WARNING** — Use `->withoutOverlapping()`
[F-P12-034] **WARNING** — Use `->onOneServer()`

### Timezone & Timing

[F-P12-035] **WARNING** — Specify timezone explicitly
[F-P12-036] **WARNING** — Use `->evenInMaintenanceMode()`
[F-P12-037] **WARNING** — Monitor schedule health

### Failure Handling

[F-P12-038] **WARNING** — Use `->onFailure()` for alerting
[F-P12-039] **WARNING** — Use `->emailOutputOnFailure()`
[F-P12-040] **WARNING** — Test scheduled commands independently

### Database Notifications

[F-P12-041] **WARNING** — Don't store sensitive data in notification payloads
[F-P12-042] **WARNING** — Set a retention policy
[F-P12-043] **WARNING** — Index the `notifiable_id` column

### Mail Notifications

[F-P12-044] **WARNING** — Queue all mail notifications
[F-P12-045] **WARNING** — Use Markdown mail templates
[F-P12-046] **WARNING** — Test mail rendering

### SMS / Third-Party Channels

[F-P12-047] **WARNING** — Rate limit SMS notifications
[F-P12-048] **WARNING** — Never send secrets via SMS
[F-P12-049] **WARNING** — Handle delivery failures gracefully
[F-P12-050] **WARNING** — Use the `via()` method for per-user channel preferences

### Constructor Injection

[F-P12-051] **WARNING** — Inject dependencies via constructor, not resolved inline

### Anti-Patterns

[F-P12-052] **WARNING** — No `app()` / `resolve()` in business logic
[F-P12-053] **WARNING** — No static facade calls in domain/action classes
[F-P12-054] **WARNING** — No `new` for services
[F-P12-055] **WARNING** — No God constructors

### Interface Segregation

[F-P12-056] **WARNING** — Inject narrow interfaces, not broad ones
[F-P12-057] **WARNING** — One implementation per interface (usually)

### Laravel Container Features

[F-P12-058] **WARNING** — Use contextual binding for different implementations
[F-P12-059] **WARNING** — Use `Scoped` singletons for request-scoped state
[F-P12-060] **WARNING** — Register bindings in providers, not scattered across bootstrap

### Outbound HTTP Calls

[F-P12-061] **WARNING** — Set timeouts on all HTTP calls
[F-P12-062] **WARNING** — Retry with backoff
[F-P12-063] **WARNING** — Queue external API calls
[F-P12-064] **WARNING** — Circuit breaker for unreliable APIs

### Response Handling

[F-P12-065] **WARNING** — Check response status
[F-P12-066] **WARNING** — Don't trust external response data
[F-P12-067] **WARNING** — Log external API failures
[F-P12-068] **WARNING** — Never log credentials in requests

### Saloon / HTTP Client Patterns

[F-P12-069] **WARNING** — Use connector classes, not raw `Http::get()`
[F-P12-070] **WARNING** — Mock external APIs in tests
[F-P12-071] **WARNING** — Handle rate limits from external APIs

### Implementation

[F-P12-072] **WARNING** — Use a structured system
[F-P12-073] **WARNING** — Feature flags are temporary
[F-P12-074] **WARNING** — Test both flag states
[F-P12-075] **WARNING** — Default to off for new features

### Rollout Safety

[F-P12-076] **WARNING** — Gradual rollout with percentage
[F-P12-077] **WARNING** — Instant kill switch
[F-P12-078] **WARNING** — Audit flag changes

### Connection Security

[F-P12-079] **WARNING** — Require authentication
[F-P12-080] **WARNING** — Use TLS for Redis connections in production
[F-P12-081] **WARNING** — Bind Redis to localhost or private network
[F-P12-082] **WARNING** — Use separate Redis databases for different purposes

### Data Safety

[F-P12-083] **WARNING** — Set `maxmemory-policy` on Redis
[F-P12-084] **WARNING** — Don't store sensitive data in Redis without encryption
[F-P12-085] **WARNING** — Monitor Redis memory usage
[F-P12-086] **WARNING** — Use `SCAN` instead of `KEYS *`

### Session Safety via Redis

[F-P12-087] **WARNING** — `session.gc_maxlifetime` matches Laravel `session.lifetime`
[F-P12-088] **WARNING** — Redis persistence configured

### Image & Media Processing Security

[F-P12-089] **WARNING** — Validate MIME type server-side
[F-P12-090] **WARNING** — Strip EXIF data from uploaded images
[F-P12-091] **WARNING** — Limit image dimensions
[F-P12-092] **WARNING** — Resize images server-side
[F-P12-093] **WARNING** — SVG files can contain JavaScript
[F-P12-094] **WARNING** — Queue image processing
[F-P12-095] **WARNING** — Serve user-uploaded images from a separate domain

### Search & Full-Text Safety

[F-P12-096] **WARNING** — Sanitize search queries
[F-P12-097] **WARNING** — Limit search query length
[F-P12-098] **WARNING** — Rate limit search endpoints
[F-P12-099] **WARNING** — Use database full-text indexes
[F-P12-100] **WARNING** — Never expose raw search engine errors to users
[F-P12-101] **WARNING** — Paginate search results
[F-P12-102] **WARNING** — Highlight matches safely

### API Versioning Strategies

[F-P12-103] **WARNING** — URI versioning is simplest
[F-P12-104] **WARNING** — Header versioning for cleaner URLs
[F-P12-105] **WARNING** — Version transformers, not models
[F-P12-106] **WARNING** — Deprecation notices
[F-P12-107] **WARNING** — Backward compatibility within a version
[F-P12-108] **WARNING** — Run both versions' tests
[F-P12-109] **WARNING** — Maximum 2 active versions
[F-P12-110] **WARNING** — Document migration guide

### Circuit Breaker Pattern

[F-P12-111] **WARNING** — Wrap external service calls in a circuit breaker
[F-P12-112] **WARNING** — Three states
[F-P12-113] **WARNING** — Configurable thresholds
[F-P12-114] **WARNING** — Per-service breakers
[F-P12-115] **WARNING** — Fallback behavior
[F-P12-116] **WARNING** — Monitor circuit state
[F-P12-117] **WARNING** — Cache-backed state for multi-process

### Webhook Sending Best Practices

[F-P12-118] **WARNING** — Sign webhook payloads
[F-P12-119] **WARNING** — Include a unique event ID
[F-P12-120] **WARNING** — Include a timestamp
[F-P12-121] **WARNING** — Retry with exponential backoff
[F-P12-122] **WARNING** — Log delivery attempts
[F-P12-123] **WARNING** — Disable endpoints after repeated failures
[F-P12-124] **WARNING** — Async delivery
[F-P12-125] **WARNING** — Payload size limits
[F-P12-126] **WARNING** — Verify subscriber URL

### Message Queue Reliability Patterns

[F-P12-127] **WARNING** — At-least-once delivery
[F-P12-128] **WARNING** — Dead letter queue (DLQ)
[F-P12-129] **WARNING** — Job batching for related operations
[F-P12-130] **WARNING** — Unique jobs
[F-P12-131] **WARNING** — Job chaining for sequential processing
[F-P12-132] **WARNING** — Timeout protection
[F-P12-133] **WARNING** — Monitor queue depth
[F-P12-134] **WARNING** — Separate queues by priority
[F-P12-135] **WARNING** — Graceful shutdown
[F-P12-136] **WARNING** — Horizon for monitoring
