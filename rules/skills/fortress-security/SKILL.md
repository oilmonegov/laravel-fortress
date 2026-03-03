---
description: "Laravel Fortress Part 1 — Application Security. 22 sections, 179 checks covering OWASP, CSP, CORS, SSRF, XSS, CSRF, injection prevention, security headers."
---

# Fortress: Application Security

> Part I of The Laravel Fortress — 22 sections · 179 checks
> https://github.com/oilmonegov/laravel-fortress/blob/main/parts/01-application-security.md

When writing or reviewing code, enforce these rules.
Check `.fortress.yml` in the project root for overrides.

## Project Awareness

Before enforcing rules, read `composer.json` and `package.json` to detect the project's PHP version,
Laravel version, and installed packages. Skip rules whose conditions don't match the detected stack.

## Rules

### SQL Injection

[F-P01-001] **CRITICAL** — Use Eloquent or Query Builder with parameter binding
[F-P01-002] **CRITICAL** — Validate column names in `orderBy()` / `groupBy()`
[F-P01-003] **CRITICAL** — Never pass untrusted input to `Rule::unique()` ignore parameter
[F-P01-004] **CRITICAL** — Escape LIKE wildcards

### XSS (Cross-Site Scripting)

[F-P01-005] **CRITICAL** — Use `{{ }}` (escaped) in Blade, never `{!! !!}` with untrusted data
[F-P01-006] **CRITICAL** — Sanitize rich text with HTMLPurifier
[F-P01-007] **CRITICAL** — Set Content-Security-Policy headers
[F-P01-008] **CRITICAL** — Vue/React auto-escapes by default

### CSRF

[F-P01-009] **CRITICAL** — All state-changing routes use POST/PUT/PATCH/DELETE
[F-P01-010] **CRITICAL** — `@csrf` directive in every form
[F-P01-011] **CRITICAL** — SPA sends X-XSRF-TOKEN header

### Command Injection

[F-P01-012] **CRITICAL** — Use `escapeshellarg()` / `escapeshellcmd()`
[F-P01-013] **CRITICAL** — Prefer Laravel Process facade
[F-P01-014] **CRITICAL** — Never use `eval()`, `exec()` with unsanitised input, `extract()`, or `unserialize()` on untrusted data

### HTTP Security Headers

[F-P01-015] **CRITICAL** — `X-Frame-Options: DENY`
[F-P01-016] **CRITICAL** — `X-Content-Type-Options: nosniff`
[F-P01-017] **CRITICAL** — `Strict-Transport-Security: max-age=31536000; includeSubDomains`
[F-P01-018] **CRITICAL** — `Referrer-Policy: strict-origin-when-cross-origin`
[F-P01-019] **CRITICAL** — `Permissions-Policy: camera=(), microphone=(), geolocation=()`
[F-P01-020] **CRITICAL** — Content-Security-Policy

### CORS

[F-P01-021] **CRITICAL** — Never use `'*'` for `allowed_origins` in production
[F-P01-022] **CRITICAL** — Limit `allowed_methods` to what the API actually uses
[F-P01-023] **CRITICAL** — Set `supports_credentials: true` only when needed

### Server-Side Request Forgery (SSRF)

[F-P01-024] **CRITICAL** — Validate and allowlist URLs before fetching
[F-P01-025] **CRITICAL** — Block internal IP ranges

### API Security

[F-P01-026] **CRITICAL** — Rate limit all endpoints
[F-P01-027] **CRITICAL** — Use Sanctum for API token auth
[F-P01-028] **CRITICAL** — API versioning
[F-P01-029] **CRITICAL** — Use API Resources
[F-P01-030] **CRITICAL** — Pagination
[F-P01-031] **CRITICAL** — No sensitive data in URLs

### Session & Cookie Security

[F-P01-032] **CRITICAL** — Regenerate session after login
[F-P01-033] **CRITICAL** — Invalidate session on logout
[F-P01-034] **CRITICAL** — Short session lifetime for sensitive apps
[F-P01-035] **CRITICAL** — Use `redis` or `database` session driver

### File Upload & Storage Security

