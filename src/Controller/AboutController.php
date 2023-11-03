<?php

namespace App\Controller;

use App\Model\Post;
use Twig\Environment;
use Twig\Error\LoaderError;
use Twig\Error\RuntimeError;
use Twig\Error\SyntaxError;

class AboutController
{
    private Environment $twig;
    private Post $postModel;
    public function __construct(Post $postModel,Environment $twig) {
        $this->twig = $twig;
    }


    /**
     * @throws SyntaxError
     * @throws RuntimeError
     * @throws LoaderError
     */
    public function about(): string
    {
        return $this->twig->load('about.twig')->render();
    }
}