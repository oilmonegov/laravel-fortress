<?php

declare(strict_types=1);

use Fortress\Review\Checks\P14\DebugModeCheck;
use Fortress\Review\Checks\P14\HealthEndpointCheck;
use Fortress\Review\ReviewContext;

it('detects hardcoded debug mode', function () {
    $check = new DebugModeCheck;
    $context = new ReviewContext(fixturesPath());

    $results = $check->run($context);

    expect($results)->not->toBeEmpty();
    expect($results[0]->severity)->toBe('critical');
});

it('detects missing health endpoint', function () {
    $check = new HealthEndpointCheck;
    $context = new ReviewContext(fixturesPath());

    $results = $check->run($context);

    expect($results)->not->toBeEmpty();
    expect($results[0]->problem)->toContain('health check endpoint');
});
