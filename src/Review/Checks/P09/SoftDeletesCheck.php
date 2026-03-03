<?php

declare(strict_types=1);

namespace Fortress\Review\Checks\P09;

use Fortress\Review\BaseCheck;
use Fortress\Review\CheckResult;
use Fortress\Review\ReviewContext;

class SoftDeletesCheck extends BaseCheck
{
    public function id(): string
    {
        return 'soft_deletes';
    }

    public function ruleId(): string
    {
        return 'F-P09-010';
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
        return 'Detect Eloquent models without SoftDeletes trait';
    }

    /** @return CheckResult[] */
    public function run(ReviewContext $context): array
    {
        $results = [];

        foreach ($context->modelFiles() as $file) {
            $path = $file->getRealPath();
            $content = $context->content($path);
            $relativePath = $this->fileRelativePath($path, $context->basePath());

            if (! str_contains($content, 'extends Model') && ! str_contains($content, 'extends Authenticatable')) {
                continue;
            }

            // Skip Pivot models
            if (str_contains($content, 'extends Pivot') || str_contains($content, 'extends MorphPivot')) {
                continue;
            }

            if (! str_contains($content, 'SoftDeletes')) {
                $results[] = $this->result(
                    $relativePath,
                    null,
                    'Model does not use `SoftDeletes` trait. Deleted records will be permanently lost.',
                    'Add `use SoftDeletes;` trait and a `$table->softDeletes()` migration column for recoverable deletions.',
                );
            }
        }

        return $results;
    }
}
