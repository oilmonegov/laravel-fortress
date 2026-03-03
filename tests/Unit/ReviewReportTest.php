<?php

declare(strict_types=1);

use Fortress\Review\CheckResult;
use Fortress\Review\ReviewReport;

it('generates a markdown report with header', function () {
    $report = new ReviewReport;
    $markdown = $report->generate(
        [],
        'test-review',
        ['P01', 'P05'],
        '2026-03-03 14:30:00',
    );

    expect($markdown)->toContain('# Fortress Review: test-review')
        ->toContain('**Date:** 2026-03-03 14:30:00')
        ->toContain('P01, P05')
        ->toContain('**Total findings:** 0')
        ->toContain('No findings. All checks passed.');
});

it('generates summary table with severity counts', function () {
    $results = [
        new CheckResult('F-P01-001', 'critical', 'app/Test.php', 1, 'Problem 1', 'Fix 1'),
        new CheckResult('F-P01-002', 'critical', 'app/Test.php', 2, 'Problem 2', 'Fix 2'),
        new CheckResult('F-P06-001', 'warning', 'app/Other.php', 1, 'Problem 3', 'Fix 3'),
        new CheckResult('F-P07-020', 'info', 'app/Other.php', 5, 'Problem 4', 'Fix 4'),
    ];

    $report = new ReviewReport;
    $markdown = $report->generate($results, 'test', ['P01', 'P06', 'P07'], '2026-03-03 14:30:00');

    expect($markdown)->toContain('**Total findings:** 4')
        ->toContain('2 critical')
        ->toContain('1 warning')
        ->toContain('1 info')
        ->toContain('| Critical | 2 |')
        ->toContain('| Warning  | 1 |')
        ->toContain('| Info     | 1 |');
});

it('groups findings by part and severity', function () {
    $results = [
        new CheckResult('F-P01-001', 'critical', 'app/A.php', 1, 'SQL injection', 'Use binding'),
        new CheckResult('F-P06-001', 'warning', 'app/B.php', 1, 'Missing strict_types', 'Add declaration'),
    ];

    $report = new ReviewReport;
    $markdown = $report->generate($results, 'test', ['P01', 'P06'], '2026-03-03');

    expect($markdown)->toContain('## Part I — Application Security')
        ->toContain('## Part VI — PHP Language')
        ->toContain('### Critical')
        ->toContain('### Warning');
});

it('includes problem, solution, and snippet for each finding', function () {
    $results = [
        new CheckResult(
            'F-P01-001',
            'critical',
            'app/Services/Search.php',
            42,
            'SQL injection via string interpolation.',
            'Use parameter binding.',
            '> 42| $results = DB::select("SELECT * FROM users WHERE name = \'{$name}\'");',
        ),
    ];

    $report = new ReviewReport;
    $markdown = $report->generate($results, 'test', ['P01'], '2026-03-03');

    expect($markdown)->toContain('[F-P01-001]')
        ->toContain('`app/Services/Search.php:42`')
        ->toContain('**Problem:** SQL injection via string interpolation.')
        ->toContain('**Solution:** Use parameter binding.')
        ->toContain('DB::select');
});

it('sorts findings by file then line within each severity', function () {
    $results = [
        new CheckResult('F-P01-001', 'critical', 'app/B.php', 20, 'Problem', 'Fix'),
        new CheckResult('F-P01-002', 'critical', 'app/A.php', 10, 'Problem', 'Fix'),
        new CheckResult('F-P01-001', 'critical', 'app/A.php', 5, 'Problem', 'Fix'),
    ];

    $report = new ReviewReport;
    $markdown = $report->generate($results, 'test', ['P01'], '2026-03-03');

    // A.php:5 should appear before A.php:10, both before B.php:20
    $posA5 = strpos($markdown, 'app/A.php:5');
    $posA10 = strpos($markdown, 'app/A.php:10');
    $posB20 = strpos($markdown, 'app/B.php:20');

    expect($posA5)->toBeLessThan($posA10)
        ->and($posA10)->toBeLessThan($posB20);
});
