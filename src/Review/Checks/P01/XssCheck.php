<?php

declare(strict_types=1);

namespace Fortress\Review\Checks\P01;

use Fortress\Review\BaseCheck;
use Fortress\Review\CheckResult;
use Fortress\Review\ReviewContext;

class XssCheck extends BaseCheck
{
    public function id(): string
    {
        return 'xss';
    }

    public function ruleId(): string
    {
        return 'F-P01-002';
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
        return 'Detect cross-site scripting (XSS) vulnerabilities in templates';
    }

    /** @return CheckResult[] */
    public function run(ReviewContext $context): array
    {
        $results = [];

        // Check Blade files for unescaped output
        foreach ($context->bladeFiles() as $file) {
            $path = $file->getRealPath();
            $content = $context->content($path);
            $relativePath = $this->fileRelativePath($path, $context->basePath());

            $matches = $this->matchPattern($content, '/\{!!\s*.+?\s*!!\}/');
            foreach ($matches as $match) {
                $results[] = $this->result(
                    $relativePath,
                    $match['line'],
                    'Blade template uses unescaped output `{!! !!}` which may allow XSS.',
                    'Use escaped output `{{ }}` instead. If raw HTML is required, sanitize with `strip_tags()` or a dedicated HTML purifier.',
                    $this->getSnippet($content, $match['line']),
                );
            }
        }

        // Check Vue files for v-html
        foreach ($context->vueFiles() as $file) {
            $path = $file->getRealPath();
            $content = $context->content($path);
            $relativePath = $this->fileRelativePath($path, $context->basePath());

            $matches = $this->matchPattern($content, '/v-html\s*=/');
            foreach ($matches as $match) {
                $results[] = $this->result(
                    $relativePath,
                    $match['line'],
                    'Vue template uses `v-html` which renders raw HTML and may allow XSS.',
                    'Use `v-text` for plain text. If HTML rendering is required, sanitize with DOMPurify before binding.',
                    $this->getSnippet($content, $match['line']),
                );
            }
        }

        return $results;
    }
}
