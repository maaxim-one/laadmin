<?php

namespace MaaximOne\LaAdmin\Providers;

use Illuminate\Support\ServiceProvider;
use Illuminate\Support\Facades\Route;

class LaAdminRouteProvider extends ServiceProvider
{
    /**
     * Bootstrap services.
     */
    public function boot(): void
    {
//        Route::group([
//            'as' => 'laadmin.',
//            'middleware' => ['web', 'IsAdmin'],
//            'prefix' => 'admin',
//        ], function () {
//            if (file_exists(base_path('routes/admin.php'))) {
//                $this->loadRoutesFrom(base_path('routes/admin.php'));
//            }
//        });

        Route::group([
            'namespace' => 'MaaximOne\LaAdmin\Http\Controllers',
            'middleware' => 'web',
            'prefix' => 'admin',
            'as' => 'laadmin.',
        ], function () {
            $this->loadRoutesFrom(__DIR__ . '/../../routes/web.php');
        });
    }
}
