<?php

namespace App\Controller;

use AllowDynamicProperties;
use PDO;
use Twig\Environment;
use Twig\Error\LoaderError;
use Twig\Error\RuntimeError;
use Twig\Error\SyntaxError;

class AboutController
{
    private Environment $twig;
    public function __construct(Environment $twig) {
        $this->twig = $twig;
    }

    /**
     * @throws SyntaxError
     * @throws RuntimeError
     * @throws LoaderError
     */
    public function about(): string
    {
        return $this->twig->load('about.twig')->render([]);
    }
}
