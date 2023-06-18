<?php

namespace MaaximOne\LaAdmin\Providers;

use Illuminate\Support\ServiceProvider;
use MaaximOne\LaAdmin\Classes\LaAdmin;

class LaAdminFacadesProvider extends ServiceProvider
{
    public function register(): void
    {
        $this->app->bind('LaAdmin', function ($app) {
            return new LaAdmin();
        });
    }
}
