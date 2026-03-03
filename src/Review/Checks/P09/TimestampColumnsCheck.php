<?php

declare(strict_types=1);

namespace Fortress\Review\Checks\P09;

use Fortress\Review\BaseCheck;
use Fortress\Review\CheckResult;
use Fortress\Review\ReviewContext;

class TimestampColumnsCheck extends BaseCheck
{
    public function id(): string
    {
        return 'timestamp_columns';
    }

    public function ruleId(): string
    {
        return 'F-P09-020';
    }

    public function part(): string
    {
        return 'P09';
    }

    public function severity(): string
    {
        return 'info';
    }

    public function description(): string
    {
        return 'Detect create_table migrations without timestamps()';
    }

    /** @return CheckResult[] */
    public function run(ReviewContext $context): array
    {
        $results = [];

        foreach ($context->migrationFiles() as $file) {
            $path = $file->getRealPath();
            $content = $context->content($path);
            $relativePath = $this->fileRelativePath($path, $context->basePath());

            // Only check create table migrations
            if (! str_contains($content, 'Schema::create')) {
                continue;
            }

            if (! str_contains($content, 'timestamps()') && ! str_contains($content, 'timestamp(') && ! str_contains($content, 'created_at') && ! str_contains($content, 'nullableTimestamps')) {
                $results[] = $this->result(
                    $relativePath,
                    null,
                    'Create table migration does not include `timestamps()`.',
                    'Add `$table->timestamps()` to include `created_at` and `updated_at` columns for audit tracking.',
                );
            }
        }

        return $results;
    }
}
