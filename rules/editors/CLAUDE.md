> Add this to your project's CLAUDE.md or place in .claude/fortress-rules.md

<fortress-rules>

# The Laravel Fortress — AI Rules
# Version 1.0.0 | 1,755 checks | 200 sections | 14 parts
# https://github.com/chuxolab/laravel-fortress

## STEP 1: Understand the Project (MANDATORY)

Before applying any rules, build a project profile by reading actual project files.
Do NOT assume versions. Do NOT skip this step.

### 1a. Read `composer.json`
- `require.php` → PHP version constraint
- `require.laravel/framework` → Laravel version
- `require.*` and `require-dev.*` → Installed packages

### 1b. Read `package.json` (if present)
- `dependencies.vue` → Vue version
- `dependencies.react` → React (skip Vue rules if present)
- `dependencies.@inertiajs/*` → Inertia version
- `devDependencies.tailwindcss` → Tailwind version

### 1c. Read `.env` or `.env.example`
- `DB_CONNECTION` → Database engine
- `CACHE_STORE` → Cache backend
- `QUEUE_CONNECTION` → Queue backend

### 1d. Read `.fortress.yml` (if present in project root)
- Part toggles (enabled/disabled)
- Enforcement overrides (strict/standard/relaxed)
- Per-rule overrides
- Minimum severity threshold

### 1e. Build the Project Profile
- PHP version, Laravel version, Database engine
- Frontend: vue3 | vue2 | react | blade | none
- Testing: pest | phpunit
- Enabled parts and enforcement modes

## STEP 2: Apply Rules Intelligently

For each rule, check:
1. Is the Part enabled? (default: yes)
2. Does the rule's condition match the project profile?
3. What enforcement level applies?
4. Adapt advice to the detected version

Version adaptation examples:
- Laravel 11+: `casts()` method. Laravel 10: `$casts` property.
- PHP 8.4: property hooks. PHP 8.2: traditional getters.
- Pest: `it()` syntax. PHPUnit: `test` method prefix.

## STEP 3: Rules by Part

### P01 — Application Security (179 checks)

[F-P01-001] CRITICAL: Use Eloquent or Query Builder with parameter binding
[F-P01-002] CRITICAL: Validate column names in 'orderBy()' / 'groupBy()'
[F-P01-003] CRITICAL: Never pass untrusted input to 'Rule::unique()' ignore parameter
[F-P01-004] CRITICAL: Escape LIKE wildcards
[F-P01-005] CRITICAL: Use '{{ }}' (escaped) in Blade, never '{!! !!}' with untrusted data
[F-P01-006] CRITICAL: Sanitize rich text with HTMLPurifier
[F-P01-007] CRITICAL: Set Content-Security-Policy headers
[F-P01-008] CRITICAL: Vue/React auto-escapes by default
[F-P01-009] CRITICAL: All state-changing routes use POST/PUT/PATCH/DELETE
[F-P01-010] CRITICAL: '@csrf' directive in every form
[F-P01-011] CRITICAL: SPA sends X-XSRF-TOKEN header
[F-P01-012] CRITICAL: Use 'escapeshellarg()' / 'escapeshellcmd()'
[F-P01-013] CRITICAL: Prefer Laravel Process facade
[F-P01-014] CRITICAL: Never use 'eval()', 'exec()' with unsanitised input, 'extract()', or 'unserialize()' on untrusted data
[F-P01-015] CRITICAL: 'X-Frame-Options: DENY'
[F-P01-016] CRITICAL: 'X-Content-Type-Options: nosniff'
[F-P01-017] CRITICAL: 'Strict-Transport-Security: max-age=31536000; includeSubDomains'
[F-P01-018] CRITICAL: 'Referrer-Policy: strict-origin-when-cross-origin'
[F-P01-019] CRITICAL: 'Permissions-Policy: camera=(), microphone=(), geolocation=()'
[F-P01-020] CRITICAL: Content-Security-Policy
[F-P01-021] CRITICAL: Never use ''*'' for 'allowed_origins' in production
[F-P01-022] CRITICAL: Limit 'allowed_methods' to what the API actually uses
[F-P01-023] CRITICAL: Set 'supports_credentials: true' only when needed
[F-P01-024] CRITICAL: Validate and allowlist URLs before fetching
[F-P01-025] CRITICAL: Block internal IP ranges
[F-P01-026] CRITICAL: Rate limit all endpoints
[F-P01-027] CRITICAL: Use Sanctum for API token auth
[F-P01-028] CRITICAL: API versioning
[F-P01-029] CRITICAL: Use API Resources
[F-P01-030] CRITICAL: Pagination
[F-P01-031] CRITICAL: No sensitive data in URLs
[F-P01-032] CRITICAL: Regenerate session after login
[F-P01-033] CRITICAL: Invalidate session on logout
[F-P01-034] CRITICAL: Short session lifetime for sensitive apps
[F-P01-035] CRITICAL: Use 'redis' or 'database' session driver
[F-P01-036] CRITICAL: Validate MIME type and extension
[F-P01-037] CRITICAL: Never use the original filename
[F-P01-038] CRITICAL: Store uploads outside the web root
[F-P01-039] CRITICAL: Use signed temporary URLs for downloads
[F-P01-040] CRITICAL: Prevent directory traversal
[F-P01-041] CRITICAL: Limit file size
[F-P01-042] CRITICAL: Scan for malware
[F-P01-043] CRITICAL: No executable uploads
[F-P01-044] CRITICAL: Name all routes
[F-P01-045] CRITICAL: Group routes by authentication/authorization
[F-P01-046] CRITICAL: No sensitive operations on GET routes
[F-P01-047] CRITICAL: Use 'Route::permanentRedirect()' for old URLs
[F-P01-048] CRITICAL: Remove debug/test routes in production
[F-P01-049] CRITICAL: Disable route listing in production
[F-P01-050] CRITICAL: Auth before authorization
[F-P01-051] CRITICAL: Rate limiting early in the stack
[F-P01-052] CRITICAL: CORS before everything
[F-P01-053] CRITICAL: Use implicit route model binding
[F-P01-054] CRITICAL: Scope bindings to parent models
[F-P01-055] CRITICAL: Use custom route keys for public URLs
[F-P01-056] CRITICAL: Never use 'unserialize()' on untrusted data
[F-P01-057] CRITICAL: Use 'allowed_classes: false' if 'unserialize()' is unavoidable
[F-P01-058] CRITICAL: Never store serialized PHP objects in database/cache without JSON alternative
[F-P01-059] CRITICAL: Use 'JSON_THROW_ON_ERROR' flag
[F-P01-060] CRITICAL: Limit JSON decode depth
[F-P01-061] CRITICAL: Use 'JSON_PRETTY_PRINT' only for debugging
[F-P01-062] CRITICAL: Never interpolate user input into email headers
[F-P01-063] CRITICAL: Validate email addresses with 'email:rfc,dns'
[F-P01-064] CRITICAL: Queue all email sending
[F-P01-065] CRITICAL: Rate limit email sending
[F-P01-066] CRITICAL: Use vague messages for password reset
[F-P01-067] CRITICAL: Set SPF, DKIM, and DMARC records
[F-P01-068] CRITICAL: Use reply-to, not from, for user-generated emails
[F-P01-069] CRITICAL: Limit attachment sizes and types
[F-P01-070] CRITICAL: '{{ }}' for all dynamic content
[F-P01-071] CRITICAL: '{!! !!}' only for pre-sanitized HTML
[F-P01-072] CRITICAL: No user data in '@php' blocks
[F-P01-073] CRITICAL: No user data in '@include()' paths
[F-P01-074] CRITICAL: No 'v-html' with user data
[F-P01-075] CRITICAL: Validate 'href' and 'src' attributes
[F-P01-076] CRITICAL: Don't pass sensitive data as props
[F-P01-077] CRITICAL: Don't expose env variables to frontend
[F-P01-078] CRITICAL: Type all component props
[F-P01-079] CRITICAL: Validate prop values
[F-P01-080] CRITICAL: Default values for optional props
[F-P01-081] CRITICAL: Never redirect to user-supplied URLs without validation
[F-P01-082] CRITICAL: Use 'redirect()->intended()' for post-login redirects
[F-P01-083] CRITICAL: Validate 'url' type inputs with 'url' rule AND domain allowlist
[F-P01-084] CRITICAL: Always scope queries to the authenticated user
[F-P01-085] CRITICAL: Use policies for ownership checks
[F-P01-086] CRITICAL: Use UUIDs instead of sequential IDs in URLs
[F-P01-087] CRITICAL: Route model binding with scoped relationships
[F-P01-088] CRITICAL: Sanitize cell values starting with '=', '+', '-', '@', '\t', '\r'
[F-P01-089] CRITICAL: Apply to all CSV, XLSX, and TSV exports
[F-P01-090] CRITICAL: Apply to all user-provided columns
[F-P01-091] CRITICAL: Set 'Content-Disposition: attachment'
[F-P01-092] CRITICAL: Set correct 'Content-Type'
[F-P01-093] CRITICAL: Limit exported columns
[F-P01-094] CRITICAL: Audit who exports what
[F-P01-095] CRITICAL: All private and presence channels require authorization
[F-P01-096] CRITICAL: Never send sensitive data on public channels
[F-P01-097] CRITICAL: Presence channels expose user info
[F-P01-098] CRITICAL: Sanitize broadcast payloads
[F-P01-099] CRITICAL: Limit payload size
[F-P01-100] CRITICAL: Use private channels for user-specific data
[F-P01-101] CRITICAL: Set 'APP_URL' correctly
[F-P01-102] CRITICAL: 'TrustHosts' middleware configured
[F-P01-103] CRITICAL: Never use '$request->getHost()' for security decisions without validation
[F-P01-104] CRITICAL: Password reset links use 'APP_URL', not request host
[F-P01-105] CRITICAL: 'TrustProxies' middleware configured
[F-P01-106] CRITICAL: Don't trust '*' for proxies in production
[F-P01-107] CRITICAL: Never build file paths from user input directly
[F-P01-108] CRITICAL: Validate file extensions against an allowlist
[F-P01-109] CRITICAL: Use 'realpath()' and verify prefix
[F-P01-110] CRITICAL: Reject null bytes in file names
[F-P01-111] CRITICAL: Zip extraction: validate entry paths
[F-P01-112] CRITICAL: Storage disk scoping
[F-P01-113] CRITICAL: No user-controlled 'include'/'require'
[F-P01-114] CRITICAL: Disable external entities in libxml
[F-P01-115] CRITICAL: Use 'simplexml_load_string()' with 'LIBXML_NONET'
[F-P01-116] CRITICAL: Reject DTD declarations in input XML
[F-P01-117] CRITICAL: Prefer JSON over XML
[F-P01-118] CRITICAL: SOAP clients: disable entity expansion
[F-P01-119] CRITICAL: SVG upload XXE
[F-P01-120] CRITICAL: Use a single reverse proxy
[F-P01-121] CRITICAL: Reject ambiguous 'Transfer-Encoding' headers
[F-P01-122] CRITICAL: Normalize 'Content-Length' and 'Transfer-Encoding'
[F-P01-123] CRITICAL: Use HTTP/2 end-to-end where possible
[F-P01-124] CRITICAL: Keep web server and proxy versions current
[F-P01-125] CRITICAL: Test with smuggling detection tools
[F-P01-126] CRITICAL: Set 'X-Frame-Options: DENY'
[F-P01-127] CRITICAL: CSP 'frame-ancestors 'none''
[F-P01-128] CRITICAL: Sensitive actions require re-authentication
[F-P01-129] CRITICAL: No frameable authentication pages
[F-P01-130] CRITICAL: JavaScript frame-busting as last resort
[F-P01-131] CRITICAL: Audit DNS CNAME records
[F-P01-132] CRITICAL: Remove DNS records when decommissioning services
[F-P01-133] CRITICAL: Monitor subdomains automatically
[F-P01-134] CRITICAL: Wildcard DNS is dangerous
[F-P01-135] CRITICAL: Verify after decommission
[F-P01-136] CRITICAL: Never fetch user-supplied URLs without validation
[F-P01-137] CRITICAL: Block cloud metadata endpoints
[F-P01-138] CRITICAL: Allowlist outbound domains
[F-P01-139] CRITICAL: DNS rebinding defense
[F-P01-140] CRITICAL: Disable redirects in HTTP client
[F-P01-141] CRITICAL: Use 'Http::withOptions(['allow_redirects' => false])'
[F-P01-142] CRITICAL: Protocol restriction
[F-P01-143] CRITICAL: Start with a restrictive baseline
[F-P01-144] CRITICAL: Use nonces for inline scripts
[F-P01-145] CRITICAL: No ''unsafe-eval''
[F-P01-146] CRITICAL: ''unsafe-inline'' only for styles, never scripts
[F-P01-147] CRITICAL: 'report-uri' / 'report-to' directive
[F-P01-148] CRITICAL: 'frame-ancestors 'none''
[F-P01-149] CRITICAL: 'base-uri 'self''
[F-P01-150] CRITICAL: 'form-action 'self''
[F-P01-151] CRITICAL: Test with 'Content-Security-Policy-Report-Only' first
[F-P01-152] CRITICAL: 'Strict-Transport-Security: max-age=31536000; includeSubDomains; preload'
[F-P01-153] CRITICAL: 'Content-Security-Policy'
[F-P01-154] CRITICAL: 'X-Content-Type-Options: nosniff'
[F-P01-155] CRITICAL: 'X-Frame-Options: DENY'
[F-P01-156] CRITICAL: 'Referrer-Policy: strict-origin-when-cross-origin'
[F-P01-157] CRITICAL: 'Permissions-Policy'
[F-P01-158] CRITICAL: 'Cross-Origin-Opener-Policy: same-origin'
[F-P01-159] CRITICAL: 'Cross-Origin-Resource-Policy: same-origin'
[F-P01-160] CRITICAL: 'Cross-Origin-Embedder-Policy: require-corp'
[F-P01-161] CRITICAL: No 'Server' header
[F-P01-162] CRITICAL: No 'X-Powered-By' header
[F-P01-163] CRITICAL: Never use 'Access-Control-Allow-Origin: *' with credentials
[F-P01-164] CRITICAL: Allowlist specific origins
[F-P01-165] CRITICAL: Always include 'Vary: Origin'
[F-P01-166] CRITICAL: Restrict 'Access-Control-Allow-Methods'
[F-P01-167] CRITICAL: Restrict 'Access-Control-Allow-Headers'
[F-P01-168] CRITICAL: Set 'Access-Control-Max-Age'
[F-P01-169] CRITICAL: No CORS on non-API routes
[F-P01-170] CRITICAL: 'Access-Control-Allow-Credentials: true' only when needed
[F-P01-171] CRITICAL: Use 'npm ci' in CI, not 'npm install'
[F-P01-172] CRITICAL: Pin exact versions in 'package.json'
[F-P01-173] CRITICAL: Audit regularly
[F-P01-174] CRITICAL: Subresource Integrity (SRI)
[F-P01-175] CRITICAL: Review lockfile diffs in PRs
[F-P01-176] CRITICAL: Use '--ignore-scripts' for untrusted packages
[F-P01-177] CRITICAL: Private registry mirror
[F-P01-178] CRITICAL: Socket.dev or Snyk for dependency monitoring
[F-P01-179] CRITICAL: No CDN for critical dependencies


