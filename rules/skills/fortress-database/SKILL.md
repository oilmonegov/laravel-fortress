---
description: "Laravel Fortress Part 9 — Database Engineering. 19 sections, 158 checks covering Migrations, indexes, JSON columns, replicas, partitioning, connection pooling."
---

# Fortress: Database Engineering

> Part IX of The Laravel Fortress — 19 sections · 158 checks
> https://github.com/oilmonegov/laravel-fortress/blob/main/parts/09-database-engineering.md

When writing or reviewing code, enforce these rules.
Check `.fortress.yml` in the project root for overrides.

## Project Awareness

Before enforcing rules, read `composer.json` and `package.json` to detect the project's PHP version,
Laravel version, and installed packages. Skip rules whose conditions don't match the detected stack.

## Rules

### Migrations

[F-P09-001] **WARNING** — Column modifications preserve existing attributes
[F-P09-002] **WARNING** — Foreign keys use appropriate delete action
[F-P09-003] **WARNING** — Add `down()` method that reverses `up()`
[F-P09-004] **WARNING** — Add indexes on all foreign keys
[F-P09-005] **WARNING** — Add composite indexes for common query patterns
[F-P09-006] **WARNING** — Use `uuid()` or `ulid()` for public-facing IDs

### Indexes

[F-P09-007] **WARNING** — Unique constraints on natural keys
[F-P09-008] **WARNING** — Composite indexes match query column order
[F-P09-009] **WARNING** — Index columns used in WHERE, ORDER BY, JOIN
[F-P09-010] **WARNING** — No redundant indexes

### Soft Deletes

[F-P09-011] **WARNING** — All domain models use `SoftDeletes`
[F-P09-012] **WARNING** — Unique constraints with soft deletes
[F-P09-013] **WARNING** — `withTrashed()` in relationships where needed

### Query Optimisation

[F-P09-014] **WARNING** — Use `select()` to limit columns
[F-P09-015] **WARNING** — Use `chunk()` or `cursor()` for large datasets
[F-P09-016] **WARNING** — Use `exists()` not `count() > 0`
[F-P09-017] **WARNING** — Use `pluck()` for single-column results
[F-P09-018] **WARNING** — Use database-level aggregation

### Transaction Isolation

[F-P09-019] **WARNING** — Understand your isolation level
[F-P09-020] **WARNING** — Use `lockForUpdate()` for pessimistic locking
[F-P09-021] **WARNING** — Use `sharedLock()` for read consistency

### Relationship Security

[F-P09-022] **WARNING** — Morph maps for polymorphic relationships
[F-P09-023] **WARNING** — `enforceMorphMap()` in production
[F-P09-024] **WARNING** — UUID morph columns for UUID models

### Relationship Query Safety

[F-P09-025] **WARNING** — Eager load to prevent N+1
[F-P09-026] **WARNING** — `withCount()` instead of loading the full relation
[F-P09-027] **WARNING** — `has()` / `whereHas()` for existence filters
[F-P09-028] **WARNING** — `withTrashed()` for soft-deleted parent access

### Pivot / Many-to-Many

[F-P09-029] **WARNING** — Use `sync()` carefully
[F-P09-030] **WARNING** — Validate pivot data

### Server-Side Pagination

[F-P09-031] **WARNING** — Every list endpoint is paginated
[F-P09-032] **WARNING** — Cap `per_page` parameter
[F-P09-033] **WARNING** — Use cursor pagination for large datasets
[F-P09-034] **WARNING** — API responses include pagination metadata

### Exports & Bulk Operations

[F-P09-035] **WARNING** — Queue large exports
[F-P09-036] **WARNING** — Stream large downloads
[F-P09-037] **WARNING** — Limit export row counts

### Safe Migration Patterns

[F-P09-038] **WARNING** — Add columns as nullable first, then backfill, then add constraints
[F-P09-039] **WARNING** — Never rename columns in one step
[F-P09-040] **WARNING** — Never drop columns that code still references
[F-P09-041] **WARNING** — Use `after()` for column ordering

### Index Safety

[F-P09-042] **WARNING** — Create indexes concurrently when possible
[F-P09-043] **WARNING** — Drop indexes before dropping columns

### Testing Migrations

[F-P09-044] **WARNING** — Test both `up()` and `down()`
[F-P09-045] **WARNING** — Test migration on production-sized data
[F-P09-046] **WARNING** — Run `migrate --pretend` first

