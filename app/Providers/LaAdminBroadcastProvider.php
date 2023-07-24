<?php

namespace MaaximOne\LaAdmin\Providers;

use Illuminate\Support\ServiceProvider;

class LaAdminBroadcastProvider extends ServiceProvider
{
    public function boot(): void
    {
        require __DIR__ . '/../../routes/channels.php';
    }
}
