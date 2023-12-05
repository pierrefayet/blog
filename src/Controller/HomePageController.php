<?php

namespace App\Controller;

use App\Model\Post;
use App\service\Mailer;
use Twig\Environment;
use Twig\Error\LoaderError;
use Twig\Error\RuntimeError;
use Twig\Error\SyntaxError;

class HomePageController
{
    private Post $postModel;
    private Environment $twig;


    public function __construct(Post $postModel, Environment $twig)
    {
        $this->postModel = $postModel;
        $this->twig = $twig;
    }

    /**
     * @throws RuntimeError
     * @throws SyntaxError
     * @throws LoaderError
     */
    public function home(): string
    {
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            if ($_POST['csrf'] !== hash('sha256', 'openclassroom')) {
                $url = "/public/asset/cv/Cv_Pierre_Fayet.pdf";
                $params['successMessage'] = 'Un probléme est survenu, veuillez contacter l\'administrateur.';

                return $this->twig->load('homePage.twig')->render([$params, 'post' => $this->postModel->getNewPosts(), 'cvUrl' => $url, 'hash' => hash('sha256', 'openclassroom')]);
            }

            $mailer = new Mailer();
            $mailer->send([]);

        }

        $url = "/public/asset/cv/Cv_Pierre_Fayet.pdf";
        return $this->twig->load('homePage.twig')->render(['post' => $this->postModel->getNewPosts(), 'cvUrl' => $url, 'hash' => hash('sha256', 'openclassroom')]);
    }
}
