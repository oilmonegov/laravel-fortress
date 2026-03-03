[← Previous Part](08-laravel-framework-mastery.md) | [Full Checklist](../checklist.md) | [Next Part →](10-frontend-engineering.md)

# Part IX — Database Engineering

**19 sections · 158 checks**

- [12. Database, Migrations & Query Safety](#12-database-migrations-query-safety)
- [41. Eloquent Relationship & Polymorphic Safety](#41-eloquent-relationship-polymorphic-safety)
- [42. Pagination & Unbounded Query Prevention](#42-pagination-unbounded-query-prevention)
- [54. Zero-Downtime Migration Safety](#54-zero-downtime-migration-safety)
- [55. Collection & Array Safety](#55-collection-array-safety)
- [64. Eloquent Query Scoping & Filters](#64-eloquent-query-scoping-filters)
- [65. Soft Delete Pitfalls](#65-soft-delete-pitfalls)
- [78. JSON Column Safety](#78-json-column-safety)
- [90. UUID & ULID Best Practices](#90-uuid-ulid-best-practices)
- [169. Schema Design Patterns](#169-schema-design-patterns)
- [170. MySQL Strict Mode & sql_mode](#170-mysql-strict-mode-sql_mode)
- [171. Read Replica & Connection Splitting](#171-read-replica-connection-splitting)
- [172. Index Optimization & EXPLAIN](#172-index-optimization-explain)
- [173. Database Partitioning Strategies](#173-database-partitioning-strategies)
- [174. Connection Pooling & Limits](#174-connection-pooling-limits)
- [175. Migration Rollback Safety](#175-migration-rollback-safety)
- [176. Slow Query Detection & Monitoring](#176-slow-query-detection-monitoring)
- [177. Foreign Key Cascade Strategy](#177-foreign-key-cascade-strategy)
- [178. Composite Key & Unique Constraint Pitfalls](#178-composite-key-unique-constraint-pitfalls)

---

## 12. Database, Migrations & Query Safety

### Migrations

- [ ] **Column modifications preserve existing attributes** — When `->change()` on a column, re-specify all attributes (nullable, default, comment). Omitted attributes are silently dropped.
- [ ] **Foreign keys use appropriate delete action** — `cascadeOnDelete()` for child data, `restrictOnDelete()` for data that must not be orphan-deleted (financial records, audit trails).
- [ ] **Add `down()` method that reverses `up()`** — Rollback must work.
- [ ] **Add indexes on all foreign keys** — MySQL does this automatically; other engines may not.
- [ ] **Add composite indexes for common query patterns** — `(status, created_at)`, `(user_id, status)`, etc.
- [ ] **Use `uuid()` or `ulid()` for public-facing IDs** — Auto-increment IDs enable enumeration.

### Indexes

- [ ] **Unique constraints on natural keys** — Email addresses, reference numbers, (currency_pair + timestamp) combinations.
- [ ] **Composite indexes match query column order** — `index(['status', 'created_at'])` only helps queries that filter on `status` first.
- [ ] **Index columns used in WHERE, ORDER BY, JOIN** — Use `EXPLAIN` to verify queries use indexes.
- [ ] **No redundant indexes** — A composite index `(a, b)` already covers queries on just `(a)`.

### Soft Deletes

- [ ] **All domain models use `SoftDeletes`** — Data should never be physically deleted in production.
- [ ] **Unique constraints with soft deletes** — Use `->whereNull('deleted_at')` partial unique indexes, or handle uniqueness in application logic.
- [ ] **`withTrashed()` in relationships where needed** — Soft-deleted parents should still be accessible from child records.

### Query Optimisation

- [ ] **Use `select()` to limit columns** — Don't `SELECT *` when you need 3 fields.
- [ ] **Use `chunk()` or `cursor()` for large datasets** — Don't load 100k rows into memory.
- [ ] **Use `exists()` not `count() > 0`** — `EXISTS` short-circuits on the first match.
- [ ] **Use `pluck()` for single-column results** — Returns a flat collection.
- [ ] **Use database-level aggregation** — `SUM`, `COUNT`, `AVG` in SQL, not PHP loops.

### Transaction Isolation

- [ ] **Understand your isolation level** — MySQL default is `REPEATABLE READ`. Use `READ COMMITTED` for long-running reports to avoid gap locks.
- [ ] **Use `lockForUpdate()` for pessimistic locking** — Prevents concurrent reads from getting stale data.
- [ ] **Use `sharedLock()` for read consistency** — When you need consistent reads without preventing other reads.

---


## 41. Eloquent Relationship & Polymorphic Safety

### Relationship Security

- [ ] **Morph maps for polymorphic relationships** — Map class names to short aliases. Prevents class name exposure in database.

```php
// AppServiceProvider::boot()
Relation::enforceMorphMap([
    'user' => User::class,
    'post' => Post::class,
    'comment' => Comment::class,
]);
```

- [ ] **`enforceMorphMap()` in production** — Throws if an unmapped class is used. Prevents data corruption.
- [ ] **UUID morph columns for UUID models** — Use `uuidMorphs()` or `nullableUuidMorphs()` instead of `morphs()` when the related model uses UUIDs.

### Relationship Query Safety

- [ ] **Eager load to prevent N+1** — `with()` on queries, `$with` on models for always-needed relations.
- [ ] **`withCount()` instead of loading the full relation** — When you only need the count.
- [ ] **`has()` / `whereHas()` for existence filters** — Don't load relations just to check existence.
- [ ] **`withTrashed()` for soft-deleted parent access** — Child records should still be able to access their (soft-deleted) parent.

### Pivot / Many-to-Many

- [ ] **Use `sync()` carefully** — `sync()` detaches all existing and reattaches. Use `syncWithoutDetaching()` to only add new ones.
- [ ] **Validate pivot data** — Don't pass unvalidated user input to `attach()` or `sync()` pivot arrays.

---


## 42. Pagination & Unbounded Query Prevention

### Server-Side Pagination

- [ ] **Every list endpoint is paginated** — Use `->paginate($perPage)` or `->cursorPaginate($perPage)`.
- [ ] **Cap `per_page` parameter** — Don't let users request `?per_page=999999`.

```php
$perPage = min($request->integer('per_page', 25), 100);
$results = Model::query()->paginate($perPage);
```

- [ ] **Use cursor pagination for large datasets** — More efficient than offset pagination at high page numbers.
- [ ] **API responses include pagination metadata** — `total`, `per_page`, `current_page`, `last_page`, `next_page_url`.

### Exports & Bulk Operations

- [ ] **Queue large exports** — Don't generate a 100MB CSV in a web request.
- [ ] **Stream large downloads** — Use `StreamedResponse` or `LazyCollection` for huge datasets.
- [ ] **Limit export row counts** — Set a maximum (e.g., 50,000 rows) or require date range filters.

---


## 54. Zero-Downtime Migration Safety

### Safe Migration Patterns

- [ ] **Add columns as nullable first, then backfill, then add constraints** — Adding a `NOT NULL` column without default locks the table.

```php
// Step 1: Add nullable column (no lock)
Schema::table('orders', fn (Blueprint $table) =>
    $table->string('tracking_number')->nullable()
);

// Step 2: Backfill data (queue job)
Order::whereNull('tracking_number')->chunk(500, fn ($orders) =>
    $orders->each->update(['tracking_number' => 'N/A'])
);

// Step 3: Add constraint (after backfill complete)
Schema::table('orders', fn (Blueprint $table) =>
    $table->string('tracking_number')->nullable(false)->change()
);
```

- [ ] **Never rename columns in one step** — Add new column, copy data, update code, drop old column across multiple deployments.
- [ ] **Never drop columns that code still references** — Remove code references first, deploy, then drop column.
- [ ] **Use `after()` for column ordering** — `$table->string('foo')->after('bar')` for readability but it's not required.

### Index Safety

- [ ] **Create indexes concurrently when possible** — Large table index creation locks the table. Use `ALGORITHM=INPLACE, LOCK=NONE` on MySQL 8.
- [ ] **Drop indexes before dropping columns** — Some databases error if you drop a column with an index.

### Testing Migrations

- [ ] **Test both `up()` and `down()`** — Rollback must work.
- [ ] **Test migration on production-sized data** — A migration that works on 100 rows may lock the table at 10M rows.
- [ ] **Run `migrate --pretend` first** — See the SQL without executing.

---


## 55. Collection & Array Safety

### Null & Empty Handling

- [ ] **Use `->first()` with caution — it returns `null`** — Chain `->firstOrFail()` or null-check the result.
- [ ] **Don't chain methods on potentially null results** — `$collection->first()->name` throws if collection is empty.

```php
// DANGEROUS
$name = User::where('email', $email)->first()->name; // Null pointer if not found

// SAFE
$user = User::where('email', $email)->firstOrFail(); // Throws 404
$name = $user->name;

// SAFE — null coalescing
$name = User::where('email', $email)->value('name') ?? 'Unknown';
```

- [ ] **Use `->isEmpty()` / `->isNotEmpty()`** — Not `count($collection) === 0`.
- [ ] **Use `->whenNotEmpty()` / `->whenEmpty()`** — For conditional processing.

### Collection Method Pitfalls

- [ ] **`->pluck()` returns duplicates** — Use `->pluck()->unique()` if uniqueness matters.
- [ ] **`->map()` vs `->transform()`** — `map()` returns a new collection. `transform()` mutates in place.
- [ ] **`->filter()` without callback removes falsy values** — `false`, `null`, `0`, `''`, `[]` are all removed. Be explicit.
- [ ] **`->each()` cannot break early** — Use `->first()` with a callback, or a `foreach` loop.
- [ ] **`->reduce()` needs an initial value** — Without it, the first element is used as the initial value.

### Array Spread & Merge

- [ ] **`array_merge()` re-indexes numeric keys** — Use `+` operator or spread `[...$a, ...$b]` to preserve keys.
- [ ] **`[...$a, ...$b]` — later values overwrite earlier ones** — Same behavior as `array_merge()` for string keys.

---


## 64. Eloquent Query Scoping & Filters

- [ ] **Never pass raw user input to `where()` column names** — Validate against an allowlist.
- [ ] **Use Spatie Query Builder `AllowedFilter`** — Limits which filters users can apply.
- [ ] **Use `AllowedSort` to prevent SQL injection via sort columns**.
- [ ] **Default sorts prevent unpredictable ordering** — Always provide `->defaultSort('-created_at')`.
- [ ] **Filter by enum value, not raw string** — `AllowedFilter::exact('status')` combined with `Rule::enum()` in validation.
- [ ] **Scope nested relationships** — `AllowedFilter::scope('active_users')` delegates to model scope.
- [ ] **Paginate all query builder results** — Never return unbound collections.

---


## 65. Soft Delete Pitfalls

- [ ] **Unique constraints break with soft deletes** — Two records with the same email can exist if one is soft-deleted. Use partial unique indexes: `CREATE UNIQUE INDEX idx ON users(email) WHERE deleted_at IS NULL`.
- [ ] **`withTrashed()` in relationships for FK integrity** — A child record should still access its soft-deleted parent.
- [ ] **`forceDelete()` requires explicit confirmation** — Never auto-call in batch operations without human review.
- [ ] **Cascade soft deletes manually** — Laravel doesn't cascade soft deletes. Use observers or `softDeletes` packages.
- [ ] **Restore cascades** — If you soft-delete a parent and its children, restoring the parent should restore the children too.
- [ ] **Global scope means `count()` excludes deleted** — `Model::count()` silently excludes trashed records. Use `withTrashed()->count()` for totals.
- [ ] **Pruning old soft-deleted records** — Use `model:prune` with `prunable()` scope for retention policy enforcement.

---


## 78. JSON Column Safety

- [ ] **Cast JSON columns with `'array'` or `'collection'`** — Not raw `json_decode()`.
- [ ] **Validate JSON structure** — `'metadata' => 'required|array'` plus nested rules.
- [ ] **Index JSON paths for queries** — MySQL: `ALTER TABLE t ADD INDEX idx ((CAST(data->>'$.status' AS CHAR(20))))`.
- [ ] **Don't store relational data in JSON** — If you query/filter by it, it belongs in a proper column.
- [ ] **Beware of `null` vs missing key** — `$model->metadata['key']` throws if key doesn't exist. Use `$model->metadata['key'] ?? null`.
- [ ] **JSON merge update safety** — `$model->update(['metadata->key' => $value])` doesn't merge — it replaces the entire JSON if not careful. Use `->forceFill()` for JSON arrow syntax.

---


## 90. UUID & ULID Best Practices

- [ ] **Use UUIDv7 (time-sorted) for primary keys** — Laravel's `HasUuids` uses UUIDv7. Preserves insert order for B-tree indexes.
- [ ] **Use UUIDv4 for non-sequential tokens** — Non-guessable, no time information.
- [ ] **Use ULID for human-readable sorted IDs** — 26 characters, Crockford Base32, sortable.
- [ ] **Consistent column types** — `$table->uuid('id')->primary()` for UUID PKs. `$table->foreignUuid('user_id')` for FKs.
- [ ] **Type hint UUID IDs as `string`** — Not `int`. UUIDs are strings.
- [ ] **Don't expose auto-increment IDs alongside UUIDs** — Defeats the purpose. Use UUID as the only identifier.
- [ ] **Index UUID columns** — B-tree indexes on UUID columns work well with UUIDv7 (time-sorted). UUIDv4 causes index fragmentation.

---


## 169. Schema Design Patterns

- [ ] **Normalize to 3NF by default** — Then denormalize selectively for performance-critical read paths.
- [ ] **Star schema for reporting tables** — Fact tables (journal_lines) + dimension tables (accounts, periods, users).
- [ ] **Lookup tables for reference data** — Currencies, countries, account types — not hardcoded enums in application code if the list changes frequently.
- [ ] **History tables for temporal data** — `exchange_rates` stores every rate, not just the latest.
- [ ] **Audit columns on every table** — `created_at`, `updated_at`, `deleted_at`, `created_by`, `updated_by`.
- [ ] **UUID primary keys for distributed systems** — UUIDv7 (time-ordered) over UUIDv4 (random) for index performance.
- [ ] **No nullable foreign keys without justification** — A nullable FK often indicates a missing relationship or incorrect schema.
- [ ] **Document schema decisions** — Why is this column `VARCHAR(20)` and not `ENUM`? Why is this relationship polymorphic?

---


## 170. MySQL Strict Mode & sql_mode

- [ ] **Enable strict mode** — `STRICT_TRANS_TABLES` prevents silent data truncation.

```sql
-- Verify
SELECT @@sql_mode;
-- Should include: STRICT_TRANS_TABLES,NO_ZERO_DATE,NO_ZERO_IN_DATE,ERROR_FOR_DIVISION_BY_ZERO
```

- [ ] **`NO_ZERO_DATE`** — Rejects `0000-00-00` as a date value.
- [ ] **`NO_ZERO_IN_DATE`** — Rejects `2026-00-15` (zero month or day).
- [ ] **`ERROR_FOR_DIVISION_BY_ZERO`** — Makes `SELECT 1/0` an error, not `NULL` with a warning.
- [ ] **`ONLY_FULL_GROUP_BY`** — Requires all non-aggregated columns in `GROUP BY`. Prevents ambiguous results.
- [ ] **Test with production `sql_mode`** — Development MySQL should have the same `sql_mode` as production.
- [ ] **Laravel's `strict` mode in database config** — `'strict' => true` in `config/database.php` sets sensible defaults.
- [ ] **No `SET sql_mode = ''` workarounds** — If a query fails in strict mode, fix the query, don't weaken the mode.

---


## 171. Read Replica & Connection Splitting

- [ ] **Configure read/write connections** — Laravel supports this natively.

```php
// config/database.php
'mysql' => [
    'read' => ['host' => env('DB_READ_HOST', '127.0.0.1')],
    'write' => ['host' => env('DB_WRITE_HOST', '127.0.0.1')],
    'sticky' => true,  // After write, subsequent reads use write connection
    // ...
],
```

- [ ] **`sticky` option for read-your-own-writes** — After a write within the same request, reads go to the write connection.
- [ ] **Reports and dashboards use read connection** — `Model::on('mysql-read')->query()` or let Laravel auto-route.
- [ ] **Replication lag awareness** — After a write, data may not be on the replica for 10-500ms.
- [ ] **Don't use replicas for financial consistency checks** — Balance checks must hit the primary.
- [ ] **Monitor replication lag** — Alert when lag exceeds acceptable threshold (e.g., 1 second).
- [ ] **Failover to primary** — If the replica is down, route reads to primary, not fail.

---


## 172. Index Optimization & EXPLAIN

- [ ] **EXPLAIN every slow query** — `EXPLAIN SELECT ...` shows the execution plan.

```sql
EXPLAIN SELECT * FROM journal_lines
WHERE account_id = 'abc' AND posted_at BETWEEN '2026-01-01' AND '2026-01-31';
-- Look for: type=ref or range (good), type=ALL (bad — full table scan)
```

- [ ] **Composite indexes match query order** — `INDEX(account_id, posted_at)` helps the query above. `INDEX(posted_at, account_id)` does not.
- [ ] **Leftmost prefix rule** — A composite index `(a, b, c)` can serve queries on `(a)`, `(a, b)`, `(a, b, c)` — but not `(b)` or `(c)` alone.
- [ ] **Covering indexes** — If the index includes all queried columns, MySQL reads only the index, not the table.
- [ ] **Don't over-index** — Each index slows writes. Profile before adding.
- [ ] **Remove unused indexes** — `sys.schema_unused_indexes` in MySQL shows indexes with zero reads.
- [ ] **Index for `ORDER BY`** — If you sort by `created_at DESC`, include it in the index.
- [ ] **`FORCE INDEX` as last resort** — Usually indicates a missing index or bad query structure.

---


## 173. Database Partitioning Strategies

- [ ] **Partition by date for time-series data** — Journal entries, audit logs, exchange rates.

```sql
ALTER TABLE audit_log PARTITION BY RANGE (YEAR(created_at)) (
    PARTITION p2025 VALUES LESS THAN (2026),
    PARTITION p2026 VALUES LESS THAN (2027),
    PARTITION pmax  VALUES LESS THAN MAXVALUE
);
```

- [ ] **Partition pruning** — Queries that include the partition key only scan relevant partitions.
- [ ] **Don't partition small tables** — Partitioning adds overhead. Only for tables > 10M rows.
- [ ] **Unique indexes must include the partition key** — MySQL limitation.
- [ ] **Archive old partitions** — `ALTER TABLE ... DROP PARTITION p2023` is instant (vs. `DELETE` millions of rows).
- [ ] **Test partition boundaries** — Data inserted on Dec 31 23:59:59 and Jan 1 00:00:00 land in correct partitions.
- [ ] **Application-transparent** — Laravel Eloquent doesn't know about partitions. Queries work normally.

---


## 174. Connection Pooling & Limits

- [ ] **Set `max_connections` appropriately** — Default MySQL 151 may be too low for production.
- [ ] **Laravel's persistent connections** — `'options' => [PDO::ATTR_PERSISTENT => true]` reuses connections across requests.
- [ ] **Use a connection pooler for high concurrency** — ProxySQL, PgBouncer (PostgreSQL), or cloud-managed pooling.
- [ ] **Monitor connection count** — `SHOW STATUS LIKE 'Threads_connected';`. Alert when approaching max.
- [ ] **Close connections in long-running processes** — Queue workers, daemons: `DB::disconnect()` periodically.
- [ ] **Set `wait_timeout`** — Kill idle connections after a reasonable period (default 28800s = 8 hours is usually too long).
- [ ] **Connection per queue worker** — Each worker holds a connection. 10 workers = 10 connections minimum.

---


## 175. Migration Rollback Safety

- [ ] **Every migration has a working `down()` method** — Test it: `php artisan migrate:rollback --step=1`.
- [ ] **`down()` reverses the `up()` exactly** — If `up()` adds a column, `down()` drops it. If `up()` renames, `down()` renames back.
- [ ] **Destructive `down()` methods are acceptable** — Dropping a column in `down()` is fine; the migration is being reversed intentionally.
- [ ] **Cannot rollback data migrations** — If `up()` transforms data, `down()` may not be able to reverse it. Document this.

```php
public function down(): void
{
    // WARNING: This migration transforms data and cannot be fully reversed.
    // The column is dropped but the original values cannot be restored.
    Schema::table('users', function (Blueprint $table) {
        $table->dropColumn('full_name');
    });
}
```

- [ ] **Test the full cycle** — `migrate → rollback → migrate` should work without errors.
- [ ] **Don't modify old migrations** — Create a new migration. Old migrations may have already run in production.
- [ ] **Squash migrations periodically** — `php artisan schema:dump` creates a SQL dump that replaces old migrations.

---


## 176. Slow Query Detection & Monitoring

- [ ] **Enable MySQL slow query log** — `slow_query_log = ON`, `long_query_time = 1` (seconds).
- [ ] **Laravel query log in development** — `DB::enableQueryLog()` + `DB::getQueryLog()`.
- [ ] **Use `DB::listen()` for real-time monitoring** — Log queries above a threshold.

```php
// In AppServiceProvider::boot()
if (app()->isProduction()) {
    DB::listen(function (QueryExecuted $query) {
        if ($query->time > 1000) { // > 1 second
            Log::warning('Slow query', [
                'sql' => $query->sql,
                'time' => $query->time,
                'bindings' => $query->bindings,
            ]);
        }
    });
}
```

- [ ] **`preventLazyLoading()` in non-production** — `Model::preventLazyLoading(!app()->isProduction())`.
- [ ] **Identify N+1 queries** — Laravel Debugbar or `preventLazyLoading()` catches them.
- [ ] **Monitor query count per request** — > 50 queries per request is a red flag.
- [ ] **EXPLAIN slow queries before optimizing** — Don't guess; use the execution plan.
- [ ] **Dashboard for slow queries** — Aggregate slow query logs into a dashboard for trends.

---


## 177. Foreign Key Cascade Strategy

- [ ] **Default to `RESTRICT`** — Prevents accidental deletion of referenced records.

```php
$table->foreignUuid('account_id')
    ->constrained('accounts')
    ->restrictOnDelete();  // Explicit — cannot delete account with journal lines
```

- [ ] **`CASCADE` only for owned children** — Period close steps belong to a period close. Deleting the parent deletes steps.
- [ ] **Never `CASCADE` on financial records** — Journal entries, payments, settlements: always `RESTRICT`.
- [ ] **`SET NULL` for optional relationships** — If the relationship is nullable and the parent can be removed.
- [ ] **Document cascade behavior** — In the migration comment, explain WHY this cascade strategy was chosen.
- [ ] **Test cascade behavior** — Write a test that attempts to delete a parent and verifies the expected outcome.
- [ ] **Review cascades on schema changes** — When adding a new FK, check what happens if the parent is deleted.

---


## 178. Composite Key & Unique Constraint Pitfalls

- [ ] **Unique constraints prevent duplicate data** — `$table->unique(['base_currency', 'quote_currency', 'captured_at'])`.
- [ ] **Order matters for composite indexes** — Put the most selective column first.
- [ ] **NULL handling in unique constraints** — MySQL allows multiple `NULL` values in a unique index. PostgreSQL treats them as distinct.
- [ ] **Application-level uniqueness + DB constraint** — Validate uniqueness in the form request AND enforce at the database level.

```php
// Form request
'email' => ['required', 'email', Rule::unique('users')->ignore($this->user)],

// Migration
$table->string('email')->unique();
```

- [ ] **Composite unique with soft deletes** — Soft-deleted records still occupy the unique constraint. Use a partial index or add `deleted_at` to the constraint.
- [ ] **Don't use composite primary keys with Eloquent** — Eloquent doesn't support composite PKs natively. Use a surrogate PK + unique constraint.
- [ ] **Migration naming for constraints** — `{table}_{columns}_unique`: `exchange_rates_pair_captured_unique`.

---


---

[← Previous Part](08-laravel-framework-mastery.md) | [Full Checklist](../checklist.md) | [Next Part →](10-frontend-engineering.md)
