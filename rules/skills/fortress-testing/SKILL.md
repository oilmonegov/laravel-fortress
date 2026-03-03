---
description: "Laravel Fortress Part 11 — Testing & Quality Assurance. 9 sections, 76 checks covering Pest/PHPUnit, mocks, mutation testing, coverage, factories, flaky tests."
---

# Fortress: Testing & Quality Assurance

> Part XI of The Laravel Fortress — 9 sections · 76 checks
> https://github.com/chuxolab/laravel-fortress/blob/main/parts/11-testing-quality-assurance.md

When writing or reviewing code, enforce these rules.
Check `.fortress.yml` in the project root for overrides.

## Project Awareness

Before enforcing rules, read `composer.json` and `package.json` to detect the project's PHP version,
Laravel version, and installed packages. Skip rules whose conditions don't match the detected stack.

## Rules

### Structure

[F-P11-001] **WARNING** — Feature tests for every action/controller
[F-P11-002] **WARNING** — Unit tests for value objects, DTOs, and pure functions
[F-P11-003] **WARNING** — Use factories, not manual model creation
[F-P11-004] **WARNING** — Use `it()` syntax (Pest)

### Assertions

[F-P11-005] **WARNING** — Assert specific outcomes, not just "no errors"
[F-P11-006] **WARNING** — Assert side effects
[F-P11-007] **WARNING** — Test validation rules
[F-P11-008] **WARNING** — Test authorization

### Edge Cases

[F-P11-009] **WARNING** — Test concurrent access
[F-P11-010] **WARNING** — Test boundary values
[F-P11-011] **WARNING** — Test state machine transitions
[F-P11-012] **WARNING** — Test SoD enforcement
[F-P11-013] **WARNING** — Test period lock enforcement
[F-P11-014] **WARNING** — Test idempotency

### Anti-Patterns in Tests

[F-P11-015] **WARNING** — No `sleep()` in tests
[F-P11-016] **WARNING** — No hardcoded IDs
[F-P11-017] **WARNING** — Resolve injected actions from container
[F-P11-018] **WARNING** — Clean state per test

### Test Doubles: Mocks, Stubs, Fakes, Spies

[F-P11-019] **WARNING** — Know the terminology
[F-P11-020] **WARNING** — Prefer fakes over mocks
[F-P11-021] **WARNING** — Don't mock what you don't own
[F-P11-022] **WARNING** — Laravel's built-in fakes
[F-P11-023] **WARNING** — Assert specific interactions, not all interactions
[F-P11-024] **WARNING** — Spies are for "did this happen?" assertions
[F-P11-025] **WARNING** — Clean up mocks

### Mutation Testing

[F-P11-026] **WARNING** — Mutation testing finds weak tests
[F-P11-027] **WARNING** — Use Infection PHP
[F-P11-028] **WARNING** — MSI (Mutation Score Indicator)
[F-P11-029] **WARNING** — Focus on critical code
[F-P11-030] **WARNING** — Common surviving mutations
[F-P11-031] **WARNING** — Run in CI on critical paths
[F-P11-032] **WARNING** — Fix surviving mutations by adding assertions

### Contract Testing

[F-P11-033] **WARNING** — Contract tests verify API boundaries
[F-P11-034] **WARNING** — Test your API's contract
[F-P11-035] **WARNING** — Version contracts
[F-P11-036] **WARNING** — Consumer-driven contracts
[F-P11-037] **WARNING** — Test external API contracts
[F-P11-038] **WARNING** — Break detection in CI

### Snapshot Testing

[F-P11-039] **WARNING** — Snapshot tests capture expected output
[F-P11-040] **WARNING** — Review snapshot changes in PRs
[F-P11-041] **WARNING** — Don't snapshot volatile data
[F-P11-042] **WARNING** — Snapshot granularity
[F-P11-043] **WARNING** — Update snapshots deliberately
[F-P11-044] **WARNING** — Use for regression detection

### Load & Stress Testing

[F-P11-045] **WARNING** — Define performance targets
[F-P11-046] **WARNING** — Use k6, Artillery, or JMeter
[F-P11-047] **WARNING** — Test with realistic data
[F-P11-048] **WARNING** — Test under concurrent load
[F-P11-049] **WARNING** — Identify breaking points
[F-P11-050] **WARNING** — Profile under load
[F-P11-051] **WARNING** — Load test in a staging environment
[F-P11-052] **WARNING** — Monitor database during load tests

### Test Data Management & Factories

[F-P11-053] **WARNING** — Factories for every model
[F-P11-054] **WARNING** — Factory states for common scenarios
[F-P11-055] **WARNING** — Factories create valid data by default
[F-P11-056] **WARNING** — Use `Sequence` for varied data
[F-P11-057] **WARNING** — Don't use `create()` when `make()` suffices
[F-P11-058] **WARNING** — Seeders for development data
[F-P11-059] **WARNING** — Database cleaner between tests
[F-P11-060] **WARNING** — Factory relationships

### Flaky Test Prevention

[F-P11-061] **WARNING** — No time-dependent tests
[F-P11-062] **WARNING** — No order-dependent tests
[F-P11-063] **WARNING** — No random data in assertions
[F-P11-064] **WARNING** — Retry flaky tests in CI (temporarily)
[F-P11-065] **WARNING** — Isolate external dependencies
[F-P11-066] **WARNING** — Database isolation
[F-P11-067] **WARNING** — Deterministic IDs
[F-P11-068] **WARNING** — Run tests 10x locally

### Code Coverage Strategy

[F-P11-069] **WARNING** — Set a minimum coverage threshold
[F-P11-070] **WARNING** — Coverage on critical code is higher
[F-P11-071] **WARNING** — Don't chase 100%
[F-P11-072] **WARNING** — Branch coverage over line coverage
[F-P11-073] **WARNING** — Coverage ratchet
[F-P11-074] **WARNING** — Exclude generated code
[F-P11-075] **WARNING** — Visual coverage reports
[F-P11-076] **WARNING** — Use mutation testing (§190) as a coverage quality check
