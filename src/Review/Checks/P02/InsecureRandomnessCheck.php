<?php

declare(strict_types=1);

namespace Fortress\Review\Checks\P02;

use Fortress\Review\BaseCheck;
use Fortress\Review\CheckResult;
use Fortress\Review\ReviewContext;

class InsecureRandomnessCheck extends BaseCheck
{
    public function id(): string
    {
        return 'insecure_randomness';
    }

    public function ruleId(): string
    {
        return 'F-P02-005';
    }

    public function part(): string
    {
        return 'P02';
    }

    public function severity(): string
    {
        return 'warning';
    }

    public function description(): string
    {
        return 'Detect use of weak random number generators for security-sensitive operations';
    }

    /** @return CheckResult[] */
    public function run(ReviewContext $context): array
    {
        $results = [];

        foreach ($context->phpFiles() as $file) {
            $path = $file->getRealPath();
            $content = $context->content($path);
            $relativePath = $this->fileRelativePath($path, $context->basePath());

            foreach (['/\brand\s*\(/', '/\bmt_rand\s*\(/', '/\barray_rand\s*\(/'] as $pattern) {
                $matches = $this->matchPattern($content, $pattern);
                foreach ($matches as $match) {
                    $results[] = $this->result(
                        $relativePath,
                        $match['line'],
                        'Weak random number generator used. `rand()` and `mt_rand()` are not cryptographically secure.',
                        'Use `random_int()` for integers, `random_bytes()` for raw bytes, or `Str::random()` for token strings.',
                        $this->getSnippet($content, $match['line']),
                    );
                }
            }
        }

        return $results;
    }
}
