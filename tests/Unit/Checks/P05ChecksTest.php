<?php

declare(strict_types=1);

use Fortress\Review\Checks\P05\CurrencyHandlingCheck;
use Fortress\Review\Checks\P05\FloatingPointMoneyCheck;
use Fortress\Review\Checks\P05\MoneyComparisonCheck;
use Fortress\Review\Checks\P05\RoundingModeCheck;
use Fortress\Review\ReviewContext;

it('detects floating-point money in migrations', function () {
    $check = new FloatingPointMoneyCheck;
    $context = new ReviewContext(fixturesPath());

    $results = $check->run($context);

    expect($check->ruleId())->toBe('F-P05-001')
        ->and($check->severity())->toBe('critical')
        ->and($results)->not->toBeEmpty();

    // Should find float('amount') and double('total') in the migration fixture
    $migrationResults = array_filter($results, fn ($r) => str_contains($r->file, 'migrations'));
    expect($migrationResults)->not->toBeEmpty();
});

it('detects float cast on money in models', function () {
    $check = new FloatingPointMoneyCheck;
    $context = new ReviewContext(fixturesPath());

    $results = $check->run($context);

    $modelResults = array_filter($results, fn ($r) => str_contains($r->file, 'FinancialModel'));
    expect($modelResults)->not->toBeEmpty();
});

it('has correct metadata for currency handling check', function () {
    $check = new CurrencyHandlingCheck;

    expect($check->id())->toBe('currency_handling')
        ->and($check->ruleId())->toBe('F-P05-005')
        ->and($check->part())->toBe('P05')
        ->and($check->severity())->toBe('warning');
});

it('has correct metadata for rounding mode check', function () {
    $check = new RoundingModeCheck;

    expect($check->id())->toBe('rounding_mode')
        ->and($check->ruleId())->toBe('F-P05-010')
        ->and($check->severity())->toBe('warning');
});

it('has correct metadata for money comparison check', function () {
    $check = new MoneyComparisonCheck;

    expect($check->id())->toBe('money_comparison')
        ->and($check->ruleId())->toBe('F-P05-015')
        ->and($check->severity())->toBe('warning');
});
