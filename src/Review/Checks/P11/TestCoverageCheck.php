<?php

declare(strict_types=1);

namespace Fortress\Review\Checks\P11;

use Fortress\Review\BaseCheck;
use Fortress\Review\CheckResult;
use Fortress\Review\ReviewContext;

class TestCoverageCheck extends BaseCheck
{
    public function id(): string
    {
        return 'test_coverage';
    }

    public function ruleId(): string
    {
        return 'F-P11-001';
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
        return 'Detect controllers and actions without corresponding test files';
    }

    /** @return CheckResult[] */
    public function run(ReviewContext $context): array
    {
        $results = [];
        $testFiles = $context->testFiles();
        $testNames = [];

        foreach ($testFiles as $file) {
            $testNames[] = strtolower(pathinfo($file->getFilename(), PATHINFO_FILENAME));
        }

        // Check controllers
        foreach ($context->controllerFiles() as $file) {
            $className = pathinfo($file->getFilename(), PATHINFO_FILENAME);
            $testName = strtolower(str_replace('Controller', '', $className));

            $hasTest = false;
            foreach ($testNames as $name) {
                if (str_contains($name, $testName)) {
                    $hasTest = true;
                    break;
                }
            }

            if (! $hasTest) {
                $relativePath = $this->fileRelativePath($file->getRealPath(), $context->basePath());
                $results[] = $this->result(
                    $relativePath,
                    null,
                    "Controller `{$className}` has no corresponding test file.",
                    "Create a feature test: `php artisan make:test {$className}Test`.",
                );
            }
        }

        // Check actions
        foreach ($context->actionFiles() as $file) {
            $className = pathinfo($file->getFilename(), PATHINFO_FILENAME);
            $testName = strtolower($className);

            $hasTest = false;
            foreach ($testNames as $name) {
                if (str_contains($name, $testName)) {
                    $hasTest = true;
                    break;
                }
            }

            if (! $hasTest) {
                $relativePath = $this->fileRelativePath($file->getRealPath(), $context->basePath());
                $results[] = $this->result(
                    $relativePath,
                    null,
                    "Action `{$className}` has no corresponding test file.",
                    'Create a feature test for this action class.',
                );
            }
        }

        return $results;
    }
}