### P02 — Cryptography & Data Protection (109 checks)

[F-P02-001] CRITICAL: Use 'Hash::make()' for passwords
[F-P02-002] CRITICAL: Prefer Argon2id over bcrypt
[F-P02-003] CRITICAL: Tune hash parameters for 200-500ms on your hardware
[F-P02-004] CRITICAL: Use 'Hash::needsRehash()' for transparent upgrades
[F-P02-005] CRITICAL: Never use for passwords
[F-P02-006] CRITICAL: bcrypt silently truncates at 72 bytes
[F-P02-007] CRITICAL: bcrypt has a null byte issue
[F-P02-008] CRITICAL: 'APP_KEY' is critical — rotate periodically
[F-P02-009] CRITICAL: Use 'APP_PREVIOUS_KEYS' for key rotation
[F-P02-010] CRITICAL: Encrypt sensitive model attributes
[F-P02-011] CRITICAL: Use 'Crypt::encryptString()' for application-level encryption
[F-P02-012] CRITICAL: Use 'php artisan env:encrypt' for encrypted '.env' in CI/CD
[F-P02-013] CRITICAL: Use 'Str::random()' or 'random_bytes()' for security tokens
[F-P02-014] CRITICAL: Never use 'rand()', 'mt_rand()', 'array_rand()', 'uniqid()', 'microtime()', or 'md5(time())' for tokens
[F-P02-015] CRITICAL: Use signed routes for sensitive links
[F-P02-016] CRITICAL: Use temporary signed URLs with short expiration
[F-P02-017] CRITICAL: Validate webhook signatures with 'hash_equals()'
[F-P02-018] CRITICAL: Never use '==' or '===' to compare hashes/tokens
[F-P02-019] CRITICAL: Never roll your own encryption algorithm
[F-P02-020] CRITICAL: Never use ECB mode
[F-P02-021] CRITICAL: Never store encryption keys in code or version control
[F-P02-022] CRITICAL: Never reuse IVs/nonces
[F-P02-023] CRITICAL: Never use 'base64_encode()' as "encryption"
[F-P02-024] CRITICAL: Minimum password length: 12 characters
[F-P02-025] CRITICAL: Maximum password length: 128 characters
[F-P02-026] CRITICAL: Check against breached password lists
[F-P02-027] CRITICAL: No password composition rules
[F-P02-028] CRITICAL: Rate limit login attempts
[F-P02-029] CRITICAL: Constant-time password comparison
[F-P02-030] CRITICAL: Never display passwords in logs, responses, or error messages
[F-P02-031] CRITICAL: Session invalidation on password change
[F-P02-032] CRITICAL: '$dontFlash' includes password fields
[F-P02-033] CRITICAL: Enforce 2FA for all users (or admin+ roles)
[F-P02-034] CRITICAL: TOTP over SMS
[F-P02-035] CRITICAL: Store 2FA secrets encrypted
[F-P02-036] CRITICAL: Generate and store recovery codes
[F-P02-037] CRITICAL: Rate limit 2FA verification attempts
[F-P02-038] CRITICAL: Reconfirmation for sensitive operations
[F-P02-039] CRITICAL: 2FA confirmation timestamp
[F-P02-040] CRITICAL: Backup code usage alerts
[F-P02-041] CRITICAL: 'inputmode="numeric"' on OTP fields
[F-P02-042] CRITICAL: Never log passwords
[F-P02-043] CRITICAL: Never log credit card numbers
[F-P02-044] CRITICAL: Never log API keys or tokens
[F-P02-045] CRITICAL: Never log session IDs
[F-P02-046] CRITICAL: Never log PII unnecessarily
[F-P02-047] CRITICAL: Redact request logging
[F-P02-048] CRITICAL: Configure '$dontReport' for noisy exceptions
[F-P02-049] CRITICAL: Log levels are appropriate
[F-P02-050] CRITICAL: Structured context, not string interpolation
[F-P02-051] CRITICAL: Log rotation configured
[F-P02-052] CRITICAL: 'APP_KEY' rotation uses 'APP_PREVIOUS_KEYS'
[F-P02-053] CRITICAL: Schedule key rotation
[F-P02-054] CRITICAL: Database encrypted columns re-encrypt on rotation
[F-P02-055] CRITICAL: Never store 'APP_KEY' in version control
[F-P02-056] CRITICAL: Separate encryption keys per purpose
[F-P02-057] CRITICAL: Key destruction procedure
[F-P02-058] CRITICAL: HSM for high-value keys
[F-P02-059] CRITICAL: TLS 1.2 minimum, TLS 1.3 preferred
[F-P02-060] CRITICAL: Strong cipher suites only
[F-P02-061] CRITICAL: Auto-renew certificates
[F-P02-062] CRITICAL: Monitor expiration
[F-P02-063] CRITICAL: OCSP stapling enabled
[F-P02-064] CRITICAL: Certificate Transparency logs
[F-P02-065] CRITICAL: Internal services use TLS too
[F-P02-066] CRITICAL: No self-signed in production
[F-P02-067] CRITICAL: Tier 1 — Public
[F-P02-068] CRITICAL: Tier 2 — Internal
[F-P02-069] CRITICAL: Tier 3 — Confidential
[F-P02-070] CRITICAL: Tier 4 — Restricted
[F-P02-071] CRITICAL: Every model/table has a classification
[F-P02-072] CRITICAL: Access logging scales with tier
[F-P02-073] CRITICAL: Retention policies per tier
[F-P02-074] CRITICAL: Data flow diagrams
[F-P02-075] CRITICAL: Identify all PII fields
[F-P02-076] CRITICAL: '$hidden' on sensitive model attributes
[F-P02-077] CRITICAL: Anonymize in non-production environments
[F-P02-078] CRITICAL: Right to erasure (GDPR Article 17)
[F-P02-079] CRITICAL: No PII in URLs
[F-P02-080] CRITICAL: No PII in log messages
[F-P02-081] CRITICAL: PII inventory maintained
[F-P02-082] CRITICAL: Tokenize payment card numbers
[F-P02-083] CRITICAL: Mask in display
[F-P02-084] CRITICAL: Mask in logs
[F-P02-085] CRITICAL: Distinct tokenization per purpose
[F-P02-086] CRITICAL: Token-to-original mapping stored securely
[F-P02-087] CRITICAL: No reversible tokenization without access control
[F-P02-088] CRITICAL: Use Laravel 'encrypted' cast for sensitive model fields
[F-P02-089] CRITICAL: Encrypted fields are not searchable
[F-P02-090] CRITICAL: Database-level encryption (TDE)
[F-P02-091] CRITICAL: Backup encryption
[F-P02-092] CRITICAL: File storage encryption
[F-P02-093] CRITICAL: Key separation
[F-P02-094] CRITICAL: Test that decryption fails gracefully
[F-P02-095] CRITICAL: No secrets in '.env' files on servers
[F-P02-096] CRITICAL: No secrets in environment variables visible to 'phpinfo()'
[F-P02-097] CRITICAL: Rotate secrets on personnel change
[F-P02-098] CRITICAL: Least privilege for secret access
[F-P02-099] CRITICAL: Audit secret access
[F-P02-100] CRITICAL: Cache secrets in memory, not disk
[F-P02-101] CRITICAL: Secret versioning
[F-P02-102] CRITICAL: Emergency rotation procedure documented
[F-P02-103] CRITICAL: Abstract crypto operations behind interfaces
[F-P02-104] CRITICAL: 'Hash::needsRehash($hash)'
[F-P02-105] CRITICAL: Config-driven algorithm selection
[F-P02-106] CRITICAL: Document current algorithms
[F-P02-107] CRITICAL: Migration path for algorithm changes
[F-P02-108] CRITICAL: No custom cryptography
[F-P02-109] CRITICAL: Post-quantum readiness


### P03 — Authentication & Authorization (110 checks)

[F-P03-001] CRITICAL: Every controller method must authorize
[F-P03-002] CRITICAL: Pass model instances to 'authorize()', not class strings
[F-P03-003] CRITICAL: Use 'Gate::allows()' / '$user->can()' for non-controller checks
[F-P03-004] CRITICAL: Scope queries to the authenticated user
[F-P03-005] CRITICAL: Use 'can()' not 'hasPermissionTo()'
[F-P03-006] CRITICAL: Permissions are granular, not boolean
[F-P03-007] CRITICAL: Roles contain permissions, not logic
[F-P03-008] CRITICAL: Super-admin bypass uses 'Gate::before'
[F-P03-009] CRITICAL: Protect super-admin from modification
[F-P03-010] CRITICAL: Define permissions in an enum, not as raw strings
[F-P03-011] CRITICAL: Creator cannot approve their own resource
[F-P03-012] CRITICAL: Enforce SoD inside DB transactions
[F-P03-013] CRITICAL: Document accepted SoD bypasses
[F-P03-014] CRITICAL: Every model declares explicit '$fillable'
[F-P03-015] CRITICAL: Never use '$guarded = []'
[F-P03-016] CRITICAL: Never call 'Model::unguard()'
[F-P03-017] CRITICAL: Audit every model's '$fillable' for privilege escalation fields
[F-P03-018] CRITICAL: Use '$request->validated()' or '$request->safe()->only([...])'
[F-P03-019] CRITICAL: '$hidden' on sensitive fields
[F-P03-020] CRITICAL: Never expose internal IDs unnecessarily
[F-P03-021] CRITICAL: One policy per model
[F-P03-022] CRITICAL: Policy methods mirror controller methods
[F-P03-023] CRITICAL: Check ownership AND permission
[F-P03-024] CRITICAL: 'before()' for blanket overrides
[F-P03-025] CRITICAL: Return 'null' from 'before()' to fall through
[F-P03-026] CRITICAL: Don't call other policies from within a policy
[F-P03-027] CRITICAL: Test policies in isolation
[F-P03-028] CRITICAL: 'Gate::before' runs before every check
[F-P03-029] CRITICAL: 'Gate::after' runs after policy
[F-P03-030] CRITICAL: Guest users
[F-P03-031] CRITICAL: 'authorize()' throws 'AuthorizationException'
[F-P03-032] CRITICAL: 'can()' returns boolean
[F-P03-033] CRITICAL: Resource authorization
[F-P03-034] CRITICAL: Response-based authorization
[F-P03-035] CRITICAL: Per-user rate limits for authenticated routes
[F-P03-036] CRITICAL: Per-IP rate limits for unauthenticated routes
[F-P03-037] CRITICAL: Per-route rate limits
[F-P03-038] CRITICAL: Compound keys
[F-P03-039] CRITICAL: Include 'X-RateLimit-Limit' header
[F-P03-040] CRITICAL: Include 'X-RateLimit-Remaining' header
[F-P03-041] CRITICAL: Include 'Retry-After' header on 429
[F-P03-042] CRITICAL: Rate limit failed attempts more aggressively
[F-P03-043] CRITICAL: Progressive rate limiting
[F-P03-044] CRITICAL: Rate limit by feature, not just endpoint
[F-P03-045] CRITICAL: Use PKCE for all OAuth flows
[F-P03-046] CRITICAL: Validate 'state' parameter
[F-P03-047] CRITICAL: Validate 'id_token' signature
[F-P03-048] CRITICAL: Validate 'iss' (issuer) and 'aud' (audience) claims
[F-P03-049] CRITICAL: Short-lived access tokens
[F-P03-050] CRITICAL: Store refresh tokens encrypted
[F-P03-051] CRITICAL: Revoke tokens on logout
[F-P03-052] CRITICAL: Validate 'nonce' claim
[F-P03-053] CRITICAL: Scope minimization
[F-P03-054] CRITICAL: Token introspection for opaque tokens
[F-P03-055] CRITICAL: Always verify the signature
[F-P03-056] CRITICAL: Pin the algorithm server-side
[F-P03-057] CRITICAL: Validate 'exp', 'iat', 'nbf' claims
[F-P03-058] CRITICAL: Short expiration
[F-P03-059] CRITICAL: 'jti' claim for revocation
[F-P03-060] CRITICAL: Asymmetric keys for distributed systems
[F-P03-061] CRITICAL: No sensitive data in JWT payload
[F-P03-062] CRITICAL: Rotate signing keys periodically
[F-P03-063] CRITICAL: 'kid' (Key ID) header
[F-P03-064] CRITICAL: Hash API keys before storage
[F-P03-065] CRITICAL: Prefix keys for identification
[F-P03-066] CRITICAL: Expiration dates on all keys
[F-P03-067] CRITICAL: Scope/permission per key
[F-P03-068] CRITICAL: Rate limit per key
[F-P03-069] CRITICAL: Revocation is instant
[F-P03-070] CRITICAL: Audit log per key
[F-P03-071] CRITICAL: Key rotation without downtime
[F-P03-072] CRITICAL: 'Session::regenerate()' on login
[F-P03-073] CRITICAL: 'Session::invalidate()' on logout
[F-P03-074] CRITICAL: Session ID not in URL
[F-P03-075] CRITICAL: 'HttpOnly' flag on session cookie
[F-P03-076] CRITICAL: 'Secure' flag on session cookie
[F-P03-077] CRITICAL: 'SameSite=Lax' or 'Strict'
[F-P03-078] CRITICAL: Session timeout
[F-P03-079] CRITICAL: Absolute session timeout
[F-P03-080] CRITICAL: Concurrent session limits
[F-P03-081] CRITICAL: Bind session to user-agent or IP
[F-P03-082] CRITICAL: Rate limit login attempts
[F-P03-083] CRITICAL: Progressive delays
[F-P03-084] CRITICAL: Account lockout after threshold
[F-P03-085] CRITICAL: CAPTCHA on repeated failures
[F-P03-086] CRITICAL: IP-based rate limiting
[F-P03-087] CRITICAL: Credential stuffing detection
[F-P03-088] CRITICAL: Honeypot fields
[F-P03-089] CRITICAL: Timing-safe responses
[F-P03-090] CRITICAL: RBAC (Role-Based Access Control)
[F-P03-091] CRITICAL: ABAC (Attribute-Based Access Control)
[F-P03-092] CRITICAL: Don't mix raw role checks with permission checks
[F-P03-093] CRITICAL: Permissions are the atoms, roles are groups
[F-P03-094] CRITICAL: Avoid role proliferation
[F-P03-095] CRITICAL: Document the permission matrix
[F-P03-096] CRITICAL: Users cannot modify their own roles
[F-P03-097] CRITICAL: Users cannot assign roles higher than their own
[F-P03-098] CRITICAL: Admin panel access is audited
[F-P03-099] CRITICAL: 'SuperAdmin' bypass is minimal
[F-P03-100] CRITICAL: No hidden admin routes
[F-P03-101] CRITICAL: Horizontal privilege escalation
[F-P03-102] CRITICAL: Vertical privilege escalation
[F-P03-103] CRITICAL: Impersonation requires explicit permission
[F-P03-104] CRITICAL: Cannot impersonate higher-privileged users
[F-P03-105] CRITICAL: Store original user ID in session
[F-P03-106] CRITICAL: Visual indicator when impersonating
[F-P03-107] CRITICAL: Impersonation is audit-logged
[F-P03-108] CRITICAL: Impersonated sessions cannot change security settings
[F-P03-109] CRITICAL: Auto-expire impersonation
[F-P03-110] CRITICAL: Impersonation cannot be chained


