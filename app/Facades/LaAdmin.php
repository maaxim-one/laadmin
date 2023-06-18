<?php

namespace MaaximOne\LaAdmin\Facades;

use Illuminate\Support\Facades\Facade;

/**
 * @see \MaaximOne\LaAdmin\Classes\LaAdmin
 */
class LaAdmin extends Facade
{
    protected static function getFacadeAccessor(): string
    {
        return 'LaAdmin';
    }
}
