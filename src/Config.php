<?php
declare(strict_types=1);

namespace App;

final class Config
{
    private static array $data = [];

    public static function init(array $env): void
    {
        self::$data = $env;
    }

    public static function get(string $key, mixed $default = null): mixed
    {
        return self::$data[$key] ?? $default;
    }
}