### P04 — Data Integrity & Concurrency (84 checks)

[F-P04-001] CRITICAL: Multi-model writes must be wrapped in transactions
[F-P04-002] CRITICAL: Check transaction depth in traits/concerns that require transactions
[F-P04-003] CRITICAL: Nested transactions use savepoints
[F-P04-004] CRITICAL: Pessimistic lock before status checks
[F-P04-005] CRITICAL: Idempotency keys for retryable operations
[F-P04-006] CRITICAL: Lock rows in consistent order
[F-P04-007] CRITICAL: Keep transactions short
[F-P04-008] CRITICAL: Use optimistic locking for low-contention updates
[F-P04-009] CRITICAL: Status enums define 'allowedTransitions()'
[F-P04-010] CRITICAL: 'canTransitionTo()' method
[F-P04-011] CRITICAL: 'isTerminal()' method
[F-P04-012] CRITICAL: 'label()' method
[F-P04-013] CRITICAL: 'color()' method
[F-P04-014] CRITICAL: Use 'transitionTo()' method, never direct status updates
[F-P04-015] CRITICAL: Immutability on terminal states
[F-P04-016] CRITICAL: Log all state transitions
[F-P04-017] CRITICAL: State machine guards run inside transactions
[F-P04-018] CRITICAL: Centralize reference generation
[F-P04-019] CRITICAL: Reference format includes prefix, timestamp, random
[F-P04-020] CRITICAL: Register all reference prefixes in an enum
[F-P04-021] CRITICAL: Validate reference format at system boundaries
[F-P04-022] CRITICAL: Don't re-validate persisted references
[F-P04-023] CRITICAL: Idempotency keys are unique-constrained
[F-P04-024] CRITICAL: Idempotency key expiry
[F-P04-025] CRITICAL: Return the same response for duplicate idempotent requests
[F-P04-026] CRITICAL: Segregation of duties enforced
[F-P04-027] CRITICAL: Multi-level approval
[F-P04-028] CRITICAL: Approval deadlines
[F-P04-029] CRITICAL: Rejection with reason
[F-P04-030] CRITICAL: Recall/withdraw by creator
[F-P04-031] CRITICAL: Delegation
[F-P04-032] CRITICAL: Approval comments immutable
[F-P04-033] CRITICAL: Approval state resets on re-submission
[F-P04-034] CRITICAL: Add 'version' column for contested records
[F-P04-035] CRITICAL: Return '409 Conflict' on version mismatch
[F-P04-036] CRITICAL: Frontend sends 'version' with update requests
[F-P04-037] CRITICAL: Alternative: 'updated_at' timestamp comparison
[F-P04-038] CRITICAL: Combine with 'lockForUpdate()' for critical paths
[F-P04-039] CRITICAL: Retry logic with backoff
[F-P04-040] CRITICAL: Use 'Cache::lock()' for cross-process locks
[F-P04-041] CRITICAL: Always set a TTL
[F-P04-042] CRITICAL: Use 'block()' for waiting
[F-P04-043] CRITICAL: Owner token for safe release
[F-P04-044] CRITICAL: Avoid distributed locks when possible
[F-P04-045] CRITICAL: Monitor lock contention
[F-P04-046] CRITICAL: No nested locks
[F-P04-047] CRITICAL: Long-running transactions use the Saga pattern
[F-P04-048] CRITICAL: Compensation is idempotent
[F-P04-049] CRITICAL: Log each step completion
[F-P04-050] CRITICAL: Distinguish critical vs non-critical steps
[F-P04-051] CRITICAL: Timeouts on each step
[F-P04-052] CRITICAL: Saga state machine
[F-P04-053] CRITICAL: Manual override for stuck sagas
[F-P04-054] CRITICAL: Events carry timestamps
[F-P04-055] CRITICAL: Sequence numbers for strict ordering
[F-P04-056] CRITICAL: Causal ordering via vector clocks
[F-P04-057] CRITICAL: Idempotent event handlers
[F-P04-058] CRITICAL: Out-of-order detection
[F-P04-059] CRITICAL: Event replay respects original order
[F-P04-060] CRITICAL: No side effects in projectors that depend on wall-clock time
[F-P04-061] CRITICAL: Aggregate version validation
[F-P04-062] CRITICAL: HTTP endpoints
[F-P04-063] CRITICAL: Queue jobs
[F-P04-064] CRITICAL: Database writes
[F-P04-065] CRITICAL: Event handlers
[F-P04-066] CRITICAL: External API calls
[F-P04-067] CRITICAL: Webhook receivers
[F-P04-068] CRITICAL: Idempotency key TTL matches operation lifecycle
[F-P04-069] CRITICAL: Return same response for duplicate requests
[F-P04-070] CRITICAL: UI communicates async operations clearly
[F-P04-071] CRITICAL: Read-your-own-writes
[F-P04-072] CRITICAL: Stale reads are acceptable for aggregations
[F-P04-073] CRITICAL: Compensation for failed async operations
[F-P04-074] CRITICAL: Retry with exponential backoff
[F-P04-075] CRITICAL: Dead letter queue
[F-P04-076] CRITICAL: Reconciliation jobs
[F-P04-077] CRITICAL: No distributed transactions
[F-P04-078] CRITICAL: Last-write-wins (LWW)
[F-P04-079] CRITICAL: First-write-wins
[F-P04-080] CRITICAL: Application-level merge
[F-P04-081] CRITICAL: User-resolved conflicts
[F-P04-082] CRITICAL: CRDTs for specific data types
[F-P04-083] CRITICAL: Conflict detection before resolution
[F-P04-084] CRITICAL: Audit conflicts


### P05 — Financial & Monetary Correctness (62 checks)

[F-P05-001] CRITICAL: No 'float', 'double', 'decimal' PHP types for monetary arithmetic
[F-P05-002] CRITICAL: No 'bcadd()', 'bcsub()', 'bcmul()', 'bcdiv()', 'bccomp()'
[F-P05-003] CRITICAL: Use PascalCase 'RoundingMode' enum cases
[F-P05-004] CRITICAL: Store money as strings, not database 'DECIMAL'
[F-P05-005] CRITICAL: Use 3-column pattern for money
[F-P05-006] CRITICAL: Never store money as cents/integers
[F-P05-007] CRITICAL: No 'parseFloat()', 'Number()', or native JS arithmetic on monetary values
[F-P05-008] CRITICAL: Format money server-side or with a dedicated formatter
[F-P05-009] CRITICAL: Never sum amounts in different currencies
[F-P05-010] CRITICAL: Store the exchange rate used
[F-P05-011] CRITICAL: Functional currency is the measurement base
[F-P05-012] CRITICAL: Spot rate at transaction date
[F-P05-013] CRITICAL: Monetary items revalued at closing rate
[F-P05-014] CRITICAL: Non-monetary items at historical rate
[F-P05-015] CRITICAL: Exchange differences to P&L or OCI
[F-P05-016] CRITICAL: Rate source is auditable
[F-P05-017] CRITICAL: Average rates for period aggregations
[F-P05-018] CRITICAL: Inter-company translations
[F-P05-019] CRITICAL: Define rounding rules per currency
[F-P05-020] CRITICAL: Rounding mode is explicit
[F-P05-021] CRITICAL: Never round intermediate calculations
[F-P05-022] CRITICAL: Rounding differences are booked
[F-P05-023] CRITICAL: Allocation uses largest-remainder method
[F-P05-024] CRITICAL: Test rounding edge cases
[F-P05-025] CRITICAL: Three-way reconciliation
[F-P05-026] CRITICAL: Matching rules are configurable
[F-P05-027] CRITICAL: Tolerance thresholds
[F-P05-028] CRITICAL: Unmatched items flagged for review
[F-P05-029] CRITICAL: One-to-many and many-to-one matching
[F-P05-030] CRITICAL: Reconciliation state machine
[F-P05-031] CRITICAL: Audit trail per match
[F-P05-032] CRITICAL: Delta reconciliation
[F-P05-033] CRITICAL: Break analysis
[F-P05-034] CRITICAL: Period lock prevents new postings
[F-P05-035] CRITICAL: Close is a multi-step process
[F-P05-036] CRITICAL: Reopen requires elevated permission
[F-P05-037] CRITICAL: Closing entries are auto-generated
[F-P05-038] CRITICAL: Trial balance must balance before close
[F-P05-039] CRITICAL: Comparative period data is frozen
[F-P05-040] CRITICAL: Period close is idempotent
[F-P05-041] CRITICAL: Posted journal entries are never updated
[F-P05-042] CRITICAL: 'SoftDeletes' on journal entries — but deletes are forbidden post-posting
[F-P05-043] CRITICAL: No raw 'UPDATE' on journal lines
[F-P05-044] CRITICAL: Reversals create new entries
[F-P05-045] CRITICAL: Void marks as void, doesn't delete
[F-P05-046] CRITICAL: Hash chain for tamper detection
[F-P05-047] CRITICAL: Read-only database user for reports
[F-P05-048] CRITICAL: Inter-company accounts are clearly identified
[F-P05-049] CRITICAL: Reciprocal entries must balance
[F-P05-050] CRITICAL: Elimination entries are auto-generated
[F-P05-051] CRITICAL: Transfer pricing is documented
[F-P05-052] CRITICAL: Audit trail for inter-company
[F-P05-053] CRITICAL: Consolidation adjustments are reversible
[F-P05-054] CRITICAL: Segregation of Duties (SoD)
[F-P05-055] CRITICAL: Dual approval for material transactions
[F-P05-056] CRITICAL: Audit trail is tamper-evident
[F-P05-057] CRITICAL: Retention policies enforced
[F-P05-058] CRITICAL: Access reviews quarterly
[F-P05-059] CRITICAL: Change management logged
[F-P05-060] CRITICAL: IFRS 9 — Expected Credit Loss
[F-P05-061] CRITICAL: IFRS 15 — Revenue Recognition
[F-P05-062] CRITICAL: Management override controls


### P06 — PHP Language & Type Safety (126 checks)

