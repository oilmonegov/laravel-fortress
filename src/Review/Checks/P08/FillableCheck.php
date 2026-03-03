<?php

declare(strict_types=1);

namespace Fortress\Review\Checks\P08;

use Fortress\Review\BaseCheck;
use Fortress\Review\CheckResult;
use Fortress\Review\ReviewContext;

class FillableCheck extends BaseCheck
{
    public function id(): string
    {
        return 'fillable';
    }

    public function ruleId(): string
    {
        return 'F-P08-003';
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
        return 'Detect Eloquent models without $fillable or $guarded';
    }

    /** @return CheckResult[] */
    public function run(ReviewContext $context): array
    {
        $results = [];

        foreach ($context->modelFiles() as $file) {
            $path = $file->getRealPath();
            $content = $context->content($path);
            $relativePath = $this->fileRelativePath($path, $context->basePath());

            if (! str_contains($content, 'extends Model') && ! str_contains($content, 'extends Authenticatable') && ! str_contains($content, 'extends Pivot')) {
                continue;
            }

            if (! str_contains($content, '$fillable') && ! str_contains($content, '$guarded')) {
                $results[] = $this->result(
                    $relativePath,
                    null,
                    'Eloquent model does not define `$fillable` or `$guarded` property.',
                    'Add a `protected $fillable = [...]` array listing the mass-assignable attributes.',
                );
            }
        }

        return $results;
    }
}
