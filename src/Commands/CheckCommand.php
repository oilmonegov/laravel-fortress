<?php

declare(strict_types=1);

namespace Fortress\Commands;

use Illuminate\Console\Command;
use Symfony\Component\Finder\Finder;

use function Laravel\Prompts\confirm;
use function Laravel\Prompts\info;
use function Laravel\Prompts\select;
use function Laravel\Prompts\warning;

class CheckCommand extends Command
{
    protected $signature = 'fortress:check
        {--fix : Auto-fix issues where possible}
        {--part= : Scan specific part (e.g., P01, P06)}
        {--select : Interactively select which checks to run}';

    protected $description = 'Run a quick fortress compliance scan on your codebase';

    /** @var array<string, array{rule: string, description: string}> */
    private array $checks = [
        'strict_types' => ['rule' => 'F-P06-001', 'description' => 'declare(strict_types=1) in PHP files'],
        'debug_statements' => ['rule' => 'F-P07-012', 'description' => 'No debug statements (dd, dump, ray, var_dump)'],
        'env_tracked' => ['rule' => 'F-P01-015', 'description' => '.env not tracked in git'],
        'fillable' => ['rule' => 'F-P08-003', 'description' => 'Models define $fillable'],
        'raw_env' => ['rule' => 'F-P08-041', 'description' => 'No env() outside config files'],
        'mass_assignment' => ['rule' => 'F-P01-010', 'description' => 'No unguarded mass assignment'],
    ];

    public function handle(): int
    {
        $this->newLine();
        $this->line('  <fg=blue;options=bold>[Fortress]</> Compliance Scan');
        $this->newLine();

        $fix = (bool) $this->option('fix');
        $part = $this->option('part');

        // Filter checks by part if specified
        $checksToRun = $this->checks;
        if ($part) {
            $partPrefix = strtoupper($part);
            $checksToRun = array_filter($checksToRun, function ($check) use ($partPrefix) {
                return str_starts_with($check['rule'], "F-{$partPrefix}-");
            });

            if (empty($checksToRun)) {
                warning("No checks found for part: {$part}");

                return self::SUCCESS;
            }
        }

        // Interactive selection
        if ($this->option('select')) {
            $options = [];
            foreach ($checksToRun as $key => $check) {
                $options[$key] = "[{$check['rule']}] {$check['description']}";
            }

            $selected = select(
                label: 'Which check would you like to run?',
                options: $options,
            );

            $checksToRun = [$selected => $checksToRun[$selected]];
        }

        $totalIssues = 0;
        $totalFixed = 0;

        foreach ($checksToRun as $check => $meta) {
            $result = match ($check) {
                'strict_types' => $this->checkStrictTypes($fix),
                'debug_statements' => $this->checkDebugStatements(),
                'env_tracked' => $this->checkEnvTracked(),
                'fillable' => $this->checkFillable(),
                'raw_env' => $this->checkRawEnv(),
                'mass_assignment' => $this->checkMassAssignment(),
                default => ['issues' => 0, 'fixed' => 0],
            };

            $totalIssues += $result['issues'];
            $totalFixed += $result['fixed'];
        }

        // Summary
        $this->newLine();
        $this->line('  ────────────────────────────────────');

        if ($totalIssues === 0) {
            info('All checks passed.');
        } else {
            $remaining = $totalIssues - $totalFixed;
            warning("{$totalIssues} issue(s) found".($totalFixed > 0 ? ", {$totalFixed} auto-fixed, {$remaining} remaining" : '').'.');

            if (! $fix && $totalIssues > 0) {
                $this->newLine();

                if (confirm(label: 'Run again with --fix to auto-fix where possible?', default: false)) {
                    return $this->call('fortress:check', ['--fix' => true]);
                }
            }
        }

        return $totalIssues - $totalFixed > 0 ? self::FAILURE : self::SUCCESS;
    }

    /**
     * @return array{issues: int, fixed: int}
     */
    private function checkStrictTypes(bool $fix): array
    {
        $issues = 0;
        $fixed = 0;

        $files = $this->findPhpFiles();
        $missing = [];

        foreach ($files as $file) {
            $path = $file->getRealPath();
            if ($path === false) {
                continue;
            }

            $content = file_get_contents($path);
            if ($content === false) {
                continue;
            }

            // Skip files that are not proper PHP (blade templates, etc.)
            if (! str_starts_with(trim($content), '<?php')) {
                continue;
            }

            if (! str_contains($content, 'declare(strict_types=1)')) {
                $issues++;
                $missing[] = $file->getRelativePathname();

                if ($fix) {
                    $content = preg_replace(
                        '/^<\?php\s*\n/',
                        "<?php\n\ndeclare(strict_types=1);\n",
                        $content,
                        1,
                        $count,
                    );

                    if ($count > 0 && $content !== null) {
                        file_put_contents($path, $content);
                        $fixed++;
                    }
                }
            }
        }

        $rule = $this->checks['strict_types']['rule'];

        if ($issues === 0) {
            $this->components->info("[{$rule}] All PHP files have strict_types");
        } else {
            $label = $fix ? "{$fixed}/{$issues} fixed" : "{$issues} file(s)";
            $this->components->warn("[{$rule}] Missing strict_types: {$label}");

            if ($issues <= 10) {
                foreach ($missing as $file) {
                    $this->line("         <fg=gray>{$file}</>");
                }
            }
        }

        return ['issues' => $issues, 'fixed' => $fixed];
    }

