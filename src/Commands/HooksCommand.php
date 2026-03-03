<?php

declare(strict_types=1);

namespace Fortress\Commands;

use Illuminate\Console\Command;

use function Laravel\Prompts\confirm;
use function Laravel\Prompts\info;
use function Laravel\Prompts\multiselect;
use function Laravel\Prompts\note;
use function Laravel\Prompts\warning;

class HooksCommand extends Command
{
    protected $signature = 'fortress:hooks
        {action=install : install, uninstall, list, or update}
        {--with-stubs : Include stub hooks (server-side templates)}
        {--select : Interactively select which hooks to install}
        {--force : Overwrite existing hooks without prompting}';

    protected $description = 'Manage Laravel Fortress git hooks';

    /** @var array<string, string> */
    private array $activeHooks = [
        'pre-commit' => 'Pint, debug statements, .env, secrets, file size',
        'commit-msg' => 'Conventional commits, length, WIP blocking',
        'pre-push' => 'Tests, PHPStan, audit, direct push blocking',
        'prepare-commit-msg' => 'AI co-author tag, branch prefix',
        'post-checkout' => 'Lock file change detection',
        'post-merge' => 'Lock file + migration change detection',
        'pre-rebase' => 'Protected branch rebase blocking',
        'post-commit' => 'Advisory: strict_types, TODOs',
        'pre-merge-commit' => 'AI auto-merge blocker',
        'applypatch-msg' => 'Patch message validation',
    ];

    /** @var array<string, string> */
    private array $stubHooks = [
        'post-rewrite' => 'Post-amend/rebase re-checks',
        'pre-receive' => 'Server-side: reject policy-violating pushes',
        'update' => 'Server-side: per-branch validation',
        'post-receive' => 'Server-side: deployment triggers',
        'pre-applypatch' => 'Patch content validation',
    ];

    public function handle(): int
    {
        $gitDir = base_path('.git');
        if (! is_dir($gitDir)) {
            $this->components->error('Not a git repository. Run git init first.');

            return self::FAILURE;
        }

        return match ($this->argument('action')) {
            'install' => $this->installHooks(),
            'uninstall' => $this->uninstallHooks(),
            'list' => $this->listHooks(),
            'update' => $this->updateHooks(),
            default => $this->invalidAction(),
        };
    }

    private function installHooks(): int
    {
        $hooksDir = base_path('.git/hooks');
        @mkdir($hooksDir, 0755, true);

        $force = (bool) $this->option('force');
        $withStubs = (bool) $this->option('with-stubs');
        $selectMode = (bool) $this->option('select');

        // Determine which hooks to install
        if ($selectMode) {
            $options = [];
            foreach ($this->activeHooks as $name => $desc) {
                $options[$name] = "{$name} — {$desc}";
            }

            $selectedActive = multiselect(
                label: 'Which active hooks do you want to install?',
                options: $options,
                default: array_keys($this->activeHooks),
                hint: 'Use space to toggle. All recommended by default.',
                required: 'Select at least one hook.',
            );

            if ($withStubs) {
                $stubOptions = [];
                foreach ($this->stubHooks as $name => $desc) {
                    $stubOptions[$name] = "{$name} — {$desc}";
                }

                $selectedStubs = multiselect(
                    label: 'Which stub hooks do you want to install?',
                    options: $stubOptions,
                    hint: 'Stubs are templates — activate by editing them.',
                );
            } else {
                $selectedStubs = [];
            }
        } else {
            $selectedActive = array_keys($this->activeHooks);
            $selectedStubs = $withStubs ? array_keys($this->stubHooks) : [];
        }

        $installed = 0;

        // Copy shared library
        $libSource = $this->packagePath('hooks/fortress-hook-lib.sh');
        $libDest = "{$hooksDir}/fortress-hook-lib.sh";
        if (file_exists($libSource)) {
            copy($libSource, $libDest);
            $this->components->info('Copied fortress-hook-lib.sh');
            $installed++;
        }

        // Install active hooks
        foreach ($selectedActive as $hook) {
            $installed += $this->copyHook($hook, 'active', $hooksDir, $force);
        }

        // Install stub hooks
        foreach ($selectedStubs as $hook) {
            $installed += $this->copyHook($hook, 'stubs', $hooksDir, $force);
        }

        $this->newLine();
        info("{$installed} file(s) installed to .git/hooks/");
        note('Test with: git commit --allow-empty -m "test: fortress hook check"');

        return self::SUCCESS;
    }

