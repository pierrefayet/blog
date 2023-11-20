<?php

namespace App\Controller;

use App\Model\Post;
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
        session_start();
        return $this->twig->load('homePage.twig')->render(['posts' => $this->postModel->getNewPosts()]);
    }
}