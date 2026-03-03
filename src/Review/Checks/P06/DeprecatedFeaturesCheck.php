<?php

declare(strict_types=1);

namespace Fortress\Review\Checks\P06;

use Fortress\Review\BaseCheck;
use Fortress\Review\CheckResult;
use Fortress\Review\ReviewContext;

class DeprecatedFeaturesCheck extends BaseCheck
{
    public function id(): string
    {
        return 'deprecated_features';
    }

    public function ruleId(): string
    {
        return 'F-P06-020';
    }

    public function part(): string
    {
        return 'P06';
    }

    public function severity(): string
    {
        return 'warning';
    }

    public function description(): string
    {
        return 'Detect usage of PHP deprecated functions and features';
    }

    /** @return CheckResult[] */
    public function run(ReviewContext $context): array
    {
        $results = [];
        $deprecated = [
            'create_function' => 'Use anonymous functions (closures) instead.',
            'each' => 'Use `foreach` loop instead.',
            'split' => 'Use `preg_split()` instead.',
            'ereg' => 'Use `preg_match()` instead.',
            'eregi' => 'Use `preg_match()` with `i` flag instead.',
            'ereg_replace' => 'Use `preg_replace()` instead.',
            'mysql_connect' => 'Use PDO or Laravel\'s database abstraction.',
            'mysql_query' => 'Use PDO or Laravel\'s query builder.',
        ];

        foreach ($context->phpFiles() as $file) {
            $path = $file->getRealPath();
            $content = $context->content($path);
            $relativePath = $this->fileRelativePath($path, $context->basePath());

            foreach ($deprecated as $function => $replacement) {
                $matches = $this->matchPattern($content, '/\b'.preg_quote($function, '/').'\s*\(/');
                foreach ($matches as $match) {
                    $results[] = $this->result(
                        $relativePath,
                        $match['line'],
                        "`{$function}()` is deprecated and may be removed in future PHP versions.",
                        $replacement,
                        $this->getSnippet($content, $match['line']),
                    );
                }
            }
        }

        return $results;
    }
}
