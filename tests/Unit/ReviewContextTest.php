<?php

declare(strict_types=1);

use Fortress\Review\ReviewContext;

it('discovers php files from app, config, database, and routes directories', function () {
    $context = new ReviewContext(fixturesPath());

    $phpFiles = $context->phpFiles();

    expect($phpFiles)->not->toBeEmpty();

    $filenames = array_map(fn ($f) => $f->getFilename(), $phpFiles);
    expect($filenames)->toContain('BadModel.php')
        ->toContain('User.php')
        ->toContain('BadController.php')
        ->toContain('ProcessPayment.php')
        ->toContain('app.php')
        ->toContain('web.php');
});

it('discovers blade files', function () {
    $context = new ReviewContext(fixturesPath());

    $bladeFiles = $context->bladeFiles();

    expect($bladeFiles)->not->toBeEmpty();

    $filenames = array_map(fn ($f) => $f->getFilename(), $bladeFiles);
    expect($filenames)->toContain('form.blade.php');
});

it('discovers js files', function () {
    $context = new ReviewContext(fixturesPath());

    $jsFiles = $context->jsFiles();

    expect($jsFiles)->not->toBeEmpty();

    $filenames = array_map(fn ($f) => $f->getFilename(), $jsFiles);
    expect($filenames)->toContain('app.js');
});

it('discovers migration files', function () {
    $context = new ReviewContext(fixturesPath());

    $migrations = $context->migrationFiles();

    expect($migrations)->not->toBeEmpty();

    $filenames = array_map(fn ($f) => $f->getFilename(), $migrations);
    expect($filenames)->toContain('2024_01_01_000000_create_orders_table.php');
});

it('discovers model files', function () {
    $context = new ReviewContext(fixturesPath());

    $models = $context->modelFiles();

    expect($models)->not->toBeEmpty();

    $filenames = array_map(fn ($f) => $f->getFilename(), $models);
    expect($filenames)->toContain('User.php')
        ->toContain('BadModel.php')
        ->toContain('FinancialModel.php');
});

it('discovers controller files', function () {
    $context = new ReviewContext(fixturesPath());

    $controllers = $context->controllerFiles();

    expect($controllers)->not->toBeEmpty();

    $filenames = array_map(fn ($f) => $f->getFilename(), $controllers);
    expect($filenames)->toContain('BadController.php');
});

it('discovers job files', function () {
    $context = new ReviewContext(fixturesPath());

    $jobs = $context->jobFiles();

    expect($jobs)->not->toBeEmpty();

    $filenames = array_map(fn ($f) => $f->getFilename(), $jobs);
    expect($filenames)->toContain('ProcessOrder.php');
});

it('caches file content', function () {
    $context = new ReviewContext(fixturesPath());

    $path = fixturesPath('config/app.php');
    $content1 = $context->content($path);
    $content2 = $context->content($path);

    expect($content1)->toBe($content2)
        ->and($content1)->toContain('APP_NAME');
});

it('returns base path', function () {
    $context = new ReviewContext('/some/path');

    expect($context->basePath())->toBe('/some/path');
});

it('returns empty arrays for missing directories', function () {
    $context = new ReviewContext('/nonexistent/path');

    expect($context->phpFiles())->toBeEmpty()
        ->and($context->jsFiles())->toBeEmpty()
        ->and($context->vueFiles())->toBeEmpty()
        ->and($context->bladeFiles())->toBeEmpty()
        ->and($context->migrationFiles())->toBeEmpty()
        ->and($context->modelFiles())->toBeEmpty()
        ->and($context->controllerFiles())->toBeEmpty()
        ->and($context->jobFiles())->toBeEmpty()
        ->and($context->actionFiles())->toBeEmpty()
        ->and($context->testFiles())->toBeEmpty()
        ->and($context->routeFiles())->toBeEmpty()
        ->and($context->configFiles())->toBeEmpty();
});
