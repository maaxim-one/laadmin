<?php

namespace MaaximOne\LaAdmin\Providers;

use MaaximOne\LaAdmin\Http\Middleware\IsAdmin;
use Illuminate\Support\ServiceProvider;

class LaAdminServiceProvider extends ServiceProvider
{
    public function register(): void
    {
        $this->app->registerDeferredProvider(LaAdminRouteProvider::class);
        $this->app->registerDeferredProvider(LaAdminFacadesProvider::class);

        app('router')->pushMiddlewareToGroup('IsAdmin', IsAdmin::class);

        $this->loadMigrationsFrom(__DIR__ . '/../../database/migrations');
    }

    public function boot(): void
    {
        $this->loadViewsFrom(__DIR__ . '/../../resources/views', 'laadmin');
    }
}
