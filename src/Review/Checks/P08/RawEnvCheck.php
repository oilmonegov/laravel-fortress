<?php

declare(strict_types=1);

namespace Fortress\Review\Checks\P08;

use Fortress\Review\BaseCheck;
use Fortress\Review\CheckResult;
use Fortress\Review\ReviewContext;

class RawEnvCheck extends BaseCheck
{
    public function id(): string
    {
        return 'raw_env';
    }

    public function ruleId(): string
    {
        return 'F-P08-041';
    }

    public function part(): string
    {
        return 'P08';
    }

    public function severity(): string
    {
        return 'warning';
    }

    public function description(): string
    {
        return 'Detect env() calls outside of config files';
    }

    /** @return CheckResult[] */
    public function run(ReviewContext $context): array
    {
        $results = [];

        foreach ($context->phpFiles() as $file) {
            $path = $file->getRealPath();
            $content = $context->content($path);
            $relativePath = $this->fileRelativePath($path, $context->basePath());

            if (str_starts_with($relativePath, 'config/') || str_starts_with($relativePath, 'config\\')) {
                continue;
            }

            $matches = $this->matchPattern($content, '/\benv\s*\(\s*["\']/');
            foreach ($matches as $match) {
                $line = explode("\n", $content)[$match['line'] - 1] ?? '';
                if (preg_match('/^\s*(?:\/\/|\*|#)/', $line)) {
                    continue;
                }

                $results[] = $this->result(
                    $relativePath,
                    $match['line'],
                    '`env()` called outside of config files. Environment values are not cached when using `config:cache`.',
                    'Define the value in a config file using `env()`, then access via `config(\'file.key\')` in application code.',
                    $this->getSnippet($content, $match['line']),
                );
            }
        }

        return $results;
    }
}
