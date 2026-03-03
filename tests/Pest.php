<?php

declare(strict_types=1);

/*
|--------------------------------------------------------------------------
| Test Helpers
|--------------------------------------------------------------------------
*/

function fixturesPath(string $path = ''): string
{
    return __DIR__.'/fixtures'.($path ? '/'.ltrim($path, '/') : '');
}