    private function copyHook(string $name, string $type, string $hooksDir, bool $force): int
    {
        $source = $this->packagePath("hooks/{$type}/{$name}");
        $dest = "{$hooksDir}/{$name}";

        if (! file_exists($source)) {
            $this->components->warn("Source not found: hooks/{$type}/{$name}");

            return 0;
        }

        // Handle existing hook
        if (file_exists($dest) && ! $this->isFortressHook($dest)) {
            if (! $force) {
                $overwrite = confirm(
                    label: "Existing {$name} hook found (not a fortress hook). Overwrite?",
                    default: true,
                    hint: "The existing hook will be backed up to {$name}.pre-fortress.bak",
                );

                if (! $overwrite) {
                    $this->components->warn("Skipped: {$name}");

                    return 0;
                }
            }

            copy($dest, "{$dest}.pre-fortress.bak");
            $this->components->info("Backed up: {$name} → {$name}.pre-fortress.bak");
        }

        copy($source, $dest);
        chmod($dest, 0755);

        $label = $type === 'stubs' ? "{$name} (stub)" : $name;
        $this->components->info("Installed: {$label}");

        return 1;
    }

    private function uninstallHooks(): int
    {
        $hooksDir = base_path('.git/hooks');
        if (! is_dir($hooksDir)) {
            warning('No .git/hooks directory found.');

            return self::SUCCESS;
        }

        $removed = 0;
        $files = scandir($hooksDir) ?: [];

        foreach ($files as $file) {
            if ($file === '.' || $file === '..') {
                continue;
            }

            $path = "{$hooksDir}/{$file}";
            if (! is_file($path) || ! $this->isFortressHook($path)) {
                continue;
            }

            unlink($path);
            $this->components->info("Removed: {$file}");
            $removed++;

            // Restore backup
            $backup = "{$path}.pre-fortress.bak";
            if (file_exists($backup)) {
                rename($backup, $path);
                $this->components->info("Restored backup: {$file}");
            }
        }

        if ($removed === 0) {
            note('No fortress hooks found to remove.');
        } else {
            info("{$removed} hook(s) removed.");
        }

        return self::SUCCESS;
    }

    private function listHooks(): int
    {
        $hooksDir = base_path('.git/hooks');
        if (! is_dir($hooksDir)) {
            note('No .git/hooks directory found.');

            return self::SUCCESS;
        }

        $rows = [];
        $files = scandir($hooksDir) ?: [];

        foreach ($files as $file) {
            if ($file === '.' || $file === '..') {
                continue;
            }

            $path = "{$hooksDir}/{$file}";
            if (! is_file($path)) {
                continue;
            }

            if ($this->isFortressHook($path)) {
                $isStub = str_contains(file_get_contents($path) ?: '', '(stub)');
                $type = $isStub ? 'stub' : 'active';
                $desc = $this->activeHooks[$file] ?? $this->stubHooks[$file] ?? '—';
                $rows[] = [$file, $type, $desc];
            }
        }

        if (empty($rows)) {
            note('No fortress hooks installed.');

            return self::SUCCESS;
        }

        $this->table(['Hook', 'Type', 'Purpose'], $rows);

        // Check backups
        $backups = glob("{$hooksDir}/*.pre-fortress.bak") ?: [];
        if (count($backups) > 0) {
            note(count($backups).' backup(s) found (.pre-fortress.bak)');
        }

        return self::SUCCESS;
    }

    private function updateHooks(): int
    {
        info('Updating fortress hooks...');

        return $this->installHooks();
    }

    private function invalidAction(): int
    {
        $this->components->error("Invalid action: {$this->argument('action')}. Use: install, uninstall, list, or update.");

        return self::FAILURE;
    }

    private function isFortressHook(string $path): bool
    {
        $content = file_get_contents($path);

        return $content !== false && str_contains($content, '@fortress-hook');
    }

    private function packagePath(string $path): string
    {
        return dirname(__DIR__, 2).'/'.$path;
    }
}
