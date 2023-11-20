<?php

namespace App\Controller;

use App\Model\Post;
use Twig\Environment;
use Twig\Error\LoaderError;
use Twig\Error\RuntimeError;
use Twig\Error\SyntaxError;
use Twig\Loader\FilesystemLoader;

class ShowPostController
{
    private Post $postModel; // On injecte le modèle
    private Environment $twig;

    public function __construct(Post $postModel, Environment $twig)
    {
        $this->postModel = $postModel; // on injecte le modèle dans le constructeur;
        $this->twig = $twig;
    }

    /**
     * @throws SyntaxError
     * @throws RuntimeError
     * @throws LoaderError
     */
    public function show(): void
    {
        $postId = $_GET['postId'];
         var_dump($this->twig->load('post/show.html.twig')->render(['postId' => $this->postModel->getSinglePost($postId)]));
    }
}
