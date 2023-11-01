<?php

namespace App\Controller;

use App\Model\Post;
use Twig\Environment;
use Twig\Error\LoaderError;
use Twig\Error\RuntimeError;
use Twig\Error\SyntaxError;
use Twig\Loader\FilesystemLoader;

class HomePageController
{
    private Post $postModel;

    public function __construct(Post $postModel)
    {
        $this->postModel = $postModel;
    }
    /**
     * @throws RuntimeError
     * @throws SyntaxError
     * @throws LoaderError
     */
    public function home(): string
    {
        $posts = $this->postModel->getNewPosts();
        $loader = new FilesystemLoader('templates/');
        $twig = new Environment($loader);

        $template = $twig->load('homePage.twig');
        return $template->render([
            'posts' => $posts,
        ]);
    }
}