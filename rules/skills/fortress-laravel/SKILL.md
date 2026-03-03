---
description: "Laravel Fortress Part 8 — Laravel Framework Mastery. 23 sections, 196 checks covering Anti-patterns, validation, Eloquent, caching, events, service providers, custom casts."
---

# Fortress: Laravel Framework Mastery

> Part VIII of The Laravel Fortress — 23 sections · 196 checks
> https://github.com/chuxolab/laravel-fortress/blob/main/parts/08-laravel-framework-mastery.md

When writing or reviewing code, enforce these rules.
Check `.fortress.yml` in the project root for overrides.

## Project Awareness

Before enforcing rules, read `composer.json` and `package.json` to detect the project's PHP version,
Laravel version, and installed packages. Skip rules whose conditions don't match the detected stack.

## Rules

### N+1 Queries

[F-P08-001] **WARNING** — Eager load relationships

### Fat Controllers

[F-P08-002] **WARNING** — Controllers under 10 lines per method

### God Models

[F-P08-003] **WARNING** — Models should only have
[F-P08-004] **WARNING** — Business logic belongs in Actions/Services

### Raw Queries

[F-P08-005] **WARNING** — Use Eloquent or Query Builder
[F-P08-006] **WARNING** — Use `Model::query()` not `DB::table()`

### env() Outside Config

[F-P08-007] **WARNING** — Never use `env()` outside `config/` files

### Missing Form Requests

[F-P08-008] **WARNING** — Every controller store/update uses a Form Request
[F-P08-009] **WARNING** — Array-based rules, not string pipe syntax

### Missing Policies

[F-P08-010] **WARNING** — Every model with user-owned data has a Policy
[F-P08-011] **WARNING** — Policies check ownership, not just permissions

### Unqueued Heavy Operations

[F-P08-012] **WARNING** — Send emails via queued notifications
[F-P08-013] **WARNING** — Queue PDF generation, CSV exports, report building
[F-P08-014] **WARNING** — Queue webhook dispatching

### Form Requests

[F-P08-015] **WARNING** — Dedicated Form Request for every store/update
[F-P08-016] **WARNING** — `authorize()` method returns a boolean or policy check
[F-P08-017] **WARNING** — Use `Rule::enum()` for enum validation
[F-P08-018] **WARNING** — Custom validation messages
[F-P08-019] **WARNING** — Custom validation rules

### Input Sanitisation

[F-P08-020] **WARNING** — Validate on input, escape on output
[F-P08-021] **WARNING** — Use `$request->validated()` or `$request->safe()`
[F-P08-022] **WARNING** — Validate file types by MIME, not extension
[F-P08-023] **WARNING** — Limit string lengths
[F-P08-024] **WARNING** — Limit array sizes

### Period / Date Validation

[F-P08-025] **WARNING** — Use select/dropdown for period inputs
[F-P08-026] **WARNING** — Validate date ranges

### Exception Hierarchy

[F-P08-027] **WARNING** — Use domain-specific exceptions, not generic ones
[F-P08-028] **WARNING** — Extend a base domain exception
[F-P08-029] **WARNING** — Map exceptions to HTTP status codes

### Exception Anti-Patterns

[F-P08-030] **WARNING** — Never catch `\Exception` or `\Throwable` and silently swallow
[F-P08-031] **WARNING** — Never expose internal exception messages to users
[F-P08-032] **WARNING** — Never expose stack traces in production
[F-P08-033] **WARNING** — Use `report()` helper for non-fatal exceptions
[F-P08-034] **WARNING** — Use `abort()` for HTTP exceptions
[F-P08-035] **WARNING** — Define `$dontReport` / `$dontFlash` in exception handler

### Structured Error Responses

[F-P08-036] **WARNING** — Return consistent JSON error structure for APIs
[F-P08-037] **WARNING** — Validation errors return 422
[F-P08-038] **WARNING** — Authorization failures return 403
[F-P08-039] **WARNING** — Not found returns 404
[F-P08-040] **WARNING** — Rate limit exceeded returns 429

