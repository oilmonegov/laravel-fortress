# Changelog

All notable changes to this project will be documented in this file.

The format is based on [Keep a Changelog](https://keepachangelog.com/en/1.1.0/).

## [1.2.0] - 2026-03-03

### Added

- **Deep code review**: `fortress:review` command with 52 automated checks across all 14 parts
- **Review report generation**: Markdown reports saved to `docs/fortress-reviews/` with problem + solution documentation
- **Extensible check architecture**: `FortressCheck` interface, `BaseCheck` abstract, `CheckResult` value object, `ReviewContext` for file discovery
- **52 check classes**: P01 (8), P02 (3), P03 (4), P04 (3), P05 (4), P06 (5), P07 (4), P08 (6), P09 (4), P10 (3), P11 (3), P12 (2), P13 (1), P14 (2)
- **Part filtering**: `--part=P01 --part=P05` to scope reviews
- **Severity filtering**: `--severity=critical` to focus on high-impact findings
- **Interactive selection**: `--select` for choosing parts interactively
- **Console output**: `--format=console` for terminal-friendly output
- **Financial checks**: Floating-point money detection, currency handling, rounding mode, money comparison (P05)
- **Security checks**: SQL injection, XSS, CSRF, open redirect, mass assignment, secrets, deserialization (P01)

## [1.1.0] - 2026-03-03

### Added

- **Composer package**: Install via `composer require --dev chuxolab/laravel-fortress`
- **Artisan commands**: `fortress:install`, `fortress:hooks`, `fortress:check`
- **10 active git hooks**: pre-commit, commit-msg, pre-push, prepare-commit-msg, post-checkout, post-merge, pre-rebase, post-commit, pre-merge-commit, applypatch-msg
- **5 stub hooks**: post-rewrite, pre-receive, update, post-receive, pre-applypatch
- **Shared hook library** (`fortress-hook-lib.sh`): config parsing, colored output, AI detection, git helpers
- **Standalone hook installer** (`hooks/install-hooks.sh`) for non-Laravel projects
- **AI auto-merge blocker**: `pre-merge-commit` hook detects AI context and blocks merge to protected branches
- **PR merge protection**: GitHub Actions workflow template requiring human approval
- **Compliance scanner**: `fortress:check` command with 6 automated checks and `--fix` support
- **Interactive installation**: Laravel Prompts-based multiselect for components, hooks, and editors
- **Git hooks configuration**: New `git_hooks` and `merge_protection` sections in `.fortress.yml`
- **Release workflow**: GitHub Actions auto-release on tag push

### Changed

- Updated `install.sh` with `--hooks`, `--ci`, and `--all` flags
- Updated `.fortress.example.yml` with git hooks and merge protection configuration
- Updated repo URLs from `chuxolab` to `oilmonegov` GitHub organization

## [1.0.0] - 2026-03-03

### Added

- Initial release with 1,755 checks across 200 sections organized into 14 parts
- Full single-file reference (`checklist.md`)
- 14 browsable part files in `parts/` directory
- AI skill system with support for Claude Code, Cursor, Windsurf, and GitHub Copilot
- Project-level configuration via `.fortress.yml`
- Claude Code modular skills (one per Part)
- Installer script for automated setup
