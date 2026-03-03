<?php

declare(strict_types=1);

use Fortress\Review\Checks\P10\ConsoleLogCheck;
use Fortress\Review\Checks\P10\UnescapedOutputCheck;
use Fortress\Review\ReviewContext;

it('detects console.log in JS files', function () {
    $check = new ConsoleLogCheck;
    $context = new ReviewContext(fixturesPath());

    $results = $check->run($context);

    expect($results)->not->toBeEmpty();

    $jsResults = array_filter($results, fn ($r) => str_contains($r->file, 'app.js'));
    expect($jsResults)->not->toBeEmpty();
});

it('detects unescaped Blade output', function () {
    $check = new UnescapedOutputCheck;
    $context = new ReviewContext(fixturesPath());

    $results = $check->run($context);

    expect($results)->not->toBeEmpty();

    $bladeResults = array_filter($results, fn ($r) => str_contains($r->file, 'form.blade.php'));
    expect($bladeResults)->not->toBeEmpty();
});
