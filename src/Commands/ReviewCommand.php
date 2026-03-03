<?php

declare(strict_types=1);

namespace Fortress\Commands;

use Fortress\Review\ReviewReport;
use Fortress\Review\ReviewRunner;
use Illuminate\Console\Command;

use function Laravel\Prompts\info;
use function Laravel\Prompts\multiselect;
use function Laravel\Prompts\note;
use function Laravel\Prompts\warning;

class ReviewCommand extends Command
{
    protected $signature = 'fortress:review
        {name? : Review name for the report filename}
        {--part=* : Limit review to specific parts (e.g., P01, P05)}
        {--severity= : Minimum severity threshold (critical|warning|info)}
        {--select : Interactively select which parts to review}
        {--format=markdown : Output format (markdown|console)}';

    protected $description = 'Run a deep-dive fortress code review with detailed findings and solutions';

    public function handle(): int
    {
        $this->newLine();
        $this->line('  <fg=blue;options=bold>[Fortress]</> Deep Code Review');
        $this->newLine();

        $basePath = (string) base_path();
        $runner = new ReviewRunner($basePath);

        // Handle part filtering
        $parts = $this->option('part');
        if ($this->option('select')) {
            $available = $runner->getAvailableParts();
            $options = [];
            foreach ($available as $code => $name) {
                $options[$code] = "{$code} — {$name}";
            }

            $parts = multiselect(
                label: 'Which parts would you like to review?',
                options: $options,
                default: array_keys($available),
                hint: 'Use space to toggle, enter to confirm.',
                required: 'Select at least one part.',
            );
        }

        if (! empty($parts)) {
            $runner->filterByParts($parts);
        }

        // Handle severity filter
        $severity = $this->option('severity');
        if ($severity) {
            if (! in_array($severity, ['critical', 'warning', 'info'], true)) {
                warning("Invalid severity: {$severity}. Use: critical, warning, or info.");

                return self::FAILURE;
            }
            $runner->filterBySeverity($severity);
        }

        $totalChecks = $runner->totalChecks();
        if ($totalChecks === 0) {
            warning('No checks match the selected filters.');

            return self::SUCCESS;
        }

        note("Running {$totalChecks} checks...");

        // Run with progress
        $progressBar = $this->output->createProgressBar($totalChecks);
        $progressBar->setFormat('  %current%/%max% [%bar%] %message%');
        $progressBar->setMessage('Starting...');
        $progressBar->start();

        $results = $runner->run(function ($check, $current, $total) use ($progressBar) {
            $progressBar->setMessage("[{$check->ruleId()}] {$check->description()}");
            $progressBar->advance();
        });

        $progressBar->finish();
        $this->newLine(2);

        // Count by severity
        $critical = count(array_filter($results, fn ($r) => $r->severity === 'critical'));
        $warnings = count(array_filter($results, fn ($r) => $r->severity === 'warning'));
        $infos = count(array_filter($results, fn ($r) => $r->severity === 'info'));
        $total = count($results);

        // Summary table
        $this->table(
            ['Severity', 'Count'],
            [
                ['<fg=red>Critical</>', (string) $critical],
                ['<fg=yellow>Warning</>', (string) $warnings],
                ['<fg=blue>Info</>', (string) $infos],
                ['<options=bold>Total</>', (string) $total],
            ],
        );

        // Generate report
        if ($this->option('format') === 'markdown' || ! $this->option('format')) {
            $reportPath = $this->saveReport($results, $parts ?: array_keys($runner->getAvailableParts()));

            if ($reportPath) {
                $this->newLine();
                info("Report saved to: {$reportPath}");
            }
        }

        // Console format: show findings inline
        if ($this->option('format') === 'console') {
            $this->renderConsoleOutput($results);
        }

        if ($total === 0) {
            $this->newLine();
            info('All checks passed. No findings.');
        }

        return $critical > 0 ? self::FAILURE : self::SUCCESS;
    }

    /**
     * @param  \Fortress\Review\CheckResult[]  $results
     * @param  string[]  $parts
     */
    private function saveReport(array $results, array $parts): ?string
    {
        $name = $this->argument('name') ?: 'review';
        $date = date('Y-m-d');
        $time = date('His');
        $id = substr(bin2hex(random_bytes(2)), 0, 4);

        $filename = "review-{$name}-{$date}-{$time}-{$id}.md";
        $dir = base_path('docs/fortress-reviews');

        if (! is_dir($dir)) {
            mkdir($dir, 0755, true);
        }

        $report = new ReviewReport;
        $markdown = $report->generate(
            $results,
            $name,
            $parts,
            date('Y-m-d H:i:s'),
        );

        $path = "{$dir}/{$filename}";
        file_put_contents($path, $markdown);

        return "docs/fortress-reviews/{$filename}";
    }

    /** @param \Fortress\Review\CheckResult[] $results */
    private function renderConsoleOutput(array $results): void
    {
        foreach ($results as $result) {
            $severityColor = match ($result->severity) {
                'critical' => 'red',
                'warning' => 'yellow',
                default => 'blue',
            };

            $location = $result->file;
            if ($result->line !== null) {
                $location .= ':'.$result->line;
            }

            $this->newLine();
            $this->line("  <fg={$severityColor};options=bold>[{$result->ruleId}]</> <fg=gray>{$location}</>");
            $this->line("  <fg=white>{$result->problem}</>");
            $this->line("  <fg=green>Fix:</> {$result->solution}");

            if ($result->snippet) {
                $this->newLine();
                foreach (explode("\n", $result->snippet) as $snippetLine) {
                    $this->line("    <fg=gray>{$snippetLine}</>");
                }
            }
        }
    }
}
