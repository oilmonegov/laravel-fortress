<?php

declare(strict_types=1);

namespace Fortress\Review\Checks\P07;

use Fortress\Review\BaseCheck;
use Fortress\Review\CheckResult;
use Fortress\Review\ReviewContext;

class ClassLengthCheck extends BaseCheck
{
    private const MAX_CLASS_LINES = 300;

    public function id(): string
    {
        return 'class_length';
    }

    public function ruleId(): string
    {
        return 'F-P07-035';
    }

    public function part(): string
    {
        return 'P07';
    }

    public function severity(): string
    {
        return 'info';
    }

    public function description(): string
    {
        return 'Detect classes exceeding '.self::MAX_CLASS_LINES.' lines';
    }

    /** @return CheckResult[] */
    public function run(ReviewContext $context): array
    {
        $results = [];

        foreach ($context->phpFiles() as $file) {
            $path = $file->getRealPath();
            $content = $context->content($path);
            $relativePath = $this->fileRelativePath($path, $context->basePath());

            $lines = explode("\n", $content);
            $totalLines = count($lines);

            if ($totalLines > self::MAX_CLASS_LINES) {
                // Verify it actually contains a class
                if (preg_match('/^(?:abstract\s+|final\s+)?class\s+\w+/m', $content)) {
                    $results[] = $this->result(
                        $relativePath,
                        null,
                        "File is {$totalLines} lines long (max: ".self::MAX_CLASS_LINES.').',
                        'Extract concerns into traits, delegate to helper classes, or split into focused single-responsibility classes.',
                    );
                }
            }
        }

        return $results;
    }
}
