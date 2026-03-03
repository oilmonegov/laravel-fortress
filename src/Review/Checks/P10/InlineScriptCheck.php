<?php

declare(strict_types=1);

namespace Fortress\Review\Checks\P10;

use Fortress\Review\BaseCheck;
use Fortress\Review\CheckResult;
use Fortress\Review\ReviewContext;

class InlineScriptCheck extends BaseCheck
{
    public function id(): string
    {
        return 'inline_script';
    }

    public function ruleId(): string
    {
        return 'F-P10-001';
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
        return 'Detect inline <script> blocks in Blade and Vue templates';
    }

    /** @return CheckResult[] */
    public function run(ReviewContext $context): array
    {
        $results = [];

        foreach ($context->bladeFiles() as $file) {
            $path = $file->getRealPath();
            $content = $context->content($path);
            $relativePath = $this->fileRelativePath($path, $context->basePath());

            // Inline <script> with JS code (not just src attributes)
            $matches = $this->matchPattern($content, '/<script(?!\s+src)[^>]*>(?!\s*<\/script>)/');
            foreach ($matches as $match) {
                $results[] = $this->result(
                    $relativePath,
                    $match['line'],
                    'Inline `<script>` block in Blade template hinders CSP enforcement and maintainability.',
                    'Extract JavaScript to a separate `.js` file or use `@push(\'scripts\')` with external files.',
                    $this->getSnippet($content, $match['line']),
                );
            }
        }

        return $results;
    }
}
