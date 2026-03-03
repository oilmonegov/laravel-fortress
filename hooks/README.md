# Laravel Fortress — Git Hooks

**10 active hooks + 5 stubs** for AI-assisted development safety.

These hooks enforce engineering discipline at the git level — catching debug statements, formatting violations, secret leaks, and AI auto-merge attempts before they reach your repository.

## Why Git Hooks for AI Development?

When AI coding assistants write code, they move fast. That speed is valuable — but it means more code flowing through your pipeline, often without the natural "pause and review" that manual coding provides. Fortress hooks add safety rails:

- **pre-commit**: Catches debug statements, .env files, and formatting issues before they enter history
- **pre-push**: Runs your test suite and static analysis before code leaves your machine
- **pre-merge-commit**: Blocks AI agents from auto-merging to protected branches
- **commit-msg**: Enforces conventional commit format for clean, parseable history

## Installation

### Via Composer (Laravel projects)

```bash
composer require --dev chuxolab/laravel-fortress
php artisan fortress:hooks install
```

### Via Shell (any project)

```bash
./install-hooks.sh /path/to/your/project
```

### Manual

Copy hooks from `active/` to your project's `.git/hooks/` directory:

```bash
cp hooks/active/* /path/to/project/.git/hooks/
cp hooks/fortress-hook-lib.sh /path/to/project/.git/hooks/
chmod +x /path/to/project/.git/hooks/*
```

## Active Hooks (10)

| Hook | Blocking | Purpose |
|------|----------|---------|
| `pre-commit` | Yes | Pint formatting, debug statements, .env files, secrets, file size limits |
| `commit-msg` | Yes | Conventional commits, length limits, blocks WIP on protected branches |
| `pre-push` | Yes | Tests, PHPStan, composer audit, blocks direct push to protected branches |
| `prepare-commit-msg` | No | Auto-adds AI co-author tag, branch prefix |
| `post-checkout` | No | Warns when composer.lock or JS lock files changed |
| `post-merge` | No | Same as post-checkout, plus migration change detection |
| `pre-rebase` | Yes | Blocks rebase of protected branches |
| `post-commit` | No | Advisory: strict_types check, TODO audit |
| `pre-merge-commit` | Yes | **AI auto-merge blocker** (requires git 2.36+) |
| `applypatch-msg` | Yes | Validates patch commit messages (reuses commit-msg logic) |

## Stub Hooks (5)

Server-side hooks and less common client-side hooks. Each includes documentation and a commented-out implementation body.

| Hook | Purpose |
|------|---------|
| `post-rewrite` | Re-checks after amend/rebase |
| `pre-receive` | Server-side: reject policy-violating pushes |
| `update` | Server-side: per-branch validation |
| `post-receive` | Server-side: deployment triggers |
| `pre-applypatch` | Patch content validation |

Install stubs with: `fortress:hooks install --with-stubs` or `./install-hooks.sh --with-stubs`

## Configuration

All hooks read from `.fortress.yml` in your project root. See the `git_hooks` section in [`.fortress.example.yml`](../rules/.fortress.example.yml).

### Disabling Hooks

**Globally** (disable all fortress hooks):
```yaml
fortress:
  git_hooks:
    enabled: false
```

**Per-hook**:
```yaml
fortress:
  git_hooks:
    pre_commit:
      enabled: false
```

**Per-check** (within a hook):
```yaml
fortress:
  git_hooks:
    pre_commit:
      enabled: true
      run_pint: false      # Skip Pint check
      check_debug: true    # Keep debug check
```

### Emergency Bypass

If you need to bypass hooks in an emergency:

```bash
git commit --no-verify -m "fix: emergency hotfix"
```

This skips ALL hooks. Use sparingly and document why.

## AI Merge Protection

The `pre-merge-commit` hook is the primary AI auto-merge blocker. It detects AI context through:

1. **Environment variables**: `CLAUDE_CODE`, `CURSOR_SESSION`, `WINDSURF_SESSION`, `COPILOT_AGENT`
2. **Git author patterns**: `noreply@anthropic.com`, `[bot]`, AI-related identifiers

When AI context is detected AND the target is a protected branch, the merge is blocked with instructions to use a pull request workflow.

### Using with Husky

If your team uses [Husky](https://typicode.github.io/husky/), you can wire fortress hooks into your Husky setup:

```bash
# .husky/pre-commit
#!/bin/sh
. "$(dirname "$0")/_/husky.sh"

# Run fortress pre-commit hook
.git/hooks/pre-commit
```

Or call the hook scripts directly from your Husky config.

## Shared Library

All hooks source `fortress-hook-lib.sh` which provides:

| Function | Purpose |
|----------|---------|
| `fortress_config_get key default` | Read `.fortress.yml` values |
| `fortress_hook_enabled hook_name` | Check if hook is enabled |
| `fortress_header hook_name` | Print banner |
| `fortress_pass/fail/warn/skip msg` | Colored output |
| `fortress_has_command cmd` | Tool availability check |
| `fortress_staged_files ext` | Get staged files by extension |
| `fortress_current_branch` | Current branch name |
| `fortress_is_protected_branch` | Check against protected branch list |
| `fortress_is_ai_context` | Detect AI agent environment |
