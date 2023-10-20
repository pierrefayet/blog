<?php

namespace Pierre\Projet5Blog\Controller;

use Twig\Environment;
use Twig\Error\LoaderError;
use Twig\Error\RuntimeError;
use Twig\Error\SyntaxError;
use Twig\Loader\FilesystemLoader;

class HomeController
{
    /**
     * @throws SyntaxError
     * @throws RuntimeError
     * @throws LoaderError
     */
    public function homepage() : void
    {
        $loader = new FilesystemLoader(__DIR__  .'../../Templates');
        $twig = new Environment($loader);

        $template = $twig->load("homePage.twig");
        echo $template->render(['posts' => 'tata']);
    }
}