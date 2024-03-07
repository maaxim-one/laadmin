<?php

namespace MaaximOne\LaAdmin\Providers;

use MaaximOne\LaAdmin\Http\Middleware\CheckAccept;
use MaaximOne\LaAdmin\Http\Middleware\IsAdmin;
use MaaximOne\LaAdmin\Facades\LaAdminRole;
use Illuminate\Support\ServiceProvider;

class LaAdminServiceProvider extends ServiceProvider
{
    public function register(): void
    {
        app('router')->pushMiddlewareToGroup('IsAdmin', IsAdmin::class);
        app('router')->pushMiddlewareToGroup('CheckAccept', CheckAccept::class);

        $this->mergeConfigFrom(__DIR__ . '/../../config/la-admin.php', 'laadmin');
        $this->loadMigrationsFrom(__DIR__ . '/../../database/migrations');
    }

    public function boot(): void
    {
        $this->loadViewsFrom(__DIR__ . '/../../resources/views', 'laadmin');

        $this->app->registerDeferredProvider(LaAdminEventProvider::class);
        $this->app->registerDeferredProvider(LaAdminRouteProvider::class);
        $this->app->registerDeferredProvider(LaAdminFacadesProvider::class);
        $this->app->registerDeferredProvider(LaAdminCommandsProvider::class);
        $this->app->registerDeferredProvider(LaAdminBroadcastProvider::class);

        if (file_exists(app_path('laadmin/pages.php'))) {
            require_once app_path('laadmin/pages.php');
        }

        $this->setDefaultRules();
    }

    public function setDefaultRules(): void
    {
        LaAdminRole::make('users')
            ->setAbbreviation('Пользователи')
            ->setCustomRule('reset', false, 'Сброс паролей');

        LaAdminRole::make('roles')
            ->setAbbreviation('Роли');

        LaAdminRole::make('errors')
            ->setAbbreviation('Ошибки')
            ->setCustomParams([
                "read" => [
                    "value" => false,
                    "abbreviation" => "Просматривать"
                ],
                "fixed" => [
                    "value" => false,
                    "abbreviation" => "Отмечать как \"Исправлено\""
                ],
                "comment" => [
                    "value" => false,
                    "abbreviation" => "Комментировать"
                ]
            ]);
    }
}
