<?php

declare(strict_types=1);

namespace Fortress\Review\Checks\P05;

use Fortress\Review\BaseCheck;
use Fortress\Review\CheckResult;
use Fortress\Review\ReviewContext;

class MoneyComparisonCheck extends BaseCheck
{
    public function id(): string
    {
        return 'money_comparison';
    }

    public function ruleId(): string
    {
        return 'F-P05-015';
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
        return 'Detect loose comparisons on monetary values';
    }

    /** @return CheckResult[] */
    public function run(ReviewContext $context): array
    {
        $results = [];

        foreach ($context->phpFiles() as $file) {
            $path = $file->getRealPath();
            $content = $context->content($path);
            $relativePath = $this->fileRelativePath($path, $context->basePath());

            // == or != comparison on money variables
            $matches = $this->matchPattern($content, '/\$(?:amount|balance|total|price|cost)\s*(?:==|!=|<|>|<=|>=)\s*\$/');
            foreach ($matches as $match) {
                $results[] = $this->result(
                    $relativePath,
                    $match['line'],
                    'Direct comparison operators on monetary values may produce incorrect results with string or float representations.',
                    'Use `BigDecimal::isEqualTo()`, `isGreaterThan()`, `isLessThan()`, or `compareTo()` methods.',
                    $this->getSnippet($content, $match['line']),
                );
            }

            // Comparing money with == 0 or == '0'
            $matches = $this->matchPattern($content, '/\$(?:amount|balance|total)\s*==\s*(?:0|["\']0["\'])/');
            foreach ($matches as $match) {
                $results[] = $this->result(
                    $relativePath,
                    $match['line'],
                    'Loose comparison of monetary value with zero. String "0.00" == 0 is true but "0.00" === 0 is false.',
                    'Use `BigDecimal::isZero()` or `->compareTo(BigDecimal::zero()) === 0`.',
                    $this->getSnippet($content, $match['line']),
                );
            }
        }

        return $results;
    }
}
