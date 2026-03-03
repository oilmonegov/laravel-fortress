#!/bin/bash
# install.sh — Install Laravel Fortress rules into your project
#
# Usage:
#   curl -sL https://raw.githubusercontent.com/chuxolab/laravel-fortress/main/install.sh | bash
#   ./install.sh /path/to/your/project
#
# This script detects your editor and copies the appropriate rule files.

set -euo pipefail

TARGET="${1:-.}"
REPO_RAW="https://raw.githubusercontent.com/chuxolab/laravel-fortress/main"
INSTALLED=()

echo ""
echo "  ╔══════════════════════════════════════╗"
echo "  ║     The Laravel Fortress Installer   ║"
echo "  ║   1,755 checks · 200 sections · v1  ║"
echo "  ╚══════════════════════════════════════╝"
echo ""

# Verify target is a Laravel project
if [ ! -f "$TARGET/composer.json" ]; then
    echo "  ⚠  No composer.json found in $TARGET"
    echo "     Are you sure this is a Laravel project?"
    echo ""
    read -p "  Continue anyway? [y/N] " -n 1 -r
    echo ""
    if [[ ! $REPLY =~ ^[Yy]$ ]]; then
        echo "  Aborted."
        exit 1
    fi
fi

# Helper: download a file from the repo
download() {
    local src="$1"
    local dest="$2"
    if command -v curl &>/dev/null; then
        curl -sL "$REPO_RAW/$src" -o "$dest"
    elif command -v wget &>/dev/null; then
        wget -q "$REPO_RAW/$src" -O "$dest"
    else
        echo "  ✗ Neither curl nor wget found. Cannot download files."
        exit 1
    fi
}

# --- Claude Code ---
if [ -d "$TARGET/.claude" ]; then
    echo "  ✓ Detected: Claude Code"

    # Copy skill files
    SKILLS=(
        fortress-security fortress-crypto fortress-auth fortress-concurrency
        fortress-financial fortress-php fortress-clean-code fortress-laravel
        fortress-database fortress-frontend fortress-testing fortress-apis
        fortress-logging fortress-infrastructure
    )

    mkdir -p "$TARGET/.claude/skills"
    for skill in "${SKILLS[@]}"; do
        mkdir -p "$TARGET/.claude/skills/$skill"
        download "rules/skills/$skill/SKILL.md" "$TARGET/.claude/skills/$skill/SKILL.md"
    done
    INSTALLED+=("Claude Code skills (14 skill files)")
    echo "     Installed 14 skill files to .claude/skills/"

    # Offer to add rules to CLAUDE.md
    if [ -f "$TARGET/CLAUDE.md" ]; then
        echo "     ℹ  Existing CLAUDE.md found — fortress rules NOT merged automatically."
        echo "     → Manually merge rules/editors/CLAUDE.md into your CLAUDE.md"
    else
        download "rules/editors/CLAUDE.md" "$TARGET/CLAUDE.md"
        INSTALLED+=("CLAUDE.md")
        echo "     Created CLAUDE.md with fortress rules"
    fi
fi

# --- Cursor ---
if [ -f "$TARGET/.cursorrules" ] || [ -d "$TARGET/.cursor" ] || command -v cursor &>/dev/null 2>&1; then
    echo "  ✓ Detected: Cursor"
    if [ -f "$TARGET/.cursorrules" ]; then
        echo "     ℹ  Existing .cursorrules found — backed up to .cursorrules.bak"
        cp "$TARGET/.cursorrules" "$TARGET/.cursorrules.bak"
    fi
    download "rules/editors/.cursorrules" "$TARGET/.cursorrules"
    INSTALLED+=(".cursorrules")
    echo "     Installed .cursorrules"
fi

# --- Windsurf ---
if [ -f "$TARGET/.windsurfrules" ] || command -v windsurf &>/dev/null 2>&1; then
    echo "  ✓ Detected: Windsurf"
    if [ -f "$TARGET/.windsurfrules" ]; then
        echo "     ℹ  Existing .windsurfrules found — backed up to .windsurfrules.bak"
        cp "$TARGET/.windsurfrules" "$TARGET/.windsurfrules.bak"
    fi
    download "rules/editors/.windsurfrules" "$TARGET/.windsurfrules"
    INSTALLED+=(".windsurfrules")
    echo "     Installed .windsurfrules"
fi

# --- GitHub Copilot ---
if [ -d "$TARGET/.github" ]; then
    echo "  ✓ Detected: GitHub repository"
    if [ ! -f "$TARGET/.github/copilot-instructions.md" ]; then
        download "rules/editors/copilot-instructions.md" "$TARGET/.github/copilot-instructions.md"
        INSTALLED+=("copilot-instructions.md")
        echo "     Installed .github/copilot-instructions.md"
    else
        echo "     ℹ  Existing copilot-instructions.md found — skipped"
    fi
fi

# --- .fortress.yml ---
if [ ! -f "$TARGET/.fortress.yml" ]; then
    download "rules/.fortress.example.yml" "$TARGET/.fortress.yml"
    INSTALLED+=(".fortress.yml")
    echo ""
    echo "  ✓ Created .fortress.yml (configure your parts and enforcement levels)"
else
    echo ""
    echo "  ℹ  Existing .fortress.yml found — skipped"
fi

# --- No editor detected ---
if [ ${#INSTALLED[@]} -eq 0 ]; then
    echo "  ℹ  No supported editor detected."
    echo ""
    echo "  Manual installation:"
    echo "    Claude Code  → Copy rules/editors/CLAUDE.md to your project root"
    echo "    Cursor       → Copy rules/editors/.cursorrules to your project root"
    echo "    Windsurf     → Copy rules/editors/.windsurfrules to your project root"
    echo "    Copilot      → Copy rules/editors/copilot-instructions.md to .github/"
    echo ""
    echo "  All editors    → Copy rules/.fortress.example.yml to .fortress.yml"
    exit 0
fi

# --- Summary ---
echo ""
echo "  ────────────────────────────────────"
echo "  Installed:"
for item in "${INSTALLED[@]}"; do
    echo "    • $item"
done
echo ""
echo "  Next steps:"
echo "    1. Edit .fortress.yml to configure parts and enforcement levels"
echo "    2. Add .fortress.yml to version control"
echo "    3. Start coding — your AI assistant will enforce the fortress"
echo ""
echo "  Full checklist: https://github.com/chuxolab/laravel-fortress"
echo ""
