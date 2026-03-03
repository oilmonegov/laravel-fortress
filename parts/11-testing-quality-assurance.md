[← Previous Part](10-frontend-engineering.md) | [Full Checklist](../checklist.md) | [Next Part →](12-apis-queues-integration.md)

# Part XI — Testing & Quality Assurance

**9 sections · 76 checks**

- [14. Testing Quality](#14-testing-quality)
- [189. Test Doubles: Mocks, Stubs, Fakes, Spies](#189-test-doubles-mocks-stubs-fakes-spies)
- [190. Mutation Testing](#190-mutation-testing)
- [191. Contract Testing](#191-contract-testing)
- [192. Snapshot Testing](#192-snapshot-testing)
- [193. Load & Stress Testing](#193-load-stress-testing)
- [194. Test Data Management & Factories](#194-test-data-management-factories)
- [195. Flaky Test Prevention](#195-flaky-test-prevention)
- [196. Code Coverage Strategy](#196-code-coverage-strategy)

---

## 14. Testing Quality

### Structure

- [ ] **Feature tests for every action/controller** — Test the full HTTP flow.
- [ ] **Unit tests for value objects, DTOs, and pure functions** — No database, no HTTP.
- [ ] **Use factories, not manual model creation** — Factories enforce valid default state.

```php
// GOOD — factory with state
$order = Order::factory()->approved()->create();

// BAD — manual construction (brittle, doesn't validate relationships)
$order = Order::create(['status' => 'approved', 'user_id' => 1, ...]);
```

- [ ] **Use `it()` syntax (Pest)** — `it('creates an order', function () { ... })`.

### Assertions

- [ ] **Assert specific outcomes, not just "no errors"** — Check the database state, response content, dispatched events.
- [ ] **Assert side effects** — Email sent, event dispatched, job queued, log written.
- [ ] **Test validation rules** — Both valid and invalid inputs.
- [ ] **Test authorization** — Forbidden users get 403, authorized users get 200.

### Edge Cases

- [ ] **Test concurrent access** — Use `lockForUpdate()` tests with parallel requests.
- [ ] **Test boundary values** — 0, negative, max int, empty strings, null.
- [ ] **Test state machine transitions** — Every valid and invalid transition path.
- [ ] **Test SoD enforcement** — Same user cannot create + approve.
- [ ] **Test period lock enforcement** — Operations on locked periods are rejected.
- [ ] **Test idempotency** — Same request twice produces the same result without side effects.

### Anti-Patterns in Tests

- [ ] **No `sleep()` in tests** — Use fakes, mocks, or `travel()` for time-dependent tests.
- [ ] **No hardcoded IDs** — Use factory-generated models.
- [ ] **Resolve injected actions from container** — `app(CreateOrder::class)->handle(...)` not `(new CreateOrder(...))->handle(...)`.
- [ ] **Clean state per test** — Use `RefreshDatabase` or `LazilyRefreshDatabase` trait.

---


## 189. Test Doubles: Mocks, Stubs, Fakes, Spies

- [ ] **Know the terminology** — **Stub** returns canned answers. **Mock** verifies interactions. **Fake** has working but simplified logic. **Spy** records calls for later assertion.
- [ ] **Prefer fakes over mocks** — Fakes are more realistic and less brittle.

```php
// Fake
Notification::fake();
// ... perform action ...
Notification::assertSentTo($user, JournalEntryApprovedNotification::class);

// Mock — more brittle, tests implementation details
$mock = Mockery::mock(ExchangeRateProvider::class);
$mock->shouldReceive('getRate')->once()->andReturn(BigDecimal::of('1.5'));
```

- [ ] **Don't mock what you don't own** — Mock your interfaces, not third-party classes. Wrap third-party code in an adapter.
- [ ] **Laravel's built-in fakes** — `Event::fake()`, `Bus::fake()`, `Queue::fake()`, `Mail::fake()`, `Storage::fake()`. Use them.
- [ ] **Assert specific interactions, not all interactions** — `->once()` and `->withArgs()` are fine. `->shouldNotHaveBeenCalled()` on every other method is over-specification.
- [ ] **Spies are for "did this happen?" assertions** — When you don't want to set expectations upfront.
- [ ] **Clean up mocks** — Mockery::close() in tearDown or use the MockeryPHPUnitIntegration trait.

---


## 190. Mutation Testing

- [ ] **Mutation testing finds weak tests** — It modifies your code (mutations) and checks if tests fail. Surviving mutations = gaps.
- [ ] **Use Infection PHP** — `composer require infection/infection --dev`.

```bash
vendor/bin/infection --min-msi=70 --min-covered-msi=80
```

- [ ] **MSI (Mutation Score Indicator)** — Target > 70%. Higher for critical paths (financial, auth).
- [ ] **Focus on critical code** — Run mutation testing on `app/Actions/`, `app/Rules/`, not on controllers or views.
- [ ] **Common surviving mutations** — Boundary changes (`>` → `>=`), removed conditionals, changed return values. Each indicates a missing assertion.
- [ ] **Run in CI on critical paths** — Full mutation testing is slow. Run on changed files or critical directories only.
- [ ] **Fix surviving mutations by adding assertions** — Not by weakening the mutation config.

---


## 191. Contract Testing

- [ ] **Contract tests verify API boundaries** — Consumer and provider agree on request/response format.
- [ ] **Test your API's contract** — The response structure your frontend expects matches what the backend sends.

```php
it('returns the expected journal entry structure', function () {
    $entry = JournalEntry::factory()->posted()->create();

    $response = $this->getJson("/api/journal-entries/{$entry->id}");

    $response->assertJsonStructure([
        'data' => [
            'id', 'reference', 'status', 'posted_at',
            'lines' => [['account_id', 'debit_amount', 'credit_amount']],
        ],
    ]);
});
```

- [ ] **Version contracts** — When the API changes, update the contract version.
- [ ] **Consumer-driven contracts** — The frontend team defines what they need; the backend team fulfills it.
- [ ] **Test external API contracts** — If you consume a third-party API, write a contract test that verifies their response structure.
- [ ] **Break detection in CI** — Contract tests fail when either side changes the structure without agreement.

---


## 192. Snapshot Testing

- [ ] **Snapshot tests capture expected output** — Useful for complex JSON responses, HTML output, or configuration.

```php
it('generates the expected trial balance', function () {
    // ... setup ...
    $result = (new TrialBalanceQuery())->execute($period);
    expect($result->toArray())->toMatchSnapshot();
});
```

- [ ] **Review snapshot changes in PRs** — Snapshot updates should be intentional, not accidental.
- [ ] **Don't snapshot volatile data** — Timestamps, UUIDs, random values. Normalize before snapshotting.
- [ ] **Snapshot granularity** — Snapshot the structure, not the exact values. Use `toMatchJsonSnapshot()` for JSON.
- [ ] **Update snapshots deliberately** — `--update-snapshots` flag. Never auto-update in CI.
- [ ] **Use for regression detection** — The snapshot is a baseline. Any change requires review.

---


## 193. Load & Stress Testing

- [ ] **Define performance targets** — P95 response time < 200ms, throughput > 100 req/s, error rate < 0.1%.
- [ ] **Use k6, Artillery, or JMeter** — Not `ab` (too simplistic for real-world testing).

```javascript
// k6 script
import http from 'k6/http';
import { check, sleep } from 'k6';

export const options = {
    vus: 50,
    duration: '5m',
    thresholds: { http_req_duration: ['p(95)<200'] },
};

export default function () {
    const res = http.get('https://app.test/api/trial-balance');
    check(res, { 'status is 200': (r) => r.status === 200 });
    sleep(1);
}
```

- [ ] **Test with realistic data** — An empty database performs differently from 10M rows.
- [ ] **Test under concurrent load** — 50-100 virtual users hitting the same endpoints simultaneously.
- [ ] **Identify breaking points** — Gradually increase load until the system degrades. Know your limits.
- [ ] **Profile under load** — Use Xdebug profiler or Blackfire to find bottlenecks under realistic conditions.
- [ ] **Load test in a staging environment** — Never load test production unless you control the blast radius.
- [ ] **Monitor database during load tests** — Slow queries, connection exhaustion, lock contention.

---


## 194. Test Data Management & Factories

- [ ] **Factories for every model** — `php artisan make:factory` for each model.
- [ ] **Factory states for common scenarios** — `->posted()`, `->draft()`, `->approved()`.

```php
class JournalEntryFactory extends Factory
{
    public function posted(): self
    {
        return $this->state(fn () => [
            'status' => JournalEntryStatus::Posted,
            'posted_at' => now(),
        ]);
    }

    public function withLines(int $count = 2): self
    {
        return $this->has(JournalLine::factory()->count($count));
    }
}
```

- [ ] **Factories create valid data by default** — The base factory state should pass all validation rules.
- [ ] **Use `Sequence` for varied data** — `JournalLine::factory()->count(4)->sequence(fn ($seq) => [...])`.
- [ ] **Don't use `create()` when `make()` suffices** — `make()` is faster (no database hit).
- [ ] **Seeders for development data** — Realistic data volumes for manual testing and demos.
- [ ] **Database cleaner between tests** — `RefreshDatabase` or `LazilyRefreshDatabase` trait.
- [ ] **Factory relationships** — Use `for()` and `has()`: `JournalEntry::factory()->has(JournalLine::factory()->count(4))->create()`.

---


## 195. Flaky Test Prevention

- [ ] **No time-dependent tests** — Use `Carbon::setTestNow()` or `$this->freezeTime()`.

```php
it('expires after 24 hours', function () {
    $this->freezeTime();
    $token = Token::factory()->create(['created_at' => now()]);

    $this->travel(25)->hours();

    expect($token->fresh()->isExpired())->toBeTrue();
});
```

- [ ] **No order-dependent tests** — Each test creates its own data. Never depend on another test's side effects.
- [ ] **No random data in assertions** — If a factory uses `fake()->word()`, assert structure, not specific values.
- [ ] **Retry flaky tests in CI (temporarily)** — `--retry=1` while you fix the root cause. Don't leave it permanently.
- [ ] **Isolate external dependencies** — Mock HTTP clients, queue drivers, mail. Use `Http::fake()`.
- [ ] **Database isolation** — `RefreshDatabase` resets between tests. Don't rely on shared state.
- [ ] **Deterministic IDs** — If test order matters due to auto-increment IDs, use UUIDs or explicit IDs.
- [ ] **Run tests 10x locally** — `for i in {1..10}; do php artisan test --compact; done`. If any run fails, the test is flaky.

---


## 196. Code Coverage Strategy

- [ ] **Set a minimum coverage threshold** — 80% line coverage as a baseline for most projects.
- [ ] **Coverage on critical code is higher** — Actions, rules, validators: 95%+. Controllers, seeders: lower is acceptable.
- [ ] **Don't chase 100%** — 100% coverage doesn't mean 100% correct. Focus on meaningful assertions.
- [ ] **Branch coverage over line coverage** — Uncovered branches (else paths, catch blocks) are more dangerous than uncovered lines.
- [ ] **Coverage ratchet** — Coverage can only increase, never decrease. CI fails if coverage drops.
- [ ] **Exclude generated code** — Migrations, IDE helpers, config files. Configure in `phpunit.xml`:

```xml
<coverage>
    <include>
        <directory suffix=".php">app</directory>
    </include>
    <exclude>
        <directory>app/Console/Commands</directory>
    </exclude>
</coverage>
```

- [ ] **Visual coverage reports** — `php artisan test --coverage --min=80` shows uncovered lines.
- [ ] **Use mutation testing (§190) as a coverage quality check** — High coverage with low MSI means tests aren't asserting enough.

---


---

[← Previous Part](10-frontend-engineering.md) | [Full Checklist](../checklist.md) | [Next Part →](12-apis-queues-integration.md)
