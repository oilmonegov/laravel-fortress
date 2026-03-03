---
description: "Laravel Fortress Part 3 — Authentication & Authorization. 13 sections, 110 checks covering Auth flows, RBAC, policies, rate limiting, session security, OAuth, JWT."
---

# Fortress: Authentication & Authorization

> Part III of The Laravel Fortress — 13 sections · 110 checks
> https://github.com/oilmonegov/laravel-fortress/blob/main/parts/03-authentication-authorization.md

When writing or reviewing code, enforce these rules.
Check `.fortress.yml` in the project root for overrides.

## Project Awareness

Before enforcing rules, read `composer.json` and `package.json` to detect the project's PHP version,
Laravel version, and installed packages. Skip rules whose conditions don't match the detected stack.

## Rules

### Authorization Checks

[F-P03-001] **CRITICAL** — Every controller method must authorize
[F-P03-002] **CRITICAL** — Pass model instances to `authorize()`, not class strings
[F-P03-003] **CRITICAL** — Use `Gate::allows()` / `$user->can()` for non-controller checks
[F-P03-004] **CRITICAL** — Scope queries to the authenticated user
[F-P03-005] **CRITICAL** — Use `can()` not `hasPermissionTo()`

### Permission Design

[F-P03-006] **CRITICAL** — Permissions are granular, not boolean
[F-P03-007] **CRITICAL** — Roles contain permissions, not logic
[F-P03-008] **CRITICAL** — Super-admin bypass uses `Gate::before`
[F-P03-009] **CRITICAL** — Protect super-admin from modification
[F-P03-010] **CRITICAL** — Define permissions in an enum, not as raw strings

### Segregation of Duties

[F-P03-011] **CRITICAL** — Creator cannot approve their own resource
[F-P03-012] **CRITICAL** — Enforce SoD inside DB transactions
[F-P03-013] **CRITICAL** — Document accepted SoD bypasses

### Fillable vs Guarded

[F-P03-014] **CRITICAL** — Every model declares explicit `$fillable`
[F-P03-015] **CRITICAL** — Never use `$guarded = []`
[F-P03-016] **CRITICAL** — Never call `Model::unguard()`

### Sensitive Fields That Must NOT Be Fillable

[F-P03-017] **CRITICAL** — Audit every model's `$fillable` for privilege escalation fields
[F-P03-018] **CRITICAL** — Use `$request->validated()` or `$request->safe()->only([...])`

### Hidden & Appended Fields

[F-P03-019] **CRITICAL** — `$hidden` on sensitive fields
[F-P03-020] **CRITICAL** — Never expose internal IDs unnecessarily

### Policy Design Patterns

[F-P03-021] **CRITICAL** — One policy per model
[F-P03-022] **CRITICAL** — Policy methods mirror controller methods
[F-P03-023] **CRITICAL** — Check ownership AND permission
[F-P03-024] **CRITICAL** — `before()` for blanket overrides
[F-P03-025] **CRITICAL** — Return `null` from `before()` to fall through
[F-P03-026] **CRITICAL** — Don't call other policies from within a policy
[F-P03-027] **CRITICAL** — Test policies in isolation

### Gate & Authorization Edge Cases

[F-P03-028] **CRITICAL** — `Gate::before` runs before every check
[F-P03-029] **CRITICAL** — `Gate::after` runs after policy
[F-P03-030] **CRITICAL** — Guest users
[F-P03-031] **CRITICAL** — `authorize()` throws `AuthorizationException`
[F-P03-032] **CRITICAL** — `can()` returns boolean
[F-P03-033] **CRITICAL** — Resource authorization
[F-P03-034] **CRITICAL** — Response-based authorization

### Granular Limiters

[F-P03-035] **CRITICAL** — Per-user rate limits for authenticated routes
[F-P03-036] **CRITICAL** — Per-IP rate limits for unauthenticated routes
[F-P03-037] **CRITICAL** — Per-route rate limits
[F-P03-038] **CRITICAL** — Compound keys

### Response Headers

[F-P03-039] **CRITICAL** — Include `X-RateLimit-Limit` header
[F-P03-040] **CRITICAL** — Include `X-RateLimit-Remaining` header
[F-P03-041] **CRITICAL** — Include `Retry-After` header on 429

### Anti-Abuse

[F-P03-042] **CRITICAL** — Rate limit failed attempts more aggressively
[F-P03-043] **CRITICAL** — Progressive rate limiting
[F-P03-044] **CRITICAL** — Rate limit by feature, not just endpoint

### OAuth 2.0 & OpenID Connect

