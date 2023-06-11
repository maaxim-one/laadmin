<?php

namespace MaaximOne\LaAdmin\Providers;

use Illuminate\Support\ServiceProvider;

class LaAdminServiceProvider extends ServiceProvider
{
    public function register(): void
    {
        $this->app->registerDeferredProvider(LaAdminRouteProvider::class);
    }

    public function boot(): void
    {
        $this->loadViewsFrom(__DIR__ . '/../../resources/views', 'laadmin');
    }
}
