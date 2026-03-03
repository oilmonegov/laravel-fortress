<?php

declare(strict_types=1);

namespace Fortress\Review\Checks\P14;

use Fortress\Review\BaseCheck;
use Fortress\Review\CheckResult;
use Fortress\Review\ReviewContext;

class HealthEndpointCheck extends BaseCheck
{
    public function id(): string
    {
        return 'health_endpoint';
    }

    public function ruleId(): string
    {
        return 'F-P14-010';
    }

    public function part(): string
    {
        return 'P14';
    }

    public function severity(): string
    {
        return 'info';
    }

    public function description(): string
    {
        return 'Check if a health check endpoint is defined';
    }

    /** @return CheckResult[] */
    public function run(ReviewContext $context): array
    {
        $results = [];
        $hasHealthRoute = false;

        foreach ($context->routeFiles() as $file) {
            $content = $context->content($file->getRealPath());

            if (preg_match('/["\']\/(?:health|up|status|ping)["\']/', $content)) {
                $hasHealthRoute = true;
                break;
            }
        }

        if (! $hasHealthRoute) {
            $results[] = $this->result(
                'routes/',
                null,
                'No health check endpoint (`/health`, `/up`, `/status`) found in route files.',
                'Add a health check route: `Route::get(\'/up\', fn () => response(\'OK\'))->name(\'health\');` or use Laravel\'s built-in `/up` endpoint.',
            );
        }

        return $results;
    }
}
