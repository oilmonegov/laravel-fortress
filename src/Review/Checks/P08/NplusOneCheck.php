<?php

declare(strict_types=1);

namespace Fortress\Review\Checks\P08;

use Fortress\Review\BaseCheck;
use Fortress\Review\CheckResult;
use Fortress\Review\ReviewContext;

class NplusOneCheck extends BaseCheck
{
    public function id(): string
    {
        return 'n_plus_one';
    }

    public function ruleId(): string
    {
        return 'F-P08-050';
    }

    public function part(): string
    {
        return 'P08';
    }

    public function severity(): string
    {
        return 'warning';
    }

    public function description(): string
    {
        return 'Detect potential N+1 query problems from relationship access in loops';
    }

    /** @return CheckResult[] */
    public function run(ReviewContext $context): array
    {
        $results = [];

        // Scan Blade files for relationship access in loops
        foreach ($context->bladeFiles() as $file) {
            $path = $file->getRealPath();
            $content = $context->content($path);
            $relativePath = $this->fileRelativePath($path, $context->basePath());

            // @foreach with nested relationship access
            if (preg_match_all('/@foreach\s*\(\s*\$\w+\s+as\s+\$(\w+)\s*\)/', $content, $foreachMatches, PREG_OFFSET_CAPTURE)) {
                foreach ($foreachMatches[1] as $index => $foreachMatch) {
                    $varName = $foreachMatch[0];
                    $startPos = $foreachMatch[1];
                    $afterForeach = substr($content, $startPos, 2000);

                    // Check for relationship access within the loop
                    if (preg_match('/\$'.preg_quote($varName, '/').'->(\w+)->/', $afterForeach, $relMatch)) {
                        $lineNumber = substr_count(substr($content, 0, $startPos), "\n") + 1;
                        $results[] = $this->result(
                            $relativePath,
                            $lineNumber,
                            "Potential N+1 query: `\${$varName}->{$relMatch[1]}` accessed inside a loop without eager loading.",
                            "Add `->with('{$relMatch[1]}')` to the query that loads the collection.",
                            $this->getSnippet($content, $lineNumber),
                        );
                    }
                }
            }
        }

        // Scan PHP files for foreach with relationship access
        foreach ($context->phpFiles() as $file) {
            $path = $file->getRealPath();
            $content = $context->content($path);
            $relativePath = $this->fileRelativePath($path, $context->basePath());

            if (preg_match('/(?:tests|migrations|seeders)[\/\\\\]/', $relativePath)) {
                continue;
            }

            $matches = $this->matchPattern($content, '/foreach\s*\(\s*\$\w+\s+as\s+\$(\w+)\)/');
            foreach ($matches as $match) {
                if (preg_match('/\$(\w+)/', $match['match'], $varMatch)) {
                    $lines = explode("\n", $content);
                    $loopBody = implode("\n", array_slice($lines, $match['line'] - 1, 20));
                    $varName = $varMatch[1];

                    if (preg_match('/\$'.preg_quote($varName, '/').'->(\w+)\s*(?:->|;|\))/', $loopBody, $relMatch)) {
                        // Check if this looks like a relationship (not a simple property)
                        $relName = $relMatch[1];
                        if (preg_match('/^[a-z]/', $relName) && ! in_array($relName, ['id', 'name', 'email', 'status', 'type', 'created_at', 'updated_at', 'deleted_at'], true)) {
                            // Heuristic: skip if ->with() is found earlier
                            $beforeLoop = implode("\n", array_slice($lines, max(0, $match['line'] - 10), 10));
                            if (! str_contains($beforeLoop, "with('{$relName}')") && ! str_contains($beforeLoop, "with(\"{$relName}\")") && ! str_contains($beforeLoop, "with(['{$relName}')")) {
                                // Too noisy for general use; skip this heuristic in PHP files
                                // Only flag in blade files above
                            }
                        }
                    }
                }
            }
        }

        return $results;
    }
}
