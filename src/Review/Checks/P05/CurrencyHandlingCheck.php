<?php

declare(strict_types=1);

namespace Fortress\Review\Checks\P05;

use Fortress\Review\BaseCheck;
use Fortress\Review\CheckResult;
use Fortress\Review\ReviewContext;

class CurrencyHandlingCheck extends BaseCheck
{
    public function id(): string
    {
        return 'currency_handling';
    }

    public function ruleId(): string
    {
        return 'F-P05-005';
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
        return 'Detect money operations without explicit currency pairing';
    }

    /** @return CheckResult[] */
    public function run(ReviewContext $context): array
    {
        $results = [];

        foreach ($context->phpFiles() as $file) {
            $path = $file->getRealPath();
            $content = $context->content($path);
            $relativePath = $this->fileRelativePath($path, $context->basePath());

            if (preg_match('/(?:migrations|seeders|tests|config)[\/\\\\]/', $relativePath)) {
                continue;
            }

            // number_format on potential money values
            $matches = $this->matchPattern($content, '/number_format\s*\(\s*\$(?:amount|balance|total|price|cost)/');
            foreach ($matches as $match) {
                $results[] = $this->result(
                    $relativePath,
                    $match['line'],
                    '`number_format()` used on monetary value without explicit currency context.',
                    'Use a Money value object that pairs amount with currency, then format via the currency\'s rules (decimal places, symbol).',
                    $this->getSnippet($content, $match['line']),
                );
            }

            // Arithmetic on money without currency check
            $matches = $this->matchPattern($content, '/\$(?:amount|total|balance)\s*[\+\-\*\/]\s*\$(?:amount|total|balance)/');
            foreach ($matches as $match) {
                $results[] = $this->result(
                    $relativePath,
                    $match['line'],
                    'Direct arithmetic on monetary variables without currency validation.',
                    'Use BigDecimal arithmetic methods and verify currencies match before operations: `$a->currency === $b->currency`.',
                    $this->getSnippet($content, $match['line']),
                );
            }
        }

        return $results;
    }
}
