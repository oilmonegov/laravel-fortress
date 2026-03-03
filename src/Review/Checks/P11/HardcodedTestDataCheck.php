<?php

declare(strict_types=1);

namespace Fortress\Review\Checks\P11;

use Fortress\Review\BaseCheck;
use Fortress\Review\CheckResult;
use Fortress\Review\ReviewContext;

class HardcodedTestDataCheck extends BaseCheck
{
    public function id(): string
    {
        return 'hardcoded_test_data';
    }

    public function ruleId(): string
    {
        return 'F-P11-015';
    }

    public function part(): string
    {
        return 'P11';
    }

    public function severity(): string
    {
        return 'info';
    }

    public function description(): string
    {
        return 'Detect hardcoded IDs and data in tests that should use factories';
    }

    /** @return CheckResult[] */
    public function run(ReviewContext $context): array
    {
        $results = [];

        foreach ($context->testFiles() as $file) {
            $path = $file->getRealPath();
            $content = $context->content($path);
            $relativePath = $this->fileRelativePath($path, $context->basePath());

            // Hardcoded user IDs
            $matches = $this->matchPattern($content, '/["\']user_id["\']\s*=>\s*\d+/');
            foreach ($matches as $match) {
                $results[] = $this->result(
                    $relativePath,
                    $match['line'],
                    'Hardcoded user ID in test data. Tests may fail if database state changes.',
                    'Use model factories: `$user = User::factory()->create()` then reference `$user->id`.',
                    $this->getSnippet($content, $match['line']),
                );
            }

            // new Model(['...']) with hardcoded data instead of factory
            $matches = $this->matchPattern($content, '/new\s+(?:User|Post|Order|Product)\s*\(\s*\[/');
            foreach ($matches as $match) {
                $results[] = $this->result(
                    $relativePath,
                    $match['line'],
                    'Test creates model with hardcoded attributes instead of using a factory.',
                    'Use `Model::factory()->create([...])` or `Model::factory()->make([...])` for test data.',
                    $this->getSnippet($content, $match['line']),
                );
            }
        }

        return $results;
    }
}
