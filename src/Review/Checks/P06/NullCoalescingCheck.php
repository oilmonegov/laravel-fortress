<?php

declare(strict_types=1);

namespace Fortress\Review\Checks\P06;

use Fortress\Review\BaseCheck;
use Fortress\Review\CheckResult;
use Fortress\Review\ReviewContext;

class NullCoalescingCheck extends BaseCheck
{
    public function id(): string
    {
        return 'null_coalescing';
    }

    public function ruleId(): string
    {
        return 'F-P06-015';
    }

    public function part(): string
    {
        return 'P06';
    }

    public function severity(): string
    {
        return 'info';
    }

    public function description(): string
    {
        return 'Detect verbose null checks that could use null coalescing operator';
    }

    /** @return CheckResult[] */
    public function run(ReviewContext $context): array
    {
        $results = [];

        foreach ($context->phpFiles() as $file) {
            $path = $file->getRealPath();
            $content = $context->content($path);
            $relativePath = $this->fileRelativePath($path, $context->basePath());

            // isset($x) ? $x : $y pattern
            $matches = $this->matchPattern($content, '/isset\s*\(\s*(\$\w+)\s*\)\s*\?\s*\1\s*:/');
            foreach ($matches as $match) {
                $results[] = $this->result(
                    $relativePath,
                    $match['line'],
                    'Verbose `isset()` ternary can be simplified with null coalescing operator.',
                    'Replace `isset($x) ? $x : $y` with `$x ?? $y`.',
                    $this->getSnippet($content, $match['line']),
                );
            }

            // array_key_exists + ternary pattern
            $matches = $this->matchPattern($content, '/array_key_exists\s*\(\s*["\'][^"\']+["\']\s*,\s*\$\w+\s*\)\s*\?\s*\$\w+\[/');
            foreach ($matches as $match) {
                $results[] = $this->result(
                    $relativePath,
                    $match['line'],
                    'Verbose `array_key_exists()` ternary can be simplified.',
                    'Use null coalescing: `$array[\'key\'] ?? $default`.',
                    $this->getSnippet($content, $match['line']),
                );
            }
        }

        return $results;
    }
}
