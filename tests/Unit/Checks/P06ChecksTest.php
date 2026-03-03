<?php

declare(strict_types=1);

use Fortress\Review\Checks\P06\DeprecatedFeaturesCheck;
use Fortress\Review\Checks\P06\NullCoalescingCheck;
use Fortress\Review\Checks\P06\StrictComparisonCheck;
use Fortress\Review\Checks\P06\StrictTypesCheck;
use Fortress\Review\Checks\P06\TypeDeclarationCheck;
use Fortress\Review\ReviewContext;

it('detects missing strict_types declaration', function () {
    $check = new StrictTypesCheck;
    $context = new ReviewContext(fixturesPath());

    $results = $check->run($context);

    expect($results)->not->toBeEmpty();

    // BadModel.php is missing strict_types
    $badModelResults = array_filter($results, fn ($r) => str_contains($r->file, 'BadModel'));
    expect($badModelResults)->not->toBeEmpty();

    // User.php has strict_types — should NOT be in results
    $userResults = array_filter($results, fn ($r) => str_contains($r->file, 'User.php'));
    expect($userResults)->toBeEmpty();
});

it('has correct severity for strict types', function () {
    $check = new StrictTypesCheck;

    expect($check->severity())->toBe('warning')
        ->and($check->ruleId())->toBe('F-P06-001');
});

it('has correct metadata for all P06 checks', function () {
    $checks = [
        [new StrictTypesCheck, 'F-P06-001', 'warning'],
        [new TypeDeclarationCheck, 'F-P06-005', 'warning'],
        [new StrictComparisonCheck, 'F-P06-010', 'warning'],
        [new NullCoalescingCheck, 'F-P06-015', 'info'],
        [new DeprecatedFeaturesCheck, 'F-P06-020', 'warning'],
    ];

    foreach ($checks as [$check, $ruleId, $severity]) {
        expect($check->ruleId())->toBe($ruleId)
            ->and($check->severity())->toBe($severity)
            ->and($check->part())->toBe('P06');
    }
});
