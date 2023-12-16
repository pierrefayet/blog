<?php

namespace App\Service;

use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\Exception;

class Mailer
{
    private PHPMailer $mailer;

    public function __construct()
    {
        $this->mailer = new PHPMailer();
    }

    /**
     * @throws Exception
     */
    public function send(string $from_name, string $from_email, string $subject, string $message): bool
    {
        $this->mailer->SMTPDebug = 0;
        $this->mailer->isSMTP();
        $this->mailer->Host = 'smtp.gmail.com';
        $this->mailer->SMTPAuth = true;
        $this->mailer->Username = 'piero69450@gmail.com';
        $this->mailer->Password = 'wtuf dzge nyej ahzn';
        $this->mailer->SMTPSecure = PHPMailer::ENCRYPTION_SMTPS;
        $this->mailer->Port = 465;
        $this->mailer->setFrom($from_email, $from_name);
        $this->mailer->addAddress('piero69450@gmail.com', 'contact@gmail.com');
        $this->mailer->isHTML(true);
        $this->mailer->Subject = $subject;
        $this->mailer->Body = $message;
        $this->mailer->send();

        return true;
    }
}