[F-P06-001] WARNING: Constructor property promotion
[F-P06-002] WARNING: 'match' over 'switch'
[F-P06-003] WARNING: Backed enums for all magic strings
[F-P06-004] WARNING: Null-safe operator '?->'
[F-P06-005] WARNING: Arrow functions 'fn() =>'
[F-P06-006] WARNING: Named arguments
[F-P06-007] WARNING: 'readonly' properties
[F-P06-008] WARNING: First-class callable syntax 'method(...)'
[F-P06-009] WARNING: '#[Override]' attribute
[F-P06-010] WARNING: Property hooks
[F-P06-011] WARNING: Asymmetric visibility
[F-P06-012] WARNING: '#[\Deprecated]' attribute
[F-P06-013] WARNING: New array functions
[F-P06-014] WARNING: 'new' without parentheses chaining
[F-P06-015] WARNING: No 'switch' statements
[F-P06-016] WARNING: No 'array_push($arr, $item)'
[F-P06-017] WARNING: No 'isset($x) ? $x : $default'
[F-P06-018] WARNING: No 'strpos($str, 'x') !== false'
[F-P06-019] WARNING: No 'substr($str, 0, 3) === 'foo''
[F-P06-020] WARNING: No 'array_key_exists('key', $arr)'
[F-P06-021] WARNING: No 'call_user_func($callback, $arg)'
[F-P06-022] WARNING: 'declare(strict_types=1)' in every PHP file
[F-P06-023] WARNING: Explicit return types on all methods
[F-P06-024] WARNING: Use 'void' for methods that return nothing
[F-P06-025] WARNING: Use 'never' for methods that always throw or exit
[F-P06-026] WARNING: Type hints on all parameters
[F-P06-027] WARNING: Use union types 'string|int' over 'mixed'
[F-P06-028] WARNING: Use intersection types 'Renderable&Countable'
[F-P06-029] WARNING: Avoid 'mixed' — it means "I don't know"
[F-P06-030] WARNING: Model casts use 'casts()' method, not '$casts' property
[F-P06-031] WARNING: Use 'immutable_datetime' over 'datetime'
[F-P06-032] WARNING: Use ''encrypted'' cast for sensitive attributes
[F-P06-033] WARNING: Use ''boolean'' cast for flag columns
[F-P06-034] WARNING: Use ''array'' or ''collection'' cast for JSON columns
[F-P06-035] WARNING: Use 'AsEnumCollection::of(Enum::class)' for JSON arrays of enum values
[F-P06-036] WARNING: Array shapes for complex arrays
[F-P06-037] WARNING: '@template' for generic types
[F-P06-038] WARNING: Avoid catastrophic backtracking patterns
[F-P06-039] WARNING: Limit input length before regex matching
[F-P06-040] WARNING: Use possessive quantifiers or atomic groups
[F-P06-041] WARNING: Prefer 'str_contains()', 'str_starts_with()', 'str_ends_with()'
[F-P06-042] WARNING: Test regex with adversarial inputs
[F-P06-043] WARNING: Store all timestamps in UTC
[F-P06-044] WARNING: Convert to user's timezone only for display
[F-P06-045] WARNING: Use 'CarbonImmutable' not 'Carbon'
[F-P06-046] WARNING: Model casts use 'immutable_datetime'
[F-P06-047] WARNING: Compare dates with Carbon methods, not strings
[F-P06-048] WARNING: Beware of timezone-naive comparisons
[F-P06-049] WARNING: Use '->startOfDay()' / '->endOfDay()' for date-range queries
[F-P06-050] WARNING: Use 'date_format:Y-m-d' not just 'date'
[F-P06-051] WARNING: Validate date ranges
[F-P06-052] WARNING: Reject impossible dates
[F-P06-053] WARNING: 'allow_url_include = Off'
[F-P06-054] WARNING: 'disable_functions'
[F-P06-055] WARNING: 'expose_php = Off'
[F-P06-056] WARNING: 'session.use_strict_mode = 1'
[F-P06-057] WARNING: 'open_basedir'
[F-P06-058] WARNING: 'implode()' with reversed arguments
[F-P06-059] WARNING: 'SCREAMING_CASE' constants on built-in enums
[F-P06-060] WARNING: Legacy MySQL extensions
[F-P06-061] WARNING: Dynamic properties on classes
[F-P06-062] WARNING: 'utf8_encode()' / 'utf8_decode()'
[F-P06-063] WARNING: '${var}' string interpolation
[F-P06-064] WARNING: '#[\ReturnTypeWillChange]'
[F-P06-065] WARNING: Optional parameter before required
[F-P06-066] WARNING: All deprecation warnings treated as errors in CI
[F-P06-067] WARNING: Run 'rector' with PHP 9.0 rules
[F-P06-068] WARNING: No reliance on '__autoload()'
[F-P06-069] WARNING: Strict comparisons everywhere
[F-P06-070] WARNING: Backed enums ('string' or 'int') for all database-persisted enums
[F-P06-071] WARNING: TitleCase enum cases
[F-P06-072] WARNING: 'label()' method for human-readable text
[F-P06-073] WARNING: 'color()' method for UI badge colors
[F-P06-074] WARNING: 'allowedTransitions()' for state machines
[F-P06-075] WARNING: 'isTerminal()' for end states
[F-P06-076] WARNING: No business logic in enums
[F-P06-077] WARNING: TypeScript mirrors for frontend enums
[F-P06-078] WARNING: Validate with 'Rule::enum()'
[F-P06-079] WARNING: Never hardcode enum values as strings
[F-P06-080] WARNING: Fibers are cooperative, not parallel
[F-P06-081] WARNING: Use Fibers for non-blocking I/O
[F-P06-082] WARNING: Don't use Fibers for CPU-bound work
[F-P06-083] WARNING: Exception handling in Fibers
[F-P06-084] WARNING: Avoid long-running Fibers in request context
[F-P06-085] WARNING: Framework integration
[F-P06-086] WARNING: Use 'readonly class' for DTOs and Value Objects
[F-P06-087] WARNING: 'readonly' properties cannot be re-assigned
[F-P06-088] WARNING: 'readonly' classes cannot have non-readonly properties
[F-P06-089] WARNING: No dynamic properties on 'readonly' classes
[F-P06-090] WARNING: Clone and readonly
[F-P06-091] WARNING: Events should be readonly
[F-P06-092] WARNING: Don't use 'readonly' on Eloquent models
[F-P06-093] WARNING: Use 'Closure::fromCallable()' replacement syntax
[F-P06-094] WARNING: Use with 'array_map', 'array_filter'
[F-P06-095] WARNING: Use for route actions in tests
[F-P06-096] WARNING: Works with static methods
[F-P06-097] WARNING: Works with built-in functions
[F-P06-098] WARNING: Type-safe
[F-P06-099] WARNING: 'array_find()' (PHP 8.4)
[F-P06-100] WARNING: 'array_find_key()' (PHP 8.4)
[F-P06-101] WARNING: 'array_any()' (PHP 8.4)
[F-P06-102] WARNING: 'array_all()' (PHP 8.4)
[F-P06-103] WARNING: Use over Collection for simple arrays
[F-P06-104] WARNING: Prefer 'array_is_list()' (PHP 8.1)
[F-P06-105] WARNING: '#[Override]' (PHP 8.3)
[F-P06-106] WARNING: '#[Deprecated]' (PHP 8.4)
[F-P06-107] WARNING: Custom attributes for metadata
[F-P06-108] WARNING: Framework attributes
[F-P06-109] WARNING: No runtime reflection for attributes in hot paths
[F-P06-110] WARNING: Intersection types
[F-P06-111] WARNING: DNF (Disjunctive Normal Form) types (PHP 8.2)
[F-P06-112] WARNING: Use intersection types for DI parameters
[F-P06-113] WARNING: Prefer composition over complex types
[F-P06-114] WARNING: No intersection types with 'class' types
[F-P06-115] WARNING: 'get' and 'set' hooks
[F-P06-116] WARNING: Virtual properties
[F-P06-117] WARNING: Hooks work with 'readonly'
[F-P06-118] WARNING: Use for computed properties
[F-P06-119] WARNING: Don't use on Eloquent model properties
[F-P06-120] WARNING: Hooks are inherited
[F-P06-121] WARNING: Different visibility for read vs write
[F-P06-122] WARNING: Use for immutable-from-outside properties
[F-P06-123] WARNING: Constructor promotion syntax
[F-P06-124] WARNING: Set visibility must be equal or more restrictive than get
[F-P06-125] WARNING: Prefer over separate getter methods
[F-P06-126] WARNING: Value objects benefit most


### P07 — Clean Code & Software Design (128 checks)

[F-P07-001] WARNING: Controllers only dispatch to actions/services
[F-P07-002] WARNING: One action class per business operation
[F-P07-003] WARNING: Query objects for complex reads
[F-P07-004] WARNING: Validate preconditions at the top, return/throw immediately
[F-P07-005] WARNING: Use value objects for domain concepts
[F-P07-006] WARNING: Use typed DTOs for data transfer
[F-P07-007] WARNING: Value objects are immutable
[F-P07-008] WARNING: Value objects validate on construction
[F-P07-009] WARNING: Inject interfaces, not concrete classes
[F-P07-010] WARNING: Constructor injection over method injection
[F-P07-011] WARNING: Never call 'app()' in business logic
[F-P07-012] WARNING: No static facades in domain logic
[F-P07-013] WARNING: Classes: PascalCase, descriptive nouns
[F-P07-014] WARNING: Methods: camelCase, verb-first
[F-P07-015] WARNING: Variables: camelCase, descriptive
[F-P07-016] WARNING: Boolean methods: 'is', 'has', 'can', 'should' prefixes
[F-P07-017] WARNING: Constants and enum cases: PascalCase
[F-P07-018] WARNING: No abbreviations
[F-P07-019] WARNING: Consistent element order in classes
[F-P07-020] WARNING: Resource controller method order
[F-P07-021] WARNING: Model method order
[F-P07-022] WARNING: Don't abstract for one use case
[F-P07-023] WARNING: Don't create helpers/utilities for single-use operations
[F-P07-024] WARNING: Don't add configurability that isn't needed
[F-P07-025] WARNING: Methods under 20 lines (soft limit)
[F-P07-026] WARNING: One level of abstraction per method
[F-P07-027] WARNING: Maximum 2 levels of nesting
[F-P07-028] WARNING: No boolean parameters
[F-P07-029] WARNING: Code should be self-documenting
[F-P07-030] WARNING: Comments explain WHY, not WHAT
[F-P07-031] WARNING: PHPDoc for complex signatures
[F-P07-032] WARNING: No commented-out code
[F-P07-033] WARNING: No TODO without a ticket
[F-P07-034] WARNING: Avoid generic names
[F-P07-035] WARNING: Boolean variables read as assertions
[F-P07-036] WARNING: Collection variables are plural
[F-P07-037] WARNING: Methods that return booleans start with 'is', 'has', 'can', 'should'
[F-P07-038] WARNING: Methods that transform data describe the output
[F-P07-039] WARNING: Interfaces for all external boundaries
[F-P07-040] WARNING: Interfaces in 'app/Contracts/'
[F-P07-041] WARNING: One method per interface (where practical)
[F-P07-042] WARNING: Return types on interface methods
[F-P07-043] WARNING: Fake implementations for testing
[F-P07-044] WARNING: Bind in ServiceProvider
[F-P07-045] WARNING: Document interface contracts
[F-P07-046] WARNING: Traits are horizontal reuse, not inheritance substitutes
[F-P07-047] WARNING: Traits declare their dependencies
[F-P07-048] WARNING: No property conflicts
[F-P07-049] WARNING: Small, focused traits
[F-P07-050] WARNING: No business logic in traits
[F-P07-051] WARNING: Document trait requirements
[F-P07-052] WARNING: Test traits independently
[F-P07-053] WARNING: Events are past tense
[F-P07-054] WARNING: Events are immutable
[F-P07-055] WARNING: Events carry all necessary data
[F-P07-056] WARNING: Events are serializable
[F-P07-057] WARNING: One event per state change
[F-P07-058] WARNING: Event names are domain-specific
[F-P07-059] WARNING: Metadata separate from payload
[F-P07-060] WARNING: All VOs implement a common interface
[F-P07-061] WARNING: Validate on construction
[F-P07-062] WARNING: Immutable
[F-P07-063] WARNING: No identity
[F-P07-064] WARNING: '__toString()' for serialization
[F-P07-065] WARNING: Architecture test enforcement
[F-P07-066] WARNING: Factory methods for common creation patterns
[F-P07-067] WARNING: Separate read and write models
[F-P07-068] WARNING: Commands don't return data
[F-P07-069] WARNING: Queries don't change state
[F-P07-070] WARNING: Read models are denormalized
[F-P07-071] WARNING: Separate database connections for reads
[F-P07-072] WARNING: Eventually consistent reads are acceptable
[F-P07-073] WARNING: Don't over-apply
[F-P07-074] WARNING: Eloquent IS the repository
[F-P07-075] WARNING: Use repositories when you need to swap implementations
[F-P07-076] WARNING: Use Query objects instead
[F-P07-077] WARNING: Never wrap Eloquent just for testability
[F-P07-078] WARNING: If you use repositories, they return domain objects
[F-P07-079] WARNING: Each module owns its models, actions, and routes
[F-P07-080] WARNING: Cross-context communication via events or service interfaces
[F-P07-081] WARNING: Shared kernel is minimal
[F-P07-082] WARNING: No cross-context database JOINs
[F-P07-083] WARNING: Context map documents relationships
[F-P07-084] WARNING: Naming reflects the context
[F-P07-085] WARNING: Avoid monolithic route files
[F-P07-086] WARNING: Use Laravel Pipelines for sequential processing
[F-P07-087] WARNING: Each pipe has a single responsibility
[F-P07-088] WARNING: Pipes are reusable
[F-P07-089] WARNING: Pipes throw exceptions on failure
[F-P07-090] WARNING: Order matters
[F-P07-091] WARNING: Test each pipe in isolation
[F-P07-092] WARNING: Don't nest pipelines
[F-P07-093] WARNING: Encapsulate complex business predicates
[F-P07-094] WARNING: Compose specifications
[F-P07-095] WARNING: Use for dynamic filtering
[F-P07-096] WARNING: Specifications are testable
[F-P07-097] WARNING: Document the business rule
[F-P07-098] WARNING: Don't over-engineer for simple boolean checks
[F-P07-099] WARNING: Replace null checks with a Null Object
[F-P07-100] WARNING: Use for optional dependencies
[F-P07-101] WARNING: Null Objects are singletons
[F-P07-102] WARNING: Don't use when null has meaning
[F-P07-103] WARNING: Named constructors clarify intent
[F-P07-104] WARNING: Replace conditionals with strategies
[F-P07-105] WARNING: Inject strategies, don't construct inline
[F-P07-106] WARNING: Strategies are interchangeable at runtime
[F-P07-107] WARNING: Each strategy is independently testable
[F-P07-108] WARNING: Prefer over 'match'/'switch' when branches have complex logic
[F-P07-109] WARNING: Use builders for objects with many optional parameters
[F-P07-110] WARNING: Builders enforce required parameters
[F-P07-111] WARNING: Immutable builders
[F-P07-112] WARNING: Use for query construction
[F-P07-113] WARNING: Use for notification construction
[F-P07-114] WARNING: Don't use when a constructor is clear
[F-P07-115] WARNING: Default to immutable, opt into mutability
[F-P07-116] WARNING: Value Objects are always immutable
[F-P07-117] WARNING: Events are always immutable
[F-P07-118] WARNING: Use 'CarbonImmutable' over 'Carbon'
[F-P07-119] WARNING: Collections: '->toImmutable()' for shared data
[F-P07-120] WARNING: Immutable DTOs for cross-boundary data
[F-P07-121] WARNING: Mutation returns new instances
[F-P07-122] WARNING: Mutable state is explicitly scoped
[F-P07-123] WARNING: Wrap primitive values in domain-specific types
[F-P07-124] WARNING: Self-validating on construction
[F-P07-125] WARNING: Type-safe function signatures
[F-P07-126] WARNING: Equality by value
[F-P07-127] WARNING: Use for: account codes, currency codes, reference numbers, BVN, phone numbers
[F-P07-128] WARNING: Don't micro-type everything


### P08 — Laravel Framework Mastery (196 checks)

