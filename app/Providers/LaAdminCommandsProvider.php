<?php

namespace MaaximOne\LaAdmin\Providers;

use Illuminate\Support\ServiceProvider;
use MaaximOne\LaAdmin\Console\Commands\LaAdminInstall;

class LaAdminCommandsProvider extends ServiceProvider
{
    public function boot(): void
    {
        $this->commands([
            LaAdminInstall::class
        ]);
    }
}
