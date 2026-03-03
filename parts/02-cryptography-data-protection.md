[← Previous Part](01-application-security.md) | [Full Checklist](../checklist.md) | [Next Part →](03-authentication-authorization.md)

# Part II — Cryptography & Data Protection

**12 sections · 109 checks**

- [2. Hashing, Encryption & Cryptography](#2-hashing-encryption-cryptography)
- [83. Password & Credential Management](#83-password-credential-management)
- [84. Two-Factor Authentication (2FA) Depth](#84-two-factor-authentication-2fa-depth)
- [85. Logging Sensitive Data Prevention](#85-logging-sensitive-data-prevention)
- [111. Key Management & Rotation](#111-key-management-rotation)
- [112. TLS & Certificate Management](#112-tls-certificate-management)
- [113. Data Classification & Handling Tiers](#113-data-classification-handling-tiers)
- [114. PII Detection & Anonymization](#114-pii-detection-anonymization)
- [115. Tokenization & Data Masking](#115-tokenization-data-masking)
- [116. At-Rest Encryption Strategies](#116-at-rest-encryption-strategies)
- [117. Secrets Management (Vault / Cloud)](#117-secrets-management-vault-cloud)
- [118. Cryptographic Agility](#118-cryptographic-agility)

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


---

[← Previous Part](01-application-security.md) | [Full Checklist](../checklist.md) | [Next Part →](03-authentication-authorization.md)
