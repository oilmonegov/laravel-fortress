<?php

declare(strict_types=1);

namespace Fortress\Review\Checks\P08;

use Fortress\Review\BaseCheck;
use Fortress\Review\CheckResult;
use Fortress\Review\ReviewContext;

class RawQueryCheck extends BaseCheck
{
    public function id(): string
    {
        return 'raw_query';
    }

    public function ruleId(): string
    {
        return 'F-P08-055';
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
        return 'Detect usage of DB::raw() and raw query methods';
    }

    /** @return CheckResult[] */
    public function run(ReviewContext $context): array
    {
        $results = [];

        foreach ($context->phpFiles() as $file) {
            $path = $file->getRealPath();
            $content = $context->content($path);
            $relativePath = $this->fileRelativePath($path, $context->basePath());

            // Skip migrations and Query classes (raw SQL may be intentional)
            if (preg_match('/(?:migrations|Queries)[\/\\\\]/', $relativePath)) {
                continue;
            }

            $matches = $this->matchPattern($content, '/DB::(?:raw|select|insert|update|delete|statement)\s*\(/');
            foreach ($matches as $match) {
                $results[] = $this->result(
                    $relativePath,
                    $match['line'],
                    'Raw database operation used instead of Eloquent or query builder.',
                    'Prefer Eloquent models and query builder methods. If raw SQL is necessary, ensure parameters are bound: `DB::select(\'...?\', [$param])`.',
                    $this->getSnippet($content, $match['line']),
                );
            }
        }

        return $results;
    }
}
