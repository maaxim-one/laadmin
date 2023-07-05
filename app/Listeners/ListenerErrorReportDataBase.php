<?php

namespace MaaximOne\LaAdmin\Listeners;

use MaaximOne\LaAdmin\Events\ErrorReportEvent;
use MaaximOne\LaAdmin\Models\ErrorReport;

class ListenerErrorReportDataBase
{
    public function handle(ErrorReportEvent $event): void
    {
        $report = new ErrorReport();
        $report->report_file = $event->_e->getFile();
        $report->report_code = $event->_e->getCode();
        $report->report_line = $event->_e->getLine();
        $report->report_message = $event->_e->getMessage();
        $report->report_events = [
            [
                'text' => 'Создано',
                'date' => now()->format($report->getDateFormat()),
                'color' => 'error',
                'user' => [
                    'name' => 'System'
                ],
            ]
        ];
        $report->save();
    }
}
