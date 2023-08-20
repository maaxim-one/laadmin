<?php

namespace MaaximOne\LaAdmin\Providers;

use Illuminate\Foundation\Application;
use Illuminate\Support\ServiceProvider;
use MaaximOne\LaAdmin\Classes\LaAdmin;
use MaaximOne\LaAdmin\Classes\Page\LaAdminPage;

class LaAdminFacadesProvider extends ServiceProvider
{
    public function register(): void
    {
        $this->app->bind('LaAdmin', function (Application $app) {
            return new LaAdmin();
        });

        $this->app->bind('LaAdminPage', function (Application $app) {
            return new LaAdminPage();
        });
    }
}
