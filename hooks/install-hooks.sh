#!/bin/bash
# install-hooks.sh — Standalone hook installer for Laravel Fortress
# @fortress-hook v1.1.0
#
# Usage:
#   ./install-hooks.sh /path/to/project              # Install active hooks
#   ./install-hooks.sh --with-stubs /path/to/project  # Include stub hooks
#   ./install-hooks.sh --uninstall /path/to/project    # Remove fortress hooks
#   ./install-hooks.sh --list /path/to/project         # Show installed hooks
#
# For non-Laravel projects or CI environments. Laravel projects should
# prefer: composer require --dev chuxolab/laravel-fortress && php artisan fortress:hooks install

set -euo pipefail

VERSION="1.1.0"
MARKER="@fortress-hook"

# --- Parse Arguments ---
ACTION="install"
WITH_STUBS=false
TARGET=""

while [[ $# -gt 0 ]]; do
    case "$1" in
        --with-stubs) WITH_STUBS=true; shift ;;
        --uninstall)  ACTION="uninstall"; shift ;;
        --list)       ACTION="list"; shift ;;
        --help|-h)    ACTION="help"; shift ;;
        *)            TARGET="$1"; shift ;;
    esac
done

TARGET="${TARGET:-.}"

# --- Help ---
if [ "$ACTION" = "help" ]; then
    echo "Laravel Fortress Hook Installer v$VERSION"
    echo ""
    echo "Usage:"
    echo "  ./install-hooks.sh [options] /path/to/project"
    echo ""
    echo "Options:"
    echo "  --with-stubs   Include stub hooks (server-side templates)"
    echo "  --uninstall    Remove fortress hooks and restore backups"
    echo "  --list         List installed fortress hooks"
    echo "  --help         Show this help message"
    echo ""
    echo "Examples:"
    echo "  ./install-hooks.sh .                    # Install in current directory"
    echo "  ./install-hooks.sh --with-stubs ~/app   # Install with stubs"
    echo "  ./install-hooks.sh --uninstall ~/app    # Uninstall"
    exit 0
fi

# --- Validate ---
if [ ! -d "$TARGET/.git" ]; then
    echo "Error: $TARGET is not a git repository (no .git directory found)"
    exit 1
fi

HOOKS_DIR="$TARGET/.git/hooks"
mkdir -p "$HOOKS_DIR"

# Find the source hooks directory (this script's sibling directories)
SCRIPT_DIR="$(cd "$(dirname "$0")" && pwd)"
ACTIVE_DIR="$SCRIPT_DIR/active"
STUBS_DIR="$SCRIPT_DIR/stubs"
LIB_FILE="$SCRIPT_DIR/fortress-hook-lib.sh"

# --- List ---
if [ "$ACTION" = "list" ]; then
    echo "Laravel Fortress Hooks — Installed in $TARGET"
    echo ""
    FOUND=0
    for hook in "$HOOKS_DIR"/*; do
        [ -f "$hook" ] || continue
        if grep -q "$MARKER" "$hook" 2>/dev/null; then
            NAME=$(basename "$hook")
            TYPE="active"
            if grep -q "(stub)" "$hook" 2>/dev/null; then
                TYPE="stub"
            fi
            echo "  ✓ $NAME ($TYPE)"
            FOUND=$((FOUND + 1))
        fi
    done

    if [ "$FOUND" -eq 0 ]; then
        echo "  No fortress hooks installed."
    else
        echo ""
        echo "  $FOUND fortress hook(s) installed."
    fi

    # Check for backups
    BACKUPS=$(find "$HOOKS_DIR" -name "*.pre-fortress.bak" 2>/dev/null | wc -l | tr -d ' ')
    if [ "$BACKUPS" -gt 0 ]; then
        echo "  $BACKUPS backup(s) found (.pre-fortress.bak)"
    fi
    exit 0
fi

# --- Uninstall ---
if [ "$ACTION" = "uninstall" ]; then
    echo "Laravel Fortress — Uninstalling hooks from $TARGET"
    echo ""
    REMOVED=0
    for hook in "$HOOKS_DIR"/*; do
        [ -f "$hook" ] || continue
        NAME=$(basename "$hook")
        if grep -q "$MARKER" "$hook" 2>/dev/null; then
            rm "$hook"
            echo "  ✗ Removed: $NAME"
            REMOVED=$((REMOVED + 1))

            # Restore backup if exists
            BACKUP="$HOOKS_DIR/${NAME}.pre-fortress.bak"
            if [ -f "$BACKUP" ]; then
                mv "$BACKUP" "$hook"
                echo "    ↩ Restored backup: $NAME"
            fi
        fi
    done

    if [ "$REMOVED" -eq 0 ]; then
        echo "  No fortress hooks found to remove."
    else
        echo ""
        echo "  $REMOVED hook(s) removed."
    fi
    exit 0
fi

# --- Install ---
echo ""
echo "  ╔══════════════════════════════════════╗"
echo "  ║   Laravel Fortress — Hook Installer  ║"
echo "  ║          v$VERSION · $(ls "$ACTIVE_DIR" 2>/dev/null | wc -l | tr -d ' ') active hooks       ║"
echo "  ╚══════════════════════════════════════╝"
echo ""

if [ ! -d "$ACTIVE_DIR" ]; then
    echo "Error: Active hooks directory not found at $ACTIVE_DIR"
    echo "Make sure this script is in the hooks/ directory of the fortress package."
    exit 1
fi

INSTALLED=0

# Copy shared library
if [ -f "$LIB_FILE" ]; then
    cp "$LIB_FILE" "$HOOKS_DIR/fortress-hook-lib.sh"
    echo "  ✓ Copied: fortress-hook-lib.sh"
    INSTALLED=$((INSTALLED + 1))
fi

# Copy active hooks
for hook in "$ACTIVE_DIR"/*; do
    [ -f "$hook" ] || continue
    NAME=$(basename "$hook")
    DEST="$HOOKS_DIR/$NAME"

    # Backup existing hook if it's not a fortress hook
    if [ -f "$DEST" ] && ! grep -q "$MARKER" "$DEST" 2>/dev/null; then
        cp "$DEST" "${DEST}.pre-fortress.bak"
        echo "  ↩ Backed up: $NAME → ${NAME}.pre-fortress.bak"
    fi

    cp "$hook" "$DEST"
    chmod +x "$DEST"
    echo "  ✓ Installed: $NAME"
    INSTALLED=$((INSTALLED + 1))
done

# Copy stub hooks if requested
if [ "$WITH_STUBS" = true ] && [ -d "$STUBS_DIR" ]; then
    echo ""
    echo "  Stubs:"
    for hook in "$STUBS_DIR"/*; do
        [ -f "$hook" ] || continue
        NAME=$(basename "$hook")
        DEST="$HOOKS_DIR/$NAME"

        if [ -f "$DEST" ] && ! grep -q "$MARKER" "$DEST" 2>/dev/null; then
            cp "$DEST" "${DEST}.pre-fortress.bak"
            echo "  ↩ Backed up: $NAME → ${NAME}.pre-fortress.bak"
        fi

        cp "$hook" "$DEST"
        chmod +x "$DEST"
        echo "  ✓ Installed: $NAME (stub)"
        INSTALLED=$((INSTALLED + 1))
    done
fi

echo ""
echo "  ────────────────────────────────────"
echo "  $INSTALLED file(s) installed to $HOOKS_DIR"
echo ""
echo "  Next steps:"
echo "    1. Copy .fortress.example.yml to .fortress.yml and customize"
echo "    2. Test with: git commit --allow-empty -m 'test: fortress hook check'"
echo ""