    /**
     * @return array{issues: int, fixed: int}
     */
    private function checkDebugStatements(): array
    {
        $issues = 0;
        $files = $this->findPhpFiles();
        $found = [];

        foreach ($files as $file) {
            $content = file_get_contents($file->getRealPath());
            if ($content === false) {
                continue;
            }

            if (preg_match_all('/\b(dd|dump|ray|var_dump|print_r)\s*\(/', $content, $matches)) {
                $issues += count($matches[0]);
                $found[] = $file->getRelativePathname().' ('.implode(', ', array_unique($matches[1])).')';
            }
        }

        $rule = $this->checks['debug_statements']['rule'];

        if ($issues === 0) {
            $this->components->info("[{$rule}] No debug statements found");
        } else {
            $this->components->warn("[{$rule}] Debug statements found: {$issues} occurrence(s)");
            foreach (array_slice($found, 0, 10) as $file) {
                $this->line("         <fg=gray>{$file}</>");
            }
        }

        return ['issues' => $issues, 'fixed' => 0];
    }

    /**
     * @return array{issues: int, fixed: int}
     */
    private function checkEnvTracked(): array
    {
        $root = base_path();
        $issues = 0;

        // Check if .env is tracked by git
        exec("cd {$root} && git ls-files .env 2>/dev/null", $output);

        if (! empty($output)) {
            $issues++;
            $this->components->error("[{$this->checks['env_tracked']['rule']}] .env is tracked in git!");
            $this->line('         <fg=gray>Add .env to .gitignore and run: git rm --cached .env</>');
        } else {
            $this->components->info("[{$this->checks['env_tracked']['rule']}] .env is not tracked in git");
        }

        return ['issues' => $issues, 'fixed' => 0];
    }

    /**
     * @return array{issues: int, fixed: int}
     */
    private function checkFillable(): array
    {
        $issues = 0;
        $modelsDir = base_path('app/Models');
        $missing = [];

        if (! is_dir($modelsDir)) {
            return ['issues' => 0, 'fixed' => 0];
        }

        $finder = (new Finder)->files()->name('*.php')->in($modelsDir);

        foreach ($finder as $file) {
            $content = file_get_contents($file->getRealPath());
            if ($content === false) {
                continue;
            }

            // Skip if it's not an Eloquent model (simple heuristic)
            if (! str_contains($content, 'extends Model') && ! str_contains($content, 'extends Authenticatable')) {
                continue;
            }

            if (! str_contains($content, '$fillable') && ! str_contains($content, '$guarded')) {
                $issues++;
                $missing[] = $file->getRelativePathname();
            }
        }

        $rule = $this->checks['fillable']['rule'];

        if ($issues === 0) {
            $this->components->info("[{$rule}] All models define \$fillable or \$guarded");
        } else {
            $this->components->warn("[{$rule}] Missing \$fillable: {$issues} model(s)");
            foreach ($missing as $file) {
                $this->line("         <fg=gray>{$file}</>");
            }
        }

        return ['issues' => $issues, 'fixed' => 0];
    }

    /**
     * @return array{issues: int, fixed: int}
     */
    private function checkRawEnv(): array
    {
        $issues = 0;
        $found = [];

        $files = $this->findPhpFiles();

        foreach ($files as $file) {
            // Skip config directory — env() is expected there
            if (str_starts_with($file->getRelativePathname(), 'config/') || str_starts_with($file->getRelativePathname(), 'config\\')) {
                continue;
            }

            $content = file_get_contents($file->getRealPath());
            if ($content === false) {
                continue;
            }

            if (preg_match_all('/\benv\s*\(/', $content, $matches)) {
                $count = count($matches[0]);
                $issues += $count;
                $found[] = "{$file->getRelativePathname()} ({$count})";
            }
        }

        $rule = $this->checks['raw_env']['rule'];

        if ($issues === 0) {
            $this->components->info("[{$rule}] No env() calls outside config/");
        } else {
            $this->components->warn("[{$rule}] env() outside config: {$issues} occurrence(s)");
            foreach (array_slice($found, 0, 10) as $file) {
                $this->line("         <fg=gray>{$file}</>");
            }
        }

        return ['issues' => $issues, 'fixed' => 0];
    }

    /**
     * @return array{issues: int, fixed: int}
     */
    private function checkMassAssignment(): array
    {
        $issues = 0;
        $found = [];

        $files = $this->findPhpFiles();

        foreach ($files as $file) {
            $content = file_get_contents($file->getRealPath());
            if ($content === false) {
                continue;
            }

            // Check for Model::unguard() or Eloquent::unguard()
            if (preg_match_all('/\b(Model|Eloquent)::unguard\s*\(/', $content, $matches)) {
                $issues += count($matches[0]);
                $found[] = $file->getRelativePathname();
            }
        }

        $rule = $this->checks['mass_assignment']['rule'];

        if ($issues === 0) {
            $this->components->info("[{$rule}] No unguarded mass assignment");
        } else {
            $this->components->warn("[{$rule}] Unguarded mass assignment: {$issues} occurrence(s)");
            foreach ($found as $file) {
                $this->line("         <fg=gray>{$file}</>");
            }
        }

        return ['issues' => $issues, 'fixed' => 0];
    }

    private function findPhpFiles(): Finder
    {
        $dirs = array_filter([
            base_path('app'),
            base_path('config'),
            base_path('database'),
            base_path('routes'),
        ], 'is_dir');

        return (new Finder)->files()->name('*.php')->in($dirs)->notName('*.blade.php');
    }
}
