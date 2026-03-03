---
description: "Laravel Fortress Part 6 — PHP Language & Type Safety. 15 sections, 126 checks covering PHP 8.x features, strict typing, enums, readonly, property hooks."
---

# Fortress: PHP Language & Type Safety

> Part VI of The Laravel Fortress — 15 sections · 126 checks
> https://github.com/oilmonegov/laravel-fortress/blob/main/parts/06-php-language-type-safety.md

When writing or reviewing code, enforce these rules.
Check `.fortress.yml` in the project root for overrides.

## Project Awareness

Before enforcing rules, read `composer.json` and `package.json` to detect the project's PHP version,
Laravel version, and installed packages. Skip rules whose conditions don't match the detected stack.

## Rules

### Must Use

[F-P06-001] **WARNING** — Constructor property promotion
[F-P06-002] **WARNING** — `match` over `switch`
[F-P06-003] **WARNING** — Backed enums for all magic strings
[F-P06-004] **WARNING** — Null-safe operator `?->`
[F-P06-005] **WARNING** — Arrow functions `fn() =>`
[F-P06-006] **WARNING** — Named arguments
[F-P06-007] **WARNING** — `readonly` properties
[F-P06-008] **WARNING** — First-class callable syntax `method(...)`
[F-P06-009] **WARNING** — `#[Override]` attribute

### PHP 8.4 Specific

[F-P06-010] **WARNING** — Property hooks
[F-P06-011] **WARNING** — Asymmetric visibility
[F-P06-012] **WARNING** — `#[\Deprecated]` attribute
[F-P06-013] **WARNING** — New array functions
[F-P06-014] **WARNING** — `new` without parentheses chaining

### Avoid Legacy Patterns

[F-P06-015] **WARNING** — No `switch` statements
[F-P06-016] **WARNING** — No `array_push($arr, $item)`
[F-P06-017] **WARNING** — No `isset($x) ? $x : $default`
[F-P06-018] **WARNING** — No `strpos($str, 'x') !== false`
[F-P06-019] **WARNING** — No `substr($str, 0, 3) === 'foo'`
[F-P06-020] **WARNING** — No `array_key_exists('key', $arr)`
[F-P06-021] **WARNING** — No `call_user_func($callback, $arg)`

### Declarations

[F-P06-022] **WARNING** — `declare(strict_types=1)` in every PHP file

### Return Types

[F-P06-023] **WARNING** — Explicit return types on all methods
[F-P06-024] **WARNING** — Use `void` for methods that return nothing
[F-P06-025] **WARNING** — Use `never` for methods that always throw or exit

### Type Hints

[F-P06-026] **WARNING** — Type hints on all parameters
[F-P06-027] **WARNING** — Use union types `string|int` over `mixed`
[F-P06-028] **WARNING** — Use intersection types `Renderable&Countable`
[F-P06-029] **WARNING** — Avoid `mixed` — it means "I don't know"

### Cast Safety

[F-P06-030] **WARNING** — Model casts use `casts()` method, not `$casts` property
[F-P06-031] **WARNING** — Use `immutable_datetime` over `datetime`
[F-P06-032] **WARNING** — Use `'encrypted'` cast for sensitive attributes
[F-P06-033] **WARNING** — Use `'boolean'` cast for flag columns
[F-P06-034] **WARNING** — Use `'array'` or `'collection'` cast for JSON columns
[F-P06-035] **WARNING** — Use `AsEnumCollection::of(Enum::class)` for JSON arrays of enum values

### PHPDoc for Complex Types

[F-P06-036] **WARNING** — Array shapes for complex arrays
[F-P06-037] **WARNING** — `@template` for generic types

### Regular Expression Denial of Service

[F-P06-038] **WARNING** — Avoid catastrophic backtracking patterns
[F-P06-039] **WARNING** — Limit input length before regex matching
[F-P06-040] **WARNING** — Use possessive quantifiers or atomic groups
[F-P06-041] **WARNING** — Prefer `str_contains()`, `str_starts_with()`, `str_ends_with()`
[F-P06-042] **WARNING** — Test regex with adversarial inputs

### Timezone Handling

[F-P06-043] **WARNING** — Store all timestamps in UTC
[F-P06-044] **WARNING** — Convert to user's timezone only for display
[F-P06-045] **WARNING** — Use `CarbonImmutable` not `Carbon`
[F-P06-046] **WARNING** — Model casts use `immutable_datetime`

### Date Comparison

[F-P06-047] **WARNING** — Compare dates with Carbon methods, not strings
[F-P06-048] **WARNING** — Beware of timezone-naive comparisons
[F-P06-049] **WARNING** — Use `->startOfDay()` / `->endOfDay()` for date-range queries

### Date Validation

[F-P06-050] **WARNING** — Use `date_format:Y-m-d` not just `date`
[F-P06-051] **WARNING** — Validate date ranges
[F-P06-052] **WARNING** — Reject impossible dates

### php.ini Hardening

[F-P06-053] **WARNING** — `allow_url_include = Off`
[F-P06-054] **WARNING** — `disable_functions`
[F-P06-055] **WARNING** — `expose_php = Off`
[F-P06-056] **WARNING** — `session.use_strict_mode = 1`
[F-P06-057] **WARNING** — `open_basedir`

### Deprecated in PHP 8.4

