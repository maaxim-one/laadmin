<?php

namespace MaaximOne\LaAdmin\Providers;

use MaaximOne\LaAdmin\Classes\Page\LaAdminPage;
use MaaximOne\LaAdmin\Classes\Role\LaAdminRole;
use Illuminate\Support\ServiceProvider;
use Illuminate\Foundation\Application;
use MaaximOne\LaAdmin\Classes\LaAdmin;

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

        $this->app->bind('LaAdminRole', function (Application $app) {
            return new LaAdminRole();
        });
    }
}
