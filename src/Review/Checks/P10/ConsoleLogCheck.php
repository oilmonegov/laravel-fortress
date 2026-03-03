<?php

declare(strict_types=1);

namespace Fortress\Review\Checks\P10;

use Fortress\Review\BaseCheck;
use Fortress\Review\CheckResult;
use Fortress\Review\ReviewContext;

class ConsoleLogCheck extends BaseCheck
{
    public function id(): string
    {
        return 'console_log';
    }

    public function ruleId(): string
    {
        return 'F-P10-010';
    }

    public function part(): string
    {
        return 'P10';
    }

    public function severity(): string
    {
        return 'warning';
    }

    public function description(): string
    {
        return 'Detect console.log and console.debug statements in JavaScript/TypeScript/Vue files';
    }

    /** @return CheckResult[] */
    public function run(ReviewContext $context): array
    {
        $results = [];

        $allFrontendFiles = array_merge($context->jsFiles(), $context->vueFiles());

        foreach ($allFrontendFiles as $file) {
            $path = $file->getRealPath();
            $content = $context->content($path);
            $relativePath = $this->fileRelativePath($path, $context->basePath());

            $matches = $this->matchPattern($content, '/\bconsole\.(?:log|debug|info|warn|error|trace)\s*\(/');
            foreach ($matches as $match) {
                $line = explode("\n", $content)[$match['line'] - 1] ?? '';
                // Skip commented-out statements
                if (preg_match('/^\s*(?:\/\/|\*)/', $line)) {
                    continue;
                }

                $results[] = $this->result(
                    $relativePath,
                    $match['line'],
                    'Console statement found in production code.',
                    'Remove `console.*` calls before committing, or use a proper logging service for production diagnostics.',
                    $this->getSnippet($content, $match['line']),
                );
            }
        }

        return $results;
    }
}
