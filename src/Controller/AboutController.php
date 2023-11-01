<?php

namespace App\Controller;

use Twig\Environment;
use Twig\Error\LoaderError;
use Twig\Error\RuntimeError;
use Twig\Error\SyntaxError;
use Twig\Loader\FilesystemLoader;

class AboutController
{
    public function __construct() {

    }
    /**
     * @throws RuntimeError
     * @throws SyntaxError
     * @throws LoaderError
     */
    public function about(): string
    {
        $loader = new FilesystemLoader('templates/');
        $twig = new Environment($loader);

        $template = $twig->load('about.twig');
        return $template->render();
    }
}