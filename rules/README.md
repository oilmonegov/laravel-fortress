# Laravel Fortress — AI Skill System

> **Teach your AI coding assistant 1,755 engineering rules in one command.**

The Laravel Fortress AI skill system makes your coding assistant (Claude Code, Cursor, Windsurf, or GitHub Copilot) aware of all 1,755 checks. It automatically adapts rules to your project's PHP version, Laravel version, database, and installed packages.

## How It Works

```
┌─────────────────┐     ┌──────────────┐     ┌─────────────────┐
│  fortress-rules  │────▶│  .fortress   │────▶│  AI assistant   │
│  .yml (master)   │     │  .yml (your  │     │  applies rules  │
│  1,755 rules     │     │  project)    │     │  intelligently  │
└─────────────────┘     └──────────────┘     └─────────────────┘
```

1. **Master rules** (`fortress-rules.yml`) — Every check coded as a machine-readable rule with severity, enforcement level, and applicability conditions.
2. **Your config** (`.fortress.yml`) — You choose which Parts to enable and at what enforcement level.
3. **AI applies rules** — The assistant reads your config, detects your stack, and enforces only the relevant rules.

## Quick Install

### One-liner (recommended)

```bash
curl -sL https://raw.githubusercontent.com/chuxolab/laravel-fortress/main/install.sh | bash
```

Or from a specific directory:

```bash
curl -sL https://raw.githubusercontent.com/chuxolab/laravel-fortress/main/install.sh | bash -s /path/to/your/project
```

The installer auto-detects your editor and installs the appropriate files.

### Manual Install

#### Claude Code

Copy the 14 skill files to your project:

```bash
# From the fortress repo
cp -r rules/skills/* /path/to/your/project/.claude/skills/

# Or just the main rules file
cp rules/editors/CLAUDE.md /path/to/your/project/CLAUDE.md
```

#### Cursor

```bash
cp rules/editors/.cursorrules /path/to/your/project/.cursorrules
```

#### Windsurf

```bash
cp rules/editors/.windsurfrules /path/to/your/project/.windsurfrules
```

#### GitHub Copilot

```bash
cp rules/editors/copilot-instructions.md /path/to/your/project/.github/copilot-instructions.md
```

#### All editors — add the config file

```bash
cp rules/.fortress.example.yml /path/to/your/project/.fortress.yml
```

## Configuration (`.fortress.yml`)

Create a `.fortress.yml` in your project root to configure which rules are active:

```yaml
fortress:
  version: "1.0.0"

  parts:
    P01_application_security:
      enabled: true
      enforcement: strict      # strict | standard | relaxed

    P05_financial_monetary:
      enabled: true            # Enable for fintech apps
      enforcement: strict

    P10_frontend:
      enabled: false           # Disable for API-only projects

  # Override specific rules
  rules:
    F-P08-042:
      enabled: false
      reason: "We use a custom ORM"

  minimum_severity: warning    # Ignore 'info' level rules
```

See [`.fortress.example.yml`](.fortress.example.yml) for the full template with all options.

### Enforcement Levels

| Level | Behavior |
|-------|----------|
| `strict` | AI flags every violation. No exceptions allowed. |
| `standard` | AI flags violations but accepts justified overrides. |
| `relaxed` | AI mentions as a suggestion only. |

### Severity Levels

| Level | Meaning |
|-------|---------|
| `critical` | Must fix. Violations are bugs or security holes. |
| `warning` | Should fix. Violations are code smells or risks. |
| `info` | Good practice. Informational only. |

## Stack Detection (Version Agnostic)

The AI assistant does NOT hardcode versions. Instead, it reads your project files at runtime:

| Detection | Source | What It Reads |
|-----------|--------|---------------|
| PHP version | `composer.json` | `config.platform.php` or `require.php` |
| Laravel version | `composer.json` | `require.laravel/framework` |
| Frontend | `package.json` | `dependencies.vue`, `dependencies.react` |
| Database | `.env` | `DB_CONNECTION` |
| Testing | `composer.json` | `require-dev.pestphp/pest` or `phpunit/phpunit` |
| Redis | `.env` | `CACHE_STORE`, `QUEUE_CONNECTION` |

### Version Adaptation

Rules adapt to whatever version your project uses:

| Rule Says | PHP 8.2 Project | PHP 8.4 Project |
|-----------|-----------------|-----------------|
| "Use property hooks" | Skipped | Applied |
| "Use `#[Override]`" | Skipped | Applied |
| "Use `array_find()`" | Suggests `collect()->first()` | Applied |

| Rule Says | Laravel 10 | Laravel 12 |
|-----------|------------|------------|
| "Use `casts()` method" | Suggests `$casts` property | Applied |
| "Use `Dumpable` trait" | Skipped | Applied |

| Rule Says | Pest Project | PHPUnit Project |
|-----------|-------------|-----------------|
| "Use `it()` syntax" | Applied | Suggests `test_*()` method |

## `applies_when` Conditions

Each rule carries conditions that determine when it applies:

| Condition | Meaning |
|-----------|---------|
| `always` | Universal — applies to any Laravel project |
| `php >= 8.1` | Requires PHP 8.1 or higher |
| `php >= 8.4` | Requires PHP 8.4 or higher |
| `laravel >= 11` | Requires Laravel 11 or higher |
| `has: package-name` | Package must be in composer.json or package.json |
| `database: mysql` | MySQL database |
| `database: pgsql` | PostgreSQL database |
| `frontend: any` | Any frontend framework detected |
| `frontend: vue` | Vue.js detected |
| `no_frontend` | API-only project |
| `testing: pest` | Pest testing framework |
| `testing: phpunit` | PHPUnit testing framework |
| `has_redis` | Redis configured for cache, queue, or session |

