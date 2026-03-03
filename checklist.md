# The Laravel Fortress

### A Comprehensive Engineering Standards Manual

> **2,000+ checks across 200 sections, organized into 14 parts.** The definitive reference for building Laravel applications that are **secure by default**, **correct under concurrency**, **auditable end-to-end**, and **maintainable at scale**.

**What following this achieves:**

- **Security** — Survives OWASP Top 10 penetration tests, closes injection vectors, hardens authentication and authorization, and satisfies SOC 2 / ISO 27001 audit requirements.
- **Correctness** — Eliminates floating-point money bugs, race conditions, state machine bypasses, and silent data corruption. Every financial operation is transactional, idempotent, and reconcilable.
- **Auditability** — Produces a complete, tamper-evident audit trail from user action to ledger entry. Every state change is logged, every approval is enforced, every reversal is traceable.
- **Maintainability** — Enforces consistent patterns (actions, DTOs, enums, value objects, form requests) that let new developers navigate the codebase in hours, not weeks. Cognitive complexity stays low; dead code stays gone.
- **Resilience** — Handles deployment without downtime, recovers from failures with clear runbooks, and scales under load without data loss or silent degradation.

This is not a style guide — it is an **engineering discipline framework** applicable to any Laravel project.

**Scope**: Laravel 11/12 · PHP 8.3/8.4 · MySQL / PostgreSQL · Vue 3 / Inertia v2 · Tailwind CSS 4 · Redis · Pest / PHPUnit

**Last updated**: 2026-03-03

---

## Table of Contents

> Browse individual parts in the [`parts/`](parts/) directory.

