<?php

namespace MaaximOne\LaAdmin\Classes;

class LaAdmin
{
    protected static string $version;

    public function __construct()
    {
        self::$version = json_decode(file_get_contents(__DIR__ . '/../../composer.json'))->version;
    }
}