### Try/Catch Placement

[F-P08-041] **WARNING** — Catch at the boundary, not deep in business logic
[F-P08-042] **WARNING** — Third-party API calls always wrapped in try/catch
[F-P08-043] **WARNING** — Never catch exceptions inside DB transactions just to continue

### Model Boot Traps

[F-P08-044] **WARNING** — Never put heavy logic in `boot()` / `booted()`
[F-P08-045] **WARNING** — Avoid `creating`/`updating` observers for business logic
[F-P08-046] **WARNING** — Use `withoutEvents()` for bulk operations

### Serialization Dangers

[F-P08-047] **WARNING** — Define `$hidden` on all models with sensitive data
[F-P08-048] **WARNING** — Use API Resources for responses, never raw `$model->toArray()`
[F-P08-049] **WARNING** — Beware of `$appends`
[F-P08-050] **WARNING** — Never pass full models to queued jobs

### Accessors & Mutators

[F-P08-051] **WARNING** — Use `Attribute` accessor syntax (Laravel 9+)
[F-P08-052] **WARNING** — Never put side effects in accessors

### Model Immutability

[F-P08-053] **WARNING** — Terminal-state records must be immutable
[F-P08-054] **WARNING** — Use `$model->isDirty()` / `$model->wasChanged()` for change detection

### Scopes

[F-P08-055] **WARNING** — Use named scopes for reusable filters
[F-P08-056] **WARNING** — Global scopes need careful consideration
[F-P08-057] **WARNING** — Soft delete IS a global scope

### Cache Poisoning

[F-P08-058] **WARNING** — Never use user input as cache keys without hashing
[F-P08-059] **WARNING** — Cache key collisions
[F-P08-060] **WARNING** — Never cache authenticated user data globally

### Cache Invalidation

[F-P08-061] **WARNING** — Invalidate cache when underlying data changes
[F-P08-062] **WARNING** — Set appropriate TTLs
[F-P08-063] **WARNING** — Use `Cache::lock()` for cache stampede prevention

### Serialization in Cache

[F-P08-064] **WARNING** — Cached data must be serializable
[F-P08-065] **WARNING** — Don't cache Eloquent models directly
[F-P08-066] **WARNING** — Beware of stale cache after deployments

### Event Dispatching

[F-P08-067] **WARNING** — Events should be data carriers, not logic executors
[F-P08-068] **WARNING** — Use typed properties on events
[F-P08-069] **WARNING** — Events dispatched inside transactions may need `afterCommit`

### Listener Safety

[F-P08-070] **WARNING** — Queue listeners that do external I/O
[F-P08-071] **WARNING** — Handle listener failures gracefully
[F-P08-072] **WARNING** — No circular event chains
[F-P08-073] **WARNING** — Order-dependent listeners need explicit ordering

### Event Sourcing Specific

[F-P08-074] **WARNING** — Events carry all data needed for projection
[F-P08-075] **WARNING** — Every recorded event has an `apply` method on the aggregate
[F-P08-076] **WARNING** — Event metadata attached consistently
[F-P08-077] **WARNING** — Audit reactor handles every event type

### Register vs Boot

[F-P08-078] **WARNING** — `register()` — bind interfaces, register singletons
[F-P08-079] **WARNING** — `boot()` — configure observers, gates, macros
[F-P08-080] **WARNING** — Never do database queries in `register()` or `boot()`

### Deferred Providers

[F-P08-081] **WARNING** — Use `DeferrableProvider` for rarely-used bindings

### Common Mistakes

[F-P08-082] **WARNING** — Don't register the same binding twice
[F-P08-083] **WARNING** — Don't register middleware in providers
[F-P08-084] **WARNING** — Don't put business logic in providers

### Config File Organization

