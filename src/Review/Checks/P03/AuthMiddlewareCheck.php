<?php

declare(strict_types=1);

namespace Fortress\Review\Checks\P03;

use Fortress\Review\BaseCheck;
use Fortress\Review\CheckResult;
use Fortress\Review\ReviewContext;

class AuthMiddlewareCheck extends BaseCheck
{
    public function id(): string
    {
        return 'auth_middleware';
    }

    public function ruleId(): string
    {
        return 'F-P03-001';
    }

    public function part(): string
    {
        return 'P03';
    }

    public function severity(): string
    {
        return 'critical';
    }

    public function description(): string
    {
        return 'Detect route definitions that may be missing authentication middleware';
    }

    /** @return CheckResult[] */
    public function run(ReviewContext $context): array
    {
        $results = [];

        foreach ($context->routeFiles() as $file) {
            $path = $file->getRealPath();
            $content = $context->content($path);
            $relativePath = $this->fileRelativePath($path, $context->basePath());

            // Skip if file name suggests public routes
            $basename = basename($relativePath);
            if (in_array($basename, ['console.php', 'channels.php'], true)) {
                continue;
            }

            // Find route definitions not inside a middleware group
            $matches = $this->matchPattern($content, '/Route::(?:get|post|put|patch|delete)\s*\(\s*["\']\/(?!login|register|password|forgot|reset|verify|health|up|sanctum)/');
            foreach ($matches as $match) {
                // Check if there's middleware('auth') in the surrounding context
                $lines = explode("\n", $content);
                $startSearch = max(0, $match['line'] - 15);
                $searchBlock = implode("\n", array_slice($lines, $startSearch, 20));

                if (! preg_match('/middleware\s*\(\s*.*auth/', $searchBlock) && ! str_contains($searchBlock, "->middleware('auth')") && ! str_contains($searchBlock, "->middleware(['auth")) {
                    // Only flag if no auth middleware found in the surrounding context
                    $results[] = $this->result(
                        $relativePath,
                        $match['line'],
                        'Route definition may be missing `auth` middleware.',
                        'Wrap in a `Route::middleware(\'auth\')` group or add `->middleware(\'auth\')` to the route.',
                        $this->getSnippet($content, $match['line']),
                    );
                }
            }
        }

        return $results;
    }
}