### Part I — Application Security ([browse](parts/01-application-security.md))
1. [Security — OWASP & Laravel-Specific](#1-security--owasp--laravel-specific)
19. [API Security](#19-api-security)
20. [Session & Cookie Security](#20-session--cookie-security)
21. [File Upload & Storage Security](#21-file-upload--storage-security)
27. [Route & Middleware Security](#27-route--middleware-security)
28. [Serialization & Object Injection](#28-serialization--object-injection)
29. [Email Security](#29-email-security)
33. [Blade, View & Component Security](#33-blade-view--component-security)
39. [Open Redirect & IDOR Prevention](#39-open-redirect--idor-prevention)
44. [CSV & Spreadsheet Export Injection](#44-csv--spreadsheet-export-injection)
49. [Broadcasting & WebSocket Security](#49-broadcasting--websocket-security)
52. [DNS Rebinding & Host Header Attacks](#52-dns-rebinding--host-header-attacks)
101. [Path Traversal & Directory Escape](#101-path-traversal--directory-escape)
102. [XML External Entity (XXE) Prevention](#102-xml-external-entity-xxe-prevention)
103. [HTTP Request Smuggling](#103-http-request-smuggling)
104. [Clickjacking & UI Redress](#104-clickjacking--ui-redress)
105. [Subdomain Takeover Prevention](#105-subdomain-takeover-prevention)
106. [Server-Side Request Forgery (SSRF) Deep Dive](#106-server-side-request-forgery-ssrf-deep-dive)
107. [Content Security Policy (CSP) Engineering](#107-content-security-policy-csp-engineering)
108. [HTTP Security Headers Checklist](#108-http-security-headers-checklist)
109. [CORS Misconfiguration](#109-cors-misconfiguration)
110. [Supply Chain Attack Vectors (Frontend)](#110-supply-chain-attack-vectors-frontend)

### Part II — Cryptography & Data Protection ([browse](parts/02-cryptography-data-protection.md))
2. [Hashing, Encryption & Cryptography](#2-hashing-encryption--cryptography)
83. [Password & Credential Management](#83-password--credential-management)
84. [Two-Factor Authentication (2FA) Depth](#84-two-factor-authentication-2fa-depth)
85. [Logging Sensitive Data Prevention](#85-logging-sensitive-data-prevention)
111. [Key Management & Rotation](#111-key-management--rotation)
112. [TLS & Certificate Management](#112-tls--certificate-management)
113. [Data Classification & Handling Tiers](#113-data-classification--handling-tiers)
114. [PII Detection & Anonymization](#114-pii-detection--anonymization)
115. [Tokenization & Data Masking](#115-tokenization--data-masking)
116. [At-Rest Encryption Strategies](#116-at-rest-encryption-strategies)
117. [Secrets Management (Vault / Cloud)](#117-secrets-management-vault--cloud)
118. [Cryptographic Agility](#118-cryptographic-agility)

### Part III — Authentication & Authorization ([browse](parts/03-authentication-authorization.md))
3. [Authentication & Authorization](#3-authentication--authorization)
4. [Mass Assignment & Sensitive Fields](#4-mass-assignment--sensitive-fields)
81. [Policy Design Patterns](#81-policy-design-patterns)
82. [Gate & Authorization Edge Cases](#82-gate--authorization-edge-cases)
89. [Rate Limiting Deep Dive](#89-rate-limiting-deep-dive)
119. [OAuth 2.0 & OpenID Connect](#119-oauth-20--openid-connect)
120. [JWT Security Hardening](#120-jwt-security-hardening)
121. [API Key Lifecycle Management](#121-api-key-lifecycle-management)
122. [Session Fixation & Hijacking Deep Dive](#122-session-fixation--hijacking-deep-dive)
123. [Bot Detection & Brute Force Mitigation](#123-bot-detection--brute-force-mitigation)
124. [RBAC vs ABAC Design Patterns](#124-rbac-vs-abac-design-patterns)
125. [Privilege Escalation Prevention](#125-privilege-escalation-prevention)
126. [Impersonation Safety](#126-impersonation-safety)

### Part IV — Data Integrity & Concurrency ([browse](parts/04-data-integrity-concurrency.md))
5. [Concurrency, Transactions & Race Conditions](#5-concurrency-transactions--race-conditions)
36. [State Machine Integrity](#36-state-machine-integrity)
91. [Transaction Reference & Idempotency Patterns](#91-transaction-reference--idempotency-patterns)
92. [Approval Workflow Integrity](#92-approval-workflow-integrity)
127. [Optimistic Locking Patterns](#127-optimistic-locking-patterns)
128. [Distributed Lock Strategies](#128-distributed-lock-strategies)
129. [Saga & Compensation Patterns](#129-saga--compensation-patterns)
130. [Event Ordering & Causality](#130-event-ordering--causality)
131. [Idempotency at Every Layer](#131-idempotency-at-every-layer)
132. [Eventual Consistency Handling](#132-eventual-consistency-handling)
133. [Conflict Resolution Strategies](#133-conflict-resolution-strategies)

### Part V — Financial & Monetary Correctness ([browse](parts/05-financial-monetary-correctness.md))
6. [Money, Arithmetic & Precision](#6-money-arithmetic--precision)
134. [Multi-Currency Translation (IAS 21)](#134-multi-currency-translation-ias-21)
135. [Rounding Policy Registry](#135-rounding-policy-registry)
136. [Reconciliation Algorithm Patterns](#136-reconciliation-algorithm-patterns)
137. [Financial Period Close Safety](#137-financial-period-close-safety)
138. [Ledger Immutability Enforcement](#138-ledger-immutability-enforcement)
139. [Inter-Company & Elimination Entries](#139-inter-company--elimination-entries)
140. [Regulatory Compliance Checks (SOX / IFRS)](#140-regulatory-compliance-checks-sox--ifrs)

### Part VI — PHP Language & Type Safety ([browse](parts/06-php-language-type-safety.md))
7. [Modern PHP Syntax (8.3/8.4)](#7-modern-php-syntax-8384)
8. [Strict Typing & Type Safety](#8-strict-typing--type-safety)
30. [Regex Safety (ReDoS)](#30-regex-safety-redos)
31. [Date, Time & Timezone Safety](#31-date-time--timezone-safety)
40. [PHP Configuration Security](#40-php-configuration-security)
63. [PHP Deprecation Awareness](#63-php-deprecation-awareness)
71. [Enum Best Practices (Deep Dive)](#71-enum-best-practices-deep-dive)
141. [Fiber & Async Patterns](#141-fiber--async-patterns)
142. [Readonly Classes & Properties Deep Dive](#142-readonly-classes--properties-deep-dive)
143. [First-Class Callable Syntax](#143-first-class-callable-syntax)
144. [Modern Array Functions](#144-modern-array-functions)
145. [Attribute-Based Programming](#145-attribute-based-programming)
146. [Intersection & DNF Types](#146-intersection--dnf-types)
147. [Property Hooks (PHP 8.4)](#147-property-hooks-php-84)
148. [Asymmetric Visibility (PHP 8.4)](#148-asymmetric-visibility-php-84)

### Part VII — Clean Code & Software Design ([browse](parts/07-clean-code-software-design.md))
9. [Clean Code & SOLID Principles](#9-clean-code--solid-principles)
57. [Code Readability & Cognitive Complexity](#57-code-readability--cognitive-complexity)
72. [Interface & Contract Design](#72-interface--contract-design)
73. [Trait Hygiene](#73-trait-hygiene)
94. [Domain Event Design](#94-domain-event-design)
95. [Value Object Contract Enforcement](#95-value-object-contract-enforcement)
149. [CQRS Pattern Implementation](#149-cqrs-pattern-implementation)
150. [Repository Pattern (When Appropriate)](#150-repository-pattern-when-appropriate)
151. [Bounded Context & Module Boundaries](#151-bounded-context--module-boundaries)
152. [Pipeline Pattern](#152-pipeline-pattern)
153. [Specification Pattern for Business Rules](#153-specification-pattern-for-business-rules)
154. [Null Object Pattern](#154-null-object-pattern)
155. [Strategy Pattern for Variant Logic](#155-strategy-pattern-for-variant-logic)
156. [Builder Pattern for Complex Construction](#156-builder-pattern-for-complex-construction)
157. [Immutability as a Default](#157-immutability-as-a-default)
158. [Domain Primitives & Micro-Types](#158-domain-primitives--micro-types)

### Part VIII — Laravel Framework Mastery ([browse](parts/08-laravel-framework-mastery.md))
10. [Laravel Anti-Patterns](#10-laravel-anti-patterns)
11. [Validation & Input Handling](#11-validation--input-handling)
24. [Error Handling & Exception Design](#24-error-handling--exception-design)
25. [Eloquent Model Safety](#25-eloquent-model-safety)
26. [Caching Safety](#26-caching-safety)
34. [Event & Listener Safety](#34-event--listener-safety)
35. [Service Provider Hygiene](#35-service-provider-hygiene)
74. [Config File Organization](#74-config-file-organization)
75. [Middleware Authoring Pitfalls](#75-middleware-authoring-pitfalls)
76. [Form Request Advanced Patterns](#76-form-request-advanced-patterns)
77. [Eloquent Observer & Event Ordering](#77-eloquent-observer--event-ordering)
79. [Artisan Make Command Hygiene](#79-artisan-make-command-hygiene)
80. [Seeder & Factory Safety](#80-seeder--factory-safety)
159. [Custom Eloquent Casts](#159-custom-eloquent-casts)
160. [Service Container Advanced Bindings](#160-service-container-advanced-bindings)
161. [Macros & Mixins Discipline](#161-macros--mixins-discipline)
162. [Route Model Binding Advanced Patterns](#162-route-model-binding-advanced-patterns)
163. [Laravel Prompts for CLI UX](#163-laravel-prompts-for-cli-ux)
164. [Custom Validation Rules](#164-custom-validation-rules)
165. [Notification Architecture](#165-notification-architecture)
166. [Eloquent Accessor & Mutator Hygiene](#166-eloquent-accessor--mutator-hygiene)
167. [Model Event Lifecycle Awareness](#167-model-event-lifecycle-awareness)
168. [Action Pattern Discipline](#168-action-pattern-discipline)

### Part IX — Database Engineering ([browse](parts/09-database-engineering.md))
12. [Database, Migrations & Query Safety](#12-database-migrations--query-safety)
41. [Eloquent Relationship & Polymorphic Safety](#41-eloquent-relationship--polymorphic-safety)
42. [Pagination & Unbounded Query Prevention](#42-pagination--unbounded-query-prevention)
54. [Zero-Downtime Migration Safety](#54-zero-downtime-migration-safety)
55. [Collection & Array Safety](#55-collection--array-safety)
64. [Eloquent Query Scoping & Filters](#64-eloquent-query-scoping--filters)
65. [Soft Delete Pitfalls](#65-soft-delete-pitfalls)
78. [JSON Column Safety](#78-json-column-safety)
90. [UUID & ULID Best Practices](#90-uuid--ulid-best-practices)
169. [Schema Design Patterns](#169-schema-design-patterns)
170. [MySQL Strict Mode & sql_mode](#170-mysql-strict-mode--sql_mode)
171. [Read Replica & Connection Splitting](#171-read-replica--connection-splitting)
172. [Index Optimization & EXPLAIN](#172-index-optimization--explain)
173. [Database Partitioning Strategies](#173-database-partitioning-strategies)
174. [Connection Pooling & Limits](#174-connection-pooling--limits)
175. [Migration Rollback Safety](#175-migration-rollback-safety)
176. [Slow Query Detection & Monitoring](#176-slow-query-detection--monitoring)
177. [Foreign Key Cascade Strategy](#177-foreign-key-cascade-strategy)
178. [Composite Key & Unique Constraint Pitfalls](#178-composite-key--unique-constraint-pitfalls)

### Part X — Frontend Engineering ([browse](parts/10-frontend-engineering.md))
13. [Frontend Security & Quality](#13-frontend-security--quality)
43. [Internationalization & Encoding Safety](#43-internationalization--encoding-safety)
60. [Accessibility (A11y) Baseline](#60-accessibility-a11y-baseline)
61. [Inertia.js / SPA-Specific Security](#61-inertiajs--spa-specific-security)
96. [TypeScript & Frontend Type Safety](#96-typescript--frontend-type-safety)
97. [Vue Component Patterns](#97-vue-component-patterns)
98. [Tailwind CSS Hygiene](#98-tailwind-css-hygiene)
179. [Bundle Size & Tree Shaking](#179-bundle-size--tree-shaking)
180. [Core Web Vitals Optimization](#180-core-web-vitals-optimization)
181. [SPA State Management Patterns](#181-spa-state-management-patterns)
182. [Form UX & Validation Patterns](#182-form-ux--validation-patterns)
183. [Error Boundaries & Fallback UI](#183-error-boundaries--fallback-ui)
184. [Progressive Enhancement](#184-progressive-enhancement)
185. [Keyboard Navigation & Focus Management](#185-keyboard-navigation--focus-management)
186. [Frontend Error Tracking](#186-frontend-error-tracking)
187. [Image Optimization & Lazy Loading](#187-image-optimization--lazy-loading)
188. [Dark Mode Implementation](#188-dark-mode-implementation)

### Part XI — Testing & Quality Assurance ([browse](parts/11-testing-quality-assurance.md))
14. [Testing Quality](#14-testing-quality)
189. [Test Doubles: Mocks, Stubs, Fakes, Spies](#189-test-doubles-mocks-stubs-fakes-spies)
190. [Mutation Testing](#190-mutation-testing)
191. [Contract Testing](#191-contract-testing)
192. [Snapshot Testing](#192-snapshot-testing)
193. [Load & Stress Testing](#193-load--stress-testing)
194. [Test Data Management & Factories](#194-test-data-management--factories)
195. [Flaky Test Prevention](#195-flaky-test-prevention)
196. [Code Coverage Strategy](#196-code-coverage-strategy)

### Part XII — APIs, Queues & Integration ([browse](parts/12-apis-queues-integration.md))
22. [Queue & Job Safety](#22-queue--job-safety)
45. [Webhook Receiving & Replay Prevention](#45-webhook-receiving--replay-prevention)
46. [Multi-Tenancy & Data Isolation](#46-multi-tenancy--data-isolation)
47. [Console Command Safety](#47-console-command-safety)
48. [Scheduling & Cron Safety](#48-scheduling--cron-safety)
50. [Notification Channel Safety](#50-notification-channel-safety)
56. [Dependency Injection Discipline](#56-dependency-injection-discipline)
58. [HTTP Client & External API Safety](#58-http-client--external-api-safety)
59. [Feature Flags & Rollout Safety](#59-feature-flags--rollout-safety)
62. [Redis Security](#62-redis-security)
87. [Image & Media Processing Security](#87-image--media-processing-security)
88. [Search & Full-Text Safety](#88-search--full-text-safety)
197. [API Versioning Strategies](#197-api-versioning-strategies)
198. [Circuit Breaker Pattern](#198-circuit-breaker-pattern)
199. [Webhook Sending Best Practices](#199-webhook-sending-best-practices)
200. [Message Queue Reliability Patterns](#200-message-queue-reliability-patterns)

### Part XIII — Logging, Monitoring & Audit ([browse](parts/13-logging-monitoring-audit.md))
16. [Logging, Monitoring & Audit Trails](#16-logging-monitoring--audit-trails)
86. [Third-Party Package Audit](#86-third-party-package-audit)
93. [Audit Trail Completeness](#93-audit-trail-completeness)

### Part XIV — Infrastructure & Operations ([browse](parts/14-infrastructure-operations.md))
15. [Configuration, Secrets & Environment](#15-configuration-secrets--environment)
17. [Dead Code & Unused Artifacts](#17-dead-code--unused-artifacts)
18. [Dependency & Supply Chain Security](#18-dependency--supply-chain-security)
23. [PR / Code Review Checklist (Quick Reference)](#23-pr--code-review-checklist-quick-reference)
32. [Memory, Performance & Resource Exhaustion](#32-memory-performance--resource-exhaustion)
37. [Deployment & Production Hardening](#37-deployment--production-hardening)
38. [Git & Version Control Hygiene](#38-git--version-control-hygiene)
51. [Debug & Development Tool Leakage](#51-debug--development-tool-leakage)
53. [Backup & Disaster Recovery](#53-backup--disaster-recovery)
66. [Environment Parity](#66-environment-parity)
67. [Docker & Container Security](#67-docker--container-security)
68. [PDF Generation Security](#68-pdf-generation-security)
69. [Temporary File & Directory Safety](#69-temporary-file--directory-safety)
70. [Process & Exec Safety](#70-process--exec-safety)
99. [CI/CD Pipeline Checks](#99-cicd-pipeline-checks)
100. [Post-Incident Review Checklist](#100-post-incident-review-checklist)

---

## 1. Security — OWASP & Laravel-Specific

### SQL Injection

- [ ] **Use Eloquent or Query Builder with parameter binding** — Never interpolate user input into raw SQL strings.

```php
// SAFE
User::where('email', $email)->first();
DB::table('users')->where('email', '=', $email)->get();

// SAFE — raw with explicit bindings
User::whereRaw('age > ?', [$request->input('age')])->get();

// DANGEROUS — never do this
DB::select("SELECT * FROM users WHERE email = '$email'");
```

- [ ] **Validate column names in `orderBy()` / `groupBy()`** — User-controlled sort columns must be validated against an allowlist. Spatie Query Builder handles this with `AllowedSort`.

```php
// SAFE — allowlist
$allowed = ['name', 'created_at', 'amount'];
$sortBy = in_array($request->sort, $allowed) ? $request->sort : 'created_at';
Model::query()->orderBy($sortBy)->get();

// DANGEROUS — raw user input in orderBy
Model::query()->orderBy($request->sort)->get();
```

- [ ] **Never pass untrusted input to `Rule::unique()` ignore parameter** — The ignore ID can be manipulated for SQL injection.
- [ ] **Escape LIKE wildcards** — User input used in `LIKE` clauses must escape `%` and `_`.

### XSS (Cross-Site Scripting)

- [ ] **Use `{{ }}` (escaped) in Blade, never `{!! !!}` with untrusted data** — Blade double-braces auto-escape. Triple-braces / `{!! !!}` output raw HTML.
- [ ] **Sanitize rich text with HTMLPurifier** — Use `stevebauman/purify` or equivalent. Never trust user-provided HTML.
- [ ] **Set Content-Security-Policy headers** — Use nonce-based CSP via `spatie/laravel-csp` or custom middleware. Avoid `unsafe-inline`.
- [ ] **Vue/React auto-escapes by default** — But `v-html` / `dangerouslySetInnerHTML` bypass escaping. Never use with user data.

### CSRF

- [ ] **All state-changing routes use POST/PUT/PATCH/DELETE** — Never mutate data via GET requests.
- [ ] **`@csrf` directive in every form** — Laravel's `VerifyCsrfToken` middleware validates automatically.
- [ ] **SPA sends X-XSRF-TOKEN header** — Inertia handles this automatically. For custom AJAX, read from the `XSRF-TOKEN` cookie.

### Command Injection

- [ ] **Use `escapeshellarg()` / `escapeshellcmd()`** — When executing shell commands with user input.
- [ ] **Prefer Laravel Process facade** — `Process::run(['command', $safeArg])` avoids shell interpretation.
- [ ] **Never use `eval()`, `exec()` with unsanitised input, `extract()`, or `unserialize()` on untrusted data**.

### HTTP Security Headers

- [ ] **`X-Frame-Options: DENY`** — Prevent clickjacking.
- [ ] **`X-Content-Type-Options: nosniff`** — Prevent MIME sniffing.
- [ ] **`Strict-Transport-Security: max-age=31536000; includeSubDomains`** — HSTS enforcement.
- [ ] **`Referrer-Policy: strict-origin-when-cross-origin`** — Limit referrer leakage.
- [ ] **`Permissions-Policy: camera=(), microphone=(), geolocation=()`** — Disable unnecessary browser APIs.
- [ ] **Content-Security-Policy** — Nonce-based script policy. No `unsafe-inline`, no `unsafe-eval`.

```php
// Middleware example
public function handle(Request $request, Closure $next): Response
{
    $response = $next($request);

    $response->headers->set('X-Frame-Options', 'DENY');
    $response->headers->set('X-Content-Type-Options', 'nosniff');
    $response->headers->set('Strict-Transport-Security', 'max-age=31536000; includeSubDomains');
    $response->headers->set('Referrer-Policy', 'strict-origin-when-cross-origin');
    $response->headers->set('Permissions-Policy', 'camera=(), microphone=(), geolocation=()');

    return $response;
}
```

### CORS

- [ ] **Never use `'*'` for `allowed_origins` in production** — Specify exact origin(s) in `config/cors.php`.
- [ ] **Limit `allowed_methods` to what the API actually uses** — Don't allow all methods.
- [ ] **Set `supports_credentials: true` only when needed** — Required for cookie-based auth (Sanctum SPA).

### Server-Side Request Forgery (SSRF)

- [ ] **Validate and allowlist URLs before fetching** — When the application fetches user-supplied URLs, restrict to expected domains/ports.
- [ ] **Block internal IP ranges** — Reject `127.0.0.1`, `10.x`, `172.16-31.x`, `192.168.x`, `169.254.x` in user-supplied URLs.

---

## 2. Hashing, Encryption & Cryptography

### Password Hashing

- [ ] **Use `Hash::make()` for passwords** — Never store plaintext passwords. Never use `md5()`, `sha1()`, or `sha256()` for passwords.
- [ ] **Prefer Argon2id over bcrypt** — Argon2id is OWASP's top recommendation: memory-hard, GPU-resistant, no 72-byte truncation.
- [ ] **Tune hash parameters for 200-500ms on your hardware** — bcrypt rounds 12-14, Argon2id memory 64-128MB.

```php
// config/hashing.php
'driver' => 'argon2id',

'argon' => [
    'memory' => 65536,  // 64 MB
    'threads' => 1,
    'time' => 4,
],

'bcrypt' => [
    'rounds' => 12,
],
```

- [ ] **Use `Hash::needsRehash()` for transparent upgrades** — Rehash on login when switching algorithms or raising cost.

```php
if (Hash::needsRehash($user->password)) {
    $user->update(['password' => Hash::make($plaintext)]);
}
```

- [ ] **Never use for passwords**: `md5()`, `sha1()`, `sha256()`, `sha512()`, `crc32()`, `base64_encode()`. These are not password hashing algorithms — they are fast digest functions with no work factor.

### Bcrypt Limitations

- [ ] **bcrypt silently truncates at 72 bytes** — Passwords longer than 72 characters are effectively truncated. If this matters, pre-hash with SHA-256: `Hash::make(hash('sha256', $password))`.
- [ ] **bcrypt has a null byte issue** — A null byte in the password truncates it. Validate passwords contain no null bytes.

### Encryption

- [ ] **`APP_KEY` is critical — rotate periodically** — Use `php artisan key:generate`. The key encrypts all `Crypt::encrypt()` data, sessions (if encrypted), signed cookies, and `encrypted` model casts.
- [ ] **Use `APP_PREVIOUS_KEYS` for key rotation** — Add old keys so existing encrypted data can still be decrypted during migration.
- [ ] **Encrypt sensitive model attributes** — Use `'encrypted'` or `'encrypted:string'` cast for SSNs, API keys, secrets.

```php
protected function casts(): array
{
    return [
        'api_secret' => 'encrypted',
        'ssn' => 'encrypted:string',
    ];
}
```

- [ ] **Use `Crypt::encryptString()` for application-level encryption** — Returns authenticated encryption (AES-256-CBC with HMAC). Never roll your own crypto.
- [ ] **Use `php artisan env:encrypt` for encrypted `.env` in CI/CD** — Eliminates secrets in plain text on deploy targets.

### Tokens & Random Generation

- [ ] **Use `Str::random()` or `random_bytes()` for security tokens** — Both are cryptographically secure.
- [ ] **Never use `rand()`, `mt_rand()`, `array_rand()`, `uniqid()`, `microtime()`, or `md5(time())` for tokens** — These are predictable and not cryptographically secure.

```php
// SAFE
$token = Str::random(64);                // CSPRNG
$token = bin2hex(random_bytes(32));       // CSPRNG
$uuid = Str::uuid();                     // UUID v4 via CSPRNG
$orderedId = Str::orderedUuid();         // UUID v7 (time-sorted)

// DANGEROUS for tokens
$token = md5(time());                    // Predictable
$token = uniqid('', true);              // Time-based
$token = rand(100000, 999999);          // Weak PRNG
```

### Signed URLs & HMAC

- [ ] **Use signed routes for sensitive links** — Password resets, email verification, unsubscribe, download links.
- [ ] **Use temporary signed URLs with short expiration** — `URL::temporarySignedRoute('route', now()->addMinutes(30), $params)`.
- [ ] **Validate webhook signatures with `hash_equals()`** — Timing-safe string comparison prevents timing attacks.

```php
// Webhook HMAC verification
$expected = hash_hmac('sha256', $payload, config('services.stripe.webhook_secret'));
$isValid = hash_equals($expected, $request->header('Stripe-Signature'));
```

- [ ] **Never use `==` or `===` to compare hashes/tokens** — Use `hash_equals()` for constant-time comparison.

### What NOT To Do with Cryptography

- [ ] **Never roll your own encryption algorithm** — Use Laravel's `Crypt` facade or `sodium_*` functions.
- [ ] **Never use ECB mode** — ECB leaks patterns. Laravel uses CBC by default.
- [ ] **Never store encryption keys in code or version control** — Keys belong in environment variables or secret managers.
- [ ] **Never reuse IVs/nonces** — Laravel handles this automatically with `Crypt::encrypt()`.
- [ ] **Never use `base64_encode()` as "encryption"** — Base64 is encoding, not encryption. It provides zero security.

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

## 13. Frontend Security & Quality

### Form Security (Sensitive Applications)

- [ ] **Disable browser autofill on sensitive forms** — `autocomplete="off" autocorrect="off" autocapitalize="off" spellcheck="false"` on all inputs.
- [ ] **Never use `autocomplete="email"`, `"password"`, `"name"`** — On financial/accounting platforms, autofill is a data leak vector.

### XSS in Frontend Frameworks

- [ ] **Never use `v-html` with user data (Vue)** — Sanitise with DOMPurify first.
- [ ] **Never use `dangerouslySetInnerHTML` with user data (React)**.
- [ ] **Validate URL schemes** — When rendering user-provided links, reject `javascript:` protocol.

### Money Display

- [ ] **Format with dedicated formatter, not string templates** — Use `Intl.NumberFormat` or a composable.
- [ ] **No arithmetic with JS native numbers on money** — Use `decimal.js-light`, `big.js`, or similar.

### Cookie & CSP

- [ ] **SameSite=Strict for high-security apps** — Prevents CSRF via cross-site navigation. Use `Lax` only if cross-site login flows are needed.
- [ ] **Nonce-based CSP** — Dynamic nonce per request for inline scripts.
- [ ] **`HttpOnly` on session cookies** — Prevents JavaScript access to the session cookie.
- [ ] **`Secure` flag on all cookies in production** — Cookies sent only over HTTPS.

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

## 15. Configuration, Secrets & Environment

### Environment Variables

- [ ] **`APP_DEBUG=false` in production** — Debug mode exposes stack traces, env vars, and SQL queries.
- [ ] **`APP_ENV=production` in production** — Affects error display, caching, and service resolution.
- [ ] **Never commit `.env` to version control** — `.env` is in `.gitignore` by default. Keep it that way.
- [ ] **Use `php artisan env:encrypt` for deployment** — Encrypted `.env` files can be safely committed.
- [ ] **Never use `env()` outside `config/` files** — `env()` returns `null` after `config:cache`.
- [ ] **Cache config in production** — `php artisan config:cache`.
- [ ] **Cache routes in production** — `php artisan route:cache`.

### APP_KEY Security

- [ ] **Rotate `APP_KEY` periodically** — It encrypts sessions, cookies, and encrypted attributes. Thousands of `APP_KEY` values were leaked to GitHub in 2025.
- [ ] **Use `APP_PREVIOUS_KEYS` during rotation** — Old keys are tried for decryption, enabling gradual rotation.
- [ ] **Re-encrypt database columns after key rotation** — `encrypted` cast data must be re-encrypted with the new key.

### Secrets Management

- [ ] **No secrets in code, config files, or version control** — Use environment variables or secret managers.
- [ ] **Consider external secret managers for production** — AWS Secrets Manager, HashiCorp Vault, Google Secrets Manager.
- [ ] **Audit `.env.example` for real values** — Example files sometimes contain real API keys by mistake.

---

## 16. Logging, Monitoring & Audit Trails

### Application Logging

- [ ] **Log security events** — Failed logins, permission denials, 2FA failures, unusual access patterns.
- [ ] **Log business events** — Order created, payment processed, entry posted, approval granted.
- [ ] **Structured logging** — Use context arrays: `Log::info('Order created', ['order_id' => $id, 'user_id' => $user])`.
- [ ] **Never log sensitive data** — Passwords, tokens, credit card numbers, PII.
- [ ] **Use appropriate log levels** — `emergency` > `alert` > `critical` > `error` > `warning` > `notice` > `info` > `debug`.

### Audit Trails

- [ ] **Use `spatie/laravel-activitylog` or equivalent** — Automatic model change tracking.
- [ ] **Every model has `LogsActivity` trait** — With `getActivitylogOptions()` configured.
- [ ] **Record who, what, when, where** — User ID, action, timestamp, IP address.
- [ ] **Audit logs are immutable** — Never soft-delete or modify audit records.

### Monitoring

- [ ] **Exception tracking in production** — Sentry, Flare, Bugsnag, or similar.
- [ ] **Queue monitoring** — Laravel Horizon dashboard or equivalent.
- [ ] **Slow query logging** — Enable MySQL slow query log or use Laravel Debugbar in development.

---

## 17. Dead Code & Unused Artifacts

### What to Check For

- [ ] **Unused imports** — PHPStan / Pint detect these automatically.
- [ ] **Unused model scopes** — `scopeActive()` that nothing calls.
- [ ] **Unused enum cases** — Enum cases with no references.
- [ ] **Unused Vue components** — Components never imported.
- [ ] **Unused composables** — `useXxx()` files never imported.
- [ ] **Orphaned routes** — Routes pointing to deleted controllers.
- [ ] **Unreachable controller methods** — Methods not bound to any route.
- [ ] **Dead config keys** — Config values never read.
- [ ] **Unused middleware** — Registered but never applied.
- [ ] **Duplicate enums** — Two enums representing the same concept.
- [ ] **Commented-out code** — Delete it. Version control has the history.
- [ ] **TODO/FIXME comments older than 30 days** — Either fix them or create tickets.

### Tools

- [ ] **PHPStan level 8+** — Catches dead code, type errors, unused imports.
- [ ] **Laravel Pint** — Removes unused imports, enforces coding standards.
- [ ] **ESLint / `vue-tsc`** — Catches unused vars, imports, and type errors in frontend.
- [ ] **`composer audit`** — Checks for known vulnerabilities in dependencies.
- [ ] **`npm audit`** — Checks for known vulnerabilities in npm dependencies.

---

## 18. Dependency & Supply Chain Security

- [ ] **Run `composer audit` regularly** — Check for known CVEs in PHP dependencies.
- [ ] **Run `npm audit` regularly** — Check for known CVEs in Node dependencies.
- [ ] **Commit lock files** — Both `composer.lock` and `package-lock.json` / `pnpm-lock.yaml` must be committed.
- [ ] **Review dependency updates** — Don't blindly upgrade. Check changelogs for breaking changes.
- [ ] **Minimize dependencies** — Every dependency is an attack surface. Prefer Laravel built-ins.
- [ ] **Pin major versions** — Use `^` constraints in composer.json, but review major version bumps.
- [ ] **Verify package integrity** — `composer validate` and check package signatures.
- [ ] **No typosquatting** — Double-check package names before installing.

---

## 19. API Security

- [ ] **Rate limit all endpoints** — Use `ThrottleRequests` middleware or `RateLimiter::for()`.

```php
RateLimiter::for('api', function (Request $request) {
    return Limit::perMinute(60)->by($request->user()?->id ?: $request->ip());
});

RateLimiter::for('login', function (Request $request) {
    return Limit::perMinute(5)->by($request->input('email') . '|' . $request->ip());
});
```

- [ ] **Use Sanctum for API token auth** — Scope tokens with abilities.
- [ ] **API versioning** — `Route::prefix('api/v1')` or header-based versioning.
- [ ] **Use API Resources** — `JsonResource` and `ResourceCollection` for response formatting. Never return raw models.
- [ ] **Pagination** — Always paginate list endpoints. Never return unbounded collections.
- [ ] **No sensitive data in URLs** — Tokens, passwords, secrets belong in headers or request body, not query strings (URLs are logged).

---

## 20. Session & Cookie Security

```php
// config/session.php recommended settings
'driver' => env('SESSION_DRIVER', 'redis'),      // Use redis or database, not file
'lifetime' => 120,                                 // Minutes
'expire_on_close' => false,
'encrypt' => true,                                 // Encrypt session payload
'http_only' => true,                               // No JS access
'secure' => env('SESSION_SECURE_COOKIE', true),    // HTTPS only
'same_site' => 'strict',                           // Or 'lax' if cross-site login needed
```

- [ ] **Regenerate session after login** — `$request->session()->regenerate()`. Laravel does this automatically with Fortify/Breeze.
- [ ] **Invalidate session on logout** — `$request->session()->invalidate()` + `$request->session()->regenerateToken()`.
- [ ] **Short session lifetime for sensitive apps** — 15-30 minutes for financial applications.
- [ ] **Use `redis` or `database` session driver** — Not `file` in production (no horizontal scaling, no atomic operations).

---

## 21. File Upload & Storage Security

- [ ] **Validate MIME type and extension** — `'file|mimes:pdf,xlsx,csv|max:10240'`.
- [ ] **Never use the original filename** — Generate a UUID or hash-based name.

```php
$path = $request->file('document')->storeAs(
    'documents/' . auth()->id(),
    Str::uuid() . '.' . $request->file('document')->extension(),
    'private',
);
```

- [ ] **Store uploads outside the web root** — Use a `private` disk, not `public`.
- [ ] **Use signed temporary URLs for downloads** — `Storage::temporaryUrl($path, now()->addMinutes(5))`.
- [ ] **Prevent directory traversal** — `basename($filename)` before any file path construction.
- [ ] **Limit file size** — Both in validation rules and server config (`upload_max_filesize`, `post_max_size`).
- [ ] **Scan for malware** — Use ClamAV or cloud scanning for user uploads in sensitive applications.
- [ ] **No executable uploads** — Reject `.php`, `.sh`, `.exe`, `.bat`, `.phar`, `.svg` (SVG can contain scripts).

---

## 22. Queue & Job Safety

- [ ] **All external HTTP calls are queued** — Email, webhooks, API calls, PDF generation.
- [ ] **Set `$tries`, `$maxExceptions`, `$timeout`** — Prevent infinite retries and zombie jobs.

```php
class ProcessPayment implements ShouldQueue
{
    public int $tries = 3;
    public int $maxExceptions = 2;
    public int $timeout = 60;
    public int $backoff = 30;

    public function handle(): void { /* ... */ }

    public function failed(\Throwable $exception): void
    {
        // Notify team, log, create incident
    }
}
```

- [ ] **Implement `failed()` method** — Handle permanent failures with logging/alerting.
- [ ] **Use `ShouldBeUnique` for non-concurrent jobs** — Prevent duplicate processing.
- [ ] **Use `ShouldBeEncrypted` for jobs with sensitive data** — Encrypt the serialized payload.
- [ ] **Rate limit job dispatching** — Use `Bus::batch()` with `allowFailures()` for bulk operations.
- [ ] **Monitor queue depth** — Alert when queues back up beyond threshold.
- [ ] **Idempotent job design** — Jobs may run more than once. Ensure repeated execution is safe.
- [ ] **Don't serialize Eloquent models with sensitive data** — Jobs serialize model IDs, then re-query. Be aware of what's serialized.

---

## 23. PR / Code Review Checklist (Quick Reference)

Every PR should pass these checks before merge:

### Automated

- [ ] `vendor/bin/pint --dirty` — Code style
- [ ] `vendor/bin/phpstan analyse` — Static analysis (level 8)
- [ ] `php artisan test --compact` — All tests pass
- [ ] `npm run build` — Frontend compiles
- [ ] `composer audit` — No known vulnerabilities
- [ ] `npm audit` — No known vulnerabilities

### Security

- [ ] No hardcoded secrets, API keys, or credentials
- [ ] Authorization on every new controller method
- [ ] CSRF protection on every state-changing route
- [ ] Input validation via Form Request
- [ ] No raw SQL with user input
- [ ] No `v-html` / `{!! !!}` with user data
- [ ] No `$guarded = []` or missing `$fillable`
- [ ] Sensitive fields not in `$fillable`

### Data Integrity

- [ ] Multi-model writes wrapped in DB transactions
- [ ] `lockForUpdate()` for status-check-then-update patterns
- [ ] No float/bcmath arithmetic on money
- [ ] Idempotency for retryable operations
- [ ] State machine transitions via `transitionTo()`, not raw updates

### Quality

- [ ] `declare(strict_types=1)` on new PHP files
- [ ] Explicit return types on all new methods
- [ ] Enum references, not hardcoded strings
- [ ] Early returns / guard clauses (no deep nesting)
- [ ] Modern PHP syntax (match, enums, readonly, named args)
- [ ] Factory-based test setup
- [ ] Feature test for every new action
- [ ] No N+1 queries (eager loading where needed)

### Models

- [ ] `$fillable` declared (no privilege escalation fields)
- [ ] `SoftDeletes` trait
- [ ] `LogsActivity` trait with `getActivitylogOptions()`
- [ ] `casts()` method with appropriate casts
- [ ] Factory created (with useful states)
- [ ] `$hidden` on sensitive attributes

### Enums

- [ ] `allowedTransitions()` if used as state machine
- [ ] `isTerminal()` method
- [ ] `label()` / `color()` for UI display
- [ ] TypeScript mirror if used in frontend
- [ ] Registered in seeders if permission/role enum

### Events

- [ ] Audit log handler in reactor
- [ ] Metadata attached (via aggregate or `EmitsStoredEvents`)
- [ ] Data carried in event (not fetched from DB during replay)

### Routes

- [ ] Auth middleware applied
- [ ] 2FA middleware on destructive operations
- [ ] Rate limiting where appropriate
- [ ] Named route for Wayfinder / `route()` usage

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

## 27. Route & Middleware Security

### Route Definition

- [ ] **Name all routes** — `->name('orders.show')` for programmatic URL generation via `route()`.
- [ ] **Group routes by authentication/authorization** — Don't scatter `auth` middleware across individual routes.

```php
Route::middleware(['auth', 'verified'])->group(function () {
    Route::resource('orders', OrderController::class);
});
```

- [ ] **No sensitive operations on GET routes** — GET requests can be prefetched by browsers, logged in proxy servers, cached by CDNs. State changes require POST/PUT/PATCH/DELETE.
- [ ] **Use `Route::permanentRedirect()` for old URLs** — Not `Route::get()` with a redirect in the controller.
- [ ] **Remove debug/test routes in production** — No `/test`, `/debug`, `/phpinfo` routes.
- [ ] **Disable route listing in production** — `php artisan route:list` should not be accessible to users.

### Middleware Ordering

- [ ] **Auth before authorization** — `auth` → `verified` → `can:permission` → `2fa.reconfirm`.
- [ ] **Rate limiting early in the stack** — Before heavy processing occurs.
- [ ] **CORS before everything** — Preflight requests need headers before any other middleware runs.

### Route Model Binding

- [ ] **Use implicit route model binding** — Type-hint the model in the controller method. Laravel resolves automatically.
- [ ] **Scope bindings to parent models** — Use `->scopeBindings()` to prevent users accessing resources they don't own.

```php
// Without scoping — user can access any comment by ID
Route::get('/posts/{post}/comments/{comment}', [CommentController::class, 'show']);

// With scoping — comment must belong to the post
Route::get('/posts/{post}/comments/{comment}', [CommentController::class, 'show'])
    ->scopeBindings();
```

- [ ] **Use custom route keys for public URLs** — `getRouteKeyName()` returns `'slug'` instead of `'id'` to prevent ID enumeration.

```php
public function getRouteKeyName(): string
{
    return 'slug'; // or 'uuid'
}
```

---

## 28. Serialization & Object Injection

### PHP Object Injection

- [ ] **Never use `unserialize()` on untrusted data** — PHP's `unserialize()` can instantiate arbitrary objects, triggering magic methods (`__destruct`, `__wakeup`, `__toString`) that may execute code.

```php
// CRITICAL VULNERABILITY
$data = unserialize($request->input('data'));

// SAFE alternatives
$data = json_decode($request->input('data'), true);
$data = json_decode($request->input('data'), true, 512, JSON_THROW_ON_ERROR);
```

- [ ] **Use `allowed_classes: false` if `unserialize()` is unavoidable** — `unserialize($data, ['allowed_classes' => false])` prevents object instantiation.
- [ ] **Never store serialized PHP objects in database/cache without JSON alternative** — JSON is language-agnostic, doesn't instantiate objects, and is human-readable.

### JSON Safety

- [ ] **Use `JSON_THROW_ON_ERROR` flag** — Don't silently ignore malformed JSON.

```php
// SAFE — throws on bad input
$data = json_decode($input, true, 512, JSON_THROW_ON_ERROR);
$json = json_encode($data, JSON_THROW_ON_ERROR);

// DANGEROUS — returns null on failure (silent)
$data = json_decode($input);
```

- [ ] **Limit JSON decode depth** — Default depth of 512 is fine. Custom API inputs may need lower limits.
- [ ] **Use `JSON_PRETTY_PRINT` only for debugging** — Not in production API responses (wastes bandwidth).

---

## 29. Email Security

### Header Injection

- [ ] **Never interpolate user input into email headers** — Use Laravel's Mailable classes which escape automatically.

```php
// DANGEROUS — header injection via To/CC/BCC
mail($request->input('to'), 'Subject', 'Body');

// SAFE — Laravel Mailable
Mail::to($validatedEmail)->send(new OrderConfirmation($order));
```

### Email Best Practices

- [ ] **Validate email addresses with `email:rfc,dns`** — The `dns` flag checks MX records exist.
- [ ] **Queue all email sending** — Use `ShouldQueue` on Mailable or Notification classes.
- [ ] **Rate limit email sending** — Prevent abuse of password reset, invitation, or notification endpoints.
- [ ] **Use vague messages for password reset** — "If an account exists with that email, we've sent a reset link." Prevents user enumeration.
- [ ] **Set SPF, DKIM, and DMARC records** — Prevents email spoofing of your domain.
- [ ] **Use reply-to, not from, for user-generated emails** — `from` must be your domain. Use `replyTo()` for the user's address.
- [ ] **Limit attachment sizes and types** — Same rules as file upload security.

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

## 32. Memory, Performance & Resource Exhaustion

### Unbounded Operations

- [ ] **Never load unbounded result sets into memory** — Use `chunk()`, `lazy()`, `cursor()`, or pagination.

```php
// DANGEROUS — loads all records
$users = User::all();
foreach ($users as $user) { ... }

// SAFE — processes in chunks
User::query()->chunk(500, function ($users) {
    foreach ($users as $user) { ... }
});

// SAFE — lazy collection (one query, one row at a time)
User::query()->lazy()->each(function ($user) { ... });
```

- [ ] **Paginate all index/list endpoints** — Use `->paginate()` or `->cursorPaginate()`. Never return unbounded collections.
- [ ] **Limit array sizes in validation** — `'items' => 'array|max:100'` prevents memory bombs.
- [ ] **Limit string lengths** — `'description' => 'string|max:10000'`.
- [ ] **Limit file upload sizes** — Both in validation and `php.ini` (`upload_max_filesize`, `post_max_size`).

### N+1 Detection

- [ ] **Enable `preventLazyLoading()` in development** — Throws exceptions on N+1 queries.
- [ ] **Use `preventSilentlyDiscardingAttributes()` in development** — Throws when filling non-fillable attributes.

```php
// AppServiceProvider::boot()
Model::preventLazyLoading(! app()->isProduction());
Model::preventSilentlyDiscardingAttributes(! app()->isProduction());
Model::preventAccessingMissingAttributes(! app()->isProduction());
```

### Query Performance

- [ ] **Use `select()` on queries** — Don't fetch 50 columns when you need 3.
- [ ] **Use `exists()` not `count() > 0`** — `EXISTS` stops at first match.
- [ ] **Use `value()` for single scalar values** — `User::where('id', $id)->value('email')`.
- [ ] **Use `pluck()` for single-column lists** — Returns flat collection.
- [ ] **Use database aggregation** — `->sum()`, `->avg()`, `->count()` at query level, not PHP loops.
- [ ] **Index WHERE and ORDER BY columns** — Use `EXPLAIN` to verify index usage.

### Memory Leaks in Long-Running Processes

- [ ] **Disable query log in queue workers** — `DB::disableQueryLog()` in job constructors or AppServiceProvider.
- [ ] **Flush event listeners in long loops** — `Event::forgetPushed()` if using `Event::fakeFor()`.
- [ ] **Call `gc_collect_cycles()` in batch processing** — For very long-running processes.

---

## 33. Blade, View & Component Security

### Blade

- [ ] **`{{ }}` for all dynamic content** — Auto-escapes HTML entities.
- [ ] **`{!! !!}` only for pre-sanitized HTML** — Must be sanitized with HTMLPurifier before rendering.
- [ ] **No user data in `@php` blocks** — If you must use `@php`, don't evaluate user input.
- [ ] **No user data in `@include()` paths** — `@include($request->input('template'))` is arbitrary file inclusion.

```php
// CRITICAL VULNERABILITY
@include($request->input('view'))

// SAFE — allowlist
@include(match($type) {
    'invoice' => 'pdf.invoice',
    'receipt' => 'pdf.receipt',
    default => 'pdf.generic',
})
```

### Vue / Inertia Component Security

- [ ] **No `v-html` with user data** — Use `v-text` or `{{ }}` interpolation.
- [ ] **Validate `href` and `src` attributes** — Reject `javascript:` and `data:` protocols.
- [ ] **Don't pass sensitive data as props** — Filter server-side. Use API Resources.
- [ ] **Don't expose env variables to frontend** — Inertia shared props should not contain secrets.

### Component Props

- [ ] **Type all component props** — TypeScript interfaces or `defineProps<{}>()` with explicit types.
- [ ] **Validate prop values** — Use `validator` function in `defineProps` for enum-like props.
- [ ] **Default values for optional props** — Prevent `undefined` rendering.

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

## 37. Deployment & Production Hardening

### Application Configuration

- [ ] **`APP_DEBUG=false`** — Prevents stack traces, env vars, and SQL queries from leaking.
- [ ] **`APP_ENV=production`** — Affects error display and debug toolbar visibility.
- [ ] **Cache everything** — `config:cache`, `route:cache`, `view:cache`, `event:cache`.
- [ ] **Run `composer install --no-dev`** — No development dependencies in production.
- [ ] **Run `npm run build`** — Compile frontend assets.

### Server Configuration

- [ ] **Web server points to `/public` directory** — Never serve the project root.
- [ ] **`.env` not accessible via HTTP** — Webserver config blocks dotfiles.
- [ ] **`storage/` not accessible via HTTP** — Only `storage/app/public` via symlink.
- [ ] **PHP `expose_php = Off`** — Hides PHP version from `X-Powered-By` header.
- [ ] **PHP `display_errors = Off`** — Even if Laravel catches them, belt-and-suspenders.
- [ ] **File permissions: dirs 755, files 644** — Storage/cache dirs may need 775.

### Database

- [ ] **Separate DB user for application** — Not root. Minimal privileges (SELECT, INSERT, UPDATE, DELETE on app tables only).
- [ ] **No `DROP`, `ALTER`, `CREATE` permissions for app user** — Migrations run under a separate admin connection or CI/CD.
- [ ] **Enable slow query log** — For performance monitoring.
- [ ] **Regular backups** — Automated, tested, offsite.

### Monitoring

- [ ] **Exception tracker in production** — Sentry, Flare, Bugsnag, or equivalent.
- [ ] **Uptime monitoring** — External health check endpoint.
- [ ] **Queue monitoring** — Horizon dashboard or alerts on queue depth.
- [ ] **Disk space monitoring** — Log files and upload storage can fill disks.

---

## 38. Git & Version Control Hygiene

### Secret Prevention

- [ ] **`.gitignore` includes `.env`, `storage/`, `vendor/`, `node_modules/`** — Verify these are present.
- [ ] **Pre-commit hooks scan for secrets** — Use `gitleaks`, `trufflehog`, or `detect-secrets`.
- [ ] **Never commit API keys, passwords, or tokens** — Even in test files. Use `.env` or fakes.
- [ ] **If a secret is committed, rotate it immediately** — Removing from history with `git filter-branch` is insufficient. The key is compromised.

### Branch Protection

- [ ] **Protect main/master branch** — Require PR reviews, passing CI, no force push.
- [ ] **No `--force` push to shared branches** — Use `--force-with-lease` if absolutely necessary.
- [ ] **No `--no-verify` commits** — Pre-commit hooks exist for a reason. Fix the hook issue, don't skip it.

### Commit Hygiene

- [ ] **Atomic commits** — One logical change per commit.
- [ ] **Descriptive commit messages** — `fix: prevent double-posting via lockForUpdate` not `fix stuff`.
- [ ] **No generated files in commits** — `composer.lock` and `package-lock.json` yes, but not `vendor/`, `node_modules/`, or compiled assets.

---

## 39. Open Redirect & IDOR Prevention

### Open Redirect

- [ ] **Never redirect to user-supplied URLs without validation** — Attackers can redirect to phishing sites.

```php
// DANGEROUS — open redirect
return redirect($request->input('redirect_url'));

// SAFE — validate against allowlist
$url = $request->input('redirect_url', '/dashboard');
$allowed = ['/', '/dashboard', '/settings'];
return redirect(in_array($url, $allowed) ? $url : '/dashboard');

// SAFE — only allow same-host redirects
$url = $request->input('redirect_url');
if (parse_url($url, PHP_URL_HOST) !== null) {
    abort(400, 'External redirects not allowed');
}
return redirect($url);
```

- [ ] **Use `redirect()->intended()` for post-login redirects** — Laravel validates the intended URL is internal.
- [ ] **Validate `url` type inputs with `url` rule AND domain allowlist** — `'callback' => ['required', 'url', new AllowedDomain]`.

### Insecure Direct Object Reference (IDOR)

- [ ] **Always scope queries to the authenticated user** — `Order::where('user_id', auth()->id())->findOrFail($id)`.
- [ ] **Use policies for ownership checks** — `$this->authorize('view', $order)` where policy checks `$user->id === $order->user_id`.
- [ ] **Use UUIDs instead of sequential IDs in URLs** — Prevents enumeration (`/orders/1`, `/orders/2`, `/orders/3`).
- [ ] **Route model binding with scoped relationships** — `->scopeBindings()` ensures the child belongs to the parent.

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

## 43. Internationalization & Encoding Safety

### Character Encoding

- [ ] **Use UTF-8 everywhere** — Database, PHP, HTML, JSON. No mixed encodings.
- [ ] **Database charset is `utf8mb4`** — Not `utf8` (which can't store emoji or some Unicode characters).
- [ ] **Use `mb_*` functions for string operations** — `mb_strlen()`, `mb_substr()`, `mb_strtolower()`. Not `strlen()`, `substr()`, `strtolower()`.

```php
// DANGEROUS — byte-level operations, breaks on multibyte
strlen('café');     // Returns 5 (bytes), not 4 (characters)
substr('café', 0, 4); // Truncates mid-character

// SAFE — character-level operations
mb_strlen('café');     // Returns 4
mb_substr('café', 0, 4); // Returns 'café'
```

### HTML Encoding

- [ ] **`htmlspecialchars()` with `ENT_QUOTES | ENT_SUBSTITUTE`** — Blade's `{{ }}` does this automatically.
- [ ] **Specify charset in `htmlspecialchars()`** — `htmlspecialchars($str, ENT_QUOTES | ENT_SUBSTITUTE, 'UTF-8')`.
- [ ] **JSON encoding with `JSON_UNESCAPED_UNICODE`** — When returning JSON containing non-ASCII characters, this keeps them readable instead of `\uXXXX` escaping.

### SQL & Unicode

- [ ] **`utf8mb4_unicode_ci` collation** — For case-insensitive, accent-insensitive comparisons.
- [ ] **Beware of Unicode normalization** — `é` (single codepoint) and `é` (e + combining accent) are different bytes but same character. Use `Normalizer::normalize()` for comparisons if relevant.

### Translation / Localization

- [ ] **Use Laravel's `__()` helper for user-facing strings** — Not hardcoded English.
- [ ] **Never concatenate translated strings** — Use placeholders: `__('Welcome, :name', ['name' => $user->name])`.
- [ ] **Validate locale input** — Don't let users set arbitrary locales: `in_array($locale, config('app.available_locales'))`.

---

## 44. CSV & Spreadsheet Export Injection

### Formula Injection (CSV Injection / DDE)

- [ ] **Sanitize cell values starting with `=`, `+`, `-`, `@`, `\t`, `\r`** — When a user-controlled string starts with these characters, Excel/Sheets interprets it as a formula, enabling code execution on the recipient's machine.

```php
// DANGEROUS — direct user input in CSV cell
fputcsv($handle, [$user->name, $user->email, $user->bio]);
// If bio = "=HYPERLINK(\"https://evil.com\",\"Click\")" → formula executes

// SAFE — prefix with single quote or tab to neutralize formulas
function sanitizeCsvCell(string $value): string
{
    $dangerous = ['=', '+', '-', '@', "\t", "\r", "\n"];
    if (in_array($value[0] ?? '', $dangerous, true)) {
        return "'" . $value; // Prefix with single quote
    }
    return $value;
}
```

- [ ] **Apply to all CSV, XLSX, and TSV exports** — Not just CSV. Excel processes formulas in all formats.
- [ ] **Apply to all user-provided columns** — Name, description, notes, comments — any field the user can edit.

### Export Safety

- [ ] **Set `Content-Disposition: attachment`** — Force download, never inline rendering.
- [ ] **Set correct `Content-Type`** — `text/csv`, `application/vnd.openxmlformats-officedocument.spreadsheetml.sheet`. Not `text/html`.
- [ ] **Limit exported columns** — Don't export internal IDs, soft-delete timestamps, or system metadata.
- [ ] **Audit who exports what** — Log export events with user ID, export type, row count, and filter criteria.

---

## 45. Webhook Receiving & Replay Prevention

### Signature Verification

- [ ] **Verify webhook signatures before processing** — Use HMAC-SHA256. Reject unsigned or invalid requests.

```php
public function handle(Request $request): Response
{
    $payload = $request->getContent();
    $signature = $request->header('X-Webhook-Signature');
    $secret = config('services.provider.webhook_secret');

    $expected = hash_hmac('sha256', $payload, $secret);

    if (! hash_equals($expected, $signature)) {
        abort(403, 'Invalid signature');
    }

    // Process webhook...
}
```

- [ ] **Use `hash_equals()` for timing-safe comparison** — Not `===`.

### Replay Attacks

- [ ] **Check timestamp freshness** — Reject webhooks older than 5 minutes (or provider's recommended tolerance).

```php
$timestamp = $request->header('X-Webhook-Timestamp');
if (abs(time() - (int) $timestamp) > 300) {
    abort(403, 'Webhook too old');
}
```

- [ ] **Idempotency via event ID** — Store processed webhook IDs. Reject duplicates.

```php
$eventId = $request->header('X-Webhook-Id');
if (WebhookLog::where('event_id', $eventId)->exists()) {
    return response()->json(['status' => 'already_processed'], 200);
}
WebhookLog::create(['event_id' => $eventId, 'payload' => $payload]);
```

### Processing Safety

- [ ] **Queue webhook processing** — Return 200 immediately, process async. Providers retry on timeout.
- [ ] **Return 200 even for ignored events** — Non-2xx triggers retries. Silently ignore events you don't handle.
- [ ] **Log all webhook attempts** — Both successful and failed. Include payload hash (not full payload for PII reasons).
- [ ] **Allowlist webhook source IPs** — If the provider publishes IP ranges.

---

## 46. Multi-Tenancy & Data Isolation

### Query Scoping

- [ ] **Global scope or middleware-based tenant filtering** — Every query must be scoped to the current tenant. One missed scope = cross-tenant data leak.

```php
// Global scope approach
class TenantScope implements Scope
{
    public function apply(Builder $builder, Model $model): void
    {
        $builder->where('tenant_id', app('tenant')->id);
    }
}

// Middleware approach — set tenant context
public function handle(Request $request, Closure $next): Response
{
    $tenant = Tenant::findByDomain($request->getHost());
    app()->instance('tenant', $tenant);
    return $next($request);
}
```

- [ ] **Test cross-tenant isolation** — Explicitly verify that tenant A cannot access tenant B's data.
- [ ] **Audit raw queries for missing tenant filters** — `DB::select()` and `DB::table()` bypass global scopes.

### Shared Resources

- [ ] **Tenant-specific encryption keys** — Don't use the same `APP_KEY` for encrypting all tenants' data (if required by compliance).
- [ ] **Tenant-specific cache prefixes** — `Cache::tags(['tenant:' . $tenantId])` or config-level prefix.
- [ ] **Queue jobs carry tenant context** — Serialize the tenant ID, restore context in `handle()`.
- [ ] **Scheduled commands run per-tenant** — Iterate tenants, set context, then execute.

---

## 47. Console Command Safety

### Input Validation

- [ ] **Validate arguments and options** — Console input is user input. Validate with `$this->argument()` checks.
- [ ] **Use confirmations for destructive commands** — `$this->confirm('Are you sure?')` or `--force` flag.

```php
public function handle(): int
{
    if (! $this->option('force') && ! $this->confirm('This will delete all expired records. Continue?')) {
        return self::FAILURE;
    }

    // Proceed...
    return self::SUCCESS;
}
```

- [ ] **Return proper exit codes** — `self::SUCCESS` (0), `self::FAILURE` (1), `self::INVALID` (2).

### Production Safety

- [ ] **Gate dangerous commands behind environment checks** — `if (app()->isProduction() && ! $this->option('force'))`.
- [ ] **Never run `migrate:fresh` or `db:wipe` in production** — Use `migrate` only.
- [ ] **Log all command executions** — Who ran what, when, with what arguments.
- [ ] **Use `--no-interaction` in CI/CD** — Prevents prompts from hanging deployments.
- [ ] **No `dd()` or `dump()` in commands** — Use `$this->info()`, `$this->warn()`, `$this->error()`.

---

## 48. Scheduling & Cron Safety

### Overlap Prevention

- [ ] **Use `->withoutOverlapping()`** — Prevents a scheduled job from running if the previous instance is still running.

```php
$schedule->command('reports:generate')
    ->dailyAt('02:00')
    ->withoutOverlapping(30)  // Lock expires after 30 minutes
    ->onOneServer();          // Only one server in multi-server
```

- [ ] **Use `->onOneServer()`** — In load-balanced environments, ensures only one server runs the job.

### Timezone & Timing

- [ ] **Specify timezone explicitly** — `->timezone('UTC')` or set in `scheduleTimezone()` method.
- [ ] **Use `->evenInMaintenanceMode()`** — For critical jobs (backups, health checks) that must run during maintenance.
- [ ] **Monitor schedule health** — Use `->pingBefore()` / `->thenPing()` or Laravel's schedule health check.

### Failure Handling

- [ ] **Use `->onFailure()` for alerting** — Send notification or log when a scheduled task fails.
- [ ] **Use `->emailOutputOnFailure()`** — Get command output when something breaks.
- [ ] **Test scheduled commands independently** — `php artisan schedule:test` to verify timing.

---

## 49. Broadcasting & WebSocket Security

### Channel Authorization

- [ ] **All private and presence channels require authorization** — Define in `routes/channels.php`.

```php
Broadcast::channel('orders.{orderId}', function (User $user, string $orderId) {
    return $user->id === Order::findOrFail($orderId)->user_id;
});
```

- [ ] **Never send sensitive data on public channels** — Public channels are accessible to anyone with the channel name.
- [ ] **Presence channels expose user info** — Only include the minimum needed (ID, name). Not email, role, etc.

### Payload Safety

- [ ] **Sanitize broadcast payloads** — Same XSS rules as API responses. Don't broadcast raw user input.
- [ ] **Limit payload size** — WebSocket providers have payload limits (typically 10-100KB). Don't broadcast entire models.
- [ ] **Use private channels for user-specific data** — `private-user.{userId}` for notifications, account updates, etc.

---

## 50. Notification Channel Safety

### Database Notifications

- [ ] **Don't store sensitive data in notification payloads** — Notification data is stored as JSON in the `notifications` table. Anyone with DB access can read it.
- [ ] **Set a retention policy** — Old notifications accumulate. Prune with `model:prune` or a scheduled cleanup.
- [ ] **Index the `notifiable_id` column** — For performance on `$user->notifications` queries.

### Mail Notifications

- [ ] **Queue all mail notifications** — `implements ShouldQueue` on the notification class.
- [ ] **Use Markdown mail templates** — Consistent branding, automatic text fallback.
- [ ] **Test mail rendering** — `$notification->toMail($user)->render()` in tests.

### SMS / Third-Party Channels

- [ ] **Rate limit SMS notifications** — SMS is expensive. Prevent loops.
- [ ] **Never send secrets via SMS** — SMS is unencrypted and interceptable (SS7 attacks).
- [ ] **Handle delivery failures gracefully** — Channel failures should not break the application.
- [ ] **Use the `via()` method for per-user channel preferences** — Don't blast all channels.

```php
public function via(object $notifiable): array
{
    return $notifiable->prefers_sms ? ['vonage'] : ['mail', 'database'];
}
```

---

## 51. Debug & Development Tool Leakage

### Production Checks

- [ ] **Laravel Debugbar disabled in production** — Check `DEBUGBAR_ENABLED=false` or uninstall in production.
- [ ] **Laravel Telescope restricted in production** — Gate access to admin users only via `TelescopeServiceProvider::gate()`.
- [ ] **Horizon dashboard gated** — `Horizon::auth()` restricts dashboard access. Default is no access in production.
- [ ] **No `dd()`, `dump()`, `var_dump()`, `print_r()` left in code** — Use static analysis or pre-commit hooks to catch these.
- [ ] **No `ray()` calls left in code** — Ray debugging calls left in production send data to the developer's machine.
- [ ] **No `Log::debug()` with sensitive data** — Debug-level logs should be suppressed in production via log level config.
- [ ] **`/phpinfo` route doesn't exist** — `phpinfo()` exposes server config, PHP extensions, environment variables.

### Error Page Leakage

- [ ] **Custom error pages for 404, 403, 500, 503** — Don't show the default Laravel error page in production. It may hint at framework version.
- [ ] **Error responses don't include exception class names** — The class `App\Exceptions\InsufficientFundsException` reveals internal architecture. Return generic messages.

---

## 52. DNS Rebinding & Host Header Attacks

### Host Header Validation

- [ ] **Set `APP_URL` correctly** — Laravel uses it for URL generation. Incorrect `APP_URL` can lead to phishing via password reset links.
- [ ] **`TrustHosts` middleware configured** — List allowed hostnames. Rejects requests with unexpected `Host` headers.

```php
// app/Http/Middleware/TrustHosts.php
public function hosts(): array
{
    return [
        $this->allSubdomainsOfApplicationUrl(),
        // Or explicit:
        'myapp.com',
        '*.myapp.com',
    ];
}
```

- [ ] **Never use `$request->getHost()` for security decisions without validation** — Attacker can set arbitrary `Host` headers.
- [ ] **Password reset links use `APP_URL`, not request host** — Verify `ResetPassword` notification uses `config('app.url')`.

### Trusted Proxies

- [ ] **`TrustProxies` middleware configured** — If behind a load balancer, trust the proxy IPs to get the real client IP.
- [ ] **Don't trust `*` for proxies in production** — Specify exact proxy IPs or CIDR ranges.

---

## 53. Backup & Disaster Recovery

### Backup Strategy

- [ ] **Automated daily backups** — Use `spatie/laravel-backup` or equivalent.
- [ ] **Backup includes database AND files** — Uploads, storage, `.env` (encrypted).
- [ ] **Offsite backup storage** — S3, GCS, or separate server. Not the same disk as the app.
- [ ] **Backup encryption** — Encrypt backups at rest with `spatie/laravel-backup`'s encryption support.
- [ ] **Backup retention policy** — Keep daily for 7 days, weekly for 4 weeks, monthly for 12 months.

### Recovery Testing

- [ ] **Test restore procedure quarterly** — Untested backups are not backups.
- [ ] **Document recovery steps** — Who does what, in what order, with what credentials.
- [ ] **Recovery Time Objective (RTO) defined** — How long can you be down?
- [ ] **Recovery Point Objective (RPO) defined** — How much data can you lose?

### Monitoring

- [ ] **Alert on backup failure** — `spatie/laravel-backup` fires events on success/failure.
- [ ] **Monitor backup size trends** — Sudden drops may indicate a broken backup.
- [ ] **Alert on backup age** — If no backup in 48 hours, something is wrong.

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

## 56. Dependency Injection Discipline

### Constructor Injection

- [ ] **Inject dependencies via constructor, not resolved inline** — Makes dependencies explicit and testable.

```php
// GOOD — explicit dependency, easily mocked
final class PostJournalEntry
{
    public function __construct(
        private readonly PeriodLockCheckerInterface $lockChecker,
        private readonly ExchangeRateGatewayInterface $rateGateway,
    ) {}
}

// BAD — hidden dependencies, hard to test
final class PostJournalEntry
{
    public function handle(): void
    {
        $checker = app(PeriodLockCheckerInterface::class); // Hidden
        $rate = resolve(ExchangeRateGatewayInterface::class); // Hidden
    }
}
```

### Anti-Patterns

- [ ] **No `app()` / `resolve()` in business logic** — Acceptable in service providers, tests, and commands. Nowhere else.
- [ ] **No static facade calls in domain/action classes** — `Cache::get()`, `Log::info()` are acceptable in infrastructure code, not domain logic. Inject the contract instead.
- [ ] **No `new` for services** — `new PaymentGateway()` in business logic creates tight coupling. Bind to interface and inject.
- [ ] **No God constructors** — If a class has more than 4-5 constructor dependencies, it's doing too much. Extract a sub-service.

### Interface Segregation

- [ ] **Inject narrow interfaces, not broad ones** — `PeriodLockCheckerInterface` not `AccountingServiceInterface` with 30 methods.
- [ ] **One implementation per interface (usually)** — If an interface only has one implementation, ensure it's there for testability (fakes/mocks) or future extension, not just ceremony.

### Laravel Container Features

- [ ] **Use contextual binding for different implementations** — `$this->app->when(A::class)->needs(I::class)->give(B::class)`.
- [ ] **Use `Scoped` singletons for request-scoped state** — `$this->app->scoped(TenantContext::class)`.
- [ ] **Register bindings in providers, not scattered across bootstrap** — Central, discoverable location.

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

## 58. HTTP Client & External API Safety

### Outbound HTTP Calls

- [ ] **Set timeouts on all HTTP calls** — Prevent hanging requests from blocking workers.

```php
Http::timeout(10)->connectTimeout(5)->get('https://api.example.com/data');
```

- [ ] **Retry with backoff** — `Http::retry(3, 100)->get(...)`.
- [ ] **Queue external API calls** — Don't make users wait for third-party responses.
- [ ] **Circuit breaker for unreliable APIs** — After N failures, stop calling and return a fallback.

### Response Handling

- [ ] **Check response status** — `$response->successful()`, `$response->failed()`, `$response->throw()`.
- [ ] **Don't trust external response data** — Validate and sanitize. External APIs can return unexpected structures.
- [ ] **Log external API failures** — With request URL (redacted), status code, and response body (truncated).
- [ ] **Never log credentials in requests** — Redact `Authorization` headers, API keys, tokens.

### Saloon / HTTP Client Patterns

- [ ] **Use connector classes, not raw `Http::get()`** — Centralize base URL, auth, headers in a connector.
- [ ] **Mock external APIs in tests** — `Http::fake()` or Saloon's mock client. Never call real APIs in test suites.
- [ ] **Handle rate limits from external APIs** — Respect `Retry-After` headers. Implement exponential backoff.

---

## 59. Feature Flags & Rollout Safety

### Implementation

- [ ] **Use a structured system** — Laravel Pennant, `spatie/laravel-settings`, or config-based flags. Not ad-hoc `if` checks.
- [ ] **Feature flags are temporary** — Remove the flag and dead code path after full rollout. Feature flags are not permanent config.
- [ ] **Test both flag states** — Every feature behind a flag needs tests for on AND off.
- [ ] **Default to off for new features** — Fail closed. New features are disabled until explicitly enabled.

### Rollout Safety

- [ ] **Gradual rollout with percentage** — 10% → 25% → 50% → 100%. Monitor between each step.
- [ ] **Instant kill switch** — Every feature flag can be turned off immediately without a deploy.
- [ ] **Audit flag changes** — Who enabled what, when.

---

## 60. Accessibility (A11y) Baseline

### Semantic HTML

- [ ] **Use proper heading hierarchy** — `h1` → `h2` → `h3`. No skipping levels.
- [ ] **Use `<button>` for actions, `<a>` for navigation** — Not `<div @click>`.
- [ ] **Form inputs have `<label>` elements** — `<label for="email">`.
- [ ] **Tables use `<th>` for headers with `scope="col"` or `scope="row"`**.

### ARIA & Keyboard

- [ ] **Interactive elements are keyboard-accessible** — Tab navigation, Enter/Space to activate.
- [ ] **`aria-label` on icon-only buttons** — `<button aria-label="Close">X</button>`.
- [ ] **`role` attributes on custom widgets** — Modals, dropdowns, tabs.
- [ ] **Focus management** — After modal open/close, focus moves to the correct element.
- [ ] **Visible focus indicators** — Don't remove `outline` without providing an alternative.

### Visual

- [ ] **Color contrast ratio minimum 4.5:1** — For normal text. 3:1 for large text.
- [ ] **Don't convey information by color alone** — Use icons, text, or patterns alongside color.
- [ ] **Responsive text** — Support browser zoom to 200% without horizontal scroll.
- [ ] **Reduced motion support** — `@media (prefers-reduced-motion: reduce)` disables animations.

---

## 61. Inertia.js / SPA-Specific Security

### Prop Exposure

- [ ] **Only send necessary data as props** — Don't send full model objects. Use Resources/DTOs.
- [ ] **Never send passwords, tokens, or secrets as props** — Even if they're on the `$hidden` array, a `toArray()` override could expose them.
- [ ] **Filter shared props** — `HandleInertiaRequests::share()` runs on every request. Don't overshare.

```php
// DANGEROUS — sharing too much
public function share(Request $request): array
{
    return [
        'auth' => [
            'user' => $request->user(), // Full user model with all attributes
        ],
    ];
}

// SAFE — selective sharing
public function share(Request $request): array
{
    return [
        'auth' => [
            'user' => $request->user() ? [
                'id' => $request->user()->id,
                'name' => $request->user()->name,
                'roles' => $request->user()->getRoleNames(),
            ] : null,
        ],
    ];
}
```

### Deferred & Lazy Props

- [ ] **Deferred props load after initial page render** — Add skeleton/loading states for deferred data.
- [ ] **Optional props not sent unless requested** — Use `Inertia::optional()` for data only needed on specific interactions.
- [ ] **Don't defer auth/permission data** — Authorization data must be available immediately for UI gating.

### Navigation & History

- [ ] **`preserveScroll` on form submissions** — Prevent scroll jump to top.
- [ ] **`preserveState` for filter/search interactions** — Keep component state during partial reloads.
- [ ] **`replace: true` for redirects within flows** — Prevent back-button confusion.
- [ ] **Handle 419 (CSRF token mismatch) gracefully** — Show a "session expired, please refresh" message.

### Server-Side Rendering (SSR)

- [ ] **No `window`, `document`, `localStorage` during SSR** — Guard with `typeof window !== 'undefined'` or `onMounted()`.
- [ ] **SSR response doesn't contain user-specific data in HTML source** — Initial HTML is potentially cached. Use deferred props for user-specific data.

---

## 62. Redis Security

### Connection Security

- [ ] **Require authentication** — Set `REDIS_PASSWORD` in `.env`. Don't run Redis without a password.
- [ ] **Use TLS for Redis connections in production** — `REDIS_SCHEME=tls` or `rediss://` URL scheme.
- [ ] **Bind Redis to localhost or private network** — Never expose Redis to the internet.
- [ ] **Use separate Redis databases for different purposes** — Sessions on DB 0, cache on DB 1, queues on DB 2. Prevents `FLUSHDB` from wiping sessions when clearing cache.

```php
// config/database.php
'redis' => [
    'default' => ['database' => env('REDIS_DB', 0)],
    'cache' => ['database' => env('REDIS_CACHE_DB', 1)],
    'session' => ['database' => env('REDIS_SESSION_DB', 2)],
    'queue' => ['database' => env('REDIS_QUEUE_DB', 3)],
],
```

### Data Safety

- [ ] **Set `maxmemory-policy` on Redis** — Prevent Redis from consuming all memory. Use `allkeys-lru` for cache, `noeviction` for queues.
- [ ] **Don't store sensitive data in Redis without encryption** — Redis stores data in plaintext. Use `ShouldBeEncrypted` for jobs or encrypt manually.
- [ ] **Monitor Redis memory usage** — Alert when approaching `maxmemory`.
- [ ] **Use `SCAN` instead of `KEYS *`** — `KEYS` blocks Redis. `SCAN` is non-blocking.

### Session Safety via Redis

- [ ] **`session.gc_maxlifetime` matches Laravel `session.lifetime`** — Prevents premature session eviction.
- [ ] **Redis persistence configured** — `RDB` or `AOF` depending on durability requirements. Queue data loss is unacceptable.

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

## 66. Environment Parity

- [ ] **Development matches production stack versions** — Same PHP, MySQL, Redis, Node versions.
- [ ] **Same database engine in tests** — SQLite tests miss MySQL-specific behavior (JSON queries, full-text search, strict mode). Use MySQL in CI.
- [ ] **Same queue driver in staging** — `sync` driver hides timing bugs. Use Redis in staging.
- [ ] **Same cache driver in staging** — `array` cache hides race conditions. Use Redis.
- [ ] **Same session driver in staging** — `file` driver hides scaling issues.
- [ ] **PHP strict mode matches** — `error_reporting`, `display_errors`, `strict_types` should match.

---

## 67. Docker & Container Security

- [ ] **Run as non-root user** — `USER www-data` in Dockerfile. Not `root`.
- [ ] **Minimal base images** — `php:8.4-fpm-alpine` over `php:8.4-fpm`. Smaller attack surface.
- [ ] **No secrets in Dockerfiles or build args** — Use runtime env vars or secret managers.
- [ ] **Pin image versions** — `php:8.4.3-fpm-alpine` not `php:latest`. Reproducible builds.
- [ ] **Read-only filesystem where possible** — `--read-only` flag. Write only to `/tmp` and mounted volumes.
- [ ] **Scan images for vulnerabilities** — `docker scout`, Trivy, or Snyk.
- [ ] **Don't install dev dependencies in production images** — Separate build stages. `composer install --no-dev` in runtime stage.

---

## 68. PDF Generation Security

- [ ] **Sanitize HTML before PDF rendering** — PDF engines (wkhtmltopdf, Puppeteer, Dompdf) execute HTML/CSS. Untrusted HTML = XSS inside PDF generator.
- [ ] **Disable JavaScript in PDF engines** — `--disable-javascript` for wkhtmltopdf. Prevents script execution.
- [ ] **Disable network access in PDF engines** — `--disable-local-file-access` and `--disable-external-links`. Prevents SSRF.
- [ ] **Limit PDF page count** — Unbounded input can generate million-page PDFs (DoS).
- [ ] **Queue PDF generation** — PDF rendering is CPU-intensive. Don't block web requests.
- [ ] **Serve PDFs as downloads** — `Content-Disposition: attachment`. Not inline (which could execute embedded JavaScript in some viewers).

---

## 69. Temporary File & Directory Safety

- [ ] **Use `sys_get_temp_dir()` or Laravel's `storage_path('temp/')`** — Not hardcoded `/tmp`.
- [ ] **Delete temp files after use** — Use `try/finally` or `register_shutdown_function()`.
- [ ] **Unique temp file names** — Use `tempnam()` or `Str::uuid()`. Never predictable names.
- [ ] **Don't serve temp directories via HTTP** — Ensure web server config blocks access.
- [ ] **Set restrictive permissions** — `0600` for temp files. Not world-readable.
- [ ] **Schedule temp cleanup** — Cron job to remove temp files older than 24 hours.

---

## 70. Process & Exec Safety

- [ ] **Use Laravel Process facade over raw `exec()`/`shell_exec()`**.

```php
// SAFE — no shell interpretation
$result = Process::run(['ffmpeg', '-i', $inputPath, '-o', $outputPath]);

// DANGEROUS — shell interprets the string
exec("ffmpeg -i {$inputPath} -o {$outputPath}");
```

- [ ] **Set timeout on all external processes** — `Process::timeout(30)->run(...)`.
- [ ] **Check exit codes** — `$result->successful()`, `$result->exitCode()`.
- [ ] **Capture stderr separately** — `$result->errorOutput()` for debugging.
- [ ] **Never pass user input to process arguments without escaping** — Even with array syntax, validate inputs.
- [ ] **Log process executions** — Command, exit code, duration. For audit trail.

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

## 78. JSON Column Safety

- [ ] **Cast JSON columns with `'array'` or `'collection'`** — Not raw `json_decode()`.
- [ ] **Validate JSON structure** — `'metadata' => 'required|array'` plus nested rules.
- [ ] **Index JSON paths for queries** — MySQL: `ALTER TABLE t ADD INDEX idx ((CAST(data->>'$.status' AS CHAR(20))))`.
- [ ] **Don't store relational data in JSON** — If you query/filter by it, it belongs in a proper column.
- [ ] **Beware of `null` vs missing key** — `$model->metadata['key']` throws if key doesn't exist. Use `$model->metadata['key'] ?? null`.
- [ ] **JSON merge update safety** — `$model->update(['metadata->key' => $value])` doesn't merge — it replaces the entire JSON if not careful. Use `->forceFill()` for JSON arrow syntax.

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

## 83. Password & Credential Management

- [ ] **Minimum password length: 12 characters** — NIST SP 800-63B recommends 8+, OWASP recommends 12+.
- [ ] **Maximum password length: 128 characters** — Prevents DoS via extremely long passwords hitting hash functions.
- [ ] **Check against breached password lists** — Use `Password::min(12)->uncompromised()` rule.
- [ ] **No password composition rules** — NIST says don't require uppercase/lowercase/numbers/symbols. Length matters most.
- [ ] **Rate limit login attempts** — `ThrottleRequests` or `RateLimiter::for('login')`.
- [ ] **Constant-time password comparison** — `Hash::check()` handles this. Never `$password === $storedHash`.
- [ ] **Never display passwords in logs, responses, or error messages**.
- [ ] **Session invalidation on password change** — `Auth::logoutOtherDevices($newPassword)`.
- [ ] **`$dontFlash` includes password fields** — Prevents passwords from flashing back to forms after validation errors.

---

## 84. Two-Factor Authentication (2FA) Depth

- [ ] **Enforce 2FA for all users (or admin+ roles)** — Use middleware to redirect users without 2FA to setup page.
- [ ] **TOTP over SMS** — SMS is vulnerable to SIM-swapping and SS7 attacks.
- [ ] **Store 2FA secrets encrypted** — `'two_factor_secret' => 'encrypted'` cast.
- [ ] **Generate and store recovery codes** — 8 codes, hashed, single-use. Display once at setup.
- [ ] **Rate limit 2FA verification attempts** — 5 attempts per minute maximum.
- [ ] **Reconfirmation for sensitive operations** — Don't just check "is 2FA enabled". Require fresh 2FA input for destructive actions.
- [ ] **2FA confirmation timestamp** — `confirmed_via_2fa_at` column for audit proof. Valid for single action only.
- [ ] **Backup code usage alerts** — Notify user when a recovery code is used.
- [ ] **`inputmode="numeric"` on OTP fields** — Shows numeric keyboard on mobile.

---

## 85. Logging Sensitive Data Prevention

- [ ] **Never log passwords** — Not in plaintext, not hashed, not partially masked.
- [ ] **Never log credit card numbers** — Even partially. Use tokens.
- [ ] **Never log API keys or tokens** — Mask: `sk_live_...xxxx`.
- [ ] **Never log session IDs** — Enables session hijacking.
- [ ] **Never log PII unnecessarily** — SSN, date of birth, home address.
- [ ] **Redact request logging** — `$request->except(['password', 'password_confirmation', 'credit_card'])`.
- [ ] **Configure `$dontReport` for noisy exceptions** — `ValidationException`, `HttpException` 404s.
- [ ] **Log levels are appropriate** — Errors are `error`, warnings are `warning`. Not everything is `info`.
- [ ] **Structured context, not string interpolation** — `Log::info('Order created', ['id' => $id])` not `Log::info("Order $id created")`.
- [ ] **Log rotation configured** — Prevent log files from filling the disk. Use `daily` channel with `LOG_DEPRECATIONS_CHANNEL`.

---

## 86. Third-Party Package Audit

- [ ] **Read the package source before installing** — Especially for packages with few stars/downloads.
- [ ] **Check maintenance status** — Last commit date, open issues, release frequency.
- [ ] **Check license compatibility** — MIT, Apache 2.0, BSD are safe. GPL may have implications.
- [ ] **Review package permissions** — Does it need filesystem access? Network access? Why?
- [ ] **Pin to stable versions** — `^3.0` not `dev-main` or `*`.
- [ ] **Prefer Laravel-ecosystem packages** — `spatie/*`, `laravel/*`, `filament/*` — well-maintained, community-reviewed.
- [ ] **Remove unused packages** — `composer why <package>` shows what depends on it. Remove if nothing does.
- [ ] **Review `composer.json` scripts** — `post-install-cmd` and `post-update-cmd` can execute arbitrary code.

---

## 87. Image & Media Processing Security

- [ ] **Validate MIME type server-side** — Don't trust file extension or client-provided Content-Type.
- [ ] **Strip EXIF data from uploaded images** — EXIF contains GPS coordinates, camera info, and sometimes thumbnails of other images.

```php
// Using Intervention Image
$image = Image::read($file)->stripMetadata()->save();
```

- [ ] **Limit image dimensions** — Reject images larger than 10000x10000 pixels (decompression bomb prevention).
- [ ] **Resize images server-side** — Don't serve 4000x3000 originals when 400x300 thumbnails suffice.
- [ ] **SVG files can contain JavaScript** — Treat SVGs as untrusted HTML. Sanitize or reject.
- [ ] **Queue image processing** — Resizing, thumbnail generation, format conversion are CPU-intensive.
- [ ] **Serve user-uploaded images from a separate domain** — Prevents cookie leakage and XSS escalation.

---

## 88. Search & Full-Text Safety

- [ ] **Sanitize search queries** — Escape special characters in search syntax (`+`, `-`, `*`, `"`, `~`).
- [ ] **Limit search query length** — `max:200` in validation. Long queries are DoS vectors.
- [ ] **Rate limit search endpoints** — Expensive queries can be used for DoS.
- [ ] **Use database full-text indexes** — Not `LIKE '%term%'` which can't use indexes.
- [ ] **Never expose raw search engine errors to users** — Elasticsearch, Algolia errors may reveal index structure.
- [ ] **Paginate search results** — Never return all matches.
- [ ] **Highlight matches safely** — If highlighting user query terms in results, escape the HTML.

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

## 90. UUID & ULID Best Practices

- [ ] **Use UUIDv7 (time-sorted) for primary keys** — Laravel's `HasUuids` uses UUIDv7. Preserves insert order for B-tree indexes.
- [ ] **Use UUIDv4 for non-sequential tokens** — Non-guessable, no time information.
- [ ] **Use ULID for human-readable sorted IDs** — 26 characters, Crockford Base32, sortable.
- [ ] **Consistent column types** — `$table->uuid('id')->primary()` for UUID PKs. `$table->foreignUuid('user_id')` for FKs.
- [ ] **Type hint UUID IDs as `string`** — Not `int`. UUIDs are strings.
- [ ] **Don't expose auto-increment IDs alongside UUIDs** — Defeats the purpose. Use UUID as the only identifier.
- [ ] **Index UUID columns** — B-tree indexes on UUID columns work well with UUIDv7 (time-sorted). UUIDv4 causes index fragmentation.

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

## 93. Audit Trail Completeness

- [ ] **Every create, update, delete logged** — Use `LogsActivity` or equivalent.
- [ ] **Log the before/after values** — `logOnlyDirty()` to capture what changed.
- [ ] **Log the actor** — `CauserResolver` for the user who made the change.
- [ ] **Log the IP and user agent** — Via event metadata or middleware.
- [ ] **Audit logs are append-only** — Never update or soft-delete audit records.
- [ ] **Login/logout events logged** — `Login`, `Logout`, `Failed`, `Lockout` events.
- [ ] **Permission changes logged** — Role assignment/revocation, permission grants.
- [ ] **Export events logged** — Who exported what data, when.
- [ ] **Audit log retention policy** — Keep for 7 years for financial applications (regulatory requirement).
- [ ] **Searchable audit trail** — Index on `subject_type`, `causer_id`, `created_at`.

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

## 96. TypeScript & Frontend Type Safety

- [ ] **Strict TypeScript** — `"strict": true` in `tsconfig.json`.
- [ ] **No `any` type** — Use `unknown` and narrow. `any` bypasses all type checking.
- [ ] **Define interfaces for all API responses** — Don't use inline types.
- [ ] **Type Inertia page props** — `defineProps<{ orders: PaginatedResponse<Order> }>()`.
- [ ] **Enum mirrors for backend enums** — Every PHP enum used in the frontend has a TS equivalent.
- [ ] **No `as` type assertions** — Use type guards (`if ('status' in obj)`) instead.
- [ ] **Null checks before property access** — `user?.name` not `user.name` when nullable.
- [ ] **Function return types explicit** — Even for composables and utility functions.

---

## 97. Vue Component Patterns

- [ ] **`<script setup lang="ts">`** — Composition API with TypeScript.
- [ ] **Props are typed and documented** — `defineProps<{ title: string; count?: number }>()`.
- [ ] **Emits are typed** — `defineEmits<{ (e: 'update', value: string): void }>()`.
- [ ] **Composables for reusable logic** — `useFormatAmount()`, `useAuth()`, `useToast()`.
- [ ] **No business logic in templates** — Extract to `computed` or composables.
- [ ] **`v-if` before `v-for`** — Never on the same element. Wrap in `<template v-if>`.
- [ ] **Key all `v-for` loops** — `:key="item.id"`, not `:key="index"`.
- [ ] **Cleanup side effects in `onUnmounted()`** — Event listeners, intervals, subscriptions.
- [ ] **No direct DOM manipulation** — Use refs and reactive data. No `document.querySelector()`.

---

## 98. Tailwind CSS Hygiene

- [ ] **Use design tokens, not raw values** — `text-primary` not `text-[#1a2b3c]`.
- [ ] **Consistent spacing scale** — Don't mix `p-3` and `p-[13px]`.
- [ ] **Responsive design mobile-first** — `text-sm md:text-base lg:text-lg`.
- [ ] **Dark mode support** — Use `dark:` variants. Don't hardcode light colors.
- [ ] **No `!important` via `!` prefix** — Fix specificity instead.
- [ ] **Extract repeated patterns into components** — Not utility class strings passed as props.
- [ ] **Purge unused CSS in production** — Tailwind 4 does this automatically. Verify build output size.
- [ ] **Use `cn()` utility for conditional classes** — `cn('base', condition && 'active')`.

---

## 99. CI/CD Pipeline Checks

### Required Pipeline Stages

- [ ] **Lint** — Pint (PHP), ESLint (JS/TS), Prettier (formatting).
- [ ] **Static analysis** — PHPStan level 8, `vue-tsc --noEmit`.
- [ ] **Unit & feature tests** — `php artisan test --compact`.
- [ ] **Build** — `npm run build` succeeds.
- [ ] **Security audit** — `composer audit`, `npm audit`.
- [ ] **Migration check** — `php artisan migrate --pretend` shows no errors.

### Pipeline Safety

- [ ] **Fail fast** — Lint and static analysis run before slow tests.
- [ ] **Parallel test execution** — `php artisan test --parallel` for faster feedback.
- [ ] **Cache dependencies** — `composer install` and `npm ci` cached between runs.
- [ ] **No secrets in CI logs** — Mask env variables. `echo "::add-mask::$SECRET"` in GitHub Actions.
- [ ] **Branch protection** — PRs require passing pipeline and review.
- [ ] **No deploy from local** — Only CI/CD pipeline deploys to production.
- [ ] **Rollback plan** — Every deployment has a documented rollback procedure.

---

## 100. Post-Incident Review Checklist

After any production incident, verify these were addressed:

### Immediate

- [ ] **Incident documented** — What happened, when, duration, impact.
- [ ] **Root cause identified** — Not just symptoms.
- [ ] **Fix deployed** — With test coverage for the specific failure.
- [ ] **Affected data assessed** — Any corrupted records? Any data loss?
- [ ] **Users notified** — If they were affected.

### Prevention

- [ ] **Monitoring added** — Alert that would have caught this sooner.
- [ ] **Test added** — Regression test for the specific bug.
- [ ] **Checklist updated** — This document. Add a new check for the failure class.
- [ ] **Related code audited** — Same pattern likely exists elsewhere.
- [ ] **Runbook updated** — Steps to diagnose and fix this class of issue.

### Review

- [ ] **Timeline reconstructed** — From first symptom to full resolution.
- [ ] **Detection time measured** — How long before someone noticed?
- [ ] **Response time measured** — How long from detection to fix deployed?
- [ ] **Blame-free retrospective held** — Focus on systems, not individuals.
- [ ] **Action items assigned and tracked** — With owners and deadlines.

---

## 101. Path Traversal & Directory Escape

- [ ] **Never build file paths from user input directly** — Use `basename()` to strip directory components.

```php
// DANGEROUS
$path = storage_path('reports/' . $request->input('filename'));

// SAFE
$path = storage_path('reports/' . basename($request->input('filename')));
```

- [ ] **Validate file extensions against an allowlist** — `in:pdf,csv,xlsx`, not a denylist.
- [ ] **Use `realpath()` and verify prefix** — Confirm resolved path stays inside the intended directory.

```php
$resolved = realpath($uploadDir . '/' . basename($name));
if (!$resolved || !str_starts_with($resolved, realpath($uploadDir))) {
    abort(403, 'Invalid file path');
}
```

- [ ] **Reject null bytes in file names** — `\0` can truncate paths in older PHP versions.
- [ ] **Zip extraction: validate entry paths** — Zip files can contain `../../etc/passwd` entries. Check each entry with `basename()`.
- [ ] **Storage disk scoping** — Use named disks (`Storage::disk('exports')`) rather than raw file system paths.
- [ ] **No user-controlled `include`/`require`** — Dynamic file inclusion is a critical RCE vector.

---

## 102. XML External Entity (XXE) Prevention

- [ ] **Disable external entities in libxml** — Always call `libxml_disable_entity_loader(true)` before parsing XML (PHP < 8.0) or use `LIBXML_NOENT` flag carefully.

```php
// SAFE — PHP 8.0+ disables entity loading by default
$doc = new DOMDocument();
$doc->loadXML($xml, LIBXML_NONET | LIBXML_NOENT);
```

- [ ] **Use `simplexml_load_string()` with `LIBXML_NONET`** — Prevents network-based XXE.
- [ ] **Reject DTD declarations in input XML** — If your application doesn't need DTDs, reject any XML containing `<!DOCTYPE`.

```php
if (str_contains($xml, '<!DOCTYPE')) {
    throw new InvalidArgumentException('DTD not allowed');
}
```

- [ ] **Prefer JSON over XML** — If you control the API format, avoid XML entirely.
- [ ] **SOAP clients: disable entity expansion** — `SoapClient` options should include `LIBXML_NONET`.
- [ ] **SVG upload XXE** — SVG files are XML. Parse/sanitize uploaded SVGs to strip `<!ENTITY>` declarations.

---

## 103. HTTP Request Smuggling

- [ ] **Use a single reverse proxy** — Mixed proxy/server configurations (nginx + Apache) invite smuggling.
- [ ] **Reject ambiguous `Transfer-Encoding` headers** — Proxy and backend must agree on encoding.
- [ ] **Normalize `Content-Length` and `Transfer-Encoding`** — Never allow both on the same request.
- [ ] **Use HTTP/2 end-to-end where possible** — HTTP/2 is binary-framed and immune to CL/TE smuggling.
- [ ] **Keep web server and proxy versions current** — Smuggling bugs are patched frequently in nginx, Apache, and cloud LBs.
- [ ] **Test with smuggling detection tools** — `smuggler`, `h2csmuggler`, Burp Suite scanner.

---

## 104. Clickjacking & UI Redress

- [ ] **Set `X-Frame-Options: DENY`** — Or `SAMEORIGIN` if you embed your own pages in iframes.

```php
// In middleware or bootstrap/app.php
->withMiddleware(function (Middleware $middleware) {
    $middleware->append(\App\Http\Middleware\SecurityHeaders::class);
})

// SecurityHeaders middleware
$response->headers->set('X-Frame-Options', 'DENY');
```

- [ ] **CSP `frame-ancestors 'none'`** — Modern replacement for `X-Frame-Options`. Use both for defense-in-depth.
- [ ] **Sensitive actions require re-authentication** — Even if framed, a password prompt defeats clickjacking.
- [ ] **No frameable authentication pages** — Login, password reset, and 2FA pages must never be embeddable.
- [ ] **JavaScript frame-busting as last resort** — `if (top !== self) { top.location = self.location; }` — but CSP is more reliable.

---

## 105. Subdomain Takeover Prevention

- [ ] **Audit DNS CNAME records** — Any CNAME pointing to a deprovisioned service (Heroku, S3, Azure, GitHub Pages) is takeover-vulnerable.
- [ ] **Remove DNS records when decommissioning services** — Delete the CNAME before or simultaneously with the service.
- [ ] **Monitor subdomains automatically** — Tools like `subfinder` + scheduled checks.
- [ ] **Wildcard DNS is dangerous** — `*.example.com` CNAME to anything means every subdomain is a target.
- [ ] **Verify after decommission** — `dig subdomain.example.com` should return `NXDOMAIN`, not a dangling CNAME.

---

## 106. Server-Side Request Forgery (SSRF) Deep Dive

- [ ] **Never fetch user-supplied URLs without validation** — Block internal IPs (127.0.0.0/8, 10.0.0.0/8, 172.16.0.0/12, 169.254.169.254).

```php
function isSafeUrl(string $url): bool
{
    $parsed = parse_url($url);
    $host = $parsed['host'] ?? '';
    $ip = gethostbyname($host);

    $blocked = ['127.0.0.1', '0.0.0.0', '::1'];
    if (in_array($ip, $blocked) || filter_var($ip, FILTER_VALIDATE_IP, FILTER_FLAG_NO_PRIV_RANGE | FILTER_FLAG_NO_RES_RANGE) === false) {
        return false;
    }
    return true;
}
```

- [ ] **Block cloud metadata endpoints** — `169.254.169.254` (AWS/GCP/Azure metadata service). Also block `fd00:ec2::254`.
- [ ] **Allowlist outbound domains** — If the application only calls known APIs, enforce a domain allowlist.
- [ ] **DNS rebinding defense** — Resolve the hostname, validate the IP, then connect to the IP directly.
- [ ] **Disable redirects in HTTP client** — Or re-validate after each redirect.
- [ ] **Use `Http::withOptions(['allow_redirects' => false])`** — Prevent SSRF via redirect chains.
- [ ] **Protocol restriction** — Only allow `https://` and `http://`. Block `file://`, `gopher://`, `dict://`, `ftp://`.

---

## 107. Content Security Policy (CSP) Engineering

- [ ] **Start with a restrictive baseline** — `default-src 'self'; script-src 'self'; style-src 'self' 'unsafe-inline'; img-src 'self' data:`.
- [ ] **Use nonces for inline scripts** — Generate per-request: `script-src 'nonce-{{ $nonce }}'`.

```php
// Middleware: generate nonce per request
$nonce = base64_encode(random_bytes(16));
app()->instance('csp-nonce', $nonce);

// Add to CSP header
"script-src 'self' 'nonce-{$nonce}'"
```

- [ ] **No `'unsafe-eval'`** — Blocks `eval()`, `new Function()`, and template compiler exploits.
- [ ] **`'unsafe-inline'` only for styles, never scripts** — Or use style nonces too.
- [ ] **`report-uri` / `report-to` directive** — Collect CSP violations to identify issues before enforcing.
- [ ] **`frame-ancestors 'none'`** — Replaces `X-Frame-Options`.
- [ ] **`base-uri 'self'`** — Prevents `<base>` tag injection that redirects all relative URLs.
- [ ] **`form-action 'self'`** — Prevents form submissions to external domains.
- [ ] **Test with `Content-Security-Policy-Report-Only` first** — Deploy reporting mode before enforcement.

---

## 108. HTTP Security Headers Checklist

Every production response should include:

- [ ] **`Strict-Transport-Security: max-age=31536000; includeSubDomains; preload`** — HSTS with preload.
- [ ] **`Content-Security-Policy`** — See §107.
- [ ] **`X-Content-Type-Options: nosniff`** — Prevents MIME-type sniffing.
- [ ] **`X-Frame-Options: DENY`** — Clickjacking protection.
- [ ] **`Referrer-Policy: strict-origin-when-cross-origin`** — Limits referrer information leakage.
- [ ] **`Permissions-Policy`** — Disable unnecessary browser features: `camera=(), microphone=(), geolocation=()`.
- [ ] **`Cross-Origin-Opener-Policy: same-origin`** — Isolates browsing context.
- [ ] **`Cross-Origin-Resource-Policy: same-origin`** — Prevents cross-origin reads of resources.
- [ ] **`Cross-Origin-Embedder-Policy: require-corp`** — Enables `SharedArrayBuffer` safely (needed for some crypto).
- [ ] **No `Server` header** — Remove or obfuscate. Don't reveal `Apache/2.4.41` or `nginx/1.21.0`.
- [ ] **No `X-Powered-By` header** — Laravel should not expose `X-Powered-By: PHP/8.4`.

```php
// Remove in php.ini or .htaccess
expose_php = Off

// Or in middleware
$response->headers->remove('X-Powered-By');
$response->headers->remove('Server');
```

---

## 109. CORS Misconfiguration

- [ ] **Never use `Access-Control-Allow-Origin: *` with credentials** — Browsers reject it, but it signals misconfiguration intent.
- [ ] **Allowlist specific origins** — Not `*`. Not `$request->header('Origin')` reflected back blindly.

```php
// DANGEROUS — reflects any origin
$response->header('Access-Control-Allow-Origin', $request->header('Origin'));

// SAFE — allowlist
$allowed = ['https://app.example.com', 'https://admin.example.com'];
$origin = $request->header('Origin');
if (in_array($origin, $allowed)) {
    $response->header('Access-Control-Allow-Origin', $origin);
    $response->header('Vary', 'Origin');
}
```

- [ ] **Always include `Vary: Origin`** — When the `Access-Control-Allow-Origin` value changes per request.
- [ ] **Restrict `Access-Control-Allow-Methods`** — Only methods the endpoint actually supports.
- [ ] **Restrict `Access-Control-Allow-Headers`** — Only headers the frontend actually sends.
- [ ] **Set `Access-Control-Max-Age`** — Cache preflight results (e.g., `86400` seconds).
- [ ] **No CORS on non-API routes** — SPA pages served via Inertia don't need CORS headers.
- [ ] **`Access-Control-Allow-Credentials: true` only when needed** — And only with explicit origin (not `*`).

---

## 110. Supply Chain Attack Vectors (Frontend)

- [ ] **Use `npm ci` in CI, not `npm install`** — `ci` uses the lockfile exactly, preventing supply-chain drift.
- [ ] **Pin exact versions in `package.json`** — `"vue": "3.5.13"`, not `"^3.5.0"`.
- [ ] **Audit regularly** — `npm audit --production` in CI pipeline.
- [ ] **Subresource Integrity (SRI)** — CDN-loaded scripts need `integrity="sha384-..."` attributes.
- [ ] **Review lockfile diffs in PRs** — A lockfile change adding 50 new packages is suspicious.
- [ ] **Use `--ignore-scripts` for untrusted packages** — Some packages run arbitrary code on install.
- [ ] **Private registry mirror** — For enterprise: mirror npmjs to a private Verdaccio/Artifactory.
- [ ] **Socket.dev or Snyk for dependency monitoring** — Real-time alerts for compromised packages.
- [ ] **No CDN for critical dependencies** — Bundle everything locally. CDN compromise = full XSS.

---

## 111. Key Management & Rotation

- [ ] **`APP_KEY` rotation uses `APP_PREVIOUS_KEYS`** — Laravel 11+ supports decryption with previous keys during rotation.

```env
APP_KEY=base64:newkey...
APP_PREVIOUS_KEYS=base64:oldkey1...,base64:oldkey2...
```

- [ ] **Schedule key rotation** — At minimum annually, or after any suspected compromise.
- [ ] **Database encrypted columns re-encrypt on rotation** — Write a migration/command that reads and re-saves encrypted fields.
- [ ] **Never store `APP_KEY` in version control** — `.env` files are gitignored. Use secrets managers for production.
- [ ] **Separate encryption keys per purpose** — `APP_KEY` for session/encryption, distinct keys for payment tokenization, API signing.
- [ ] **Key destruction procedure** — Document how to irrevocably destroy old keys after rotation.
- [ ] **HSM for high-value keys** — AWS CloudHSM or GCP Cloud KMS for payment/signing keys.

---

## 112. TLS & Certificate Management

- [ ] **TLS 1.2 minimum, TLS 1.3 preferred** — Disable TLS 1.0 and 1.1 at the web server level.
- [ ] **Strong cipher suites only** — No RC4, no 3DES, no CBC-mode ciphers. Use `ECDHE+AESGCM`.
- [ ] **Auto-renew certificates** — Let's Encrypt + certbot or cloud-managed certificates.
- [ ] **Monitor expiration** — Alert 30 days before certificate expiry. `ssl-cert-check` or cloud monitoring.
- [ ] **OCSP stapling enabled** — Reduces certificate validation latency.
- [ ] **Certificate Transparency logs** — Monitor for unauthorized certificates issued for your domains.
- [ ] **Internal services use TLS too** — Database connections, Redis, queue brokers — all TLS in production.
- [ ] **No self-signed in production** — Self-signed certs are for development only.

---

## 113. Data Classification & Handling Tiers

Define handling rules per data tier:

- [ ] **Tier 1 — Public**: Marketing content, public API docs. No special handling.
- [ ] **Tier 2 — Internal**: Employee names, internal tickets. Access controls required.
- [ ] **Tier 3 — Confidential**: Financial data, customer PII, trade details. Encrypted at rest, audit-logged, access-controlled.
- [ ] **Tier 4 — Restricted**: Passwords, encryption keys, payment tokens, 2FA secrets. Encrypted, masked in logs, HSM-managed, rotated.
- [ ] **Every model/table has a classification** — Document it in a schema comment or model docblock.
- [ ] **Access logging scales with tier** — Tier 4 logs every read, Tier 2 logs writes only.
- [ ] **Retention policies per tier** — Tier 4 data has a defined TTL and destruction procedure.
- [ ] **Data flow diagrams** — Map where each tier flows (browser, API, queue, log, backup) and verify encryption at each hop.

---

## 114. PII Detection & Anonymization

- [ ] **Identify all PII fields** — Email, phone, name, address, IP, date of birth, government IDs. Tag them in model docblocks.
- [ ] **`$hidden` on sensitive model attributes** — Prevents accidental JSON serialization.

```php
protected $hidden = ['password', 'two_factor_secret', 'phone', 'date_of_birth'];
```

- [ ] **Anonymize in non-production environments** — Seeders and database snapshots replace PII with fake data.

```php
// Anonymization command
User::query()->update([
    'email' => DB::raw("CONCAT('user', id, '@example.test')"),
    'phone' => DB::raw("'0000000000'"),
    'name' => DB::raw("CONCAT('User ', id)"),
]);
```

- [ ] **Right to erasure (GDPR Article 17)** — Implement soft-delete + anonymize. Don't just delete — anonymize audit logs too.
- [ ] **No PII in URLs** — `/users/john@example.com` leaks PII in access logs. Use UUIDs.
- [ ] **No PII in log messages** — Log user IDs, not names or emails. See §85.
- [ ] **PII inventory maintained** — A living document listing every field, table, and third-party service that stores PII.

---

## 115. Tokenization & Data Masking

- [ ] **Tokenize payment card numbers** — Never store full PANs. Use payment gateway tokens (Stripe `tok_*`, Paystack `auth_*`).
- [ ] **Mask in display** — `**** **** **** 4242`, `j***@example.com`, `080****5678`.

```php
function maskEmail(string $email): string
{
    [$local, $domain] = explode('@', $email);
    return $local[0] . str_repeat('*', max(strlen($local) - 2, 1)) . $local[-1] . '@' . $domain;
}
```

- [ ] **Mask in logs** — Override `__toString()` or use log formatters that detect and mask patterns.
- [ ] **Distinct tokenization per purpose** — Search tokens, display tokens, and reference tokens should be separate.
- [ ] **Token-to-original mapping stored securely** — The mapping table itself is Tier 4 data.
- [ ] **No reversible tokenization without access control** — Detokenization requires explicit authorization.

---

## 116. At-Rest Encryption Strategies

- [ ] **Use Laravel `encrypted` cast for sensitive model fields** — Transparent encryption/decryption.

```php
protected function casts(): array
{
    return [
        'ssn' => 'encrypted',
        'bank_account' => 'encrypted',
        'two_factor_secret' => 'encrypted',
    ];
}
```

- [ ] **Encrypted fields are not searchable** — Encryption changes the stored value. Use blind indexes for lookups.

```php
// Blind index pattern
$blindIndex = hash_hmac('sha256', $email, config('app.blind_index_key'));
User::where('email_index', $blindIndex)->first();
```

- [ ] **Database-level encryption (TDE)** — MySQL Enterprise or cloud-managed (AWS RDS, GCP Cloud SQL) TDE.
- [ ] **Backup encryption** — `spatie/laravel-backup` supports encryption. Enable it.
- [ ] **File storage encryption** — S3 server-side encryption (`SSE-S3` or `SSE-KMS`).
- [ ] **Key separation** — Encryption key is separate from application key. Column-level encryption uses per-column keys in enterprise setups.
- [ ] **Test that decryption fails gracefully** — If a key is rotated or wrong, don't crash — log and alert.

---

## 117. Secrets Management (Vault / Cloud)

- [ ] **No secrets in `.env` files on servers** — Use AWS Secrets Manager, GCP Secret Manager, HashiCorp Vault, or Laravel Envoyer secrets.
- [ ] **No secrets in environment variables visible to `phpinfo()`** — Disable `phpinfo()` in production.
- [ ] **Rotate secrets on personnel change** — When a developer leaves, rotate all secrets they had access to.
- [ ] **Least privilege for secret access** — Application only reads secrets it needs. No wildcard `secretsmanager:GetSecretValue` on `*`.
- [ ] **Audit secret access** — CloudTrail / Vault audit log shows who accessed which secret and when.
- [ ] **Cache secrets in memory, not disk** — Fetch from vault on boot, hold in-process. Don't write to `/tmp`.
- [ ] **Secret versioning** — Vault/cloud managers support versioned secrets. Reference specific versions in config.
- [ ] **Emergency rotation procedure documented** — "If API key X is compromised: run this command, deploy this config."

---

## 118. Cryptographic Agility

- [ ] **Abstract crypto operations behind interfaces** — So algorithms can be swapped without changing call sites.

```php
interface Hasher
{
    public function hash(string $value): string;
    public function verify(string $value, string $hash): bool;
}

// Swap Bcrypt → Argon2id by changing binding, not call sites
```

- [ ] **`Hash::needsRehash($hash)`** — Laravel automatically rehashes on login when algorithm/cost changes.
- [ ] **Config-driven algorithm selection** — `config('hashing.driver')`, not hardcoded `bcrypt()`.
- [ ] **Document current algorithms** — "As of 2026: Argon2id for passwords, AES-256-GCM for encryption, SHA-256 for HMACs."
- [ ] **Migration path for algorithm changes** — Old hashes are verified and rehashed on next use, not bulk-migrated.
- [ ] **No custom cryptography** — Use well-audited libraries (`sodium`, `openssl`). Never implement your own cipher.
- [ ] **Post-quantum readiness** — Track NIST PQC standards. Plan for ML-KEM/ML-DSA migration when PHP libraries support them.

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

## 179. Bundle Size & Tree Shaking

- [ ] **Analyze bundle size** — `npx vite-bundle-visualizer` or `rollup-plugin-visualizer`.
- [ ] **Tree shaking works only with ES modules** — `import { specific } from 'library'`, not `require('library')`.
- [ ] **Avoid barrel file re-exports** — `import { Button } from '@/components'` may pull in everything. Import directly.

```typescript
// BAD — may include entire component library
import { Button } from '@/components';

// GOOD — tree-shakeable
import Button from '@/components/ui/button/Button.vue';
```

- [ ] **Dynamic imports for route-level code splitting** — Inertia page resolution already does this.
- [ ] **Lazy load heavy libraries** — Charts, date pickers, rich text editors: `const Chart = defineAsyncComponent(() => import('chart.js'))`.
- [ ] **Monitor bundle size in CI** — Fail build if bundle exceeds a threshold.
- [ ] **Remove unused dependencies** — `npx depcheck` identifies packages imported in `package.json` but not used.
- [ ] **CSS purging** — Tailwind 4 purges unused CSS automatically. Verify production CSS size.

---

## 180. Core Web Vitals Optimization

- [ ] **LCP (Largest Contentful Paint) < 2.5s** — Preload critical images, inline critical CSS, server-side render above-the-fold.
- [ ] **FID / INP (Interaction to Next Paint) < 200ms** — Avoid blocking the main thread. Defer non-critical JavaScript.
- [ ] **CLS (Cumulative Layout Shift) < 0.1** — Set explicit dimensions on images/iframes. No layout shifts from lazy-loaded content.
- [ ] **Preload critical assets** — `<link rel="preload" as="font" href="...">` for web fonts.
- [ ] **Compress responses** — Gzip/Brotli for HTML, CSS, JS. Nginx: `gzip on; gzip_types text/css application/javascript;`.
- [ ] **Cache static assets aggressively** — Vite adds content hashes to filenames. Set `Cache-Control: max-age=31536000, immutable`.
- [ ] **Measure in the field** — Lab tests (Lighthouse) differ from field data (CrUX). Use both.
- [ ] **Font display strategy** — `font-display: swap` prevents invisible text during font load.
- [ ] **Avoid render-blocking resources** — Defer non-critical CSS and JS. Use `async` or `defer` on script tags.

---

## 181. SPA State Management Patterns

- [ ] **Server is the source of truth** — Inertia page props are the canonical state. Don't duplicate in client stores.
- [ ] **Use `useForm()` for form state** — Handles submission, errors, processing state, and dirty tracking.

```typescript
const form = useForm({
    amount: '',
    account_id: '',
    description: '',
});

form.post(route('journal-entries.store'), {
    preserveScroll: true,
    onSuccess: () => form.reset(),
});
```

- [ ] **Composables for shared reactive state** — `useAuth()`, `useToast()`, `useSettings()`.
- [ ] **Don't use Pinia/Vuex with Inertia** — Inertia replaces the need for a client-side store. Use shared props and composables.
- [ ] **Optimistic updates with rollback** — Update the UI immediately, revert if the server rejects.
- [ ] **Debounce search inputs** — Don't fire a request on every keystroke. Use `watchDebounced` or manual `setTimeout`.
- [ ] **Clear state on navigation** — `router.on('navigate', () => { resetState() })`.
- [ ] **URL is state** — Use query parameters for filters, pagination, sorting. `router.visit(url, { data: { page: 2 } })`.

---

## 182. Form UX & Validation Patterns

- [ ] **Server-side validation is the authority** — Client-side validation is for UX, not security.
- [ ] **Display server errors per field** — Inertia provides `$page.props.errors` mapped to field names.

```vue
<template>
  <Input v-model="form.amount" :error="form.errors.amount" />
  <span v-if="form.errors.amount" class="text-sm text-red-600">{{ form.errors.amount }}</span>
</template>
```

- [ ] **Disable submit button during processing** — `form.processing` is `true` during submission.
- [ ] **Preserve scroll position on error** — `preserveScroll: true` in form options.
- [ ] **Clear errors on input change** — `form.clearErrors('amount')` when the user modifies the field.
- [ ] **Confirm destructive actions** — Delete, void, reverse: show a confirmation dialog before submitting.
- [ ] **Success feedback** — Toast notification or banner after successful submission.
- [ ] **Disable browser autofill on accounting forms** — `autocomplete="off"` on all inputs (see CLAUDE.md form security rule).
- [ ] **Tab order is logical** — `tabindex` follows the visual flow. Date → Amount → Account → Description → Submit.

---

## 183. Error Boundaries & Fallback UI

- [ ] **Vue `onErrorCaptured()` for component-level error boundaries** — Catch rendering errors without crashing the whole page.

```typescript
onErrorCaptured((error, instance, info) => {
    console.error('Component error:', error, info);
    showFallback.value = true;
    return false; // Prevent propagation
});
```

- [ ] **Fallback UI for failed deferred props** — If a deferred data load fails, show an error state, not a spinner forever.
- [ ] **Global error handler** — `app.config.errorHandler` catches unhandled errors across all components.
- [ ] **Retry buttons** — When a data fetch fails, show "Something went wrong. [Retry]" not a blank screen.
- [ ] **Graceful degradation for non-critical features** — If the notification count API fails, hide the badge; don't crash the navbar.
- [ ] **Error tracking integration** — Send frontend errors to Sentry, Bugsnag, or a custom endpoint.
- [ ] **No unhandled promise rejections** — Every `async` call has a `.catch()` or is inside a `try/catch`.

---

## 184. Progressive Enhancement

- [ ] **Core functionality works without JavaScript** — For SSR/Inertia apps, ensure the initial render is meaningful.
- [ ] **No-JS fallback for critical flows** — Login, password reset should work even if JS fails to load.
- [ ] **Loading states for deferred content** — Skeleton screens, not blank areas.

```vue
<Deferred>
  <template #fallback>
    <div class="animate-pulse h-8 bg-gray-200 rounded w-full" />
  </template>
  <ReportTable :data="reportData" />
</Deferred>
```

- [ ] **Semantic HTML first** — Use `<button>`, `<a>`, `<form>`, `<table>`, `<nav>`. Not `<div @click>`.
- [ ] **Links are links, buttons are buttons** — Navigation = `<a>`. Actions = `<button>`. Never the reverse.
- [ ] **Print stylesheets** — Financial reports should be printable. `@media print { ... }`.
- [ ] **Reduced motion** — `@media (prefers-reduced-motion: reduce) { animation: none; }`.

---

## 185. Keyboard Navigation & Focus Management

- [ ] **All interactive elements are focusable** — Buttons, links, inputs. Not `<div>` or `<span>` with click handlers.
- [ ] **Visible focus indicators** — `:focus-visible` ring on all interactive elements. Don't remove outlines.

```css
:focus-visible {
  outline: 2px solid var(--tally-accent);
  outline-offset: 2px;
}
```

- [ ] **Tab order follows visual order** — Don't use `tabindex > 0`. Use `tabindex="0"` or rely on DOM order.
- [ ] **Escape closes modals and popovers** — Consistent keyboard dismissal.
- [ ] **Arrow keys navigate within composite widgets** — Dropdown menus, date pickers, tab groups.
- [ ] **Skip navigation link** — First focusable element: "Skip to main content" link.
- [ ] **Focus trapping in modals** — Tab cycles within the modal, not behind it.
- [ ] **Announce dynamic changes** — `aria-live="polite"` for status messages, toast notifications.
- [ ] **Keyboard shortcuts documented** — If the app has shortcuts (Ctrl+S to save), document them in a help modal.

---

## 186. Frontend Error Tracking

- [ ] **Capture unhandled exceptions** — `window.addEventListener('error', handler)` and `window.addEventListener('unhandledrejection', handler)`.
- [ ] **Source maps in production (private)** — Upload source maps to Sentry/Bugsnag for readable stack traces. Don't serve them publicly.
- [ ] **Context with errors** — Include: current route, user ID (anonymized), browser/OS, page props.
- [ ] **Breadcrumbs** — Track recent user actions (clicks, navigation, API calls) leading up to the error.
- [ ] **Rate limit error reporting** — Don't send 10,000 identical error reports per minute. Deduplicate.
- [ ] **Distinguish errors by severity** — Network timeout vs. TypeError vs. ChunkLoadError require different responses.
- [ ] **`ChunkLoadError` handling** — When a deployment changes chunk hashes, old pages fail. Auto-reload on `ChunkLoadError`.

```typescript
router.on('exception', (event) => {
    if (event.detail.error?.name === 'ChunkLoadError') {
        window.location.reload();
    }
});
```

---

## 187. Image Optimization & Lazy Loading

- [ ] **Serve modern formats** — WebP or AVIF instead of PNG/JPEG. 30-50% smaller.
- [ ] **Responsive images** — `srcset` and `sizes` attributes for different viewport widths.

```html
<img
  src="/images/report-800.webp"
  srcset="/images/report-400.webp 400w, /images/report-800.webp 800w, /images/report-1200.webp 1200w"
  sizes="(max-width: 600px) 400px, (max-width: 1024px) 800px, 1200px"
  alt="Financial report"
  loading="lazy"
  decoding="async"
  width="800"
  height="600"
>
```

- [ ] **`loading="lazy"` for below-the-fold images** — Native browser lazy loading.
- [ ] **`decoding="async"`** — Don't block rendering while decoding images.
- [ ] **Explicit `width` and `height`** — Prevents layout shift (CLS).
- [ ] **SVG for icons and logos** — Scalable, small, no quality loss.
- [ ] **No images in CSS `background-image` for content images** — Use `<img>` for semantic images. CSS backgrounds for decoration.
- [ ] **Image CDN for dynamic resizing** — Cloudflare Images, Imgix, or similar. Resize on the edge, not the server.

---

## 188. Dark Mode Implementation

- [ ] **CSS custom properties for theme colors** — Switch values, not classes.

```css
:root {
  --bg-primary: #ffffff;
  --text-primary: #1a1a2e;
}

.dark {
  --bg-primary: #1a1a2e;
  --text-primary: #e0e0e0;
}
```

- [ ] **Respect `prefers-color-scheme`** — Default to the user's OS preference.
- [ ] **User override persisted** — If the user chooses light/dark, save it (localStorage or database).
- [ ] **Tailwind `dark:` variant** — `bg-white dark:bg-gray-900 text-gray-900 dark:text-gray-100`.
- [ ] **Test both modes** — Every page, every component, every state. Not just the happy path.
- [ ] **Sufficient contrast in both modes** — WCAG AA: 4.5:1 for normal text, 3:1 for large text.
- [ ] **No flash on load** — Apply the theme class before rendering. Use a `<script>` in `<head>` to set `.dark` synchronously.
- [ ] **Charts and graphs adapt** — Grid lines, labels, and legends must be readable in both modes.
- [ ] **Print always uses light mode** — `@media print { .dark { /* reset to light */ } }`.

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

## 197. API Versioning Strategies

- [ ] **URI versioning is simplest** — `/api/v1/users`, `/api/v2/users`.

```php
Route::prefix('api/v1')->group(function () {
    Route::apiResource('journal-entries', V1\JournalEntryController::class);
});

Route::prefix('api/v2')->group(function () {
    Route::apiResource('journal-entries', V2\JournalEntryController::class);
});
```

- [ ] **Header versioning for cleaner URLs** — `Accept: application/vnd.app.v2+json`. More RESTful but harder to test.
- [ ] **Version transformers, not models** — The underlying model is the same; the API Resource formats it differently per version.
- [ ] **Deprecation notices** — Sunset header: `Sunset: Sat, 01 Jan 2027 00:00:00 GMT`. Inform consumers of timeline.
- [ ] **Backward compatibility within a version** — Adding fields is fine. Removing or renaming fields is a breaking change.
- [ ] **Run both versions' tests** — Until v1 is fully sunset, both test suites must pass.
- [ ] **Maximum 2 active versions** — More than 2 active versions is a maintenance burden.
- [ ] **Document migration guide** — Per-endpoint changes from v1 to v2.

---

## 198. Circuit Breaker Pattern

- [ ] **Wrap external service calls in a circuit breaker** — Prevent cascade failures when a dependency is down.

```php
class CircuitBreaker
{
    private int $failures = 0;
    private int $threshold = 5;
    private ?CarbonImmutable $openUntil = null;

    public function call(Closure $action): mixed
    {
        if ($this->isOpen()) {
            throw new CircuitOpenException('Service unavailable');
        }

        try {
            $result = $action();
            $this->reset();
            return $result;
        } catch (Throwable $e) {
            $this->recordFailure();
            throw $e;
        }
    }

    private function isOpen(): bool
    {
        return $this->openUntil !== null && now()->lt($this->openUntil);
    }

    private function recordFailure(): void
    {
        $this->failures++;
        if ($this->failures >= $this->threshold) {
            $this->openUntil = now()->addSeconds(30); // Open for 30s
        }
    }

    private function reset(): void
    {
        $this->failures = 0;
        $this->openUntil = null;
    }
}
```

- [ ] **Three states** — **Closed** (normal), **Open** (failing fast), **Half-Open** (testing recovery).
- [ ] **Configurable thresholds** — Failure count, timeout duration, half-open test count.
- [ ] **Per-service breakers** — Each external dependency has its own circuit breaker.
- [ ] **Fallback behavior** — When the circuit is open, return cached data, a default response, or a graceful error.
- [ ] **Monitor circuit state** — Dashboard showing which circuits are open and for how long.
- [ ] **Cache-backed state for multi-process** — Store circuit state in Redis so all workers share it.

---

## 199. Webhook Sending Best Practices

- [ ] **Sign webhook payloads** — HMAC-SHA256 with a per-subscriber secret.

```php
$payload = json_encode($data);
$signature = hash_hmac('sha256', $payload, $subscriber->webhook_secret);
$response = Http::withHeaders([
    'X-Signature-256' => "sha256={$signature}",
    'X-Webhook-ID' => $webhookId,
    'X-Webhook-Timestamp' => now()->unix(),
])->post($subscriber->webhook_url, $data);
```

- [ ] **Include a unique event ID** — For receiver deduplication.
- [ ] **Include a timestamp** — For receiver freshness checks.
- [ ] **Retry with exponential backoff** — 5s, 30s, 5m, 30m, 2h. Max 5 retries.
- [ ] **Log delivery attempts** — Status code, response time, retry count per webhook delivery.
- [ ] **Disable endpoints after repeated failures** — After 10 consecutive failures, mark the endpoint as inactive. Notify the subscriber.
- [ ] **Async delivery** — Send webhooks via queued jobs, not synchronously during the request.
- [ ] **Payload size limits** — Don't send 10MB payloads. Send a summary with a link to fetch full data.
- [ ] **Verify subscriber URL** — Block internal IPs and private networks (SSRF prevention, §106).

---

## 200. Message Queue Reliability Patterns

- [ ] **At-least-once delivery** — Assume messages may be delivered more than once. Make handlers idempotent.
- [ ] **Dead letter queue (DLQ)** — Messages that fail after all retries go to a DLQ for manual inspection.

```php
class ProcessSettlement implements ShouldQueue
{
    public int $tries = 5;
    public array $backoff = [5, 30, 300, 1800, 7200];

    public function failed(Throwable $exception): void
    {
        // Notify ops team
        Log::critical('Settlement processing failed permanently', [
            'settlement_id' => $this->settlement->id,
            'exception' => $exception->getMessage(),
        ]);
    }
}
```

- [ ] **Job batching for related operations** — `Bus::batch([...])` with `then`, `catch`, `finally` callbacks.
- [ ] **Unique jobs** — `ShouldBeUnique` prevents duplicate job processing.
- [ ] **Job chaining for sequential processing** — `Bus::chain([StepOne::class, StepTwo::class])->dispatch()`.
- [ ] **Timeout protection** — `public int $timeout = 60;` kills jobs that run too long.
- [ ] **Monitor queue depth** — Alert when the queue backlog exceeds a threshold (e.g., > 1000 pending jobs).
- [ ] **Separate queues by priority** — `--queue=critical,default,low`. Critical jobs process first.
- [ ] **Graceful shutdown** — `php artisan queue:restart` after deployments to pick up new code.
- [ ] **Horizon for monitoring** — Laravel Horizon provides real-time dashboards for Redis-based queues.

---

*End of The Laravel Fortress — 200 sections, 14 parts, 2,000+ checks.*
