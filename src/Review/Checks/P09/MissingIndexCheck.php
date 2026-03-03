<?php

declare(strict_types=1);

namespace Fortress\Review\Checks\P09;

use Fortress\Review\BaseCheck;
use Fortress\Review\CheckResult;
use Fortress\Review\ReviewContext;

class MissingIndexCheck extends BaseCheck
{
    public function id(): string
    {
        return 'missing_index';
    }

    public function ruleId(): string
    {
        return 'F-P09-001';
    }

    public function part(): string
    {
        return 'P09';
    }

    public function severity(): string
    {
        return 'warning';
    }

    public function description(): string
    {
        return 'Detect foreign key columns without indexes in migrations';
    }

    /** @return CheckResult[] */
    public function run(ReviewContext $context): array
    {
        $results = [];

        foreach ($context->migrationFiles() as $file) {
            $path = $file->getRealPath();
            $content = $context->content($path);
            $relativePath = $this->fileRelativePath($path, $context->basePath());

            // Find columns ending in _id that don't have an index
            $matches = $this->matchPattern($content, '/->(?:unsignedBigInteger|unsignedInteger|uuid|foreignId|foreignUuid)\s*\(\s*["\'](\w+_id)["\']\s*\)/');
            foreach ($matches as $match) {
                // Check if the same column has an index or foreign key defined nearby
                if (preg_match('/\w+_id/', $match['match'], $colMatch)) {
                    $colName = $colMatch[0];
                    if (! str_contains($content, "->index('{$colName}')") && ! str_contains($content, "->index(\"{$colName}\")") && ! str_contains($content, 'constrained()') && ! str_contains($content, "foreign('{$colName}')") && ! str_contains($content, "foreign(\"{$colName}\")")) {
                        // foreignId/foreignUuid with constrained() auto-adds index, so only flag unsignedBigInteger/uuid
                        if (str_contains($match['match'], 'unsignedBigInteger') || str_contains($match['match'], 'unsignedInteger') || str_contains($match['match'], 'uuid')) {
                            $results[] = $this->result(
                                $relativePath,
                                $match['line'],
                                "Column `{$colName}` looks like a foreign key but has no index.",
                                "Add `->index()` after the column definition or use `->foreignId('{$colName}')->constrained()` which auto-creates the index.",
                                $this->getSnippet($content, $match['line']),
                            );
                        }
                    }
                }
            }
        }

        return $results;
    }
}
