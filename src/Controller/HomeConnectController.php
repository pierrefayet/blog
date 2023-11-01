<?php

namespace App\Controller;

use Twig\Environment;
use Twig\Error\LoaderError;
use Twig\Error\RuntimeError;
use Twig\Error\SyntaxError;
use Twig\Loader\FilesystemLoader;

class HomeConnectController
{
    /**
     * @throws RuntimeError
     * @throws SyntaxError
     * @throws LoaderError
     */
    public function connect(): string
    {
        $loader = new FilesystemLoader('templates/');
        $twig = new Environment($loader);

        $template = $twig->load('homepageConnect.twig');
        return $template->render();
    }
}