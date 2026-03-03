<?php

declare(strict_types=1);

use Fortress\Review\Checks\P12\QueueRetryCheck;
use Fortress\Review\Checks\P12\UnvalidatedApiInputCheck;
use Fortress\Review\ReviewContext;

it('detects queue jobs missing retry properties', function () {
    $check = new QueueRetryCheck;
    $context = new ReviewContext(fixturesPath());

    $results = $check->run($context);

    expect($results)->not->toBeEmpty();

    $orderResults = array_filter($results, fn ($r) => str_contains($r->file, 'ProcessOrder'));
    expect($orderResults)->not->toBeEmpty();
    expect(array_values($orderResults)[0]->problem)->toContain('$tries');
});

it('detects unvalidated API input', function () {
    $check = new UnvalidatedApiInputCheck;
    $context = new ReviewContext(fixturesPath());

    $results = $check->run($context);

    expect($results)->not->toBeEmpty();

    // BadController uses $request->all()
    $controllerResults = array_filter($results, fn ($r) => str_contains($r->file, 'BadController'));
    expect($controllerResults)->not->toBeEmpty();
});
