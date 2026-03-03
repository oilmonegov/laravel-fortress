<?php

declare(strict_types=1);

namespace Fortress\Review\Checks\P04;

use Fortress\Review\BaseCheck;
use Fortress\Review\CheckResult;
use Fortress\Review\ReviewContext;

class DatabaseTransactionCheck extends BaseCheck
{
    public function id(): string
    {
        return 'database_transaction';
    }

    public function ruleId(): string
    {
        return 'F-P04-001';
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
        return 'Detect multi-model writes without database transactions';
    }

    /** @return CheckResult[] */
    public function run(ReviewContext $context): array
    {
        $results = [];

        foreach ($context->phpFiles() as $file) {
            $path = $file->getRealPath();
            $content = $context->content($path);
            $relativePath = $this->fileRelativePath($path, $context->basePath());

            // Skip migrations, seeders, tests
            if (preg_match('/(?:migrations|seeders|tests)[\/\\\\]/', $relativePath)) {
                continue;
            }

            // Count write operations in the file
            $writeOps = preg_match_all('/(?:::create\s*\(|->save\s*\(|->update\s*\(|->delete\s*\(|->insert\s*\()/', $content);

            // If multiple writes exist but no transaction wrapping
            if ($writeOps >= 2 && ! str_contains($content, 'DB::transaction') && ! str_contains($content, 'DB::beginTransaction')) {
                $results[] = $this->result(
                    $relativePath,
                    null,
                    "File has {$writeOps} write operations without a database transaction wrapper.",
                    'Wrap related writes in `DB::transaction(function () { ... })` to ensure atomicity.',
                );
            }
        }

        return $results;
    }
}
