<?php

namespace MaaximOne\LaAdmin\Facades;

use Illuminate\Support\Facades\Facade;

/**
 *
 * @method static array __toResponse()
 *
 * @see \MaaximOne\LaAdmin\Classes\Role\LaAdminRole
 */
class LaAdminRole extends Facade
{
    protected static function getFacadeAccessor(): string
    {
        return 'LaAdminRole';
    }
}
