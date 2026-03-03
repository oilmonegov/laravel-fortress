<?php

declare(strict_types=1);

namespace Fortress\Review\Checks\P05;

use Fortress\Review\BaseCheck;
use Fortress\Review\CheckResult;
use Fortress\Review\ReviewContext;

class RoundingModeCheck extends BaseCheck
{
    public function id(): string
    {
        return 'rounding_mode';
    }

    public function ruleId(): string
    {
        return 'F-P05-010';
    }

    public function part(): string
    {
        return 'P05';
    }

    public function severity(): string
    {
        return 'warning';
    }

    public function description(): string
    {
        return 'Detect division and rounding operations without explicit RoundingMode';
    }

    /** @return CheckResult[] */
    public function run(ReviewContext $context): array
    {
        $results = [];

        foreach ($context->phpFiles() as $file) {
            $path = $file->getRealPath();
            $content = $context->content($path);
            $relativePath = $this->fileRelativePath($path, $context->basePath());

            // BigDecimal::dividedBy without RoundingMode
            $matches = $this->matchPattern($content, '/->dividedBy\s*\([^)]+\)\s*(?!.*RoundingMode)/');
            foreach ($matches as $match) {
                $line = explode("\n", $content)[$match['line'] - 1] ?? '';
                if (! str_contains($line, 'RoundingMode')) {
                    $results[] = $this->result(
                        $relativePath,
                        $match['line'],
                        'BigDecimal division without explicit RoundingMode may throw or produce unexpected results.',
                        'Specify a RoundingMode: `->dividedBy($divisor, $scale, RoundingMode::HalfUp)`.',
                        $this->getSnippet($content, $match['line']),
                    );
                }
            }

            // round() on potential money values
            $matches = $this->matchPattern($content, '/\bround\s*\(\s*\$(?:amount|balance|total|price|cost|rate)/');
            foreach ($matches as $match) {
                $results[] = $this->result(
                    $relativePath,
                    $match['line'],
                    'PHP `round()` uses banker\'s rounding by default which may not match business requirements.',
                    'Use `BigDecimal::toScale($precision, RoundingMode::HalfUp)` for explicit, predictable rounding.',
                    $this->getSnippet($content, $match['line']),
                );
            }
        }

        return $results;
    }
}
