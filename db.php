<?php
declare(strict_types=1);

require __DIR__ . '/vendor/autoload.php';

use App\Config;

$dotenv = Dotenv\Dotenv::createImmutable(__DIR__);
if (file_exists(__DIR__.'/.env')) { $dotenv->load(); } Config::init($_ENV);

$dsn = sprintf(
    'mysql:host=%s;port=%s;dbname=%s;charset=%s',
    Config::get('DB_HOST'),
    Config::get('DB_PORT'),
    Config::get('DB_NAME'),
    Config::get('DB_CHARSET')
);

try 
{
    $pdo = new PDO($dsn, Config::get('DB_USER'), Config::get('DB_PASS'), 
    [
        PDO::ATTR_ERRMODE            => PDO::ERRMODE_EXCEPTION,
        PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC,
        PDO::ATTR_EMULATE_PREPARES   => false,
    ]);
} 
catch (PDOException $e) 
{
    die("DB bağlantısı alınamadı: " . $e->getMessage());
}

return $pdo;
