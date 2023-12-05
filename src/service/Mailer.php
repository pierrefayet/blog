<?php
//Import PHPMailer classes into the global namespace
//These must be at the top of your script, not inside a function
use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\SMTP;
use PHPMailer\PHPMailer\Exception;

//Load Composer's autoloader
require 'vendor/autoload.php';

//Create an instance; passing `true` enables exceptions
class Mailer {
    private PHPMailer $mailer;
   public function __construct(PHPMailer $mailer) {
       $this->mailer =$mailer;
   }

   public function send($data) {

   }
}