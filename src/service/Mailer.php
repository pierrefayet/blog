<?php
//Import PHPMailer classes into the global namespace
//These must be at the top of your script, not inside a function
namespace App\service;

use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\SMTP;
use PHPMailer\PHPMailer\Exception;

//Create an instance; passing `true` enables exceptions
class Mailer
{
    private PHPMailer $mailer;

    public function __construct()
    {
        $this->mailer = new PHPMailer();
    }

    public function send( string $data): void
    {
        try {
            $this->mailer->setFrom('piero69450@gmail.com', 'Mailer');
            $this->mailer->addAddress('piero69450@gmail.com', 'Piero');
            $this->mailer->isHTML(true);
            $this->mailer->Subject = 'Contact blog';
            $this->mailer->Body = 'This is the HTML message body <b>in bold!</b>';
            $this->mailer->send();
        } catch (Exception $e) {
            echo "Message could not be sent. Mailer Error: {$this->mailer->ErrorInfo}";
        }
    }
}
