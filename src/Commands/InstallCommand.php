<?php

declare(strict_types=1);

namespace Fortress\Commands;

use Illuminate\Console\Command;

use function Laravel\Prompts\confirm;
use function Laravel\Prompts\info;
use function Laravel\Prompts\multiselect;
use function Laravel\Prompts\note;
use function Laravel\Prompts\warning;

class InstallCommand extends Command
{
    protected $signature = 'fortress:install
        {--hooks : Install git hooks only}
        {--rules : Install AI rules only}
        {--ci : Install CI/CD workflow only}
        {--all : Install everything without prompting}
        {--force : Overwrite existing files}';

    protected $description = 'Install Laravel Fortress rules, git hooks, and CI/CD workflows';

    public function handle(): int
    {
        $this->printBanner();

        if ($this->option('all')) {
            $components = ['rules', 'hooks', 'ci', 'config'];
        } elseif ($this->option('hooks') || $this->option('rules') || $this->option('ci')) {
            $components = array_filter([
                $this->option('rules') ? 'rules' : null,
                $this->option('hooks') ? 'hooks' : null,
                $this->option('ci') ? 'ci' : null,
            ]);
        } else {
            $components = multiselect(
                label: 'What would you like to install?',
                options: [
                    'rules' => 'AI Rules — Editor-specific rule files (Claude Code, Cursor, Windsurf, Copilot)',
                    'hooks' => 'Git Hooks — 10 active hooks for development safety',
                    'ci' => 'CI/CD — GitHub Actions PR protection workflow',
                    'config' => 'Config — .fortress.yml configuration file',
                ],
                default: ['rules', 'hooks', 'config'],
                hint: 'Use space to toggle, enter to confirm.',
                required: 'Select at least one component to install.',
            );
        }

        $force = (bool) $this->option('force');

        if (in_array('config', $components)) {
            $this->installConfig($force);
        }

        if (in_array('rules', $components)) {
            $this->installRules($force);
        }

        if (in_array('hooks', $components)) {
            $this->call('fortress:hooks', [
                'action' => 'install',
                '--force' => $force,
            ]);
        }

        if (in_array('ci', $components)) {
            $this->installCi($force);
        }

        $this->newLine();
        info('Fortress installation complete.');
        $this->printNextSteps($components);

        return self::SUCCESS;
    }

    private function printBanner(): void
    {
        $this->newLine();
        $this->line('  <fg=blue>╔══════════════════════════════════════╗</>');
        $this->line('  <fg=blue>║</>    <fg=white;options=bold>The Laravel Fortress Installer</> <fg=blue>  ║</>');
        $this->line('  <fg=blue>║</>  1,755 checks · 200 sections · v1.1<fg=blue> ║</>');
        $this->line('  <fg=blue>╚══════════════════════════════════════╝</>');
        $this->newLine();
    }

    private function installConfig(bool $force): void
    {
        $dest = base_path('.fortress.yml');

        if (file_exists($dest) && ! $force) {
            warning('Existing .fortress.yml found — skipped (use --force to overwrite).');

            return;
        }

        $source = $this->packagePath('rules/.fortress.example.yml');
        if (! file_exists($source)) {
            $this->components->error('Source config not found: ' . $source);

            return;
        }

        copy($source, $dest);
        $this->components->info('Created .fortress.yml');
    }

    private function installRules(bool $force): void
    {
        $detectedEditors = $this->detectEditors();

        if (empty($detectedEditors)) {
            $editors = multiselect(
                label: 'No editors detected. Which rule files would you like to install?',
                options: [
                    'claude' => 'Claude Code — CLAUDE.md + 14 skill files',
                    'cursor' => 'Cursor — .cursorrules',
                    'windsurf' => 'Windsurf — .windsurfrules',
                    'copilot' => 'GitHub Copilot — .github/copilot-instructions.md',
                ],
                hint: 'Select the editors you use.',
            );
        } else {
            $editors = $detectedEditors;
            note('Detected editors: ' . implode(', ', array_map('ucfirst', $editors)));
        }

        foreach ($editors as $editor) {
            match ($editor) {
                'claude' => $this->installClaudeRules($force),
                'cursor' => $this->installEditorFile('.cursorrules', 'rules/editors/.cursorrules', $force),
                'windsurf' => $this->installEditorFile('.windsurfrules', 'rules/editors/.windsurfrules', $force),
                'copilot' => $this->installCopilotRules($force),
                default => null,
            };
        }
    }