[F-P08-001] WARNING: Eager load relationships
[F-P08-002] WARNING: Controllers under 10 lines per method
[F-P08-003] WARNING: Models should only have
[F-P08-004] WARNING: Business logic belongs in Actions/Services
[F-P08-005] WARNING: Use Eloquent or Query Builder
[F-P08-006] WARNING: Use 'Model::query()' not 'DB::table()'
[F-P08-007] WARNING: Never use 'env()' outside 'config/' files
[F-P08-008] WARNING: Every controller store/update uses a Form Request
[F-P08-009] WARNING: Array-based rules, not string pipe syntax
[F-P08-010] WARNING: Every model with user-owned data has a Policy
[F-P08-011] WARNING: Policies check ownership, not just permissions
[F-P08-012] WARNING: Send emails via queued notifications
[F-P08-013] WARNING: Queue PDF generation, CSV exports, report building
[F-P08-014] WARNING: Queue webhook dispatching
[F-P08-015] WARNING: Dedicated Form Request for every store/update
[F-P08-016] WARNING: 'authorize()' method returns a boolean or policy check
[F-P08-017] WARNING: Use 'Rule::enum()' for enum validation
[F-P08-018] WARNING: Custom validation messages
[F-P08-019] WARNING: Custom validation rules
[F-P08-020] WARNING: Validate on input, escape on output
[F-P08-021] WARNING: Use '$request->validated()' or '$request->safe()'
[F-P08-022] WARNING: Validate file types by MIME, not extension
[F-P08-023] WARNING: Limit string lengths
[F-P08-024] WARNING: Limit array sizes
[F-P08-025] WARNING: Use select/dropdown for period inputs
[F-P08-026] WARNING: Validate date ranges
[F-P08-027] WARNING: Use domain-specific exceptions, not generic ones
[F-P08-028] WARNING: Extend a base domain exception
[F-P08-029] WARNING: Map exceptions to HTTP status codes
[F-P08-030] WARNING: Never catch '\Exception' or '\Throwable' and silently swallow
[F-P08-031] WARNING: Never expose internal exception messages to users
[F-P08-032] WARNING: Never expose stack traces in production
[F-P08-033] WARNING: Use 'report()' helper for non-fatal exceptions
[F-P08-034] WARNING: Use 'abort()' for HTTP exceptions
[F-P08-035] WARNING: Define '$dontReport' / '$dontFlash' in exception handler
[F-P08-036] WARNING: Return consistent JSON error structure for APIs
[F-P08-037] WARNING: Validation errors return 422
[F-P08-038] WARNING: Authorization failures return 403
[F-P08-039] WARNING: Not found returns 404
[F-P08-040] WARNING: Rate limit exceeded returns 429
[F-P08-041] WARNING: Catch at the boundary, not deep in business logic
[F-P08-042] WARNING: Third-party API calls always wrapped in try/catch
[F-P08-043] WARNING: Never catch exceptions inside DB transactions just to continue
[F-P08-044] WARNING: Never put heavy logic in 'boot()' / 'booted()'
[F-P08-045] WARNING: Avoid 'creating'/'updating' observers for business logic
[F-P08-046] WARNING: Use 'withoutEvents()' for bulk operations
[F-P08-047] WARNING: Define '$hidden' on all models with sensitive data
[F-P08-048] WARNING: Use API Resources for responses, never raw '$model->toArray()'
[F-P08-049] WARNING: Beware of '$appends'
[F-P08-050] WARNING: Never pass full models to queued jobs
[F-P08-051] WARNING: Use 'Attribute' accessor syntax (Laravel 9+)
[F-P08-052] WARNING: Never put side effects in accessors
[F-P08-053] WARNING: Terminal-state records must be immutable
[F-P08-054] WARNING: Use '$model->isDirty()' / '$model->wasChanged()' for change detection
[F-P08-055] WARNING: Use named scopes for reusable filters
[F-P08-056] WARNING: Global scopes need careful consideration
[F-P08-057] WARNING: Soft delete IS a global scope
[F-P08-058] WARNING: Never use user input as cache keys without hashing
[F-P08-059] WARNING: Cache key collisions
[F-P08-060] WARNING: Never cache authenticated user data globally
[F-P08-061] WARNING: Invalidate cache when underlying data changes
[F-P08-062] WARNING: Set appropriate TTLs
[F-P08-063] WARNING: Use 'Cache::lock()' for cache stampede prevention
[F-P08-064] WARNING: Cached data must be serializable
[F-P08-065] WARNING: Don't cache Eloquent models directly
[F-P08-066] WARNING: Beware of stale cache after deployments
[F-P08-067] WARNING: Events should be data carriers, not logic executors
[F-P08-068] WARNING: Use typed properties on events
[F-P08-069] WARNING: Events dispatched inside transactions may need 'afterCommit'
[F-P08-070] WARNING: Queue listeners that do external I/O
[F-P08-071] WARNING: Handle listener failures gracefully
[F-P08-072] WARNING: No circular event chains
[F-P08-073] WARNING: Order-dependent listeners need explicit ordering
[F-P08-074] WARNING: Events carry all data needed for projection
[F-P08-075] WARNING: Every recorded event has an 'apply' method on the aggregate
[F-P08-076] WARNING: Event metadata attached consistently
[F-P08-077] WARNING: Audit reactor handles every event type
[F-P08-078] WARNING: 'register()' — bind interfaces, register singletons
[F-P08-079] WARNING: 'boot()' — configure observers, gates, macros
[F-P08-080] WARNING: Never do database queries in 'register()' or 'boot()'
[F-P08-081] WARNING: Use 'DeferrableProvider' for rarely-used bindings
[F-P08-082] WARNING: Don't register the same binding twice
[F-P08-083] WARNING: Don't register middleware in providers
[F-P08-084] WARNING: Don't put business logic in providers
[F-P08-085] WARNING: One config file per domain
[F-P08-086] WARNING: Env-driven values only at config level
[F-P08-087] WARNING: Type-cast env values
[F-P08-088] WARNING: Default values for all env() calls
[F-P08-089] WARNING: No nested closures in config files
[F-P08-090] WARNING: Config keys are snake_case
[F-P08-091] WARNING: Set response headers in '$next()' response, not before
[F-P08-092] WARNING: Don't mutate the request object
[F-P08-093] WARNING: Terminate middleware for post-response work
[F-P08-094] WARNING: Middleware should be stateless
[F-P08-095] WARNING: Order matters
[F-P08-096] WARNING: Short-circuit early
[F-P08-097] WARNING: 'authorize()' returns policy check
[F-P08-098] WARNING: 'prepareForValidation()' for input normalization
[F-P08-099] WARNING: 'passedValidation()' for post-validation transforms
[F-P08-100] WARNING: Conditional rules with 'sometimes()'
[F-P08-101] WARNING: Custom rule objects over closure rules
[F-P08-102] WARNING: Nested array validation
[F-P08-103] WARNING: Error message customization
[F-P08-104] WARNING: Observers fire in registration order
[F-P08-105] WARNING: 'creating' fires before 'created'
[F-P08-106] WARNING: 'updating' gets dirty attributes
[F-P08-107] WARNING: 'deleting' fires before soft delete
[F-P08-108] WARNING: Observers don't fire on bulk operations
[F-P08-109] WARNING: Avoid heavy work in observers
[F-P08-110] WARNING: Model events don't fire in 'DB::table()' queries
[F-P08-111] WARNING: Always use 'php artisan make:*'
[F-P08-112] WARNING: Pass '--no-interaction'
[F-P08-113] WARNING: Use relevant flags
[F-P08-114] WARNING: Use 'make:test --pest'
[F-P08-115] WARNING: Use 'make:class'
[F-P08-116] WARNING: Verify file after generation
[F-P08-117] WARNING: Every model has a factory
[F-P08-118] WARNING: Factories produce valid default state
[F-P08-119] WARNING: Use factory states for variations
[F-P08-120] WARNING: Factories respect unique constraints
[F-P08-121] WARNING: No real data in factories
[F-P08-122] WARNING: Seeders are idempotent
[F-P08-123] WARNING: Seeders don't depend on execution order
[F-P08-124] WARNING: Production seeders separate from dev seeders
[F-P08-125] WARNING: No 'DB::table()->truncate()' in production seeders
[F-P08-126] WARNING: Use transactions in seeders
[F-P08-127] WARNING: Implement 'CastsAttributes' for complex types
[F-P08-128] WARNING: Casts are stateless
[F-P08-129] WARNING: Handle 'null' gracefully
[F-P08-130] WARNING: Custom casts for enums with extra logic
[F-P08-131] WARNING: Test casts with real models
[F-P08-132] WARNING: Register reusable casts in a service provider
[F-P08-133] WARNING: Contextual bindings
[F-P08-134] WARNING: Scoped singletons for request lifecycle
[F-P08-135] WARNING: Tagged bindings for collecting implementations
[F-P08-136] WARNING: Deferred providers for performance
[F-P08-137] WARNING: No service location
[F-P08-138] WARNING: 'bind' vs 'singleton' vs 'scoped'
[F-P08-139] WARNING: Test bindings
[F-P08-140] WARNING: Register macros in service providers
[F-P08-141] WARNING: Type-hint macro parameters
[F-P08-142] WARNING: PHPDoc for IDE support
[F-P08-143] WARNING: Don't override built-in methods
[F-P08-144] WARNING: Macros are global
[F-P08-145] WARNING: Prefer scopes on models over macros
[F-P08-146] WARNING: Test macros
[F-P08-147] WARNING: Custom binding resolution
[F-P08-148] WARNING: Scoped bindings
[F-P08-149] WARNING: Soft-deleted model binding
[F-P08-150] WARNING: Enum route binding
[F-P08-151] WARNING: Custom keys via 'getRouteKeyName()'
[F-P08-152] WARNING: Don't resolve in controller
[F-P08-153] WARNING: Use 'Laravel\Prompts' for Artisan command UX
[F-P08-154] WARNING: Validate input with prompts
[F-P08-155] WARNING: Use 'spin()' for long operations
[F-P08-156] WARNING: Fallback for non-interactive mode
[F-P08-157] WARNING: Use 'table()' for tabular output
[F-P08-158] WARNING: Use 'info()', 'warn()', 'error()'
[F-P08-159] WARNING: Create rule objects
[F-P08-160] WARNING: Rules are unit-testable
[F-P08-161] WARNING: Use 'Rule::when()' for conditional rules
[F-P08-162] WARNING: Use 'Rule::enum()'
[F-P08-163] WARNING: Avoid closure rules in production
[F-P08-164] WARNING: Custom error messages with ':attribute' placeholder
[F-P08-165] WARNING: One notification class per event
[F-P08-166] WARNING: Implement 'ShouldQueue'
[F-P08-167] WARNING: Use 'via()' to control channels
[F-P08-168] WARNING: Database notifications have structured data
[F-P08-169] WARNING: Rate limit notifications
[F-P08-170] WARNING: Notification preferences per user
[F-P08-171] WARNING: Test notifications
[F-P08-172] WARNING: No sensitive data in notifications
[F-P08-173] WARNING: Use the 'Attribute' return type (Laravel 9+)
[F-P08-174] WARNING: Computed attributes go in '$appends'
[F-P08-175] WARNING: Don't put business logic in accessors
[F-P08-176] WARNING: Avoid expensive accessors
[F-P08-177] WARNING: Mutators validate input
[F-P08-178] WARNING: Don't use accessors for formatted dates
[F-P08-179] WARNING: Test accessors and mutators
[F-P08-180] WARNING: Know the event order
[F-P08-181] WARNING: 'saving' fires on both create and update
[F-P08-182] WARNING: Events don't fire on mass operations
[F-P08-183] WARNING: 'deleted' doesn't fire on 'DB::table()' deletes
[F-P08-184] WARNING: Observers are registered globally
[F-P08-185] WARNING: Beware of infinite loops
[F-P08-186] WARNING: Use 'Model::withoutEvents()' for seed/migration operations
[F-P08-187] WARNING: 'booted()' for model boot logic
[F-P08-188] WARNING: One Action, one job
[F-P08-189] WARNING: Actions receive typed parameters
[F-P08-190] WARNING: Actions don't depend on HTTP context
[F-P08-191] WARNING: Actions are testable without HTTP
[F-P08-192] WARNING: Actions call other Actions
[F-P08-193] WARNING: Actions wrap transactions
[F-P08-194] WARNING: Actions throw domain exceptions
[F-P08-195] WARNING: Actions don't return HTTP responses
[F-P08-196] WARNING: Naming convention


### P09 — Database Engineering (158 checks)

