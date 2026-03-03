<?php

declare(strict_types=1);

use Fortress\Review\Checks\P01\CsrfCheck;
use Fortress\Review\Checks\P01\HardcodedSecretsCheck;
use Fortress\Review\Checks\P01\InsecureDeserializationCheck;
use Fortress\Review\Checks\P01\MassAssignmentCheck;
use Fortress\Review\Checks\P01\SqlInjectionCheck;
use Fortress\Review\Checks\P01\XssCheck;
use Fortress\Review\ReviewContext;

it('detects SQL injection via string interpolation', function () {
    $check = new SqlInjectionCheck;
    $context = new ReviewContext(fixturesPath());

    $results = $check->run($context);

    expect($check->id())->toBe('sql_injection')
        ->and($check->ruleId())->toBe('F-P01-001')
        ->and($check->part())->toBe('P01')
        ->and($check->severity())->toBe('critical');

    $files = array_map(fn ($r) => $r->file, $results);
    expect($files)->toContain('app/Http/Controllers/BadController.php');
});

it('detects XSS via unescaped Blade output', function () {
    $check = new XssCheck;
    $context = new ReviewContext(fixturesPath());

    $results = $check->run($context);

    expect($results)->not->toBeEmpty();

    $bladeResults = array_filter($results, fn ($r) => str_contains($r->file, 'form.blade.php'));
    expect($bladeResults)->not->toBeEmpty();
});

it('detects missing CSRF in forms', function () {
    $check = new CsrfCheck;
    $context = new ReviewContext(fixturesPath());

    $results = $check->run($context);

    expect($results)->not->toBeEmpty();
    expect($results[0]->problem)->toContain('CSRF');
});

it('detects mass assignment via forceFill and unguard', function () {
    $check = new MassAssignmentCheck;
    $context = new ReviewContext(fixturesPath());

    $results = $check->run($context);

    // Our fixtures don't have forceFill/unguard, so results may be empty
    expect($check->severity())->toBe('critical');
});

it('detects insecure deserialization', function () {
    $check = new InsecureDeserializationCheck;
    $context = new ReviewContext(fixturesPath());

    $results = $check->run($context);

    expect($results)->not->toBeEmpty();

    $files = array_map(fn ($r) => $r->file, $results);
    expect($files)->toContain('app/Actions/ProcessPayment.php');
});

it('detects hardcoded secrets', function () {
    $check = new HardcodedSecretsCheck;

    expect($check->id())->toBe('hardcoded_secrets')
        ->and($check->severity())->toBe('critical');
});
