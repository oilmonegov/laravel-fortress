# Changelog

All notable changes to this project will be documented in this file.

The format is based on [Keep a Changelog](https://keepachangelog.com/en/1.1.0/).

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
