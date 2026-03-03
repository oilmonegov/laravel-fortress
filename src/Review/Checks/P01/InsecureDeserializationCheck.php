<?php

declare(strict_types=1);

namespace Fortress\Review\Checks\P01;

use Fortress\Review\BaseCheck;
use Fortress\Review\CheckResult;
use Fortress\Review\ReviewContext;

class InsecureDeserializationCheck extends BaseCheck
{
    public function id(): string
    {
        return 'insecure_deserialization';
    }

    public function ruleId(): string
    {
        return 'F-P01-018';
    }

    public function part(): string
    {
        return 'P01';
    }

    public function severity(): string
    {
        return 'critical';
    }

    public function description(): string
    {
        return 'Detect unserialize() usage with potentially untrusted data';
    }

    /** @return CheckResult[] */
    public function run(ReviewContext $context): array
    {
        $results = [];

        foreach ($context->phpFiles() as $file) {
            $path = $file->getRealPath();
            $content = $context->content($path);
            $relativePath = $this->fileRelativePath($path, $context->basePath());

            $matches = $this->matchPattern($content, '/\bunserialize\s*\(/');
            foreach ($matches as $match) {
                $results[] = $this->result(
                    $relativePath,
                    $match['line'],
                    '`unserialize()` can execute arbitrary code if fed untrusted data.',
                    'Use `json_decode()` for data interchange. If `unserialize()` is required, restrict allowed classes: `unserialize($data, [\'allowed_classes\' => [SafeClass::class]])`.',
                    $this->getSnippet($content, $match['line']),
                );
            }
        }

        return $results;
    }
}
