<?php

declare(strict_types=1);

namespace Fortress\Review\Checks\P05;

use Fortress\Review\BaseCheck;
use Fortress\Review\CheckResult;
use Fortress\Review\ReviewContext;

class FloatingPointMoneyCheck extends BaseCheck
{
    public function id(): string
    {
        return 'floating_point_money';
    }

    public function ruleId(): string
    {
        return 'F-P05-001';
    }

    public function part(): string
    {
        return 'P05';
    }

    public function severity(): string
    {
        return 'critical';
    }

    public function description(): string
    {
        return 'Detect floating-point types used for monetary values';
    }

    /** @return CheckResult[] */
    public function run(ReviewContext $context): array
    {
        $results = [];

        // Check migrations for float/double money columns
        foreach ($context->migrationFiles() as $file) {
            $path = $file->getRealPath();
            $content = $context->content($path);
            $relativePath = $this->fileRelativePath($path, $context->basePath());

            $matches = $this->matchPattern($content, '/->(?:float|double)\s*\(\s*["\'](?:amount|balance|total|price|cost|rate|fee|commission|debit|credit)["\']/');
            foreach ($matches as $match) {
                $results[] = $this->result(
                    $relativePath,
                    $match['line'],
                    'Migration uses float/double type for a monetary column. Floating-point arithmetic causes rounding errors.',
                    'Use `->string()` (VARCHAR) with brick/math BigDecimal, or `->decimal(\'column\', 20, 8)` for fixed precision.',
                    $this->getSnippet($content, $match['line']),
                );
            }
        }

        // Check PHP code for float casts on money
        foreach ($context->phpFiles() as $file) {
            $path = $file->getRealPath();
            $content = $context->content($path);
            $relativePath = $this->fileRelativePath($path, $context->basePath());

            $matches = $this->matchPattern($content, '/\(float\)\s*\$(?:amount|balance|total|price|cost|money)/');
            foreach ($matches as $match) {
                $results[] = $this->result(
                    $relativePath,
                    $match['line'],
                    'Float cast on monetary variable causes precision loss.',
                    'Use `BigDecimal::of($value)` from brick/math for safe monetary arithmetic.',
                    $this->getSnippet($content, $match['line']),
                );
            }

            // Check model casts for float on money columns
            $matches = $this->matchPattern($content, '/["\'](?:amount|balance|total|price|cost|rate|fee)["\']\s*=>\s*["\'](?:float|double|decimal)["\']/');
            foreach ($matches as $match) {
                $results[] = $this->result(
                    $relativePath,
                    $match['line'],
                    'Model casts monetary column to float/double which causes precision loss.',
                    'Use a MoneyCast or custom cast that preserves precision with string storage and BigDecimal.',
                    $this->getSnippet($content, $match['line']),
                );
            }
        }

        return $results;
    }
}
