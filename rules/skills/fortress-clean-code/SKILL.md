---
description: "Laravel Fortress Part 7 — Clean Code & Software Design. 16 sections, 128 checks covering SOLID, patterns (CQRS, Pipeline, Strategy, Builder), value objects, immutability."
---

# Fortress: Clean Code & Software Design

> Part VII of The Laravel Fortress — 16 sections · 128 checks
> https://github.com/oilmonegov/laravel-fortress/blob/main/parts/07-clean-code-software-design.md

When writing or reviewing code, enforce these rules.
Check `.fortress.yml` in the project root for overrides.

## Project Awareness

Before enforcing rules, read `composer.json` and `package.json` to detect the project's PHP version,
Laravel version, and installed packages. Skip rules whose conditions don't match the detected stack.

## Rules

### Single Responsibility

[F-P07-001] **WARNING** — Controllers only dispatch to actions/services
[F-P07-002] **WARNING** — One action class per business operation
[F-P07-003] **WARNING** — Query objects for complex reads

### Early Returns & Guard Clauses

[F-P07-004] **WARNING** — Validate preconditions at the top, return/throw immediately

### Value Objects & DTOs

[F-P07-005] **WARNING** — Use value objects for domain concepts
[F-P07-006] **WARNING** — Use typed DTOs for data transfer
[F-P07-007] **WARNING** — Value objects are immutable
[F-P07-008] **WARNING** — Value objects validate on construction

### Dependency Injection

[F-P07-009] **WARNING** — Inject interfaces, not concrete classes
[F-P07-010] **WARNING** — Constructor injection over method injection
[F-P07-011] **WARNING** — Never call `app()` in business logic
[F-P07-012] **WARNING** — No static facades in domain logic

### Naming

[F-P07-013] **WARNING** — Classes: PascalCase, descriptive nouns
[F-P07-014] **WARNING** — Methods: camelCase, verb-first
[F-P07-015] **WARNING** — Variables: camelCase, descriptive
[F-P07-016] **WARNING** — Boolean methods: `is`, `has`, `can`, `should` prefixes
[F-P07-017] **WARNING** — Constants and enum cases: PascalCase
[F-P07-018] **WARNING** — No abbreviations

### Class Organization

[F-P07-019] **WARNING** — Consistent element order in classes
[F-P07-020] **WARNING** — Resource controller method order
[F-P07-021] **WARNING** — Model method order

### No Premature Abstraction

[F-P07-022] **WARNING** — Don't abstract for one use case
[F-P07-023] **WARNING** — Don't create helpers/utilities for single-use operations
[F-P07-024] **WARNING** — Don't add configurability that isn't needed

### Method Length

[F-P07-025] **WARNING** — Methods under 20 lines (soft limit)
[F-P07-026] **WARNING** — One level of abstraction per method

### Nesting Depth

[F-P07-027] **WARNING** — Maximum 2 levels of nesting

### Boolean Parameters

[F-P07-028] **WARNING** — No boolean parameters

### Comments

[F-P07-029] **WARNING** — Code should be self-documenting
[F-P07-030] **WARNING** — Comments explain WHY, not WHAT
[F-P07-031] **WARNING** — PHPDoc for complex signatures
[F-P07-032] **WARNING** — No commented-out code
[F-P07-033] **WARNING** — No TODO without a ticket

### Naming Precision

[F-P07-034] **WARNING** — Avoid generic names
[F-P07-035] **WARNING** — Boolean variables read as assertions
[F-P07-036] **WARNING** — Collection variables are plural
[F-P07-037] **WARNING** — Methods that return booleans start with `is`, `has`, `can`, `should`
[F-P07-038] **WARNING** — Methods that transform data describe the output

### Interface & Contract Design

[F-P07-039] **WARNING** — Interfaces for all external boundaries
[F-P07-040] **WARNING** — Interfaces in `app/Contracts/`
[F-P07-041] **WARNING** — One method per interface (where practical)
[F-P07-042] **WARNING** — Return types on interface methods
[F-P07-043] **WARNING** — Fake implementations for testing
[F-P07-044] **WARNING** — Bind in ServiceProvider
[F-P07-045] **WARNING** — Document interface contracts

### Trait Hygiene

[F-P07-046] **WARNING** — Traits are horizontal reuse, not inheritance substitutes
[F-P07-047] **WARNING** — Traits declare their dependencies
[F-P07-048] **WARNING** — No property conflicts
[F-P07-049] **WARNING** — Small, focused traits
[F-P07-050] **WARNING** — No business logic in traits
[F-P07-051] **WARNING** — Document trait requirements
[F-P07-052] **WARNING** — Test traits independently

### Domain Event Design

[F-P07-053] **WARNING** — Events are past tense
[F-P07-054] **WARNING** — Events are immutable
[F-P07-055] **WARNING** — Events carry all necessary data
[F-P07-056] **WARNING** — Events are serializable
[F-P07-057] **WARNING** — One event per state change
[F-P07-058] **WARNING** — Event names are domain-specific
[F-P07-059] **WARNING** — Metadata separate from payload

