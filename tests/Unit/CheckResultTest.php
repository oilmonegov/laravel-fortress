<?php

declare(strict_types=1);

use Fortress\Review\CheckResult;

it('constructs a check result with all properties', function () {
    $result = new CheckResult(
        ruleId: 'F-P01-001',
        severity: 'critical',
        file: 'app/Models/User.php',
        line: 42,
        problem: 'SQL injection detected.',
        solution: 'Use parameter binding.',
        snippet: '$results = DB::select("SELECT * FROM users WHERE name = \'{$name}\'");',
    );

    expect($result->ruleId)->toBe('F-P01-001')
        ->and($result->severity)->toBe('critical')
        ->and($result->file)->toBe('app/Models/User.php')
        ->and($result->line)->toBe(42)
        ->and($result->problem)->toBe('SQL injection detected.')
        ->and($result->solution)->toBe('Use parameter binding.')
        ->and($result->snippet)->toContain('DB::select');
});

it('allows null line number', function () {
    $result = new CheckResult(
        ruleId: 'F-P08-003',
        severity: 'warning',
        file: 'app/Models/Post.php',
        line: null,
        problem: 'Missing $fillable.',
        solution: 'Add $fillable array.',
    );

    expect($result->line)->toBeNull()
        ->and($result->snippet)->toBeNull();
});
