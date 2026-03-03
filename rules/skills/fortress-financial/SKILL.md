---
description: "Laravel Fortress Part 5 — Financial & Monetary Correctness. 8 sections, 62 checks covering Money handling, multi-currency, rounding, reconciliation, ledger integrity."
---

# Fortress: Financial & Monetary Correctness

> Part V of The Laravel Fortress — 8 sections · 62 checks
> https://github.com/chuxolab/laravel-fortress/blob/main/parts/05-financial-monetary-correctness.md

When writing or reviewing code, enforce these rules.
Check `.fortress.yml` in the project root for overrides.

## Project Awareness

Before enforcing rules, read `composer.json` and `package.json` to detect the project's PHP version,
Laravel version, and installed packages. Skip rules whose conditions don't match the detected stack.

## Rules

### Never Use Floats for Money

[F-P05-001] **CRITICAL** — No `float`, `double`, `decimal` PHP types for monetary arithmetic
[F-P05-002] **CRITICAL** — No `bcadd()`, `bcsub()`, `bcmul()`, `bcdiv()`, `bccomp()`
[F-P05-003] **CRITICAL** — Use PascalCase `RoundingMode` enum cases

### Money Storage

[F-P05-004] **CRITICAL** — Store money as strings, not database `DECIMAL`
[F-P05-005] **CRITICAL** — Use 3-column pattern for money
[F-P05-006] **CRITICAL** — Never store money as cents/integers

### Frontend Money

[F-P05-007] **CRITICAL** — No `parseFloat()`, `Number()`, or native JS arithmetic on monetary values
[F-P05-008] **CRITICAL** — Format money server-side or with a dedicated formatter

### Cross-Currency Operations

[F-P05-009] **CRITICAL** — Never sum amounts in different currencies
[F-P05-010] **CRITICAL** — Store the exchange rate used

### Multi-Currency Translation (IAS 21)

[F-P05-011] **CRITICAL** — Functional currency is the measurement base
[F-P05-012] **CRITICAL** — Spot rate at transaction date
[F-P05-013] **CRITICAL** — Monetary items revalued at closing rate
[F-P05-014] **CRITICAL** — Non-monetary items at historical rate
[F-P05-015] **CRITICAL** — Exchange differences to P&L or OCI
[F-P05-016] **CRITICAL** — Rate source is auditable
[F-P05-017] **CRITICAL** — Average rates for period aggregations
[F-P05-018] **CRITICAL** — Inter-company translations

### Rounding Policy Registry

[F-P05-019] **CRITICAL** — Define rounding rules per currency
[F-P05-020] **CRITICAL** — Rounding mode is explicit
[F-P05-021] **CRITICAL** — Never round intermediate calculations
[F-P05-022] **CRITICAL** — Rounding differences are booked
[F-P05-023] **CRITICAL** — Allocation uses largest-remainder method
[F-P05-024] **CRITICAL** — Test rounding edge cases

### Reconciliation Algorithm Patterns

[F-P05-025] **CRITICAL** — Three-way reconciliation
[F-P05-026] **CRITICAL** — Matching rules are configurable
[F-P05-027] **CRITICAL** — Tolerance thresholds
[F-P05-028] **CRITICAL** — Unmatched items flagged for review
[F-P05-029] **CRITICAL** — One-to-many and many-to-one matching
[F-P05-030] **CRITICAL** — Reconciliation state machine
[F-P05-031] **CRITICAL** — Audit trail per match
[F-P05-032] **CRITICAL** — Delta reconciliation
[F-P05-033] **CRITICAL** — Break analysis

### Financial Period Close Safety

[F-P05-034] **CRITICAL** — Period lock prevents new postings
[F-P05-035] **CRITICAL** — Close is a multi-step process
[F-P05-036] **CRITICAL** — Reopen requires elevated permission
[F-P05-037] **CRITICAL** — Closing entries are auto-generated
[F-P05-038] **CRITICAL** — Trial balance must balance before close
[F-P05-039] **CRITICAL** — Comparative period data is frozen
[F-P05-040] **CRITICAL** — Period close is idempotent

### Ledger Immutability Enforcement

[F-P05-041] **CRITICAL** — Posted journal entries are never updated
[F-P05-042] **CRITICAL** — `SoftDeletes` on journal entries — but deletes are forbidden post-posting
[F-P05-043] **CRITICAL** — No raw `UPDATE` on journal lines
[F-P05-044] **CRITICAL** — Reversals create new entries
[F-P05-045] **CRITICAL** — Void marks as void, doesn't delete
[F-P05-046] **CRITICAL** — Hash chain for tamper detection
[F-P05-047] **CRITICAL** — Read-only database user for reports

### Inter-Company & Elimination Entries

[F-P05-048] **CRITICAL** — Inter-company accounts are clearly identified
[F-P05-049] **CRITICAL** — Reciprocal entries must balance
[F-P05-050] **CRITICAL** — Elimination entries are auto-generated
[F-P05-051] **CRITICAL** — Transfer pricing is documented
[F-P05-052] **CRITICAL** — Audit trail for inter-company
[F-P05-053] **CRITICAL** — Consolidation adjustments are reversible

### Regulatory Compliance Checks (SOX / IFRS)

[F-P05-054] **CRITICAL** — Segregation of Duties (SoD)
[F-P05-055] **CRITICAL** — Dual approval for material transactions
[F-P05-056] **CRITICAL** — Audit trail is tamper-evident
[F-P05-057] **CRITICAL** — Retention policies enforced
[F-P05-058] **CRITICAL** — Access reviews quarterly
[F-P05-059] **CRITICAL** — Change management logged
[F-P05-060] **CRITICAL** — IFRS 9 — Expected Credit Loss
[F-P05-061] **CRITICAL** — IFRS 15 — Revenue Recognition
[F-P05-062] **CRITICAL** — Management override controls
