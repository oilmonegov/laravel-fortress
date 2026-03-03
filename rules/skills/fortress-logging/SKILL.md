---
description: "Laravel Fortress Part 13 — Logging, Monitoring & Audit. 3 sections, 30 checks covering Logging, audit trails, third-party package audits."
---

# Fortress: Logging, Monitoring & Audit

> Part XIII of The Laravel Fortress — 3 sections · 30 checks
> https://github.com/oilmonegov/laravel-fortress/blob/main/parts/13-logging-monitoring-audit.md

When writing or reviewing code, enforce these rules.
Check `.fortress.yml` in the project root for overrides.

## Project Awareness

Before enforcing rules, read `composer.json` and `package.json` to detect the project's PHP version,
Laravel version, and installed packages. Skip rules whose conditions don't match the detected stack.

## Rules

### Application Logging

[F-P13-001] **WARNING** — Log security events
[F-P13-002] **WARNING** — Log business events
[F-P13-003] **WARNING** — Structured logging
[F-P13-004] **WARNING** — Never log sensitive data
[F-P13-005] **WARNING** — Use appropriate log levels

### Audit Trails

[F-P13-006] **WARNING** — Use `spatie/laravel-activitylog` or equivalent
[F-P13-007] **WARNING** — Every model has `LogsActivity` trait
[F-P13-008] **WARNING** — Record who, what, when, where
[F-P13-009] **WARNING** — Audit logs are immutable

### Monitoring

[F-P13-010] **WARNING** — Exception tracking in production
[F-P13-011] **WARNING** — Queue monitoring
[F-P13-012] **WARNING** — Slow query logging

### Third-Party Package Audit

[F-P13-013] **WARNING** — Read the package source before installing
[F-P13-014] **WARNING** — Check maintenance status
[F-P13-015] **WARNING** — Check license compatibility
[F-P13-016] **WARNING** — Review package permissions
[F-P13-017] **WARNING** — Pin to stable versions
[F-P13-018] **WARNING** — Prefer Laravel-ecosystem packages
[F-P13-019] **WARNING** — Remove unused packages
[F-P13-020] **WARNING** — Review `composer.json` scripts

### Audit Trail Completeness

[F-P13-021] **WARNING** — Every create, update, delete logged
[F-P13-022] **WARNING** — Log the before/after values
[F-P13-023] **WARNING** — Log the actor
[F-P13-024] **WARNING** — Log the IP and user agent
[F-P13-025] **WARNING** — Audit logs are append-only
[F-P13-026] **WARNING** — Login/logout events logged
[F-P13-027] **WARNING** — Permission changes logged
[F-P13-028] **WARNING** — Export events logged
[F-P13-029] **WARNING** — Audit log retention policy
[F-P13-030] **WARNING** — Searchable audit trail
