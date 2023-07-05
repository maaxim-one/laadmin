<?php

namespace MaaximOne\LaAdmin\Listeners;

use MaaximOne\LaAdmin\Mail\LaAdminErrorReportMail;
use MaaximOne\LaAdmin\Events\ErrorReportEvent;
use Illuminate\Support\Facades\Mail;

class ListenerErrorReportMail
{
    public function handle(ErrorReportEvent $event): void
    {
        if (config('laadmin.report_error_email')) {
            Mail::send(new LaAdminErrorReportMail($event->_e));
        }
    }
}
