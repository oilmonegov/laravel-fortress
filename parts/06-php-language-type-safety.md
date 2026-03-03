[← Previous Part](05-financial-monetary-correctness.md) | [Full Checklist](../checklist.md) | [Next Part →](07-clean-code-software-design.md)

# Part VI — PHP Language & Type Safety

**15 sections · 126 checks**

- [7. Modern PHP Syntax (8.3/8.4)](#7-modern-php-syntax-8384)
- [8. Strict Typing & Type Safety](#8-strict-typing-type-safety)
- [30. Regex Safety (ReDoS)](#30-regex-safety-redos)
- [31. Date, Time & Timezone Safety](#31-date-time-timezone-safety)
- [40. PHP Configuration Security](#40-php-configuration-security)
- [63. PHP Deprecation Awareness](#63-php-deprecation-awareness)
- [71. Enum Best Practices (Deep Dive)](#71-enum-best-practices-deep-dive)
- [141. Fiber & Async Patterns](#141-fiber-async-patterns)
- [142. Readonly Classes & Properties Deep Dive](#142-readonly-classes-properties-deep-dive)
- [143. First-Class Callable Syntax](#143-first-class-callable-syntax)
- [144. Modern Array Functions](#144-modern-array-functions)
- [145. Attribute-Based Programming](#145-attribute-based-programming)
- [146. Intersection & DNF Types](#146-intersection-dnf-types)
- [147. Property Hooks (PHP 8.4)](#147-property-hooks-php-84)
- [148. Asymmetric Visibility (PHP 8.4)](#148-asymmetric-visibility-php-84)

---

## 7. Modern PHP Syntax (8.3/8.4)

### Must Use

- [ ] **Constructor property promotion** — Eliminate boilerplate.

```php
// Modern
public function __construct(
    private readonly UserRepository $users,
    private readonly EventDispatcher $events,
) {}

// Legacy — avoid
private UserRepository $users;
private EventDispatcher $events;

public function __construct(UserRepository $users, EventDispatcher $events)
{
    $this->users = $users;
    $this->events = $events;
}
```

- [ ] **`match` over `switch`** — Strict comparison, returns a value, no fall-through bugs.

```php
$label = match ($status) {
    Status::Pending  => 'Awaiting Review',
    Status::Approved => 'Approved',
    Status::Rejected => 'Rejected',
    default          => 'Unknown',
};
```

- [ ] **Backed enums for all magic strings** — Statuses, types, categories, roles, permissions, currencies.
- [ ] **Null-safe operator `?->`** — Replace multi-level null checks.
- [ ] **Arrow functions `fn() =>`** — For short closures (single expression).
- [ ] **Named arguments** — For readability, especially with boolean/optional parameters.
- [ ] **`readonly` properties** — For value objects, DTOs, and anything that shouldn't change after construction.
- [ ] **First-class callable syntax `method(...)`** — Replace `[$this, 'method']` and `Closure::fromCallable()`.
- [ ] **`#[Override]` attribute** — On all overridden methods. Compile-time safety net for refactoring.

### PHP 8.4 Specific

- [ ] **Property hooks** — Use for computed properties and validation-on-set.
- [ ] **Asymmetric visibility** — `public private(set)` replaces many getter methods.
- [ ] **`#[\Deprecated]` attribute** — Mark deprecated methods formally instead of `@deprecated` docblocks.
- [ ] **New array functions** — `array_find()`, `array_find_key()`, `array_any()`, `array_all()` replace verbose loops.
- [ ] **`new` without parentheses chaining** — `new Foo()->method()` instead of `(new Foo())->method()`.

### Avoid Legacy Patterns

- [ ] **No `switch` statements** — Use `match` or polymorphism.
- [ ] **No `array_push($arr, $item)`** — Use `$arr[] = $item`.
- [ ] **No `isset($x) ? $x : $default`** — Use `$x ?? $default`.
- [ ] **No `strpos($str, 'x') !== false`** — Use `str_contains($str, 'x')`.
- [ ] **No `substr($str, 0, 3) === 'foo'`** — Use `str_starts_with($str, 'foo')`.
- [ ] **No `array_key_exists('key', $arr)`** — Use `isset($arr['key'])` or `$arr['key'] ?? null`.
- [ ] **No `call_user_func($callback, $arg)`** — Use `$callback($arg)` or first-class callables.

---


## 8. Strict Typing & Type Safety

### Declarations

- [ ] **`declare(strict_types=1)` in every PHP file** — Without this, PHP silently coerces types. `'5' + 3` returns `8` instead of throwing a TypeError.

```php
<?php

declare(strict_types=1);

// With strict_types=1:
function add(int $a, int $b): int { return $a + $b; }
add('5', '3'); // TypeError: Argument #1 must be int, string given

// Without strict_types=1:
add('5', '3'); // Returns 8 — silent coercion
```

### Return Types

- [ ] **Explicit return types on all methods** — Including `void`, `never`, `self`, `static`.
- [ ] **Use `void` for methods that return nothing** — Don't omit the return type.
- [ ] **Use `never` for methods that always throw or exit** — `function fail(): never { throw new Exception(); }`.

### Type Hints

- [ ] **Type hints on all parameters** — Including closures, arrays, and nullable types.
- [ ] **Use union types `string|int` over `mixed`** — Be as specific as possible.
- [ ] **Use intersection types `Renderable&Countable`** — When an argument must satisfy multiple interfaces.
- [ ] **Avoid `mixed` — it means "I don't know"** — If you truly need it, document why.

### Cast Safety

- [ ] **Model casts use `casts()` method, not `$casts` property** — The method is more flexible and testable.
- [ ] **Use `immutable_datetime` over `datetime`** — Prevents accidental mutation of date objects.
- [ ] **Use `'encrypted'` cast for sensitive attributes** — API keys, secrets, personal data.
- [ ] **Use `'boolean'` cast for flag columns** — Not `'integer'`.
- [ ] **Use `'array'` or `'collection'` cast for JSON columns** — Not raw `json_decode()`.
- [ ] **Use `AsEnumCollection::of(Enum::class)` for JSON arrays of enum values**.

```php
protected function casts(): array
{
    return [
        'status' => OrderStatus::class,
        'metadata' => 'array',
        'amount' => MoneyCast::class,
        'published_at' => 'immutable_datetime',
        'is_active' => 'boolean',
        'settings' => AsArrayObject::class,
        'tags' => AsEnumCollection::of(Tag::class),
    ];
}
```

### PHPDoc for Complex Types

- [ ] **Array shapes for complex arrays** — When arrays have known structure, document it.

```php
/**
 * @param array{
 *     name: string,
 *     email: string,
 *     roles: list<string>,
 *     metadata?: array<string, mixed>,
 * } $data
 *
 * @return array{success: bool, user_id: string}
 */
public function createUser(array $data): array
```

- [ ] **`@template` for generic types** — For reusable classes/methods that work with different types.

---


## 30. Regex Safety (ReDoS)

### Regular Expression Denial of Service

- [ ] **Avoid catastrophic backtracking patterns** — Nested quantifiers like `(a+)+$`, `(a|a)+$`, `(.*a){x}` cause exponential time on crafted input.

```php
// VULNERABLE — exponential backtracking
preg_match('/^(a+)+$/', $userInput);

// SAFE — no nested quantifiers
preg_match('/^a+$/', $userInput);
```

- [ ] **Limit input length before regex matching** — `strlen($input) > 10000 ? abort(422) : preg_match(...)`.
- [ ] **Use possessive quantifiers or atomic groups** — `a++` or `(?>a+)` prevent backtracking.
- [ ] **Prefer `str_contains()`, `str_starts_with()`, `str_ends_with()`** — For simple string checks instead of regex.
- [ ] **Test regex with adversarial inputs** — Try strings like `aaaaaaaaaaaaaaaaaaaab` against patterns with `(a+)+`.

---


## 31. Date, Time & Timezone Safety

### Timezone Handling

- [ ] **Store all timestamps in UTC** — Set `APP_TIMEZONE=UTC` in `.env` and `'timezone' => 'UTC'` in `config/app.php`.
- [ ] **Convert to user's timezone only for display** — Never store local times.
- [ ] **Use `CarbonImmutable` not `Carbon`** — Mutable dates cause subtle bugs when passed between methods.

```php
// DANGEROUS — mutation affects all references
$startDate = Carbon::now();
$endDate = $startDate->addDays(7); // Also mutates $startDate!

// SAFE — immutable returns new instance
$startDate = CarbonImmutable::now();
$endDate = $startDate->addDays(7); // $startDate unchanged
```

- [ ] **Model casts use `immutable_datetime`** — Not `datetime`.

```php
protected function casts(): array
{
    return [
        'published_at' => 'immutable_datetime',
        'expires_at' => 'immutable_datetime',
    ];
}
```

### Date Comparison

- [ ] **Compare dates with Carbon methods, not strings** — `$date->isBefore($other)`, `$date->isAfter($other)`, `$date->isSameDay($other)`.
- [ ] **Beware of timezone-naive comparisons** — `2026-03-03 23:00 UTC` and `2026-03-04 00:00 WAT` are the same moment.
- [ ] **Use `->startOfDay()` / `->endOfDay()` for date-range queries** — Not manual time strings.

### Date Validation

- [ ] **Use `date_format:Y-m-d` not just `date`** — The `date` rule accepts many ambiguous formats.
- [ ] **Validate date ranges** — `'end_date' => 'after_or_equal:start_date'`.
- [ ] **Reject impossible dates** — `2026-02-30` should fail validation. `date_format` handles this.

---


## 40. PHP Configuration Security

### php.ini Hardening

```ini
; Hide PHP version
expose_php = Off

; Disable dangerous functions
disable_functions = exec,passthru,shell_exec,system,proc_open,popen,
    curl_exec,curl_multi_exec,parse_ini_file,show_source

; Error handling
display_errors = Off
display_startup_errors = Off
log_errors = On
error_reporting = E_ALL

; Session security
session.cookie_httponly = 1
session.cookie_secure = 1
session.use_strict_mode = 1
session.cookie_samesite = Strict

; Upload limits
upload_max_filesize = 10M
post_max_size = 12M
max_file_uploads = 10

; Memory and execution limits
memory_limit = 256M
max_execution_time = 30
max_input_time = 60
max_input_vars = 1000

; Disable remote file inclusion
allow_url_fopen = Off   ; (if your app doesn't need HTTP file access)
allow_url_include = Off  ; ALWAYS off
```

- [ ] **`allow_url_include = Off`** — Prevents remote file inclusion (RFI). Always off.
- [ ] **`disable_functions`** — Disable shell execution functions if not needed.
- [ ] **`expose_php = Off`** — Hides `X-Powered-By: PHP/8.4` header.
- [ ] **`session.use_strict_mode = 1`** — Rejects uninitialized session IDs.
- [ ] **`open_basedir`** — Restrict file access to project directory (if feasible).

---


## 63. PHP Deprecation Awareness

### Deprecated in PHP 8.4

- [ ] **`implode()` with reversed arguments** — `implode($array, ',')` is deprecated. Use `implode(',', $array)`.
- [ ] **`SCREAMING_CASE` constants on built-in enums** — Many PHP 8.4+ enum-backed constants use PascalCase. Check library changelogs.
- [ ] **Legacy MySQL extensions** — `mysql_*` functions were removed in PHP 7.0 but some codebases still reference them via wrapper libraries.

### Deprecated Patterns to Avoid

- [ ] **Dynamic properties on classes** — Deprecated in PHP 8.2, fatal error in PHP 9.0. Use `#[AllowDynamicProperties]` or declare properties explicitly.

```php
// DEPRECATED — dynamic property
$user = new User();
$user->nonExistentProperty = 'value'; // Deprecation warning in 8.2

// SAFE — declare all properties
class User
{
    public ?string $customField = null;
}
```

- [ ] **`utf8_encode()` / `utf8_decode()`** — Deprecated in PHP 8.2. Use `mb_convert_encoding($str, 'UTF-8', 'ISO-8859-1')`.
- [ ] **`${var}` string interpolation** — Deprecated in PHP 8.2. Use `{$var}` instead.
- [ ] **`#[\ReturnTypeWillChange]`** — Temporary suppression for missing return types. Add proper return types instead.
- [ ] **Optional parameter before required** — `function foo($optional = null, $required)` deprecated in PHP 8.0. Reorder parameters.

### Preparing for PHP 9.0

- [ ] **All deprecation warnings treated as errors in CI** — `error_reporting(E_ALL)` and fail tests on deprecation notices.
- [ ] **Run `rector` with PHP 9.0 rules** — Automated upgrade detection.
- [ ] **No reliance on `__autoload()`** — Use Composer's PSR-4 autoloading exclusively.
- [ ] **Strict comparisons everywhere** — Loose comparison behavior changes between PHP versions. `===` is stable.

---


## 71. Enum Best Practices (Deep Dive)

- [ ] **Backed enums (`string` or `int`) for all database-persisted enums** — Unbacked enums can't be stored.
- [ ] **TitleCase enum cases** — `OrderStatus::Pending`, not `PENDING` or `pending`.
- [ ] **`label()` method for human-readable text** — `$status->label()` returns `'In Progress'`.
- [ ] **`color()` method for UI badge colors** — `$status->color()` returns `'yellow'`.
- [ ] **`allowedTransitions()` for state machines** — Returns `array<self>`.
- [ ] **`isTerminal()` for end states** — `return match($this) { self::Completed, self::Cancelled => true, default => false }`.
- [ ] **No business logic in enums** — Enums define state. Actions contain logic.
- [ ] **TypeScript mirrors for frontend enums** — Every PHP enum used in Vue must have a TS equivalent.
- [ ] **Validate with `Rule::enum()`** — Not `Rule::in(array_column(...))`.
- [ ] **Never hardcode enum values as strings** — `OrderStatus::Pending` not `'pending'`.

---


## 141. Fiber & Async Patterns

- [ ] **Fibers are cooperative, not parallel** — PHP Fibers don't run in separate threads. They yield/resume within a single thread.
- [ ] **Use Fibers for non-blocking I/O** — HTTP requests, database queries via async drivers (Amp, ReactPHP).
- [ ] **Don't use Fibers for CPU-bound work** — No parallelism gain. Use queue jobs instead.
- [ ] **Exception handling in Fibers** — Uncaught exceptions propagate to the calling code via `$fiber->resume()`.

```php
$fiber = new Fiber(function (): void {
    $result = Fiber::suspend('waiting for data');
    // Process $result
});

$fiber->start();                  // Returns 'waiting for data'
$fiber->resume($fetchedData);     // Continues execution
```

- [ ] **Avoid long-running Fibers in request context** — PHP-FPM requests should complete quickly. Use queues for background work.
- [ ] **Framework integration** — Prefer Laravel's `Http::pool()` over raw Fibers for concurrent HTTP requests.

```php
$responses = Http::pool(fn (Pool $pool) => [
    $pool->get('https://api.example.com/users'),
    $pool->get('https://api.example.com/orders'),
]);
```

---


## 142. Readonly Classes & Properties Deep Dive

- [ ] **Use `readonly class` for DTOs and Value Objects** — Prevents all properties from being modified after construction.

```php
readonly class Money
{
    public function __construct(
        public string $amount,
        public int $scale,
        public string $currency,
    ) {}
}
```

- [ ] **`readonly` properties cannot be re-assigned** — Not even from within the class (except in the constructor).
- [ ] **`readonly` classes cannot have non-readonly properties** — The `readonly` modifier applies to all declared properties.
- [ ] **No dynamic properties on `readonly` classes** — `#[AllowDynamicProperties]` cannot be used with `readonly class`.
- [ ] **Clone and readonly** — PHP 8.3 allows `clone $this` with property modification in `__clone()`. Before 8.3, `readonly` properties cannot be changed even during cloning.
- [ ] **Events should be readonly** — Stored events are immutable records of what happened.
- [ ] **Don't use `readonly` on Eloquent models** — Models need mutable state for hydration, casts, and relationships.

---


## 143. First-Class Callable Syntax

- [ ] **Use `Closure::fromCallable()` replacement syntax** — Shorter, clearer.

```php
// Old
$fn = Closure::fromCallable([$this, 'process']);

// PHP 8.1+ first-class callable
$fn = $this->process(...);
```

- [ ] **Use with `array_map`, `array_filter`** — Cleaner than string function names.

```php
$names = array_map(strtoupper(...), $rawNames);
$valid = array_filter($items, $this->isValid(...));
```

- [ ] **Use for route actions in tests** — `action(UserController::class . '@show', ...)` → `action([UserController::class, 'show'])`.
- [ ] **Works with static methods** — `MyClass::staticMethod(...)`.
- [ ] **Works with built-in functions** — `strlen(...)`, `is_null(...)`.
- [ ] **Type-safe** — The resulting `Closure` carries the type signature of the original callable.

---


## 144. Modern Array Functions

- [ ] **`array_find()` (PHP 8.4)** — Returns the first element matching a callback.

```php
$admin = array_find($users, fn (User $user) => $user->isAdmin());
// Returns null if none found — no need for Collection::first()
```

- [ ] **`array_find_key()` (PHP 8.4)** — Returns the key of the first matching element.
- [ ] **`array_any()` (PHP 8.4)** — Returns `true` if any element matches.

```php
$hasOverdue = array_any($invoices, fn ($inv) => $inv->isOverdue());
```

- [ ] **`array_all()` (PHP 8.4)** — Returns `true` if all elements match.

```php
$allApproved = array_all($entries, fn ($e) => $e->status === Status::Approved);
```

- [ ] **Use over Collection for simple arrays** — When you already have a plain array, avoid wrapping in `collect()` just for `->first()`.
- [ ] **Prefer `array_is_list()` (PHP 8.1)** — Check if an array is a sequential list (0-indexed, no gaps).

---


## 145. Attribute-Based Programming

- [ ] **`#[Override]` (PHP 8.3)** — Explicitly mark methods that override a parent. Compile-time error if the parent method doesn't exist.

```php
class AppServiceProvider extends ServiceProvider
{
    #[Override]
    public function register(): void
    {
        // ...
    }
}
```

- [ ] **`#[Deprecated]` (PHP 8.4)** — Mark methods as deprecated with a message.

```php
#[Deprecated('Use calculateTotal() instead', since: '2.0')]
public function getTotal(): BigDecimal
{
    return $this->calculateTotal();
}
```

- [ ] **Custom attributes for metadata** — Replace docblock annotations with typed attributes.

```php
#[Attribute(Attribute::TARGET_PROPERTY)]
class Sensitive {}

class User extends Model
{
    #[Sensitive]
    public string $email;
}
```

- [ ] **Framework attributes** — Laravel uses `#[ObservedBy]`, `#[ScopedBy]`. Use them where available.
- [ ] **No runtime reflection for attributes in hot paths** — Attribute reflection is slow. Cache results.

---


## 146. Intersection & DNF Types

- [ ] **Intersection types** — Require a value to satisfy multiple interfaces.

```php
function process(Renderable&Countable $collection): string
{
    return $collection->render() . " ({$collection->count()} items)";
}
```

- [ ] **DNF (Disjunctive Normal Form) types (PHP 8.2)** — Combine union and intersection.

```php
function handle((Renderable&Countable)|null $items): void
{
    // Accepts null or something that is both Renderable and Countable
}
```

- [ ] **Use intersection types for DI parameters** — When a class must implement multiple interfaces.
- [ ] **Prefer composition over complex types** — If a DNF type is hard to read, extract an interface that extends both.

```php
interface RenderableCollection extends Renderable, Countable {}
```

- [ ] **No intersection types with `class` types** — Only interfaces can be intersected (as of PHP 8.2).

---


## 147. Property Hooks (PHP 8.4)

- [ ] **`get` and `set` hooks** — Replace magic `__get`/`__set` with typed, per-property hooks.

```php
class Temperature
{
    public float $celsius {
        get => $this->celsius;
        set (float $value) {
            if ($value < -273.15) {
                throw new InvalidArgumentException('Below absolute zero');
            }
            $this->celsius = $value;
        }
    }

    public float $fahrenheit {
        get => $this->celsius * 9/5 + 32;
        set (float $value) => $this->celsius = ($value - 32) * 5/9;
    }
}
```

- [ ] **Virtual properties** — Properties with only a `get` hook and no backing storage.
- [ ] **Hooks work with `readonly`** — A `readonly` property with a `set` hook runs the hook once in the constructor.
- [ ] **Use for computed properties** — Replace accessor methods with `get` hooks.
- [ ] **Don't use on Eloquent model properties** — Eloquent has its own attribute system (`Attribute::get()` / `Attribute::set()`).
- [ ] **Hooks are inherited** — Child classes can override parent hooks with `#[Override]`.

---


## 148. Asymmetric Visibility (PHP 8.4)

- [ ] **Different visibility for read vs write** — `public private(set)` means readable everywhere, writable only internally.

```php
class BankAccount
{
    public private(set) string $accountNumber;
    public protected(set) BigDecimal $balance;

    public function __construct(string $accountNumber, BigDecimal $initialBalance)
    {
        $this->accountNumber = $accountNumber;
        $this->balance = $initialBalance;
    }
}

$account->accountNumber;      // OK — public read
$account->accountNumber = 'x'; // Error — private set
```

- [ ] **Use for immutable-from-outside properties** — Replaces the pattern of `private $prop` + `public function getProp()`.
- [ ] **Constructor promotion syntax** — `public private(set) string $name`.
- [ ] **Set visibility must be equal or more restrictive than get** — `public protected(set)` is valid; `protected public(set)` is not.
- [ ] **Prefer over separate getter methods** — Reduces boilerplate while maintaining encapsulation.
- [ ] **Value objects benefit most** — Properties readable everywhere, settable only in constructor.

---


---

[← Previous Part](05-financial-monetary-correctness.md) | [Full Checklist](../checklist.md) | [Next Part →](07-clean-code-software-design.md)
