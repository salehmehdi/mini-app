<?php
declare(strict_types=1);

namespace App;

final class Csrf
{
    private const KEY = '_csrf';

    private static function ensureSessionStarted(): void
    {
        if (session_status() === PHP_SESSION_NONE) 
        {
            session_start();
        }
    }

    public static function generateToken(): string
    {
        self::ensureSessionStarted();
        if (empty($_SESSION[self::KEY])) 
        {
            $_SESSION[self::KEY] = bin2hex(random_bytes(32));
        }
        return $_SESSION[self::KEY];
    }

    public static function validate(?string $token): bool
    {
        self::ensureSessionStarted();
        return isset($_SESSION[self::KEY]) && hash_equals((string)$_SESSION[self::KEY], (string)$token);
    }

    public function validateToken(?string $token): bool
    {
        return self::validate($token);
    }
}
