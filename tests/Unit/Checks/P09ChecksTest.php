<?php

declare(strict_types=1);

use Fortress\Review\Checks\P09\MissingIndexCheck;
use Fortress\Review\Checks\P09\SoftDeletesCheck;
use Fortress\Review\Checks\P09\TimestampColumnsCheck;
use Fortress\Review\ReviewContext;

it('detects foreign key columns without indexes', function () {
    $check = new MissingIndexCheck;
    $context = new ReviewContext(fixturesPath());

    $results = $check->run($context);

    expect($results)->not->toBeEmpty();

    // Migration has unsignedBigInteger('user_id') without index
    $userIdResults = array_filter($results, fn ($r) => str_contains($r->problem, 'user_id'));
    expect($userIdResults)->not->toBeEmpty();
});

it('detects models without SoftDeletes', function () {
    $check = new SoftDeletesCheck;
    $context = new ReviewContext(fixturesPath());

    $results = $check->run($context);

    expect($results)->not->toBeEmpty();

    // BadModel and FinancialModel lack SoftDeletes
    $badModelResults = array_filter($results, fn ($r) => str_contains($r->file, 'BadModel'));
    expect($badModelResults)->not->toBeEmpty();

    // User has SoftDeletes — should NOT be flagged
    $userResults = array_filter($results, fn ($r) => str_contains($r->file, 'User.php'));
    expect($userResults)->toBeEmpty();
});

it('detects missing timestamps in create table migrations', function () {
    $check = new TimestampColumnsCheck;
    $context = new ReviewContext(fixturesPath());

    $results = $check->run($context);

    expect($results)->not->toBeEmpty();
});
