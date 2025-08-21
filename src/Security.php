<?php
declare(strict_types=1);

namespace App;

final class Security
{
    public static function sanitize(string $str): string
    {
        return htmlspecialchars($str, ENT_QUOTES | ENT_SUBSTITUTE, 'UTF-8');
    }
}
