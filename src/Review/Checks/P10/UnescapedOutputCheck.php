<?php

declare(strict_types=1);

namespace Fortress\Review\Checks\P10;

use Fortress\Review\BaseCheck;
use Fortress\Review\CheckResult;
use Fortress\Review\ReviewContext;

class UnescapedOutputCheck extends BaseCheck
{
    public function id(): string
    {
        return 'unescaped_output';
    }

    public function ruleId(): string
    {
        return 'F-P10-005';
    }

    public function part(): string
    {
        return 'P10';
    }

    public function severity(): string
    {
        return 'critical';
    }

    public function description(): string
    {
        return 'Detect unescaped output in Blade and Vue templates (XSS risk)';
    }

    /** @return CheckResult[] */
    public function run(ReviewContext $context): array
    {
        $results = [];

        foreach ($context->bladeFiles() as $file) {
            $path = $file->getRealPath();
            $content = $context->content($path);
            $relativePath = $this->fileRelativePath($path, $context->basePath());

            $matches = $this->matchPattern($content, '/\{!!\s*.+?\s*!!\}/');
            foreach ($matches as $match) {
                $results[] = $this->result(
                    $relativePath,
                    $match['line'],
                    'Unescaped Blade output `{!! !!}` may allow XSS attacks.',
                    'Use escaped output `{{ }}` instead. If raw HTML is truly needed, sanitize server-side first.',
                    $this->getSnippet($content, $match['line']),
                );
            }
        }

        foreach ($context->vueFiles() as $file) {
            $path = $file->getRealPath();
            $content = $context->content($path);
            $relativePath = $this->fileRelativePath($path, $context->basePath());

            $matches = $this->matchPattern($content, '/v-html\s*=/');
            foreach ($matches as $match) {
                $results[] = $this->result(
                    $relativePath,
                    $match['line'],
                    '`v-html` renders raw HTML in Vue and may allow XSS.',
                    'Use `v-text` or template interpolation `{{ }}`. If `v-html` is required, sanitize with DOMPurify.',
                    $this->getSnippet($content, $match['line']),
                );
            }
        }

        return $results;
    }
}
