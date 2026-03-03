[← Previous Part](06-php-language-type-safety.md) | [Full Checklist](../checklist.md) | [Next Part →](08-laravel-framework-mastery.md)

# Part VII — Clean Code & Software Design

**16 sections · 128 checks**

- [9. Clean Code & SOLID Principles](#9-clean-code-solid-principles)
- [57. Code Readability & Cognitive Complexity](#57-code-readability-cognitive-complexity)
- [72. Interface & Contract Design](#72-interface-contract-design)
- [73. Trait Hygiene](#73-trait-hygiene)
- [94. Domain Event Design](#94-domain-event-design)
- [95. Value Object Contract Enforcement](#95-value-object-contract-enforcement)
- [149. CQRS Pattern Implementation](#149-cqrs-pattern-implementation)
- [150. Repository Pattern (When Appropriate)](#150-repository-pattern-when-appropriate)
- [151. Bounded Context & Module Boundaries](#151-bounded-context-module-boundaries)
- [152. Pipeline Pattern](#152-pipeline-pattern)
- [153. Specification Pattern for Business Rules](#153-specification-pattern-for-business-rules)
- [154. Null Object Pattern](#154-null-object-pattern)
- [155. Strategy Pattern for Variant Logic](#155-strategy-pattern-for-variant-logic)
- [156. Builder Pattern for Complex Construction](#156-builder-pattern-for-complex-construction)
- [157. Immutability as a Default](#157-immutability-as-a-default)
- [158. Domain Primitives & Micro-Types](#158-domain-primitives-micro-types)

---

## 9. Clean Code & SOLID Principles

### Single Responsibility

- [ ] **Controllers only dispatch to actions/services** — No business logic in controllers. 5-10 lines per method.

```php
// CLEAN — controller dispatches to action
public function store(StoreOrderRequest $request): RedirectResponse
{
    $order = app(CreateOrder::class)->handle($request->validated(), auth()->user());

    return redirect()->route('orders.show', $order);
}

// DIRTY — controller does everything
public function store(Request $request): RedirectResponse
{
    $validated = $request->validate([...]);
    $order = Order::create($validated);
    $order->items()->createMany($validated['items']);
    $order->calculateTotals();
    Mail::send(new OrderConfirmation($order));
    event(new OrderCreated($order));
    return redirect()->route('orders.show', $order);
}
```

- [ ] **One action class per business operation** — `app/Actions/CreateOrder.php`, `app/Actions/ApproveOrder.php`, etc.
- [ ] **Query objects for complex reads** — `app/Queries/Reports/SalesReportQuery.php`. Keep models thin.

### Early Returns & Guard Clauses

- [ ] **Validate preconditions at the top, return/throw immediately** — Keep the happy path at minimum nesting depth.

```php
// CLEAN — guard clauses
public function handle(Order $order, string $userId): void
{
    if ($order->isLocked()) {
        throw new OrderLockedException($order->id);
    }

    if ($order->created_by === $userId) {
        throw new SegregationOfDutiesException('Creator cannot approve');
    }

    // Happy path at top level
    $order->approve($userId);
}

// DIRTY — deep nesting
public function handle(Order $order, string $userId): void
{
    if (! $order->isLocked()) {
        if ($order->created_by !== $userId) {
            $order->approve($userId);
        } else {
            throw new SegregationOfDutiesException('Creator cannot approve');
        }
    } else {
        throw new OrderLockedException($order->id);
    }
}
```

### Value Objects & DTOs

- [ ] **Use value objects for domain concepts** — `Money`, `AccountCode`, `Period`, `EmailAddress`. Not raw strings/arrays.
- [ ] **Use typed DTOs for data transfer** — `spatie/laravel-data` or plain `readonly class`. Not associative arrays.
- [ ] **Value objects are immutable** — Use `readonly` properties. Operations return new instances.
- [ ] **Value objects validate on construction** — Invalid state should be impossible.

```php
readonly class AccountCode implements \Stringable
{
    public function __construct(
        public string $value,
    ) {
        if (! preg_match('/^\d{6}$/', $value)) {
            throw new \InvalidArgumentException("Invalid account code: {$value}");
        }
    }

    public function __toString(): string
    {
        return $this->value;
    }
}
```

### Dependency Injection

- [ ] **Inject interfaces, not concrete classes** — Code to contracts.
- [ ] **Constructor injection over method injection** — Except for optional parameters.
- [ ] **Never call `app()` in business logic** — Resolve via constructor. `app()` is acceptable in tests, commands, and service providers.
- [ ] **No static facades in domain logic** — Prefer injected dependencies that can be mocked.

### Naming

- [ ] **Classes: PascalCase, descriptive nouns** — `JournalEntryApprovalService`, not `JEApprSvc`.
- [ ] **Methods: camelCase, verb-first** — `calculateTotal()`, `isApproved()`, `canTransitionTo()`.
- [ ] **Variables: camelCase, descriptive** — `$isEligibleForDiscount`, not `$flag`.
- [ ] **Boolean methods: `is`, `has`, `can`, `should` prefixes** — `$user->isActive()`, `$order->canBeRefunded()`.
- [ ] **Constants and enum cases: PascalCase** (PHP convention) — `Status::Pending`, not `STATUS_PENDING`.
- [ ] **No abbreviations** — `$transaction` not `$txn`, `$repository` not `$repo` (unless universally understood like `$id`, `$url`).

### Class Organization

- [ ] **Consistent element order in classes**:
  1. Traits (use statements)
  2. Constants
  3. Properties
  4. Constructor
  5. Static factory methods
  6. Public methods
  7. Protected methods
  8. Private methods
  9. Magic methods

- [ ] **Resource controller method order**: `index → create → store → show → edit → update → destroy`.
- [ ] **Model method order**: Traits → Properties → `casts()` → `boot()` → Relationships → Scopes → Accessors → Custom methods.

### No Premature Abstraction

- [ ] **Don't abstract for one use case** — Three similar code blocks is fine. Extract only when a real pattern emerges.
- [ ] **Don't create helpers/utilities for single-use operations** — Inline is clearer.
- [ ] **Don't add configurability that isn't needed** — Feature flags and config keys for things that will never change add complexity.

---


## 57. Code Readability & Cognitive Complexity

### Method Length

- [ ] **Methods under 20 lines (soft limit)** — If it's longer, extract private methods.
- [ ] **One level of abstraction per method** — A method should either coordinate high-level steps or do low-level work, not both.

### Nesting Depth

- [ ] **Maximum 2 levels of nesting** — If you need a third level, extract a method or use early returns.

```php
// BAD — 4 levels deep
foreach ($orders as $order) {
    if ($order->isActive()) {
        foreach ($order->items as $item) {
            if ($item->needsShipping()) {
                $this->ship($item); // 4 levels deep
            }
        }
    }
}

// GOOD — extracted and flat
foreach ($orders as $order) {
    $this->processOrder($order);
}

private function processOrder(Order $order): void
{
    if (! $order->isActive()) {
        return;
    }

    $order->items
        ->filter(fn (Item $item) => $item->needsShipping())
        ->each(fn (Item $item) => $this->ship($item));
}
```

### Boolean Parameters

- [ ] **No boolean parameters** — Use named arguments or separate methods.

```php
// BAD — what does true mean?
$this->sendEmail($user, true, false);

// GOOD — named arguments
$this->sendEmail($user, withAttachment: true, isUrgent: false);

// BETTER — separate methods
$this->sendUrgentEmail($user);
$this->sendEmailWithAttachment($user);
```

### Comments

- [ ] **Code should be self-documenting** — If you need a comment explaining what, rename the variable or method.
- [ ] **Comments explain WHY, not WHAT** — `// Calculate total` is noise. `// Apply 15% VAT per HMRC regulation` is value.
- [ ] **PHPDoc for complex signatures** — Array shapes, generics, template types. Not for `function getName(): string`.
- [ ] **No commented-out code** — Delete it. Git has the history.
- [ ] **No TODO without a ticket** — `// TODO: fix this` is a lie. Create an issue or fix it now.

### Naming Precision

- [ ] **Avoid generic names** — `$data`, `$result`, `$temp`, `$item`, `$stuff` tell you nothing.
- [ ] **Boolean variables read as assertions** — `$isActive`, `$hasPermission`, `$canDelete`, `$shouldNotify`.
- [ ] **Collection variables are plural** — `$users` not `$user` for a collection.
- [ ] **Methods that return booleans start with `is`, `has`, `can`, `should`**.
- [ ] **Methods that transform data describe the output** — `toArray()`, `asJson()`, `formatted()`.

---


## 72. Interface & Contract Design

- [ ] **Interfaces for all external boundaries** — Gateways, payment providers, email services, rate checkers.
- [ ] **Interfaces in `app/Contracts/`** — Separate from implementations.
- [ ] **One method per interface (where practical)** — Interface Segregation Principle.
- [ ] **Return types on interface methods** — `public function find(string $id): ?User`.
- [ ] **Fake implementations for testing** — `FakePaymentGateway implements PaymentGatewayInterface`.
- [ ] **Bind in ServiceProvider** — `$this->app->bind(Interface::class, Implementation::class)`.
- [ ] **Document interface contracts** — PHPDoc on the interface, not the implementation.

---


## 73. Trait Hygiene

- [ ] **Traits are horizontal reuse, not inheritance substitutes** — Don't make God traits with 20 methods.
- [ ] **Traits declare their dependencies** — Use abstract methods or type hints to declare what the using class must provide.
- [ ] **No property conflicts** — Two traits defining the same property is a fatal error. Namespace trait properties.
- [ ] **Small, focused traits** — `SoftDeletes`, `HasUuids`, `LogsActivity` — one concern per trait.
- [ ] **No business logic in traits** — Traits provide infrastructure. Actions contain business logic.
- [ ] **Document trait requirements** — PHPDoc `@mixin` or `@method` annotations for IDE support.
- [ ] **Test traits independently** — Use anonymous classes or dedicated test-only classes.

---


## 94. Domain Event Design

- [ ] **Events are past tense** — `OrderCreated`, `PaymentReceived`, `EntryPosted`. Not `CreateOrder`.
- [ ] **Events are immutable** — `readonly` properties. No setters.
- [ ] **Events carry all necessary data** — Don't rely on DB lookups during event handling.
- [ ] **Events are serializable** — No closures, connections, or file handles.
- [ ] **One event per state change** — `OrderApproved`, `OrderShipped`, `OrderDelivered`. Not `OrderUpdated`.
- [ ] **Event names are domain-specific** — `InvoicePaid` not `ModelUpdated`.
- [ ] **Metadata separate from payload** — User ID, timestamp, request ID in metadata. Business data in payload.

---


## 95. Value Object Contract Enforcement

- [ ] **All VOs implement a common interface** — `ValueObject extends \Stringable` with `equals()` and `__toString()`.
- [ ] **Validate on construction** — Invalid VOs cannot exist. Throw in constructor.
- [ ] **Immutable** — `readonly` properties. Operations return new instances.
- [ ] **No identity** — Two VOs with the same value are equal. Use `equals()`, not `===`.
- [ ] **`__toString()` for serialization** — VOs can be cast to string for storage.
- [ ] **Architecture test enforcement** — Arch test verifies all VOs in `app/ValueObjects/` implement the interface.
- [ ] **Factory methods for common creation patterns** — `Money::of('100.00', 'NGN')`, `Period::fromString('2026-03')`.

---


## 149. CQRS Pattern Implementation

- [ ] **Separate read and write models** — Write models (Aggregates, Actions) are distinct from read models (Queries, Projections).

```
app/
├── Actions/         # Write side — commands
│   └── Ledger/
│       ├── CreateJournalEntry.php
│       └── ApproveJournalEntry.php
├── Queries/         # Read side — queries
│   └── Reports/
│       ├── TrialBalanceQuery.php
│       └── LedgerQuery.php
└── Projectors/      # Event → Read model
    └── LedgerProjector.php
```

- [ ] **Commands don't return data** — A command changes state and returns void (or the created resource ID).
- [ ] **Queries don't change state** — A query returns data and has no side effects.
- [ ] **Read models are denormalized** — Optimized for read performance, not normalization.
- [ ] **Separate database connections for reads** — Read models can use a replica.
- [ ] **Eventually consistent reads are acceptable** — The projection may lag behind the write by milliseconds.
- [ ] **Don't over-apply** — CQRS adds complexity. Use it for domains with complex reads (reports) and complex writes (event-sourced aggregates), not CRUD screens.

---


## 150. Repository Pattern (When Appropriate)

- [ ] **Eloquent IS the repository** — For most Laravel apps, wrapping Eloquent in a repository adds no value.

```php
// UNNECESSARY — Eloquent already provides this
class UserRepository
{
    public function findById(string $id): User
    {
        return User::findOrFail($id);
    }
}

// JUST USE ELOQUENT
User::findOrFail($id);
```

- [ ] **Use repositories when you need to swap implementations** — Different data sources (API, cache, database) behind one interface.
- [ ] **Use Query objects instead** — For complex read logic, a dedicated query class is better than a repository method.

```php
// Better than UserRepository::getActiveUsersWithRecentOrders()
class ActiveUsersWithRecentOrdersQuery
{
    public function execute(): Collection
    {
        return User::query()
            ->where('is_active', true)
            ->whereHas('orders', fn ($q) => $q->recent())
            ->with('orders')
            ->get();
    }
}
```

- [ ] **Never wrap Eloquent just for testability** — Eloquent can be tested with `RefreshDatabase` or factories.
- [ ] **If you use repositories, they return domain objects** — Not Eloquent models. (This is rare in Laravel.)

---


## 151. Bounded Context & Module Boundaries

- [ ] **Each module owns its models, actions, and routes** — No cross-module Eloquent queries.

```
app/
├── Ledger/          # Bounded context
│   ├── Models/
│   ├── Actions/
│   ├── Events/
│   └── routes.php
├── Reconciliation/  # Bounded context
│   ├── Models/
│   ├── Actions/
│   └── routes.php
```

- [ ] **Cross-context communication via events or service interfaces** — Module A dispatches an event; Module B reacts.
- [ ] **Shared kernel is minimal** — Common enums, value objects, and base classes. Not business logic.
- [ ] **No cross-context database JOINs** — If Module A needs Module B's data, B exposes a query or API.
- [ ] **Context map documents relationships** — Upstream/downstream, conformist, anti-corruption layer.
- [ ] **Naming reflects the context** — `Ledger\Account` and `Banking\Account` are different models, not shared.
- [ ] **Avoid monolithic route files** — Each context registers its own routes.

---


## 152. Pipeline Pattern

- [ ] **Use Laravel Pipelines for sequential processing** — Each stage transforms or validates the payload.

```php
use Illuminate\Pipeline\Pipeline;

$result = app(Pipeline::class)
    ->send($journalEntry)
    ->through([
        ValidateBalanced::class,
        EnforcePeriodLock::class,
        AssignReference::class,
        CalculateFunctionalAmounts::class,
    ])
    ->thenReturn();
```

- [ ] **Each pipe has a single responsibility** — One class, one transformation.
- [ ] **Pipes are reusable** — The same validation pipe can appear in different pipelines.
- [ ] **Pipes throw exceptions on failure** — Don't return error codes. Throw typed exceptions.
- [ ] **Order matters** — Validate before transform. Authorize before process.
- [ ] **Test each pipe in isolation** — Unit test the pipe class, integration test the full pipeline.
- [ ] **Don't nest pipelines** — Keep the pipeline flat. If a pipe needs sub-steps, it's an Action, not a pipe.

---


## 153. Specification Pattern for Business Rules

- [ ] **Encapsulate complex business predicates** — Each specification answers one question: "Does this entity satisfy rule X?"

```php
interface Specification
{
    public function isSatisfiedBy(mixed $candidate): bool;
}

class IsEligibleForAutoApproval implements Specification
{
    public function isSatisfiedBy(mixed $entry): bool
    {
        return $entry->amount->isLessThan(Money::of('100000', 'NGN'))
            && $entry->line_count <= 10
            && $entry->created_by_role === 'senior_accountant';
    }
}
```

- [ ] **Compose specifications** — `AndSpecification`, `OrSpecification`, `NotSpecification`.
- [ ] **Use for dynamic filtering** — Build query scopes from specifications.
- [ ] **Specifications are testable** — Unit test each specification with edge case inputs.
- [ ] **Document the business rule** — The specification name IS the documentation.
- [ ] **Don't over-engineer for simple boolean checks** — A single `if` statement doesn't need a specification class.

---


## 154. Null Object Pattern

- [ ] **Replace null checks with a Null Object** — An object that implements the interface but does nothing.

```php
interface TaxCalculator
{
    public function calculate(BigDecimal $amount): BigDecimal;
}

class NullTaxCalculator implements TaxCalculator
{
    public function calculate(BigDecimal $amount): BigDecimal
    {
        return BigDecimal::zero();
    }
}

// Usage — no null check needed
$tax = $this->taxCalculator->calculate($subtotal);
```

- [ ] **Use for optional dependencies** — Instead of `?TaxCalculator`, inject `NullTaxCalculator` as default.
- [ ] **Null Objects are singletons** — They're stateless, so reuse the same instance.
- [ ] **Don't use when null has meaning** — If "no result" is semantically different from "empty result," use `null`.
- [ ] **Named constructors clarify intent** — `TaxCalculator::none()` returns the null implementation.

---


## 155. Strategy Pattern for Variant Logic

- [ ] **Replace conditionals with strategies** — When behavior varies by type, context, or configuration.

```php
interface ExchangeRateProvider
{
    public function getRate(string $base, string $quote, CarbonImmutable $date): BigDecimal;
}

class CbxRateProvider implements ExchangeRateProvider { /* ... */ }
class ManualRateProvider implements ExchangeRateProvider { /* ... */ }
class FallbackRateProvider implements ExchangeRateProvider { /* ... */ }

// Bind via service container
$this->app->bind(ExchangeRateProvider::class, fn () =>
    match (config('rates.provider')) {
        'cbx' => new CbxRateProvider(),
        'manual' => new ManualRateProvider(),
        default => new FallbackRateProvider(),
    }
);
```

- [ ] **Inject strategies, don't construct inline** — Use DI, not `new` inside business logic.
- [ ] **Strategies are interchangeable at runtime** — Configuration or context determines which strategy runs.
- [ ] **Each strategy is independently testable** — Mock or stub the interface, test each implementation.
- [ ] **Prefer over `match`/`switch` when branches have complex logic** — A 3-line `match` is fine; a 50-line `match` needs strategies.

---


## 156. Builder Pattern for Complex Construction

- [ ] **Use builders for objects with many optional parameters** — Avoids telescoping constructors.

```php
$query = TrialBalanceQuery::builder()
    ->forPeriod($period)
    ->withCurrency('NGN')
    ->includeSubLedger()
    ->excludeZeroBalances()
    ->build();
```

- [ ] **Builders enforce required parameters** — `build()` throws if required fields are missing.
- [ ] **Immutable builders** — Each method returns a new builder instance.
- [ ] **Use for query construction** — Report queries with many optional filters.
- [ ] **Use for notification construction** — Complex notifications with optional channels, recipients, and data.
- [ ] **Don't use when a constructor is clear** — A 3-parameter constructor doesn't need a builder.

---


## 157. Immutability as a Default

- [ ] **Default to immutable, opt into mutability** — `readonly` properties, `CarbonImmutable`, immutable collections.
- [ ] **Value Objects are always immutable** — `Money`, `CryptoPair`, `TransactionReference`.
- [ ] **Events are always immutable** — Once recorded, event data never changes.
- [ ] **Use `CarbonImmutable` over `Carbon`** — `immutable_datetime` in model casts.

```php
protected function casts(): array
{
    return [
        'posted_at' => 'immutable_datetime',
        'created_at' => 'immutable_datetime',
    ];
}
```

- [ ] **Collections: `->toImmutable()` for shared data** — Prevents accidental mutation by other code.
- [ ] **Immutable DTOs for cross-boundary data** — `readonly class CreateJournalEntryData { ... }`.
- [ ] **Mutation returns new instances** — `$newMoney = $money->plus($other)`, not `$money->add($other)` (in-place).
- [ ] **Mutable state is explicitly scoped** — Only inside Actions, only within a transaction.

---


## 158. Domain Primitives & Micro-Types

- [ ] **Wrap primitive values in domain-specific types** — Prevents primitive obsession.

```php
readonly class AccountCode
{
    public function __construct(public string $value)
    {
        if (!preg_match('/^\d{6}$/', $value)) {
            throw new InvalidArgumentException("Invalid account code: {$value}");
        }
    }

    public function __toString(): string
    {
        return $this->value;
    }
}

// Usage — can't accidentally pass a random string
function postToAccount(AccountCode $code, Money $amount): void { }
```

- [ ] **Self-validating on construction** — Invalid values cannot exist.
- [ ] **Type-safe function signatures** — `function transfer(AccountCode $from, AccountCode $to)` not `function transfer(string $from, string $to)`.
- [ ] **Equality by value** — Two `AccountCode('110500')` instances are equal.
- [ ] **Use for: account codes, currency codes, reference numbers, BVN, phone numbers** — Any string/int with validation rules.
- [ ] **Don't micro-type everything** — A user's display name doesn't need a `DisplayName` class.

---


---

[← Previous Part](06-php-language-type-safety.md) | [Full Checklist](../checklist.md) | [Next Part →](08-laravel-framework-mastery.md)
