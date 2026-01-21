<?php

namespace App\Libraries;

use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\SMTP;
use PHPMailer\PHPMailer\Exception;

class Mailer
{
    protected PHPMailer $mailer;
    protected array $theme;

    public function __construct()
    {
        $this->mailer = new PHPMailer(true);
        $this->theme = include APPPATH . 'Views/email/theme.php';

        // SMTP Configuration
        $this->mailer->isSMTP();
        $this->mailer->Host       = env('MAIL_HOST', 'smtp.gmail.com');
        $this->mailer->SMTPAuth   = true;
        $this->mailer->Username   = env('MAIL_USERNAME', '');
        $this->mailer->Password   = env('MAIL_PASSWORD', '');
        $this->mailer->SMTPSecure = env('MAIL_ENCRYPTION', 'tls') === 'ssl' 
                                    ? PHPMailer::ENCRYPTION_SMTPS 
                                    : PHPMailer::ENCRYPTION_STARTTLS;
        $this->mailer->Port       = (int) env('MAIL_PORT', 587);

        // Default sender
        $this->mailer->setFrom(
            env('MAIL_FROM_ADDRESS', ''),
            env('MAIL_FROM_NAME', $this->theme['brand_name'])
        );
    }

    /**
     * Send password reset email
     */
    public function sendResetEmail(string $to, string $link): array
    {
        return $this->send(
            $to,
            "Password Reset - {$this->theme['brand_name']}",
            'email/reset_password',
            ['link' => $link]
        );
    }

    /**
     * Send welcome email with confirmation link
     */
    public function sendWelcomeEmail(string $to, string $name, string $link): array
    {
        return $this->send(
            $to,
            "Welcome to {$this->theme['brand_name']}",
            'email/welcome',
            ['name' => $name, 'link' => $link]
        );
    }

    /**
     * Core send method - renders view and sends email
     */
    protected function send(string $to, string $subject, string $template, array $data = []): array
    {
        try {
            $this->mailer->clearAddresses();
            $this->mailer->addAddress($to);
            $this->mailer->isHTML(true);
            $this->mailer->Subject = $subject;
            $this->mailer->Body    = view($template, $data);
            $this->mailer->AltBody = strip_tags($this->mailer->Body);

            $this->mailer->send();

            return ['success' => true, 'message' => 'Email sent successfully'];
        } catch (Exception $e) {
            return ['success' => false, 'message' => 'Failed to send email: ' . $this->mailer->ErrorInfo];
        }
    }
}
