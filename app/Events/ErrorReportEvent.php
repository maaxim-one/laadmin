<?php

namespace MaaximOne\LaAdmin\Events;

use Illuminate\Contracts\Broadcasting\ShouldBroadcast;
use Illuminate\Foundation\Events\Dispatchable;
use Throwable;

class ErrorReportEvent implements ShouldBroadcast
{
    use Dispatchable;

    public Throwable $_e;

    public function __construct(Throwable $e)
    {
        $this->_e = $e;
    }

    public function broadcastOn(): string
    {
        return 'newError';
    }
}