[F-P08-085] **WARNING** — One config file per domain
[F-P08-086] **WARNING** — Env-driven values only at config level
[F-P08-087] **WARNING** — Type-cast env values
[F-P08-088] **WARNING** — Default values for all env() calls
[F-P08-089] **WARNING** — No nested closures in config files
[F-P08-090] **WARNING** — Config keys are snake_case

### Middleware Authoring Pitfalls

[F-P08-091] **WARNING** — Set response headers in `$next()` response, not before
[F-P08-092] **WARNING** — Don't mutate the request object
[F-P08-093] **WARNING** — Terminate middleware for post-response work
[F-P08-094] **WARNING** — Middleware should be stateless
[F-P08-095] **WARNING** — Order matters
[F-P08-096] **WARNING** — Short-circuit early

### Form Request Advanced Patterns

[F-P08-097] **WARNING** — `authorize()` returns policy check
[F-P08-098] **WARNING** — `prepareForValidation()` for input normalization
[F-P08-099] **WARNING** — `passedValidation()` for post-validation transforms
[F-P08-100] **WARNING** — Conditional rules with `sometimes()`
[F-P08-101] **WARNING** — Custom rule objects over closure rules
[F-P08-102] **WARNING** — Nested array validation
[F-P08-103] **WARNING** — Error message customization

### Eloquent Observer & Event Ordering

[F-P08-104] **WARNING** — Observers fire in registration order
[F-P08-105] **WARNING** — `creating` fires before `created`
[F-P08-106] **WARNING** — `updating` gets dirty attributes
[F-P08-107] **WARNING** — `deleting` fires before soft delete
[F-P08-108] **WARNING** — Observers don't fire on bulk operations
[F-P08-109] **WARNING** — Avoid heavy work in observers
[F-P08-110] **WARNING** — Model events don't fire in `DB::table()` queries

### Artisan Make Command Hygiene

[F-P08-111] **WARNING** — Always use `php artisan make:*`
[F-P08-112] **WARNING** — Pass `--no-interaction`
[F-P08-113] **WARNING** — Use relevant flags
[F-P08-114] **WARNING** — Use `make:test --pest`
[F-P08-115] **WARNING** — Use `make:class`
[F-P08-116] **WARNING** — Verify file after generation

### Factories

[F-P08-117] **WARNING** — Every model has a factory
[F-P08-118] **WARNING** — Factories produce valid default state
[F-P08-119] **WARNING** — Use factory states for variations
[F-P08-120] **WARNING** — Factories respect unique constraints
[F-P08-121] **WARNING** — No real data in factories

### Seeders

[F-P08-122] **WARNING** — Seeders are idempotent
[F-P08-123] **WARNING** — Seeders don't depend on execution order
[F-P08-124] **WARNING** — Production seeders separate from dev seeders
[F-P08-125] **WARNING** — No `DB::table()->truncate()` in production seeders
[F-P08-126] **WARNING** — Use transactions in seeders

### Custom Eloquent Casts

[F-P08-127] **WARNING** — Implement `CastsAttributes` for complex types
[F-P08-128] **WARNING** — Casts are stateless
[F-P08-129] **WARNING** — Handle `null` gracefully
[F-P08-130] **WARNING** — Custom casts for enums with extra logic
[F-P08-131] **WARNING** — Test casts with real models
[F-P08-132] **WARNING** — Register reusable casts in a service provider

### Service Container Advanced Bindings

[F-P08-133] **WARNING** — Contextual bindings
[F-P08-134] **WARNING** — Scoped singletons for request lifecycle
[F-P08-135] **WARNING** — Tagged bindings for collecting implementations
[F-P08-136] **WARNING** — Deferred providers for performance
[F-P08-137] **WARNING** — No service location
[F-P08-138] **WARNING** — `bind` vs `singleton` vs `scoped`
[F-P08-139] **WARNING** — Test bindings

### Macros & Mixins Discipline

