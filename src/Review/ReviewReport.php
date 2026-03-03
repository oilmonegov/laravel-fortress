<?php

declare(strict_types=1);

namespace Fortress\Review;

class ReviewReport
{
    /** @var array<string, int> */
    private const SEVERITY_ORDER = [
        'critical' => 3,
        'warning' => 2,
        'info' => 1,
    ];

    /** @var array<string, string> */
    private const PART_NAMES = [
        'P01' => 'Part I — Application Security',
        'P02' => 'Part II — Cryptography',
        'P03' => 'Part III — Authentication & Authorization',
        'P04' => 'Part IV — Data Integrity',
        'P05' => 'Part V — Financial Accuracy',
        'P06' => 'Part VI — PHP Language',
        'P07' => 'Part VII — Clean Code',
        'P08' => 'Part VIII — Laravel Framework',
        'P09' => 'Part IX — Database',
        'P10' => 'Part X — Frontend',
        'P11' => 'Part XI — Testing',
        'P12' => 'Part XII — APIs & Queues',
        'P13' => 'Part XIII — Logging',
        'P14' => 'Part XIV — Infrastructure',
    ];

    /**
     * @param  CheckResult[]  $results
     * @param  string[]  $partsReviewed
     */
    public function generate(
        array $results,
        string $name,
        array $partsReviewed,
        string $date,
    ): string {
        $grouped = $this->groupResults($results);
        $counts = $this->countBySeverity($results);
        $totalFindings = count($results);

        $md = "# Fortress Review: {$name}\n\n";
        $md .= "**Date:** {$date}  \n";
        $md .= '**Parts reviewed:** '.implode(', ', $partsReviewed)."  \n";
        $md .= "**Total findings:** {$totalFindings}";

        if ($totalFindings > 0) {
            $parts = [];
            if (($counts['critical'] ?? 0) > 0) {
                $parts[] = "{$counts['critical']} critical";
            }
            if (($counts['warning'] ?? 0) > 0) {
                $parts[] = "{$counts['warning']} warning";
            }
            if (($counts['info'] ?? 0) > 0) {
                $parts[] = "{$counts['info']} info";
            }
            $md .= ' ('.implode(', ', $parts).')';
        }

        $md .= "\n\n";

        // Summary table
        $md .= "## Summary\n\n";
        $md .= "| Severity | Count |\n";
        $md .= "|----------|-------|\n";
        $md .= '| Critical | '.($counts['critical'] ?? 0)." |\n";
        $md .= '| Warning  | '.($counts['warning'] ?? 0)." |\n";
        $md .= '| Info     | '.($counts['info'] ?? 0)." |\n";
        $md .= "\n";

        if ($totalFindings === 0) {
            $md .= "No findings. All checks passed.\n";

            return $md;
        }

        // Findings grouped by part then severity
        foreach ($grouped as $part => $severities) {
            $partTitle = self::PART_NAMES[$part] ?? $part;
            $md .= "## {$partTitle}\n\n";

            foreach (['critical', 'warning', 'info'] as $severity) {
                if (! isset($severities[$severity]) || empty($severities[$severity])) {
                    continue;
                }

                $md .= '### '.ucfirst($severity)."\n\n";

                foreach ($severities[$severity] as $result) {
                    /** @var CheckResult $result */
                    $location = $result->file;
                    if ($result->line !== null) {
                        $location .= ':'.$result->line;
                    }

                    $md .= "#### [{$result->ruleId}] `{$location}`\n\n";
                    $md .= "**Problem:** {$result->problem}\n\n";

                    if ($result->snippet !== null) {
                        $md .= "```php\n{$result->snippet}\n```\n\n";
                    }

                    $md .= "**Solution:** {$result->solution}\n\n";
                    $md .= "---\n\n";
                }
            }
        }

        return $md;
    }

    /**
     * @param  CheckResult[]  $results
     * @return array<string, array<string, CheckResult[]>>
     */
    private function groupResults(array $results): array
    {
        $grouped = [];

        foreach ($results as $result) {
            $part = $this->extractPart($result->ruleId);
            $grouped[$part][$result->severity][] = $result;
        }

        // Sort parts
        ksort($grouped);

        // Sort findings within each severity by file then line
        foreach ($grouped as &$severities) {
            foreach ($severities as &$findings) {
                usort($findings, function (CheckResult $a, CheckResult $b) {
                    $fileCmp = strcmp($a->file, $b->file);
                    if ($fileCmp !== 0) {
                        return $fileCmp;
                    }

                    return ($a->line ?? 0) <=> ($b->line ?? 0);
                });
            }
        }

        return $grouped;
    }

    /**
     * @param  CheckResult[]  $results
     * @return array<string, int>
     */
    private function countBySeverity(array $results): array
    {
        $counts = ['critical' => 0, 'warning' => 0, 'info' => 0];

        foreach ($results as $result) {
            $severity = strtolower($result->severity);
            if (isset($counts[$severity])) {
                $counts[$severity]++;
            }
        }

        return $counts;
    }

    private function extractPart(string $ruleId): string
    {
        // F-P01-001 => P01
        if (preg_match('/F-(P\d+)-/', $ruleId, $m)) {
            return $m[1];
        }

        return 'P00';
    }
}
