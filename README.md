# The Laravel Fortress

[![License: MIT](https://img.shields.io/badge/License-MIT-blue.svg)](LICENSE)
[![PRs Welcome](https://img.shields.io/badge/PRs-welcome-brightgreen.svg)](CONTRIBUTING.md)
[![Checks](https://img.shields.io/badge/checks-1%2C755-orange.svg)](#the-14-parts)

**1,755 checks. 200 sections. 14 parts. One standard.**

A comprehensive engineering standards checklist for building Laravel applications that are secure by default, correct under concurrency, auditable end-to-end, and maintainable at scale.

---

## Why This Exists

Every Laravel project accumulates hard-won lessons — security holes patched at 2 AM, race conditions discovered in production, floating-point money bugs that silently corrupt ledgers, dead code that nobody dares delete. These lessons are usually trapped in individual developers' heads or scattered across PR comments.

**The Laravel Fortress** captures those lessons as a single, actionable checklist. It was born from repeated codebase audits of a production financial platform and expanded into a universal reference applicable to any Laravel project.

## What Following This Achieves

| Outcome | What It Means |
|---------|---------------|
| **Security** | Survives OWASP Top 10 penetration tests, closes injection vectors, hardens auth, satisfies SOC 2 / ISO 27001 audits |
| **Correctness** | Eliminates floating-point money bugs, race conditions, state machine bypasses, and silent data corruption |
| **Auditability** | Complete, tamper-evident audit trail from user action to ledger entry |
| **Maintainability** | Consistent patterns that let new developers navigate the codebase in hours, not weeks |
| **Resilience** | Zero-downtime deployments, clear runbooks, no data loss under load |

## Scope

Laravel 9–12 &middot; PHP 8.1–8.4 &middot; MySQL / PostgreSQL &middot; Vue / React / Blade &middot; Tailwind CSS &middot; Redis &middot; Pest / PHPUnit

> **Version agnostic**: The [AI skill system](#ai-integration) detects your project's actual versions and applies only the relevant rules.

## The 14 Parts

Browse individual parts in the [`parts/`](parts/) directory, or read the full [`checklist.md`](checklist.md).

| Part | Focus | Sections | Checks | Browse |
|------|-------|:--------:|:------:|--------|
| **I** | Application Security | 22 | 179 | [`01-application-security.md`](parts/01-application-security.md) |
| **II** | Cryptography & Data Protection | 12 | 109 | [`02-cryptography-data-protection.md`](parts/02-cryptography-data-protection.md) |
| **III** | Authentication & Authorization | 13 | 110 | [`03-authentication-authorization.md`](parts/03-authentication-authorization.md) |
| **IV** | Data Integrity & Concurrency | 11 | 84 | [`04-data-integrity-concurrency.md`](parts/04-data-integrity-concurrency.md) |
| **V** | Financial & Monetary Correctness | 8 | 62 | [`05-financial-monetary-correctness.md`](parts/05-financial-monetary-correctness.md) |
| **VI** | PHP Language & Type Safety | 15 | 126 | [`06-php-language-type-safety.md`](parts/06-php-language-type-safety.md) |
| **VII** | Clean Code & Software Design | 16 | 128 | [`07-clean-code-software-design.md`](parts/07-clean-code-software-design.md) |
| **VIII** | Laravel Framework Mastery | 23 | 196 | [`08-laravel-framework-mastery.md`](parts/08-laravel-framework-mastery.md) |
| **IX** | Database Engineering | 19 | 158 | [`09-database-engineering.md`](parts/09-database-engineering.md) |
| **X** | Frontend Engineering | 17 | 153 | [`10-frontend-engineering.md`](parts/10-frontend-engineering.md) |
| **XI** | Testing & Quality Assurance | 9 | 76 | [`11-testing-quality-assurance.md`](parts/11-testing-quality-assurance.md) |
| **XII** | APIs, Queues & Integration | 16 | 136 | [`12-apis-queues-integration.md`](parts/12-apis-queues-integration.md) |
| **XIII** | Logging, Monitoring & Audit | 3 | 30 | [`13-logging-monitoring-audit.md`](parts/13-logging-monitoring-audit.md) |
| **XIV** | Infrastructure & Operations | 16 | 208 | [`14-infrastructure-operations.md`](parts/14-infrastructure-operations.md) |

## Quick Start

### Read the checklist

1. **Full reference** &mdash; [`checklist.md`](checklist.md) (all 200 sections in one file)
2. **By part** &mdash; Browse [`parts/`](parts/) for the topic you're working on
3. **Work through the checks** &mdash; each one is a `- [ ]` checkbox you can tick off

### Install the AI skill (recommended)

```bash
curl -sL https://raw.githubusercontent.com/chuxolab/laravel-fortress/main/install.sh | bash
```

This auto-detects your editor (Claude Code, Cursor, Windsurf, Copilot) and installs the appropriate rule files. See [AI Integration](#ai-integration) below.

## How to Use This

### During Code Review

1. **Identify which Parts apply** — Map the PR's changed files to fortress Parts (controllers → P01/P03/P08, models → P08/P09, money logic → P05, etc.)
2. **Review against the relevant Part(s)** — Open the part file or ask your AI assistant to review the diff against those rules
3. **Reference rule IDs in comments** — Use `[F-P01-003]` format so findings are traceable and searchable
4. **Use your AI agent** — Ask it: *"Review this PR against fortress Parts P01 and P08. Flag violations with rule IDs."*

See [`rules/README.md`](rules/README.md#using-the-fortress-for-code-review) for the full code review workflow and recommended tools per editor.

### During Sprint Planning
When scoping a new feature, scan the related parts to identify security, correctness, and testing requirements upfront — not as afterthoughts.

### For Onboarding
Give new team members Parts VI–VIII (PHP, Clean Code, Laravel Mastery) as required reading. It's faster than explaining conventions one PR at a time.

### As an Audit Checklist
Walk through the entire document systematically when preparing for a security audit, compliance review, or codebase health assessment.

### In CI/CD
Many checks can be automated. Static analysis (PHPStan), linting (Pint), dependency audits (`composer audit`), and test coverage thresholds can enforce checks continuously.

## AI Integration

The Laravel Fortress includes a complete **AI skill system** that teaches your coding assistant all 1,755 checks. The system is **version-agnostic** — it detects your project's PHP version, Laravel version, database, and packages at runtime and applies only the relevant rules.

### Laravel Boost (MCP Server) — Strongly Recommended

[Laravel Boost](https://github.com/laravel/boost) is a Model Context Protocol (MCP) server that gives your AI agent direct access to your running Laravel application — database schema, routes, config, logs, Artisan commands, and a Tinker REPL. This transforms fortress enforcement from static code analysis to **live application verification**.

```bash
composer require laravel/boost --dev
```

With Boost, your AI agent can verify fortress rules against your actual database schema, route list, config values, and application state — not just source code. See [`rules/README.md`](rules/README.md#laravel-boost-mcp-server--essential-for-laravel-projects) for the full tool-to-fortress mapping.

### Supported Editors

| Editor | File | Recommended Workflow |
|--------|------|---------------------|
| **Claude Code** | 14 modular skills + `CLAUDE.md` | `feature-dev` plugin + Laravel Boost MCP for architecture, implementation, review, and live verification |
| **Cursor** | `.cursorrules` | Inline review + Composer mode + Boost MCP for live verification |
| **Windsurf** | `.windsurfrules` | Cascade flows + Boost MCP for multi-step generation with live checks |
| **GitHub Copilot** | `.github/copilot-instructions.md` | Copilot Chat + PR review + Boost MCP for fortress context |

See [`rules/README.md`](rules/README.md#recommended-tools--workflows) for detailed recommended tools, workflow patterns, and how to use the fortress for code review with each editor.

### How It Works

1. The AI reads your `composer.json`, `package.json`, and `.env` to build a project profile (or uses Laravel Boost's `application-info` for the most accurate detection)
2. It reads `.fortress.yml` (if present) to check which parts are enabled
3. For each rule, it checks whether the rule applies to your detected stack
4. Rules are enforced at the configured enforcement level (strict / standard / relaxed)
5. With Laravel Boost, the agent can **verify rules against your live application** — querying schema, routes, config, and executing test code

### Configuration

Create a `.fortress.yml` in your project root:

```yaml
fortress:
  version: "1.0.0"

  parts:
    P01_application_security:
      enabled: true
      enforcement: strict

    P05_financial_monetary:
      enabled: true
      enforcement: strict       # Critical for fintech apps

    P10_frontend:
      enabled: false            # Disable for API-only projects

  minimum_severity: warning     # Ignore 'info' level rules
```

See [`rules/.fortress.example.yml`](rules/.fortress.example.yml) for the full template and [`rules/README.md`](rules/README.md) for detailed documentation.

## Git Hooks & Merge Protection

The Laravel Fortress includes **10 active git hooks + 5 stubs** designed for AI-assisted development safety. When AI coding assistants write code, they move fast — these hooks add safety rails at the git level.

### Why Hooks Matter for AI Development

- AI agents can commit debug statements, .env files, and formatting violations at machine speed
- Without merge protection, an AI agent could auto-merge directly to production branches
- Conventional commit enforcement keeps your history clean and parseable
- Pre-push test gates catch regressions before they reach the remote

### Quick Install

```bash
# Via Composer (Laravel projects — recommended)
composer require --dev chuxolab/laravel-fortress
php artisan fortress:install

# Via shell (any PHP project)
curl -sL https://raw.githubusercontent.com/oilmonegov/laravel-fortress/main/install.sh | bash
```

### Hook Summary

| Hook | Blocking | Purpose |
|------|----------|---------|
| `pre-commit` | Yes | Pint formatting, debug statements, .env files, secrets, file size |
| `commit-msg` | Yes | Conventional commits, length limits, WIP blocking |
| `pre-push` | Yes | Tests, PHPStan, composer audit, direct push blocking |
| `prepare-commit-msg` | No | Auto-adds AI co-author tag, branch prefix |
| `post-checkout` | No | Warns when lock files changed between branches |
| `post-merge` | No | Lock file + migration change detection |
| `pre-rebase` | Yes | Blocks rebase of protected branches |
| `post-commit` | No | Advisory: strict_types check, TODO audit |
| `pre-merge-commit` | Yes | **AI auto-merge blocker** (git 2.36+) |
| `applypatch-msg` | Yes | Validates patch commit messages |

### Merge Protection

The `pre-merge-commit` hook detects AI context (Claude Code, Cursor, Windsurf, Copilot) and blocks auto-merge to protected branches. This is configurable but **strongly recommended** as the default:

```yaml
# .fortress.yml
fortress:
  merge_protection:
    require_human_approval: true
    block_ai_auto_merge: true
    protected_branches: [main, master, production]
```

A complementary GitHub Actions workflow (`pr-protection.yml`) enforces human review approval and blocks bot auto-merge at the CI level.

### Artisan Commands

| Command | Purpose |
|---------|---------|
| `fortress:install` | Interactive installer — rules, hooks, CI, config |
| `fortress:hooks install` | Install git hooks (with `--select` for interactive selection) |
| `fortress:hooks list` | Show installed fortress hooks |
| `fortress:hooks uninstall` | Remove fortress hooks, restore backups |
| `fortress:check` | Run compliance scan (strict_types, debug stmts, .env, etc.) |
| `fortress:check --fix` | Auto-fix issues where possible |

See [`hooks/README.md`](hooks/README.md) for full hook documentation.

## Not a Style Guide

This document does not prescribe tabs vs spaces or where to put your braces. It prescribes **engineering discipline**: how to handle money without rounding errors, how to prevent race conditions on financial records, how to structure authentication so privilege escalation is impossible, how to design migrations that don't cause downtime.

Style is preference. Discipline is survival.

## Contributing

Contributions are welcome. See [CONTRIBUTING.md](CONTRIBUTING.md) for guidelines.

The bar for new checks is: **"Would this have prevented a real bug, security vulnerability, or production incident?"** If yes, it belongs here. If it's a matter of preference, it doesn't.

## License

This project is licensed under the MIT License. See [LICENSE](LICENSE) for details.

---

Built with hard-won lessons from production. Maintained by the community.