### Value Object Contract Enforcement

[F-P07-060] **WARNING** — All VOs implement a common interface
[F-P07-061] **WARNING** — Validate on construction
[F-P07-062] **WARNING** — Immutable
[F-P07-063] **WARNING** — No identity
[F-P07-064] **WARNING** — `__toString()` for serialization
[F-P07-065] **WARNING** — Architecture test enforcement
[F-P07-066] **WARNING** — Factory methods for common creation patterns

### CQRS Pattern Implementation

[F-P07-067] **WARNING** — Separate read and write models
[F-P07-068] **WARNING** — Commands don't return data
[F-P07-069] **WARNING** — Queries don't change state
[F-P07-070] **WARNING** — Read models are denormalized
[F-P07-071] **WARNING** — Separate database connections for reads
[F-P07-072] **WARNING** — Eventually consistent reads are acceptable
[F-P07-073] **WARNING** — Don't over-apply

### Repository Pattern (When Appropriate)

[F-P07-074] **WARNING** — Eloquent IS the repository
[F-P07-075] **WARNING** — Use repositories when you need to swap implementations
[F-P07-076] **WARNING** — Use Query objects instead
[F-P07-077] **WARNING** — Never wrap Eloquent just for testability
[F-P07-078] **WARNING** — If you use repositories, they return domain objects

### Bounded Context & Module Boundaries

[F-P07-079] **WARNING** — Each module owns its models, actions, and routes
[F-P07-080] **WARNING** — Cross-context communication via events or service interfaces
[F-P07-081] **WARNING** — Shared kernel is minimal
[F-P07-082] **WARNING** — No cross-context database JOINs
[F-P07-083] **WARNING** — Context map documents relationships
[F-P07-084] **WARNING** — Naming reflects the context
[F-P07-085] **WARNING** — Avoid monolithic route files

### Pipeline Pattern

[F-P07-086] **WARNING** — Use Laravel Pipelines for sequential processing
[F-P07-087] **WARNING** — Each pipe has a single responsibility
[F-P07-088] **WARNING** — Pipes are reusable
[F-P07-089] **WARNING** — Pipes throw exceptions on failure
[F-P07-090] **WARNING** — Order matters
[F-P07-091] **WARNING** — Test each pipe in isolation
[F-P07-092] **WARNING** — Don't nest pipelines

### Specification Pattern for Business Rules

[F-P07-093] **WARNING** — Encapsulate complex business predicates
[F-P07-094] **WARNING** — Compose specifications
[F-P07-095] **WARNING** — Use for dynamic filtering
[F-P07-096] **WARNING** — Specifications are testable
[F-P07-097] **WARNING** — Document the business rule
[F-P07-098] **WARNING** — Don't over-engineer for simple boolean checks

### Null Object Pattern

[F-P07-099] **WARNING** — Replace null checks with a Null Object
[F-P07-100] **WARNING** — Use for optional dependencies
[F-P07-101] **WARNING** — Null Objects are singletons
[F-P07-102] **WARNING** — Don't use when null has meaning
[F-P07-103] **WARNING** — Named constructors clarify intent

### Strategy Pattern for Variant Logic

[F-P07-104] **WARNING** — Replace conditionals with strategies
[F-P07-105] **WARNING** — Inject strategies, don't construct inline
[F-P07-106] **WARNING** — Strategies are interchangeable at runtime
[F-P07-107] **WARNING** — Each strategy is independently testable
[F-P07-108] **WARNING** — Prefer over `match`/`switch` when branches have complex logic

### Builder Pattern for Complex Construction

[F-P07-109] **WARNING** — Use builders for objects with many optional parameters
[F-P07-110] **WARNING** — Builders enforce required parameters
[F-P07-111] **WARNING** — Immutable builders
[F-P07-112] **WARNING** — Use for query construction
[F-P07-113] **WARNING** — Use for notification construction
[F-P07-114] **WARNING** — Don't use when a constructor is clear

### Immutability as a Default

[F-P07-115] **WARNING** — Default to immutable, opt into mutability
[F-P07-116] **WARNING** — Value Objects are always immutable
[F-P07-117] **WARNING** — Events are always immutable
[F-P07-118] **WARNING** — Use `CarbonImmutable` over `Carbon`
[F-P07-119] **WARNING** — Collections: `->toImmutable()` for shared data
[F-P07-120] **WARNING** — Immutable DTOs for cross-boundary data
[F-P07-121] **WARNING** — Mutation returns new instances
[F-P07-122] **WARNING** — Mutable state is explicitly scoped

### Domain Primitives & Micro-Types

[F-P07-123] **WARNING** — Wrap primitive values in domain-specific types
[F-P07-124] **WARNING** — Self-validating on construction
[F-P07-125] **WARNING** — Type-safe function signatures
[F-P07-126] **WARNING** — Equality by value
[F-P07-127] **WARNING** — Use for: account codes, currency codes, reference numbers, BVN, phone numbers
[F-P07-128] **WARNING** — Don't micro-type everything
