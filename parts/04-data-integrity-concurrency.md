[← Previous Part](03-authentication-authorization.md) | [Full Checklist](../checklist.md) | [Next Part →](05-financial-monetary-correctness.md)

# Part IV — Data Integrity & Concurrency

**11 sections · 84 checks**

- [5. Concurrency, Transactions & Race Conditions](#5-concurrency-transactions-race-conditions)
- [36. State Machine Integrity](#36-state-machine-integrity)
- [91. Transaction Reference & Idempotency Patterns](#91-transaction-reference-idempotency-patterns)
- [92. Approval Workflow Integrity](#92-approval-workflow-integrity)
- [127. Optimistic Locking Patterns](#127-optimistic-locking-patterns)
- [128. Distributed Lock Strategies](#128-distributed-lock-strategies)
- [129. Saga & Compensation Patterns](#129-saga-compensation-patterns)
- [130. Event Ordering & Causality](#130-event-ordering-causality)
- [131. Idempotency at Every Layer](#131-idempotency-at-every-layer)
- [132. Eventual Consistency Handling](#132-eventual-consistency-handling)
- [133. Conflict Resolution Strategies](#133-conflict-resolution-strategies)

---

## 5. Concurrency, Transactions & Race Conditions

### Database Transactions

- [ ] **Multi-model writes must be wrapped in transactions** — Use `DB::transaction()` or a `Transactional` trait/concern.

```php
// SAFE
DB::transaction(function () use ($data) {
    $order = Order::create($data['order']);
    $order->items()->createMany($data['items']);
    $order->payment()->create($data['payment']);
});

// DANGEROUS — partial writes on failure
$order = Order::create($data['order']);
$order->items()->createMany($data['items']); // If this fails, orphaned order
```

- [ ] **Check transaction depth in traits/concerns that require transactions** — Assert `DB::transactionLevel() > 0` at runtime for code that must run inside a transaction.
- [ ] **Nested transactions use savepoints** — Laravel handles savepoints automatically via `DB::transaction()` nesting.

### TOCTOU (Time-of-Check-to-Time-of-Use)

- [ ] **Pessimistic lock before status checks** — Read state with `lockForUpdate()`, then validate, then write. All within one transaction.

```php
// SAFE — lock, check, update
DB::transaction(function () use ($id, $userId) {
    $entry = JournalEntry::query()->lockForUpdate()->findOrFail($id);

    if ($entry->status !== Status::Approved) {
        throw new InvalidStateException('Entry is not in Approved state');
    }

    $entry->update(['status' => Status::Posted, 'posted_by' => $userId]);
});

// DANGEROUS — check outside lock
$entry = JournalEntry::findOrFail($id);
if ($entry->status !== Status::Approved) { /* ... */ }
// Another request could change status between check and update
$entry->update(['status' => Status::Posted]);
```

- [ ] **Idempotency keys for retryable operations** — Queue jobs, webhook handlers, and double-click-prone endpoints need idempotency.

```php
// Use unique constraint on idempotency key column
IdempotencyRecord::firstOrCreate(['key' => $idempotencyKey]);
```

### Deadlock Prevention

- [ ] **Lock rows in consistent order** — When locking multiple rows, always lock in the same order (e.g., by primary key ASC) to prevent deadlocks.
- [ ] **Keep transactions short** — Don't do HTTP calls, file I/O, or heavy computation inside transactions.
- [ ] **Use optimistic locking for low-contention updates** — Check a `version` or `updated_at` column.

---


## 36. State Machine Integrity

### Enum-Based State Machines

- [ ] **Status enums define `allowedTransitions()`** — Return an array of valid target states.
- [ ] **`canTransitionTo()` method** — Public API for checking if a transition is valid.
- [ ] **`isTerminal()` method** — Returns `true` for end states (no further transitions).
- [ ] **`label()` method** — Human-readable label for UI display.
- [ ] **`color()` method** — Badge/status color for UI.

```php
enum OrderStatus: string
{
    case Draft = 'draft';
    case Submitted = 'submitted';
    case Approved = 'approved';
    case Fulfilled = 'fulfilled';
    case Cancelled = 'cancelled';

    public function allowedTransitions(): array
    {
        return match ($this) {
            self::Draft     => [self::Submitted, self::Cancelled],
            self::Submitted => [self::Approved, self::Cancelled],
            self::Approved  => [self::Fulfilled, self::Cancelled],
            default         => [],
        };
    }

    public function isTerminal(): bool
    {
        return match ($this) {
            self::Fulfilled, self::Cancelled => true,
            default => false,
        };
    }
}
```

### Model Integration

- [ ] **Use `transitionTo()` method, never direct status updates** — The method validates the transition.
- [ ] **Immutability on terminal states** — Models in terminal states should reject all writes.
- [ ] **Log all state transitions** — Activity log with `old_status → new_status`.
- [ ] **State machine guards run inside transactions** — Prevent TOCTOU on concurrent transitions.

---


## 91. Transaction Reference & Idempotency Patterns

- [ ] **Centralize reference generation** — One factory method, one format, one place to change.
- [ ] **Reference format includes prefix, timestamp, random** — `JE-20260303120000-A8F3B2C1` is traceable and unique.
- [ ] **Register all reference prefixes in an enum** — Prevents collisions across domains.
- [ ] **Validate reference format at system boundaries** — `fromValidated()` for API input and imports.
- [ ] **Don't re-validate persisted references** — Data read from DB is already valid.
- [ ] **Idempotency keys are unique-constrained** — Database-level uniqueness prevents double processing.
- [ ] **Idempotency key expiry** — Clean up keys older than 24-48 hours.
- [ ] **Return the same response for duplicate idempotent requests** — Don't return an error; return the original result.

---


## 92. Approval Workflow Integrity

- [ ] **Segregation of duties enforced** — Creator cannot approve. Approver cannot be the same as creator.
- [ ] **Multi-level approval** — Define levels in workflow templates. Each level requires a different person.
- [ ] **Approval deadlines** — Pending approvals older than N days should trigger alerts.
- [ ] **Rejection with reason** — Mandatory reason field on rejection. Logged to audit trail.
- [ ] **Recall/withdraw by creator** — Creator can withdraw a pending approval. Not after approved.
- [ ] **Delegation** — Approve on behalf of another user with explicit delegation record.
- [ ] **Approval comments immutable** — Once submitted, approval decisions cannot be edited or deleted.
- [ ] **Approval state resets on re-submission** — If a rejected item is edited and resubmitted, approvals reset.

---


## 127. Optimistic Locking Patterns

- [ ] **Add `version` column for contested records** — Increment on every update.

```php
// Migration
$table->unsignedInteger('version')->default(1);

// Update with version check
$affected = JournalEntry::where('id', $entry->id)
    ->where('version', $entry->version)
    ->update([
        'status' => 'approved',
        'version' => $entry->version + 1,
    ]);

if ($affected === 0) {
    throw new StaleRecordException('Record was modified by another user');
}
```

- [ ] **Return `409 Conflict` on version mismatch** — Frontend should refresh and retry.
- [ ] **Frontend sends `version` with update requests** — Hidden field or request header.
- [ ] **Alternative: `updated_at` timestamp comparison** — Less reliable under high concurrency but simpler.
- [ ] **Combine with `lockForUpdate()` for critical paths** — Optimistic for UI, pessimistic for batch processing.
- [ ] **Retry logic with backoff** — On conflict, wait briefly and retry (max 3 attempts).

---


## 128. Distributed Lock Strategies

- [ ] **Use `Cache::lock()` for cross-process locks** — Redis-backed atomic locks.

```php
$lock = Cache::lock('process-settlement:' . $settlementId, 30);

if ($lock->get()) {
    try {
        // Critical section
        $this->processSettlement($settlement);
    } finally {
        $lock->release();
    }
}
```

- [ ] **Always set a TTL** — Prevent deadlocks from crashed processes. TTL should exceed expected operation time.
- [ ] **Use `block()` for waiting** — `$lock->block(10)` waits up to 10 seconds to acquire.
- [ ] **Owner token for safe release** — Only the process that acquired the lock can release it.

```php
$lock = Cache::lock('key', 30);
$token = $lock->get();
// Later...
Cache::restoreLock('key', $token)->release();
```

- [ ] **Avoid distributed locks when possible** — Database-level `FOR UPDATE` is simpler and more reliable for single-database systems.
- [ ] **Monitor lock contention** — High wait times indicate an architectural problem, not a tuning problem.
- [ ] **No nested locks** — Prevent deadlocks by establishing a global lock ordering.

---


## 129. Saga & Compensation Patterns

- [ ] **Long-running transactions use the Saga pattern** — Each step has a compensating action.

```php
class SettlementSaga
{
    public function execute(Settlement $settlement): void
    {
        $this->debitWallet($settlement);         // Step 1
        try {
            $this->createJournalEntry($settlement); // Step 2
        } catch (Throwable $e) {
            $this->creditWallet($settlement);     // Compensate Step 1
            throw $e;
        }
        try {
            $this->notifyParties($settlement);     // Step 3
        } catch (Throwable $e) {
            // Non-critical — log but don't compensate
            report($e);
        }
    }
}
```

- [ ] **Compensation is idempotent** — Re-running a compensation step produces the same result.
- [ ] **Log each step completion** — For debugging and manual intervention.
- [ ] **Distinguish critical vs non-critical steps** — Notification failure shouldn't roll back a financial transaction.
- [ ] **Timeouts on each step** — Don't wait forever for external services.
- [ ] **Saga state machine** — Track which steps have completed, which need compensation.
- [ ] **Manual override for stuck sagas** — Admin UI to force-complete or force-compensate.

---


## 130. Event Ordering & Causality

- [ ] **Events carry timestamps** — `occurred_at` from the originating system, not the storage time.
- [ ] **Sequence numbers for strict ordering** — Within an aggregate, events have monotonically increasing sequence numbers.
- [ ] **Causal ordering via vector clocks** — When events span multiple aggregates, use causal metadata.
- [ ] **Idempotent event handlers** — Processing the same event twice produces the same result.
- [ ] **Out-of-order detection** — If event N+2 arrives before N+1, queue it or fail loudly.
- [ ] **Event replay respects original order** — `StoredEvent::query()->orderBy('id')`.
- [ ] **No side effects in projectors that depend on wall-clock time** — Use the event's `occurred_at`, not `now()`.
- [ ] **Aggregate version validation** — Before recording, verify `$this->aggregateVersion` matches expectations.

---


## 131. Idempotency at Every Layer

- [ ] **HTTP endpoints** — Use idempotency keys in request headers.

```php
// Middleware checks for duplicate requests
$key = $request->header('Idempotency-Key');
if ($key && Cache::has("idempotency:{$key}")) {
    return Cache::get("idempotency:{$key}");
}
// Process request...
Cache::put("idempotency:{$key}", $response, now()->addHours(24));
```

- [ ] **Queue jobs** — `ShouldBeUnique` or manual deduplication with job ID.
- [ ] **Database writes** — Use `updateOrCreate` or unique constraints + `INSERT IGNORE`.
- [ ] **Event handlers** — Track processed event IDs. Skip duplicates.
- [ ] **External API calls** — Send idempotency keys to payment gateways and partners.
- [ ] **Webhook receivers** — Track `event_id` to prevent double-processing.
- [ ] **Idempotency key TTL matches operation lifecycle** — Don't expire keys before the client might retry.
- [ ] **Return same response for duplicate requests** — Don't return an error; return the original success response.

---


## 132. Eventual Consistency Handling

- [ ] **UI communicates async operations clearly** — "Your request is being processed" with polling or push updates.
- [ ] **Read-your-own-writes** — After a write, the same user session sees the updated data (use primary DB, not replica).
- [ ] **Stale reads are acceptable for aggregations** — Dashboards can show data a few seconds behind.
- [ ] **Compensation for failed async operations** — If an event-driven write fails, publish a compensating event.
- [ ] **Retry with exponential backoff** — Failed event handlers retry: 1s, 5s, 30s, 5m, 30m.
- [ ] **Dead letter queue** — Events that fail after all retries go to a DLQ for manual inspection.
- [ ] **Reconciliation jobs** — Periodic batch jobs verify eventual consistency actually converged.
- [ ] **No distributed transactions** — 2PC across services is fragile. Use sagas (§129) instead.

---


## 133. Conflict Resolution Strategies

- [ ] **Last-write-wins (LWW)** — Simplest. Acceptable for non-critical fields (display name, preferences).
- [ ] **First-write-wins** — For immutable records (journal entries, audit events).
- [ ] **Application-level merge** — For concurrent edits to different fields of the same record.

```php
// Merge strategy: accept non-conflicting changes
if ($current->name !== $original->name && $request->name !== $original->name) {
    throw new ConflictException('Both users edited the name field');
}
```

- [ ] **User-resolved conflicts** — Show both versions to the user and let them choose.
- [ ] **CRDTs for specific data types** — Counters, sets, and registers that merge automatically.
- [ ] **Conflict detection before resolution** — You can't resolve what you don't detect. Use version numbers (§127).
- [ ] **Audit conflicts** — Log every conflict occurrence, resolution method, and resulting state.

---


---

[← Previous Part](03-authentication-authorization.md) | [Full Checklist](../checklist.md) | [Next Part →](05-financial-monetary-correctness.md)
