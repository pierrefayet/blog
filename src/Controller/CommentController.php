<?php

namespace App\Controller;

use App\Model\Comment;
use Twig\Environment;
use Twig\Error\LoaderError;
use Twig\Error\RuntimeError;
use Twig\Error\SyntaxError;


class CommentController
{
    private Environment $twig;
    private Comment $comment;


    public function __construct(Comment $comment, Environment $twig)
    {
        $this->comment = $comment;
        $this->twig = $twig;
    }

    /**
     * @throws RuntimeError
     * @throws SyntaxError
     * @throws LoaderError
     */
    public function index(): string
    {
        return $this->twig->load('commentPage/listing.twig')->render();
    }
}