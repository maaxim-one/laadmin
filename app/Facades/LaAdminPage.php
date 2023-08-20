<?php

namespace MaaximOne\LaAdmin\Facades;

use MaaximOne\LaAdmin\Classes\Page\Page;
use Illuminate\Support\Facades\Facade;

/**
 * @method static \MaaximOne\LaAdmin\Classes\Page\LaAdminPage make($pageName)
 * @method static Page getPage(string $pageName)
 * @method static array __toResponse()
 *
 * @see \MaaximOne\LaAdmin\Classes\Page\LaAdminPage
 */
class LaAdminPage extends Facade
{
    protected static function getFacadeAccessor(): string
    {
        return 'LaAdminPage';
    }
}