### Null & Empty Handling

[F-P09-047] **WARNING** — Use `->first()` with caution — it returns `null`
[F-P09-048] **WARNING** — Don't chain methods on potentially null results
[F-P09-049] **WARNING** — Use `->isEmpty()` / `->isNotEmpty()`
[F-P09-050] **WARNING** — Use `->whenNotEmpty()` / `->whenEmpty()`

### Collection Method Pitfalls

[F-P09-051] **WARNING** — `->pluck()` returns duplicates
[F-P09-052] **WARNING** — `->map()` vs `->transform()`
[F-P09-053] **WARNING** — `->filter()` without callback removes falsy values
[F-P09-054] **WARNING** — `->each()` cannot break early
[F-P09-055] **WARNING** — `->reduce()` needs an initial value

### Array Spread & Merge

[F-P09-056] **WARNING** — `array_merge()` re-indexes numeric keys
[F-P09-057] **WARNING** — `[...$a, ...$b]` — later values overwrite earlier ones

### Eloquent Query Scoping & Filters

[F-P09-058] **WARNING** — Never pass raw user input to `where()` column names
[F-P09-059] **WARNING** — Use Spatie Query Builder `AllowedFilter`
[F-P09-060] **WARNING** — Use `AllowedSort` to prevent SQL injection via sort columns
[F-P09-061] **WARNING** — Default sorts prevent unpredictable ordering
[F-P09-062] **WARNING** — Filter by enum value, not raw string
[F-P09-063] **WARNING** — Scope nested relationships
[F-P09-064] **WARNING** — Paginate all query builder results

### Soft Delete Pitfalls

[F-P09-065] **WARNING** — Unique constraints break with soft deletes
[F-P09-066] **WARNING** — `withTrashed()` in relationships for FK integrity
[F-P09-067] **WARNING** — `forceDelete()` requires explicit confirmation
[F-P09-068] **WARNING** — Cascade soft deletes manually
[F-P09-069] **WARNING** — Restore cascades
[F-P09-070] **WARNING** — Global scope means `count()` excludes deleted
[F-P09-071] **WARNING** — Pruning old soft-deleted records

### JSON Column Safety

[F-P09-072] **WARNING** — Cast JSON columns with `'array'` or `'collection'`
[F-P09-073] **WARNING** — Validate JSON structure
[F-P09-074] **WARNING** — Index JSON paths for queries
[F-P09-075] **WARNING** — Don't store relational data in JSON
[F-P09-076] **WARNING** — Beware of `null` vs missing key
[F-P09-077] **WARNING** — JSON merge update safety

### UUID & ULID Best Practices

[F-P09-078] **WARNING** — Use UUIDv7 (time-sorted) for primary keys
[F-P09-079] **WARNING** — Use UUIDv4 for non-sequential tokens
[F-P09-080] **WARNING** — Use ULID for human-readable sorted IDs
[F-P09-081] **WARNING** — Consistent column types
[F-P09-082] **WARNING** — Type hint UUID IDs as `string`
[F-P09-083] **WARNING** — Don't expose auto-increment IDs alongside UUIDs
[F-P09-084] **WARNING** — Index UUID columns

### Schema Design Patterns

[F-P09-085] **WARNING** — Normalize to 3NF by default
[F-P09-086] **WARNING** — Star schema for reporting tables
[F-P09-087] **WARNING** — Lookup tables for reference data
[F-P09-088] **WARNING** — History tables for temporal data
[F-P09-089] **WARNING** — Audit columns on every table
[F-P09-090] **WARNING** — UUID primary keys for distributed systems
[F-P09-091] **WARNING** — No nullable foreign keys without justification
[F-P09-092] **WARNING** — Document schema decisions

### MySQL Strict Mode & sql_mode

[F-P09-093] **WARNING** — Enable strict mode
[F-P09-094] **WARNING** — `NO_ZERO_DATE`
[F-P09-095] **WARNING** — `NO_ZERO_IN_DATE`
[F-P09-096] **WARNING** — `ERROR_FOR_DIVISION_BY_ZERO`
[F-P09-097] **WARNING** — `ONLY_FULL_GROUP_BY`
[F-P09-098] **WARNING** — Test with production `sql_mode`
[F-P09-099] **WARNING** — Laravel's `strict` mode in database config
[F-P09-100] **WARNING** — No `SET sql_mode = ''` workarounds

