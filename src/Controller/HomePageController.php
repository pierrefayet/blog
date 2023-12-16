<?php

namespace App\Controller;

use App\Service\CheckForm;
use App\Service\Mailer;
use App\Service\SecurityCsrf;
use PHPMailer\PHPMailer\Exception;
use Twig\Environment;
use Twig\Error\LoaderError;
use Twig\Error\RuntimeError;
use Twig\Error\SyntaxError;

class HomePageController
{
    private Mailer $mailer;
    private Environment $twig;


    public function __construct(Mailer $mailer, Environment $twig)
    {
        $this->twig = $twig;
        $this->mailer = $mailer;
    }

    /**
     * @throws RuntimeError
     * @throws SyntaxError
     * @throws LoaderError
     * @throws Exception
     */
    public function home(): string
    {
        $params = [];
        $url = "/public/asset/cv/Cv_Pierre_Fayet.pdf";
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            if (SecurityCsrf::check($_POST)) {
                $from_name = $_POST['name'];
                $from_email = $_POST['email'];
                $subject = $_POST['subject'];
                $message = $_POST['message'];
                $errors = CheckForm::checkFormMail($from_name, $from_email, $subject, $message);

                if (!empty($errors)) {
                    $params['errorMessage'] = implode('<br>', $errors);
                }

                if (!$this->mailer->send($from_name, $from_email, $subject, $message)) {
                    $params['errorMessage'] = ['Un probléme est survenu, veuillez contacter l\'administrateur.'];

                    return $this->twig->load('homePage.twig')->render([
                        'cvUrl' => $url, 'params' => $params
                    ]);
                }
            } else {
                $params['errorMessage'] = 'Token CSRF invalide';
            }
        }

        return $this->twig->load('homePage.twig')->render([
            'cvUrl' => $url, $params
        ]);
    }
}
