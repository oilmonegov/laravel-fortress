<?php

declare(strict_types=1);

namespace Fortress\Review\Checks\P04;

use Fortress\Review\BaseCheck;
use Fortress\Review\CheckResult;
use Fortress\Review\ReviewContext;

class RaceConditionCheck extends BaseCheck
{
    public function id(): string
    {
        return 'race_condition';
    }

    public function ruleId(): string
    {
        return 'F-P04-005';
    }

    public function part(): string
    {
        return 'P04';
    }

    public function severity(): string
    {
        return 'warning';
    }

    public function description(): string
    {
        return 'Detect read-then-write patterns that may be vulnerable to race conditions';
    }

    /** @return CheckResult[] */
    public function run(ReviewContext $context): array
    {
        $results = [];

        foreach ($context->phpFiles() as $file) {
            $path = $file->getRealPath();
            $content = $context->content($path);
            $relativePath = $this->fileRelativePath($path, $context->basePath());

            if (preg_match('/(?:migrations|seeders|tests)[\/\\\\]/', $relativePath)) {
                continue;
            }

            // findOrFail followed by update without locking
            $matches = $this->matchPattern($content, '/(?:findOrFail|find)\s*\([^)]+\)\s*;/');
            foreach ($matches as $match) {
                $lines = explode("\n", $content);
                $nextLines = implode("\n", array_slice($lines, $match['line'] - 1, 5));

                if (preg_match('/->(?:update|save|increment|decrement)\s*\(/', $nextLines)
                    && ! str_contains($nextLines, 'lockForUpdate')
                    && ! str_contains($nextLines, 'sharedLock')) {
                    $results[] = $this->result(
                        $relativePath,
                        $match['line'],
                        'Read-then-write pattern without database locking may cause race conditions.',
                        'Use `->lockForUpdate()` on the query: `Model::where(...)->lockForUpdate()->first()`.',
                        $this->getSnippet($content, $match['line']),
                    );
                }
            }
        }

        return $results;
    }
}
