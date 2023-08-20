<?php

namespace MaaximOne\LaAdmin\Classes;

class LaAdmin
{
    public static string $version;

    public function __construct()
    {
        self::$version = json_decode(file_get_contents(__DIR__ . '/../../composer.json'))->version;
    }

    /**
     * @return string
     */
    public static function getVersion(): string
    {
        return self::$version;
    }
}