[F-P09-001] WARNING: Column modifications preserve existing attributes
[F-P09-002] WARNING: Foreign keys use appropriate delete action
[F-P09-003] WARNING: Add 'down()' method that reverses 'up()'
[F-P09-004] WARNING: Add indexes on all foreign keys
[F-P09-005] WARNING: Add composite indexes for common query patterns
[F-P09-006] WARNING: Use 'uuid()' or 'ulid()' for public-facing IDs
[F-P09-007] WARNING: Unique constraints on natural keys
[F-P09-008] WARNING: Composite indexes match query column order
[F-P09-009] WARNING: Index columns used in WHERE, ORDER BY, JOIN
[F-P09-010] WARNING: No redundant indexes
[F-P09-011] WARNING: All domain models use 'SoftDeletes'
[F-P09-012] WARNING: Unique constraints with soft deletes
[F-P09-013] WARNING: 'withTrashed()' in relationships where needed
[F-P09-014] WARNING: Use 'select()' to limit columns
[F-P09-015] WARNING: Use 'chunk()' or 'cursor()' for large datasets
[F-P09-016] WARNING: Use 'exists()' not 'count() > 0'
[F-P09-017] WARNING: Use 'pluck()' for single-column results
[F-P09-018] WARNING: Use database-level aggregation
[F-P09-019] WARNING: Understand your isolation level
[F-P09-020] WARNING: Use 'lockForUpdate()' for pessimistic locking
[F-P09-021] WARNING: Use 'sharedLock()' for read consistency
[F-P09-022] WARNING: Morph maps for polymorphic relationships
[F-P09-023] WARNING: 'enforceMorphMap()' in production
[F-P09-024] WARNING: UUID morph columns for UUID models
[F-P09-025] WARNING: Eager load to prevent N+1
[F-P09-026] WARNING: 'withCount()' instead of loading the full relation
[F-P09-027] WARNING: 'has()' / 'whereHas()' for existence filters
[F-P09-028] WARNING: 'withTrashed()' for soft-deleted parent access
[F-P09-029] WARNING: Use 'sync()' carefully
[F-P09-030] WARNING: Validate pivot data
[F-P09-031] WARNING: Every list endpoint is paginated
[F-P09-032] WARNING: Cap 'per_page' parameter
[F-P09-033] WARNING: Use cursor pagination for large datasets
[F-P09-034] WARNING: API responses include pagination metadata
[F-P09-035] WARNING: Queue large exports
[F-P09-036] WARNING: Stream large downloads
[F-P09-037] WARNING: Limit export row counts
[F-P09-038] WARNING: Add columns as nullable first, then backfill, then add constraints
[F-P09-039] WARNING: Never rename columns in one step
[F-P09-040] WARNING: Never drop columns that code still references
[F-P09-041] WARNING: Use 'after()' for column ordering
[F-P09-042] WARNING: Create indexes concurrently when possible
[F-P09-043] WARNING: Drop indexes before dropping columns
[F-P09-044] WARNING: Test both 'up()' and 'down()'
[F-P09-045] WARNING: Test migration on production-sized data
[F-P09-046] WARNING: Run 'migrate --pretend' first
[F-P09-047] WARNING: Use '->first()' with caution — it returns 'null'
[F-P09-048] WARNING: Don't chain methods on potentially null results
[F-P09-049] WARNING: Use '->isEmpty()' / '->isNotEmpty()'
[F-P09-050] WARNING: Use '->whenNotEmpty()' / '->whenEmpty()'
[F-P09-051] WARNING: '->pluck()' returns duplicates
[F-P09-052] WARNING: '->map()' vs '->transform()'
[F-P09-053] WARNING: '->filter()' without callback removes falsy values
[F-P09-054] WARNING: '->each()' cannot break early
[F-P09-055] WARNING: '->reduce()' needs an initial value
[F-P09-056] WARNING: 'array_merge()' re-indexes numeric keys
[F-P09-057] WARNING: '[...$a, ...$b]' — later values overwrite earlier ones
[F-P09-058] WARNING: Never pass raw user input to 'where()' column names
[F-P09-059] WARNING: Use Spatie Query Builder 'AllowedFilter'
[F-P09-060] WARNING: Use 'AllowedSort' to prevent SQL injection via sort columns
[F-P09-061] WARNING: Default sorts prevent unpredictable ordering
[F-P09-062] WARNING: Filter by enum value, not raw string
[F-P09-063] WARNING: Scope nested relationships
[F-P09-064] WARNING: Paginate all query builder results
[F-P09-065] WARNING: Unique constraints break with soft deletes
[F-P09-066] WARNING: 'withTrashed()' in relationships for FK integrity
[F-P09-067] WARNING: 'forceDelete()' requires explicit confirmation
[F-P09-068] WARNING: Cascade soft deletes manually
[F-P09-069] WARNING: Restore cascades
[F-P09-070] WARNING: Global scope means 'count()' excludes deleted
[F-P09-071] WARNING: Pruning old soft-deleted records
[F-P09-072] WARNING: Cast JSON columns with ''array'' or ''collection''
[F-P09-073] WARNING: Validate JSON structure
[F-P09-074] WARNING: Index JSON paths for queries
[F-P09-075] WARNING: Don't store relational data in JSON
[F-P09-076] WARNING: Beware of 'null' vs missing key
[F-P09-077] WARNING: JSON merge update safety
[F-P09-078] WARNING: Use UUIDv7 (time-sorted) for primary keys
[F-P09-079] WARNING: Use UUIDv4 for non-sequential tokens
[F-P09-080] WARNING: Use ULID for human-readable sorted IDs
[F-P09-081] WARNING: Consistent column types
[F-P09-082] WARNING: Type hint UUID IDs as 'string'
[F-P09-083] WARNING: Don't expose auto-increment IDs alongside UUIDs
[F-P09-084] WARNING: Index UUID columns
[F-P09-085] WARNING: Normalize to 3NF by default
[F-P09-086] WARNING: Star schema for reporting tables
[F-P09-087] WARNING: Lookup tables for reference data
[F-P09-088] WARNING: History tables for temporal data
[F-P09-089] WARNING: Audit columns on every table
[F-P09-090] WARNING: UUID primary keys for distributed systems
[F-P09-091] WARNING: No nullable foreign keys without justification
[F-P09-092] WARNING: Document schema decisions
[F-P09-093] WARNING: Enable strict mode
[F-P09-094] WARNING: 'NO_ZERO_DATE'
[F-P09-095] WARNING: 'NO_ZERO_IN_DATE'
[F-P09-096] WARNING: 'ERROR_FOR_DIVISION_BY_ZERO'
[F-P09-097] WARNING: 'ONLY_FULL_GROUP_BY'
[F-P09-098] WARNING: Test with production 'sql_mode'
[F-P09-099] WARNING: Laravel's 'strict' mode in database config
[F-P09-100] WARNING: No 'SET sql_mode = ''' workarounds
[F-P09-101] WARNING: Configure read/write connections
[F-P09-102] WARNING: 'sticky' option for read-your-own-writes
[F-P09-103] WARNING: Reports and dashboards use read connection
[F-P09-104] WARNING: Replication lag awareness
[F-P09-105] WARNING: Don't use replicas for financial consistency checks
[F-P09-106] WARNING: Monitor replication lag
[F-P09-107] WARNING: Failover to primary
[F-P09-108] WARNING: EXPLAIN every slow query
[F-P09-109] WARNING: Composite indexes match query order
[F-P09-110] WARNING: Leftmost prefix rule
[F-P09-111] WARNING: Covering indexes
[F-P09-112] WARNING: Don't over-index
[F-P09-113] WARNING: Remove unused indexes
[F-P09-114] WARNING: Index for 'ORDER BY'
[F-P09-115] WARNING: 'FORCE INDEX' as last resort
[F-P09-116] WARNING: Partition by date for time-series data
[F-P09-117] WARNING: Partition pruning
[F-P09-118] WARNING: Don't partition small tables
[F-P09-119] WARNING: Unique indexes must include the partition key
[F-P09-120] WARNING: Archive old partitions
[F-P09-121] WARNING: Test partition boundaries
[F-P09-122] WARNING: Application-transparent
[F-P09-123] WARNING: Set 'max_connections' appropriately
[F-P09-124] WARNING: Laravel's persistent connections
[F-P09-125] WARNING: Use a connection pooler for high concurrency
[F-P09-126] WARNING: Monitor connection count
[F-P09-127] WARNING: Close connections in long-running processes
[F-P09-128] WARNING: Set 'wait_timeout'
[F-P09-129] WARNING: Connection per queue worker
[F-P09-130] WARNING: Every migration has a working 'down()' method
[F-P09-131] WARNING: 'down()' reverses the 'up()' exactly
[F-P09-132] WARNING: Destructive 'down()' methods are acceptable
[F-P09-133] WARNING: Cannot rollback data migrations
[F-P09-134] WARNING: Test the full cycle
[F-P09-135] WARNING: Don't modify old migrations
[F-P09-136] WARNING: Squash migrations periodically
[F-P09-137] WARNING: Enable MySQL slow query log
[F-P09-138] WARNING: Laravel query log in development
[F-P09-139] WARNING: Use 'DB::listen()' for real-time monitoring
[F-P09-140] WARNING: 'preventLazyLoading()' in non-production
[F-P09-141] WARNING: Identify N+1 queries
[F-P09-142] WARNING: Monitor query count per request
[F-P09-143] WARNING: EXPLAIN slow queries before optimizing
[F-P09-144] WARNING: Dashboard for slow queries
[F-P09-145] WARNING: Default to 'RESTRICT'
[F-P09-146] WARNING: 'CASCADE' only for owned children
[F-P09-147] WARNING: Never 'CASCADE' on financial records
[F-P09-148] WARNING: 'SET NULL' for optional relationships
[F-P09-149] WARNING: Document cascade behavior
[F-P09-150] WARNING: Test cascade behavior
[F-P09-151] WARNING: Review cascades on schema changes
[F-P09-152] WARNING: Unique constraints prevent duplicate data
[F-P09-153] WARNING: Order matters for composite indexes
[F-P09-154] WARNING: NULL handling in unique constraints
[F-P09-155] WARNING: Application-level uniqueness + DB constraint
[F-P09-156] WARNING: Composite unique with soft deletes
[F-P09-157] WARNING: Don't use composite primary keys with Eloquent
[F-P09-158] WARNING: Migration naming for constraints


### P10 — Frontend Engineering (153 checks)

[F-P10-001] WARNING: Disable browser autofill on sensitive forms
[F-P10-002] WARNING: Never use 'autocomplete="email"', '"password"', '"name"'
[F-P10-003] WARNING: Never use 'v-html' with user data (Vue)
[F-P10-004] WARNING: Never use 'dangerouslySetInnerHTML' with user data (React)
[F-P10-005] WARNING: Validate URL schemes
[F-P10-006] WARNING: Format with dedicated formatter, not string templates
[F-P10-007] WARNING: No arithmetic with JS native numbers on money
[F-P10-008] WARNING: SameSite=Strict for high-security apps
[F-P10-009] WARNING: Nonce-based CSP
[F-P10-010] WARNING: 'HttpOnly' on session cookies
[F-P10-011] WARNING: 'Secure' flag on all cookies in production
[F-P10-012] WARNING: Use UTF-8 everywhere
[F-P10-013] WARNING: Database charset is 'utf8mb4'
[F-P10-014] WARNING: Use 'mb_*' functions for string operations
[F-P10-015] WARNING: 'htmlspecialchars()' with 'ENT_QUOTES | ENT_SUBSTITUTE'
[F-P10-016] WARNING: Specify charset in 'htmlspecialchars()'
[F-P10-017] WARNING: JSON encoding with 'JSON_UNESCAPED_UNICODE'
[F-P10-018] WARNING: 'utf8mb4_unicode_ci' collation
[F-P10-019] WARNING: Beware of Unicode normalization
[F-P10-020] WARNING: Use Laravel's '__()' helper for user-facing strings
[F-P10-021] WARNING: Never concatenate translated strings
[F-P10-022] WARNING: Validate locale input
[F-P10-023] WARNING: Use proper heading hierarchy
[F-P10-024] WARNING: Use '<button>' for actions, '<a>' for navigation
[F-P10-025] WARNING: Form inputs have '<label>' elements
[F-P10-026] WARNING: Tables use '<th>' for headers with 'scope="col"' or 'scope="row"'
[F-P10-027] WARNING: Interactive elements are keyboard-accessible
[F-P10-028] WARNING: 'aria-label' on icon-only buttons
[F-P10-029] WARNING: 'role' attributes on custom widgets
[F-P10-030] WARNING: Focus management
[F-P10-031] WARNING: Visible focus indicators
[F-P10-032] WARNING: Color contrast ratio minimum 4.5:1
[F-P10-033] WARNING: Don't convey information by color alone
[F-P10-034] WARNING: Responsive text
[F-P10-035] WARNING: Reduced motion support
[F-P10-036] WARNING: Only send necessary data as props
[F-P10-037] WARNING: Never send passwords, tokens, or secrets as props
[F-P10-038] WARNING: Filter shared props
[F-P10-039] WARNING: Deferred props load after initial page render
[F-P10-040] WARNING: Optional props not sent unless requested
[F-P10-041] WARNING: Don't defer auth/permission data
[F-P10-042] WARNING: 'preserveScroll' on form submissions
[F-P10-043] WARNING: 'preserveState' for filter/search interactions
[F-P10-044] WARNING: 'replace: true' for redirects within flows
[F-P10-045] WARNING: Handle 419 (CSRF token mismatch) gracefully
[F-P10-046] WARNING: No 'window', 'document', 'localStorage' during SSR
[F-P10-047] WARNING: SSR response doesn't contain user-specific data in HTML source
[F-P10-048] WARNING: Strict TypeScript
[F-P10-049] WARNING: No 'any' type
[F-P10-050] WARNING: Define interfaces for all API responses
[F-P10-051] WARNING: Type Inertia page props
[F-P10-052] WARNING: Enum mirrors for backend enums
[F-P10-053] WARNING: No 'as' type assertions
[F-P10-054] WARNING: Null checks before property access
[F-P10-055] WARNING: Function return types explicit
[F-P10-056] WARNING: '<script setup lang="ts">'
[F-P10-057] WARNING: Props are typed and documented
[F-P10-058] WARNING: Emits are typed
[F-P10-059] WARNING: Composables for reusable logic
[F-P10-060] WARNING: No business logic in templates
[F-P10-061] WARNING: 'v-if' before 'v-for'
[F-P10-062] WARNING: Key all 'v-for' loops
[F-P10-063] WARNING: Cleanup side effects in 'onUnmounted()'
[F-P10-064] WARNING: No direct DOM manipulation
[F-P10-065] WARNING: Use design tokens, not raw values
[F-P10-066] WARNING: Consistent spacing scale
[F-P10-067] WARNING: Responsive design mobile-first
[F-P10-068] WARNING: Dark mode support
[F-P10-069] WARNING: No '!important' via '!' prefix
[F-P10-070] WARNING: Extract repeated patterns into components
[F-P10-071] WARNING: Purge unused CSS in production
[F-P10-072] WARNING: Use 'cn()' utility for conditional classes
[F-P10-073] WARNING: Analyze bundle size
[F-P10-074] WARNING: Tree shaking works only with ES modules
[F-P10-075] WARNING: Avoid barrel file re-exports
[F-P10-076] WARNING: Dynamic imports for route-level code splitting
[F-P10-077] WARNING: Lazy load heavy libraries
[F-P10-078] WARNING: Monitor bundle size in CI
[F-P10-079] WARNING: Remove unused dependencies
[F-P10-080] WARNING: CSS purging
[F-P10-081] WARNING: LCP (Largest Contentful Paint) < 2.5s
[F-P10-082] WARNING: FID / INP (Interaction to Next Paint) < 200ms
[F-P10-083] WARNING: CLS (Cumulative Layout Shift) < 0.1
[F-P10-084] WARNING: Preload critical assets
[F-P10-085] WARNING: Compress responses
[F-P10-086] WARNING: Cache static assets aggressively
[F-P10-087] WARNING: Measure in the field
[F-P10-088] WARNING: Font display strategy
[F-P10-089] WARNING: Avoid render-blocking resources
[F-P10-090] WARNING: Server is the source of truth
[F-P10-091] WARNING: Use 'useForm()' for form state
[F-P10-092] WARNING: Composables for shared reactive state
[F-P10-093] WARNING: Don't use Pinia/Vuex with Inertia
[F-P10-094] WARNING: Optimistic updates with rollback
[F-P10-095] WARNING: Debounce search inputs
[F-P10-096] WARNING: Clear state on navigation
[F-P10-097] WARNING: URL is state
[F-P10-098] WARNING: Server-side validation is the authority
[F-P10-099] WARNING: Display server errors per field
[F-P10-100] WARNING: Disable submit button during processing
[F-P10-101] WARNING: Preserve scroll position on error
[F-P10-102] WARNING: Clear errors on input change
[F-P10-103] WARNING: Confirm destructive actions
[F-P10-104] WARNING: Success feedback
[F-P10-105] WARNING: Disable browser autofill on accounting forms
[F-P10-106] WARNING: Tab order is logical
[F-P10-107] WARNING: Vue 'onErrorCaptured()' for component-level error boundaries
[F-P10-108] WARNING: Fallback UI for failed deferred props
[F-P10-109] WARNING: Global error handler
[F-P10-110] WARNING: Retry buttons
[F-P10-111] WARNING: Graceful degradation for non-critical features
[F-P10-112] WARNING: Error tracking integration
[F-P10-113] WARNING: No unhandled promise rejections
[F-P10-114] WARNING: Core functionality works without JavaScript
[F-P10-115] WARNING: No-JS fallback for critical flows
[F-P10-116] WARNING: Loading states for deferred content
[F-P10-117] WARNING: Semantic HTML first
[F-P10-118] WARNING: Links are links, buttons are buttons
[F-P10-119] WARNING: Print stylesheets
[F-P10-120] WARNING: Reduced motion
[F-P10-121] WARNING: All interactive elements are focusable
[F-P10-122] WARNING: Visible focus indicators
[F-P10-123] WARNING: Tab order follows visual order
[F-P10-124] WARNING: Escape closes modals and popovers
[F-P10-125] WARNING: Arrow keys navigate within composite widgets
[F-P10-126] WARNING: Skip navigation link
[F-P10-127] WARNING: Focus trapping in modals
[F-P10-128] WARNING: Announce dynamic changes
[F-P10-129] WARNING: Keyboard shortcuts documented
[F-P10-130] WARNING: Capture unhandled exceptions
[F-P10-131] WARNING: Source maps in production (private)
[F-P10-132] WARNING: Context with errors
[F-P10-133] WARNING: Breadcrumbs
[F-P10-134] WARNING: Rate limit error reporting
[F-P10-135] WARNING: Distinguish errors by severity
[F-P10-136] WARNING: 'ChunkLoadError' handling
[F-P10-137] WARNING: Serve modern formats
[F-P10-138] WARNING: Responsive images
[F-P10-139] WARNING: 'loading="lazy"' for below-the-fold images
[F-P10-140] WARNING: 'decoding="async"'
[F-P10-141] WARNING: Explicit 'width' and 'height'
[F-P10-142] WARNING: SVG for icons and logos
[F-P10-143] WARNING: No images in CSS 'background-image' for content images
[F-P10-144] WARNING: Image CDN for dynamic resizing
[F-P10-145] WARNING: CSS custom properties for theme colors
[F-P10-146] WARNING: Respect 'prefers-color-scheme'
[F-P10-147] WARNING: User override persisted
[F-P10-148] WARNING: Tailwind 'dark:' variant
[F-P10-149] WARNING: Test both modes
[F-P10-150] WARNING: Sufficient contrast in both modes
[F-P10-151] WARNING: No flash on load
[F-P10-152] WARNING: Charts and graphs adapt
[F-P10-153] WARNING: Print always uses light mode


