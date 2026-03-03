[← Previous Part](12-apis-queues-integration.md) | [Full Checklist](../checklist.md) | [Next Part →](14-infrastructure-operations.md)

# Part XIII — Logging, Monitoring & Audit

**3 sections · 30 checks**

- [16. Logging, Monitoring & Audit Trails](#16-logging-monitoring-audit-trails)
- [86. Third-Party Package Audit](#86-third-party-package-audit)
- [93. Audit Trail Completeness](#93-audit-trail-completeness)

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


---

[← Previous Part](12-apis-queues-integration.md) | [Full Checklist](../checklist.md) | [Next Part →](14-infrastructure-operations.md)