[F-P01-036] **CRITICAL** — Validate MIME type and extension
[F-P01-037] **CRITICAL** — Never use the original filename
[F-P01-038] **CRITICAL** — Store uploads outside the web root
[F-P01-039] **CRITICAL** — Use signed temporary URLs for downloads
[F-P01-040] **CRITICAL** — Prevent directory traversal
[F-P01-041] **CRITICAL** — Limit file size
[F-P01-042] **CRITICAL** — Scan for malware
[F-P01-043] **CRITICAL** — No executable uploads

### Route Definition

[F-P01-044] **CRITICAL** — Name all routes
[F-P01-045] **CRITICAL** — Group routes by authentication/authorization
[F-P01-046] **CRITICAL** — No sensitive operations on GET routes
[F-P01-047] **CRITICAL** — Use `Route::permanentRedirect()` for old URLs
[F-P01-048] **CRITICAL** — Remove debug/test routes in production
[F-P01-049] **CRITICAL** — Disable route listing in production

### Middleware Ordering

[F-P01-050] **CRITICAL** — Auth before authorization
[F-P01-051] **CRITICAL** — Rate limiting early in the stack
[F-P01-052] **CRITICAL** — CORS before everything

### Route Model Binding

[F-P01-053] **CRITICAL** — Use implicit route model binding
[F-P01-054] **CRITICAL** — Scope bindings to parent models
[F-P01-055] **CRITICAL** — Use custom route keys for public URLs

### PHP Object Injection

[F-P01-056] **CRITICAL** — Never use `unserialize()` on untrusted data
[F-P01-057] **CRITICAL** — Use `allowed_classes: false` if `unserialize()` is unavoidable
[F-P01-058] **CRITICAL** — Never store serialized PHP objects in database/cache without JSON alternative

### JSON Safety

[F-P01-059] **CRITICAL** — Use `JSON_THROW_ON_ERROR` flag
[F-P01-060] **CRITICAL** — Limit JSON decode depth
[F-P01-061] **CRITICAL** — Use `JSON_PRETTY_PRINT` only for debugging

### Header Injection

[F-P01-062] **CRITICAL** — Never interpolate user input into email headers

### Email Best Practices

[F-P01-063] **CRITICAL** — Validate email addresses with `email:rfc,dns`
[F-P01-064] **CRITICAL** — Queue all email sending
[F-P01-065] **CRITICAL** — Rate limit email sending
[F-P01-066] **CRITICAL** — Use vague messages for password reset
[F-P01-067] **CRITICAL** — Set SPF, DKIM, and DMARC records
[F-P01-068] **CRITICAL** — Use reply-to, not from, for user-generated emails
[F-P01-069] **CRITICAL** — Limit attachment sizes and types

### Blade

[F-P01-070] **CRITICAL** — `{{ }}` for all dynamic content
[F-P01-071] **CRITICAL** — `{!! !!}` only for pre-sanitized HTML
[F-P01-072] **CRITICAL** — No user data in `@php` blocks
[F-P01-073] **CRITICAL** — No user data in `@include()` paths

### Vue / Inertia Component Security

[F-P01-074] **CRITICAL** — No `v-html` with user data
[F-P01-075] **CRITICAL** — Validate `href` and `src` attributes
[F-P01-076] **CRITICAL** — Don't pass sensitive data as props
[F-P01-077] **CRITICAL** — Don't expose env variables to frontend

### Component Props

[F-P01-078] **CRITICAL** — Type all component props
[F-P01-079] **CRITICAL** — Validate prop values
[F-P01-080] **CRITICAL** — Default values for optional props

### Open Redirect

[F-P01-081] **CRITICAL** — Never redirect to user-supplied URLs without validation
[F-P01-082] **CRITICAL** — Use `redirect()->intended()` for post-login redirects
[F-P01-083] **CRITICAL** — Validate `url` type inputs with `url` rule AND domain allowlist

### Insecure Direct Object Reference (IDOR)

