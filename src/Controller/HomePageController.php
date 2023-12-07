<?php

namespace App\Controller;

use App\Model\Post;
use App\service\Mailer;
use PHPMailer\PHPMailer\Exception;
use Twig\Environment;
use Twig\Error\LoaderError;
use Twig\Error\RuntimeError;
use Twig\Error\SyntaxError;

class HomePageController
{
    private Mailer $mailer;
    private Post $postModel;
    private Environment $twig;


    public function __construct(Mailer $mailer, Post $postModel, Environment $twig)
    {
        $this->postModel = $postModel;
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
        $url = "/public/asset/cv/Cv_Pierre_Fayet.pdf";
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            if ($_POST['csrf'] !== hash('sha256', 'openclassroom')) {
                $params['successMessage'] = 'Un probléme est survenu, veuillez contacter l\'administrateur.';

                return $this->twig->load('homePage.twig')->render([
                    $params, 'post' => $this->postModel->getNewPosts(),
                    'cvUrl' => $url,
                    'hash' => hash('sha256', 'openclassroom')
                ]);
            }

            $from_name = $_POST['name'];
            $from_email = $_POST['email'];
            $subject = $_POST['subject'];
            $message = $_POST['message'];
            $this->mailer->send($from_name, $from_email, $subject, $message);
            if ($this->mailer->send($from_name, $from_email, $subject, $message)) {
                $params['successMessage'] = 'Un probléme est survenu, veuillez contacter l\'administrateur.';
                return $this->twig->load('homePage.twig')->render([
                    'post' => $this->postModel->getNewPosts(),
                    'cvUrl' => $url,
                    'hash' => hash('sha256', 'openclassroom')]);
            }
        }

        return $this->twig->load('homePage.twig')->render([
            'post' => $this->postModel->getNewPosts(),
            'cvUrl' => $url,
            'hash' => hash('sha256', 'openclassroom')]);
    }
}