[F-P08-140] **WARNING** — Register macros in service providers
[F-P08-141] **WARNING** — Type-hint macro parameters
[F-P08-142] **WARNING** — PHPDoc for IDE support
[F-P08-143] **WARNING** — Don't override built-in methods
[F-P08-144] **WARNING** — Macros are global
[F-P08-145] **WARNING** — Prefer scopes on models over macros
[F-P08-146] **WARNING** — Test macros

### Route Model Binding Advanced Patterns

[F-P08-147] **WARNING** — Custom binding resolution
[F-P08-148] **WARNING** — Scoped bindings
[F-P08-149] **WARNING** — Soft-deleted model binding
[F-P08-150] **WARNING** — Enum route binding
[F-P08-151] **WARNING** — Custom keys via `getRouteKeyName()`
[F-P08-152] **WARNING** — Don't resolve in controller

### Laravel Prompts for CLI UX

[F-P08-153] **WARNING** — Use `Laravel\Prompts` for Artisan command UX
[F-P08-154] **WARNING** — Validate input with prompts
[F-P08-155] **WARNING** — Use `spin()` for long operations
[F-P08-156] **WARNING** — Fallback for non-interactive mode
[F-P08-157] **WARNING** — Use `table()` for tabular output
[F-P08-158] **WARNING** — Use `info()`, `warn()`, `error()`

### Custom Validation Rules

[F-P08-159] **WARNING** — Create rule objects
[F-P08-160] **WARNING** — Rules are unit-testable
[F-P08-161] **WARNING** — Use `Rule::when()` for conditional rules
[F-P08-162] **WARNING** — Use `Rule::enum()`
[F-P08-163] **WARNING** — Avoid closure rules in production
[F-P08-164] **WARNING** — Custom error messages with `:attribute` placeholder

### Notification Architecture

[F-P08-165] **WARNING** — One notification class per event
[F-P08-166] **WARNING** — Implement `ShouldQueue`
[F-P08-167] **WARNING** — Use `via()` to control channels
[F-P08-168] **WARNING** — Database notifications have structured data
[F-P08-169] **WARNING** — Rate limit notifications
[F-P08-170] **WARNING** — Notification preferences per user
[F-P08-171] **WARNING** — Test notifications
[F-P08-172] **WARNING** — No sensitive data in notifications

### Eloquent Accessor & Mutator Hygiene

[F-P08-173] **WARNING** — Use the `Attribute` return type (Laravel 9+)
[F-P08-174] **WARNING** — Computed attributes go in `$appends`
[F-P08-175] **WARNING** — Don't put business logic in accessors
[F-P08-176] **WARNING** — Avoid expensive accessors
[F-P08-177] **WARNING** — Mutators validate input
[F-P08-178] **WARNING** — Don't use accessors for formatted dates
[F-P08-179] **WARNING** — Test accessors and mutators

### Model Event Lifecycle Awareness

[F-P08-180] **WARNING** — Know the event order
[F-P08-181] **WARNING** — `saving` fires on both create and update
[F-P08-182] **WARNING** — Events don't fire on mass operations
[F-P08-183] **WARNING** — `deleted` doesn't fire on `DB::table()` deletes
[F-P08-184] **WARNING** — Observers are registered globally
[F-P08-185] **WARNING** — Beware of infinite loops
[F-P08-186] **WARNING** — Use `Model::withoutEvents()` for seed/migration operations
[F-P08-187] **WARNING** — `booted()` for model boot logic

### Action Pattern Discipline

[F-P08-188] **WARNING** — One Action, one job
[F-P08-189] **WARNING** — Actions receive typed parameters
[F-P08-190] **WARNING** — Actions don't depend on HTTP context
[F-P08-191] **WARNING** — Actions are testable without HTTP
[F-P08-192] **WARNING** — Actions call other Actions
[F-P08-193] **WARNING** — Actions wrap transactions
[F-P08-194] **WARNING** — Actions throw domain exceptions
[F-P08-195] **WARNING** — Actions don't return HTTP responses
[F-P08-196] **WARNING** — Naming convention