### P11 — Testing & Quality Assurance (76 checks)

[F-P11-001] WARNING: Feature tests for every action/controller
[F-P11-002] WARNING: Unit tests for value objects, DTOs, and pure functions
[F-P11-003] WARNING: Use factories, not manual model creation
[F-P11-004] WARNING: Use 'it()' syntax (Pest)
[F-P11-005] WARNING: Assert specific outcomes, not just "no errors"
[F-P11-006] WARNING: Assert side effects
[F-P11-007] WARNING: Test validation rules
[F-P11-008] WARNING: Test authorization
[F-P11-009] WARNING: Test concurrent access
[F-P11-010] WARNING: Test boundary values
[F-P11-011] WARNING: Test state machine transitions
[F-P11-012] WARNING: Test SoD enforcement
[F-P11-013] WARNING: Test period lock enforcement
[F-P11-014] WARNING: Test idempotency
[F-P11-015] WARNING: No 'sleep()' in tests
[F-P11-016] WARNING: No hardcoded IDs
[F-P11-017] WARNING: Resolve injected actions from container
[F-P11-018] WARNING: Clean state per test
[F-P11-019] WARNING: Know the terminology
[F-P11-020] WARNING: Prefer fakes over mocks
[F-P11-021] WARNING: Don't mock what you don't own
[F-P11-022] WARNING: Laravel's built-in fakes
[F-P11-023] WARNING: Assert specific interactions, not all interactions
[F-P11-024] WARNING: Spies are for "did this happen?" assertions
[F-P11-025] WARNING: Clean up mocks
[F-P11-026] WARNING: Mutation testing finds weak tests
[F-P11-027] WARNING: Use Infection PHP
[F-P11-028] WARNING: MSI (Mutation Score Indicator)
[F-P11-029] WARNING: Focus on critical code
[F-P11-030] WARNING: Common surviving mutations
[F-P11-031] WARNING: Run in CI on critical paths
[F-P11-032] WARNING: Fix surviving mutations by adding assertions
[F-P11-033] WARNING: Contract tests verify API boundaries
[F-P11-034] WARNING: Test your API's contract
[F-P11-035] WARNING: Version contracts
[F-P11-036] WARNING: Consumer-driven contracts
[F-P11-037] WARNING: Test external API contracts
[F-P11-038] WARNING: Break detection in CI
[F-P11-039] WARNING: Snapshot tests capture expected output
[F-P11-040] WARNING: Review snapshot changes in PRs
[F-P11-041] WARNING: Don't snapshot volatile data
[F-P11-042] WARNING: Snapshot granularity
[F-P11-043] WARNING: Update snapshots deliberately
[F-P11-044] WARNING: Use for regression detection
[F-P11-045] WARNING: Define performance targets
[F-P11-046] WARNING: Use k6, Artillery, or JMeter
[F-P11-047] WARNING: Test with realistic data
[F-P11-048] WARNING: Test under concurrent load
[F-P11-049] WARNING: Identify breaking points
[F-P11-050] WARNING: Profile under load
[F-P11-051] WARNING: Load test in a staging environment
[F-P11-052] WARNING: Monitor database during load tests
[F-P11-053] WARNING: Factories for every model
[F-P11-054] WARNING: Factory states for common scenarios
[F-P11-055] WARNING: Factories create valid data by default
[F-P11-056] WARNING: Use 'Sequence' for varied data
[F-P11-057] WARNING: Don't use 'create()' when 'make()' suffices
[F-P11-058] WARNING: Seeders for development data
[F-P11-059] WARNING: Database cleaner between tests
[F-P11-060] WARNING: Factory relationships
[F-P11-061] WARNING: No time-dependent tests
[F-P11-062] WARNING: No order-dependent tests
[F-P11-063] WARNING: No random data in assertions
[F-P11-064] WARNING: Retry flaky tests in CI (temporarily)
[F-P11-065] WARNING: Isolate external dependencies
[F-P11-066] WARNING: Database isolation
[F-P11-067] WARNING: Deterministic IDs
[F-P11-068] WARNING: Run tests 10x locally
[F-P11-069] WARNING: Set a minimum coverage threshold
[F-P11-070] WARNING: Coverage on critical code is higher
[F-P11-071] WARNING: Don't chase 100%
[F-P11-072] WARNING: Branch coverage over line coverage
[F-P11-073] WARNING: Coverage ratchet
[F-P11-074] WARNING: Exclude generated code
[F-P11-075] WARNING: Visual coverage reports
[F-P11-076] WARNING: Use mutation testing (§190) as a coverage quality check


### P12 — APIs, Queues & Integration (136 checks)

[F-P12-001] WARNING: All external HTTP calls are queued
[F-P12-002] WARNING: Set '$tries', '$maxExceptions', '$timeout'
[F-P12-003] WARNING: Implement 'failed()' method
[F-P12-004] WARNING: Use 'ShouldBeUnique' for non-concurrent jobs
[F-P12-005] WARNING: Use 'ShouldBeEncrypted' for jobs with sensitive data
[F-P12-006] WARNING: Rate limit job dispatching
[F-P12-007] WARNING: Monitor queue depth
[F-P12-008] WARNING: Idempotent job design
[F-P12-009] WARNING: Don't serialize Eloquent models with sensitive data
[F-P12-010] WARNING: Verify webhook signatures before processing
[F-P12-011] WARNING: Use 'hash_equals()' for timing-safe comparison
[F-P12-012] WARNING: Check timestamp freshness
[F-P12-013] WARNING: Idempotency via event ID
[F-P12-014] WARNING: Queue webhook processing
[F-P12-015] WARNING: Return 200 even for ignored events
[F-P12-016] WARNING: Log all webhook attempts
[F-P12-017] WARNING: Allowlist webhook source IPs
[F-P12-018] WARNING: Global scope or middleware-based tenant filtering
[F-P12-019] WARNING: Test cross-tenant isolation
[F-P12-020] WARNING: Audit raw queries for missing tenant filters
[F-P12-021] WARNING: Tenant-specific encryption keys
[F-P12-022] WARNING: Tenant-specific cache prefixes
[F-P12-023] WARNING: Queue jobs carry tenant context
[F-P12-024] WARNING: Scheduled commands run per-tenant
[F-P12-025] WARNING: Validate arguments and options
[F-P12-026] WARNING: Use confirmations for destructive commands
[F-P12-027] WARNING: Return proper exit codes
[F-P12-028] WARNING: Gate dangerous commands behind environment checks
[F-P12-029] WARNING: Never run 'migrate:fresh' or 'db:wipe' in production
[F-P12-030] WARNING: Log all command executions
[F-P12-031] WARNING: Use '--no-interaction' in CI/CD
[F-P12-032] WARNING: No 'dd()' or 'dump()' in commands
[F-P12-033] WARNING: Use '->withoutOverlapping()'
[F-P12-034] WARNING: Use '->onOneServer()'
[F-P12-035] WARNING: Specify timezone explicitly
[F-P12-036] WARNING: Use '->evenInMaintenanceMode()'
[F-P12-037] WARNING: Monitor schedule health
[F-P12-038] WARNING: Use '->onFailure()' for alerting
[F-P12-039] WARNING: Use '->emailOutputOnFailure()'
[F-P12-040] WARNING: Test scheduled commands independently
[F-P12-041] WARNING: Don't store sensitive data in notification payloads
[F-P12-042] WARNING: Set a retention policy
[F-P12-043] WARNING: Index the 'notifiable_id' column
[F-P12-044] WARNING: Queue all mail notifications
[F-P12-045] WARNING: Use Markdown mail templates
[F-P12-046] WARNING: Test mail rendering
[F-P12-047] WARNING: Rate limit SMS notifications
[F-P12-048] WARNING: Never send secrets via SMS
[F-P12-049] WARNING: Handle delivery failures gracefully
[F-P12-050] WARNING: Use the 'via()' method for per-user channel preferences
[F-P12-051] WARNING: Inject dependencies via constructor, not resolved inline
[F-P12-052] WARNING: No 'app()' / 'resolve()' in business logic
[F-P12-053] WARNING: No static facade calls in domain/action classes
[F-P12-054] WARNING: No 'new' for services
[F-P12-055] WARNING: No God constructors
[F-P12-056] WARNING: Inject narrow interfaces, not broad ones
[F-P12-057] WARNING: One implementation per interface (usually)
[F-P12-058] WARNING: Use contextual binding for different implementations
[F-P12-059] WARNING: Use 'Scoped' singletons for request-scoped state
[F-P12-060] WARNING: Register bindings in providers, not scattered across bootstrap
[F-P12-061] WARNING: Set timeouts on all HTTP calls
[F-P12-062] WARNING: Retry with backoff
[F-P12-063] WARNING: Queue external API calls
[F-P12-064] WARNING: Circuit breaker for unreliable APIs
[F-P12-065] WARNING: Check response status
[F-P12-066] WARNING: Don't trust external response data
[F-P12-067] WARNING: Log external API failures
[F-P12-068] WARNING: Never log credentials in requests
[F-P12-069] WARNING: Use connector classes, not raw 'Http::get()'
[F-P12-070] WARNING: Mock external APIs in tests
[F-P12-071] WARNING: Handle rate limits from external APIs
[F-P12-072] WARNING: Use a structured system
[F-P12-073] WARNING: Feature flags are temporary
[F-P12-074] WARNING: Test both flag states
[F-P12-075] WARNING: Default to off for new features
[F-P12-076] WARNING: Gradual rollout with percentage
[F-P12-077] WARNING: Instant kill switch
[F-P12-078] WARNING: Audit flag changes
[F-P12-079] WARNING: Require authentication
[F-P12-080] WARNING: Use TLS for Redis connections in production
[F-P12-081] WARNING: Bind Redis to localhost or private network
[F-P12-082] WARNING: Use separate Redis databases for different purposes
[F-P12-083] WARNING: Set 'maxmemory-policy' on Redis
[F-P12-084] WARNING: Don't store sensitive data in Redis without encryption
[F-P12-085] WARNING: Monitor Redis memory usage
[F-P12-086] WARNING: Use 'SCAN' instead of 'KEYS *'
[F-P12-087] WARNING: 'session.gc_maxlifetime' matches Laravel 'session.lifetime'
[F-P12-088] WARNING: Redis persistence configured
[F-P12-089] WARNING: Validate MIME type server-side
[F-P12-090] WARNING: Strip EXIF data from uploaded images
[F-P12-091] WARNING: Limit image dimensions
[F-P12-092] WARNING: Resize images server-side
[F-P12-093] WARNING: SVG files can contain JavaScript
[F-P12-094] WARNING: Queue image processing
[F-P12-095] WARNING: Serve user-uploaded images from a separate domain
[F-P12-096] WARNING: Sanitize search queries
[F-P12-097] WARNING: Limit search query length
[F-P12-098] WARNING: Rate limit search endpoints
[F-P12-099] WARNING: Use database full-text indexes
[F-P12-100] WARNING: Never expose raw search engine errors to users
[F-P12-101] WARNING: Paginate search results
[F-P12-102] WARNING: Highlight matches safely
[F-P12-103] WARNING: URI versioning is simplest
[F-P12-104] WARNING: Header versioning for cleaner URLs
[F-P12-105] WARNING: Version transformers, not models
[F-P12-106] WARNING: Deprecation notices
[F-P12-107] WARNING: Backward compatibility within a version
[F-P12-108] WARNING: Run both versions' tests
[F-P12-109] WARNING: Maximum 2 active versions
[F-P12-110] WARNING: Document migration guide
[F-P12-111] WARNING: Wrap external service calls in a circuit breaker
[F-P12-112] WARNING: Three states
[F-P12-113] WARNING: Configurable thresholds
[F-P12-114] WARNING: Per-service breakers
[F-P12-115] WARNING: Fallback behavior
[F-P12-116] WARNING: Monitor circuit state
[F-P12-117] WARNING: Cache-backed state for multi-process
[F-P12-118] WARNING: Sign webhook payloads
[F-P12-119] WARNING: Include a unique event ID
[F-P12-120] WARNING: Include a timestamp
[F-P12-121] WARNING: Retry with exponential backoff
[F-P12-122] WARNING: Log delivery attempts
[F-P12-123] WARNING: Disable endpoints after repeated failures
[F-P12-124] WARNING: Async delivery
[F-P12-125] WARNING: Payload size limits
[F-P12-126] WARNING: Verify subscriber URL
[F-P12-127] WARNING: At-least-once delivery
[F-P12-128] WARNING: Dead letter queue (DLQ)
[F-P12-129] WARNING: Job batching for related operations
[F-P12-130] WARNING: Unique jobs
[F-P12-131] WARNING: Job chaining for sequential processing
[F-P12-132] WARNING: Timeout protection
[F-P12-133] WARNING: Monitor queue depth
[F-P12-134] WARNING: Separate queues by priority
[F-P12-135] WARNING: Graceful shutdown
[F-P12-136] WARNING: Horizon for monitoring


