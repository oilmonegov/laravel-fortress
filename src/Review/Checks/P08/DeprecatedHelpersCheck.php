<?php

declare(strict_types=1);

namespace Fortress\Review\Checks\P08;

use Fortress\Review\BaseCheck;
use Fortress\Review\CheckResult;
use Fortress\Review\ReviewContext;

class DeprecatedHelpersCheck extends BaseCheck
{
    public function id(): string
    {
        return 'deprecated_helpers';
    }

    public function ruleId(): string
    {
        return 'F-P08-070';
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
        return 'Detect deprecated Laravel helper functions';
    }

    /** @return CheckResult[] */
    public function run(ReviewContext $context): array
    {
        $results = [];
        $deprecated = [
            'str_contains' => 'Str::contains()',
            'str_slug' => 'Str::slug()',
            'str_plural' => 'Str::plural()',
            'str_singular' => 'Str::singular()',
            'str_limit' => 'Str::limit()',
            'str_start' => 'Str::start()',
            'str_finish' => 'Str::finish()',
            'starts_with' => 'Str::startsWith()',
            'ends_with' => 'Str::endsWith()',
            'camel_case' => 'Str::camel()',
            'snake_case' => 'Str::snake()',
            'studly_case' => 'Str::studly()',
            'title_case' => 'Str::title()',
            'kebab_case' => 'Str::kebab()',
            'array_add' => 'Arr::add()',
            'array_collapse' => 'Arr::collapse()',
            'array_divide' => 'Arr::divide()',
            'array_dot' => 'Arr::dot()',
            'array_except' => 'Arr::except()',
            'array_first' => 'Arr::first()',
            'array_flatten' => 'Arr::flatten()',
            'array_forget' => 'Arr::forget()',
            'array_get' => 'Arr::get()',
            'array_has' => 'Arr::has()',
            'array_last' => 'Arr::last()',
            'array_only' => 'Arr::only()',
            'array_pluck' => 'Arr::pluck()',
            'array_pull' => 'Arr::pull()',
            'array_set' => 'Arr::set()',
            'array_sort' => 'Arr::sort()',
            'array_where' => 'Arr::where()',
            'array_wrap' => 'Arr::wrap()',
        ];

        foreach ($context->phpFiles() as $file) {
            $path = $file->getRealPath();
            $content = $context->content($path);
            $relativePath = $this->fileRelativePath($path, $context->basePath());

            foreach ($deprecated as $helper => $replacement) {
                // Only match as function calls (not method calls)
                $matches = $this->matchPattern($content, '/(?<![>:\w])'.preg_quote($helper, '/').'\s*\(/');
                foreach ($matches as $match) {
                    $results[] = $this->result(
                        $relativePath,
                        $match['line'],
                        "`{$helper}()` helper was removed in Laravel 10+.",
                        "Use `{$replacement}` instead.",
                        $this->getSnippet($content, $match['line']),
                    );
                }
            }
        }

        return $results;
    }
}
