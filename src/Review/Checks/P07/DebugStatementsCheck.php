<?php

declare(strict_types=1);

namespace Fortress\Review\Checks\P07;

use Fortress\Review\BaseCheck;
use Fortress\Review\CheckResult;
use Fortress\Review\ReviewContext;

class DebugStatementsCheck extends BaseCheck
{
    public function id(): string
    {
        return 'debug_statements';
    }

    public function ruleId(): string
    {
        return 'F-P07-012';
    }

    public function part(): string
    {
        return 'P07';
    }

    public function severity(): string
    {
        return 'warning';
    }

    public function description(): string
    {
        return 'Detect debug statements left in code';
    }

    /** @return CheckResult[] */
    public function run(ReviewContext $context): array
    {
        $results = [];

        foreach ($context->phpFiles() as $file) {
            $path = $file->getRealPath();
            $content = $context->content($path);
            $relativePath = $this->fileRelativePath($path, $context->basePath());

            $matches = $this->matchPattern($content, '/\b(dd|dump|ray|var_dump|print_r|var_export)\s*\(/');
            foreach ($matches as $match) {
                $line = explode("\n", $content)[$match['line'] - 1] ?? '';
                // Skip commented-out debug statements
                if (preg_match('/^\s*(?:\/\/|\*|#)/', $line)) {
                    continue;
                }

                $results[] = $this->result(
                    $relativePath,
                    $match['line'],
                    'Debug statement found in code. These should not be committed.',
                    'Remove the debug statement. Use `Log::debug()` for persistent debugging or Xdebug for step-through debugging.',
                    $this->getSnippet($content, $match['line']),
                );
            }
        }

        return $results;
    }
}
