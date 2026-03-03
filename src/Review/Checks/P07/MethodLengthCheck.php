<?php

declare(strict_types=1);

namespace Fortress\Review\Checks\P07;

use Fortress\Review\BaseCheck;
use Fortress\Review\CheckResult;
use Fortress\Review\ReviewContext;

class MethodLengthCheck extends BaseCheck
{
    private const MAX_METHOD_LINES = 30;

    public function id(): string
    {
        return 'method_length';
    }

    public function ruleId(): string
    {
        return 'F-P07-030';
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
        return 'Detect methods exceeding '.self::MAX_METHOD_LINES.' lines';
    }

    /** @return CheckResult[] */
    public function run(ReviewContext $context): array
    {
        $results = [];

        foreach ($context->phpFiles() as $file) {
            $path = $file->getRealPath();
            $content = $context->content($path);
            $relativePath = $this->fileRelativePath($path, $context->basePath());

            // Find function declarations and count their lines
            $lines = explode("\n", $content);
            $inMethod = false;
            $methodStart = 0;
            $braceCount = 0;
            $methodName = '';

            foreach ($lines as $index => $line) {
                if (! $inMethod && preg_match('/(?:public|protected|private|static)\s+function\s+(\w+)\s*\(/', $line, $m)) {
                    $inMethod = true;
                    $methodStart = $index + 1;
                    $braceCount = 0;
                    $methodName = $m[1];
                }

                if ($inMethod) {
                    $braceCount += substr_count($line, '{') - substr_count($line, '}');

                    if ($braceCount <= 0 && str_contains($line, '}')) {
                        $methodEnd = $index + 1;
                        $methodLines = $methodEnd - $methodStart + 1;

                        if ($methodLines > self::MAX_METHOD_LINES) {
                            $results[] = $this->result(
                                $relativePath,
                                $methodStart,
                                "Method `{$methodName}()` is {$methodLines} lines long (max: ".self::MAX_METHOD_LINES.').',
                                'Extract sub-methods, use early returns, or delegate to helper classes to reduce method complexity.',
                            );
                        }

                        $inMethod = false;
                    }
                }
            }
        }

        return $results;
    }
}
