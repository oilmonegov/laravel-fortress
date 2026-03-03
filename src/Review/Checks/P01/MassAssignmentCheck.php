<?php

declare(strict_types=1);

namespace Fortress\Review\Checks\P01;

use Fortress\Review\BaseCheck;
use Fortress\Review\CheckResult;
use Fortress\Review\ReviewContext;

class MassAssignmentCheck extends BaseCheck
{
    public function id(): string
    {
        return 'mass_assignment';
    }

    public function ruleId(): string
    {
        return 'F-P01-010';
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
        return 'Detect unguarded mass assignment and forceFill usage';
    }

    /** @return CheckResult[] */
    public function run(ReviewContext $context): array
    {
        $results = [];

        foreach ($context->phpFiles() as $file) {
            $path = $file->getRealPath();
            $content = $context->content($path);
            $relativePath = $this->fileRelativePath($path, $context->basePath());

            // Model::unguard()
            $matches = $this->matchPattern($content, '/\b(?:Model|Eloquent)::unguard\s*\(/');
            foreach ($matches as $match) {
                $results[] = $this->result(
                    $relativePath,
                    $match['line'],
                    'Model::unguard() disables mass assignment protection globally.',
                    'Define `$fillable` on each model instead of disabling guard protection. If needed in seeders, use `Model::unguard()` within a limited scope.',
                    $this->getSnippet($content, $match['line']),
                );
            }

            // forceFill usage
            $matches = $this->matchPattern($content, '/->forceFill\s*\(/');
            foreach ($matches as $match) {
                $results[] = $this->result(
                    $relativePath,
                    $match['line'],
                    '`forceFill()` bypasses mass assignment protection.',
                    'Use `->fill()` with properly defined `$fillable` properties. Only use `forceFill()` when explicitly intended and documented.',
                    $this->getSnippet($content, $match['line']),
                );
            }

            // forceCreate usage
            $matches = $this->matchPattern($content, '/::forceCreate\s*\(/');
            foreach ($matches as $match) {
                $results[] = $this->result(
                    $relativePath,
                    $match['line'],
                    '`forceCreate()` bypasses mass assignment protection.',
                    'Use `::create()` with properly defined `$fillable` properties on the model.',
                    $this->getSnippet($content, $match['line']),
                );
            }
        }

        return $results;
    }
}
