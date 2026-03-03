[← Previous Part](04-data-integrity-concurrency.md) | [Full Checklist](../checklist.md) | [Next Part →](06-php-language-type-safety.md)

# Part V — Financial & Monetary Correctness

**8 sections · 62 checks**

- [6. Money, Arithmetic & Precision](#6-money-arithmetic-precision)
- [134. Multi-Currency Translation (IAS 21)](#134-multi-currency-translation-ias-21)
- [135. Rounding Policy Registry](#135-rounding-policy-registry)
- [136. Reconciliation Algorithm Patterns](#136-reconciliation-algorithm-patterns)
- [137. Financial Period Close Safety](#137-financial-period-close-safety)
- [138. Ledger Immutability Enforcement](#138-ledger-immutability-enforcement)
- [139. Inter-Company & Elimination Entries](#139-inter-company-elimination-entries)
- [140. Regulatory Compliance Checks (SOX / IFRS)](#140-regulatory-compliance-checks-sox-ifrs)

---

## 6. Money, Arithmetic & Precision

### Never Use Floats for Money

- [ ] **No `float`, `double`, `decimal` PHP types for monetary arithmetic** — IEEE 754 floats cannot represent 0.1 exactly. Use `brick/math` `BigDecimal`.
- [ ] **No `bcadd()`, `bcsub()`, `bcmul()`, `bcdiv()`, `bccomp()`** — These are legacy PHP extensions with string-based APIs, no objects, poor error handling, and no type safety. Use `brick/math` instead.

```php
// DANGEROUS — float arithmetic
$total = 0.1 + 0.2;          // 0.30000000000000004
$price = 19.99 * 100;        // 1998.9999999999998

// DANGEROUS — bcmath (legacy, stringly-typed, no rounding mode enum)
$sum = bcadd('100.50', '200.75', 2);
$cmp = bccomp('100', '100.00', 2);

// SAFE — brick/math
use Brick\Math\BigDecimal;
use Brick\Math\RoundingMode;

$sum = BigDecimal::of('100.50')->plus('200.75');
$div = BigDecimal::of('100')->dividedBy('3', 4, RoundingMode::HalfUp);
$cmp = BigDecimal::of('100')->isEqualTo('100.00'); // true
```

- [ ] **Use PascalCase `RoundingMode` enum cases** — `RoundingMode::HalfUp`, `RoundingMode::Down`. The SCREAMING_CASE constants (`HALF_UP`, `DOWN`) are deprecated.

### Money Storage

- [ ] **Store money as strings, not database `DECIMAL`** — A `DECIMAL(19,4)` column silently rounds on insert. Store as VARCHAR with explicit scale to preserve exact values.
- [ ] **Use 3-column pattern for money** — `amount` (VARCHAR), `amount_scale` (TINYINT), `amount_currency` (VARCHAR/CHAR). Cast via a Money value object.
- [ ] **Never store money as cents/integers** — This fails for currencies with 3 decimal places (KWD, BHD) or no decimal places (JPY).

### Frontend Money

- [ ] **No `parseFloat()`, `Number()`, or native JS arithmetic on monetary values** — Use `decimal.js-light`, `big.js`, or `dinero.js`.
- [ ] **Format money server-side or with a dedicated formatter** — `Intl.NumberFormat` for display only, never for calculation.

### Cross-Currency Operations

- [ ] **Never sum amounts in different currencies** — Translate to a common (functional) currency first, then sum.
- [ ] **Store the exchange rate used** — For audit trail and recalculation.

---


## 134. Multi-Currency Translation (IAS 21)

- [ ] **Functional currency is the measurement base** — All journal lines carry `functional_amount` in the functional currency.

```php
// Every journal line stores both:
$line->amount           // Transaction currency
$line->amount_currency  // e.g., GHS
$line->functional_amount          // Functional currency (NGN)
$line->functional_amount_currency // Always NGN
```

- [ ] **Spot rate at transaction date** — Use the exchange rate on the date of the transaction, not today's rate.
- [ ] **Monetary items revalued at closing rate** — Bank balances, receivables, payables revalued at period-end.
- [ ] **Non-monetary items at historical rate** — Fixed assets, equity stay at the rate when originally recorded.
- [ ] **Exchange differences to P&L or OCI** — Trading differences to P&L; translation differences to Other Comprehensive Income.
- [ ] **Rate source is auditable** — Store the `exchange_rate_id` used, not just the rate value.
- [ ] **Average rates for period aggregations** — Revenue/expense at average rate over the period.
- [ ] **Inter-company translations** — Eliminate inter-company balances before translating.

---


## 135. Rounding Policy Registry

- [ ] **Define rounding rules per currency** — NGN rounds to 2 decimal places, BTC to 8, JPY to 0.

```php
enum Currency: string
{
    case NGN = 'NGN';
    case BTC = 'BTC';
    case JPY = 'JPY';

    public function scale(): int
    {
        return match ($this) {
            self::NGN => 2,
            self::BTC => 8,
            self::JPY => 0,
        };
    }
}
```

- [ ] **Rounding mode is explicit** — `RoundingMode::HalfUp` for financial, `RoundingMode::Down` for tax truncation.
- [ ] **Never round intermediate calculations** — Only round the final result.
- [ ] **Rounding differences are booked** — If debits and credits don't balance after rounding, book a 1-cent adjustment to a rounding account.
- [ ] **Allocation uses largest-remainder method** — When splitting amounts (e.g., tax allocation), distribute the remainder to avoid rounding drift.

```php
function allocate(BigDecimal $total, array $ratios): array
{
    $allocated = [];
    $sum = BigDecimal::zero();
    foreach ($ratios as $i => $ratio) {
        $share = $total->multipliedBy($ratio)->toScale(2, RoundingMode::Down);
        $allocated[$i] = $share;
        $sum = $sum->plus($share);
    }
    // Assign remainder to last entry
    $allocated[array_key_last($allocated)] = $allocated[array_key_last($allocated)]->plus($total->minus($sum));
    return $allocated;
}
```

- [ ] **Test rounding edge cases** — 0.01 split 3 ways, 1.00 split 7 ways, 0.00 amounts.

---


## 136. Reconciliation Algorithm Patterns

- [ ] **Three-way reconciliation** — System balance vs bank statement vs expected balance.
- [ ] **Matching rules are configurable** — Match by amount, reference, date range, or combination.
- [ ] **Tolerance thresholds** — Allow small differences (e.g., ±0.01 NGN) as auto-matched.
- [ ] **Unmatched items flagged for review** — Never silently discard unmatched entries.
- [ ] **One-to-many and many-to-one matching** — One bank entry can match multiple system entries (and vice versa).
- [ ] **Reconciliation state machine** — `Unmatched → Suggested → Confirmed → Disputed`.
- [ ] **Audit trail per match** — Who confirmed, when, and what the suggested alternatives were.
- [ ] **Delta reconciliation** — Only process new entries since the last reconciliation run, not the full history.
- [ ] **Break analysis** — When a reconciliation breaks, the system should show exactly where and why.

---


## 137. Financial Period Close Safety

- [ ] **Period lock prevents new postings** — After close, no journal entries can be posted to the period.

```php
trait EnforcesPeriodLock
{
    protected function assertPeriodOpen(CarbonImmutable $date): void
    {
        $period = AccountingPeriod::forDate($date);
        if ($period?->is_locked) {
            throw new PeriodLockedException("Period {$period->name} is closed");
        }
    }
}
```

- [ ] **Close is a multi-step process** — Checklist: all entries posted, all reconciliations complete, all accruals booked, all revaluations run.
- [ ] **Reopen requires elevated permission** — `period.reopen` permission, audit-logged, with a reason.
- [ ] **Closing entries are auto-generated** — Revenue/expense accounts closed to retained earnings.
- [ ] **Trial balance must balance before close** — Assert `SUM(debit) = SUM(credit)` for the period.
- [ ] **Comparative period data is frozen** — Once closed, prior period data is immutable for comparative reporting.
- [ ] **Period close is idempotent** — Running close twice produces the same result.

---


## 138. Ledger Immutability Enforcement

- [ ] **Posted journal entries are never updated** — Corrections use reversing entries.
- [ ] **`SoftDeletes` on journal entries — but deletes are forbidden post-posting** — Only draft/pending entries can be deleted.

```php
public function delete(): void
{
    if ($this->status->isTerminal()) {
        throw new ImmutableRecordException('Cannot delete a posted journal entry');
    }
    parent::delete();
}
```

- [ ] **No raw `UPDATE` on journal lines** — Eloquent model events enforce immutability checks.
- [ ] **Reversals create new entries** — A reversal is a new journal entry with opposite debits/credits, referencing the original.
- [ ] **Void marks as void, doesn't delete** — `VoidedAt` timestamp + `voided_by` + reversal entry.
- [ ] **Hash chain for tamper detection** — Each journal entry stores a hash of its data + previous entry's hash.
- [ ] **Read-only database user for reports** — Reporting queries run with a user that has only SELECT privilege.

---


## 139. Inter-Company & Elimination Entries

- [ ] **Inter-company accounts are clearly identified** — Separate chart of accounts range (e.g., 200000-209999).
- [ ] **Reciprocal entries must balance** — Company A's receivable from B = Company B's payable to A.
- [ ] **Elimination entries are auto-generated** — During consolidation, eliminate inter-company balances.
- [ ] **Transfer pricing is documented** — Inter-company transfers use market rates or documented transfer pricing policy.
- [ ] **Audit trail for inter-company** — Each inter-company entry references both entities and the underlying transaction.
- [ ] **Consolidation adjustments are reversible** — Elimination entries can be reversed if the consolidation is reopened.

---


## 140. Regulatory Compliance Checks (SOX / IFRS)

- [ ] **Segregation of Duties (SoD)** — Creator cannot approve their own entries.

```php
public function approve(User $approver, JournalEntry $entry): void
{
    if ($entry->created_by === $approver->id) {
        throw new SoDViolationException('Cannot approve your own journal entry');
    }
}
```

- [ ] **Dual approval for material transactions** — Above a threshold, require two independent approvals.
- [ ] **Audit trail is tamper-evident** — Activity log records are append-only with integrity checks.
- [ ] **Retention policies enforced** — Financial records retained for 7+ years (varies by jurisdiction).
- [ ] **Access reviews quarterly** — Who has access to what? Review and revoke stale permissions.
- [ ] **Change management logged** — Schema changes, permission changes, and config changes are audit-logged.
- [ ] **IFRS 9 — Expected Credit Loss** — Receivables carry an ECL provision, updated per period.
- [ ] **IFRS 15 — Revenue Recognition** — Revenue recognized when performance obligations are satisfied, not when cash is received.
- [ ] **Management override controls** — Even super-admins can't bypass SoD without a documented, time-boxed exception.

---


---

[← Previous Part](04-data-integrity-concurrency.md) | [Full Checklist](../checklist.md) | [Next Part →](06-php-language-type-safety.md)
