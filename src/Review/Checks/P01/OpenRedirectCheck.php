<?php

declare(strict_types=1);

namespace Fortress\Review\Checks\P01;

use Fortress\Review\BaseCheck;
use Fortress\Review\CheckResult;
use Fortress\Review\ReviewContext;

class OpenRedirectCheck extends BaseCheck
{
    public function id(): string
    {
        return 'open_redirect';
    }

    public function ruleId(): string
    {
        return 'F-P01-007';
    }

    public function part(): string
    {
        return 'P01';
    }

    public function severity(): string
    {
        return 'warning';
    }

    public function description(): string
    {
        return 'Detect open redirect vulnerabilities from unvalidated user input';
    }

    /** @return CheckResult[] */
    public function run(ReviewContext $context): array
    {
        $results = [];

        foreach ($context->phpFiles() as $file) {
            $path = $file->getRealPath();
            $content = $context->content($path);
            $relativePath = $this->fileRelativePath($path, $context->basePath());

            // redirect() with request input
            $matches = $this->matchPattern($content, '/redirect\s*\(\s*\$request->(?:input|get|query)\s*\(/');
            foreach ($matches as $match) {
                $results[] = $this->result(
                    $relativePath,
                    $match['line'],
                    'Redirect uses unvalidated user input, risking open redirect attacks.',
                    'Validate the URL against an allowlist of trusted domains or use `url()->previous()` / named routes instead.',
                    $this->getSnippet($content, $match['line']),
                );
            }

            // Redirect::to with variable
            $matches = $this->matchPattern($content, '/Redirect::to\s*\(\s*\$(?:request|_GET|_POST)/');
            foreach ($matches as $match) {
                $results[] = $this->result(
                    $relativePath,
                    $match['line'],
                    'Redirect::to() uses user-controlled input without validation.',
                    'Use `redirect()->intended()` or validate against trusted URLs.',
                    $this->getSnippet($content, $match['line']),
                );
            }
        }

        return $results;
    }
}