### Read Replica & Connection Splitting

[F-P09-101] **WARNING** — Configure read/write connections
[F-P09-102] **WARNING** — `sticky` option for read-your-own-writes
[F-P09-103] **WARNING** — Reports and dashboards use read connection
[F-P09-104] **WARNING** — Replication lag awareness
[F-P09-105] **WARNING** — Don't use replicas for financial consistency checks
[F-P09-106] **WARNING** — Monitor replication lag
[F-P09-107] **WARNING** — Failover to primary

### Index Optimization & EXPLAIN

[F-P09-108] **WARNING** — EXPLAIN every slow query
[F-P09-109] **WARNING** — Composite indexes match query order
[F-P09-110] **WARNING** — Leftmost prefix rule
[F-P09-111] **WARNING** — Covering indexes
[F-P09-112] **WARNING** — Don't over-index
[F-P09-113] **WARNING** — Remove unused indexes
[F-P09-114] **WARNING** — Index for `ORDER BY`
[F-P09-115] **WARNING** — `FORCE INDEX` as last resort

### Database Partitioning Strategies

[F-P09-116] **WARNING** — Partition by date for time-series data
[F-P09-117] **WARNING** — Partition pruning
[F-P09-118] **WARNING** — Don't partition small tables
[F-P09-119] **WARNING** — Unique indexes must include the partition key
[F-P09-120] **WARNING** — Archive old partitions
[F-P09-121] **WARNING** — Test partition boundaries
[F-P09-122] **WARNING** — Application-transparent

### Connection Pooling & Limits

[F-P09-123] **WARNING** — Set `max_connections` appropriately
[F-P09-124] **WARNING** — Laravel's persistent connections
[F-P09-125] **WARNING** — Use a connection pooler for high concurrency
[F-P09-126] **WARNING** — Monitor connection count
[F-P09-127] **WARNING** — Close connections in long-running processes
[F-P09-128] **WARNING** — Set `wait_timeout`
[F-P09-129] **WARNING** — Connection per queue worker

### Migration Rollback Safety

[F-P09-130] **WARNING** — Every migration has a working `down()` method
[F-P09-131] **WARNING** — `down()` reverses the `up()` exactly
[F-P09-132] **WARNING** — Destructive `down()` methods are acceptable
[F-P09-133] **WARNING** — Cannot rollback data migrations
[F-P09-134] **WARNING** — Test the full cycle
[F-P09-135] **WARNING** — Don't modify old migrations
[F-P09-136] **WARNING** — Squash migrations periodically

### Slow Query Detection & Monitoring

[F-P09-137] **WARNING** — Enable MySQL slow query log
[F-P09-138] **WARNING** — Laravel query log in development
[F-P09-139] **WARNING** — Use `DB::listen()` for real-time monitoring
[F-P09-140] **WARNING** — `preventLazyLoading()` in non-production
[F-P09-141] **WARNING** — Identify N+1 queries
[F-P09-142] **WARNING** — Monitor query count per request
[F-P09-143] **WARNING** — EXPLAIN slow queries before optimizing
[F-P09-144] **WARNING** — Dashboard for slow queries

### Foreign Key Cascade Strategy

[F-P09-145] **WARNING** — Default to `RESTRICT`
[F-P09-146] **WARNING** — `CASCADE` only for owned children
[F-P09-147] **WARNING** — Never `CASCADE` on financial records
[F-P09-148] **WARNING** — `SET NULL` for optional relationships
[F-P09-149] **WARNING** — Document cascade behavior
[F-P09-150] **WARNING** — Test cascade behavior
[F-P09-151] **WARNING** — Review cascades on schema changes

### Composite Key & Unique Constraint Pitfalls

[F-P09-152] **WARNING** — Unique constraints prevent duplicate data
[F-P09-153] **WARNING** — Order matters for composite indexes
[F-P09-154] **WARNING** — NULL handling in unique constraints
[F-P09-155] **WARNING** — Application-level uniqueness + DB constraint
[F-P09-156] **WARNING** — Composite unique with soft deletes
[F-P09-157] **WARNING** — Don't use composite primary keys with Eloquent
[F-P09-158] **WARNING** — Migration naming for constraints
