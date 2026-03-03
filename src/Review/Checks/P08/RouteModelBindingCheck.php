<?php

declare(strict_types=1);

namespace Fortress\Review\Checks\P08;

use Fortress\Review\BaseCheck;
use Fortress\Review\CheckResult;
use Fortress\Review\ReviewContext;

class RouteModelBindingCheck extends BaseCheck
{
    public function id(): string
    {
        return 'route_model_binding';
    }

    public function ruleId(): string
    {
        return 'F-P08-060';
    }

    public function part(): string
    {
        return 'P08';
    }

    public function severity(): string
    {
        return 'info';
    }

    public function description(): string
    {
        return 'Detect manual Model::findOrFail() in controllers that could use route model binding';
    }

    /** @return CheckResult[] */
    public function run(ReviewContext $context): array
    {
        $results = [];

        foreach ($context->controllerFiles() as $file) {
            $path = $file->getRealPath();
            $content = $context->content($path);
            $relativePath = $this->fileRelativePath($path, $context->basePath());

            $matches = $this->matchPattern($content, '/\w+::findOrFail\s*\(\s*\$\w+\s*\)/');
            foreach ($matches as $match) {
                $results[] = $this->result(
                    $relativePath,
                    $match['line'],
                    'Manual `Model::findOrFail()` in controller could use route model binding.',
                    'Type-hint the model in the controller method signature: `public function show(Post $post)` and Laravel will resolve it automatically.',
                    $this->getSnippet($content, $match['line']),
                );
            }
        }

        return $results;
    }
}
