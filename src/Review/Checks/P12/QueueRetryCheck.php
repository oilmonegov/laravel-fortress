<?php

declare(strict_types=1);

namespace Fortress\Review\Checks\P12;

use Fortress\Review\BaseCheck;
use Fortress\Review\CheckResult;
use Fortress\Review\ReviewContext;

class QueueRetryCheck extends BaseCheck
{
    public function id(): string
    {
        return 'queue_retry';
    }

    public function ruleId(): string
    {
        return 'F-P12-010';
    }

    public function part(): string
    {
        return 'P12';
    }

    public function severity(): string
    {
        return 'warning';
    }

    public function description(): string
    {
        return 'Detect queue jobs missing $tries, $timeout, or $backoff properties';
    }

    /** @return CheckResult[] */
    public function run(ReviewContext $context): array
    {
        $results = [];

        foreach ($context->jobFiles() as $file) {
            $path = $file->getRealPath();
            $content = $context->content($path);
            $relativePath = $this->fileRelativePath($path, $context->basePath());

            if (! str_contains($content, 'ShouldQueue')) {
                continue;
            }

            $missing = [];

            if (! str_contains($content, '$tries')) {
                $missing[] = '$tries';
            }
            if (! str_contains($content, '$timeout')) {
                $missing[] = '$timeout';
            }
            if (! str_contains($content, '$backoff') && ! str_contains($content, 'backoff()')) {
                $missing[] = '$backoff';
            }

            if (! empty($missing)) {
                $results[] = $this->result(
                    $relativePath,
                    null,
                    'Queue job is missing: '.implode(', ', $missing).'. Jobs may retry indefinitely or hang.',
                    'Add properties: `public int $tries = 3;`, `public int $timeout = 60;`, `public int $backoff = 10;`.',
                );
            }
        }

        return $results;
    }
}