### P13 — Logging, Monitoring & Audit (30 checks)

[F-P13-001] WARNING: Log security events
[F-P13-002] WARNING: Log business events
[F-P13-003] WARNING: Structured logging
[F-P13-004] WARNING: Never log sensitive data
[F-P13-005] WARNING: Use appropriate log levels
[F-P13-006] WARNING: Use 'spatie/laravel-activitylog' or equivalent
[F-P13-007] WARNING: Every model has 'LogsActivity' trait
[F-P13-008] WARNING: Record who, what, when, where
[F-P13-009] WARNING: Audit logs are immutable
[F-P13-010] WARNING: Exception tracking in production
[F-P13-011] WARNING: Queue monitoring
[F-P13-012] WARNING: Slow query logging
[F-P13-013] WARNING: Read the package source before installing
[F-P13-014] WARNING: Check maintenance status
[F-P13-015] WARNING: Check license compatibility
[F-P13-016] WARNING: Review package permissions
[F-P13-017] WARNING: Pin to stable versions
[F-P13-018] WARNING: Prefer Laravel-ecosystem packages
[F-P13-019] WARNING: Remove unused packages
[F-P13-020] WARNING: Review 'composer.json' scripts
[F-P13-021] WARNING: Every create, update, delete logged
[F-P13-022] WARNING: Log the before/after values
[F-P13-023] WARNING: Log the actor
[F-P13-024] WARNING: Log the IP and user agent
[F-P13-025] WARNING: Audit logs are append-only
[F-P13-026] WARNING: Login/logout events logged
[F-P13-027] WARNING: Permission changes logged
[F-P13-028] WARNING: Export events logged
[F-P13-029] WARNING: Audit log retention policy
[F-P13-030] WARNING: Searchable audit trail


### P14 — Infrastructure & Operations (208 checks)

[F-P14-001] WARNING: 'APP_DEBUG=false' in production
[F-P14-002] WARNING: 'APP_ENV=production' in production
[F-P14-003] WARNING: Never commit '.env' to version control
[F-P14-004] WARNING: Use 'php artisan env:encrypt' for deployment
[F-P14-005] WARNING: Never use 'env()' outside 'config/' files
[F-P14-006] WARNING: Cache config in production
[F-P14-007] WARNING: Cache routes in production
[F-P14-008] WARNING: Rotate 'APP_KEY' periodically
[F-P14-009] WARNING: Use 'APP_PREVIOUS_KEYS' during rotation
[F-P14-010] WARNING: Re-encrypt database columns after key rotation
[F-P14-011] WARNING: No secrets in code, config files, or version control
[F-P14-012] WARNING: Consider external secret managers for production
[F-P14-013] WARNING: Audit '.env.example' for real values
[F-P14-014] WARNING: Unused imports
[F-P14-015] WARNING: Unused model scopes
[F-P14-016] WARNING: Unused enum cases
[F-P14-017] WARNING: Unused Vue components
[F-P14-018] WARNING: Unused composables
[F-P14-019] WARNING: Orphaned routes
[F-P14-020] WARNING: Unreachable controller methods
[F-P14-021] WARNING: Dead config keys
[F-P14-022] WARNING: Unused middleware
[F-P14-023] WARNING: Duplicate enums
[F-P14-024] WARNING: Commented-out code
[F-P14-025] WARNING: TODO/FIXME comments older than 30 days
[F-P14-026] WARNING: PHPStan level 8+
[F-P14-027] WARNING: Laravel Pint
[F-P14-028] WARNING: ESLint / 'vue-tsc'
[F-P14-029] WARNING: 'composer audit'
[F-P14-030] WARNING: 'npm audit'
[F-P14-031] WARNING: Run 'composer audit' regularly
[F-P14-032] WARNING: Run 'npm audit' regularly
[F-P14-033] WARNING: Commit lock files
[F-P14-034] WARNING: Review dependency updates
[F-P14-035] WARNING: Minimize dependencies
[F-P14-036] WARNING: Pin major versions
[F-P14-037] WARNING: Verify package integrity
[F-P14-038] WARNING: No typosquatting
[F-P14-039] WARNING: 'vendor/bin/pint --dirty'
[F-P14-040] WARNING: 'vendor/bin/phpstan analyse'
[F-P14-041] WARNING: 'php artisan test --compact'
[F-P14-042] WARNING: 'npm run build'
[F-P14-043] WARNING: 'composer audit'
[F-P14-044] WARNING: 'npm audit'
[F-P14-045] WARNING: No hardcoded secrets, API keys, or credentials
[F-P14-046] WARNING: Authorization on every new controller method
[F-P14-047] WARNING: CSRF protection on every state-changing route
[F-P14-048] WARNING: Input validation via Form Request
[F-P14-049] WARNING: No raw SQL with user input
[F-P14-050] WARNING: No 'v-html' / '{!! !!}' with user data
[F-P14-051] WARNING: No '$guarded = []' or missing '$fillable'
[F-P14-052] WARNING: Sensitive fields not in '$fillable'
[F-P14-053] WARNING: Multi-model writes wrapped in DB transactions
[F-P14-054] WARNING: 'lockForUpdate()' for status-check-then-update patterns
[F-P14-055] WARNING: No float/bcmath arithmetic on money
[F-P14-056] WARNING: Idempotency for retryable operations
[F-P14-057] WARNING: State machine transitions via 'transitionTo()', not raw updates
[F-P14-058] WARNING: 'declare(strict_types=1)' on new PHP files
[F-P14-059] WARNING: Explicit return types on all new methods
[F-P14-060] WARNING: Enum references, not hardcoded strings
[F-P14-061] WARNING: Early returns / guard clauses (no deep nesting)
[F-P14-062] WARNING: Modern PHP syntax (match, enums, readonly, named args)
[F-P14-063] WARNING: Factory-based test setup
[F-P14-064] WARNING: Feature test for every new action
[F-P14-065] WARNING: No N+1 queries (eager loading where needed)
[F-P14-066] WARNING: '$fillable' declared (no privilege escalation fields)
[F-P14-067] WARNING: 'SoftDeletes' trait
[F-P14-068] WARNING: 'LogsActivity' trait with 'getActivitylogOptions()'
[F-P14-069] WARNING: 'casts()' method with appropriate casts
[F-P14-070] WARNING: Factory created (with useful states)
[F-P14-071] WARNING: '$hidden' on sensitive attributes
[F-P14-072] WARNING: 'allowedTransitions()' if used as state machine
[F-P14-073] WARNING: 'isTerminal()' method
[F-P14-074] WARNING: 'label()' / 'color()' for UI display
[F-P14-075] WARNING: TypeScript mirror if used in frontend
[F-P14-076] WARNING: Registered in seeders if permission/role enum
[F-P14-077] WARNING: Audit log handler in reactor
[F-P14-078] WARNING: Metadata attached (via aggregate or 'EmitsStoredEvents')
[F-P14-079] WARNING: Data carried in event (not fetched from DB during replay)
[F-P14-080] WARNING: Auth middleware applied
[F-P14-081] WARNING: 2FA middleware on destructive operations
[F-P14-082] WARNING: Rate limiting where appropriate
[F-P14-083] WARNING: Named route for Wayfinder / 'route()' usage
[F-P14-084] WARNING: Never load unbounded result sets into memory
[F-P14-085] WARNING: Paginate all index/list endpoints
[F-P14-086] WARNING: Limit array sizes in validation
[F-P14-087] WARNING: Limit string lengths
[F-P14-088] WARNING: Limit file upload sizes
[F-P14-089] WARNING: Enable 'preventLazyLoading()' in development
[F-P14-090] WARNING: Use 'preventSilentlyDiscardingAttributes()' in development
[F-P14-091] WARNING: Use 'select()' on queries
[F-P14-092] WARNING: Use 'exists()' not 'count() > 0'
[F-P14-093] WARNING: Use 'value()' for single scalar values
[F-P14-094] WARNING: Use 'pluck()' for single-column lists
[F-P14-095] WARNING: Use database aggregation
[F-P14-096] WARNING: Index WHERE and ORDER BY columns
[F-P14-097] WARNING: Disable query log in queue workers
[F-P14-098] WARNING: Flush event listeners in long loops
[F-P14-099] WARNING: Call 'gc_collect_cycles()' in batch processing
[F-P14-100] WARNING: 'APP_DEBUG=false'
[F-P14-101] WARNING: 'APP_ENV=production'
[F-P14-102] WARNING: Cache everything
[F-P14-103] WARNING: Run 'composer install --no-dev'
[F-P14-104] WARNING: Run 'npm run build'
[F-P14-105] WARNING: Web server points to '/public' directory
[F-P14-106] WARNING: '.env' not accessible via HTTP
[F-P14-107] WARNING: 'storage/' not accessible via HTTP
[F-P14-108] WARNING: PHP 'expose_php = Off'
[F-P14-109] WARNING: PHP 'display_errors = Off'
[F-P14-110] WARNING: File permissions: dirs 755, files 644
[F-P14-111] WARNING: Separate DB user for application
[F-P14-112] WARNING: No 'DROP', 'ALTER', 'CREATE' permissions for app user
[F-P14-113] WARNING: Enable slow query log
[F-P14-114] WARNING: Regular backups
[F-P14-115] WARNING: Exception tracker in production
[F-P14-116] WARNING: Uptime monitoring
[F-P14-117] WARNING: Queue monitoring
[F-P14-118] WARNING: Disk space monitoring
[F-P14-119] WARNING: '.gitignore' includes '.env', 'storage/', 'vendor/', 'node_modules/'
[F-P14-120] WARNING: Pre-commit hooks scan for secrets
[F-P14-121] WARNING: Never commit API keys, passwords, or tokens
[F-P14-122] WARNING: If a secret is committed, rotate it immediately
[F-P14-123] WARNING: Protect main/master branch
[F-P14-124] WARNING: No '--force' push to shared branches
[F-P14-125] WARNING: No '--no-verify' commits
[F-P14-126] WARNING: Atomic commits
[F-P14-127] WARNING: Descriptive commit messages
[F-P14-128] WARNING: No generated files in commits
[F-P14-129] WARNING: Laravel Debugbar disabled in production
[F-P14-130] WARNING: Laravel Telescope restricted in production
[F-P14-131] WARNING: Horizon dashboard gated
[F-P14-132] WARNING: No 'dd()', 'dump()', 'var_dump()', 'print_r()' left in code
[F-P14-133] WARNING: No 'ray()' calls left in code
[F-P14-134] WARNING: No 'Log::debug()' with sensitive data
[F-P14-135] WARNING: '/phpinfo' route doesn't exist
[F-P14-136] WARNING: Custom error pages for 404, 403, 500, 503
[F-P14-137] WARNING: Error responses don't include exception class names
[F-P14-138] WARNING: Automated daily backups
[F-P14-139] WARNING: Backup includes database AND files
[F-P14-140] WARNING: Offsite backup storage
[F-P14-141] WARNING: Backup encryption
[F-P14-142] WARNING: Backup retention policy
[F-P14-143] WARNING: Test restore procedure quarterly
[F-P14-144] WARNING: Document recovery steps
[F-P14-145] WARNING: Recovery Time Objective (RTO) defined
[F-P14-146] WARNING: Recovery Point Objective (RPO) defined
[F-P14-147] WARNING: Alert on backup failure
[F-P14-148] WARNING: Monitor backup size trends
[F-P14-149] WARNING: Alert on backup age
[F-P14-150] WARNING: Development matches production stack versions
[F-P14-151] WARNING: Same database engine in tests
[F-P14-152] WARNING: Same queue driver in staging
[F-P14-153] WARNING: Same cache driver in staging
[F-P14-154] WARNING: Same session driver in staging
[F-P14-155] WARNING: PHP strict mode matches
[F-P14-156] WARNING: Run as non-root user
[F-P14-157] WARNING: Minimal base images
[F-P14-158] WARNING: No secrets in Dockerfiles or build args
[F-P14-159] WARNING: Pin image versions
[F-P14-160] WARNING: Read-only filesystem where possible
[F-P14-161] WARNING: Scan images for vulnerabilities
[F-P14-162] WARNING: Don't install dev dependencies in production images
[F-P14-163] WARNING: Sanitize HTML before PDF rendering
[F-P14-164] WARNING: Disable JavaScript in PDF engines
[F-P14-165] WARNING: Disable network access in PDF engines
[F-P14-166] WARNING: Limit PDF page count
[F-P14-167] WARNING: Queue PDF generation
[F-P14-168] WARNING: Serve PDFs as downloads
[F-P14-169] WARNING: Use 'sys_get_temp_dir()' or Laravel's 'storage_path('temp/')'
[F-P14-170] WARNING: Delete temp files after use
[F-P14-171] WARNING: Unique temp file names
[F-P14-172] WARNING: Don't serve temp directories via HTTP
[F-P14-173] WARNING: Set restrictive permissions
[F-P14-174] WARNING: Schedule temp cleanup
[F-P14-175] WARNING: Use Laravel Process facade over raw 'exec()'/'shell_exec()'
[F-P14-176] WARNING: Set timeout on all external processes
[F-P14-177] WARNING: Check exit codes
[F-P14-178] WARNING: Capture stderr separately
[F-P14-179] WARNING: Never pass user input to process arguments without escaping
[F-P14-180] WARNING: Log process executions
[F-P14-181] WARNING: Lint
[F-P14-182] WARNING: Static analysis
[F-P14-183] WARNING: Unit & feature tests
[F-P14-184] WARNING: Build
[F-P14-185] WARNING: Security audit
[F-P14-186] WARNING: Migration check
[F-P14-187] WARNING: Fail fast
[F-P14-188] WARNING: Parallel test execution
[F-P14-189] WARNING: Cache dependencies
[F-P14-190] WARNING: No secrets in CI logs
[F-P14-191] WARNING: Branch protection
[F-P14-192] WARNING: No deploy from local
[F-P14-193] WARNING: Rollback plan
[F-P14-194] WARNING: Incident documented
[F-P14-195] WARNING: Root cause identified
[F-P14-196] WARNING: Fix deployed
[F-P14-197] WARNING: Affected data assessed
[F-P14-198] WARNING: Users notified
[F-P14-199] WARNING: Monitoring added
[F-P14-200] WARNING: Test added
[F-P14-201] WARNING: Checklist updated
[F-P14-202] WARNING: Related code audited
[F-P14-203] WARNING: Runbook updated
[F-P14-204] WARNING: Timeline reconstructed
[F-P14-205] WARNING: Detection time measured
[F-P14-206] WARNING: Response time measured
[F-P14-207] WARNING: Blame-free retrospective held
[F-P14-208] WARNING: Action items assigned and tracked

</fortress-rules>
