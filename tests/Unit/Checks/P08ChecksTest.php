<?php

declare(strict_types=1);

use Fortress\Review\Checks\P08\FillableCheck;
use Fortress\Review\Checks\P08\RawEnvCheck;
use Fortress\Review\ReviewContext;

it('detects models without $fillable', function () {
    $check = new FillableCheck;
    $context = new ReviewContext(fixturesPath());

    $results = $check->run($context);

    expect($results)->not->toBeEmpty();

    // BadModel has no $fillable
    $badModelResults = array_filter($results, fn ($r) => str_contains($r->file, 'BadModel'));
    expect($badModelResults)->not->toBeEmpty();

    // User has $fillable — should NOT be flagged
    $userResults = array_filter($results, fn ($r) => str_contains($r->file, 'User.php'));
    expect($userResults)->toBeEmpty();
});

it('skips config files for raw env check', function () {
    $check = new RawEnvCheck;
    $context = new ReviewContext(fixturesPath());

    $results = $check->run($context);

    // config/app.php uses env() — should NOT be flagged
    $configResults = array_filter($results, fn ($r) => str_starts_with($r->file, 'config/'));
    expect($configResults)->toBeEmpty();
});
