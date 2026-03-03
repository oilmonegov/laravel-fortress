<?php

declare(strict_types=1);

namespace Fortress\Review\Checks\P12;

use Fortress\Review\BaseCheck;
use Fortress\Review\CheckResult;
use Fortress\Review\ReviewContext;

class UnvalidatedApiInputCheck extends BaseCheck
{
    public function id(): string
    {
        return 'unvalidated_api_input';
    }

    public function ruleId(): string
    {
        return 'F-P12-001';
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
        return 'Detect $request->all() usage without form request validation';
    }

    /** @return CheckResult[] */
    public function run(ReviewContext $context): array
    {
        $results = [];

        foreach ($context->controllerFiles() as $file) {
            $path = $file->getRealPath();
            $content = $context->content($path);
            $relativePath = $this->fileRelativePath($path, $context->basePath());

            // $request->all() used directly
            $matches = $this->matchPattern($content, '/\$request->all\s*\(\s*\)/');
            foreach ($matches as $match) {
                $results[] = $this->result(
                    $relativePath,
                    $match['line'],
                    '`$request->all()` passes all input without filtering, risking mass assignment.',
                    'Use `$request->validated()` with a Form Request class, or `$request->only([\'field1\', \'field2\'])`.',
                    $this->getSnippet($content, $match['line']),
                );
            }

            // $request->input() passed directly to create/update
            $matches = $this->matchPattern($content, '/->(?:create|update|fill)\s*\(\s*\$request->(?:input|post|query)\s*\(\s*\)\s*\)/');
            foreach ($matches as $match) {
                $results[] = $this->result(
                    $relativePath,
                    $match['line'],
                    'Unfiltered request data passed directly to model write operation.',
                    'Use a Form Request class with validation rules and pass `$request->validated()` instead.',
                    $this->getSnippet($content, $match['line']),
                );
            }
        }

        return $results;
    }
}
