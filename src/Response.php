<?php
declare(strict_types=1);

namespace App;

final class Response
{
    public static function json(array $payload, int $status = 200): void
    {
        http_response_code($status);
        header('Content-Type: application/json; charset=utf-8');
        echo json_encode($payload, JSON_UNESCAPED_UNICODE);
        exit;
    }

    public static function jsonOk(string $message, array $data = []): void
    {
        self::json(
        [
            'status'  => 'success',
            'message' => $message,
            'data'    => $data
        ], 200);
    }

    public static function jsonError(string $message, array $fields = [], int $status = 400): void
    {
        self::json(
        [
            'status'  => 'error',
            'message' => $message,
            'fields'  => $fields
        ], $status);
    }
}
