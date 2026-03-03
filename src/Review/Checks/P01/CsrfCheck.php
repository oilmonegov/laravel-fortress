<?php

declare(strict_types=1);

namespace Fortress\Review\Checks\P01;

use Fortress\Review\BaseCheck;
use Fortress\Review\CheckResult;
use Fortress\Review\ReviewContext;

class CsrfCheck extends BaseCheck
{
    public function id(): string
    {
        return 'csrf';
    }

    public function ruleId(): string
    {
        return 'F-P01-005';
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
        return 'Detect forms without CSRF protection';
    }

    /** @return CheckResult[] */
    public function run(ReviewContext $context): array
    {
        $results = [];

        foreach ($context->bladeFiles() as $file) {
            $path = $file->getRealPath();
            $content = $context->content($path);
            $relativePath = $this->fileRelativePath($path, $context->basePath());

            // Find <form> tags with POST/PUT/PATCH/DELETE methods
            if (preg_match_all('/<form[^>]*method\s*=\s*["\'](?:post|put|patch|delete)["\'][^>]*>/i', $content, $formMatches, PREG_OFFSET_CAPTURE)) {
                foreach ($formMatches[0] as $formMatch) {
                    $formPosition = $formMatch[1];
                    $lineNumber = substr_count(substr($content, 0, $formPosition), "\n") + 1;

                    // Check if @csrf appears within the next ~500 chars after the form tag
                    $afterForm = substr($content, $formPosition, 500);
                    if (! str_contains($afterForm, '@csrf') && ! str_contains($afterForm, 'csrf_field()') && ! str_contains($afterForm, '_token')) {
                        $results[] = $this->result(
                            $relativePath,
                            $lineNumber,
                            'Form with state-changing method is missing CSRF protection.',
                            'Add `@csrf` directive immediately after the `<form>` opening tag.',
                            $this->getSnippet($content, $lineNumber),
                        );
                    }
                }
            }
        }

        return $results;
    }
}
