<?php

declare(strict_types=1);

use Fortress\Review\CheckResult;
use Fortress\Review\ReviewRunner;

it('discovers all 52 check classes', function () {
    $runner = new ReviewRunner(fixturesPath());

    expect($runner->totalChecks())->toBe(52);
});

it('returns available parts', function () {
    $runner = new ReviewRunner(fixturesPath());
    $parts = $runner->getAvailableParts();

    expect($parts)->toHaveKeys(['P01', 'P02', 'P03', 'P04', 'P05', 'P06', 'P07', 'P08', 'P09', 'P10', 'P11', 'P12', 'P13', 'P14'])
        ->and($parts['P01'])->toBe('Application Security')
        ->and($parts['P05'])->toBe('Financial Accuracy');
});

it('filters checks by part', function () {
    $runner = new ReviewRunner(fixturesPath());
    $runner->filterByParts(['P01']);

    expect($runner->totalChecks())->toBe(8);
});

it('filters checks by multiple parts', function () {
    $runner = new ReviewRunner(fixturesPath());
    $runner->filterByParts(['P01', 'P05']);

    expect($runner->totalChecks())->toBe(12); // 8 + 4
});

it('runs checks and returns results', function () {
    $runner = new ReviewRunner(fixturesPath());
    $runner->filterByParts(['P06']); // PHP Language checks

    $results = $runner->run();

    expect($results)->toBeArray()
        ->and($results)->not->toBeEmpty();

    // Should find missing strict_types in BadModel.php
    $strictTypeResults = array_filter($results, fn (CheckResult $r) => $r->ruleId === 'F-P06-001');
    expect($strictTypeResults)->not->toBeEmpty();
});

it('filters results by minimum severity', function () {
    $runner = new ReviewRunner(fixturesPath());
    $runner->filterBySeverity('critical');

    $results = $runner->run();

    foreach ($results as $result) {
        expect($result->severity)->toBe('critical');
    }
});

it('calls progress callback during run', function () {
    $runner = new ReviewRunner(fixturesPath());
    $runner->filterByParts(['P14']); // Small part, 2 checks

    $progressCalls = [];
    $runner->run(function ($check, $current, $total) use (&$progressCalls) {
        $progressCalls[] = ['current' => $current, 'total' => $total];
    });

    expect($progressCalls)->toHaveCount(2)
        ->and($progressCalls[0]['current'])->toBe(1)
        ->and($progressCalls[0]['total'])->toBe(2)
        ->and($progressCalls[1]['current'])->toBe(2);
});

it('returns minimal results for empty directory', function () {
    $runner = new ReviewRunner('/tmp/nonexistent-fortress-test-dir');
    $runner->filterByParts(['P06']); // PHP checks only — no files = no results

    $results = $runner->run();

    expect($results)->toBeEmpty();
});
