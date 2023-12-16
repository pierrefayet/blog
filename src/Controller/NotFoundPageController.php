<?php

namespace App\Controller;

use Twig\Environment;
use Twig\Error\LoaderError;
use Twig\Error\RuntimeError;
use Twig\Error\SyntaxError;

class NotFoundPageController
{
    private Environment $twig;

    public function __construct(Environment $twig)
    {
        $this->twig = $twig;
    }

    /**
     * @throws SyntaxError<<
     * @throws RuntimeError
     * @throws LoaderError
     */
    public function default(): string
    {
        return $this->twig->load('notFoundPage.html.twig')->render();
    }
}
