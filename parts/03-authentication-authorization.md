[← Previous Part](02-cryptography-data-protection.md) | [Full Checklist](../checklist.md) | [Next Part →](04-data-integrity-concurrency.md)

# Part III — Authentication & Authorization

**13 sections · 110 checks**

- [3. Authentication & Authorization](#3-authentication-authorization)
- [4. Mass Assignment & Sensitive Fields](#4-mass-assignment-sensitive-fields)
- [81. Policy Design Patterns](#81-policy-design-patterns)
- [82. Gate & Authorization Edge Cases](#82-gate-authorization-edge-cases)
- [89. Rate Limiting Deep Dive](#89-rate-limiting-deep-dive)
- [119. OAuth 2.0 & OpenID Connect](#119-oauth-20-openid-connect)
- [120. JWT Security Hardening](#120-jwt-security-hardening)
- [121. API Key Lifecycle Management](#121-api-key-lifecycle-management)
- [122. Session Fixation & Hijacking Deep Dive](#122-session-fixation-hijacking-deep-dive)
- [123. Bot Detection & Brute Force Mitigation](#123-bot-detection-brute-force-mitigation)
- [124. RBAC vs ABAC Design Patterns](#124-rbac-vs-abac-design-patterns)
- [125. Privilege Escalation Prevention](#125-privilege-escalation-prevention)
- [126. Impersonation Safety](#126-impersonation-safety)

---

## 3. Authentication & Authorization

### Authorization Checks

- [ ] **Every controller method must authorize** — Use `$this->authorize('action', $model)`, policy middleware, or `can` middleware.
- [ ] **Pass model instances to `authorize()`, not class strings** — `$this->authorize('update', $post)` not `$this->authorize('update', Post::class)`.
- [ ] **Use `Gate::allows()` / `$user->can()` for non-controller checks** — Actions, jobs, and services should verify authorization.
- [ ] **Scope queries to the authenticated user** — `Model::where('user_id', auth()->id())` or use a global scope. Never rely on URL-based access control alone.

```php
// SAFE — scoped to user
AiInteraction::where('user_id', auth()->id())->findOrFail($id);

// DANGEROUS — any user can access any record
AiInteraction::findOrFail($id);
```

- [ ] **Use `can()` not `hasPermissionTo()`** — `can()` flows through `Gate::before` (super-admin bypass). Spatie's `hasPermissionTo()` does not.

### Permission Design

- [ ] **Permissions are granular, not boolean** — `manage-users` is too broad. Use `view-users`, `create-users`, `update-users`, `delete-users`.
- [ ] **Roles contain permissions, not logic** — Never `if ($user->role === 'admin')`. Use `if ($user->can('update-posts'))`.
- [ ] **Super-admin bypass uses `Gate::before`** — Not hardcoded checks in every controller.

```php
// AppServiceProvider or AuthServiceProvider
Gate::before(function (User $user, string $ability) {
    if ($user->hasRole('super_admin')) {
        return true;
    }
});
```

- [ ] **Protect super-admin from modification** — Super-admin role must be immutable. No user can assign, revoke, or edit it via the UI.
- [ ] **Define permissions in an enum, not as raw strings** — Prevents typos, enables IDE autocompletion, and makes refactoring safe.

### Segregation of Duties

- [ ] **Creator cannot approve their own resource** — In maker-checker workflows, `created_by !== approved_by`.
- [ ] **Enforce SoD inside DB transactions** — Check SoD after `lockForUpdate()`, not before, to prevent TOCTOU.
- [ ] **Document accepted SoD bypasses** — System-generated / automated entries that skip SoD must be documented as architectural decisions.

---


## 4. Mass Assignment & Sensitive Fields

### Fillable vs Guarded

- [ ] **Every model declares explicit `$fillable`** — List only the fields that should be mass-assignable.
- [ ] **Never use `$guarded = []`** — This makes all columns mass-assignable, including `id`, `is_admin`, `role`, etc.
- [ ] **Never call `Model::unguard()`** — Disables mass-assignment protection globally. Only acceptable in seeders via `Model::unguard()` wrapped in `Model::reguard()`.

```php
// SAFE
protected $fillable = ['name', 'email', 'bio'];

// DANGEROUS
protected $guarded = [];     // Everything is fillable
protected $guarded = ['id']; // Everything except id — still too permissive
```

### Sensitive Fields That Must NOT Be Fillable

These fields should never appear in `$fillable`. Set them explicitly in code:

```php
// User model — these must be set via dedicated methods, never mass-assigned
// NEVER put these in $fillable:
'password',              // Set via Hash::make() in dedicated action
'email_verified_at',     // Set via markEmailAsVerified()
'remember_token',        // Managed by Laravel auth
'two_factor_secret',     // Set via Fortify 2FA flow
'two_factor_recovery_codes',
'two_factor_confirmed_at',
'is_admin',              // Set via role assignment
'role',                  // Set via Spatie or custom role system
'permissions',           // Managed via permission system
'api_token',             // Generated via createToken()
'stripe_id',             // Managed by Cashier
'trial_ends_at',         // Managed by subscription logic
```

- [ ] **Audit every model's `$fillable` for privilege escalation fields** — Can a user promote themselves to admin? Can they change their email_verified_at? Can they set another user's ID as a foreign key?
- [ ] **Use `$request->validated()` or `$request->safe()->only([...])`** — Never `$request->all()` or `$request->input()` for mass creation.

```php
// SAFE
$user = User::create($request->validated());
$user = User::create($request->safe()->only(['name', 'email']));

// DANGEROUS
$user = User::create($request->all());         // All request data
$user->forceFill($request->all())->save();      // Bypasses guarded
User::forceCreate($request->all());             // Bypasses guarded
```

### Hidden & Appended Fields

- [ ] **`$hidden` on sensitive fields** — Prevent passwords, tokens, secrets from appearing in JSON/array serialization.

```php
protected $hidden = [
    'password',
    'remember_token',
    'two_factor_secret',
    'two_factor_recovery_codes',
];
```

- [ ] **Never expose internal IDs unnecessarily** — Use UUIDs for public-facing resources. Auto-increment IDs enable enumeration attacks.

---


## 81. Policy Design Patterns

- [ ] **One policy per model** — `OrderPolicy` for `Order`, `UserPolicy` for `User`.
- [ ] **Policy methods mirror controller methods** — `viewAny`, `view`, `create`, `update`, `delete`, `restore`, `forceDelete`.
- [ ] **Check ownership AND permission** — `return $user->id === $order->user_id && $user->can('update-orders')`.
- [ ] **`before()` for blanket overrides** — Super-admin, banned users.
- [ ] **Return `null` from `before()` to fall through** — Not `false` (which denies explicitly).
- [ ] **Don't call other policies from within a policy** — Policies should be self-contained.
- [ ] **Test policies in isolation** — `$this->assertTrue((new OrderPolicy)->update($user, $order))`.

---


## 82. Gate & Authorization Edge Cases

- [ ] **`Gate::before` runs before every check** — Return `true` to allow, `false` to deny, `null` to continue to policy.
- [ ] **`Gate::after` runs after policy** — For logging, not for overriding decisions.
- [ ] **Guest users** — Nullable `$user` in gate callbacks. `Gate::define('view', fn (?User $user) => true)`.
- [ ] **`authorize()` throws `AuthorizationException`** — Caught by exception handler, returns 403.
- [ ] **`can()` returns boolean** — Use in conditionals. `authorize()` in controllers.
- [ ] **Resource authorization** — `$this->authorizeResource(Order::class, 'order')` in controller constructor auto-maps methods.
- [ ] **Response-based authorization** — `Gate::inspect('update', $order)` returns `Response` with message.

---


## 89. Rate Limiting Deep Dive

### Granular Limiters

- [ ] **Per-user rate limits for authenticated routes** — `Limit::perMinute(60)->by($request->user()->id)`.
- [ ] **Per-IP rate limits for unauthenticated routes** — `Limit::perMinute(10)->by($request->ip())`.
- [ ] **Per-route rate limits** — Login (5/min), API (60/min), search (20/min), export (5/hour).
- [ ] **Compound keys** — `$request->input('email') . '|' . $request->ip()` for login to prevent distributed attacks.

```php
RateLimiter::for('login', function (Request $request) {
    return [
        Limit::perMinute(5)->by($request->input('email')),
        Limit::perMinute(15)->by($request->ip()),
    ];
});
```

### Response Headers

- [ ] **Include `X-RateLimit-Limit` header** — Tells clients their limit.
- [ ] **Include `X-RateLimit-Remaining` header** — Tells clients remaining requests.
- [ ] **Include `Retry-After` header on 429** — Tells clients when to retry.

### Anti-Abuse

- [ ] **Rate limit failed attempts more aggressively** — `Limit::perMinute(5)->by($request->ip())->response(fn () => abort(429))`.
- [ ] **Progressive rate limiting** — After 5 failed logins, increase delay exponentially.
- [ ] **Rate limit by feature, not just endpoint** — AI features, export features, PDF generation.

---


## 119. OAuth 2.0 & OpenID Connect

- [ ] **Use PKCE for all OAuth flows** — Even for server-side apps. `code_challenge_method=S256`.
- [ ] **Validate `state` parameter** — Prevents CSRF on the OAuth callback.
- [ ] **Validate `id_token` signature** — Verify JWT against the provider's JWKS endpoint.
- [ ] **Validate `iss` (issuer) and `aud` (audience) claims** — Reject tokens not intended for your application.
- [ ] **Short-lived access tokens** — 15-60 minutes. Use refresh tokens for long sessions.
- [ ] **Store refresh tokens encrypted** — They are Tier 4 data.
- [ ] **Revoke tokens on logout** — Call the provider's revocation endpoint.
- [ ] **Validate `nonce` claim** — Prevents replay of `id_token`.
- [ ] **Scope minimization** — Request only the scopes you need (`openid email profile`, not `admin`).
- [ ] **Token introspection for opaque tokens** — If using opaque tokens, validate via the introspection endpoint, not by decoding.

---


## 120. JWT Security Hardening

- [ ] **Always verify the signature** — Never use `alg: none`.

```php
// DANGEROUS — accepts unsigned tokens
$payload = json_decode(base64_decode(explode('.', $jwt)[1]));

// SAFE — verify with a library
$decoded = Firebase\JWT\JWT::decode($jwt, new Key($publicKey, 'RS256'));
```

- [ ] **Pin the algorithm server-side** — Don't trust the `alg` header from the token.
- [ ] **Validate `exp`, `iat`, `nbf` claims** — Reject expired or future-dated tokens.
- [ ] **Short expiration** — Access tokens: 15 minutes. Refresh tokens: 7-30 days.
- [ ] **`jti` claim for revocation** — Store revoked `jti` values in a blocklist (Redis with TTL matching token expiry).
- [ ] **Asymmetric keys for distributed systems** — RS256 or ES256, not HS256 (symmetric shared secret).
- [ ] **No sensitive data in JWT payload** — JWTs are base64, not encrypted. Don't embed PII.
- [ ] **Rotate signing keys periodically** — Publish old public keys for a transition period via JWKS endpoint.
- [ ] **`kid` (Key ID) header** — Allows key rotation without breaking existing tokens.

---


## 121. API Key Lifecycle Management

- [ ] **Hash API keys before storage** — Store `hash('sha256', $key)`. Compare with `hash_equals()`.

```php
// On creation
$plaintext = Str::random(64);
$model->api_key_hash = hash('sha256', $plaintext);
// Return $plaintext to user ONCE, never again

// On authentication
$hash = hash('sha256', $request->bearerToken());
$model = ApiKey::where('key_hash', $hash)->first();
```

- [ ] **Prefix keys for identification** — `sk_live_*`, `sk_test_*` — so leaked keys are instantly identifiable.
- [ ] **Expiration dates on all keys** — No perpetual keys. Enforce renewal.
- [ ] **Scope/permission per key** — Each key has specific capabilities, not full account access.
- [ ] **Rate limit per key** — Not just per IP. Abused keys get throttled independently.
- [ ] **Revocation is instant** — Deleting a key row immediately invalidates it. No cache lag.
- [ ] **Audit log per key** — Every action performed with a key is logged with the key ID.
- [ ] **Key rotation without downtime** — Allow two active keys simultaneously during rotation window.

---


## 122. Session Fixation & Hijacking Deep Dive

- [ ] **`Session::regenerate()` on login** — Laravel does this by default with Fortify/Breeze. Verify custom auth flows.
- [ ] **`Session::invalidate()` on logout** — Destroys session data and regenerates ID.
- [ ] **Session ID not in URL** — `session.use_only_cookies = 1` in PHP config.
- [ ] **`HttpOnly` flag on session cookie** — Prevents JavaScript access. Laravel sets this by default.
- [ ] **`Secure` flag on session cookie** — Only sent over HTTPS. Set `SESSION_SECURE_COOKIE=true`.
- [ ] **`SameSite=Lax` or `Strict`** — Prevents CSRF via cross-site session riding.
- [ ] **Session timeout** — `SESSION_LIFETIME=120` (minutes). Shorter for financial applications.
- [ ] **Absolute session timeout** — Even active sessions expire after a maximum duration (e.g., 8 hours).
- [ ] **Concurrent session limits** — Detect and optionally restrict multiple active sessions per user.
- [ ] **Bind session to user-agent or IP** — Optional: invalidate sessions when these change (beware mobile networks).

---


## 123. Bot Detection & Brute Force Mitigation

- [ ] **Rate limit login attempts** — Laravel's `ThrottleRequests` middleware: `throttle:5,1` (5 attempts per minute).

```php
// In routes
Route::post('/login', LoginController::class)
    ->middleware('throttle:5,1');
```

- [ ] **Progressive delays** — After N failures, add exponential backoff. `RateLimiter::for('login', ...)`.
- [ ] **Account lockout after threshold** — Lock account for 15-30 minutes after 10 consecutive failures.
- [ ] **CAPTCHA on repeated failures** — Show CAPTCHA after 3 failed attempts, not on first visit.
- [ ] **IP-based rate limiting** — But don't rely solely on IP (NAT, VPNs, Tor).
- [ ] **Credential stuffing detection** — Monitor for distributed low-volume attacks across many accounts from many IPs.
- [ ] **Honeypot fields** — Hidden form fields that bots fill but humans don't.

```html
<input type="text" name="website" style="display: none" tabindex="-1" autocomplete="off">
```

- [ ] **Timing-safe responses** — Login failure and success should take similar time. `Hash::check()` runs even for non-existent users.

---


## 124. RBAC vs ABAC Design Patterns

- [ ] **RBAC (Role-Based Access Control)** — Assign permissions to roles, assign roles to users. Best for most applications.

```php
// Check role
$user->hasRole('accountant');

// Check permission (preferred — more granular)
$user->can('journal-entries.approve');
```

- [ ] **ABAC (Attribute-Based Access Control)** — Decisions based on user attributes, resource attributes, and context. Use for complex rules.

```php
// ABAC-style: policy checks resource attributes
public function approve(User $user, JournalEntry $entry): bool
{
    return $user->can('journal-entries.approve')
        && $entry->status === JournalEntryStatus::Pending
        && $entry->created_by !== $user->id;  // SoD
}
```

- [ ] **Don't mix raw role checks with permission checks** — Use `$user->can('action')`, not `$user->hasRole('admin')` in business logic.
- [ ] **Permissions are the atoms, roles are groups** — Check permissions, not roles, in policies and gates.
- [ ] **Avoid role proliferation** — If you have 50+ roles, consider ABAC or permission-based logic.
- [ ] **Document the permission matrix** — Table showing which roles have which permissions.

---


## 125. Privilege Escalation Prevention

- [ ] **Users cannot modify their own roles** — Role assignment requires a different, authorized user.
- [ ] **Users cannot assign roles higher than their own** — A `manager` cannot assign `super-admin`.

```php
public function assignRole(User $actor, User $target, string $role): void
{
    if (! $actor->can('roles.assign')) {
        throw new AuthorizationException();
    }
    // Prevent escalation
    $assignableRoles = $this->rolesAssignableBy($actor);
    if (! in_array($role, $assignableRoles)) {
        throw new AuthorizationException('Cannot assign a role above your own');
    }
    $target->assignRole($role);
}
```

- [ ] **Admin panel access is audited** — Every admin action is logged.
- [ ] **`SuperAdmin` bypass is minimal** — Only for emergencies, time-boxed, and audit-logged.
- [ ] **No hidden admin routes** — `/admin`, `/secret-panel` — all behind proper auth middleware and policies.
- [ ] **Horizontal privilege escalation** — Users cannot access other users' resources. Always scope queries: `Order::where('user_id', auth()->id())`.
- [ ] **Vertical privilege escalation** — Regular users cannot call admin endpoints. Middleware + policy double-check.

---


## 126. Impersonation Safety

- [ ] **Impersonation requires explicit permission** — `$user->can('users.impersonate')`.
- [ ] **Cannot impersonate higher-privileged users** — Admin can impersonate regular users, not super-admins.
- [ ] **Store original user ID in session** — `session(['impersonator_id' => auth()->id()])`.
- [ ] **Visual indicator when impersonating** — A persistent banner: "You are impersonating User X. [Stop]".
- [ ] **Impersonation is audit-logged** — Log start, stop, and all actions during impersonation.
- [ ] **Impersonated sessions cannot change security settings** — No password changes, no 2FA changes, no role changes.
- [ ] **Auto-expire impersonation** — Maximum duration (e.g., 1 hour), then automatically revert.
- [ ] **Impersonation cannot be chained** — An impersonated user cannot impersonate another user.

---


---

[← Previous Part](02-cryptography-data-protection.md) | [Full Checklist](../checklist.md) | [Next Part →](04-data-integrity-concurrency.md)