[F-P01-084] **CRITICAL** — Always scope queries to the authenticated user
[F-P01-085] **CRITICAL** — Use policies for ownership checks
[F-P01-086] **CRITICAL** — Use UUIDs instead of sequential IDs in URLs
[F-P01-087] **CRITICAL** — Route model binding with scoped relationships

### Formula Injection (CSV Injection / DDE)

[F-P01-088] **CRITICAL** — Sanitize cell values starting with `=`, `+`, `-`, `@`, `\t`, `\r`
[F-P01-089] **CRITICAL** — Apply to all CSV, XLSX, and TSV exports
[F-P01-090] **CRITICAL** — Apply to all user-provided columns

### Export Safety

[F-P01-091] **CRITICAL** — Set `Content-Disposition: attachment`
[F-P01-092] **CRITICAL** — Set correct `Content-Type`
[F-P01-093] **CRITICAL** — Limit exported columns
[F-P01-094] **CRITICAL** — Audit who exports what

### Channel Authorization

[F-P01-095] **CRITICAL** — All private and presence channels require authorization
[F-P01-096] **CRITICAL** — Never send sensitive data on public channels
[F-P01-097] **CRITICAL** — Presence channels expose user info

### Payload Safety

[F-P01-098] **CRITICAL** — Sanitize broadcast payloads
[F-P01-099] **CRITICAL** — Limit payload size
[F-P01-100] **CRITICAL** — Use private channels for user-specific data

### Host Header Validation

[F-P01-101] **CRITICAL** — Set `APP_URL` correctly
[F-P01-102] **CRITICAL** — `TrustHosts` middleware configured
[F-P01-103] **CRITICAL** — Never use `$request->getHost()` for security decisions without validation
[F-P01-104] **CRITICAL** — Password reset links use `APP_URL`, not request host

### Trusted Proxies

[F-P01-105] **CRITICAL** — `TrustProxies` middleware configured
[F-P01-106] **CRITICAL** — Don't trust `*` for proxies in production

### Path Traversal & Directory Escape

[F-P01-107] **CRITICAL** — Never build file paths from user input directly
[F-P01-108] **CRITICAL** — Validate file extensions against an allowlist
[F-P01-109] **CRITICAL** — Use `realpath()` and verify prefix
[F-P01-110] **CRITICAL** — Reject null bytes in file names
[F-P01-111] **CRITICAL** — Zip extraction: validate entry paths
[F-P01-112] **CRITICAL** — Storage disk scoping
[F-P01-113] **CRITICAL** — No user-controlled `include`/`require`

### XML External Entity (XXE) Prevention

[F-P01-114] **CRITICAL** — Disable external entities in libxml
[F-P01-115] **CRITICAL** — Use `simplexml_load_string()` with `LIBXML_NONET`
[F-P01-116] **CRITICAL** — Reject DTD declarations in input XML
[F-P01-117] **CRITICAL** — Prefer JSON over XML
[F-P01-118] **CRITICAL** — SOAP clients: disable entity expansion
[F-P01-119] **CRITICAL** — SVG upload XXE

### HTTP Request Smuggling

[F-P01-120] **CRITICAL** — Use a single reverse proxy
[F-P01-121] **CRITICAL** — Reject ambiguous `Transfer-Encoding` headers
[F-P01-122] **CRITICAL** — Normalize `Content-Length` and `Transfer-Encoding`
[F-P01-123] **CRITICAL** — Use HTTP/2 end-to-end where possible
[F-P01-124] **CRITICAL** — Keep web server and proxy versions current
[F-P01-125] **CRITICAL** — Test with smuggling detection tools

### Clickjacking & UI Redress

[F-P01-126] **CRITICAL** — Set `X-Frame-Options: DENY`
[F-P01-127] **CRITICAL** — CSP `frame-ancestors 'none'`
[F-P01-128] **CRITICAL** — Sensitive actions require re-authentication
[F-P01-129] **CRITICAL** — No frameable authentication pages
[F-P01-130] **CRITICAL** — JavaScript frame-busting as last resort

### Subdomain Takeover Prevention

