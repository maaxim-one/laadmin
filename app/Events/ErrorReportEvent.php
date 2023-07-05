<?php

namespace MaaximOne\LaAdmin\Events;

use Illuminate\Foundation\Events\Dispatchable;
use Throwable;

class ErrorReportEvent
{
    use Dispatchable;

    public Throwable $_e;

    public function __construct(Throwable $e)
    {
        $this->_e = $e;
    }
}
