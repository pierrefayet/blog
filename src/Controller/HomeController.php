<?php

namespace App\Controller;

use Twig\Environment;
use Twig\Error\LoaderError;
use Twig\Error\RuntimeError;
use Twig\Error\SyntaxError;
use Twig\Loader\FilesystemLoader;
use PDO;

class HomeController
{
    /**
     * @throws RuntimeError
     * @throws SyntaxError
     * @throws LoaderError
     */
    public function home(): string
    {

        $loader = new FilesystemLoader('templates/');
        $twig = new Environment($loader);

        $template = $twig->load('homePage.twig');
        return $template->render();
    }
}