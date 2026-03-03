<?php

declare(strict_types=1);

use Fortress\Review\Checks\P07\DebugStatementsCheck;
use Fortress\Review\ReviewContext;

it('detects debug statements in PHP files', function () {
    $check = new DebugStatementsCheck;
    $context = new ReviewContext(fixturesPath());

    $results = $check->run($context);

    expect($results)->not->toBeEmpty();

    // BadController.php has dd()
    $badControllerResults = array_filter($results, fn ($r) => str_contains($r->file, 'BadController'));
    expect($badControllerResults)->not->toBeEmpty();
});

it('has correct metadata', function () {
    $check = new DebugStatementsCheck;

    expect($check->id())->toBe('debug_statements')
        ->and($check->ruleId())->toBe('F-P07-012')
        ->and($check->severity())->toBe('warning')
        ->and($check->part())->toBe('P07');
});