    private function installClaudeRules(bool $force): void
    {
        $skillsDir = base_path('.claude/skills');
        $skills = [
            'fortress-security', 'fortress-crypto', 'fortress-auth', 'fortress-concurrency',
            'fortress-financial', 'fortress-php', 'fortress-clean-code', 'fortress-laravel',
            'fortress-database', 'fortress-frontend', 'fortress-testing', 'fortress-apis',
            'fortress-logging', 'fortress-infrastructure',
        ];

        @mkdir($skillsDir, 0755, true);

        foreach ($skills as $skill) {
            $source = $this->packagePath("rules/skills/{$skill}/SKILL.md");
            $dest = "{$skillsDir}/{$skill}/SKILL.md";
            @mkdir(dirname($dest), 0755, true);

            if (file_exists($source)) {
                copy($source, $dest);
            }
        }

        $this->components->info('Installed 14 Claude Code skill files');

        $claudeMd = base_path('CLAUDE.md');
        if (file_exists($claudeMd) && ! $force) {
            warning('Existing CLAUDE.md found — merge rules/editors/CLAUDE.md manually.');
        } else {
            $source = $this->packagePath('rules/editors/CLAUDE.md');
            if (file_exists($source)) {
                copy($source, $claudeMd);
                $this->components->info('Created CLAUDE.md');
            }
        }
    }

    private function installEditorFile(string $filename, string $sourcePath, bool $force): void
    {
        $dest = base_path($filename);

        if (file_exists($dest) && ! $force) {
            if (confirm("Existing {$filename} found. Overwrite?", default: false)) {
                copy($dest, "{$dest}.bak");
                $this->components->info("Backed up {$filename} to {$filename}.bak");
            } else {
                warning("Skipped {$filename}.");

                return;
            }
        }

        $source = $this->packagePath($sourcePath);
        if (file_exists($source)) {
            copy($source, $dest);
            $this->components->info("Installed {$filename}");
        }
    }

    private function installCopilotRules(bool $force): void
    {
        $dest = base_path('.github/copilot-instructions.md');
        @mkdir(dirname($dest), 0755, true);

        if (file_exists($dest) && ! $force) {
            warning('Existing copilot-instructions.md found — skipped.');

            return;
        }

        $source = $this->packagePath('rules/editors/copilot-instructions.md');
        if (file_exists($source)) {
            copy($source, $dest);
            $this->components->info('Installed .github/copilot-instructions.md');
        }
    }

    private function installCi(bool $force): void
    {
        $dest = base_path('.github/workflows/fortress-pr-protection.yml');
        @mkdir(dirname($dest), 0755, true);

        if (file_exists($dest) && ! $force) {
            warning('Existing fortress-pr-protection.yml found — skipped.');

            return;
        }

        $source = $this->packagePath('.github/workflows/pr-protection.yml');
        if (file_exists($source)) {
            copy($source, $dest);
            $this->components->info('Installed GitHub Actions PR protection workflow');
            note('Configure branch protection rules on GitHub to require the "Fortress Quality Gates" and "Merge Protection" checks.');
        }
    }

    private function detectEditors(): array
    {
        $editors = [];

        if (is_dir(base_path('.claude'))) {
            $editors[] = 'claude';
        }

        if (file_exists(base_path('.cursorrules')) || is_dir(base_path('.cursor'))) {
            $editors[] = 'cursor';
        }

        if (file_exists(base_path('.windsurfrules'))) {
            $editors[] = 'windsurf';
        }

        if (is_dir(base_path('.github'))) {
            $editors[] = 'copilot';
        }

        return $editors;
    }

    private function printNextSteps(array $components): void
    {
        $this->newLine();
        $this->line('  <fg=white;options=bold>Next steps:</>');

        if (in_array('config', $components)) {
            $this->line('    1. Edit <fg=cyan>.fortress.yml</> to configure parts and enforcement levels');
        }

        if (in_array('hooks', $components)) {
            $this->line('    2. Test hooks: <fg=cyan>git commit --allow-empty -m "test: fortress check"</>');
        }

        if (in_array('ci', $components)) {
            $this->line('    3. Enable branch protection rules on GitHub');
        }

        $this->line('    4. Add <fg=cyan>.fortress.yml</> to version control');
        $this->newLine();
    }

    private function packagePath(string $path): string
    {
        return dirname(__DIR__, 2) . '/' . $path;
    }
}
