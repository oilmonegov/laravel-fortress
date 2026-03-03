<?php

declare(strict_types=1);

namespace Fortress;

use Fortress\Commands\CheckCommand;
use Fortress\Commands\HooksCommand;
use Fortress\Commands\InstallCommand;
use Illuminate\Support\ServiceProvider;

class FortressServiceProvider extends ServiceProvider
{
    public function boot(): void
    {
        if ($this->app->runningInConsole()) {
            $this->commands([
                InstallCommand::class,
                HooksCommand::class,
                CheckCommand::class,
            ]);
        }
    }
}
