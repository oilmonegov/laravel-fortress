<?php

declare(strict_types=1);

namespace Fortress\Review\Checks\P11;

use Fortress\Review\BaseCheck;
use Fortress\Review\CheckResult;
use Fortress\Review\ReviewContext;

class AssertionQualityCheck extends BaseCheck
{
    public function id(): string
    {
        return 'assertion_quality';
    }

    public function ruleId(): string
    {
        return 'F-P11-010';
    }

    public function part(): string
    {
        return 'P11';
    }

    public function severity(): string
    {
        return 'warning';
    }

    public function description(): string
    {
        return 'Detect tests with no meaningful assertions';
    }

    /** @return CheckResult[] */
    public function run(ReviewContext $context): array
    {
        $results = [];

        foreach ($context->testFiles() as $file) {
            $path = $file->getRealPath();
            $content = $context->content($path);
            $relativePath = $this->fileRelativePath($path, $context->basePath());

            // Tests with assertTrue(true) as the only assertion
            $matches = $this->matchPattern($content, '/->assertTrue\s*\(\s*true\s*\)/');
            foreach ($matches as $match) {
                $results[] = $this->result(
                    $relativePath,
                    $match['line'],
                    'Test uses `assertTrue(true)` which provides no meaningful verification.',
                    'Replace with assertions that actually verify behavior: `->assertStatus(200)`, `->assertDatabaseHas()`, etc.',
                    $this->getSnippet($content, $match['line']),
                );
            }

            // Test methods/closures with no assertions at all (Pest it() blocks)
            if (preg_match_all('/it\s*\(\s*["\'][^"\']+["\']\s*,\s*function\s*\(\)\s*\{([^}]*)\}/s', $content, $testMatches)) {
                foreach ($testMatches[1] as $index => $body) {
                    if (! preg_match('/(?:assert|expect|should|toBe|toHave|toContain|->get\(|->post\(|->put\(|->delete\()/', $body)) {
                        $pos = strpos($content, $testMatches[0][$index]);
                        $lineNum = $pos !== false ? substr_count(substr($content, 0, $pos), "\n") + 1 : null;
                        $results[] = $this->result(
                            $relativePath,
                            $lineNum,
                            'Test appears to have no assertions or expectations.',
                            'Add meaningful assertions that verify the expected behavior.',
                            $lineNum ? $this->getSnippet($content, $lineNum) : null,
                        );
                    }
                }
            }
        }

        return $results;
    }
}
