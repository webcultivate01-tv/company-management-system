<?php
use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\SMTP;
use PHPMailer\PHPMailer\Exception;

class MailConfig {
    // ─── CONFIGURE YOUR SMTP CREDENTIALS HERE ────────────────────────────────
    public static string $host     = 'smtp.gmail.com';
    public static int    $port     = 587;
    public static string $username = 'your_email@gmail.com';
    public static string $password = 'your_app_password';
    public static string $fromName = 'CompanyMS Admin';
    public static string $fromEmail= 'your_email@gmail.com';
    // ─────────────────────────────────────────────────────────────────────────

    public static function createMailer(): PHPMailer {
        $mail = new PHPMailer(true);
        $mail->isSMTP();
        $mail->Host       = self::$host;
        $mail->SMTPAuth   = true;
        $mail->Username   = self::$username;
        $mail->Password   = self::$password;
        $mail->SMTPSecure = PHPMailer::ENCRYPTION_STARTTLS;
        $mail->Port       = self::$port;
        $mail->setFrom(self::$fromEmail, self::$fromName);
        $mail->isHTML(true);
        $mail->CharSet = 'UTF-8';
        return $mail;
    }
}
