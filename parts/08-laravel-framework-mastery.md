[← Previous Part](07-clean-code-software-design.md) | [Full Checklist](../checklist.md) | [Next Part →](09-database-engineering.md)

# Part VIII — Laravel Framework Mastery

**23 sections · 196 checks**

- [10. Laravel Anti-Patterns](#10-laravel-anti-patterns)
- [11. Validation & Input Handling](#11-validation-input-handling)
- [24. Error Handling & Exception Design](#24-error-handling-exception-design)
- [25. Eloquent Model Safety](#25-eloquent-model-safety)
- [26. Caching Safety](#26-caching-safety)
- [34. Event & Listener Safety](#34-event-listener-safety)
- [35. Service Provider Hygiene](#35-service-provider-hygiene)
- [74. Config File Organization](#74-config-file-organization)
- [75. Middleware Authoring Pitfalls](#75-middleware-authoring-pitfalls)
- [76. Form Request Advanced Patterns](#76-form-request-advanced-patterns)
- [77. Eloquent Observer & Event Ordering](#77-eloquent-observer-event-ordering)
- [79. Artisan Make Command Hygiene](#79-artisan-make-command-hygiene)
- [80. Seeder & Factory Safety](#80-seeder-factory-safety)
- [159. Custom Eloquent Casts](#159-custom-eloquent-casts)
- [160. Service Container Advanced Bindings](#160-service-container-advanced-bindings)
- [161. Macros & Mixins Discipline](#161-macros-mixins-discipline)
- [162. Route Model Binding Advanced Patterns](#162-route-model-binding-advanced-patterns)
- [163. Laravel Prompts for CLI UX](#163-laravel-prompts-for-cli-ux)
- [164. Custom Validation Rules](#164-custom-validation-rules)
- [165. Notification Architecture](#165-notification-architecture)
- [166. Eloquent Accessor & Mutator Hygiene](#166-eloquent-accessor-mutator-hygiene)
- [167. Model Event Lifecycle Awareness](#167-model-event-lifecycle-awareness)
- [168. Action Pattern Discipline](#168-action-pattern-discipline)

---

## 10. Laravel Anti-Patterns

### N+1 Queries

- [ ] **Eager load relationships** — Use `with()`, `load()`, or `$with` property on the model. Use Laravel Debugbar or `preventLazyLoading()` to detect.

```php
// In AppServiceProvider::boot() for development
Model::preventLazyLoading(! app()->isProduction());

// SAFE
$posts = Post::with(['author', 'comments'])->get();

// N+1 — triggers a query per post
$posts = Post::all();
foreach ($posts as $post) {
    echo $post->author->name; // Query for each post
}
```

### Fat Controllers

- [ ] **Controllers under 10 lines per method** — Delegate to Form Requests (validation), Actions (logic), and Resources (response formatting).

### God Models

- [ ] **Models should only have**: relationships, scopes, casts, accessors/mutators, and configuration.
- [ ] **Business logic belongs in Actions/Services** — Not in model methods.

### Raw Queries

- [ ] **Use Eloquent or Query Builder** — Not `DB::select()` with raw SQL unless performance-critical and parameterised.
- [ ] **Use `Model::query()` not `DB::table()`** — Maintains model boots, events, soft deletes, global scopes.

### env() Outside Config

- [ ] **Never use `env()` outside `config/` files** — After `config:cache`, `env()` returns `null`. Always `config('key')`.

```php
// WRONG — breaks after config:cache
$apiKey = env('STRIPE_KEY');

// RIGHT
// In config/services.php:
'stripe' => ['key' => env('STRIPE_KEY')],
// In code:
$apiKey = config('services.stripe.key');
```

### Missing Form Requests

- [ ] **Every controller store/update uses a Form Request** — Never `$request->validate()` inline in controllers.
- [ ] **Array-based rules, not string pipe syntax** — `['required', 'string', 'max:255']` not `'required|string|max:255'`.

### Missing Policies

- [ ] **Every model with user-owned data has a Policy** — Register in `AuthServiceProvider` or via auto-discovery.
- [ ] **Policies check ownership, not just permissions** — `$user->id === $post->user_id && $user->can('edit-posts')`.

### Unqueued Heavy Operations

- [ ] **Send emails via queued notifications** — `$user->notify(new OrderConfirmation($order))` with `ShouldQueue`.
- [ ] **Queue PDF generation, CSV exports, report building** — Anything over 500ms should be queued.
- [ ] **Queue webhook dispatching** — External HTTP calls should never block user requests.

---


## 11. Validation & Input Handling

### Form Requests

- [ ] **Dedicated Form Request for every store/update** — `php artisan make:request StoreOrderRequest`.
- [ ] **`authorize()` method returns a boolean or policy check** — Don't skip authorization.
- [ ] **Use `Rule::enum()` for enum validation** — Not `Rule::in(array_column(Enum::cases(), 'value'))`.

```php
public function rules(): array
{
    return [
        'status' => ['required', Rule::enum(OrderStatus::class)],
        'email' => ['required', 'email:rfc,dns', 'max:255'],
        'amount' => ['required', 'numeric', 'min:0.01'],
        'items' => ['required', 'array', 'min:1'],
        'items.*.product_id' => ['required', 'uuid', 'exists:products,id'],
        'items.*.quantity' => ['required', 'integer', 'min:1'],
    ];
}
```

- [ ] **Custom validation messages** — Provide user-friendly messages, not framework defaults.
- [ ] **Custom validation rules** — Extract complex validation into `Rule` objects.

### Input Sanitisation

- [ ] **Validate on input, escape on output** — Store raw data, sanitise at render time.
- [ ] **Use `$request->validated()` or `$request->safe()`** — Never `$request->all()` for data creation.
- [ ] **Validate file types by MIME, not extension** — Extensions can be forged.
- [ ] **Limit string lengths** — Every string field must have `max:` validation.
- [ ] **Limit array sizes** — Use `max:` on array validation rules to prevent memory exhaustion.

### Period / Date Validation

- [ ] **Use select/dropdown for period inputs** — Not free-text. Prevents invalid formats like `2025-13` or `2025-00`.
- [ ] **Validate date ranges** — `'end_date' => ['after_or_equal:start_date']`.

---


## 24. Error Handling & Exception Design

### Exception Hierarchy

- [ ] **Use domain-specific exceptions, not generic ones** — `InsufficientFundsException` not `\RuntimeException('Not enough funds')`.
- [ ] **Extend a base domain exception** — All app exceptions extend `App\Exceptions\DomainException` (or similar) so they can be caught uniformly.
- [ ] **Map exceptions to HTTP status codes** — Domain exceptions should carry `httpStatusCode()` or use `render()`.

```php
abstract class DomainException extends \RuntimeException
{
    public function __construct(
        string $message,
        protected readonly array $context = [],
        ?\Throwable $previous = null,
    ) {
        parent::__construct($message, 0, $previous);
    }

    abstract public function httpStatusCode(): int;
}

class InsufficientFundsException extends DomainException
{
    public function httpStatusCode(): int { return 422; }
}
```

### Exception Anti-Patterns

- [ ] **Never catch `\Exception` or `\Throwable` and silently swallow** — Always log or rethrow.

```php
// DANGEROUS — error swallowed
try {
    $this->process();
} catch (\Exception $e) {
    // Nothing — error disappears
}

// SAFE — log and rethrow as domain exception
try {
    $this->process();
} catch (\Throwable $e) {
    Log::error('Processing failed', ['error' => $e->getMessage()]);
    throw new ProcessingFailedException('Could not process request', [], $e);
}
```

- [ ] **Never expose internal exception messages to users** — Return generic messages in production. Log the real error.
- [ ] **Never expose stack traces in production** — `APP_DEBUG=false` prevents this, but verify custom error handlers also obey it.
- [ ] **Use `report()` helper for non-fatal exceptions** — `report($e)` logs without halting execution.
- [ ] **Use `abort()` for HTTP exceptions** — `abort(404)`, `abort(403, 'Unauthorized')`. Not `throw new HttpException()`.
- [ ] **Define `$dontReport` / `$dontFlash` in exception handler** — Suppress noisy exceptions (e.g., `ValidationException`, `ModelNotFoundException`), prevent passwords from flashing back to forms.

### Structured Error Responses

- [ ] **Return consistent JSON error structure for APIs** — `{ "message": "...", "errors": { "field": ["..."] } }`.
- [ ] **Validation errors return 422** — Not 400 or 500.
- [ ] **Authorization failures return 403** — Not 401 (which means unauthenticated).
- [ ] **Not found returns 404** — Use `findOrFail()` or `firstOrFail()` for automatic 404s.
- [ ] **Rate limit exceeded returns 429** — With `Retry-After` header.

### Try/Catch Placement

- [ ] **Catch at the boundary, not deep in business logic** — Controllers and job handlers catch. Actions throw.
- [ ] **Third-party API calls always wrapped in try/catch** — Network calls can fail. Catch, log, throw domain exception.
- [ ] **Never catch exceptions inside DB transactions just to continue** — This breaks transaction integrity. Let the exception bubble up so the transaction rolls back.

---


## 25. Eloquent Model Safety

### Model Boot Traps

- [ ] **Never put heavy logic in `boot()` / `booted()`** — Model events fire on every create/update/delete. Heavy logic causes cascading performance issues.
- [ ] **Avoid `creating`/`updating` observers for business logic** — Use explicit action classes instead. Observers are invisible and create hidden coupling.
- [ ] **Use `withoutEvents()` for bulk operations** — Prevents observer cascades during data imports/migrations.

```php
// Seeder / migration — skip observers
User::withoutEvents(function () {
    User::factory()->count(1000)->create();
});
```

### Serialization Dangers

- [ ] **Define `$hidden` on all models with sensitive data** — Prevents accidental exposure via `toArray()` / `toJson()` / API responses.
- [ ] **Use API Resources for responses, never raw `$model->toArray()`** — Resources give explicit control over what fields are exposed.
- [ ] **Beware of `$appends`** — Appended attributes are included in every serialization. Use sparingly.
- [ ] **Never pass full models to queued jobs** — Laravel serializes the model ID and re-queries on execution. But `SerializesModels` can expose data in queue payloads. Use `ShouldBeEncrypted` for sensitive jobs.

### Accessors & Mutators

- [ ] **Use `Attribute` accessor syntax (Laravel 9+)** — Not the old `getXxxAttribute()` / `setXxxAttribute()` style.

```php
// Modern
protected function fullName(): Attribute
{
    return Attribute::make(
        get: fn () => "{$this->first_name} {$this->last_name}",
    );
}

// Legacy — avoid
public function getFullNameAttribute(): string
{
    return "{$this->first_name} {$this->last_name}";
}
```

- [ ] **Never put side effects in accessors** — Accessors should be pure. No database queries, no logging, no state changes.

### Model Immutability

- [ ] **Terminal-state records must be immutable** — Posted journal entries, completed orders, closed periods — override `save()`, `update()`, `delete()` or use a saving observer to reject writes.
- [ ] **Use `$model->isDirty()` / `$model->wasChanged()` for change detection** — Not manual comparisons.

### Scopes

- [ ] **Use named scopes for reusable filters** — `scopeActive()`, `scopeForPeriod()`, `scopeByUser()`.
- [ ] **Global scopes need careful consideration** — They apply to ALL queries. Easy to forget they exist. Prefer local scopes unless the filter truly applies everywhere.
- [ ] **Soft delete IS a global scope** — Remember `withTrashed()` when you need deleted records.

---


## 26. Caching Safety

### Cache Poisoning

- [ ] **Never use user input as cache keys without hashing** — `cache()->get($request->input('key'))` allows cache key manipulation.

```php
// DANGEROUS
$data = cache()->get($request->input('key'));

// SAFE — hash user input
$key = 'report:' . hash('xxh128', $request->input('filter'));
$data = cache()->get($key);
```

- [ ] **Cache key collisions** — Prefix keys by model/domain: `users:profile:{$userId}`, `reports:trial-balance:{$period}`.
- [ ] **Never cache authenticated user data globally** — One user's data served to another. Scope cache keys per user or use `Cache::tags()`.

### Cache Invalidation

- [ ] **Invalidate cache when underlying data changes** — Use model observers or explicit `cache()->forget()` in actions.
- [ ] **Set appropriate TTLs** — No infinite caches without invalidation strategy. Use `remember()` with explicit seconds.
- [ ] **Use `Cache::lock()` for cache stampede prevention** — When many requests miss the cache simultaneously.

```php
$value = Cache::lock('report-generation')->block(5, function () {
    return Cache::remember('daily-report', 3600, function () {
        return $this->generateReport();
    });
});
```

### Serialization in Cache

- [ ] **Cached data must be serializable** — Closures, database connections, and file handles cannot be cached.
- [ ] **Don't cache Eloquent models directly** — They carry database connections and heavy metadata. Cache DTOs or arrays.
- [ ] **Beware of stale cache after deployments** — `php artisan cache:clear` after deploys, or use cache versioning.

---


## 34. Event & Listener Safety

### Event Dispatching

- [ ] **Events should be data carriers, not logic executors** — Events contain data. Listeners contain logic.
- [ ] **Use typed properties on events** — Not raw arrays.
- [ ] **Events dispatched inside transactions may need `afterCommit`** — Listeners that depend on committed data should use `ShouldHandleEventsAfterCommit` or `$afterCommit = true`.

```php
class OrderCreated implements ShouldDispatchAfterCommit
{
    public function __construct(
        public readonly string $orderId,
        public readonly string $userId,
    ) {}
}
```

### Listener Safety

- [ ] **Queue listeners that do external I/O** — Email, HTTP calls, file operations. Use `ShouldQueue`.
- [ ] **Handle listener failures gracefully** — One failing listener should not prevent others from running. Use `try/catch` in listeners, or configure `$tries` and `failed()`.
- [ ] **No circular event chains** — Listener A dispatches Event B, Listener B dispatches Event A → infinite loop.
- [ ] **Order-dependent listeners need explicit ordering** — Use the `$listen` array in EventServiceProvider or `Attribute` class.

### Event Sourcing Specific

- [ ] **Events carry all data needed for projection** — Don't query the DB during replay. The data may not exist yet.
- [ ] **Every recorded event has an `apply` method on the aggregate** — Even if it's a no-op.
- [ ] **Event metadata attached consistently** — User ID, IP, user agent, request ID on every event.
- [ ] **Audit reactor handles every event type** — No gaps in the audit trail.

---


## 35. Service Provider Hygiene

### Register vs Boot

- [ ] **`register()` — bind interfaces, register singletons** — No model access, no route access, no config access beyond `config()`.
- [ ] **`boot()` — configure observers, gates, macros** — Can access models, routes, and other providers.
- [ ] **Never do database queries in `register()` or `boot()`** — This runs on every request, including artisan commands and queue workers.

### Deferred Providers

- [ ] **Use `DeferrableProvider` for rarely-used bindings** — Provider only loads when the bound interface is resolved.

### Common Mistakes

- [ ] **Don't register the same binding twice** — Check if another provider already binds the interface.
- [ ] **Don't register middleware in providers** — Use `bootstrap/app.php` in Laravel 11+.
- [ ] **Don't put business logic in providers** — Providers are for wiring, not logic.

---


## 74. Config File Organization

- [ ] **One config file per domain** — `config/auth.php`, `config/cache.php`, `config/ai.php`. Not one giant `config/app.php`.
- [ ] **Env-driven values only at config level** — `'key' => env('API_KEY')` in config. `config('services.api_key')` everywhere else.
- [ ] **Type-cast env values** — `(int) env('TIMEOUT', 30)` or `(bool) env('FEATURE_FLAG', false)`.
- [ ] **Default values for all env() calls** — `env('KEY', 'default')`. Never `env('KEY')` without a fallback.
- [ ] **No nested closures in config files** — Config is serialized by `config:cache`. Closures break caching.
- [ ] **Config keys are snake_case** — `config('services.stripe.webhook_secret')`.

---


## 75. Middleware Authoring Pitfalls

- [ ] **Set response headers in `$next()` response, not before** — The response doesn't exist until after `$next($request)`.
- [ ] **Don't mutate the request object** — Create a new request with `$request->merge()` if values must change.
- [ ] **Terminate middleware for post-response work** — Implement `TerminableMiddleware` for logging, analytics.
- [ ] **Middleware should be stateless** — No properties that persist between requests. Middleware instances may be reused.
- [ ] **Order matters** — Auth before authorization. Rate limiting before heavy processing. CORS before everything.
- [ ] **Short-circuit early** — Return responses immediately for rejected requests. Don't call `$next()` unnecessarily.

---


## 76. Form Request Advanced Patterns

- [ ] **`authorize()` returns policy check** — `return $this->user()->can('create', Order::class)`.
- [ ] **`prepareForValidation()` for input normalization** — Trim, lowercase, format before rules run.

```php
protected function prepareForValidation(): void
{
    $this->merge([
        'email' => strtolower(trim($this->email)),
        'phone' => preg_replace('/[^0-9]/', '', $this->phone),
    ]);
}
```

- [ ] **`passedValidation()` for post-validation transforms** — Convert to DTOs after validation passes.
- [ ] **Conditional rules with `sometimes()`** — `'discount' => 'sometimes|numeric|min:0'`.
- [ ] **Custom rule objects over closure rules** — Reusable, testable, named.
- [ ] **Nested array validation** — `'items.*.quantity' => 'required|integer|min:1'`.
- [ ] **Error message customization** — Override `messages()` and `attributes()` for user-friendly text.

---


## 77. Eloquent Observer & Event Ordering

- [ ] **Observers fire in registration order** — Register critical observers first.
- [ ] **`creating` fires before `created`** — Use `creating` to set defaults, `created` for side effects.
- [ ] **`updating` gets dirty attributes** — `$model->isDirty('status')` in observers.
- [ ] **`deleting` fires before soft delete** — Can prevent deletion by returning `false`.
- [ ] **Observers don't fire on bulk operations** — `Model::where(...)->update([...])` bypasses observers. Use `each->update()` if observers are needed.
- [ ] **Avoid heavy work in observers** — Queue side effects. Observers run synchronously.
- [ ] **Model events don't fire in `DB::table()` queries** — Only Eloquent triggers events.

---


## 79. Artisan Make Command Hygiene

- [ ] **Always use `php artisan make:*`** — Creates files in the correct directory with proper namespace.
- [ ] **Pass `--no-interaction`** — Prevents prompts from hanging in CI.
- [ ] **Use relevant flags** — `make:model -mfsc` (migration, factory, seeder, controller) for full scaffold.
- [ ] **Use `make:test --pest`** — Creates Pest test, not PHPUnit.
- [ ] **Use `make:class`** — For plain PHP classes that don't fit other categories.
- [ ] **Verify file after generation** — Make commands generate stubs. Always review and customize.

---


## 80. Seeder & Factory Safety

### Factories

- [ ] **Every model has a factory** — No exceptions.
- [ ] **Factories produce valid default state** — `Model::factory()->create()` should work without overrides.
- [ ] **Use factory states for variations** — `->approved()`, `->withItems()`, `->forUser($user)`.
- [ ] **Factories respect unique constraints** — Use `fake()->unique()` for unique columns.
- [ ] **No real data in factories** — No real email addresses, phone numbers, or names.

### Seeders

- [ ] **Seeders are idempotent** — Running twice doesn't create duplicates. Use `updateOrCreate()`.
- [ ] **Seeders don't depend on execution order** — Or explicitly call dependencies.
- [ ] **Production seeders separate from dev seeders** — Roles/permissions seeder runs in production. Fake data seeders don't.
- [ ] **No `DB::table()->truncate()` in production seeders** — Data loss risk.
- [ ] **Use transactions in seeders** — Fail atomically.

---


## 159. Custom Eloquent Casts

- [ ] **Implement `CastsAttributes` for complex types** — Transparent serialization/deserialization.

```php
class MoneyCast implements CastsAttributes
{
    public function get(Model $model, string $key, mixed $value, array $attributes): ?Money
    {
        $prefix = Str::before($key, '_amount') ?: $key;
        if ($attributes["{$prefix}_amount"] === null) {
            return null;
        }
        return Money::of(
            $attributes["{$prefix}_amount"],
            (int) $attributes["{$prefix}_amount_scale"],
            $attributes["{$prefix}_amount_currency"],
        );
    }

    public function set(Model $model, string $key, mixed $value, array $attributes): array
    {
        $prefix = Str::before($key, '_amount') ?: $key;
        return $value?->toStorageArray($prefix) ?? [
            "{$prefix}_amount" => null,
            "{$prefix}_amount_scale" => null,
            "{$prefix}_amount_currency" => null,
        ];
    }
}
```

- [ ] **Casts are stateless** — Don't store mutable state in a cast class.
- [ ] **Handle `null` gracefully** — Return `null` if the stored value is null.
- [ ] **Custom casts for enums with extra logic** — When `BackedEnum` cast isn't enough.
- [ ] **Test casts with real models** — Create a model, set the attribute, save, reload, and verify.
- [ ] **Register reusable casts in a service provider** — If the same cast is used across many models.

---


## 160. Service Container Advanced Bindings

- [ ] **Contextual bindings** — Different implementations for different consumers.

```php
$this->app->when(SettlementAction::class)
    ->needs(ExchangeRateProvider::class)
    ->give(LiveRateProvider::class);

$this->app->when(ReportQuery::class)
    ->needs(ExchangeRateProvider::class)
    ->give(CachedRateProvider::class);
```

- [ ] **Scoped singletons for request lifecycle** — `$this->app->scoped(TenantContext::class)`.
- [ ] **Tagged bindings for collecting implementations** — `$this->app->tag([CbxProvider::class, ManualProvider::class], 'rate-providers')`.
- [ ] **Deferred providers for performance** — Implement `DeferrableProvider` to load only when needed.
- [ ] **No service location** — Don't call `app(Foo::class)` in business logic. Inject via constructor.

```php
// BAD — service location
class MyAction
{
    public function execute(): void
    {
        $service = app(ExchangeRateProvider::class);
    }
}

// GOOD — constructor injection
class MyAction
{
    public function __construct(private ExchangeRateProvider $rateProvider) {}
}
```

- [ ] **`bind` vs `singleton` vs `scoped`** — `bind` = new instance each time. `singleton` = one per app. `scoped` = one per request.
- [ ] **Test bindings** — Assert `$this->app->make(Interface::class)` returns the expected implementation.

---


## 161. Macros & Mixins Discipline

- [ ] **Register macros in service providers** — Not in controllers or random files.

```php
// In AppServiceProvider::boot()
Builder::macro('whereDateBetween', function (string $column, CarbonImmutable $start, CarbonImmutable $end) {
    return $this->whereBetween($column, [$start->startOfDay(), $end->endOfDay()]);
});
```

- [ ] **Type-hint macro parameters** — Even though macros bypass static analysis, type hints document intent.
- [ ] **PHPDoc for IDE support** — Add `@method` annotations or use IDE helper packages.
- [ ] **Don't override built-in methods** — Macros that shadow existing methods cause confusion.
- [ ] **Macros are global** — Every query builder instance gets the macro. Use sparingly.
- [ ] **Prefer scopes on models over macros** — Scopes are model-specific; macros are global.
- [ ] **Test macros** — They're invisible to static analysis, so tests are your only safety net.

---


## 162. Route Model Binding Advanced Patterns

- [ ] **Custom binding resolution** — Override `resolveRouteBinding()` for complex lookups.

```php
public function resolveRouteBinding($value, $field = null): ?self
{
    return $this->where($field ?? 'id', $value)
        ->where('tenant_id', auth()->user()->tenant_id)
        ->firstOrFail();
}
```

- [ ] **Scoped bindings** — `Route::scopeBindings()` ensures child resources belong to parent.

```php
Route::get('/accounts/{account}/entries/{journalEntry}', ShowEntry::class)
    ->scopeBindings();
// journalEntry is scoped to account automatically
```

- [ ] **Soft-deleted model binding** — `Route::withTrashed()` for admin views of deleted records.
- [ ] **Enum route binding** — PHP enums bind automatically in Laravel 12.

```php
Route::get('/entries/status/{status}', IndexByStatus::class);
// {status} auto-resolves to Status enum
```

- [ ] **Custom keys via `getRouteKeyName()`** — Use UUIDs or slugs instead of auto-increment IDs.
- [ ] **Don't resolve in controller** — Let route model binding do the work. The controller receives typed models.

---


## 163. Laravel Prompts for CLI UX

- [ ] **Use `Laravel\Prompts` for Artisan command UX** — Rich terminal UI for selects, confirms, progress bars.

```php
use function Laravel\Prompts\select;
use function Laravel\Prompts\confirm;
use function Laravel\Prompts\progress;

$period = select(
    label: 'Which period to close?',
    options: $openPeriods->pluck('name', 'id')->all(),
);

if (confirm('Are you sure you want to close this period?')) {
    progress(
        label: 'Closing period...',
        steps: $closingSteps,
        callback: fn ($step) => $step->execute(),
    );
}
```

- [ ] **Validate input with prompts** — `text(label: 'Amount', validate: ['amount' => 'required|numeric|min:0'])`.
- [ ] **Use `spin()` for long operations** — Shows a spinner while waiting.
- [ ] **Fallback for non-interactive mode** — `--no-interaction` should use defaults or fail gracefully.
- [ ] **Use `table()` for tabular output** — Not `echo` with manual formatting.
- [ ] **Use `info()`, `warn()`, `error()`** — Colored, semantic output instead of plain `$this->line()`.

---


## 164. Custom Validation Rules

- [ ] **Create rule objects** — `php artisan make:rule BalancedJournalLines`.

```php
class BalancedJournalLines implements ValidationRule
{
    public function validate(string $attribute, mixed $value, Closure $fail): void
    {
        $totalDebit = BigDecimal::zero();
        $totalCredit = BigDecimal::zero();

        foreach ($value as $line) {
            $totalDebit = $totalDebit->plus(BigDecimal::of($line['debit_amount'] ?? '0'));
            $totalCredit = $totalCredit->plus(BigDecimal::of($line['credit_amount'] ?? '0'));
        }

        if (!$totalDebit->isEqualTo($totalCredit)) {
            $fail('Journal entry must balance. Debit: :debit, Credit: :credit')
                ->translate(['debit' => $totalDebit, 'credit' => $totalCredit]);
        }
    }
}
```

- [ ] **Rules are unit-testable** — Test the rule class directly, not just via form requests.
- [ ] **Use `Rule::when()` for conditional rules** — `Rule::when($isInternational, ['swift_code' => 'required'])`.
- [ ] **Use `Rule::enum()`** — `'status' => [Rule::enum(Status::class)]`.
- [ ] **Avoid closure rules in production** — They can't be serialized. Use rule objects.
- [ ] **Custom error messages with `:attribute` placeholder** — Messages should read naturally.

---


## 165. Notification Architecture

- [ ] **One notification class per event** — `JournalEntryApprovedNotification`, not a generic `SystemNotification`.
- [ ] **Implement `ShouldQueue`** — Notifications should not block the request.
- [ ] **Use `via()` to control channels** — Database, mail, SMS — based on user preferences.

```php
public function via(object $notifiable): array
{
    $channels = ['database'];
    if ($notifiable->email_notifications_enabled) {
        $channels[] = 'mail';
    }
    return $channels;
}
```

- [ ] **Database notifications have structured data** — `toArray()` returns typed, queryable data.
- [ ] **Rate limit notifications** — Don't send 100 emails for 100 similar events. Batch or throttle.
- [ ] **Notification preferences per user** — Let users choose which notifications they receive and via which channel.
- [ ] **Test notifications** — `Notification::fake()` + `Notification::assertSentTo()`.
- [ ] **No sensitive data in notifications** — Don't email full transaction amounts or account numbers.

---


## 166. Eloquent Accessor & Mutator Hygiene

- [ ] **Use the `Attribute` return type (Laravel 9+)** — Not the old `get{Name}Attribute` convention.

```php
protected function fullName(): Attribute
{
    return Attribute::get(fn () => "{$this->first_name} {$this->last_name}");
}
```

- [ ] **Computed attributes go in `$appends`** — If they should appear in JSON/array serialization.
- [ ] **Don't put business logic in accessors** — Accessors format data; they don't calculate business rules.
- [ ] **Avoid expensive accessors** — Each accessor runs per model instance. N models = N accessor calls.
- [ ] **Mutators validate input** — A mutator that sets `email` should normalize casing.

```php
protected function email(): Attribute
{
    return Attribute::set(fn (string $value) => strtolower(trim($value)));
}
```

- [ ] **Don't use accessors for formatted dates** — Use `immutable_datetime` cast + format in the view/frontend.
- [ ] **Test accessors and mutators** — Create a model, set values, assert the accessor returns expected output.

---


## 167. Model Event Lifecycle Awareness

- [ ] **Know the event order** — `creating → created → saving → saved → updating → updated`.
- [ ] **`saving` fires on both create and update** — Use `creating`/`updating` if you need to distinguish.
- [ ] **Events don't fire on mass operations** — `Model::query()->update([...])` skips events. Use `each()` if events are needed.

```php
// Events DO NOT fire
JournalEntry::query()->where('status', 'draft')->update(['status' => 'archived']);

// Events DO fire (but is slower)
JournalEntry::query()->where('status', 'draft')->each(fn ($e) => $e->update(['status' => 'archived']));
```

- [ ] **`deleted` doesn't fire on `DB::table()` deletes** — Only Eloquent model deletes trigger events.
- [ ] **Observers are registered globally** — An observer on `User` runs for every User operation in every context.
- [ ] **Beware of infinite loops** — An `updated` observer that calls `$model->save()` triggers `updating`/`updated` again.
- [ ] **Use `Model::withoutEvents()` for seed/migration operations** — Prevents observers from interfering.
- [ ] **`booted()` for model boot logic** — Not `boot()`. `booted()` runs after the parent boot chain.

---


## 168. Action Pattern Discipline

- [ ] **One Action, one job** — `CreateJournalEntry`, `ApproveJournalEntry`, `ReverseJournalEntry`.
- [ ] **Actions receive typed parameters** — DTOs or typed arguments, not raw arrays or request objects.

```php
class CreateJournalEntry
{
    public function execute(CreateJournalEntryData $data): JournalEntry
    {
        return DB::transaction(function () use ($data) {
            // ...
        });
    }
}
```

- [ ] **Actions don't depend on HTTP context** — No `request()`, no `auth()` inside. Pass everything in.
- [ ] **Actions are testable without HTTP** — Call `$action->execute($data)` directly in tests.
- [ ] **Actions call other Actions** — Composition, not inheritance.
- [ ] **Actions wrap transactions** — The Action owns the transaction boundary.
- [ ] **Actions throw domain exceptions** — `InsufficientBalanceException`, not `RuntimeException`.
- [ ] **Actions don't return HTTP responses** — Return domain objects. Let the controller format the response.
- [ ] **Naming convention** — Verb + Noun: `CreateUser`, `ApproveJournalEntry`, `RevalueForexBalances`.

---


---

[← Previous Part](07-clean-code-software-design.md) | [Full Checklist](../checklist.md) | [Next Part →](09-database-engineering.md)
