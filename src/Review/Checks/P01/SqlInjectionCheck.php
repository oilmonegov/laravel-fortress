<?php

declare(strict_types=1);

namespace Fortress\Review\Checks\P01;

use Fortress\Review\BaseCheck;
use Fortress\Review\CheckResult;
use Fortress\Review\ReviewContext;

class SqlInjectionCheck extends BaseCheck
{
    public function id(): string
    {
        return 'sql_injection';
    }

    public function ruleId(): string
    {
        return 'F-P01-001';
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
        return 'Detect SQL injection vulnerabilities from string interpolation in queries';
    }

    /** @return CheckResult[] */
    public function run(ReviewContext $context): array
    {
        $results = [];

        foreach ($context->phpFiles() as $file) {
            $path = $file->getRealPath();
            $content = $context->content($path);
            $relativePath = $this->fileRelativePath($path, $context->basePath());

            // DB::raw() with variables
            $matches = $this->matchPattern($content, '/DB::raw\s*\(\s*["\'].*\$\w+/');
            foreach ($matches as $match) {
                $results[] = $this->result(
                    $relativePath,
                    $match['line'],
                    'DB::raw() uses string interpolation with a variable, risking SQL injection.',
                    'Use parameter binding instead: `DB::raw(\'LOWER(?)\', [$value])` or query builder `->whereRaw(\'col = ?\', [$val])`.',
                    $this->getSnippet($content, $match['line']),
                );
            }

            // String interpolation in query methods
            $matches = $this->matchPattern($content, '/->(?:where|whereRaw|selectRaw|orderByRaw|groupByRaw|havingRaw)\s*\(\s*"[^"]*\$\w+/');
            foreach ($matches as $match) {
                $results[] = $this->result(
                    $relativePath,
                    $match['line'],
                    'Query method uses double-quoted string with variable interpolation.',
                    'Use parameter binding: `->whereRaw(\'col = ?\', [$value])` or query builder methods.',
                    $this->getSnippet($content, $match['line']),
                );
            }

            // DB::select/statement with interpolated variables
            $matches = $this->matchPattern($content, '/DB::(?:select|statement|insert|update|delete)\s*\(\s*"[^"]*\$\w+/');
            foreach ($matches as $match) {
                $results[] = $this->result(
                    $relativePath,
                    $match['line'],
                    'Raw DB statement uses string interpolation instead of parameter binding.',
                    'Use parameter binding: `DB::select(\'SELECT * FROM users WHERE id = ?\', [$id])`.',
                    $this->getSnippet($content, $match['line']),
                );
            }
        }

        return $results;
    }
}