[F-P01-131] **CRITICAL** — Audit DNS CNAME records
[F-P01-132] **CRITICAL** — Remove DNS records when decommissioning services
[F-P01-133] **CRITICAL** — Monitor subdomains automatically
[F-P01-134] **CRITICAL** — Wildcard DNS is dangerous
[F-P01-135] **CRITICAL** — Verify after decommission

### Server-Side Request Forgery (SSRF) Deep Dive

[F-P01-136] **CRITICAL** — Never fetch user-supplied URLs without validation
[F-P01-137] **CRITICAL** — Block cloud metadata endpoints
[F-P01-138] **CRITICAL** — Allowlist outbound domains
[F-P01-139] **CRITICAL** — DNS rebinding defense
[F-P01-140] **CRITICAL** — Disable redirects in HTTP client
[F-P01-141] **CRITICAL** — Use `Http::withOptions(['allow_redirects' => false])`
[F-P01-142] **CRITICAL** — Protocol restriction

### Content Security Policy (CSP) Engineering

[F-P01-143] **CRITICAL** — Start with a restrictive baseline
[F-P01-144] **CRITICAL** — Use nonces for inline scripts
[F-P01-145] **CRITICAL** — No `'unsafe-eval'`
[F-P01-146] **CRITICAL** — `'unsafe-inline'` only for styles, never scripts
[F-P01-147] **CRITICAL** — `report-uri` / `report-to` directive
[F-P01-148] **CRITICAL** — `frame-ancestors 'none'`
[F-P01-149] **CRITICAL** — `base-uri 'self'`
[F-P01-150] **CRITICAL** — `form-action 'self'`
[F-P01-151] **CRITICAL** — Test with `Content-Security-Policy-Report-Only` first

### HTTP Security Headers Checklist

[F-P01-152] **CRITICAL** — `Strict-Transport-Security: max-age=31536000; includeSubDomains; preload`
[F-P01-153] **CRITICAL** — `Content-Security-Policy`
[F-P01-154] **CRITICAL** — `X-Content-Type-Options: nosniff`
[F-P01-155] **CRITICAL** — `X-Frame-Options: DENY`
[F-P01-156] **CRITICAL** — `Referrer-Policy: strict-origin-when-cross-origin`
[F-P01-157] **CRITICAL** — `Permissions-Policy`
[F-P01-158] **CRITICAL** — `Cross-Origin-Opener-Policy: same-origin`
[F-P01-159] **CRITICAL** — `Cross-Origin-Resource-Policy: same-origin`
[F-P01-160] **CRITICAL** — `Cross-Origin-Embedder-Policy: require-corp`
[F-P01-161] **CRITICAL** — No `Server` header
[F-P01-162] **CRITICAL** — No `X-Powered-By` header

### CORS Misconfiguration

[F-P01-163] **CRITICAL** — Never use `Access-Control-Allow-Origin: *` with credentials
[F-P01-164] **CRITICAL** — Allowlist specific origins
[F-P01-165] **CRITICAL** — Always include `Vary: Origin`
[F-P01-166] **CRITICAL** — Restrict `Access-Control-Allow-Methods`
[F-P01-167] **CRITICAL** — Restrict `Access-Control-Allow-Headers`
[F-P01-168] **CRITICAL** — Set `Access-Control-Max-Age`
[F-P01-169] **CRITICAL** — No CORS on non-API routes
[F-P01-170] **CRITICAL** — `Access-Control-Allow-Credentials: true` only when needed

### Supply Chain Attack Vectors (Frontend)

[F-P01-171] **CRITICAL** — Use `npm ci` in CI, not `npm install`
[F-P01-172] **CRITICAL** — Pin exact versions in `package.json`
[F-P01-173] **CRITICAL** — Audit regularly
[F-P01-174] **CRITICAL** — Subresource Integrity (SRI)
[F-P01-175] **CRITICAL** — Review lockfile diffs in PRs
[F-P01-176] **CRITICAL** — Use `--ignore-scripts` for untrusted packages
[F-P01-177] **CRITICAL** — Private registry mirror
[F-P01-178] **CRITICAL** — Socket.dev or Snyk for dependency monitoring
[F-P01-179] **CRITICAL** — No CDN for critical dependencies