[F-P06-058] **WARNING** — `implode()` with reversed arguments
[F-P06-059] **WARNING** — `SCREAMING_CASE` constants on built-in enums
[F-P06-060] **WARNING** — Legacy MySQL extensions

### Deprecated Patterns to Avoid

[F-P06-061] **WARNING** — Dynamic properties on classes
[F-P06-062] **WARNING** — `utf8_encode()` / `utf8_decode()`
[F-P06-063] **WARNING** — `${var}` string interpolation
[F-P06-064] **WARNING** — `#[\ReturnTypeWillChange]`
[F-P06-065] **WARNING** — Optional parameter before required

### Preparing for PHP 9.0

[F-P06-066] **WARNING** — All deprecation warnings treated as errors in CI
[F-P06-067] **WARNING** — Run `rector` with PHP 9.0 rules
[F-P06-068] **WARNING** — No reliance on `__autoload()`
[F-P06-069] **WARNING** — Strict comparisons everywhere

### Enum Best Practices (Deep Dive)

[F-P06-070] **WARNING** — Backed enums (`string` or `int`) for all database-persisted enums
[F-P06-071] **WARNING** — TitleCase enum cases
[F-P06-072] **WARNING** — `label()` method for human-readable text
[F-P06-073] **WARNING** — `color()` method for UI badge colors
[F-P06-074] **WARNING** — `allowedTransitions()` for state machines
[F-P06-075] **WARNING** — `isTerminal()` for end states
[F-P06-076] **WARNING** — No business logic in enums
[F-P06-077] **WARNING** — TypeScript mirrors for frontend enums
[F-P06-078] **WARNING** — Validate with `Rule::enum()`
[F-P06-079] **WARNING** — Never hardcode enum values as strings

### Fiber & Async Patterns

[F-P06-080] **WARNING** — Fibers are cooperative, not parallel
[F-P06-081] **WARNING** — Use Fibers for non-blocking I/O
[F-P06-082] **WARNING** — Don't use Fibers for CPU-bound work
[F-P06-083] **WARNING** — Exception handling in Fibers
[F-P06-084] **WARNING** — Avoid long-running Fibers in request context
[F-P06-085] **WARNING** — Framework integration

### Readonly Classes & Properties Deep Dive

[F-P06-086] **WARNING** — Use `readonly class` for DTOs and Value Objects
[F-P06-087] **WARNING** — `readonly` properties cannot be re-assigned
[F-P06-088] **WARNING** — `readonly` classes cannot have non-readonly properties
[F-P06-089] **WARNING** — No dynamic properties on `readonly` classes
[F-P06-090] **WARNING** — Clone and readonly
[F-P06-091] **WARNING** — Events should be readonly
[F-P06-092] **WARNING** — Don't use `readonly` on Eloquent models

### First-Class Callable Syntax

[F-P06-093] **WARNING** — Use `Closure::fromCallable()` replacement syntax
[F-P06-094] **WARNING** — Use with `array_map`, `array_filter`
[F-P06-095] **WARNING** — Use for route actions in tests
[F-P06-096] **WARNING** — Works with static methods
[F-P06-097] **WARNING** — Works with built-in functions
[F-P06-098] **WARNING** — Type-safe

### Modern Array Functions

[F-P06-099] **WARNING** — `array_find()` (PHP 8.4)
[F-P06-100] **WARNING** — `array_find_key()` (PHP 8.4)
[F-P06-101] **WARNING** — `array_any()` (PHP 8.4)
[F-P06-102] **WARNING** — `array_all()` (PHP 8.4)
[F-P06-103] **WARNING** — Use over Collection for simple arrays
[F-P06-104] **WARNING** — Prefer `array_is_list()` (PHP 8.1)

### Attribute-Based Programming

[F-P06-105] **WARNING** — `#[Override]` (PHP 8.3)
[F-P06-106] **WARNING** — `#[Deprecated]` (PHP 8.4)
[F-P06-107] **WARNING** — Custom attributes for metadata
[F-P06-108] **WARNING** — Framework attributes
[F-P06-109] **WARNING** — No runtime reflection for attributes in hot paths

### Intersection & DNF Types

[F-P06-110] **WARNING** — Intersection types
[F-P06-111] **WARNING** — DNF (Disjunctive Normal Form) types (PHP 8.2)
[F-P06-112] **WARNING** — Use intersection types for DI parameters
[F-P06-113] **WARNING** — Prefer composition over complex types
[F-P06-114] **WARNING** — No intersection types with `class` types

### Property Hooks (PHP 8.4)

[F-P06-115] **WARNING** — `get` and `set` hooks
[F-P06-116] **WARNING** — Virtual properties
[F-P06-117] **WARNING** — Hooks work with `readonly`
[F-P06-118] **WARNING** — Use for computed properties
[F-P06-119] **WARNING** — Don't use on Eloquent model properties
[F-P06-120] **WARNING** — Hooks are inherited

### Asymmetric Visibility (PHP 8.4)

[F-P06-121] **WARNING** — Different visibility for read vs write
[F-P06-122] **WARNING** — Use for immutable-from-outside properties
[F-P06-123] **WARNING** — Constructor promotion syntax
[F-P06-124] **WARNING** — Set visibility must be equal or more restrictive than get
[F-P06-125] **WARNING** — Prefer over separate getter methods
[F-P06-126] **WARNING** — Value objects benefit most
