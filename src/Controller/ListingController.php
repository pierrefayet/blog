<?php

namespace App\Controller;

use App\Model\Post;
use Twig\Environment;
use Twig\Error\LoaderError;
use Twig\Error\RuntimeError;
use Twig\Error\SyntaxError;
use Twig\Loader\FilesystemLoader;

class ListingController
{
    private Post $postModel;

    public function __construct(Post $postModel)
    {
        $this->postModel = $postModel; // on injecte le modèle dans le constructeur;
    }

    /**
     * @throws RuntimeError
     * @throws SyntaxError
     * @throws LoaderError
     */
    public function index(): string
    {
        $posts = $this->postModel->getAllPosts();
        $loader = new FilesystemLoader('templates/');
        $twig = new Environment($loader);

        $template = $twig->load('listing.twig');
        return $template->render([
            'posts' => $posts,
        ]);
    }
}