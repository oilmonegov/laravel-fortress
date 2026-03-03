<?php

declare(strict_types=1);

namespace Fortress\Review\Checks\P06;

use Fortress\Review\BaseCheck;
use Fortress\Review\CheckResult;
use Fortress\Review\ReviewContext;

class StrictComparisonCheck extends BaseCheck
{
    public function id(): string
    {
        return 'strict_comparison';
    }

    public function ruleId(): string
    {
        return 'F-P06-010';
    }

    public function part(): string
    {
        return 'P06';
    }

    public function severity(): string
    {
        return 'warning';
    }

    public function description(): string
    {
        return 'Detect loose equality comparisons (== instead of ===)';
    }

    /** @return CheckResult[] */
    public function run(ReviewContext $context): array
    {
        $results = [];

        foreach ($context->phpFiles() as $file) {
            $path = $file->getRealPath();
            $content = $context->content($path);
            $relativePath = $this->fileRelativePath($path, $context->basePath());

            // Match == that isn't ===, ==>, or part of =>
            $matches = $this->matchPattern($content, '/(?<!=)(?<!!)==(?!=|>)(?!>)/');
            foreach ($matches as $match) {
                $line = explode("\n", $content)[$match['line'] - 1] ?? '';

                // Skip comments and strings that might contain ==
                if (preg_match('/^\s*(?:\/\/|\*|#)/', $line)) {
                    continue;
                }

                $results[] = $this->result(
                    $relativePath,
                    $match['line'],
                    'Loose equality comparison (`==`) may produce unexpected type coercion results.',
                    'Use strict comparison (`===`) to compare both value and type.',
                    $this->getSnippet($content, $match['line']),
                );
            }

            // != instead of !==
            $matches = $this->matchPattern($content, '/(?<!!)!=(?!=)/');
            foreach ($matches as $match) {
                $line = explode("\n", $content)[$match['line'] - 1] ?? '';
                if (preg_match('/^\s*(?:\/\/|\*|#)/', $line)) {
                    continue;
                }

                $results[] = $this->result(
                    $relativePath,
                    $match['line'],
                    'Loose inequality comparison (`!=`) may produce unexpected type coercion results.',
                    'Use strict comparison (`!==`) to compare both value and type.',
                    $this->getSnippet($content, $match['line']),
                );
            }
        }

        return $results;
    }
}
