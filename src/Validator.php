<?php
declare(strict_types=1);

namespace App;

final class Validator 
{
    private array $errors = [];

    public function required(string $field, ?string $value, string $message): void
    {
        if (empty($value) || trim($value) === '') 
        {
            $this->errors[$field] = $message;
        }
    }

    public function email(string $field, ?string $value, string $message): void
    {
        if (!empty($value) && !filter_var($value, FILTER_VALIDATE_EMAIL)) 
        {
            $this->errors[$field] = $message;
        }
    }

    public function maxLength(string $field, ?string $value, int $max, string $message = ''): void
    {
        if ($value !== null && mb_strlen($value) > $max) 
        {
            $this->errors[$field] = $message ?: "Maksimum $max simvol ola bilər";
        }
    }

    public function hasErrors(): bool
    {
        return !empty($this->errors);
    }

    public function getErrors(): array
    {
        return $this->errors;
    }
}
