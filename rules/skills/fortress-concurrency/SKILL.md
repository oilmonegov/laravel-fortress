---
description: "Laravel Fortress Part 4 — Data Integrity & Concurrency. 11 sections, 84 checks covering Transactions, locking, sagas, idempotency, state machines, event ordering."
---

# Fortress: Data Integrity & Concurrency

> Part IV of The Laravel Fortress — 11 sections · 84 checks
> https://github.com/chuxolab/laravel-fortress/blob/main/parts/04-data-integrity-concurrency.md

When writing or reviewing code, enforce these rules.
Check `.fortress.yml` in the project root for overrides.

## Project Awareness

Before enforcing rules, read `composer.json` and `package.json` to detect the project's PHP version,
Laravel version, and installed packages. Skip rules whose conditions don't match the detected stack.

## Rules

### Database Transactions

[F-P04-001] **CRITICAL** — Multi-model writes must be wrapped in transactions
[F-P04-002] **CRITICAL** — Check transaction depth in traits/concerns that require transactions
[F-P04-003] **CRITICAL** — Nested transactions use savepoints

### TOCTOU (Time-of-Check-to-Time-of-Use)

[F-P04-004] **CRITICAL** — Pessimistic lock before status checks
[F-P04-005] **CRITICAL** — Idempotency keys for retryable operations

### Deadlock Prevention

[F-P04-006] **CRITICAL** — Lock rows in consistent order
[F-P04-007] **CRITICAL** — Keep transactions short
[F-P04-008] **CRITICAL** — Use optimistic locking for low-contention updates

### Enum-Based State Machines

[F-P04-009] **CRITICAL** — Status enums define `allowedTransitions()`
[F-P04-010] **CRITICAL** — `canTransitionTo()` method
[F-P04-011] **CRITICAL** — `isTerminal()` method
[F-P04-012] **CRITICAL** — `label()` method
[F-P04-013] **CRITICAL** — `color()` method

### Model Integration

[F-P04-014] **CRITICAL** — Use `transitionTo()` method, never direct status updates
[F-P04-015] **CRITICAL** — Immutability on terminal states
[F-P04-016] **CRITICAL** — Log all state transitions
[F-P04-017] **CRITICAL** — State machine guards run inside transactions

### Transaction Reference & Idempotency Patterns

[F-P04-018] **CRITICAL** — Centralize reference generation
[F-P04-019] **CRITICAL** — Reference format includes prefix, timestamp, random
[F-P04-020] **CRITICAL** — Register all reference prefixes in an enum
[F-P04-021] **CRITICAL** — Validate reference format at system boundaries
[F-P04-022] **CRITICAL** — Don't re-validate persisted references
[F-P04-023] **CRITICAL** — Idempotency keys are unique-constrained
[F-P04-024] **CRITICAL** — Idempotency key expiry
[F-P04-025] **CRITICAL** — Return the same response for duplicate idempotent requests

### Approval Workflow Integrity

[F-P04-026] **CRITICAL** — Segregation of duties enforced
[F-P04-027] **CRITICAL** — Multi-level approval
[F-P04-028] **CRITICAL** — Approval deadlines
[F-P04-029] **CRITICAL** — Rejection with reason
[F-P04-030] **CRITICAL** — Recall/withdraw by creator
[F-P04-031] **CRITICAL** — Delegation
[F-P04-032] **CRITICAL** — Approval comments immutable
[F-P04-033] **CRITICAL** — Approval state resets on re-submission

### Optimistic Locking Patterns

[F-P04-034] **CRITICAL** — Add `version` column for contested records
[F-P04-035] **CRITICAL** — Return `409 Conflict` on version mismatch
[F-P04-036] **CRITICAL** — Frontend sends `version` with update requests
[F-P04-037] **CRITICAL** — Alternative: `updated_at` timestamp comparison
[F-P04-038] **CRITICAL** — Combine with `lockForUpdate()` for critical paths
[F-P04-039] **CRITICAL** — Retry logic with backoff

### Distributed Lock Strategies

[F-P04-040] **CRITICAL** — Use `Cache::lock()` for cross-process locks
[F-P04-041] **CRITICAL** — Always set a TTL
[F-P04-042] **CRITICAL** — Use `block()` for waiting
[F-P04-043] **CRITICAL** — Owner token for safe release
[F-P04-044] **CRITICAL** — Avoid distributed locks when possible
[F-P04-045] **CRITICAL** — Monitor lock contention
[F-P04-046] **CRITICAL** — No nested locks

### Saga & Compensation Patterns

[F-P04-047] **CRITICAL** — Long-running transactions use the Saga pattern
[F-P04-048] **CRITICAL** — Compensation is idempotent
[F-P04-049] **CRITICAL** — Log each step completion
[F-P04-050] **CRITICAL** — Distinguish critical vs non-critical steps
[F-P04-051] **CRITICAL** — Timeouts on each step
[F-P04-052] **CRITICAL** — Saga state machine
[F-P04-053] **CRITICAL** — Manual override for stuck sagas

### Event Ordering & Causality

[F-P04-054] **CRITICAL** — Events carry timestamps
[F-P04-055] **CRITICAL** — Sequence numbers for strict ordering
[F-P04-056] **CRITICAL** — Causal ordering via vector clocks
[F-P04-057] **CRITICAL** — Idempotent event handlers
[F-P04-058] **CRITICAL** — Out-of-order detection
[F-P04-059] **CRITICAL** — Event replay respects original order
[F-P04-060] **CRITICAL** — No side effects in projectors that depend on wall-clock time
[F-P04-061] **CRITICAL** — Aggregate version validation

### Idempotency at Every Layer

[F-P04-062] **CRITICAL** — HTTP endpoints
[F-P04-063] **CRITICAL** — Queue jobs
[F-P04-064] **CRITICAL** — Database writes
[F-P04-065] **CRITICAL** — Event handlers
[F-P04-066] **CRITICAL** — External API calls
[F-P04-067] **CRITICAL** — Webhook receivers
[F-P04-068] **CRITICAL** — Idempotency key TTL matches operation lifecycle
[F-P04-069] **CRITICAL** — Return same response for duplicate requests

### Eventual Consistency Handling

[F-P04-070] **CRITICAL** — UI communicates async operations clearly
[F-P04-071] **CRITICAL** — Read-your-own-writes
[F-P04-072] **CRITICAL** — Stale reads are acceptable for aggregations
[F-P04-073] **CRITICAL** — Compensation for failed async operations
[F-P04-074] **CRITICAL** — Retry with exponential backoff
[F-P04-075] **CRITICAL** — Dead letter queue
[F-P04-076] **CRITICAL** — Reconciliation jobs
[F-P04-077] **CRITICAL** — No distributed transactions

### Conflict Resolution Strategies

[F-P04-078] **CRITICAL** — Last-write-wins (LWW)
[F-P04-079] **CRITICAL** — First-write-wins
[F-P04-080] **CRITICAL** — Application-level merge
[F-P04-081] **CRITICAL** — User-resolved conflicts
[F-P04-082] **CRITICAL** — CRDTs for specific data types
[F-P04-083] **CRITICAL** — Conflict detection before resolution
[F-P04-084] **CRITICAL** — Audit conflicts
