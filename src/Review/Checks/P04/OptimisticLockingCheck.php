<?php

declare(strict_types=1);

namespace Fortress\Review\Checks\P04;

use Fortress\Review\BaseCheck;
use Fortress\Review\CheckResult;
use Fortress\Review\ReviewContext;

class OptimisticLockingCheck extends BaseCheck
{
    public function id(): string
    {
        return 'optimistic_locking';
    }

    public function ruleId(): string
    {
        return 'F-P04-010';
    }

    public function part(): string
    {
        return 'P04';
    }

    public function severity(): string
    {
        return 'info';
    }

    public function description(): string
    {
        return 'Check if financial models have optimistic locking mechanisms';
    }

    /** @return CheckResult[] */
    public function run(ReviewContext $context): array
    {
        $results = [];

        foreach ($context->modelFiles() as $file) {
            $path = $file->getRealPath();
            $content = $context->content($path);
            $relativePath = $this->fileRelativePath($path, $context->basePath());

            // Only check models that handle money/financial data
            $isFinancial = preg_match('/(?:amount|balance|total|price|cost|money|currency|ledger|journal|transaction)/i', $content);

            if ($isFinancial && ! str_contains($content, 'lock_version') && ! str_contains($content, 'optimistic')) {
                $results[] = $this->result(
                    $relativePath,
                    null,
                    'Financial model does not implement optimistic locking.',
                    'Consider adding a `lock_version` integer column and checking it on updates to prevent concurrent modification issues.',
                );
            }
        }

        return $results;
    }
}