## Recommended Tools & Workflows

The fortress defines *what* rules to enforce. These tools help your AI agent *how* to work with them effectively across the development lifecycle.

### Claude Code

Claude Code has the richest integration via modular skills and specialized sub-agents:

| Workflow | Tool / Plugin | How It Uses the Fortress |
|----------|--------------|--------------------------|
| **Feature development** | `feature-dev` plugin (Anthropic) | Use `feature-dev:code-architect` to design features, then `feature-dev:code-reviewer` to verify the implementation against fortress rules. The architect reads relevant Part skills before proposing a design. |
| **Code review** | `feature-dev:code-reviewer` | Point the reviewer at a diff or file set. It cross-references the fortress skills to flag violations by rule ID (e.g., `F-P01-003`). |
| **Deep codebase analysis** | `feature-dev:code-explorer` | Traces execution paths and maps architecture. Combine with fortress skills to audit whether existing code meets standards. |
| **Focused audit** | Fortress skills directly | Activate `fortress-security` when reviewing auth code, `fortress-financial` when reviewing money logic, etc. The agent applies only the relevant Part's rules. |
| **Code simplification** | `laravel-simplifier` (if available) | After fortress review, simplify flagged code while preserving compliance. |

**Skill activation pattern for Claude Code:**

```
# In your project's CLAUDE.md, add a skill activation table:
| Domain                    | Fortress Skill(s) to Activate         |
|---------------------------|---------------------------------------|
| Auth, roles, permissions  | fortress-auth, fortress-security      |
| Money, ledger, trades     | fortress-financial, fortress-security |
| API endpoints, webhooks   | fortress-apis, fortress-security      |
| Database migrations       | fortress-database                     |
| Vue/Inertia pages         | fortress-frontend                     |
| Tests                     | fortress-testing                      |
| Any new code              | fortress-clean-code, fortress-php     |
```

### Cursor

| Workflow | How It Works |
|----------|-------------|
| **Inline review** | Cursor reads `.cursorrules` automatically. When you ask it to review code or implement a feature, it applies fortress rules in context. |
| **Chat review** | Ask: *"Review this file against the fortress security rules"* — Cursor references the embedded rules. |
| **Composer mode** | For multi-file changes, Cursor applies rules across all touched files. |

### Windsurf

| Workflow | How It Works |
|----------|-------------|
| **Cascade flows** | Windsurf reads `.windsurfrules` and applies rules during its multi-step Cascade flows. |
| **Code generation** | Rules constrain generated code to follow fortress standards. |
| **Review commands** | Ask Windsurf to audit a file or directory against specific Parts. |

### GitHub Copilot

| Workflow | How It Works |
|----------|-------------|
| **Copilot Chat** | With `copilot-instructions.md` installed, Copilot Chat applies fortress rules when answering questions or generating code. |
| **PR reviews** | Use Copilot's PR review feature — it will reference the fortress rules when evaluating changes. |
| **Inline suggestions** | Copilot's completions are influenced by the rules, steering toward compliant patterns. |

### Using the Fortress for Code Review

The fortress is designed to be a code review companion. Here's a practical workflow for any editor:

**1. Identify the affected domains**

Look at which files the PR touches and map them to Parts:

| Files Changed | Relevant Parts |
|--------------|----------------|
| Controllers, middleware, routes | P01 (Security), P03 (Auth), P08 (Laravel) |
| Models, migrations | P08 (Laravel), P09 (Database), P04 (Concurrency) |
| Money/financial logic | P05 (Financial), P04 (Concurrency) |
| Vue/TS components | P10 (Frontend) |
| Tests | P11 (Testing) |
| Jobs, queues, webhooks | P12 (APIs & Queues) |
| Config, deployment | P14 (Infrastructure) |

**2. Run the review**

Ask your AI agent:

```
Review this PR against fortress Parts P01, P03, and P08.
Flag any violations with the rule ID.
```

Or for a focused security audit:

```
Audit this file for security issues using the fortress-security skill.
Report each finding as: [Rule ID] Severity — Description — Line number.
```

**3. Use rule IDs in PR comments**

Reference specific rules in review feedback:

```
[F-P01-003] CRITICAL — This query uses string interpolation instead of
parameter binding. See Section 1 in Part I.
```

**4. Track compliance over time**

Use rule IDs to tag issues in your project tracker. Over time, you'll see which Parts have the most violations and can prioritize team education.

## File Reference

| File | Purpose |
|------|---------|
| `fortress-rules.yml` | Master rule registry — all 1,755 rules with IDs, severity, conditions |
| `.fortress.example.yml` | Template for project-level config — copy to `.fortress.yml` |
| `editors/CLAUDE.md` | Pre-built rules for Claude Code |
| `editors/.cursorrules` | Pre-built rules for Cursor |
| `editors/.windsurfrules` | Pre-built rules for Windsurf |
| `editors/copilot-instructions.md` | Pre-built rules for GitHub Copilot |
| `skills/fortress-*/SKILL.md` | Claude Code modular skills (one per Part) |

## Rule ID Format

Every rule has a unique ID: `F-P{part}-{sequence}`

- `F` — Fortress prefix
- `P{part}` — Part number (01–14)
- `{sequence}` — Sequential number within the Part (001, 002, ...)

Examples:
- `F-P01-001` — First rule in Part I (Application Security)
- `F-P05-042` — 42nd rule in Part V (Financial & Monetary Correctness)
- `F-P14-208` — Last rule in Part XIV (Infrastructure & Operations)

Use rule IDs in `.fortress.yml` to override specific rules, and in PR comments to reference checks.
