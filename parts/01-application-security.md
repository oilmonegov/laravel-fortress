[Full Checklist](../checklist.md) | [Next Part →](02-cryptography-data-protection.md)

# Part I — Application Security

**22 sections · 179 checks**

- [1. Security — OWASP & Laravel-Specific](#1-security-owasp-laravel-specific)
- [19. API Security](#19-api-security)
- [20. Session & Cookie Security](#20-session-cookie-security)
- [21. File Upload & Storage Security](#21-file-upload-storage-security)
- [27. Route & Middleware Security](#27-route-middleware-security)
- [28. Serialization & Object Injection](#28-serialization-object-injection)
- [29. Email Security](#29-email-security)
- [33. Blade, View & Component Security](#33-blade-view-component-security)
- [39. Open Redirect & IDOR Prevention](#39-open-redirect-idor-prevention)
- [44. CSV & Spreadsheet Export Injection](#44-csv-spreadsheet-export-injection)
- [49. Broadcasting & WebSocket Security](#49-broadcasting-websocket-security)
- [52. DNS Rebinding & Host Header Attacks](#52-dns-rebinding-host-header-attacks)
- [101. Path Traversal & Directory Escape](#101-path-traversal-directory-escape)
- [102. XML External Entity (XXE) Prevention](#102-xml-external-entity-xxe-prevention)
- [103. HTTP Request Smuggling](#103-http-request-smuggling)
- [104. Clickjacking & UI Redress](#104-clickjacking-ui-redress)
- [105. Subdomain Takeover Prevention](#105-subdomain-takeover-prevention)
- [106. Server-Side Request Forgery (SSRF) Deep Dive](#106-server-side-request-forgery-ssrf-deep-dive)
- [107. Content Security Policy (CSP) Engineering](#107-content-security-policy-csp-engineering)
- [108. HTTP Security Headers Checklist](#108-http-security-headers-checklist)
- [109. CORS Misconfiguration](#109-cors-misconfiguration)
- [110. Supply Chain Attack Vectors (Frontend)](#110-supply-chain-attack-vectors-frontend)

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


---

[Full Checklist](../checklist.md) | [Next Part →](02-cryptography-data-protection.md)
