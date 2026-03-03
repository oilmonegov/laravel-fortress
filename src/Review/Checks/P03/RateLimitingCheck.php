<?php

declare(strict_types=1);

namespace Fortress\Review\Checks\P03;

use Fortress\Review\BaseCheck;
use Fortress\Review\CheckResult;
use Fortress\Review\ReviewContext;

class RateLimitingCheck extends BaseCheck
{
    public function id(): string
    {
        return 'rate_limiting';
    }

    public function ruleId(): string
    {
        return 'F-P03-010';
    }

    public function part(): string
    {
        return 'P03';
    }

    public function severity(): string
    {
        return 'warning';
    }

    public function description(): string
    {
        return 'Detect login and API routes without rate limiting';
    }

    /** @return CheckResult[] */
    public function run(ReviewContext $context): array
    {
        $results = [];

        foreach ($context->routeFiles() as $file) {
            $path = $file->getRealPath();
            $content = $context->content($path);
            $relativePath = $this->fileRelativePath($path, $context->basePath());

            // Check for login routes without throttle
            $matches = $this->matchPattern($content, '/Route::post\s*\(\s*["\'].*login/');
            foreach ($matches as $match) {
                $lines = explode("\n", $content);
                $startSearch = max(0, $match['line'] - 10);
                $searchBlock = implode("\n", array_slice($lines, $startSearch, 15));

                if (! str_contains($searchBlock, 'throttle')) {
                    $results[] = $this->result(
                        $relativePath,
                        $match['line'],
                        'Login route does not appear to have rate limiting.',
                        'Add `throttle:login` middleware: `->middleware(\'throttle:login\')` or define a rate limiter in `AppServiceProvider`.',
                        $this->getSnippet($content, $match['line']),
                    );
                }
            }

            // Check API route files without throttle middleware
            if (str_contains($relativePath, 'api') && ! str_contains($content, 'throttle')) {
                $results[] = $this->result(
                    $relativePath,
                    null,
                    'API route file has no rate limiting middleware applied.',
                    'Add `throttle:api` middleware to API route groups in `bootstrap/app.php` or the route file.',
                );
            }
        }

        return $results;
    }
}
