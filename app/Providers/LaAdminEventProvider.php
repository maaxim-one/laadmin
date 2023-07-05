<?php

namespace MaaximOne\LaAdmin\Providers;

use Illuminate\Foundation\Support\Providers\EventServiceProvider;
use MaaximOne\LaAdmin\Listeners\ListenerErrorReportDataBase;
use MaaximOne\LaAdmin\Listeners\ListenerErrorReportMail;
use MaaximOne\LaAdmin\Events\ErrorReportEvent;

class LaAdminEventProvider extends EventServiceProvider
{
    protected $listen = [
        ErrorReportEvent::class => [
            ListenerErrorReportDataBase::class,
            ListenerErrorReportMail::class,
        ]
    ];
}
