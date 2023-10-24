<?php

namespace App\Controller;

use Twig\Environment;
use Twig\Error\LoaderError;
use Twig\Error\RuntimeError;
use Twig\Error\SyntaxError;
use Twig\Loader\FilesystemLoader;

class PageController
{
    /**
     * @throws RuntimeError
     * @throws SyntaxError
     * @throws LoaderError
     */
    public function displayPage(string $page): void
    {
        $loader = new FilesystemLoader('templates/');
        $twig = new Environment($loader);

        $template = $twig->load(sprintf('%s.twig', $page));
        echo $template->render();
    }
}


