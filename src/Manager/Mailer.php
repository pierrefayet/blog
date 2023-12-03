<?php

namespace App\Manager;

use Symfony\Component\Mailer\Transport;
use Symfony\Component\Mailer\Mailer as SymfonyMailer;
use Symfony\Component\Mime\Email;

class Mailer {
    private $mailer;

    public function __construct() {
        // Configurez votre transport ici
        $transport = Transport::fromDsn('smtp://username:password@host:port');
        $this->mailer = new SymfonyMailer($transport);
    }

    public function sendEmail($from, $to, $subject, $text, $html) {
        $email = (new Email())
            ->from($from)
            ->to($to)
            ->subject($subject)
            ->text($text)
            ->html($html);

        try {
            $this->mailer->send($email);
        } catch (\Symfony\Component\Mailer\Exception\TransportExceptionInterface $e) {
            throw $e;
        }
    }
}