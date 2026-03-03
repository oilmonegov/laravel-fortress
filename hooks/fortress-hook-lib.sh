#!/bin/bash
# fortress-hook-lib.sh — Shared library for Laravel Fortress git hooks
# @fortress-hook v1.1.0
#
# All fortress hooks source this file for common functionality.
# Do not execute this file directly.

# --- Constants ---
FORTRESS_VERSION="1.1.0"
FORTRESS_CONFIG=".fortress.yml"
FORTRESS_MARKER="@fortress-hook"

# --- Colors ---
RED='\033[0;31m'
GREEN='\033[0;32m'
YELLOW='\033[0;33m'
BLUE='\033[0;34m'
BOLD='\033[1m'
NC='\033[0m' # No Color

# Disable colors if not a terminal
if [ ! -t 1 ]; then
    RED='' GREEN='' YELLOW='' BLUE='' BOLD='' NC=''
fi

# --- Output Helpers ---

fortress_header() {
    local hook_name="$1"
    echo -e "${BOLD}${BLUE}[Fortress]${NC} ${hook_name}"
}

fortress_pass() {
    echo -e "  ${GREEN}✓${NC} $1"
}

fortress_fail() {
    echo -e "  ${RED}✗${NC} $1"
}

fortress_warn() {
    echo -e "  ${YELLOW}!${NC} $1"
}

fortress_skip() {
    echo -e "  ${BLUE}–${NC} $1 (skipped)"
}

fortress_info() {
    echo -e "  ${BLUE}ℹ${NC} $1"
}

# --- Configuration ---

# Read a value from .fortress.yml using grep/sed (no YAML parser needed).
# Handles simple flat key-value pairs within the fortress config structure.
# Usage: fortress_config_get "git_hooks.pre_commit.run_pint" "true"
fortress_config_get() {
    local key="$1"
    local default="$2"
    local config_file

    # Find config file (search up to git root)
    config_file=$(fortress_find_config)
    if [ -z "$config_file" ]; then
        echo "$default"
        return
    fi

    # Convert dot notation to search for the last segment after finding the parent context
    local value
    value=$(grep -E "^\s+${key##*.}:" "$config_file" 2>/dev/null | head -1 | sed 's/.*:\s*//' | sed 's/\s*#.*//' | tr -d '"' | tr -d "'" | xargs)

    if [ -n "$value" ]; then
        echo "$value"
    else
        echo "$default"
    fi
}

# Find the .fortress.yml config file by searching up to git root
fortress_find_config() {
    local dir
    dir=$(git rev-parse --show-toplevel 2>/dev/null)
    if [ -n "$dir" ] && [ -f "$dir/$FORTRESS_CONFIG" ]; then
        echo "$dir/$FORTRESS_CONFIG"
    fi
}

# Check if a specific hook is enabled in config
# Default: enabled (hooks are opt-out, not opt-in)
fortress_hook_enabled() {
    local hook_name="$1"
    local config_file
    config_file=$(fortress_find_config)

    if [ -z "$config_file" ]; then
        # No config = all hooks enabled
        return 0
    fi

    # Check if git_hooks.enabled is false (global kill switch)
    local global_enabled
    global_enabled=$(grep -A1 "git_hooks:" "$config_file" 2>/dev/null | grep "enabled:" | head -1 | sed 's/.*:\s*//' | tr -d ' ')
    if [ "$global_enabled" = "false" ]; then
        return 1
    fi

    # Check if the specific hook section has enabled: false
    local hook_key="${hook_name//-/_}" # pre-commit -> pre_commit
    local hook_enabled
    hook_enabled=$(grep -A1 "${hook_key}:" "$config_file" 2>/dev/null | grep "enabled:" | head -1 | sed 's/.*:\s*//' | tr -d ' ')
    if [ "$hook_enabled" = "false" ]; then
        return 1
    fi

    return 0
}

# --- Git Helpers ---

# Get staged files, optionally filtered by extension
# Usage: fortress_staged_files "php"  or  fortress_staged_files "" (all files)
fortress_staged_files() {
    local ext="$1"
    if [ -n "$ext" ]; then
        git diff --cached --name-only --diff-filter=ACMR | grep -E "\.${ext}$" 2>/dev/null || true
    else
        git diff --cached --name-only --diff-filter=ACMR 2>/dev/null || true
    fi
}

# Get the current branch name
fortress_current_branch() {
    git rev-parse --abbrev-ref HEAD 2>/dev/null
}

# Check if the current branch is in the protected branches list
fortress_is_protected_branch() {
    local branch
    branch=$(fortress_current_branch)
    local protected_branches
    protected_branches=$(fortress_config_get "protected_branches" "main,master,production")

    # Normalize: remove brackets, spaces
    protected_branches=$(echo "$protected_branches" | tr -d '[]' | tr ',' '\n' | sed 's/^[[:space:]]*//;s/[[:space:]]*$//')

    echo "$protected_branches" | grep -qx "$branch"
}

# Get the git root directory
fortress_git_root() {
    git rev-parse --show-toplevel 2>/dev/null
}

# --- Tool Detection ---

# Check if a command is available
fortress_has_command() {
    command -v "$1" &>/dev/null
}

# --- AI Context Detection ---

# Detect if running in an AI agent environment
# Returns 0 (true) if AI context detected, 1 (false) otherwise
fortress_is_ai_context() {
    # Check environment variables set by AI coding tools
    [ -n "${CLAUDE_CODE:-}" ] && return 0
    [ -n "${CURSOR_SESSION:-}" ] && return 0
    [ -n "${WINDSURF_SESSION:-}" ] && return 0
    [ -n "${COPILOT_AGENT:-}" ] && return 0
    [ -n "${AIDER_SESSION:-}" ] && return 0
    [ -n "${CONTINUE_SESSION:-}" ] && return 0

    # Check git author for AI indicators
    local author
    author=$(git var GIT_AUTHOR_IDENT 2>/dev/null || true)
    if echo "$author" | grep -qiE "(noreply@anthropic\.com|\[bot\]|copilot|claude|cursor-ai)" 2>/dev/null; then
        return 0
    fi

    return 1
}

# Check if the last commit message contains AI co-author tags
fortress_has_ai_coauthor() {
    local msg="$1"
    echo "$msg" | grep -qiE "Co-Authored-By:.*\b(Claude|Copilot|Cursor|Windsurf|AI|GPT|Gemini)\b" 2>/dev/null
}

# --- Validation Helpers ---

# Check if a file contains a pattern
fortress_file_contains() {
    local file="$1"
    local pattern="$2"
    grep -qE "$pattern" "$file" 2>/dev/null
}

# Get file size in KB
fortress_file_size_kb() {
    local file="$1"
    local size
    if [ "$(uname)" = "Darwin" ]; then
        size=$(stat -f%z "$file" 2>/dev/null || echo 0)
    else
        size=$(stat -c%s "$file" 2>/dev/null || echo 0)
    fi
    echo $(( size / 1024 ))
}