[F-P03-045] **CRITICAL** — Use PKCE for all OAuth flows
[F-P03-046] **CRITICAL** — Validate `state` parameter
[F-P03-047] **CRITICAL** — Validate `id_token` signature
[F-P03-048] **CRITICAL** — Validate `iss` (issuer) and `aud` (audience) claims
[F-P03-049] **CRITICAL** — Short-lived access tokens
[F-P03-050] **CRITICAL** — Store refresh tokens encrypted
[F-P03-051] **CRITICAL** — Revoke tokens on logout
[F-P03-052] **CRITICAL** — Validate `nonce` claim
[F-P03-053] **CRITICAL** — Scope minimization
[F-P03-054] **CRITICAL** — Token introspection for opaque tokens

### JWT Security Hardening

[F-P03-055] **CRITICAL** — Always verify the signature
[F-P03-056] **CRITICAL** — Pin the algorithm server-side
[F-P03-057] **CRITICAL** — Validate `exp`, `iat`, `nbf` claims
[F-P03-058] **CRITICAL** — Short expiration
[F-P03-059] **CRITICAL** — `jti` claim for revocation
[F-P03-060] **CRITICAL** — Asymmetric keys for distributed systems
[F-P03-061] **CRITICAL** — No sensitive data in JWT payload
[F-P03-062] **CRITICAL** — Rotate signing keys periodically
[F-P03-063] **CRITICAL** — `kid` (Key ID) header

### API Key Lifecycle Management

[F-P03-064] **CRITICAL** — Hash API keys before storage
[F-P03-065] **CRITICAL** — Prefix keys for identification
[F-P03-066] **CRITICAL** — Expiration dates on all keys
[F-P03-067] **CRITICAL** — Scope/permission per key
[F-P03-068] **CRITICAL** — Rate limit per key
[F-P03-069] **CRITICAL** — Revocation is instant
[F-P03-070] **CRITICAL** — Audit log per key
[F-P03-071] **CRITICAL** — Key rotation without downtime

### Session Fixation & Hijacking Deep Dive

[F-P03-072] **CRITICAL** — `Session::regenerate()` on login
[F-P03-073] **CRITICAL** — `Session::invalidate()` on logout
[F-P03-074] **CRITICAL** — Session ID not in URL
[F-P03-075] **CRITICAL** — `HttpOnly` flag on session cookie
[F-P03-076] **CRITICAL** — `Secure` flag on session cookie
[F-P03-077] **CRITICAL** — `SameSite=Lax` or `Strict`
[F-P03-078] **CRITICAL** — Session timeout
[F-P03-079] **CRITICAL** — Absolute session timeout
[F-P03-080] **CRITICAL** — Concurrent session limits
[F-P03-081] **CRITICAL** — Bind session to user-agent or IP

### Bot Detection & Brute Force Mitigation

[F-P03-082] **CRITICAL** — Rate limit login attempts
[F-P03-083] **CRITICAL** — Progressive delays
[F-P03-084] **CRITICAL** — Account lockout after threshold
[F-P03-085] **CRITICAL** — CAPTCHA on repeated failures
[F-P03-086] **CRITICAL** — IP-based rate limiting
[F-P03-087] **CRITICAL** — Credential stuffing detection
[F-P03-088] **CRITICAL** — Honeypot fields
[F-P03-089] **CRITICAL** — Timing-safe responses

### RBAC vs ABAC Design Patterns

[F-P03-090] **CRITICAL** — RBAC (Role-Based Access Control)
[F-P03-091] **CRITICAL** — ABAC (Attribute-Based Access Control)
[F-P03-092] **CRITICAL** — Don't mix raw role checks with permission checks
[F-P03-093] **CRITICAL** — Permissions are the atoms, roles are groups
[F-P03-094] **CRITICAL** — Avoid role proliferation
[F-P03-095] **CRITICAL** — Document the permission matrix

### Privilege Escalation Prevention

[F-P03-096] **CRITICAL** — Users cannot modify their own roles
[F-P03-097] **CRITICAL** — Users cannot assign roles higher than their own
[F-P03-098] **CRITICAL** — Admin panel access is audited
[F-P03-099] **CRITICAL** — `SuperAdmin` bypass is minimal
[F-P03-100] **CRITICAL** — No hidden admin routes
[F-P03-101] **CRITICAL** — Horizontal privilege escalation
[F-P03-102] **CRITICAL** — Vertical privilege escalation

### Impersonation Safety

[F-P03-103] **CRITICAL** — Impersonation requires explicit permission
[F-P03-104] **CRITICAL** — Cannot impersonate higher-privileged users
[F-P03-105] **CRITICAL** — Store original user ID in session
[F-P03-106] **CRITICAL** — Visual indicator when impersonating
[F-P03-107] **CRITICAL** — Impersonation is audit-logged
[F-P03-108] **CRITICAL** — Impersonated sessions cannot change security settings
[F-P03-109] **CRITICAL** — Auto-expire impersonation
[F-P03-110] **CRITICAL** — Impersonation cannot be chained
