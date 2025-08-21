<?php
namespace App;

use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\Exception;
use App\Config;
use Dotenv\Dotenv;

class Mail
{
    private string $adminEmail;

    public function __construct()
    {
        $dotenv = Dotenv::createImmutable(__DIR__);
        if (file_exists(__DIR__.'/.env')) { $dotenv->load(); } Config::init($_ENV);

        $this->adminEmail = Config::get('ADMIN_EMAIL');
    }

    public function sendAdminNotification(array $data): bool
    {
        $mail = new PHPMailer(true);

        try 
        {
            $mail->isSMTP();
            $mail->Host       = $_ENV['SMTP_HOST'] ?? 'smtp.example.com';
            $mail->SMTPAuth   = true;
            $mail->Username   = $_ENV['SMTP_USER'] ?? 'user@example.com';
            $mail->Password   = $_ENV['SMTP_PASS'] ?? 'password';
            $mail->SMTPSecure = PHPMailer::ENCRYPTION_STARTTLS;
            $mail->Port       = (int)($_ENV['SMTP_PORT'] ?? 587);

            $mail->setFrom($_ENV['SMTP_FROM'] ?? 'no-reply@example.com', 'Qeydiyyat Sistemi');
            $mail->addAddress($this->adminEmail, 'Admin');

            $mail->isHTML(true);
            $mail->Subject = 'Yeni qeydiyyat';

            $body  = "<h2>Yeni qeydiyyat yaradildi</h2>";
            $body .= "<p><b>Ad Soyad:</b> " . htmlspecialchars($data['name'] ?? '-') . "</p>";
            $body .= "<p><b>Email:</b> " . htmlspecialchars($data['email'] ?? '-') . "</p>";
            $body .= "<p><b>company:</b> " . htmlspecialchars($data['company'] ?? '-') . "</p>";
            $body .= "<p><b>Tarih:</b> " . date('Y-m-d H:i:s') . "</p>";

            $mail->Body = $body;

            return $mail->send();
        } 
        catch (Exception $e) 
        {
            error_log("Mail Error: {$mail->ErrorInfo}");
            return false;
        }
    }
}
